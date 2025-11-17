<?php
/**
 * AquaLuxe Assets Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Assets class
 * 
 * Handles the registration and enqueuing of assets for the theme
 */
class AquaLuxe_Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Register and enqueue frontend assets
        add_action('wp_enqueue_scripts', array($this, 'register_styles'), 5);
        add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 5);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 10);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 10);
        
        // Register and enqueue admin assets
        add_action('admin_enqueue_scripts', array($this, 'register_admin_styles'), 5);
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'), 5);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'), 10);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'), 10);
        
        // Block editor assets
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
        
        // Script optimization
        add_filter('script_loader_tag', array($this, 'add_script_attributes'), 10, 3);
        
        // Resource hints and preloading
        add_action('wp_head', array($this, 'preload_assets'), 2);
        add_filter('wp_resource_hints', array($this, 'resource_hints'), 10, 2);
        
        // Dark mode support
        add_action('wp_head', array($this, 'add_dark_mode_support'), 1);
        
        // Remove unnecessary assets
        add_action('wp_enqueue_scripts', array($this, 'dequeue_unnecessary_assets'), 100);
    }

    /**
     * Register styles
     */
    public function register_styles() {
        // Main stylesheet
        wp_register_style(
            'aqualuxe-main',
            $this->get_asset_url('css/main.css'),
            array(),
            $this->get_asset_version('css/main.css'),
            'all'
        );

        // Critical CSS (above the fold)
        wp_register_style(
            'aqualuxe-critical',
            $this->get_asset_url('css/critical.css'),
            array(),
            $this->get_asset_version('css/critical.css'),
            'all'
        );
        
        // Mobile-specific styles
        wp_register_style(
            'aqualuxe-mobile',
            $this->get_asset_url('css/mobile.css'),
            array('aqualuxe-main'),
            $this->get_asset_version('css/mobile.css'),
            'all'
        );

        // Blog specific styles
        wp_register_style(
            'aqualuxe-blog',
            $this->get_asset_url('css/blog.css'),
            array('aqualuxe-main'),
            $this->get_asset_version('css/blog.css'),
            'all'
        );

        // WooCommerce styles
        if (class_exists('WooCommerce')) {
            wp_register_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                array('aqualuxe-main'),
                $this->get_asset_version('css/woocommerce.css'),
                'all'
            );
            
            // Product specific styles
            wp_register_style(
                'aqualuxe-product',
                $this->get_asset_url('css/product.css'),
                array('aqualuxe-woocommerce'),
                $this->get_asset_version('css/product.css'),
                'all'
            );
            
            // Cart and checkout styles
            wp_register_style(
                'aqualuxe-cart-checkout',
                $this->get_asset_url('css/cart-checkout.css'),
                array('aqualuxe-woocommerce'),
                $this->get_asset_version('css/cart-checkout.css'),
                'all'
            );
        }

        // Google Fonts
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
        $body_font = get_theme_mod('aqualuxe_body_font', 'Montserrat');
        
        $fonts = array($heading_font, $body_font);
        $fonts = array_unique($fonts);
        
        $google_fonts = $this->get_google_fonts_url($fonts);
        
        if ($google_fonts) {
            wp_register_style(
                'aqualuxe-google-fonts',
                $google_fonts,
                array(),
                AQUALUXE_VERSION,
                'all'
            );
        }
        
        // Print styles
        wp_register_style(
            'aqualuxe-print',
            $this->get_asset_url('css/print.css'),
            array(),
            $this->get_asset_version('css/print.css'),
            'print'
        );
    }

    /**
     * Register scripts
     */
    public function register_scripts() {
        // Register vendors first (third-party libraries)
        wp_register_script(
            'aqualuxe-vendors',
            $this->get_asset_url('js/vendors.js'),
            array(),
            $this->get_asset_version('js/vendors.js'),
            true
        );
        
        // Dark mode script - load in header to prevent FOUC
        wp_register_script(
            'aqualuxe-dark-mode',
            $this->get_asset_url('js/dark-mode.js'),
            array(),
            $this->get_asset_version('js/dark-mode.js'),
            false // Load in header
        );

        // Main script
        wp_register_script(
            'aqualuxe-main',
            $this->get_asset_url('js/main.js'),
            array('aqualuxe-vendors'),
            $this->get_asset_version('js/main.js'),
            true
        );
        
        // Lazy loading script
        wp_register_script(
            'aqualuxe-lazy-load',
            $this->get_asset_url('js/lazy-load.js'),
            array('aqualuxe-main'),
            $this->get_asset_version('js/lazy-load.js'),
            true
        );
        
        // Mobile navigation script
        wp_register_script(
            'aqualuxe-mobile-nav',
            $this->get_asset_url('js/mobile-nav.js'),
            array('aqualuxe-main'),
            $this->get_asset_version('js/mobile-nav.js'),
            true
        );

        // Blog specific scripts
        wp_register_script(
            'aqualuxe-blog',
            $this->get_asset_url('js/blog.js'),
            array('aqualuxe-main'),
            $this->get_asset_version('js/blog.js'),
            true
        );

        // WooCommerce scripts
        if (class_exists('WooCommerce')) {
            // General WooCommerce scripts
            wp_register_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url('js/woocommerce.js'),
                array('aqualuxe-main'),
                $this->get_asset_version('js/woocommerce.js'),
                true
            );
            
            // Product specific scripts
            wp_register_script(
                'aqualuxe-product',
                $this->get_asset_url('js/product.js'),
                array('aqualuxe-woocommerce'),
                $this->get_asset_version('js/product.js'),
                true
            );
            
            // Cart and checkout scripts
            wp_register_script(
                'aqualuxe-cart-checkout',
                $this->get_asset_url('js/cart-checkout.js'),
                array('aqualuxe-woocommerce'),
                $this->get_asset_version('js/cart-checkout.js'),
                true
            );
        }
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Critical CSS - always load
        wp_enqueue_style('aqualuxe-critical');
        
        // Google Fonts - always load
        wp_enqueue_style('aqualuxe-google-fonts');
        
        // Main stylesheet - always load
        wp_enqueue_style('aqualuxe-main');
        
        // Mobile styles - always load
        wp_enqueue_style('aqualuxe-mobile');
        
        // Print styles - always load
        wp_enqueue_style('aqualuxe-print');
        
        // Blog specific styles - conditionally load
        if (is_home() || is_archive() || is_search() || is_singular('post')) {
            wp_enqueue_style('aqualuxe-blog');
        }
        
        // WooCommerce styles - conditionally load
        if (class_exists('WooCommerce')) {
            // General WooCommerce styles
            if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
                wp_enqueue_style('aqualuxe-woocommerce');
            }
            
            // Product specific styles
            if (is_product()) {
                wp_enqueue_style('aqualuxe-product');
            }
            
            // Cart and checkout styles
            if (is_cart() || is_checkout()) {
                wp_enqueue_style('aqualuxe-cart-checkout');
            }
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Vendors - always load
        wp_enqueue_script('aqualuxe-vendors');
        
        // Dark mode script - always load
        wp_enqueue_script('aqualuxe-dark-mode');
        
        // Main script - always load
        wp_enqueue_script('aqualuxe-main');
        
        // Lazy loading - always load
        wp_enqueue_script('aqualuxe-lazy-load');
        
        // Mobile navigation - always load
        wp_enqueue_script('aqualuxe-mobile-nav');
        
        // Blog specific scripts - conditionally load
        if (is_home() || is_archive() || is_search() || is_singular('post')) {
            wp_enqueue_script('aqualuxe-blog');
        }
        
        // WooCommerce scripts - conditionally load
        if (class_exists('WooCommerce')) {
            // General WooCommerce scripts
            if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
                wp_enqueue_script('aqualuxe-woocommerce');
            }
            
            // Product specific scripts
            if (is_product()) {
                wp_enqueue_script('aqualuxe-product');
            }
            
            // Cart and checkout scripts
            if (is_cart() || is_checkout()) {
                wp_enqueue_script('aqualuxe-cart-checkout');
            }
        }
        
        // Comment reply script - conditionally load
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Localize script
        wp_localize_script(
            'aqualuxe-main',
            'aqualuxeData',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-nonce'),
                'homeUrl' => home_url('/'),
                'isLoggedIn' => is_user_logged_in(),
                'darkModeDefault' => get_theme_mod('aqualuxe_dark_mode_default', false),
                'lazyLoad' => get_theme_mod('aqualuxe_lazy_loading', true),
                'currency' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$',
                'i18n' => array(
                    'addToCart' => __('Add to Cart', 'aqualuxe'),
                    'viewCart' => __('View Cart', 'aqualuxe'),
                    'addedToCart' => __('Added to Cart', 'aqualuxe'),
                    'addToWishlist' => __('Add to Wishlist', 'aqualuxe'),
                    'removeFromWishlist' => __('Remove from Wishlist', 'aqualuxe'),
                    'addedToWishlist' => __('Added to Wishlist', 'aqualuxe'),
                    'removedFromWishlist' => __('Removed from Wishlist', 'aqualuxe'),
                    'compare' => __('Compare', 'aqualuxe'),
                    'quickView' => __('Quick View', 'aqualuxe'),
                    'loading' => __('Loading...', 'aqualuxe'),
                    'error' => __('Error', 'aqualuxe'),
                    'success' => __('Success', 'aqualuxe'),
                ),
            )
        );
    }

    /**
     * Register admin styles
     */
    public function register_admin_styles() {
        // Admin stylesheet
        wp_register_style(
            'aqualuxe-admin',
            $this->get_asset_url('css/admin.css'),
            array(),
            $this->get_asset_version('css/admin.css'),
            'all'
        );
    }

    /**
     * Register admin scripts
     */
    public function register_admin_scripts() {
        // Admin script
        wp_register_script(
            'aqualuxe-admin',
            $this->get_asset_url('js/admin.js'),
            array('jquery', 'wp-color-picker'),
            $this->get_asset_version('js/admin.js'),
            true
        );
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles($hook) {
        // Admin stylesheet - only load on specific admin pages
        $admin_pages = array(
            'toplevel_page_aqualuxe-options',
            'aqualuxe_page_aqualuxe-theme-options',
            'post.php',
            'post-new.php',
            'edit.php',
        );
        
        if (in_array($hook, $admin_pages) || strpos($hook, 'aqualuxe') !== false) {
            wp_enqueue_style('aqualuxe-admin');
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        // Admin script - only load on specific admin pages
        $admin_pages = array(
            'toplevel_page_aqualuxe-options',
            'aqualuxe_page_aqualuxe-theme-options',
            'post.php',
            'post-new.php',
            'edit.php',
        );
        
        if (in_array($hook, $admin_pages) || strpos($hook, 'aqualuxe') !== false) {
            wp_enqueue_script('aqualuxe-admin');
            
            // Localize script
            wp_localize_script(
                'aqualuxe-admin',
                'aqualuxeAdminData',
                array(
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
                )
            );
        }
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets() {
        // Editor stylesheet
        wp_enqueue_style(
            'aqualuxe-editor-style',
            $this->get_asset_url('css/editor-style.css'),
            array(),
            $this->get_asset_version('css/editor-style.css'),
            'all'
        );
        
        // Editor script
        wp_enqueue_script(
            'aqualuxe-editor-script',
            $this->get_asset_url('js/editor.js'),
            array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
            $this->get_asset_version('js/editor.js'),
            true
        );
    }

    /**
     * Add script attributes
     *
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @param string $src    The script source.
     * @return string
     */
    public function add_script_attributes($tag, $handle, $src) {
        // Add defer attribute to main script
        if ('aqualuxe-main' === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        // Add async attribute to lazy load script
        if ('aqualuxe-lazy-load' === $handle) {
            return str_replace(' src', ' async src', $tag);
        }
        
        // Add defer attribute to vendor scripts
        if ('aqualuxe-vendors' === $handle) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }

    /**
     * Preload assets
     */
    public function preload_assets() {
        // Only preload if enabled in theme options
        if (!get_theme_mod('aqualuxe_preload_resources', true)) {
            return;
        }
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/critical.css')) . '" as="style">' . "\n";
        
        // Preload main CSS
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/main.css')) . '" as="style">' . "\n";
        
        // Preload dark mode JS (important to prevent FOUC)
        echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/dark-mode.js')) . '" as="script">' . "\n";
        
        // Conditionally preload other assets based on page type
        if (is_home() || is_archive() || is_search() || is_singular('post')) {
            echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/blog.css')) . '" as="style">' . "\n";
            echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/blog.js')) . '" as="script">' . "\n";
        }
        
        if (class_exists('WooCommerce') && is_woocommerce()) {
            echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/woocommerce.css')) . '" as="style">' . "\n";
            echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/woocommerce.js')) . '" as="script">' . "\n";
            
            if (is_product()) {
                echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/product.css')) . '" as="style">' . "\n";
                echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/product.js')) . '" as="script">' . "\n";
            }
            
            if (is_cart() || is_checkout()) {
                echo '<link rel="preload" href="' . esc_url($this->get_asset_url('css/cart-checkout.css')) . '" as="style">' . "\n";
                echo '<link rel="preload" href="' . esc_url($this->get_asset_url('js/cart-checkout.js')) . '" as="script">' . "\n";
            }
        }
    }

    /**
     * Add resource hints
     *
     * @param array  $urls          URLs to print for resource hints.
     * @param string $relation_type The relation type the URLs are printed for.
     * @return array
     */
    public function resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            // Add Google Fonts domain
            $urls[] = array(
                'href' => 'https://fonts.googleapis.com',
                'crossorigin',
            );
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
            
            // Add Google Analytics if enabled
            if (get_theme_mod('aqualuxe_google_analytics_id', '')) {
                $urls[] = array(
                    'href' => 'https://www.google-analytics.com',
                    'crossorigin',
                );
            }
        }
        
        return $urls;
    }

    /**
     * Add dark mode support
     */
    public function add_dark_mode_support() {
        // Add class to html element based on default setting to prevent FOUC
        $dark_mode_default = get_theme_mod('aqualuxe_dark_mode_default', false);
        $prefers_dark = $dark_mode_default ? 'true' : 'false';
        
        echo '<script>
            // Add dark mode class to prevent FOUC (Flash of Unstyled Content)
            (function() {
                const savedTheme = localStorage.getItem("aqualuxe-theme");
                const prefersDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
                
                if (savedTheme === "dark" || (savedTheme === null && (prefersDarkMode || ' . $prefers_dark . '))) {
                    document.documentElement.classList.add("dark");
                }
            })();
        </script>' . "\n";
    }

    /**
     * Dequeue unnecessary assets
     */
    public function dequeue_unnecessary_assets() {
        // Remove emoji scripts
        if (get_theme_mod('aqualuxe_disable_emojis', true)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }
        
        // Remove embed script
        if (get_theme_mod('aqualuxe_disable_embeds', true)) {
            wp_deregister_script('wp-embed');
        }
        
        // Remove block library CSS if not using Gutenberg
        if (get_theme_mod('aqualuxe_disable_block_css', false) && !is_admin()) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
        }
        
        // Remove jQuery migrate
        if (get_theme_mod('aqualuxe_disable_jquery_migrate', true) && !is_admin()) {
            wp_deregister_script('jquery-migrate');
        }
    }

    /**
     * Get asset URL
     *
     * @param string $path Asset path.
     * @return string
     */
    private function get_asset_url($path) {
        return AQUALUXE_ASSETS_URI . $path;
    }

    /**
     * Get asset version
     *
     * @param string $path Asset path.
     * @return string
     */
    private function get_asset_version($path) {
        // Use file modification time as version if cache busting is enabled
        if (get_theme_mod('aqualuxe_cache_busting', true) && file_exists(AQUALUXE_DIR . 'assets/' . $path)) {
            return filemtime(AQUALUXE_DIR . 'assets/' . $path);
        }
        
        return AQUALUXE_VERSION;
    }

    /**
     * Get Google Fonts URL
     *
     * @param array $fonts Array of font families.
     * @return string
     */
    private function get_google_fonts_url($fonts) {
        if (empty($fonts)) {
            return '';
        }
        
        $font_families = array();
        
        foreach ($fonts as $font) {
            $font_families[] = $font . ':wght@300;400;500;600;700';
        }
        
        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'display' => 'swap',
        );
        
        return add_query_arg($query_args, 'https://fonts.googleapis.com/css2');
    }
}

// Initialize the assets class
new AquaLuxe_Assets();