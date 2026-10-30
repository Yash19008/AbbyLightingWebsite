import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { getCollection, getCollections } from '@/lib/api/collections';
import HeroSection from '@/components/collections/HeroSection';
import ParametersSectionDynamic from '@/components/collections/ParametersSectionDynamic';
import CompositionsSectionDynamic from '@/components/collections/CompositionsSectionDynamic';
import ProductsSection from '@/components/collections/ProductsSection';
import TonesSectionDynamic from '@/components/collections/TonesSectionDynamic';
import ScoreSection from '@/components/collections/ScoreSection';
import PlacesSectionDynamic from '@/components/collections/PlacesSectionDynamic';
import CatalogueSection from '@/components/collections/CatalogueSection';
import RelatedCollections from '@/components/collections/RelatedCollections';
import { buildPageMetadata } from '@/lib/seo/meta-config';
import '../../../styles/collections.css';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

interface CollectionPageProps {
  params: Promise<{
    slug: string;
  }>;
}

export async function generateMetadata({ params }: CollectionPageProps): Promise<Metadata> {
  try {
    const { slug } = await params;
    const response = await getCollection(slug);
    const collection = response?.data;

    if (!collection) {
      return buildPageMetadata('collectionsDetailDefault', {
        title: 'Collection Not Found | Abby Lighting',
        description: 'The requested lighting collection could not be found.',
        noindex: true,
      });
    }

    const title = collection.meta_title || `${collection.name} Collection | Abby Lighting`;
    const description =
      collection.meta_description ||
      collection.hero_section?.description ||
      `Explore the ${collection.name} modular architectural and decorative lighting collection by Abby Lighting.`;
    const canonical = `/collections/${slug}`;

    return buildPageMetadata('collectionsDetailDefault', {
      title,
      description,
      canonical,
    });
  } catch {
    return buildPageMetadata('collectionsDetailDefault', {
      title: 'Collection | Abby Lighting',
      noindex: true,
    });
  }
}

export default async function CollectionPage({ params }: CollectionPageProps) {
  try {
    // Await params (Next.js 15 requirement)
    const { slug } = await params;
    
    // Fetch collection data from API
    const [response, collectionsRes] = await Promise.all([
      getCollection(slug),
      getCollections()
    ]);
    
    if (!response.success) {
      notFound();
    }

    const collection = response.data;
    const otherCollections = collectionsRes.success 
      ? collectionsRes.data.filter(c => c.slug !== slug) 
      : [];

    return (
      <div className="symphony-page">
        {/* Dynamic Hero Section - Fetched from API */}
        {collection.hero_section && (
          <HeroSection 
            heroSection={collection.hero_section} 
            collectionName={collection.name}
          />
        )}

        {/* Parameters Section - Dynamic if data exists */}
        {collection.parameters_section && (
          <ParametersSectionDynamic
            parametersSection={collection.parameters_section}
            collectionSlug={collection.slug}
          />
        )}

        {/* Compositions Section - Dynamic if data exists */}
        {collection.compositions_section && (
          <CompositionsSectionDynamic 
            compositionsSection={collection.compositions_section} 
            collectionName={collection.name}
          />
        )}

        {/* Products Section - Dynamic if data exists */}
        {collection.products_section?.is_active && (
          <ProductsSection 
            products={collection.products || []} 
            collectionName={collection.name} 
            sectionData={collection.products_section} 
          />
        )}
        
        {/* Tones Section - Dynamic if data exists */}
        {collection.tones_section && (
          <TonesSectionDynamic tonesSection={collection.tones_section} />
        )}
        
        {collection.spread_drop_section?.is_active && <ScoreSection />}
        
        {/* Places Section - Dynamic if data exists */}
        {collection.places_section && (
          <PlacesSectionDynamic placesSection={collection.places_section} />
        )}

        {/* Catalogue Section - Dynamic if data exists */}
        {collection.catalogue_section?.is_active && (
          <CatalogueSection catalogueSection={collection.catalogue_section} />
        )}
        <RelatedCollections collections={otherCollections} />
      </div>
    );
  } catch (error) {
    console.error('Error loading collection:', error);
    notFound();
  }
}
