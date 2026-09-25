"use client";

import { useRef, useState } from 'react';
import Image from 'next/image';
import LookModal, { LookItem } from '@/components/inspiration/LookModal';
import '@/styles/inspiration.css';

type CompositionItem = {
  id: number;
  image: string;
  title: string;
  category: string;
  kicker: string;
};

type CompositionsSectionData = {
  title: string;
  subtitle: string;
  items: CompositionItem[];
};

type Props = {
  data: CompositionsSectionData;
};

export default function CompositionsSection({ data }: Props) {
  const scrollRef = useRef<HTMLDivElement>(null);
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const [activeLook, setActiveLook] = useState<LookItem | null>(null);

  const scroll = (direction: 'prev' | 'next') => {
    if (!scrollRef.current) return;
    const container = scrollRef.current;
    const card = container.querySelector('article');
    if (!card) return;
    
    const gap = 12;
    const scrollAmount = card.getBoundingClientRect().width + gap;
    const newScroll = direction === 'next' 
      ? container.scrollLeft + scrollAmount 
      : container.scrollLeft - scrollAmount;
    
    container.scrollTo({
      left: newScroll,
      behavior: 'smooth',
    });
  };

  const handleClick = (comp: CompositionItem, index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
    setActiveLook({
      title: comp.kicker || comp.title || 'Composition',
      kicker: [comp.title, comp.category].filter(Boolean).join(' · ') || 'Symphony Composition',
      room: comp.category ? comp.category.toLowerCase() : 'living',
      image: comp.image,
    });
  };

  return (
    <section className="s-section s-inspire">
      <div className="s-head">
        <h2>{data.title || "Compositions to inspire"}</h2>
        <p>{data.subtitle}</p>
      </div>
      <div className="s-carousel">
        <button
          className="s-carousel-arrow s-carousel-prev"
          type="button"
          aria-label="Previous compositions"
          onClick={() => scroll('prev')}
        >
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
            <path d="M15 18l-6-6 6-6"></path>
          </svg>
        </button>
        <div className="s-scroll" ref={scrollRef}>
          {data.items.map((comp, index) => (
            <article
              key={index}
              className={`s-look ${activeIndex === index ? 'is-active' : ''}`}
              tabIndex={0}
              onClick={() => handleClick(comp, index)}
              style={{ cursor: 'pointer' }}
            >
              <Image
                src={comp.image}
                alt={comp.title || "Composition"}
                width={400}
                height={500}
                style={{ objectFit: 'cover' }}
              />
              <div>
                {comp.kicker && <p>{comp.kicker}</p>}
                <span>{comp.title} {comp.category ? `· ${comp.category}` : ''}</span>
              </div>
            </article>
          ))}
        </div>
        <button
          className="s-carousel-arrow s-carousel-next"
          type="button"
          aria-label="Next compositions"
          onClick={() => scroll('next')}
        >
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 18l6-6-6-6"></path>
          </svg>
        </button>
      </div>

      <LookModal
        isOpen={Boolean(activeLook)}
        look={activeLook}
        onClose={() => setActiveLook(null)}
      />
    </section>
  );
}
