/**
 * Single entry point for ALL backend requests.
 *
 * Rules:
 *   1. Never call `fetch` to the backend directly elsewhere — always go
 *      through `api()` so logging, auth, error shape, and base URL stay
 *      in one place.
 *   2. RSC reads pass `token` explicitly (read from httpOnly cookie via
 *      `getToken()`). Client components do not need to pass a token —
 *      mutations are routed through Next.js server actions which forward
 *      the cookie themselves.
 */

const BASE =
  process.env.INTERNAL_API_URL ??
  process.env.NEXT_PUBLIC_API_URL ??
  'http://localhost:8000/api/v1';

export class ApiError extends Error {
  constructor(
    public readonly status: number,
    public readonly body: unknown,
    message?: string,
  ) {
    super(message ?? `API error ${status}`);
    this.name = 'ApiError';
  }
}

export interface ApiOptions extends Omit<RequestInit, 'body'> {
  /** Object body — will be JSON-stringified. */
  json?: unknown;
  /** FormData / string body passed through as-is (for multipart uploads). */
  body?: BodyInit | null;
  /** Bearer token for the Authorization header. */
  token?: string;
}

export async function api<T>(path: string, opts: ApiOptions = {}): Promise<T> {
  const { json, body: rawBody, token, headers, ...rest } = opts;

  const init: RequestInit = {
    cache: 'no-store',
    ...rest,
    headers: {
      Accept: 'application/json',
      ...(json !== undefined ? { 'Content-Type': 'application/json' } : {}),
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...headers,
    },
    ...(json !== undefined
      ? { body: JSON.stringify(json) }
      : rawBody !== undefined && rawBody !== null
      ? { body: rawBody }
      : {}),
  };

  const url = path.startsWith('http') ? path : `${BASE}${path}`;
  const res = await fetch(url, init);

  if (res.status === 204) {
    return undefined as T;
  }

  let responseBody: unknown = null;
  try {
    responseBody = await res.json();
  } catch {
    // non-JSON response
  }

  if (!res.ok) {
    throw new ApiError(res.status, responseBody);
  }

  return responseBody as T;
}
