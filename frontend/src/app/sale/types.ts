export interface SaleSubmissionImage {
  id: number;
  url: string;
}

export interface SaleSubmission {
  id: number;
  ten_tho: string;
  nghe: string;
  khu_vuc: string;
  sdt_tho: string;
  nam_kn: number | null;
  bang_gia: Array<{ ten: string; gia: string }> | null;
  status: 'pending' | 'approved' | 'rejected';
  ly_do_tu_choi: string | null;
  company_id: number | null;
  so_anh: number;
  /** Có khi backend load relation images (list CTV + hàng đợi duyệt). */
  anh?: SaleSubmissionImage[];
  created_at: string | null;
}

export interface SaleSubmissionListResponse {
  data: SaleSubmission[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    counts?: { pending: number; approved: number; rejected: number };
    tong_hoa_hong?: number;
  };
}

export function formatVnd(n: number): string {
  return new Intl.NumberFormat('vi-VN').format(n) + 'đ';
}
