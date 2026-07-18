import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { CtvTable, type CtvRow } from './ctv-table';

export const metadata: Metadata = {
  title: 'Trung tâm điều hành',
  robots: { index: false, follow: false },
};

interface MonthRow {
  thang: string;
  ho_so_nhap: number;
  ho_so_duyet: number;
  hoa_hong: number;
  hoa_hong_chua_tra: number;
  tang_vi_ao: number;
  phi_lead_ao: number;
  lich_tao: number;
  lich_confirmed: number;
  lich_completed: number;
}

interface Overview {
  months: MonthRow[];
  tong: {
    hoa_hong_chua_tra_toan_bo: number;
    tho_dang_hoat_dong: number;
    tong_vi_ao_dang_no: number;
  };
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

/**
 * Trung tâm điều hành — dành cho Quản lý (gate như /sale/duyet).
 * Phễu vận hành + CHI PHÍ (khớp FM model) + hiệu suất CTV + đối soát hoa hồng.
 */
export default async function QuanTriPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let overview: Overview | null = null;
  let ctv: CtvRow[] = [];
  let denied = false;
  try {
    overview = await api<Overview>('/admin/dashboard/overview?months=6', { token: token ?? undefined });
    const res = await api<{ data: CtvRow[] }>('/admin/ctv/performance', { token: token ?? undefined });
    ctv = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !overview) {
    return (
      <div className="mx-auto max-w-2xl px-6 py-20 text-center">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Không có quyền</h1>
        <p className="mt-3 text-on-surface-variant">Trang này chỉ dành cho Quản lý.</p>
        <Link href={'/sale' as Route} className="mt-6 inline-block text-primary hover:underline">
          ← Về trang Sale
        </Link>
      </div>
    );
  }

  const now = overview.months[overview.months.length - 1];
  const tiles = [
    { label: 'Hoa hồng CHƯA TRẢ (toàn bộ)', value: vnd(overview.tong.hoa_hong_chua_tra_toan_bo), tone: overview.tong.hoa_hong_chua_tra_toan_bo > 0 ? 'text-red-600' : 'text-primary' },
    { label: 'Hoa hồng phát sinh tháng này', value: vnd(now?.hoa_hong ?? 0), tone: 'text-on-surface' },
    { label: 'Thợ đang hoạt động', value: String(overview.tong.tho_dang_hoat_dong), tone: 'text-primary' },
    { label: 'Ví ảo đang nợ thợ (credit)', value: vnd(overview.tong.tong_vi_ao_dang_no), tone: 'text-amber-600' },
  ];

  return (
    <div className="mx-auto max-w-5xl px-6 py-10">
      <div className="mb-8 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Trung tâm điều hành</h1>
          <p className="text-sm text-on-surface-variant">
            Phễu · chi phí (khớp FM model) · hiệu suất CTV — 6 tháng gần nhất
          </p>
        </div>
        <Link href={'/sale/duyet' as Route} className="text-sm font-semibold text-primary hover:underline">
          Duyệt hồ sơ & hàng đợi →
        </Link>
      </div>

      {/* Tiles tổng */}
      <div className="mb-10 grid grid-cols-2 gap-4 md:grid-cols-4">
        {tiles.map((t) => (
          <div key={t.label} className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15">
            <p className={`font-headline text-xl font-extrabold ${t.tone}`}>{t.value}</p>
            <p className="mt-1 text-xs text-on-surface-variant">{t.label}</p>
          </div>
        ))}
      </div>

      {/* Bảng theo tháng */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Theo tháng</h2>
      <div className="mb-2 overflow-x-auto rounded-2xl bg-surface-container-lowest ring-1 ring-outline-variant/15">
        <table className="w-full text-sm">
          <thead>
            <tr className="text-left text-xs uppercase tracking-wide text-on-surface-variant">
              <th className="px-4 py-3">Tháng</th>
              <th className="px-3 py-3 text-right">HS nhập</th>
              <th className="px-3 py-3 text-right">HS duyệt</th>
              <th className="px-3 py-3 text-right">Lịch tạo</th>
              <th className="px-3 py-3 text-right">Confirmed</th>
              <th className="px-3 py-3 text-right">Hoa hồng (mặt)</th>
              <th className="px-3 py-3 text-right">Tặng ví (ảo)</th>
              <th className="px-4 py-3 text-right">Phí lead tiêu (ảo)</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-outline-variant/10">
            {overview.months.map((m) => (
              <tr key={m.thang}>
                <td className="px-4 py-3 font-semibold text-on-surface">{m.thang}</td>
                <td className="px-3 py-3 text-right">{m.ho_so_nhap}</td>
                <td className="px-3 py-3 text-right font-bold text-primary">{m.ho_so_duyet}</td>
                <td className="px-3 py-3 text-right">{m.lich_tao}</td>
                <td className="px-3 py-3 text-right">{m.lich_confirmed}</td>
                <td className="px-3 py-3 text-right font-semibold">{vnd(m.hoa_hong)}</td>
                <td className="px-3 py-3 text-right text-amber-700">{vnd(m.tang_vi_ao)}</td>
                <td className="px-4 py-3 text-right text-on-surface-variant">{vnd(m.phi_lead_ao)}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <p className="mb-10 text-xs text-outline">
        Hoa hồng = tiền mặt phải trả CTV. Tặng ví & phí lead = tiền ảo (credit) — theo dõi mức trợ
        giá, không phải chi tiền mặt. Đối chiếu với <code>docs/business/fm-model-doitay.xlsx</code>.
      </p>

      {/* CTV */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">
        Hiệu suất CTV & đối soát hoa hồng
      </h2>
      <CtvTable rows={ctv} />
      <p className="mt-2 text-xs text-outline">
        "Đã trả" = đánh dấu thanh toán toàn bộ khoản đang nợ của CTV đó (sau khi bạn chuyển khoản thật).
        Tỉ lệ duyệt đỏ khi &lt;70% (ngưỡng KPI).
      </p>
    </div>
  );
}
