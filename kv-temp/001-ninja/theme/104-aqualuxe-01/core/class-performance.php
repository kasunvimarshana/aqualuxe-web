<?php
/**
 * Performance Optimization Class
 * 
 * Handles performance optimizations
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Performance {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize performance optimizations
     */
    private function init() {
        // Remove emoji scripts
        add_action('init', [$this, 'disable_emojis']);
        
        // Optimize queries
        add_action('pre_get_posts', [$this, 'optimize_queries']);
        
        // Lazy load images
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading']);
        add_filter('the_content', [$this, 'add_lazy_loading_to_content']);
        
        // Preload critical resources
        add_action('wp_head', [$this, 'preload_critical_resources'], 1);
        
        // Optimize scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'optimize_assets'], 999);
        
        // Database query optimization
        add_action('init', [$this, 'optimize_database']);
        
        // Cache headers
        add_action('send_headers', [$this, 'set_cache_headers']);
        
        // Minify HTML output
        if (!is_admin()) {
            add_action('init', [$this, 'start_html_minification']);
        }
        
        // Critical CSS inlining
        add_action('wp_head', [$this, 'inline_critical_css'], 1);
    }
    
    /**
     * Disable emoji scripts
     */
    public function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        // Remove from TinyMCE
        add_filter('tiny_mce_plugins', [$this, 'disable_emoji_tinymce']);
        add_filter('wp_resource_hints', [$this, 'disable_emoji_dns_prefetch'], 10, 2);
    }
    
    /**
     * Remove emoji from TinyMCE
     */
    public function disable_emoji_tinymce($plugins) {
        if (is_array($plugins)) {
            return array_diff($plugins, ['wpemoji']);
        }
        return [];
    }
    
    /**
     * Remove emoji DNS prefetch
     */
    public function disable_emoji_dns_prefetch($urls, $relation_type) {
        if ('dns-prefetch' === $relation_type) {
            $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
            $urls = array_diff($urls, [$emoji_svg_url]);
        }
        return $urls;
    }
    
    /**
     * Optimize database queries
     */
    public function optimize_queries($query) {
        if (!is_admin() && $query->is_main_query()) {
            // Limit post revisions
            if ($query->is_home()) {
                $query->set('posts_per_page', get_option('posts_per_page', 10));
            }
            
            // Exclude attachments from search
            if ($query->is_search()) {
                $query->set('post_type', ['post', 'page']);
            }
        }
    }
    
    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr) {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
            $attr['data-src'] = $attr['src'];
            $attr['src'] = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"><rect width="1" height="1" fill="#f0f0f0"/></svg>');
            $attr['class'] = isset($attr['class']) ? $attr['class'] . ' lazy' : 'lazy';
        }
        return $attr;
    }
    
    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (!is_admin() && !is_feed()) {
            $content = preg_replace_callback(
                '/<img[^>]+>/i',
                function($matches) {
                    $img = $matches[0];
                    
                    // Skip if already has loading attribute
                    if (strpos($img, 'loading=') !== false) {
                        return $img;
                    }
                    
                    // Add lazy loading
                    $img = str_replace('<img', '<img loading="lazy"', $img);
                    
                    // Add data-src for JavaScript lazy loading
                    if (strpos($img, 'data-src=') === false) {
                        $img = preg_replace('/src="([^"]+)"/', 'data-src="$1" src="data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"><rect width="1" height="1" fill="#f0f0f0"/></svg>') . '"', $img);
                        $img = str_replace('class="', 'class="lazy ', $img);
                    }
                    
                    return $img;
                },
                $content
            );
        }
        return $content;
    }
    
    /**
     * Preload critical resources
     */
    public function preload_critical_resources() {
        // Preload critical CSS
        echo '<link rel="preload" href="' . aqualuxe_asset('css/app.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload critical JavaScript
        echo '<link rel="preload" href="' . aqualuxe_asset('js/app.js') . '" as="script">' . "\n";
        
        // Preload fonts
        $fonts = [
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap'
        ];
        
        foreach ($fonts as $font) {
            echo '<link rel="preload" href="' . $font . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        }
        
        // DNS prefetch for external resources
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    }
    
    /**
     * Optimize asset loading
     */
    public function optimize_assets() {
        // Defer non-critical JavaScript
        $this->defer_scripts();
        
        // Remove unnecessary scripts
        $this->remove_unnecessary_scripts();
    }
    
    /**
     * Defer non-critical scripts
     */
    private function defer_scripts() {
        $defer_scripts = [
            'aqualuxe-woocommerce',
            'comment-reply'
        ];
        
        foreach ($defer_scripts as $handle) {
            wp_script_add_data($handle, 'defer', true);
        }
    }
    
    /**
     * Remove unnecessary scripts
     */
    private function remove_unnecessary_scripts() {
        if (!is_admin()) {
            // Remove jQuery Migrate
            wp_dequeue_script('jquery-migrate');
            
            // Remove block library CSS if not using Gutenberg blocks
            if (!has_blocks()) {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('global-styles');
            }
        }
    }
    
    /**
     * Optimize database
     */
    public function optimize_database() {
        // Limit post revisions
        if (!defined('WP_POST_REVISIONS')) {
            define('WP_POST_REVISIONS', 3);
        }
        
        // Set autosave interval
        if (!defined('AUTOSAVE_INTERVAL')) {
            define('AUTOSAVE_INTERVAL', 300); // 5 minutes
        }
        
        // Disable file editing
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }
    
    /**
     * Set cache headers
     */
    public function set_cache_headers() {
        if (!is_admin() && !is_user_logged_in()) {
            $expires = 30 * DAY_IN_SECONDS; // 30 days
            
            header('Cache-Control: public, max-age=' . $expires);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime(__FILE__)) . ' GMT');
            
            // ETags for better caching
            $etag = md5(get_template_directory() . AQUALUXE_VERSION);
            header('ETag: "' . $etag . '"');
            
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === '"' . $etag . '"') {
                http_response_code(304);
                exit;
            }
        }
    }
    
    /**
     * Start HTML minification
     */
    public function start_html_minification() {
        ob_start([$this, 'minify_html']);
    }
    
    /**
     * Minify HTML output
     */
    public function minify_html($html) {
        // Skip minification for admin and login pages
        if (is_admin() || strpos($_SERVER['REQUEST_URI'], 'wp-login') !== false) {
            return $html;
        }
        
        // Remove comments
        $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
        
        // Remove extra whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove whitespace around block elements
        $html = preg_replace('/\s*(<\/?(div|p|h[1-6]|section|article|header|footer|nav|main|aside)[^>]*>)\s*/', '$1', $html);
        
        return trim($html);
    }
    
    /**
     * Inline critical CSS
     */
    public function inline_critical_css() {
        $critical_css = $this->get_critical_css();
        if ($critical_css) {
            echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>' . "\n";
        }
    }
    
    /**
     * Get critical CSS for above-the-fold content
     */
    private function get_critical_css() {
        // This would typically be generated by a tool like Penthouse or Critical
        // For now, we'll include basic critical styles
        $critical_css = "
            body{font-family:Inter,sans-serif;margin:0;padding:0;line-height:1.6}
            .site-header{background:#fff;box-shadow:0 2px 4px rgba(0,0,0,0.1);position:sticky;top:0;z-index:40}
            .container{max-width:1200px;margin:0 auto;padding:0 1rem}
            .hero{min-height:60vh;display:flex;align-items:center;background:linear-gradient(135deg,#0ea5e9,#64748b)}
            .hero-title{font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:1rem}
            .btn{display:inline-flex;align-items:center;padding:0.75rem 1.5rem;font-weight:500;border-radius:0.5rem;text-decoration:none;transition:all 0.2s}
            .btn-primary{background:#0ea5e9;color:#fff}
            .btn-primary:hover{background:#0284c7}
            @media(max-width:768px){.hero-title{font-size:1.875rem}}
        ";
        
        return trim($critical_css);
    }
    
    /**
     * Get page speed score
     */
    public function get_page_speed_metrics() {
        $metrics = [
            'query_count' => get_num_queries(),
            'memory_usage' => size_format(memory_get_peak_usage()),
            'load_time' => timer_stop()
        ];
        
        return $metrics;
    }
}