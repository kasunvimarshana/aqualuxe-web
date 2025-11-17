<?php
/**
 * WooCommerce Wishlist functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce Wishlist Class
 */
class AquaLuxe_WC_Wishlist {
    /**
     * Constructor
     */
    public function __construct() {
        // Only initialize if wishlist is enabled
        if (!get_theme_mod('wishlist', true)) {
            return;
        }
        
        // Add wishlist button
        add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 20);
        add_action('woocommerce_after_add_to_cart_button', array($this, 'wishlist_button'), 10);
        
        // Register AJAX handlers
        add_action('wp_ajax_aqualuxe_toggle_wishlist', array($this, 'toggle_wishlist'));
        add_action('wp_ajax_nopriv_aqualuxe_toggle_wishlist', array($this, 'toggle_wishlist_guest'));
        
        // Add wishlist page
        add_action('init', array($this, 'register_wishlist_endpoint'));
        add_filter('query_vars', array($this, 'add_wishlist_query_var'));
        add_filter('woocommerce_account_menu_items', array($this, 'add_wishlist_menu_item'));
        add_action('woocommerce_account_wishlist_endpoint', array($this, 'wishlist_content'));
        
        // Enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add wishlist count to header
        add_filter('aqualuxe_wishlist_count', array($this, 'get_wishlist_count'));
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        global $product;
        
        $wishlist = $this->get_wishlist();
        $in_wishlist = in_array($product->get_id(), $wishlist);
        $icon_class = $in_wishlist ? 'text-red-500 fill-current' : '';
        
