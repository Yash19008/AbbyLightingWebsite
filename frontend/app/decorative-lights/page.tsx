import React from 'react';
import type { Metadata } from 'next';
import DecorativeListingClient from '@/components/decorative/DecorativeListingClient';
import { getDecorativeHero } from '@/lib/api/decorative-hero';
import { buildPageMetadata } from '@/lib/seo/meta-config';
import '@/styles/decorative-products.css';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function generateMetadata(): Promise<Metadata> {
  try {
    const heroRes = await getDecorativeHero();
    const hero = heroRes?.data;
    const title = hero?.heading
      ? `${hero.heading} ${hero.styled_heading || ''}`.trim() + ' | Abby Lighting'
      : undefined;
    const description = hero?.description || undefined;

    return buildPageMetadata('decorativeLights', {
      title,
      description,
    });
  } catch {
    return buildPageMetadata('decorativeLights');
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
