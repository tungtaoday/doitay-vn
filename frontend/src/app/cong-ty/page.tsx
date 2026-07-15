import { permanentRedirect } from 'next/navigation';
import type { Route } from 'next';

/**
 * /cong-ty (danh sách) trước đây trùng nội dung 100% với /tho — duplicate
 * content (SEO) + rối điều hướng. Hợp nhất: 301 về /tho, giữ nguyên query.
 *
 * LƯU Ý: route chi tiết /cong-ty/[id]/[[...rest]] GIỮ NGUYÊN (URL parity với
 * legacy /companies/{id}/{slug} — xem CLAUDE.md, đừng đụng).
 */
interface PageProps {
  searchParams: Promise<Record<string, string | string[] | undefined>>;
}

export default async function CongTyListRedirect({ searchParams }: PageProps) {
  const params = await searchParams;
  const qs = new URLSearchParams();
  for (const [k, v] of Object.entries(params)) {
    if (typeof v === 'string' && v) qs.set(k, v);
  }
  const suffix = qs.toString();
  permanentRedirect(`/tho${suffix ? `?${suffix}` : ''}` as Route);
}
