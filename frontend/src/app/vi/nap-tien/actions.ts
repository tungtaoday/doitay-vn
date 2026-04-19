'use server';

import { revalidatePath } from 'next/cache';
import { redirect } from 'next/navigation';
import type { Route } from 'next';
import { api, ApiError } from '@/lib/api';
import { getToken } from '@/lib/auth';
import type { DepositRequest, DetailEnvelope } from '@/lib/api-types';

type ActionResult = { ok: true } | { ok: false; error: string };

export async function createDeposit(formData: FormData): Promise<ActionResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập hết hạn' };

  const walletId = formData.get('wallet_id');
  const methodId = formData.get('payment_method_id');
  const amount = formData.get('amount');
  const notes = formData.get('user_notes');
  const proof = formData.get('payment_proof');

  if (!walletId || !methodId || !amount) {
    return { ok: false, error: 'Thiếu thông tin bắt buộc' };
  }

  const body = new FormData();
  body.set('wallet_id', String(walletId));
  body.set('payment_method_id', String(methodId));
  body.set('amount', String(amount));
  if (notes) body.set('user_notes', String(notes));
  if (proof instanceof File && proof.size > 0) body.set('payment_proof', proof);
  body.set('idempotency_key', `${walletId}-${methodId}-${amount}-${Date.now()}`);

  let deposit: DepositRequest;
  try {
    const res = await api<DetailEnvelope<DepositRequest>>('/user/deposits', {
      method: 'POST',
      body,
      token,
    });
    deposit = res.data;
  } catch (e) {
    if (e instanceof ApiError) {
      const msg =
        (e.body as { message?: string } | null)?.message ??
        (e.status === 422 ? 'Dữ liệu không hợp lệ' : 'Có lỗi xảy ra');
      return { ok: false, error: msg };
    }
    throw e;
  }

  revalidatePath('/vi/nap-tien/lich-su');
  revalidatePath('/vi/wallet');
  redirect(`/vi/nap-tien/${deposit.id}` as Route);
}

export async function cancelDeposit(id: number): Promise<ActionResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập hết hạn' };

  try {
    await api(`/user/deposits/${id}/cancel`, { method: 'POST', token });
  } catch (e) {
    if (e instanceof ApiError) {
      return {
        ok: false,
        error: (e.body as { message?: string } | null)?.message ?? 'Không hủy được',
      };
    }
    throw e;
  }

  revalidatePath(`/vi/nap-tien/${id}`);
  revalidatePath('/vi/nap-tien/lich-su');
  return { ok: true };
}

export async function uploadDepositProof(id: number, formData: FormData): Promise<ActionResult> {
  const token = await getToken();
  if (!token) return { ok: false, error: 'Phiên đăng nhập hết hạn' };

  const proof = formData.get('payment_proof');
  if (!(proof instanceof File) || proof.size === 0) {
    return { ok: false, error: 'Vui lòng chọn ảnh chứng minh' };
  }

  const body = new FormData();
  body.set('payment_proof', proof);

  try {
    await api(`/user/deposits/${id}/upload-proof`, { method: 'POST', body, token });
  } catch (e) {
    if (e instanceof ApiError) {
      return {
        ok: false,
        error: (e.body as { message?: string } | null)?.message ?? 'Upload thất bại',
      };
    }
    throw e;
  }

  revalidatePath(`/vi/nap-tien/${id}`);
  return { ok: true };
}
