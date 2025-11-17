// Navigation functionality
(function() {
    'use strict';
    
    const Navigation = {
        init: function() {
            this.bindEvents();
            this.handleAccessibility();
        },
        
        bindEvents: function() {
            // Handle dropdown menus
            const menuItems = document.querySelectorAll('.main-navigation li');
            menuItems.forEach(item => {
                const submenu = item.querySelector('ul');
                if (submenu) {
                    this.handleDropdown(item, submenu);
                }
            });
        },
        
        handleDropdown: function(item, submenu) {
            const link = item.querySelector('a');
            
            // Mouse events
            item.addEventListener('mouseenter', () => {
                submenu.classList.add('show');
                if (link) link.setAttribute('aria-expanded', 'true');
            });
            
            item.addEventListener('mouseleave', () => {
                submenu.classList.remove('show');
                if (link) link.setAttribute('aria-expanded', 'false');
            });
            
            // Keyboard events
            if (link) {
                link.addEventListener('focus', () => {
                    submenu.classList.add('show');
                    link.setAttribute('aria-expanded', 'true');
                });
                
                link.addEventListener('blur', (e) => {
                    // Only hide if focus is not moving to a child element
                    setTimeout(() => {
                        if (!item.contains(document.activeElement)) {
                            submenu.classList.remove('show');
                            link.setAttribute('aria-expanded', 'false');
                        }
                    }, 100);
                });
            }
        },
        
        handleAccessibility: function() {
            // Add ARIA attributes
            const menuItems = document.querySelectorAll('.main-navigation li');
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                const submenu = item.querySelector('ul');
                
                if (submenu && link) {
                    link.setAttribute('aria-haspopup', 'true');
                    link.setAttribute('aria-expanded', 'false');
                    submenu.setAttribute('aria-labelledby', link.id || `menu-item-${Date.now()}`);
                }
            });
        }
    };
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Navigation.init());
    } else {
        Navigation.init();
    }
})();