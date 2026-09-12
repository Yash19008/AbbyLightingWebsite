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
        <a className="btn copy-btn" href="/#contact">
          Browse the Library
        </a>
      </div>
    </section>
  );
}
