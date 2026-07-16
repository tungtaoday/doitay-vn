'use client';

import { useActionState } from 'react';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { resetPasswordAction, type ResetResult } from './actions';

export function ResetForm({ identifier }: { identifier: string }) {
  const router = useRouter();
  const [state, formAction, isPending] = useActionState<ResetResult | null, FormData>(
    resetPasswordAction,
    null,
  );

  useEffect(() => {
    if (state?.ok) {
      router.replace('/login');
    }
  }, [state, router]);

  return (
    <>
      {state && !state.ok && (
        <div className="rounded-2xl bg-error-container px-4 py-3 text-sm text-on-error-container">
          {state.error}
        </div>
      )}

      {state?.ok && (
        <div className="rounded-2xl bg-green-100 px-4 py-3 text-sm text-green-800">
          Đặt lại mật khẩu thành công! Đang chuyển tới trang đăng nhập...
        </div>
      )}

      <form action={formAction} className="space-y-5">
        <input type="hidden" name="identifier" defaultValue={identifier} />

        <label className="block">
          <span className="text-sm font-medium text-on-surface">
            Email/SĐT đã đăng ký
          </span>
          <input
            type="text"
            defaultValue={identifier}
            disabled
            className="mt-2 w-full rounded-2xl bg-surface-container-low px-5 py-3.5 text-sm text-on-surface-variant"
          />
        </label>

        <label className="block">
          <span className="text-sm font-medium text-on-surface">Mã xác nhận</span>
          <input
            name="code"
            type="text"
            inputMode="numeric"
            pattern="\d{6}"
            maxLength={6}
            required
            placeholder="6 chữ số"
            className="mt-2 w-full rounded-2xl bg-surface-container-low px-5 py-3.5 text-center font-mono text-lg tracking-[0.5em] text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </label>

        <label className="block">
          <span className="text-sm font-medium text-on-surface">Mật khẩu mới</span>
          <input
            name="password"
            type="password"
            required
            minLength={8}
            autoComplete="new-password"
            placeholder="Tối thiểu 8 ký tự"
            className="mt-2 w-full rounded-2xl bg-surface-container-low px-5 py-3.5 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </label>

        <label className="block">
          <span className="text-sm font-medium text-on-surface">Nhập lại mật khẩu</span>
          <input
            name="password_confirmation"
            type="password"
            required
            minLength={8}
            autoComplete="new-password"
            className="mt-2 w-full rounded-2xl bg-surface-container-low px-5 py-3.5 text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </label>

        <button
          type="submit"
          disabled={isPending}
          className="w-full rounded-2xl bg-primary px-6 py-3.5 text-sm font-semibold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-[0.98] disabled:opacity-60"
        >
          {isPending ? 'Đang xử lý...' : 'Đặt lại mật khẩu'}
        </button>
      </form>
    </>
  );
}
