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
  const [isGeneratingPdf, setIsGeneratingPdf] = useState(false);

  const handleDownloadDatasheet = async () => {
    try {
      setIsGeneratingPdf(true);
      const datasheetModule = await import('@/lib/symphony-datasheet-vector.js');
      if (datasheetModule.generateProductDatasheet) {
        await datasheetModule.generateProductDatasheet();
      }
    } catch (error) {
      console.error('Failed to generate PDF:', error);
      alert('Could not prepare PDF — try again');
    } finally {
      setIsGeneratingPdf(false);
    }
  };

  const toggleSection = (id: string) => {
    setOpenSections((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const renderChipsValue = (jsonValue: string) => {
    try {
      const chips: { value: string; code?: string | null }[] = JSON.parse(jsonValue);
      return (
        <span className="spec-chips">
          {chips.map((chip, i) => (
            <React.Fragment key={i}>
              {i > 0 && <span className="spec-chip-sep" style={{ margin: '0 12px' }}>&nbsp;</span>}
              <span className="spec-chip">
                {chip.value}
                {chip.code && <sup className="spec-chip-code" style={{ color: '#C0C0C0', marginLeft: '2px', fontWeight: '500' }}>{chip.code}</sup>}
              </span>
            </React.Fragment>
          ))}
        </span>
      );
    } catch {
      return <span>{jsonValue}</span>;
    }
  };

  const renderSpecItems = (items: DecSpecItem[]) => {
    if (!items || items.length === 0) return null;
    return (
      <div className="spec-table-container">
        <table className="spec-table">
          <tbody>
            {items.map((item, idx) => (
              <tr key={idx} className="figma-spec-row">
                <td className="spec-label"><b>{item.label}</b></td>
                <td className="spec-value">
                  {item.label === "Size" ? (
                    <span className="spec-size-value">
                      <i className="spec-code">OS</i> <span dangerouslySetInnerHTML={{ __html: item.value }} />
                    </span>
                  ) : (
                    item.value_type === 'chips' ? renderChipsValue(item.value) : <span dangerouslySetInnerHTML={{ __html: item.value }} />
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
            <tr className="figma-spec-row">
              <td className="spec-label">
                <b>Size</b>
                <span style={{ display: 'none' }} data-values={sizes.map(s => s.label).join('|')}>
                  {sizes.map((s, idx) => (
                    <span key={idx}>
                      {s.label}
                      {s.code && <sup className="spec-code">{s.code}</sup>}
                    </span>
                  ))}
                </span>
              </td>
              {sizes.map((s) => (
                <td key={s.id} className="spec-value">
                  <strong>
                    {s.label}
                    {s.code && <sup style={{ color: '#C0C0C0', marginLeft: '2px', fontWeight: '400' }}>{s.code}</sup>}
                  </strong>
                </td>
              ))}
            </tr>
            {labelsArray.map((label) => (
              <tr key={label} className="figma-spec-row">
                <td className="spec-label">
                  <b>{label}</b>
                  <span style={{ display: 'none' }} data-values={sizes.map(s => {
                    const spec = s.spec_rows?.dimensions?.find((d: DecSpecItem) => d.label === label);
                    if (spec && spec.value_type === 'chips') {
                      try {
                        const parsed = JSON.parse(spec.value);
                        return parsed.map((c: { value: string }) => c.value).join('  ');
                      } catch (_e) { return spec.value; }
                    }
                    return spec ? spec.value.replace(/<[^>]*>?/gm, '') : "-";
                  }).join('|')}>
                    {sizes.map((s, idx) => {
                      const spec = s.spec_rows?.dimensions?.find((d: DecSpecItem) => d.label === label);
                      return <span key={idx}>
                        {spec ? (spec.value_type === 'chips' ? renderChipsValue(spec.value) : <span dangerouslySetInnerHTML={{ __html: spec.value }} />) : "-"}
                      </span>;
                    })}
                  </span>
                </td>
                {sizes.map((s) => {
                  const spec = s.spec_rows?.dimensions?.find((d: DecSpecItem) => d.label === label);
                  return (
                    <td key={s.id} className="spec-value">
                      {spec ? (spec.value_type === 'chips' ? renderChipsValue(spec.value) : <div dangerouslySetInnerHTML={{ __html: spec.value }} />) : "-"}
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
        <details open={openSections['downloads']} onToggle={(e) => setOpenSections(prev => ({ ...prev, downloads: (e.target as HTMLDetailsElement).open }))}>
          <summary>Downloads</summary>
          <div className="download-row">
            <button
              id="download-tds-btn"
              onClick={handleDownloadDatasheet}
              disabled={isGeneratingPdf}
            >
              {isGeneratingPdf ? 'Preparing PDF…' : 'Datasheet (PDF)'}
            </button>
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
      </div>
    </section>
  );
}
