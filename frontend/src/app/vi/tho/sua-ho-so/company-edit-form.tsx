'use client';

import { useActionState, useEffect, useRef, useState, useTransition } from 'react';
import { updateCompanyAction, type UpdateCompanyResult } from './actions';
import { fetchDistricts, fetchWards } from '@/app/vi/hoan-thanh-ho-so/actions';
import { uploadCompanyImage, uploadPortfolioImage } from '@/app/vi/tho/dang-ky/actions';
import type { LocationItem, PublicCategory, UserCompany } from '@/lib/api-types';

type ServiceRow = { name: string; unit: string; price: string };

export function CompanyEditForm({
  company,
  categories,
  cities,
  defaultCityCode,
  defaultDistrictCode,
  initialDistricts,
  initialWards,
}: {
  company: UserCompany;
  categories: PublicCategory[];
  cities: LocationItem[];
  defaultCityCode: string;
  defaultDistrictCode: string;
  initialDistricts: LocationItem[];
  initialWards: LocationItem[];
}) {
  const [state, formAction, isPending] = useActionState<UpdateCompanyResult | null, FormData>(
    updateCompanyAction,
    null,
  );
  const [, startTransition] = useTransition();
  const [saved, setSaved] = useState(false);
  const [uploadError, setUploadError] = useState<string | null>(null);
  const [uploading, setUploading] = useState(false);

  // Basic info
  const [name, setName] = useState(company.name);
  const [email, setEmail] = useState(company.email);
  const [phone, setPhone] = useState(company.phone ?? '');
  const [categoryId, setCategoryId] = useState(String(company.category_id));
  const [experience, setExperience] = useState(String(company.experience));
  const [description, setDescription] = useState(company.description);
  const [tags, setTags] = useState(company.tags.join(', '));

  // Services
  const [services, setServices] = useState<ServiceRow[]>(
    company.services.length > 0
      ? company.services.map((s) => ({ name: s.name, unit: s.description ?? '', price: s.price ?? '' }))
      : [{ name: '', unit: '', price: '' }],
  );

  // Location
  const [cityCode, setCityCode] = useState(defaultCityCode);
  const [districtCode, setDistrictCode] = useState(defaultDistrictCode);
  const [wardCode, setWardCode] = useState('');
  const [address, setAddress] = useState(company.location.address ?? '');
  const [districts, setDistricts] = useState<LocationItem[]>(initialDistricts);
  const [wards, setWards] = useState<LocationItem[]>(initialWards);

  // Avatar
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(company.image);
  const avatarInputRef = useRef<HTMLInputElement>(null);

  // Portfolio: existing + new
  const [newPortfolioFiles, setNewPortfolioFiles] = useState<File[]>([]);
  const [newPortfolioPreviews, setNewPortfolioPreviews] = useState<string[]>([]);
  const portfolioInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (state?.ok) {
      if (avatarFile || newPortfolioFiles.length > 0) {
        handleUploadAfterSave(state.company.id);
      } else {
        setSaved(true);
        setTimeout(() => setSaved(false), 3000);
      }
    }
  }, [state]); // eslint-disable-line react-hooks/exhaustive-deps

  async function handleUploadAfterSave(companyId: number) {
    setUploading(true);
    setUploadError(null);

    if (avatarFile) {
      const res = await uploadCompanyImage(companyId, avatarFile);
      if (!res.ok) { setUploadError(res.error); setUploading(false); return; }
    }

    for (const file of newPortfolioFiles) {
      const res = await uploadPortfolioImage(companyId, file, file.name.replace(/\.[^.]+$/, ''));
      if (!res.ok) { setUploadError(res.error); setUploading(false); return; }
    }

    setUploading(false);
    setAvatarFile(null);
    setNewPortfolioFiles([]);
    setNewPortfolioPreviews([]);
    setSaved(true);
    setTimeout(() => setSaved(false), 3000);
  }

  function handleCityChange(code: string) {
    setCityCode(code); setDistrictCode(''); setWardCode(''); setWards([]);
    if (code) startTransition(async () => setDistricts(await fetchDistricts(code)));
    else setDistricts([]);
  }

  function handleDistrictChange(code: string) {
    setDistrictCode(code); setWardCode('');
    if (code) startTransition(async () => setWards(await fetchWards(code)));
    else setWards([]);
  }

  function updateService(i: number, patch: Partial<ServiceRow>) {
    setServices((prev) => prev.map((s, idx) => (idx === i ? { ...s, ...patch } : s)));
  }
  function addService() { setServices((prev) => [...prev, { name: '', unit: '', price: '' }]); }
  function removeService(i: number) { setServices((prev) => prev.filter((_, idx) => idx !== i)); }

  function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      setUploadError('Ảnh đại diện không được vượt quá 2MB');
      e.target.value = ''; return;
    }
    setUploadError(null);
    setAvatarFile(file);
    setAvatarPreview(URL.createObjectURL(file));
  }

  function handlePortfolioAdd(e: React.ChangeEvent<HTMLInputElement>) {
    const files = Array.from(e.target.files ?? []);
    if (!files.length) return;
    const oversized = files.find((f) => f.size > 2 * 1024 * 1024);
    if (oversized) {
      setUploadError(`Ảnh "${oversized.name}" vượt quá 2MB`);
      e.target.value = ''; return;
    }
    setUploadError(null);
    setNewPortfolioFiles((prev) => [...prev, ...files]);
    setNewPortfolioPreviews((prev) => [...prev, ...files.map((f) => URL.createObjectURL(f))]);
    e.target.value = '';
  }

  function removeNewPortfolio(i: number) {
    setNewPortfolioFiles((prev) => prev.filter((_, idx) => idx !== i));
    setNewPortfolioPreviews((prev) => { URL.revokeObjectURL(prev[i]); return prev.filter((_, idx) => idx !== i); });
  }

  const servicesClean = services
    .filter((s) => s.name.trim() !== '')
    .map((s) => ({ name: s.name, description: s.unit, price: s.price }));

  const isSubmitting = isPending || uploading;
  const fieldError = (key: string) => state && !state.ok ? state.fieldErrors?.[key] : undefined;

  return (
    <form action={formAction} className="space-y-10">
      <input type="hidden" name="company_id" value={company.id} />
      <input type="hidden" name="name" value={name} />
      <input type="hidden" name="email" value={email ?? ''} />
      <input type="hidden" name="phone" value={phone ?? ''} />
      <input type="hidden" name="category_id" value={categoryId} />
      <input type="hidden" name="experience" value={experience} />
      <input type="hidden" name="description" value={description} />
      <input type="hidden" name="tags" value={tags} />
      <input type="hidden" name="services_json" value={JSON.stringify(servicesClean)} />
      <input type="hidden" name="city_code" value={cityCode} />
      <input type="hidden" name="district_code" value={districtCode} />
      <input type="hidden" name="ward_code" value={wardCode} />
      <input type="hidden" name="address" value={address} />

      {/* Banners */}
      {state && !state.ok && (
        <div className="rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">{state.error}</div>
      )}
      {uploadError && (
        <div className="rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">{uploadError}</div>
      )}
      {saved && (
        <div className="flex items-center gap-2 rounded-lg bg-primary/10 px-6 py-4 text-[1rem] font-bold text-primary">
          <span className="material-symbols-outlined" style={{ fontVariationSettings: "'FILL' 1" }}>check_circle</span>
          Đã lưu thay đổi thành công
        </div>
      )}

      {/* === Thông tin cơ bản === */}
      <div className="rounded-xl bg-surface-container-lowest p-8">
        <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
          <span className="material-symbols-outlined text-primary">badge</span>
          Thông tin cơ bản
        </h3>
        <div className="space-y-6">
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Tên Thợ / Doanh nghiệp</label>
              <input type="text" value={name} onChange={(e) => setName(e.target.value)} maxLength={255}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
              {fieldError('name') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('name')}</p>}
            </div>
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Số điện thoại</label>
              <input type="tel" value={phone} onChange={(e) => setPhone(e.target.value.replace(/\D/g, ''))} maxLength={11}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
            </div>
          </div>
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Ngành nghề chính</label>
              <select value={categoryId} onChange={(e) => setCategoryId(e.target.value)}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary">
                <option value="">— Chọn ngành nghề —</option>
                {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
              </select>
              {fieldError('category_id') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('category_id')}</p>}
            </div>
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Số năm kinh nghiệm</label>
              <input type="number" value={experience} onChange={(e) => setExperience(e.target.value)} min={0} max={100}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
            </div>
          </div>
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Email liên hệ</label>
            <input type="text" value={email ?? ''} onChange={(e) => setEmail(e.target.value)} maxLength={120}
              className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
          </div>
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">
              Giới thiệu bản thân <span className="font-normal text-secondary">(tối thiểu 50 ký tự)</span>
            </label>
            <textarea value={description} onChange={(e) => setDescription(e.target.value)} rows={5} maxLength={5000}
              className="w-full rounded-lg border-none bg-surface-container-low px-4 py-4 text-[1.125rem] font-medium leading-relaxed text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
            <p className="mt-1 text-right text-[0.75rem] text-secondary">{description.length}/5000</p>
            {fieldError('description') && <p className="mt-1 text-[0.875rem] text-error">{fieldError('description')}</p>}
          </div>
        </div>
      </div>

      {/* === Dịch vụ & Bảng giá === */}
      <div className="rounded-xl bg-surface-container-lowest p-8">
        <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
          <span className="material-symbols-outlined text-primary">settings_suggest</span>
          Dịch vụ & Bảng giá
        </h3>
        <div className="mb-6">
          <label className="mb-2 block text-[1rem] font-bold text-on-surface">Lĩnh vực <span className="font-normal text-secondary">(phân cách bằng dấu phẩy)</span></label>
          <input type="text" value={tags} onChange={(e) => setTags(e.target.value)} maxLength={500}
            placeholder="VD: sửa điện, sửa nước, lắp đặt..."
            className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
        </div>
        <div className="overflow-hidden rounded-lg border border-outline-variant/15">
          <table className="w-full text-left">
            <thead className="bg-surface-container-high">
              <tr>
                <th className="px-6 py-4 text-[0.875rem] font-bold text-on-surface">Dịch vụ</th>
                <th className="px-6 py-4 text-[0.875rem] font-bold text-on-surface">Đơn vị</th>
                <th className="px-6 py-4 text-[0.875rem] font-bold text-on-surface">Giá (VNĐ)</th>
                <th className="w-12 px-4 py-4"></th>
              </tr>
            </thead>
            <tbody className="divide-y divide-surface-container">
              {services.map((svc, i) => (
                <tr key={i}>
                  <td className="px-6 py-3"><input type="text" value={svc.name} onChange={(e) => updateService(i, { name: e.target.value })} placeholder="VD: Lắp đặt đèn trần" className="w-full border-none bg-transparent p-0 text-[1rem] font-medium text-on-surface outline-none placeholder:text-outline focus:ring-0" /></td>
                  <td className="px-6 py-3"><input type="text" value={svc.unit} onChange={(e) => updateService(i, { unit: e.target.value })} placeholder="Cái / Lần / m²" className="w-full border-none bg-transparent p-0 text-[1rem] text-secondary outline-none placeholder:text-outline focus:ring-0" /></td>
                  <td className="px-6 py-3"><input type="text" value={svc.price} onChange={(e) => updateService(i, { price: e.target.value })} placeholder="150.000" className="w-full border-none bg-transparent p-0 text-[1rem] font-bold text-on-surface outline-none placeholder:text-outline focus:ring-0" /></td>
                  <td className="px-4 py-3">{services.length > 1 && <button type="button" onClick={() => removeService(i)} className="text-error hover:text-error/80"><span className="material-symbols-outlined">delete</span></button>}</td>
                </tr>
              ))}
            </tbody>
          </table>
          <button type="button" onClick={addService} className="flex w-full items-center justify-center gap-2 bg-surface-container-low py-4 text-[0.875rem] font-bold text-primary transition-colors hover:bg-surface-container">
            <span className="material-symbols-outlined">add_circle</span> Thêm dịch vụ
          </button>
        </div>
      </div>

      {/* === Ảnh đại diện === */}
      <div className="rounded-xl bg-surface-container-lowest p-8">
        <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
          <span className="material-symbols-outlined text-primary">photo_camera</span>
          Ảnh đại diện
        </h3>
        <div className="flex items-start gap-8">
          <div onClick={() => avatarInputRef.current?.click()}
            className="group relative flex h-48 w-48 shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-primary/30 bg-surface-container-low transition-colors hover:border-primary/60">
            {avatarPreview ? (
              // eslint-disable-next-line @next/next/no-img-element
              <img src={avatarPreview} alt="Avatar" className="h-full w-full object-cover" />
            ) : (
              <div className="flex flex-col items-center gap-2 text-secondary">
                <span className="material-symbols-outlined text-4xl">photo_camera</span>
                <span className="text-[0.75rem] font-medium">Chọn ảnh</span>
              </div>
            )}
            <div className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
              <span className="material-symbols-outlined text-3xl text-white">photo_camera</span>
            </div>
          </div>
          <input ref={avatarInputRef} type="file" accept="image/jpeg,image/png,image/webp" onChange={handleAvatarChange} className="hidden" />
          <div className="pt-2">
            <p className="text-[1rem] font-bold text-on-surface">Click để thay đổi ảnh đại diện</p>
            <p className="mt-1 text-[0.875rem] text-secondary">JPEG/PNG/WebP, tối đa 2MB</p>
          </div>
        </div>
      </div>

      {/* === Ảnh dự án mới === */}
      <div className="rounded-xl bg-surface-container-lowest p-8">
        <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
          <span className="material-symbols-outlined text-primary">collections</span>
          Thêm ảnh dự án
        </h3>
        <div className="grid grid-cols-3 gap-4 md:grid-cols-4">
          {newPortfolioPreviews.map((src, i) => (
            <div key={i} className="group relative aspect-square overflow-hidden rounded-lg bg-surface-container-low">
              {/* eslint-disable-next-line @next/next/no-img-element */}
              <img src={src} alt={`Dự án ${i + 1}`} className="h-full w-full object-cover" />
              <button type="button" onClick={() => removeNewPortfolio(i)}
                className="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-error text-on-error opacity-0 transition-opacity group-hover:opacity-100">
                <span className="material-symbols-outlined text-[1rem]">close</span>
              </button>
            </div>
          ))}
          <button type="button" onClick={() => portfolioInputRef.current?.click()}
            className="flex aspect-square flex-col items-center justify-center rounded-lg border-2 border-dashed border-outline bg-surface-container-low text-secondary transition-colors hover:border-primary hover:text-primary">
            <span className="material-symbols-outlined text-3xl">add_photo_alternate</span>
            <span className="mt-2 text-[0.75rem] font-bold">Thêm ảnh</span>
          </button>
        </div>
        <input ref={portfolioInputRef} type="file" accept="image/jpeg,image/png,image/webp" multiple onChange={handlePortfolioAdd} className="hidden" />
        <p className="mt-3 text-[0.75rem] text-secondary">JPEG/PNG/WebP, tối đa 2MB mỗi ảnh</p>
      </div>

      {/* === Khu vực phục vụ === */}
      <div className="rounded-xl bg-surface-container-lowest p-8">
        <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
          <span className="material-symbols-outlined text-primary">location_on</span>
          Khu vực phục vụ
        </h3>
        <div className="space-y-6">
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Địa chỉ cụ thể</label>
            <input type="text" value={address} onChange={(e) => setAddress(e.target.value)} maxLength={255}
              className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
          </div>
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Tỉnh / Thành phố</label>
              <select value={cityCode} onChange={(e) => handleCityChange(e.target.value)}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary">
                <option value="">— Chọn tỉnh/thành —</option>
                {cities.map((c) => <option key={c.code} value={c.code}>{c.name}</option>)}
              </select>
            </div>
            <div>
              <label className="mb-2 block text-[1rem] font-bold text-on-surface">Quận / Huyện</label>
              <select value={districtCode} onChange={(e) => handleDistrictChange(e.target.value)} disabled={!cityCode}
                className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60">
                <option value="">— Chọn quận/huyện —</option>
                {districts.map((d) => <option key={d.code} value={d.code}>{d.name}</option>)}
              </select>
            </div>
          </div>
          <div>
            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Phường / Xã</label>
            <select value={wardCode} onChange={(e) => setWardCode(e.target.value)} disabled={!districtCode}
              className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60">
              <option value="">— Chọn phường/xã —</option>
              {wards.map((w) => <option key={w.code} value={w.code}>{w.name}</option>)}
            </select>
          </div>
        </div>
      </div>

      {/* Submit */}
      <div className="flex justify-end pt-2">
        <button type="submit" disabled={isSubmitting}
          className="flex h-14 items-center gap-2 rounded-xl bg-primary px-12 text-[1.25rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60">
          {uploading ? 'Đang upload ảnh...' : isPending ? 'Đang lưu...' : (
            <><span className="material-symbols-outlined">save</span> Lưu thay đổi</>
          )}
        </button>
      </div>
    </form>
  );
}
