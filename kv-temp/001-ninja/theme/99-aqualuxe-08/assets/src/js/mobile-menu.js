// Mobile menu functionality
(function() {
    'use strict';
    
    const MobileMenu = {
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            const mobileMenuClose = document.querySelector('.mobile-menu-close');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', () => this.openMenu());
            }
            
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', () => this.closeMenu());
            }
            
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', (e) => {
                    if (e.target === mobileMenuOverlay) {
                        this.closeMenu();
                    }
                });
            }
            
            // Close on escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.isMenuOpen()) {
                    this.closeMenu();
                }
            });
        },
        
        openMenu: function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            
            if (menuToggle) menuToggle.classList.add('active');
            if (mobileMenuOverlay) mobileMenuOverlay.classList.add('active');
            
            document.body.style.overflow = 'hidden';
            
            // Focus the first menu item
            const firstMenuItem = document.querySelector('.mobile-menu a');
            if (firstMenuItem) {
                setTimeout(() => firstMenuItem.focus(), 300);
            }
        },
        
        closeMenu: function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            
            if (menuToggle) menuToggle.classList.remove('active');
            if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('active');
            
            document.body.style.overflow = '';
            
            // Return focus to menu toggle
            if (menuToggle) menuToggle.focus();
        },
        
        isMenuOpen: function() {
            const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');
            return mobileMenuOverlay && mobileMenuOverlay.classList.contains('active');
        }
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => MobileMenu.init());
    } else {
        MobileMenu.init();
    }
})();