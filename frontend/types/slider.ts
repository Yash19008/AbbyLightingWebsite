export interface Slider {
  id: number;
  path: string;
  mobile_path?: string | null;
  tablet_path?: string | null;
  image_url: string | null;
  mobile_image_url?: string | null;
  tablet_image_url?: string | null;
  for_mobile: number;
  sort_order: number;
  url: string | null;
  heading: string | null;
  heading_highlight: string | null;
  description: string | null;
  button_text: string | null;
  button_link: string | null;
  created_at: string;
  updated_at: string;
}

export interface SlidersResponse {
  success: boolean;
  data: {
    web: Slider[];
    mobile: Slider[];
    all: Slider[];
  };
}
