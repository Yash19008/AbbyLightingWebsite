"use client";

import { useState } from 'react';
import { ParametersSection as ParametersSectionType } from '@/types/collection';

interface ParametersSectionProps {
  parametersSection: ParametersSectionType;
}

export default function ParametersSectionDynamic({ parametersSection }: ParametersSectionProps) {
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

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
        <h2>{parametersSection.title}</h2>
        <p>{parametersSection.subtitle}</p>
      </div>
      <div className="s-parameter-grid">
        {parametersSection.items.map((param, index) => {
          const cardBg = param.bg_color || undefined;
          const cardHoverBg = param.hover_bg_color || undefined;
          return (
            <article
              key={param.id}
              tabIndex={0}
              className={activeIndex === index ? 'is-active' : ''}
              onClick={() => handleClick(index)}
              onKeyDown={(e) => handleKeyDown(e, index)}
              style={
                cardBg
                  ? { backgroundColor: cardBg }
                  : undefined
              }
              onMouseEnter={
                cardHoverBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = cardHoverBg;
                    }
                  : undefined
              }
              onMouseLeave={
                cardBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = cardBg;
                    }
                  : cardHoverBg
                  ? (e) => {
                      (e.currentTarget as HTMLElement).style.backgroundColor = '';
                    }
                  : undefined
              }
            >
              <small>{param.small_text}</small>
              <h3>{param.title}</h3>
              <span className="s-parameter-arrow" aria-hidden="true" />
              <p>{param.description}</p>
            </article>
          );
        })}
      </div>
    </section>
  );
}
