"use client";

import { useState, useEffect, useRef, use } from "react";
import { fetchDecorativeProductDetail } from "@/lib/api/server-fetchers";

const defaultGalleryImages = [
  "/images/cymbal/image-01.jpg",
  "/images/cymbal/image-02.jpg",
  "/images/cymbal/image-03.jpg",
  "/images/cymbal/image-04.jpg",
  "/images/cymbal/image-05.jpg",
];

const defaultSwatches = [
  { name: "White", hex: "#f2f0ea", code: "WHT", stage_image: "/images/cymbal/image-01.jpg" },
  { name: "Black", hex: "#1f1f1f", code: "BLK", stage_image: "/images/cymbal/image-02.jpg" },
  { name: "Terra", hex: "#9c482a", code: "TRA", stage_image: "/images/cymbal/image-03.jpg" },
  { name: "Sand", hex: "#b69665", code: "SND", stage_image: "/images/cymbal/image-04.jpg" },
];

const defaultSizes = [
  { label: "Small", value: "350" },
  { label: "Medium", value: "450" },
  { label: "Large", value: "560" },
];

export default function DynamicProductDetailPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = use(params);

  const [productData, setProductData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  const [activeImageIdx, setActiveImageIdx] = useState(0);
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);
  const [isEnquiryOpen, setIsEnquiryOpen] = useState(false);
  const [isEnquirySent, setIsEnquirySent] = useState(false);
  const [isDatasheetOpen, setIsDatasheetOpen] = useState(false);

  const thumbsRef = useRef<HTMLDivElement>(null);
  const relatedRef = useRef<HTMLDivElement>(null);

  const [activeColor, setActiveColor] = useState<any>(defaultSwatches[0]);
  const [activeSize, setActiveSize] = useState<any>(defaultSizes[0]);

  // Fetch product data from API
  useEffect(() => {
    async function loadData() {
      setLoading(true);
      try {
        const apiData = await fetchDecorativeProductDetail(slug);
        if (apiData) {
          setProductData(apiData);
          if (apiData.swatches && apiData.swatches.length > 0) {
            setActiveColor(apiData.swatches[0]);
          }
          if (apiData.sizes && apiData.sizes.length > 0) {
            setActiveSize(apiData.sizes[0]);
          }
        }
      } catch (err) {
        console.error("Error loading product detail API:", err);
      } finally {
        setLoading(false);
      }
    }
    loadData();
  }, [slug]);

  const title = productData?.title || (slug ? slug.replace(/-/g, " ").toUpperCase() : "PRODUCT DETAILS");
  const category = productData?.category || "Decorative · Pendant Light";
  const shortDescription = productData?.short_description || "Precision engineered decorative luminaire with customizable finishes and dynamic output.";
  
  const galleryImages = (productData?.gallery_images && productData.gallery_images.length > 0)
    ? productData.gallery_images
    : defaultGalleryImages;

  const swatches = (productData?.swatches && productData.swatches.length > 0)
    ? productData.swatches
    : defaultSwatches;

  const sizes = (productData?.sizes && productData.sizes.length > 0)
    ? productData.sizes
    : defaultSizes;

  // Find variation image if available for active swatch
  const stageImage = (() => {
    if (productData?.variations && productData.variations.length > 0) {
      const match = productData.variations.find((v: any) =>
        v.attributes.some((attrName: string) =>
          attrName.toLowerCase() === activeColor.name.toLowerCase()
        )
      );
      if (match && match.image_url) return match.image_url;
    }
    return activeColor.stage_image || galleryImages[0];
  })();

  const handleSwatchSelect = (swatch: any) => {
    setActiveColor(swatch);
    // Find variation image if available
    if (productData?.variations) {
      const match = productData.variations.find((v: any) =>
        v.attributes.some((attrName: string) =>
          attrName.toLowerCase() === swatch.name.toLowerCase()
        )
      );
      if (match && match.image_url) {
        const imgIdx = galleryImages.indexOf(match.image_url);
        if (imgIdx !== -1) {
          setActiveImageIdx(imgIdx);
        }
      }
    }
  };

  const handleEnquirySubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsEnquirySent(true);
  };

  const downloadPdf = async (variants: any[]) => {
    const { jsPDF } = await import("jspdf");
    const doc = new jsPDF({ unit: "mm", format: "a4" });
    const primaryColor = [26, 28, 29];
    const secondaryColor = [120, 120, 120];

    doc.setFont("helvetica", "bold");
    doc.setFontSize(21);
    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
    doc.text(title.toUpperCase(), 15, 48);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
    doc.text(`COLOUR: ${activeColor.name}   SIZE: ${activeSize.label}`, 15, 58);

    doc.save(`${slug}-datasheet.pdf`);
  };

  return (
    <div className="product-page">
      <div className="product-shell">
        <div className="site-breadcrumb site-breadcrumb--on-light product-breadcrumb product-reveal is-visible">
          <a href="/">Home</a> / Decorative / {category} / {title}
        </div>
        <section className="product-top">
          <div className="product-gallery product-reveal is-visible">
            <div className="thumb-carousel">
              <button
                className="gallery-arrow gallery-prev"
                onClick={() => thumbsRef.current?.scrollBy({ left: -282, behavior: "smooth" })}
                aria-label="Previous images"
              >
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M15 18l-6-6 6-6"></path>
                </svg>
              </button>
              <div className="product-thumbs" ref={thumbsRef}>
                {galleryImages.map((img: string, idx: number) => (
                  <button
                    key={`${img}-${idx}`}
                    className={activeImageIdx === idx ? "active" : ""}
                    onClick={() => setActiveImageIdx(idx)}
                  >
                    <img src={img} alt={`${title} view ${idx + 1}`} />
                  </button>
                ))}
              </div>
              <button
                className="gallery-arrow gallery-next"
                onClick={() => thumbsRef.current?.scrollBy({ left: 282, behavior: "smooth" })}
                aria-label="Next images"
              >
                ›
              </button>
            </div>
            <button
              className="product-stage"
              onClick={() => setIsLightboxOpen(true)}
              aria-label="Enlarge image"
            >
              <img src={stageImage} alt={title} />
              <span>Zoom ⊕</span>
            </button>
          </div>

          <div className="product-info product-reveal is-visible">
            <span className="product-tag">{category}</span>
            <h1>{title}</h1>
            <p className="product-desc">{shortDescription}</p>

            {/* Dynamic Swatches / Colors */}
            <div className="product-option">
              <strong>Finish / Color</strong>
              <div className="swatches">
                {swatches.map((swatch: any) => (
                  <button
                    key={swatch.name}
                    className={activeColor.name === swatch.name ? "active" : ""}
                    onClick={() => handleSwatchSelect(swatch)}
                  >
                    <i style={{ backgroundColor: swatch.hex }} />
                    <span>{swatch.name}</span>
                  </button>
                ))}
              </div>
            </div>

            {/* Dynamic Sizes */}
            <div className="product-option">
              <strong>Size</strong>
              <div className="size-options">
                {sizes.map((s: any) => (
                  <button
                    key={s.label}
                    className={activeSize.label === s.label ? "active" : ""}
                    onClick={() => setActiveSize(s)}
                  >
                    {s.label}
                  </button>
                ))}
              </div>
            </div>

            <div className="product-sku">
              <span>SKU</span><b>{productData?.sku || `${slug.toUpperCase()}-${activeSize.value || 'STD'}-${activeColor.code || 'WHT'}`}</b>
            </div>

            <button className="product-enquire" onClick={() => setIsEnquiryOpen(true)}>
              Enquire Now
            </button>

            <p className="product-help">
              <svg stroke="currentColor" fill="currentColor" strokeWidth="0" viewBox="0 0 448 512" height="1em" width="1em">
                <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
              </svg>
              <span>Need bulk orders or custom solutions? <a href="https://wa.me/" target="_blank" rel="noreferrer">Talk to us.</a></span>
            </p>
          </div>
        </section>
      </div>

      {/* Lightbox Modal */}
      {isLightboxOpen && (
        <div className="product-modal lightbox-modal" onClick={(e) => e.target === e.currentTarget && setIsLightboxOpen(false)}>
          <button className="lightbox-close" aria-label="Close" onClick={() => setIsLightboxOpen(false)}>
            ×
          </button>
          <img src={stageImage} alt={title} />
        </div>
      )}

      {/* Enquiry Modal */}
      {isEnquiryOpen && (
        <div className="product-modal enquiry-modal" onMouseDown={(e) => e.target === e.currentTarget && setIsEnquiryOpen(false)}>
          <div className="enquiry-card">
            <button className="modal-x" onClick={() => setIsEnquiryOpen(false)}>
              ×
            </button>
            {isEnquirySent ? (
              <div className="sent">
                <b>✓</b>
                <h2>Enquiry sent</h2>
                <p>Thanks - our team will get back to you shortly with pricing, finishes and lead time.</p>
              </div>
            ) : (
              <>
                <p>Product enquiry</p>
                <h2>Enquire about {title}</h2>
                <form onSubmit={handleEnquirySubmit}>
                  <label>
                    Product & selection
                    <input readOnly value={`${title} · ${activeColor.name} · ${activeSize.label}`} />
                  </label>
                  <label>
                    Full name
                    <input required placeholder="Your name" />
                  </label>
                  <div>
                    <label>
                      Email ID
                      <input required type="email" placeholder="you@company.com" />
                    </label>
                    <label>
                      Phone number
                      <input required placeholder="+91" />
                    </label>
                  </div>
                  <label>
                    Tell us more (optional)
                    <textarea placeholder="Quantities, project details, custom finish or cable length, timeline." />
                  </label>
                  <button type="submit">Send enquiry</button>
                </form>
              </>
            )}
          </div>
        </div>
      )}

      {/* ── Collection Band ─────────────────────────────────────────── */}
      <section className="collection-band">
        <img src="/images/cymbal/image-02.jpg" alt="Quarry collection" />
        <div className="product-reveal is-visible">
          <p>The collection</p>
          <h2>Part of <em>Quarry</em></h2>
          <span>
            Cymbal S belongs to the Quarry Collection — sculptural lighting in stone, terrazzo, cement and metal, shaped to celebrate material character rather than conceal it. Crafted by nature, refined by design.
          </span>
          <a href="/decorative-products">Explore Collection</a>
        </div>
      </section>

      {/* ── Product Specs ───────────────────────────────────────────── */}
      <section className="product-specs">
        <h2 className="product-reveal is-visible">About this product</h2>
        <div className="spec-grid product-reveal is-visible product-delay-1">
          <section className="spec-accordion open">
            <button type="button" aria-expanded="true">
              <span>Specifications</span>
              <i>−</i>
            </button>
            <div className="spec-body">
              <div>
                <p><b>Category</b><span>Pendant light</span></p>
                <p><b>Available in finishes</b><span>White, Black, Terra, Sand</span></p>
                <p><b>Material</b><span>Cement, Metal</span></p>
              </div>
            </div>
          </section>

          <section className="spec-accordion open">
            <button type="button" aria-expanded="true">
              <span>Dimensions</span>
              <i>−</i>
            </button>
            <div className="spec-body">
              <div>
                <p><b>Diameter</b><span>350 mm</span></p>
                <p><b>Height</b><span>260 mm</span></p>
                <p><b>Canopy</b><span>Ø 83mm, H 35mm</span></p>
                <p><b>Cable</b><span>1.5 m</span></p>
              </div>
            </div>
          </section>

          <section className="spec-accordion open">
            <button type="button" aria-expanded="true">
              <span>Light source &amp; specifications</span>
              <i>−</i>
            </button>
            <div className="spec-body">
              <div>
                <p><b>Light source</b><span>E27 · LED compatible</span></p>
                <p><b>Colour temperature</b><span>2700–3000K warm white</span></p>
                <p><b>Input</b><span>220–240V AC · 50/60 Hz</span></p>
              </div>
            </div>
          </section>

          <details open>
            <summary>Downloads</summary>
            <div className="download-row">
              <button onClick={() => downloadPdf([])}>Datasheet (PDF)</button>
              <button>Installation guide</button>
              <button>Care Instructions</button>
            </div>
          </details>
        </div>
      </section>

      {/* ── Related Products ────────────────────────────────────────── */}
      <section className="related-section">
        <h2 className="product-reveal is-visible">Related products</h2>
        <div className="related-carousel">
          <button
            className="related-arrow related-prev"
            onClick={() => relatedRef.current?.scrollBy({ left: -300, behavior: "smooth" })}
            aria-label="Previous related products"
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
          <div className="related-track" ref={relatedRef}>
            <a className="product-reveal is-visible product-delay-1" href="/product-detail/cymbal-m">
              <img src="/images/cymbal/image-03.jpg" alt="Cymbal M" />
              <h3>Cymbal M</h3>
              <span>Decorative · Pendant Light</span>
            </a>
            <a className="product-reveal is-visible product-delay-2" href="/product-detail/apex">
              <img src="/images/cymbal/image-04.jpg" alt="Apex" />
              <h3>Apex</h3>
              <span>Decorative · Pendant Light</span>
            </a>
            <a className="product-reveal is-visible product-delay-3" href="/product-detail/canopy">
              <img src="/images/cymbal/image-05.jpg" alt="Canopy" />
              <h3>Canopy</h3>
              <span>Decorative · Pendant Light</span>
            </a>
            <a className="product-reveal is-visible product-delay-3" href="/product-detail/node">
              <img src="/images/cymbal/image-06.jpg" alt="Node" />
              <h3>Node</h3>
              <span>Decorative · Pendant Light</span>
            </a>
            <a className="product-reveal is-visible product-delay-3" href="/product-detail/cymbal-l">
              <img src="/images/cymbal/image-01.jpg" alt="Cymbal L" />
              <h3>Cymbal L</h3>
              <span>Decorative · Pendant Light</span>
            </a>
          </div>
          <button
            className="related-arrow related-next"
            onClick={() => relatedRef.current?.scrollBy({ left: 300, behavior: "smooth" })}
            aria-label="Next related products"
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
        </div>
      </section>
    </div>
  );
}

