/**
 * Footer JavaScript - Accordion Functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get all accordion toggles
    const accordionToggles = document.querySelectorAll('.footer-accordion-toggle');

    accordionToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const accordion = this.parentElement;
            const panel = accordion.querySelector('.footer-accordion-panel');
            const isOpen = accordion.classList.contains('is-open');

            if (isOpen) {
                // Close accordion
                accordion.classList.remove('is-open');
                this.setAttribute('aria-expanded', 'false');
                panel.setAttribute('aria-hidden', 'true');
            } else {
                // Open accordion
                accordion.classList.add('is-open');
                this.setAttribute('aria-expanded', 'true');
                panel.setAttribute('aria-hidden', 'false');
            }
        });
    });
});
