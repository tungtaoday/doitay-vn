import type { NextConfig } from 'next';

const nextConfig: NextConfig = {
  typedRoutes: true,
  images: {
    remotePatterns: [
      { protocol: 'https', hostname: '**.doitay.vn' },
      { protocol: 'http',  hostname: 'localhost' },
    ],
  },
};

export default nextConfig;
