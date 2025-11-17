/**
 * Navigation Module
 * 
 * Handles the responsive navigation menu, dropdown toggles,
 * and accessibility features for the main navigation.
 */

class Navigation {
    constructor() {
        this.menuToggle = document.querySelector('.menu-toggle');
        this.siteNavigation = document.querySelector('.main-navigation');
        this.dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        this.subMenus = document.querySelectorAll('.sub-menu');
        this.isMenuOpen = false;
    }

    init() {
        // Skip if required elements don't exist
        if (!this.menuToggle || !this.siteNavigation) {
            return;
        }

        this.setupMenuToggle();
        this.setupDropdowns();
        this.setupKeyboardNavigation();
        this.setupResizeHandler();
    }

    setupMenuToggle() {
        // Set initial ARIA states
        this.menuToggle.setAttribute('aria-expanded', 'false');
        
        // Toggle menu on click
        this.menuToggle.addEventListener('click', () => {
            this.isMenuOpen = !this.isMenuOpen;
            this.menuToggle.setAttribute('aria-expanded', this.isMenuOpen.toString());
            this.siteNavigation.classList.toggle('toggled');
            
            if (this.isMenuOpen) {
                document.body.classList.add('menu-open');
            } else {
                document.body.classList.remove('menu-open');
            }
        });
    }

    setupDropdowns() {
        // Skip if no dropdown toggles
        if (!this.dropdownToggles.length) {
            return;
        }

        this.dropdownToggles.forEach(toggle => {
            // Set initial ARIA states
            toggle.setAttribute('aria-expanded', 'false');
            
            // Toggle dropdown on click
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', (!isExpanded).toString());
                toggle.parentNode.classList.toggle('toggled');
            });
        });
    }

    setupKeyboardNavigation() {
        // Add keyboard navigation for accessibility
        document.addEventListener('keydown', (e) => {
            // Close menu on escape key
            if (e.key === 'Escape' && this.isMenuOpen) {
                this.isMenuOpen = false;
                this.menuToggle.setAttribute('aria-expanded', 'false');
                this.siteNavigation.classList.remove('toggled');
                document.body.classList.remove('menu-open');
                this.menuToggle.focus();
            }
        });

        // Focus trap inside menu when open
        if (this.siteNavigation) {
            const focusableElements = this.siteNavigation.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            
            if (focusableElements.length > 0) {
                const firstFocusable = focusableElements[0];
                const lastFocusable = focusableElements[focusableElements.length - 1];

                this.siteNavigation.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        if (e.shiftKey && document.activeElement === firstFocusable) {
                            e.preventDefault();
                            lastFocusable.focus();
                        } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                            e.preventDefault();
                            firstFocusable.focus();
                        }
                    }
                });
            }
        }
    }

    setupResizeHandler() {
        // Reset menu state on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992 && this.isMenuOpen) {
                this.isMenuOpen = false;
                this.menuToggle.setAttribute('aria-expanded', 'false');
                this.siteNavigation.classList.remove('toggled');
                document.body.classList.remove('menu-open');
            }
        });
    }
}

export default Navigation;