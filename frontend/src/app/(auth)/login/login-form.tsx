'use client';

import { useActionState } from 'react';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import Link from 'next/link';
import type { Route } from 'next';
import { loginAction, type LoginResult } from './actions';

export function LoginForm() {
  const router = useRouter();
  const [state, formAction, isPending] = useActionState<LoginResult | null, FormData>(
    loginAction,
    null,
  );
  const [showPw, setShowPw] = useState(false);

  useEffect(() => {
    if (state?.ok) {
      if (!state.profileComplete) {
        router.replace('/vi/hoan-thanh-ho-so');
      } else if (state.hasCompany) {
        router.replace('/vi/tho/lich-hen');
      } else {
        router.replace('/vi/lich-hen');
      }
    }
  }, [state, router]);

  return (
    <>
      {state && !state.ok && (
        <div className="rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
          {state.error}
        </div>
      )}

      <form action={formAction} className="space-y-6">
        <div className="space-y-3">
          <label htmlFor="login-id" className="block text-[1.375rem] font-bold text-on-surface">
            Số điện thoại
          </label>
          <input
            id="login-id"
            name="identifier"
            type="text"
            required
            autoComplete="username"
            placeholder="09xx xxx xxx"
            className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
          />
        </div>

        <div className="space-y-3">
          <div className="flex items-center justify-between">
            <label htmlFor="login-pw" className="text-[1.375rem] font-bold text-on-surface">
              Mật khẩu
            </label>
            <Link
              href={'/quen-mat-khau' as Route}
              className="text-[1.125rem] font-bold text-primary hover:underline"
            >
              Quên mật khẩu?
            </Link>
          </div>
          <div className="relative">
            <input
              id="login-pw"
              name="password"
              type={showPw ? 'text' : 'password'}
              required
              minLength={6}
              autoComplete="current-password"
              placeholder="••••••••"
              className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 pr-14 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
            />
            <button
              type="button"
              onClick={() => setShowPw(!showPw)}
              className="absolute right-6 top-1/2 -translate-y-1/2 text-secondary"
            >
              <span className="material-symbols-outlined">
                {showPw ? 'visibility_off' : 'visibility'}
              </span>
            </button>
          </div>
        </div>

        <div className="pt-4">
          <button
            type="submit"
            disabled={isPending}
            className="h-[72px] w-full rounded-lg border-none bg-primary text-[1.375rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
          >
            {isPending ? 'Đang đăng nhập...' : 'Đăng nhập'}
          </button>
        </div>
      </form>
    </>
  );
}
