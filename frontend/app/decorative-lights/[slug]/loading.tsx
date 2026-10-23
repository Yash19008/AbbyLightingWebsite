import React from 'react';

export default function Loading() {
  return (
    <main className="product-page" style={{ opacity: 0.5 }}>
      <div className="product-shell">
        <nav className="site-breadcrumb site-breadcrumb--on-light product-breadcrumb" aria-label="Breadcrumb">
          <div style={{ width: '200px', height: '14px', background: '#e0e0e0', borderRadius: '4px' }} />
        </nav>
        <section className="product-top" style={{ display: 'flex', gap: '40px', marginTop: '20px' }}>
          <div style={{ flex: 1, height: '600px', background: '#f5f5f5', borderRadius: '8px' }} />
          <div style={{ flex: 1, padding: '40px 0' }}>
            <div style={{ width: '30%', height: '14px', background: '#e0e0e0', marginBottom: '20px', borderRadius: '4px' }} />
            <div style={{ width: '80%', height: '40px', background: '#e0e0e0', marginBottom: '30px', borderRadius: '4px' }} />
            <div style={{ width: '100%', height: '100px', background: '#f5f5f5', marginBottom: '40px', borderRadius: '4px' }} />
            <div style={{ width: '60%', height: '24px', background: '#e0e0e0', borderRadius: '4px' }} />
          </div>
        </section>
      </div>
    </main>
  );
}
