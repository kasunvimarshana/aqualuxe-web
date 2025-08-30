/**
 * AquaLuxe Care Guide JavaScript
 * 
 * Handles interactive elements for the fish care guide system
 */

(function($) {
    'use strict';

    // Care Guide Tabs
    function initCareTabs() {
        $('.care-guide-tabs .tabs-nav li').on('click', function() {
            const tabId = $(this).data('tab');
            
            // Update active tab
            $('.care-guide-tabs .tabs-nav li').removeClass('tab-active');
            $(this).addClass('tab-active');
            
            // Show selected tab content
            $('.care-guide-tabs .tab-content').removeClass('tab-active');
            $('#tab-' + tabId).addClass('tab-active');
        });
    }

    // Expandable Sections
    function initExpandableSections() {
        $('.expandable-section-header').on('click', function() {
            const $section = $(this).closest('.expandable-section');
            $section.toggleClass('expanded');
            
            const $content = $section.find('.expandable-section-content');
            if ($section.hasClass('expanded')) {
                $content.slideDown(300);
                $(this).find('.expand-icon').html('−');
            } else {
                $content.slideUp(300);
                $(this).find('.expand-icon').html('+');
            }
        });
    }

    // Tooltips
    function initTooltips() {
        $('.care-guide-tooltip').each(function() {
            const $tooltip = $(this);
            const $tooltipText = $tooltip.find('.tooltip-text');
            
            $tooltip.on('mouseenter', function() {
                $tooltipText.addClass('active');
                
                // Position tooltip to ensure it stays in viewport
                const tooltipRect = $tooltipText[0].getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                
                if (tooltipRect.right > viewportWidth) {
                    $tooltipText.css({
                        left: 'auto',
                        right: '0'
                    });
                }
            });
            
            $tooltip.on('mouseleave', function() {
                $tooltipText.removeClass('active');
            });
        });
    }

    // Care Guide Filter Form
    function initFilterForm() {
        // Auto-submit filter form when dropdown selection changes
        $('.care-guide-filter-form select').on('change', function() {
            if ($(this).val() !== '') {
                $(this).closest('form').submit();
            }
        });
    }

    // Care Guide Search
    function initCareGuideSearch() {
        const $searchInput = $('#care-guide-search-input');
        const $searchResults = $('#care-guide-search-results');
        let searchTimeout;

        $searchInput.on('keyup', function() {
            const searchTerm = $(this).val();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Set new timeout to prevent excessive AJAX requests
            searchTimeout = setTimeout(function() {
                if (searchTerm.length < 3) {
                    $searchResults.html('').hide();
                    return;
                }
                
                // Show loading indicator
                $searchResults.html('<div class="search-loading">Searching...</div>').show();
                
                // Send AJAX request
                $.ajax({
                    url: aqualuxe_care_guide.ajax_url,
                    type: 'post',
                    data: {
                        action: 'aqualuxe_search_care_guides',
                        security: aqualuxe_care_guide.nonce,
                        search_term: searchTerm
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.data.results.length > 0) {
                                let resultsHtml = '<ul class="search-results-list">';
                                
                                $.each(response.data.results, function(index, item) {
                                    resultsHtml += '<li class="search-result-item">';
                                    resultsHtml += '<a href="' + item.permalink + '">';
                                    
                                    if (item.thumbnail) {
                                        resultsHtml += '<div class="result-thumbnail">' + item.thumbnail + '</div>';
                                    }
                                    
                                    resultsHtml += '<div class="result-content">';
                                    resultsHtml += '<h4 class="result-title">' + item.title + '</h4>';
                                    
                                    if (item.excerpt) {
                                        resultsHtml += '<div class="result-excerpt">' + item.excerpt + '</div>';
                                    }
                                    
                                    resultsHtml += '</div>'; // .result-content
                                    resultsHtml += '</a>';
                                    resultsHtml += '</li>';
                                });
                                
                                resultsHtml += '</ul>';
                                
                                if (response.data.more_results) {
                                    resultsHtml += '<div class="more-results">';
                                    resultsHtml += '<a href="' + response.data.search_url + '" class="button view-all-results">View all results</a>';
                                    resultsHtml += '</div>';
                                }
                                
                                $searchResults.html(resultsHtml);
                            } else {
                                $searchResults.html('<div class="no-results">No care guides found matching your search.</div>');
                            }
                        } else {
                            $searchResults.html('<div class="search-error">Error searching care guides.</div>');
                        }
                    },
                    error: function() {
                        $searchResults.html('<div class="search-error">Error connecting to server.</div>');
                    }
                });
            }, 500);
        });
        
        // Close search results when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('.care-guide-search-container').length) {
                $searchResults.hide();
            }
        });
    }

    // Print Care Guide
    function initPrintFunctionality() {
        $('.print-care-guide').on('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }

    // Save Care Guide as PDF
    function initSavePDF() {
        $('.save-care-guide-pdf').on('click', function(e) {
            e.preventDefault();
            
            const postId = $(this).data('post-id');
            
            // Show loading indicator
            const $button = $(this);
            const originalText = $button.text();
            $button.text('Generating PDF...').prop('disabled', true);
            
            // Send AJAX request to generate PDF
            $.ajax({
                url: aqualuxe_care_guide.ajax_url,
                type: 'post',
                data: {
                    action: 'aqualuxe_generate_care_guide_pdf',
                    security: aqualuxe_care_guide.nonce,
                    post_id: postId
                },
                success: function(response) {
                    $button.text(originalText).prop('disabled', false);
                    
                    if (response.success && response.data.pdf_url) {
                        // Create temporary link and trigger download
                        const link = document.createElement('a');
                        link.href = response.data.pdf_url;
                        link.download = response.data.filename || 'care-guide.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        alert('Error generating PDF. Please try again.');
                    }
                },
                error: function() {
                    $button.text(originalText).prop('disabled', false);
                    alert('Error connecting to server. Please try again.');
                }
            });
        });
    }

    // Initialize all functionality when document is ready
    $(document).ready(function() {
        initCareTabs();
        initExpandableSections();
        initTooltips();
        initFilterForm();
        initCareGuideSearch();
        initPrintFunctionality();
        initSavePDF();
    });

})(jQuery);