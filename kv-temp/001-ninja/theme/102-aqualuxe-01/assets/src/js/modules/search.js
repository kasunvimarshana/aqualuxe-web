/**
 * Search Module
 * Handles search functionality
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';

    class SearchHandler {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.setupSearchForm();
        }

        bindEvents() {
            // Handle search toggle
            $(document).on('click', '#search-toggle', this.toggleSearch.bind(this));
            
            // Handle escape key
            $(document).on('keydown', this.handleKeydown.bind(this));
            
            // Handle outside click
            $(document).on('click', this.handleOutsideClick.bind(this));
        }

        toggleSearch(e) {
            e.preventDefault();
            const overlay = $('#search-overlay');
            overlay.toggleClass('hidden');
            
            if (!overlay.hasClass('hidden')) {
                overlay.find('input[type="search"]').focus();
            }
        }

        handleKeydown(e) {
            if (e.key === 'Escape') {
                const overlay = $('#search-overlay');
                if (!overlay.hasClass('hidden')) {
                    overlay.addClass('hidden');
                    $('#search-toggle').focus();
                }
            }
        }

        handleOutsideClick(e) {
            if (!$(e.target).closest('#search-overlay, #search-toggle').length) {
                $('#search-overlay').addClass('hidden');
            }
        }

        setupSearchForm() {
            // Add loading state to search forms
            $('.search-form').on('submit', function() {
                $(this).find('input[type="submit"]').prop('disabled', true);
            });
        }
    }

    // Initialize search handler
    new SearchHandler();

})(jQuery);