'use client';

import { useActionState, useState, useEffect, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import {
  completeProfileAction,
  fetchCities,
  fetchDistricts,
  fetchWards,
  type ProfileResult,
} from './actions';
import type { LocationItem } from '@/lib/api-types';

const STEPS = ['Thông tin cá nhân', 'Địa chỉ', 'Xác nhận'] as const;

export function ProfileForm({
  initialCities,
  defaultPhone = '',
  defaultRegisterAsExpert = false,
}: {
  initialCities: LocationItem[];
  defaultPhone?: string;
  defaultRegisterAsExpert?: boolean;
}) {
  const router = useRouter();
  const [step, setStep] = useState(0);
  const [state, formAction, isPending] = useActionState<ProfileResult | null, FormData>(
    completeProfileAction,
    null,
  );

  const [cities] = useState(initialCities);
  const [districts, setDistricts] = useState<LocationItem[]>([]);
  const [wards, setWards] = useState<LocationItem[]>([]);
  const [, startTransition] = useTransition();

  const [cityCode, setCityCode] = useState('');
  const [districtCode, setDistrictCode] = useState('');
  const [wardCode, setWardCode] = useState('');

  const [username, setUsername] = useState('');
  const [mobile, setMobile] = useState(defaultPhone);
  const [address, setAddress] = useState('');
  const [registerAsExpert, setRegisterAsExpert] = useState(defaultRegisterAsExpert);

  useEffect(() => {
    if (state?.ok) {
      if (state.registerAsExpert) {
        router.replace('/vi/tho/dang-ky');
      } else {
        router.replace('/vi/lich-hen');
      }
    }
  }, [state, router]);

  function handleCityChange(code: string) {
    setCityCode(code);
    setDistrictCode('');
    setWardCode('');
    setWards([]);
    if (code) {
      startTransition(async () => {
        const data = await fetchDistricts(code);
        setDistricts(data);
      });
    } else {
      setDistricts([]);
    }
  }

  function handleDistrictChange(code: string) {
    setDistrictCode(code);
    setWardCode('');
    if (code) {
      startTransition(async () => {
        const data = await fetchWards(code);
        setWards(data);
      });
    } else {
      setWards([]);
    }
  }

  const canGoStep2 = username.length >= 6 && /^[0-9]{10,11}$/.test(mobile);
  const canGoStep3 = cityCode && districtCode && wardCode && address.length > 0;

  return (
    <div className="space-y-8">
      {/* Step indicator */}
      <div className="flex items-center justify-center gap-3">
        {STEPS.map((label, i) => (
          <div key={label} className="flex items-center gap-3">
            <div
              className={`flex h-10 w-10 items-center justify-center rounded-full text-[1rem] font-bold ${
                i <= step
                  ? 'bg-primary text-on-primary'
                  : 'bg-surface-container-low text-secondary'
              }`}
            >
              {i + 1}
            </div>
            <span
              className={`hidden text-[1rem] sm:inline ${
                i <= step ? 'font-bold text-on-surface' : 'font-medium text-secondary'
              }`}
            >
              {label}
            </span>
            {i < STEPS.length - 1 && (
              <div className="mx-2 h-[2px] w-8 bg-surface-container-highest" />
            )}
          </div>
        ))}
      </div>

      {state && !state.ok && (
        <div className="rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
          {state.error}
        </div>
      )}

      <form action={formAction}>
        {/* Hidden fields for all steps */}
        <input type="hidden" name="username" value={username} />
        <input type="hidden" name="mobile" value={mobile} />
        <input type="hidden" name="city_code" value={cityCode} />
        <input type="hidden" name="district_code" value={districtCode} />
        <input type="hidden" name="ward_code" value={wardCode} />
        <input type="hidden" name="address" value={address} />
        {registerAsExpert && <input type="hidden" name="register_as_expert" value="on" />}

        {/* Step 1: Username + Phone */}
        {step === 0 && (
          <div className="space-y-8">
            <div className="space-y-3">
              <label htmlFor="pf-username" className="block text-[1.25rem] font-bold text-on-surface">
                Tên đăng nhập <span className="text-secondary">(username ít nhất 6 ký tự)</span>
              </label>
              <input
                id="pf-username"
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                minLength={6}
                placeholder="vd: nguyenvana"
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
              />
              {state && !state.ok && state.fieldErrors?.username && (
                <p className="text-[0.875rem] font-medium text-error">{state.fieldErrors.username}</p>
              )}
            </div>

            <div className="space-y-3">
              <label htmlFor="pf-mobile" className="block text-[1.25rem] font-bold text-on-surface">
                Số điện thoại
              </label>
              <input
                id="pf-mobile"
                type="tel"
                value={mobile}
                onChange={(e) => setMobile(e.target.value.replace(/\D/g, ''))}
                maxLength={11}
                placeholder="0901234567"
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
              />
              {defaultPhone && mobile === defaultPhone && (
                <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ đăng ký</p>
              )}
              {state && !state.ok && state.fieldErrors?.mobile && (
                <p className="text-[0.875rem] font-medium text-error">{state.fieldErrors.mobile}</p>
              )}
            </div>

            <button
              type="button"
              disabled={!canGoStep2}
              onClick={() => setStep(1)}
              className="flex h-[72px] w-full items-center justify-center gap-3 rounded-lg border-none bg-primary text-[1.375rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
            >
              <span>Tiếp theo</span>
              <span className="material-symbols-outlined">arrow_forward</span>
            </button>
          </div>
        )}

        {/* Step 2: Location */}
        {step === 1 && (
          <div className="space-y-8">
            <div className="space-y-3">
              <label htmlFor="pf-city" className="block text-[1.25rem] font-bold text-on-surface">
                Tỉnh / Thành phố
              </label>
              <select
                id="pf-city"
                value={cityCode}
                onChange={(e) => handleCityChange(e.target.value)}
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary"
              >
                <option value="">— Chọn tỉnh/thành —</option>
                {cities.map((c) => (
                  <option key={c.code} value={c.code}>
                    {c.name}
                  </option>
                ))}
              </select>
            </div>

            <div className="space-y-3">
              <label htmlFor="pf-district" className="block text-[1.25rem] font-bold text-on-surface">
                Quận / Huyện
              </label>
              <select
                id="pf-district"
                value={districtCode}
                onChange={(e) => handleDistrictChange(e.target.value)}
                disabled={!cityCode}
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60"
              >
                <option value="">— Chọn quận/huyện —</option>
                {districts.map((d) => (
                  <option key={d.code} value={d.code}>
                    {d.name}
                  </option>
                ))}
              </select>
            </div>

            <div className="space-y-3">
              <label htmlFor="pf-ward" className="block text-[1.25rem] font-bold text-on-surface">
                Phường / Xã
              </label>
              <select
                id="pf-ward"
                value={wardCode}
                onChange={(e) => setWardCode(e.target.value)}
                disabled={!districtCode}
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60"
              >
                <option value="">— Chọn phường/xã —</option>
                {wards.map((w) => (
                  <option key={w.code} value={w.code}>
                    {w.name}
                  </option>
                ))}
              </select>
            </div>

            <div className="space-y-3">
              <label htmlFor="pf-address" className="block text-[1.25rem] font-bold text-on-surface">
                Địa chỉ cụ thể
              </label>
              <input
                id="pf-address"
                type="text"
                value={address}
                onChange={(e) => setAddress(e.target.value)}
                maxLength={255}
                placeholder="Số nhà, đường..."
                className="h-[72px] w-full rounded-lg border-none bg-surface-container-low px-6 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary"
              />
            </div>

            <div className="flex gap-4">
              <button
                type="button"
                onClick={() => setStep(0)}
                className="flex h-[72px] flex-1 items-center justify-center gap-3 rounded-lg border-none bg-surface-container-low text-[1.125rem] font-bold text-on-surface transition-all hover:bg-surface-container-highest active:scale-[0.98]"
              >
                <span className="material-symbols-outlined">arrow_back</span>
                <span>Quay lại</span>
              </button>
              <button
                type="button"
                disabled={!canGoStep3}
                onClick={() => setStep(2)}
                className="flex h-[72px] flex-1 items-center justify-center gap-3 rounded-lg border-none bg-primary text-[1.125rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
              >
                <span>Tiếp theo</span>
                <span className="material-symbols-outlined">arrow_forward</span>
              </button>
            </div>
          </div>
        )}

        {/* Step 3: Summary + Expert checkbox */}
        {step === 2 && (
          <div className="space-y-8">
            <div className="rounded-lg bg-surface-container-low p-8">
              <h3 className="mb-6 text-[1.375rem] font-bold text-on-surface">Thông tin của bạn</h3>
              <dl className="space-y-4 text-[1.125rem]">
                <div className="flex justify-between">
                  <dt className="text-secondary">Tên đăng nhập</dt>
                  <dd className="font-bold text-on-surface">{username}</dd>
                </div>
                <div className="flex justify-between">
                  <dt className="text-secondary">Số điện thoại</dt>
                  <dd className="font-bold text-on-surface">{mobile}</dd>
                </div>
                <div className="flex justify-between">
                  <dt className="text-secondary">Tỉnh/Thành</dt>
                  <dd className="font-bold text-on-surface">
                    {cities.find((c) => c.code === cityCode)?.name}
                  </dd>
                </div>
                <div className="flex justify-between">
                  <dt className="text-secondary">Quận/Huyện</dt>
                  <dd className="font-bold text-on-surface">
                    {districts.find((d) => d.code === districtCode)?.name}
                  </dd>
                </div>
                <div className="flex justify-between">
                  <dt className="text-secondary">Phường/Xã</dt>
                  <dd className="font-bold text-on-surface">
                    {wards.find((w) => w.code === wardCode)?.name}
                  </dd>
                </div>
                <div className="flex justify-between">
                  <dt className="text-secondary">Địa chỉ</dt>
                  <dd className="font-bold text-on-surface">{address}</dd>
                </div>
              </dl>
            </div>

            <label className="flex cursor-pointer items-start gap-4 rounded-lg bg-surface-container-low p-6 transition-colors hover:bg-surface-container-highest">
              <input
                type="checkbox"
                checked={registerAsExpert}
                onChange={(e) => setRegisterAsExpert(e.target.checked)}
                className="mt-1 h-6 w-6 rounded border-outline text-primary focus:ring-primary"
              />
              <div>
                <span className="text-[1.125rem] font-bold text-on-surface">
                  Tôi muốn đăng ký làm thợ / chuyên gia
                </span>
                <p className="mt-2 text-[1rem] text-secondary">
                  Bạn sẽ được tạo hồ sơ công ty để nhận đơn hàng từ khách hàng
                </p>
              </div>
            </label>

            <div className="flex gap-4">
              <button
                type="button"
                onClick={() => setStep(1)}
                className="flex h-[72px] flex-1 items-center justify-center gap-3 rounded-lg border-none bg-surface-container-low text-[1.125rem] font-bold text-on-surface transition-all hover:bg-surface-container-highest active:scale-[0.98]"
              >
                <span className="material-symbols-outlined">arrow_back</span>
                <span>Quay lại</span>
              </button>
              <button
                type="submit"
                disabled={isPending}
                className="flex h-[72px] flex-1 items-center justify-center gap-3 rounded-lg border-none bg-primary text-[1.375rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
              >
                <span>{isPending ? 'Đang lưu...' : 'Hoàn tất'}</span>
                {!isPending && (
                  <span className="material-symbols-outlined">check</span>
                )}
              </button>
            </div>
          </div>
        )}
      </form>
    </div>
  );
}
