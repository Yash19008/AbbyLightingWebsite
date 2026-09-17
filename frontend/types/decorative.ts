export interface DecSpecItem {
  label: string;
  value: string;
  note?: string | null;
}

export interface DecColorMaster {
  name: string;
  type: "solid" | "gradient";
  hex_code?: string | null;
  gradient_start?: string | null;
  gradient_end?: string | null;
}

export interface DecVariant {
  id: number;
  name: string;
  sku: string | null;
  size: string | null;
  main_image: string | null;
  lighton_image: string | null;
  color_master: DecColorMaster | null;
  spec_rows: {
    basic_specifications: DecSpecItem[];
    dimensions: DecSpecItem[];
  };
}

export interface DecGallery {
  id: number;
  image: string;
  caption: string | null;
  order: number;
}

export interface DecRelatedProduct {
  id: number;
  name: string;
  slug: string;
  featured_image: string | null;
  category: {
    name: string;
  } | null;
}

export interface DecCollection {
  name: string;
  slug: string;
  short_description: string | null;
  band_image: string | null;
}

export interface DecProductDetail {
  id: number;
  name: string;
  slug: string;
  short_description: string | null;
  description: string | null;
  featured_image: string | null;
  installation_guide: string | null;
  care_instructions: string | null;
  show_family_section: boolean;
  collection: DecCollection | null;
  category: {
    name: string;
  } | null;
  variants: DecVariant[];
  galleries: DecGallery[];
  related_products: DecRelatedProduct[];
}
