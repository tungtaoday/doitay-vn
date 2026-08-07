/** Mirror của API\V1\Admin\InsightController — sửa backend thì sửa cả file này. */

export type TinhTrangKhach =
  | 'cho_chot_ket_qua'
  | 'yeu_cau_treo'
  | 'khach_moi_chua_dat'
  | 'dang_dung'
  | 'khach_quen'
  | 'da_nguoi';

export interface KhachRow {
  id: number;
  name: string | null;
  mobile: string | null;
  city: string | null;
  dang_ky: string | null;
  so_yeu_cau: number;
  yeu_cau_treo: number;
  so_lich: number;
  so_xong: number;
  lich_qua_ngay: number;
  lan_cuoi: string | null;
  tinh_trang: TinhTrangKhach;
}

export interface KhachHangResponse {
  data: {
    tong_quan: {
      tong_khach: number;
      khach_moi: number;
      co_yeu_cau: number;
      co_lich: number;
      co_xong: number;
      quay_lai: number;
      ty_le_gui_yeu_cau: number;
      ty_le_co_lich: number;
      ty_le_xong_viec: number;
      ty_le_quay_lai: number;
      yeu_cau_trong_ky: number;
      yeu_cau_ghep_duoc: number;
      ty_le_ghep: number;
    };
    khach: KhachRow[];
  };
}

/**
 * Mỗi tình trạng gắn liền một việc cụ thể — bảng này để dùng, không để ngắm.
 */
export const KHACH_INFO: Record<TinhTrangKhach, { nhan: string; mau: string; viec: string }> = {
  cho_chot_ket_qua: {
    nhan: 'Qua ngày hẹn chưa chốt',
    mau: 'bg-error-container text-on-error-container',
    viec: 'Gọi hỏi thợ làm xong chưa rồi cập nhật trạng thái lịch — để treo là mất số liệu và mất đánh giá.',
  },
  yeu_cau_treo: {
    nhan: 'Yêu cầu treo >24h',
    mau: 'bg-amber-100 text-amber-900',
    viec: 'Ghép thợ thủ công rồi gọi báo khách. Quá một ngày là khách đi tìm chỗ khác.',
  },
  khach_moi_chua_dat: {
    nhan: 'Khách mới chưa đặt',
    mau: 'bg-blue-100 text-blue-900',
    viec: 'Gọi chào trong 3 ngày đầu, hỏi đang cần sửa gì, đặt hộ luôn nếu có nhu cầu.',
  },
  dang_dung: {
    nhan: 'Đang dùng',
    mau: 'bg-surface-container-high text-on-surface',
    viec: 'Không phải làm gì. Theo dõi để họ chuyển thành khách quen.',
  },
  khach_quen: {
    nhan: 'Khách quen',
    mau: 'bg-primary-container text-on-primary-container',
    viec: 'Xin đánh giá và giới thiệu — đây là nguồn khách rẻ nhất.',
  },
  da_nguoi: {
    nhan: 'Đã nguội',
    mau: 'bg-surface-container text-on-surface-variant',
    viec: 'Không gọi lẻ. Gom lại thành một đợt nhắn tin khi có chương trình.',
  },
};

// ── Điểm chạm ────────────────────────────────────────────────────────────

export interface HanhDongRow {
  event: string;
  surface: string | null;
  channel: string | null;
  luot: number;
  nguoi: number;
  last_at: string | null;
}

export interface KenhRow {
  src: string;
  viewed: number;
  contacted: number;
  shared: number;
  booked: number;
  tong: number;
  ty_le_goi: number | null;
}

export interface SuKienRow {
  event: string;
  surface: string | null;
  channel: string | null;
  created_at: string | null;
  tho: string | null;
  src: string | null;
}

export interface DiemChamResponse {
  data: {
    days: number;
    hanh_dong: HanhDongRow[];
    theo_ngay: Array<Record<string, string | number>>;
    kenh: KenhRow[];
    pheu: Record<string, number>;
    gan_day: SuKienRow[];
    chua_do: Array<{ event: string; mo_ta: string }>;
  };
}

/** Tên tiếng Việt cho từng hành động — bảng thô toàn tiếng Anh thì không ai đọc. */
export const HANH_DONG_NHAN: Record<string, string> = {
  profile_published: 'Thợ tạo xong hồ sơ',
  profile_shared: 'Thợ gửi thẻ hồ sơ đi',
  profile_viewed: 'Khách xem hồ sơ thợ',
  contact_clicked: 'Khách bấm gọi / Zalo',
  booking_started: 'Bắt đầu đặt lịch',
  booking_confirmed: 'Lịch được xác nhận',
  request_started: 'Mở form gửi yêu cầu',
  request_submitted: 'Gửi yêu cầu thành công',
  search_performed: 'Tìm / lọc thợ',
  home_viewed: 'Vào trang chủ',
  signup_completed: 'Đăng ký tài khoản xong',
};

export const KENH_NHAN: Record<string, string> = {
  thotot_app: 'Mini App ThợTốt',
  thotot_card: 'Thẻ thợ chia sẻ',
  seo: 'Google / tìm kiếm',
  facebook: 'Facebook',
  zalo: 'Zalo',
  tiktok: 'TikTok',
  ctv: 'CTV',
  truc_tiep: 'Gõ thẳng địa chỉ',
  khac: 'Khác',
};

// ── Việc hôm nay ─────────────────────────────────────────────────────────

export interface YeuCauRow {
  id: number;
  title: string | null;
  city: string | null;
  district: string | null;
  contact_name: string | null;
  contact_phone: string | null;
  created_at: string | null;
}

export interface LichRow {
  id: number;
  status: string | null;
  appointment_date: string | null;
  appointment_time: string | null;
  created_at: string | null;
  recipient_name: string | null;
  recipient_phone: string | null;
  recipient_address: string | null;
  tho: string | null;
  tho_phone: string | null;
}

export interface KhachMoiRow {
  id: number;
  name: string | null;
  mobile: string | null;
  city: string | null;
  created_at: string | null;
}

export interface HomNayResponse {
  data: {
    yeu_cau_treo: YeuCauRow[];
    lich_hom_nay: LichRow[];
    cho_tho_nhan: LichRow[];
    cho_chot: LichRow[];
    khach_moi: KhachMoiRow[];
    dem: {
      ho_so_cho_duyet: number;
      tho_cho_duyet: number;
      hoa_hong_chua_tra: number;
    };
  };
}

export const gio = (s: string | null): string =>
  s ? s.slice(0, 16).replace('T', ' ') : '—';
