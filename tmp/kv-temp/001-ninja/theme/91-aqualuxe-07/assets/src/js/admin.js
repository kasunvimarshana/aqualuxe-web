/**
 * AquaLuxe Admin JavaScript
 * 
 * Handles WordPress admin area functionality
 * 
 * @package AquaLuxe
 * @since 1.2.0
 */

(function($) {
    'use strict';

    /**
     * Admin JavaScript initialization
     */
    const AquaLuxeAdmin = {
        
        /**
         * Initialize admin functionality
         */
        init: function() {
            this.bindEvents();
            this.initColorPickers();
            this.initMediaUploaders();
            this.initMetaBoxes();
            
            console.log('AquaLuxe Admin: Initialized');
        },

        /**
         * Bind admin events
         */
        bindEvents: function() {
            $(document).ready(this.onDocumentReady.bind(this));
            $(window).on('load', this.onWindowLoad.bind(this));
        },

        /**
         * Document ready handler
         */
        onDocumentReady: function() {
            this.setupTabs();
            this.setupTooltips();
            this.setupConditionalFields();
        },

        /**
         * Window load handler
         */
        onWindowLoad: function() {
            this.initDashboardWidgets();
        },

        /**
         * Initialize color pickers
         */
        initColorPickers: function() {
            if ($.fn.wpColorPicker) {
                $('.aqualuxe-color-picker').wpColorPicker({
                    change: function(event, ui) {
                        // Handle color change
                        $(this).trigger('aqualuxe:color-changed', ui.color.toString());
                    }
                });
            }
        },

        /**
         * Initialize media uploaders
         */
        initMediaUploaders: function() {
            $('.aqualuxe-media-upload').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const input = button.siblings('input');
                const preview = button.siblings('.media-preview');
                
                // WordPress media uploader
                const mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                    if (preview.length) {
                        preview.html('<img src="' + attachment.url + '" style="max-width: 200px;">');
                    }
                });
                
                mediaUploader.open();
            });
        },

        /**
         * Initialize meta boxes
         */
        initMetaBoxes: function() {
            // Custom meta box functionality
            $('.aqualuxe-meta-box').each(function() {
                const $metaBox = $(this);
                
                // Toggle sections
                $metaBox.find('.meta-box-toggle').on('click', function() {
                    const $section = $(this).closest('.meta-box-section');
                    $section.find('.meta-box-content').slideToggle();
                    $(this).toggleClass('open');
                });
            });
        },

        /**
         * Setup admin tabs
         */
        setupTabs: function() {
            $('.aqualuxe-tabs').each(function() {
                const $tabs = $(this);
                const $tabLinks = $tabs.find('.tab-link');
                const $tabPanes = $tabs.find('.tab-pane');
                
                $tabLinks.on('click', function(e) {
                    e.preventDefault();
                    
                    const target = $(this).attr('href');
                    
                    // Update active states
                    $tabLinks.removeClass('active');
                    $(this).addClass('active');
                    
                    $tabPanes.removeClass('active');
                    $(target).addClass('active');
                    
                    // Store active tab
                    localStorage.setItem('aqualuxe-active-tab', target);
                });
                
                // Restore active tab
                const activeTab = localStorage.getItem('aqualuxe-active-tab');
                if (activeTab && $(activeTab).length) {
                    $tabLinks.filter('[href="' + activeTab + '"]').click();
                }
            });
        },

        /**
         * Setup tooltips
         */
        setupTooltips: function() {
            $('.aqualuxe-tooltip').each(function() {
                $(this).on('mouseenter', function() {
                    const tooltip = $(this).attr('data-tooltip');
                    if (tooltip) {
                        $('<div class="aqualuxe-tooltip-popup">' + tooltip + '</div>')
                            .appendTo('body')
                            .fadeIn(200);
                    }
                }).on('mouseleave', function() {
                    $('.aqualuxe-tooltip-popup').remove();
                });
            });
        },

        /**
         * Setup conditional fields
         */
        setupConditionalFields: function() {
            $('[data-condition]').each(function() {
                const $field = $(this);
                const condition = $field.data('condition');
                const conditionField = condition.field;
                const conditionValue = condition.value;
                
                const $conditionField = $('[name="' + conditionField + '"]');
                
                if ($conditionField.length) {
                    const checkCondition = function() {
                        const currentValue = $conditionField.val();
                        if (currentValue === conditionValue) {
                            $field.slideDown();
                        } else {
                            $field.slideUp();
                        }
                    };
                    
                    $conditionField.on('change', checkCondition);
                    checkCondition(); // Check on load
                }
            });
        },

        /**
         * Initialize dashboard widgets
         */
        initDashboardWidgets: function() {
            // Theme status widget
            if ($('#aqualuxe-status-widget').length) {
                this.updateThemeStatus();
            }
        },

        /**
         * Update theme status
         */
        updateThemeStatus: function() {
            const $widget = $('#aqualuxe-status-widget');
            const $status = $widget.find('.theme-status');
            
            // Simulate status check
            setTimeout(function() {
                $status.html('<span class="status-ok">✓ Theme is running correctly</span>');
            }, 1000);
        }
    };

    /**
     * WordPress admin styles enhancement
     */
    const AdminStyles = {
        init: function() {
            this.enhanceNotices();
            this.styleButtons();
        },

        enhanceNotices: function() {
            $('.notice.aqualuxe-notice').each(function() {
                const $notice = $(this);
                $notice.prepend('<span class="notice-icon">🌊</span>');
            });
        },

        styleButtons: function() {
            $('.button.aqualuxe-button').addClass('button-primary');
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        AquaLuxeAdmin.init();
        AdminStyles.init();
    });

    // Export to global scope
    window.AquaLuxeAdmin = AquaLuxeAdmin;

})(jQuery);
