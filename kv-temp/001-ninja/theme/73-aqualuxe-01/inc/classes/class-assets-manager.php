<?php
/**
 * Assets Manager Class
 * Handles theme assets loading, compilation, and optimization
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

class AquaLuxe_Assets_Manager {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_assets']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_head', [$this, 'add_critical_css']);
        add_action('wp_footer', [$this, 'add_inline_scripts']);
        add_filter('script_loader_tag', [$this, 'add_script_attributes'], 10, 3);
        add_filter('style_loader_tag', [$this, 'add_style_attributes'], 10, 4);
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        $theme_version = wp_get_theme()->get('Version');
        $is_dev = defined('WP_DEBUG') && WP_DEBUG;
        
        // Get asset manifest for cache busting
        $manifest = $this->get_asset_manifest();
        
        // Main stylesheet
        $style_file = $is_dev ? 'app.css' : ($manifest['app.css'] ?? 'app.css');
        wp_enqueue_style(
            'aqualuxe-style',
            get_template_directory_uri() . '/assets/dist/css/' . $style_file,
            [],
            $theme_version,
            'all'
        );
        
        // Critical CSS for above-the-fold content (inline)
        if (!$is_dev) {
            $this->inline_critical_css();
        }
        
        // Main JavaScript
        $script_file = $is_dev ? 'app.js' : ($manifest['app.js'] ?? 'app.js');
        wp_enqueue_script(
            'aqualuxe-script',
            get_template_directory_uri() . '/assets/dist/js/' . $script_file,
            ['jquery'],
            $theme_version,
            true
        );
        
        // Vendor scripts (loaded separately for better caching)
        if (isset($manifest['vendor.js'])) {
            wp_enqueue_script(
                'aqualuxe-vendor',
                get_template_directory_uri() . '/assets/dist/js/' . $manifest['vendor.js'],
                [],
                $theme_version,
                true
            );
        }
        
        // Alpine.js for JavaScript reactivity
        wp_enqueue_script(
            'alpine-js',
            'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js',
            [],
            '3.0.0',
            true
        );
        
        // Glide.js for sliders (only when needed)
        if ($this->needs_slider()) {
            wp_enqueue_script(
                'glide-js',
                'https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/glide.min.js',
                [],
                '3.6.0',
                true
            );
            
            wp_enqueue_style(
                'glide-css',
                'https://cdn.jsdelivr.net/npm/@glidejs/glide@3.6.0/dist/css/glide.core.min.css',
                [],
                '3.6.0'
            );
        }
        
        // Lazy loading for images
        wp_enqueue_script(
            'lazysizes',
            'https://cdn.jsdelivr.net/npm/lazysizes@5.3.2/lazysizes.min.js',
            [],
            '5.3.2',
            true
        );
        
        // WooCommerce specific assets
        if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            $this->enqueue_woocommerce_assets();
        }
        
        // Localize script with theme data
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'themeUrl' => get_template_directory_uri(),
            'homeUrl' => home_url('/'),
            'isRtl' => is_rtl(),
            'isDev' => $is_dev,
            'strings' => [
                'loading' => __('Loading...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'success' => __('Success!', 'aqualuxe'),
                'confirm' => __('Are you sure?', 'aqualuxe'),
            ],
            'settings' => [
                'enableAnimations' => get_theme_mod('enable_animations', true),
                'enableLazyLoading' => get_theme_mod('enable_lazy_loading', true),
                'enableDarkMode' => get_theme_mod('enable_dark_mode', true),
                'animationDuration' => get_theme_mod('animation_duration', 300),
            ]
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Custom fonts
        $this->enqueue_custom_fonts();
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        $theme_version = wp_get_theme()->get('Version');
        
        // Only load on theme-related admin pages
        if (in_array($hook, ['themes.php', 'customize.php', 'widgets.php'])) {
            wp_enqueue_style(
                'aqualuxe-admin-style',
                get_template_directory_uri() . '/assets/dist/css/admin.css',
                [],
                $theme_version
            );
            
            wp_enqueue_script(
                'aqualuxe-admin-script',
                get_template_directory_uri() . '/assets/dist/js/admin.js',
                ['jquery', 'wp-color-picker'],
                $theme_version,
                true
            );
            
            wp_enqueue_style('wp-color-picker');
        }
    }
    
    /**
     * Add critical CSS inline for performance
     */
    public function add_critical_css() {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            return;
        }
        
        $critical_css_file = get_template_directory() . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
            }
        }
    }
    
    /**
     * Add inline scripts for performance
     */
    public function add_inline_scripts() {
        // Dark mode toggle script (before main JS loads)
        ?>
        <script>
        // Dark mode initialization (before DOM ready)
        (function() {
            const darkMode = localStorage.getItem('aqualuxe-dark-mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (darkMode === 'true' || (darkMode === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
        
        // Preload critical resources
        <?php if (is_front_page()): ?>
        (function() {
            // Preload hero images
            const heroImages = document.querySelectorAll('.hero-image img, .hero-video');
            heroImages.forEach(img => {
                if (img.dataset.src) {
                    const link = document.createElement('link');
                    link.rel = 'preload';
                    link.as = 'image';
                    link.href = img.dataset.src;
                    document.head.appendChild(link);
                }
            });
        })();
        <?php endif; ?>
        </script>
        <?php
    }
    
    /**
     * Add attributes to script tags
     */
    public function add_script_attributes($tag, $handle, $src) {
        // Add defer to non-critical scripts
        $defer_scripts = [
            'aqualuxe-script',
            'glide-js',
            'lazysizes'
        ];
        
        if (in_array($handle, $defer_scripts)) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
        
        // Add async to external scripts
        $async_scripts = [
            'alpine-js'
        ];
        
        if (in_array($handle, $async_scripts)) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Add attributes to style tags
     */
    public function add_style_attributes($html, $handle, $href, $media) {
        // Add preload for critical CSS
        $preload_styles = [
            'aqualuxe-style'
        ];
        
        if (in_array($handle, $preload_styles)) {
            $preload_html = '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
            $html = $preload_html . $html;
        }
        
        return $html;
    }
    
    /**
     * Enqueue WooCommerce specific assets
     */
    private function enqueue_woocommerce_assets() {
        $theme_version = wp_get_theme()->get('Version');
        
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            get_template_directory_uri() . '/assets/dist/css/woocommerce.css',
            ['aqualuxe-style'],
            $theme_version
        );
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            get_template_directory_uri() . '/assets/dist/js/woocommerce.js',
            ['aqualuxe-script', 'jquery'],
            $theme_version,
            true
        );
        
        // Product gallery scripts
        if (is_product()) {
            wp_enqueue_script(
                'zoom',
                'https://cdn.jsdelivr.net/npm/jquery-zoom@1.7.21/jquery.zoom.min.js',
                ['jquery'],
                '1.7.21',
                true
            );
            
            wp_enqueue_script(
                'photoswipe',
                'https://cdn.jsdelivr.net/npm/photoswipe@4.1.3/dist/photoswipe.min.js',
                [],
                '4.1.3',
                true
            );
            
            wp_enqueue_script(
                'photoswipe-ui',
                'https://cdn.jsdelivr.net/npm/photoswipe@4.1.3/dist/photoswipe-ui-default.min.js',
                ['photoswipe'],
                '4.1.3',
                true
            );
            
            wp_enqueue_style(
                'photoswipe-css',
                'https://cdn.jsdelivr.net/npm/photoswipe@4.1.3/dist/photoswipe.css',
                [],
                '4.1.3'
            );
        }
    }
    
    /**
     * Enqueue custom fonts
     */
    private function enqueue_custom_fonts() {
        $google_fonts = get_theme_mod('google_fonts', 'Inter:400,500,600,700');
        
        if ($google_fonts) {
            wp_enqueue_style(
                'aqualuxe-fonts',
                'https://fonts.googleapis.com/css2?family=' . $google_fonts . '&display=swap',
                [],
                null
            );
        }
        
        // Font awesome icons
        if (get_theme_mod('enable_font_awesome', true)) {
            wp_enqueue_style(
                'font-awesome',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
                [],
                '6.4.0'
            );
        }
    }
    
    /**
     * Get asset manifest for cache busting
     */
    private function get_asset_manifest() {
        $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest_content = file_get_contents($manifest_path);
            $manifest = json_decode($manifest_content, true);
            
            if (is_array($manifest)) {
                // Remove leading slash from keys
                $clean_manifest = [];
                foreach ($manifest as $key => $value) {
                    $clean_key = ltrim($key, '/');
                    $clean_manifest[$clean_key] = ltrim($value, '/');
                }
                return $clean_manifest;
            }
        }
        
        return [];
    }
    
    /**
     * Check if current page needs slider functionality
     */
    private function needs_slider() {
        return is_front_page() || 
               is_page_template('page-home.php') || 
               is_product() || 
               has_shortcode(get_post()->post_content ?? '', 'gallery') ||
               has_shortcode(get_post()->post_content ?? '', 'slider');
    }
    
    /**
     * Inline critical CSS for above-the-fold content
     */
    private function inline_critical_css() {
        $critical_css = '';
        
        // Basic critical styles
        $critical_css .= '
        html{line-height:1.15;-webkit-text-size-adjust:100%}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif}
        .container{max-width:1200px;margin:0 auto;padding:0 1rem}
        .btn{display:inline-flex;align-items:center;justify-content:center;padding:0.5rem 1rem;border-radius:0.375rem;font-weight:500;transition:all 0.2s}
        .btn-primary{background-color:#0ea5e9;color:white}
        .btn-primary:hover{background-color:#0284c7}
        .card{background:white;border-radius:0.5rem;box-shadow:0 1px 3px rgba(0,0,0,0.1)}
        .dark .card{background:#374151;color:white}
        ';
        
        if ($critical_css) {
            echo '<style id="aqualuxe-critical-inline">' . $critical_css . '</style>';
        }
    }
}

// Initialize the assets manager
new AquaLuxe_Assets_Manager();
