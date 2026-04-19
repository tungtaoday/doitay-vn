import type { Metadata } from 'next';
import type { Route } from 'next';
import Link from 'next/link';
import { notFound, redirect } from 'next/navigation';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type {
  AuthUser,
  DetailEnvelope,
  PublicCompanyDetail,
  ServiceRequestData,
  UserCompany,
} from '@/lib/api-types';
import { getServiceRequest } from '@/lib/service-requests';
import { AppointmentBookingForm } from './appointment-form';

async function loadCurrentUser(): Promise<AuthUser | null> {
  const token = await getToken();
  if (!token) return null;
  try {
    const res = await api<{ data: AuthUser }>('/auth/me', { token });
    return res.data;
  } catch {
    return null;
  }
}

interface PageProps {
  params: Promise<{ id: string; rest?: string[] }>;
  searchParams: Promise<{ from_request?: string }>;
}

async function loadServiceRequest(id: number): Promise<ServiceRequestData | null> {
  try {
    const res = await getServiceRequest(id);
    return res.data;
  } catch {
    return null;
  }
}

async function loadCompany(id: string): Promise<PublicCompanyDetail | null> {
  if (!/^\d+$/.test(id)) return null;
  try {
    const res = await api<DetailEnvelope<PublicCompanyDetail>>(
      `/public/companies/${id}`,
    );
    return res.data;
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) return null;
    throw e;
  }
}

export async function generateMetadata({
  params,
}: PageProps): Promise<Metadata> {
  const { id } = await params;
  const company = await loadCompany(id);
  if (!company) return { title: 'Không tìm thấy công ty' };

  return {
    title: company.name,
    description: company.description?.slice(0, 160) ?? undefined,
    openGraph: {
      title: company.name,
      description: company.description?.slice(0, 160) ?? undefined,
      images: company.image ? [company.image] : undefined,
    },
    alternates: {
      canonical: `/cong-ty/${company.id}/${company.vanity_slug}`,
    },
  };
}

async function loadOwnCompanyId(token: string): Promise<number | null> {
  try {
    const res = await api<{ data: UserCompany[] }>('/user/companies', { token });
    return res.data[0]?.id ?? null;
  } catch {
    return null;
  }
}

