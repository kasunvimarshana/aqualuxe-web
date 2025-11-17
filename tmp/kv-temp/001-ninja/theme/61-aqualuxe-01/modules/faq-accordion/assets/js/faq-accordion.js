/**
 * AquaLuxe Theme - FAQ Accordion Module JavaScript
 */

(function($) {
    'use strict';

    // FAQ Accordion Module
    const AquaLuxeFaqAccordion = {
        /**
         * Default settings
         */
        defaults: {
            animationSpeed: 300,
            collapsible: true,
            allowMultiple: false
        },

        /**
         * Initialize the FAQ accordion functionality
         */
        init: function() {
            // Get settings from localized script
            this.settings = $.extend({}, this.defaults, window.aqualuxeFaqAccordion || {});
            
            // Initialize all accordions
            this.initAccordions();
            
            // Bind events
            this.bindEvents();
            
            // Handle deep linking
            this.handleDeepLinking();
        },

        /**
         * Initialize all accordions
         */
        initAccordions: function() {
            $('.aqualuxe-faq-accordion').each(function(index, accordion) {
                AquaLuxeFaqAccordion.setupAccordion($(accordion));
            });
        },

        /**
         * Setup a single accordion
         * 
         * @param {jQuery} $accordion - The accordion element
         */
        setupAccordion: function($accordion) {
            // Add data attributes
            $accordion.attr('data-collapsible', this.settings.collapsible);
            $accordion.attr('data-allow-multiple', this.settings.allowMultiple);
            
            // Set up initial state
            const $items = $accordion.find('.aqualuxe-faq-item');
            
            // If no items are active and we don't allow all to be closed, activate the first one
            if (!this.settings.collapsible && $items.filter('.aqualuxe-faq-item-active').length === 0 && $items.length > 0) {
                this.activateItem($items.first());
            }
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Toggle item on click
            $(document).on('click', '.aqualuxe-faq-toggle', function(e) {
                e.preventDefault();
                
                const $toggle = $(this);
                const $item = $toggle.closest('.aqualuxe-faq-item');
                const $accordion = $item.closest('.aqualuxe-faq-accordion');
                
                AquaLuxeFaqAccordion.toggleItem($item, $accordion);
            });
            
            // Handle keyboard navigation
            $(document).on('keydown', '.aqualuxe-faq-toggle', function(e) {
                const $toggle = $(this);
                const $item = $toggle.closest('.aqualuxe-faq-item');
                const $accordion = $item.closest('.aqualuxe-faq-accordion');
                const $items = $accordion.find('.aqualuxe-faq-item');
                const $toggles = $items.find('.aqualuxe-faq-toggle');
                const currentIndex = $toggles.index($toggle);
                
                // Handle keyboard navigation
                switch (e.keyCode) {
                    case 13: // Enter
                    case 32: // Space
                        e.preventDefault();
                        AquaLuxeFaqAccordion.toggleItem($item, $accordion);
                        break;
                        
                    case 38: // Up arrow
                        e.preventDefault();
                        if (currentIndex > 0) {
                            $toggles.eq(currentIndex - 1).focus();
                        }
                        break;
                        
                    case 40: // Down arrow
                        e.preventDefault();
                        if (currentIndex < $toggles.length - 1) {
                            $toggles.eq(currentIndex + 1).focus();
                        }
                        break;
                        
                    case 36: // Home
                        e.preventDefault();
                        $toggles.first().focus();
                        break;
                        
                    case 35: // End
                        e.preventDefault();
                        $toggles.last().focus();
                        break;
                }
            });
        },

        /**
         * Toggle an accordion item
         * 
         * @param {jQuery} $item - The item to toggle
         * @param {jQuery} $accordion - The parent accordion
         */
        toggleItem: function($item, $accordion) {
            const isActive = $item.hasClass('aqualuxe-faq-item-active');
            const allowMultiple = $accordion.attr('data-allow-multiple') === 'true';
            const collapsible = $accordion.attr('data-collapsible') === 'true';
            
            // If this is active and we don't allow all to be closed, do nothing
            if (isActive && !collapsible) {
                return;
            }
            
            // If we don't allow multiple open items, close all others
            if (!allowMultiple) {
                this.deactivateAllItems($accordion, $item);
            }
            
            // Toggle this item
            if (isActive) {
                this.deactivateItem($item);
            } else {
                this.activateItem($item);
            }
        },

        /**
         * Activate an accordion item
         * 
         * @param {jQuery} $item - The item to activate
         */
        activateItem: function($item) {
            const $toggle = $item.find('.aqualuxe-faq-toggle');
            const $answer = $item.find('.aqualuxe-faq-answer');
            
            // Update classes and attributes
            $item.addClass('aqualuxe-faq-item-active');
            $toggle.attr('aria-expanded', 'true');
            
            // Show the answer with animation
            if ($answer.is('[hidden]')) {
                $answer.removeAttr('hidden').hide().slideDown(this.settings.animationSpeed);
            }
            
            // Trigger custom event
            $item.trigger('aqualuxe.faq.opened');
        },

        /**
         * Deactivate an accordion item
         * 
         * @param {jQuery} $item - The item to deactivate
         */
        deactivateItem: function($item) {
            const $toggle = $item.find('.aqualuxe-faq-toggle');
            const $answer = $item.find('.aqualuxe-faq-answer');
            
            // Update classes and attributes
            $item.removeClass('aqualuxe-faq-item-active');
            $toggle.attr('aria-expanded', 'false');
            
            // Hide the answer with animation
            $answer.slideUp(this.settings.animationSpeed, function() {
                $answer.attr('hidden', '');
            });
            
            // Trigger custom event
            $item.trigger('aqualuxe.faq.closed');
        },

        /**
         * Deactivate all items in an accordion
         * 
         * @param {jQuery} $accordion - The accordion
         * @param {jQuery} $excludeItem - Optional item to exclude
         */
        deactivateAllItems: function($accordion, $excludeItem) {
            const self = this;
            
            $accordion.find('.aqualuxe-faq-item-active').each(function() {
                const $item = $(this);
                
                // Skip the excluded item
                if ($excludeItem && $item.is($excludeItem)) {
                    return;
                }
                
                self.deactivateItem($item);
            });
        },

        /**
         * Handle deep linking to specific FAQ items
         */
        handleDeepLinking: function() {
            // Check if we have a hash in the URL
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                const $targetQuestion = $('#' + hash + '-question');
                
                if ($targetQuestion.length) {
                    const $item = $targetQuestion.closest('.aqualuxe-faq-item');
                    const $accordion = $item.closest('.aqualuxe-faq-accordion');
                    
                    // Activate the item
                    this.activateItem($item);
                    
                    // Scroll to the item
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $targetQuestion.offset().top - 100
                        }, 500);
                    }, 300);
                }
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeFaqAccordion.init();
    });

})(jQuery);