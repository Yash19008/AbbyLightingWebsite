// @ts-ignore - Vite resolves static assets imported with the raw query.
import decorativeProductsHtml from "../decoratives/reference.html?raw";

const A = {
  hero: "/images/symphony/hero-figma.png",
  looks: ["/images/symphony/look-pastel.png", "/images/symphony/look-vivid.png", "/images/symphony/look-earth.png", "/images/symphony/look-neutral.png"],
  products: Array.from({ length: 8 }, (_, index) => `/images/symphony/product-${index + 1}.png`),
  tones: ["/images/symphony/tone-earth.png", "/images/symphony/tone-pastel.png", "/images/symphony/tone-metallic.png", "/images/symphony/tone-neutral.png"],
  places: ["/images/symphony/app-quiet.png", "/images/symphony/app-cafe.png", "/images/symphony/app-work.png", "/images/symphony/app-bar.png"],
  catalogue: "/images/symphony/catalogue.png",
};

const looks = [
  ["Pastel hues that settle gently into the space.", "Symphony II · Symphony VII"],
  ["Warm interiors, vivid accents, colour taking the lead.", "Symphony X · Symphony VIII"],
  ["Clay reds, aged timber, earth tones shaped by time.", "Symphony X"],
  ["White surfaces, deliberate restraint, composed in neutrals.", "Symphony IX"],
].map((copy, i) => `<article class="s-look" tabindex="0"><img src="${A.looks[i]}" alt="Symphony composition"><div><p>${copy[0]}</p><span>${copy[1]}</span></div></article>`).join("");

const placeCopy = [
  ["The Quiet Room", "Pastel hues that settle gently into the space."],
  ["The Corner Cafe", "Warm interiors, vivid accents, colour taking the lead."],
  ["The Workroom", "A composed rhythm of light for focus and conversation."],
  ["The Hotel Bar", "Layered warmth shaped for intimate evenings."],
];
const places = placeCopy.map(([name, description], i) => `<article class="s-place" tabindex="0"><img src="${A.places[i]}" alt="${name}"><div class="s-place-copy"><h3>${name}</h3><p>${description}</p></div></article>`).join("");