export default async function CompanyDetailPage({ params, searchParams }: PageProps) {
  const { id, rest } = await params;
  const { from_request: fromRequest } = await searchParams;
  const [company, user] = await Promise.all([loadCompany(id), loadCurrentUser()]);
  if (!company) notFound();

  // Ownership check — only load user's company IDs when they have a company
  const token = await getToken();
  const ownCompanyId =
    user?.has_company && token ? await loadOwnCompanyId(token) : null;
  const isOwner = ownCompanyId === company.id;

  const serviceRequestId = fromRequest ? Number(fromRequest) : null;
  const prefillRequest =
    serviceRequestId && Number.isFinite(serviceRequestId) && user
      ? await loadServiceRequest(serviceRequestId)
      : null;

  // Canonicalise vanity slug — keeps SEO parity with legacy
  // /companies/{id}/{slug} URL pattern.
  const currentSlug = rest?.[0];
  if (currentSlug !== company.vanity_slug) {
    redirect(`/cong-ty/${company.id}/${company.vanity_slug}`);
  }

  const locationLabel =
    [company.location.district, company.location.city]
      .filter(Boolean)
      .join(', ') || 'Toàn quốc';

  return (
    <div className="mx-auto max-w-7xl px-6 pb-24 pt-12 lg:px-8">
      {/* Breadcrumbs + owner edit button */}
      <div className="mb-8 flex items-center justify-between">
        <nav className="flex items-center gap-2 text-sm text-outline">
          <Link href="/" className="transition-colors hover:text-primary">
            Trang chủ
          </Link>
          <span className="material-symbols-outlined text-xs">chevron_right</span>
          <Link href="/cong-ty" className="transition-colors hover:text-primary">
            Dịch vụ
          </Link>
          <span className="material-symbols-outlined text-xs">chevron_right</span>
          <span className="font-medium text-on-surface">{company.name}</span>
        </nav>
        {isOwner && (
          <Link
            href={'/vi/tho/sua-ho-so' as Route}
            className="flex items-center gap-2 rounded-xl border border-outline-variant/30 bg-surface-container-low px-4 py-2 text-sm font-bold text-on-surface transition-colors hover:bg-surface-container"
          >
            <span className="material-symbols-outlined text-[1.125rem] text-primary">edit</span>
            Sửa hồ sơ
          </Link>
        )}
      </div>

      <div className="grid grid-cols-1 gap-12 lg:grid-cols-12">
        {/* ─── Main column ───────────────────────────────────────────── */}
        <div className="space-y-16 lg:col-span-8">
          {/* Profile header */}
          <section className="flex flex-col items-start gap-8 md:flex-row md:items-center">
            <div className="group relative">
              <div className="h-40 w-40 overflow-hidden rounded-full border-4 border-surface-container-highest shadow-ambient">
                {/* eslint-disable-next-line @next/next/no-img-element */}
                <img
                  src={company.image ?? ''}
                  alt={company.name}
                  className="h-full w-full object-cover"
                />
              </div>
              <div className="absolute bottom-2 right-2 rounded-full border-2 border-surface bg-tertiary-container p-1.5 text-on-tertiary-container shadow-ambient">
                <span className="material-symbols-outlined fill text-sm">
                  verified
                </span>
              </div>
            </div>

            <div className="flex-1 space-y-3">
              <div className="flex flex-wrap items-center gap-3">
                <h1 className="font-headline text-4xl font-bold tracking-tight text-on-surface">
                  {company.name}
                </h1>
                <span className="rounded-full bg-primary-container px-3 py-1 text-xs font-semibold text-on-primary-container">
                  Chuyên gia xác thực
                </span>
              </div>
              {company.category ? (
                <p className="text-xl font-medium text-secondary">
                  {company.category.name}
                </p>
              ) : null}
              <div className="flex flex-wrap items-center gap-4 text-sm text-outline">
                <div className="flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">
                    location_on
                  </span>
                  <span>{locationLabel}</span>
                </div>
                <div className="flex items-center gap-1">
                  <span className="material-symbols-outlined fill text-sm text-tertiary">
                    star
                  </span>
                  <span className="font-bold text-on-surface">
                    {company.rating_avg.toFixed(1)}
                  </span>
                  <span>({company.rating_count} đánh giá)</span>
                </div>
                {company.experience > 0 ? (
                  <div className="flex items-center gap-1">
                    <span className="material-symbols-outlined text-sm">
                      history
                    </span>
                    <span>{company.experience} năm kinh nghiệm</span>
                  </div>
                ) : null}
              </div>
            </div>
          </section>

          {/* Stats bento */}
          <section className="grid grid-cols-1 gap-6 md:grid-cols-3">
            <StatCard
              icon="history"
              value={
                company.experience > 0 ? `${company.experience} năm` : '—'
              }
              label="Kinh nghiệm nghề nghiệp"
            />
            <StatCard
              icon="engineering"
              value={`${company.rating_count}+`}
              label="Đánh giá khách hàng"
            />
            <StatCard
              icon="recommend"
              value={`${company.rating_avg.toFixed(1)}/5`}
              label="Điểm hài lòng trung bình"
            />
          </section>

          {/* About + tags */}
          <section className="space-y-6">
            <h2 className="font-headline text-2xl font-bold text-on-surface">
              Giới thiệu bản thân
            </h2>
            <div className="space-y-4 leading-relaxed text-on-surface-variant">
              {company.description ? (
                company.description
                  .split(/\n+/)
                  .filter(Boolean)
                  .map((p, i) => <p key={i}>{p}</p>)
              ) : (
                <p>Chuyên gia chưa cập nhật giới thiệu chi tiết.</p>
              )}
            </div>
            {company.tags && company.tags.length > 0 ? (
              <div className="flex flex-wrap gap-2 pt-4">
                {company.tags.map((tag) => (
                  <span
                    key={tag}
                    className="rounded-full bg-surface-container-highest px-4 py-2 text-sm font-medium text-on-surface"
                  >
                    {tag}
                  </span>
                ))}
              </div>
            ) : null}
          </section>

          {/* Services */}
          {company.services && company.services.length > 0 ? (
            <section className="space-y-6">
              <h2 className="font-headline text-2xl font-bold text-on-surface">
                Dịch vụ cung cấp
              </h2>
              <div className="overflow-hidden rounded-3xl bg-surface-container-lowest">
                <table className="w-full border-collapse text-left">
                  <thead>
                    <tr className="bg-surface-container-low">
                      <th className="p-5 font-headline font-semibold text-on-surface">
                        Dịch vụ
                      </th>
                      <th className="p-5 text-right font-headline font-semibold text-on-surface">
                        Giá tham khảo
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    {company.services.map((svc, i) => {
                      const name = typeof svc === 'string' ? svc : svc.name;
                      const price = typeof svc === 'string' ? null : svc.price;
                      const desc = typeof svc === 'string' ? null : svc.description;
                      return (
                        <tr
                          key={i}
                          className="border-t border-outline-variant/10 transition-colors hover:bg-surface-bright"
                        >
                          <td className="p-5 text-on-surface">
                            <span className="font-medium">{name}</span>
                            {desc && <p className="mt-0.5 text-[0.8125rem] text-secondary">{desc}</p>}
                          </td>
                          <td className="p-5 text-right font-bold text-primary">
                            {price ? `${Number(price).toLocaleString('vi-VN')} đ` : 'Liên hệ'}
                          </td>
                        </tr>
                      );
                    })}
                  </tbody>
                </table>
              </div>
              <p className="text-right text-xs italic text-outline">
                * Giá có thể thay đổi tùy theo quy mô dự án và vật tư.
              </p>
            </section>
          ) : null}

          {/* Portfolio */}
          {company.portfolios && company.portfolios.length > 0 ? (
            <section className="space-y-6">
              <h2 className="font-headline text-2xl font-bold text-on-surface">
                Dự án đã thực hiện
              </h2>
              <div className="grid grid-cols-2 gap-4 md:grid-cols-3">
                {company.portfolios.map((p) => (
                  <div
                    key={p.id}
                    className="aspect-square overflow-hidden rounded-2xl bg-surface-container-low"
                  >
                    {p.image ? (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img
                        src={p.image}
                        alt={p.title ?? 'Portfolio'}
                        className="h-full w-full object-cover transition-transform duration-500 hover:scale-105"
                      />
                    ) : (
                      <div className="flex h-full w-full items-center justify-center text-on-surface-variant">
                        <span className="material-symbols-outlined text-3xl">
                          image
                        </span>
                      </div>
                    )}
                  </div>
                ))}
              </div>
            </section>
          ) : null}

          {/* Reviews */}
          {company.ratings_recent && company.ratings_recent.length > 0 ? (
            <section className="space-y-8">
              <div className="flex items-center justify-between">
                <h2 className="font-headline text-2xl font-bold text-on-surface">
                  Đánh giá thực tế
                </h2>
                <button className="text-sm font-semibold text-primary hover:underline">
                  Xem tất cả
                </button>
              </div>
              <div className="space-y-8">
                {company.ratings_recent.map((r) => (
                  <div key={r.id} className="flex flex-col gap-4 pb-8">
                    <div className="flex items-center gap-4">
                      <div className="h-12 w-12 overflow-hidden rounded-full bg-surface-container">
                        {r.user?.avatar ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img
                            src={r.user.avatar}
                            alt={r.user.name ?? ''}
                            className="h-full w-full object-cover"
                          />
                        ) : (
                          <div className="flex h-full w-full items-center justify-center text-on-surface-variant">
                            <span className="material-symbols-outlined">
                              person
                            </span>
                          </div>
                        )}
                      </div>
                      <div>
                        <div className="font-headline font-bold text-on-surface">
                          {r.user?.name ?? 'Khách hàng ẩn danh'}
                        </div>
                        <div className="flex text-tertiary">
                          {Array.from({
                            length: Math.round(r.score),
                          }).map((_, i) => (
                            <span
                              key={i}
                              className="material-symbols-outlined fill text-sm"
                            >
                              star
                            </span>
                          ))}
                        </div>
                      </div>
                      {r.created_at ? (
                        <span className="ml-auto text-xs text-outline">
                          {new Date(r.created_at).toLocaleDateString('vi-VN')}
                        </span>
                      ) : null}
                    </div>
                    {r.comment ? (
                      <p className="leading-relaxed text-on-surface-variant">
                        {r.comment}
                      </p>
                    ) : null}
                  </div>
                ))}
              </div>
            </section>
          ) : null}
        </div>

        {/* ─── Sticky sidebar ───────────────────────────────────────── */}
        <aside className="lg:col-span-4">
          <div className="sticky top-28 space-y-6">
            <div className="rounded-3xl bg-surface-container-lowest p-8 shadow-ambient">
              <div className="mb-6">
                <h3 className="mb-2 font-headline text-xl font-bold text-on-surface">
                  Đặt lịch với {company.name.split(' ').slice(-1)[0]}
                </h3>
                <p className="text-sm text-outline">
                  Thời gian phản hồi trung bình:{' '}
                  <span className="font-medium text-primary">15 phút</span>
                </p>
              </div>
              <AppointmentBookingForm
                company={company}
                user={user}
                prefillRequest={prefillRequest}
              />

              {company.show_contact ? (
                <div className="mt-6 space-y-2 border-t border-outline-variant/20 pt-6 text-sm text-on-surface-variant">
                  {company.phone ? (
                    <a
                      href={`tel:${company.phone}`}
                      className="flex items-center gap-3 hover:text-primary"
                    >
                      <span className="material-symbols-outlined text-primary">
                        call
                      </span>
                      {company.phone}
                    </a>
                  ) : null}
                  {company.email ? (
                    <a
                      href={`mailto:${company.email}`}
                      className="flex items-center gap-3 hover:text-primary"
                    >
                      <span className="material-symbols-outlined text-primary">
                        mail
                      </span>
                      {company.email}
                    </a>
                  ) : null}
                  {company.website ? (
                    <a
                      href={company.website}
                      target="_blank"
                      rel="noreferrer"
                      className="flex items-center gap-3 hover:text-primary"
                    >
                      <span className="material-symbols-outlined text-primary">
                        public
                      </span>
                      {company.website}
                    </a>
                  ) : null}
                </div>
              ) : null}
            </div>

            {/* Safety badge */}
            <div className="flex items-start gap-4 rounded-3xl bg-primary-container/10 p-6">
              <span className="material-symbols-outlined fill text-primary">
                shield
              </span>
              <div>
                <h4 className="font-headline text-sm font-bold text-on-primary-container">
                  Chính sách bảo hành
                </h4>
                <p className="mt-1 text-xs leading-relaxed text-on-primary-container/80">
                  doitay.vn đảm bảo chất lượng thi công và hỗ trợ bảo hành lên
                  tới 12 tháng cho mọi dịch vụ của thợ đã xác minh.
                </p>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  );
}

function StatCard({
  icon,
  value,
  label,
}: {
  icon: string;
  value: string;
  label: string;
}) {
  return (
    <div className="rounded-3xl bg-surface-container-low p-6 transition-all hover:-translate-y-1 hover:bg-surface-container">
      <span className="material-symbols-outlined mb-3 text-primary">
        {icon}
      </span>
      <div className="font-headline text-2xl font-bold text-on-surface">
        {value}
      </div>
      <div className="text-sm text-outline">{label}</div>
    </div>
  );
}
