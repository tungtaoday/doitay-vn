import type { Metadata } from 'next';
import { requireUser } from '@/lib/require-user';
import { getToken } from '@/lib/auth';
import { api, ApiError } from '@/lib/api';
import { KhongCoQuyen } from '../khong-co-quyen';
import { gio, type HomNayResponse, type LichRow } from '../insight-types';
import { NhatLenhBox, type NhatLenh } from './nhat-lenh';

export const metadata: Metadata = {
  title: 'Việc hôm nay | Quản trị',
  robots: { index: false, follow: false },
};

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

/**
 * NGHIỆP VỤ HÀNG NGÀY — mở buổi sáng là thấy đúng việc phải gọi hôm nay.
 *
 * Nguyên tắc: mỗi dòng phải gọi được ngay tại chỗ. Đã kèm tên + số điện thoại
 * của cả khách lẫn thợ, không bắt người trực mở thêm trang nào để tra.
 */
export default async function HomNayPage() {
  await requireUser({ requireProfile: false, loginPath: '/sale/login' });
  const token = await getToken();

  let d: HomNayResponse['data'] | null = null;
  let denied = false;
  try {
    const res = await api<HomNayResponse>('/admin/insight/hom-nay', { token: token ?? undefined });
    d = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 403) denied = true;
  }

  if (denied || !d) return <KhongCoQuyen />;

  const tongViec =
    d.yeu_cau_treo.length + d.cho_tho_nhan.length + d.cho_chot.length + d.lich_hom_nay.length;

  return (
    <div className="max-w-5xl">
      <div className="mb-6">
        <h1 className="font-headline text-2xl font-bold text-on-surface">Việc hôm nay</h1>
        <p className="text-sm text-on-surface-variant">
          {tongViec > 0
            ? `${tongViec} việc đang đến hạn hoặc quá hạn — làm từ trên xuống.`
            : 'Không có việc nào đến hạn. Dồn sức tuyển thợ và đẩy nội dung.'}
        </p>
      </div>

      {/* Việc theo KẾ HOẠCH (bot blueprint đẩy lên) đặt trước việc đang KẸT
          theo dữ liệu — mở một trang là thấy đủ cả hai loại. */}
      {d.nhat_lenh ? <NhatLenhBox d={d.nhat_lenh as NhatLenh} /> : null}

      <div className="mb-8 grid grid-cols-3 gap-3">
        <O nhan="Hồ sơ CTV chờ duyệt" so={String(d.dem.ho_so_cho_duyet)} />
        <O nhan="Thợ tự đăng ký chờ duyệt" so={String(d.dem.tho_cho_duyet)} />
        <O nhan="Hoa hồng chưa trả" so={vnd(d.dem.hoa_hong_chua_tra)} />
      </div>

      <Muc
        tieu_de="Yêu cầu khách treo quá 24h"
        vi_sao="Khách đã mô tả việc mà chưa ai nhận. Ghép thợ thủ công rồi gọi báo khách — để lâu là mất."
        rong={d.yeu_cau_treo.length === 0}
      >
        {d.yeu_cau_treo.map((r) => (
          <Dong
            key={r.id}
            chinh={r.title ?? `Yêu cầu #${r.id}`}
            phu={[r.district, r.city].filter(Boolean).join(', ')}
            ten={r.contact_name}
            sdt={r.contact_phone}
            thoi_gian={`gửi ${gio(r.created_at)}`}
          />
        ))}
      </Muc>

      <Muc
        tieu_de="Lịch hẹn diễn ra hôm nay"
        vi_sao="Gọi nhắc cả hai đầu từ sáng. Thợ quên lịch là hỏng luôn quan hệ với khách."
        rong={d.lich_hom_nay.length === 0}
      >
        {d.lich_hom_nay.map((a) => (
          <DongLich key={a.id} a={a} phu={`${a.appointment_time ?? ''} · ${a.status ?? ''}`} />
        ))}
      </Muc>

      <Muc
        tieu_de="Thợ chưa xác nhận quá 4 tiếng"
        vi_sao="Khách đang chờ trả lời. Gọi giục thợ, không nhận thì chuyển thợ khác ngay."
        rong={d.cho_tho_nhan.length === 0}
      >
        {d.cho_tho_nhan.map((a) => (
          <DongLich key={a.id} a={a} phu={`đặt lúc ${gio(a.created_at)}`} />
        ))}
      </Muc>

      <Muc
        tieu_de="Qua ngày hẹn mà chưa chốt kết quả"
        vi_sao="Không cập nhật thì không biết việc có thành hay không, và cũng không xin được đánh giá."
        rong={d.cho_chot.length === 0}
      >
        {d.cho_chot.map((a) => (
          <DongLich key={a.id} a={a} phu={`hẹn ngày ${a.appointment_date ?? '—'}`} />
        ))}
      </Muc>

      <Muc
        tieu_de="Khách mới 3 ngày qua chưa gửi yêu cầu nào"
        vi_sao="Họ đã đăng ký tức là có nhu cầu. Gọi hỏi đang cần sửa gì, đặt hộ luôn."
        rong={d.khach_moi.length === 0}
      >
        {d.khach_moi.map((k) => (
          <Dong
            key={k.id}
            chinh={k.name ?? `Khách #${k.id}`}
            phu={k.city ?? ''}
            ten={k.name}
            sdt={k.mobile}
            thoi_gian={`đăng ký ${gio(k.created_at)}`}
          />
        ))}
      </Muc>
    </div>
  );
}

