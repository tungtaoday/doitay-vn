/** Bảng hiệu suất thợ — khớp API GET /public/metrics/tho-performance */

export type TinhTrang =
  | 'chua_nhan_ho_so'
  | 'chua_share'
  | 'share_chua_ai_xem'
  | 'xem_nhung_khong_goi'
  | 'dang_song';

export interface ThoRow {
  id: number;
  name: string;
  nghe: string | null;
  khu_vuc: string | null;
  phone: string | null;
  status: 'live' | 'pending';
  claimed: boolean;
  created_at: string;
  shared: number;
  viewed: number;
  contacted: number;
  contact_rate: number | null;
  last_at: string | null;
  tinh_trang: TinhTrang;
}

export interface HieuSuatResponse {
  data: {
    window_days: number;
    tong_quan: {
      tong_tho: number;
      da_nhan_ho_so: number;
      da_share: number;
      co_khach_lien_he: number;
      share_rate: number;
      real_lead_rate: number;
    };
    tho: ThoRow[];
  };
}

/** Mỗi tình trạng đi kèm việc phải làm — bảng để nhìn là biết xử lý gì, không phải đoán. */
export const TINH_TRANG_INFO: Record<
  TinhTrang,
  { nhan: string; mau: string; viec: string }
> = {
  chua_nhan_ho_so: {
    nhan: 'Chưa nhận hồ sơ',
    mau: 'bg-error-container text-on-error-container',
    viec: 'Gửi lại link cho thợ bấm nhận (nút "Chép link gửi thợ" ở danh sách)',
  },
  chua_share: {
    nhan: 'Chưa gửi thẻ',
    mau: 'bg-amber-100 text-amber-900',
    viec: 'Nhắn thợ: gửi thẻ cho 2-3 khách quen — hồ sơ nằm im thì không ai gọi',
  },
  share_chua_ai_xem: {
    nhan: 'Gửi rồi, chưa ai xem',
    mau: 'bg-surface-container-high text-on-surface',
    viec: 'Hỏi thợ gửi cho mấy người; gợi ý gửi vào nhóm khách quen thay vì 1 người',
  },
  xem_nhung_khong_goi: {
    nhan: 'Có xem, không gọi',
    mau: 'bg-blue-100 text-blue-900',
    viec: 'Hồ sơ yếu: thiếu ảnh việc thật hoặc chưa có bảng giá — bổ sung rồi gửi lại',
  },
  dang_song: {
    nhan: 'Đang sống',
    mau: 'bg-primary-container text-on-primary-container',
    viec: 'Giữ nhịp: hỏi thợ có nhận được việc không, xin 1 câu đánh giá thật',
  },
};
