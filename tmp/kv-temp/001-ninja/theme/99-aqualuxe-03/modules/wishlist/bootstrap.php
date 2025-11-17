<?php
/**
 * Wishlist Module Bootstrap
 *
 * @package AquaLuxe\Modules\Wishlist
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Wishlist Module Class
 */
class AquaLuxe_Wishlist {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_add_to_wishlist', array($this, 'ajax_add_to_wishlist'));
        add_action('wp_ajax_nopriv_add_to_wishlist', array($this, 'ajax_add_to_wishlist'));
        add_action('wp_ajax_remove_from_wishlist', array($this, 'ajax_remove_from_wishlist'));
        add_action('wp_ajax_nopriv_remove_from_wishlist', array($this, 'ajax_remove_from_wishlist'));
        
        // WooCommerce hooks - only if WooCommerce is active
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_after_single_product_summary', array($this, 'add_wishlist_button'), 25);
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_loop_wishlist_button'), 15);
        }
    }
    
    /**
     * Initialize
     */
    public function init() {
        // Create wishlist page if it doesn't exist
        $this->create_wishlist_page();
        
        // Add shortcode
        add_shortcode('aqualuxe_wishlist', array($this, 'wishlist_shortcode'));
    }
    
    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-wishlist',
            AQUALUXE_THEME_URI . '/modules/wishlist/assets/wishlist.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-wishlist', 'aqualuxe_wishlist', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wishlist_nonce'),
            'add_text' => __('Add to Wishlist', 'aqualuxe'),
            'remove_text' => __('Remove from Wishlist', 'aqualuxe'),
            'view_text' => __('View Wishlist', 'aqualuxe'),
            'wishlist_url' => $this->get_wishlist_url(),
        ));
    }
    
    /**
     * Get user wishlist
     */
    public function get_user_wishlist($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            // For guests, use session
            return $this->get_guest_wishlist();
        }
        
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        return is_array($wishlist) ? $wishlist : array();
    }
    
    /**
     * Get guest wishlist from session
     */
    private function get_guest_wishlist() {
        if (!session_id()) {
            session_start();
        }
        
        return isset($_SESSION['aqualuxe_wishlist']) ? $_SESSION['aqualuxe_wishlist'] : array();
    }
    
    /**
     * Add item to wishlist
     */
    public function add_to_wishlist($product_id, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return $this->add_to_guest_wishlist($product_id);
        }
        
        $wishlist = $this->get_user_wishlist($user_id);
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
            return true;
        }
        
        return false;
    }
    
    /**
     * Add to guest wishlist
     */
    private function add_to_guest_wishlist($product_id) {
        if (!session_id()) {
            session_start();
        }
        
        if (!isset($_SESSION['aqualuxe_wishlist'])) {
            $_SESSION['aqualuxe_wishlist'] = array();
        }
        
        if (!in_array($product_id, $_SESSION['aqualuxe_wishlist'])) {
            $_SESSION['aqualuxe_wishlist'][] = $product_id;
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove item from wishlist
     */
    public function remove_from_wishlist($product_id, $user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        
        if (!$user_id) {
            return $this->remove_from_guest_wishlist($product_id);
        }
        
        $wishlist = $this->get_user_wishlist($user_id);
        $key = array_search($product_id, $wishlist);
        
        if ($key !== false) {
            unset($wishlist[$key]);
            update_user_meta($user_id, 'aqualuxe_wishlist', array_values($wishlist));
            return true;
        }
        
        return false;
    }
    
    /**
     * Remove from guest wishlist
     */
    private function remove_from_guest_wishlist($product_id) {
        if (!session_id()) {
            session_start();
        }
        
        if (isset($_SESSION['aqualuxe_wishlist'])) {
            $key = array_search($product_id, $_SESSION['aqualuxe_wishlist']);
            if ($key !== false) {
                unset($_SESSION['aqualuxe_wishlist'][$key]);
                $_SESSION['aqualuxe_wishlist'] = array_values($_SESSION['aqualuxe_wishlist']);
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if product is in wishlist
     */
    public function is_in_wishlist($product_id, $user_id = null) {
        $wishlist = $this->get_user_wishlist($user_id);
        return in_array($product_id, $wishlist);
    }
    
    /**
     * AJAX handler for add to wishlist
     */
    public function ajax_add_to_wishlist() {
        check_ajax_referer('wishlist_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        
        if (!$product_id) {
            wp_send_json_error(__('Invalid product ID', 'aqualuxe'));
        }
        
        $added = $this->add_to_wishlist($product_id);
        
        if ($added) {
            wp_send_json_success(array(
                'message' => __('Product added to wishlist', 'aqualuxe'),
                'count' => count($this->get_user_wishlist()),
            ));
        } else {
            wp_send_json_error(__('Product already in wishlist', 'aqualuxe'));
        }
    }
    
    /**
     * AJAX handler for remove from wishlist
     */
    public function ajax_remove_from_wishlist() {
        check_ajax_referer('wishlist_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        
        if (!$product_id) {
            wp_send_json_error(__('Invalid product ID', 'aqualuxe'));
        }
        
        $removed = $this->remove_from_wishlist($product_id);
        
        if ($removed) {
            wp_send_json_success(array(
                'message' => __('Product removed from wishlist', 'aqualuxe'),
                'count' => count($this->get_user_wishlist()),
            ));
        } else {
            wp_send_json_error(__('Product not in wishlist', 'aqualuxe'));
        }
    }
    
    /**
     * Add wishlist button to single product page
     */
    public function add_wishlist_button() {
        global $product;
        if (!$product) {
            return;
        }
        
        $product_id = $product->get_id();
        $is_in_wishlist = $this->is_in_wishlist($product_id);
        
        ?>
        <div class="aqualuxe-wishlist-button-wrapper">
            <button 
                class="aqualuxe-wishlist-button <?php echo $is_in_wishlist ? 'in-wishlist' : ''; ?>"
                data-product-id="<?php echo esc_attr($product_id); ?>"
                aria-label="<?php echo $is_in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe'); ?>"
            >
                <svg class="heart-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="wishlist-text">
                    <?php echo $is_in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe'); ?>
                </span>
            </button>
        </div>
        <?php
    }
    
    /**
     * Add wishlist button to product loop
     */
    public function add_loop_wishlist_button() {
        global $product;
        if (!$product) {
            return;
        }
        
        $product_id = $product->get_id();
        $is_in_wishlist = $this->is_in_wishlist($product_id);
        
        ?>
        <button 
            class="aqualuxe-wishlist-button loop-wishlist-button <?php echo $is_in_wishlist ? 'in-wishlist' : ''; ?>"
            data-product-id="<?php echo esc_attr($product_id); ?>"
            aria-label="<?php echo $is_in_wishlist ? __('Remove from Wishlist', 'aqualuxe') : __('Add to Wishlist', 'aqualuxe'); ?>"
        >
            <svg class="heart-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
        <?php
    }
    
    /**
     * Create wishlist page
     */
    private function create_wishlist_page() {
        $page_slug = 'wishlist';
        $page = get_page_by_path($page_slug);
        
        if (!$page) {
            $page_id = wp_insert_post(array(
                'post_title' => __('My Wishlist', 'aqualuxe'),
                'post_content' => '[aqualuxe_wishlist]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_slug,
            ));
            
            if ($page_id) {
                update_option('aqualuxe_wishlist_page_id', $page_id);
            }
        }
    }
    
    /**
     * Get wishlist URL
     */
    public function get_wishlist_url() {
        $page_id = get_option('aqualuxe_wishlist_page_id');
        if ($page_id) {
            return get_permalink($page_id);
        }
        return home_url('/wishlist/');
    }
    
    /**
     * Wishlist shortcode
     */
    public function wishlist_shortcode($atts) {
        $wishlist = $this->get_user_wishlist();
        
        ob_start();
        ?>
        <div class="wishlist-page">
            <h1><?php _e('My Wishlist', 'aqualuxe'); ?></h1>
            
            <?php if (empty($wishlist)) : ?>
                <div class="empty-wishlist">
                    <p><?php _e('Your wishlist is empty.', 'aqualuxe'); ?></p>
                    <?php if (class_exists('WooCommerce')) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                            <?php _e('Continue Shopping', 'aqualuxe'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <div class="wishlist-items">
                    <?php foreach ($wishlist as $product_id) : ?>
                        <?php if (class_exists('WooCommerce')) : ?>
                            <?php $product = wc_get_product($product_id); ?>
                            <?php if ($product) : ?>
                                <div class="wishlist-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                    <div class="item-image">
                                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                            <?php echo $product->get_image('medium'); ?>
                                        </a>
                                    </div>
                                    <div class="item-details">
                                        <h3><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
                                        <div class="item-price"><?php echo $product->get_price_html(); ?></div>
                                        <div class="item-actions">
                                            <?php if ($product->is_in_stock()) : ?>
                                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn btn-primary">
                                                    <?php _e('Add to Cart', 'aqualuxe'); ?>
                                                </a>
                                            <?php else : ?>
                                                <span class="out-of-stock"><?php _e('Out of Stock', 'aqualuxe'); ?></span>
                                            <?php endif; ?>
                                            <button class="remove-from-wishlist" data-product-id="<?php echo esc_attr($product_id); ?>">
                                                <?php _e('Remove', 'aqualuxe'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <?php $post = get_post($product_id); ?>
                            <?php if ($post) : ?>
                                <div class="wishlist-item" data-product-id="<?php echo esc_attr($product_id); ?>">
                                    <div class="item-details">
                                        <h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                                        <div class="item-actions">
                                            <button class="remove-from-wishlist" data-product-id="<?php echo esc_attr($product_id); ?>">
                                                <?php _e('Remove', 'aqualuxe'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the module
new AquaLuxe_Wishlist();