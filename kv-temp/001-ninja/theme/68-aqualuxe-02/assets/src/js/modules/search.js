/**
 * Search Module
 *
 * This file contains the JavaScript code for the search functionality.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get search elements
const searchToggles = document.querySelectorAll('.search-toggle');
const searchDropdowns = document.querySelectorAll('.search-dropdown');
const searchForms = document.querySelectorAll('.search-form');

// Toggle search dropdown
const toggleSearchDropdown = (toggle) => {
    const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', !isExpanded);
    
    // Find the closest search dropdown
    const dropdown = toggle.closest('.header-search').querySelector('.search-dropdown');
    if (dropdown) {
        dropdown.classList.toggle('active');
        
        // Focus on search input when dropdown is opened
        if (!isExpanded) {
            const searchInput = dropdown.querySelector('input[type="search"]');
            if (searchInput) {
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        }
    }
};

// Close search dropdown when clicking outside
const closeSearchDropdowns = (e) => {
    if (!e.target.closest('.header-search')) {
        searchToggles.forEach(toggle => {
            toggle.setAttribute('aria-expanded', 'false');
        });
        
        searchDropdowns.forEach(dropdown => {
            dropdown.classList.remove('active');
        });
    }
};

// Handle search form submission
const handleSearchSubmit = (form) => {
    form.addEventListener('submit', (e) => {
        const searchInput = form.querySelector('input[type="search"]');
        
        // Prevent submission if search input is empty
        if (!searchInput || !searchInput.value.trim()) {
            e.preventDefault();
            searchInput.focus();
        }
    });
};

// Handle AJAX search
const handleAjaxSearch = (form) => {
    if (!form.classList.contains('ajax-search')) return;
    
    const searchInput = form.querySelector('input[type="search"]');
    const resultsContainer = form.querySelector('.search-results');
    
    if (!searchInput || !resultsContainer) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.trim();
        
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Don't search if term is too short
        if (searchTerm.length < 3) {
            resultsContainer.innerHTML = '';
            resultsContainer.classList.remove('active');
            return;
        }
        
        // Set timeout to prevent too many requests
        searchTimeout = setTimeout(() => {
            // Show loading indicator
            resultsContainer.innerHTML = '<div class="search-loading"><span class="loading-spinner"></span></div>';
            resultsContainer.classList.add('active');
            
            // Create form data
            const formData = new FormData();
            formData.append('action', 'aqualuxe_ajax_search');
            formData.append('nonce', aqualuxeSettings.nonce);
            formData.append('s', searchTerm);
            
            // Send AJAX request
            fetch(aqualuxeSettings.ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show results
                    resultsContainer.innerHTML = data.data.html;
                } else {
                    // Show error message
                    resultsContainer.innerHTML = '<div class="search-error">' + (data.data.message || 'An error occurred.') + '</div>';
                }
            })
            .catch(error => {
                // Show error message
                resultsContainer.innerHTML = '<div class="search-error">An error occurred. Please try again.</div>';
                console.error('AJAX search error:', error);
            });
        }, 500);
    });
    
    // Close search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-form')) {
            resultsContainer.classList.remove('active');
        }
    });
    
    // Close search results when pressing Escape
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            resultsContainer.classList.remove('active');
            searchInput.blur();
        }
    });
};

// Initialize search functionality
const initSearch = () => {
    // Add event listeners to search toggles
    searchToggles.forEach(toggle => {
        toggle.addEventListener('click', () => {
            toggleSearchDropdown(toggle);
        });
    });
    
    // Add event listener to close search dropdowns when clicking outside
    document.addEventListener('click', closeSearchDropdowns);
    
    // Add event listeners to search forms
    searchForms.forEach(form => {
        handleSearchSubmit(form);
        handleAjaxSearch(form);
    });
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', initSearch);

// Export module
export default {
    initSearch
};