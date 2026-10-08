import { API_URL, API_BASE } from '@/lib/config';



export interface CategoryItem {
  id: number | string;
  name?: string;
  title?: string;
  slug?: string;
  uri?: string;
}

export async function getArchitecturalCategories(): Promise<CategoryItem[]> {
  try {
    const response = await fetch(`${API_URL}/categories`, {
      next: { revalidate: 3600 }
    });

    if (!response.ok) {
      console.error('Failed to fetch architectural categories');
      return [];
    }

    const data = await response.json();
    
    if (data.success && data.data) {
      return data.data;
    }

    return [];
  } catch (error) {
    console.error('Error fetching architectural categories:', error);
    return [];
  }
}
