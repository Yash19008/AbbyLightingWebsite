export interface BlogCategory {
  id?: number;
  name?: string;
  slug?: string;
}

export interface Blog {
  id?: number;
  slug?: string;
  title?: string;
  dek?: string;
  author?: string;
  content?: string;
  featured_image?: string;
  featured_image_caption?: string;
  secondary_image?: string;
  secondary_image_caption?: string;
  meta_title?: string;
  meta_description?: string;
  published_at?: string;
  category_id?: number;
  category?: BlogCategory;
}
