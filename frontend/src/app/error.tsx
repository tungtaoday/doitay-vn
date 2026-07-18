'use client';

import { useEffect } from 'react';
import Link from 'next/link';

/**
 * Error boundary toàn ứng dụng — thay màn hình "Application error" trắng trơn
 * bằng UI thân thiện + nút thử lại. Chặn crash khi API/render lỗi.
 */
export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    // Log để dev soi; không lộ chi tiết cho người dùng.
    console.error(error);
  }, [error]);

  return (
    <div className="flex min-h-[60vh] flex-col items-center justify-center px-6 py-20 text-center">
      <div className="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-error-container">
        <span className="material-symbols-outlined text-4xl text-on-error-container">
          error
        </span>
      </div>
      <h1 className="font-headline text-2xl font-bold text-on-surface md:text-3xl">
        Đã có lỗi xảy ra
      </h1>
      <p className="mt-3 max-w-md text-on-surface-variant">
        Trang gặp sự cố tạm thời. Bạn vui lòng thử lại — nếu vẫn lỗi, hãy quay lại sau ít phút.
      </p>
      <div className="mt-8 flex flex-col gap-3 sm:flex-row">
        <button
          onClick={reset}
          className="rounded-xl bg-primary px-8 py-3 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
        >
          Thử lại
        </button>
        <Link
          href="/"
          className="rounded-xl border border-outline-variant/40 px-8 py-3 font-headline font-bold text-on-surface transition-colors hover:bg-surface-container-low"
        >
          Về trang chủ
        </Link>
      </div>
      {error.digest ? (
        <p className="mt-6 text-xs text-outline">Mã lỗi: {error.digest}</p>
      ) : null}
    </div>
  );
}
