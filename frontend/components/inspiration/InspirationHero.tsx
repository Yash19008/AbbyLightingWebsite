import React from "react";
import Link from "next/link";
import { InspirationHeroData } from "@/lib/api/inspiration";

interface InspirationHeroProps {
  heroSection?: InspirationHeroData | null;
}

export default function InspirationHero({ heroSection }: InspirationHeroProps) {
  // If explicitly deactivated by admin, don't render
  if (heroSection && heroSection.is_active === false) {
    return null;
  }

  const title = heroSection?.title || "Ideas, stories & inspiration";
  const titleHighlight = heroSection?.title_highlight;
  const desktopImg = heroSection?.background_image || "/images/ins.png";
  const tabletImg = heroSection?.image_tablet || desktopImg;
  const mobileImg = heroSection?.image_mobile || tabletImg || desktopImg;

  return (
    <section className="insp-hero">
      <picture className="insp-hero-picture">
        {mobileImg && <source media="(max-width: 640px)" srcSet={mobileImg} />}
        {tabletImg && <source media="(max-width: 1024px)" srcSet={tabletImg} />}
        <img
          src={desktopImg}
          alt={title || "Inspiration"}
          className="insp-hero-img"
        />
      </picture>
      <nav className="site-breadcrumb site-breadcrumb--on-dark insp-hero-breadcrumb" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <span>Inspiration</span>
      </nav>
      <div className="insp-hero-copy">
        <h1>{title}</h1>
        {titleHighlight && <em>{titleHighlight}</em>}
      </div>
    </section>
  );
}
