"use client";

import { useState, useEffect, useRef } from "react";

const Fc = [
  "/images/decorative/symphonyiv-off.png",
  "/images/decorative/symphonyiv-on.png",
  "/images/decorative/symphonyiv-off.png",
  "/images/decorative/symphonyiv-on.png",
];

const Ic = [
  ["Black + White", "#050505", "#f4f4f1", "BLK-WHT", "/images/decorative/symphonyiv-black-white.png"],
  ["Black + Gold", "#050505", "#efb45f", "BLK-GLD", "/images/decorative/symphonyiv-on.png"],
  ["Gold + White", "#efb45f", "#f4f4f1", "GLD-WHT", "/images/decorative/symphonyiv-gold-white.png"],
  ["Black", "#050505", "#050505", "BLK", "/images/decorative/symphonyiv-black-black.png"],
];

const Lc = [
  ["Retro Red", "#bc2227"],
  ["Tangerine", "#f47829"],
  ["Amber", "#f89b21"],
  ["Marigold", "#facb31"],
  ["Ocean Blue", "#015488"],
  ["Deep Teal", "#0c7677"],
  ["Aqua", "#7dbab5"],
  ["Sage", "#8b9b78"],
  ["Olive", "#50543d"],
  ["Forest", "#50543d"],
  ["Walnut", "#5a3828"],
  ["Cocoa", "#804d25"],
  ["Terracotta", "#a55728"],
  ["Burgundy", "#5f2f26"],
  ["Brick", "#972f27"],
  ["Blush", "#c6846d"],
  ["Warm Stone", "#b5b0a1"],
  ["Soft White", "#f4f5ef"],
  ["Pearl", "#f2f3f3"],
  ["Textured White", "#e8e3da"],
  ["Graphite", "#444950"],
  ["Charcoal", "#292a29"],
  ["Black", "#101011"],
  ["Silver", "#9fa4ab"],
  ["Metallic Gold", "#b88a3b"],
];

const Pc = [
  ["Small", "350"],
  ["Medium", "450"],
  ["Large", "560"],
];

const Mc = [
  ["Symphony I", "/images/cymbal/image-03.jpg"],
  ["Symphony II", "/images/cymbal/image-04.jpg"],
  ["Symphony III", "/images/cymbal/image-05.jpg"],
  ["Symphony V", "/images/cymbal/image-06.jpg"],
  ["Cymbal L", "/images/cymbal/image-01.jpg"],
  ["Quarry Drop", "/images/cymbal/image-03.jpg"],
  ["Disc", "/images/cymbal/image-04.jpg"],
  ["Halo", "/images/cymbal/image-05.jpg"],
  ["Terra Bell", "/images/cymbal/image-06.jpg"],
  ["Stone Arc", "/images/cymbal/image-01.jpg"],
];

