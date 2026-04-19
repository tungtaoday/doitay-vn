import { redirect } from 'next/navigation';
import { getToken } from '@/lib/auth';

export default async function ViLayout({ children }: { children: React.ReactNode }) {
  const token = await getToken();
  if (!token) redirect('/login?error=unauthenticated');

  return (
    <div className="mx-auto max-w-6xl px-6 py-10 md:px-8">
      {children}
    </div>
  );
}
