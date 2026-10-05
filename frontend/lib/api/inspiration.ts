const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';

export interface InspirationHeroData {
  id?: number;
  title: string;
  title_highlight?: string | null;
  background_image?: string | null;
  is_active: boolean;
  breadcrumb_parent_text?: string | null;
  breadcrumb_parent_link?: string | null;
  breadcrumb_current_text?: string | null;
}


export async function getInspirationHero(): Promise<{ success: boolean; data: InspirationHeroData }> {
  try {
    const res = await fetch(`${API_BASE_URL}/api/inspiration-hero-section`, {
      cache: 'no-store',
    });

    if (!res.ok) {
      throw new Error('Failed to fetch inspiration hero');
    }

    return await res.json();
  } catch (error) {
    console.error('Error fetching inspiration hero:', error);
    return {
      success: true,
      data: {
        title: 'Ideas, stories & inspiration',
        title_highlight: 'Insights',
        breadcrumb_parent_text: 'Home',
        breadcrumb_parent_link: '/',
        breadcrumb_current_text: 'Inspiration',
        background_image: null,
        is_active: true,
      },
    };
  }
}
