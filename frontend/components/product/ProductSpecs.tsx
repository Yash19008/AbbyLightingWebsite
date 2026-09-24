"use client";

import React, { useState } from "react";
import { DecSpecItem, DecSize } from "@/types/decorative";

interface ProductSpecsProps {
  specRows: {
    basic_specifications: DecSpecItem[];
    dimensions: DecSpecItem[];
  };
  allSizes?: DecSize[];
  installationGuide: string | null;
  careInstructions: string | null;
}

export default function ProductSpecs({ specRows, allSizes, installationGuide, careInstructions }: ProductSpecsProps) {
  const [openSections, setOpenSections] = useState<Record<string, boolean>>({
    basic_specifications: true,
    downloads: true,
  });

  const toggleSection = (id: string) => {
    setOpenSections((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const renderSpecItems = (items: DecSpecItem[]) => {
    if (!items || items.length === 0) return null;
    return (
      <div className="spec-table-container">
        <table className="spec-table">
          <tbody>
            {items.map((item, idx) => (
              <tr key={idx}>
                <td className="spec-label">{item.label}</td>
                <td className="spec-value">
                  {item.label === "Size" ? (
                    <div className="spec-size-value">
                      <i className="spec-code">OS</i> <span dangerouslySetInnerHTML={{ __html: item.value }} />
                    </div>
                  ) : (
                    <div dangerouslySetInnerHTML={{ __html: item.value }} />
                  )}
                  {item.note && (
                    <small className="figma-spec-note">
                      {item.note.split("\n").map((n, i) => (
                        <React.Fragment key={i}>
                          {n}
                          <br />
                        </React.Fragment>
                      ))}
                    </small>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  };

  const renderComparisonTable = (sizes: DecSize[]) => {
    if (!sizes || sizes.length === 0) return null;

    // Collect all unique dimension labels across all sizes
    const dimensionLabels = new Set<string>();
    sizes.forEach((s) => {
      s.spec_rows?.dimensions?.forEach((d: DecSpecItem) => dimensionLabels.add(d.label));
    });

    if (dimensionLabels.size === 0) return null;
    const labelsArray = Array.from(dimensionLabels);

    return (
      <div className="spec-comparison-table">
        <table className="spec-table comparison-table">
          <tbody>
            <tr>
              <td className="spec-label">Size</td>
              {sizes.map((s) => (
                <td key={s.id} className="spec-value">
                  <strong>{s.label}</strong>
                </td>
              ))}
            </tr>
            {labelsArray.map((label) => (
              <tr key={label}>
                <td className="spec-label">{label}</td>
                {sizes.map((s) => {
                  const spec = s.spec_rows?.dimensions?.find((d: DecSpecItem) => d.label === label);
                  return (
                    <td key={s.id} className="spec-value">
                      {spec ? <div dangerouslySetInnerHTML={{ __html: spec.value }} /> : "-"}
                    </td>
                  );
                })}
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  };

  return (
    <section className="product-specs">
      <h2 className="product-reveal" suppressHydrationWarning>About this product</h2>
      <div className="spec-grid product-reveal product-delay-1" suppressHydrationWarning>
        
        {/* Basic Specifications (and Dimensions appended) */}
        {(specRows.basic_specifications.length > 0 || (allSizes && allSizes.length > 0)) && (
          <section className={`spec-accordion ${openSections['basic_specifications'] ? "open" : ""}`}>
            <button
              type="button"
              aria-expanded={openSections['basic_specifications']}
              onClick={() => toggleSection('basic_specifications')}
            >
              <span>Specifications</span>
              <i>{openSections['basic_specifications'] ? "−" : "+"}</i>
            </button>
            <div className="spec-body">
              <div className="spec-body-inner">
                {renderSpecItems(specRows.basic_specifications)}
                {allSizes && allSizes.length > 0 && renderComparisonTable(allSizes)}
              </div>
            </div>
          </section>
        )}

        {/* Downloads */}
        {(installationGuide || careInstructions) && (
            <details open={openSections['downloads']} onToggle={(e) => setOpenSections(prev => ({ ...prev, downloads: (e.target as HTMLDetailsElement).open }))}>
            <summary>Downloads</summary>
            <div className="download-row">
                {installationGuide && (
                    <button onClick={() => window.open(installationGuide, '_blank')}>
                        Installation guide
                    </button>
                )}
                {careInstructions && (
                    <button onClick={() => window.open(careInstructions, '_blank')}>
                        Care Instructions
                    </button>
                )}
            </div>
            </details>
        )}
      </div>
    </section>
  );
}
