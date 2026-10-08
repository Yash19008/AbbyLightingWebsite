import { API_URL, API_BASE } from '@/lib/config';

import { NewsSection } from '@/types/news-section';



export async function getNewsSection(): Promise<NewsSection | null> {
  try {
    const response = await fetch(`${API_URL}/news-section`, {
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
