// Accessibility enhancements
(function() {
    'use strict';
    
    const Accessibility = {
        init: function() {
            this.setupFocusManagement();
            this.setupKeyboardNavigation();
            this.setupAriaAttributes();
        },
        
        setupFocusManagement: function() {
            // Skip link functionality
            const skipLink = document.querySelector('.skip-link');
            if (skipLink) {
                skipLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector(skipLink.getAttribute('href'));
                    if (target) {
                        target.focus();
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                });
            }
            
            // Focus outline management
            document.addEventListener('mousedown', () => {
                document.body.classList.add('using-mouse');
            });
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    document.body.classList.remove('using-mouse');
                }
            });
        },
        
        setupKeyboardNavigation: function() {
            // Escape key to close modals/menus
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    // Close mobile menu
                    const mobileMenu = document.querySelector('.mobile-menu-overlay.active');
                    if (mobileMenu) {
                        const closeBtn = mobileMenu.querySelector('.mobile-menu-close');
                        if (closeBtn) closeBtn.click();
                    }
                    
                    // Close any open dropdowns
                    const openDropdowns = document.querySelectorAll('.main-navigation .show');
                    openDropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show');
                        const trigger = dropdown.parentElement.querySelector('a');
                        if (trigger) trigger.setAttribute('aria-expanded', 'false');
                    });
                }
            });
        },
        
        setupAriaAttributes: function() {
            // Add aria-label to buttons without text
            const buttons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
            buttons.forEach(button => {
                const text = button.textContent.trim();
                if (!text) {
                    button.setAttribute('aria-label', 'Button');
                }
            });
            
            // Add aria-expanded to collapsible elements
            const collapsibles = document.querySelectorAll('[data-toggle]');
            collapsibles.forEach(element => {
                if (!element.hasAttribute('aria-expanded')) {
                    element.setAttribute('aria-expanded', 'false');
                }
            });
        }
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Accessibility.init());
    } else {
        Accessibility.init();
    }
})();