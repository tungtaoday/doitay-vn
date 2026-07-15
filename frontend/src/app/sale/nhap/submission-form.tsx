'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import type { Route } from 'next';
import { createSubmissionAction } from './actions';

const FIELD_CLS =
  'h-12 w-full rounded-xl border-none bg-surface-container-low px-4 text-on-surface outline-none placeholder:text-outline focus:ring-2 focus:ring-primary/30';

export function SubmissionForm() {
  const router = useRouter();
  const [pending, setPending] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [fieldErrors, setFieldErrors] = useState<Record<string, string>>({});
  const [files, setFiles] = useState<File[]>([]);

  function onPickFiles(e: React.ChangeEvent<HTMLInputElement>) {
    const picked = Array.from(e.target.files ?? []);
    setFiles((prev) => [...prev, ...picked].slice(0, 5));
    e.target.value = '';
  }

  function removeFile(idx: number) {
    setFiles((prev) => prev.filter((_, i) => i !== idx));
  }

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    if (pending) return;
    setPending(true);
    setError(null);
    setFieldErrors({});

    const form = e.currentTarget;
    const get = (name: string) => (form.elements.namedItem(name) as HTMLInputElement | null)?.value.trim() ?? '';

    const fd = new FormData();
    fd.append('ten_tho', get('ten_tho'));
    fd.append('nghe', get('nghe'));
    fd.append('khu_vuc', get('khu_vuc'));
    fd.append('sdt_tho', get('sdt_tho'));
    const namKn = get('nam_kn');
    if (namKn) fd.append('nam_kn', namKn);
    files.forEach((f) => fd.append('images[]', f));

    const res = await createSubmissionAction(fd);
    setPending(false);

    if (res.ok) {
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

  return (
    <form onSubmit={onSubmit} className="space-y-5">
      {error ? (
        <div className="rounded-xl bg-error-container px-4 py-3 text-sm font-medium text-on-error-container">
          {error}
        </div>
      ) : null}

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">Tên thợ</label>
        <input name="ten_tho" className={FIELD_CLS} placeholder="VD: Anh Hùng" required />
        {err('ten_tho')}
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">Nghề</label>
        <input name="nghe" className={FIELD_CLS} placeholder="VD: Thợ điện" required />
        {err('nghe')}
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">Khu vực</label>
        <input name="khu_vuc" className={FIELD_CLS} placeholder="VD: Cầu Giấy, Hà Nội" required />
        {err('khu_vuc')}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="mb-1.5 block text-sm font-semibold text-on-surface">Số điện thoại</label>
          <input name="sdt_tho" className={FIELD_CLS} placeholder="09xxxxxxxx" inputMode="tel" required />
          {err('sdt_tho')}
        </div>
        <div>
          <label className="mb-1.5 block text-sm font-semibold text-on-surface">Năm KN (tùy chọn)</label>
          <input name="nam_kn" className={FIELD_CLS} placeholder="VD: 3" inputMode="numeric" />
          {err('nam_kn')}
        </div>
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-semibold text-on-surface">
          Ảnh công việc ({files.length}/5) — tối thiểu 3
        </label>
        <div className="grid grid-cols-3 gap-3">
          {files.map((f, i) => (
            <div key={i} className="relative aspect-square overflow-hidden rounded-xl bg-surface-container-low">
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
        disabled={pending || files.length < 3}
        className="h-12 w-full rounded-xl bg-primary font-semibold text-on-primary shadow-ambient transition-all active:scale-95 disabled:opacity-50"
      >
        {pending ? 'Đang gửi...' : 'Gửi hồ sơ'}
      </button>
    </form>
  );
}
