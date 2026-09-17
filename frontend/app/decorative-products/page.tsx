import React from 'react';
import DecorativeListingClient from '@/components/decorative/DecorativeListingClient';
import '@/styles/decorative-products.css';

export const metadata = {
  title: 'Decorative Lighting | Abby Lighting',
  description: 'Sculptural pendants, wall lights, floor and table lamps across our decorative collections.',
};

export default function DecorativeProductsPage() {
  return <DecorativeListingClient />;
}
