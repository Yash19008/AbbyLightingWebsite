import React from 'react';
import DecorativeListingClient from '@/components/decorative/DecorativeListingClient';
import { getDecorativeHero } from '@/lib/api/decorative-hero';
import '@/styles/decorative-products.css';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function generateMetadata() {
  try {
    const heroRes = await getDecorativeHero();
    const hero = heroRes?.data;
    const title = hero?.heading
      ? `${hero.heading} ${hero.styled_heading || ''}`.trim() + ' | Abby Lighting'
      : 'Decorative Lighting | Abby Lighting';
    const description = hero?.description || 'Sculptural pendants, wall lights, floor and table lamps across our decorative collections.';
    return {
      title,
      description,
    };
  } catch {
    return {
      title: 'Decorative Lighting | Abby Lighting',
      description: 'Sculptural pendants, wall lights, floor and table lamps across our decorative collections.',
    };
  }
}

export default async function DecorativeLightsPage({ searchParams }: { searchParams: Promise<{ [key: string]: string | string[] | undefined }> }) {
  const [resolvedSearchParams, heroRes] = await Promise.all([
    searchParams,
    getDecorativeHero(),
  ]);
  const category = typeof resolvedSearchParams.category === 'string' ? resolvedSearchParams.category : undefined;
  return <DecorativeListingClient initialCategory={category} initialHero={heroRes?.data} />;
}
