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
  const parentText = heroSection?.breadcrumb_parent_text || "Home";
  const parentLink = heroSection?.breadcrumb_parent_link || "/";
  const currentText = heroSection?.breadcrumb_current_text || "Inspiration";
  const bgImage = heroSection?.background_image;

  return (
    <section 
      className="insp-hero"
      style={bgImage ? { backgroundImage: `url('${bgImage}')` } : undefined}
    >
      <nav className="site-breadcrumb site-breadcrumb--on-dark insp-hero-breadcrumb" aria-label="Breadcrumb">
        <Link href={parentLink}>{parentText}</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <span>{currentText}</span>
      </nav>
      <div className="insp-hero-copy">
        <h1>{title}</h1>
        {titleHighlight && <em>{titleHighlight}</em>}
      </div>
    </section>
  );
}
