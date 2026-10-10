(() => {
  const init = () => {
    const dock = document.querySelector('.halo-nav');
    const sheet = document.querySelector('.msheet');
    const backdrop = document.querySelector('.menu-backdrop');
    const title = sheet?.querySelector('.m-title');
    const list = sheet?.querySelector('.mobile-menu-list');
    const closeButton = sheet?.querySelector('.menu-close');
    const tabs = [...document.querySelectorAll('.halo-tab')];
    if (!dock || !sheet || !backdrop || !title || !list || !tabs.length) return;

    const plate = dock.querySelector('.halo-nav-plate');
    const skin = dock.querySelector('.halo-nav-skin');
    const drawPlate = () => {
      const width = Math.max(1, Math.round(dock.getBoundingClientRect().width));
      const height = Math.max(1, Math.round(dock.getBoundingClientRect().height));
      skin?.setAttribute('viewBox', `0 0 ${width} ${height}`);
      plate?.setAttribute('d', `M6 0H${width - 6}Q${width} 0 ${width} 6V${height - 6}Q${width} ${height} ${width - 6} ${height}H6Q0 ${height} 0 ${height - 6}V6Q0 0 6 0Z`);
      dock.classList.add('is-ready', 'is-idle');
    };

    const menus = {
      products: ['Architectural', 'Decorative', 'Outdoor', 'Smart Lighting'],
      work: ['Projects', 'Clients'],
      inspiration: ['Stories & News', 'Catalogues'],
      more: ['About Us', 'Contact Us', 'Careers', 'Fairs & Events'],
    };
    const hrefs = {
      Architectural: '/home#worlds', Decorative: '/decorative3', Outdoor: '/home#worlds', 'Smart Lighting': '/home#worlds',
      Projects: '/home#projects', Clients: '/home#clients', 'Stories & News': '/home#news', Catalogues: '/home#contact',
      'About Us': '/home#contact', 'Contact Us': '/home#contact', Careers: '/home#contact', 'Fairs & Events': '/home#contact',
    };

    const close = () => {
      sheet.classList.remove('open', 'product-sheet');
      backdrop.classList.remove('open');
      document.body.style.overflowY = 'auto';
      tabs.forEach((tab, index) => {
        tab.setAttribute('aria-selected', 'false');
        tab.tabIndex = index === 0 ? 0 : -1;
      });
    };
    const open = (key, label, tab) => {
      title.textContent = label;
      list.innerHTML = `<div class="mobile-menu-group">${menus[key].map(item => `<a href="${hrefs[item]}">${item}</a>`).join('')}</div>`;
      sheet.classList.toggle('product-sheet', key === 'products');
      sheet.classList.add('open');
      backdrop.classList.add('open');
      document.body.style.overflowY = 'hidden';
      tabs.forEach(item => {
        const active = item === tab;
        item.setAttribute('aria-selected', String(active));
        item.tabIndex = active ? 0 : -1;
      });
    };

    tabs.forEach(tab => tab.addEventListener('click', () => {
      const key = tab.id.replace('halo-tab-', '');
      if (key === 'home') {
        window.location.assign('/home');
        return;
      }
      open(key, tab.textContent.trim(), tab);
    }));
    closeButton?.addEventListener('click', close);
    backdrop.addEventListener('click', close);
    document.addEventListener('keydown', event => { if (event.key === 'Escape') close(); });
    window.addEventListener('resize', drawPlate, { passive: true });
    drawPlate();

    document.addEventListener('click', event => {
      const arrow = event.target.closest('.s-products .decorative-card-arrow');
      if (arrow) {
        const dots = [...arrow.closest('.decorative-card-image').querySelectorAll('.decorative-gallery-dots button')];
        const active = Math.max(0, dots.findIndex(dot => dot.classList.contains('active')));
        const direction = arrow.classList.contains('decorative-card-next') ? 1 : dots.length - 1;
        dots[(active + direction) % dots.length]?.click();
      }
      const dot = event.target.closest('.s-products .decorative-gallery-dots button');
      if (dot) dot.parentElement.querySelectorAll('button').forEach(item => item.classList.toggle('active', item === dot));
      const swatch = event.target.closest('.s-products .decorative-swatches button');
      if (swatch) swatch.parentElement.querySelectorAll('button').forEach(item => item.classList.toggle('active', item === swatch));
    });
  };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true });
  else init();
})();
