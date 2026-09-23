const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
import { CompositionItem } from '@/types/collection';

export async function getShowcaseCompositions(): Promise<{ success: boolean; data: CompositionItem[] }> {
  try {
    const res = await fetch(`${API_BASE_URL}/api/compositions/showcase`, {
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
