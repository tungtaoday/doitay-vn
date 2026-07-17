'use client';

import Script from 'next/script';
import { useRouter } from 'next/navigation';
import { useEffect, useRef, useState } from 'react';
import { loginWithGoogle, loginWithFacebook } from '@/app/(auth)/login/actions';

declare global {
  interface Window {
    google?: {
      accounts: {
        id: {
          initialize: (config: {
            client_id: string;
            callback: (res: { credential: string }) => void;
            auto_select?: boolean;
          }) => void;
          renderButton: (
            el: HTMLElement,
            opts: { theme?: string; size?: string; width?: number; text?: string },
          ) => void;
        };
      };
    };
    FB?: {
      init: (cfg: { appId: string; version: string; cookie?: boolean; xfbml?: boolean }) => void;
      login: (
        cb: (res: { authResponse?: { accessToken: string } | null; status: string }) => void,
        opts?: { scope: string },
      ) => void;
    };
    fbAsyncInit?: () => void;
  }
}

const GOOGLE_CLIENT_ID = process.env.NEXT_PUBLIC_GOOGLE_CLIENT_ID ?? '';
const FACEBOOK_APP_ID = process.env.NEXT_PUBLIC_FACEBOOK_APP_ID ?? '';

/** true khi có ít nhất 1 nhà cung cấp đăng nhập xã hội được cấu hình. */
export const SOCIAL_LOGIN_ENABLED = Boolean(GOOGLE_CLIENT_ID || FACEBOOK_APP_ID);

export function SocialButtons() {
  const router = useRouter();
  const googleBtnRef = useRef<HTMLDivElement>(null);
  const [error, setError] = useState<string | null>(null);
  const [pending, setPending] = useState(false);

  // Chưa cấu hình provider nào → không hiện gì (tránh lộ "chưa cấu hình" cho user).
  if (!SOCIAL_LOGIN_ENABLED) return null;

  useEffect(() => {
    if (!GOOGLE_CLIENT_ID || !window.google || !googleBtnRef.current) return;
    window.google.accounts.id.initialize({
      client_id: GOOGLE_CLIENT_ID,
      callback: async (res) => {
        setPending(true);
        setError(null);
        const result = await loginWithGoogle(res.credential);
        setPending(false);
        if (result.ok) router.replace('/');
        else setError(result.error);
      },
    });
    window.google.accounts.id.renderButton(googleBtnRef.current, {
      theme: 'outline',
      size: 'large',
      width: 360,
      text: 'continue_with',
    });
  }, [router]);

  function handleGsiLoad() {
    if (!GOOGLE_CLIENT_ID || !window.google || !googleBtnRef.current) return;
    window.google.accounts.id.initialize({
      client_id: GOOGLE_CLIENT_ID,
      callback: async (res) => {
        setPending(true);
        setError(null);
        const result = await loginWithGoogle(res.credential);
        setPending(false);
        if (result.ok) router.replace('/');
        else setError(result.error);
      },
    });
    window.google.accounts.id.renderButton(googleBtnRef.current, {
      theme: 'outline',
      size: 'large',
      width: 360,
      text: 'continue_with',
    });
  }

  function handleFbInit() {
    if (!FACEBOOK_APP_ID || !window.FB) return;
    window.FB.init({ appId: FACEBOOK_APP_ID, version: 'v18.0', cookie: false, xfbml: false });
  }

  function handleFacebookClick() {
    if (!window.FB) {
      setError('Facebook SDK chưa sẵn sàng');
      return;
    }
    window.FB.login(
      async (res) => {
        if (!res.authResponse) return;
        setPending(true);
        setError(null);
        const result = await loginWithFacebook(res.authResponse.accessToken);
        setPending(false);
        if (result.ok) router.replace('/');
        else setError(result.error);
      },
      { scope: 'public_profile,email' },
    );
  }

  return (
    <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
      {GOOGLE_CLIENT_ID ? (
        <>
          <Script
            src="https://accounts.google.com/gsi/client"
            strategy="afterInteractive"
            onLoad={handleGsiLoad}
          />
          <div ref={googleBtnRef} className="flex justify-center" />
        </>
      ) : null}

      {FACEBOOK_APP_ID ? (
        <>
          <Script
            src="https://connect.facebook.net/en_US/sdk.js"
            strategy="afterInteractive"
            onLoad={handleFbInit}
          />
          <button
            type="button"
            onClick={handleFacebookClick}
            disabled={pending}
            className="flex h-[72px] w-full items-center justify-center gap-3 rounded-lg border-none bg-secondary-container text-[1.125rem] font-bold text-on-surface transition-colors hover:bg-surface-container-highest disabled:opacity-60"
          >
            <svg className="h-8 w-8 fill-[#1877F2]" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
            </svg>
            Facebook
          </button>
        </>
      ) : null}

      {error ? (
        <div className="col-span-full rounded-lg bg-error-container px-6 py-4 text-[1rem] font-medium text-on-error-container">
          {error}
        </div>
      ) : null}
    </div>
  );
}
