'use client';

import { useCallback, useEffect, useState } from 'react';

interface LocationOption {
  code: string;
  name: string;
  level?: string;
}

interface LocationData {
  cityCode: string;
  cityName: string;
  districtCode: string;
  districtName: string;
  wardCode: string;
  wardName: string;
  address: string;
}

const API_BASE =
  process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';

export function LocationPicker({
  cityCode,
  districtCode,
  wardCode,
  address,
  onChange,
}: {
  cityCode: string;
  districtCode: string;
  wardCode: string;
  address: string;
  onChange: (data: Partial<LocationData>) => void;
}) {
  const [cities, setCities] = useState<LocationOption[]>([]);
  const [districts, setDistricts] = useState<LocationOption[]>([]);
  const [wards, setWards] = useState<LocationOption[]>([]);
  const [loading, setLoading] = useState({ cities: true, districts: false, wards: false });

  useEffect(() => {
    setLoading((l) => ({ ...l, cities: true }));
    fetch(`${API_BASE}/public/locations/cities`)
      .then((r) => r.json())
      .then((d: { data: LocationOption[] }) => setCities(d.data))
      .catch(() => {})
      .finally(() => setLoading((l) => ({ ...l, cities: false })));
  }, []);

  const fetchDistricts = useCallback((code: string) => {
    if (!code) { setDistricts([]); return; }
    setLoading((l) => ({ ...l, districts: true }));
    fetch(`${API_BASE}/public/locations/districts/${code}`)
      .then((r) => r.json())
      .then((d: { data: LocationOption[] }) => setDistricts(d.data))
      .catch(() => {})
      .finally(() => setLoading((l) => ({ ...l, districts: false })));
  }, []);

  const fetchWards = useCallback((code: string) => {
    if (!code) { setWards([]); return; }
    setLoading((l) => ({ ...l, wards: true }));
    fetch(`${API_BASE}/public/locations/wards/${code}`)
      .then((r) => r.json())
      .then((d: { data: LocationOption[] }) => setWards(d.data))
      .catch(() => {})
      .finally(() => setLoading((l) => ({ ...l, wards: false })));
  }, []);

  useEffect(() => { if (cityCode) fetchDistricts(cityCode); }, [cityCode, fetchDistricts]);
  useEffect(() => { if (districtCode) fetchWards(districtCode); }, [districtCode, fetchWards]);

  function handleCity(code: string) {
    const city = cities.find((c) => c.code === code);
    onChange({
      cityCode: code,
      cityName: city?.name ?? '',
      districtCode: '',
      districtName: '',
      wardCode: '',
      wardName: '',
    });
    setDistricts([]);
    setWards([]);
    if (code) fetchDistricts(code);
  }

  function handleDistrict(code: string) {
    const dist = districts.find((d) => d.code === code);
    onChange({
      districtCode: code,
      districtName: dist?.name ?? '',
      wardCode: '',
      wardName: '',
    });
    setWards([]);
    if (code) fetchWards(code);
  }

  function handleWard(code: string) {
    const ward = wards.find((w) => w.code === code);
    onChange({ wardCode: code, wardName: ward?.name ?? '' });
  }

  const selectClass =
    'w-full rounded-2xl border-none bg-surface-container-low px-6 py-4 text-lg text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/30';

  return (
    <div className="space-y-6">
      <div>
        <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
          Tỉnh / Thành phố
        </label>
        <select
          value={cityCode}
          onChange={(e) => handleCity(e.target.value)}
          className={selectClass}
          disabled={loading.cities}
        >
          <option value="">{loading.cities ? 'Đang tải...' : '— Chọn tỉnh/thành —'}</option>
          {cities.map((c) => (
            <option key={c.code} value={c.code}>{c.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
          Quận / Huyện
        </label>
        <select
          value={districtCode}
          onChange={(e) => handleDistrict(e.target.value)}
          className={selectClass}
          disabled={!cityCode || loading.districts}
        >
          <option value="">
            {!cityCode ? '— Chọn tỉnh/thành trước —' : loading.districts ? 'Đang tải...' : '— Chọn quận/huyện —'}
          </option>
          {districts.map((d) => (
            <option key={d.code} value={d.code}>{d.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
          Phường / Xã
        </label>
        <select
          value={wardCode}
          onChange={(e) => handleWard(e.target.value)}
          className={selectClass}
          disabled={!districtCode || loading.wards}
        >
          <option value="">
            {!districtCode ? '— Chọn quận/huyện trước —' : loading.wards ? 'Đang tải...' : '— Chọn phường/xã —'}
          </option>
          {wards.map((w) => (
            <option key={w.code} value={w.code}>{w.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-4 block font-headline text-xl font-bold text-on-surface">
          Số nhà, đường (tuỳ chọn)
        </label>
        <input
          type="text"
          value={address}
          onChange={(e) => onChange({ address: e.target.value })}
          placeholder="Ví dụ: 123 Nguyễn Huệ"
          className="h-16 w-full border-x-0 border-b-4 border-t-0 border-surface-container-high bg-transparent px-0 text-2xl font-medium text-on-surface placeholder:text-outline-variant focus:border-primary focus:outline-none focus:ring-0"
        />
      </div>
    </div>
  );
}
