<?php
/**
 * Enqueue Scripts and Styles
 *
 * Optimized asset management with performance, security, and maintainability focus
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

use AquaLuxe\Core\AssetManager;

/**
 * Enqueue frontend scripts and styles with performance optimizations
 */
function aqualuxe_enqueue_scripts() {
    // Defer non-critical CSS
    add_filter('style_loader_tag', 'aqualuxe_defer_non_critical_css', 10, 4);
    
    // Main app styles with media attribute for non-critical CSS
    AssetManager::enqueue_style('aqualuxe-style', 'css/app.css');
    
    // Critical CSS inline for above-the-fold content
    aqualuxe_inline_critical_css();
    
    // Main app script with async loading for non-critical functionality
    AssetManager::enqueue_script('aqualuxe-script', 'js/app.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Add defer attribute to non-critical scripts
    add_filter('script_loader_tag', 'aqualuxe_defer_scripts', 10, 3);
    
    // Localize main script with enhanced security
    wp_localize_script('aqualuxe-script', 'aqualuxe', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => aqualuxe_create_nonce('ajax_request'),
        'theme_uri' => AQUALUXE_THEME_URI,
        'assets_uri' => AQUALUXE_ASSETS_URI,
        'is_rtl' => is_rtl(),
        'is_mobile' => wp_is_mobile(),
        'cache_version' => AQUALUXE_VERSION,
        'performance' => array(
            'lazy_load' => true,
            'preload_images' => get_theme_mod('aqualuxe_preload_images', true),
            'optimize_images' => get_theme_mod('aqualuxe_optimize_images', true),
        ),
        'strings' => array(
            'loading' => esc_html__('Loading...', 'aqualuxe'),
            'error' => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
            'success' => esc_html__('Success!', 'aqualuxe'),
            'close' => esc_html__('Close', 'aqualuxe'),
            'previous' => esc_html__('Previous', 'aqualuxe'),
            'next' => esc_html__('Next', 'aqualuxe'),
        ),
    ));

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Load page-specific assets
    aqualuxe_load_conditional_assets();
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Enqueue admin scripts and styles
 */
function aqualuxe_admin_enqueue_scripts($hook) {
    // Enqueue admin styles
    AssetManager::enqueue_style('aqualuxe-admin', 'css/admin.css');

    // Enqueue admin scripts
    AssetManager::enqueue_script('aqualuxe-admin', 'js/admin.js', array('jquery', 'jquery-ui-sortable'));

    // Localize admin script
    wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
        'strings' => array(
            'confirm_delete' => __('Are you sure you want to delete this item?', 'aqualuxe'),
            'processing' => __('Processing...', 'aqualuxe'),
            'error' => __('An error occurred. Please try again.', 'aqualuxe'),
            'success' => __('Action completed successfully.', 'aqualuxe'),
        ),
    ));

    // Enqueue customizer scripts
    if ($hook === 'customize.php') {
        AssetManager::enqueue_script('aqualuxe-customizer', 'js/customizer.js', array('jquery', 'customize-controls'));
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_enqueue_scripts');

/**
 * Enqueue customizer preview scripts
 */
function aqualuxe_customize_preview_js() {
    AssetManager::enqueue_script('aqualuxe-customizer-preview', 'js/customizer-preview.js', array('jquery', 'customize-preview'));
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue login page styles
 */
function aqualuxe_login_styles() {
    AssetManager::enqueue_style('aqualuxe-login', 'css/login.css');
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Conditionally load scripts based on page type and context
 */
function aqualuxe_conditional_scripts() {
    // Contact form scripts only on contact page
    AssetManager::enqueue_conditional_script(
        'aqualuxe-contact',
        'js/contact.js',
        function() { return is_page_template('templates/page-contact.php'); },
        array('jquery')
    );

    // WooCommerce specific scripts
    AssetManager::enqueue_conditional_script(
        'aqualuxe-woocommerce',
        'js/woocommerce.js',
        function() { return class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout()); },
        array('jquery')
    );

    // Product gallery scripts on single product pages
    AssetManager::enqueue_conditional_script(
        'aqualuxe-product-gallery',
        'js/product-gallery.js',
        function() { return is_product(); },
        array('jquery')
    );

    // Search scripts on search and archive pages
    AssetManager::enqueue_conditional_script(
        'aqualuxe-search',
        'js/search.js',
        function() { return is_search() || is_archive(); },
        array('jquery')
    );

    // Load Google Maps API if needed on contact page
    if (is_page_template('templates/page-contact.php')) {
        $google_maps_api_key = get_theme_mod('aqualuxe_google_maps_api_key', '');
        if (!empty($google_maps_api_key)) {
            wp_enqueue_script(
                'google-maps',
                'https://maps.googleapis.com/maps/api/js?key=' . esc_attr($google_maps_api_key) . '&callback=initMap',
                array(),
                null,
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_conditional_scripts', 20);

/**
 * Add async and defer attributes to scripts for performance
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Scripts to defer
    $defer_scripts = array(
        'aqualuxe-script',
        'aqualuxe-modules',
        'aqualuxe-analytics',
    );
    
    // Scripts to async  
    $async_scripts = array(
        'google-analytics',
        'gtag',
    );
    
    if (in_array($handle, $defer_scripts)) {
        $tag = str_replace('<script ', '<script defer ', $tag);
    }
    
    if (in_array($handle, $async_scripts)) {
        $tag = str_replace('<script ', '<script async ', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Add preload hints for critical resources
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ($relation_type === 'preload') {
        // Preload critical CSS
        if (AssetManager::asset_exists('css/app.css')) {
            $urls[] = array(
                'href' => AssetManager::get_asset_url('css/app.css'),
                'as' => 'style',
            );
        }
        
        // Preload critical fonts (if they exist)
        $critical_fonts = array(
            'fonts/inter-regular.woff2',
            'fonts/inter-medium.woff2',
            'fonts/inter-bold.woff2',
        );
        
        foreach ($critical_fonts as $font) {
            if (AssetManager::asset_exists($font)) {
                $urls[] = array(
                    'href' => AssetManager::get_asset_url($font),
                    'as' => 'font',
                    'type' => 'font/woff2',
                    'crossorigin' => 'anonymous',
                );
            }
        }
        
        // Preload service worker for PWA
        if (AssetManager::asset_exists('js/service-worker.js')) {
            $urls[] = array(
                'href' => AssetManager::get_asset_url('js/service-worker.js'),
                'as' => 'script',
            );
        }
    }
    
    if ($relation_type === 'prefetch') {
        // Prefetch non-critical pages
        $prefetch_pages = apply_filters('aqualuxe_prefetch_pages', array(
            '/about/',
            '/contact/',
            '/services/',
        ));
        
        foreach ($prefetch_pages as $page) {
            $urls[] = home_url($page);
        }
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);



/**
 * Remove unused default scripts and styles for performance
 * Consolidated to avoid duplication
 */
function aqualuxe_remove_default_scripts() {
    if (!is_admin()) {
        // Remove jQuery migrate in production (safer than deregistering jQuery entirely)
        if (!WP_DEBUG) {
            wp_deregister_script('jquery-migrate');
        }
        
        // Header cleanup (consolidated here to avoid duplication with security.php)
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Remove unnecessary REST API links from head
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
    }
}
add_action('init', 'aqualuxe_remove_default_scripts');

/**
 * Add PWA manifest and service worker registration
 */
function aqualuxe_add_pwa_features() {
    if (!is_admin()) {
        // Add PWA manifest
        echo '<link rel="manifest" href="' . esc_url(get_template_directory_uri() . '/manifest.json') . '">';
        
        // Add theme color for mobile browsers
        echo '<meta name="theme-color" content="#006B7D">';
        echo '<meta name="msapplication-TileColor" content="#006B7D">';
        
        // Add apple touch icons
        echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url(get_template_directory_uri() . '/assets/dist/images/apple-touch-icon.png') . '">';
        echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url(get_template_directory_uri() . '/assets/dist/images/favicon-32x32.png') . '">';
        echo '<link rel="icon" type="image/png" sizes="16x16" href="' . esc_url(get_template_directory_uri() . '/assets/dist/images/favicon-16x16.png') . '">';
        
        // Add iOS-specific meta tags
        echo '<meta name="apple-mobile-web-app-capable" content="yes">';
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">';
        echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr(get_bloginfo('name')) . '">';
        
        // Service worker registration script
        if (AssetManager::asset_exists('js/service-worker.js')) {
            ?>
            <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('<?php echo esc_url(AssetManager::get_asset_url('js/service-worker.js')); ?>')
                        .then(function(registration) {
                            <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                            console.log('AquaLuxe: Service Worker registered successfully:', registration.scope);
                            <?php endif; ?>
                        })
                        .catch(function(error) {
                            <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                            console.log('AquaLuxe: Service Worker registration failed:', error);
                            <?php endif; ?>
                        });
                });
            }
            </script>
            <?php
        }
    }
}
add_action('wp_head', 'aqualuxe_add_pwa_features');

/**
 * Add performance monitoring script
 */
function aqualuxe_add_performance_monitoring() {
    if (!is_admin() && !WP_DEBUG) {
        ?>
        <script>
        // Performance monitoring
        window.addEventListener('load', function() {
            // Monitor page load metrics
            if ('performance' in window && 'timing' in window.performance) {
                const timing = window.performance.timing;
                const loadTime = timing.loadEventEnd - timing.navigationStart;
                const domContentLoaded = timing.domContentLoadedEventEnd - timing.navigationStart;
                
                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'timing_complete', {
                        'name': 'load',
                        'value': Math.round(loadTime)
                    });
                    
                    gtag('event', 'timing_complete', {
                        'name': 'dom_content_loaded',
                        'value': Math.round(domContentLoaded)
                    });
                }
                
                // Log for development
                <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                console.log('AquaLuxe Performance:', {
                    'Page Load Time': loadTime + 'ms',
                    'DOM Content Loaded': domContentLoaded + 'ms'
                });
                <?php endif; ?>
            }
            
            // Monitor Core Web Vitals
            if ('PerformanceObserver' in window) {
                // Largest Contentful Paint
                new PerformanceObserver((list) => {
                    const entries = list.getEntries();
                    const lastEntry = entries[entries.length - 1];
                    <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                    console.log('LCP:', Math.round(lastEntry.startTime));
                    <?php endif; ?>
                }).observe({entryTypes: ['largest-contentful-paint']});
                
                // First Input Delay
                new PerformanceObserver((list) => {
                    const firstInput = list.getEntries()[0];
                    if (firstInput) {
                        const fid = firstInput.processingStart - firstInput.startTime;
                        <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                        console.log('FID:', Math.round(fid));
                        <?php endif; ?>
                    }
                }).observe({entryTypes: ['first-input']});
                
                // Cumulative Layout Shift
                let clsValue = 0;
                new PerformanceObserver((list) => {
                    for (const entry of list.getEntries()) {
                        if (!entry.hadRecentInput) {
                            clsValue += entry.value;
                        }
                    }
                    <?php if (defined('WP_DEBUG') && WP_DEBUG) : ?>
                    console.log('CLS:', clsValue);
                    <?php endif; ?>
                }).observe({entryTypes: ['layout-shift']});
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_performance_monitoring');

/**
 * Defer non-critical CSS loading
 */
function aqualuxe_defer_non_critical_css($html, $handle, $href, $media) {
    // List of critical CSS handles that should load immediately
    $critical_handles = apply_filters('aqualuxe_critical_css_handles', array(
        'aqualuxe-style'
    ));
    
    if (!in_array($handle, $critical_handles) && $media === 'all') {
        $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
        $html .= '<noscript>' . str_replace("media='print' onload=\"this.media='all'\"", "media='all'", $html) . '</noscript>';
    }
    
    return $html;
}

/**
 * Add defer attribute to non-critical scripts
 */
function aqualuxe_defer_scripts($tag, $handle, $src) {
    // List of scripts that should not be deferred
    $no_defer = apply_filters('aqualuxe_no_defer_scripts', array(
        'jquery-core',
        'jquery',
        'aqualuxe-script'
    ));
    
    if (!in_array($handle, $no_defer) && !is_admin()) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}

/**
 * Inline critical CSS for above-the-fold content
 */
function aqualuxe_inline_critical_css() {
    $critical_css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
    
    if (file_exists($critical_css_file)) {
        echo '<style id="aqualuxe-critical-css">';
        echo file_get_contents($critical_css_file);
        echo '</style>';
    }
}

/**
 * Load conditional assets based on page context
 */
function aqualuxe_load_conditional_assets() {
    // WooCommerce specific assets
    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        AssetManager::enqueue_script('aqualuxe-woocommerce', 'js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    }
    
    // Contact page assets
    if (is_page_template('page-contact.php') || is_page('contact')) {
        AssetManager::enqueue_script('aqualuxe-contact', 'js/contact.js', array('jquery'), AQUALUXE_VERSION, true);
    }
    
    // Search functionality
    if (is_search() || is_archive()) {
        AssetManager::enqueue_script('aqualuxe-search', 'js/search.js', array('jquery'), AQUALUXE_VERSION, true);
    }
    
    // Product gallery for single products
    if (is_product()) {
        AssetManager::enqueue_script('aqualuxe-product-gallery', 'js/product-gallery.js', array('jquery'), AQUALUXE_VERSION, true);
    }
}

/**
 * Optimize critical CSS delivery
 */
function aqualuxe_optimize_css_delivery() {
    // Add critical CSS inline
    AssetManager::inline_critical_css();
    
    // Add preload links for performance
    AssetManager::add_preload_links();
    
    // Add DNS prefetch hints
    AssetManager::add_dns_prefetch();
}
add_action('wp_head', 'aqualuxe_optimize_css_delivery', 1);