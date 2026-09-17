// @ts-ignore - Vite resolves static assets imported with the raw query.
import decorativeProductsHtml from "./reference.html?raw";

export async function GET() {
  return new Response(decorativeProductsHtml, {
    headers: {
      "Content-Type": "text/html; charset=utf-8",
      "Cache-Control": "public, max-age=300",
    },
  });
}
