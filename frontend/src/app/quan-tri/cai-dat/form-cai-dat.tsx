'use client';

import { useState, useTransition } from 'react';
import { luuCaiDat } from '../ops-actions';

export type CaiDat = Record<string, string | number | null>;

const TRUONG: { key: string; nhan: string; mo_ta?: string; kieu?: 'bat_tat' }[] = [
  { key: 'site_name', nhan: 'Tên website', mo_ta: 'Hiện ở tiêu đề trang và email gửi đi' },
  { key: 'email_from', nhan: 'Email gửi đi' },
  { key: 'cur_text', nhan: 'Mã tiền tệ', mo_ta: 'VD: VND' },
  { key: 'cur_sym', nhan: 'Ký hiệu tiền tệ', mo_ta: 'VD: đ' },
  { key: 'zalo_phone', nhan: 'Số Zalo hỗ trợ', mo_ta: 'Nút chat Zalo góc màn hình' },
  { key: 'zalo_name', nhan: 'Tên hiển thị Zalo' },
  { key: 'zalo_message', nhan: 'Lời chào Zalo' },
  { key: 'zalo_online', nhan: 'Hiện nút Zalo', kieu: 'bat_tat' },
  { key: 'registration', nhan: 'Cho đăng ký tài khoản mới', kieu: 'bat_tat' },
  { key: 'maintenance_mode', nhan: 'Chế độ bảo trì', kieu: 'bat_tat', mo_ta: 'Bật là khách không vào được web' },
];

/**
 * Form cài đặt — CHỈ những trường an toàn.
 *
 * Bảng general_settings còn chứa mail_config, sms_config, socialite_credentials:
 * bí mật hệ thống, API không trả ra và ở đây cũng không có ô nào để sửa. Cần
 * đụng tới thì vào admin cũ hoặc sửa thẳng trên server.
 */
export function FormCaiDat({ ban_dau }: { ban_dau: CaiDat }) {
  const [gt, setGt] = useState<Record<string, string>>(() => {
    const o: Record<string, string> = {};
    TRUONG.forEach((t) => {
      o[t.key] = ban_dau[t.key] === null || ban_dau[t.key] === undefined ? '' : String(ban_dau[t.key]);
    });
    return o;
  });
  const [xong, setXong] = useState<string | null>(null);
  const [loi, setLoi] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  function luu() {
    setLoi(null);
    setXong(null);
    start(async () => {
      const r = await luuCaiDat(gt);
      if (r.ok) setXong(r.message);
      else setLoi(r.error);
    });
  }

  return (
    <div className="rounded-2xl bg-surface-container-lowest p-5 ring-1 ring-outline-variant/15">
      <div className="grid gap-4 md:grid-cols-2">
        {TRUONG.map((t) => (
          <div key={t.key} className={t.kieu === 'bat_tat' ? 'md:col-span-2' : ''}>
            {t.kieu === 'bat_tat' ? (
              <label className="flex items-start gap-2">
                <input
                  type="checkbox"
                  checked={gt[t.key] === '1'}
                  onChange={(e) => setGt((s) => ({ ...s, [t.key]: e.target.checked ? '1' : '0' }))}
                  className="mt-1"
                />
                <span>
                  <span className="block text-sm font-semibold text-on-surface">{t.nhan}</span>
                  {t.mo_ta ? (
                    <span className="block text-xs text-on-surface-variant">{t.mo_ta}</span>
                  ) : null}
                </span>
              </label>
            ) : (
              <>
                <label className="block text-sm font-semibold text-on-surface">{t.nhan}</label>
                {t.mo_ta ? (
                  <span className="block text-xs text-on-surface-variant">{t.mo_ta}</span>
                ) : null}
                <input
                  value={gt[t.key] ?? ''}
                  onChange={(e) => setGt((s) => ({ ...s, [t.key]: e.target.value }))}
                  className="mt-1 w-full rounded-xl bg-surface-container-low px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
                />
              </>
            )}
          </div>
        ))}
      </div>

      <div className="mt-5 flex items-center gap-3">
        <button
          type="button"
          disabled={dangChay}
          onClick={luu}
          className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary disabled:opacity-50"
        >
          {dangChay ? 'Đang lưu…' : 'Lưu cài đặt'}
        </button>
        {xong ? <span className="text-sm font-semibold text-primary">{xong}</span> : null}
        {loi ? <span className="text-sm text-red-600">{loi}</span> : null}
      </div>
    </div>
  );
}
