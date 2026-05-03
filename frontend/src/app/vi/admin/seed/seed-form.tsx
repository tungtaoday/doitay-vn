'use client';

import { useActionState, useState } from 'react';
import { runSeedAction, type RunResult } from './actions';

export function SeedForm() {
  const [state, formAction, isPending] = useActionState<RunResult | null, FormData>(
    runSeedAction,
    null,
  );

  const [contractors,  setContractors]  = useState(3);
  const [customers,    setCustomers]    = useState(5);
  const [appointments, setAppointments] = useState(8);

  return (
    <div className="rounded-3xl bg-surface-container-lowest p-8 shadow-ambient">
      <div className="mb-8">
        <h2 className="font-headline text-2xl font-bold text-on-surface">
          Chạy seed thủ công
        </h2>
        <p className="mt-2 text-sm text-outline">
          Agent AI sẽ tạo hồ sơ + ảnh và đẩy vào DB qua background process.
          Quá trình mất ~2-3 phút tùy số lượng.
        </p>
      </div>

      {state && !state.ok && (
        <div className="mb-6 rounded-xl bg-error-container p-4 text-sm font-medium text-on-error-container">
          {state.error}
        </div>
      )}
      {state && state.ok && (
        <div className="mb-6 rounded-xl bg-primary-container/20 p-4 text-sm text-on-primary-container">
          <span className="material-symbols-outlined fill mr-2 align-middle text-base text-primary">
            check_circle
          </span>
          {state.message}
          {state.log && (
            <span className="ml-2 text-xs text-outline">
              Log: <code>{state.log}</code>
            </span>
          )}
        </div>
      )}

      <form action={formAction} className="space-y-6">
        <div className="grid grid-cols-3 gap-4">
          <CountInput
            label="Thợ mới"
            name="contractors"
            icon="construction"
            value={contractors}
            onChange={setContractors}
            max={20}
          />
          <CountInput
            label="Khách mới"
            name="customers"
            icon="person_add"
            value={customers}
            onChange={setCustomers}
            max={20}
          />
          <CountInput
            label="Lịch hẹn"
            name="appointments"
            icon="calendar_month"
            value={appointments}
            onChange={setAppointments}
            max={50}
          />
        </div>

        <div className="flex items-center gap-4 pt-2">
          <button
            type="submit"
            disabled={isPending}
            className="flex items-center gap-2 rounded-xl bg-primary px-8 py-3 font-headline font-bold text-on-primary transition-all active:scale-95 disabled:opacity-60"
          >
            <span className="material-symbols-outlined text-xl">
              {isPending ? 'hourglass_empty' : 'play_arrow'}
            </span>
            {isPending ? 'Đang dispatch...' : 'Chạy ngay'}
          </button>
          <p className="text-xs text-outline">
            Process chạy nền — trang này không cần chờ
          </p>
        </div>
      </form>
    </div>
  );
}

function CountInput({
  label, name, icon, value, onChange, max,
}: {
  label: string; name: string; icon: string;
  value: number; onChange: (v: number) => void; max: number;
}) {
  return (
    <div className="rounded-2xl bg-surface-container-low p-4">
      <div className="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-outline">
        <span className="material-symbols-outlined text-sm text-primary">{icon}</span>
        {label}
      </div>
      <div className="flex items-center gap-2">
        <button
          type="button"
          onClick={() => onChange(Math.max(0, value - 1))}
          className="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-container text-on-surface transition-colors hover:bg-primary hover:text-on-primary"
        >
          <span className="material-symbols-outlined text-sm">remove</span>
        </button>
        <input
          type="number"
          name={name}
          value={value}
          onChange={e => onChange(Math.min(max, Math.max(0, Number(e.target.value))))}
          className="w-12 bg-transparent text-center font-headline text-xl font-bold text-on-surface focus:outline-none"
          min={0}
          max={max}
        />
        <button
          type="button"
          onClick={() => onChange(Math.min(max, value + 1))}
          className="flex h-8 w-8 items-center justify-center rounded-lg bg-surface-container text-on-surface transition-colors hover:bg-primary hover:text-on-primary"
        >
          <span className="material-symbols-outlined text-sm">add</span>
        </button>
      </div>
    </div>
  );
}
