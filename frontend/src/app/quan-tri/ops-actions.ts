'use server';

import { revalidatePath } from 'next/cache';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';

export type OpsResult = { ok: true; message: string } | { ok: false; error: string };

/** Gọi API quản trị rồi quy về một kiểu kết quả duy nhất cho mọi nút bấm. */
async function goi(
  path: string,
  method: 'POST' | 'DELETE',
  json: Record<string, unknown> | undefined,
  lamMoi: string[],
): Promise<OpsResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Vui lòng đăng nhập lại.' };
  try {
    const res = await api<{ data: { message: string } }>(path, { method, token, json });
    lamMoi.forEach((p) => revalidatePath(p));
    return { ok: true, message: res.data.message };
  } catch (e) {
    if (e instanceof ApiError) {
      const body = e.body as { message?: string };
      return { ok: false, error: body.message ?? `Lỗi ${e.status}` };
    }
    return { ok: false, error: 'Thao tác không thành công.' };
  }
}

/** Chốt hoàn thành hoặc huỷ một lịch hẹn. */
export async function xuLyLichHen(id: number, action: 'hoan_thanh' | 'huy'): Promise<OpsResult> {
  return goi(`/admin/ops/lich-hen/${id}/action`, 'POST', { action }, [
    '/quan-tri/lich-hen',
    '/quan-tri/hom-nay',
  ]);
}

/** Xoá một đánh giá (dùng cho nội dung bậy / spam). */
export async function xoaDanhGia(id: number): Promise<OpsResult> {
  return goi(`/admin/ops/danh-gia/${id}`, 'DELETE', undefined, ['/quan-tri/danh-gia']);
}

/** Thêm mới (không truyền id) hoặc sửa một nghề. */
export async function luuDanhMuc(input: {
  id?: number;
  name: string;
  description?: string;
  status: 0 | 1;
}): Promise<OpsResult> {
  if (!input.name.trim()) return { ok: false, error: 'Tên nghề không được để trống.' };
  return goi('/admin/ops/danh-muc', 'POST', input, ['/quan-tri/danh-muc']);
}

/** Lưu các trường cài đặt được phép sửa. */
export async function luuCaiDat(input: Record<string, string>): Promise<OpsResult> {
  return goi('/admin/ops/cai-dat', 'POST', input, ['/quan-tri/cai-dat']);
}

/** Thêm một CTV vào danh sách (theo SĐT tài khoản đã có). */
export async function themCtv(input: {
  sdt: string;
  khu_vuc?: string;
  ghi_chu?: string;
}): Promise<OpsResult> {
  if (!input.sdt.trim()) return { ok: false, error: 'Nhập số điện thoại đã.' };
  return goi('/admin/ops/ctv', 'POST', input, ['/quan-tri/ctv']);
}

/** Ngưng (0) hoặc cho chạy lại (1) một CTV. */
export async function doiTrangThaiCtv(id: number, trang_thai: 0 | 1): Promise<OpsResult> {
  return goi(`/admin/ops/ctv/${id}/status`, 'POST', { trang_thai }, ['/quan-tri/ctv']);
}

/** Đánh dấu thợ tiềm năng (lead seeding): đã nhắn Zalo / bỏ qua / ra hồ sơ. */
export async function doiTrangThaiThoTiemNang(
  id: number,
  trang_thai: 'da_nhan' | 'bo_qua' | 'da_tao_ho_so',
): Promise<OpsResult> {
  return goi(`/admin/ops/tho-tiem-nang/${id}/trang-thai`, 'POST', { trang_thai }, [
    '/quan-tri/hom-nay',
  ]);
}
