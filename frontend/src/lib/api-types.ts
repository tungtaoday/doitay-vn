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
  username: string | null;
  email: string;
  mobile: string | null;
  avatar: string | null;
  profile_complete: boolean;
  has_company: boolean;
  pending_role: 'customer' | 'contractor' | 'both' | null;
  ev: boolean;
  sv: boolean;
  location: {
    city: string | null;
    district: string | null;
    ward: string | null;
    address: string | null;
  };
  role: 'user' | 'admin' | string;
  status: number;
  created_at: string | null;
}

export interface LocationItem {
  code: string;
  name: string;
}

export interface PublicCategory {
  id: number;
  name: string;
  icon: string | null;
  image: string | null;
}

export interface ServiceRequestMatch {
  company: {
    id: number;
    vanity_slug: string;
    name: string;
    image: string | null;
    category: { id: number; name: string } | null;
    avg_rating: number;
    city: string | null;
    district: string | null;
    short_description: string;
  };
  score: number;
  reasons: string[];
}

export interface ServiceRequestData {
  id: number;
  status: 'open' | 'matched' | 'closed' | 'expired' | 'cancelled';
  title: string;
  description: string;
  category: { id: number; name: string } | null;
  location: {
    city: string;
    district: string | null;
    ward: string | null;
    address: string | null;
  };
  budget: { min: number | null; max: number | null };
  preferred_date: string | null;
  preferred_time_slot: 'morning' | 'afternoon' | 'evening' | 'flexible' | null;
  contact: { name: string; phone: string };
  images: string[];
  is_editable: boolean;
  selected_company_id: number | null;
  selected_appointment_id: number | null;
  expires_at: string | null;
  closed_at: string | null;
  created_at: string | null;
  matches?: ServiceRequestMatch[];
}

export interface ServiceRequestResponse {
  data: ServiceRequestData;
}

export interface UserCompany {
  id: number;
  name: string;
  email: string | null;
  phone: string | null;
  category_id: number;
  description: string;
  experience: number;
  image: string | null;
  status: number;
  status_label: string;
  location: {
    city: string | null;
    district: string | null;
    ward: string | null;
    address: string | null;
  };
  tags: string[];
  services: Array<{ name: string; description?: string; price?: string }>;
  created_at: string | null;
}

