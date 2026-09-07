import React from "react";
import type { TocItem } from "@/lib/toc";

interface BlogTableOfContentsProps {
  items?: TocItem[];
}

export default function BlogTableOfContents({ items }: BlogTableOfContentsProps) {
  if (!items || items.length === 0) {
    return null;
  }

  return (
    <section className="toc-band" aria-labelledby="toc-heading">
      <div className="toc">
        <h2 id="toc-heading">In This Article</h2>
        <nav aria-label="Article contents">
          {items.map((item, idx) => (
            <a key={`${item.id}-${idx}`} href={`#${item.id.replace(/^#/, "")}`}>
              {item.title}
            </a>
          ))}
        </nav>
      </div>
    </section>
  );
}
