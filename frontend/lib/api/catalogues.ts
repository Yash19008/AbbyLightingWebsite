const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

export interface CatalogueCategoryDto {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  image_url: string | null;
  catalogues_count?: number;
  sort_order?: number;
}

export interface CatalogueDto {
  id: number;
  title: string;
  slug: string;
  description: string | null;
  category_id: number;
  category?: {
    id: number;
    name: string;
    slug: string;
  } | null;
  cover_image: string | null;
  pdf_url: string | null;
  download_url?: string | null;
  pdf_file_name: string | null;
  file_size: string | null;
  is_featured: boolean;
  sort_order: number;
  created_at: string | null;
}

export interface CatalogueCategoriesResponse {
  success: boolean;
  data: CatalogueCategoryDto[];
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  has_more: boolean;
}

export interface CataloguesResponse {
  success: boolean;
  data: CatalogueDto[];
  pagination?: PaginationMeta;
}

/**
 * Fetch active catalogue categories
 */
export async function getCatalogueCategories(): Promise<CatalogueCategoriesResponse> {
  try {
    const response = await fetch(`${API_BASE_URL}/api/catalogue-categories`, {
      cache: 'no-store'
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch catalogue categories: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error('Error fetching catalogue categories:', error);
    return { success: false, data: [] };
  }
}

/**
 * Fetch catalogues with optional filter and server-side pagination
 */
export async function getCatalogues(params?: {
  category?: string;
  featured?: boolean;
  search?: string;
  sort?: string;
  page?: number;
  per_page?: number;
}): Promise<CataloguesResponse> {
  try {
    const url = new URL(`${API_BASE_URL}/api/catalogues`);
    if (params?.category && params.category !== 'all' && params.category !== 'All') {
      url.searchParams.set('category', params.category);
    }
    if (params?.featured) {
      url.searchParams.set('featured', '1');
    }
    if (params?.search) {
      url.searchParams.set('search', params.search);
    }
    if (params?.sort) {
      url.searchParams.set('sort', params.sort);
    }
    if (params?.page) {
      url.searchParams.set('page', String(params.page));
    }
    if (params?.per_page) {
      url.searchParams.set('per_page', String(params.per_page));
    }

    const response = await fetch(url.toString(), {
      cache: 'no-store'
    });

    if (!response.ok) {
      throw new Error(`Failed to fetch catalogues: ${response.statusText}`);
    }

    return response.json();
  } catch (error) {
    console.error('Error fetching catalogues:', error);
    return { success: false, data: [] };
  }
}

export interface CatalogDownloadPayload {
  name: string;
  email: string;
  phone?: string;
  mobile?: string;
  city?: string;
  company?: string;
  role?: string;
  message?: string;
  catalogue_name?: string;
  catalogue_id?: number;
}

export async function submitCatalogDownload(payload: CatalogDownloadPayload): Promise<{ success: boolean; message?: string }> {
  try {
    const response = await fetch(`${API_BASE_URL}/api/catalog-downloads`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error submitting catalogue download lead:', error);
    return { success: false, message: 'Failed to submit form. Please try again.' };
  }
}

