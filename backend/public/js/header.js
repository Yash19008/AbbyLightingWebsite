/**
 * Header JavaScript - Search Toggle & Scroll Effects
 */

document.addEventListener('DOMContentLoaded', function() {
    // ===== SEARCH FUNCTIONALITY =====
    const searchContainer = document.getElementById('searchContainer');
    const searchToggle = document.getElementById('searchToggle');
    const searchInput = document.getElementById('searchInput');
    const searchIcon = document.getElementById('searchIcon');
    const closeIcon = document.getElementById('closeIcon');
    let isSearchOpen = false;

    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            isSearchOpen = !isSearchOpen;
            
            if (isSearchOpen) {
                // Open search
                searchContainer.classList.add('is-open');
                searchToggle.setAttribute('aria-expanded', 'true');
                searchToggle.setAttribute('aria-label', 'Close search');
                searchIcon.style.display = 'none';
                closeIcon.style.display = 'block';
                
                // Focus input after a short delay for smooth transition
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            } else {
                // Close search
                searchContainer.classList.remove('is-open');
                searchToggle.setAttribute('aria-expanded', 'false');
                searchToggle.setAttribute('aria-label', 'Open search');
                searchIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        });
    }

    // Close search when clicking outside
    document.addEventListener('click', function(event) {
        if (isSearchOpen && 
            searchContainer && 
            !searchContainer.contains(event.target)) {
            isSearchOpen = false;
            searchContainer.classList.remove('is-open');
            searchToggle.setAttribute('aria-expanded', 'false');
            searchToggle.setAttribute('aria-label', 'Open search');
            searchIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    });

    // Close search on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && isSearchOpen) {
            isSearchOpen = false;
            searchContainer.classList.remove('is-open');
            searchToggle.setAttribute('aria-expanded', 'false');
            searchToggle.setAttribute('aria-label', 'Open search');
            searchIcon.style.display = 'block';
            closeIcon.style.display = 'none';
        }
    });

    // ===== HEADER SCROLL EFFECT =====
    const header = document.getElementById('mainHeader');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        // Add 'scrolled' class after 500px
        if (currentScroll > 500) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // ===== MOBILE MENU =====
    const menuBackdrop = document.getElementById('menuBackdrop');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuClose = document.getElementById('menuClose');

    if (menuClose) {
        menuClose.addEventListener('click', function() {
            mobileMenu.classList.remove('is-open');
            menuBackdrop.classList.remove('is-open');
        });
    }

    if (menuBackdrop) {
        menuBackdrop.addEventListener('click', function() {
            mobileMenu.classList.remove('is-open');
            menuBackdrop.classList.remove('is-open');
        });
    }
});
