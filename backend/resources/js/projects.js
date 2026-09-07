import imagesLoaded from 'imagesloaded';
window.imagesLoaded = imagesLoaded;

import Isotope from 'isotope-layout';
import Masonry from 'masonry-layout';
window.Isotope = Isotope;

document.addEventListener('DOMContentLoaded', () => {
    let msnry = null;
    const tiles = document.getElementById('project-tiles');

    if (tiles) {
        msnry = new Isotope(tiles, {
            itemSelector: '.masonry-item',
            percentPosition: true,
            masonry: {
                itemSelector: '.masonry-item',
                columnWidth: '.masonry-sizer',
                percentPosition: true,
                horizontalSize: true
            }
        });

        imagesLoaded(tiles).on('progress', function () {
            msnry.layout();
        });

        // Apply default filter (if provided) on first load
        const defaultDataFilter = document.getElementById("links-section")?.getAttribute('default-data-filter');
        if (defaultDataFilter && defaultDataFilter.length > 0) {
            msnry.arrange({ filter: '.--' + defaultDataFilter });
            // highlight that category (since URL may not include the slug)
            document.querySelectorAll('.section-link-filter').forEach(l => l.classList.remove('active'));
            const match = [...document.querySelectorAll('.section-link-filter')]
                .find(l => (l.dataset.slug || '').toLowerCase() === defaultDataFilter.toLowerCase());
            if (match) match.classList.add('active');
        }
    }

    // ---------- helpers ----------
    function normalizeSlug(s) {
        if (!s && s !== '') return '';
        try { s = String(s || ''); } catch (e) { return ''; }
        try { s = decodeURIComponent(s); } catch (e) {}
        return s.toLowerCase().trim().replace(/\s+/g, '-').replace(/[^\w\-]/g, '');
    }

    function getActiveSlugFromPath() {
        // match /projects/category/{slug}
        const m = window.location.pathname.match(/\/projects\/category\/([^\/]+)/i);
        if (!m) return '';
        return normalizeSlug(m[1]);
    }

    function markActiveFromUrl() {
        const activeSlug = getActiveSlugFromPath();
        document.querySelectorAll('.section-link-filter').forEach(l => l.classList.remove('active'));

        if (activeSlug) {
            const match = [...document.querySelectorAll('.section-link-filter')]
                .find(l => normalizeSlug(l.dataset.slug || '') === activeSlug);
            if (match) match.classList.add('active');
        } else {
            document.querySelector('#all-projects-link')?.classList.add('active');
        }
    }

    // On initial load, if no defaultDataFilter applied above and no slug -> All active
    if (!getActiveSlugFromPath()) {
        const hasExplicitDefault = document.getElementById("links-section")?.getAttribute('default-data-filter');
        if (!hasExplicitDefault) {
            document.querySelector('#all-projects-link')?.classList.add('active');
        }
    }

    // ---------- click handlers ----------
    const filterLinks = document.querySelectorAll(".section-link-filter");
    filterLinks.forEach(link => {
        link.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            const slugRaw = link.dataset.slug || '';
            const slug = normalizeSlug(slugRaw);
            const filter = link.dataset.filter || '*';
            const currentActive = getActiveSlugFromPath(); // normalized slug from URL

            // Clicking ALL -> reset to All
            if (!slug) {
                window.history.pushState({}, document.title, window.location.origin + '/projects');
                if (msnry) msnry.arrange({ filter: '*' });

                // highlight All
                document.querySelectorAll('.section-link-filter').forEach(l => l.classList.remove('active'));
                document.querySelector('#all-projects-link')?.classList.add('active');
                return;
            }

            // Clicking the already-active category -> toggle back to All
            if (slug && slug === currentActive) {
                window.history.pushState({}, document.title, window.location.origin + '/projects');
                if (msnry) msnry.arrange({ filter: '*' });

                // highlight All
                document.querySelectorAll('.section-link-filter').forEach(l => l.classList.remove('active'));
                document.querySelector('#all-projects-link')?.classList.add('active');
                return;
            }

            // Otherwise navigate to category URL and filter
            const targetSlugForUrl = encodeURIComponent(slugRaw.replace(/\s+/g, '-').toLowerCase());
            const targetUrl = window.location.origin + '/projects/category/' + targetSlugForUrl;
            window.history.pushState({}, document.title, targetUrl);

            if (msnry) msnry.arrange({ filter: filter });

            // update visuals
            markActiveFromUrl();
        });
    });

    // ---------- back/forward navigation ----------
    window.addEventListener('popstate', function () {
        const activeSlug = getActiveSlugFromPath();
        if (msnry) {
            if (activeSlug) {
                const match = [...document.querySelectorAll('.section-link-filter')]
                    .find(l => normalizeSlug(l.dataset.slug || '') === activeSlug);
                if (match && match.dataset.filter) {
                    msnry.arrange({ filter: match.dataset.filter });
                } else {
                    msnry.arrange({ filter: '*' });
                }
            } else {
                msnry.arrange({ filter: '*' });
            }
        }
        markActiveFromUrl();
    });
});
