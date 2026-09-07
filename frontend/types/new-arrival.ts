export interface NewArrivalProduct {
  id: number;
  name: string;
  slug: string;
  category: string;
  parent_category: string;
  image_url: string | null;
  price: number | null;
  description: string | null;
}

export interface NewArrivalCategory {
  id: number;
  name: string;
  slug: string;
  products: NewArrivalProduct[];
}
