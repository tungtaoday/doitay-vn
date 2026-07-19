'use client';

import { useRouter } from 'next/navigation';
import { useState, useTransition } from 'react';
import { cancelAppointment, submitReview } from '../actions';
import type { RatingFeature } from '@/lib/api-types';

export function AppointmentActions({
  appointmentId,
  status,
  hasRating,
  features,
}: {
  appointmentId: number;
  status: string;
  hasRating: boolean;
  /** Tiêu chí đánh giá theo nghề — tải sẵn ở server (page.tsx), không fetch client. */
  features: RatingFeature[];
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
        <p className="rounded-xl bg-error-container px-5 py-4 text-sm font-medium text-on-error-container">
          {error}
        </p>
      )}

      {status === 'pending' && (
        <button
          type="button"
          onClick={handleCancel}
          disabled={isPending}
          className="inline-flex min-h-[48px] items-center gap-2 rounded-xl border border-error/30 px-6 font-headline font-bold text-error transition-colors hover:bg-error-container/40 disabled:opacity-50"
        >
          <span className="material-symbols-outlined text-[1.25rem]">event_busy</span>
          {isPending ? 'Đang xử lý…' : 'Huỷ lịch hẹn'}
        </button>
      )}

      {status === 'completed' && !hasRating && (
        <ReviewForm appointmentId={appointmentId} features={features} />
      )}

      {hasRating && (
        <p className="inline-flex items-center gap-2 rounded-xl bg-primary-container px-5 py-3.5 text-sm font-bold text-on-primary-container">
          <span className="material-symbols-outlined text-[1.25rem]" style={{ fontVariationSettings: "'FILL' 1" }}>
            check_circle
          </span>
          Bạn đã đánh giá lịch hẹn này. Cảm ơn bạn!
        </p>
      )}
    </div>
  );
}

function ReviewForm({
  appointmentId,
  features,
}: {
  appointmentId: number;
  features: RatingFeature[];
}) {
  const router = useRouter();
  const [isPending, startTransition] = useTransition();
  const [ratings, setRatings] = useState<Record<number, number>>({});
  const [comment, setComment] = useState('');
  const [error, setError] = useState('');

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
    <div className="rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
      <h2 className="mb-1 font-headline text-xl font-bold text-on-surface">Đánh giá dịch vụ</h2>
      <p className="mb-6 text-sm text-on-surface-variant">
        Đánh giá của bạn giúp thợ tốt được nhiều khách biết đến hơn.
      </p>

      {error && (
        <p className="mb-4 rounded-xl bg-error-container px-4 py-3 text-sm font-medium text-on-error-container">
          {error}
        </p>
      )}

      {features.length > 0 && (
        <div className="mb-6 divide-y divide-outline-variant/10">
          {features.map((f) => (
            <div key={f.id} className="flex items-center justify-between gap-4 py-3 first:pt-0">
              <span className="font-medium text-on-surface">{f.name}</span>
              <div className="flex" role="radiogroup" aria-label={`Chấm điểm ${f.name}`}>
                {[1, 2, 3, 4, 5].map((star) => {
                  const active = (ratings[f.id] ?? 0) >= star;
                  return (
                    <button
                      key={star}
                      type="button"
                      role="radio"
                      aria-checked={(ratings[f.id] ?? 0) === star}
                      aria-label={`${star} sao`}
                      onClick={() => setRatings((r) => ({ ...r, [f.id]: star }))}
                      className="flex h-11 w-9 items-center justify-center transition-transform active:scale-90"
                    >
                      <span
                        className={`material-symbols-outlined text-[1.75rem] transition-colors ${
                          active ? 'text-tertiary' : 'text-outline-variant'
                        }`}
                        style={{ fontVariationSettings: active ? "'FILL' 1" : "'FILL' 0" }}
                      >
                        star
                      </span>
                    </button>
                  );
                })}
              </div>
            </div>
          ))}
        </div>
      )}

      <label htmlFor="review-comment" className="mb-2 block text-sm font-bold text-on-surface">
        Nhận xét của bạn
      </label>
      <textarea
        id="review-comment"
        rows={3}
        value={comment}
        onChange={(e) => setComment(e.target.value)}
        placeholder="Thợ làm việc thế nào? Đúng giờ, đúng giá không?"
        className="mb-5 w-full rounded-xl border-none bg-surface-container-low px-4 py-3 text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30"
      />

      <button
        type="button"
        onClick={handleSubmit}
        disabled={isPending}
        className="inline-flex min-h-[48px] items-center gap-2 rounded-xl bg-primary px-7 font-headline font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-50"
      >
        <span className="material-symbols-outlined text-[1.25rem]">send</span>
        {isPending ? 'Đang gửi…' : 'Gửi đánh giá'}
      </button>
    </div>
  );
}
