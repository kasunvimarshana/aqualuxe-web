/**
 * AquaLuxe Customizer JavaScript
 * 
 * Handles WordPress Customizer functionality and live preview
 * 
 * @package AquaLuxe
 * @since 1.2.0
 */

(function($, wp) {
    'use strict';

    /**
     * Customizer JavaScript
     */
    const AquaLuxeCustomizer = {
        
        /**
         * Initialize customizer functionality
         */
        init: function() {
            this.bindEvents();
            this.setupColorControls();
            this.setupTypographyControls();
            this.setupLayoutControls();
            
            console.log('AquaLuxe Customizer: Initialized');
        },

        /**
         * Bind customizer events
         */
        bindEvents: function() {
            // Site title and tagline
            wp.customize('blogname', function(value) {
                value.bind(function(to) {
                    $('.site-title a').text(to);
                });
            });

            wp.customize('blogdescription', function(value) {
                value.bind(function(to) {
                    $('.site-description').text(to);
                });
            });

            // Header text color
            wp.customize('header_textcolor', function(value) {
                value.bind(function(to) {
                    if ('blank' === to) {
                        $('.site-title, .site-description').css({
                            'clip': 'rect(1px, 1px, 1px, 1px)',
                            'position': 'absolute'
                        });
                    } else {
                        $('.site-title, .site-description').css({
                            'clip': 'auto',
                            'position': 'relative',
                            'color': to
                        });
                    }
                });
            });
        },

        /**
         * Setup color controls
         */
        setupColorControls: function() {
            // Primary color
            wp.customize('aqualuxe_primary_color', function(value) {
                value.bind(function(to) {
                    $('body').get(0).style.setProperty('--color-primary-500', to);
                });
            });

            // Secondary color
            wp.customize('aqualuxe_secondary_color', function(value) {
                value.bind(function(to) {
                    $('body').get(0).style.setProperty('--color-secondary-500', to);
                });
            });

            // Accent color
            wp.customize('aqualuxe_accent_color', function(value) {
                value.bind(function(to) {
                    $('body').get(0).style.setProperty('--color-accent-500', to);
                });
            });

            // Background color
            wp.customize('background_color', function(value) {
                value.bind(function(to) {
                    $('body').css('background-color', '#' + to);
                });
            });
        },

        /**
         * Setup typography controls
         */
        setupTypographyControls: function() {
            // Primary font
            wp.customize('aqualuxe_primary_font', function(value) {
                value.bind(function(to) {
                    $('body').css('font-family', to);
                });
            });

            // Heading font
            wp.customize('aqualuxe_heading_font', function(value) {
                value.bind(function(to) {
                    $('h1, h2, h3, h4, h5, h6').css('font-family', to);
                });
            });

            // Font size
            wp.customize('aqualuxe_font_size', function(value) {
                value.bind(function(to) {
                    $('body').css('font-size', to + 'px');
                });
            });

            // Line height
            wp.customize('aqualuxe_line_height', function(value) {
                value.bind(function(to) {
                    $('body').css('line-height', to);
                });
            });
        },

        /**
         * Setup layout controls
         */
        setupLayoutControls: function() {
            // Container width
            wp.customize('aqualuxe_container_width', function(value) {
                value.bind(function(to) {
                    $('.container').css('max-width', to + 'px');
                });
            });

            // Header layout
            wp.customize('aqualuxe_header_layout', function(value) {
                value.bind(function(to) {
                    $('body').removeClass('header-layout-left header-layout-center header-layout-right')
                            .addClass('header-layout-' + to);
                });
            });

            // Sidebar position
            wp.customize('aqualuxe_sidebar_position', function(value) {
                value.bind(function(to) {
                    $('body').removeClass('sidebar-left sidebar-right sidebar-none')
                            .addClass('sidebar-' + to);
                });
            });

            // Footer columns
            wp.customize('aqualuxe_footer_columns', function(value) {
                value.bind(function(to) {
                    $('.footer-widgets').removeClass('footer-cols-1 footer-cols-2 footer-cols-3 footer-cols-4')
                                       .addClass('footer-cols-' + to);
                });
            });
        }
    };

    /**
     * Customizer panel enhancements
     */
    const CustomizerEnhancements = {
        init: function() {
            this.setupPanelIcons();
            this.setupConditionalControls();
            this.setupPresetButtons();
        },

        setupPanelIcons: function() {
            // Add icons to AquaLuxe panels
            $('#customize-theme-controls .control-section[id*="aqualuxe"]').each(function() {
                const $panel = $(this);
                const $title = $panel.find('.customize-section-title');
                $title.prepend('<span class="panel-icon">🌊</span> ');
            });
        },

        setupConditionalControls: function() {
            // Show/hide controls based on other settings
            wp.customize.bind('ready', function() {
                // Header display controls
                wp.customize('display_header_text', function(setting) {
                    wp.customize.control('header_textcolor', function(control) {
                        var toggle = function(visible) {
                            control.container.toggle(visible);
                        };
                        toggle(setting.get());
                        setting.bind(toggle);
                    });
                });

                // Custom logo controls
                wp.customize('custom_logo', function(setting) {
                    wp.customize.control('aqualuxe_logo_width', function(control) {
                        var toggle = function(logoId) {
                            control.container.toggle(!!logoId);
                        };
                        toggle(setting.get());
                        setting.bind(toggle);
                    });
                });
            });
        },

        setupPresetButtons: function() {
            // Add preset color scheme buttons
            if (wp.customize.control('aqualuxe_color_preset')) {
                wp.customize.control('aqualuxe_color_preset', function(control) {
                    control.container.on('click', '.preset-button', function() {
                        const preset = $(this).data('preset');
                        AquaLuxeCustomizer.applyColorPreset(preset);
                    });
                });
            }
        }
    };

    /**
     * Apply color preset
     */
    AquaLuxeCustomizer.applyColorPreset = function(preset) {
        const presets = {
            ocean: {
                primary: '#0ea5e9',
                secondary: '#06b6d4',
                accent: '#3b82f6'
            },
            coral: {
                primary: '#f97316',
                secondary: '#ef4444',
                accent: '#ec4899'
            },
            seaweed: {
                primary: '#22c55e',
                secondary: '#16a34a',
                accent: '#65a30d'
            },
            deep: {
                primary: '#1e40af',
                secondary: '#1e3a8a',
                accent: '#312e81'
            }
        };

        if (presets[preset]) {
            wp.customize('aqualuxe_primary_color').set(presets[preset].primary);
            wp.customize('aqualuxe_secondary_color').set(presets[preset].secondary);
            wp.customize('aqualuxe_accent_color').set(presets[preset].accent);
        }
    };

    // Initialize when customizer is ready
    wp.customize.bind('ready', function() {
        AquaLuxeCustomizer.init();
        CustomizerEnhancements.init();
    });

    // Initialize basic functionality immediately
    $(document).ready(function() {
        // Basic initialization that doesn't depend on wp.customize
        console.log('AquaLuxe Customizer: Document ready');
    });

    // Export to global scope
    window.AquaLuxeCustomizer = AquaLuxeCustomizer;

})(jQuery, wp || {});
