/**
 * Main JavaScript file for AquaLuxe theme
 */

// Import Alpine.js
import Alpine from 'alpinejs';

// Make Alpine available globally
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Document Ready Function
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation ul');
    
    if (menuToggle && mainNavigation) {
        menuToggle.addEventListener('click', function() {
            mainNavigation.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', 
                menuToggle.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
            );
        });
    }
    
    // Dark Mode Toggle
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    
    if (darkModeToggle) {
        // Check for saved preference
        const darkMode = localStorage.getItem('darkMode') === 'true';
        
        // Apply dark mode if saved preference exists
        if (darkMode) {
            document.body.classList.add('dark-mode');
            darkModeToggle.setAttribute('aria-pressed', 'true');
        }
        
        // Toggle dark mode on click
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDarkMode);
            darkModeToggle.setAttribute('aria-pressed', isDarkMode ? 'true' : 'false');
        });
    }
    
    // Sticky Header
    const stickyHeader = document.querySelector('.sticky-header');
    
    if (stickyHeader) {
        const headerHeight = stickyHeader.offsetHeight;
        const scrollThreshold = 100;
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > scrollThreshold) {
                stickyHeader.classList.add('is-sticky');
                document.body.style.paddingTop = headerHeight + 'px';
            } else {
                stickyHeader.classList.remove('is-sticky');
                document.body.style.paddingTop = '0';
            }
        });
    }
    
    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                e.preventDefault();
                
                window.scrollTo({
                    top: target.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Initialize Lazy Loading for Images
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
        });
    } else {
        // Fallback for browsers that don't support native lazy loading
        const lazyImageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const lazyImage = entry.target;
                    lazyImage.src = lazyImage.dataset.src;
                    if (lazyImage.dataset.srcset) {
                        lazyImage.srcset = lazyImage.dataset.srcset;
                    }
                    lazyImage.classList.remove('lazy');
                    lazyImageObserver.unobserve(lazyImage);
                }
            });
        });
        
        document.querySelectorAll('img.lazy').forEach(lazyImage => {
            lazyImageObserver.observe(lazyImage);
        });
    }
    
    // WooCommerce Specific Scripts
    if (document.body.classList.contains('woocommerce-active')) {
        // Quick View Functionality
        const quickViewButtons = document.querySelectorAll('.aqualuxe-quick-view-button');
        
        if (quickViewButtons.length > 0) {
            quickViewButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const productId = this.dataset.productId;
                    
                    // Show loading state
                    this.textContent = 'Loading...';
                    
                    // AJAX call to get product details
                    fetch(aqualuxe_params.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_quick_view',
                            product_id: productId,
                            security: aqualuxe_params.quick_view_nonce
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Create modal with product data
                            const modal = document.createElement('div');
                            modal.className = 'aqualuxe-quick-view-modal';
                            modal.innerHTML = `
                                <div class="quick-view-overlay"></div>
                                <div class="quick-view-content">
                                    <button class="quick-view-close">&times;</button>
                                    <div class="quick-view-inner">
                                        ${data.html}
                                    </div>
                                </div>
                            `;
                            
                            // Add modal to DOM
                            document.body.appendChild(modal);
                            
                            // Add event listener to close button
                            modal.querySelector('.quick-view-close').addEventListener('click', function() {
                                document.body.removeChild(modal);
                            });
                            
                            // Close on overlay click
                            modal.querySelector('.quick-view-overlay').addEventListener('click', function() {
                                document.body.removeChild(modal);
                            });
                            
                            // Reset button text
                            button.textContent = 'Quick View';
                        } else {
                            console.error('Error loading quick view:', data.message);
                            button.textContent = 'Quick View';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        button.textContent = 'Quick View';
                    });
                });
            });
        }
        
        // Wishlist Functionality
        const wishlistButtons = document.querySelectorAll('.aqualuxe-wishlist-button');
        
        if (wishlistButtons.length > 0) {
            wishlistButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const productId = this.dataset.productId;
                    
                    // Show loading state
                    this.textContent = '...';
                    
                    // AJAX call to add/remove from wishlist
                    fetch(aqualuxe_params.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_wishlist_toggle',
                            product_id: productId,
                            security: aqualuxe_params.wishlist_nonce
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button text based on action
                            this.textContent = data.in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist';
                            
                            // Show notification
                            const notification = document.createElement('div');
                            notification.className = 'aqualuxe-notification';
                            notification.textContent = data.message;
                            document.body.appendChild(notification);
                            
                            // Remove notification after 3 seconds
                            setTimeout(() => {
                                document.body.removeChild(notification);
                            }, 3000);
                        } else {
                            console.error('Error updating wishlist:', data.message);
                            this.textContent = 'Add to Wishlist';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        this.textContent = 'Add to Wishlist';
                    });
                });
            });
        }
    }
});

// Custom Functions

/**
 * Debounce function to limit how often a function can be called
 * 
 * @param {Function} func - The function to debounce
 * @param {number} wait - The time to wait in milliseconds
 * @return {Function} - The debounced function
 */
function debounce(func, wait) {
    let timeout;
    
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Format currency based on WooCommerce settings
 * 
 * @param {number} amount - The amount to format
 * @return {string} - Formatted currency string
 */
function formatCurrency(amount) {
    if (typeof aqualuxe_params === 'undefined' || typeof aqualuxe_params.currency_format === 'undefined') {
        return amount.toFixed(2);
    }
    
    const format = aqualuxe_params.currency_format;
    const symbol = aqualuxe_params.currency_symbol;
    const decimals = aqualuxe_params.currency_decimals;
    const decimalSeparator = aqualuxe_params.currency_decimal_separator;
    const thousandSeparator = aqualuxe_params.currency_thousand_separator;
    
    // Format the number with proper separators
    const formattedNumber = amount.toFixed(decimals).replace('.', decimalSeparator);
    
    // Add thousand separators
    const parts = formattedNumber.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    const finalNumber = parts.join(decimalSeparator);
    
    // Replace placeholders in the format
    return format.replace('%1$s', symbol).replace('%2$s', finalNumber);
}