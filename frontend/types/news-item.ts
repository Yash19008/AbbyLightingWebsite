export interface NewsItem {
  id: number;
  title: string;
  subtitle: string | null;
  image: string | null;
  link: string | null;
  // Alias for backward compatibility
  image_url?: string;
  category?: string;
}
