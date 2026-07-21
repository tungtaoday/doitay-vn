'use client';

import Link from 'next/link';
import type { Route } from 'next';
import { getPlaceholderImage, isSeedImage } from '@/lib/placeholder-images';

/**
 * Xem trước hồ sơ ĐÚNG NHƯ KHÁCH HÀNG NHÌN THẤY — sao lại phần hero của trang
 * công khai /tho/[id] (avatar tròn + huy hiệu, tên + "Chuyên gia xác thực",
 * nghề, khu vực/đánh giá/kinh nghiệm, giới thiệu, tags). Cập nhật trực tiếp khi
 * thợ gõ. Giúp thợ hình dung khách thấy gì mà không cần rời trang.
 */
export function ProfilePreview({
  companyId,
  isActive,
  name,
  categoryName,
  avatarSrc,
  locationLabel,
  experience,
  description,
  tags,
}: {
  companyId: number;
  isActive: boolean;
  name: string;
  categoryName: string | null;
  avatarSrc: string | null;
  locationLabel: string;
  experience: number;
  description: string;
  tags: string[];
}) {
  const src = avatarSrc && !isSeedImage(avatarSrc)
    ? avatarSrc
    : getPlaceholderImage(categoryName, name);

  return (
    <div className="overflow-hidden rounded-3xl bg-surface-container-lowest shadow-soft ring-1 ring-outline-variant/10">
      {/* Thanh nhãn giả trình duyệt — nhấn mạnh "đây là khách nhìn thấy" */}
      <div className="flex items-center justify-between gap-3 bg-on-surface px-5 py-3">
        <div className="flex items-center gap-2 text-xs font-medium text-white/70">
          <span className="material-symbols-outlined text-[1rem] text-primary-container">visibility</span>
          Khách hàng nhìn thấy hồ sơ của bạn như thế này
        </div>
        {isActive ? (
          <Link
            href={`/cong-ty/${companyId}` as Route}
            target="_blank"
            className="flex items-center gap-1 rounded-lg bg-white/10 px-2.5 py-1 text-xs font-semibold text-white transition-colors hover:bg-white/20"
          >
            Mở trang thật
            <span className="material-symbols-outlined text-[0.9rem]">open_in_new</span>
          </Link>
        ) : null}
      </div>

      {/* Hero — sao lại /tho/[id] */}
      <div className="p-6 md:p-8">
        <div className="flex flex-col items-start gap-6 sm:flex-row sm:items-center">
          <div className="relative shrink-0">
            <div className="h-28 w-28 overflow-hidden rounded-full border-4 border-surface-container-highest shadow-ambient">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={src} alt={name || 'Hồ sơ thợ'} className="h-full w-full object-cover" />
            </div>
            <div className="absolute bottom-1 right-1 rounded-full border-2 border-surface bg-tertiary-container p-1 text-on-tertiary-container shadow-ambient">
              <span className="material-symbols-outlined text-[0.9rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
                verified
              </span>
            </div>
          </div>

          <div className="min-w-0 flex-1 space-y-2">
            <div className="flex flex-wrap items-center gap-2">
              <h3 className="font-headline text-2xl font-bold tracking-tight text-on-surface">
                {name || 'Tên hồ sơ của bạn'}
              </h3>
              <span className="rounded-full bg-primary-container px-2.5 py-0.5 text-[0.7rem] font-semibold text-on-primary-container">
                Chuyên gia xác thực
              </span>
            </div>
            {categoryName ? (
              <p className="text-lg font-medium text-secondary">{categoryName}</p>
            ) : null}
            <div className="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-outline">
              <span className="flex items-center gap-1">
                <span className="material-symbols-outlined text-[1rem]">location_on</span>
                {locationLabel || 'Khu vực phục vụ'}
              </span>
              <span className="flex items-center gap-1">
                <span className="material-symbols-outlined text-[1rem] text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>star</span>
                <span className="text-on-surface-variant">Chưa có đánh giá</span>
              </span>
              {experience > 0 ? (
                <span className="flex items-center gap-1">
                  <span className="material-symbols-outlined text-[1rem]">history</span>
                  {experience} năm kinh nghiệm
                </span>
              ) : null}
            </div>
          </div>
        </div>

        {/* Giới thiệu */}
        <div className="mt-6">
          <p className="mb-1.5 font-headline text-sm font-bold text-on-surface">Giới thiệu bản thân</p>
          <p className="line-clamp-4 whitespace-pre-line text-sm leading-relaxed text-on-surface-variant">
            {description.trim() || 'Chuyên gia chưa cập nhật giới thiệu chi tiết.'}
          </p>
        </div>

        {/* Tags */}
        {tags.length > 0 ? (
          <div className="mt-4 flex flex-wrap gap-2">
            {tags.slice(0, 8).map((tag) => (
              <span
                key={tag}
                className="rounded-full bg-surface-container-highest px-3 py-1 text-xs font-medium text-on-surface"
              >
                {tag}
              </span>
            ))}
          </div>
        ) : null}
      </div>
    </div>
  );
}
