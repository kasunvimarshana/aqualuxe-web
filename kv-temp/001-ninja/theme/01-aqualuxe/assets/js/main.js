/**
 * Main JavaScript file for AquaLuxe theme
 * 
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 * 
 * @package AquaLuxe
 */

// Import dark mode functionality
import './dark-mode.js';

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
            
            // Update aria-expanded attribute
            const isExpanded = mobileMenu.classList.contains('hidden') ? 'false' : 'true';
            mobileMenuToggle.setAttribute('aria-expanded', isExpanded);
        });
    }
    
    // Dropdown menus for desktop navigation
    const dropdownToggles = document.querySelectorAll('.menu-item-has-children > a');
    
    dropdownToggles.forEach(function(toggle) {
        toggle.addEventListener('click', function(e) {
            // Only handle dropdown on desktop
            if (window.innerWidth >= 1024) {
                e.preventDefault();
                
                const parentItem = this.parentNode;
                const dropdown = parentItem.querySelector('.sub-menu');
                
                // Close all other dropdowns
                document.querySelectorAll('.menu-item-has-children.active').forEach(function(item) {
                    if (item !== parentItem) {
                        item.classList.remove('active');
                        item.querySelector('.sub-menu').classList.add('hidden');
                    }
                });
                
                // Toggle current dropdown
                parentItem.classList.toggle('active');
                dropdown.classList.toggle('hidden');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.menu-item-has-children')) {
            document.querySelectorAll('.menu-item-has-children.active').forEach(function(item) {
                item.classList.remove('active');
                item.querySelector('.sub-menu').classList.add('hidden');
            });
        }
    });
    
    // Quantity input buttons for WooCommerce
    const quantityInputs = document.querySelectorAll('.quantity input[type="number"]');
    
    quantityInputs.forEach(function(input) {
        const wrapper = document.createElement('div');
        wrapper.className = 'quantity-wrapper flex items-center border border-gray-300 dark:border-gray-600 rounded overflow-hidden';
        
        const minusBtn = document.createElement('button');
        minusBtn.type = 'button';
        minusBtn.className = 'quantity-btn minus bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1';
        minusBtn.textContent = '-';
        
        const plusBtn = document.createElement('button');
        plusBtn.type = 'button';
        plusBtn.className = 'quantity-btn plus bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 px-2 py-1';
        plusBtn.textContent = '+';
        
        // Replace input with our custom wrapper
        const parent = input.parentNode;
        parent.insertBefore(wrapper, input);
        wrapper.appendChild(minusBtn);
        wrapper.appendChild(input);
        wrapper.appendChild(plusBtn);
        
        // Update input styles
        input.className = 'text-center w-12 border-0 focus:ring-0 dark:bg-gray-700 dark:text-white';
        
        // Add event listeners
        minusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const minValue = parseInt(input.min) || 1;
            if (currentValue > minValue) {
                input.value = currentValue - 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
        
        plusBtn.addEventListener('click', function() {
            const currentValue = parseInt(input.value);
            const maxValue = parseInt(input.max);
            if (!maxValue || currentValue < maxValue) {
                input.value = currentValue + 1;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
    
    // Product gallery image switching
    const productThumbnails = document.querySelectorAll('.woocommerce-product-gallery__image a');
    const mainProductImage = document.querySelector('.woocommerce-product-gallery__image:first-child img');
    
    if (productThumbnails.length && mainProductImage) {
        productThumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function(e) {
                e.preventDefault();
                
                const fullSizeUrl = this.getAttribute('href');
                const thumbnailUrl = this.querySelector('img').getAttribute('src');
                const srcset = this.querySelector('img').getAttribute('srcset');
                const alt = this.querySelector('img').getAttribute('alt');
                
                mainProductImage.setAttribute('src', thumbnailUrl);
                mainProductImage.setAttribute('data-large_image', fullSizeUrl);
                
                if (srcset) {
                    mainProductImage.setAttribute('srcset', srcset);
                }
                
                if (alt) {
                    mainProductImage.setAttribute('alt', alt);
                }
            });
        });
    }
    
    // Initialize Alpine.js components
    if (window.Alpine) {
        // Product tabs
        window.Alpine.data('productTabs', () => ({
            activeTab: 'description',
            init() {
                // Check if URL has a hash for a specific tab
                const hash = window.location.hash;
                if (hash) {
                    const tabId = hash.substring(1);
                    if (['description', 'specifications', 'reviews'].includes(tabId)) {
                        this.activeTab = tabId;
                    }
                }
            },
            setActiveTab(tab) {
                this.activeTab = tab;
                window.location.hash = tab;
            }
        }));
    }
});