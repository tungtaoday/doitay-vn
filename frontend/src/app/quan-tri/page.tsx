import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { CtvTable, type CtvRow } from './ctv-table';
import { DieuHuongQuanTri } from './dieu-huong';
import { CanXuLy } from './can-xu-ly';

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
  khach_moi: number;
  yeu_cau_tao: number;
  yeu_cau_thanh_lich: number;
  tho_kich_hoat: number;
  tho_co_viec: number;
}

interface Overview {
  months: MonthRow[];
  tong: {
    hoa_hong_chua_tra_toan_bo: number;
    tho_dang_hoat_dong: number;
    tong_vi_ao_dang_no: number;
    tho_kich_hoat_luy_ke: number;
  };
  cau_hinh: {
    lead_fee: number;
    welcome_credit: number;
    show_contact_public: boolean;
    commission_base: number;
    commission_share: number;
    commission_activation: number;
  };
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

/**
 * Trung tâm điều hành — dành cho Quản lý (gate như /sale/duyet).
 * Phễu CUNG + CẦU · chi phí (khớp FM model) · CTV · cấu hình giai đoạn.
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
  const ch = overview.cau_hinh;
  const tiles = [
    { label: 'Hoa hồng CHƯA TRẢ (toàn bộ)', value: vnd(overview.tong.hoa_hong_chua_tra_toan_bo), tone: overview.tong.hoa_hong_chua_tra_toan_bo > 0 ? 'text-red-600' : 'text-primary' },
    { label: 'Hoa hồng phát sinh tháng này', value: vnd(now?.hoa_hong ?? 0), tone: 'text-on-surface' },
    { label: 'Thợ đang hoạt động', value: String(overview.tong.tho_dang_hoat_dong), tone: 'text-primary' },
    { label: 'Thợ đã kích hoạt (lũy kế)', value: String(overview.tong.tho_kich_hoat_luy_ke), tone: 'text-emerald-700' },
  ];

  const cfgItems = [
    { label: 'Phí lead', value: vnd(ch.lead_fee), env: 'LEAD_FEE' },
    { label: 'Tặng ví khi duyệt', value: vnd(ch.welcome_credit), env: 'WELCOME_CREDIT' },
    { label: 'HH cơ bản', value: vnd(ch.commission_base), env: 'SALE_COMMISSION_BASE' },
    { label: 'HH chia sẻ', value: vnd(ch.commission_share), env: 'SALE_COMMISSION_SHARE_BONUS' },
    { label: 'Thưởng kích hoạt', value: ch.commission_activation > 0 ? vnd(ch.commission_activation) : 'TẮT', env: 'SALE_COMMISSION_ACTIVATION' },
    { label: 'SĐT thợ public', value: ch.show_contact_public ? 'HIỆN' : 'ẨN', env: 'SHOW_CONTACT_PUBLIC' },
  ];

  return (
    <div className="mx-auto max-w-5xl px-6 py-10">
      <div className="mb-8 flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 className="font-headline text-2xl font-bold text-on-surface">Trung tâm điều hành</h1>
          <p className="text-sm text-on-surface-variant">
            Phễu cung &amp; cầu · chi phí (khớp FM model) · CTV — 6 tháng gần nhất
          </p>
        </div>
      </div>

      <DieuHuongQuanTri dang_o="/quan-tri" />

      {/* Việc đang kẹt — đặt trước số liệu vì đây là thứ phải làm hôm nay */}
      <CanXuLy />

      {/* Tiles tổng */}
      <div className="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
        {tiles.map((t) => (
          <div key={t.label} className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15">
            <p className={`font-headline text-xl font-extrabold ${t.tone}`}>{t.value}</p>
            <p className="mt-1 text-xs text-on-surface-variant">{t.label}</p>
          </div>
        ))}
      </div>

      {/* Cấu hình giai đoạn hiện tại */}
      <div className="mb-10 rounded-2xl bg-primary/5 p-5 ring-1 ring-primary/15">
        <div className="mb-3 flex items-center justify-between">
          <p className="font-headline text-sm font-bold uppercase tracking-wide text-primary">
            Cấu hình giai đoạn hiện tại
          </p>
          <p className="text-[11px] text-on-surface-variant">đổi = sửa .env server + config:cache</p>
        </div>
        <div className="grid grid-cols-2 gap-3 md:grid-cols-6">
          {cfgItems.map((c) => (
            <div key={c.env}>
              <p className="font-headline text-sm font-extrabold text-on-surface">{c.value}</p>
              <p className="text-[11px] leading-tight text-on-surface-variant">{c.label}</p>
              <p className="text-[10px] text-outline">{c.env}</p>
            </div>
          ))}
        </div>
      </div>

      {/* Bảng PHỄU cung & cầu */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Phễu cung &amp; cầu theo tháng</h2>
      <div className="mb-10 overflow-x-auto rounded-2xl bg-surface-container-lowest ring-1 ring-outline-variant/15">
        <table className="w-full text-sm">
          <thead>
            <tr className="text-left text-xs uppercase tracking-wide text-on-surface-variant">
              <th className="px-4 py-3">Tháng</th>
              <th className="px-3 py-3 text-right">Khách mới</th>
              <th className="px-3 py-3 text-right">Yêu cầu tạo</th>
              <th className="px-3 py-3 text-right">→ Thành lịch</th>
              <th className="px-3 py-3 text-right">HS nhập</th>
              <th className="px-3 py-3 text-right">HS duyệt</th>
              <th className="px-3 py-3 text-right">Lịch confirmed</th>
              <th className="px-3 py-3 text-right">Thợ kích hoạt</th>
              <th className="px-4 py-3 text-right">Thợ có việc</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-outline-variant/10">
            {overview.months.map((m) => (
              <tr key={m.thang}>
                <td className="px-4 py-3 font-semibold text-on-surface">{m.thang}</td>
                <td className="px-3 py-3 text-right">{m.khach_moi}</td>
                <td className="px-3 py-3 text-right">{m.yeu_cau_tao}</td>
                <td className="px-3 py-3 text-right">
                  {m.yeu_cau_thanh_lich}
                  {m.yeu_cau_tao > 0 ? (
                    <span className="ml-1 text-xs text-on-surface-variant">
                      ({Math.round((m.yeu_cau_thanh_lich / m.yeu_cau_tao) * 100)}%)
                    </span>
                  ) : null}
                </td>
                <td className="px-3 py-3 text-right">{m.ho_so_nhap}</td>
                <td className="px-3 py-3 text-right font-bold text-primary">{m.ho_so_duyet}</td>
                <td className="px-3 py-3 text-right">{m.lich_confirmed}</td>
                <td className="px-3 py-3 text-right font-bold text-emerald-700">{m.tho_kich_hoat}</td>
                <td className="px-4 py-3 text-right">{m.tho_co_viec}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Bảng TIỀN */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">Chi phí theo tháng</h2>
      <div className="mb-2 overflow-x-auto rounded-2xl bg-surface-container-lowest ring-1 ring-outline-variant/15">
        <table className="w-full text-sm">
          <thead>
            <tr className="text-left text-xs uppercase tracking-wide text-on-surface-variant">
              <th className="px-4 py-3">Tháng</th>
              <th className="px-3 py-3 text-right">Hoa hồng (tiền mặt)</th>
              <th className="px-3 py-3 text-right">Trong đó chưa trả</th>
              <th className="px-3 py-3 text-right">Tặng ví (ảo)</th>
              <th className="px-4 py-3 text-right">Phí lead tiêu (ảo)</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-outline-variant/10">
            {overview.months.map((m) => (
              <tr key={m.thang}>
                <td className="px-4 py-3 font-semibold text-on-surface">{m.thang}</td>
                <td className="px-3 py-3 text-right font-semibold">{vnd(m.hoa_hong)}</td>
                <td className={`px-3 py-3 text-right ${m.hoa_hong_chua_tra > 0 ? 'font-bold text-red-600' : ''}`}>{vnd(m.hoa_hong_chua_tra)}</td>
                <td className="px-3 py-3 text-right text-amber-700">{vnd(m.tang_vi_ao)}</td>
                <td className="px-4 py-3 text-right text-on-surface-variant">{vnd(m.phi_lead_ao)}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      <p className="mb-10 text-xs text-outline">
        Hoa hồng = tiền mặt phải trả CTV (gồm cơ bản + chia sẻ + kích hoạt). Tặng ví &amp; phí lead =
        tiền ảo (credit). Đối chiếu <code>docs/business/fm-model-doitay.xlsx</code>.
      </p>

      {/* CTV */}
      <h2 className="mb-3 font-headline text-lg font-bold text-on-surface">
        Hiệu suất CTV &amp; đối soát hoa hồng
      </h2>
      <CtvTable rows={ctv} />
      <p className="mt-2 text-xs text-outline">
        &quot;Đã trả&quot; = đánh dấu thanh toán toàn bộ khoản đang nợ của CTV đó (sau khi bạn chuyển
        khoản thật). Tỉ lệ duyệt đỏ khi &lt;70% (ngưỡng KPI).
      </p>
    </div>
  );
}
