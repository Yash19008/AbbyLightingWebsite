// Collection Types for Next.js Frontend

export interface CollectionHeroSection {
  background_image: string | null;
  title_prefix: string;
  title_highlight: string;
  description: string;
  breadcrumb_parent_text?: string;
  breadcrumb_parent_link?: string;
}

export interface ParameterItem {
  id: number;
  small_text: string;
  title: string;
  description: string;
  bg_color?: string | null;
  hover_bg_color?: string | null;
  order: number;
}

export interface ParametersSection {
  title: string;
  subtitle: string;
  items: ParameterItem[];
}

export interface CompositionItem {
  id: number;
  image: string;
  title: string;
  category: string;
  kicker: string;
}

export interface CompositionsSection {
  title: string;
  subtitle: string;
  items: CompositionItem[];
}

export interface Color {
  id: number;
  name: string;
  code: string;
  css_value: string;
  type: 'solid' | 'gradient';
}

export interface ToneFamily {
  id: number;
  title: string;
  image: string;
  order: number;
  colors: Color[];
}

export interface TonesSection {
  title: string;
  subtitle: string;
  families: ToneFamily[];
}

export interface PlaceItem {
  id: number;
  place_name: string;
  image: string;
  description: string;
  products: string;
  order: number;
}

export interface PlacesSection {
  title: string;
  subtitle: string;
  items: PlaceItem[];
}

export interface Product {
  id: number;
  title: string;
  slug: string;
  featured_image: string | null;
}

export interface CollectionDetail {
  id: number;
  slug: string;
  name: string;
  short_description?: string | null;
  description: string;
  meta_title: string;
  meta_description: string;
  hero_section: CollectionHeroSection | null;
  parameters_section: ParametersSection | null;
  compositions_section: CompositionsSection | null;
  tones_section: TonesSection | null;
  places_section: PlacesSection | null;
  spread_drop_section?: { is_active: boolean } | null;
  products?: Product[];
}

export interface CollectionListItem {
  id: number;
  slug: string;
  name: string;
  short_description?: string | null;
  description: string;
  hero_section: {
    background_image: string | null;
    title_prefix: string;
    title_highlight: string;
    description: string;
  } | null;
}

export interface CollectionsApiResponse {
  success: boolean;
  data: CollectionListItem[];
}

export interface CollectionDetailApiResponse {
  success: boolean;
  data: CollectionDetail;
}
