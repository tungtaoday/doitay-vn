'use server';

import { api } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { Appointment, WalletTransaction, Paginated } from '@/lib/api-types';

interface WalletOverview {
  data: { totals: { balance: number } };
}

interface TxResponse {
  data: WalletTransaction[];
}

export interface DashboardData {
  balance: number | null;
  recentAppointments: Appointment[];
  recentTransactions: WalletTransaction[];
}

export async function fetchDashboardDataAction(): Promise<DashboardData> {
  const token = await getToken();
  if (!token) return { balance: null, recentAppointments: [], recentTransactions: [] };

  const [walletRes, aptRes, txRes] = await Promise.allSettled([
    api<WalletOverview>('/user/wallet', { token }),
    api<Paginated<Appointment>>('/user/appointments?page=1&per_page=3', { token }),
    api<TxResponse>('/user/wallet/transactions?per_page=4', { token }),
  ]);

  return {
    balance: walletRes.status === 'fulfilled' ? (walletRes.value.data?.totals?.balance ?? null) : null,
    recentAppointments: aptRes.status === 'fulfilled' ? (aptRes.value.data ?? []) : [],
    recentTransactions: txRes.status === 'fulfilled' ? (txRes.value.data ?? []) : [],
  };
}
