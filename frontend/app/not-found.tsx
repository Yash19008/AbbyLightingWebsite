import Link from 'next/link';

export default function NotFound() {
  return (
    <div style={{
      minHeight: '70vh',
      display: 'flex',
      flexDirection: 'column',
      alignItems: 'center',
      justifyContent: 'center',
      textAlign: 'center',
      padding: '40px 20px',
      color: '#ffffff',
      background: '#111111'
    }}>
      <h1 style={{ fontSize: '3rem', fontWeight: 600, marginBottom: '16px', color: '#f6c177' }}>404</h1>
      <h2 style={{ fontSize: '1.5rem', marginBottom: '24px' }}>Page Not Found</h2>
      <p style={{ color: '#888888', maxWidth: '460px', marginBottom: '32px' }}>
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
      </p>
      <Link
        href="/"
        style={{
          display: 'inline-block',
          padding: '12px 28px',
          background: '#f6c177',
          color: '#111111',
          fontWeight: 600,
          borderRadius: '4px',
          textDecoration: 'none'
        }}
      >
        Back to Home
      </Link>
    </div>
  );
}
