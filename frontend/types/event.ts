export interface Event {
  id: number;
  name: string;
  source: string | null;
  source_link: string | null;
  slug: string;
  location: string | null;
  description: string | null;
  image_url: string | null;
  created_at: string;
  updated_at: string;
}
