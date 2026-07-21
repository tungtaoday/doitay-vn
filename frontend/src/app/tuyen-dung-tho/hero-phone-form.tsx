'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import type { Route } from 'next';

/**
 * Ô nhập SĐT THẬT ở hero — nhập được, bấm là sang trang đăng ký với SĐT +
 * vai trò "thợ" đã điền sẵn. Thay cho mock tĩnh trước đây (không nhập được).
 *
 * `isAuthenticated`: đã đăng nhập thì đi thẳng form tạo hồ sơ thợ
 * (/vi/tho/dang-ky), KHÔNG bắt đăng ký tài khoản user lại.
 */
export function HeroPhoneForm({ isAuthenticated = false }: { isAuthenticated?: boolean }) {
  const router = useRouter();
  const [phone, setPhone] = useState('');

  function submit(e: React.FormEvent) {
    e.preventDefault();
    if (isAuthenticated) {
      router.push('/vi/tho/dang-ky' as Route);
      return;
    }
    const p = phone.trim();
    const url = p
      ? `/dang-ky?sdt=${encodeURIComponent(p)}&role=contractor`
      : '/dang-ky?role=contractor';
    router.push(url as Route);
  }

  // Đã đăng nhập: chỉ cần 1 nút đi thẳng tới form tạo hồ sơ thợ.
  if (isAuthenticated) {
    return (
      <form onSubmit={submit} className="w-full max-w-md">
        <button
          type="submit"
          className="inline-flex h-14 items-center justify-center gap-2 rounded-xl bg-primary px-8 text-base font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
        >
          <span className="material-symbols-outlined text-[1.25rem]">handyman</span>
          Tạo hồ sơ thợ ngay
        </button>
      </form>
    );
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
