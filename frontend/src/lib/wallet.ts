import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type {
  DepositListResponse,
  DepositMethod,
  DepositRequest,
  DetailEnvelope,
  WalletOverview,
  WalletTransactionsResponse,
} from '@/lib/api-types';

async function authed<T>(path: string, init: RequestInit = {}): Promise<T> {
  const token = await getToken();
  if (!token) throw new ApiError(401, null, 'No auth token');
  return api<T>(path, { ...init, token });
}

export async function getWalletOverview(): Promise<WalletOverview> {
  return authed<WalletOverview>('/user/wallet');
}

export async function getWalletTransactions(
  params: Record<string, string | undefined> = {},
): Promise<WalletTransactionsResponse> {
  const query = new URLSearchParams();
  for (const [k, v] of Object.entries(params)) {
    if (v) query.set(k, v);
  }
  const qs = query.toString();
  return authed<WalletTransactionsResponse>(
    `/user/wallet/transactions${qs ? `?${qs}` : ''}`,
  );
}

export async function getDepositMethods(): Promise<DepositMethod[]> {
  const res = await api<{ data: DepositMethod[] }>('/public/deposit-methods');
  return res.data;
}

export async function getDepositList(page = 1): Promise<DepositListResponse> {
  return authed<DepositListResponse>(`/user/deposits?page=${page}`);
}

export async function getDepositDetail(id: number): Promise<DepositRequest> {
  const res = await authed<DetailEnvelope<DepositRequest>>(`/user/deposits/${id}`);
  return res.data;
}
