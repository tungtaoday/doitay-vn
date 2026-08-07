'use client';

import { useState, useTransition } from 'react';
import { themCtv, doiTrangThaiCtv } from '../ops-actions';
import { markPaidAction } from '../actions';

export interface CtvRow {
  id: number;
  user_id: number;
  name: string | null;
  mobile: string | null;
  khu_vuc: string | null;
  ghi_chu: string | null;
  trang_thai: number;
  them_ngay: string | null;
  nhap: number;
  duyet: number;
  tu_choi: number;
  ti_le_duyet: number | null;
  lan_cuoi: string | null;
  hoa_hong_tong: number;
  hoa_hong_chua_tra: number;
  tinh_trang: 'dang_chay' | 'chua_bat_dau' | 'nguoi' | 'ngung';
}

const vnd = (n: number) => n.toLocaleString('vi-VN') + 'đ';

export const TINH_TRANG: Record<CtvRow['tinh_trang'], { nhan: string; mau: string; viec: string }> = {
  dang_chay: {
    nhan: 'Đang chạy',
    mau: 'bg-primary-container text-on-primary-container',
    viec: 'Không phải làm gì.',
  },
  chua_bat_dau: {
    nhan: 'Chưa nhập hồ sơ nào',
    mau: 'bg-amber-100 text-amber-900',
    viec: 'Gọi hỏi vướng ở đâu, hướng dẫn lại cách vào doitay.vn/sale. Tuyển xong mà không bắt đầu là mất công tuyển.',
  },
  nguoi: {
    nhan: 'Nguội >14 ngày',
    mau: 'bg-surface-container-high text-on-surface',
    viec: 'Nhắn hỏi còn làm không. Không làm nữa thì bấm Ngưng để danh sách sạch.',
  },
  ngung: {
    nhan: 'Đã ngưng',
    mau: 'bg-surface-container text-on-surface-variant',
    viec: 'Không nhập hồ sơ mới được. Hoa hồng cũ vẫn phải trả.',
  },
};

/**
 * Danh sách CTV + thêm mới + ngưng/mở + đối soát hoa hồng.
 *
 * Điểm khác bảng hiệu suất cũ: đi từ danh sách CTV chứ không từ hồ sơ đã nhập,
 * nên người mới tuyển mà chưa làm gì vẫn hiện ra — đó mới là người cần gọi.
 */
export function BangCtv({ rows }: { rows: CtvRow[] }) {
  const [moForm, setMoForm] = useState(false);

  return (
    <>
      <div className="mb-4">
        {moForm ? (
          <FormThem onXong={() => setMoForm(false)} />
        ) : (
          <button
            type="button"
            onClick={() => setMoForm(true)}
            className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary"
          >
            + Thêm cộng tác viên
          </button>
        )}
      </div>

      {rows.length === 0 ? (
        <div className="rounded-2xl bg-surface-container-low p-8 text-center text-sm text-on-surface-variant">
          Chưa có CTV nào trong danh sách. Khi danh sách còn rỗng thì mọi tài khoản đăng nhập đều
          nhập hồ sơ được — thêm người đầu tiên vào là cổng bắt đầu kiểm tra.
        </div>
      ) : (
        <ul className="space-y-2">
          {rows.map((c) => (
            <DongCtv key={c.id} c={c} />
          ))}
        </ul>
      )}
    </>
  );
}

