/**
 * Search JavaScript Module
 * 
 * Handles enhanced search functionality including:
 * - Live search
 * - Search suggestions
 * - Modal search interface
 * - Keyboard shortcuts
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Search Controller
     */
    class SearchController {
        constructor() {
            this.settings = window.aqualuxeSearch || {};
            this.storageKey = 'aqualuxe-recent-searches';
            this.searchTimeout = null;
            this.currentRequest = null;
            this.isModalOpen = false;
            this.recentSearches = this.getRecentSearches();
            
            this.init();
        }

        /**
         * Initialize the search controller
         */
        init() {
            if (!this.settings.enabled) {
                return;
            }

            this.bindEvents();
            this.setupKeyboardShortcuts();
            this.preloadSuggestions();
            
            console.log('AquaLuxe Search initialized');
        }

        /**
         * Bind event listeners
         */
        bindEvents() {
            // Search trigger buttons
            $(document).on('click', '[data-search-trigger="modal"]', this.openSearchModal.bind(this));
            
            // Modal events
            $(document).on('click', '#aqualuxe-search-modal', this.handleModalClick.bind(this));
            $(document).on('input', '#aqualuxe-search-modal .aqualuxe-search-input', this.handleSearchInput.bind(this));
            $(document).on('submit', '#aqualuxe-search-modal .aqualuxe-search-form', this.handleSearchSubmit.bind(this));
            
            // Live search on regular forms
            $(document).on('input', '.aqualuxe-search-input:not(#aqualuxe-search-modal .aqualuxe-search-input)', 
                this.debounce(this.handleLiveSearch.bind(this), this.settings.delay || 300));
            
            // Suggestion clicks
            $(document).on('click', '.aqualuxe-search-suggestion', this.handleSuggestionClick.bind(this));
            
            // Result navigation
            $(document).on('keydown', '#aqualuxe-search-modal', this.handleKeyNavigation.bind(this));
            
            // Clear recent searches
            $(document).on('click', '[data-action="clear-recent-searches"]', this.clearRecentSearches.bind(this));
            
            // Close modal on escape or outside click
            $(document).on('keyup', this.handleGlobalKeyup.bind(this));
        }

        /**
         * Setup keyboard shortcuts
         */
        setupKeyboardShortcuts() {
            $(document).on('keydown', (event) => {
                // Ctrl/Cmd + K to open search
                if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
                    event.preventDefault();
                    this.openSearchModal();
                }
                
                // Escape to close search
                if (event.key === 'Escape' && this.isModalOpen) {
                    event.preventDefault();
                    this.closeSearchModal();
                }
                
                // Slash key to open search (like GitHub)
                if (event.key === '/' && !this.isInputFocused() && !this.isModalOpen) {
                    event.preventDefault();
                    this.openSearchModal();
                }
            });
        }

        /**
         * Open search modal
         */
        openSearchModal() {
            const $modal = $('#aqualuxe-search-modal');
            const $input = $modal.find('.aqualuxe-search-input');
            
            $modal.addClass('active');
            $input.focus();
            this.isModalOpen = true;
            
            // Prevent body scroll
            $('body').addClass('modal-open');
            
            // Load suggestions if empty
            if (!$('#aqualuxe-search-content').children().length) {
                this.showSuggestions();
            }
            
            // Track event
            this.trackEvent('search_modal_opened');
        }

        /**
         * Close search modal
         */
        closeSearchModal() {
            const $modal = $('#aqualuxe-search-modal');
            
            $modal.removeClass('active');
            this.isModalOpen = false;
            
            // Re-enable body scroll
            $('body').removeClass('modal-open');
            
            // Clear search input
            $modal.find('.aqualuxe-search-input').val('');
            
            // Clear results
            $('#aqualuxe-search-content').empty();
            
            // Cancel any pending requests
            if (this.currentRequest) {
                this.currentRequest.abort();
                this.currentRequest = null;
            }
            
            // Track event
            this.trackEvent('search_modal_closed');
        }

        /**
         * Handle modal click (close on backdrop click)
         */
        handleModalClick(event) {
            if (event.target === event.currentTarget) {
                this.closeSearchModal();
            }
        }

        /**
         * Handle search input
         */
        handleSearchInput(event) {
            const query = $(event.target).val().trim();
            
            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            // If query is empty, show suggestions
            if (!query) {
                this.showSuggestions();
                return;
            }
            
            // If query is too short, don't search
            if (query.length < this.settings.minChars) {
                return;
            }
            
            // Debounce the search
            this.searchTimeout = setTimeout(() => {
                this.performLiveSearch(query);
            }, this.settings.delay || 300);
        }

        /**
         * Handle search form submit
         */
        handleSearchSubmit(event) {
            const $form = $(event.currentTarget);
            const query = $form.find('.aqualuxe-search-input').val().trim();
            
            if (!query) {
                event.preventDefault();
                return;
            }
            
            // Add to recent searches
            this.addToRecentSearches(query);
            
            // Track search
            this.trackEvent('search_submitted', { query: query });
            
            // Close modal
            this.closeSearchModal();
            
            // Let the form submit naturally for fallback
        }

        /**
         * Handle live search on regular forms
         */
        handleLiveSearch(event) {
            if (!this.settings.liveSearch) {
                return;
            }
            
            const query = $(event.target).val().trim();
            const $container = $(event.target).closest('.aqualuxe-search-form').find('.search-results');
            
            if (!$container.length) {
                return;
            }
            
            if (!query || query.length < this.settings.minChars) {
                $container.hide();
                return;
            }
            
            this.performLiveSearch(query, $container);
        }

        /**
         * Handle suggestion click
         */
        handleSuggestionClick(event) {
            event.preventDefault();
            
            const suggestion = $(event.currentTarget).text().trim();
            const $modal = $('#aqualuxe-search-modal');
            const $input = $modal.find('.aqualuxe-search-input');
            
            $input.val(suggestion);
            this.performLiveSearch(suggestion);
            
            // Track suggestion usage
            this.trackEvent('search_suggestion_used', { suggestion: suggestion });
        }

        /**
         * Handle key navigation in results
         */
        handleKeyNavigation(event) {
            const $results = $('.aqualuxe-search-result-link');
            
            if (!$results.length) {
                return;
            }
            
            let currentIndex = $results.index($results.filter('.focused'));
            
            switch (event.key) {
                case 'ArrowDown':
                    event.preventDefault();
                    currentIndex = Math.min(currentIndex + 1, $results.length - 1);
                    this.focusResult(currentIndex);
                    break;
                    
                case 'ArrowUp':
                    event.preventDefault();
                    currentIndex = Math.max(currentIndex - 1, 0);
                    this.focusResult(currentIndex);
                    break;
                    
                case 'Enter':
                    const $focused = $results.filter('.focused');
                    if ($focused.length) {
                        event.preventDefault();
                        window.location.href = $focused.attr('href');
                    }
                    break;
            }
        }

        /**
         * Handle global keyup events
         */
        handleGlobalKeyup(event) {
            if (event.key === 'Escape' && this.isModalOpen) {
                this.closeSearchModal();
            }
        }

        /**
         * Focus a specific result
         */
        focusResult(index) {
            const $results = $('.aqualuxe-search-result-link');
            $results.removeClass('focused');
            
            if (index >= 0 && index < $results.length) {
                $results.eq(index).addClass('focused');
            }
        }

        /**
         * Perform live search
         */
        performLiveSearch(query, $container = null) {
            // Cancel previous request
            if (this.currentRequest) {
                this.currentRequest.abort();
            }
            
            // Show loading state
            this.showLoading($container);
            
            // Perform search
            this.currentRequest = $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_live_search',
                    nonce: this.settings.nonce,
                    query: query,
                    post_types: this.settings.postTypes,
                    max_results: this.settings.maxResults
                },
                success: (response) => {
                    if (response.success) {
                        this.displayResults(response.data.results, query, $container);
                        
                        // Track successful search
                        this.trackEvent('live_search_performed', {
                            query: query,
                            results_count: response.data.results.length
                        });
                    } else {
                        this.showError(response.data || 'Search failed', $container);
                    }
                },
                error: (xhr, status, error) => {
                    if (status !== 'abort') {
                        this.showError('Search request failed', $container);
                        console.error('Search error:', error);
                    }
                },
                complete: () => {
                    this.currentRequest = null;
                }
            });
        }

        /**
         * Display search results
         */
        displayResults(results, query, $container = null) {
            const $content = $container || $('#aqualuxe-search-content');
            
            if (!results || results.length === 0) {
                this.showEmptyState(query, $content);
                return;
            }
            
            let html = '<ul class="aqualuxe-search-results">';
            
            results.forEach((result, index) => {
                const image = result.image ? 
                    `<img src="${result.image}" alt="${result.title}" class="aqualuxe-search-result-image">` : 
                    '<div class="aqualuxe-search-result-image-placeholder"></div>';
                
                const excerpt = result.excerpt ? 
                    `<p class="aqualuxe-search-result-excerpt">${this.highlightQuery(result.excerpt, query)}</p>` : '';
                
                const meta = result.date ? 
                    `<div class="aqualuxe-search-result-meta">${result.type} • ${result.date}</div>` : '';
                
                html += `
                    <li class="aqualuxe-search-result">
                        <a href="${result.url}" class="aqualuxe-search-result-link" data-result-index="${index}">
                            ${image}
                            <div class="aqualuxe-search-result-content">
                                <h4 class="aqualuxe-search-result-title">${this.highlightQuery(result.title, query)}</h4>
                                ${excerpt}
                                ${meta}
                            </div>
                        </a>
                    </li>
                `;
            });
            
            html += '</ul>';
            
            // Add "view all results" link if in modal
            if (!$container && results.length >= this.settings.maxResults) {
                const searchUrl = this.getSearchUrl(query);
                html += `
                    <div class="aqualuxe-search-view-all">
                        <a href="${searchUrl}" class="aqualuxe-search-view-all-link">
                            ${this.settings.strings.viewAll || 'View all results'}
                        </a>
                    </div>
                `;
            }
            
            $content.html(html);
            
            // Focus first result
            this.focusResult(0);
        }

        /**
         * Show loading state
         */
        showLoading($container = null) {
            const $content = $container || $('#aqualuxe-search-content');
            
            $content.html(`
                <div class="aqualuxe-search-loading">
                    <div class="spinner"></div>
                    <p>${this.settings.strings.searching || 'Searching...'}</p>
                </div>
            `);
        }

        /**
         * Show empty state
         */
        showEmptyState(query, $container = null) {
            const $content = $container || $('#aqualuxe-search-content');
            
            $content.html(`
                <div class="aqualuxe-search-empty">
                    <svg class="aqualuxe-search-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3>${this.settings.strings.noResults || 'No results found'}</h3>
                    <p>Try searching for something else or browse our content.</p>
                </div>
            `);
        }

        /**
         * Show error state
         */
        showError(message, $container = null) {
            const $content = $container || $('#aqualuxe-search-content');
            
            $content.html(`
                <div class="aqualuxe-search-error">
                    <p style="color: #dc2626;">${message}</p>
                </div>
            `);
        }

        /**
         * Show search suggestions
         */
        showSuggestions() {
            if (!this.settings.suggestions) {
                return;
            }
            
            // Load suggestions via AJAX
            $.ajax({
                url: this.settings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_search_suggestions',
                    nonce: this.settings.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.displaySuggestions(response.data.suggestions);
                    }
                },
                error: (xhr, status, error) => {
                    console.warn('Failed to load search suggestions:', error);
                }
            });
        }

        /**
         * Display search suggestions
         */
        displaySuggestions(suggestions) {
            const $content = $('#aqualuxe-search-content');
            let html = '';
            
            // Recent searches
            if (this.recentSearches.length > 0) {
                html += `
                    <div class="aqualuxe-search-suggestions">
                        <h5 class="aqualuxe-search-suggestions-title">${this.settings.strings.recentSearches || 'Recent searches'}</h5>
                        <ul class="aqualuxe-search-suggestions-list">
                `;
                
                this.recentSearches.slice(0, 5).forEach(search => {
                    html += `<li class="aqualuxe-search-suggestion">${search}</li>`;
                });
                
                html += `
                        </ul>
                        <button type="button" data-action="clear-recent-searches" style="font-size: 0.75rem; color: #6b7280; background: none; border: none; cursor: pointer;">Clear recent</button>
                    </div>
                `;
            }
            
            // Popular suggestions
            if (suggestions.popular && suggestions.popular.length > 0) {
                html += `
                    <div class="aqualuxe-search-suggestions">
                        <h5 class="aqualuxe-search-suggestions-title">${this.settings.strings.suggestions || 'Popular searches'}</h5>
                        <ul class="aqualuxe-search-suggestions-list">
                `;
                
                suggestions.popular.forEach(suggestion => {
                    html += `<li class="aqualuxe-search-suggestion">${suggestion}</li>`;
                });
                
                html += '</ul></div>';
            }
            
            // Popular tags
            if (suggestions.tags && suggestions.tags.length > 0) {
                html += `
                    <div class="aqualuxe-search-suggestions">
                        <h5 class="aqualuxe-search-suggestions-title">Popular topics</h5>
                        <ul class="aqualuxe-search-suggestions-list">
                `;
                
                suggestions.tags.forEach(tag => {
                    html += `<li class="aqualuxe-search-suggestion">${tag}</li>`;
                });
                
                html += '</ul></div>';
            }
            
            $content.html(html);
        }

        /**
         * Preload suggestions for better UX
         */
        preloadSuggestions() {
            if (this.settings.suggestions) {
                // Preload suggestions in the background
                $.ajax({
                    url: this.settings.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_search_suggestions',
                        nonce: this.settings.nonce
                    },
                    success: (response) => {
                        if (response.success) {
                            // Store suggestions for later use
                            this.cachedSuggestions = response.data.suggestions;
                        }
                    }
                });
            }
        }

        /**
         * Highlight search query in text
         */
        highlightQuery(text, query) {
            if (!query || !text) {
                return text;
            }
            
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(regex, '<span class="search-highlight">$1</span>');
        }

        /**
         * Get search URL
         */
        getSearchUrl(query) {
            const url = new URL(window.location.origin);
            url.searchParams.set('s', query);
            return url.toString();
        }

        /**
         * Recent searches management
         */
        getRecentSearches() {
            try {
                const stored = localStorage.getItem(this.storageKey);
                return stored ? JSON.parse(stored) : [];
            } catch (e) {
                console.warn('Failed to read recent searches:', e);
                return [];
            }
        }

        saveRecentSearches() {
            try {
                localStorage.setItem(this.storageKey, JSON.stringify(this.recentSearches));
            } catch (e) {
                console.warn('Failed to save recent searches:', e);
            }
        }

        addToRecentSearches(query) {
            if (!query || query.length < this.settings.minChars) {
                return;
            }
            
            // Remove existing occurrence
            this.recentSearches = this.recentSearches.filter(search => search !== query);
            
            // Add to beginning
            this.recentSearches.unshift(query);
            
            // Keep only last 10
            this.recentSearches = this.recentSearches.slice(0, 10);
            
            this.saveRecentSearches();
        }

        clearRecentSearches() {
            this.recentSearches = [];
            this.saveRecentSearches();
            
            // Refresh suggestions
            this.showSuggestions();
        }

        /**
         * Utility methods
         */
        isInputFocused() {
            const activeElement = document.activeElement;
            return activeElement && (
                activeElement.tagName === 'INPUT' || 
                activeElement.tagName === 'TEXTAREA' || 
                activeElement.contentEditable === 'true'
            );
        }

        debounce(func, delay) {
            let timeoutId;
            return function (...args) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        }

        trackEvent(eventName, data = {}) {
            // Track with Google Analytics if available
            if (typeof gtag !== 'undefined') {
                gtag('event', eventName, {
                    event_category: 'search',
                    ...data
                });
            }
            
            // Custom tracking hook
            $(document).trigger('aqualuxe:searchEvent', [eventName, data]);
        }

        /**
         * Public API methods
         */
        openModal() {
            this.openSearchModal();
        }

        closeModal() {
            this.closeSearchModal();
        }

        search(query) {
            this.performLiveSearch(query);
        }

        getRecentSearchesList() {
            return [...this.recentSearches];
        }

        clearRecent() {
            this.clearRecentSearches();
        }
    }

    /**
     * Initialize when DOM is ready
     */
    $(document).ready(function() {
        // Initialize search controller
        window.aqualuxeSearchController = new SearchController();
        
        // Expose public API
        window.AquaLuxe = window.AquaLuxe || {};
        window.AquaLuxe.search = {
            open: () => window.aqualuxeSearchController.openModal(),
            close: () => window.aqualuxeSearchController.closeModal(),
            search: (query) => window.aqualuxeSearchController.search(query),
            getRecent: () => window.aqualuxeSearchController.getRecentSearchesList(),
            clearRecent: () => window.aqualuxeSearchController.clearRecent()
        };
        
        // Add search trigger to header if not already present
        const $header = $('.site-header, header');
        if ($header.length && !$header.find('[data-search-trigger]').length) {
            // This would be implemented in the header template
        }
    });

    /**
     * Handle search form enhancements
     */
    $(document).on('focus', '.aqualuxe-search-input', function() {
        const $form = $(this).closest('.aqualuxe-search-form');
        $form.addClass('focused');
    });

    $(document).on('blur', '.aqualuxe-search-input', function() {
        const $form = $(this).closest('.aqualuxe-search-form');
        setTimeout(() => {
            $form.removeClass('focused');
        }, 200); // Delay to allow for click events
    });

})(jQuery);
