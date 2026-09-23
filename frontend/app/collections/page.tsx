import { redirect, notFound } from 'next/navigation';
import { getCollections } from '@/lib/api/collections';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'Collections | Abby Lighting',
  description: 'Explore decorative and architectural lighting collections from Abby Lighting.',
};

export default async function CollectionsPage() {
  try {
    const res = await getCollections();
    if (res.success && Array.isArray(res.data) && res.data.length > 0) {
      redirect(`/collections/${res.data[0].slug}`);
    }
  } catch (error) {
    console.error('Error fetching collections:', error);
  }

  notFound();
}
