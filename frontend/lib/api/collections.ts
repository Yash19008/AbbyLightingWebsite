import { CollectionsApiResponse, CollectionDetailApiResponse } from '@/types/collection';

const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

/**
 * Fetch all collections
 */
export async function getCollections(): Promise<CollectionsApiResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/api/collections`, {
      next: { revalidate: 60 } // Revalidate every 60 seconds
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch collections: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error('Error fetching collections:', error);
    throw error;
  }
}

/**
 * Fetch single collection by slug with all sections
 */
export async function getCollection(slug: string): Promise<CollectionDetailApiResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/api/collections/${slug}`, {
      next: { revalidate: 60 } // Revalidate every 60 seconds
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch collection: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error(`Error fetching collection ${slug}:`, error);
    throw error;
  }
}
