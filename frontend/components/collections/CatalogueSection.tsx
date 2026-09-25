import Image from 'next/image';
import Link from 'next/link';
import { CatalogueSection as CatalogueSectionType } from '@/types/collection';

interface Props {
  catalogueSection?: CatalogueSectionType | null;
}

export default function CatalogueSection({ catalogueSection }: Props) {
  const bgImage = catalogueSection?.background_image || '/images/symphony/catalogue.png';
  const title = catalogueSection?.title || 'See the whole';
  const titleHighlight = catalogueSection?.title_highlight || 'collection.';
  const btnText = catalogueSection?.button_text || 'Download catalogue';
  const btnLink = catalogueSection?.button_link || '/catalogues';

  return (
    <section className="s-catalogue">
      <Image
        src={bgImage}
        alt="Catalogue background"
        fill
        style={{ objectFit: 'cover' }}
      />
      <div>
        <h2>
          {title} {titleHighlight && <em>{titleHighlight}</em>}
        </h2>
        <Link href={btnLink}>{btnText}</Link>
      </div>
    </section>
  );
}
