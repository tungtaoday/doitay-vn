import Link from 'next/link';
import type { Route } from 'next';

/** Màn 403 dùng chung cho mọi trang quản trị. */
export function KhongCoQuyen() {
  return (
    <div className="mx-auto max-w-2xl px-6 py-20 text-center">
      <h1 className="font-headline text-2xl font-bold text-on-surface">Không có quyền</h1>
      <p className="mt-3 text-on-surface-variant">Trang này chỉ dành cho Quản lý.</p>
      <Link href={'/sale' as Route} className="mt-6 inline-block text-primary hover:underline">
        ← Về trang Sale
      </Link>
    </div>
  );
}
