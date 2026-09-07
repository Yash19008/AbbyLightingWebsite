import type { DecorativeCategory } from "@/types/decorative-category";

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

export async function getDecorativeCategories(): Promise<DecorativeCategory[]> {
  try {
    const response = await fetch(`${API_URL}/api/decorative-categories`, {
      next: { revalidate: 3600 } // Cache for 1 hour
    });

    if (!response.ok) {
      console.error('Failed to fetch decorative categories');
      return [];
    }

    const data = await response.json();
    
    if (data.success && data.data) {
      return data.data;
    }

    return [];
  } catch (error) {
    console.error('Error fetching decorative categories:', error);
    return [];
  }
}

export async function getArchitecturalCategories(): Promise<any[]> {
  try {
    const response = await fetch(`${API_URL}/api/categories`, {
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
