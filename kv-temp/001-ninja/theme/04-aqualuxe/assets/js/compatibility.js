/**
 * AquaLuxe Fish Compatibility Checker
 *
 * JavaScript functionality for the fish compatibility checker tool
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Fish Compatibility Checker
    function initCompatibilityChecker() {
        // Form submission
        $('.compatibility-checker-form').on('submit', function(e) {
            e.preventDefault();
            
            // Get selected fish species
            const selectedFish = [];
            $('input[name="fish_species[]"]:checked').each(function() {
                selectedFish.push($(this).val());
            });
            
            // Validate selection
            if (selectedFish.length < 2) {
                alert(aqualuxeCompatibility.i18n.select_fish);
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.compatibility-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCompatibility.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCompatibility.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_check_compatibility',
                    nonce: aqualuxeCompatibility.nonce,
                    fish_species: selectedFish
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                        
                        // Initialize tooltips for matrix cells
                        initMatrixTooltips();
                        
                        // Scroll to results
                        $('html, body').animate({
                            scrollTop: resultsContainer.offset().top - 50
                        }, 500);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCompatibility.i18n.error + '</p>');
                }
            });
        });
    }

    // Initialize tooltips for matrix cells
    function initMatrixTooltips() {
        $('.matrix-table td').not('.same-fish').hover(function() {
            const title = $(this).attr('title');
            if (title && title !== '') {
                $(this).data('tipText', title).removeAttr('title');
                $('<p class="tooltip"></p>')
                    .text(title)
                    .appendTo('body')
                    .css('top', ($(this).offset().top - 10) + 'px')
                    .css('left', ($(this).offset().left + 20) + 'px')
                    .fadeIn('slow');
            }
        }, function() {
            const title = $(this).data('tipText');
            if (title) {
                $(this).attr('title', title);
            }
            $('.tooltip').remove();
        }).mousemove(function(e) {
            $('.tooltip').css('top', (e.pageY - 10) + 'px')
                         .css('left', (e.pageX + 20) + 'px');
        });
    }

    // Fish selection enhancements
    function enhanceFishSelection() {
        // Search functionality
        $('#fish-search-input').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('.fish-selection-item').each(function() {
                const fishName = $(this).text().toLowerCase();
                if (fishName.indexOf(searchTerm) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Select/Deselect all buttons
        $('#select-all-fish').on('click', function(e) {
            e.preventDefault();
            $('.fish-selection-item:visible input[type="checkbox"]').prop('checked', true);
        });
        
        $('#deselect-all-fish').on('click', function(e) {
            e.preventDefault();
            $('.fish-selection-item input[type="checkbox"]').prop('checked', false);
        });
        
        // Category filter
        $('#fish-category-filter').on('change', function() {
            const category = $(this).val();
            
            if (category === '') {
                $('.fish-selection-item').show();
            } else {
                $('.fish-selection-item').each(function() {
                    if ($(this).data('category') === category) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
    }

    // Fish tabs functionality
    function initFishTabs() {
        $('.fish-species-tabs .tabs-nav li').on('click', function() {
            const tabId = $(this).data('tab');
            
            // Update active tab navigation
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            
            // Show selected tab content
            $(this).closest('.fish-species-tabs').find('.tab-pane').removeClass('active');
            $(this).closest('.fish-species-tabs').find('.tab-pane#' + tabId).addClass('active');
        });
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initCompatibilityChecker();
        enhanceFishSelection();
        initFishTabs();
    });

})(jQuery);