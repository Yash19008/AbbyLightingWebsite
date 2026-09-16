(() => {
  const groups = [
    { key: "category", label: "Category", options: ["Pendant", "Wall", "Floor", "Table"] },
    { key: "finish", label: "Finish", options: ["White", "Black", "Terra", "Brass", "Colour"] },
    { key: "collection", label: "Collections", options: ["Symphony", "Quarry", "Neoma"] },
  ];

  const catalogue = [
    ...Array.from({ length: 8 }, () => ({ category: "Pendant", collection: "Quarry", finishes: ["White", "Black", "Terra"] })),
    ...Array.from({ length: 3 }, () => ({ category: "Pendant", collection: "Symphony", finishes: ["Colour"] })),
    { category: "Wall", collection: "Neoma", finishes: ["Brass"] },
  ];

  let applied = createSelection();
  let draft = createSelection();
  let enhancing = false;

  function createSelection() {
    return { category: new Set(), finish: new Set(), collection: new Set() };
  }

  function cloneSelection(source) {
    return {
      category: new Set(source.category),
      finish: new Set(source.finish),
      collection: new Set(source.collection),
    };
  }

  function matches(product, selection) {
    return (
      (!selection.category.size || selection.category.has(product.category)) &&
      (!selection.collection.size || selection.collection.has(product.collection)) &&
      (!selection.finish.size || product.finishes.some((finish) => selection.finish.has(finish)))
    );
  }

  function resultCount(selection) {
    return catalogue.filter((product) => matches(product, selection)).length;
  }

  function closeModal(modal) {
    modal.dispatchEvent(new MouseEvent("mousedown", { bubbles: true, cancelable: true }));
  }

  function syncGroup(panel, group) {
    const selected = draft[group.key];
    const all = panel.querySelector(`[data-filter-all="${group.key}"]`);
    if (all) all.checked = selected.size === 0;
    panel.querySelectorAll(`[data-filter-option="${group.key}"]`).forEach((input) => {
      input.checked = selected.has(input.value);
    });
    const show = panel.querySelector(".decorative-filter-show");
    if (show) show.textContent = `Show ${resultCount(draft)} results`;
  }

  function optionMarkup(group) {
    const all = `<label class="decorative-filter-option"><input type="checkbox" data-filter-all="${group.key}"><span>All</span></label>`;
    const options = group.options.map((option) => (
      `<label class="decorative-filter-option"><input type="checkbox" value="${option}" data-filter-option="${group.key}"><span>${option}</span></label>`
    )).join("");
    return all + options;
  }

  function accordionMarkup(group, index) {
    return `<section class="decorative-filter-accordion">
      <button class="decorative-filter-accordion-toggle" type="button" aria-expanded="false" aria-controls="decorative-filter-section-${index}">
        <span>${group.label}</span><i class="decorative-filter-chevron" aria-hidden="true"></i>
      </button>
      <div class="decorative-filter-options" id="decorative-filter-section-${index}" hidden>${optionMarkup(group)}</div>
    </section>`;
  }

  function enhanceModal(modal) {
    if (modal.dataset.checkboxFilterReady || enhancing) return;
    enhancing = true;

    requestAnimationFrame(() => {
      const currentModal = document.querySelector(".decorative-filter-modal");
      if (!currentModal || currentModal.dataset.checkboxFilterReady) {
        enhancing = false;
        return;
      }

      const panel = currentModal.querySelector(".decorative-filter-panel");
      if (!panel) {
        enhancing = false;
        return;
      }

      draft = cloneSelection(applied);
      currentModal.dataset.checkboxFilterReady = "true";
      panel.classList.add("decorative-filter-panel--checkboxes");
      panel.innerHTML = `
        <div class="decorative-filter-head">
          <h3>Filter by</h3>
          <button type="button" class="decorative-filter-close" aria-label="Close filters">×</button>
        </div>
        <div class="decorative-filter-accordions">${groups.map(accordionMarkup).join("")}</div>
        <div class="decorative-filter-foot">
          <button type="button" class="decorative-filter-clear">Clear all</button>
          <button type="button" class="decorative-filter-show">Show results</button>
        </div>`;

      groups.forEach((group) => syncGroup(panel, group));

      panel.querySelectorAll(".decorative-filter-accordion-toggle").forEach((button) => {
        button.addEventListener("click", () => {
          const expanded = button.getAttribute("aria-expanded") === "true";
          button.setAttribute("aria-expanded", String(!expanded));
          const options = panel.querySelector(`#${button.getAttribute("aria-controls")}`);
          if (options) options.hidden = expanded;
        });
      });

      groups.forEach((group) => {
        panel.querySelector(`[data-filter-all="${group.key}"]`)?.addEventListener("change", () => {
          draft[group.key].clear();
          syncGroup(panel, group);
        });
        panel.querySelectorAll(`[data-filter-option="${group.key}"]`).forEach((input) => {
          input.addEventListener("change", () => {
            input.checked ? draft[group.key].add(input.value) : draft[group.key].delete(input.value);
            syncGroup(panel, group);
          });
        });
      });

      panel.querySelector(".decorative-filter-close")?.addEventListener("click", () => closeModal(currentModal));
      panel.querySelector(".decorative-filter-clear")?.addEventListener("click", () => {
        draft = createSelection();
        groups.forEach((group) => syncGroup(panel, group));
      });
      panel.querySelector(".decorative-filter-show")?.addEventListener("click", () => {
        applied = cloneSelection(draft);
        closeModal(currentModal);
        resetNativeCategory();
        requestAnimationFrame(() => {
          const applyFilter = window.decorative3SetFilter || window.decorative2SetFilter;
          applyFilter?.((itemOrProduct) => {
            // Decorative 3 owns an in-memory React-style product list; the
            // established routes pass a rendered grid item. Support both
            // shapes without allowing this shared dialog to alter visibility.
            const product = itemOrProduct?.querySelector
              ? productFromCard(itemOrProduct.querySelector(".decorative-card"))
              : {
                  category: itemOrProduct?.type,
                  collection: itemOrProduct?.collection,
                  finishes: itemOrProduct?.finishes || [],
                };
            return Boolean(product && matches(product, applied));
          }, { revealAll: true });
        });
      });

      enhancing = false;
    });
  }

  function productFromCard(card) {
    const categoryText = card.querySelector(".decorative-card-copy p")?.textContent?.trim() || "";
    const collectionText = card.querySelector(".decorative-collection")?.textContent?.trim() || "";
    return {
      category: categoryText.replace(/\s+(Light|Lamp)$/i, ""),
      collection: collectionText.replace(/\s+Collection$/i, ""),
      finishes: Array.from(card.querySelectorAll(".decorative-swatches button[title]"), (button) => button.title),
    };
  }

  function resetNativeCategory() {
    const allTab = Array.from(document.querySelectorAll(".decorative-tabs button"))
      .find((button) => button.textContent?.trim() === "All");
    allTab?.click();

    const mobileCategory = document.querySelector(".decorative-mobile-category select");
    if (mobileCategory && mobileCategory.value !== "All") {
      mobileCategory.value = "All";
      mobileCategory.dispatchEvent(new Event("change", { bubbles: true }));
    }
  }

  const observer = new MutationObserver(() => {
    const modal = document.querySelector(".decorative-filter-modal");
    if (modal) enhanceModal(modal);
  });

  observer.observe(document.documentElement, { childList: true, subtree: true });
})();
