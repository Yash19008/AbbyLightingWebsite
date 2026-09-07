"use client";

import { useState, useEffect, useRef } from "react";

const galleryImages = [
  "/images/cymbal/image-01.jpg",
  "/images/cymbal/image-02.jpg",
  "/images/cymbal/image-03.jpg",
  "/images/cymbal/image-04.jpg",
  "/images/cymbal/image-05.jpg",
];

const Nc = [
  ["White", "#f2f0ea", "WHT"],
  ["Black", "#1f1f1f", "BLK"],
  ["Terra", "#9c482a", "TRA"],
  ["Sand", "#b69665", "SND"],
];

const Pc = [
  ["Small", "350"],
  ["Medium", "450"],
  ["Large", "560"],
];

const Mc = [
  ["Cymbal M", "/images/cymbal/image-03.jpg"],
  ["Apex", "/images/cymbal/image-04.jpg"],
  ["Canopy", "/images/cymbal/image-05.jpg"],
  ["Node", "/images/cymbal/image-06.jpg"],
  ["Cymbal L", "/images/cymbal/image-01.jpg"],
  ["Quarry Drop", "/images/cymbal/image-03.jpg"],
  ["Disc", "/images/cymbal/image-04.jpg"],
  ["Halo", "/images/cymbal/image-05.jpg"],
  ["Terra Bell", "/images/cymbal/image-06.jpg"],
  ["Stone Arc", "/images/cymbal/image-01.jpg"],
];

export default function ProductDetail() {
  const [activeImageIdx, setActiveImageIdx] = useState(0);
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);
  const [isEnquiryOpen, setIsEnquiryOpen] = useState(false);
  const [isEnquirySent, setIsEnquirySent] = useState(false);
  const [isDatasheetOpen, setIsDatasheetOpen] = useState(false);

  const thumbsRef = useRef<HTMLDivElement>(null);
  const relatedRef = useRef<HTMLDivElement>(null);

  const [activeColor, setActiveColor] = useState(Nc[0]);
  const [activeSize, setActiveSize] = useState(Pc[0]);

  const colorCode = activeColor[2];

  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        setIsLightboxOpen(false);
        setIsEnquiryOpen(false);
        setIsDatasheetOpen(false);
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
  }, [isLightboxOpen]);

  useEffect(() => {
    setActiveImageIdx(0);
    setActiveColor(Nc[0]);
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
    doc.text("CYMBAL S", 15, 48, { charSpace: 0.3 } as any);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(9);
    doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);

    const getSku = (size: any) => `QRY-CYM-${size[1]}-${color[2]}`;

    if (variants.length === 1) {
      doc.text(
        `SKU: ${getSku(sizes[0])}   COLOUR: ${color[0]}   SIZE: ${sizes[0][0]}`,
        195,
        47.5,
        { align: "right" }
      );
    } else {
      doc.text(`SIZES: ${sizes.map((s) => s[0]).join(" · ")}`, 195, 47.5, { align: "right" });
    }

    try {
      const [img1, img2] = await Promise.all([
        getBase64Image("/images/cymbal/image-01.jpg"),
        getBase64Image("/images/cymbal/image-04.jpg"),
      ]);
      doc.addImage(img1, "JPEG", 15, 56, 85, 62);
      doc.addImage(img2, "JPEG", 110, 56, 65, 62);
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

    const finishesString = Nc.map((c) => c[0]).join(" · ");

    drawSection(
      "Specifications",
      variants.length === 1
        ? [
            ["Category", "Pendant light"],
            ["Colour", color[0]],
            ["Material", "Cement, Metal"],
          ]
        : [
            ["Category", "Pendant light"],
            ["Finishes available", finishesString],
            ["Material", "Cement, Metal"],
          ]
    );

    if (variants.length === 1) {
      drawSection("Dimensions", [
        ["Diameter", `${sizes[0][1]} mm`],
        ["Height", "260 mm"],
        ["Canopy", "Ø 83mm, H 35mm"],
        ["Cable", "1.5 m"],
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
        ["Diameter", "1"],
        ["Height", "260 mm"],
        ["Canopy", "Ø 83mm, H 35mm"],
        ["Cable", "1.5 m"],
        ["SKU", "sku"],
      ];

      rows.forEach(([label, val]) => {
        doc.setFont("helvetica", "normal");
        doc.setTextColor(secondaryColor[0], secondaryColor[1], secondaryColor[2]);
        doc.text(label, 15, currentY);
        doc.setTextColor(45);

        sizes.forEach((s, idx) => {
          const textVal = val === "1" ? `${s[1]} mm` : val === "sku" ? getSku(s) : val;
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

    doc.save(`Cymbal-S${variants.length === 1 ? "" : "-all-variants"}-datasheet.pdf`);
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
          <a href="/">Home</a> / Decorative / Quarry / Cymbal S
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
            <button className="product-stage" onClick={() => setIsLightboxOpen(true)}>
              <img src={galleryImages[activeImageIdx]} alt="Cymbal S pendant" />
              <span>↗ &nbsp; Zoom</span>
            </button>
          </div>
          <div className="product-info product-reveal product-delay-1">
            <p className="product-tag">Quarry Collection · Pendant Light</p>
            <h1>Cymbal S</h1>
            <p className="product-desc">
              A sculptural pendant inspired by the quiet materiality of stone. Cymbal S pairs a hand-finished
              terrazzo shade with a softly diffused glass globe.
            </p>
            <div className="product-option">
              <strong>Colour</strong>
              <div className="swatches">
                {Nc.map((color, idx) => (
                  <button
                    key={color[0]}
                    className={`${activeColor[0] === color[0] ? "active" : ""} finish-${idx + 1}`}
                    onClick={() => {
                      setActiveColor(color);
                    }}
                  >
                    <i style={{ "--finish": color[1] } as any}></i>
                    <span>{color[0]}</span>
                  </button>
                ))}
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
              <b>QRY-CYM-{activeSize[1]}-{colorCode}</b>
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
        <img src="/images/cymbal/image-02.jpg" alt="Quarry collection" />
        <div className="product-reveal">
          <p>The collection</p>
          <h2>
            Part of <em>Quarry</em>
          </h2>
          <span>
            Cymbal S belongs to the Quarry Collection — sculptural lighting in stone, terrazzo, cement and metal,
            shaped to celebrate material character rather than conceal it. Crafted by nature, refined by design.
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
              ["Available in finishes", "White, Black, Terra, Sand"],
              ["Material", "Cement, Metal"],
            ]}
          />
          <SpecAccordion
            title="Dimensions"
            rows={[
              ["Diameter", `${activeSize[1]} mm`],
              ["Height", "260 mm"],
              ["Canopy", "Ø 83mm, H 35mm"],
              ["Cable", "1.5 m"],
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
        <h2 className="product-reveal">Related products</h2>
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
                <h3>{name}</h3>
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
          <img src={galleryImages[activeImageIdx]} alt={`Cymbal S enlarged view ${activeImageIdx + 1}`} />
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
                    <input readOnly value={`Cymbal S · ${activeColor[0]} · ${activeSize[0]}`} />
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
                {activeColor[0]} · {activeSize[0]} · QRY-CYM-{activeSize[1]}-{colorCode}
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
