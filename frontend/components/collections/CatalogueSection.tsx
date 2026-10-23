import Link from 'next/link';
import { CatalogueSection as CatalogueSectionType } from '@/types/collection';

interface Props {
  catalogueSection?: CatalogueSectionType | null;
}

export default function CatalogueSection({ catalogueSection }: Props) {
  const desktopImg = catalogueSection?.background_image || '/images/symphony/catalogue.png';
  const tabletImg = catalogueSection?.image_tablet || desktopImg;
  const mobileImg = catalogueSection?.image_mobile || tabletImg || desktopImg;
  const title = catalogueSection?.title || 'See the whole';
  const titleHighlight = catalogueSection?.title_highlight || 'collection.';
  const btnText = catalogueSection?.button_text || 'Download catalogue';
  const btnLink = catalogueSection?.button_link || '/catalogues';

  return (
    <section className="s-catalogue">
      <picture className="s-catalogue-picture">
        {mobileImg && <source media="(max-width: 640px)" srcSet={mobileImg} />}
        {tabletImg && <source media="(max-width: 1024px)" srcSet={tabletImg} />}
        <img
          src={desktopImg}
          alt="Catalogue background"
          className="s-catalogue-image"
        />
      </picture>
      <div>
        <h2>
          {title} {titleHighlight && <em>{titleHighlight}</em>}
        </h2>
        <Link href={btnLink}>{btnText}</Link>
      </div>
    </section>
  );
}
