import type { ManufacturingSection } from "@/types/manufacturing-section";

interface ManufacturingSectionProps {
  data: ManufacturingSection | null;
}

export default function ManufacturingSection({ data }: ManufacturingSectionProps) {
  return (
    <section className="manufacturing">
      <img
        className="manufacturing-media section-parallax-media"
        src={data?.background_image_url || "/images/figma-update/manufacturing.png"}
        alt=""
      />
      <div className="manufacturing-scrim" aria-hidden="true" />
      <div className="copy">
        <h2>
          {data?.title ? (
            <>
              {data.title.replace(data.title_highlight || '', '').trim()}{' '}
              {data.title_highlight && <em>{data.title_highlight}</em>}
            </>
          ) : (
            <>Built on <em>Manufacturing Excellence</em></>
          )}
        </h2>
        <p>
          {data?.description ||
            "Every Abby luminaire begins long before it reaches a project. Designed, engineered, manufactured and tested entirely in-house, our vertically integrated facility brings every stage of production under one roof."}
        </p>
        <a
          className="btn"
          href={data?.button_link || "/#manufacturing"}
        >
          {data?.button_text || "See How It's Made"}
        </a>
      </div>
    </section>
  );
}
