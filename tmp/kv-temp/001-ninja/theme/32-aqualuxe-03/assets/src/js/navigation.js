/**
 * AquaLuxe Theme - Navigation
 *
 * Handles the navigation functionality.
 */

(function() {
    'use strict';

    // Navigation toggle
    const navToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-navigation');

    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function() {
            mainNav.classList.toggle('toggled');
            
            if (mainNav.classList.contains('toggled')) {
                navToggle.setAttribute('aria-expanded', 'true');
            } else {
                navToggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // Sub-menu toggle
    const subMenuToggle = document.querySelectorAll('.sub-menu-toggle');

    if (subMenuToggle.length > 0) {
        subMenuToggle.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                const subMenu = this.nextElementSibling;
                
                if (subMenu) {
                    subMenu.classList.toggle('toggled');
                    
                    if (subMenu.classList.contains('toggled')) {
                        this.setAttribute('aria-expanded', 'true');
                    } else {
                        this.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });
    }

    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mainNav && mainNav.classList.contains('toggled')) {
            const isClickInside = mainNav.contains(event.target) || navToggle.contains(event.target);
            
            if (!isClickInside) {
                mainNav.classList.remove('toggled');
                navToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && mainNav && mainNav.classList.contains('toggled')) {
            mainNav.classList.remove('toggled');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.focus();
        }
    });

    // Add focus styles for keyboard navigation
    const links = document.querySelectorAll('a');
    
    links.forEach(function(link) {
        link.addEventListener('mousedown', function() {
            this.classList.add('mouse-focus');
        });
        
        link.addEventListener('blur', function() {
            this.classList.remove('mouse-focus');
        });
        
        link.addEventListener('keydown', function() {
            this.classList.remove('mouse-focus');
        });
    });
})();