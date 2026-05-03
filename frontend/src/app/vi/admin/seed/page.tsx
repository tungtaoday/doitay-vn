import type { Metadata } from 'next';
import { getToken } from '@/lib/auth';
import { api } from '@/lib/api';
import type { SeedStats } from '@/lib/api-types';
import { SeedForm } from './seed-form';

export const metadata: Metadata = { title: 'Seed Data | Admin' };

async function getStats(): Promise<SeedStats | null> {
  const token = await getToken();
  if (!token) return null;
  try {
    const res = await api<{ data: SeedStats }>('/admin/seed/stats', { token });
    return res.data;
  } catch { return null; }
}

export default async function AdminSeedPage() {
  const stats = await getStats();

  return (
    <div className="space-y-10">
      {/* Header */}
      <div>
        <span className="text-sm font-bold uppercase tracking-[0.2em] text-primary">
          Admin — Seed Data
        </span>
        <h1 className="mt-2 font-headline text-3xl font-bold text-on-surface">
          Marketplace Seed Agent
        </h1>
        <p className="mt-2 text-on-surface-variant">
          Tạo dữ liệu giả (thợ, khách, lịch hẹn) bằng Claude AI + Google Imagen.
          Dữ liệu seed được đánh dấu <code className="rounded bg-surface-container-highest px-1 text-xs">is_seeded=1</code> và không ảnh hưởng real users.
        </p>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-2 gap-4 md:grid-cols-5">
        <StatCard label="Thợ seed" value={stats?.seeded_contractors} icon="construction" accent="primary" />
        <StatCard label="Khách seed" value={stats?.seeded_customers} icon="person" accent="primary" />
        <StatCard label="Lịch hẹn seed" value={stats?.seeded_appointments} icon="calendar_month" accent="primary" />
        <StatCard label="Thợ thật" value={stats?.real_contractors} icon="verified" accent="tertiary" />
        <StatCard label="Khách thật" value={stats?.real_customers} icon="group" accent="tertiary" />
      </div>

      {/* Trigger form */}
      <SeedForm />

      {/* Cron section */}
      <div className="rounded-3xl border border-outline-variant/20 bg-surface-container-low p-8">
        <h2 className="mb-4 font-headline text-xl font-bold text-on-surface">
          Tự động — Cron Job (production)
        </h2>
        <p className="mb-4 text-sm text-on-surface-variant">
          SSH vào server rồi chạy <code className="rounded bg-surface-container-highest px-1">crontab -e</code> và thêm dòng sau
          để seed tự động lúc 2:00 sáng mỗi ngày:
        </p>
        <pre className="overflow-x-auto rounded-xl bg-on-background p-4 text-sm text-primary-fixed">
          <code>{`0 2 * * * cd /var/www/doitay-nextjs/agent-system && python3 scripts/run_seed.py 3 5 8 >> /var/log/doitay-seed.log 2>&1`}</code>
        </pre>
        <p className="mt-3 text-xs text-outline">
          Đảm bảo file <code>.env</code> có <code>DOITAY_API_BASE</code> trỏ tới <code>http://localhost:8080/api/v1</code> (hoặc internal API URL của production).
        </p>
      </div>
    </div>
  );
}

function StatCard({
  label, value, icon, accent,
}: {
  label: string;
  value: number | undefined;
  icon: string;
  accent: 'primary' | 'tertiary';
}) {
  const colorClass = accent === 'tertiary' ? 'text-tertiary' : 'text-primary';
  return (
    <div className="rounded-2xl bg-surface-container-lowest p-5 shadow-soft">
      <span className={`material-symbols-outlined mb-2 block text-xl ${colorClass}`}>
        {icon}
      </span>
      <div className="font-headline text-2xl font-bold text-on-surface">
        {value ?? '—'}
      </div>
      <div className="mt-1 text-xs font-medium text-outline">{label}</div>
    </div>
  );
}
