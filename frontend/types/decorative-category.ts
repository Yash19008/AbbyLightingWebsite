export interface DecorativeCategory {
  id: number;
  name: string;
  slug: string;
  image_url: string | null;
  sort_order: number;
  children?: DecorativeCategory[];
}
