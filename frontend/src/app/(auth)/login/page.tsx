import type { Metadata } from 'next';
import { redirect } from 'next/navigation';
import { login } from './actions';

export const metadata: Metadata = {
  title: 'Đăng nhập',
};

export default function LoginPage({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  async function action(formData: FormData) {
    'use server';
    const email = String(formData.get('email') ?? '');
    const password = String(formData.get('password') ?? '');
    const result = await login(email, password);
    if (result.ok) {
      redirect('/');
    } else {
      redirect(`/login?error=${encodeURIComponent(result.error)}`);
    }
  }

  return (
    <div className="mx-auto max-w-sm space-y-6">
      <h1 className="text-2xl font-bold">Đăng nhập</h1>

      <ErrorBanner searchParams={searchParams} />

      <form action={action} className="space-y-4">
        <label className="block">
          <span className="text-sm font-medium">Email</span>
          <input
            name="email"
            type="email"
            required
            className="mt-1 w-full rounded-lg border px-3 py-2"
          />
        </label>
        <label className="block">
          <span className="text-sm font-medium">Mật khẩu</span>
          <input
            name="password"
            type="password"
            required
            minLength={6}
            className="mt-1 w-full rounded-lg border px-3 py-2"
          />
        </label>
        <button className="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-white hover:bg-blue-700">
          Đăng nhập
        </button>
      </form>
    </div>
  );
}

async function ErrorBanner({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  const sp = await searchParams;
  if (!sp.error) return null;
  return (
    <div className="rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
      {sp.error}
    </div>
  );
}
