'use client';

import { useState, useTransition } from 'react';
import { luuDanhMuc } from '../ops-actions';

export interface NgheRow {
  id: number;
  name: string;
  description: string | null;
  status: number;
  icon: string | null;
  so_tho: number;
}

/**
 * Bảng nghề + sửa tại chỗ.
 *
 * Cột "số thợ" quan trọng hơn vẻ ngoài: bật một nghề chưa có thợ nào lên chỉ
 * làm khách bấm vào rồi thấy trang rỗng, nên tắt/bật phải nhìn con số đó.
 */
export function BangNghe({ rows }: { rows: NgheRow[] }) {
  const [suaId, setSuaId] = useState<number | 'moi' | null>(null);

  return (
    <>
      <div className="mb-4">
        {suaId === 'moi' ? (
          <FormNghe onXong={() => setSuaId(null)} />
        ) : (
          <button
            type="button"
            onClick={() => setSuaId('moi')}
            className="rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary"
          >
            + Thêm nghề
          </button>
        )}
      </div>

      <ul className="space-y-2">
        {rows.map((n) => (
          <li
            key={n.id}
            className="rounded-2xl bg-surface-container-lowest p-4 ring-1 ring-outline-variant/15"
          >
            {suaId === n.id ? (
              <FormNghe nghe={n} onXong={() => setSuaId(null)} />
            ) : (
              <div className="flex flex-wrap items-start justify-between gap-3">
                <div className="min-w-0">
                  <p className="font-semibold text-on-surface">
                    {n.name}
                    <span
                      className={`ml-2 rounded-full px-2 py-0.5 text-xs font-bold ${
                        n.status === 1
                          ? 'bg-primary-container text-on-primary-container'
                          : 'bg-surface-container text-on-surface-variant'
                      }`}
                    >
                      {n.status === 1 ? 'Đang hiện' : 'Đang ẩn'}
                    </span>
                  </p>
                  <p className="mt-0.5 text-xs text-on-surface-variant">
                    {n.so_tho} thợ
                    {n.so_tho === 0 && n.status === 1 ? (
                      <span className="text-red-600"> — đang hiện mà chưa có thợ nào</span>
                    ) : null}
                  </p>
                  {n.description ? (
                    <p className="mt-1 text-xs text-outline">{n.description}</p>
                  ) : null}
                </div>
                <button
                  type="button"
                  onClick={() => setSuaId(n.id)}
                  className="shrink-0 rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface"
                >
                  Sửa
                </button>
              </div>
            )}
          </li>
        ))}
      </ul>
    </>
  );
}

function FormNghe({ nghe, onXong }: { nghe?: NgheRow; onXong: () => void }) {
  const [name, setName] = useState(nghe?.name ?? '');
  const [mota, setMota] = useState(nghe?.description ?? '');
  const [status, setStatus] = useState<0 | 1>((nghe?.status === 0 ? 0 : 1) as 0 | 1);
  const [loi, setLoi] = useState<string | null>(null);
  const [xong, setXong] = useState<string | null>(null);
  const [dangChay, start] = useTransition();

  function luu() {
    setLoi(null);
    start(async () => {
      const r = await luuDanhMuc({ id: nghe?.id, name, description: mota, status });
      if (r.ok) {
        setXong(r.message);
        setTimeout(onXong, 600);
      } else setLoi(r.error);
    });
  }

  return (
    <div className="rounded-xl bg-surface-container-low p-3">
      <input
        value={name}
        onChange={(e) => setName(e.target.value)}
        placeholder="Tên nghề (VD: Thợ điện nước)"
        className="w-full rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
      />
      <input
        value={mota}
        onChange={(e) => setMota(e.target.value)}
        placeholder="Mô tả ngắn (tuỳ chọn)"
        className="mt-2 w-full rounded-xl bg-surface-container-lowest px-3 py-2 text-sm outline-none ring-2 ring-transparent focus:ring-primary/30"
      />
      <label className="mt-2 flex items-center gap-2 text-sm text-on-surface">
        <input
          type="checkbox"
          checked={status === 1}
          onChange={(e) => setStatus(e.target.checked ? 1 : 0)}
        />
        Hiện nghề này cho khách chọn
      </label>
      <div className="mt-2 flex items-center gap-2">
        <button
          type="button"
          disabled={dangChay}
          onClick={luu}
          className="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-on-primary disabled:opacity-50"
        >
          {dangChay ? 'Đang lưu…' : 'Lưu'}
        </button>
        <button
          type="button"
          onClick={onXong}
          className="rounded-xl bg-surface-container px-4 py-2 text-sm font-semibold text-on-surface"
        >
          Thôi
        </button>
        {xong ? <span className="text-xs font-semibold text-primary">{xong}</span> : null}
        {loi ? <span className="text-xs text-red-600">{loi}</span> : null}
      </div>
    </div>
  );
}
