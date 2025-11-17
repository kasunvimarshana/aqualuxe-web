<?php
/**
 * Search Module
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Search module class
 */
class Module_Search {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Module enabled
     */
    private $enabled = true;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->enabled = get_theme_mod('aqualuxe_enable_enhanced_search', true);
        
        if ($this->enabled) {
            $this->init_hooks();
        }
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
        // Enqueue assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // AJAX search handlers
        add_action('wp_ajax_aqualuxe_live_search', [$this, 'ajax_live_search']);
        add_action('wp_ajax_nopriv_aqualuxe_live_search', [$this, 'ajax_live_search']);
        add_action('wp_ajax_aqualuxe_search_suggestions', [$this, 'ajax_search_suggestions']);
        add_action('wp_ajax_nopriv_aqualuxe_search_suggestions', [$this, 'ajax_search_suggestions']);
        
        // Modify main search query
        add_action('pre_get_posts', [$this, 'modify_search_query']);
        
        // Add search form to header
        add_action('wp_head', [$this, 'add_search_modal_html']);
        
        // Shortcodes
        add_shortcode('aqualuxe_search_form', [$this, 'search_form_shortcode']);
        add_shortcode('aqualuxe_search_results', [$this, 'search_results_shortcode']);
        
        // Search results enhancement
        add_filter('the_excerpt', [$this, 'highlight_search_terms']);
        add_filter('the_title', [$this, 'highlight_search_terms_in_title']);
        
        // Customizer controls
        add_action('customize_register', [$this, 'add_customizer_controls']);
        
        // Search tracking
        add_action('wp_head', [$this, 'track_search_analytics']);
        
        // REST API endpoints
        add_action('rest_api_init', [$this, 'register_rest_endpoints']);
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Search CSS
        $search_css = "
            /* Search form styles */
            .aqualuxe-search-form {
                position: relative;
                display: flex;
                align-items: center;
                max-width: 500px;
                margin: 0 auto;
            }
            
            .aqualuxe-search-input {
                width: 100%;
                padding: 0.75rem 3rem 0.75rem 1rem;
                border: 1px solid var(--color-border);
                border-radius: 0.5rem;
                background: var(--color-bg);
                color: var(--color-text);
                font-size: 1rem;
                transition: all 0.3s ease;
            }
            
