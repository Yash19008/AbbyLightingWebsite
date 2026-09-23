"use client";

import { useRef, useState } from 'react';
import Image from 'next/image';

const places = [
  { name: 'The Quiet Room', description: 'Pastel hues that settle gently into the space.', image: '/images/symphony/app-quiet.png' },
  { name: 'The Corner Cafe', description: 'Warm interiors, vivid accents, colour taking the lead.', image: '/images/symphony/app-cafe.png' },
  { name: 'The Workroom', description: 'A composed rhythm of light for focus and conversation.', image: '/images/symphony/app-work.png' },
  { name: 'The Hotel Bar', description: 'Layered warmth shaped for intimate evenings.', image: '/images/symphony/app-bar.png' },
];

export default function PlacesSection() {
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
    <section className="s-section s-places">
      <div className="s-head">
        <h2>Symphony in place</h2>
        <p>One system, composed differently for every room.</p>
      </div>
      <div className="s-carousel">
        <button
          className="s-carousel-arrow s-carousel-prev"
          type="button"
          aria-label="Previous rooms"
          onClick={() => scroll('prev')}
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M15 18l-6-6 6-6"></path>
          </svg>
        </button>
        <div className="s-scroll" ref={scrollRef}>
          {places.map((place, index) => (
            <article
              key={index}
              className={`s-place ${activeIndex === index ? 'is-active' : ''}`}
              tabIndex={0}
              onClick={() => handleClick(index)}
            >
              <Image
                src={place.image}
                alt={place.name}
                width={400}
                height={500}
                style={{ objectFit: 'cover' }}
              />
              <div className="s-place-copy">
                <h3>{place.name}</h3>
                <p>{place.description}</p>
              </div>
            </article>
          ))}
        </div>
        <button
          className="s-carousel-arrow s-carousel-next"
          type="button"
          aria-label="Next rooms"
          onClick={() => scroll('next')}
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
            <path d="M9 18l6-6-6-6"></path>
          </svg>
        </button>
      </div>
    </section>
  );
}
