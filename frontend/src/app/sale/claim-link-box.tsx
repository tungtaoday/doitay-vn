'use client';

import { useState } from 'react';

/**
 * Link để CTV gửi thợ nhận hồ sơ đã dựng hộ. Hiện lại trong danh sách (không chỉ
 * ngay sau khi nộp) — CTV đóng máy rồi mở lại vẫn lấy được link để gửi Zalo.
 */
export function ClaimLinkBox({ link, tenTho }: { link: string; tenTho: string }) {
  const [copied, setCopied] = useState(false);

  async function copy() {
    try {
      await navigator.clipboard.writeText(link);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    } catch {
      setCopied(false);
    }
  }

  return (
    <div className="mt-3 rounded-xl bg-primary-container/20 p-3">
      <p className="text-xs font-semibold text-on-surface">
        Thợ chưa bấm link — gửi lại cho {tenTho} qua Zalo
      </p>
      <p className="mt-1 break-all text-[11px] leading-snug text-on-surface-variant">{link}</p>
      <button
        type="button"
        onClick={copy}
        className="mt-2 h-9 rounded-lg bg-primary px-4 text-xs font-bold text-on-primary active:scale-95"
      >
        {copied ? 'Đã chép link' : 'Chép link gửi thợ'}
      </button>
    </div>
  );
}
