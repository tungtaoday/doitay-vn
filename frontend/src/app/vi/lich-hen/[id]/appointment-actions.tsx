'use client';

import { useRouter } from 'next/navigation';
import { useCallback, useEffect, useState, useTransition } from 'react';
import { cancelAppointment, submitReview } from '../actions';
import type { RatingFeature } from '@/lib/api-types';

const API_BASE =
  process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';

export function AppointmentActions({
  appointmentId,
  companyId,
  status,
  hasRating,
}: {
  appointmentId: number;
  companyId: number;
  status: string;
  hasRating: boolean;
}) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [error, setError] = useState('');

  function handleCancel() {
    if (!confirm('Bạn chắc chắn muốn huỷ lịch hẹn này?')) return;
    startTransition(async () => {
      const res = await cancelAppointment(appointmentId);
      if (!res.ok) setError(res.error);
      else router.refresh();
    });
  }

  return (
    <div className="space-y-6">
      {error && (
        <p className="rounded-xl bg-error-container p-4 text-error">{error}</p>
      )}

      {status === 'pending' && (
        <button
          type="button"
          onClick={handleCancel}
          disabled={isPending}
          className="rounded-xl bg-error px-6 py-3 font-bold text-on-error transition-all hover:opacity-90 disabled:opacity-50"
        >
          {isPending ? 'Đang xử lý...' : 'Huỷ lịch hẹn'}
        </button>
      )}

      {status === 'completed' && !hasRating && (
        <ReviewForm appointmentId={appointmentId} companyId={companyId} />
      )}

      {hasRating && (
        <p className="text-sm font-medium text-green-700">
          Bạn đã đánh giá lịch hẹn này.
        </p>
      )}
    </div>
  );
}

function ReviewForm({
  appointmentId,
  companyId,
}: {
  appointmentId: number;
  companyId: number;
}) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [features, setFeatures] = useState<RatingFeature[]>([]);
  const [ratings, setRatings] = useState<Record<number, number>>({});
  const [comment, setComment] = useState('');
  const [error, setError] = useState('');

  const loadFeatures = useCallback(async () => {
    try {
      const res = await fetch(`${API_BASE}/public/companies/${companyId}`)
      const company = await res.json();
      const catId = company?.data?.category?.id;
      if (!catId) return;

      const fRes = await fetch(`${API_BASE}/public/categories/${catId}/features`);
      const fData = await fRes.json();
      if (fData?.data) setFeatures(fData.data);
    } catch {
      // ignore
    }
  }, [companyId]);

  useEffect(() => { loadFeatures(); }, [loadFeatures]);

  function handleSubmit() {
    if (!comment.trim()) { setError('Vui lòng nhập nhận xét.'); return; }
    if (features.length > 0 && Object.keys(ratings).length < features.length) {
      setError('Vui lòng đánh giá tất cả tiêu chí.');
      return;
    }
    startTransition(async () => {
      const res = await submitReview(appointmentId, ratings, comment);
      if (!res.ok) setError(res.error);
      else router.refresh();
    });
  }

  return (
    <div className="rounded-2xl bg-surface-container-lowest p-8">
      <h2 className="mb-6 font-headline text-xl font-bold text-on-surface">
        Đánh giá dịch vụ
      </h2>

      {error && (
        <p className="mb-4 rounded-xl bg-error-container p-3 text-sm text-error">{error}</p>
      )}

      {features.length > 0 && (
        <div className="mb-6 space-y-4">
          {features.map((f) => (
            <div key={f.id} className="flex items-center justify-between">
              <span className="text-on-surface">{f.name}</span>
              <div className="flex gap-1">
                {[1, 2, 3, 4, 5].map((star) => (
                  <button
                    key={star}
                    type="button"
                    onClick={() => setRatings((r) => ({ ...r, [f.id]: star }))}
                    className={`text-2xl ${
                      (ratings[f.id] ?? 0) >= star ? 'text-yellow-500' : 'text-gray-300'
                    }`}
                  >
                    ★
                  </button>
                ))}
              </div>
            </div>
          ))}
        </div>
      )}

      <textarea
        rows={3}
        value={comment}
        onChange={(e) => setComment(e.target.value)}
        placeholder="Nhận xét của bạn về dịch vụ..."
        className="mb-4 w-full rounded-xl border-none bg-surface-container-low px-4 py-3 text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30"
      />

      <button
        type="button"
        onClick={handleSubmit}
        disabled={isPending}
        className="rounded-xl bg-primary px-6 py-3 font-bold text-on-primary transition-all hover:opacity-90 disabled:opacity-50"
      >
        {isPending ? 'Đang gửi...' : 'Gửi đánh giá'}
      </button>
    </div>
  );
}
