/**
 * Navigation Module
 *
 * This file contains the JavaScript code for the navigation functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Mobile navigation toggle
const menuToggle = document.querySelector('.menu-toggle');
const mainNavigation = document.querySelector('.main-navigation');
const siteHeader = document.querySelector('.site-header');

if (menuToggle && mainNavigation) {
    menuToggle.addEventListener('click', () => {
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', !isExpanded);
        mainNavigation.classList.toggle('toggled');
        
        // Toggle body class to prevent scrolling
        document.body.classList.toggle('menu-open');
    });
}

// Handle dropdown menus
const dropdownMenus = document.querySelectorAll('.menu-item-has-children');
if (dropdownMenus.length > 0) {
    // Add dropdown toggle buttons
    dropdownMenus.forEach(item => {
        const link = item.querySelector('a');
        const submenu = item.querySelector('.sub-menu');
        
        // Create dropdown toggle button
        const dropdownToggle = document.createElement('button');
        dropdownToggle.className = 'dropdown-toggle';
        dropdownToggle.setAttribute('aria-expanded', 'false');
        dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand child menu</span>';
        
        // Insert after the link
        link.parentNode.insertBefore(dropdownToggle, link.nextSibling);
        
        // Add click event to toggle button
        dropdownToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
            dropdownToggle.setAttribute('aria-expanded', !isExpanded);
            item.classList.toggle('toggled');
            
            // Toggle submenu visibility
            if (submenu) {
                if (isExpanded) {
                    submenu.style.maxHeight = '0';
                } else {
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                }
            }
        });
    });
    
    // Handle keyboard navigation
    dropdownMenus.forEach(item => {
        const link = item.querySelector('a');
        const submenu = item.querySelector('.sub-menu');
        const dropdownToggle = item.querySelector('.dropdown-toggle');
        
        // Add keyboard navigation
        link.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                dropdownToggle.setAttribute('aria-expanded', 'true');
                item.classList.add('toggled');
                
                if (submenu) {
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    
                    // Focus on the first link in the submenu
                    const firstLink = submenu.querySelector('a');
                    if (firstLink) {
                        firstLink.focus();
                    }
                }
            }
        });
        
        // Handle keyboard navigation within submenus
        if (submenu) {
            const submenuLinks = submenu.querySelectorAll('a');
            
            submenuLinks.forEach((submenuLink, index) => {
                submenuLink.addEventListener('keydown', (e) => {
                    // Close submenu on Escape
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        dropdownToggle.setAttribute('aria-expanded', 'false');
                        item.classList.remove('toggled');
                        submenu.style.maxHeight = '0';
                        link.focus();
                    }
                    
                    // Navigate to previous link on ArrowUp
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (index > 0) {
                            submenuLinks[index - 1].focus();
                        } else {
                            link.focus();
                        }
                    }
                    
                    // Navigate to next link on ArrowDown
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (index < submenuLinks.length - 1) {
                            submenuLinks[index + 1].focus();
                        }
                    }
                });
            });
        }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.menu-item-has-children')) {
            dropdownMenus.forEach(item => {
                const dropdownToggle = item.querySelector('.dropdown-toggle');
                const submenu = item.querySelector('.sub-menu');
                
                if (dropdownToggle && submenu) {
                    dropdownToggle.setAttribute('aria-expanded', 'false');
                    item.classList.remove('toggled');
                    submenu.style.maxHeight = '0';
                }
            });
        }
    });
}

// Handle mega menu
const megaMenuItems = document.querySelectorAll('.menu-item-mega-menu');
if (megaMenuItems.length > 0) {
    megaMenuItems.forEach(item => {
        const link = item.querySelector('a');
        const megaMenu = item.querySelector('.mega-menu');
        
        // Toggle mega menu on hover
        item.addEventListener('mouseenter', () => {
            item.classList.add('toggled');
            if (megaMenu) {
                megaMenu.style.maxHeight = megaMenu.scrollHeight + 'px';
            }
        });
        
        item.addEventListener('mouseleave', () => {
            item.classList.remove('toggled');
            if (megaMenu) {
                megaMenu.style.maxHeight = '0';
            }
        });
        
        // Handle keyboard navigation
        link.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                item.classList.add('toggled');
                
                if (megaMenu) {
                    megaMenu.style.maxHeight = megaMenu.scrollHeight + 'px';
                    
                    // Focus on the first link in the mega menu
                    const firstLink = megaMenu.querySelector('a');
                    if (firstLink) {
                        firstLink.focus();
                    }
                }
            }
        });
        
        // Handle keyboard navigation within mega menu
        if (megaMenu) {
            const megaMenuLinks = megaMenu.querySelectorAll('a');
            
            megaMenuLinks.forEach((megaMenuLink, index) => {
                megaMenuLink.addEventListener('keydown', (e) => {
                    // Close mega menu on Escape
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        item.classList.remove('toggled');
                        megaMenu.style.maxHeight = '0';
                        link.focus();
                    }
                });
            });
        }
    });
}

// Handle current menu item
const currentMenuItems = document.querySelectorAll('.current-menu-item, .current_page_item');
if (currentMenuItems.length > 0) {
    currentMenuItems.forEach(item => {
        // Add aria-current attribute
        const link = item.querySelector('a');
        if (link) {
            link.setAttribute('aria-current', 'page');
        }
    });
}

// Export module
export default {
    // Add any functions that need to be exported
};