import React from 'react';
import DecorativeListingClient from '@/components/decorative/DecorativeListingClient';
import '@/styles/decorative-products.css';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'Decorative Lighting | Abby Lighting',
  description: 'Sculptural pendants, wall lights, floor and table lamps across our decorative collections.',
};

export default async function DecorativeProductsPage({ searchParams }: { searchParams: Promise<{ [key: string]: string | string[] | undefined }> }) {
  const resolvedSearchParams = await searchParams;
  const category = typeof resolvedSearchParams.category === 'string' ? resolvedSearchParams.category : undefined;
  return <DecorativeListingClient initialCategory={category} />;
}