            .aqualuxe-search-input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            }
            
            .aqualuxe-search-button {
                position: absolute;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #6b7280;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 0.25rem;
                transition: color 0.3s ease;
            }
            
            .aqualuxe-search-button:hover {
                color: #4f46e5;
            }
            
            .aqualuxe-search-button .icon {
                width: 1.25rem;
                height: 1.25rem;
            }
            
            /* Search modal */
            .aqualuxe-search-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: flex-start;
                justify-content: center;
                padding: 5vh 1rem;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .aqualuxe-search-modal.active {
                opacity: 1;
                visibility: visible;
            }
            
            .aqualuxe-search-modal-content {
                background: var(--color-bg);
                border-radius: 0.75rem;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                width: 100%;
                max-width: 600px;
                max-height: 80vh;
                overflow: hidden;
                transform: translateY(-20px);
                transition: transform 0.3s ease;
            }
            
            .aqualuxe-search-modal.active .aqualuxe-search-modal-content {
                transform: translateY(0);
            }
            
            .aqualuxe-search-modal-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--color-border);
            }
            
            .aqualuxe-search-modal-body {
                max-height: 50vh;
                overflow-y: auto;
            }
            
            /* Live search results */
            .aqualuxe-search-results {
                padding: 0;
                margin: 0;
                list-style: none;
            }
            
            .aqualuxe-search-result {
                border-bottom: 1px solid var(--color-border);
            }
            
            .aqualuxe-search-result:last-child {
                border-bottom: none;
            }
            
            .aqualuxe-search-result-link {
                display: flex;
                padding: 1rem 1.5rem;
                text-decoration: none;
                color: inherit;
                transition: background-color 0.3s ease;
            }
            
            .aqualuxe-search-result-link:hover {
                background: rgba(79, 70, 229, 0.05);
            }
            
            .aqualuxe-search-result-image {
                width: 3rem;
                height: 3rem;
                border-radius: 0.375rem;
                object-fit: cover;
                margin-right: 1rem;
                flex-shrink: 0;
            }
            
            .aqualuxe-search-result-content {
                flex: 1;
                min-width: 0;
            }
            
            .aqualuxe-search-result-title {
                font-weight: 600;
                margin: 0 0 0.25rem 0;
                font-size: 0.875rem;
                line-height: 1.4;
            }
            
            .aqualuxe-search-result-excerpt {
                font-size: 0.75rem;
                color: #6b7280;
                line-height: 1.4;
                margin: 0;
            }
            
            .aqualuxe-search-result-meta {
                font-size: 0.625rem;
                color: #9ca3af;
                margin-top: 0.25rem;
            }
            
            /* Search suggestions */
            .aqualuxe-search-suggestions {
                padding: 1rem 1.5rem;
                border-bottom: 1px solid var(--color-border);
            }
            
            .aqualuxe-search-suggestions-title {
                font-size: 0.75rem;
                font-weight: 600;
                color: #6b7280;
                margin: 0 0 0.5rem 0;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            
            .aqualuxe-search-suggestions-list {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .aqualuxe-search-suggestion {
                background: rgba(79, 70, 229, 0.1);
                color: #4f46e5;
                padding: 0.25rem 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .aqualuxe-search-suggestion:hover {
                background: #4f46e5;
                color: white;
            }
            
            /* Search highlights */
            .search-highlight {
                background: #fef3c7;
                color: #92400e;
                padding: 0.125rem 0.25rem;
                border-radius: 0.25rem;
                font-weight: 600;
            }
            
            /* Loading state */
            .aqualuxe-search-loading {
                padding: 2rem;
                text-align: center;
                color: #6b7280;
            }
            
            .aqualuxe-search-loading .spinner {
                display: inline-block;
                width: 1.5rem;
                height: 1.5rem;
                border: 2px solid #e5e7eb;
                border-top: 2px solid #4f46e5;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Empty state */
            .aqualuxe-search-empty {
                padding: 2rem;
                text-align: center;
                color: #6b7280;
            }
            
            .aqualuxe-search-empty-icon {
                width: 3rem;
                height: 3rem;
                margin: 0 auto 1rem;
                opacity: 0.5;
            }
            
            /* Responsive */
            @media (max-width: 640px) {
                .aqualuxe-search-modal {
                    padding: 2rem 1rem;
                }
                
                .aqualuxe-search-modal-content {
                    max-height: 90vh;
                }
                
                .aqualuxe-search-result-link {
                    padding: 0.75rem 1rem;
                }
                
                .aqualuxe-search-result-image {
                    width: 2.5rem;
                    height: 2.5rem;
                }
            }
            
            /* Search trigger button */
            .aqualuxe-search-trigger {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: transparent;
                border: 1px solid currentColor;
                border-radius: 0.375rem;
                color: inherit;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .aqualuxe-search-trigger:hover {
                background: rgba(79, 70, 229, 0.1);
                border-color: #4f46e5;
                color: #4f46e5;
            }
            
            .aqualuxe-search-trigger .icon {
                width: 1rem;
                height: 1rem;
            }
            
            /* Keyboard shortcut hint */
            .aqualuxe-search-shortcut {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.75rem;
                color: #9ca3af;
                margin-left: auto;
            }
            
            .aqualuxe-search-shortcut-key {
                background: #f3f4f6;
                border: 1px solid #d1d5db;
                border-radius: 0.25rem;
                padding: 0.125rem 0.375rem;
                font-family: monospace;
                font-size: 0.625rem;
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $search_css);
        
        // Search JavaScript
        wp_enqueue_script(
            'aqualuxe-search',
            AQUALUXE_THEME_URL . '/modules/search/assets/search.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-search', 'aqualuxeSearch', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_search_nonce'),
            'enabled' => $this->enabled,
            'liveSearch' => get_theme_mod('aqualuxe_search_live_search', true),
            'suggestions' => get_theme_mod('aqualuxe_search_suggestions', true),
            'minChars' => get_theme_mod('aqualuxe_search_min_chars', 3),
            'delay' => get_theme_mod('aqualuxe_search_delay', 300),
            'maxResults' => get_theme_mod('aqualuxe_search_max_results', 8),
            'postTypes' => get_theme_mod('aqualuxe_search_post_types', ['post', 'page', 'product']),
            'strings' => [
                'placeholder' => __('Search...', 'aqualuxe'),
                'noResults' => __('No results found', 'aqualuxe'),
                'searching' => __('Searching...', 'aqualuxe'),
                'suggestions' => __('Popular searches', 'aqualuxe'),
                'recentSearches' => __('Recent searches', 'aqualuxe'),
                'viewAll' => __('View all results', 'aqualuxe'),
                'closeSearch' => __('Close search', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Add search modal HTML to head
     */
    public function add_search_modal_html() {
        if (!$this->enabled) {
            return;
        }
        
        ?>
        <div id="aqualuxe-search-modal" class="aqualuxe-search-modal">
            <div class="aqualuxe-search-modal-content">
                <div class="aqualuxe-search-modal-header">
                    <form class="aqualuxe-search-form" role="search">
                        <input type="search" 
                               class="aqualuxe-search-input" 
                               name="s" 
                               placeholder="<?php esc_attr_e('Search...', 'aqualuxe'); ?>"
                               autocomplete="off"
                               aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>">
                        <button type="submit" class="aqualuxe-search-button" aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                    <div class="aqualuxe-search-shortcut">
                        <span>Press</span>
                        <kbd class="aqualuxe-search-shortcut-key">ESC</kbd>
                        <span>to close</span>
                    </div>
                </div>
                <div class="aqualuxe-search-modal-body">
                    <div id="aqualuxe-search-content"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get search form HTML
     */
    public function get_search_form($args = []) {
        $defaults = [
            'placeholder' => __('Search...', 'aqualuxe'),
            'show_button' => true,
            'button_text' => __('Search', 'aqualuxe'),
            'class' => 'aqualuxe-search-form',
            'type' => 'standard', // standard, modal, inline
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        ob_start();
        ?>
        <form class="<?php echo esc_attr($args['class']); ?>" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" 
                   class="aqualuxe-search-input" 
                   name="s" 
                   value="<?php echo get_search_query(); ?>"
                   placeholder="<?php echo esc_attr($args['placeholder']); ?>"
                   aria-label="<?php esc_attr_e('Search', 'aqualuxe'); ?>"
                   <?php if ($args['type'] === 'modal'): ?>data-search-trigger="modal"<?php endif; ?>>
            
            <?php if ($args['show_button']): ?>
            <button type="submit" class="aqualuxe-search-button" aria-label="<?php echo esc_attr($args['button_text']); ?>">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="sr-only"><?php echo esc_html($args['button_text']); ?></span>
            </button>
            <?php endif; ?>
        </form>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get search trigger button
     */
    public function get_search_trigger($args = []) {
        $defaults = [
            'text' => __('Search', 'aqualuxe'),
            'show_text' => true,
            'show_icon' => true,
            'show_shortcut' => true,
            'class' => 'aqualuxe-search-trigger',
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        ob_start();
        ?>
        <button type="button" 
                class="<?php echo esc_attr($args['class']); ?>" 
                data-search-trigger="modal"
                aria-label="<?php esc_attr_e('Open search', 'aqualuxe'); ?>">
            
            <?php if ($args['show_icon']): ?>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <?php endif; ?>
            
            <?php if ($args['show_text']): ?>
            <span><?php echo esc_html($args['text']); ?></span>
            <?php endif; ?>
            
            <?php if ($args['show_shortcut']): ?>
            <div class="aqualuxe-search-shortcut">
                <kbd class="aqualuxe-search-shortcut-key">Ctrl</kbd>
                <kbd class="aqualuxe-search-shortcut-key">K</kbd>
            </div>
            <?php endif; ?>
        </button>
        <?php
        return ob_get_clean();
    }
    
    /**
     * AJAX live search
     */
    public function ajax_live_search() {
        check_ajax_referer('aqualuxe_search_nonce', 'nonce');
        
        $query = sanitize_text_field($_POST['query'] ?? '');
        $post_types = isset($_POST['post_types']) ? array_map('sanitize_text_field', $_POST['post_types']) : ['post', 'page'];
        $max_results = isset($_POST['max_results']) ? absint($_POST['max_results']) : 8;
        
        if (strlen($query) < get_theme_mod('aqualuxe_search_min_chars', 3)) {
            wp_send_json_success(['results' => []]);
        }
        
        $args = [
            'post_type' => $post_types,
            'posts_per_page' => $max_results,
            's' => $query,
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ],
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ];
        
        // Apply filters for customization
        $args = apply_filters('aqualuxe_live_search_args', $args, $query);
        
        $search_query = new WP_Query($args);
        $results = [];
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                
                $results[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'excerpt' => $this->get_search_excerpt(get_the_content(), $query),
                    'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
                    'type' => get_post_type(),
                    'date' => get_the_date(),
                    'author' => get_the_author(),
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success([
            'results' => $results,
            'total' => $search_query->found_posts,
            'query' => $query,
        ]);
    }
    
    /**
     * AJAX search suggestions
     */
    public function ajax_search_suggestions() {
        check_ajax_referer('aqualuxe_search_nonce', 'nonce');
        
        $suggestions = $this->get_search_suggestions();
        
        wp_send_json_success([
            'suggestions' => $suggestions,
        ]);
    }
    
    /**
     * Get search suggestions
     */
    private function get_search_suggestions() {
        // Get popular search terms (you could store these in options or get from analytics)
        $popular_terms = get_theme_mod('aqualuxe_search_popular_terms', [
            'WordPress',
            'Design',
            'Development',
            'Tutorial',
            'Tips',
        ]);
        
        // Get recent searches from user session
        $recent_searches = [];
        if (isset($_SESSION['aqualuxe_recent_searches'])) {
            $recent_searches = array_slice($_SESSION['aqualuxe_recent_searches'], 0, 5);
        }
        
        // Get popular tags/categories
        $popular_tags = get_terms([
            'taxonomy' => 'post_tag',
            'orderby' => 'count',
            'order' => 'DESC',
            'number' => 5,
            'hide_empty' => true,
        ]);
        
        $tag_suggestions = [];
        if (!is_wp_error($popular_tags)) {
            $tag_suggestions = wp_list_pluck($popular_tags, 'name');
        }
        
        return [
            'popular' => $popular_terms,
            'recent' => $recent_searches,
            'tags' => $tag_suggestions,
        ];
    }
    
    /**
     * Get search excerpt with highlighting
     */
    private function get_search_excerpt($content, $query, $length = 150) {
        $content = strip_tags($content);
        $content = strip_shortcodes($content);
        
        // Find the position of the search term
        $pos = stripos($content, $query);
        
        if ($pos !== false) {
            // Extract text around the search term
            $start = max(0, $pos - 50);
            $excerpt = substr($content, $start, $length);
            
            // Clean up the excerpt
            if ($start > 0) {
                $excerpt = '...' . $excerpt;
            }
            
            if (strlen($content) > $start + $length) {
                $excerpt .= '...';
            }
        } else {
            // Fallback to regular excerpt
            $excerpt = substr($content, 0, $length);
            if (strlen($content) > $length) {
                $excerpt .= '...';
            }
        }
        
        return $excerpt;
    }
    
    /**
     * Modify main search query
     */
    public function modify_search_query($query) {
        if (!is_admin() && $query->is_main_query() && $query->is_search()) {
            // Include custom post types in search
            $post_types = get_theme_mod('aqualuxe_search_post_types', ['post', 'page']);
            $query->set('post_type', $post_types);
            
            // Improve search relevance
            $search_terms = explode(' ', get_search_query());
            
            if (count($search_terms) > 1) {
                $meta_query = [
                    'relation' => 'OR',
                ];
                
                foreach ($search_terms as $term) {
                    $meta_query[] = [
                        'key' => '_search_keywords',
                        'value' => $term,
                        'compare' => 'LIKE'
                    ];
                }
                
                $query->set('meta_query', $meta_query);
            }
        }
    }
    
    /**
     * Highlight search terms in content
     */
    public function highlight_search_terms($content) {
        if (is_search() && !is_admin()) {
            $search_terms = explode(' ', get_search_query());
            
            foreach ($search_terms as $term) {
                if (strlen($term) > 2) {
                    $content = preg_replace(
                        '/(' . preg_quote($term, '/') . ')/i',
                        '<span class="search-highlight">$1</span>',
                        $content
                    );
                }
            }
        }
        
        return $content;
    }
    
    /**
     * Highlight search terms in titles
     */
    public function highlight_search_terms_in_title($title) {
        if (is_search() && !is_admin() && in_the_loop()) {
            $search_terms = explode(' ', get_search_query());
            
            foreach ($search_terms as $term) {
                if (strlen($term) > 2) {
                    $title = preg_replace(
                        '/(' . preg_quote($term, '/') . ')/i',
                        '<span class="search-highlight">$1</span>',
                        $title
                    );
                }
            }
        }
        
        return $title;
    }
    
    /**
     * Track search analytics
     */
    public function track_search_analytics() {
        if (is_search() && !is_admin()) {
            $search_query = get_search_query();
            
            if (!empty($search_query)) {
                // Store in session for recent searches
                if (!session_id()) {
                    session_start();
                }
                
                if (!isset($_SESSION['aqualuxe_recent_searches'])) {
                    $_SESSION['aqualuxe_recent_searches'] = [];
                }
                
                // Add to recent searches (avoid duplicates)
                $recent = $_SESSION['aqualuxe_recent_searches'];
                $recent = array_diff($recent, [$search_query]);
                array_unshift($recent, $search_query);
                $_SESSION['aqualuxe_recent_searches'] = array_slice($recent, 0, 10);
                
                // Track in analytics if available
                if (function_exists('gtag')) {
                    echo '<script>gtag("event", "search", { search_term: "' . esc_js($search_query) . '" });</script>';
                }
            }
        }
    }
    
    /**
     * Shortcodes
     */
    
    /**
     * Search form shortcode
     */
    public function search_form_shortcode($atts) {
        $atts = shortcode_atts([
            'placeholder' => __('Search...', 'aqualuxe'),
            'show_button' => true,
            'button_text' => __('Search', 'aqualuxe'),
            'type' => 'standard',
            'class' => 'aqualuxe-search-form',
        ], $atts);
        
        return $this->get_search_form($atts);
    }
    
    /**
     * Search results shortcode
     */
    public function search_results_shortcode($atts) {
        $atts = shortcode_atts([
            'posts_per_page' => 10,
            'post_types' => 'post,page',
            'show_excerpt' => true,
            'show_date' => true,
            'show_author' => true,
        ], $atts);
        
        if (!is_search()) {
            return '<p>' . __('This shortcode only works on search result pages.', 'aqualuxe') . '</p>';
        }
        
        $post_types = explode(',', $atts['post_types']);
        $post_types = array_map('trim', $post_types);
        
        $search_query = new WP_Query([
            'post_type' => $post_types,
            'posts_per_page' => $atts['posts_per_page'],
            's' => get_search_query(),
            'post_status' => 'publish',
        ]);
        
        if (!$search_query->have_posts()) {
            return '<p>' . __('No results found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="aqualuxe-search-results-grid">
            <?php while ($search_query->have_posts()): $search_query->the_post(); ?>
            <article class="aqualuxe-search-result-item">
                <?php if (has_post_thumbnail()): ?>
                <div class="search-result-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="search-result-content">
                    <h3 class="search-result-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    
                    <?php if ($atts['show_excerpt']): ?>
                    <div class="search-result-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($atts['show_date'] || $atts['show_author']): ?>
                    <div class="search-result-meta">
                        <?php if ($atts['show_date']): ?>
                        <span class="search-result-date"><?php the_date(); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_author']): ?>
                        <span class="search-result-author"><?php the_author(); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * REST API endpoints
     */
    public function register_rest_endpoints() {
        register_rest_route('aqualuxe/v1', '/search', [
            'methods' => 'GET',
            'callback' => [$this, 'rest_search'],
            'permission_callback' => '__return_true',
            'args' => [
                'q' => [
                    'required' => true,
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'post_type' => [
                    'default' => 'post',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'per_page' => [
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                ],
            ],
        ]);
    }
    
    /**
     * REST search endpoint
     */
    public function rest_search($request) {
        $query = $request->get_param('q');
        $post_type = $request->get_param('post_type');
        $per_page = $request->get_param('per_page');
        
        $search_query = new WP_Query([
            'post_type' => $post_type,
            'posts_per_page' => $per_page,
            's' => $query,
            'post_status' => 'publish',
        ]);
        
        $results = [];
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                
                $results[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'excerpt' => get_the_excerpt(),
                    'date' => get_the_date('c'),
                    'type' => get_post_type(),
                ];
            }
            wp_reset_postdata();
        }
        
        return rest_ensure_response([
            'results' => $results,
            'total' => $search_query->found_posts,
            'query' => $query,
        ]);
    }
    
    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Search section
        $wp_customize->add_section('aqualuxe_search', [
            'title' => __('Search', 'aqualuxe'),
            'description' => __('Configure search functionality.', 'aqualuxe'),
            'priority' => 50,
        ]);
        
        // Enable enhanced search
        $wp_customize->add_setting('aqualuxe_enable_enhanced_search', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_enable_enhanced_search', [
            'label' => __('Enable Enhanced Search', 'aqualuxe'),
            'description' => __('Enable live search, suggestions, and enhanced search features.', 'aqualuxe'),
            'section' => 'aqualuxe_search',
            'type' => 'checkbox',
        ]);
        
        // Live search
        $wp_customize->add_setting('aqualuxe_search_live_search', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_search_live_search', [
            'label' => __('Enable Live Search', 'aqualuxe'),
            'description' => __('Show search results as the user types.', 'aqualuxe'),
            'section' => 'aqualuxe_search',
            'type' => 'checkbox',
        ]);
        
        // Search suggestions
        $wp_customize->add_setting('aqualuxe_search_suggestions', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ]);
        
        $wp_customize->add_control('aqualuxe_search_suggestions', [
            'label' => __('Enable Search Suggestions', 'aqualuxe'),
            'description' => __('Show popular and recent search terms.', 'aqualuxe'),
            'section' => 'aqualuxe_search',
            'type' => 'checkbox',
        ]);
        
        // Minimum characters
        $wp_customize->add_setting('aqualuxe_search_min_chars', [
            'default' => 3,
            'sanitize_callback' => 'absint',
        ]);
        
        $wp_customize->add_control('aqualuxe_search_min_chars', [
            'label' => __('Minimum Characters', 'aqualuxe'),
            'description' => __('Minimum number of characters before live search starts.', 'aqualuxe'),
            'section' => 'aqualuxe_search',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 10,
            ],
        ]);
        
        // Search delay
        $wp_customize->add_setting('aqualuxe_search_delay', [
            'default' => 300,
            'sanitize_callback' => 'absint',
        ]);
        
        $wp_customize->add_control('aqualuxe_search_delay', [
            'label' => __('Search Delay (ms)', 'aqualuxe'),
            'description' => __('Delay in milliseconds before performing live search.', 'aqualuxe'),
            'section' => 'aqualuxe_search',
            'type' => 'number',
            'input_attrs' => [
                'min' => 100,
                'max' => 1000,
                'step' => 50,
            ],
        ]);
    }
    
    /**
     * Public API methods
     */
    
    /**
     * Check if search is enabled
     */
    public function is_enabled() {
        return $this->enabled;
    }
    
    /**
     * Perform search
     */
    public function search($query, $args = []) {
        $defaults = [
            'post_type' => ['post', 'page'],
            'posts_per_page' => 10,
            'post_status' => 'publish',
        ];
        
        $args = wp_parse_args($args, $defaults);
        $args['s'] = $query;
        
        return new WP_Query($args);
    }
}
