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
      { protocol: 'http',  hostname: 'localhost' },
    ],
  },
};

export default nextConfig;
