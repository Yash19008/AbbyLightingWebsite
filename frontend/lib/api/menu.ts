import { API_URL } from '@/lib/config';

export interface MenuItem {
  id: number;
  title: string;
  url: string;
  location: string;
  type: string;
  children?: MenuItem[];
}

export async function fetchMenuItems(location?: string): Promise<MenuItem[]> {
  try {
    const query = location ? `?location=${location}` : '';
    const res = await fetch(`${API_URL}/menu-items${query}`, { next: { revalidate: 3600 } });
    if (!res.ok) return [];

    const data = await res.json();
    // console.log("fetchMenuItems result length:", data?.data?.length);
    if (data.success && Array.isArray(data.data)) {
      return data.data;
    }
    return [];
  } catch (error) {
    console.error('Error fetching menu items:', error);
    return [];
  }
}
