import type { Metadata } from 'next';
import Link from 'next/link';
import type { Route } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { KHACH_INFO, gio, type KhachHangResponse, type KhachRow, type TinhTrangKhach } from '../insight-types';

export const metadata: Metadata = {
  title: 'Hiệu suất khách hàng | Quản trị',
  robots: { index: false, follow: false },
};

interface PageProps {
  searchParams: Promise<{ days?: string; loc?: string }>;
}

/**
 * PHÍA CẦU — đối xứng với /sale/hieu-suat (phía cung).
 *
 * Chuỗi đọc: đăng ký → gửi yêu cầu → có lịch → xong việc → quay lại.
 * Mỗi khách mang đúng một nhãn = việc phải làm với họ trước tiên.
 */
export default async function KhachHangPage({ searchParams }: PageProps) {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const { days = '90', loc = '' } = await searchParams;
  const token = await getToken();

  let d: KhachHangResponse['data'] | null = null;
  let denied = false;
  try {
    const res = await api<KhachHangResponse>(`/admin/insight/khach-hang?days=${days}`, {
      token: token ?? undefined,
    });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  const tq = d.tong_quan;
  const rows: KhachRow[] = d.khach;
  const hien = loc ? rows.filter((r) => r.tinh_trang === loc) : rows;
  const dem = (t: string) => rows.filter((r) => r.tinh_trang === t).length;

  return (
    <div className="max-w-6xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Hiệu suất khách hàng</h1>
        <p className="text-sm text-on-surface-variant">
          {days} ngày · đăng ký → gửi yêu cầu → có lịch → xong việc → quay lại
        </p>
      </div>

      <div className="mb-4 flex gap-2">
        {['30', '90', '365'].map((x) => (
          <Link
            key={x}
            href={`/quan-tri/khach-hang?days=${x}${loc ? `&loc=${loc}` : ''}` as Route}
            className={`rounded-xl px-3 py-2 text-sm font-semibold ${
              days === x ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface'
            }`}
          >
            {x} ngày
          </Link>
        ))}
      </div>

      {/* Bậc thang: mẫu số thu hẹp dần, đọc từ trái sang */}
      <div className="mb-4 grid grid-cols-2 gap-3 md:grid-cols-5">
        <Bac nhan="Tổng khách" so={tq.tong_khach} phu={`+${tq.khach_moi} mới trong kỳ`} />
        <Bac nhan="Từng gửi yêu cầu" so={tq.co_yeu_cau} phu={`${tq.ty_le_gui_yeu_cau}% tổng khách`} />
        <Bac nhan="Từng có lịch" so={tq.co_lich} phu={`${tq.ty_le_co_lich}% tổng khách`} />
        <Bac nhan="Có việc xong" so={tq.co_xong} phu={`${tq.ty_le_xong_viec}% số có lịch`} />
        <Bac nhan="Quay lại (≥2 lần)" so={tq.quay_lai} phu={`${tq.ty_le_quay_lai}% số xong việc`} />
      </div>

      {/* Ghép yêu cầu đo trên bảng yêu cầu, không suy từ số khách */}
      <div className="mb-6 rounded-2xl bg-surface-container-low p-4 text-sm text-on-surface-variant">
        Trong kỳ có <b className="text-on-surface">{tq.yeu_cau_trong_ky}</b> yêu cầu khách gửi lên,
        ghép được thợ <b className="text-on-surface">{tq.yeu_cau_ghep_duoc}</b> ({tq.ty_le_ghep}%).
        Phần chưa ghép chính là chỗ khách rơi mất — xem ở{' '}
        <Link href={'/quan-tri/hom-nay' as Route} className="text-primary hover:underline">
          Việc hôm nay
        </Link>
        .
      </div>

      <div className="mb-4 flex flex-wrap gap-2">
        <Link
          href={`/quan-tri/khach-hang?days=${days}` as Route}
          className={`rounded-full px-3 py-1.5 text-sm font-semibold ${
            !loc ? 'bg-on-surface text-surface' : 'bg-surface-container text-on-surface'
          }`}
        >
          Tất cả ({rows.length})
        </Link>
        {(Object.keys(KHACH_INFO) as TinhTrangKhach[]).map((k) => (
          <Link
            key={k}
            href={`/quan-tri/khach-hang?days=${days}&loc=${k}` as Route}
            className={`rounded-full px-3 py-1.5 text-sm font-semibold ${
              loc === k ? 'bg-on-surface text-surface' : KHACH_INFO[k].mau
            }`}
          >
            {KHACH_INFO[k].nhan} ({dem(k)})
          </Link>
        ))}
      </div>

      {loc ? (
        <div className="mb-4 rounded-2xl bg-surface-container-low p-4">
          <p className="text-sm font-bold text-on-surface">Việc cần làm với nhóm này</p>
          <p className="mt-1 text-sm text-on-surface-variant">
            {KHACH_INFO[loc as TinhTrangKhach]?.viec}
          </p>
        </div>
      ) : null}

      {hien.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-lowest p-8 text-center text-on-surface-variant">
          Chưa có khách nào trong nhóm này.
        </div>
      ) : (
        <div className="overflow-x-auto rounded-2xl bg-surface-container-lowest shadow-soft">
          <table className="w-full min-w-[760px] border-collapse text-left text-sm">
            <thead>
              <tr className="bg-surface-container-low text-xs uppercase tracking-wide text-outline">
                <th className="p-3">Khách</th>
                <th className="p-3">Tình trạng</th>
                <th className="p-3 text-right">Yêu cầu</th>
                <th className="p-3 text-right">Lịch</th>
                <th className="p-3 text-right">Xong</th>
                <th className="p-3">Hoạt động cuối</th>
              </tr>
            </thead>
            <tbody>
              {hien.slice(0, 300).map((r) => {
                const info = KHACH_INFO[r.tinh_trang];
                return (
                  <tr key={r.id} className="border-t border-outline-variant/10 align-top">
                    <td className="p-3">
                      <p className="font-bold text-on-surface">{r.name ?? `Khách #${r.id}`}</p>
                      <p className="text-xs text-on-surface-variant">{r.city ?? ''}</p>
                      {r.mobile ? (
                        <a href={`tel:${r.mobile}`} className="text-xs text-primary">
                          {r.mobile}
                        </a>
                      ) : null}
                    </td>
                    <td className="p-3">
                      <span className={`inline-block rounded-full px-2.5 py-1 text-xs font-bold ${info.mau}`}>
                        {info.nhan}
                      </span>
                    </td>
                    <td className="p-3 text-right font-semibold">
                      {r.so_yeu_cau}
                      {r.yeu_cau_treo > 0 ? (
                        <span className="ml-1 text-xs text-red-600">({r.yeu_cau_treo} treo)</span>
                      ) : null}
                    </td>
                    <td className="p-3 text-right font-semibold">
                      {r.so_lich}
                      {r.lich_qua_ngay > 0 ? (
                        <span className="ml-1 text-xs text-red-600">({r.lich_qua_ngay} quá ngày)</span>
                      ) : null}
                    </td>
                    <td className="p-3 text-right font-semibold">{r.so_xong}</td>
                    <td className="p-3 text-xs text-on-surface-variant">{gio(r.lan_cuoi)}</td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      )}

      {hien.length > 300 ? (
        <p className="mt-3 text-xs text-outline">
          Đang hiện 300 dòng đầu trong {hien.length}. Lọc theo tình trạng để thu hẹp.
        </p>
      ) : null}
    </div>
  );
}

function Bac({ nhan, so, phu }: { nhan: string; so: number; phu: string }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-4 shadow-soft">
      <p className="text-xs font-semibold uppercase tracking-wide text-outline">{nhan}</p>
      <p className="mt-1 font-headline text-3xl font-bold text-on-surface">{so}</p>
      <p className="mt-0.5 text-xs text-on-surface-variant">{phu}</p>
    </div>
  );
}
