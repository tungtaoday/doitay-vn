'use client';

import { useEffect } from 'react';
import { recordEvent } from '@/lib/track';

/**
 * Đo lượt TÌM/LỌC thợ ở trang danh sách. Không render gì.
 *
 * Chỉ bắn khi khách thật sự lọc (có từ khoá / nghề / quận / xếp hạng) — nếu bắn
 * cả lượt vào trang trơn thì con số chỉ còn là pageview, không nói được ý định.
 * Truyền `khoa` để mỗi tổ hợp lọc mới bắn một lần, đổi trang không bắn lại.
 */
export function SearchAnalytics({
  khoa,
  soKetQua,
}: {
  khoa: string;
  soKetQua: number;
}) {
  useEffect(() => {
    if (!khoa) return;
    recordEvent('search_performed', {
      surface: 'khach',
      channel: 'web',
      meta: { loc: khoa, ket_qua: soKetQua },
    });
  }, [khoa, soKetQua]);

  return null;
}
