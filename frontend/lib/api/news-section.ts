import { NewsSection } from '@/types/news-section';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

export async function getNewsSection(): Promise<NewsSection | null> {
  try {
    const response = await fetch(`${API_URL}/api/news-section`, {
      cache: 'no-store',
    });

    if (!response.ok) {
      throw new Error('Failed to fetch news section');
    }

    const data = await response.json();
    
    if (data.success && data.data) {
      return data.data;
    }
    
    return null;
  } catch (error) {
    console.error('Error fetching news section:', error);
    return null;
  }
}
