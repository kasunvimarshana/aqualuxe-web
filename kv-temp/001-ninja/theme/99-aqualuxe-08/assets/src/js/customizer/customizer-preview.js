// Customizer preview
(function($) {
    'use strict';
    
    wp.customize.bind('preview-ready', function() {
        
        // Site title
        wp.customize('blogname', function(setting) {
            setting.bind(function(value) {
                $('.site-title a').text(value);
            });
        });
        
        // Site description
        wp.customize('blogdescription', function(setting) {
            setting.bind(function(value) {
                $('.site-description').text(value);
            });
        });
        
        // Header background color
        wp.customize('header_background_color', function(setting) {
            setting.bind(function(value) {
                $('.site-header').css('background-color', value);
            });
        });
        
        // Primary color
        wp.customize('primary_color', function(setting) {
            setting.bind(function(value) {
                $('<style id="primary-color-style"></style>').remove();
                $('head').append(`
                    <style id="primary-color-style">
                        .btn-primary, .primary-bg { background-color: ${value} !important; }
                        .primary-color { color: ${value} !important; }
                        .primary-border { border-color: ${value} !important; }
                    </style>
                `);
            });
        });
        
        // Secondary color
        wp.customize('secondary_color', function(setting) {
            setting.bind(function(value) {
                $('<style id="secondary-color-style"></style>').remove();
                $('head').append(`
                    <style id="secondary-color-style">
                        .btn-secondary, .secondary-bg { background-color: ${value} !important; }
                        .secondary-color { color: ${value} !important; }
                        .secondary-border { border-color: ${value} !important; }
                    </style>
                `);
            });
        });
        
        // Typography
        wp.customize('typography_body_font', function(setting) {
            setting.bind(function(value) {
                $('body').css('font-family', value);
            });
        });
        
        wp.customize('typography_heading_font', function(setting) {
            setting.bind(function(value) {
                $('h1, h2, h3, h4, h5, h6').css('font-family', value);
            });
        });
        
        // Header layout
        wp.customize('header_layout', function(setting) {
            setting.bind(function(value) {
                $('.site-header').removeClass('layout-default layout-centered layout-minimal')
                                 .addClass('layout-' + value);
            });
        });
        
        // Footer widget areas
        wp.customize('footer_widget_areas', function(setting) {
            setting.bind(function(value) {
                $('.footer-widget-areas').removeClass('cols-1 cols-2 cols-3 cols-4')
                                          .addClass('cols-' + value);
            });
        });
        
        // Listen for custom events from controls
        wp.customize.previewer.bind('font-changed', function(data) {
            if (data.type === 'body') {
                $('body').css('font-family', data.font);
            } else if (data.type === 'heading') {
                $('h1, h2, h3, h4, h5, h6').css('font-family', data.font);
            }
        });
        
        wp.customize.previewer.bind('footer-widgets-changed', function(data) {
            $('.footer-widget-areas').removeClass('cols-1 cols-2 cols-3 cols-4')
                                      .addClass('cols-' + data.count);
        });
        
    });
    
})(jQuery);