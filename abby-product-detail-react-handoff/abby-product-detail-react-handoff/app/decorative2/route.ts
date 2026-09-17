// @ts-ignore - Vite resolves static assets imported with the raw query.
import decorativeProductsHtml from "../decoratives/reference.html?raw";

const decorativeTwoHtml = decorativeProductsHtml
  .replace('pathname: "/decorative-products"', 'pathname: "/decorative2"')
  .replace(
    '<link rel="stylesheet" href="/assets/decorative-looks.css?v=floating-18" />',
    '<link rel="stylesheet" href="/assets/decorative2.css?v=product-only-21" />',
  )
  .replace(
    '<script defer src="/assets/decorative-looks.js?v=floating-18"></script>',
    '<script defer src="/assets/decorative2.js?v=product-only-21"></script>',
  );

export async function GET() {
  return new Response(decorativeTwoHtml, {
    headers: {
      "Content-Type": "text/html; charset=utf-8",
      "Cache-Control": "public, max-age=300",
    },
  });
}
