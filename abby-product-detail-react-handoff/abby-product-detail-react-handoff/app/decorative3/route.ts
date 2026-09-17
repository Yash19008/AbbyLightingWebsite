// @ts-ignore - Vite resolves static assets imported with the raw query.
import decorativeProductsHtml from "../decoratives/reference.html?raw";

// Decorative 3 deliberately keeps the page shell, controls and navigation from
// the catalogue, but replaces its product-card controller with the React
// catalogue's render-first card logic.
const decorativeThreeHtml = decorativeProductsHtml
  .replace('pathname: "/decorative-products"', 'pathname: "/decorative3"')
  .replace(
    '<link rel="stylesheet" href="/assets/decorative-looks.css?v=floating-18" />',
    '<link rel="stylesheet" href="/assets/decorative2.css?v=product-only-21" />\n    <link rel="stylesheet" href="/assets/decorative3-floating.css?v=topbar-1" />',
  )
  .replace(
    '<script defer src="/assets/decorative-looks.js?v=floating-18"></script>',
    '<script defer src="/assets/decorative3.js?v=react-card-logic-1"></script>',
  );

export async function GET() {
  return new Response(decorativeThreeHtml, {
    headers: {
      "Content-Type": "text/html; charset=utf-8",
      "Cache-Control": "public, max-age=300",
    },
  });
}
