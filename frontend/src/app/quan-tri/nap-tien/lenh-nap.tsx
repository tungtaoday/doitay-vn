'use client';

import { useState, useTransition } from 'react';
import { xuLyLenhNap } from './actions';

export interface LenhNap {
  id: number;
  deposit_code: string | null;
  amount: number;
  status: string;
  payment_method: string | null;
  bank_name: string | null;
  bank_account_name: string | null;
  transaction_reference: string | null;
  payment_date: string | null;
  payment_proof: string | null;
  user_notes: string | null;
  admin_notes: string | null;
  rejection_reason: string | null;
  created_at: string | null;
  processed_at: string | null;
  tho: string | null;
  tho_phone: string | null;
  nguoi_gui: string | null;
  nguoi_gui_sdt: string | null;
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

const NHAN: Record<string, { text: string; cls: string }> = {
  pending: { text: 'Chờ duyệt', cls: 'bg-amber-100 text-amber-900' },
  processing: { text: 'Đang xử lý', cls: 'bg-blue-100 text-blue-900' },
  completed: { text: 'Đã cộng ví', cls: 'bg-primary-container text-on-primary-container' },
  rejected: { text: 'Từ chối', cls: 'bg-error-container text-on-error-container' },
  cancelled: { text: 'Đã huỷ', cls: 'bg-surface-container text-on-surface-variant' },
};

/**
 * Một lệnh nạp. Duyệt = cộng tiền thật vào ví thợ nên buộc xác nhận hai bước,
 * và từ chối buộc ghi lý do (thợ sẽ nhận được thông báo kèm lý do đó).
 */
export function LenhNapItem({ d }: { d: LenhNap }) {
  const [dangMo, setDangMo] = useState(false);
  const [xacNhan, setXacNhan] = useState<'approve' | 'reject' | null>(null);
  const [lyDo, setLyDo] = useState('');
  const [ghiChu, setGhiChu] = useState('');
  const [ketQua, setKetQua] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  const nhan = NHAN[d.status] ?? { text: d.status, cls: 'bg-surface-container text-on-surface' };
  const xuLyDuoc = d.status === 'pending' || d.status === 'processing';

  function chay(action: 'approve' | 'reject' | 'processing') {
    setLoi(null);
    start(async () => {
      const r = await xuLyLenhNap(d.id, action, ghiChu, lyDo);
      if (r.ok) {
        setKetQua(r.message);
        setXacNhan(null);
      } else {
        setLoi(r.error);
      }
    });
  }

  return (
    <li className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <div className="min-w-0">
          <p className="font-headline text-lg font-extrabold text-on-surface">{vnd(d.amount)}</p>
          <p className="text-sm text-on-surface-variant">
            {d.tho ?? 'Không rõ thợ'}
            {d.tho_phone ? (
              <>
                {' · '}
                <a className="text-primary" href={`tel:${d.tho_phone}`}>
                  {d.tho_phone}
                </a>
              </>
            ) : null}
          </p>
          <p className="mt-0.5 text-xs text-outline">
            {d.deposit_code ?? `#${d.id}`} · gửi {(d.created_at ?? '').slice(0, 16).replace('T', ' ')}
          </p>
        </div>
        <span className={`shrink-0 rounded-full px-3 py-1 text-xs font-bold ${nhan.cls}`}>
          {nhan.text}
        </span>
      </div>

      <button
        type="button"
        onClick={() => setDangMo((v) => !v)}
        className="mt-2 text-xs font-semibold text-primary"
      >
        {dangMo ? 'Ẩn chi tiết chuyển khoản' : 'Xem chi tiết chuyển khoản'}
      </button>

      {dangMo ? (
        <dl className="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 rounded-xl bg-surface-container-low p-3 text-xs">
          <Doi nhan="Hình thức" gt={d.payment_method} />
          <Doi nhan="Ngân hàng" gt={d.bank_name} />
          <Doi nhan="Chủ tài khoản" gt={d.bank_account_name} />
          <Doi nhan="Mã giao dịch" gt={d.transaction_reference} />
          <Doi nhan="Ngày chuyển" gt={d.payment_date} />
          <Doi nhan="Người gửi" gt={d.nguoi_gui} />
          <Doi nhan="Ghi chú của thợ" gt={d.user_notes} />
          <Doi nhan="Ghi chú quản trị" gt={d.admin_notes} />
          {d.rejection_reason ? <Doi nhan="Lý do từ chối" gt={d.rejection_reason} /> : null}
        </dl>
      ) : null}

      {d.payment_proof && dangMo ? (
        // eslint-disable-next-line @next/next/no-img-element
        <img
          src={d.payment_proof.startsWith('http') ? d.payment_proof : `/${d.payment_proof}`}
          alt="Ảnh chuyển khoản"
          className="mt-2 max-h-72 rounded-xl object-contain"
        />
      ) : null}

      {ketQua ? (
        <p className="mt-3 rounded-xl bg-primary-container/40 px-3 py-2 text-sm text-on-surface">
          {ketQua} — tải lại trang để thấy trạng thái mới.
        </p>
      ) : null}
      {loi ? (
        <p className="mt-3 rounded-xl bg-error-container px-3 py-2 text-sm text-on-error-container">
          {loi}
        </p>
      ) : null}

      {xuLyDuoc && !ketQua ? (
        <div className="mt-3">
          {xacNhan === null ? (
            <div className="flex flex-wrap gap-2">
              <button
                type="button"
                disabled={dangChay}
                onClick={() => setXacNhan('approve')}
                className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary disabled:opacity-50"
              >
                Duyệt &amp; cộng ví
              </button>
              <button
                type="button"
                disabled={dangChay}
                onClick={() => setXacNhan('reject')}
                className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface disabled:opacity-50"
              >
                Từ chối
              </button>
              {d.status === 'pending' ? (
                <button
                  type="button"
                  disabled={dangChay}
                  onClick={() => chay('processing')}
                  className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface-variant disabled:opacity-50"
                >
                  Đang xử lý
                </button>
              ) : null}
            </div>
          ) : (
            <div className="rounded-xl bg-surface-container-low p-3">
              {xacNhan === 'approve' ? (
                <p className="text-sm font-semibold text-on-surface">
                  Cộng {vnd(d.amount)} vào ví của {d.tho ?? 'thợ'}? Thao tác này ghi sổ và không tự
                  hoàn lại được.
                </p>
              ) : (
                <>
                  <p className="text-sm font-semibold text-on-surface">
                    Từ chối lệnh nạp — thợ sẽ nhận thông báo kèm lý do.
                  </p>
                  <input
                    value={lyDo}
                    onChange={(e) => setLyDo(e.target.value)}
                    placeholder="Lý do từ chối (bắt buộc)"
                    className="mt-2 w-full rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
                  />
                </>
              )}
              <input
                value={ghiChu}
                onChange={(e) => setGhiChu(e.target.value)}
                placeholder="Ghi chú nội bộ (tuỳ chọn)"
                className="mt-2 w-full rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
              />
              <div className="mt-2 flex gap-2">
                <button
                  type="button"
                  disabled={dangChay}
                  onClick={() => chay(xacNhan)}
                  className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary disabled:opacity-50"
                >
                  {dangChay ? 'Đang chạy…' : 'Xác nhận'}
                </button>
                <button
                  type="button"
                  disabled={dangChay}
                  onClick={() => setXacNhan(null)}
                  className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface"
                >
                  Huỷ
                </button>
              </div>
            </div>
          )}
        </div>
      ) : null}
    </li>
  );
}

function Doi({ nhan, gt }: { nhan: string; gt: string | null }) {
  if (!gt) return null;
  return (
    <>
      <dt className="text-outline">{nhan}</dt>
      <dd className="font-medium text-on-surface">{gt}</dd>
    </>
  );
}
