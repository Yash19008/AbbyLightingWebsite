export interface ManufacturingSection {
  id: number;
  title: string;
  title_highlight?: string | null;
  description?: string | null;
  button_text?: string | null;
  button_link?: string | null;
  background_image_url?: string | null;
  is_active?: boolean | string;
}
