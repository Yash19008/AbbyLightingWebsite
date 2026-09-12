import React from "react";
import Link from "next/link";

export default function InspirationHero() {
  return (
    <section className="insp-hero">
      <nav className="site-breadcrumb site-breadcrumb--on-dark insp-hero-breadcrumb" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <Link href="/decorative-products">Decorative</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <span>Inspiration</span>
      </nav>
      <div className="insp-hero-copy">
        <h1>Ideas, stories &amp; inspiration</h1>
        <em>Insights</em>
      </div>
    </section>
  );
}
