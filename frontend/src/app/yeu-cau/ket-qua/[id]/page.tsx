import Link from 'next/link';
import type { Route } from 'next';
import { notFound } from 'next/navigation';
import type { Metadata } from 'next';
import { ApiError } from '@/lib/api';
import { requireUser } from '@/lib/require-user';
import { getServiceRequest } from '@/lib/service-requests';
import type { ServiceRequestMatch } from '@/lib/api-types';

export const metadata: Metadata = {
  title: 'Thợ phù hợp với yêu cầu của bạn',
};

export default async function ServiceRequestResultPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id: idParam } = await params;
  const id = Number(idParam);
  if (!Number.isFinite(id) || id <= 0) notFound();

  await requireUser();

  let data;
  try {
    const res = await getServiceRequest(id);
    data = res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) notFound();
    throw e;
  }

  const matches = data.matches ?? [];

  return (
    <div className="min-h-screen bg-surface px-6 pb-20 pt-12">
      <div className="mx-auto max-w-[960px]">
        <div className="mb-12">
          <Link
            href={'/yeu-cau' as Route}
            className="mb-6 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:underline"
          >
            <span className="material-symbols-outlined text-base">arrow_back</span>
            Tạo yêu cầu khác
          </Link>
          <h1 className="mb-3 font-headline text-4xl font-bold text-on-surface md:text-5xl">
            {matches.length > 0
              ? `Chúng tôi đã chọn ${matches.length} thợ phù hợp`
              : 'Chưa có thợ phù hợp'}
          </h1>
          <p className="text-lg text-on-surface-variant">
            Yêu cầu <strong>#{data.id}</strong> — {data.category?.name ?? 'Dịch vụ'} tại{' '}
            {data.location.district ? `${data.location.district}, ` : ''}
            {data.location.city}
          </p>
        </div>

        {matches.length === 0 && (
          <div className="rounded-3xl bg-surface-container-low p-12 text-center">
            <p className="mb-6 text-lg text-on-surface-variant">
              Hiện tại chưa có thợ nào phù hợp với danh mục + khu vực của bạn. Hệ thống sẽ tự
              cập nhật khi có thợ mới đăng ký.
            </p>
            <Link
              href={'/cong-ty' as Route}
              className="inline-flex h-12 items-center gap-2 rounded-lg bg-primary px-6 font-bold text-on-primary hover:opacity-90"
            >
              Xem tất cả thợ
            </Link>
          </div>
        )}

        <div className="space-y-4">
          {matches.map((m, idx) => (
            <MatchCard key={m.company.id} match={m} rank={idx + 1} requestId={data.id} />
          ))}
        </div>
      </div>
    </div>
  );
}

function MatchCard({
  match,
  rank,
  requestId,
}: {
  match: ServiceRequestMatch;
  rank: number;
  requestId: number;
}) {
  const { company, score, reasons } = match;
  const bookingHref = `/cong-ty/${company.id}/${company.vanity_slug}?from_request=${requestId}` as Route;

  return (
    <div className="flex flex-col gap-6 rounded-3xl bg-surface-container-lowest p-6 shadow-ambient md:flex-row md:items-center">
      <div className="flex items-center gap-4 md:w-20 md:flex-col md:items-center">
        <div className="flex h-14 w-14 items-center justify-center rounded-full bg-primary-container font-headline text-2xl font-bold text-on-primary-container">
          {rank}
        </div>
        <div className="text-xs text-outline md:text-center">điểm {score}</div>
      </div>

      <div className="flex-1">
        <div className="mb-2 flex items-start justify-between gap-3">
          <div>
            <Link
              href={`/cong-ty/${company.id}/${company.vanity_slug}` as Route}
              className="font-headline text-2xl font-bold text-on-surface hover:text-primary"
            >
              {company.name}
            </Link>
            <div className="mt-1 text-sm text-on-surface-variant">
              {company.category?.name}
              {company.city && ` • ${company.district ?? ''} ${company.city}`}
            </div>
          </div>
          <div className="flex items-center gap-1 text-sm font-semibold text-on-surface">
            <span className="material-symbols-outlined text-base text-amber-500">star</span>
            {company.avg_rating > 0 ? company.avg_rating.toFixed(1) : '—'}
          </div>
        </div>

        {company.short_description && (
          <p className="mb-3 line-clamp-2 text-sm text-on-surface-variant">{company.short_description}</p>
        )}

        <div className="flex flex-wrap gap-2">
          {reasons.map((r) => (
            <span
              key={r}
              className="rounded-full bg-secondary-container px-3 py-1 text-xs font-medium text-on-secondary-container"
            >
              {translateReason(r)}
            </span>
          ))}
        </div>
      </div>

      <div className="md:w-48">
        <Link
          href={bookingHref}
          className="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-primary font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95"
        >
          Đặt lịch ngay
          <span className="material-symbols-outlined text-lg">arrow_forward</span>
        </Link>
      </div>
    </div>
  );
}

function translateReason(reason: string): string {
  if (reason === 'category_match') return 'Đúng danh mục';
  if (reason === 'same_city') return 'Cùng tỉnh/thành';
  if (reason === 'same_district') return 'Cùng quận/huyện';
  if (reason.startsWith('rating_')) return `★ ${reason.slice('rating_'.length)}`;
  if (reason.startsWith('completed_')) return `${reason.slice('completed_'.length)} lịch đã hoàn thành`;
  return reason;
}
