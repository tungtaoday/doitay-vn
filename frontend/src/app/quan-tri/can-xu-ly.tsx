import Link from 'next/link';
import type { Route } from 'next';
import { api } from '@/lib/api';

/**
 * "CẦN XỬ LÝ NGAY" — khối đầu tiên của trung tâm điều hành.
 *
 * Số tháng cho biết tình hình, nhưng không nói phải LÀM GÌ hôm nay. Khối này đọc
 * bảng hiệu suất thợ rồi quy ra đúng ba việc chặn dòng tiền, mỗi việc bấm được
 * sang thẳng nhóm cần xử lý:
 *   thợ chưa bấm link nhận hồ sơ → chưa gửi thẻ → gửi rồi mà khách không gọi
 * Đây chính là chuỗi ra tiền của Quyển 6, đọc từ dưới lên.
 */
interface Row {
  tinh_trang: string;
  status: 'live' | 'pending';
}

export async function CanXuLy() {
  let rows: Row[] = [];
  let bacDau: { share_rate: number; real_lead_rate: number } | null = null;

  try {
    const res = await api<{
      data: { tong_quan: { share_rate: number; real_lead_rate: number }; tho: Row[] };
    }>(`/public/metrics/tho-performance?token=${process.env.METRICS_TOKEN ?? ''}&days=30`);
    rows = res.data.tho;
    bacDau = res.data.tong_quan;
  } catch {
    return (
      <div className="mb-8 rounded-2xl bg-surface-container-low p-5 text-sm text-on-surface-variant">
        Chưa đọc được số hiệu suất — kiểm tra METRICS_TOKEN trong env frontend.
      </div>
    );
  }

  const dem = (t: string) => rows.filter((r) => r.tinh_trang === t).length;
  const choDuyet = rows.filter((r) => r.status === 'pending').length;

  const viec = [
    {
      so: choDuyet,
      nhan: 'hồ sơ chờ duyệt',
      viec: 'Gọi xác minh SĐT rồi bấm Hợp lệ / Từ chối',
      href: '/sale/duyet',
      mau: 'bg-amber-100 text-amber-900',
    },
    {
      so: dem('chua_nhan_ho_so'),
      nhan: 'thợ chưa bấm link nhận hồ sơ',
      viec: 'Gửi lại link qua Zalo — chưa bấm thì hồ sơ chưa thuộc về thợ',
      href: '/sale/hieu-suat?loc=chua_nhan_ho_so',
      mau: 'bg-error-container text-on-error-container',
    },
    {
      so: dem('chua_share'),
      nhan: 'thợ chưa gửi thẻ cho khách',
      viec: 'Nhắn thợ gửi cho 2-3 khách quen — hồ sơ nằm im thì không ai gọi',
      href: '/sale/hieu-suat?loc=chua_share',
      mau: 'bg-blue-100 text-blue-900',
    },
    {
      so: dem('xem_nhung_khong_goi'),
      nhan: 'hồ sơ có khách xem mà không ai gọi',
      viec: 'Hồ sơ yếu: bổ sung ảnh việc thật hoặc bảng giá rồi gửi lại',
      href: '/sale/hieu-suat?loc=xem_nhung_khong_goi',
      mau: 'bg-surface-container-high text-on-surface',
    },
  ].filter((v) => v.so > 0);

  return (
    <div className="mb-8">
      <div className="mb-3 flex flex-wrap items-baseline justify-between gap-2">
        <h2 className="font-headline text-lg font-bold text-on-surface">Cần xử lý ngay</h2>
        {bacDau ? (
          <p className="text-xs text-on-surface-variant">
            Bắc Đẩu 30 ngày — share_rate <b>{bacDau.share_rate}%</b> · real_lead_rate{' '}
            <b>{bacDau.real_lead_rate}%</b>
          </p>
        ) : null}
      </div>

      {viec.length === 0 ? (
        <div className="rounded-2xl bg-primary-container/20 p-5 text-sm text-on-surface">
          Không có việc nào đang kẹt. Dồn sức vào tuyển thêm thợ (Quyển 10).
        </div>
      ) : (
        <div className="grid gap-3 md:grid-cols-2">
          {viec.map((v) => (
            <Link
              key={v.nhan}
              href={v.href as Route}
              className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15 transition-colors hover:bg-surface-container-low"
            >
              <div className="flex items-baseline gap-2">
                <span className={`rounded-lg px-2 py-0.5 font-headline text-lg font-extrabold ${v.mau}`}>
                  {v.so}
                </span>
                <span className="font-semibold text-on-surface">{v.nhan}</span>
              </div>
              <p className="mt-1.5 text-xs leading-snug text-on-surface-variant">{v.viec}</p>
            </Link>
          ))}
        </div>
      )}
    </div>
  );
}
