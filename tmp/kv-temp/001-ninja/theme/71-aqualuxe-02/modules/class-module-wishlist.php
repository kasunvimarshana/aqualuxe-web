<?php
/**
 * Wishlist Module
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Wishlist module class
 */
class Module_Wishlist {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Module enabled
     */
    private $enabled = true;
    
    /**
     * Wishlist table name
     */
    private $table_name;
    
    /**
     * Constructor
     */
    private function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'aqualuxe_wishlist';
        $this->enabled = get_theme_mod('aqualuxe_enable_wishlist', true);
        
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
        // Database setup
        add_action('after_switch_theme', [$this, 'create_table']);
        
        // Enqueue assets
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', [$this, 'ajax_add_to_wishlist']);
        add_action('wp_ajax_aqualuxe_remove_from_wishlist', [$this, 'ajax_remove_from_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_remove_from_wishlist', [$this, 'ajax_remove_from_wishlist']);
        add_action('wp_ajax_aqualuxe_get_wishlist', [$this, 'ajax_get_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_get_wishlist', [$this, 'ajax_get_wishlist']);
        add_action('wp_ajax_aqualuxe_clear_wishlist', [$this, 'ajax_clear_wishlist']);
        add_action('wp_ajax_nopriv_aqualuxe_clear_wishlist', [$this, 'ajax_clear_wishlist']);
        
        // WooCommerce integration
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_after_single_product_summary', [$this, 'add_wishlist_button'], 25);
            add_action('woocommerce_after_shop_loop_item', [$this, 'add_loop_wishlist_button'], 15);
        }
        
        // Regular posts integration
        add_filter('the_content', [$this, 'add_post_wishlist_button']);
        
        // Shortcodes
        add_shortcode('aqualuxe_wishlist', [$this, 'wishlist_shortcode']);
        add_shortcode('aqualuxe_wishlist_button', [$this, 'wishlist_button_shortcode']);
        add_shortcode('aqualuxe_wishlist_count', [$this, 'wishlist_count_shortcode']);
        
        // REST API
        add_action('rest_api_init', [$this, 'register_rest_endpoints']);
        
        // Admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Customizer
        add_action('customize_register', [$this, 'add_customizer_controls']);
        
        // User cleanup
        add_action('delete_user', [$this, 'cleanup_user_wishlist']);
        
        // Post cleanup
        add_action('before_delete_post', [$this, 'cleanup_post_wishlist']);
    }
    
    /**
     * Create wishlist table
     */
    public function create_table() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL DEFAULT 0,
            session_id varchar(255) NOT NULL DEFAULT '',
            post_id bigint(20) NOT NULL,
            date_added datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_item (user_id, session_id, post_id),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY post_id (post_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        // Wishlist CSS
        $wishlist_css = "
            .aqualuxe-wishlist-button {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: transparent;
                border: 1px solid currentColor;
                border-radius: 0.375rem;
                color: inherit;
                text-decoration: none;
                font-size: 0.875rem;
                transition: all 0.3s ease;
                cursor: pointer;
                position: relative;
                overflow: hidden;
            }
            
            .aqualuxe-wishlist-button:hover {
                background: rgba(79, 70, 229, 0.1);
                border-color: #4f46e5;
                color: #4f46e5;
                transform: translateY(-1px);
            }
            
            .aqualuxe-wishlist-button.in-wishlist {
                background: #dc2626;
                border-color: #dc2626;
                color: white;
            }
            
            .aqualuxe-wishlist-button.in-wishlist:hover {
                background: #b91c1c;
                border-color: #b91c1c;
            }
            
            .aqualuxe-wishlist-button .icon {
                width: 1rem;
                height: 1rem;
                transition: transform 0.3s ease;
            }
            
            .aqualuxe-wishlist-button:hover .icon {
                transform: scale(1.1);
            }
            
            .aqualuxe-wishlist-button.loading {
                opacity: 0.7;
                pointer-events: none;
            }
            
            .aqualuxe-wishlist-button.loading .icon {
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            /* Wishlist page styles */
            .aqualuxe-wishlist-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 2rem 1rem;
            }
            
            .aqualuxe-wishlist-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 2rem;
                margin-top: 2rem;
            }
            
            .aqualuxe-wishlist-item {
                border: 1px solid var(--color-border);
                border-radius: 0.5rem;
                overflow: hidden;
                transition: all 0.3s ease;
                background: white;
            }
            
