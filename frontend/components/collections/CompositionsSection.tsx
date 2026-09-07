"use client";

import { useRef, useState } from 'react';
import Image from 'next/image';

const compositions = [
  {
    image: '/images/symphony/look-pastel.png',
    description: 'Pastel hues that settle gently into the space.',
    products: 'Symphony II · Symphony VII',
  },
  {
    image: '/images/symphony/look-vivid.png',
    description: 'Warm interiors, vivid accents, colour taking the lead.',
    products: 'Symphony X · Symphony VIII',
  },
  {
    image: '/images/symphony/look-earth.png',
    description: 'Clay reds, aged timber, earth tones shaped by time.',
    products: 'Symphony X',
  },
  {
    image: '/images/symphony/look-neutral.png',
    description: 'White surfaces, deliberate restraint, composed in neutrals.',
    products: 'Symphony IX',
  },
];

export default function CompositionsSection() {
  const scrollRef = useRef<HTMLDivElement>(null);
  const [activeIndex, setActiveIndex] = useState<number | null>(null);

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

  const handleClick = (index: number) => {
    setActiveIndex(activeIndex === index ? null : index);
  };

  return (
    <section className="s-section s-inspire">
      <div className="s-head">
        <h2>Compositions to inspire</h2>
        <p>Each look explores a relationship between form, colour and arrangement — examples of what Symphony can be, not definitions of what it should be.</p>
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
          {compositions.map((comp, index) => (
            <article
              key={index}
              className={`s-look ${activeIndex === index ? 'is-active' : ''}`}
              tabIndex={0}
              onClick={() => handleClick(index)}
            >
              <Image
                src={comp.image}
                alt="Symphony composition"
                width={400}
                height={500}
                style={{ objectFit: 'cover' }}
              />
              <div>
                <p>{comp.description}</p>
                <span>{comp.products}</span>
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
    </section>
  );
}