const content = `<div class="symphony-page">
  <section class="s-hero"><img src="${A.hero}" alt="Symphony Collection"><div class="s-hero-shade"></div><div class="s-hero-copy"><nav class="s-crumb" aria-label="Breadcrumb">Home&nbsp;&nbsp;/&nbsp;&nbsp;Decorative</nav><h1><span>The</span><em>Symphony Collection</em></h1><p>The Symphony Collection is a modular family of sculptural pendants built from a simple idea — choose a form, choose a tone, and compose it across your ceiling. Ten interchangeable shapes, a palette of colour families, and a language of arrangement let a single fixture become a quiet solo or a fully orchestrated ensemble. Every installation is unique, yet unmistakably Symphony.</p></div></section>
  <section class="s-section s-parameters"><div class="s-head"><h2>Four Parameters , One Composition</h2><p>Every Symphony installation is built from four choices that work together as one system.</p></div><div class="s-parameter-grid">
    <article tabindex="0"><small>The form</small><h3>The Notes</h3><span class="s-parameter-arrow" aria-hidden="true"></span><p>A sculptural pendant inspired by the quiet materiality of stone. Cymbal S pairs a hand-finished terrazzo shade with a softly diffused glass globe.</p></article>
    <article tabindex="0"><small>The Tones</small><h3>The colour</h3><span class="s-parameter-arrow" aria-hidden="true"></span><p>A curated palette in six families — Pastels, Vivids, Earths, Coastals, Neutrals and Metallics. Some create harmony, others contrast, together a broad range of expression.</p></article>
    <article tabindex="0"><small>The Ensemble</small><h3>The scale</h3><span class="s-parameter-arrow" aria-hidden="true"></span><p>A composition may be a solo or an ensemble. One pendant carries the idea in its purest form; many introduce rhythm, dialogue and variation.</p></article>
    <article tabindex="0"><small>The Score</small><h3>The arrangement</h3><span class="s-parameter-arrow" aria-hidden="true"></span><p>Written through Spread and Drop — one shaping the composition across the ceiling, the other its vertical suspension. Together they establish rhythm, balance and movement.</p></article>
  </div></section>
  <section class="s-section s-inspire"><div class="s-head"><h2>Compositions to inspire</h2><p>Each look explores a relationship between form, colour and arrangement examples of what Symphony can be, not definitions of what it should be. Hover a tag to see the piece used.</p></div><div class="s-carousel"><button class="s-carousel-arrow s-carousel-prev" type="button" aria-label="Previous compositions">‹</button><div class="s-scroll">${looks}</div><button class="s-carousel-arrow s-carousel-next" type="button" aria-label="Next compositions">›</button></div></section>
  <section class="s-section s-products"><div class="s-head"><h2>Ten Forms</h2><p>Each note is a distinct silhouette. Available across every Symphony tone.</p></div><div class="s-products-carousel"><div class="decorative-grid is-settled"></div></div><button class="s-view" type="button"><span class="s-view-desktop">View all</span><span class="s-view-mobile">View more</span></button></section>
  <section class="s-section s-tones"><div class="s-head"><h2>The colour families</h2><p>A curated palette organised into families — each with its own moodboard and named finishes.</p></div><div class="s-tone-carousel"><div class="s-tone-grid">
    <article><img src="${A.tones[0]}" alt="The Earths"><div><h3>The Earths</h3><ul><li><i style="background:#5c2f26"></i>Coffee Brown</li><li><i style="background:#b9ad9a"></i>Pebble Grey</li><li><i style="background:#b5652f"></i>Malt Brown</li><li><i style="background:#80461f"></i>Milk Chocolate</li><li><i style="background:#57604e"></i>Olive Green</li><li><i style="background:#a6332b"></i>Brick Red</li><li><i style="background:#4e2e25"></i>Chestnut Brown</li><li><i style="background:#c88566"></i>Matt Blush</li><li><i style="background:#bd8643"></i>Tan Brown</li></ul></div></article>
    <article><img src="${A.tones[2]}" alt="The Metallics"><div><h3>The Metallics</h3><ul><li><i style="background:#b5714a"></i>Metallic Copper</li><li><i style="background:#1f1f1f"></i>Matt Black</li><li><i style="background:#f2f0ea"></i>Matt White</li><li><i style="background:#c9a24b"></i>Metallic Gold</li></ul></div></article>
    <article><img src="${A.tones[3]}" alt="The Neutrals"><div><h3>The Neutrals</h3><ul><li><i style="background:#23262a"></i>Charcoal Black</li><li><i style="background:#5a636b"></i>Graphite Gray</li><li><i style="background:#9aa3ab"></i>Zen Gray</li><li><i style="background:#f4f3ee"></i>Arctic White</li></ul></div></article>
    <article><img src="${A.tones[1]}" alt="The Pastels"><div><h3>The Pastels</h3><ul><li><i style="background:#704034"></i>Icy Blue</li><li><i style="background:#b5652f"></i>Spring Green</li><li><i style="background:#57604e"></i>Matt Blush</li><li><i style="background:#4e2e25"></i>Dusky Pink</li><li><i style="background:#bd8643"></i>Pebble Grey</li></ul></div></article>
  </div><button class="s-tone-next" type="button" aria-label="Next colour family">›</button><div class="s-tone-dots" aria-hidden="true"><i class="active"></i><i></i><i></i><i></i></div></div></section>
  <section class="s-section s-score"><div class="s-head"><h2>Spread & Drop</h2><p>Two axes of composition — how fixtures are distributed across the ceiling, and how they fall.</p></div><div class="s-score-grid">
    <article class="score-group"><div class="score-intro"><h3>The Spread</h3><p>How the fixtures are distributed<br>across the ceiling plane.</p></div><div class="score-items">
      <div class="score-item"><div class="spread-pattern spread-linear" aria-hidden="true"><i></i><i></i><i></i><i></i></div><h4>Linear</h4><p>Along a single axis.<br>Directional, architectural<br>and deliberate.</p></div>
      <div class="score-item"><div class="spread-pattern spread-triangular" aria-hidden="true"><i></i><i></i><i></i></div><h4>Triangular</h4><p>How the fixtures are<br>distributed across the<br>ceiling plane.</p></div>
      <div class="score-item"><div class="spread-pattern spread-circular" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i></div><h4>Linear</h4><p>How the fixtures are<br>distributed across the<br>ceiling plane.</p></div>
    </div></article>
    <article class="score-group"><div class="score-intro"><h3>The Drop</h3><p>How the fixtures are<br>distributed vertically.</p></div><div class="score-items">
      <div class="score-item"><div class="drop-pattern drop-level" aria-hidden="true"><i></i><i></i><i></i></div><h4>Level</h4><p>How the fixtures are<br>distributed across the<br>ceiling plane.</p></div>
      <div class="score-item"><div class="drop-pattern drop-stepped" aria-hidden="true"><i></i><i></i><i></i></div><h4>Stepped</h4><p>Graduating from one height<br>to another. The eye led in a<br>direction.</p></div>
      <div class="score-item"><div class="drop-pattern drop-cascading" aria-hidden="true"><i></i><i></i><i></i></div><h4>Cascading</h4><p>Varying heights without<br>prescribed order.<br>Movement through the<br>volume.</p></div>
    </div></article>
  </div></section>
  <section class="s-section s-places"><div class="s-head"><h2>Symphony in place</h2><p>One system, composed differently for every room.</p></div><div class="s-carousel"><button class="s-carousel-arrow s-carousel-prev" type="button" aria-label="Previous rooms">‹</button><div class="s-scroll">${places}</div><button class="s-carousel-arrow s-carousel-next" type="button" aria-label="Next rooms">›</button></div></section>
  <section class="s-catalogue"><img src="${A.catalogue}" alt="Symphony catalogue"><div><h2>See the whole <em>collection.</em></h2><a href="#">Download catalogue</a></div></section>
  <section class="s-section s-related"><div class="s-head"><h2>Explore other collections</h2></div><div><a href="#"><img src="${A.looks[2]}" alt="Quarry"><h3>Quarry</h3><p>The note is set, the composition is yours</p></a><a href="#"><img src="${A.looks[3]}" alt="Neoma"><h3>Neoma</h3><p>Eternal beauty, brought to earth</p></a></div></section>
</div>`;

const heroStart = decorativeProductsHtml.indexOf('<section class="decorative-hero">');
const footerStart = decorativeProductsHtml.indexOf('<footer id="contact">');
const shellHead = decorativeProductsHtml.slice(0, heroStart)
  .replace('<link rel="stylesheet" href="/assets/decorative-looks.css?v=floating-18" />', '<link rel="stylesheet" href="/assets/symphonycollection.css?v=2" />')
  .replace('<script defer src="/assets/decorative-filter-lightbox.js"></script>', '')
  .replace('<script defer src="/assets/decorative-looks.js?v=floating-18"></script>', '');
const dockStart = shellHead.indexOf('<nav class="halo-nav-wrap');
const shellHeadWithoutDock = dockStart >= 0 ? shellHead.slice(0, dockStart) : shellHead;
const shellFooter = decorativeProductsHtml.slice(footerStart)
  .replace('</body>', '<script defer src="/assets/symphonycollection.js?v=collections-1"></script></body>');
export const symphonyDocument = shellHeadWithoutDock + content + shellFooter;
