'use client';

import { useEffect, useRef, useState } from 'react';

function compact(n: number): string {
  if (n >= 1000) {
    const k = n / 1000;
    return (k % 1 === 0 ? k.toFixed(0) : k.toFixed(1)) + 'k';
  }
  return String(n);
}

/**
 * Đếm số từ 0 → value khi cuộn vào tầm nhìn. Định dạng gọn (1000 → 1k).
 * value = 0 hiển thị "—". Tôn trọng prefers-reduced-motion.
 */
export function CountUp({
  value,
  suffix = '',
  duration = 1400,
  className = '',
}: {
  value: number;
  suffix?: string;
  duration?: number;
  className?: string;
}) {
  const ref = useRef<HTMLSpanElement>(null);
  const [n, setN] = useState(0);

  useEffect(() => {
    const el = ref.current;
    if (!el || value <= 0) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      setN(value);
      return;
    }
    const io = new IntersectionObserver(
      ([entry]) => {
        if (!entry.isIntersecting) return;
        io.disconnect();
        let start: number | null = null;
        const tick = (t: number) => {
          if (start === null) start = t;
          const p = Math.min((t - start) / duration, 1);
          const eased = 1 - Math.pow(1 - p, 3);
          setN(Math.round(eased * value));
          if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
      },
      { threshold: 0.4 },
    );
    io.observe(el);
    return () => io.disconnect();
  }, [value, duration]);

  if (value <= 0) {
    return <span className={className}>—</span>;
  }

  return (
    <span ref={ref} className={className}>
      {compact(n)}
      {suffix}
    </span>
  );
}
