export const API_BASE = (process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');
export const API_URL = `${API_BASE}/api`;

/** Build a full URL from a storage-relative path */
export function storageUrl(path: string | null | undefined, fallback = '/images/placeholder.svg'): string {
  if (!path) return fallback;
  if (path.startsWith('http')) return path;
  return `${API_BASE}/${path.replace(/^\//, '')}`;
}

/** Build a full URL for blog images */
export function blogImageUrl(filename: string | null | undefined): string {
  if (!filename) return '/images/placeholder.svg';
  if (filename.startsWith('http')) return filename;
  return `${API_BASE}/uploads/blogs/${filename}`;
}
