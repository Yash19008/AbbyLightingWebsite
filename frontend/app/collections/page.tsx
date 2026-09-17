import CollectionHero from '@/components/collections/CollectionHero';
import ParametersSection from '@/components/collections/ParametersSection';
import CompositionsSection from '@/components/collections/CompositionsSection';
import ProductsSection from '@/components/collections/ProductsSection';
import TonesSection from '@/components/collections/TonesSection';
import ScoreSection from '@/components/collections/ScoreSection';
import PlacesSection from '@/components/collections/PlacesSection';
import CatalogueSection from '@/components/collections/CatalogueSection';
import RelatedCollections from '@/components/collections/RelatedCollections';
import MobileNav from '@/components/collections/MobileNav';
import '../../styles/collections.css';

export const metadata = {
  title: 'Symphony Collection | Abby Lighting',
  description: 'The Symphony Collection is a modular family of sculptural pendants. Choose a form, choose a tone, and compose it across your ceiling.',
};

export default function CollectionsPage() {
  return (
    <div className="symphony-page">
      <CollectionHero />
      <ParametersSection />
      <CompositionsSection data={{ title: '', subtitle: '', items: [] }} />
      <ProductsSection products={[]} collectionName="Symphony" />
      <TonesSection />
      <ScoreSection />
      <PlacesSection />
      <CatalogueSection />
      <RelatedCollections collections={[]} />
      <MobileNav />
    </div>
  );
}
