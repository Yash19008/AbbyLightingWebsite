/**
 * Catalogues page skeleton loading screen — shown automatically by Next.js
 * when navigating to /catalogues.
 * Clean, consistent white/light skeleton design.
 */
export default function CataloguesLoading() {
  return (
    <div className="cat-skeleton-page">
      {/* ─── Hero Banner Skeleton ─── */}
      <div className="cat-skel-hero">
        <div className="cat-skel-shimmer" />
        <div className="cat-skel-hero-copy">
          <div className="cat-skel-block cat-skel-h1" />
          <div className="cat-skel-block cat-skel-h1-sub" />
        </div>
      </div>

      {/* ─── Main Section ─── */}
      <div className="cat-skel-container">
        {/* Toolbar Skeleton */}
        <div className="cat-skel-toolbar">
          <div className="cat-skel-tabs">
            {[0, 1, 2, 3, 4].map((i) => (
              <div key={i} className="cat-skel-block cat-skel-tab" />
            ))}
          </div>
          <div className="cat-skel-block cat-skel-sort-btn" />
        </div>

        {/* 3-Column Catalogue Grid Skeleton */}
        <div className="cat-skel-grid">
          {[0, 1, 2, 3, 4, 5].map((i) => (
            <div key={i} className="cat-skel-card">
              <div className="cat-skel-cover">
                <div className="cat-skel-shimmer" />
              </div>
              <div className="cat-skel-block cat-skel-title" />
            </div>
          ))}
        </div>
      </div>

      <style>{`
        .cat-skeleton-page {
          padding-top: 88px;
          background: #ffffff;
          min-height: 100vh;
          overflow: hidden;
        }

        /* ─── Shimmer Animation ─── */
        @keyframes cat-shimmer {
          0%   { background-position: -800px 0; }
          100% { background-position:  800px 0; }
        }

        .cat-skel-shimmer {
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
          animation: cat-shimmer 1.6s ease-in-out infinite;
        }

        .cat-skel-block {
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
          animation: cat-shimmer 1.6s ease-in-out infinite;
        }

        /* ─── Hero ─── */
        .cat-skel-hero {
          position: relative;
          height: 478px;
          background: #f0f0f0;
          display: flex;
          align-items: flex-end;
        }
        .cat-skel-hero-copy {
          position: absolute;
          left: max(6.6vw, 32px);
          bottom: 52px;
          z-index: 2;
          width: 100%;
          max-width: 500px;
        }
        .cat-skel-h1 {
          height: 48px;
          width: 280px;
          margin-bottom: 12px;
        }
        .cat-skel-h1-sub {
          height: 52px;
          width: 220px;
        }

        /* ─── Container ─── */
        .cat-skel-container {
          width: min(1126px, calc(100% - 64px));
          margin: 0 auto;
          padding: 80px 0 112px;
        }

        /* ─── Toolbar ─── */
        .cat-skel-toolbar {
          display: flex;
          align-items: center;
          justify-content: space-between;
          border-bottom: 0.5px solid #ccc;
          padding-bottom: 14px;
          margin-bottom: 47px;
        }
        .cat-skel-tabs {
          display: flex;
          gap: 20px;
        }
        .cat-skel-tab {
          height: 18px;
          width: 75px;
        }
        .cat-skel-sort-btn {
          height: 40px;
          width: 126px;
          border-radius: 0;
          flex-shrink: 0;
        }

        /* ─── Grid & Cards ─── */
        .cat-skel-grid {
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 73px 24px;
        }
        .cat-skel-card {
          display: flex;
          flex-direction: column;
        }
        .cat-skel-cover {
          position: relative;
          aspect-ratio: 359 / 427;
          background: #f2f2f2;
          border-radius: 2px;
          overflow: hidden;
        }
        .cat-skel-title {
          height: 22px;
          width: 65%;
          margin-top: 13px;
        }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
          .cat-skel-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 40px 18px;
          }
        }
        @media (max-width: 600px) {
          .cat-skel-hero {
            height: 340px;
          }
          .cat-skel-container {
            width: calc(100% - 32px);
            padding: 40px 0 60px;
          }
          .cat-skel-grid {
            grid-template-columns: 1fr;
            gap: 32px;
          }
          .cat-skel-tabs {
            display: none;
          }
        }
      `}</style>
    </div>
  );
}
