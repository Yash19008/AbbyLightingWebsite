import assert from "node:assert/strict";
import { readFile } from "node:fs/promises";
import test from "node:test";

const developmentPreviewMeta =
  /<meta(?=[^>]*\bname=["']codex-preview["'])(?=[^>]*\bcontent=["']development["'])[^>]*>/i;

test("renders development preview metadata", async () => {
  const workerUrl = new URL("../dist/server/index.js", import.meta.url);
  workerUrl.searchParams.set("test", `${process.pid}-${Date.now()}`);
  const { default: worker } = await import(workerUrl.href);

  const response = await worker.fetch(
    new Request("http://localhost/home", {
      headers: { accept: "text/html" },
    }),
    {
      ASSETS: {
        fetch: async () => new Response("Not found", { status: 404 }),
      },
    },
    {
      waitUntil() {},
      passThroughOnException() {},
    },
  );

  assert.equal(response.status, 200);
  assert.match(
    response.headers.get("content-type") ?? "",
    /^text\/html\b/i,
  );
  assert.match(await response.text(), developmentPreviewMeta);
});

test("renders the decorative catalogue with its shared shell and local assets", async () => {
  const workerUrl = new URL("../dist/server/index.js", import.meta.url);
  workerUrl.searchParams.set("decoratives-test", `${process.pid}-${Date.now()}`);
  const { default: worker } = await import(workerUrl.href);

  const response = await worker.fetch(
    new Request("http://localhost/decoratives", {
      headers: { accept: "text/html" },
    }),
    {
      ASSETS: {
        fetch: async () => new Response("Not found", { status: 404 }),
      },
    },
    {
      waitUntil() {},
      passThroughOnException() {},
    },
  );

  assert.equal(response.status, 200);
  assert.match(response.headers.get("content-type") ?? "", /^text\/html\b/i);

  const html = await response.text();
  assert.match(html, /<header class="sitehead">/);
  assert.match(html, /<footer id="contact">/);
  assert.match(html, /Decorative<!-- -->\s*<em>Lights<\/em>/);
  assert.match(html, /Cymbal/);
  assert.match(html, /Symphony IV/);
  assert.match(html, /\/assets\/decorative-route-links\.js/);
  assert.match(html, /\/assets\/decorative-looks\.css/);
  assert.match(html, /\/assets\/decorative-looks\.js/);
  assert.doesNotMatch(html, /final-home-shell-B2XRKU5R\.js/);
});

test("decorative catalogue enhancer provides twenty cards and reuses base cards in looks", async () => {
  const script = await readFile(new URL("../public/assets/decorative-looks.js", import.meta.url), "utf8");
  const catalogueBlock = script.match(/const catalogueProducts = \[([\s\S]*?)\]\.map/);
  assert.ok(catalogueBlock);
  assert.equal((catalogueBlock[1].match(/\{ key:/g) ?? []).length, 20);
  assert.match(script, /function productCardNode\(/);
  assert.match(script, /\.is-dummy-product \.decorative-card-image/);
  assert.match(script, /const batchSize = mobile \? 8 : 12/);
  assert.match(script, /const endsAtLookBoundary = requestedLimit < catalogueProducts\.length/);
  assert.match(script, /requestedLimit \+ \(endsAtLookBoundary \? 2 : 0\)/);
  assert.match(script, /function lookInsertionPoints\(productCount\)/);
  assert.match(script, /Math\.floor\(productCount \/ 6\)/);
  assert.match(script, /lookInsertionPoints\(products\.length\)/);
  assert.doesNotMatch(script, /syncLookDimensions/);
  assert.doesNotMatch(script, /modalImage/);
  assert.match(script, /modalPictureMarkup = \(look, stateClass\) => pictureMarkup\(look, stateClass, "eager"\)/);
  assert.match(script, /function setupFloatingToolbar\(/);
  assert.match(script, /sourceTools\.cloneNode\(true\)/);
  assert.match(script, /sourceLight\.click\(\)/);
  assert.match(script, /new ResizeObserver\(syncFloatingPosition\)/);

  const styles = await readFile(new URL("../public/assets/decorative-looks.css", import.meta.url), "utf8");
  assert.doesNotMatch(styles, /decorative-look-heading/);
  assert.doesNotMatch(styles, /--look-card-height/);
  assert.doesNotMatch(styles, /height:\s*596px/);
  assert.match(styles, /\.decorative-look-slot\s*\{[^}]*grid-row:\s*span 2;[^}]*align-self:\s*stretch/s);
  assert.match(styles, /\.decorative-look-card\s*\{[^}]*height:\s*100%;[^}]*min-height:\s*100%/s);
  assert.match(styles, /\.decorative-look-hero \.decorative-look-modal-picture img\s*\{[^}]*object-fit:\s*cover;[^}]*object-position:\s*center/s);
  assert.match(styles, /grid-template-columns:\s*1fr/);
  assert.match(styles, /aspect-ratio:\s*16 \/ 10/);
  assert.match(styles, /translateY\(100%\)/);
  assert.match(styles, /@media \(width < 768px\)/);
  assert.match(styles, /\.decorative-floating-tools\s*\{[^}]*z-index:\s*4000;[^}]*position:\s*fixed/s);
  assert.match(styles, /\.decorative-floating-tools\.is-visible\s*\{[^}]*opacity:\s*1/s);
  assert.match(styles, /bottom:\s*calc\(84px \+ env\(safe-area-inset-bottom\)\)/);
  assert.match(styles, /\.decorative-filter-button::after\s*\{[^}]*content:\s*"Filter"/s);
  assert.doesNotMatch(styles, /\.decorative-mobile-category,\s*\.decorative-tabs,\s*\.decorative-filter-button\s*\{\s*display:\s*none/s);
  assert.match(styles, /transition:\s*transform 220ms cubic-bezier\(\.23, 1, \.32, 1\), opacity 180ms ease-out/);

  const modalReference = await readFile(new URL("../public/images/decorative/lookbook-hotel-layout.png", import.meta.url));
  assert.deepEqual([...modalReference.subarray(1, 4)], [80, 78, 71]);
});

test("decorative2 is a product-only variation with a full floating toolbar", async () => {
  const workerUrl = new URL("../dist/server/index.js", import.meta.url);
  workerUrl.searchParams.set("decorative2", `${process.pid}-${Date.now()}`);
  const { default: worker } = await import(workerUrl.href);
  const response = await worker.fetch(
    new Request("http://localhost/decorative2", { headers: { accept: "text/html" } }),
    { ASSETS: { fetch: async () => new Response("Not found", { status: 404 }) } },
    { waitUntil() {}, passThroughOnException() {} },
  );
  const html = await response.text();
  assert.match(html, /decorative2\.css\?v=product-only-21/);
  assert.match(html, /decorative2\.js\?v=product-only-21/);
  assert.doesNotMatch(html, /decorative-looks\.(css|js)/);

  const script = await readFile(new URL("../public/assets/decorative2.js", import.meta.url), "utf8");
  assert.match(script, /const catalogueProducts = \[/);
  assert.match(script, /const batchSize = matchMedia\("\(max-width: 600px\)"\)\.matches \? 8 : 12/);
  assert.match(script, /shell\.appendChild\(toolbar\.cloneNode\(true\)\)/);
  assert.match(script, /const dockIsVisible = \(dock\) =>/);
  assert.match(script, /dock\.classList\.contains\("dock-hidden"\)/);
  assert.match(script, /clone\.style\.removeProperty\("display"\)/);
  assert.match(script, /function applyLightState\(lit\)/);
  assert.match(script, /sourceLight\.dispatchEvent\(new Event\("change", \{ bubbles: true \}\)\)/);
  assert.doesNotMatch(script, /decorative-look|lookCard|buildModal/);

  const styles = await readFile(new URL("../public/assets/decorative2.css", import.meta.url), "utf8");
  assert.match(styles, /\.decorative2-floating-toolbar\s*\{[^}]*position:\s*fixed;[^}]*top:\s*var\(--decorative2-floating-top\)/s);
  assert.match(styles, /bottom:\s*var\(--decorative2-dock-offset, calc\(97px \+ env\(safe-area-inset-bottom\)\)\)/);
  assert.doesNotMatch(styles, /decorative-look/);
});
