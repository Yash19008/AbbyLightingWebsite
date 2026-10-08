import { API_URL, API_BASE } from '@/lib/config';


import { CompositionItem } from '@/types/collection';

export async function getShowcaseCompositions(): Promise<{ success: boolean; data: CompositionItem[] }> {
  try {
    const res = await fetch(`${API_URL}/compositions/showcase`, {
      cache: 'no-store'
    });
    
    if (!res.ok) {
      throw new Error('Failed to fetch showcase compositions');
    }
    
    return await res.json();
  } catch (error) {
    console.error('Error fetching showcase compositions:', error);
    return { success: false, data: [] };
  }
}
