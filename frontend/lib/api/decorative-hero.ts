import { API_URL } from '@/lib/config';
import { DecorativeHeroData } from '@/types/decorative';

export async function getDecorativeHero(): Promise<{ success: boolean; data: DecorativeHeroData }> {
  try {
    const res = await fetch(`${API_URL}/decorative-hero`, {
      cache: 'no-store',
    });

    if (!res.ok) {
      throw new Error('Failed to fetch decorative hero');
    }

    return await res.json();
  } catch (error) {
    console.error('Error fetching decorative hero:', error);
    return {
      success: true,
      data: {
        heading: 'Decorative',
        styled_heading: 'Lights',
        description:
          'Sculptural pendants, wall lights, floor and table lamps across the Symphony, Quarry and Neoma collections — cast concrete discs, colour-blocked forms and lunar orbs, all made to order.',
        image_desktop: null,
        image_tablet: null,
        image_mobile: null,
        is_active: true,
      },
    };
  }
}
