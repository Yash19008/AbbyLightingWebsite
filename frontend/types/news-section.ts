export interface NewsSection {
  id: number;
  title: string;
  subtitle: string | null;
  image: string | null;
  link: string | null;
  is_active: boolean;
}
