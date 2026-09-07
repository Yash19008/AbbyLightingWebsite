"use client";

import React from "react";
import Link from "next/link";

export interface RelatedArticle {
  title: string;
  tag: string;
  image: string;
  link: string;
  alt: string;
}

interface BlogRelatedSectionProps {
  articles?: RelatedArticle[];
}

export default function BlogRelatedSection({
  articles = [],
}: BlogRelatedSectionProps) {
  if (!articles || articles.length === 0) {
    return null;
  }

  return (
    <section className="related" aria-labelledby="keep-reading">
      <div className="related-shell">
        <h2 id="keep-reading">Keep reading</h2>
        <div className="related-grid">
          {articles.map((item, idx) => (
            <Link key={idx} className="related-card" href={item.link}>
              <figure>
                <img
                  src={item.image}
                  alt={item.alt || item.title}
                  onError={(e) => {
                    (e.currentTarget as HTMLImageElement).src =
                      "/images/reference/project-atlas.png";
                  }}
                />
              </figure>
              <div className="related-copy">
                <small>{item.tag}</small>
                <h3>{item.title}</h3>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}
