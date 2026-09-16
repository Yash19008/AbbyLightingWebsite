/**
 * Inspiration page skeleton loading screen — shown automatically by Next.js
 * when navigating to /inspiration.
 * Clean, unified white/light skeleton design matching all sections.
 */
export default function InspirationLoading() {
  return (
    <div className="insp-skeleton-page">
      {/* ─── Hero Banner Skeleton (Clean Light) ─── */}
      <div className="insp-skel-hero">
        <div className="insp-skel-shimmer-light" />
        <div className="insp-skel-hero-content">
          <div className="insp-skel-block insp-skel-breadcrumb" />
          <div className="insp-skel-block insp-skel-h1" />
          <div className="insp-skel-block insp-skel-h1-italic" />
        </div>
      </div>

      <div className="insp-skel-shell">
        {/* ─── Looks In Place Skeleton ─── */}
        <div className="insp-skel-section">
          <div className="insp-skel-head">
            <div>
              <div className="insp-skel-block insp-skel-title" />
              <div className="insp-skel-block insp-skel-deck" />
            </div>
            <div className="insp-skel-block insp-skel-filter-btn" />
          </div>

          <div className="insp-skel-looks-grid">
            {[0, 1, 2, 3, 4, 5, 6, 7].map((i) => (
              <div key={i} className="insp-skel-look-card">
                <div className="insp-skel-shimmer-light" />
                <div className="insp-skel-card-body-bottom">
                  <div className="insp-skel-block insp-skel-card-title-light" />
                  <div className="insp-skel-block insp-skel-card-sub-light" />
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* ─── Watch & Shop Skeleton ─── */}
        <div className="insp-skel-section">
          <div className="insp-skel-head-simple">
            <div className="insp-skel-block insp-skel-title" />
            <div className="insp-skel-block insp-skel-deck" />
          </div>

          <div className="insp-skel-reels-grid">
            {[0, 1, 2, 3].map((i) => (
              <div key={i} className="insp-skel-reel-card">
                <div className="insp-skel-shimmer-light" />
                <div className="insp-skel-play-circle" />
              </div>
            ))}
          </div>
        </div>

        {/* ─── Journal / Guides & Stories Skeleton ─── */}
        <div className="insp-skel-section insp-skel-journal">
          <div className="insp-skel-head">
            <div className="insp-skel-block insp-skel-title" />
            <div className="insp-skel-block insp-skel-sort-btn" />
          </div>

          {/* Category tabs */}
          <div className="insp-skel-tabs">
            {[0, 1, 2, 3, 4].map((i) => (
              <div key={i} className="insp-skel-block insp-skel-tab" />
            ))}
          </div>

          <div className="insp-skel-story-grid">
            {[0, 1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="insp-skel-story-card">
                <div className="insp-skel-story-img">
                  <div className="insp-skel-shimmer-light" />
                </div>
                <div className="insp-skel-story-copy">
                  <div className="insp-skel-block insp-skel-story-tag" />
                  <div className="insp-skel-block insp-skel-story-heading" />
                  <div className="insp-skel-block insp-skel-story-heading insp-skel-w70" />
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <style>{`
        .insp-skeleton-page {
          background: #ffffff;
          min-height: 100vh;
          overflow: hidden;
        }

        /* ─── Shimmer Animations ─── */
        @keyframes insp-shimmer {
          0%   { background-position: -800px 0; }
          100% { background-position:  800px 0; }
        }

        .insp-skel-shimmer-light {
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
          animation: insp-shimmer 1.6s ease-in-out infinite;
        }

        .insp-skel-block {
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
          animation: insp-shimmer 1.6s ease-in-out infinite;
        }

        /* ─── Hero Section ─── */
        .insp-skel-hero {
          height: 575px;
          position: relative;
          background: #f0f0f0;
          display: flex;
          align-items: flex-end;
          padding-left: 6.45vw;
          padding-bottom: 67px;
        }
        .insp-skel-hero-content {
          position: relative;
          z-index: 2;
          width: 100%;
          max-width: 650px;
        }
        .insp-skel-breadcrumb {
          height: 14px;
          width: 180px;
          margin-bottom: 24px;
        }
        .insp-skel-h1 {
          height: 46px;
          width: 90%;
          margin-bottom: 12px;
        }
        .insp-skel-h1-italic {
          height: 40px;
          width: 200px;
        }

        /* ─── Shell ─── */
        .insp-skel-shell {
          width: 100%;
          padding: 0 6.45vw;
          box-sizing: border-box;
        }

        .insp-skel-section {
          padding: 80px 0 0;
        }
        .insp-skel-journal {
          padding-bottom: 80px;
        }

        .insp-skel-head {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          margin-bottom: 35px;
          gap: 24px;
        }
        .insp-skel-head-simple {
          margin-bottom: 30px;
        }

        .insp-skel-title {
          height: 38px;
          width: 320px;
          margin-bottom: 14px;
        }
        .insp-skel-deck {
          height: 18px;
          width: 480px;
          max-width: 100%;
        }
        .insp-skel-filter-btn {
          height: 40px;
          width: 140px;
          border-radius: 0;
          flex-shrink: 0;
        }
        .insp-skel-sort-btn {
          height: 38px;
          width: 130px;
          border-radius: 0;
          flex-shrink: 0;
        }

        /* ─── Looks Grid ─── */
        .insp-skel-looks-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 14px;
        }
        .insp-skel-look-card {
          height: 429px;
          position: relative;
          border-radius: 2px;
          overflow: hidden;
          background: #f2f2f2;
        }
        .insp-skel-card-body-bottom {
          position: absolute;
          bottom: 0;
          left: 0;
          right: 0;
          padding: 20px;
          background: linear-gradient(to top, rgba(255, 255, 255, 0.9) 0%, transparent 100%);
          z-index: 2;
        }
        .insp-skel-card-title-light {
          height: 18px;
          width: 75%;
          margin-bottom: 8px;
        }
        .insp-skel-card-sub-light {
          height: 12px;
          width: 45%;
        }

        /* ─── Reels Grid ─── */
        .insp-skel-reels-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 14px;
        }
        .insp-skel-reel-card {
          height: 545px;
          position: relative;
          border-radius: 4px;
          overflow: hidden;
          background: #f2f2f2;
        }
        .insp-skel-play-circle {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          width: 58px;
          height: 58px;
          border-radius: 50%;
          background: rgba(255, 255, 255, 0.95);
          box-shadow: 0 4px 15px rgba(0,0,0,0.06);
          z-index: 2;
        }

        /* ─── Journal Tabs & Grid ─── */
        .insp-skel-tabs {
          display: flex;
          gap: 39px;
          border-bottom: 1px solid #d8d8d8;
          margin: 30px 0 35px;
          padding-bottom: 16px;
        }
        .insp-skel-tab {
          height: 16px;
          width: 70px;
        }

        .insp-skel-story-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 28px 16px;
        }
        .insp-skel-story-card {
          background: #f8f8f8;
          border-radius: 4px;
          overflow: hidden;
          display: flex;
          flex-direction: column;
        }
        .insp-skel-story-img {
          height: 300px;
          position: relative;
          background: #f2f2f2;
        }
        .insp-skel-story-copy {
          padding: 18px 18px 22px;
        }
        .insp-skel-story-tag {
          height: 14px;
          width: 60px;
          margin-bottom: 10px;
        }
        .insp-skel-story-heading {
          height: 20px;
          width: 90%;
          margin-bottom: 8px;
        }
        .insp-skel-w70 {
          width: 65% !important;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
          .insp-skel-looks-grid {
            grid-template-columns: repeat(2, 1fr);
          }
          .insp-skel-reels-grid {
            grid-template-columns: repeat(2, 1fr);
          }
          .insp-skel-story-grid {
            grid-template-columns: repeat(2, 1fr);
          }
        }

        @media (max-width: 640px) {
          .insp-skel-hero {
            height: 420px;
            padding-left: 20px;
            padding-bottom: 40px;
          }
          .insp-skel-shell {
            padding: 0 20px;
          }
          .insp-skel-looks-grid {
            grid-template-columns: 1fr;
          }
          .insp-skel-reels-grid {
            grid-template-columns: 1fr;
          }
          .insp-skel-story-grid {
            grid-template-columns: 1fr;
          }
          .insp-skel-title {
            width: 220px;
            height: 30px;
          }
        }
      `}</style>
    </div>
  );
}
