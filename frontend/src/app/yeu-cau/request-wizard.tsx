'use client';

import { useCallback, useEffect, useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import type { Route } from 'next';
import type { AuthUser, PublicCategory } from '@/lib/api-types';
import { categoryStyle } from '@/lib/category-style';
import { TrustBadges } from '@/components/trust-badges';
import { createServiceRequestAction } from './actions';
import { LocationPicker } from './location-picker';

interface WizardState {
  categoryId: number | null;
  title: string;
  description: string;
  images: File[];
  cityCode: string;
  cityName: string;
  districtCode: string;
  districtName: string;
  wardCode: string;
  wardName: string;
  address: string;
  phone: string;
  name: string;
}

const TOTAL_STEPS = 4;

export function RequestWizard({
  categories,
  user,
}: {
  categories: PublicCategory[];
  /** null = khách chưa đăng nhập — vẫn cho điền form, gate ở bước gửi (server action trả needsLogin). */
  user: AuthUser | null;
}) {
  const router = useRouter();
  const [step, setStep] = useState(1);
  const [isPending, startTransition] = useTransition();
  const [submitError, setSubmitError] = useState<string | null>(null);

  const [state, setState] = useState<WizardState>({
    categoryId: null,
    title: '',
    description: '',
    images: [],
    cityCode: '',
    cityName: user?.location?.city ?? '',
    districtCode: '',
    districtName: user?.location?.district ?? '',
    wardCode: '',
    wardName: user?.location?.ward ?? '',
    address: user?.location?.address ?? '',
    phone: user?.mobile ?? '',
    name: user?.name ?? '',
  });
  const [imagePreviews, setImagePreviews] = useState<string[]>([]);

  function patch(p: Partial<WizardState>) {
    setState((s) => ({ ...s, ...p }));
  }

  const handleImages = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.target.files ?? []).slice(0, 5);
    setState((s) => ({ ...s, images: [...s.images, ...files].slice(0, 5) }));
  }, []);

  useEffect(() => {
    const urls = state.images.map((f) => URL.createObjectURL(f));
    setImagePreviews(urls);
    return () => urls.forEach(URL.revokeObjectURL);
  }, [state.images]);

  function removeImage(idx: number) {
    setState((s) => ({ ...s, images: s.images.filter((_, i) => i !== idx) }));
  }

  function next() {
    if (step < TOTAL_STEPS) setStep(step + 1);
    else submit();
  }

  function prev() {
    if (step > 1) setStep(step - 1);
    else router.push('/');
  }

  function submit() {
    if (!state.categoryId || !state.cityName) return;
    setSubmitError(null);
    const description = state.description.trim();
    startTransition(async () => {
      // Tiêu đề là TUỲ CHỌN: dưới 5 ký tự (rule backend) thì tự lấy mô tả làm
      // tiêu đề — người dùng không bao giờ gặp lỗi "tối thiểu 5 ký tự" ở bước 4.
      const trimmedTitle = state.title.trim();
      const result = await createServiceRequestAction({
        category_id: state.categoryId!,
        title: (trimmedTitle.length >= 5 ? trimmedTitle : description.slice(0, 60)) as string,
        description,
        city: state.cityName,
        district: state.districtName || undefined,
        ward: state.wardName || undefined,
        address: state.address.trim() || undefined,
        contact_name: state.name.trim(),
        contact_phone: state.phone.trim(),
      });

      if (!result.ok) {
        if (result.needsLogin) {
          router.push('/login');
          return;
        }
        setSubmitError(result.error);
        return;
      }

      router.push(`/yeu-cau/ket-qua/${result.id}` as Route);
    });
  }

  const canContinue =
    (step === 1 && state.categoryId !== null) ||
    (step === 2 && state.description.trim().length >= 20) ||
    (step === 3 && Boolean(state.cityName)) ||
    (step === 4 && state.phone.trim().length >= 9 && state.name.trim().length > 0);

  return (
    <div className="min-h-screen bg-surface px-6 pb-20 pt-12">
      <div className="mx-auto max-w-[800px]">
        {/* Progress */}
        <div className="mb-12 flex items-center gap-2">
          {Array.from({ length: TOTAL_STEPS }).map((_, i) => (
            <div
              key={i}
              className={
                i + 1 <= step
                  ? 'h-1.5 flex-1 rounded-full bg-primary'
                  : 'h-1.5 flex-1 rounded-full bg-surface-container-high'
              }
            />
          ))}
        </div>

        {/* Step 1: Category */}
        {step === 1 && (
          <StepShell title="Bạn cần giúp đỡ về việc gì?" subtitle="Chọn loại dịch vụ phù hợp nhất.">
            {/* Mobile: 2 cột compact — trước đây 1 cột, mỗi thẻ ~250px khiến
                bước chọn nghề dài hơn 4 màn hình. Desktop giữ nguyên độ thoáng. */}
            <div className="mb-10 grid grid-cols-2 gap-3 md:mb-20 md:gap-6">
              {categories.map((cat) => {
                const active = state.categoryId === cat.id;
                const style = categoryStyle(cat.name);
                return (
                  <button
                    key={cat.id}
                    type="button"
                    onClick={() => patch({ categoryId: cat.id })}
                    className={`group flex flex-col items-start rounded-2xl border-2 bg-surface-container-lowest p-4 text-left transition-all active:scale-[0.98] md:rounded-3xl md:p-10 ${
                      active ? 'border-primary shadow-ambient' : 'border-transparent hover:border-outline-variant'
                    }`}
                  >
                    {/* Màu + icon theo NGÀNH NGHỀ — giữ nguyên khi chọn, viền primary báo trạng thái active */}
                    <div className={`mb-3 flex h-10 w-10 items-center justify-center rounded-full md:mb-8 md:h-16 md:w-16 ${style.chip}`}>
                      <span className="material-symbols-outlined text-xl md:text-2xl">{style.icon}</span>
                    </div>
                    <span className="block font-headline text-sm font-bold leading-snug text-on-surface md:mb-2 md:text-2xl">{cat.name}</span>
                  </button>
                );
              })}
            </div>
          </StepShell>
        )}

        {/* Step 2: Description + Images */}
        {step === 2 && (
          <StepShell title="Hãy mô tả chi tiết hơn." subtitle="Càng nhiều ngữ cảnh, thợ càng báo giá chính xác.">
            <div className="mb-20 space-y-8">
              <div>
                <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
                  Tiêu đề ngắn (tuỳ chọn)
                </label>
                <input
                  type="text"
                  value={state.title}
                  onChange={(e) => patch({ title: e.target.value })}
                  placeholder="Ví dụ: ổ cắm phòng ngủ bị tê"
                  className="h-16 w-full border-x-0 border-b-4 border-t-0 border-surface-container-high bg-transparent px-0 text-2xl font-medium text-on-surface placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-0"
                />
                {state.title.trim().length > 0 && state.title.trim().length < 5 ? (
                  <p className="mt-2 text-sm text-outline">
                    Tiêu đề ngắn quá (cần ≥5 ký tự) — nếu để vậy, hệ thống sẽ tự dùng phần mô tả làm tiêu đề.
                  </p>
                ) : null}
              </div>
              <div>
                <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
                  Mô tả chi tiết
                </label>
                <textarea
                  rows={5}
                  value={state.description}
                  onChange={(e) => patch({ description: e.target.value })}
                  placeholder="Mô tả tình trạng hiện tại, số lượng thiết bị, mức độ gấp..."
                  className="w-full rounded-2xl border-none bg-surface-container-low px-6 py-4 text-lg text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-primary/30"
                />
                <p className="mt-2 text-sm text-outline">Tối thiểu 20 ký tự ({state.description.trim().length})</p>
              </div>
              <div>
                <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
                  Hình ảnh minh hoạ (tối đa 5)
                </label>
                <div className="grid grid-cols-3 gap-4 md:grid-cols-5">
                  {imagePreviews.map((url, idx) => (
                    <div key={idx} className="group relative aspect-square overflow-hidden rounded-2xl ring-1 ring-outline-variant">
                      {/* eslint-disable-next-line @next/next/no-img-element */}
                      <img src={url} alt={`Ảnh ${idx + 1}`} className="h-full w-full object-cover" />
                      <button
                        type="button"
                        onClick={() => removeImage(idx)}
                        className="absolute right-1 top-1 flex h-6 w-6 items-center justify-center rounded-full bg-black/50 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100"
                      >
                        ✕
                      </button>
                    </div>
                  ))}
                  {state.images.length < 5 && (
                    <label className="flex aspect-square cursor-pointer flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-outline-variant bg-surface-container-low transition-colors hover:bg-surface-container">
                      <span className="material-symbols-outlined text-outline">add_a_photo</span>
                      <span className="text-[10px] font-bold uppercase text-outline">Tải ảnh lên</span>
                      <input
                        type="file"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        multiple
                        onChange={handleImages}
                        className="sr-only"
                      />
                    </label>
                  )}
                </div>
                <p className="mt-2 text-xs text-outline">Hình ảnh sẽ được gửi kèm ở bước sau (chưa upload trong MVP).</p>
              </div>
            </div>
          </StepShell>
        )}

        {/* Step 3: Location cascade */}
        {step === 3 && (
          <StepShell title="Bạn ở đâu?" subtitle="Để chúng tôi ưu tiên thợ trong khu vực gần nhất.">
            <div className="mb-20">
              <LocationPicker
                cityCode={state.cityCode}
                districtCode={state.districtCode}
                wardCode={state.wardCode}
                address={state.address}
                onChange={(loc) => patch(loc)}
              />
            </div>
          </StepShell>
        )}

        {/* Step 4: Contact */}
        {step === 4 && (
          <StepShell title="Liên hệ của bạn?" subtitle="Thợ sẽ gọi cho bạn để xác nhận chi tiết.">
            <div className="mb-20 space-y-8">
              <div>
                <label className="mb-4 block font-headline text-xl font-bold text-on-surface">Họ tên</label>
                <input
                  type="text"
                  value={state.name}
                  onChange={(e) => patch({ name: e.target.value })}
                  placeholder="Nguyễn Văn A"
                  className="h-16 w-full border-x-0 border-b-4 border-t-0 border-surface-container-high bg-transparent px-0 text-2xl font-medium text-on-surface placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-0"
                />
              </div>
              <div>
                <label className="mb-4 block font-headline text-xl font-bold text-on-surface">Số điện thoại</label>
                <input
                  type="tel"
                  value={state.phone}
                  onChange={(e) => patch({ phone: e.target.value })}
                  placeholder="090x xxx xxx"
                  className="h-16 w-full border-x-0 border-b-4 border-t-0 border-surface-container-high bg-transparent px-0 text-2xl font-medium text-on-surface placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-0"
                />
              </div>
              {submitError && (
                <div className="rounded-2xl bg-error-container px-6 py-4 text-on-error-container">
                  {submitError}
                </div>
              )}
            </div>
          </StepShell>
        )}

        {/* Actions */}
        <div className="flex items-center justify-between gap-6">
          <button
            type="button"
            onClick={prev}
            disabled={isPending}
            className="flex h-14 items-center gap-2 rounded-lg px-8 font-headline text-lg font-bold text-primary transition-all hover:bg-primary/5 active:scale-95 disabled:opacity-30"
          >
            <span className="material-symbols-outlined text-2xl">arrow_back</span>
            {step === 1 ? 'Huỷ' : 'Quay lại'}
          </button>
          <button
            type="button"
            onClick={next}
            disabled={!canContinue || isPending}
            className="flex h-14 items-center gap-3 rounded-lg bg-primary px-12 font-headline text-lg font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-30"
          >
            {step === TOTAL_STEPS ? (isPending ? 'Đang gửi…' : 'Gửi yêu cầu') : 'Tiếp tục'}
            <span className="material-symbols-outlined text-2xl">arrow_forward</span>
          </button>
        </div>

        {/* Trấn an tin cậy khi điền yêu cầu */}
        <div className="mt-12 border-t border-outline-variant/20 pt-8">
          <TrustBadges variant="strip" className="justify-center" />
        </div>
      </div>
    </div>
  );
}

function StepShell({ title, subtitle, children }: { title: string; subtitle: string; children: React.ReactNode }) {
  return (
    <>
      <div className="mb-16">
        <h1 className="mb-4 font-headline text-5xl font-bold leading-[1.1] text-on-surface md:text-[3.5rem]">{title}</h1>
        <p className="text-xl font-medium text-on-surface-variant">{subtitle}</p>
      </div>
      {children}
    </>
  );
}
