'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import type { Route } from 'next';
import { createSubmissionAction, lookupThoAction, type ThoLookup } from './actions';
import { NGHE, TINH_THANH, QUAN_HA_NOI, ghepKhuVuc } from '../danh-muc';

const FIELD_CLS =
  'h-12 w-full rounded-xl border-none bg-surface-container-low px-4 text-on-surface outline-none placeholder:text-outline focus:ring-2 focus:ring-primary/30';

type Loai = 'lam_ho' | 'da_mo';

export function SubmissionForm() {
  const router = useRouter();
  const [loai, setLoai] = useState<Loai>('lam_ho');
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [fieldErrors, setFieldErrors] = useState<Record<string, string>>({});
  const [files, setFiles] = useState<File[]>([]);
  const [tinh, setTinh] = useState<string>('Hà Nội');
  const [lookup, setLookup] = useState<ThoLookup | null>(null);
  const [checking, setChecking] = useState(false);
  const [claimLink, setClaimLink] = useState<string | null>(null);
  const [copied, setCopied] = useState(false);

  const minAnh = loai === 'da_mo' ? 1 : 3;

  function onPickFiles(e: React.ChangeEvent<HTMLInputElement>) {
    const picked = Array.from(e.target.files ?? []);
    setFiles((prev) => [...prev, ...picked].slice(0, 5));
    e.target.value = '';
  }

  function removeFile(idx: number) {
    setFiles((prev) => prev.filter((_, i) => i !== idx));
  }

  /** Rời ô SĐT → tra xem thợ đã tự mở hồ sơ chưa, gợi ý đúng kiểu nộp. */
  async function onPhoneBlur(e: React.FocusEvent<HTMLInputElement>) {
    const sdt = e.target.value.trim();
    if (sdt.length < 8) return;
    setChecking(true);
    const res = await lookupThoAction(sdt);
    setChecking(false);
    setLookup(res);
    if (res?.da_mo && !res.da_co_ctv) setLoai('da_mo');
  }

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    if (pending) return;
    setPending(true);
    setError(null);
    setFieldErrors({});

    const form = e.currentTarget;
    const get = (name: string) =>
      (form.elements.namedItem(name) as HTMLInputElement | null)?.value.trim() ?? '';

    const fd = new FormData();
    fd.append('loai', loai);
    fd.append('sdt_tho', get('sdt_tho'));
    if (loai === 'lam_ho') {
      fd.append('ten_tho', get('ten_tho'));
      // Nghề và khu vực lấy từ DANH SÁCH CHUẨN (khớp app thợ) — không gõ tay nữa,
      // nếu không hai bên ghi khác nhau và không khớp được hồ sơ.
      fd.append('nghe', get('nghe'));
      fd.append('khu_vuc', ghepKhuVuc(get('quan'), get('tinh')));
      const namKn = get('nam_kn');
      if (namKn) fd.append('nam_kn', namKn);
    }
    files.forEach((f) => fd.append('images[]', f));

    const res = await createSubmissionAction(fd);
    setPending(false);

    if (res.ok) {
      if (res.claimLink) {
        setClaimLink(res.claimLink);   // giữ lại màn hình để CTV copy link gửi thợ
        return;
      }
      router.push('/sale' as Route);
      router.refresh();
      return;
    }
    if (res.needsLogin) {
      router.push('/login' as Route);
      return;
    }
    setError(res.error);
    if (res.fieldErrors) setFieldErrors(res.fieldErrors);
  }

  const err = (name: string) =>
    fieldErrors[name] ? <p className="mt-1 text-xs text-error">{fieldErrors[name]}</p> : null;

  // ── Nộp xong kiểu "làm hộ": đưa link để CTV gửi thợ ngay ──
  if (claimLink) {
    return (
      <div className="space-y-5">
        <div className="rounded-2xl bg-primary-container/20 p-5">
          <h2 className="mb-1 font-headline text-lg font-bold text-on-surface">Đã lưu hồ sơ</h2>
          <p className="text-sm text-on-surface-variant">
            Gửi link dưới đây cho thợ qua Zalo. Thợ bấm vào là mở app, thấy hồ sơ đã làm sẵn và
            gửi thẻ cho khách được ngay — thợ không phải nhập lại gì.
          </p>
        </div>

        <div className="rounded-xl bg-surface-container-low p-4">
          <p className="break-all text-sm text-on-surface">{claimLink}</p>
        </div>

        <button
          type="button"
          onClick={async () => {
            try {
              await navigator.clipboard.writeText(claimLink);
              setCopied(true);
              setTimeout(() => setCopied(false), 2000);
            } catch {
              setCopied(false);
            }
          }}
          className="h-12 w-full rounded-xl bg-primary font-semibold text-on-primary shadow-ambient active:scale-95"
        >
          {copied ? 'Đã chép link' : 'Chép link gửi thợ'}
        </button>

        <button
          type="button"
          onClick={() => {
            router.push('/sale' as Route);
            router.refresh();
          }}
          className="h-12 w-full rounded-xl bg-surface-container font-semibold text-on-surface active:scale-95"
        >
          Xong, về danh sách
        </button>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className="space-y-5">
      {error ? (
        <div className="rounded-xl bg-error-container px-4 py-3 text-sm font-medium text-on-error-container">
          {error}
        </div>
      ) : null}

      {/* Hai kiểu nộp công */}
      <div className="grid grid-cols-2 gap-2 rounded-2xl bg-surface-container-low p-1.5">
        {([
          ['lam_ho', 'Làm hộ tại chỗ', 'Thợ chưa có hồ sơ'],
          ['da_mo', 'Thợ đã tự mở', 'Chỉ khai nhận công'],
        ] as [Loai, string, string][]).map(([v, label, hint]) => (
          <button
            key={v}
            type="button"
            onClick={() => setLoai(v)}
            className={`rounded-xl px-3 py-2.5 text-left transition-colors ${
              loai === v ? 'bg-primary text-on-primary' : 'text-on-surface-variant'
            }`}
          >
            <span className="block text-sm font-bold">{label}</span>
            <span className={`block text-xs ${loai === v ? 'text-on-primary/80' : 'text-outline'}`}>
              {hint}
            </span>
          </button>
        ))}
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">
          Số điện thoại thợ
        </label>
        <input
          name="sdt_tho"
          className={FIELD_CLS}
          placeholder="09xxxxxxxx"
          inputMode="tel"
          onBlur={onPhoneBlur}
          required
        />
        {checking ? <p className="mt-1 text-xs text-outline">Đang kiểm tra số này…</p> : null}
        {lookup?.da_co_ctv ? (
          <p className="mt-1 text-xs text-error">Số này đã có người nộp — không tính công lần nữa.</p>
        ) : lookup?.da_mo ? (
          <p className="mt-1 text-xs text-primary">
            Thợ đã tự mở hồ sơ: <b>{lookup.ten_tho}</b>
            {lookup.nghe ? ` · ${lookup.nghe}` : ''}
            {lookup.khu_vuc ? ` · ${lookup.khu_vuc}` : ''} — chọn “Thợ đã tự mở”, khỏi gõ lại.
          </p>
        ) : lookup && !lookup.da_mo ? (
          <p className="mt-1 text-xs text-outline">
            Chưa có hồ sơ nào của số này — dùng “Làm hộ tại chỗ”.
          </p>
        ) : null}
        {err('sdt_tho')}
      </div>

      {loai === 'lam_ho' ? (
        <>
          <div>
            <label className="mb-1.5 block text-sm font-semibold text-on-surface">Tên thợ</label>
            <input name="ten_tho" className={FIELD_CLS} placeholder="VD: Anh Hùng" required />
            {err('ten_tho')}
          </div>

          <div>
            <label className="mb-1.5 block text-sm font-semibold text-on-surface">Nghề</label>
            <select name="nghe" className={FIELD_CLS} defaultValue="" required>
              <option value="" disabled>— Chọn nghề —</option>
              {NGHE.map((n) => (
                <option key={n} value={n}>{n}</option>
              ))}
            </select>
            <p className="mt-1 text-xs text-outline">
              Chọn đúng như trong app thợ để hồ sơ khớp được với nhau.
            </p>
            {err('nghe')}
          </div>

          <div className="grid grid-cols-2 gap-4">
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-on-surface">Tỉnh/Thành</label>
              <select
                name="tinh"
                className={FIELD_CLS}
                value={tinh}
                onChange={(e) => setTinh(e.target.value)}
                required
              >
                {TINH_THANH.map((t) => (
                  <option key={t} value={t}>{t}</option>
                ))}
              </select>
            </div>
            <div>
              <label className="mb-1.5 block text-sm font-semibold text-on-surface">Quận/Huyện</label>
              {tinh === 'Hà Nội' ? (
                <select name="quan" className={FIELD_CLS} defaultValue="" required>
                  <option value="" disabled>— Chọn quận —</option>
                  {QUAN_HA_NOI.map((q) => (
                    <option key={q} value={q}>{q}</option>
                  ))}
                </select>
              ) : (
                <input name="quan" className={FIELD_CLS} placeholder="VD: Quận 1" required />
              )}
              {err('khu_vuc')}
            </div>
          </div>

          <div>
            <label className="mb-1.5 block text-sm font-semibold text-on-surface">
              Năm kinh nghiệm (tùy chọn)
            </label>
            <input name="nam_kn" className={FIELD_CLS} placeholder="VD: 3" inputMode="numeric" />
            {err('nam_kn')}
          </div>
        </>
      ) : (
        <div className="rounded-xl bg-surface-container-low p-4 text-sm text-on-surface-variant">
          Tên, nghề và khu vực lấy thẳng từ hồ sơ thợ đã tạo — bạn không phải gõ lại.
        </div>
      )}

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">
          {loai === 'da_mo'
            ? `Ảnh bằng chứng đã gặp thợ (${files.length}/5) — tối thiểu 1`
            : `Ảnh công việc (${files.length}/5) — tối thiểu 3`}
        </label>
        <p className="mb-2 text-xs text-outline">
          {loai === 'da_mo'
            ? 'Ảnh chụp chung với thợ (xin phép trước) hoặc ảnh chụp đoạn chat — để đối chiếu khi nghiệm thu.'
            : 'Ảnh phải là công việc THẬT của thợ (ảnh mạng sẽ bị từ chối, không tính công).'}
        </p>
        <div className="grid grid-cols-3 gap-3">
          {files.map((f, i) => (
            <div
              key={i}
              className="relative aspect-square overflow-hidden rounded-xl bg-surface-container-low"
            >
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={URL.createObjectURL(f)} alt={`Ảnh ${i + 1}`} className="h-full w-full object-cover" />
              <button
                type="button"
                onClick={() => removeFile(i)}
                className="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-error text-on-error"
                aria-label="Xoá ảnh"
              >
                ✕
              </button>
            </div>
          ))}
          {files.length < 5 ? (
            <label className="flex aspect-square cursor-pointer flex-col items-center justify-center rounded-xl bg-surface-container-low text-on-surface-variant hover:bg-surface-container">
              <span className="text-2xl">+</span>
              <span className="text-xs">Thêm ảnh</span>
              <input type="file" accept="image/*" multiple className="hidden" onChange={onPickFiles} />
            </label>
          ) : null}
        </div>
        {err('images')}
      </div>

      <button
        type="submit"
        disabled={pending || files.length < minAnh}
        className="h-12 w-full rounded-xl bg-primary font-semibold text-on-primary shadow-ambient transition-all active:scale-95 disabled:opacity-50"
      >
        {pending ? 'Đang gửi...' : loai === 'da_mo' ? 'Khai nhận công' : 'Gửi hồ sơ'}
      </button>
    </form>
  );
}
