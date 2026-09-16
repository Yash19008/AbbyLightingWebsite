import type { HomeCatalogueSection } from "@/types/home-catalogue-section";

interface CatalogueSectionProps {
  data?: HomeCatalogueSection | null;
}

function renderTitle(title?: string | null, highlight?: string | null) {
  const cleanTitle = (title || "").trim();
  const cleanHighlight = (highlight || "").trim();

  if (!cleanHighlight) {
    return cleanTitle || "Find the right catalogue.";
  }

  if (!cleanTitle) {
    return <em>{cleanHighlight}</em>;
  }

  // Check if highlight is a substring of title (case-insensitive)
  const lowerTitle = cleanTitle.toLowerCase();
  const lowerHighlight = cleanHighlight.toLowerCase();
  const index = lowerTitle.indexOf(lowerHighlight);

  if (index !== -1) {
    const before = cleanTitle.slice(0, index);
    const matched = cleanTitle.slice(index, index + cleanHighlight.length);
    const after = cleanTitle.slice(index + cleanHighlight.length);
    return (
      <>
        {before}
        <em>{matched}</em>
        {after}
      </>
    );
  }

  // If highlight is separate from title (e.g. Title: "Find the right", Highlight: "catalogue.")
  return (
    <>
      {cleanTitle}{" "}
      <em>{cleanHighlight}</em>
    </>
  );
}

export default function CatalogueSection({ data }: CatalogueSectionProps) {
  // If no dynamic catalogue data exists or disabled in admin, hide section completely
  if (!data || data.is_active === false) {
    return null;
  }

  return (
    <section className="image-cta">
      {data.background_image_url && (
        <img
          className="catalogue-media section-parallax-media"
          src={data.background_image_url}
          alt={data.title || "Abby Lighting Catalogue"}
        />
      )}
      <div className="copy reveal">
        <h2>
          {renderTitle(data.title, data.title_highlight)}
        </h2>
        {data.description && <p>{data.description}</p>}
        {data.button_text && data.button_link && (
          <a className="btn copy-btn" href={data.button_link}>
            {data.button_text}
          </a>
        )}
      </div>
    </section>
  );
}