        echo '<button class="wishlist-toggle inline-flex items-center p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 wishlist-icon ' . esc_attr($icon_class) . '" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
        echo '</svg>';
        echo '<span class="sr-only">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
        echo '</button>';
    }

    /**
     * Toggle wishlist
     */
    public function toggle_wishlist() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wishlist_nonce')) {
            wp_send_json_error('Invalid nonce');
            exit;
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
            exit;
        }
        
        $product_id = absint($_POST['product_id']);
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_error('User not logged in');
            exit;
        }
        
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!is_array($wishlist)) {
            $wishlist = array();
        }
        
        $in_wishlist = in_array($product_id, $wishlist);
        
        if ($in_wishlist) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist', 'aqualuxe');
            $status = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist', 'aqualuxe');
            $status = 'added';
        }
        
        update_user_meta($user_id, 'aqualuxe_wishlist', array_unique($wishlist));
        
        wp_send_json_success(array(
            'message' => $message,
            'status' => $status,
            'count' => count($wishlist),
        ));
        exit;
    }

    /**
     * Toggle wishlist for guest users
     */
    public function toggle_wishlist_guest() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wishlist_nonce')) {
            wp_send_json_error('Invalid nonce');
            exit;
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
            exit;
        }
        
        $product_id = absint($_POST['product_id']);
        
        // Get wishlist from cookie
        $wishlist = $this->get_wishlist();
        
        $in_wishlist = in_array($product_id, $wishlist);
        
        if ($in_wishlist) {
            // Remove from wishlist
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist', 'aqualuxe');
            $status = 'removed';
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist', 'aqualuxe');
            $status = 'added';
        }
        
        // Save wishlist to cookie
        $this->set_wishlist_cookie($wishlist);
        
        wp_send_json_success(array(
            'message' => $message,
            'status' => $status,
            'count' => count($wishlist),
        ));
        exit;
    }

    /**
     * Register wishlist endpoint
     */
    public function register_wishlist_endpoint() {
        add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
    }

    /**
     * Add wishlist query var
     *
     * @param array $vars Query vars.
     * @return array
     */
    public function add_wishlist_query_var($vars) {
        $vars[] = 'wishlist';
        return $vars;
    }

    /**
     * Add wishlist menu item
     *
     * @param array $items Menu items.
     * @return array
     */
    public function add_wishlist_menu_item($items) {
        // Add wishlist after dashboard
        $new_items = array();
        
        foreach ($items as $key => $value) {
            $new_items[$key] = $value;
            
            if ($key === 'dashboard') {
                $new_items['wishlist'] = __('Wishlist', 'aqualuxe');
            }
        }
        
        return $new_items;
    }

    /**
     * Wishlist content
     */
    public function wishlist_content() {
        $wishlist = $this->get_wishlist();
        
        echo '<div class="wishlist-content">';
        echo '<h2 class="text-2xl font-bold mb-6">' . esc_html__('My Wishlist', 'aqualuxe') . '</h2>';
        
        if (empty($wishlist)) {
            echo '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">';
            echo esc_html__('Your wishlist is empty.', 'aqualuxe');
            echo ' <a class="button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300 ml-4" href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Browse products', 'aqualuxe') . '</a>';
            echo '</div>';
        } else {
            echo '<div class="wishlist-products grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
            
            foreach ($wishlist as $product_id) {
                $product = wc_get_product($product_id);
                
                if (!$product) {
                    continue;
                }
                
                echo '<div class="wishlist-product bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">';
                
                // Product image
                echo '<a href="' . esc_url(get_permalink($product_id)) . '" class="block">';
                echo '<div class="product-image h-48 overflow-hidden">';
                echo $product->get_image('medium', array('class' => 'w-full h-full object-cover'));
                echo '</div>';
                echo '</a>';
                
                // Product details
                echo '<div class="product-details p-4">';
                echo '<h3 class="text-lg font-bold mb-2"><a href="' . esc_url(get_permalink($product_id)) . '" class="hover:text-primary dark:hover:text-primary-dark transition-colors duration-300">' . esc_html($product->get_name()) . '</a></h3>';
                echo '<div class="price mb-4">' . $product->get_price_html() . '</div>';
                
                // Actions
                echo '<div class="flex space-x-2">';
                
                // Add to cart
                echo '<a href="' . esc_url($product->add_to_cart_url()) . '" data-quantity="1" class="add_to_cart_button ajax_add_to_cart flex-grow inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300" data-product_id="' . esc_attr($product_id) . '" data-product_sku="' . esc_attr($product->get_sku()) . '">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />';
                echo '</svg>';
                echo esc_html__('Add to Cart', 'aqualuxe');
                echo '</a>';
                
                // Remove from wishlist
                echo '<button class="wishlist-remove inline-flex justify-center items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300" data-product-id="' . esc_attr($product_id) . '">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                echo '</svg>';
                echo '</button>';
                
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script('aqualuxe-wishlist', get_template_directory_uri() . '/assets/js/wishlist.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-wishlist', 'aqualuxeWishlist', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_wishlist_nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'loginUrl' => wc_get_page_permalink('myaccount'),
            'i18n' => array(
                'addToWishlist' => __('Add to Wishlist', 'aqualuxe'),
                'removeFromWishlist' => __('Remove from Wishlist', 'aqualuxe'),
                'added' => __('Added to wishlist', 'aqualuxe'),
                'removed' => __('Removed from wishlist', 'aqualuxe'),
                'loginRequired' => __('Please log in to add items to your wishlist', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    public function get_wishlist() {
        if (is_user_logged_in()) {
            $user_id = get_current_user_id();
            $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
            
            if (!is_array($wishlist)) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
        
        return array_unique(array_filter($wishlist));
    }

    /**
     * Get wishlist from cookie
     *
     * @return array
     */
    private function get_wishlist_from_cookie() {
        $cookie_name = 'aqualuxe_wishlist';
        
        if (isset($_COOKIE[$cookie_name])) {
            $wishlist = json_decode(stripslashes($_COOKIE[$cookie_name]), true);
            
            if (!is_array($wishlist)) {
                $wishlist = array();
            }
        } else {
            $wishlist = array();
        }
        
        return $wishlist;
    }

    /**
     * Set wishlist cookie
     *
     * @param array $wishlist Wishlist.
     */
    private function set_wishlist_cookie($wishlist) {
        $cookie_name = 'aqualuxe_wishlist';
        $expire = time() + 30 * DAY_IN_SECONDS;
        
        setcookie($cookie_name, json_encode($wishlist), $expire, COOKIEPATH, COOKIE_DOMAIN);
    }

    /**
     * Get wishlist count
     *
     * @return int
     */
    public function get_wishlist_count() {
        $wishlist = $this->get_wishlist();
        return count($wishlist);
    }
}

// Initialize the class
new AquaLuxe_WC_Wishlist();