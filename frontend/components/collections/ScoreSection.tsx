export default function ScoreSection() {
  const spreadPatterns = [
    { name: 'Linear', description: 'Along a single axis.\nDirectional, architectural\nand deliberate.', className: 'spread-linear', dots: 4 },
    { name: 'Triangular', description: 'Three points forming\nasymmetric balance\nacross the field.', className: 'spread-triangular', dots: 3 },
    { name: 'Circular', description: 'Radial distribution\naround a central\naxis or void.', className: 'spread-circular', dots: 6 },
  ];

  const dropPatterns = [
    { name: 'Level', description: 'All pendants at\na single consistent\nheight.', className: 'drop-level', dots: 3 },
    { name: 'Stepped', description: 'Graduating from one height\nto another. The eye led in a\ndirection.', className: 'drop-stepped', dots: 3 },
    { name: 'Cascading', description: 'Varying heights without\nprescribed order.\nMovement through the\nvolume.', className: 'drop-cascading', dots: 3 },
  ];

  return (
    <section className="s-section s-score">
      <div className="s-head">
        <h2>Spread & Drop</h2>
        <p>Two axes of composition — how fixtures are distributed across the ceiling, and how they fall.</p>
      </div>
      <div className="s-score-grid">
        <article className="score-group">
          <div className="score-intro">
            <h3>The Spread</h3>
            <p>How the fixtures are distributed<br />across the ceiling plane.</p>
          </div>
          <div className="score-items">
            {spreadPatterns.map((pattern, index) => (
              <div key={index} className="score-item">
                <div className={`spread-pattern ${pattern.className}`} aria-hidden="true">
                  {Array.from({ length: pattern.dots }).map((_, i) => (
                    <i key={i} />
                  ))}
                </div>
                <h4>{pattern.name}</h4>
                <p style={{ whiteSpace: 'pre-line' }}>{pattern.description}</p>
              </div>
            ))}
          </div>
        </article>

        <article className="score-group">
          <div className="score-intro">
            <h3>The Drop</h3>
            <p>How the fixtures are<br />distributed vertically.</p>
          </div>
          <div className="score-items">
            {dropPatterns.map((pattern, index) => (
              <div key={index} className="score-item">
                <div className={`drop-pattern ${pattern.className}`} aria-hidden="true">
                  {Array.from({ length: pattern.dots }).map((_, i) => (
                    <i key={i} />
                  ))}
                </div>
                <h4>{pattern.name}</h4>
                <p style={{ whiteSpace: 'pre-line' }}>{pattern.description}</p>
              </div>
            ))}
          </div>
        </article>
      </div>
    </section>
  );
}
