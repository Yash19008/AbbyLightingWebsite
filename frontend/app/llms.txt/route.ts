import { API_URL } from '@/lib/config';
import { SITE_URL } from '@/lib/seo/meta-config';

export const dynamic = 'force-dynamic';
export const revalidate = 3600;

interface CollectionSummary {
  name: string;
  slug: string;
  meta_description?: string;
  hero_section?: { description?: string };
}

interface DecProductSummary {
  name: string;
  slug: string;
  short_description?: string;
  description?: string;
}

interface BlogSummary {
  title: string;
  slug: string;
  dek?: string;
  meta_description?: string;
}

interface CatalogueSummary {
  title: string;
  file_size?: string;
  description?: string;
}

export async function GET() {
  const baseUrl = SITE_URL.replace(/\/$/, '');

  // Fetch dynamic sections in parallel with safety
  const [collectionsRes, decProductsRes, blogsRes, cataloguesRes] = await Promise.allSettled([
    fetch(`${API_URL}/collections`, { next: { revalidate: 3600 }, cache: 'no-store' }).then((r) => r.json()),
    fetch(`${API_URL}/dec-products`, { next: { revalidate: 3600 }, cache: 'no-store' }).then((r) => r.json()),
    fetch(`${API_URL}/blogs`, { next: { revalidate: 3600 }, cache: 'no-store' }).then((r) => r.json()),
    fetch(`${API_URL}/catalogues`, { next: { revalidate: 3600 }, cache: 'no-store' }).then((r) => r.json()),
  ]);

  const collections: CollectionSummary[] =
    collectionsRes.status === 'fulfilled' && collectionsRes.value?.success && Array.isArray(collectionsRes.value.data)
      ? collectionsRes.value.data
      : [];

  const decProducts: DecProductSummary[] =
    decProductsRes.status === 'fulfilled' && decProductsRes.value?.success && Array.isArray(decProductsRes.value.data)
      ? decProductsRes.value.data
      : [];

  const blogs: BlogSummary[] =
    blogsRes.status === 'fulfilled' && blogsRes.value?.success && Array.isArray(blogsRes.value.data)
      ? blogsRes.value.data
      : [];

  const catalogues: CatalogueSummary[] =
    cataloguesRes.status === 'fulfilled' && cataloguesRes.value?.success && Array.isArray(cataloguesRes.value.data)
      ? cataloguesRes.value.data
      : [];

  const text = `# Abby Lighting

> India's premier architectural and decorative lighting manufacturer. Engineered, tested, and manufactured in-house for high-performance commercial, hospitality, institutional, and luxury residential projects globally.

Website: ${baseUrl}
Canonical Domain: https://abbylighting.com

## About Abby Lighting
Abby Lighting is a vertically integrated lighting design and manufacturing company based in India. With complete in-house capabilities spanning precision optical design, CNC machining, laser cutting, automated surface-mount electronic assembly (SMT), electrostatic powder coating, and a photometric testing laboratory equipped with a Type-C Goniophotometer and integrating spheres, Abby Lighting delivers world-class architectural and decorative luminaires.

### Key Capabilities & Engineering Standards
- **High Color Fidelity**: CRI 90+ standard with high R9 (>50) for true color rendering.
- **MacAdam Consistency**: 2-step and 3-step MacAdam ellipse color binning across all production runs.
- **Thermal & Driver Reliability**: High-efficiency, flicker-free drivers supporting DALI-2, Casambi BLE mesh, 0-10V, and Phase dimming.
- **Modular & Serviceable**: Designed on circular economy principles with field-replaceable drivers and LED engines.
- **Ingress & In-House Testing**: Robust ingress protection (IP20 up to IP68) and corrosion-resistant powder coatings for tropical and coastal environments.

---

## Lighting Worlds & Categories

### 1. Architectural Lighting
High-performance luminaires for retail, corporate workplaces, museums, galleries, and healthcare:
- **Recessed Downlights**: Spotboy, Luke, Rad, Rio, Flute, Microline, iSoft Deep
- **Track & Framing Projectors**: Spot-on Framer 48V, Spotboy SF, Dark Knight Flat
- **Linear Systems**: LightLine, Roa, Microline, Linea Magic 48V
- **Wall Washers**: iWash, Lensed Wallwasher, iGraze, Look Around
- **Link**: ${baseUrl}/products

### 2. Decorative Lighting
Sculptural contemporary pendants, custom chandeliers, wall lights, and floor lamps crafted in India with artisan finishes:
- **Signature Fixtures**: Apex, Canopy, Dew, Node, Orb, Seam, Turret, Symphony IV, Circulo
- **Custom Finishes**: Brushed Brass, Raw Concrete, Terracotta, Textured Graphite, Champagne Gold, Matte Black
- **Link**: ${baseUrl}/decorative-lights

### 3. Collections (Modular Systems)
Architectural-decorative hybrid lighting systems with configurable parameters, tones, and compositions:
- **Link**: ${baseUrl}/collections

### 4. Outdoor & Landscape Lighting
Rugged architectural landscape luminaires: inground uplights, bollards, facade graziers, step lights, and underwater fixtures with marine-grade coatings and IP67/IP68 ratings.

### 5. Abby Smart Lighting
Advanced intelligent lighting controls:
- DALI-2, DMX512, Casambi Wireless Mesh, Tunable White (2700K - 6500K), and Human Centric Circadian Lighting.
- **Link**: ${baseUrl}/abby-smart

---

## Professional Design Tools

### Ambient Light Calculator
A professional lighting calculation tool for architects, lighting designers, and MEP consultants. Computes required lumens, lux levels, fixture spacing, and layout specifications based on room dimensions, ceiling height, and surface reflectance.
- **Tool URL**: ${baseUrl}/light-calculator

---

## Key Site Pages
- **Home**: ${baseUrl}
- **Collections**: ${baseUrl}/collections
- **Decorative Lights**: ${baseUrl}/decorative-lights
- **Architectural Products**: ${baseUrl}/products
- **Product Catalogues**: ${baseUrl}/catalogues
- **Inspiration & Spaces**: ${baseUrl}/inspiration
- **Manufacturing & Journal**: ${baseUrl}/blogs
- **Light Calculator**: ${baseUrl}/light-calculator
- **Projects Portfolio**: ${baseUrl}/projects
- **Clients & Partners**: ${baseUrl}/clients
- **Company Story**: ${baseUrl}/company
- **Contact & Showrooms**: ${baseUrl}/contact

---

## Active Collections (${collections.length})
${
  collections.length > 0
    ? collections
        .map(
          (c) =>
            `- **${c.name}**: ${baseUrl}/collections/${c.slug}\n  ${
              c.meta_description || c.hero_section?.description || 'Modular architectural lighting collection.'
            }`
        )
        .join('\n\n')
    : `- **Symphony IV**: ${baseUrl}/collections/symphony-iv\n- **Circulo**: ${baseUrl}/collections/circulo`
}

---

## Active Decorative Products (${decProducts.length})
${
  decProducts.length > 0
    ? decProducts
        .map(
          (p) =>
            `- **${p.name}**: ${baseUrl}/decorative-lights/${p.slug}\n  ${
              p.short_description || p.description || 'Decorative luminaire handcrafted in India.'
            }`
        )
        .join('\n\n')
    : `- **Apex**: ${baseUrl}/decorative-lights/apex\n- **Canopy**: ${baseUrl}/decorative-lights/canopy\n- **Dew**: ${baseUrl}/decorative-lights/dew\n- **Node**: ${baseUrl}/decorative-lights/node\n- **Orb**: ${baseUrl}/decorative-lights/orb\n- **Seam**: ${baseUrl}/decorative-lights/seam\n- **Turret**: ${baseUrl}/decorative-lights/turret`
}

---

## Latest Articles & Design Insights (${blogs.length})
${
  blogs.length > 0
    ? blogs
        .map(
          (b) =>
            `- **${b.title}**: ${baseUrl}/blogs/${b.slug}\n  ${
              b.dek || b.meta_description || 'Design and manufacturing story from Abby Lighting.'
            }`
        )
        .join('\n\n')
    : `- **Made in India, designed for the world**: ${baseUrl}/blogs/made-in-india-designed-for-the-world`
}

---

## Product Catalogues & Specification Downloads (${catalogues.length})
${
  catalogues.length > 0
    ? catalogues
        .map(
          (cat) =>
            `- **${cat.title}**${cat.file_size ? ` (${cat.file_size})` : ''}: ${baseUrl}/catalogues\n  ${
              cat.description || 'Downloadable specification sheets and lookbooks.'
            }`
        )
        .join('\n\n')
    : `- **Complete Architectural Lighting Catalogue**: ${baseUrl}/catalogues\n- **Decorative Lighting Collection Lookbook**: ${baseUrl}/catalogues`
}

---

## Contact, Showrooms & Inquiries
- **Showrooms & Experience Centres**: Mumbai, Delhi NCR, Bengaluru, Hyderabad, Pune, Ahmedabad
- **Head Office & Works**: Abby Lighting, Mumbai, India
- **General Inquiries**: info@abbylighting.com
- **Project Specifications**: sales@abbylighting.com
- **Project Inquiry Form**: ${baseUrl}/contact
- **Sitemap**: ${baseUrl}/sitemap.xml
`;

  return new Response(text, {
    status: 200,
    headers: {
      'Content-Type': 'text/plain; charset=utf-8',
      'Cache-Control': 'public, max-age=3600, s-maxage=3600',
    },
  });
}
