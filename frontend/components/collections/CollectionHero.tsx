import Image from 'next/image';
import Link from 'next/link';

export default function CollectionHero() {
  return (
    <section className="s-hero">
      <Image
        src="/images/symphony/hero-figma.png"
        alt="Symphony Collection"
        fill
        priority
        className="s-hero-image"
        style={{ objectFit: 'cover' }}
      />
      <div className="s-hero-shade" />
      <nav className="site-breadcrumb site-breadcrumb--on-dark s-crumb" aria-label="Breadcrumb">
        <Link href="/">Home</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <Link href="/decorative-products">Decorative</Link>
        &nbsp;&nbsp;/&nbsp;&nbsp;
        <span>Symphony Collection</span>
      </nav>
      <div className="s-hero-copy">
        <h1>
          <span>The</span>
          <em>Symphony Collection</em>
        </h1>
        <p>
          The Symphony Collection is a modular family of sculptural pendants built from a simple idea — choose a form, choose a tone, and compose it across your ceiling. Ten interchangeable shapes, a palette of colour families, and a language of arrangement let a single fixture become a quiet solo or a fully orchestrated ensemble. Every installation is unique, yet unmistakably Symphony.
        </p>
      </div>
    </section>
  );
}
