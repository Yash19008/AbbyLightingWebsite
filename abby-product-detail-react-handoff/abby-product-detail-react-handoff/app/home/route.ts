// @ts-ignore - Vite resolves static assets imported with the raw query.
import decorativeHtml from "./reference.html?raw";

export async function GET() {
  return new Response(decorativeHtml, {
    headers: {
      "Content-Type": "text/html; charset=utf-8",
      "Cache-Control": "public, max-age=300",
    },
  });
}
