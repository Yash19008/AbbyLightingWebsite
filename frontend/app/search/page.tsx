import type { Metadata } from 'next';
import { redirect } from 'next/navigation';
import { buildPageMetadata } from '@/lib/seo/meta-config';

export const metadata: Metadata = buildPageMetadata('search');

export default async function SearchPage({
  searchParams,
}: {
  searchParams: Promise<{ [key: string]: string | string[] | undefined }>;
}) {
  const params = await searchParams;
  const q = typeof params.q === 'string' ? params.q : '';
  const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000';
  
  redirect(`${apiUrl}/search?q=${encodeURIComponent(q)}`);

  return (
    <div style={{ padding: '4rem', textAlign: 'center' }}>
      <p>Redirecting to search...</p>
    </div>
  );
}
