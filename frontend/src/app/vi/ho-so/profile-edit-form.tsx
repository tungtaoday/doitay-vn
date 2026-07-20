'use client';

import { useActionState, useEffect, useRef, useState, useTransition } from 'react';
import {
  fetchDistricts,
  fetchWards,
} from '@/app/vi/hoan-thanh-ho-so/actions';
import { updateProfileAction, uploadAvatarAction, type UpdateProfileResult, type UploadAvatarResult } from './actions';
import type { AuthUser, LocationItem } from '@/lib/api-types';
import { UserAvatar } from '@/components/user-avatar';

export function ProfileEditForm({
  user,
  cities,
  initialDistricts,
  initialWards,
}: {
  user: AuthUser;
  cities: LocationItem[];
  initialDistricts: LocationItem[];
  initialWards: LocationItem[];
}) {
  const [state, formAction, isPending] = useActionState<UpdateProfileResult | null, FormData>(
    updateProfileAction,
    null,
  );
  const [avatarState, avatarFormAction, isAvatarPending] = useActionState<UploadAvatarResult | null, FormData>(
    uploadAvatarAction,
    null,
  );
  const [, startTransition] = useTransition();
  const [saved, setSaved] = useState(false);
  const [avatarSaved, setAvatarSaved] = useState(false);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);
  const [avatarError, setAvatarError] = useState<string | null>(null);
  const avatarInputRef = useRef<HTMLInputElement>(null);

  // Fields
  const [name, setName] = useState(user.name);
  const [username, setUsername] = useState(user.username ?? '');
  const [mobile, setMobile] = useState(user.mobile ?? '');
  const [about, setAbout] = useState('');

  // Location
  const [cityCode, setCityCode] = useState('');
  const [districtCode, setDistrictCode] = useState('');
  const [wardCode, setWardCode] = useState('');
  const [address, setAddress] = useState(user.location.address ?? '');
  const [districts, setDistricts] = useState<LocationItem[]>(initialDistricts);
  const [wards, setWards] = useState<LocationItem[]>(initialWards);

  // Resolve initial city/district/ward codes from display names
  useEffect(() => {
    const city = cities.find((c) => c.name === user.location.city);
    if (city) setCityCode(city.code);
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => {
    if (state?.ok) {
      setSaved(true);
      setTimeout(() => setSaved(false), 3000);
    }
  }, [state]);

  useEffect(() => {
    if (avatarState?.ok) {
      setAvatarSaved(true);
      setAvatarPreview(null);
      setTimeout(() => setAvatarSaved(false), 3000);
    } else if (avatarState && !avatarState.ok) {
      setAvatarError(avatarState.error);
    }
  }, [avatarState]);

  function handleAvatarFile(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      setAvatarError('Ảnh tối đa 2MB');
      e.target.value = '';
      return;
    }
    setAvatarError(null);
    setAvatarPreview(URL.createObjectURL(file));
  }

  function handleCityChange(code: string) {
    setCityCode(code);
    setDistrictCode('');
    setWardCode('');
    setWards([]);
    if (code) {
      startTransition(async () => setDistricts(await fetchDistricts(code)));
    } else {
      setDistricts([]);
    }
  }

  function handleDistrictChange(code: string) {
    setDistrictCode(code);
    setWardCode('');
    if (code) {
      startTransition(async () => setWards(await fetchWards(code)));
    } else {
      setWards([]);
    }
  }

  const fieldError = (key: string) =>
    state && !state.ok ? state.fieldErrors?.[key] : undefined;

  return (
    <form action={formAction} className="space-y-10">
      {/* Hidden fields */}
      <input type="hidden" name="name" value={name} />
      <input type="hidden" name="username" value={username} />
      <input type="hidden" name="mobile" value={mobile} />
      <input type="hidden" name="about" value={about} />
      <input type="hidden" name="city_code" value={cityCode} />
      <input type="hidden" name="district_code" value={districtCode} />
      <input type="hidden" name="ward_code" value={wardCode} />
      <input type="hidden" name="address" value={address} />

      {/* Error banner */}
      {state && !state.ok && (
        <div className="rounded-xl bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
          {state.error}
        </div>
      )}

      {/* Success banner */}
      {saved && (
        <div className="flex items-center gap-2 rounded-xl bg-primary/10 px-6 py-4 text-[1rem] font-bold text-primary">
          <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>check_circle</span>
          Đã lưu thay đổi thành công
        </div>
      )}

      {/* === Section: Avatar === */}
      <div className="rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
        <h3 className="mb-6 flex items-center gap-3 font-headline text-xl font-bold text-on-surface">
          <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10"><span className="material-symbols-outlined text-[1.25rem] text-primary">photo_camera</span></span>
          Ảnh đại diện
        </h3>

        <div className="flex items-center gap-6">
          {/* Preview */}
          <div className="relative h-20 w-20 shrink-0">
            {avatarPreview ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={avatarPreview} alt="Preview" className="h-20 w-20 rounded-2xl object-cover" />
            ) : (
              <UserAvatar
                name={user.name}
                userId={user.id}
                avatarUrl={user.avatar}
                sizeClass="h-20 w-20"
                roundedClass="rounded-2xl"
                fontClass="text-2xl"
              />
            )}
            {/* Camera overlay */}
            <button
              type="button"
              onClick={() => avatarInputRef.current?.click()}
              className="absolute inset-0 flex items-center justify-center rounded-2xl bg-black/30 opacity-0 transition-opacity hover:opacity-100"
            >
              <span className="material-symbols-outlined text-[1.5rem] text-white">photo_camera</span>
            </button>
          </div>

          {/* Upload form */}
          <form action={avatarFormAction} className="flex-1">
            <input
              ref={avatarInputRef}
              type="file"
              name="avatar"
              accept="image/jpeg,image/png,image/jpg,image/webp"
              onChange={handleAvatarFile}
              className="hidden"
            />
            <p className="text-[0.875rem] font-medium text-on-surface">
              {user.avatar ? 'Thay đổi ảnh đại diện' : 'Thêm ảnh đại diện'}
            </p>
            <p className="mt-1 text-[0.75rem] text-secondary">
              JPG, PNG, WebP — tối đa 2MB. Tỉ lệ vuông sẽ đẹp nhất.
            </p>

            {avatarError && (
              <p className="mt-2 text-[0.75rem] text-error">{avatarError}</p>
            )}
            {avatarSaved && (
              <p className="mt-2 flex items-center gap-1 text-[0.75rem] font-bold text-primary">
                <span className="material-symbols-outlined text-[1rem]" style={{ fontVariationSettings: "'FILL' 1" }}>check_circle</span>
                Ảnh đã cập nhật
              </p>
            )}

            <div className="mt-3 flex gap-3">
              <button
                type="button"
                onClick={() => avatarInputRef.current?.click()}
                className="flex items-center gap-2 rounded-xl border border-outline-variant/30 bg-surface-container-low px-4 py-2.5 text-[0.875rem] font-bold text-on-surface transition-colors hover:bg-surface-container"
              >
                <span className="material-symbols-outlined text-[1rem]">upload</span>
                Chọn ảnh
              </button>
              {avatarPreview && (
                <button
                  type="submit"
                  disabled={isAvatarPending}
                  className="flex min-h-[44px] items-center gap-2 rounded-xl bg-primary px-4 text-sm font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-60"
                >
                  <span className="material-symbols-outlined text-[1.125rem]">check</span>
                  {isAvatarPending ? 'Đang tải…' : 'Lưu ảnh'}
                </button>
              )}
            </div>
          </form>
        </div>
      </div>

      {/* === Section: Thông tin cá nhân === */}
      <div className="rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
        <h3 className="mb-6 flex items-center gap-3 font-headline text-xl font-bold text-on-surface">
          <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10"><span className="material-symbols-outlined text-[1.25rem] text-primary">person</span></span>
          Thông tin cá nhân
        </h3>
        <div className="space-y-6">
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            {/* Họ tên */}
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Họ và Tên</label>
              <input
                aria-label="Họ và Tên"
                type="text"
                value={name}
                onChange={(e) => setName(e.target.value)}
                maxLength={255}
                className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary/30"
              />
              {fieldError('name') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('name')}</p>}
            </div>

            {/* Tên đăng nhập */}
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">
                Tên đăng nhập <span className="font-normal text-secondary">(username)</span>
              </label>
              <input
                aria-label="Tên đăng nhập"
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value.replace(/[^a-z0-9_-]/gi, '').toLowerCase())}
                maxLength={50}
                placeholder="vd: nguyenvana"
                className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary/30"
              />
              <p className="mt-1 text-[0.75rem] text-secondary">Chỉ dùng chữ thường, số, gạch ngang, gạch dưới. Tối thiểu 6 ký tự.</p>
              {fieldError('username') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('username')}</p>}
            </div>
          </div>

          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            {/* Số điện thoại */}
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Số điện thoại</label>
              <input
                aria-label="Số điện thoại"
                type="tel"
                value={mobile}
                onChange={(e) => setMobile(e.target.value.replace(/\D/g, ''))}
                maxLength={11}
                placeholder="0901234567"
                className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary/30"
              />
              {fieldError('mobile') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('mobile')}</p>}
            </div>

            {/* Email — read-only (system generated) */}
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Email đăng nhập</label>
              <input
                aria-label="Email đăng nhập"
                type="text"
                value={user.email}
                readOnly
                className="h-14 w-full cursor-not-allowed rounded-xl border-none bg-surface-container-low/50 px-4 text-base font-medium text-secondary outline-none"
              />
              <p className="mt-1 text-[0.75rem] text-secondary">Email được tạo tự động, không thể thay đổi</p>
            </div>
          </div>

          {/* Giới thiệu bản thân */}
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Giới thiệu bản thân</label>
            <textarea
              value={about}
              onChange={(e) => setAbout(e.target.value)}
              rows={3}
              maxLength={1000}
              placeholder="Vài dòng về bạn..."
              className="w-full rounded-xl border-none bg-surface-container-low px-4 py-3 text-[1rem] font-medium leading-relaxed text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary/30"
            />
            <p className="mt-1 text-right text-[0.75rem] text-secondary">{about.length}/1000</p>
          </div>
        </div>
      </div>

      {/* === Section: Địa chỉ === */}
      <div className="rounded-3xl bg-surface-container-lowest p-6 shadow-soft ring-1 ring-outline-variant/10 md:p-8">
        <h3 className="mb-6 flex items-center gap-3 font-headline text-xl font-bold text-on-surface">
          <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10"><span className="material-symbols-outlined text-[1.25rem] text-primary">location_on</span></span>
          Địa chỉ
        </h3>
        <div className="space-y-6">
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Địa chỉ cụ thể</label>
            <input
              aria-label="Địa chỉ cụ thể"
              type="text"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              maxLength={255}
              placeholder="Số nhà, đường..."
              className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary/30"
            />
            {fieldError('address') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('address')}</p>}
          </div>

          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Tỉnh / Thành phố</label>
              <select
                aria-label="Tỉnh / Thành phố"
                value={cityCode}
                onChange={(e) => handleCityChange(e.target.value)}
                className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary/30"
              >
                <option value="">— Chọn tỉnh/thành —</option>
                {cities.map((c) => <option key={c.code} value={c.code}>{c.name}</option>)}
              </select>
              {fieldError('city_code') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('city_code')}</p>}
            </div>

            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Quận / Huyện</label>
              <select
                aria-label="Quận / Huyện"
                value={districtCode}
                onChange={(e) => handleDistrictChange(e.target.value)}
                disabled={!cityCode}
                className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary/30 disabled:opacity-60"
              >
                <option value="">— Chọn quận/huyện —</option>
                {districts.map((d) => <option key={d.code} value={d.code}>{d.name}</option>)}
              </select>
              {fieldError('district_code') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('district_code')}</p>}
            </div>
          </div>

          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Phường / Xã</label>
            <select
              aria-label="Phường / Xã"
              value={wardCode}
              onChange={(e) => setWardCode(e.target.value)}
              disabled={!districtCode}
              className="h-14 w-full rounded-xl border-none bg-surface-container-low px-4 text-base font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary/30 disabled:opacity-60"
            >
              <option value="">— Chọn phường/xã —</option>
              {wards.map((w) => <option key={w.code} value={w.code}>{w.name}</option>)}
            </select>
          </div>
        </div>
      </div>

      {/* Submit */}
      <div className="flex flex-col-reverse items-center gap-3 sm:flex-row sm:justify-end">
        <p className="text-sm text-on-surface-variant sm:mr-auto">
          Thông tin này hiển thị với thợ khi bạn đặt lịch.
        </p>
        <button
          type="submit"
          disabled={isPending}
          className="inline-flex min-h-[52px] w-full items-center justify-center gap-2 rounded-xl bg-primary px-10 font-headline text-lg font-bold text-on-primary shadow-ambient transition-all hover:bg-primary-hover active:scale-95 disabled:opacity-60 sm:w-auto"
        >
          <span className="material-symbols-outlined text-[1.25rem]">save</span>
          {isPending ? 'Đang lưu…' : 'Lưu thay đổi'}
        </button>
      </div>
    </form>
  );
}
