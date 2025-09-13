/**
 * Advanced Search Functionality
 * Handles search enhancements, filters, and AJAX search
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  /**
   * Search Enhancement Handler
   */
  class SearchHandler {
    constructor() {
      this.searchForms = document.querySelectorAll('.search-form');
      this.searchInputs = document.querySelectorAll('.search-field');
      this.searchFilters = document.querySelectorAll('.search-filter');
      this.init();
    }

    /**
     * Initialize search functionality
     */
    init() {
      this.setupSearchForms();
      this.setupLiveSearch();
      this.setupSearchFilters();
    }

    /**
     * Setup search forms
     */
    setupSearchForms() {
      this.searchForms.forEach(form => {
        form.addEventListener('submit', e => {
          this.handleSearchSubmit(e, form);
        });
      });
    }

    /**
     * Setup live search functionality
     */
    setupLiveSearch() {
      this.searchInputs.forEach(input => {
        let debounceTimer;

        input.addEventListener('input', e => {
          clearTimeout(debounceTimer);

          debounceTimer = setTimeout(() => {
            this.performLiveSearch(e.target.value);
          }, 300);
        });

        // Clear search results when input is cleared
        input.addEventListener('keyup', e => {
          if (e.target.value === '') {
            this.clearSearchResults();
          }
        });
      });
    }

    /**
     * Setup search filters
     */
    setupSearchFilters() {
      this.searchFilters.forEach(filter => {
        filter.addEventListener('change', () => {
          this.updateSearchResults();
        });
      });
    }

    /**
     * Handle search form submission
     * @param {Event} e
     * @param {HTMLElement} form
     */
    handleSearchSubmit(e, form) {
      e.preventDefault();

      const searchTerm = form.querySelector('.search-field').value.trim();

      if (searchTerm.length < 2) {
        this.showSearchMessage('Please enter at least 2 characters to search.');
        return;
      }

      this.performSearch(searchTerm);
    }

    /**
     * Perform live search
     * @param {string} searchTerm
     */
    async performLiveSearch(searchTerm) {
      if (searchTerm.length < 2) {
        this.clearSearchResults();
        return;
      }

      try {
        this.showSearchLoading();

        // Simulate search (replace with actual AJAX)
        const results = await this.simulateSearch(searchTerm);

        this.displaySearchResults(results);
      } catch (error) {
        this.showSearchMessage('Search error occurred. Please try again.');
      }
    }

    /**
     * Perform full search
     * @param {string} searchTerm
     */
    async performSearch(searchTerm) {
      try {
        this.showSearchLoading();

        // Get active filters
        const filters = this.getActiveFilters();

        // Simulate search with filters
        const results = await this.simulateSearch(searchTerm, filters);

        this.displaySearchResults(results);
      } catch (error) {
        this.showSearchMessage('Search error occurred. Please try again.');
      }
    }

    /**
     * Get active search filters
     * @returns {Object}
     */
    getActiveFilters() {
      const filters = {};

      this.searchFilters.forEach(filter => {
        if (filter.checked || filter.selected) {
          const filterType = filter.dataset.filterType || 'category';
          if (!filters[filterType]) {
            filters[filterType] = [];
          }
          filters[filterType].push(filter.value);
        }
      });

      return filters;
    }

    /**
     * Simulate search (replace with actual AJAX implementation)
     * @param {string} searchTerm
     */
    simulateSearch(searchTerm) {
      return new Promise(resolve => {
        setTimeout(() => {
          // Mock search results
          const mockResults = [
            {
              title: `Result for "${searchTerm}"`,
              excerpt: 'This is a mock search result...',
              url: '#',
              type: 'post',
            },
          ];
          resolve(mockResults);
        }, 500);
      });
    }

    /**
     * Display search results
     * @param {Array} results
     */
    displaySearchResults(results) {
      let resultsContainer = document.querySelector('.search-results');

      if (!resultsContainer) {
        resultsContainer = this.createSearchResultsContainer();
      }

      if (results.length === 0) {
        resultsContainer.innerHTML =
          '<p class="no-results">No results found.</p>';
        resultsContainer.style.display = 'block';
        return;
      }

      let html = '<div class="search-results-list">';

      results.forEach(result => {
        html += `
          <div class="search-result-item">
            <h3><a href="${result.url}">${result.title}</a></h3>
            <p>${result.excerpt}</p>
            <span class="result-type">${result.type}</span>
          </div>
        `;
      });

      html += '</div>';

      resultsContainer.innerHTML = html;
      resultsContainer.style.display = 'block';
    }

    /**
     * Create search results container
     * @returns {HTMLElement}
     */
    createSearchResultsContainer() {
      const container = document.createElement('div');
      container.className = 'search-results';
      container.style.display = 'none';

      // Insert after the first search form
      const firstForm = this.searchForms[0];
      if (firstForm) {
        firstForm.parentNode.insertBefore(container, firstForm.nextSibling);
      }

      return container;
    }

    /**
     * Show search loading state
     */
    showSearchLoading() {
      const resultsContainer = document.querySelector('.search-results');
      if (resultsContainer) {
        resultsContainer.innerHTML =
          '<p class="search-loading">Searching...</p>';
        resultsContainer.style.display = 'block';
      }
    }

    /**
     * Clear search results
     */
    clearSearchResults() {
      const resultsContainer = document.querySelector('.search-results');
      if (resultsContainer) {
        resultsContainer.style.display = 'none';
      }
    }

    /**
     * Show search message
     * @param {string} message
     */
    showSearchMessage(message) {
      const resultsContainer =
        document.querySelector('.search-results') ||
        this.createSearchResultsContainer();

      resultsContainer.innerHTML = `<p class="search-message">${message}</p>`;
      resultsContainer.style.display = 'block';
    }

    /**
     * Update search results based on current filters
     */
    updateSearchResults() {
      const activeInput =
        document.querySelector('.search-field:focus') ||
        document.querySelector('.search-field');

      if (activeInput && activeInput.value.length >= 2) {
        this.performSearch(activeInput.value);
      }
    }
  }

  // Initialize search handler
  new SearchHandler();
});
