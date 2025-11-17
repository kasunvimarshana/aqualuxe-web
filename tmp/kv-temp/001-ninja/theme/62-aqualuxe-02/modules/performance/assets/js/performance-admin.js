/**
 * AquaLuxe Performance Module Admin JavaScript
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Performance Admin
     */
    var AquaLuxePerformanceAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Clear cache button
            $('.aqualuxe-performance-clear-cache').on('click', this.clearCache);
            
            // Generate critical CSS button
            $('.aqualuxe-performance-generate-critical-css').on('click', this.generateCriticalCSS);
            
            // Toggle settings
            $('#aqualuxe_performance_enable_caching').on('change', this.toggleCachingSettings);
            $('#aqualuxe_performance_enable_minification').on('change', this.toggleMinificationSettings);
            $('#aqualuxe_performance_enable_lazy_loading').on('change', this.toggleLazyLoadingSettings);
            $('#aqualuxe_performance_enable_critical_css').on('change', this.toggleCriticalCSSSettings);
            $('#aqualuxe_performance_enable_preloading').on('change', this.togglePreloadingSettings);
            
            // Initialize toggles
            this.toggleCachingSettings();
            this.toggleMinificationSettings();
            this.toggleLazyLoadingSettings();
            this.toggleCriticalCSSSettings();
            this.togglePreloadingSettings();
        },

        /**
         * Clear cache
         */
        clearCache: function(e) {
            e.preventDefault();
            
            // Confirm action
            if (!confirm(aqualuxePerformance.i18n.confirm)) {
                return;
            }
            
            // Disable button
            var $button = $(this);
            $button.prop('disabled', true).text(aqualuxePerformance.i18n.clearingCache);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxePerformance.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_performance_clear_cache',
                    nonce: aqualuxePerformance.nonce
                },
                success: function(response) {
                    // Check if successful
                    if (response.success) {
                        // Show success message
                        alert(response.data.message);
                    } else {
                        // Show error message
                        alert(response.data.message || aqualuxePerformance.i18n.cacheClearError);
                    }
                    
                    // Enable button
                    $button.prop('disabled', false).text(aqualuxePerformance.i18n.clearCache);
                },
                error: function() {
                    // Show error message
                    alert(aqualuxePerformance.i18n.cacheClearError);
                    
                    // Enable button
                    $button.prop('disabled', false).text(aqualuxePerformance.i18n.clearCache);
                }
            });
        },

        /**
         * Generate critical CSS
         */
        generateCriticalCSS: function(e) {
            e.preventDefault();
            
            // Confirm action
            if (!confirm(aqualuxePerformance.i18n.confirm)) {
                return;
            }
            
            // Disable button
            var $button = $(this);
            $button.prop('disabled', true).text(aqualuxePerformance.i18n.generatingCriticalCss);
            
            // Send AJAX request
            $.ajax({
                url: aqualuxePerformance.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_performance_generate_critical_css',
                    nonce: aqualuxePerformance.nonce
                },
                success: function(response) {
                    // Check if successful
                    if (response.success) {
                        // Show success message
                        alert(response.data.message);
                        
                        // Update critical CSS textarea
                        if (response.data.critical_css) {
                            $('textarea[name="aqualuxe_performance_critical_css"]').val(response.data.critical_css);
                        }
                    } else {
                        // Show error message
                        alert(response.data.message || aqualuxePerformance.i18n.criticalCssError);
                    }
                    
                    // Enable button
                    $button.prop('disabled', false).text(aqualuxePerformance.i18n.generateCriticalCss);
                },
                error: function() {
                    // Show error message
                    alert(aqualuxePerformance.i18n.criticalCssError);
                    
                    // Enable button
                    $button.prop('disabled', false).text(aqualuxePerformance.i18n.generateCriticalCss);
                }
            });
        },

        /**
         * Toggle caching settings
         */
        toggleCachingSettings: function() {
            var enabled = $('#aqualuxe_performance_enable_caching').is(':checked');
            
            // Toggle dependent settings
            $('input[name="aqualuxe_performance_cache_expiration"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_enable_browser_caching"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_enable_gzip"]').closest('tr').toggle(enabled);
        },

        /**
         * Toggle minification settings
         */
        toggleMinificationSettings: function() {
            var enabled = $('#aqualuxe_performance_enable_minification').is(':checked');
            
            // Toggle dependent settings
            $('input[name="aqualuxe_performance_minify_html"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_minify_css"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_minify_js"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_combine_css"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_combine_js"]').closest('tr').toggle(enabled);
        },

        /**
         * Toggle lazy loading settings
         */
        toggleLazyLoadingSettings: function() {
            var enabled = $('#aqualuxe_performance_enable_lazy_loading').is(':checked');
            
            // Toggle dependent settings
            $('input[name="aqualuxe_performance_lazy_load_images"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_lazy_load_iframes"]').closest('tr').toggle(enabled);
            $('input[name="aqualuxe_performance_lazy_load_videos"]').closest('tr').toggle(enabled);
        },

        /**
         * Toggle critical CSS settings
         */
        toggleCriticalCSSSettings: function() {
            var enabled = $('#aqualuxe_performance_enable_critical_css').is(':checked');
            
            // Toggle dependent settings
            $('textarea[name="aqualuxe_performance_critical_css"]').closest('tr').toggle(enabled);
        },

        /**
         * Toggle preloading settings
         */
        togglePreloadingSettings: function() {
            var enabled = $('#aqualuxe_performance_enable_preloading').is(':checked');
            
            // Toggle dependent settings
            $('textarea[name="aqualuxe_performance_preload_fonts"]').closest('tr').toggle(enabled);
            $('textarea[name="aqualuxe_performance_preload_assets"]').closest('tr').toggle(enabled);
            $('textarea[name="aqualuxe_performance_dns_prefetch"]').closest('tr').toggle(enabled);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxePerformanceAdmin.init();
    });

})(jQuery);