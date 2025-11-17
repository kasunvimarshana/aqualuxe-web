<?php
/**
 * Performance Optimization Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Performance optimization class
 */
class Performance {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Asset optimization
        add_action('wp_enqueue_scripts', [$this, 'optimize_assets'], 999);
        add_filter('style_loader_tag', [$this, 'add_preload_styles'], 10, 4);
        add_filter('script_loader_tag', [$this, 'add_async_defer'], 10, 3);
        
        // Critical CSS
        add_action('wp_head', [$this, 'inline_critical_css'], 1);
        
        // Preload key resources
        add_action('wp_head', [$this, 'add_resource_hints'], 2);
        
        // Remove unnecessary features
        $this->remove_unnecessary_features();
        
        // Database optimization
        add_action('wp_footer', [$this, 'cleanup_head']);
        
        // Image optimization
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading'], 10, 3);
        add_filter('the_content', [$this, 'add_lazy_loading_to_content']);
        
        // Cache control
        add_action('wp_head', [$this, 'add_cache_headers']);
        
        // Minification (if not handled by plugins)
        if (get_theme_mod('aqualuxe_enable_minification', false)) {
            add_filter('style_loader_src', [$this, 'minify_css']);
            add_filter('script_loader_src', [$this, 'minify_js']);
        }
        
        // Optimize database queries
        add_action('pre_get_posts', [$this, 'optimize_queries']);
        
        // Memory optimization
        add_action('wp_footer', [$this, 'cleanup_memory']);
        
