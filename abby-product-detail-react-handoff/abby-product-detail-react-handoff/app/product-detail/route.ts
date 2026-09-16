import { productListingHtml } from "./product-listing-html";

export async function GET() {
  return new Response(productListingHtml, {
    headers: {
      "content-type": "text/html; charset=utf-8",
      "cache-control": "public, max-age=300",
    },
  });
}
