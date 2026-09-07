import type { WatchAndShopResponse, WatchAndShopItem } from '@/types/watch-and-shop';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

export async function getWatchAndShops(): Promise<WatchAndShopItem[]> {
  try {
    const response = await fetch(`${API_URL}/api/watch-and-shops`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      cache: 'no-store',
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const json: WatchAndShopResponse = await response.json();
    return json.data || [];
  } catch (error) {
    console.error('Error fetching watch & shop items:', error);
    return [];
  }
}
