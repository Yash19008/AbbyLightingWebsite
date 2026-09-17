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

  let loadedBatches = 1;
  let mounting = false;
  let mountTimer = 0;
  let modalFilterMatches = () => true;

  const lightIsOn = () => document.querySelector(".decorative-toolbar .decorative-light-toggle input")?.checked !== false;

  function applyLightState(lit) {
    document.querySelectorAll(".decorative-light-toggle").forEach((label) => {
      const input = label.querySelector("input");
      if (input) input.checked = lit;
      label.classList.toggle("is-on", lit);
      label.classList.toggle("is-off", !lit);
      const desktopText = label.querySelector(".decorative-light-desktop");
      const mobileText = label.querySelector(".decorative-light-mobile");
      if (desktopText) desktopText.textContent = lit ? "Light on" : "Light off";
      if (mobileText) mobileText.textContent = lit ? "On" : "Off";
    });
    document.querySelectorAll(".decorative-grid .decorative-card-image").forEach((image) => {
      image.classList.toggle("is-lit", lit);
      image.classList.toggle("is-unlit", !lit);
    });
  }

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
    const cardImage = item.querySelector(".decorative-card-image");
    const lit = lightIsOn();
    cardImage?.classList.toggle("is-lit", lit);
    cardImage?.classList.toggle("is-unlit", !lit);
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
      clone.style.removeProperty("display");
      clone.hidden = false;
      clone.removeAttribute("aria-hidden");
      prepareProductCard(clone, product, index);
      grid.appendChild(clone);
      items.push(clone);
    }
  }

  function activeCategory() {
    return document.querySelector(".decorative-toolbar .decorative-tabs button.active")?.textContent?.trim()
      || document.querySelector(".decorative-mobile-category select")?.value
      || "All";
  }

  function matchesActiveFilters(item) {
    const category = activeCategory();
    const productCategory = item.querySelector(".decorative-card-copy p")?.textContent?.trim() || "";
    const categoryMatches = category === "All" || productCategory.toLowerCase().startsWith(category.toLowerCase());
    return categoryMatches && modalFilterMatches(item);
  }

  function applyProductVisibility(grid) {
    const items = Array.from(grid.querySelectorAll(":scope > .decorative-grid-item-motion"));
    const batchSize = matchMedia("(max-width: 600px)").matches ? 8 : 12;
    const visibleLimit = Math.min(catalogueProducts.length, batchSize * loadedBatches);
    let visibleCount = 0;
    items.forEach((item, index) => {
      const shouldShow = index < visibleLimit && matchesActiveFilters(item);
      // This route owns all product visibility. Clear any legacy inline state
      // from the shared listing before applying the one class-based state.
      item.style.removeProperty("display");
      item.style.removeProperty("grid-column");
      item.style.removeProperty("grid-row");
      item.classList.toggle("decorative2-is-hidden", !shouldShow);
      item.hidden = !shouldShow;
      item.setAttribute("aria-hidden", String(!shouldShow));
      if (shouldShow) visibleCount += 1;
    });

    const container = document.querySelector(".decorative-load-more");
    let button = container?.querySelector("button");
    if (container && button && button.dataset.productPaginationReady !== "true") {
      const replacement = button.cloneNode(true);
      button.replaceWith(replacement);
      button = replacement;
      button.dataset.productPaginationReady = "true";
      button.addEventListener("click", () => {
        loadedBatches += 1;
        mountProducts();
      });
    }
    if (container && button) {
      container.hidden = visibleLimit >= catalogueProducts.length;
      button.textContent = "View more";
      button.setAttribute("aria-label", `View ${Math.min(batchSize, catalogueProducts.length - visibleLimit)} more products`);
    }

    let empty = document.querySelector(".decorative-filter-empty");
    if (!visibleCount) {
      if (!empty) {
        empty = document.createElement("p");
        empty.className = "decorative-empty decorative-filter-empty";
        empty.textContent = "No pieces match these filters yet.";
        grid.insertAdjacentElement("afterend", empty);
      }
    } else {
      empty?.remove();
    }
  }

  function mountProducts() {
    clearTimeout(mountTimer);
    if (mounting) return;
    const grid = document.querySelector(".decorative-grid");
    if (!grid) return;
    mounting = true;
    ensureTwentyProducts(grid);
    applyProductVisibility(grid);
    mounting = false;
  }

  function scheduleMount(delay = 40) {
    clearTimeout(mountTimer);
    mountTimer = window.setTimeout(mountProducts, delay);
  }

  function setupFloatingToolbar() {
    const toolbar = document.querySelector(".decorative-toolbar");
    if (!toolbar || toolbar.dataset.decorativeTwoFloatingReady === "true") return;
    toolbar.dataset.decorativeTwoFloatingReady = "true";

    const floating = document.createElement("aside");
    floating.className = "decorative2-floating-toolbar";
    floating.setAttribute("aria-label", "Product controls");
    floating.setAttribute("aria-hidden", "true");
    floating.setAttribute("inert", "");
    const shell = document.createElement("div");
    shell.className = "decorative-shell";
    shell.appendChild(toolbar.cloneNode(true));
    floating.appendChild(shell);
    document.body.appendChild(floating);

    const clonedToolbar = floating.querySelector(".decorative-toolbar");
    const sourceFilter = toolbar.querySelector(".decorative-filter-button");
    const sourceSort = toolbar.querySelector(".decorative-sort select");
    const sourceLight = toolbar.querySelector(".decorative-light-toggle input");
    const clonedFilter = clonedToolbar.querySelector(".decorative-filter-button");
    const clonedSort = clonedToolbar.querySelector(".decorative-sort select");
    const clonedLight = clonedToolbar.querySelector(".decorative-light-toggle input");

    const syncTabs = () => {
      const sourceTabs = Array.from(toolbar.querySelectorAll(".decorative-tabs button"));
      const clonedTabs = Array.from(clonedToolbar.querySelectorAll(".decorative-tabs button"));
      clonedTabs.forEach((tab, index) => {
        const active = sourceTabs[index]?.classList.contains("active") || false;
        tab.classList.toggle("active", active);
        tab.setAttribute("aria-selected", String(active));
      });
      const activeTab = clonedTabs.find((tab) => tab.classList.contains("active"));
      const indicator = clonedToolbar.querySelector(".decorative-tab-indicator");
      if (activeTab && indicator) {
        indicator.style.width = `${activeTab.offsetWidth}px`;
        indicator.style.transform = `translateX(${activeTab.offsetLeft}px)`;
      }
    };

    const syncControls = () => {
      if (sourceSort && clonedSort) clonedSort.value = sourceSort.value;
      if (sourceLight && clonedLight) clonedLight.checked = sourceLight.checked;
      const lit = sourceLight?.checked !== false;
      const label = clonedToolbar.querySelector(".decorative-light-toggle");
      label?.classList.toggle("is-on", lit);
      label?.classList.toggle("is-off", !lit);
      const desktopText = clonedToolbar.querySelector(".decorative-light-desktop");
      const mobileText = clonedToolbar.querySelector(".decorative-light-mobile");
      if (desktopText) desktopText.textContent = lit ? "Light on" : "Light off";
      if (mobileText) mobileText.textContent = lit ? "On" : "Off";
      syncTabs();
    };

    clonedFilter?.addEventListener("click", () => sourceFilter?.click());
    clonedSort?.addEventListener("change", () => {
      if (!sourceSort) return;
      sourceSort.value = clonedSort.value;
      sourceSort.dispatchEvent(new Event("change", { bubbles: true }));
      requestAnimationFrame(syncControls);
    });
    clonedLight?.addEventListener("change", () => {
      if (!sourceLight) return;
      sourceLight.checked = clonedLight.checked;
      sourceLight.dispatchEvent(new Event("change", { bubbles: true }));
      requestAnimationFrame(() => {
        applyLightState(clonedLight.checked);
        syncControls();
      });
    });
    clonedToolbar.querySelectorAll(".decorative-tabs button").forEach((tab, index) => {
      tab.addEventListener("click", () => {
        toolbar.querySelectorAll(".decorative-tabs button")[index]?.click();
        window.setTimeout(syncControls, 280);
      });
    });

    document.addEventListener("change", (event) => {
      if (toolbar.contains(event.target)) requestAnimationFrame(syncControls);
    });
    toolbar.addEventListener("click", () => window.setTimeout(syncControls, 280));

    const header = document.querySelector(".decorative-global-nav .sitehead");
    let toolbarPassedHeader = false;
    let mobileDockSeen = false;
    let observer = null;

    const findDock = () => document.querySelector(".dock.dock-visible, .mobile-bottom-dock.dock-visible, [data-mobile-dock].dock-visible")
      || document.querySelector(".dock, .mobile-bottom-dock, [data-mobile-dock]");

    const dockIsVisible = (dock) => {
      if (!dock) return !mobileDockSeen;
      mobileDockSeen = true;
      if (dock.classList.contains("dock-hidden")) return false;
      if (dock.classList.contains("dock-visible")) return true;
      const style = getComputedStyle(dock);
      const rect = dock.getBoundingClientRect();
      return style.display !== "none"
        && style.visibility !== "hidden"
        && Number.parseFloat(style.opacity || "1") > .05
        && rect.width > 0
        && rect.height > 0
        && rect.bottom > 0
        && rect.top < innerHeight;
    };

    const updateVisibility = () => {
      const mobile = matchMedia("(max-width: 600px)").matches;
      const dock = mobile ? findDock() : null;
      const dockVisible = !mobile || dockIsVisible(dock);
      if (dock) {
        const dockRect = dock.getBoundingClientRect();
        floating.style.setProperty("--decorative2-dock-offset", `${Math.max(0, innerHeight - dockRect.top)}px`);
      }
      const visible = toolbarPassedHeader && dockVisible;
      floating.classList.toggle("is-visible", visible);
      floating.setAttribute("aria-hidden", String(!visible));
      floating.toggleAttribute("inert", !visible);
    };

    const syncPosition = () => {
      const headerHeight = Math.ceil(header?.getBoundingClientRect().height || 111);
      floating.style.setProperty("--decorative2-floating-top", `${headerHeight}px`);
      observer?.disconnect();
      observer = new IntersectionObserver(([entry]) => {
        toolbarPassedHeader = !entry.isIntersecting && entry.boundingClientRect.bottom <= headerHeight;
        updateVisibility();
      }, { threshold: 0, rootMargin: `${-headerHeight}px 0px 0px 0px` });
      observer.observe(toolbar);
      updateVisibility();
    };

    let frame = 0;
    const scheduleVisibility = () => {
      cancelAnimationFrame(frame);
      frame = requestAnimationFrame(updateVisibility);
    };
    new MutationObserver(scheduleVisibility).observe(document.body, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ["class", "style", "hidden"],
    });
    window.addEventListener("scroll", scheduleVisibility, { passive: true });
    window.addEventListener("resize", () => {
      syncPosition();
      scheduleMount(180);
    });
    if (header && "ResizeObserver" in window) new ResizeObserver(syncPosition).observe(header);
    syncControls();
    syncPosition();
  }

  function initialize() {
    if (document.documentElement.dataset.decorativeTwoReady === "true") return;
    document.documentElement.dataset.decorativeTwoReady = "true";
    // Public bridge used by the shared dialog script. The dialog supplies only
    // its match rule; this route remains the single owner of DOM visibility.
    window.decorative2SetFilter = (matcher, { revealAll = false } = {}) => {
      modalFilterMatches = typeof matcher === "function" ? matcher : () => true;
      if (revealAll) {
        const batchSize = matchMedia("(max-width: 600px)").matches ? 8 : 12;
        loadedBatches = Math.ceil(catalogueProducts.length / batchSize);
      }
      mountProducts();
    };
    setupFloatingToolbar();

    document.addEventListener("change", (event) => {
      if (event.target.matches(".decorative-light-toggle input")) {
        const lit = event.target.checked;
        requestAnimationFrame(() => applyLightState(lit));
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
      }
    });
    applyLightState(lightIsOn());
    scheduleMount(500);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initialize, { once: true });
  } else {
    initialize();
  }
})();
