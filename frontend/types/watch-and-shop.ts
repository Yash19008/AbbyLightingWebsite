export interface WatchAndShopItem {
  id: number;
  title?: string | null;
  thumbnail: string;
  video_type: 'upload' | 'url' | 'instagram' | 'youtube';
  video_url: string;
  product_name?: string | null;
  product_link?: string | null;
  display_order: number;
}

export interface WatchAndShopResponse {
  success: boolean;
  data: WatchAndShopItem[];
}
