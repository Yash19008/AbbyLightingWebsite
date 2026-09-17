(() => {
  const catalogueProducts = [
    { key: "cymbal", name: "Cymbal", category: "Pendant Light", collection: "Quarry", imageKey: "cymbal" },
    { key: "dew", name: "Dew", category: "Pendant Light", collection: "Quarry", imageKey: "dew" },
    { key: "apex", name: "Apex", category: "Pendant Light", collection: "Quarry", imageKey: "apex" },
    { key: "node", name: "Node", category: "Pendant Light", collection: "Quarry", imageKey: "node" },
    { key: "seam", name: "Seam", category: "Pendant Light", collection: "Quarry", imageKey: "seam" },
    { key: "orb", name: "Orb", category: "Pendant Light", collection: "Quarry", imageKey: "orb" },
    { key: "canopy", name: "Canopy", category: "Pendant Light", collection: "Quarry", imageKey: "canopy" },
    { key: "turret", name: "Turret", category: "Pendant Light", collection: "Quarry", imageKey: "turret" },
    { key: "symphonyiv", name: "Symphony IV", category: "Pendant Light", collection: "Symphony", imageKey: "symphonyiv", href: "/product-detail/symphony-iv" },
    { key: "aria", name: "Aria", category: "Pendant Light", collection: "Symphony", imageKey: "cymbal" },
    { key: "cadence", name: "Cadence", category: "Pendant Light", collection: "Symphony", imageKey: "dew" },
    { key: "halo", name: "Halo", category: "Wall Light", collection: "Neoma", imageKey: "orb" },
    { key: "ember", name: "Ember", category: "Pendant Light", collection: "Quarry", imageKey: "apex" },
    { key: "axis", name: "Axis", category: "Pendant Light", collection: "Symphony", imageKey: "node" },
    { key: "plume", name: "Plume", category: "Wall Light", collection: "Neoma", imageKey: "seam" },
    { key: "sol", name: "Sol", category: "Pendant Light", collection: "Quarry", imageKey: "canopy" },
    { key: "orbit", name: "Orbit", category: "Pendant Light", collection: "Quarry", imageKey: "turret" },
    { key: "veil", name: "Veil", category: "Wall Light", collection: "Neoma", imageKey: "orb" },
    { key: "arc", name: "Arc", category: "Pendant Light", collection: "Symphony", imageKey: "symphonyiv" },
    { key: "dusk", name: "Dusk", category: "Pendant Light", collection: "Quarry", imageKey: "dew" },
  ].map((product) => ({ href: "/product-detail", ...product }));

  const productData = Object.fromEntries(catalogueProducts.map((product) => [product.key, product]));

  const looks = [
    {
      title: "Warm interiors, vivid accents, colour taking the lead.",
      subtitle: "Symphony VIII · Pendant Light",
      modalTitle: "Stone & light in a hotel arrival",
      modalSubtitle: "Lookbook · Hospitality",
      desktop: "/images/figma-update/hero-decorative.png",
      mobile: "/images/figma-update/hero-decorative-mobile-frame139.png",
      products: ["cymbal", "dew", "apex"],
    },
    {
      title: "Pastel hues that settle gently into the space.",
      subtitle: "Symphony IV · Pendant Light",
      modalTitle: "Pastel hues in a quiet retreat",
      modalSubtitle: "Lookbook · Residential",
      desktop: "/images/decorative/hero.png",
      mobile: "/images/decorative/hero.png",
      products: ["symphonyiv", "canopy", "turret"],
    },
  ];

  let openIndex = null;
  let lastTrigger = null;
  let modal = null;
  let observer = null;
  let mounting = false;
  let mountTimer = 0;
  let loadedBatches = 1;
  let previousBodyOverflow = "";

  const pictureMarkup = (look, stateClass, loading = "lazy") => `
    <picture class="decorative-look-picture ${stateClass}" aria-hidden="true">
      <source media="(max-width: 600px)" srcset="${look.mobile}">
      <img src="${look.desktop}" alt="" loading="${loading}">
    </picture>`;

  const modalPictureMarkup = (look, stateClass) => pictureMarkup(look, stateClass, "eager")
    .replace("decorative-look-picture", "decorative-look-picture decorative-look-modal-picture");

  const isLightOn = () => document.querySelector(".decorative-light-toggle input")?.checked !== false;

  function prepareProductCard(item, product, index) {
    item.dataset.productKey = product.key;
    item.style.setProperty("--filter-delay", `${index * 40}ms`);
    item.querySelectorAll(".decorative-product-image.is-previous").forEach((image) => image.remove());
    item.querySelector(".decorative-card")?.style.setProperty("--card-order", String(index % 3));
    const title = item.querySelector(".decorative-card-copy h3");
    const category = item.querySelector(".decorative-card-copy p");
    const collection = item.querySelector(".decorative-collection");
    if (title) title.textContent = product.name;
    if (category) category.textContent = product.category;
    if (collection) collection.textContent = `${product.collection} Collection`;
    item.querySelectorAll(".decorative-card-main-link").forEach((link) => {
      link.href = product.href;
      link.setAttribute("aria-label", `View ${product.name}`);
    });
    item.querySelectorAll(".decorative-product-image.is-light-off").forEach((image) => {
      image.src = `/images/decorative/${product.imageKey}-off.png`;
      image.alt = "";
    });
    item.querySelectorAll(".decorative-product-image.is-light-on").forEach((image) => {
      image.src = `/images/decorative/${product.imageKey}-on.png`;
      image.alt = `${product.name}, light on`;
    });
  }

  function ensureTwentyProducts(grid) {
    const items = Array.from(grid.querySelectorAll(":scope > .decorative-grid-item-motion"));
    items.slice(0, catalogueProducts.length).forEach((item, index) => {
      prepareProductCard(item, catalogueProducts[index], index);
    });
    while (items.length < catalogueProducts.length) {
      const index = items.length;
      const product = catalogueProducts[index];
      const sourceIndex = Math.max(0, catalogueProducts.findIndex((candidate) => candidate.key === product.imageKey));
      const template = items[sourceIndex] || items[index % Math.max(items.length, 1)];
      if (!template) break;
      const clone = template.cloneNode(true);
      clone.classList.add("is-visible", "is-dummy-product");
      prepareProductCard(clone, product, index);
      grid.appendChild(clone);
      items.push(clone);
    }
  }

  function applyProductPagination(grid, mobile) {
    const items = Array.from(grid.querySelectorAll(":scope > .decorative-grid-item-motion"));
    const batchSize = mobile ? 8 : 12;
    const requestedLimit = Math.min(catalogueProducts.length, batchSize * loadedBatches);
    const endsAtLookBoundary = requestedLimit < catalogueProducts.length
      && requestedLimit % 6 === 0
      && requestedLimit / 6 <= looks.length;
    const visibleLimit = Math.min(
      catalogueProducts.length,
      requestedLimit + (endsAtLookBoundary ? 2 : 0),
    );
    items.forEach((item, index) => {
      item.hidden = index >= visibleLimit;
      item.setAttribute("aria-hidden", item.hidden ? "true" : "false");
    });

    const container = document.querySelector(".decorative-load-more");
    let button = container?.querySelector("button");
    if (container && button && button.dataset.lookPaginationReady !== "true") {
      const replacement = button.cloneNode(true);
      button.replaceWith(replacement);
      button = replacement;
      button.dataset.lookPaginationReady = "true";
      button.addEventListener("click", () => {
        loadedBatches += 1;
        mountLooks();
      });
    }
    if (container && button) {
      container.hidden = visibleLimit >= catalogueProducts.length;
      button.textContent = "View more";
      button.setAttribute("aria-label", `View ${Math.min(batchSize, catalogueProducts.length - visibleLimit)} more products`);
    }
  }

  function productCardNode(key, lit) {
    const source = document.querySelector(`.decorative-grid-item-motion[data-product-key="${key}"] .decorative-card`);
    if (!source || !productData[key]) return null;
    const card = source.cloneNode(true);
    card.classList.add("decorative-look-product");
    const image = card.querySelector(".decorative-card-image");
    image?.classList.toggle("is-lit", lit);
    image?.classList.toggle("is-unlit", !lit);
    card.querySelectorAll("button").forEach((button) => button.setAttribute("tabindex", "-1"));
    return card;
  }

  function lookCard(index, lit) {
    const look = looks[index % looks.length];
    const slot = document.createElement("div");
    slot.className = `decorative-look-slot ${index % 2 === 0 ? "is-left" : "is-right"}`;
    slot.dataset.decorativeLook = String(index);
    slot.style.setProperty("--light-delay", `${Math.min(index * 100 + 250, 700)}ms`);
    slot.innerHTML = `
      <button class="decorative-look-card ${lit ? "is-lit" : "is-unlit"}" type="button" aria-label="Open look: ${look.title}">
        ${pictureMarkup(look, "is-off")}
        ${pictureMarkup(look, "is-on")}
        <span class="decorative-look-copy">
          <strong class="decorative-look-title">${look.title}</strong>
          <span class="decorative-look-subtitle">${look.subtitle}</span>
        </span>
      </button>`;
    slot.querySelector("button")?.addEventListener("click", (event) => openLook(index, event.currentTarget));
    return slot;
  }

  function visibleProducts(grid) {
    return Array.from(grid.querySelectorAll(":scope > .decorative-grid-item-motion")).filter((item) => {
      return item.style.display !== "none" && getComputedStyle(item).display !== "none";
    });
  }

  function lookInsertionPoints(productCount) {
    const completeGroups = Math.min(Math.floor(productCount / 6), looks.length);
    return Array.from({ length: completeGroups }, (_, index) => (index + 1) * 6);
  }

  function mountLooks() {
    clearTimeout(mountTimer);
    if (mounting) return;
    const grid = document.querySelector(".decorative-grid");
    if (!grid) return;

    mounting = true;
    grid.querySelectorAll(":scope > .decorative-look-slot").forEach((node) => node.remove());
    ensureTwentyProducts(grid);
    const mobile = matchMedia("(max-width: 600px)").matches;
    applyProductPagination(grid, mobile);
    const products = visibleProducts(grid);
    const lit = isLightOn();
    lookInsertionPoints(products.length).forEach((end, lookIndex) => {
      products[end - 1].insertAdjacentElement("afterend", lookCard(lookIndex, lit));
    });
    mounting = false;
  }

  function scheduleMount(delay = 30) {
    clearTimeout(mountTimer);
    mountTimer = window.setTimeout(mountLooks, delay);
  }

  function buildModal() {
    modal = document.createElement("div");
    modal.className = "decorative-look-modal";
    modal.hidden = true;
    modal.setAttribute("role", "dialog");
    modal.setAttribute("aria-modal", "true");
    modal.innerHTML = `<div class="decorative-look-dialog" role="document"></div>`;
    modal.addEventListener("mousedown", (event) => {
      if (event.target === modal) closeLook();
    });
    document.body.appendChild(modal);
  }

  function openLook(index, trigger) {
    if (!modal) buildModal();
    const look = looks[index % looks.length];
    const lit = isLightOn();
    openIndex = index;
    lastTrigger = trigger;
    trigger?.classList.add("is-opening");
    window.setTimeout(() => trigger?.classList.remove("is-opening"), 180);
    const dialog = modal.querySelector(".decorative-look-dialog");
    dialog.innerHTML = `
      <button class="decorative-look-close" type="button" aria-label="Close look">×</button>
      <section class="decorative-look-hero ${lit ? "is-lit" : "is-unlit"}">
        ${modalPictureMarkup(look, "is-off")}
        ${modalPictureMarkup(look, "is-on")}
      </section>
      <section class="decorative-look-products" aria-labelledby="decorative-look-products-title">
        <div class="decorative-look-modal-copy">
          <h2 id="decorative-look-title">${look.modalTitle || look.title}</h2>
          <p id="decorative-look-description">${look.modalSubtitle || look.subtitle}</p>
        </div>
        <h3 id="decorative-look-products-title">Products used</h3>
        <div class="decorative-look-product-row" data-product-count="${look.products.length}"></div>
        <button class="decorative-look-scroll-next" type="button" aria-label="Show more products">›</button>
      </section>`;
    const productRow = dialog.querySelector(".decorative-look-product-row");
    look.products.forEach((key) => {
      const card = productCardNode(key, lit);
      if (card) productRow?.appendChild(card);
    });
    const scrollButton = dialog.querySelector(".decorative-look-scroll-next");
    scrollButton?.toggleAttribute("hidden", look.products.length <= 2);
    scrollButton?.addEventListener("click", () => {
      productRow?.scrollBy({ left: 164, behavior: "smooth" });
    });
    modal.setAttribute("aria-labelledby", "decorative-look-title");
    modal.setAttribute("aria-describedby", "decorative-look-description");
    modal.querySelector(".decorative-look-close")?.addEventListener("click", closeLook);
    modal.hidden = false;
    previousBodyOverflow = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    requestAnimationFrame(() => {
      modal.classList.add("is-open");
      modal.querySelector(".decorative-look-close")?.focus();
    });
  }

  function closeLook() {
    if (!modal || modal.hidden) return;
    modal.classList.remove("is-open");
    const closeDuration = matchMedia("(prefers-reduced-motion: reduce)").matches ? 200 : 320;
    window.setTimeout(() => {
      modal.hidden = true;
      document.body.style.overflow = previousBodyOverflow;
      openIndex = null;
      lastTrigger?.focus();
      lastTrigger = null;
    }, closeDuration);
  }

  function syncLightState() {
    const lit = isLightOn();
    document.querySelectorAll(".decorative-look-card, .decorative-look-hero").forEach((node) => {
      node.classList.toggle("is-lit", lit);
      node.classList.toggle("is-unlit", !lit);
    });
    document.querySelectorAll(".decorative-look-product .decorative-card-image, .decorative-grid .is-dummy-product .decorative-card-image").forEach((node) => {
      node.classList.toggle("is-lit", lit);
      node.classList.toggle("is-unlit", !lit);
    });
  }

  function setupFloatingToolbar() {
    const toolbar = document.querySelector(".decorative-toolbar");
    const sourceTools = toolbar?.querySelector(".decorative-tools");
    if (!toolbar || !sourceTools || toolbar.dataset.floatingReady === "true") return;
    toolbar.dataset.floatingReady = "true";

    const floating = document.createElement("aside");
    floating.className = "decorative-floating-tools";
    floating.setAttribute("aria-label", "Product controls");
    floating.setAttribute("aria-hidden", "true");
    floating.setAttribute("inert", "");
    floating.appendChild(sourceTools.cloneNode(true));
    document.body.appendChild(floating);

    const sourceFilter = sourceTools.querySelector(".decorative-filter-button");
    const sourceSort = sourceTools.querySelector(".decorative-sort select");
    const sourceLight = sourceTools.querySelector(".decorative-light-toggle input");
    const floatingFilter = floating.querySelector(".decorative-filter-button");
    const floatingSort = floating.querySelector(".decorative-sort select");
    const floatingLight = floating.querySelector(".decorative-light-toggle input");

    const syncFloatingControls = () => {
      if (sourceSort && floatingSort) floatingSort.value = sourceSort.value;
      if (sourceLight && floatingLight) floatingLight.checked = sourceLight.checked;
      const lit = sourceLight?.checked !== false;
      const floatingLabel = floating.querySelector(".decorative-light-toggle");
      floatingLabel?.classList.toggle("is-on", lit);
      floatingLabel?.classList.toggle("is-off", !lit);
      const desktopText = floating.querySelector(".decorative-light-desktop");
      const mobileText = floating.querySelector(".decorative-light-mobile");
      if (desktopText) desktopText.textContent = lit ? "Light on" : "Light off";
      if (mobileText) mobileText.textContent = lit ? "On" : "Off";
    };

    floatingFilter?.addEventListener("click", () => sourceFilter?.click());
    floatingSort?.addEventListener("change", () => {
      if (!sourceSort) return;
      sourceSort.value = floatingSort.value;
      sourceSort.dispatchEvent(new Event("change", { bubbles: true }));
      requestAnimationFrame(syncFloatingControls);
    });
    floatingLight?.addEventListener("change", () => {
      if (!sourceLight || sourceLight.checked === floatingLight.checked) return;
      sourceLight.click();
      requestAnimationFrame(syncFloatingControls);
    });

    document.addEventListener("change", (event) => {
      if (sourceTools.contains(event.target)) requestAnimationFrame(syncFloatingControls);
    });

    const header = document.querySelector(".decorative-global-nav .sitehead");
    let visibilityObserver = null;
    const syncFloatingPosition = () => {
      const headerHeight = Math.ceil(header?.getBoundingClientRect().height || 88);
      floating.style.setProperty("--decorative-floating-top", `${headerHeight}px`);
      visibilityObserver?.disconnect();
      visibilityObserver = new IntersectionObserver(([entry]) => {
        const visible = !entry.isIntersecting && entry.boundingClientRect.bottom <= headerHeight;
        floating.classList.toggle("is-visible", visible);
        floating.setAttribute("aria-hidden", String(!visible));
        floating.toggleAttribute("inert", !visible);
      }, {
        threshold: 0,
        rootMargin: `${-headerHeight}px 0px 0px 0px`,
      });
      visibilityObserver.observe(toolbar);
    };

    syncFloatingControls();
    syncFloatingPosition();
    if (header && "ResizeObserver" in window) {
      new ResizeObserver(syncFloatingPosition).observe(header);
    }
  }

  function initialize() {
    if (document.documentElement.dataset.decorativeLooksReady === "true") return;
    document.documentElement.dataset.decorativeLooksReady = "true";
    buildModal();
    setupFloatingToolbar();

    document.addEventListener("change", (event) => {
      if (event.target.matches(".decorative-light-toggle input")) {
        requestAnimationFrame(syncLightState);
      }
      if (event.target.matches(".decorative-mobile-category select, .decorative-sort select")) {
        loadedBatches = 1;
        scheduleMount(220);
      }
    });

    document.addEventListener("click", (event) => {
      if (event.target.closest(".decorative-tabs, .decorative-filter-modal")) {
        loadedBatches = 1;
        scheduleMount(260);
      } else if (event.target.closest(".decorative-load-more")) {
        scheduleMount(260);
      }
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape" && openIndex !== null) closeLook();
      if (event.key !== "Tab" || !modal || modal.hidden) return;
      const focusable = Array.from(modal.querySelectorAll("button, a[href]"));
      if (!focusable.length) return;
      const first = focusable[0];
      const last = focusable[focusable.length - 1];
      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    });

    let resizeTimer = 0;
    window.addEventListener("resize", () => {
      clearTimeout(resizeTimer);
      resizeTimer = window.setTimeout(mountLooks, 180);
    });

    const grid = document.querySelector(".decorative-grid");
    if (grid) {
      observer = new MutationObserver((records) => {
        const meaningfulChange = records.some((record) => {
          if (record.type === "attributes") {
            return record.target.matches?.(".decorative-grid-item-motion");
          }
          return [...record.addedNodes, ...record.removedNodes].some((node) => {
            return node.nodeType === Node.ELEMENT_NODE && !node.matches(".decorative-look-slot");
          });
        });
        if (!mounting && meaningfulChange) scheduleMount(80);
      });
      observer.observe(grid, { childList: true, subtree: true, attributes: true, attributeFilter: ["style"] });
    }

    scheduleMount(500);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initialize, { once: true });
  } else {
    initialize();
  }
})();
