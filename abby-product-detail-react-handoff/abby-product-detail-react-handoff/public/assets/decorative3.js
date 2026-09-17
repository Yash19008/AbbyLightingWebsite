(() => {
  // This is the same stable list → filter/sort → slice → "Load more" model
  // used by the supplied React catalogue. Cards are rendered from state rather
  // than cloned or individually hidden, so a grid can never retain empty cells.
  const products = [
    ["cymbal", "Cymbal", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["dew", "Dew", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["apex", "Apex", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["node", "Node", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["seam", "Seam", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["orb", "Orb", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["canopy", "Canopy", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["turret", "Turret", "Pendant", "Quarry", ["White", "Black", "Terra"]],
    ["symphonyiv", "Symphony IV", "Pendant", "Symphony", ["Colour"]],
    ["cymbal", "Aria", "Pendant", "Symphony", ["Colour"]],
    ["dew", "Cadence", "Pendant", "Symphony", ["Colour"]],
    ["orb", "Wall Mount", "Wall", "Neoma", ["Brass"]],
  ].map(([imageKey, name, type, collection, finishes]) => ({ imageKey, name, type, collection, finishes }));

  const swatchColors = { White: "#f2f0ea", Black: "#1f1f1f", Terra: "#9c482a", Colour: "#d3af79", Brass: "#b69665" };
  let category = "All";
  let sort = "new";
  let visibleCount = 9;
  let filterMatches = () => true;

  const grid = () => document.querySelector(".decorative-grid");
  const cardMatches = (product) => (category === "All" || product.type === category) && filterMatches(product);
  const visibleProducts = () => products.filter(cardMatches).sort((a, b) => sort === "name" ? a.name.localeCompare(b.name) : 0);
  const esc = (value) => String(value).replace(/[&<>'"]/g, (char) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", "'": "&#39;", '"': "&quot;" }[char]));

  function cardMarkup(product, index) {
    const lightOn = document.querySelector(".decorative-toolbar .decorative-light-toggle input")?.checked !== false;
    const productPath = product.name === "Symphony IV" ? "/product-detail/symphony-iv" : "/product-detail";
    const swatches = product.finishes.map((finish, finishIndex) => `<button type="button" class="${finishIndex === 0 ? "active" : ""}" style="background:${swatchColors[finish] || "#d3af79"}" title="${finish}" aria-label="Select ${finish}"></button>`).join("");
    return `<div class="decorative-grid-item-motion" data-product-name="${esc(product.name)}" data-product-type="${esc(product.type)}" data-product-collection="${esc(product.collection)}" style="--filter-delay:${index * 40}ms">
      <article class="decorative-card decorative-reveal is-visible" style="--card-order:${index % 3};--light-delay:${index * 50}ms">
        <div class="decorative-card-image ${lightOn ? "is-lit" : "is-unlit"}">
          <a class="decorative-card-main-link" href="${productPath}" aria-label="View ${esc(product.name)}">
            <img class="decorative-product-image is-current is-light-off" src="/images/decorative/${product.imageKey}-off.png" alt="" aria-hidden="true" />
            <img class="decorative-product-image is-current is-light-on" src="/images/decorative/${product.imageKey}-on.png" alt="${esc(product.name)}" />
          </a>
          <button type="button" class="decorative-card-arrow decorative-card-prev" aria-label="Previous ${esc(product.name)} image">‹</button>
          <button type="button" class="decorative-card-arrow decorative-card-next" aria-label="Next ${esc(product.name)} image">›</button>
          <span class="decorative-gallery-dots"><button class="active" type="button" aria-label="Show ${esc(product.name)} view 1"></button><button type="button" aria-label="Show ${esc(product.name)} view 2"></button><button type="button" aria-label="Show ${esc(product.name)} view 3"></button></span>
        </div>
        <div class="decorative-card-copy"><h3>${esc(product.name)}</h3><p>${esc(product.type)} Light</p><div class="decorative-swatches" aria-label="${esc(product.name)} finishes">${swatches}</div><span class="decorative-collection">${esc(product.collection)} Collection</span></div>
      </article>
    </div>`;
  }

  function renderProducts() {
    const target = grid();
    if (!target) return;
    const matching = visibleProducts();
    target.innerHTML = matching.slice(0, visibleCount).map(cardMarkup).join("");
    const loadMore = document.querySelector(".decorative-load-more");
    const button = loadMore?.querySelector("button");
    if (loadMore && button) {
      loadMore.hidden = visibleCount >= matching.length;
      button.textContent = "Load more";
      button.setAttribute("aria-label", `Load ${Math.min(6, Math.max(0, matching.length - visibleCount))} more products`);
    }
    document.querySelector(".decorative-filter-empty")?.remove();
    if (!matching.length) {
      const empty = document.createElement("p");
      empty.className = "decorative-empty decorative-filter-empty";
      empty.textContent = "No pieces match these filters yet.";
      target.insertAdjacentElement("afterend", empty);
    }
  }

  function setLight(lit) {
    document.querySelectorAll(".decorative-light-toggle").forEach((label) => {
      const input = label.querySelector("input");
      if (input) input.checked = lit;
      label.classList.toggle("is-on", lit); label.classList.toggle("is-off", !lit);
      const desktop = label.querySelector(".decorative-light-desktop");
      const mobile = label.querySelector(".decorative-light-mobile");
      if (desktop) desktop.textContent = lit ? "Light on" : "Light off";
      if (mobile) mobile.textContent = lit ? "On" : "Off";
    });
    document.querySelectorAll(".decorative-card-image").forEach((image) => {
      image.classList.toggle("is-lit", lit); image.classList.toggle("is-unlit", !lit);
    });
  }

  function setupInteractions() {
    document.querySelector(".decorative-load-more button")?.addEventListener("click", () => { visibleCount += 6; renderProducts(); });
    document.addEventListener("click", (event) => {
      const tab = event.target.closest(".decorative-tabs button");
      if (tab) { category = tab.textContent.trim(); visibleCount = 9; renderProducts(); }
      const arrow = event.target.closest(".decorative-card-arrow");
      if (arrow) {
        const card = arrow.closest(".decorative-card-image");
        const dots = Array.from(card.querySelectorAll(".decorative-gallery-dots button"));
        const active = Math.max(0, dots.findIndex((dot) => dot.classList.contains("active")));
        const next = (active + (arrow.classList.contains("decorative-card-next") ? 1 : dots.length - 1)) % dots.length;
        dots[next]?.click();
      }
      const dot = event.target.closest(".decorative-gallery-dots button");
      if (dot) dot.parentElement.querySelectorAll("button").forEach((candidate) => candidate.classList.toggle("active", candidate === dot));
      const swatch = event.target.closest(".decorative-swatches button");
      if (swatch) swatch.parentElement.querySelectorAll("button").forEach((candidate) => candidate.classList.toggle("active", candidate === swatch));
    });
    document.addEventListener("change", (event) => {
      if (event.target.matches(".decorative-light-toggle input")) setLight(event.target.checked);
      if (event.target.matches(".decorative-sort select")) { sort = event.target.value; visibleCount = 9; renderProducts(); }
      if (event.target.matches(".decorative-mobile-category select")) { category = event.target.value; visibleCount = 9; renderProducts(); }
    });
    window.decorative3SetFilter = (matcher) => { filterMatches = typeof matcher === "function" ? matcher : () => true; visibleCount = 9; renderProducts(); };
  }

  function setupFloatingToolbar() {
    const source = document.querySelector(".decorative-toolbar");
    if (!source) return;
    const floating = document.createElement("aside");
    floating.className = "decorative2-floating-toolbar";
    floating.setAttribute("aria-label", "Product controls");
    floating.innerHTML = `<div class="decorative-shell"></div>`;
    const clone = source.cloneNode(true); floating.firstElementChild.appendChild(clone); document.body.appendChild(floating);
    const sync = () => {
      clone.querySelectorAll(".decorative-tabs button").forEach((button, i) => {
        const original = source.querySelectorAll(".decorative-tabs button")[i]; button.classList.toggle("active", original?.classList.contains("active"));
      });
      const originalSort = source.querySelector(".decorative-sort select"); const cloneSort = clone.querySelector(".decorative-sort select"); if (originalSort && cloneSort) cloneSort.value = originalSort.value;
      const originalLight = source.querySelector(".decorative-light-toggle input"); if (originalLight) setLight(originalLight.checked);
    };
    clone.addEventListener("click", (event) => {
      const tab = event.target.closest(".decorative-tabs button"); if (tab) source.querySelectorAll(".decorative-tabs button")[Array.from(clone.querySelectorAll(".decorative-tabs button")).indexOf(tab)]?.click();
      if (event.target.closest(".decorative-filter-button")) source.querySelector(".decorative-filter-button")?.click();
    });
    clone.addEventListener("change", (event) => {
      if (event.target.matches(".decorative-sort select")) { const select = source.querySelector(".decorative-sort select"); select.value = event.target.value; select.dispatchEvent(new Event("change", { bubbles: true })); }
      if (event.target.matches(".decorative-light-toggle input")) setLight(event.target.checked);
    });
    const header = document.querySelector(".decorative-global-nav .sitehead");
    const update = () => {
      const headerHeight = Math.ceil(header?.getBoundingClientRect().height || 111);
      floating.style.setProperty("--decorative2-floating-top", `${headerHeight}px`);
      const passed = source.getBoundingClientRect().bottom <= headerHeight;
      floating.classList.toggle("is-visible", passed); sync();
    };
    window.addEventListener("scroll", update, { passive: true }); window.addEventListener("resize", update); new MutationObserver(update).observe(document.body, { attributes: true, subtree: true, attributeFilter: ["class", "style", "hidden"] }); update();
  }

  function initialize() { renderProducts(); setupInteractions(); setupFloatingToolbar(); setLight(document.querySelector(".decorative-light-toggle input")?.checked !== false); }
  document.readyState === "loading" ? document.addEventListener("DOMContentLoaded", initialize, { once: true }) : initialize();
})();
