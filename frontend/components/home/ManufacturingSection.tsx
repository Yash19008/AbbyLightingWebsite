import type { ManufacturingSection } from "@/types/manufacturing-section";

interface ManufacturingSectionProps {
  data?: ManufacturingSection | null;
}

function renderTitle(title?: string | null, highlight?: string | null) {
  const cleanTitle = (title || "").trim();
  const cleanHighlight = (highlight || "").trim();

  if (!cleanHighlight) {
    return cleanTitle || "Built on Manufacturing Excellence";
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

  // If highlight is separate from title (e.g. Title: "Built on", Highlight: "Manufacturing Excellence")
  return (
    <>
      {cleanTitle}{" "}
      <em>{cleanHighlight}</em>
    </>
  );
}

export default function ManufacturingSection({ data }: ManufacturingSectionProps) {
  // If no dynamic manufacturing data exists or disabled in admin, hide section completely
  if (!data || data.is_active === false || (data as any).is_active === "no") {
    return null;
  }

  return (
    <section className="manufacturing">
      <img
        className="manufacturing-media section-parallax-media"
        src={data.background_image_url || "/images/figma-update/manufacturing.png"}
        alt="Manufacturing Excellence"
      />
      <div className="manufacturing-scrim" aria-hidden="true" />
      <div className="copy">
        <h2>
          {renderTitle(data.title, data.title_highlight)}
        </h2>
        {data.description && <p>{data.description}</p>}
        {data.button_text && data.button_link && (
          <a
            className="btn"
            href={data.button_link}
          >
            {data.button_text}
          </a>
        )}
      </div>
    </section>
  );
}
