import type { ClientsResponse, ClientResponse } from '@/types/client';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

/**
 * Fetch all clients from Laravel API
 */
export async function getClients(): Promise<ClientsResponse> {
  try {
    const response = await fetch(`${API_URL}/api/clients`, {
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
    console.error('Error fetching clients:', error);
    throw error;
  }
}

/**
 * Fetch single client by ID from Laravel API
 */
export async function getClientById(id: number): Promise<ClientResponse> {
  try {
    const response = await fetch(`${API_URL}/api/clients/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
      cache: 'no-store',
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error(`Error fetching client ${id}:`, error);
    throw error;
  }
}
