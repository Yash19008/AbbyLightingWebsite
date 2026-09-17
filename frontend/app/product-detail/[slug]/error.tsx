'use client';

import { useEffect } from 'react';
import Link from 'next/link';

export default function Error({
  error,
  reset,
}: {
  error: Error & { digest?: string };
  reset: () => void;
}) {
  useEffect(() => {
    // Log the error to an error reporting service
    console.error(error);
  }, [error]);

  return (
    <div style={{ padding: '100px 20px', textAlign: 'center', fontFamily: 'Inter, sans-serif' }}>
      <h2 style={{ fontSize: '24px', marginBottom: '16px', fontWeight: 500 }}>Something went wrong!</h2>
      <p style={{ color: '#666', marginBottom: '32px' }}>We were unable to load the product details. The server might be down or restarting.</p>
      <div style={{ display: 'flex', gap: '16px', justifyContent: 'center' }}>
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
        <Link 
          href="/decorative-products"
          style={{
            padding: '12px 24px',
            background: '#f2f2f2',
            color: '#1a1c1d',
            textDecoration: 'none',
            border: '1px solid #ddd',
            fontSize: '14px',
            letterSpacing: '0.05em'
          }}
        >
          Go back to listing
        </Link>
      </div>
    </div>
  );
}