function O({ nhan, so }: { nhan: string; so: string }) {
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <p className="font-headline text-xl font-extrabold text-on-surface">{so}</p>
      <p className="mt-1 text-xs text-on-surface-variant">{nhan}</p>
    </div>
  );
}

function Muc({
  tieu_de,
  vi_sao,
  rong,
  children,
}: {
  tieu_de: string;
  vi_sao: string;
  rong: boolean;
  children: React.ReactNode;
}) {
  return (
    <section className="mb-8">
      <h2 className="font-headline text-lg font-bold text-on-surface">{tieu_de}</h2>
      <p className="mb-3 text-xs text-on-surface-variant">{vi_sao}</p>
      {rong ? (
        <p className="rounded-2xl bg-surface-container-low px-4 py-3 text-sm text-on-surface-variant">
          Không có việc nào.
        </p>
      ) : (
        <ul className="space-y-2">{children}</ul>
      )}
    </section>
  );
}

function Dong({
  chinh,
  phu,
  ten,
  sdt,
  thoi_gian,
  them,
}: {
  chinh: string;
  phu?: string;
  ten?: string | null;
  sdt?: string | null;
  thoi_gian?: string;
  them?: React.ReactNode;
}) {
  return (
    <li className="flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-surface-container-lowest px-4 py-3 ring-1 ring-outline-variant/15">
      <div className="min-w-0">
        <p className="truncate font-semibold text-on-surface">{chinh}</p>
        <p className="truncate text-xs text-on-surface-variant">
          {[phu, ten, thoi_gian].filter(Boolean).join(' · ')}
        </p>
        {them}
      </div>
      {sdt ? (
        <a
          href={`tel:${sdt}`}
          className="shrink-0 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary"
        >
          Gọi {sdt}
        </a>
      ) : null}
    </li>
  );
}

function DongLich({ a, phu }: { a: LichRow; phu: string }) {
  return (
    <Dong
      chinh={a.recipient_name ?? `Lịch #${a.id}`}
      phu={phu}
      sdt={a.recipient_phone}
      them={
        a.tho ? (
          <p className="truncate text-xs text-outline">
            Thợ: {a.tho}
            {a.tho_phone ? (
              <>
                {' · '}
                <a className="text-primary" href={`tel:${a.tho_phone}`}>
                  gọi thợ {a.tho_phone}
                </a>
              </>
            ) : null}
          </p>
        ) : null
      }
    />
  );
}