            .aqualuxe-wishlist-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            }
            
            .aqualuxe-wishlist-item-image {
                position: relative;
                aspect-ratio: 4/3;
                overflow: hidden;
            }
            
            .aqualuxe-wishlist-item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }
            
            .aqualuxe-wishlist-item:hover .aqualuxe-wishlist-item-image img {
                transform: scale(1.05);
            }
            
            .aqualuxe-wishlist-item-content {
                padding: 1.5rem;
            }
            
            .aqualuxe-wishlist-item-title {
                font-size: 1.125rem;
                font-weight: 600;
                margin: 0 0 0.5rem 0;
                line-height: 1.4;
            }
            
            .aqualuxe-wishlist-item-title a {
                color: inherit;
                text-decoration: none;
            }
            
            .aqualuxe-wishlist-item-title a:hover {
                color: #4f46e5;
            }
            
            .aqualuxe-wishlist-item-price {
                font-size: 1.25rem;
                font-weight: 700;
                color: #059669;
                margin: 0.5rem 0;
            }
            
            .aqualuxe-wishlist-item-actions {
                display: flex;
                gap: 0.75rem;
                margin-top: 1rem;
            }
            
            .aqualuxe-wishlist-empty {
                text-align: center;
                padding: 4rem 2rem;
            }
            
            .aqualuxe-wishlist-empty-icon {
                width: 4rem;
                height: 4rem;
                margin: 0 auto 1rem;
                opacity: 0.3;
            }
            
            .aqualuxe-wishlist-count {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.875rem;
                color: #6b7280;
            }
            
            /* Floating wishlist button */
            .aqualuxe-wishlist-floating {
                position: fixed;
                bottom: 2rem;
                right: 2rem;
                z-index: 1000;
                background: #4f46e5;
                color: white;
                border: none;
                border-radius: 50%;
                width: 3.5rem;
                height: 3.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
                transition: all 0.3s ease;
            }
            
            .aqualuxe-wishlist-floating:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(79, 70, 229, 0.6);
            }
            
            .aqualuxe-wishlist-floating .count {
                position: absolute;
                top: -0.25rem;
                right: -0.25rem;
                background: #dc2626;
                color: white;
                border-radius: 50%;
                width: 1.25rem;
                height: 1.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.625rem;
                font-weight: 700;
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $wishlist_css);
        
        // Wishlist JavaScript
        wp_enqueue_script(
            'aqualuxe-wishlist',
            AQUALUXE_THEME_URL . '/modules/wishlist/assets/wishlist.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-wishlist', 'aqualuxeWishlist', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_wishlist_nonce'),
            'enabled' => $this->enabled,
            'userId' => get_current_user_id(),
            'sessionId' => $this->get_session_id(),
            'strings' => [
                'addToWishlist' => __('Add to Wishlist', 'aqualuxe'),
                'removeFromWishlist' => __('Remove from Wishlist', 'aqualuxe'),
                'addedToWishlist' => __('Added to wishlist!', 'aqualuxe'),
                'removedFromWishlist' => __('Removed from wishlist!', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'emptyWishlist' => __('Your wishlist is empty', 'aqualuxe'),
                'continueShopping' => __('Continue Shopping', 'aqualuxe'),
            ],
        ]);
    }
    
    /**
     * Add wishlist button to single product
     */
    public function add_wishlist_button() {
        if (!$this->enabled) {
            return;
        }
        
        global $product;
        if (!$product) {
            return;
        }
        
        echo $this->get_wishlist_button($product->get_id());
    }
    
    /**
     * Add wishlist button to product loop
     */
    public function add_loop_wishlist_button() {
        if (!$this->enabled) {
            return;
        }
        
        global $product;
        if (!$product) {
            return;
        }
        
        echo $this->get_wishlist_button($product->get_id(), ['size' => 'small']);
    }
    
    /**
     * Add wishlist button to post content
     */
    public function add_post_wishlist_button($content) {
        if (!$this->enabled || !is_single() || !in_the_loop()) {
            return $content;
        }
        
        $post_types = get_theme_mod('aqualuxe_wishlist_post_types', ['post']);
        if (!in_array(get_post_type(), $post_types)) {
            return $content;
        }
        
        $button = $this->get_wishlist_button(get_the_ID());
        $position = get_theme_mod('aqualuxe_wishlist_content_position', 'after');
        
        if ($position === 'before') {
            return $button . $content;
        } else {
            return $content . $button;
        }
    }
    
    /**
     * Get wishlist button HTML
     */
    public function get_wishlist_button($post_id, $args = []) {
        $defaults = [
            'text' => true,
            'icon' => true,
            'size' => 'normal',
            'style' => 'button',
            'class' => '',
        ];
        
        $args = wp_parse_args($args, $defaults);
        $is_in_wishlist = $this->is_in_wishlist($post_id);
        
        $classes = [
            'aqualuxe-wishlist-button',
            'size-' . $args['size'],
            'style-' . $args['style'],
            $args['class']
        ];
        
        if ($is_in_wishlist) {
            $classes[] = 'in-wishlist';
        }
        
        $button_text = $is_in_wishlist ? 
            __('Remove from Wishlist', 'aqualuxe') : 
            __('Add to Wishlist', 'aqualuxe');
        
        $icon = $is_in_wishlist ? 
            '<svg class="icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>' :
            '<svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>';
        
        $output = '<button type="button" 
                    class="' . esc_attr(implode(' ', $classes)) . '" 
                    data-post-id="' . esc_attr($post_id) . '"
                    data-action="' . ($is_in_wishlist ? 'remove' : 'add') . '"
                    title="' . esc_attr($button_text) . '">';
        
        if ($args['icon']) {
            $output .= $icon;
        }
        
        if ($args['text']) {
            $output .= '<span class="text">' . esc_html($button_text) . '</span>';
        }
        
        $output .= '</button>';
        
        return $output;
    }
    
    /**
     * Check if item is in wishlist
     */
    public function is_in_wishlist($post_id, $user_id = null, $session_id = null) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        $where = "post_id = %d AND (";
        $params = [$post_id];
        
        if ($user_id) {
            $where .= "user_id = %d";
            $params[] = $user_id;
        } else {
            $where .= "user_id = 0";
        }
        
        if ($session_id) {
            $where .= " OR session_id = %s";
            $params[] = $session_id;
        }
        
        $where .= ")";
        
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} WHERE {$where}",
            $params
        ));
        
        return $count > 0;
    }
    
    /**
     * Add item to wishlist
     */
    public function add_to_wishlist($post_id, $user_id = null, $session_id = null) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        // Check if already in wishlist
        if ($this->is_in_wishlist($post_id, $user_id, $session_id)) {
            return false;
        }
        
        $result = $wpdb->insert(
            $this->table_name,
            [
                'user_id' => $user_id,
                'session_id' => $session_id,
                'post_id' => $post_id,
                'date_added' => current_time('mysql'),
            ],
            ['%d', '%s', '%d', '%s']
        );
        
        if ($result) {
            do_action('aqualuxe_added_to_wishlist', $post_id, $user_id, $session_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove item from wishlist
     */
    public function remove_from_wishlist($post_id, $user_id = null, $session_id = null) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        $where = ['post_id' => $post_id];
        $where_format = ['%d'];
        
        if ($user_id) {
            $where['user_id'] = $user_id;
            $where_format[] = '%d';
        } else {
            $where['session_id'] = $session_id;
            $where_format[] = '%s';
        }
        
        $result = $wpdb->delete($this->table_name, $where, $where_format);
        
        if ($result) {
            do_action('aqualuxe_removed_from_wishlist', $post_id, $user_id, $session_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Get wishlist items
     */
    public function get_wishlist($user_id = null, $session_id = null, $limit = null, $offset = 0) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        $where = "";
        $params = [];
        
        if ($user_id) {
            $where .= "user_id = %d";
            $params[] = $user_id;
        } else {
            $where .= "session_id = %s";
            $params[] = $session_id;
        }
        
        $sql = "SELECT * FROM {$this->table_name} WHERE {$where} ORDER BY date_added DESC";
        
        if ($limit) {
            $sql .= " LIMIT %d OFFSET %d";
            $params[] = absint($limit);
            $params[] = absint($offset);
        }
        
        // Enhanced security: Ensure we have params if we have placeholders
        if (empty($params) && strpos($sql, '%') !== false) {
            return [];
        }
        
        return $wpdb->get_results($wpdb->prepare($sql, $params));
    }
    
    /**
     * Get wishlist count
     */
    public function get_wishlist_count($user_id = null, $session_id = null) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        $where = "";
        $params = [];
        
        if ($user_id) {
            $where .= "user_id = %d";
            $params[] = $user_id;
        } else {
            $where .= "session_id = %s";
            $params[] = $session_id;
        }
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$this->table_name} WHERE {$where}",
            $params
        ));
    }
    
    /**
     * Clear wishlist
     */
    public function clear_wishlist($user_id = null, $session_id = null) {
        global $wpdb;
        
        if ($user_id === null) {
            $user_id = get_current_user_id();
        }
        
        if ($session_id === null) {
            $session_id = $this->get_session_id();
        }
        
        $where = [];
        $where_format = [];
        
        if ($user_id) {
            $where['user_id'] = $user_id;
            $where_format[] = '%d';
        } else {
            $where['session_id'] = $session_id;
            $where_format[] = '%s';
        }
        
        $result = $wpdb->delete($this->table_name, $where, $where_format);
        
        if ($result) {
            do_action('aqualuxe_wishlist_cleared', $user_id, $session_id);
            return true;
        }
        
        return false;
    }
    
    /**
     * Get session ID
     */
    private function get_session_id() {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['aqualuxe_session_id'])) {
            $_SESSION['aqualuxe_session_id'] = wp_generate_uuid4();
        }
        
        return $_SESSION['aqualuxe_session_id'];
    }
    
    /**
     * AJAX handlers
     */
    
    /**
     * AJAX add to wishlist
     */
    public function ajax_add_to_wishlist() {
        check_ajax_referer('aqualuxe_wishlist_nonce', 'nonce');
        
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        
        if (!$post_id || !get_post($post_id)) {
            wp_send_json_error(__('Invalid item.', 'aqualuxe'));
        }
        
        $result = $this->add_to_wishlist($post_id);
        
        if ($result) {
            wp_send_json_success([
                'message' => __('Added to wishlist!', 'aqualuxe'),
                'count' => $this->get_wishlist_count(),
                'button_html' => $this->get_wishlist_button($post_id),
            ]);
        } else {
            wp_send_json_error(__('Failed to add to wishlist.', 'aqualuxe'));
        }
    }
    
    /**
     * AJAX remove from wishlist
     */
    public function ajax_remove_from_wishlist() {
        check_ajax_referer('aqualuxe_wishlist_nonce', 'nonce');
        
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error(__('Invalid item.', 'aqualuxe'));
        }
        
        $result = $this->remove_from_wishlist($post_id);
        
        if ($result) {
            wp_send_json_success([
                'message' => __('Removed from wishlist!', 'aqualuxe'),
                'count' => $this->get_wishlist_count(),
                'button_html' => $this->get_wishlist_button($post_id),
            ]);
        } else {
            wp_send_json_error(__('Failed to remove from wishlist.', 'aqualuxe'));
        }
    }
    
    /**
     * AJAX get wishlist
     */
    public function ajax_get_wishlist() {
        check_ajax_referer('aqualuxe_wishlist_nonce', 'nonce');
        
        $page = isset($_POST['page']) ? absint($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? absint($_POST['per_page']) : 12;
        $offset = ($page - 1) * $per_page;
        
        $items = $this->get_wishlist(null, null, $per_page, $offset);
        $total = $this->get_wishlist_count();
        
        $formatted_items = [];
        foreach ($items as $item) {
            $post = get_post($item->post_id);
            if ($post) {
                $formatted_items[] = [
                    'id' => $item->post_id,
                    'title' => get_the_title($item->post_id),
                    'url' => get_permalink($item->post_id),
                    'image' => get_the_post_thumbnail_url($item->post_id, 'medium'),
                    'excerpt' => get_the_excerpt($item->post_id),
                    'date_added' => $item->date_added,
                    'price' => $this->get_item_price($item->post_id),
                ];
            }
        }
        
        wp_send_json_success([
            'items' => $formatted_items,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil($total / $per_page),
        ]);
    }
    
    /**
     * AJAX clear wishlist
     */
    public function ajax_clear_wishlist() {
        check_ajax_referer('aqualuxe_wishlist_nonce', 'nonce');
        
        $result = $this->clear_wishlist();
        
        if ($result) {
            wp_send_json_success([
                'message' => __('Wishlist cleared!', 'aqualuxe'),
                'count' => 0,
            ]);
        } else {
            wp_send_json_error(__('Failed to clear wishlist.', 'aqualuxe'));
        }
    }
    
    /**
     * Get item price (WooCommerce integration)
     */
    private function get_item_price($post_id) {
        if (class_exists('WooCommerce')) {
            $product = wc_get_product($post_id);
            if ($product) {
                return $product->get_price_html();
            }
        }
        
        return '';
    }
    
    /**
     * Shortcodes
     */
    
    /**
     * Wishlist shortcode
     */
    public function wishlist_shortcode($atts) {
        $atts = shortcode_atts([
            'columns' => 3,
            'per_page' => 12,
            'show_pagination' => true,
        ], $atts);
        
        if (!$this->enabled) {
            return '<p>' . __('Wishlist is disabled.', 'aqualuxe') . '</p>';
        }
        
        $items = $this->get_wishlist();
        
        if (empty($items)) {
            return $this->get_empty_wishlist_html();
        }
        
        ob_start();
        ?>
        <div class="aqualuxe-wishlist-container">
            <div class="aqualuxe-wishlist-grid" style="grid-template-columns: repeat(<?php echo absint($atts['columns']); ?>, 1fr);">
                <?php foreach ($items as $item): 
                    $post = get_post($item->post_id);
                    if (!$post) continue;
                ?>
                <div class="aqualuxe-wishlist-item" data-post-id="<?php echo esc_attr($item->post_id); ?>">
                    <div class="aqualuxe-wishlist-item-image">
                        <a href="<?php echo esc_url(get_permalink($item->post_id)); ?>">
                            <?php echo get_the_post_thumbnail($item->post_id, 'medium'); ?>
                        </a>
                    </div>
                    <div class="aqualuxe-wishlist-item-content">
                        <h3 class="aqualuxe-wishlist-item-title">
                            <a href="<?php echo esc_url(get_permalink($item->post_id)); ?>">
                                <?php echo esc_html(get_the_title($item->post_id)); ?>
                            </a>
                        </h3>
                        <?php if ($price = $this->get_item_price($item->post_id)): ?>
                        <div class="aqualuxe-wishlist-item-price"><?php echo $price; ?></div>
                        <?php endif; ?>
                        <div class="aqualuxe-wishlist-item-actions">
                            <?php echo $this->get_wishlist_button($item->post_id); ?>
                            <a href="<?php echo esc_url(get_permalink($item->post_id)); ?>" class="button">
                                <?php _e('View Item', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Wishlist button shortcode
     */
    public function wishlist_button_shortcode($atts) {
        $atts = shortcode_atts([
            'post_id' => get_the_ID(),
            'text' => true,
            'icon' => true,
            'size' => 'normal',
            'style' => 'button',
        ], $atts);
        
        if (!$this->enabled) {
            return '';
        }
        
        return $this->get_wishlist_button($atts['post_id'], $atts);
    }
    
    /**
     * Wishlist count shortcode
     */
    public function wishlist_count_shortcode($atts) {
        $atts = shortcode_atts([
            'text' => true,
            'icon' => true,
        ], $atts);
        
        if (!$this->enabled) {
            return '';
        }
        
        $count = $this->get_wishlist_count();
        $output = '<span class="aqualuxe-wishlist-count">';
        
        if ($atts['icon']) {
            $output .= '<svg class="icon" width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>';
        }
        
        if ($atts['text']) {
            $output .= sprintf(_n('%d item', '%d items', $count, 'aqualuxe'), $count);
        } else {
            $output .= $count;
        }
        
        $output .= '</span>';
        
        return $output;
    }
    
    /**
     * Get empty wishlist HTML
     */
    private function get_empty_wishlist_html() {
        ob_start();
        ?>
        <div class="aqualuxe-wishlist-empty">
            <svg class="aqualuxe-wishlist-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3><?php _e('Your wishlist is empty', 'aqualuxe'); ?></h3>
            <p><?php _e('Start adding items to your wishlist by clicking the heart icon on products you love.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button">
                <?php _e('Continue Shopping', 'aqualuxe'); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * REST API endpoints
     */
    public function register_rest_endpoints() {
        register_rest_route('aqualuxe/v1', '/wishlist', [
            'methods' => 'GET',
            'callback' => [$this, 'rest_get_wishlist'],
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/wishlist', [
            'methods' => 'POST',
            'callback' => [$this, 'rest_add_to_wishlist'],
            'permission_callback' => '__return_true',
        ]);
        
        register_rest_route('aqualuxe/v1', '/wishlist/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'rest_remove_from_wishlist'],
            'permission_callback' => '__return_true',
        ]);
    }
    
    /**
     * REST get wishlist
     */
    public function rest_get_wishlist($request) {
        $items = $this->get_wishlist();
        $formatted_items = [];
        
        foreach ($items as $item) {
            $post = get_post($item->post_id);
            if ($post) {
                $formatted_items[] = [
                    'id' => $item->post_id,
                    'title' => get_the_title($item->post_id),
                    'url' => get_permalink($item->post_id),
                    'image' => get_the_post_thumbnail_url($item->post_id, 'medium'),
                    'date_added' => $item->date_added,
                ];
            }
        }
        
        return rest_ensure_response($formatted_items);
    }
    
    /**
     * REST add to wishlist
     */
    public function rest_add_to_wishlist($request) {
        $post_id = $request->get_param('post_id');
        
        if (!$post_id || !get_post($post_id)) {
            return new WP_Error('invalid_post', 'Invalid post ID', ['status' => 400]);
        }
        
        $result = $this->add_to_wishlist($post_id);
        
        if ($result) {
            return rest_ensure_response(['success' => true, 'message' => 'Added to wishlist']);
        } else {
            return new WP_Error('add_failed', 'Failed to add to wishlist', ['status' => 500]);
        }
    }
    
    /**
     * REST remove from wishlist
     */
    public function rest_remove_from_wishlist($request) {
        $post_id = $request->get_param('id');
        
        if (!$post_id) {
            return new WP_Error('invalid_post', 'Invalid post ID', ['status' => 400]);
        }
        
        $result = $this->remove_from_wishlist($post_id);
        
        if ($result) {
            return rest_ensure_response(['success' => true, 'message' => 'Removed from wishlist']);
        } else {
            return new WP_Error('remove_failed', 'Failed to remove from wishlist', ['status' => 500]);
        }
    }
    
    /**
     * Admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __('Wishlist', 'aqualuxe'),
            __('Wishlist', 'aqualuxe'),
            'manage_options',
            'aqualuxe-wishlist',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        // Admin page implementation would go here
        echo '<div class="wrap"><h1>' . __('Wishlist Management', 'aqualuxe') . '</h1></div>';
    }
    
    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Wishlist section
        $wp_customize->add_section('aqualuxe_wishlist', [
            'title' => __('Wishlist', 'aqualuxe'),
            'description' => __('Configure wishlist functionality.', 'aqualuxe'),
            'priority' => 40,
        ]);
        
        // Enable wishlist
        $wp_customize->add_setting('aqualuxe_enable_wishlist', [
            'default' => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('aqualuxe_enable_wishlist', [
            'label' => __('Enable Wishlist', 'aqualuxe'),
            'description' => __('Allow users to add items to their wishlist.', 'aqualuxe'),
            'section' => 'aqualuxe_wishlist',
            'type' => 'checkbox',
        ]);
        
        // Post types
        $wp_customize->add_setting('aqualuxe_wishlist_post_types', [
            'default' => ['post'],
            'sanitize_callback' => [$this, 'sanitize_post_types'],
        ]);
        
        $wp_customize->add_control('aqualuxe_wishlist_post_types', [
            'label' => __('Enabled Post Types', 'aqualuxe'),
            'description' => __('Select which post types should have wishlist functionality.', 'aqualuxe'),
            'section' => 'aqualuxe_wishlist',
            'type' => 'select',
            'multiple' => true,
            'choices' => $this->get_post_type_choices(),
        ]);
    }
    
    /**
     * Get post type choices for customizer
     */
    private function get_post_type_choices() {
        $post_types = get_post_types(['public' => true], 'objects');
        $choices = [];
        
        foreach ($post_types as $post_type) {
            $choices[$post_type->name] = $post_type->label;
        }
        
        return $choices;
    }
    
    /**
     * Sanitize post types
     */
    public function sanitize_post_types($input) {
        if (!is_array($input)) {
            return ['post'];
        }
        
        $valid_post_types = array_keys(get_post_types(['public' => true]));
        return array_intersect($input, $valid_post_types);
    }
    
    /**
     * Cleanup functions
     */
    
    /**
     * Cleanup user wishlist on user deletion
     */
    public function cleanup_user_wishlist($user_id) {
        global $wpdb;
        $wpdb->delete($this->table_name, ['user_id' => $user_id], ['%d']);
    }
    
    /**
     * Cleanup post wishlist on post deletion
     */
    public function cleanup_post_wishlist($post_id) {
        global $wpdb;
        $wpdb->delete($this->table_name, ['post_id' => $post_id], ['%d']);
    }
    
    /**
     * Public API methods
     */
    
    /**
     * Check if module is enabled
     */
    public function is_enabled() {
        return $this->enabled;
    }
    
    /**
     * Get table name
     */
    public function get_table_name() {
        return $this->table_name;
    }
}
