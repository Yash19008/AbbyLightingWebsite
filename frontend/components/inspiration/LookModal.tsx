"use client";

import React, { useEffect } from "react";

export interface LookItem {
  title: string;
  kicker: string;
  room: string;
  image: string;
  productsUsed?: Array<{
    name: string;
    type: string;
    image: string;
  }>;
}

interface LookModalProps {
  isOpen: boolean;
  look: LookItem | null;
  onClose: () => void;
}

export default function LookModal({ isOpen, look, onClose }: LookModalProps) {
  useEffect(() => {
    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === "Escape" && isOpen) {
        onClose();
      }
    };
    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [isOpen, onClose]);

  if (!isOpen || !look) return null;

  const defaultProducts = look.productsUsed || [
    { name: "Cymbal S", type: "Pendant light", image: "/images/decorative/cymbal-on.png" },
    { name: "Apex", type: "Pendant light", image: "/images/decorative/apex-on.png" },
    { name: "Cymbal", type: "Pendant light", image: "/images/decorative/cymbal-on.png" },
  ];

  return (
    <div
      className="look-modal"
      role="dialog"
      aria-modal="true"
      aria-labelledby="look-modal-title"
      onClick={(e) => {
        if (e.target === e.currentTarget) onClose();
      }}
    >
      <div className="modal-card">
        <button
          className="modal-close"
          type="button"
          aria-label="Close lookbook"
          onClick={onClose}
        >
          ×
        </button>
        <img
          className="modal-image"
          src={look.image}
          alt={look.title}
          onError={(e) => {
            (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
          }}
        />
        <div className="modal-content">
          <h3 id="look-modal-title">{look.title}</h3>
          <p className="modal-kicker">{look.kicker}</p>
          <div className="products-used">Products used</div>
          <div className="mini-products" aria-label="Products used">
            {defaultProducts.map((p, idx) => (
              <article key={idx} className="mini-product">
                <img
                  src={p.image}
                  alt={p.name}
                  onError={(e) => {
                    (e.currentTarget as HTMLImageElement).src = "/images/reference/project-atlas.png";
                  }}
                />
                <div>
                  <strong>{p.name}</strong>
                  <br />
                  <small>{p.type}</small>
                </div>
              </article>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
