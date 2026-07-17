'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import type { Route } from 'next';

/**
 * Ô nhập SĐT THẬT ở hero — nhập được, bấm là sang trang đăng ký với SĐT +
 * vai trò "thợ" đã điền sẵn. Thay cho mock tĩnh trước đây (không nhập được).
 */
export function HeroPhoneForm() {
  const router = useRouter();
  const [phone, setPhone] = useState('');

  function submit(e: React.FormEvent) {
    e.preventDefault();
    const p = phone.trim();
    const url = p
      ? `/dang-ky?sdt=${encodeURIComponent(p)}&role=contractor`
      : '/dang-ky?role=contractor';
    router.push(url as Route);
  }

  return (
    <form onSubmit={submit} className="flex w-full max-w-md flex-col gap-3 sm:flex-row">
      <input
        type="tel"
        inputMode="tel"
        value={phone}
        onChange={(e) => setPhone(e.target.value)}
        placeholder="Nhập số điện thoại của bạn"
        aria-label="Số điện thoại"
        className="h-14 flex-1 rounded-xl border border-outline-variant/40 bg-surface-container-lowest px-5 text-base font-medium text-on-surface outline-none transition-all placeholder:text-outline focus:border-primary focus:ring-2 focus:ring-primary/30"
      />
      <button
        type="submit"
        className="h-14 shrink-0 rounded-xl bg-primary px-7 text-base font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
      >
        Tạo hồ sơ miễn phí
      </button>
    </form>
  );
}
