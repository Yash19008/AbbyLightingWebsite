export interface Client {
  id: number;
  path: string;
  image_url: string | null;
  is_featured?: boolean;
  created_at: string;
  updated_at: string;
}

export interface ClientsResponse {
  success: boolean;
  data: Client[];
}

export interface ClientResponse {
  success: boolean;
  data: Client;
}
