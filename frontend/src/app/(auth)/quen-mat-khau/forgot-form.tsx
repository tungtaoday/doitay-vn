'use client';

import { useActionState } from 'react';
import { sendResetCodeAction, type ForgotResult } from './actions';

export function ForgotForm() {
  const [state, formAction, isPending] = useActionState<ForgotResult | null, FormData>(
    sendResetCodeAction,
    null,
  );

  return (
    <>
      {state && !state.ok && (
        <div className="rounded-2xl bg-error-container px-4 py-3 text-sm text-on-error-container">
          {state.error}
        </div>
      )}

      <form action={formAction} className="space-y-5">
        <label className="block">
          <span className="text-sm font-medium text-on-surface">
            Email hoặc số điện thoại
          </span>
          <input
            name="identifier"
            type="text"
            required
            autoComplete="username"
            placeholder="email@example.com hoặc 0901234567"
            className="mt-2 w-full rounded-2xl bg-surface-container-low px-5 py-3.5 text-sm text-on-surface placeholder:text-on-surface-variant/60 focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </label>

        <button
          type="submit"
          disabled={isPending}
          className="w-full rounded-2xl bg-gradient-to-r from-primary to-primary-container px-6 py-3.5 text-sm font-semibold text-on-primary shadow-ambient transition-all active:scale-[0.98] disabled:opacity-60"
        >
          {isPending ? 'Đang gửi...' : 'Gửi mã xác nhận'}
        </button>
      </form>
    </>
  );
}
