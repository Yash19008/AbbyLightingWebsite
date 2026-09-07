export interface Project {
  id: number;
  name: string;
  type: string;
  location: string;
  description: string | null;
  slug: string;
  sequence: number;
  image_url: string | null;
  created_at: string;
  updated_at: string;
}
