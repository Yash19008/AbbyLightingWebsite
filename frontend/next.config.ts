import type { NextConfig } from "next";

// Parse the API URL from environment variable
const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
const url = new URL(apiUrl);

const nextConfig: NextConfig = {
  // Removed 'output: export' to enable SSR and API calls
  // Use standalone mode for production deployment

  images: {
    unoptimized: process.env.NODE_ENV === 'development',
    remotePatterns: [
      {
        protocol: 'http',
        hostname: 'localhost',
        pathname: '/**',
      },
      {
        protocol: 'http',
        hostname: '127.0.0.1',
        pathname: '/**',
      },
      {
        protocol: url.protocol.replace(':', '') as 'http' | 'https',
        hostname: url.hostname,
        port: url.port || undefined,
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: 'abby.bandmusic.in',
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: 'abbylighting.com',
        pathname: '/**',
      },
      {
        protocol: 'https',
        hostname: 'www.abbylighting.com',
        pathname: '/**',
      },
    ],
  },
};

export default nextConfig;