function DongCtv({ c }: { c: CtvRow }) {
  const tt = TINH_TRANG[c.tinh_trang];
  const [xong, setXong] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  function doiTrangThai() {
    setLoi(null);
    start(async () => {
      const r = await doiTrangThaiCtv(c.id, c.trang_thai === 1 ? 0 : 1);
      if (r.ok) setXong(r.message);
      else setLoi(r.error);
    });
  }

  function danhDauDaTra() {
    setLoi(null);
    start(async () => {
      const r = await markPaidAction(c.user_id);
      if (r.ok) setXong(`Đã đánh dấu trả ${vnd(r.tongTien)} (${r.soKhoan} khoản).`);
      else setLoi(r.error);
    });
  }

  return (
    <li className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15">
      <div className="flex flex-wrap items-start justify-between gap-3">
        <div className="min-w-0">
          <p className="font-semibold text-on-surface">
            {c.name ?? `CTV #${c.id}`}
            {c.mobile ? (
              <>
                {' · '}
                <a href={`tel:${c.mobile}`} className="text-primary">
                  {c.mobile}
                </a>
              </>
            ) : null}
          </p>
          <p className="text-xs text-on-surface-variant">
            {c.khu_vuc ?? 'Chưa ghi khu vực'} · nhập {c.nhap} · duyệt {c.duyet}
            {c.ti_le_duyet !== null ? (
              <span className={c.ti_le_duyet < 70 ? ' text-red-600' : ''}> ({c.ti_le_duyet}%)</span>
            ) : null}
            {c.lan_cuoi ? ` · lần cuối ${c.lan_cuoi.slice(0, 10)}` : ' · chưa nhập lần nào'}
          </p>
          {c.ghi_chu ? <p className="mt-0.5 text-xs text-outline">{c.ghi_chu}</p> : null}
          <p className="mt-1 text-xs text-on-surface">
            Hoa hồng {vnd(c.hoa_hong_tong)}
            {c.hoa_hong_chua_tra > 0 ? (
              <span className="font-bold text-red-600"> · chưa trả {vnd(c.hoa_hong_chua_tra)}</span>
            ) : null}
          </p>
        </div>

        <div className="flex shrink-0 flex-col items-end gap-2">
          <span className={`rounded-full px-3 py-1 text-xs font-bold ${tt.mau}`}>{tt.nhan}</span>
          {xong ? (
            <span className="text-xs font-semibold text-primary">{xong}</span>
          ) : (
            <span className="flex gap-1.5">
              {c.hoa_hong_chua_tra > 0 ? (
                <button
                  type="button"
                  disabled={dangChay}
                  onClick={danhDauDaTra}
                  className="rounded-lg bg-primary px-3 py-1.5 text-xs font-semibold text-on-primary disabled:opacity-50"
                >
                  Đã trả
                </button>
              ) : null}
              <button
                type="button"
                disabled={dangChay}
                onClick={doiTrangThai}
                className="rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface disabled:opacity-50"
              >
                {dangChay ? '…' : c.trang_thai === 1 ? 'Ngưng' : 'Cho chạy lại'}
              </button>
            </span>
          )}
          {loi ? <span className="text-xs text-red-600">{loi}</span> : null}
        </div>
      </div>

      {c.tinh_trang !== 'dang_chay' ? (
        <p className="mt-2 rounded-xl bg-surface-container-low px-3 py-2 text-xs text-on-surface-variant">
          {tt.viec}
        </p>
      ) : null}
    </li>
  );
}

function FormThem({ onXong }: { onXong: () => void }) {
  const [sdt, setSdt] = useState('');
  const [khuVuc, setKhuVuc] = useState('');
  const [ghiChu, setGhiChu] = useState('');
  const [xong, setXong] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  function luu() {
    setLoi(null);
    start(async () => {
      const r = await themCtv({ sdt, khu_vuc: khuVuc, ghi_chu: ghiChu });
      if (r.ok) {
        setXong(r.message);
        setSdt('');
        setKhuVuc('');
        setGhiChu('');
      } else setLoi(r.error);
    });
  }

  return (
    <div className="rounded-2xl bg-surface-container-low p-4">
      <p className="text-sm font-semibold text-on-surface">Thêm cộng tác viên</p>
      <p className="mt-0.5 text-xs text-on-surface-variant">
        Người đó phải có sẵn tài khoản trên doitay.vn (mật khẩu do chính họ đặt). Chưa có thì bảo
        đăng ký trước rồi quay lại thêm.
      </p>
      <div className="mt-3 grid gap-2 md:grid-cols-3">
        <input
          value={sdt}
          onChange={(e) => setSdt(e.target.value)}
          placeholder="Số điện thoại tài khoản"
          className="rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
        <input
          value={khuVuc}
          onChange={(e) => setKhuVuc(e.target.value)}
          placeholder="Khu vực phụ trách"
          className="rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
        <input
          value={ghiChu}
          onChange={(e) => setGhiChu(e.target.value)}
          placeholder="Ghi chú (ai giới thiệu…)"
          className="rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
        />
      </div>
      <div className="mt-3 flex flex-wrap items-center gap-2">
        <button
          type="button"
          disabled={dangChay || !sdt.trim()}
          onClick={luu}
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary disabled:opacity-50"
        >
          {dangChay ? 'Đang thêm…' : 'Thêm'}
        </button>
        <button
          type="button"
          onClick={onXong}
          className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface"
        >
          Đóng
        </button>
        {xong ? <span className="text-sm font-semibold text-primary">{xong}</span> : null}
        {loi ? <span className="text-sm text-red-600">{loi}</span> : null}
      </div>
    </div>
  );
}
