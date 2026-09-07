export default function CatalogueSection() {
  return (
    <section className="image-cta">
      <img 
        className="catalogue-media section-parallax-media" 
        src="/images/figma-update/catalogue.png" 
        alt="" 
      />
      <div className="copy reveal">
        <h2>
          Find the right <em>catalogue.</em>
        </h2>
        <p>
          Explore our complete collection of architectural, decorative and outdoor lighting, with detailed specifications for every luminaire.
        </p>
        <a className="btn" href="/#contact">
          View Catalogues
        </a>
      </div>
    </section>
  );
}
