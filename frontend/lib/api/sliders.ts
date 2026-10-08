import { API_URL, API_BASE } from '@/lib/config';

import type { SlidersResponse } from '@/types/slider';



/**
 * Fetch all sliders from Laravel API
 */
export async function getSliders(): Promise<SlidersResponse> {
  try {
    const response = await fetch(`${API_URL}/sliders`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      cache: 'no-store', // Disable caching for fresh data
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching sliders:', error);
    throw error;
  }
}
