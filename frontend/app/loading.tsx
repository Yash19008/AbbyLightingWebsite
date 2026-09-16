/**
 * Homepage skeleton loading screen — shown by Next.js automatically
 * while the async page.tsx fetches server-side data.
 * Clean, consistent white/light skeleton design matching all dynamic sections.
 */
export default function HomeLoading() {
  return (
    <div className="home-skeleton">
      {/* ─── Hero Slider ─── */}
      <div className="skel-section skel-hero">
        <div className="skel-shimmer" />
        <div className="skel-hero-content">
          <div className="skel-block skel-tag" />
          <div className="skel-block skel-h1-wide" />
          <div className="skel-block skel-h1-narrow" />
          <div className="skel-block skel-para" />
          <div className="skel-block skel-btn" />
        </div>
        {/* slider dots */}
        <div className="skel-dots">
          {[0, 1, 2].map((i) => (
            <span key={i} className="skel-dot" />
          ))}
        </div>
      </div>

      {/* ─── Light Worlds ─── */}
      <div className="skel-section skel-worlds">
        <div className="skel-section-header">
          <div className="skel-block skel-label" />
          <div className="skel-block skel-h2" />
        </div>
        <div className="skel-grid skel-grid-4">
          {[0, 1, 2, 3].map((i) => (
            <div key={i} className="skel-card skel-world-card">
              <div className="skel-shimmer" />
            </div>
          ))}
        </div>
      </div>

      {/* ─── Manufacturing / About ─── */}
      <div className="skel-section skel-mfg">
        <div className="skel-mfg-inner">
          <div className="skel-mfg-text">
            <div className="skel-block skel-label" />
            <div className="skel-block skel-h2" />
            <div className="skel-block skel-h2 skel-h2-short" />
            <div className="skel-block skel-para" />
            <div className="skel-block skel-para skel-para-short" />
            <div className="skel-block skel-btn" />
          </div>
          <div className="skel-mfg-image">
            <div className="skel-shimmer" />
          </div>
        </div>
      </div>

      {/* ─── New Arrivals ─── */}
      <div className="skel-section skel-arrivals">
        <div className="skel-section-header">
          <div className="skel-block skel-label" />
          <div className="skel-block skel-h2" />
        </div>
        {/* Tab pills */}
        <div className="skel-tabs">
          {[0, 1, 2, 3].map((i) => (
            <div key={i} className="skel-block skel-tab-pill" />
          ))}
        </div>
        <div className="skel-grid skel-grid-4">
          {[0, 1, 2, 3].map((i) => (
            <div key={i} className="skel-card skel-product-card">
              <div className="skel-shimmer" />
              <div className="skel-card-body">
                <div className="skel-block skel-card-title" />
                <div className="skel-block skel-card-sub" />
                <div className="skel-block skel-card-sub skel-w60" />
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* ─── Projects ─── */}
      <div className="skel-section skel-projects">
        <div className="skel-section-header">
          <div className="skel-block skel-label" />
          <div className="skel-block skel-h2" />
        </div>
        <div className="skel-grid skel-grid-3">
          {[0, 1, 2].map((i) => (
            <div key={i} className="skel-card skel-project-card">
              <div className="skel-shimmer" />
              <div className="skel-card-body">
                <div className="skel-block skel-card-title" />
                <div className="skel-block skel-card-sub skel-w60" />
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* ─── Clients ─── */}
      <div className="skel-section skel-clients">
        <div className="skel-section-header skel-center">
          <div className="skel-block skel-h2 skel-mx-auto" />
        </div>
        <div className="skel-clients-row">
          {[0, 1, 2, 3, 4, 5].map((i) => (
            <div key={i} className="skel-client-logo">
              <div className="skel-shimmer" />
            </div>
          ))}
        </div>
      </div>

      {/* ─── Catalogue ─── */}
      <div className="skel-section skel-catalogue">
        <div className="skel-section-header">
          <div className="skel-block skel-label" />
          <div className="skel-block skel-h2" />
        </div>
        <div className="skel-grid skel-grid-4">
          {[0, 1, 2, 3].map((i) => (
            <div key={i} className="skel-card skel-catalogue-card">
              <div className="skel-shimmer" />
              <div className="skel-card-body">
                <div className="skel-block skel-card-title" />
                <div className="skel-block skel-card-sub skel-w60" />
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* ─── News / Blog ─── */}
      <div className="skel-section skel-news">
        <div className="skel-section-header">
          <div className="skel-block skel-label" />
          <div className="skel-block skel-h2" />
        </div>
        <div className="skel-grid skel-grid-3">
          {[0, 1, 2].map((i) => (
            <div key={i} className="skel-card skel-news-card">
              <div className="skel-shimmer" />
              <div className="skel-card-body">
                <div className="skel-block skel-card-tag" />
                <div className="skel-block skel-card-title" />
                <div className="skel-block skel-para skel-w80" />
                <div className="skel-block skel-para skel-w60" />
              </div>
            </div>
          ))}
        </div>
      </div>

      <style>{`
        /* ─── Base ─── */
        .home-skeleton {
          background: #ffffff;
          min-height: 100vh;
          overflow: hidden;
        }

        /* ─── Shimmer keyframe ─── */
        @keyframes skel-shimmer {
          0%   { background-position: -800px 0; }
          100% { background-position:  800px 0; }
        }

        .skel-shimmer {
          position: absolute;
          inset: 0;
          background: linear-gradient(
            90deg,
            #ececec 0%,
            #f5f5f5 40%,
            #ffffff 50%,
            #f5f5f5 60%,
            #ececec 100%
          );
          background-size: 1600px 100%;
          animation: skel-shimmer 1.6s ease-in-out infinite;
        }

        /* ─── Blocks ─── */
        .skel-block {
          border-radius: 4px;
          background: linear-gradient(
            90deg,
            #e4e4e4 0%,
            #efefef 40%,
            #f8f8f8 50%,
            #efefef 60%,
            #e4e4e4 100%
          );
          background-size: 1600px 100%;
          animation: skel-shimmer 1.6s ease-in-out infinite;
        }

        .skel-label    { height: 12px; width: 80px; margin-bottom: 12px; }
        .skel-tag      { height: 12px; width: 100px; margin-bottom: 16px; }
        .skel-h1-wide  { height: 52px; width: 70%; margin-bottom: 10px; border-radius: 6px; }
        .skel-h1-narrow{ height: 52px; width: 45%; margin-bottom: 20px; border-radius: 6px; }
        .skel-h2       { height: 36px; width: 340px; margin-bottom: 8px; border-radius: 6px; }
        .skel-h2-short { width: 220px; }
        .skel-para     { height: 14px; width: 90%; margin-bottom: 10px; }
        .skel-para-short{ width: 70%; }
        .skel-w60      { width: 60% !important; }
        .skel-w80      { width: 80% !important; }
        .skel-btn      { height: 44px; width: 140px; border-radius: 6px; margin-top: 8px; }
        .skel-tab-pill { height: 36px; width: 110px; border-radius: 20px; }
        .skel-card-tag { height: 10px; width: 70px; margin-bottom: 8px; }
        .skel-card-title{ height: 18px; width: 85%; margin-bottom: 8px; border-radius: 4px; }
        .skel-card-sub { height: 13px; width: 100%; margin-bottom: 6px; }
        .skel-mx-auto  { margin-left: auto; margin-right: auto; }

        /* ─── Sections ─── */
        .skel-section {
          padding: 80px 60px;
          position: relative;
        }

        .skel-section-header {
          margin-bottom: 40px;
        }
        .skel-center { text-align: center; display: flex; flex-direction: column; align-items: center; }

        /* Hero */
        .skel-hero {
          height: 100vh;
          padding: 0;
          display: flex;
          align-items: flex-end;
          padding-bottom: 80px;
          padding-left: 80px;
          background: #f0f0f0;
          position: relative;
        }
        .skel-hero-content { position: relative; z-index: 1; max-width: 600px; }
        .skel-dots {
          position: absolute;
          bottom: 32px;
          right: 60px;
          display: flex;
          gap: 8px;
        }
        .skel-dot {
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background: #d8d8d8;
          animation: skel-shimmer 1.6s ease-in-out infinite;
        }

        /* Tabs */
        .skel-tabs {
          display: flex;
          gap: 12px;
          margin-bottom: 32px;
        }

        /* Grids */
        .skel-grid {
          display: grid;
          gap: 24px;
        }
        .skel-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .skel-grid-3 { grid-template-columns: repeat(3, 1fr); }

        /* Cards */
        .skel-card {
          border-radius: 10px;
          overflow: hidden;
          position: relative;
          background: #f2f2f2;
        }
        .skel-card .skel-shimmer { border-radius: 0; }

        .skel-world-card   { height: 340px; }
        .skel-product-card { height: 360px; }
        .skel-project-card { height: 300px; }
        .skel-catalogue-card{ height: 280px; }
        .skel-news-card    { height: 340px; }

        .skel-card-body {
          position: absolute;
          bottom: 0;
          left: 0;
          right: 0;
          padding: 16px;
          background: linear-gradient(to top, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.4) 70%, transparent 100%);
        }

        /* Manufacturing */
        .skel-mfg { padding: 100px 60px; }
        .skel-mfg-inner {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 80px;
          align-items: center;
        }
        .skel-mfg-text { display: flex; flex-direction: column; gap: 0; }
        .skel-mfg-image {
          height: 520px;
          border-radius: 12px;
          overflow: hidden;
          position: relative;
          background: #f2f2f2;
        }

        /* Clients */
        .skel-clients { padding: 60px; }
        .skel-clients-row {
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 48px;
          flex-wrap: wrap;
          margin-top: 40px;
        }
        .skel-client-logo {
          width: 120px;
          height: 48px;
          border-radius: 6px;
          position: relative;
          overflow: hidden;
          background: #f2f2f2;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
          .skel-grid-4 { grid-template-columns: repeat(2, 1fr); }
          .skel-mfg-inner { grid-template-columns: 1fr; }
          .skel-mfg-image { height: 320px; }
        }
        @media (max-width: 640px) {
          .skel-section { padding: 60px 24px; }
          .skel-hero { padding-left: 24px; }
          .skel-grid-4 { grid-template-columns: 1fr; }
          .skel-grid-3 { grid-template-columns: 1fr; }
          .skel-h1-wide { height: 36px; width: 85%; }
          .skel-h1-narrow{ height: 36px; }
          .skel-hero { height: 85vh; }
        }
      `}</style>
    </div>
  );
}
