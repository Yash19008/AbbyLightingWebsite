(() => {
  const rewriteLinks = () => {
    document.querySelectorAll("a[href]").forEach((link) => {
      const href = link.getAttribute("href");
      if (!href) return;

      if (href === "/decorative-products" || href.startsWith("/decorative-products?")) {
        link.setAttribute("href", href.replace("/decorative-products", "/decoratives"));
      } else if (href === "/") {
        link.setAttribute("href", "/home");
      } else if (href.startsWith("/#")) {
        link.setAttribute("href", `/home${href.slice(1)}`);
      }
    });
  };

  rewriteLinks();
  new MutationObserver(rewriteLinks).observe(document.documentElement, {
    childList: true,
    subtree: true,
  });
})();
