/**
 * AquaLuxe Theme Navigation
 * 
 * This file handles the responsive navigation menu functionality.
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
});

/**
 * Initialize navigation functionality
 */
function initNavigation() {
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    const header = document.querySelector('.site-header');
    
    if (!menuToggle || !mobileMenu) return;
    
    // Toggle mobile menu
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        
        const expanded = menuToggle.getAttribute('aria-expanded') === 'true' || false;
        menuToggle.setAttribute('aria-expanded', !expanded);
        mobileMenu.classList.toggle('active');
        
        // Toggle menu icon
        const menuIcon = menuToggle.querySelector('.menu-icon');
        const closeIcon = menuToggle.querySelector('.close-icon');
        
        if (menuIcon && closeIcon) {
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
        
        // Prevent body scroll when menu is open
        document.body.classList.toggle('menu-open');
    });
    
    // Handle submenu toggles
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const parent = this.closest('li');
            const submenu = parent.querySelector('.sub-menu');
            const expanded = this.getAttribute('aria-expanded') === 'true' || false;
            
            // Close other submenus at the same level
            if (window.innerWidth < 1024) { // Mobile only
                const siblings = Array.from(parent.parentNode.children).filter(item => item !== parent);
                siblings.forEach(sibling => {
                    const siblingToggle = sibling.querySelector('.submenu-toggle');
                    const siblingSubmenu = sibling.querySelector('.sub-menu');
                    
                    if (siblingToggle && siblingSubmenu) {
                        siblingToggle.setAttribute('aria-expanded', 'false');
                        siblingSubmenu.classList.remove('active');
                        siblingToggle.querySelector('.toggle-icon').classList.remove('rotate-180');
                    }
                });
            }
            
            // Toggle current submenu
            this.setAttribute('aria-expanded', !expanded);
            submenu.classList.toggle('active');
            
            // Rotate arrow icon
            const toggleIcon = this.querySelector('.toggle-icon');
            if (toggleIcon) {
                toggleIcon.classList.toggle('rotate-180');
            }
        });
    });
    
    // Handle hover on desktop
    const menuItems = document.querySelectorAll('.menu-item-has-children');
    menuItems.forEach(item => {
        // Only apply hover behavior on desktop
        if (window.innerWidth >= 1024) {
            item.addEventListener('mouseenter', function() {
                const submenu = this.querySelector('.sub-menu');
                if (submenu) {
                    submenu.classList.add('active');
                    
                    // Position submenu to not go off-screen
                    const rect = submenu.getBoundingClientRect();
                    if (rect.right > window.innerWidth) {
                        submenu.classList.add('submenu-right');
                    }
                }
            });
            
            item.addEventListener('mouseleave', function() {
                const submenu = this.querySelector('.sub-menu');
                if (submenu) {
                    submenu.classList.remove('active');
                    submenu.classList.remove('submenu-right');
                }
            });
        }
    });
    
    // Sticky header on scroll
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.classList.add('sticky');
            
            // Hide header when scrolling down, show when scrolling up
            if (scrollTop > lastScrollTop) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
        } else {
            header.classList.remove('sticky');
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            mobileMenu.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
            
            // Reset menu icons
            const menuIcon = menuToggle.querySelector('.menu-icon');
            const closeIcon = menuToggle.querySelector('.close-icon');
            
            if (menuIcon && closeIcon) {
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileMenu.classList.contains('active') && 
            !mobileMenu.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            mobileMenu.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
            
            // Reset menu icons
            const menuIcon = menuToggle.querySelector('.menu-icon');
            const closeIcon = menuToggle.querySelector('.close-icon');
            
            if (menuIcon && closeIcon) {
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }
    });
    
    // Handle keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Close menu on ESC key
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            mobileMenu.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('menu-open');
            menuToggle.focus();
            
            // Reset menu icons
            const menuIcon = menuToggle.querySelector('.menu-icon');
            const closeIcon = menuToggle.querySelector('.close-icon');
            
            if (menuIcon && closeIcon) {
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        }
    });
    
    // Add accessibility attributes
    menuToggle.setAttribute('aria-haspopup', 'true');
    menuToggle.setAttribute('aria-controls', 'mobile-menu');
    
    submenuToggles.forEach(toggle => {
        const submenuId = toggle.getAttribute('data-submenu');
        const submenu = document.getElementById(submenuId);
        
        if (submenu) {
            toggle.setAttribute('aria-haspopup', 'true');
            toggle.setAttribute('aria-controls', submenuId);
        }
    });
}

// Export functions for use in other files
export {
    initNavigation
};