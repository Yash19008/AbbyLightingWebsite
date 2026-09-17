"use client";

import React, { useState } from "react";
import DOMPurify from 'isomorphic-dompurify';
import { DecSpecItem } from "@/types/decorative";

interface ProductSpecsProps {
  specRows: {
    basic_specifications: DecSpecItem[];
    dimensions: DecSpecItem[];
  };
  allVariants?: Record<string, unknown>[];
  installationGuide: string | null;
  careInstructions: string | null;
}

export default function ProductSpecs({ specRows, allVariants, installationGuide, careInstructions }: ProductSpecsProps) {
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
                      <i className="spec-code">OS</i> <span dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(item.value) }} />
                    </div>
                  ) : (
                    <div dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(item.value) }} />
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

  const renderComparisonTable = (variants: Record<string, unknown>[]) => {
    if (!variants || variants.length === 0) return null;

    // Collect all unique dimension labels across all variants
    const dimensionLabels = new Set<string>();
    variants.forEach(v => {
      (v.spec_rows as Record<string, unknown>)?.dimensions?.forEach((d: DecSpecItem) => dimensionLabels.add(d.label));
    });

    if (dimensionLabels.size === 0) return null;
    const labelsArray = Array.from(dimensionLabels);

    return (
      <div className="spec-comparison-table">
        <table className="spec-table comparison-table">
          <tbody>
            <tr>
              <td className="spec-label">Size</td>
              {variants.map(v => (
                <td key={v.id} className="spec-value">
                  <strong>{v.size || v.name}</strong>
                </td>
              ))}
            </tr>
            {labelsArray.map(label => (
              <tr key={label}>
                <td className="spec-label">{label}</td>
                {variants.map(v => {
                  const spec = v.spec_rows?.dimensions?.find((d: DecSpecItem) => d.label === label);
                  return (
                    <td key={v.id} className="spec-value">
                      {spec ? <div dangerouslySetInnerHTML={{ __html: DOMPurify.sanitize(spec.value) }} /> : "-"}
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
      <h2 className="product-reveal">About this product</h2>
      <div className="spec-grid product-reveal product-delay-1">
        
        {/* Basic Specifications (and Dimensions appended) */}
        {(specRows.basic_specifications.length > 0 || (allVariants && allVariants.length > 0)) && (
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
                {allVariants && allVariants.length > 0 && renderComparisonTable(allVariants)}
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
