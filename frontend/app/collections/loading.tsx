/**
 * Collections page skeleton loading screen — shown automatically by Next.js
 * while fetching collection data server-side.
 * Clean, unified white/light skeleton design matching all collection sections.
 */
export default function CollectionsLoading() {
  return (
    <div className="coll-skeleton-page">
      {/* ─── Hero Section ─── */}
      <div className="coll-skel-hero">
        <div className="coll-skel-shimmer" />
        <div className="coll-skel-hero-inner">
          <div className="coll-skel-block coll-skel-breadcrumb" />
          <div className="coll-skel-block coll-skel-hero-title" />
          <div className="coll-skel-block coll-skel-hero-sub" />
        </div>
      </div>

      {/* ─── Parameters Bar ─── */}
      <div className="coll-skel-params-wrap">
        <div className="coll-skel-params">
          {[0, 1, 2, 3].map((i) => (
            <div key={i} className="coll-skel-param-item">
              <div className="coll-skel-block coll-skel-param-label" />
              <div className="coll-skel-block coll-skel-param-val" />
            </div>
          ))}
        </div>
      </div>

      {/* ─── Products Section ─── */}
      <div className="coll-skel-section coll-skel-shell">
        <div className="coll-skel-sec-head">
          <div className="coll-skel-block coll-skel-sec-title" />
          <div className="coll-skel-block coll-skel-sec-deck" />
        </div>

        {/* 4-column Products Grid */}
        <div className="coll-skel-products-grid">
          {[0, 1, 2, 3, 4, 5, 6, 7].map((i) => (
            <div key={i} className="coll-skel-prod-card">
              <div className="coll-skel-prod-thumb">
                <div className="coll-skel-shimmer" />
              </div>
              <div className="coll-skel-prod-info">
                <div className="coll-skel-block coll-skel-prod-tag" />
                <div className="coll-skel-block coll-skel-prod-name" />
                <div className="coll-skel-block coll-skel-prod-spec" />
              </div>
            </div>
          ))}
        </div>
      </div>

      {/* ─── Compositions Section ─── */}
      <div className="coll-skel-section coll-skel-shell">
        <div className="coll-skel-composition">
          <div className="coll-skel-comp-left">
            <div className="coll-skel-block coll-skel-sec-title" />
            <div className="coll-skel-block coll-skel-sec-deck" />
            <div className="coll-skel-block coll-skel-comp-para" />
            <div className="coll-skel-block coll-skel-comp-btn" />
          </div>
          <div className="coll-skel-comp-right">
            <div className="coll-skel-shimmer" />
          </div>
        </div>
      </div>

      {/* ─── Related Collections ─── */}
      <div className="coll-skel-section coll-skel-shell coll-skel-related">
        <div className="coll-skel-sec-head">
          <div className="coll-skel-block coll-skel-sec-title" />
        </div>
        <div className="coll-skel-related-grid">
          {[0, 1, 2].map((i) => (
            <div key={i} className="coll-skel-related-card">
              <div className="coll-skel-related-img">
                <div className="coll-skel-shimmer" />
              </div>
              <div className="coll-skel-block coll-skel-related-title" />
            </div>
          ))}
        </div>
      </div>

      <style>{`
        .coll-skeleton-page {
          background: #ffffff;
          min-height: 100vh;
          overflow: hidden;
        }

        /* ─── Shimmer ─── */
        @keyframes coll-shimmer {
          0%   { background-position: -800px 0; }
          100% { background-position:  800px 0; }
        }

        .coll-skel-shimmer {
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
          animation: coll-shimmer 1.6s ease-in-out infinite;
        }

        .coll-skel-block {
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
          animation: coll-shimmer 1.6s ease-in-out infinite;
        }

        /* ─── Hero ─── */
        .coll-skel-hero {
          height: 600px;
          position: relative;
          background: #f0f0f0;
          display: flex;
          align-items: flex-end;
          padding: 0 6.45vw 70px;
        }
        .coll-skel-hero-inner {
          position: relative;
          z-index: 2;
          width: 100%;
          max-width: 600px;
        }
        .coll-skel-breadcrumb {
          height: 14px;
          width: 190px;
          margin-bottom: 22px;
        }
        .coll-skel-hero-title {
          height: 52px;
          width: 380px;
          margin-bottom: 12px;
        }
        .coll-skel-hero-sub {
          height: 24px;
          width: 240px;
        }

        /* ─── Parameters ─── */
        .coll-skel-params-wrap {
          border-bottom: 1px solid #eee;
          padding: 28px 6.45vw;
          background: #fafafa;
        }
        .coll-skel-params {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 24px;
        }
        .coll-skel-param-item {
          display: flex;
          flex-direction: column;
          gap: 8px;
        }
        .coll-skel-param-label {
          height: 12px;
          width: 60px;
        }
        .coll-skel-param-val {
          height: 20px;
          width: 120px;
        }

        /* ─── Shell ─── */
        .coll-skel-shell {
          width: 100%;
          padding: 80px 6.45vw 0;
          box-sizing: border-box;
        }
        .coll-skel-related {
          padding-bottom: 100px;
        }

        .coll-skel-sec-head {
          margin-bottom: 40px;
        }
        .coll-skel-sec-title {
          height: 36px;
          width: 280px;
          margin-bottom: 10px;
        }
        .coll-skel-sec-deck {
          height: 16px;
          width: 440px;
        }

        /* ─── Products Grid ─── */
        .coll-skel-products-grid {
          display: grid;
          grid-template-columns: repeat(4, 1fr);
          gap: 24px;
        }
        .coll-skel-prod-card {
          background: #f8f8f8;
          border-radius: 6px;
          overflow: hidden;
          display: flex;
          flex-direction: column;
        }
        .coll-skel-prod-thumb {
          height: 320px;
          position: relative;
          background: #f0f0f0;
        }
        .coll-skel-prod-info {
          padding: 16px;
        }
        .coll-skel-prod-tag {
          height: 11px;
          width: 50px;
          margin-bottom: 8px;
        }
        .coll-skel-prod-name {
          height: 18px;
          width: 80%;
          margin-bottom: 6px;
        }
        .coll-skel-prod-spec {
          height: 13px;
          width: 55%;
        }

        /* ─── Composition ─── */
        .coll-skel-composition {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 60px;
          align-items: center;
          background: #fcfcfc;
          border: 1px solid #f0f0f0;
          border-radius: 8px;
          padding: 48px;
        }
        .coll-skel-comp-para {
          height: 60px;
          width: 90%;
          margin: 16px 0 24px;
        }
        .coll-skel-comp-btn {
          height: 44px;
          width: 140px;
        }
        .coll-skel-comp-right {
          height: 380px;
          position: relative;
          border-radius: 6px;
          overflow: hidden;
          background: #f0f0f0;
        }

        /* ─── Related ─── */
        .coll-skel-related-grid {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 24px;
        }
        .coll-skel-related-card {
          border-radius: 6px;
          overflow: hidden;
        }
        .coll-skel-related-img {
          height: 300px;
          position: relative;
          background: #f0f0f0;
          border-radius: 6px;
        }
        .coll-skel-related-title {
          height: 20px;
          width: 60%;
          margin-top: 14px;
        }

        /* ─── Responsive ─── */
        @media (max-width: 1024px) {
          .coll-skel-products-grid {
            grid-template-columns: repeat(2, 1fr);
          }
          .coll-skel-params {
            grid-template-columns: repeat(2, 1fr);
          }
          .coll-skel-composition {
            grid-template-columns: 1fr;
          }
        }
        @media (max-width: 640px) {
          .coll-skel-hero {
            height: 400px;
            padding: 0 20px 40px;
          }
          .coll-skel-shell {
            padding: 50px 20px 0;
          }
          .coll-skel-products-grid {
            grid-template-columns: 1fr;
          }
          .coll-skel-related-grid {
            grid-template-columns: 1fr;
          }
          .coll-skel-params {
            grid-template-columns: 1fr;
          }
        }
      `}</style>
    </div>
  );
}
