'use client';

import { useEffect, useRef, useState, type ReactNode } from 'react';

/**
 * Fade + trượt lên khi cuộn vào tầm nhìn. Chỉ chạy 1 lần.
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
    const io = new IntersectionObserver(
      ([entry]) => {
        // Reveal khi vào tầm nhìn — HOẶC khi đã bị cuộn qua (top < 0) để
        // không kẹt ẩn nếu người dùng nhảy thẳng xuống (phím End / khôi phục cuộn).
        if (entry.isIntersecting || entry.boundingClientRect.top < 0) {
          setShown(true);
          io.disconnect();
        }
      },
      { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );
    io.observe(el);
    return () => io.disconnect();
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
