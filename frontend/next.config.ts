import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  typedRoutes: true,
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
