const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

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