export default function SymphonyIV() {
  const [activeImageIdx, setActiveImageIdx] = useState(0);
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);
  const [isEnquiryOpen, setIsEnquiryOpen] = useState(false);
  const [isEnquirySent, setIsEnquirySent] = useState(false);
  const [isDatasheetOpen, setIsDatasheetOpen] = useState(false);
  const [isMoreColoursOpen, setIsMoreColoursOpen] = useState(false);

  const thumbsRef = useRef<HTMLDivElement>(null);
  const relatedRef = useRef<HTMLDivElement>(null);

  const [activeColor, setActiveColor] = useState(Ic[0]);
  const [activeSize, setActiveSize] = useState(Pc[0]);

  const colorCode = activeColor[3];
  const activeStageImage = activeColor[4];
  const galleryImages = [activeStageImage, ...Fc];

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        setIsLightboxOpen(false);
        setIsEnquiryOpen(false);
        setIsDatasheetOpen(false);
        setIsMoreColoursOpen(false);
      }
      if (isLightboxOpen && e.key === "ArrowRight") {
        setActiveImageIdx((prev) => (prev + 1) % galleryImages.length);
      }
      if (isLightboxOpen && e.key === "ArrowLeft") {
        setActiveImageIdx((prev) => (prev - 1 + galleryImages.length) % galleryImages.length);
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isLightboxOpen, galleryImages.length]);

  useEffect(() => {
    setActiveImageIdx(0);
    setActiveColor(Ic[0]);
  }, []);

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -6% 0px" }
    );
    document.querySelectorAll(".product-reveal").forEach((el) => observer.observe(el));
    return () => observer.disconnect();
  }, []);

  const handleEnquirySubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsEnquirySent(true);
  };

  const downloadPdf = async (variants: any[]) => {
    const { jsPDF } = await import("jspdf");
    const doc = new jsPDF({ unit: "mm", format: "a4" });
    const primaryColor = [26, 28, 29];
    const secondaryColor = [120, 120, 120];
    const [color] = variants[0];
    const sizes = variants.map((v) => v[1]);

    try {
      const logoBase64 = await getBase64Image("/images/abby-logo-black.png");
      doc.addImage(logoBase64, "PNG", 15, 11, 34, 19);
    } catch (err) {
      console.error("Logo PDF render error:", err);
    }

    doc.setDrawColor(225);
    doc.line(15, 35, 195, 35);

    doc.setFont("helvetica", "bold");
    doc.setFontSize(21);
    doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
    doc.text("SYMPHONY IV", 15, 48, { charSpace: 0.3 } as any);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);

    const getSku = (size: any) => `SYM-IV-${size[1]}-${color[3]}`;

    if (variants.length === 1) {
      doc.text(
        `SKU: ${getSku(sizes[0])}   COLOUR COMBINATION: ${color[0]}   SIZE: ${sizes[0][0]}`,
        195,
        47.5,
        { align: "right" }
      );
    } else {
      doc.text(`SIZES: ${sizes.map((s) => s[0]).join(" · ")}`, 195, 47.5, { align: "right" });
    }

    try {
      const [img1, img2] = await Promise.all([
        getBase64Image("/images/decorative/symphonyiv-on.png"),
        getBase64Image("/images/decorative/symphonyiv-off.png"),
      ]);
      doc.addImage(img1, "PNG", 15, 56, 85, 62);
      doc.addImage(img2, "PNG", 110, 56, 65, 62);
    } catch (err) {
      console.error("Images PDF render error:", err);
    }

    let currentY = 128;
    const drawSection = (title: string, rows: any[][]) => {
      doc.setFont("helvetica", "bold");
      doc.setFontSize(12);
      doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
      doc.text(title, 15, currentY);
      currentY += 8;

      rows.forEach(([label, val]) => {
        doc.setFont("helvetica", "normal");
        doc.setFontSize(10);
        doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.text(label, 15, currentY);
        doc.setTextColor(45);
        doc.text(val, 195, currentY, { align: "right" });
        doc.setDrawColor(232);
        doc.line(15, currentY + 3, 195, currentY + 3);
        currentY += 7.2;
      });
      currentY += 4;
    };

    const finishesString = Ic.map((c) => c[0]).join(" · ");

    drawSection(
      "Specifications",
      variants.length === 1
        ? [
            ["Category", "Pendant light"],
            ["Colour combination", color[0]],
            ["Material", "Metal"],
          ]
        : [
            ["Category", "Pendant light"],
            ["Finishes available", finishesString],
            ["Material", "Metal"],
          ]
    );

    if (variants.length === 1) {
      drawSection("Dimensions", [
        ["Diameter", "Ø 250 mm"],
        ["Height", "H 250 mm"],
        ["Canopy", "Ø 95mm, H 45mm"],
        ["Cable", "Standard 1.5 m · Custom lengths available"],
      ]);
    } else {
      doc.setFont("helvetica", "bold");
      doc.setFontSize(12);
      doc.setTextColor(primaryColor[0], primaryColor[1], primaryColor[2]);
      doc.text("Dimensions", 15, currentY);
      currentY += 8;

      const columnsX = sizes.map((_, idx) => 72 + idx * 38);
      doc.setFontSize(9.5);
      sizes.forEach((s, idx) => doc.text(s[0], columnsX[idx], currentY));
      currentY += 6;

      const rows = [
        ["Diameter", "Ø 250 mm"],
        ["Height", "H 250 mm"],
        ["Canopy", "Ø 95mm, H 45mm"],
        ["Cable", "Standard 1.5 m"],
        ["SKU", "sku"],
      ];

      rows.forEach(([label, val]) => {
        doc.setFont("helvetica", "normal");
        doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.text(label, 15, currentY);
        doc.setTextColor(45);

        sizes.forEach((s, idx) => {
          const textVal = val === "sku" ? getSku(s) : val;
          doc.text(textVal, columnsX[idx], currentY);
        });

        doc.setDrawColor(234);
        doc.line(15, currentY + 3, 195, currentY + 3);
        currentY += 7.2;
      });
      currentY += 4;
    }

    drawSection("Light source & specifications", [
      ["Light source", "E27 · LED compatible"],
      ["Colour temperature", "2700–3000K warm white"],
      ["Input", "220–240V AC · 50/60 Hz"],
    ]);

    doc.setDrawColor(225);
    doc.line(15, 250, 195, 250);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(7.5);
    doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);

    const footerText = doc.splitTextToSize(
      "Abby Lighting & Switchgear Limited 802 A, Fortune Terraces, New Link Road, Opp City Mall, Andheri West, Mumbai - 400053, India. Abby reserves the right to discontinue any product without prior notice.",
      120
    );
    doc.text(footerText, 15, 256);
    doc.text("frontdesk@abbylighting.com  |  +91 9833645212", 195, 256, { align: "right" });
    doc.text("www.abbylighting.com", 195, 260, { align: "right" });

    doc.save(`Symphony-IV${variants.length === 1 ? "" : "-all-variants"}-datasheet.pdf`);
  };

  const getBase64Image = async (url: string): Promise<string> => {
    const res = await fetch(url);
    const blob = await res.blob();
    return await new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.onload = () => resolve(reader.result as string);
      reader.onerror = reject;
      reader.readAsDataURL(blob);
    });
  };

  return (
    <div className="product-page">
      <div className="product-shell">
        <div className="site-breadcrumb site-breadcrumb--on-light product-breadcrumb product-reveal">
          <a href="/">Home</a> / Decorative / Symphony / Symphony IV
        </div>
        <section className="product-top">
          <div className="product-gallery product-reveal">
            <div className="thumb-carousel">
              <button
                className="gallery-arrow gallery-prev"
                onClick={() => thumbsRef.current?.scrollBy({ left: -282, behavior: "smooth" })}
                aria-label="Previous product images"
              >
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M15 18l-6-6 6-6"></path>
                </svg>
              </button>
              <div className="product-thumbs" ref={thumbsRef}>
                {[...galleryImages, ...galleryImages].map((img, idx) => (
                  <button
                    key={`${img}-${idx}`}
                    className={activeImageIdx === idx % galleryImages.length ? "active" : ""}
                    onClick={() => setActiveImageIdx(idx % galleryImages.length)}
                  >
                    <img src={img} alt="" />
                  </button>
                ))}
              </div>
              <button
                className="gallery-arrow gallery-next"
                onClick={() => thumbsRef.current?.scrollBy({ left: 282, behavior: "smooth" })}
                aria-label="Next product images"
              >
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                  <path d="M9 18l6-6-6-6"></path>
                </svg>
              </button>
            </div>
            <button className="product-stage symphony-stage" onClick={() => setIsLightboxOpen(true)}>
              <img src={galleryImages[activeImageIdx]} alt="Symphony IV pendant" />
              <span>↗ &nbsp; Zoom</span>
            </button>
          </div>
          <div className="product-info product-reveal product-delay-1">
            <p className="product-tag">Symphony Collection · Pendant Light</p>
            <h1>Symphony IV</h1>
            <p className="product-desc">
              A playful sculptural pendant composed through contrasting colour and refined metallic detail.
              Symphony IV brings a distinctive graphic character to intimate and expressive interiors.
            </p>
            <div className="product-option symphony-colour-option">
              <strong>Colour</strong>
              <div className="swatches combination-swatches">
                {Ic.map((color, idx) => (
                  <button
                    key={color[0]}
                    className={`${activeColor[0] === color[0] ? "active" : ""} finish-${idx + 1}`}
                    onClick={() => {
                      setActiveColor(color);
                      setActiveImageIdx(0);
                    }}
                  >
                    <i style={{ "--finish-a": color[1], "--finish-b": color[2] } as any}></i>
                    <span>{color[0]}</span>
                  </button>
                ))}
                <button
                  className={`more-colours ${isMoreColoursOpen ? "active" : ""}`}
                  onClick={() => setIsMoreColoursOpen(!isMoreColoursOpen)}
                  aria-expanded={isMoreColoursOpen}
                  aria-controls="extended-colour-panel"
                >
                  <i>+</i>
                  <span>25 more</span>
                </button>
              </div>
              <div className={`inline-colour-panel-wrap ${isMoreColoursOpen ? "open" : ""}`} id="extended-colour-panel">
                <section className="inline-colour-panel" aria-label="25 additional colours">
                  <div className="colour-library">
                    {Lc.map(([name, hex], idx) => (
                      <div key={name} className={idx === 0 ? "preview-active" : ""} title={name}>
                        <i style={{ background: hex }}></i>
                      </div>
                    ))}
                  </div>
                  <p>
                    COLOUR : <b>RETRO RED</b>
                  </p>
                </section>
              </div>
            </div>
            <div className="product-option">
              <strong>Size</strong>
              <div className="size-options">
                {Pc.map((size) => (
                  <button
                    key={size[0]}
                    className={activeSize[0] === size[0] ? "active" : ""}
                    onClick={() => setActiveSize(size)}
                  >
                    {size[0]}
                  </button>
                ))}
              </div>
            </div>
            <div className="product-sku">
              <span>SKU</span>
              <b>SYM-IV-{activeSize[1]}-{colorCode}</b>
            </div>
            <button
              className="product-enquire"
              onClick={() => {
                setIsEnquirySent(false);
                setIsEnquiryOpen(true);
              }}
            >
              Enquire Now
            </button>
            <p className="product-help">
              <svg
                stroke="currentColor"
                fill="currentColor"
                strokeWidth="0"
                viewBox="0 0 448 512"
                aria-hidden="true"
                height="1em"
                width="1em"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"></path>
              </svg>{" "}
              <span>
                Need bulk orders or custom solutions?{" "}
                <a href="https://wa.me/" target="_blank" rel="noreferrer">
                  Talk to us.
                </a>
              </span>
            </p>
          </div>
        </section>
      </div>

      <section className="collection-band">
        <img src="/images/decorative/hero.png" alt="Symphony collection" />
        <div className="product-reveal">
          <p>The collection</p>
          <h2>
            Part of <em>Symphony Collections</em>
          </h2>
          <span>
            A family of expressive pendants built around colour, rhythm and carefully balanced geometry.
            Symphony brings confident combinations and crafted metallic detail into contemporary interiors.
          </span>
          <a href="#">Explore Collection</a>
        </div>
      </section>

      <section className="product-specs">
        <h2 className="product-reveal">About this product</h2>
        <div className="spec-grid product-reveal product-delay-1">
          <SpecAccordion
            title="Specifications"
            rows={[
              ["Category", "Pendant light"],
              ["Available in finishes", "Two-colour combinations"],
              ["Material", "Metal"],
            ]}
          />
          <SpecAccordion
            title="Dimensions"
            rows={[
              ["Diameter", "Ø 250 mm"],
              ["Height", "H 250 mm"],
              ["Canopy", "Ø 95mm, H 45mm"],
              ["Cable", "Standard 1.5 m · Custom lengths available"],
            ]}
          />
          <SpecAccordion
            title="Light source & specifications"
            rows={[
              ["Light source", "E27 · LED compatible"],
              ["Colour temperature", "2700–3000K warm white"],
              ["Input", "220–240V AC · 50/60 Hz"],
            ]}
          />
          <details open>
            <summary>Downloads</summary>
            <div className="download-row">
              <button onClick={() => setIsDatasheetOpen(true)}>Datasheet (PDF)</button>
              <button>Installation guide</button>
              <button>Care Instructions</button>
            </div>
          </details>
        </div>
      </section>

      <section className="related-section">
        <h2 className="product-reveal">The Symphony Family</h2>
        <div className="related-carousel">
          <button
            className="related-arrow related-prev"
            onClick={() => relatedRef.current?.scrollBy({ left: -313, behavior: "smooth" })}
            aria-label="Previous related products"
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
          <div className="related-track" ref={relatedRef}>
            {Mc.map(([name, img], r) => (
              <a
                key={name}
                className={`product-reveal product-delay-${Math.min(r + 1, 3)}`}
                href="#"
              >
                <img src={img} alt={name} />
                <h3>{r < 4 ? `Symphony ${["I", "II", "III", "V"][r]}` : name}</h3>
                <span>Decorative · Pendant Light</span>
              </a>
            ))}
          </div>
          <button
            className="related-arrow related-next"
            onClick={() => relatedRef.current?.scrollBy({ left: 313, behavior: "smooth" })}
            aria-label="Next related products"
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
        </div>
      </section>

      {/* Lightbox Modal */}
      {isLightboxOpen && (
        <div className="product-modal lightbox-modal" onClick={(e) => e.target === e.currentTarget && setIsLightboxOpen(false)}>
          <button className="lightbox-close" aria-label="Close" onClick={() => setIsLightboxOpen(false)}>
            ×
          </button>
          <button
            className="lightbox-arrow lightbox-prev"
            aria-label="Previous image"
            onClick={() => setActiveImageIdx((prev) => (prev - 1 + galleryImages.length) % galleryImages.length)}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M15 18l-6-6 6-6"></path>
            </svg>
          </button>
          <img src={galleryImages[activeImageIdx]} alt={`Symphony IV enlarged view ${activeImageIdx + 1}`} />
          <button
            className="lightbox-arrow lightbox-next"
            aria-label="Next image"
            onClick={() => setActiveImageIdx((prev) => (prev + 1) % galleryImages.length)}
          >
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
              <path d="M9 18l6-6-6-6"></path>
            </svg>
          </button>
          <span className="lightbox-count">
            {activeImageIdx + 1} / {galleryImages.length}
          </span>
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
                <p>Thanks — our team will get back to you shortly with pricing, finishes and lead time.</p>
              </div>
            ) : (
              <>
                <p>Product enquiry</p>
                <h2>Enquire about this product</h2>
                <form onSubmit={handleEnquirySubmit}>
                  <label>
                    Product & selection
                    <input readOnly value={`Symphony IV · ${activeColor[0]} · ${activeSize[0]}`} />
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
                    <textarea placeholder="Quantities, project details, custom finish or cable length, timeline…" />
                  </label>
                  <button type="submit">Send enquiry →</button>
                </form>
              </>
            )}
          </div>
        </div>
      )}

      {/* Datasheet Download Modal */}
      {isDatasheetOpen && (
        <div className="product-modal datasheet-modal" onMouseDown={(e) => e.target === e.currentTarget && setIsDatasheetOpen(false)}>
          <div className="datasheet-card">
            <button className="modal-x" onClick={() => setIsDatasheetOpen(false)}>
              ×
            </button>
            <p>Download datasheet</p>
            <h2>Choose what to include</h2>
            <button
              onClick={() => {
                downloadPdf([[activeColor, activeSize]]);
                setIsDatasheetOpen(false);
              }}
            >
              <b>This variant</b>
              <span>
                {activeColor[0]} · {activeSize[0]} · SYM-IV-{activeSize[1]}-{colorCode}
              </span>
            </button>
            <button
              onClick={() => {
                downloadPdf(Pc.map((size) => [activeColor, size]));
                setIsDatasheetOpen(false);
              }}
            >
              <b>All variants</b>
              <span>All available sizes and finish combinations</span>
            </button>
          </div>
        </div>
      )}
    </div>
  );
}

interface SpecAccordionProps {
  title: string;
  rows: string[][];
}

function SpecAccordion({ title, rows }: SpecAccordionProps) {
  const [isOpen, setIsOpen] = useState(true);
  return (
    <section className={`spec-accordion ${isOpen ? "open" : ""}`}>
      <button type="button" onClick={() => setIsOpen(!isOpen)} aria-expanded={isOpen}>
        <span>{title}</span>
        <i>{isOpen ? "−" : "+"}</i>
      </button>
      <div className="spec-body">
        <div>
          {rows.map(([label, val]) => (
            <p key={label}>
              <b>{label}</b>
              <span>{val}</span>
            </p>
          ))}
        </div>
      </div>
    </section>
  );
}
