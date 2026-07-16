'use client';

import { useActionState, useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { saleLoginAction, type SaleLoginResult } from './actions';

export function SaleLoginForm() {
  const router = useRouter();
  const [state, formAction, isPending] = useActionState<SaleLoginResult | null, FormData>(
    saleLoginAction,
    null,
  );
  const [showPw, setShowPw] = useState(false);

  useEffect(() => {
    // Sale đăng nhập xong vào THẲNG /sale — không đi vào luồng hồ sơ thợ.
    if (state?.ok) router.replace('/sale');
  }, [state, router]);

  return (
    <>
      {state && !state.ok && (
        <div className="mb-6 rounded-xl bg-error-container px-5 py-4 text-sm font-medium text-on-error-container">
          {state.error}
        </div>
      )}

      <form action={formAction} className="space-y-5">
        <div className="space-y-2">
          <label htmlFor="sale-id" className="block text-sm font-bold text-on-surface">
            Email hoặc số điện thoại
          </label>
          <input
            id="sale-id"
            name="identifier"
            type="text"
            required
            autoComplete="username"
            placeholder="Tài khoản do quản lý cấp"
            className="h-14 w-full rounded-xl border-none bg-surface-container-low px-5 text-base font-medium text-on-surface outline-none transition-all placeholder:text-outline focus:ring-2 focus:ring-primary/30"
          />
        </div>

        <div className="space-y-2">
          <label htmlFor="sale-pw" className="block text-sm font-bold text-on-surface">
            Mật khẩu
          </label>
          <div className="relative">
            <input
              id="sale-pw"
              name="password"
              type={showPw ? 'text' : 'password'}
              required
              minLength={6}
              autoComplete="current-password"
              placeholder="••••••••"
              className="h-14 w-full rounded-xl border-none bg-surface-container-low px-5 pr-14 text-base font-medium text-on-surface outline-none transition-all placeholder:text-outline focus:ring-2 focus:ring-primary/30"
            />
            <button
              type="button"
              onClick={() => setShowPw(!showPw)}
              aria-label={showPw ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'}
              className="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant"
            >
              <span className="material-symbols-outlined">
                {showPw ? 'visibility_off' : 'visibility'}
              </span>
            </button>
          </div>
        </div>

        <button
          type="submit"
          disabled={isPending}
          className="h-14 w-full rounded-xl border-none bg-primary text-base font-bold text-on-primary transition-all hover:bg-primary-hover active:scale-[0.98] disabled:opacity-60"
        >
          {isPending ? 'Đang đăng nhập…' : 'Đăng nhập'}
        </button>
      </form>
    </>
  );
}