        // Admin performance
        if (is_admin()) {
            add_action('admin_init', [$this, 'optimize_admin']);
        }
    }
    
    /**
     * Optimize asset loading
     */
    public function optimize_assets() {
        // Combine CSS files if enabled
        if (get_theme_mod('aqualuxe_combine_css', false)) {
            $this->combine_css_files();
        }
        
        // Combine JS files if enabled
        if (get_theme_mod('aqualuxe_combine_js', false)) {
            $this->combine_js_files();
        }
        
        // Remove unused CSS
        if (get_theme_mod('aqualuxe_remove_unused_css', false)) {
            $this->remove_unused_css();
        }
        
        // Optimize font loading
        $this->optimize_font_loading();
    }
    
    /**
     * Add preload for critical styles
     */
    public function add_preload_styles($html, $handle, $href, $media) {
        // Preload critical stylesheets
        $critical_styles = ['aqualuxe-style', 'aqualuxe-critical'];
        
        if (in_array($handle, $critical_styles)) {
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
            $html .= '<noscript>' . str_replace("rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", "rel='stylesheet'", $html) . '</noscript>';
        }
        
        return $html;
    }
    
    /**
     * Add async/defer to scripts
     */
    public function add_async_defer($tag, $handle, $src) {
        // Scripts to defer
        $defer_scripts = [
            'aqualuxe-script',
            'aqualuxe-woocommerce',
            'aqualuxe-modules',
            'wp-embed',
        ];
        
        // Scripts to load async
        $async_scripts = [
            'google-analytics',
            'facebook-pixel',
            'gtag',
        ];
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Inline critical CSS
     */
    public function inline_critical_css() {
        if (!get_theme_mod('aqualuxe_inline_critical_css', true)) {
            return;
        }
        
        $critical_css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="critical-css">' . $this->minify_css_content($critical_css) . '</style>';
            }
        } else {
            // Generate basic critical CSS
            $this->generate_basic_critical_css();
        }
    }
    
    /**
     * Generate basic critical CSS for above-the-fold content
     */
    private function generate_basic_critical_css() {
        $critical_css = '
            html { line-height: 1.15; -webkit-text-size-adjust: 100%; }
            body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
            header { display: block; }
            nav { display: block; }
            main { display: block; }
            .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
            .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0; }
            .btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; text-decoration: none; }
            .btn-primary { background-color: #0ea5e9; color: white; }
            .header { background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
            .nav { display: flex; align-items: center; justify-content: space-between; padding: 1rem 0; }
            .logo { font-size: 1.5rem; font-weight: bold; }
        ';
        
        echo '<style id="critical-css">' . $this->minify_css_content($critical_css) . '</style>';
    }
    
    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        // DNS prefetch for external domains
        $external_domains = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//www.google-analytics.com',
            '//www.googletagmanager.com',
        ];
        
        foreach ($external_domains as $domain) {
            echo '<link rel="dns-prefetch" href="' . $domain . '">';
        }
        
        // Preconnect to critical external resources
        echo '<link rel="preconnect" href="//fonts.googleapis.com" crossorigin>';
        echo '<link rel="preconnect" href="//fonts.gstatic.com" crossorigin>';
        
        // Preload critical assets
        $asset_manager = Asset_Manager::get_instance();
        
        // Preload critical fonts
        $critical_fonts = get_theme_mod('aqualuxe_critical_fonts', []);
        foreach ($critical_fonts as $font_url) {
            if ($font_url) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>';
            }
        }
        
        // Preload hero image
        if (is_front_page()) {
            $hero_image = get_theme_mod('aqualuxe_hero_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">';
            }
        }
    }
    
    /**
     * Remove unnecessary WordPress features
     */
    private function remove_unnecessary_features() {
        if (get_theme_mod('aqualuxe_remove_emoji', true)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }
        
        if (get_theme_mod('aqualuxe_remove_gutenberg_css', true) && !is_admin()) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-blocks-style');
        }
        
        if (get_theme_mod('aqualuxe_remove_jquery_migrate', true)) {
            add_action('wp_default_scripts', function($scripts) {
                if (!is_admin() && isset($scripts->registered['jquery'])) {
                    $script = $scripts->registered['jquery'];
                    if ($script->deps) {
                        $script->deps = array_diff($script->deps, ['jquery-migrate']);
                    }
                }
            });
        }
        
        // Remove unnecessary query strings
        add_filter('style_loader_src', [$this, 'remove_query_strings'], 10, 1);
        add_filter('script_loader_src', [$this, 'remove_query_strings'], 10, 1);
    }
    
    /**
     * Clean up wp_head
     */
    public function cleanup_head() {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size) {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
            $attr['decoding'] = 'async';
        }
        return $attr;
    }
    
    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (!is_admin() && !is_feed()) {
            $content = preg_replace('/<img(.*?)src=/i', '<img$1loading="lazy" decoding="async" src=', $content);
        }
        return $content;
    }
    
    /**
     * Add cache headers
     */
    public function add_cache_headers() {
        if (!is_admin() && !is_user_logged_in()) {
            $cache_time = get_theme_mod('aqualuxe_cache_time', 3600); // 1 hour default
            
            header('Cache-Control: public, max-age=' . $cache_time);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
            header('Vary: Accept-Encoding');
        }
    }
    
    /**
     * Minify CSS
     */
    public function minify_css($src) {
        if (strpos($src, AQUALUXE_THEME_URL) !== false && !strpos($src, '.min.')) {
            $minified_src = str_replace('.css', '.min.css', $src);
            if (file_exists(str_replace(AQUALUXE_THEME_URL, AQUALUXE_THEME_DIR, $minified_src))) {
                return $minified_src;
            }
        }
        return $src;
    }
    
    /**
     * Minify JavaScript
     */
    public function minify_js($src) {
        if (strpos($src, AQUALUXE_THEME_URL) !== false && !strpos($src, '.min.')) {
            $minified_src = str_replace('.js', '.min.js', $src);
            if (file_exists(str_replace(AQUALUXE_THEME_URL, AQUALUXE_THEME_DIR, $minified_src))) {
                return $minified_src;
            }
        }
        return $src;
    }
    
    /**
     * Minify CSS content
     */
    private function minify_css_content($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        $css = str_replace('; ', ';', $css);
        $css = str_replace(': ', ':', $css);
        $css = str_replace(' {', '{', $css);
        $css = str_replace('{ ', '{', $css);
        $css = str_replace(', ', ',', $css);
        $css = str_replace('} ', '}', $css);
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }
    
    /**
     * Combine CSS files
     */
    private function combine_css_files() {
        global $wp_styles;
        
        $handles_to_combine = [];
        $combined_css = '';
        
        foreach ($wp_styles->queue as $handle) {
            if (strpos($wp_styles->registered[$handle]->src, AQUALUXE_THEME_URL) !== false) {
                $handles_to_combine[] = $handle;
                $file_path = str_replace(AQUALUXE_THEME_URL, AQUALUXE_THEME_DIR, $wp_styles->registered[$handle]->src);
                
                if (file_exists($file_path)) {
                    $combined_css .= file_get_contents($file_path) . "\n";
                }
            }
        }
        
        if (!empty($handles_to_combine)) {
            // Remove individual stylesheets
            foreach ($handles_to_combine as $handle) {
                wp_dequeue_style($handle);
            }
            
            // Create combined file
            $combined_file = AQUALUXE_THEME_DIR . '/assets/dist/css/combined.css';
            file_put_contents($combined_file, $this->minify_css_content($combined_css));
            
            // Enqueue combined file
            wp_enqueue_style(
                'aqualuxe-combined',
                AQUALUXE_THEME_URL . '/assets/dist/css/combined.css',
                [],
                filemtime($combined_file)
            );
        }
    }
    
    /**
     * Combine JavaScript files
     */
    private function combine_js_files() {
        global $wp_scripts;
        
        $handles_to_combine = [];
        $combined_js = '';
        
        foreach ($wp_scripts->queue as $handle) {
            if (strpos($wp_scripts->registered[$handle]->src, AQUALUXE_THEME_URL) !== false) {
                $handles_to_combine[] = $handle;
                $file_path = str_replace(AQUALUXE_THEME_URL, AQUALUXE_THEME_DIR, $wp_scripts->registered[$handle]->src);
                
                if (file_exists($file_path)) {
                    $combined_js .= file_get_contents($file_path) . ";\n";
                }
            }
        }
        
        if (!empty($handles_to_combine)) {
            // Remove individual scripts
            foreach ($handles_to_combine as $handle) {
                wp_dequeue_script($handle);
            }
            
            // Create combined file
            $combined_file = AQUALUXE_THEME_DIR . '/assets/dist/js/combined.js';
            file_put_contents($combined_file, $combined_js);
            
            // Enqueue combined file
            wp_enqueue_script(
                'aqualuxe-combined',
                AQUALUXE_THEME_URL . '/assets/dist/js/combined.js',
                ['jquery'],
                filemtime($combined_file),
                true
            );
        }
    }
    
    /**
     * Remove unused CSS
     */
    private function remove_unused_css() {
        // This is a simplified implementation
        // In production, you'd use tools like PurgeCSS
        
        if (!is_front_page()) {
            // Remove front page specific styles on other pages
            wp_dequeue_style('aqualuxe-homepage');
        }
        
        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            // Remove WooCommerce styles on non-shop pages
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
        }
        
        if (!is_singular('post')) {
            // Remove single post styles on other pages
            wp_dequeue_style('aqualuxe-single-post');
        }
    }
    
    /**
     * Optimize font loading
     */
    private function optimize_font_loading() {
        // Remove default Google Fonts if using custom fonts
        if (get_theme_mod('aqualuxe_use_custom_fonts', false)) {
            wp_dequeue_style('aqualuxe-google-fonts');
        }
        
        // Add font-display: swap to improve loading performance
        add_filter('style_loader_tag', function($html, $handle) {
            if (strpos($handle, 'font') !== false) {
                $html = str_replace('rel=\'stylesheet\'', 'rel=\'stylesheet\' media=\'print\' onload="this.media=\'all\'"', $html);
            }
            return $html;
        }, 10, 2);
    }
    
    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_queries($query) {
        if (!is_admin() && $query->is_main_query()) {
            // Limit post revisions
            if ($query->is_home() || $query->is_archive()) {
                $query->set('posts_per_page', get_theme_mod('aqualuxe_posts_per_page', 10));
            }
            
            // Exclude unnecessary post meta from queries
            if ($query->is_archive()) {
                $query->set('no_found_rows', true);
            }
        }
    }
    
    /**
     * Clean up memory
     */
    public function cleanup_memory() {
        // Clear object cache if it exists
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Clean up temporary variables
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }
    
    /**
     * Optimize admin performance
     */
    public function optimize_admin() {
        // Disable admin bar for non-admin users
        if (!current_user_can('manage_options') && get_theme_mod('aqualuxe_disable_admin_bar', false)) {
            show_admin_bar(false);
        }
        
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', get_theme_mod('aqualuxe_post_revisions', 3));
        }
        
        // Increase memory limit if needed
        if (get_theme_mod('aqualuxe_increase_memory_limit', false)) {
            ini_set('memory_limit', '512M');
        }
    }
    
    /**
     * Get performance metrics
     */
    public function get_performance_metrics() {
        $metrics = [];
        
        // Page load time
        if (defined('AQUALUXE_START_TIME')) {
            $metrics['page_load_time'] = round((microtime(true) - AQUALUXE_START_TIME) * 1000, 2);
        }
        
        // Memory usage
        $metrics['memory_usage'] = round(memory_get_usage() / 1024 / 1024, 2);
        $metrics['memory_peak'] = round(memory_get_peak_usage() / 1024 / 1024, 2);
        
        // Database queries
        $metrics['db_queries'] = get_num_queries();
        
        // HTTP requests
        $metrics['http_requests'] = $this->count_http_requests();
        
        // Asset counts
        global $wp_scripts, $wp_styles;
        $metrics['css_files'] = count($wp_styles->queue);
        $metrics['js_files'] = count($wp_scripts->queue);
        
        return $metrics;
    }
    
    /**
     * Count HTTP requests
     */
    private function count_http_requests() {
        // This would require monitoring HTTP requests
        // For now, return estimated count based on enqueued assets
        global $wp_scripts, $wp_styles;
        return count($wp_scripts->queue) + count($wp_styles->queue);
    }
    
    /**
     * Generate performance report
     */
    public function generate_performance_report() {
        $metrics = $this->get_performance_metrics();
        $optimizations = [];
        
        // Check for common performance issues
        if ($metrics['page_load_time'] > 3000) {
            $optimizations[] = 'Page load time is over 3 seconds. Consider enabling caching.';
        }
        
        if ($metrics['memory_usage'] > 64) {
            $optimizations[] = 'High memory usage detected. Consider optimizing plugins and queries.';
        }
        
        if ($metrics['db_queries'] > 50) {
            $optimizations[] = 'High number of database queries. Consider caching or query optimization.';
        }
        
        if ($metrics['css_files'] > 10) {
            $optimizations[] = 'Consider combining CSS files to reduce HTTP requests.';
        }
        
        if ($metrics['js_files'] > 10) {
            $optimizations[] = 'Consider combining JavaScript files to reduce HTTP requests.';
        }
        
        return [
            'metrics' => $metrics,
            'optimizations' => $optimizations,
            'score' => $this->calculate_performance_score($metrics),
        ];
    }
    
    /**
     * Calculate performance score
     */
    private function calculate_performance_score($metrics) {
        $score = 100;
        
        // Deduct points for performance issues
        if ($metrics['page_load_time'] > 1000) {
            $score -= min(50, ($metrics['page_load_time'] - 1000) / 100);
        }
        
        if ($metrics['memory_usage'] > 32) {
            $score -= min(20, ($metrics['memory_usage'] - 32) / 2);
        }
        
        if ($metrics['db_queries'] > 20) {
            $score -= min(20, ($metrics['db_queries'] - 20) / 2);
        }
        
        if ($metrics['css_files'] > 5) {
            $score -= min(10, ($metrics['css_files'] - 5) * 2);
        }
        
        if ($metrics['js_files'] > 5) {
            $score -= min(10, ($metrics['js_files'] - 5) * 2);
        }
        
        return max(0, round($score));
    }
}
