export interface SaleSubmission {
  id: number;
  ten_tho: string;
  nghe: string;
  khu_vuc: string;
  sdt_tho: string;
  nam_kn: number | null;
  status: 'pending' | 'approved' | 'rejected';
  ly_do_tu_choi: string | null;
  so_anh: number;
  created_at: string | null;
}

export interface SaleSubmissionListResponse {
  data: SaleSubmission[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}
