import { readFile } from 'node:fs/promises';
import path from 'node:path';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

/**
 * Trả nội dung SỔ TAY VẬN HÀNH (bản dựng sẵn) cho iframe của /quan-tri/so-tay.
 *
 * Vì sao không để trong `public/`: đây là toàn bộ chiến lược ra thị trường, giá,
 * mô hình tài chính CTV — bỏ vào public/ là ai có đường dẫn cũng đọc được. Ở đây
 * đọc từ `frontend/content/` (ngoài public) và kiểm quyền Quản lý trước khi trả.
 *
 * Sai quyền thì trả 404 chứ không phải 403: người lạ không cần biết là có file.
 */
const KHONG_THAY = new Response('Not found', { status: 404 });

export async function GET(): Promise<Response> {
  const token = await getToken();
  if (!token) return KHONG_THAY;

  // Mượn đúng gate của trung tâm quản trị: endpoint này 403 với người không phải
  // Quản lý, nên không cần dựng thêm cơ chế phân quyền riêng.
  try {
    await api('/admin/ops/cai-dat', { token });
  } catch (e) {
    if (e instanceof ApiError) return KHONG_THAY;
    throw e;
  }

  let html: string;
  try {
    html = await readFile(path.join(process.cwd(), 'content', 'so-tay.html'), 'utf8');
  } catch {
    return new Response(
      '<p style="font-family:sans-serif;padding:2rem">Chưa có bản sổ tay trên máy chủ. '
        + 'Dựng lại rồi chép vào <code>frontend/content/so-tay.html</code>.</p>',
      { status: 200, headers: { 'Content-Type': 'text/html; charset=utf-8' } },
    );
  }

  return new Response(html, {
    status: 200,
    headers: {
      'Content-Type': 'text/html; charset=utf-8',
      // Nội dung nội bộ: không cho cache dùng chung, không cho lập chỉ mục.
      'Cache-Control': 'private, no-store',
      'X-Robots-Tag': 'noindex, nofollow',
    },
  });
}
