/**
 * Hand-maintained types mirroring Laravel JsonResource output shapes.
 * Phase 1: maintain manually. Phase 2 will switch to OpenAPI generation
 * via dedoc/scramble + openapi-typescript.
 *
 * If a Laravel Resource changes its shape, update the matching interface
 * here in the same PR — otherwise the frontend silently breaks at runtime.
 */

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  mobile: string | null;
  avatar: string | null;
  role: 'user' | 'admin' | string;
  status: number;
  created_at: string | null;
}

export interface LoginResponse {
  token: string;
  user: AuthUser;
}

export interface RegisterResponse {
  token: string;
  user: AuthUser;
}

export interface MeResponse {
  data: AuthUser;
}

export interface PublicCompanyListItem {
  id: number;
  /** Derived from `name` server-side; used for SEO URL `/cong-ty/{id}/{vanity_slug}`. */
  vanity_slug: string;
  name: string;
  image: string | null;
  short_description: string | null;
  category?: { id: number; name: string };
  location: { district: string | null; city: string | null; state: string | null };
  rating_avg: number;
  rating_count: number;
  experience: number;
}

export interface PublicCompanyDetail extends PublicCompanyListItem {
  description: string | null;
  tags: string[];
  services: string[];
  business_hours: unknown;
  portfolios?: { id: number; title: string | null; image: string | null }[];
  ratings_recent?: {
    id: number;
    score: number;
    comment: string | null;
    created_at: string | null;
    user: { name: string | null; avatar: string | null } | null;
  }[];
  show_contact: boolean;
  phone: string | null;
  email: string | null;
  website: string | null;
  created_at: string | null;
}

export interface Paginated<T> {
  data: T[];
  links: { first: string; last: string; prev: string | null; next: string | null };
  meta: {
    current_page: number;
    from: number | null;
    to: number | null;
    total: number;
    per_page: number;
    last_page: number;
    version?: string;
  };
}

export interface DetailEnvelope<T> {
  data: T;
}
