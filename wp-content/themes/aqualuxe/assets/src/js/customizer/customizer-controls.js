// Customizer controls
(function($) {
    'use strict';
    
    wp.customize.bind('ready', function() {
        
        // Color scheme dependency
        wp.customize('color_scheme', function(setting) {
            setting.bind(function(value) {
                // Update dependent controls based on color scheme
                if (value === 'custom') {
                    $('.customize-control-primary_color').show();
                    $('.customize-control-secondary_color').show();
                } else {
                    $('.customize-control-primary_color').hide();
                    $('.customize-control-secondary_color').hide();
                }
            });
        });
        
        // Header layout dependency
        wp.customize('header_layout', function(setting) {
            setting.bind(function(value) {
                if (value === 'centered') {
                    $('.customize-control-header_search').show();
                } else {
                    $('.customize-control-header_search').hide();
                }
            });
        });
        
        // Typography controls
        wp.customize('typography_body_font', function(setting) {
            setting.bind(function(value) {
                // Update preview when font changes
                wp.customize.previewer.send('font-changed', {
                    type: 'body',
                    font: value
                });
            });
        });
        
        wp.customize('typography_heading_font', function(setting) {
            setting.bind(function(value) {
                wp.customize.previewer.send('font-changed', {
                    type: 'heading',
                    font: value
                });
            });
        });
        
        // Footer widget areas
        wp.customize('footer_widget_areas', function(setting) {
            setting.bind(function(value) {
                wp.customize.previewer.send('footer-widgets-changed', {
                    count: value
                });
            });
        });
        
    });
    
})(jQuery);