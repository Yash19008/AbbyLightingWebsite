export interface HomeCatalogueSection {
  id?: number | null;
  title: string;
  title_highlight?: string | null;
  description?: string | null;
  button_text?: string | null;
  button_link?: string | null;
  background_image_url?: string | null;
  image_tablet_url?: string | null;
  image_mobile_url?: string | null;
  is_active?: boolean;
}
