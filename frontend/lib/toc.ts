export interface TocItem {
  id: string;
  title: string;
}

export function processArticleContent(html?: string): { processedHtml: string; tocItems: TocItem[] } {
  if (!html || typeof html !== "string") {
    return { processedHtml: "", tocItems: [] };
  }

  const tocItems: TocItem[] = [];
  const slugCounts: Record<string, number> = {};

  const processedHtml = html.replace(/<h2([^>]*)>(.*?)<\/h2>/gi, (match, attrs, text) => {
    const cleanText = text.replace(/<[^>]+>/g, "").trim();
    if (!cleanText) return match;

    const idMatch = attrs.match(/id=["']([^"']+)["']/i);
    let id = idMatch ? idMatch[1] : "";

    if (!id) {
      let slug = cleanText
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-")
        .replace(/^-+|-+$/g, "");

      if (!slug) slug = "section";

      if (slugCounts[slug]) {
        slugCounts[slug]++;
        id = `${slug}-${slugCounts[slug]}`;
      } else {
        slugCounts[slug] = 1;
        id = slug;
      }

      tocItems.push({ id, title: cleanText });
      return `<h2${attrs} id="${id}">${text}</h2>`;
    }

    tocItems.push({ id, title: cleanText });
    return match;
  });

  return { processedHtml, tocItems };
}
