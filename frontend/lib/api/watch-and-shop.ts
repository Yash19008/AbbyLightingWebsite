import { API_URL, API_BASE } from '@/lib/config';

import type { WatchAndShopResponse, WatchAndShopItem } from '@/types/watch-and-shop';



export async function getWatchAndShops(): Promise<WatchAndShopItem[]> {
  try {
    const response = await fetch(`${API_URL}/watch-and-shops`, {
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
