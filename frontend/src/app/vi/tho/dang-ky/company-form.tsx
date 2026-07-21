'use client';

import { useActionState, useEffect, useRef, useState, useTransition } from 'react';
import { useRouter } from 'next/navigation';
import {
  createCompanyAction,
  uploadCompanyImage,
  uploadPortfolioImage,
  type CreateCompanyResult,
} from './actions';
import { fetchDistricts, fetchWards } from '@/app/vi/hoan-thanh-ho-so/actions';
import type { LocationItem, PublicCategory } from '@/lib/api-types';

type ServiceRow = { name: string; unit: string; price: string };

const STEPS = [
  { key: 'identity', label: 'Cá nhân', icon: 'badge' },
  { key: 'specialization', label: 'Chuyên môn', icon: 'construction' },
  // Bước này là Hình ảnh & Khu vực — KHÔNG phải xác minh SĐT (chưa có tính năng đó).
  // Nhãn cũ "Xác minh" hứa hẹn sai với người dùng.
  { key: 'media', label: 'Hình ảnh', icon: 'photo_camera' },
  { key: 'review', label: 'Kiểm tra', icon: 'rate_review' },
] as const;

export function CompanyForm({
  categories,
  cities,
  defaultName,
  defaultEmail,
  defaultPhone,
  defaultCityCode,
  defaultAddress,
}: {
  categories: PublicCategory[];
  cities: LocationItem[];
  defaultName: string;
  defaultEmail: string | null;
  defaultPhone: string;
  defaultCityCode?: string;
  defaultAddress?: string;
}) {
  const router = useRouter();
  const [step, setStep] = useState(0);
  const [state, formAction, isPending] = useActionState<CreateCompanyResult | null, FormData>(
    createCompanyAction,
    null,
  );
  const [, startTransition] = useTransition();

  // ---- Step 1: Identity ----
  const [companyName, setCompanyName] = useState('');
  const [fullName, setFullName] = useState(defaultName);
  const [phone, setPhone] = useState(defaultPhone);
  const [email, setEmail] = useState(defaultEmail ?? '');
  const [categoryId, setCategoryId] = useState('');
  const [experience, setExperience] = useState('');
  const [description, setDescription] = useState('');

  // ---- Step 2: Specialization (services + tags) ----
  const [tags, setTags] = useState('');
  const [services, setServices] = useState<ServiceRow[]>([
    { name: '', unit: '', price: '' },
  ]);

  // ---- Step 3: Verification (location + images) ----
  const [cityCode, setCityCode] = useState(defaultCityCode ?? '');
  const [districtCode, setDistrictCode] = useState('');
  const [wardCode, setWardCode] = useState('');
  const [address, setAddress] = useState(defaultAddress ?? '');
  const [districts, setDistricts] = useState<LocationItem[]>([]);
  const [wards, setWards] = useState<LocationItem[]>([]);

  // ---- Image uploads ----
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);
  const [portfolioFiles, setPortfolioFiles] = useState<File[]>([]);
  const [portfolioPreviews, setPortfolioPreviews] = useState<string[]>([]);
  const [uploading, setUploading] = useState(false);
  const [uploadError, setUploadError] = useState<string | null>(null);
  const avatarInputRef = useRef<HTMLInputElement>(null);
  const portfolioInputRef = useRef<HTMLInputElement>(null);

  // After company created → upload images → redirect
  useEffect(() => {
    if (!state?.ok) return;
    const companyId = state.id;

    async function uploadAndRedirect() {
      setUploading(true);
      setUploadError(null);

      // Upload avatar
      if (avatarFile) {
        const res = await uploadCompanyImage(companyId, avatarFile);
        if (!res.ok) {
          setUploadError(res.error);
          setUploading(false);
          return;
        }
      }

      // Upload portfolio images
      for (const file of portfolioFiles) {
        const res = await uploadPortfolioImage(companyId, file, file.name.replace(/\.[^.]+$/, ''));
        if (!res.ok) {
          setUploadError(res.error);
          setUploading(false);
          return;
        }
      }

      setUploading(false);
      router.replace('/vi/tho/lich-hen');
    }

    if (avatarFile || portfolioFiles.length > 0) {
      uploadAndRedirect();
    } else {
      router.replace('/vi/tho/lich-hen');
    }
  }, [state]); // eslint-disable-line react-hooks/exhaustive-deps

  // Pre-load districts if city is pre-set
  useEffect(() => {
    if (cityCode) {
      startTransition(async () => {
        setDistricts(await fetchDistricts(cityCode));
      });
    }
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

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

  // ---- Service table helpers ----
  function updateService(i: number, patch: Partial<ServiceRow>) {
    setServices((prev) => prev.map((s, idx) => (idx === i ? { ...s, ...patch } : s)));
  }
  function addService() {
    setServices((prev) => [...prev, { name: '', unit: '', price: '' }]);
  }
  function removeService(i: number) {
    setServices((prev) => prev.filter((_, idx) => idx !== i));
  }

  // ---- Avatar handler ----
  function handleAvatarChange(e: React.ChangeEvent<HTMLInputElement>) {
    const file = e.target.files?.[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      setUploadError('Ảnh đại diện không được vượt quá 2MB');
      e.target.value = '';
      return;
    }
    setUploadError(null);
    setAvatarFile(file);
    setAvatarPreview(URL.createObjectURL(file));
  }

  // ---- Portfolio handler ----
  function handlePortfolioAdd(e: React.ChangeEvent<HTMLInputElement>) {
    const files = Array.from(e.target.files ?? []);
    if (!files.length) return;
    const oversized = files.find((f) => f.size > 2 * 1024 * 1024);
    if (oversized) {
      setUploadError(`Ảnh "${oversized.name}" vượt quá 2MB. Mỗi ảnh tối đa 2MB.`);
      e.target.value = '';
      return;
    }
    setUploadError(null);
    setPortfolioFiles((prev) => [...prev, ...files]);
    setPortfolioPreviews((prev) => [...prev, ...files.map((f) => URL.createObjectURL(f))]);
    e.target.value = ''; // reset for re-select
  }
  function removePortfolio(i: number) {
    setPortfolioFiles((prev) => prev.filter((_, idx) => idx !== i));
    setPortfolioPreviews((prev) => {
      URL.revokeObjectURL(prev[i]);
      return prev.filter((_, idx) => idx !== i);
    });
  }

  const servicesClean = services
    .filter((s) => s.name.trim() !== '')
    .map((s) => ({ name: s.name, description: s.unit, price: s.price }));

  // ---- Progress calculation (step-based: đã đi qua step = done) ----
  const completedChecks = [
    step >= 1, // Đã qua step 0 (Cá nhân)
    step >= 2, // Đã qua step 1 (Chuyên môn)
    step >= 3, // Đã qua step 2 (Hình ảnh & Khu vực)
    false,     // Xác nhận cuối cùng = chỉ done khi submit thành công
  ];
  const completedCount = completedChecks.filter(Boolean).length;
  const progressPercent = Math.round((completedCount / 4) * 100);

  const isSubmitting = isPending || uploading;

  return (
    <div className="mx-auto max-w-6xl">
      {/* ===== Stepper ngang — nằm TRONG luồng trang, không đè header/nav ===== */}
      <div className="mb-10">
        <div className="flex items-center">
          {STEPS.map((s, i) => {
            const isActive = i === step;
            const isDone = i < step;
            return (
              <div key={s.key} className={`flex items-center ${i > 0 ? 'flex-1' : ''}`}>
                {i > 0 && (
                  <div className={`mx-2 h-0.5 flex-1 rounded-full transition-colors md:mx-3 ${isDone || isActive ? 'bg-primary' : 'bg-outline-variant/30'}`} />
                )}
                <button
                  type="button"
                  onClick={() => setStep(i)}
                  aria-current={isActive ? 'step' : undefined}
                  className="group flex shrink-0 flex-col items-center gap-1.5"
                >
                  <span
                    className={`flex h-11 w-11 items-center justify-center rounded-full transition-all ${
                      isActive
                        ? 'bg-primary text-on-primary shadow-ambient'
                        : isDone
                          ? 'bg-primary/15 text-primary'
                          : 'bg-surface-container-lowest text-outline ring-1 ring-outline-variant/25 group-hover:text-on-surface'
                    }`}
                  >
                    <span
                      className="material-symbols-outlined text-[1.375rem]"
                      style={isDone || isActive ? { fontVariationSettings: "'FILL' 1" } : undefined}
                    >
                      {isDone ? 'check' : s.icon}
                    </span>
                  </span>
                  <span
                    className={`whitespace-nowrap text-xs font-semibold ${
                      isActive ? 'text-primary' : isDone ? 'text-on-surface' : 'text-on-surface-variant'
                    } ${isActive ? '' : 'hidden sm:block'}`}
                  >
                    {s.label}
                  </span>
                </button>
              </div>
            );
          })}
        </div>
      </div>

      {/* Step header */}
      <div className="mb-8">
        <span className="text-xs font-bold uppercase tracking-widest text-primary">Bước {step + 1}/{STEPS.length}</span>
        <h1 className="mt-1.5 font-headline text-3xl font-bold text-on-surface md:text-4xl">
          {step === 0 && 'Thông tin cá nhân'}
          {step === 1 && 'Chuyên môn & Dịch vụ'}
          {step === 2 && 'Hình ảnh & Khu vực'}
          {step === 3 && 'Kiểm tra & Gửi hồ sơ'}
        </h1>
        <p className="mt-2 text-lg text-on-surface-variant">
          {step === 0 && 'Thông tin cơ bản để khách hàng biết bạn là ai.'}
          {step === 1 && 'Hãy cho khách hàng biết bạn có thể giúp gì cho họ.'}
          {step === 2 && 'Ảnh đại diện, dự án đã thực hiện và khu vực phục vụ.'}
          {step === 3 && 'Xem lại thông tin trước khi gửi.'}
        </p>
      </div>

          {state && !state.ok && (
            <div className="mb-8 rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
              {state.error}
            </div>
          )}
          {uploadError && (
            <div className="mb-8 rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
              {uploadError}
            </div>
          )}

          <div className="grid grid-cols-1 gap-8 xl:grid-cols-12">
            {/* ===== Form column ===== */}
            <div className="xl:col-span-8">
              <form action={formAction}>
                <input type="hidden" name="name" value={companyName || fullName} />
                <input type="hidden" name="email" value={email} />
                <input type="hidden" name="phone" value={phone} />
                <input type="hidden" name="category_id" value={categoryId} />
                <input type="hidden" name="experience" value={experience} />
                <input type="hidden" name="description" value={description} />
                <input type="hidden" name="tags" value={tags} />
                <input type="hidden" name="services_json" value={JSON.stringify(servicesClean)} />
                <input type="hidden" name="city_code" value={cityCode} />
                <input type="hidden" name="district_code" value={districtCode} />
                <input type="hidden" name="ward_code" value={wardCode} />
                <input type="hidden" name="address" value={address} />

                {/* ========== STEP 1: Cá nhân ========== */}
                {step === 0 && (
                  <section className="space-y-10">
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                        <span className="material-symbols-outlined text-primary">person</span>
                        Thông tin cơ bản
                      </h3>
                      <div className="space-y-6">
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Họ và Tên</label>
                            <input aria-label="Họ và Tên" type="text" value={fullName} onChange={(e) => setFullName(e.target.value)} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
                            <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ đăng ký</p>
                          </div>
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Số điện thoại</label>
                            <input aria-label="Số điện thoại" type="tel" value={phone} onChange={(e) => setPhone(e.target.value.replace(/\D/g, ''))} maxLength={11} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
                            {phone && <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ đăng ký</p>}
                          </div>
                        </div>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Tên Thợ / Doanh nghiệp</label>
                            <input aria-label="Tên Thợ / Doanh nghiệp" type="text" value={companyName} onChange={(e) => setCompanyName(e.target.value)} maxLength={255} placeholder="VD: Thợ điện nước Minh Anh" className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
                            <p className="mt-1 text-[0.75rem] text-secondary">Bỏ trống sẽ tự động lấy từ Họ tên</p>
                          </div>
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Email liên hệ</label>
                            <input aria-label="Email liên hệ" type="text" value={email} onChange={(e) => setEmail(e.target.value)} maxLength={120} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary" />
                            {email && <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ đăng ký</p>}
                          </div>
                        </div>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Ngành nghề chính</label>
                            <select aria-label="Ngành nghề chính" value={categoryId} onChange={(e) => setCategoryId(e.target.value)} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary">
                              <option value="">— Chọn ngành nghề —</option>
                              {categories.map((c) => (<option key={c.id} value={c.id}>{c.name}</option>))}
                            </select>
                          </div>
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Số năm kinh nghiệm</label>
                            <input aria-label="Số năm kinh nghiệm" type="number" value={experience} onChange={(e) => setExperience(e.target.value)} min={0} max={100} placeholder="VD: 8" className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
                          </div>
                        </div>
                        <div>
                          <label className="mb-2 block text-[1rem] font-bold text-on-surface">
                            Giới thiệu bản thân <span className="font-medium text-secondary">(tối thiểu 50 ký tự)</span>
                          </label>
                          <textarea value={description} onChange={(e) => setDescription(e.target.value)} rows={4} maxLength={5000} placeholder="VD: Hơn 8 năm kinh nghiệm trong lĩnh vực sửa chữa điện nước gia dụng. Tận tâm, uy tín và chuyên nghiệp..." className="w-full rounded-lg border-none bg-surface-container-low px-4 py-4 text-[1.125rem] font-medium leading-relaxed text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
                          <p className="mt-1 text-right text-[0.75rem] text-secondary">{description.length}/5000 ký tự</p>
                        </div>
                      </div>
                    </div>
                    <div className="flex justify-end pt-4">
                      <button type="button" onClick={() => setStep(1)} className="flex h-14 items-center gap-2 rounded-lg bg-primary px-10 text-[1.125rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98]">
                        Tiếp tục chuyên môn
                        <span className="material-symbols-outlined">arrow_forward</span>
                      </button>
                    </div>
                  </section>
                )}

                {/* ========== STEP 2: Chuyên môn & Dịch vụ ========== */}
                {step === 1 && (
                  <section className="space-y-10">
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                        <span className="material-symbols-outlined text-primary">settings_suggest</span>
                        Dịch vụ & Bảng giá
                      </h3>
                      <div className="mb-8">
                        <label className="mb-3 block text-[1rem] font-bold text-on-surface">
                          Lĩnh vực hoạt động <span className="font-medium text-secondary">(phân cách bằng dấu phẩy)</span>
                        </label>
                        <input type="text" value={tags} onChange={(e) => setTags(e.target.value)} maxLength={500} placeholder="VD: sửa điện, sửa nước, lắp đặt, bảo trì..." className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
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
                                <td className="px-4 py-3">{services.length > 1 && (<button type="button" onClick={() => removeService(i)} className="text-error hover:text-error/80"><span className="material-symbols-outlined">delete</span></button>)}</td>
                              </tr>
                            ))}
                          </tbody>
                        </table>
                        <button type="button" onClick={addService} className="flex w-full items-center justify-center gap-2 bg-surface-container-low py-4 text-[0.875rem] font-bold text-primary transition-colors hover:bg-surface-container">
                          <span className="material-symbols-outlined">add_circle</span>
                          Thêm dịch vụ mới
                        </button>
                      </div>
                    </div>
                    <div className="flex justify-between pt-4">
                      <button type="button" onClick={() => setStep(0)} className="flex h-14 items-center gap-2 rounded-lg px-8 text-[1.125rem] font-bold text-on-surface transition-colors hover:bg-surface-container-low active:scale-[0.98]">
                        <span className="material-symbols-outlined">arrow_back</span> Quay lại
                      </button>
                      <button type="button" onClick={() => setStep(2)} className="flex h-14 items-center gap-2 rounded-lg bg-primary px-10 text-[1.125rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98]">
                        Tiếp tục hình ảnh <span className="material-symbols-outlined">arrow_forward</span>
                      </button>
                    </div>
                  </section>
                )}

                {/* ========== STEP 3: Hình ảnh & Khu vực ========== */}
                {step === 2 && (
                  <section className="space-y-10">
                    {/* Avatar upload */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                        <span className="material-symbols-outlined text-primary">verified</span>
                        Ảnh đại diện chuyên nghiệp
                      </h3>
                      <div className="flex items-start gap-8">
                        <div
                          onClick={() => avatarInputRef.current?.click()}
                          className="group relative flex h-48 w-48 shrink-0 cursor-pointer items-center justify-center overflow-hidden rounded-xl border-2 border-dashed border-primary/30 bg-surface-container-low transition-colors hover:border-primary/60"
                        >
                          {avatarPreview ? (
                            // eslint-disable-next-line @next/next/no-img-element
                            <img src={avatarPreview} alt="Avatar preview" className="h-full w-full object-cover" />
                          ) : (
                            <div className="flex flex-col items-center gap-2 text-secondary">
                              <span className="material-symbols-outlined text-4xl">photo_camera</span>
                              <span className="text-[0.75rem] font-medium">Chọn ảnh</span>
                            </div>
                          )}
                          {avatarPreview && (
                            <div className="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100">
                              <span className="material-symbols-outlined text-3xl text-white">photo_camera</span>
                            </div>
                          )}
                        </div>
                        <input ref={avatarInputRef} type="file" accept="image/jpeg,image/png,image/webp" onChange={handleAvatarChange} className="hidden" />
                        <div className="pt-2">
                          <p className="text-[1rem] font-bold text-on-surface">Ảnh đại diện</p>
                          <p className="mt-1 text-[0.875rem] text-secondary">Ảnh rõ mặt, chuyên nghiệp. JPEG/PNG/WebP, tối đa 2MB.</p>
                          <p className="mt-2 text-[0.75rem] text-secondary">Khách hàng sẽ thấy ảnh này trên hồ sơ công khai của bạn.</p>
                        </div>
                      </div>
                    </div>

                    {/* Portfolio upload */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                        <span className="material-symbols-outlined text-primary">collections</span>
                        Dự án đã thực hiện
                      </h3>
                      <p className="mb-4 text-[0.875rem] text-secondary">Thêm ảnh các công trình/dự án bạn đã hoàn thành để tạo sự tin tưởng.</p>

                      <div className="grid grid-cols-2 gap-4 md:grid-cols-3">
                        {portfolioPreviews.map((src, i) => (
                          <div key={i} className="group relative aspect-square overflow-hidden rounded-lg bg-surface-container-low">
                            {/* eslint-disable-next-line @next/next/no-img-element */}
                            <img src={src} alt={`Dự án ${i + 1}`} className="h-full w-full object-cover" />
                            <button
                              type="button"
                              onClick={() => removePortfolio(i)}
                              className="absolute right-2 top-2 flex h-8 w-8 items-center justify-center rounded-full bg-error text-on-error opacity-0 transition-opacity group-hover:opacity-100"
                            >
                              <span className="material-symbols-outlined text-[1rem]">close</span>
                            </button>
                          </div>
                        ))}

                        <button
                          type="button"
                          onClick={() => portfolioInputRef.current?.click()}
                          className="flex aspect-square flex-col items-center justify-center rounded-lg border-2 border-dashed border-outline bg-surface-container-low text-secondary transition-colors hover:border-primary hover:bg-surface-container hover:text-primary"
                        >
                          <span className="material-symbols-outlined text-3xl">add_photo_alternate</span>
                          <span className="mt-2 text-[0.75rem] font-bold">Thêm ảnh</span>
                        </button>
                      </div>
                      <input ref={portfolioInputRef} type="file" accept="image/jpeg,image/png,image/webp" multiple onChange={handlePortfolioAdd} className="hidden" />
                      <p className="mt-3 text-[0.75rem] text-secondary">JPEG/PNG/WebP, tối đa 2MB mỗi ảnh</p>
                    </div>

                    {/* Location */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <h3 className="mb-6 flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                        <span className="material-symbols-outlined text-primary">location_on</span>
                        Khu vực phục vụ
                      </h3>
                      <div className="space-y-6">
                        <div>
                          <label className="mb-2 block text-[1rem] font-bold text-on-surface">Địa chỉ hiện tại</label>
                          <input type="text" value={address} onChange={(e) => setAddress(e.target.value)} maxLength={255} placeholder="Số nhà, đường..." className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none placeholder:text-outline transition-all focus:ring-2 focus:ring-primary" />
                          {defaultAddress && address === defaultAddress && <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ hồ sơ</p>}
                        </div>
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Tỉnh / Thành phố</label>
                            <select aria-label="Tỉnh / Thành phố" value={cityCode} onChange={(e) => handleCityChange(e.target.value)} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary">
                              <option value="">— Chọn tỉnh/thành —</option>
                              {cities.map((c) => (<option key={c.code} value={c.code}>{c.name}</option>))}
                            </select>
                            {defaultCityCode && cityCode === defaultCityCode && <p className="mt-1 text-[0.75rem] text-secondary">Đã điền từ hồ sơ</p>}
                          </div>
                          <div>
                            <label className="mb-2 block text-[1rem] font-bold text-on-surface">Quận / Huyện</label>
                            <select aria-label="Quận / Huyện" value={districtCode} onChange={(e) => handleDistrictChange(e.target.value)} disabled={!cityCode} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60">
                              <option value="">— Chọn quận/huyện —</option>
                              {districts.map((d) => (<option key={d.code} value={d.code}>{d.name}</option>))}
                            </select>
                          </div>
                        </div>
                        <div>
                          <label className="mb-2 block text-[1rem] font-bold text-on-surface">Phường / Xã</label>
                          <select aria-label="Phường / Xã" value={wardCode} onChange={(e) => setWardCode(e.target.value)} disabled={!districtCode} className="h-14 w-full rounded-lg border-none bg-surface-container-low px-4 text-[1.125rem] font-medium text-on-surface outline-none transition-all focus:ring-2 focus:ring-primary disabled:opacity-60">
                            <option value="">— Chọn phường/xã —</option>
                            {wards.map((w) => (<option key={w.code} value={w.code}>{w.name}</option>))}
                          </select>
                        </div>
                      </div>
                    </div>

                    <div className="flex justify-between pt-4">
                      <button type="button" onClick={() => setStep(1)} className="flex h-14 items-center gap-2 rounded-lg px-8 text-[1.125rem] font-bold text-on-surface transition-colors hover:bg-surface-container-low active:scale-[0.98]">
                        <span className="material-symbols-outlined">arrow_back</span> Quay lại
                      </button>
                      <button type="button" onClick={() => setStep(3)} className="flex h-14 items-center gap-2 rounded-lg bg-primary px-10 text-[1.125rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98]">
                        Kiểm tra hồ sơ <span className="material-symbols-outlined">arrow_forward</span>
                      </button>
                    </div>
                  </section>
                )}

                {/* ========== STEP 4: Review & Submit ========== */}
                {step === 3 && (
                  <section className="space-y-10">
                    {/* Summary: Personal */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <div className="mb-4 flex items-center justify-between">
                        <h3 className="flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                          <span className="material-symbols-outlined text-primary">badge</span> Thông tin cá nhân
                        </h3>
                        <button type="button" onClick={() => setStep(0)} className="text-[0.875rem] font-bold text-primary hover:underline">Sửa</button>
                      </div>
                      <dl className="grid grid-cols-2 gap-4 text-[1rem]">
                        <div><dt className="text-secondary">Họ tên</dt><dd className="font-bold text-on-surface">{fullName}</dd></div>
                        <div><dt className="text-secondary">Điện thoại</dt><dd className="font-bold text-on-surface">{phone || '—'}</dd></div>
                        <div><dt className="text-secondary">Tên Thợ / DN</dt><dd className="font-bold text-on-surface">{companyName || fullName}</dd></div>
                        <div><dt className="text-secondary">Email</dt><dd className="font-bold text-on-surface">{email || '—'}</dd></div>
                        <div><dt className="text-secondary">Ngành nghề</dt><dd className="font-bold text-on-surface">{categories.find((c) => String(c.id) === categoryId)?.name || '—'}</dd></div>
                        <div><dt className="text-secondary">Kinh nghiệm</dt><dd className="font-bold text-on-surface">{experience ? `${experience} năm` : '—'}</dd></div>
                        <div className="col-span-2"><dt className="text-secondary">Giới thiệu</dt><dd className="mt-1 whitespace-pre-line font-medium text-on-surface">{description || '—'}</dd></div>
                      </dl>
                    </div>

                    {/* Summary: Services */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <div className="mb-4 flex items-center justify-between">
                        <h3 className="flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                          <span className="material-symbols-outlined text-primary">construction</span> Dịch vụ & Bảng giá
                        </h3>
                        <button type="button" onClick={() => setStep(1)} className="text-[0.875rem] font-bold text-primary hover:underline">Sửa</button>
                      </div>
                      {tags && (
                        <div className="mb-4 flex flex-wrap gap-2">
                          {tags.split(',').map((t) => t.trim()).filter(Boolean).map((t) => (
                            <span key={t} className="rounded-full bg-primary/10 px-4 py-1.5 text-[0.875rem] font-medium text-primary">{t}</span>
                          ))}
                        </div>
                      )}
                      {servicesClean.length > 0 ? (
                        <div className="overflow-hidden rounded-lg border border-outline-variant/15">
                          <table className="w-full text-left">
                            <thead className="bg-surface-container-high">
                              <tr>
                                <th className="px-6 py-3 text-[0.875rem] font-bold text-on-surface">Dịch vụ</th>
                                <th className="px-6 py-3 text-[0.875rem] font-bold text-on-surface">Đơn vị</th>
                                <th className="px-6 py-3 text-[0.875rem] font-bold text-on-surface">Giá</th>
                              </tr>
                            </thead>
                            <tbody className="divide-y divide-surface-container">
                              {servicesClean.map((s, i) => (
                                <tr key={i}>
                                  <td className="px-6 py-3 text-[1rem] font-medium text-on-surface">{s.name}</td>
                                  <td className="px-6 py-3 text-[1rem] text-secondary">{s.description || '—'}</td>
                                  <td className="px-6 py-3 text-[1rem] font-bold text-on-surface">{s.price || 'Liên hệ'}</td>
                                </tr>
                              ))}
                            </tbody>
                          </table>
                        </div>
                      ) : (
                        <p className="text-[1rem] text-secondary">Chưa thêm dịch vụ nào</p>
                      )}
                    </div>

                    {/* Summary: Images + Location */}
                    <div className="rounded-xl bg-surface-container-lowest p-8">
                      <div className="mb-4 flex items-center justify-between">
                        <h3 className="flex items-center gap-2 font-headline text-[1.25rem] font-bold text-on-surface">
                          <span className="material-symbols-outlined text-primary">location_on</span> Hình ảnh & Khu vực
                        </h3>
                        <button type="button" onClick={() => setStep(2)} className="text-[0.875rem] font-bold text-primary hover:underline">Sửa</button>
                      </div>

                      {/* Avatar + portfolio preview */}
                      <div className="mb-6 flex items-center gap-4">
                        {avatarPreview ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img src={avatarPreview} alt="Avatar" className="h-16 w-16 rounded-full object-cover" />
                        ) : (
                          <div className="flex h-16 w-16 items-center justify-center rounded-full bg-surface-container-low text-secondary">
                            <span className="material-symbols-outlined text-2xl">person</span>
                          </div>
                        )}
                        <div>
                          <p className="text-[1rem] font-bold text-on-surface">{avatarFile ? 'Ảnh đại diện đã chọn' : 'Chưa có ảnh đại diện'}</p>
                          <p className="text-[0.875rem] text-secondary">{portfolioFiles.length} ảnh dự án</p>
                        </div>
                      </div>

                      {portfolioPreviews.length > 0 && (
                        <div className="mb-6 flex gap-2 overflow-x-auto pb-2">
                          {portfolioPreviews.map((src, i) => (
                            // eslint-disable-next-line @next/next/no-img-element
                            <img key={i} src={src} alt={`Dự án ${i + 1}`} className="h-20 w-20 shrink-0 rounded-lg object-cover" />
                          ))}
                        </div>
                      )}

                      <dl className="grid grid-cols-2 gap-4 text-[1rem]">
                        <div><dt className="text-secondary">Tỉnh/Thành</dt><dd className="font-bold text-on-surface">{cities.find((c) => c.code === cityCode)?.name || '—'}</dd></div>
                        <div><dt className="text-secondary">Quận/Huyện</dt><dd className="font-bold text-on-surface">{districts.find((d) => d.code === districtCode)?.name || '—'}</dd></div>
                        <div><dt className="text-secondary">Phường/Xã</dt><dd className="font-bold text-on-surface">{wards.find((w) => w.code === wardCode)?.name || '—'}</dd></div>
                        <div><dt className="text-secondary">Địa chỉ</dt><dd className="font-bold text-on-surface">{address || '—'}</dd></div>
                      </dl>
                    </div>

                    {/* Submit */}
                    <div className="flex justify-between border-t border-outline-variant/15 pt-8">
                      <button type="button" onClick={() => setStep(2)} className="flex h-14 items-center gap-2 rounded-lg px-8 text-[1.125rem] font-bold text-on-surface transition-colors hover:bg-surface-container-low active:scale-[0.98]">
                        <span className="material-symbols-outlined">arrow_back</span> Quay lại
                      </button>
                      <button
                        type="submit"
                        disabled={isSubmitting}
                        className="flex h-14 items-center gap-2 rounded-lg bg-primary px-12 text-[1.25rem] font-bold text-on-primary transition-all hover:brightness-105 active:scale-[0.98] disabled:opacity-60"
                      >
                        {uploading ? 'Đang upload ảnh...' : isPending ? 'Đang gửi hồ sơ...' : 'Gửi hồ sơ — Chờ duyệt'}
                        {!isSubmitting && <span className="material-symbols-outlined">check</span>}
                      </button>
                    </div>
                    <p className="text-center text-[0.875rem] text-secondary">Hồ sơ sẽ được admin duyệt trước khi hiển thị công khai</p>
                  </section>
                )}
              </form>
            </div>

            {/* ===== Progress sidebar ===== */}
            <div className="hidden xl:col-span-4 xl:block">
              {/* top-40 = dưới header (80px) + DashboardNav (~64px) + margin */}
              <div className="sticky top-40 space-y-6">
                <div className="rounded-2xl bg-on-surface p-8 shadow-ambient">
                  <h4 className="mb-4 font-headline text-[1.25rem] font-bold text-white">Hồ sơ hoàn tất {progressPercent}%</h4>
                  <div className="mb-6 h-3 w-full overflow-hidden rounded-full bg-white/15">
                    {/* Sky (#48BBE2) nổi rõ trên navy, teal primary bị chìm */}
                    <div className="h-full rounded-full bg-primary-container transition-all" style={{ width: `${progressPercent}%` }} />
                  </div>
                  <ul className="space-y-4">
                    {[
                      { label: 'Thông tin cơ bản', done: completedChecks[0] },
                      { label: 'Dịch vụ & Bảng giá', done: completedChecks[1] },
                      { label: 'Hình ảnh & Khu vực', done: completedChecks[2] },
                      { label: 'Xác nhận cuối cùng', done: completedChecks[3] },
                    ].map((item) => (
                      <li key={item.label} className={`flex items-center gap-3 text-[0.875rem] ${item.done ? 'font-semibold text-white' : 'text-white/70'}`}>
                        <span
                          className={`material-symbols-outlined ${item.done ? 'text-primary-container' : 'text-white/40'}`}
                          style={item.done ? { fontVariationSettings: "'FILL' 1" } : undefined}
                        >
                          {item.done ? 'check_circle' : 'radio_button_unchecked'}
                        </span>
                        {item.label}
                      </li>
                    ))}
                  </ul>
                </div>
                <div className="rounded-xl border border-primary/20 bg-surface-container-high p-8">
                  <h4 className="mb-3 font-headline text-[1rem] font-bold text-on-surface">Tại sao cần thông tin này?</h4>
                  <p className="text-[0.875rem] leading-relaxed text-secondary">
                    Hồ sơ đầy đủ giúp khách hàng tin tưởng bạn hơn. Các chuyên gia có bảng giá rõ ràng thường nhận được nhiều hơn <strong>40%</strong> yêu cầu công việc.
                  </p>
                  <div className="mt-6 flex items-center gap-3">
                    <span className="flex h-10 w-10 items-center justify-center rounded-full bg-tertiary/20">
                      <span className="material-symbols-outlined text-tertiary" style={{ fontVariationSettings: "'FILL' 1" }}>verified</span>
                    </span>
                    <div>
                      <p className="text-[0.75rem] font-bold text-tertiary">Verified</p>
                      <p className="text-[0.625rem] text-secondary">Nhận ngay sau khi xác thực</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </div>
  );
}
