// Search functionality
(function() {
    'use strict';
    
    const Search = {
        init: function() {
            this.enhanceSearchForm();
            this.setupLiveSearch();
        },
        
        enhanceSearchForm: function() {
            const searchForms = document.querySelectorAll('.search-form');
            searchForms.forEach(form => {
                const input = form.querySelector('input[type="search"]');
                if (input) {
                    input.addEventListener('input', this.debounce((e) => {
                        this.handleSearchInput(e.target);
                    }, 300));
                }
            });
        },
        
        setupLiveSearch: function() {
            // This would setup AJAX live search functionality
            // For now, just enhance the basic search experience
        },
        
        handleSearchInput: function(input) {
            const query = input.value.trim();
            
            if (query.length >= 3) {
                // Could trigger live search here
                console.log('Search query:', query);
            }
        },
        
        debounce: function(func, wait) {
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
    };
    
    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => Search.init());
    } else {
        Search.init();
    }
})();