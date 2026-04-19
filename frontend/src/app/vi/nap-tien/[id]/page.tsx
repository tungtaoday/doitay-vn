import { notFound } from 'next/navigation';
import Link from 'next/link';
import type { Route } from 'next';
import { ApiError } from '@/lib/api';
import { getDepositDetail } from '@/lib/wallet';
import { formatDateTime, formatVND } from '@/lib/format';
import type { DepositStatus } from '@/lib/api-types';
import { CancelButton, UploadProofForm } from './detail-actions';

export const metadata = { title: 'Chi tiết yêu cầu nạp tiền' };

const STATUS_STYLES: Record<DepositStatus, string> = {
  pending: 'bg-amber-100 text-amber-800',
  processing: 'bg-blue-100 text-blue-800',
  completed: 'bg-emerald-100 text-emerald-800',
  rejected: 'bg-rose-100 text-rose-800',
  cancelled: 'bg-slate-100 text-slate-700',
};

export default async function DepositDetailPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const depositId = Number(id);
  if (!depositId) notFound();

  let deposit;
  try {
    deposit = await getDepositDetail(depositId);
  } catch (e) {
    if (e instanceof ApiError && e.status === 404) notFound();
    throw e;
  }

  return (
    <div className="space-y-6">
      <header className="flex flex-wrap items-start justify-between gap-4">
        <div>
          <Link href={'/vi/nap-tien/lich-su' as Route} className="text-xs text-primary hover:underline">
            ← Lịch sử nạp tiền
          </Link>
          <h1 className="mt-2 font-headline text-3xl font-bold text-on-surface">
            {deposit.deposit_code}
          </h1>
          <p className="mt-1 text-sm text-on-surface-variant">
            Tạo ngày {formatDateTime(deposit.created_at)}
          </p>
        </div>
        <span
          className={`rounded-full px-4 py-2 text-sm font-medium ${STATUS_STYLES[deposit.status]}`}
        >
          {deposit.status_label}
        </span>
      </header>

      <section className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
        <dl className="grid gap-4 md:grid-cols-2">
          <Row label="Số tiền">{formatVND(deposit.amount)}</Row>
          <Row label="Phương thức">{deposit.payment_method_label}</Row>
          <Row label="Ví">{deposit.wallet.company_name ?? '—'}</Row>
          <Row label="Xử lý lúc">{formatDateTime(deposit.processed_at)}</Row>
          {deposit.user_notes && <Row label="Ghi chú">{deposit.user_notes}</Row>}
          {deposit.rejection_reason && (
            <Row label="Lý do từ chối / hủy">
              <span className="text-rose-600">{deposit.rejection_reason}</span>
            </Row>
          )}
        </dl>
      </section>

      {deposit.payment_proof_url && (
        <section className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
          <h2 className="mb-3 text-sm font-semibold text-on-surface">Ảnh chứng minh</h2>
          {/* eslint-disable-next-line @next/next/no-img-element */}
          <img
            src={deposit.payment_proof_url}
            alt="Ảnh chứng minh thanh toán"
            className="max-h-96 rounded-xl ring-1 ring-outline-variant"
          />
        </section>
      )}

      {deposit.can_upload_proof && (
        <section className="rounded-2xl bg-surface p-6 ring-1 ring-outline-variant">
          <h2 className="mb-3 text-sm font-semibold text-on-surface">
            {deposit.payment_proof_url ? 'Cập nhật ảnh chứng minh' : 'Upload ảnh chứng minh'}
          </h2>
          <UploadProofForm depositId={deposit.id} />
        </section>
      )}

      {deposit.can_be_cancelled && <CancelButton depositId={deposit.id} />}
    </div>
  );
}

function Row({ label, children }: { label: string; children: React.ReactNode }) {
  return (
    <div>
      <dt className="text-xs uppercase tracking-wider text-on-surface-variant">{label}</dt>
      <dd className="mt-1 text-sm font-medium text-on-surface">{children}</dd>
    </div>
  );
}
