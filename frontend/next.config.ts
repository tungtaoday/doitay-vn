import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  typedRoutes: true,
  async redirects() {
    return [
      // Hợp nhất trang danh sách trùng lặp: /cong-ty -> /tho (308 thật ở tầng HTTP,
      // tốt cho SEO — page-level permanentRedirect chỉ ra 200 + client redirect do streaming).
      // Chỉ match ĐÚNG /cong-ty; KHÔNG ảnh hưởng /cong-ty/[id]/... (URL parity legacy).
      { source: '/cong-ty', destination: '/tho', permanent: true },
    ];
  },
  experimental: {
    serverActions: {
      bodySizeLimit: '2mb',
    },
  },
  images: {
    remotePatterns: [
      { protocol: 'https', hostname: '**.doitay.vn' },
      { protocol: 'https', hostname: 'doitay.vn' },
      { protocol: 'http',  hostname: 'doitay.vn' },
      { protocol: 'https', hostname: 'images.unsplash.com' },
      { protocol: 'https', hostname: '**.unsplash.com' },
      { protocol: 'http',  hostname: '165.22.252.188' },
      { protocol: 'http',  hostname: 'localhost' },
    ],
  },
};

export default nextConfig;
