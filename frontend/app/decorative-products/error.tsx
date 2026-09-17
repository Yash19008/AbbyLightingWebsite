'use client';

import { useEffect } from 'react';

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    console.error(error);
  }, [error]);

  return (
    <div style={{ padding: '100px 20px', textAlign: 'center', fontFamily: 'Inter, sans-serif' }}>
      <h2 style={{ fontSize: '24px', marginBottom: '16px', fontWeight: 500 }}>Unable to load products</h2>
      <p style={{ color: '#666', marginBottom: '32px' }}>There was an issue connecting to our catalog. The server might be down or restarting.</p>
      <button
        onClick={() => reset()}
        style={{
          padding: '12px 24px',
          background: '#1a1c1d',
          color: '#fff',
          border: 'none',
          cursor: 'pointer',
          fontSize: '14px',
          letterSpacing: '0.05em'
        }}
      >
        Try again
      </button>
    </div>
  );
}
