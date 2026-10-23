import { API_URL, API_BASE } from '@/lib/config';


import { CompositionItem } from '@/types/collection';

export interface ShowcaseCompositionsResponse {
  success: boolean;
  data: CompositionItem[];
  categories?: string[];
  pagination?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    has_more: boolean;
  };
}

export async function getShowcaseCompositions(options?: {
  page?: number;
  per_page?: number;
  category?: string;
}): Promise<ShowcaseCompositionsResponse> {
  try {
    const params = new URLSearchParams();
    if (options?.page) params.set('page', String(options.page));
    if (options?.per_page) params.set('per_page', String(options.per_page));
    else params.set('per_page', '8');
    if (options?.category && options.category !== 'all') params.set('category', options.category);

    const query = params.toString() ? `?${params.toString()}` : '';
    const res = await fetch(`${API_URL}/compositions/showcase${query}`, {
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
