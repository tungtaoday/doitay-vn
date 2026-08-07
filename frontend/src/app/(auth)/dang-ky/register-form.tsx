'use client';

import { useActionState } from 'react';
import { recordEvent } from '@/lib/track';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { registerAction, type RegisterResult } from './actions';

export function RegisterForm({
  initialIdentifier = '',
  initialRole = 'customer',
}: {
  initialIdentifier?: string;
  initialRole?: 'customer' | 'contractor' | 'both';
} = {}) {
  const router = useRouter();
  const [state, formAction, isPending] = useActionState<RegisterResult | null, FormData>(
    registerAction,
    null,
  );

  useEffect(() => {
    if (state?.ok) {
      recordEvent('signup_completed', { surface: 'khach', channel: 'web' });
      if (!state.profileComplete) {
        router.replace('/vi/hoan-thanh-ho-so');
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

      <form action={formAction} className="space-y-8">
        {/* Role selection */}
        <fieldset>
          <legend className="mb-3 text-[1.25rem] font-bold text-on-surface">Bạn là ai?</legend>
          <div className="grid grid-cols-3 gap-3">
            {[
              { value: 'customer', label: 'Khách hàng', icon: 'person' },
              { value: 'contractor', label: 'Thợ / Chuyên gia', icon: 'construction' },
              { value: 'both', label: 'Cả hai', icon: 'group' },
            ].map((role) => (
              <label
                key={role.value}
                className="flex cursor-pointer flex-col items-center gap-2 rounded-lg bg-surface-container-low px-3 py-4 text-center transition-colors has-[:checked]:bg-primary has-[:checked]:text-on-primary"
              >
                <input
                  type="radio"
                  name="user_role"
                  value={role.value}
                  defaultChecked={role.value === initialRole}
                  className="sr-only"
                />
                <span className="material-symbols-outlined text-2xl">{role.icon}</span>
                <span className="text-[0.875rem] font-bold">{role.label}</span>
              </label>
            ))}
          </div>
        </fieldset>

        {/* Name fields */}
        <div className="grid grid-cols-2 gap-4">
          <div className="space-y-3">
            <label htmlFor="reg-last" className="block text-[1.25rem] font-bold text-on-surface">
              Họ
            </label>
            <input
              id="reg-last"
              name="lastname"
              type="text"
              required
              maxLength={50}
              autoComplete="family-name"
              placeholder="Nguyễn"
              className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
            />
          </div>
          <div className="space-y-3">
            <label htmlFor="reg-first" className="block text-[1.25rem] font-bold text-on-surface">
              Tên
            </label>
            <input
              id="reg-first"
              name="firstname"
              type="text"
              required
              maxLength={50}
              autoComplete="given-name"
              placeholder="Văn A"
              className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
            />
          </div>
        </div>

        {/* Identifier */}
        <div className="space-y-3">
          <label htmlFor="reg-id" className="block text-[1.25rem] font-bold text-on-surface">
            Số điện thoại hoặc email
          </label>
          <input
            id="reg-id"
            name="identifier"
            type="text"
            required
            defaultValue={initialIdentifier}
            autoComplete="username"
            placeholder="090 123 4567"
            className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
          />
        </div>

        {/* Password */}
        <div className="space-y-3">
          <label htmlFor="reg-pw" className="block text-[1.25rem] font-bold text-on-surface">
            Mật khẩu
          </label>
          <input
            id="reg-pw"
            name="password"
            type="password"
            required
            minLength={8}
            autoComplete="new-password"
            placeholder="Tối thiểu 8 ký tự"
            className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
          />
        </div>

        {/* Confirm password */}
        <div className="space-y-3">
          <label htmlFor="reg-pw2" className="block text-[1.25rem] font-bold text-on-surface">
            Nhập lại mật khẩu
          </label>
          <input
            id="reg-pw2"
            name="password_confirmation"
            type="password"
            required
            minLength={8}
            autoComplete="new-password"
            placeholder="••••••••"
            className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
          />
        </div>

        {/* Submit */}
        <button
          type="submit"
          disabled={isPending}
          className="flex h-[72px] w-full items-center justify-center gap-3 rounded-lg border-none bg-primary text-[1.375rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
        >
          <span>{isPending ? 'Đang tạo tài khoản...' : 'Đăng ký ngay'}</span>
          {!isPending && (
            <span className="material-symbols-outlined">arrow_forward</span>
          )}
        </button>
      </form>
    </>
  );
}
