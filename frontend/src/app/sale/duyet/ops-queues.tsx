'use client';

import { useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import { approveCompanyAction, rejectCompanyAction } from './review-actions';

export interface OpsQueues {
  counts: {
    stale_requests: number;
    pending_appointments: number;
    pending_deposits: number;
    pending_companies: number;
  };
  stale_requests: Array<{ id: number; title: string; city: string; district: string | null; contact_name: string; created_at: string }>;
  pending_appointments: Array<{ id: number; recipient_name: string; appointment_date: string; appointment_time: string; created_at: string; company?: { name: string; phone: string | null } }>;
  pending_deposits: Array<{ id: number; amount: string | number; created_at: string }>;
  pending_companies: Array<{ id: number; name: string; phone: string | null; city: string | null; district: string | null; created_at: string; user?: { name: string; mobile: string | null } }>;
}

const CHECKLIST = [
  'Ảnh công việc là ảnh thật, không phải ảnh mạng',
  'SĐT gọi thử được, đúng người',
  'Nghề + khu vực rõ ràng, giá dịch vụ hợp lý',
];

function ago(iso: string): string {
  const h = Math.floor((Date.now() - new Date(iso).getTime()) / 3600000);
  if (h < 24) return `${h}h trước`;
  return `${Math.floor(h / 24)} ngày trước`;
}

/** P0.3 — Hàng đợi vận hành cho Quản lý trực: mọi thứ đang chờ con người. */
export function OpsQueuesPanel({ queues }: { queues: OpsQueues }) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [error, setError] = useState<string | null>(null);
  // Ô nhập lý do mở ngay tại dòng đang từ chối, giữ riêng theo id để bấm nhiều
  // dòng không đè lý do của nhau.
  const [dangTuChoi, setDangTuChoi] = useState<number | null>(null);
  const [lyDoTuChoi, setLyDoTuChoi] = useState<Record<number, string>>({});
  const c = queues.counts;

  function rejectCompany(id: number) {
    const lyDo = lyDoTuChoi[id]?.trim();
    if (!lyDo) {
      setDangTuChoi(id);
      return;
    }
    setError(null);
    startTransition(async () => {
      const res = await rejectCompanyAction(id, lyDo);
      if (res.ok) {
        setDangTuChoi(null);
        router.refresh();
      } else setError(res.error);
    });
  }

  function approveCompany(id: number) {
    setError(null);
    startTransition(async () => {
      const res = await approveCompanyAction(id);
      if (!res.ok) setError(res.error);
      else router.refresh();
    });
  }

  const tiles = [
    { n: c.pending_companies, label: 'Thợ chờ duyệt', tone: c.pending_companies > 0 ? 'text-primary' : 'text-outline' },
    { n: c.pending_deposits, label: 'Lệnh nạp chờ duyệt', tone: c.pending_deposits > 0 ? 'text-amber-600' : 'text-outline' },
    { n: c.pending_appointments, label: 'Lịch hẹn chờ >4h', tone: c.pending_appointments > 0 ? 'text-red-600' : 'text-outline' },
    { n: c.stale_requests, label: 'Yêu cầu mở >24h', tone: c.stale_requests > 0 ? 'text-red-600' : 'text-outline' },
  ];

  return (
    <section className="mb-10">
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Hàng đợi vận hành</h2>
      <div className="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
        {tiles.map((t) => (
          <div key={t.label} className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
            <p className={`font-headline text-2xl font-extrabold ${t.tone}`}>{t.n}</p>
            <p className="text-xs text-on-surface-variant">{t.label}</p>
          </div>
        ))}
      </div>

      {error ? (
        <div className="mb-4 rounded-xl bg-error-container px-4 py-3 text-sm text-on-error-container">{error}</div>
      ) : null}

      {/* Thợ tự đăng ký chờ duyệt — duyệt ngay tại đây (P1.3 hợp nhất) */}
      {queues.pending_companies.length > 0 ? (
        <div className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15">
          <div className="mb-3 flex items-center justify-between">
            <p className="font-headline font-bold text-on-surface">Thợ tự đăng ký chờ duyệt</p>
          </div>
          <div className="mb-4 rounded-xl bg-primary/5 px-4 py-3">
            <p className="mb-1 text-xs font-bold uppercase tracking-wide text-primary">Checklist trước khi duyệt</p>
            <ul className="space-y-0.5 text-xs text-on-surface-variant">
              {CHECKLIST.map((item) => (
                <li key={item} className="flex items-start gap-1.5">
                  <span className="material-symbols-outlined text-sm text-primary">check</span>
                  {item}
                </li>
              ))}
            </ul>
          </div>
          <ul className="divide-y divide-outline-variant/15">
            {queues.pending_companies.map((co) => (
              <li key={co.id} className="flex items-center justify-between gap-3 py-3">
                <div className="min-w-0">
                  <p className="truncate font-semibold text-on-surface">{co.name}</p>
                  <p className="text-xs text-on-surface-variant">
                    {[co.district, co.city].filter(Boolean).join(', ') || '—'} · {co.phone ?? co.user?.mobile ?? 'chưa có SĐT'} · {ago(co.created_at)}
                  </p>
                </div>
                <div className="flex shrink-0 flex-col items-end gap-1.5">
                  <div className="flex gap-2">
                    <button
                      onClick={() => approveCompany(co.id)}
                      disabled={isPending}
                      className="rounded-xl bg-primary px-4 py-2 text-sm font-bold text-on-primary transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-60"
                    >
                      Duyệt + tặng ví
                    </button>
                    <button
                      onClick={() => rejectCompany(co.id)}
                      disabled={isPending}
                      className="rounded-xl bg-surface-container px-3 py-2 text-sm font-semibold text-on-surface transition-all active:scale-95 disabled:opacity-60"
                    >
                      Từ chối
                    </button>
                  </div>
                  {dangTuChoi === co.id ? (
                    <input
                      autoFocus
                      value={lyDoTuChoi[co.id] ?? ''}
                      onChange={(e) => setLyDoTuChoi((m) => ({ ...m, [co.id]: e.target.value }))}
                      onKeyDown={(e) => {
                        if (e.key === 'Enter') rejectCompany(co.id);
                      }}
                      placeholder="Lý do từ chối rồi Enter"
                      className="w-56 rounded-lg bg-surface-container-low px-2 py-1.5 text-xs outline-none ring-2 ring-transparent focus:ring-primary/30"
                    />
                  ) : null}
                </div>
              </li>
            ))}
          </ul>
          <p className="mt-2 text-[11px] text-outline">
            Duyệt = hồ sơ lên chợ + tự tạo ví kèm tín dụng chào mừng. Từ chối bắt buộc ghi lý do —
            thợ nhận thông báo kèm lý do đó để sửa lại.
          </p>
        </div>
      ) : null}

      {/* Cảnh báo đỏ: lịch hẹn kẹt + yêu cầu mở lâu */}
      {(queues.pending_appointments.length > 0 || queues.stale_requests.length > 0) ? (
        <div className="mt-4 grid gap-4 md:grid-cols-2">
          {queues.pending_appointments.length > 0 ? (
            <div className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-red-200">
              <p className="mb-2 font-headline text-sm font-bold text-red-600">Lịch hẹn chưa được thợ xác nhận (&gt;4h)</p>
              <ul className="space-y-2 text-xs text-on-surface-variant">
                {queues.pending_appointments.map((a) => (
                  <li key={a.id}>
                    <span className="font-semibold text-on-surface">{a.company?.name ?? 'Thợ'}</span>
                    {' — khách '}{a.recipient_name} · hẹn {a.appointment_date} {a.appointment_time} · tạo {ago(a.created_at)}
                    {a.company?.phone ? <> · <a className="text-primary" href={`tel:${a.company.phone}`}>gọi nhắc thợ</a></> : null}
                  </li>
                ))}
              </ul>
            </div>
          ) : null}
          {queues.stale_requests.length > 0 ? (
            <div className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-red-200">
              <p className="mb-2 font-headline text-sm font-bold text-red-600">Yêu cầu mở chưa ai nhận (&gt;24h)</p>
              <ul className="space-y-2 text-xs text-on-surface-variant">
                {queues.stale_requests.map((r) => (
                  <li key={r.id}>
                    <span className="font-semibold text-on-surface">{r.title}</span>
                    {' — '}{[r.district, r.city].filter(Boolean).join(', ')} · {r.contact_name} · {ago(r.created_at)}
                  </li>
                ))}
              </ul>
            </div>
          ) : null}
        </div>
      ) : null}
    </section>
  );
}
