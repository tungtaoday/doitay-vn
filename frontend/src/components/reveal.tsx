'use client';

import { useEffect, useRef, useState, type ReactNode } from 'react';

/**
 * Fade + trượt lên khi cuộn vào tầm nhìn. Chỉ chạy 1 lần.
 * Dùng kiểm tra vị trí theo scroll (bền với mọi kiểu cuộn — kể cả nhảy thẳng
 * xuống bằng phím End / khôi phục vị trí cuộn — không bao giờ kẹt ẩn nội dung).
 * Tôn trọng prefers-reduced-motion (hiện ngay, không animate).
 */
export function Reveal({
  children,
  className = '',
  delay = 0,
}: {
  children: ReactNode;
  className?: string;
  delay?: number;
}) {
  const ref = useRef<HTMLDivElement>(null);
  const [shown, setShown] = useState(false);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      setShown(true);
      return;
    }

    let done = false;
    const check = () => {
      if (done || !ref.current) return;
      const top = ref.current.getBoundingClientRect().top;
      // Vào tầm nhìn (mép trên đã lên trên 92% chiều cao) hoặc đã cuộn qua.
      if (top < window.innerHeight * 0.92) {
        done = true;
        setShown(true);
        window.removeEventListener('scroll', check);
        window.removeEventListener('resize', check);
      }
    };

    check(); // trạng thái ban đầu
    window.addEventListener('scroll', check, { passive: true });
    window.addEventListener('resize', check, { passive: true });
    return () => {
      window.removeEventListener('scroll', check);
      window.removeEventListener('resize', check);
    };
  }, []);

  return (
    <div
      ref={ref}
      style={{ transitionDelay: `${delay}ms` }}
      className={`transition-all duration-700 ease-out ${
        shown ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'
      } ${className}`}
    >
      {children}
    </div>
  );
}
