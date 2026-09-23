"use client";

import { useState } from 'react';

const parameters = [
  {
    small: 'The form',
    title: 'The Notes',
    description: 'A sculptural pendant inspired by the quiet materiality of stone. Each form is distinct, a note that can stand alone or compose in harmony with others.',
  },
  {
    small: 'The Tones',
    title: 'The colour',
    description: 'A curated palette in six families — Pastels, Vivids, Earths, Coastals, Neutrals and Metallics. Some create harmony, others contrast, together a broad range of expression.',
  },
  {
    small: 'The Ensemble',
    title: 'The scale',
    description: 'A composition may be a solo or an ensemble. One pendant carries the idea in its purest form; many introduce rhythm, dialogue and variation.',
  },
  {
    small: 'The Score',
    title: 'The arrangement',
    description: 'Written through Spread and Drop — one shaping the composition across the ceiling, the other its vertical suspension. Together they establish rhythm, balance and movement.',
  },
];

export default function ParametersSection() {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const [visibleCount, setVisibleCount] = useState(8);

  const visibleParameters = parameters.slice(0, visibleCount);

  const handleClick = (index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  const handleKeyDown = (e: React.KeyboardEvent, index: number) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      handleClick(index);
    } else if (e.key === 'Escape') {
      setActiveIndex(null);
    }
  };

  return (
    <section className="s-section s-parameters">
      <div className="s-head">
        <h2>Four Parameters, One Composition</h2>
        <p>Every Symphony installation is built from four choices that work together as one system.</p>
      </div>
      <div className="s-parameter-grid">
        {visibleParameters.map((param, index) => (
          <article
            key={index}
            tabIndex={0}
            className={activeIndex === index ? 'is-active' : ''}
            onClick={() => handleClick(index)}
            onKeyDown={(e) => handleKeyDown(e, index)}
          >
            <small>{param.small}</small>
            <h3>{param.title}</h3>
            <span className="s-parameter-arrow" aria-hidden="true" />
            <p>{param.description}</p>
          </article>
        ))}
      </div>

      {visibleCount < parameters.length && (
        <div style={{ display: 'flex', justifyContent: 'center', width: '100%' }}>
          <button
            type="button"
            className="s-view-more-btn"
            onClick={() => setVisibleCount((prev) => prev + 8)}
          >
            View More
          </button>
        </div>
      )}
    </section>
  );
}
