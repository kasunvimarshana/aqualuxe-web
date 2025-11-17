/**
 * Search Module
 * 
 * Handles the search functionality for the theme.
 */

const Search = {
  /**
   * Initialize the search functionality
   */
  init() {
    this.cacheDOM();
    this.bindEvents();
  },

  /**
   * Cache DOM elements
   */
  cacheDOM() {
    this.searchToggle = document.querySelector('.site-header__search-toggle');
    this.searchForm = document.querySelector('.site-header__search-form');
    this.searchInput = this.searchForm ? this.searchForm.querySelector('input[type="search"]') : null;
    this.body = document.body;
  },

  /**
   * Bind events
   */
  bindEvents() {
    // Toggle search form
    if (this.searchToggle && this.searchForm) {
      this.searchToggle.addEventListener('click', event => {
        event.preventDefault();
        this.toggleSearch();
      });
    }

    // Close search form when clicking outside
    document.addEventListener('click', event => {
      if (this.searchForm && this.searchForm.classList.contains('active')) {
        if (!this.searchForm.contains(event.target) && event.target !== this.searchToggle) {
          this.closeSearch();
        }
      }
    });

    // Close search form on escape key
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape' && this.searchForm && this.searchForm.classList.contains('active')) {
        this.closeSearch();
      }
    });

    // AJAX search results (if enabled)
    if (this.searchInput) {
      this.setupAjaxSearch();
    }
  },

  /**
   * Toggle search form
   */
  toggleSearch() {
    if (this.searchForm) {
      const isActive = this.searchForm.classList.contains('active');
      
      if (isActive) {
        this.closeSearch();
      } else {
        this.openSearch();
      }
    }
  },

  /**
   * Open search form
   */
  openSearch() {
    if (this.searchForm) {
      this.searchForm.classList.add('active');
      this.searchToggle.setAttribute('aria-expanded', 'true');
      
      // Focus search input
      if (this.searchInput) {
        setTimeout(() => {
          this.searchInput.focus();
        }, 100);
      }
    }
  },

  /**
   * Close search form
   */
  closeSearch() {
    if (this.searchForm) {
      this.searchForm.classList.remove('active');
      this.searchToggle.setAttribute('aria-expanded', 'false');
    }
  },

  /**
   * Setup AJAX search
   */
  setupAjaxSearch() {
    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'search-results';
    this.searchForm.appendChild(resultsContainer);

    // Debounce function for search input
    const debounce = (func, delay) => {
      let timeout;
      return function() {
        const context = this;
        const args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), delay);
      };
    };

    // Search input event
    this.searchInput.addEventListener('input', debounce(() => {
      const query = this.searchInput.value.trim();
      
      if (query.length >= 3) {
        this.performSearch(query, resultsContainer);
      } else {
        resultsContainer.innerHTML = '';
        resultsContainer.classList.remove('active');
      }
    }, 300));
  },

  /**
   * Perform AJAX search
   * 
   * @param {string} query - Search query
   * @param {HTMLElement} resultsContainer - Results container element
   */
  performSearch(query, resultsContainer) {
    // Show loading indicator
    resultsContainer.innerHTML = '<div class="search-loading">Searching...</div>';
    resultsContainer.classList.add('active');

    // Check if we have the aqualuxeData object from WordPress
    if (window.aqualuxeData && window.aqualuxeData.ajaxUrl) {
      // Create form data
      const formData = new FormData();
      formData.append('action', 'aqualuxe_ajax_search');
      formData.append('query', query);
      formData.append('nonce', window.aqualuxeData.nonce);

      // Send AJAX request
      fetch(window.aqualuxeData.ajaxUrl, {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Display results
            resultsContainer.innerHTML = data.data;
          } else {
            // Show error
            resultsContainer.innerHTML = '<div class="search-error">Error loading results.</div>';
          }
        })
        .catch(error => {
          console.error('AJAX Search Error:', error);
          resultsContainer.innerHTML = '<div class="search-error">Error loading results.</div>';
        });
    } else {
      // Fallback if AJAX is not available
      resultsContainer.innerHTML = '<div class="search-message">Press Enter to search.</div>';
    }
  }
};

export default Search;