export interface CompleteProfileResponse {
  data: AuthUser;
  register_as_expert: boolean;
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
  services: Array<{ name: string; price?: string | null; description?: string | null }> | string[];
  business_hours: unknown;
  portfolios?: { id: number; title: string | null; image: string | null }[];
  ratings_recent?: {
    id: number;
    score: number;
    comment: string | null;
    created_at: string | null;
    user: { name: string | null; avatar: string | null } | null;
    features: { name: string; rating: number }[];
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

export interface Wallet {
  id: number;
  company: { id: number | null; name: string | null };
  balance: number;
  currency: string;
  is_active: boolean;
  created_at: string | null;
}

export interface WalletTotals {
  balance: number;
  spent_total: number;
  bonuses_total: number;
  spent_this_month: number;
}

export interface WalletOverview {
  data: {
    wallets: Wallet[];
    totals: WalletTotals;
    wallet_count: number;
  };
}

export interface WalletTransaction {
  id: number;
  wallet_id: number;
  company_name: string | null;
  type: 'credit' | 'debit';
  transaction_type: string;
  transaction_type_label: string;
  amount: number;
  signed_amount: number;
  balance_before: number;
  balance_after: number;
  description: string | null;
  reference_id: string | null;
  status: string;
  created_at: string | null;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

export interface WalletTransactionsResponse {
  data: WalletTransaction[];
  meta: PaginationMeta;
}

export type DepositStatus = 'pending' | 'processing' | 'completed' | 'rejected' | 'cancelled';

export interface DepositRequest {
  id: number;
  deposit_code: string;
  wallet: { id: number; company_name: string | null };
  amount: number;
  payment_method: string;
  payment_method_label: string;
  status: DepositStatus;
  status_label: string;
  user_notes: string | null;
  payment_proof_url: string | null;
  rejection_reason: string | null;
  processed_at: string | null;
  can_be_cancelled: boolean;
  can_upload_proof: boolean;
  created_at: string | null;
}

export interface DepositListResponse {
  data: DepositRequest[];
  stats: {
    total_requests: number;
    completed_requests: number;
    pending_requests: number;
    total_deposited: number;
  };
  meta: PaginationMeta;
}

export interface DepositMethod {
  id: number;
  name: string;
  payment_method: string;
  qr_code_url: string | null;
  bank: {
    name: string | null;
    branch: string | null;
    account_number: string | null;
    account_name: string | null;
  };
  wallet: { phone: string | null; name: string | null };
  instructions: string | null;
  note_template: string | null;
  min_amount: number;
  max_amount: number | null;
  processing_hours: string | null;
}

// --- Appointments ---

export type AppointmentStatus = 'pending' | 'confirmed' | 'completed' | 'canceled';

export interface AppointmentCompany {
  id: number | null;
  name: string | null;
  image?: string | null;
}

export interface Appointment {
  id: number;
  company: AppointmentCompany;
  recipient_name: string;
  recipient_phone: string;
  recipient_address: string;
  appointment_date: string;
  appointment_time: string;
  notes: string | null;
  status: AppointmentStatus;
  status_label: string;
  can_cancel: boolean;
  can_review: boolean;
  has_rating: boolean;
  created_at: string | null;
}

export interface ThoAppointmentCustomer {
  name: string;
  phone: string;
  address: string;
}

export interface ThoAppointment {
  id: number;
  company: { id: number | null; name: string | null };
  customer: ThoAppointmentCustomer;
  customer_info_unlocked: boolean;
  appointment_date: string;
  appointment_time: string;
  notes: string | null;
  status: AppointmentStatus;
  status_label: string;
  can_confirm: boolean;
  can_complete: boolean;
  can_cancel: boolean;
  confirm_fee: number | null;
  created_at: string | null;
}

export interface ThoAppointmentStats {
  total_balance: number;
  pending_count: number;
  pending_cost: number;
  can_afford_all: boolean;
  confirmed_this_month: number;
  completed_this_month: number;
}

export interface ThoAppointmentListResponse {
  data: ThoAppointment[];
  stats: ThoAppointmentStats;
  meta: PaginationMeta;
}

// --- Ratings ---

export interface RatingDetail {
  feature: string | null;
  rating: number;
}

export interface PublicRating {
  id: number;
  user: { name: string | null };
  avg_rating: number;
  comment: string | null;
  details: RatingDetail[];
  appointment_date: string | null;
  created_at: string | null;
}

export interface RatingFeature {
  id: number;
  name: string;
  description: string | null;
}

// --- Notifications ---

export type NotificationPriority = 'low' | 'normal' | 'high' | string;

export interface UserNotification {
  id: number;
  type: string;
  title: string;
  message: string;
  icon: string | null;
  color: string | null;
  action_url: string | null;
  data: Record<string, unknown> | null;
  is_read: boolean;
  is_important: boolean;
  priority: NotificationPriority;
  read_at: string | null;
  created_at: string | null;
}

export interface NotificationListResponse {
  data: UserNotification[];
  meta: PaginationMeta;
  unread_count: number;
}

export interface SiteSettings {
  site_name: string;
  site_logo: string | null;
  site_logo_dark: string | null;
  site_favicon: string | null;
  colors: { primary: string | null; secondary: string | null };
  currency: { text: string; symbol: string };
  features: {
    registration: boolean;
    maintenance_mode: boolean;
    email_verify: boolean;
    sms_verify: boolean;
    kyc: boolean;
    multi_language: boolean;
    force_ssl: boolean;
  };
  contact: { support_email: string | null; phone: string | null };
  social: { google_enabled: boolean; facebook_enabled: boolean };
  zalo: {
    enabled: boolean;
    phone: string | null;
    name: string | null;
    avatar: string | null;
    message: string | null;
    position: string;
    button_size: string;
    show_mobile: boolean;
  };
  banner: {
    heading: string | null;
    subheading: string | null;
    image: string | null;
  };
}

export interface SeedStats {
  seeded_contractors:  number;
  seeded_customers:    number;
  seeded_appointments: number;
  real_contractors:    number;
  real_customers:      number;
}

