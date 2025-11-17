/**
 * Customizer JavaScript
 * 
 * @package AquaLuxe
 */

(function($) {
    'use strict';
    
    /**
     * Customizer functionality
     */
    wp.customize.bind('ready', function() {
        
        // Live preview for logo height
        wp.customize('aqualuxe_logo_height', function(value) {
            value.bind(function(newval) {
                $('.custom-logo img').css('height', newval + 'px');
            });
        });
        
        // Live preview for primary color
        wp.customize('aqualuxe_primary_color', function(value) {
            value.bind(function(newval) {
                // Update CSS custom properties or inject styles
                const style = `
                    <style id="aqualuxe-primary-color">
                        :root { --color-primary: ${newval}; }
                        .text-primary-600, .bg-primary-600 { background-color: ${newval} !important; }
                    </style>
                `;
                
                $('#aqualuxe-primary-color').remove();
                $('head').append(style);
            });
        });
        
        // Live preview for secondary color
        wp.customize('aqualuxe_secondary_color', function(value) {
            value.bind(function(newval) {
                const style = `
                    <style id="aqualuxe-secondary-color">
                        :root { --color-secondary: ${newval}; }
                        .text-secondary-600, .bg-secondary-600 { background-color: ${newval} !important; }
                    </style>
                `;
                
                $('#aqualuxe-secondary-color').remove();
                $('head').append(style);
            });
        });
        
        // Live preview for accent color
        wp.customize('aqualuxe_accent_color', function(value) {
            value.bind(function(newval) {
                const style = `
                    <style id="aqualuxe-accent-color">
                        :root { --color-accent: ${newval}; }
                        .text-accent-600, .bg-accent-600 { background-color: ${newval} !important; }
                    </style>
                `;
                
                $('#aqualuxe-accent-color').remove();
                $('head').append(style);
            });
        });
        
        // Live preview for base font size
        wp.customize('aqualuxe_base_font_size', function(value) {
            value.bind(function(newval) {
                $('body').css('font-size', newval + 'px');
            });
        });
        
        // Live preview for container width
        wp.customize('aqualuxe_container_width', function(value) {
            value.bind(function(newval) {
                $('.container').css('max-width', newval + 'px');
            });
        });
        
        // Live preview for sticky header
        wp.customize('aqualuxe_sticky_header', function(value) {
            value.bind(function(newval) {
                if (newval) {
                    $('.site-header').addClass('sticky top-0 z-40');
                } else {
                    $('.site-header').removeClass('sticky top-0 z-40');
                }
            });
        });
        
        // Live preview for header transparency
        wp.customize('aqualuxe_header_transparent', function(value) {
            value.bind(function(newval) {
                if (newval && $('body').hasClass('home')) {
                    $('.site-header').addClass('bg-transparent').removeClass('bg-white');
                } else {
                    $('.site-header').removeClass('bg-transparent').addClass('bg-white');
                }
            });
        });
        
        // Live preview for footer description
        wp.customize('aqualuxe_footer_description', function(value) {
            value.bind(function(newval) {
                $('.footer-brand p').text(newval);
            });
        });
        
        // Live preview for contact information
        wp.customize('aqualuxe_phone', function(value) {
            value.bind(function(newval) {
                $('[href^="tel:"]').attr('href', 'tel:' + newval).find('span, text').text(newval);
            });
        });
        
        wp.customize('aqualuxe_email', function(value) {
            value.bind(function(newval) {
                $('[href^="mailto:"]').attr('href', 'mailto:' + newval).find('span, text').text(newval);
            });
        });
        
        // Live preview for social media links
        const socialNetworks = ['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'pinterest'];
        
        socialNetworks.forEach(function(network) {
            wp.customize('aqualuxe_' + network + '_url', function(value) {
                value.bind(function(newval) {
                    const $link = $(`.fa-${network}`).closest('a');
                    
                    if (newval) {
                        $link.attr('href', newval).show();
                    } else {
                        $link.hide();
                    }
                });
            });
        });
        
        // Handle section focus
        wp.customize.section.each(function(section) {
            section.expanded.bind(function(isExpanded) {
                if (isExpanded) {
                    // Scroll to relevant section in preview
                    const sectionId = section.id;
                    
                    if (sectionId === 'aqualuxe_header') {
                        wp.customize.previewer.send('scroll-to-element', '.site-header');
                    } else if (sectionId === 'aqualuxe_footer') {
                        wp.customize.previewer.send('scroll-to-element', '.site-footer');
                    }
                }
            });
        });
    });
    
    // Handle preview scrolling
    wp.customize.bind('preview-ready', function() {
        wp.customize.preview.bind('scroll-to-element', function(selector) {
            const $element = $(selector);
            if ($element.length) {
                $('html, body').animate({
                    scrollTop: $element.offset().top
                }, 500);
            }
        });
    });
    
})(jQuery);