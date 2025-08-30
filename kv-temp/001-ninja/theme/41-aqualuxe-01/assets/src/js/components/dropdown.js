/**
 * AquaLuxe Theme Dropdown Component
 *
 * Handles dropdown functionality for menus and other components.
 */

import { trapFocus } from '../utils/helpers';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns that don't use Alpine.js
    initDropdowns();
});

/**
 * Initialize all dropdowns on the page
 */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown:not([x-data])');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (!toggle || !menu) return;
        
        // Set initial ARIA attributes
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-haspopup', 'true');
        
        const menuId = menu.id || `dropdown-menu-${Math.floor(Math.random() * 1000)}`;
        menu.id = menuId;
        toggle.setAttribute('aria-controls', menuId);
        
        // Add event listeners
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            toggleDropdown(dropdown);
        });
        
        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdown.contains(e.target) && isDropdownOpen(dropdown)) {
                closeDropdown(dropdown);
            }
        });
        
        // Close when pressing Escape
        dropdown.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && isDropdownOpen(dropdown)) {
                closeDropdown(dropdown);
                toggle.focus();
            }
        });
        
        // Handle keyboard navigation
        menu.addEventListener('keydown', (e) => {
            const items = Array.from(menu.querySelectorAll('a, button'));
            const index = items.indexOf(document.activeElement);
            
            // Arrow down
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const nextIndex = index < items.length - 1 ? index + 1 : 0;
                items[nextIndex].focus();
            }
            
            // Arrow up
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prevIndex = index > 0 ? index - 1 : items.length - 1;
                items[prevIndex].focus();
            }
        });
    });
}

/**
 * Toggle a dropdown's open/closed state
 * 
 * @param {HTMLElement} dropdown - The dropdown container element
 */
function toggleDropdown(dropdown) {
    const isOpen = isDropdownOpen(dropdown);
    
    // Close all other dropdowns
    document.querySelectorAll('.dropdown.is-active').forEach(activeDropdown => {
        if (activeDropdown !== dropdown) {
            closeDropdown(activeDropdown);
        }
    });
    
    if (isOpen) {
        closeDropdown(dropdown);
    } else {
        openDropdown(dropdown);
    }
}

/**
 * Open a dropdown
 * 
 * @param {HTMLElement} dropdown - The dropdown container element
 */
function openDropdown(dropdown) {
    const toggle = dropdown.querySelector('.dropdown-toggle');
    const menu = dropdown.querySelector('.dropdown-menu');
    
    if (!toggle || !menu) return;
    
    dropdown.classList.add('is-active');
    menu.classList.remove('hidden');
    toggle.setAttribute('aria-expanded', 'true');
    
    // Trap focus within the dropdown
    const focusTrap = trapFocus(menu);
    dropdown.focusTrap = focusTrap;
    
    // Focus the first item
    const firstItem = menu.querySelector('a, button');
    if (firstItem) {
        firstItem.focus();
    }
}

/**
 * Close a dropdown
 * 
 * @param {HTMLElement} dropdown - The dropdown container element
 */
function closeDropdown(dropdown) {
    const toggle = dropdown.querySelector('.dropdown-toggle');
    const menu = dropdown.querySelector('.dropdown-menu');
    
    if (!toggle || !menu) return;
    
    dropdown.classList.remove('is-active');
    menu.classList.add('hidden');
    toggle.setAttribute('aria-expanded', 'false');
    
    // Release focus trap
    if (dropdown.focusTrap) {
        dropdown.focusTrap.release();
        dropdown.focusTrap = null;
    }
}

/**
 * Check if a dropdown is open
 * 
 * @param {HTMLElement} dropdown - The dropdown container element
 * @returns {boolean} - Whether the dropdown is open
 */
function isDropdownOpen(dropdown) {
    return dropdown.classList.contains('is-active');
}

// Export for use in other modules
export { initDropdowns, toggleDropdown, openDropdown, closeDropdown };