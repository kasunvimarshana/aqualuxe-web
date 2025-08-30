/**
 * AJAX Loader Module
 * 
 * Handles dynamic content loading via AJAX for various theme features:
 * - Infinite scroll for posts
 * - Load more buttons
 * - Dynamic filtering
 * - Search results
 */

class AjaxLoader {
    constructor() {
        this.infiniteScrollContainers = document.querySelectorAll('.infinite-scroll');
        this.loadMoreButtons = document.querySelectorAll('.load-more-button');
        this.filterForms = document.querySelectorAll('.ajax-filter-form');
        this.ajaxSearchForms = document.querySelectorAll('.ajax-search-form');
        this.loadingClass = 'loading';
        this.loadingTemplate = '<div class="loading-spinner"><span class="screen-reader-text">Loading...</span></div>';
        this.ajaxUrl = window.aqualuxe?.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = window.aqualuxe?.nonce || '';
    }

    init() {
        this.setupInfiniteScroll();
        this.setupLoadMoreButtons();
        this.setupFilterForms();
        this.setupAjaxSearch();
    }

    setupInfiniteScroll() {
        if (!this.infiniteScrollContainers.length) {
            return;
        }

        const options = {
            root: null,
            rootMargin: '0px 0px 200px 0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const container = entry.target;
                    
                    // Don't load if already loading or no more pages
                    if (container.classList.contains(this.loadingClass) || 
                        container.classList.contains('all-loaded')) {
                        return;
                    }
                    
                    this.loadMoreContent(container);
                }
            });
        }, options);

        this.infiniteScrollContainers.forEach(container => {
            // Create and append sentinel element
            const sentinel = document.createElement('div');
            sentinel.className = 'infinite-scroll-sentinel';
            container.appendChild(sentinel);
            
            // Observe the sentinel
            observer.observe(sentinel);
        });
    }

    setupLoadMoreButtons() {
        if (!this.loadMoreButtons.length) {
            return;
        }

        this.loadMoreButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                const container = document.querySelector(button.dataset.container);
                if (!container) {
                    console.error('Container not found:', button.dataset.container);
                    return;
                }
                
                // Don't load if already loading
                if (container.classList.contains(this.loadingClass)) {
                    return;
                }
                
                this.loadMoreContent(container, button);
            });
        });
    }

    setupFilterForms() {
        if (!this.filterForms.length) {
            return;
        }

        this.filterForms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const container = document.querySelector(form.dataset.container);
                if (!container) {
                    console.error('Container not found:', form.dataset.container);
                    return;
                }
                
                this.filterContent(form, container);
            });
            
            // Handle auto-submit filters
            const autoSubmitInputs = form.querySelectorAll('input[data-auto-submit], select[data-auto-submit]');
            autoSubmitInputs.forEach(input => {
                const eventType = input.tagName === 'SELECT' ? 'change' : 'input';
                
                input.addEventListener(eventType, () => {
                    // Add small delay for better UX
                    clearTimeout(input.autoSubmitTimeout);
                    input.autoSubmitTimeout = setTimeout(() => {
                        form.dispatchEvent(new Event('submit'));
                    }, 500);
                });
            });
        });
    }

    setupAjaxSearch() {
        if (!this.ajaxSearchForms.length) {
            return;
        }

        this.ajaxSearchForms.forEach(form => {
            const input = form.querySelector('input[type="search"]');
            const resultsContainer = document.querySelector(form.dataset.results);
            
            if (!input || !resultsContainer) {
                return;
            }
            
            input.addEventListener('input', () => {
                clearTimeout(input.searchTimeout);
                
                // Don't search for empty or very short queries
                if (!input.value || input.value.length < 3) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.remove('has-results');
                    return;
                }
                
                input.searchTimeout = setTimeout(() => {
                    this.performSearch(input.value, resultsContainer);
                }, 500);
            });
        });
    }

    loadMoreContent(container, button = null) {
        // Get loading parameters
        const action = container.dataset.action || 'load_more_posts';
        const currentPage = parseInt(container.dataset.page || 1, 10);
        const maxPages = parseInt(container.dataset.maxPages || 0, 10);
        
        // Check if we've reached the max pages
        if (maxPages > 0 && currentPage >= maxPages) {
            if (button) {
                button.classList.add('disabled');
                button.textContent = button.dataset.noMoreText || 'No more content';
            }
            container.classList.add('all-loaded');
            return;
        }
        
        // Add loading state
        container.classList.add(this.loadingClass);
        if (button) {
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = button.dataset.loadingText || 'Loading...';
        }
        
        // Append loading indicator
        const loadingElement = document.createElement('div');
        loadingElement.className = 'loading-indicator';
        loadingElement.innerHTML = this.loadingTemplate;
        container.appendChild(loadingElement);
        
        // Prepare data
        const data = new FormData();
        data.append('action', action);
        data.append('nonce', this.nonce);
        data.append('page', currentPage + 1);
        
        // Add any custom data attributes
        for (const [key, value] of Object.entries(container.dataset)) {
            if (key !== 'action' && key !== 'page' && key !== 'maxPages' && key !== 'container') {
                data.append(key, value);
            }
        }
        
        // Make AJAX request
        fetch(this.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            // Remove loading indicator
            loadingElement.remove();
            
            if (response.success && response.data) {
                // Insert new content
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = response.data.html;
                
                // Append each child individually
                while (tempDiv.firstChild) {
                    container.insertBefore(tempDiv.firstChild, container.querySelector('.infinite-scroll-sentinel'));
                }
                
                // Update page counter
                container.dataset.page = currentPage + 1;
                
                // Check if we've reached the last page
                if (response.data.isLastPage || (maxPages > 0 && currentPage + 1 >= maxPages)) {
                    if (button) {
                        button.classList.add('disabled');
                        button.textContent = button.dataset.noMoreText || 'No more content';
                    }
                    container.classList.add('all-loaded');
                }
                
                // Trigger event for other scripts
                container.dispatchEvent(new CustomEvent('contentLoaded', {
                    detail: { newElements: response.data.html }
                }));
            } else {
                console.error('AJAX load failed:', response.data?.message || 'Unknown error');
                container.classList.add('ajax-error');
            }
        })
        .catch(error => {
            console.error('AJAX error:', error);
            container.classList.add('ajax-error');
            loadingElement.remove();
        })
        .finally(() => {
            // Reset loading state
            container.classList.remove(this.loadingClass);
            if (button && !container.classList.contains('all-loaded')) {
                button.disabled = false;
                button.textContent = button.dataset.originalText;
            }
        });
    }

    filterContent(form, container) {
        // Add loading state
        container.classList.add(this.loadingClass);
        
        // Append loading indicator
        const loadingElement = document.createElement('div');
        loadingElement.className = 'loading-indicator';
        loadingElement.innerHTML = this.loadingTemplate;
        container.appendChild(loadingElement);
        
        // Get form data
        const formData = new FormData(form);
        formData.append('action', form.dataset.action || 'filter_content');
        formData.append('nonce', this.nonce);
        
        // Make AJAX request
        fetch(this.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            // Remove loading indicator
            loadingElement.remove();
            
            if (response.success && response.data) {
                // Replace container content
                container.innerHTML = response.data.html;
                
                // Update URL if needed
                if (form.dataset.updateUrl && window.history && window.history.pushState) {
                    const url = new URL(window.location);
                    
                    // Clear existing parameters if needed
                    if (form.dataset.clearParams === 'true') {
                        for (const key of url.searchParams.keys()) {
                            url.searchParams.delete(key);
                        }
                    }
                    
                    // Add form parameters to URL
                    for (const [key, value] of formData.entries()) {
                        if (key !== 'action' && key !== 'nonce' && value) {
                            url.searchParams.set(key, value);
                        }
                    }
                    
                    window.history.pushState({}, '', url);
                }
                
                // Trigger event for other scripts
                container.dispatchEvent(new CustomEvent('contentFiltered', {
                    detail: { response: response.data }
                }));
            } else {
                console.error('AJAX filter failed:', response.data?.message || 'Unknown error');
                container.classList.add('ajax-error');
                
                // Show no results message if provided
                if (form.dataset.noResults) {
                    container.innerHTML = document.querySelector(form.dataset.noResults).innerHTML;
                } else {
                    container.innerHTML = '<p>No results found.</p>';
                }
            }
        })
        .catch(error => {
            console.error('AJAX error:', error);
            container.classList.add('ajax-error');
            loadingElement.remove();
        })
        .finally(() => {
            // Reset loading state
            container.classList.remove(this.loadingClass);
        });
    }

    performSearch(query, resultsContainer) {
        // Add loading state
        resultsContainer.classList.add(this.loadingClass);
        resultsContainer.innerHTML = this.loadingTemplate;
        
        // Prepare data
        const data = new FormData();
        data.append('action', 'ajax_search');
        data.append('nonce', this.nonce);
        data.append('query', query);
        
        // Make AJAX request
        fetch(this.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            if (response.success && response.data) {
                resultsContainer.innerHTML = response.data.html;
                resultsContainer.classList.add('has-results');
                
                // Trigger event for other scripts
                resultsContainer.dispatchEvent(new CustomEvent('searchResults', {
                    detail: { query: query, results: response.data }
                }));
            } else {
                resultsContainer.innerHTML = '<p>No results found.</p>';
                resultsContainer.classList.add('no-results');
            }
        })
        .catch(error => {
            console.error('AJAX search error:', error);
            resultsContainer.innerHTML = '<p>Error loading results.</p>';
            resultsContainer.classList.add('ajax-error');
        })
        .finally(() => {
            // Reset loading state
            resultsContainer.classList.remove(this.loadingClass);
        });
    }
}

export default AjaxLoader;