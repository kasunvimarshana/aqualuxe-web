<?php
/**
 * AquaLuxe WooCommerce Integration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_WooCommerce Class
 * 
 * Handles the theme WooCommerce integration
 */
class AquaLuxe_WooCommerce {
    /**
     * Constructor
     */
    public function __construct() {
        // Include required files
        $this->includes();
        
        // Setup WooCommerce
        add_action('after_setup_theme', array($this, 'setup'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Modify WooCommerce templates
        add_filter('woocommerce_locate_template', array($this, 'locate_template'), 10, 3);
        
        // Modify product loop
        add_action('woocommerce_before_shop_loop_item', array($this, 'product_loop_start'), 5);
        add_action('woocommerce_after_shop_loop_item', array($this, 'product_loop_end'), 15);
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'product_badges'), 5);
        add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'), 11);
        add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 12);
        
        // Modify single product
        add_action('woocommerce_before_single_product_summary', array($this, 'product_badges'), 5);
        add_action('woocommerce_single_product_summary', array($this, 'single_product_stock_status'), 25);
        add_action('woocommerce_single_product_summary', array($this, 'single_product_share'), 50);
        add_action('woocommerce_after_single_product_summary', array($this, 'recently_viewed_products'), 20);
        
        // Modify related products
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        
        // Modify upsells
        add_filter('woocommerce_upsell_display_args', array($this, 'upsell_products_args'));
        
        // Modify cross-sells
        add_filter('woocommerce_cross_sells_columns', array($this, 'cross_sells_columns'));
        add_filter('woocommerce_cross_sells_total', array($this, 'cross_sells_total'));
        
        // Modify checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
        
        // Modify account menu
        add_filter('woocommerce_account_menu_items', array($this, 'account_menu_items'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_quick_view', array($this, 'quick_view_ajax'));
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', array($this, 'quick_view_ajax'));
        
        add_action('wp_ajax_aqualuxe_add_to_wishlist', array($this, 'add_to_wishlist_ajax'));
        add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', array($this, 'add_to_wishlist_ajax'));
        
        // Add shortcodes
        add_shortcode('aqualuxe_featured_products', array($this, 'featured_products_shortcode'));
        add_shortcode('aqualuxe_new_products', array($this, 'new_products_shortcode'));
        add_shortcode('aqualuxe_sale_products', array($this, 'sale_products_shortcode'));
        add_shortcode('aqualuxe_best_selling_products', array($this, 'best_selling_products_shortcode'));
        add_shortcode('aqualuxe_product_categories', array($this, 'product_categories_shortcode'));
        
        // Add widgets
        add_action('widgets_init', array($this, 'register_widgets'));
        
        // Add product data tabs
        add_filter('woocommerce_product_tabs', array($this, 'product_tabs'));
        
        // Add product meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_box_data'));
        
        // Modify product columns
        add_filter('manage_edit-product_columns', array($this, 'product_columns'));
        add_action('manage_product_posts_custom_column', array($this, 'product_custom_column'), 10, 2);
        
        // Modify product category columns
        add_filter('manage_edit-product_cat_columns', array($this, 'product_cat_columns'));
        add_filter('manage_product_cat_custom_column', array($this, 'product_cat_column'), 10, 3);
        
        // Add product category fields
        add_action('product_cat_add_form_fields', array($this, 'add_product_cat_fields'));
        add_action('product_cat_edit_form_fields', array($this, 'edit_product_cat_fields'), 10, 2);
        add_action('created_term', array($this, 'save_product_cat_fields'), 10, 3);
        add_action('edit_term', array($this, 'save_product_cat_fields'), 10, 3);
        
        // Add product tag fields
        add_action('product_tag_add_form_fields', array($this, 'add_product_tag_fields'));
        add_action('product_tag_edit_form_fields', array($this, 'edit_product_tag_fields'), 10, 2);
        add_action('created_term', array($this, 'save_product_tag_fields'), 10, 3);
        add_action('edit_term', array($this, 'save_product_tag_fields'), 10, 3);
        
        // Add product attribute fields
        add_action('woocommerce_attribute_added', array($this, 'save_attribute_fields'), 10, 2);
        add_action('woocommerce_attribute_updated', array($this, 'save_attribute_fields'), 10, 2);
        
        // Add product variation fields
        add_action('woocommerce_product_after_variable_attributes', array($this, 'variation_fields'), 10, 3);
        add_action('woocommerce_save_product_variation', array($this, 'save_variation_fields'), 10, 2);
        
        // Add product data panels
        add_filter('woocommerce_product_data_tabs', array($this, 'product_data_tabs'));
        add_action('woocommerce_product_data_panels', array($this, 'product_data_panels'));
        add_action('woocommerce_process_product_meta', array($this, 'save_product_data_fields'));
        
        // Add product admin filters
        add_action('restrict_manage_posts', array($this, 'product_filters'));
        add_filter('parse_query', array($this, 'product_filters_query'));
        
        // Add product admin bulk actions
        add_filter('bulk_actions-edit-product', array($this, 'product_bulk_actions'));
        add_filter('handle_bulk_actions-edit-product', array($this, 'handle_product_bulk_actions'), 10, 3);
        
        // Add product admin notices
        add_action('admin_notices', array($this, 'product_admin_notices'));
        
        // Add product admin meta boxes
        add_action('add_meta_boxes', array($this, 'add_product_meta_boxes'));
        add_action('save_post', array($this, 'save_product_meta_box_data'));
        
        // Add product admin columns
        add_filter('manage_edit-product_columns', array($this, 'product_admin_columns'));
        add_action('manage_product_posts_custom_column', array($this, 'product_admin_custom_column'), 10, 2);
        add_filter('manage_edit-product_sortable_columns', array($this, 'product_admin_sortable_columns'));
        
        // Add product admin column filters
        add_action('pre_get_posts', array($this, 'product_admin_column_orderby'));
        
        // Add product admin column bulk edit
        add_action('bulk_edit_custom_box', array($this, 'product_bulk_edit'), 10, 2);
        add_action('save_post', array($this, 'product_bulk_edit_save'));
        
        // Add product admin quick edit
        add_action('quick_edit_custom_box', array($this, 'product_quick_edit'), 10, 2);
        add_action('save_post', array($this, 'product_quick_edit_save'));
        
        // Add product admin inline data
        add_filter('post_row_actions', array($this, 'product_row_actions'), 10, 2);
        add_action('admin_footer', array($this, 'product_admin_scripts'));
        
        // Add product admin enqueue scripts
        add_action('admin_enqueue_scripts', array($this, 'product_admin_enqueue_scripts'));
        
        // Add product admin ajax handlers
        add_action('wp_ajax_aqualuxe_product_admin_ajax', array($this, 'product_admin_ajax'));
        
        // Add product admin settings
        add_filter('woocommerce_get_settings_products', array($this, 'product_settings'), 10, 2);
        
        // Add product admin settings sections
        add_filter('woocommerce_get_sections_products', array($this, 'product_settings_sections'));
        
        // Add product admin settings fields
        add_filter('woocommerce_get_settings_products', array($this, 'product_settings_fields'), 10, 2);
        
        // Add product admin settings tabs
        add_filter('woocommerce_settings_tabs_array', array($this, 'product_settings_tabs'), 50);
        
        // Add product admin settings tab content
        add_action('woocommerce_settings_tabs_aqualuxe', array($this, 'product_settings_tab_content'));
        
        // Add product admin settings save
        add_action('woocommerce_update_options_aqualuxe', array($this, 'product_settings_save'));
        
        // Add product admin settings sections
        add_action('woocommerce_sections_aqualuxe', array($this, 'product_settings_sections_content'));
        
        // Add product admin settings fields
        add_action('woocommerce_settings_aqualuxe', array($this, 'product_settings_fields_content'));
        
        // Add product admin settings save
        add_action('woocommerce_settings_save_aqualuxe', array($this, 'product_settings_fields_save'));
    }

    /**
     * Include required files
     */
    public function includes() {
        // Include template functions
        require_once AQUALUXE_DIR . 'inc/woocommerce/aqualuxe-woocommerce-template-functions.php';
        
        // Include template hooks
        require_once AQUALUXE_DIR . 'inc/woocommerce/aqualuxe-woocommerce-template-hooks.php';
    }

    /**
     * Setup WooCommerce
     */
    public function setup() {
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Remove default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', array($this, 'wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'wrapper_end'), 10);
        
        // Remove default WooCommerce sidebar
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        
        // Add custom sidebar
        add_action('woocommerce_sidebar', array($this, 'get_sidebar'), 10);
        
        // Remove default WooCommerce breadcrumbs
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
        
        // Add custom breadcrumbs
        add_action('woocommerce_before_main_content', array($this, 'breadcrumbs'), 20);
        
        // Remove default WooCommerce result count and catalog ordering
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
        
        // Add custom result count and catalog ordering
        add_action('woocommerce_before_shop_loop', array($this, 'result_count'), 20);
        add_action('woocommerce_before_shop_loop', array($this, 'catalog_ordering'), 30);
        
        // Remove default WooCommerce pagination
        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
        
        // Add custom pagination
        add_action('woocommerce_after_shop_loop', array($this, 'pagination'), 10);
        
        // Remove default WooCommerce product loop title
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
        
        // Add custom product loop title
        add_action('woocommerce_shop_loop_item_title', array($this, 'template_loop_product_title'), 10);
        
        // Remove default WooCommerce product loop rating
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
        
        // Add custom product loop rating
        add_action('woocommerce_after_shop_loop_item_title', array($this, 'template_loop_rating'), 5);
        
        // Remove default WooCommerce product loop price
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        
        // Add custom product loop price
        add_action('woocommerce_after_shop_loop_item_title', array($this, 'template_loop_price'), 10);
        
        // Remove default WooCommerce product loop add to cart
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        
        // Add custom product loop add to cart
        add_action('woocommerce_after_shop_loop_item', array($this, 'template_loop_add_to_cart'), 10);
        
        // Remove default WooCommerce product single title
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
        
        // Add custom product single title
        add_action('woocommerce_single_product_summary', array($this, 'template_single_title'), 5);
        
        // Remove default WooCommerce product single rating
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
        
        // Add custom product single rating
        add_action('woocommerce_single_product_summary', array($this, 'template_single_rating'), 10);
        
        // Remove default WooCommerce product single price
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        
        // Add custom product single price
        add_action('woocommerce_single_product_summary', array($this, 'template_single_price'), 10);
        
        // Remove default WooCommerce product single excerpt
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
        
        // Add custom product single excerpt
        add_action('woocommerce_single_product_summary', array($this, 'template_single_excerpt'), 20);
        
        // Remove default WooCommerce product single add to cart
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        
        // Add custom product single add to cart
        add_action('woocommerce_single_product_summary', array($this, 'template_single_add_to_cart'), 30);
        
        // Remove default WooCommerce product single meta
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
        
        // Add custom product single meta
        add_action('woocommerce_single_product_summary', array($this, 'template_single_meta'), 40);
        
        // Remove default WooCommerce product single sharing
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
        
        // Add custom product single sharing
        add_action('woocommerce_single_product_summary', array($this, 'template_single_sharing'), 50);
        
        // Remove default WooCommerce product tabs
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
        
        // Add custom product tabs
        add_action('woocommerce_after_single_product_summary', array($this, 'output_product_data_tabs'), 10);
        
        // Remove default WooCommerce related products
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
        
        // Add custom related products
        add_action('woocommerce_after_single_product_summary', array($this, 'output_related_products'), 20);
        
        // Remove default WooCommerce upsell products
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
        
        // Add custom upsell products
        add_action('woocommerce_after_single_product_summary', array($this, 'upsell_display'), 15);
        
        // Remove default WooCommerce cross-sell products
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
        
        // Add custom cross-sell products
        add_action('woocommerce_cart_collaterals', array($this, 'cross_sell_display'));
        
        // Remove default WooCommerce cart totals
        remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);
        
        // Add custom cart totals
        add_action('woocommerce_cart_collaterals', array($this, 'cart_totals'), 10);
        
        // Remove default WooCommerce checkout coupon form
        remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
        
        // Add custom checkout coupon form
        add_action('woocommerce_before_checkout_form', array($this, 'checkout_coupon_form'), 10);
        
        // Remove default WooCommerce checkout login form
        remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
        
        // Add custom checkout login form
        add_action('woocommerce_before_checkout_form', array($this, 'checkout_login_form'), 10);
        
        // Remove default WooCommerce checkout billing form
        remove_action('woocommerce_checkout_billing', 'woocommerce_checkout_billing_form', 10);
        
        // Add custom checkout billing form
        add_action('woocommerce_checkout_billing', array($this, 'checkout_billing_form'), 10);
        
        // Remove default WooCommerce checkout shipping form
        remove_action('woocommerce_checkout_shipping', 'woocommerce_checkout_shipping_form', 10);
        
        // Add custom checkout shipping form
        add_action('woocommerce_checkout_shipping', array($this, 'checkout_shipping_form'), 10);
        
        // Remove default WooCommerce checkout payment
        remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
        
        // Add custom checkout payment
        add_action('woocommerce_checkout_order_review', array($this, 'checkout_payment'), 20);
        
        // Remove default WooCommerce checkout order review
        remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);
        
        // Add custom checkout order review
        add_action('woocommerce_checkout_order_review', array($this, 'order_review'), 10);
        
        // Remove default WooCommerce account navigation
        remove_action('woocommerce_account_navigation', 'woocommerce_account_navigation');
        
        // Add custom account navigation
        add_action('woocommerce_account_navigation', array($this, 'account_navigation'));
        
        // Remove default WooCommerce account content
        remove_action('woocommerce_account_content', 'woocommerce_account_content');
        
        // Add custom account content
        add_action('woocommerce_account_content', array($this, 'account_content'));
        
        // Remove default WooCommerce account dashboard
        remove_action('woocommerce_account_dashboard', 'woocommerce_account_dashboard');
        
        // Add custom account dashboard
        add_action('woocommerce_account_dashboard', array($this, 'account_dashboard'));
        
        // Remove default WooCommerce account orders
        remove_action('woocommerce_account_orders_endpoint', 'woocommerce_account_orders');
        
        // Add custom account orders
        add_action('woocommerce_account_orders_endpoint', array($this, 'account_orders'));
        
        // Remove default WooCommerce account downloads
        remove_action('woocommerce_account_downloads_endpoint', 'woocommerce_account_downloads');
        
        // Add custom account downloads
        add_action('woocommerce_account_downloads_endpoint', array($this, 'account_downloads'));
        
        // Remove default WooCommerce account addresses
        remove_action('woocommerce_account_edit-address_endpoint', 'woocommerce_account_edit_address');
        
        // Add custom account addresses
        add_action('woocommerce_account_edit-address_endpoint', array($this, 'account_edit_address'));
        
        // Remove default WooCommerce account payment methods
        remove_action('woocommerce_account_payment-methods_endpoint', 'woocommerce_account_payment_methods');
        
        // Add custom account payment methods
        add_action('woocommerce_account_payment-methods_endpoint', array($this, 'account_payment_methods'));
        
        // Remove default WooCommerce account edit account
        remove_action('woocommerce_account_edit-account_endpoint', 'woocommerce_account_edit_account');
        
        // Add custom account edit account
        add_action('woocommerce_account_edit-account_endpoint', array($this, 'account_edit_account'));
        
        // Remove default WooCommerce account view order
        remove_action('woocommerce_account_view-order_endpoint', 'woocommerce_account_view_order');
        
        // Add custom account view order
        add_action('woocommerce_account_view-order_endpoint', array($this, 'account_view_order'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/css/woocommerce.css',
            array('aqualuxe-style'),
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_URI . 'assets/js/woocommerce.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize WooCommerce scripts
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-woocommerce-nonce'),
            )
        );
        
        // Enqueue quick view script
        if (is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy()) {
            wp_enqueue_script(
                'aqualuxe-quick-view',
                AQUALUXE_URI . 'assets/js/quick-view.js',
                array('jquery', 'wc-add-to-cart-variation'),
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script(
                'aqualuxe-quick-view',
                'aqualuxeQuickView',
                array(
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce'   => wp_create_nonce('aqualuxe-quick-view-nonce'),
                )
            );
        }
        
        // Enqueue wishlist script
        wp_enqueue_script(
            'aqualuxe-wishlist',
            AQUALUXE_URI . 'assets/js/wishlist.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-wishlist',
            'aqualuxeWishlist',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-wishlist-nonce'),
            )
        );
    }

    /**
     * Locate WooCommerce template
     * 
     * @param string $template      Template file.
     * @param string $template_name Template name.
     * @param string $template_path Template path.
     * @return string
     */
    public function locate_template($template, $template_name, $template_path) {
        // Check if the template exists in the theme
        $theme_template = locate_template(array(
            'woocommerce/' . $template_name,
            $template_name,
        ));
        
        // If the template exists in the theme, return it
        if ($theme_template) {
            return $theme_template;
        }
        
        // Check if the template exists in the plugin
        $plugin_template = AQUALUXE_DIR . 'woocommerce/' . $template_name;
        
        // If the template exists in the plugin, return it
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
        
        // Return the default template
        return $template;
    }

    /**
     * Product loop start
     */
    public function product_loop_start() {
        echo '<div class="product-inner">';
    }

    /**
     * Product loop end
     */
    public function product_loop_end() {
        echo '</div>';
    }

    /**
     * Product badges
     */
    public function product_badges() {
        global $product;
        
        echo '<div class="product-badges">';
        
        // Sale badge
        if ($product->is_on_sale()) {
            echo '<span class="badge sale">' . esc_html__('Sale', 'aqualuxe') . '</span>';
        }
        
        // New badge (products published within the last 30 days)
        $days_as_new = 30;
        $post_date = get_the_time('U');
        $current_date = current_time('timestamp');
        $seconds_in_day = 86400;
        
        if (($current_date - $post_date) < ($days_as_new * $seconds_in_day)) {
            echo '<span class="badge new">' . esc_html__('New', 'aqualuxe') . '</span>';
        }
        
        // Out of stock badge
        if (!$product->is_in_stock()) {
            echo '<span class="badge out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
        }
        
        // Featured badge
        if ($product->is_featured()) {
            echo '<span class="badge featured">' . esc_html__('Featured', 'aqualuxe') . '</span>';
        }
        
        echo '</div>';
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;
        
        if (aqualuxe_get_option('show_quick_view', true)) {
            echo '<a href="#" class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
        }
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        global $product;
        
        if (aqualuxe_get_option('show_wishlist', true)) {
            // Check if YITH WooCommerce Wishlist is active
            if (defined('YITH_WCWL') && YITH_WCWL) {
                echo do_shortcode('[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '"]');
            } else {
                // Fallback wishlist button
                echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
            }
        }
    }

    /**
     * Single product stock status
     */
    public function single_product_stock_status() {
        global $product;
        
        if ($product->is_in_stock()) {
            echo '<div class="stock in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</div>';
        } else {
            echo '<div class="stock out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</div>';
        }
    }

    /**
     * Single product share
     */
    public function single_product_share() {
        $product_url = urlencode(get_permalink());
        $product_title = urlencode(get_the_title());
        $product_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));
        
        ?>
        <div class="product-share">
            <span class="share-title"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
            <a class="share-facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $product_url; ?>" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a class="share-twitter" href="https://twitter.com/intent/tweet?url=<?php echo $product_url; ?>&text=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-twitter"></i>
            </a>
            <a class="share-pinterest" href="https://pinterest.com/pin/create/button/?url=<?php echo $product_url; ?>&media=<?php echo $product_thumbnail; ?>&description=<?php echo $product_title; ?>" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-pinterest-p"></i>
            </a>
            <a class="share-email" href="mailto:?subject=<?php echo $product_title; ?>&body=<?php echo $product_url; ?>">
                <i class="fas fa-envelope"></i>
            </a>
        </div>
        <?php
    }

    /**
     * Recently viewed products
     */
    public function recently_viewed_products() {
        if (!aqualuxe_get_option('show_recently_viewed', true)) {
            return;
        }
        
        // Get recently viewed products
        $viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array) explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();
        $viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));
        
        if (empty($viewed_products)) {
            return;
        }
        
        // Remove current product
        $current_product_id = get_the_ID();
        $viewed_products = array_diff($viewed_products, array($current_product_id));
        
        if (empty($viewed_products)) {
            return;
        }
        
        // Limit to 4 products
        $viewed_products = array_slice($viewed_products, 0, 4);
        
        // Get products
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'post__in'       => $viewed_products,
            'orderby'        => 'post__in',
            'posts_per_page' => 4,
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            ?>
            <section class="recently-viewed-products">
                <h2><?php esc_html_e('Recently Viewed', 'aqualuxe'); ?></h2>
                <ul class="products columns-4">
                    <?php while ($products->have_posts()) : $products->the_post(); ?>
                        <?php wc_get_template_part('content', 'product'); ?>
                    <?php endwhile; ?>
                </ul>
            </section>
            <?php
        }
        
        wp_reset_postdata();
    }

    /**
     * Modify related products args
     * 
     * @param array $args Related products args.
     * @return array
     */
    public function related_products_args($args) {
        if (!aqualuxe_get_option('show_related_products', true)) {
            return array(
                'posts_per_page' => 0,
                'columns'        => 0,
                'orderby'        => 'rand',
            );
        }
        
        $args['posts_per_page'] = aqualuxe_get_option('related_products_count', 4);
        $args['columns'] = min(4, $args['posts_per_page']);
        
        return $args;
    }

    /**
     * Modify upsell products args
     * 
     * @param array $args Upsell products args.
     * @return array
     */
    public function upsell_products_args($args) {
        if (!aqualuxe_get_option('show_upsells', true)) {
            return array(
                'posts_per_page' => 0,
                'columns'        => 0,
                'orderby'        => 'rand',
            );
        }
        
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }

    /**
     * Modify cross-sells columns
     * 
     * @param int $columns Cross-sells columns.
     * @return int
     */
    public function cross_sells_columns($columns) {
        return 2;
    }

    /**
     * Modify cross-sells total
     * 
     * @param int $total Cross-sells total.
     * @return int
     */
    public function cross_sells_total($total) {
        return 2;
    }

    /**
     * Modify checkout fields
     * 
     * @param array $fields Checkout fields.
     * @return array
     */
    public function checkout_fields($fields) {
        // Make the order notes field optional
        if (!aqualuxe_get_option('show_order_notes', true)) {
            unset($fields['order']['order_comments']);
        }
        
        return $fields;
    }

    /**
     * Modify account menu items
     * 
     * @param array $items Account menu items.
     * @return array
     */
    public function account_menu_items($items) {
        // Add wishlist item
        if (aqualuxe_get_option('show_wishlist', true)) {
            $wishlist_item = array('wishlist' => esc_html__('Wishlist', 'aqualuxe'));
            $items = array_slice($items, 0, 1, true) + $wishlist_item + array_slice($items, 1, count($items) - 1, true);
        }
        
        return $items;
    }

    /**
     * Quick view AJAX handler
     */
    public function quick_view_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-quick-view-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Invalid nonce', 'aqualuxe')));
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error(array('message' => esc_html__('Invalid product ID', 'aqualuxe')));
        }
        
        $product_id = absint($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array('message' => esc_html__('Invalid product', 'aqualuxe')));
        }
        
        // Get quick view template
        ob_start();
        include AQUALUXE_DIR . 'woocommerce/quick-view.php';
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Add to wishlist AJAX handler
     */
    public function add_to_wishlist_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-wishlist-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Invalid nonce', 'aqualuxe')));
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error(array('message' => esc_html__('Invalid product ID', 'aqualuxe')));
        }
        
        $product_id = absint($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array('message' => esc_html__('Invalid product', 'aqualuxe')));
        }
        
        // Get user ID
        $user_id = get_current_user_id();
        
        // If user is logged in, save to user meta
        if ($user_id) {
            $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
            $wishlist = $wishlist ? $wishlist : array();
            
            if (in_array($product_id, $wishlist)) {
                // Remove from wishlist
                $wishlist = array_diff($wishlist, array($product_id));
                update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
                
                wp_send_json_success(array(
                    'message' => esc_html__('Product removed from wishlist', 'aqualuxe'),
                    'action'  => 'removed',
                ));
            } else {
                // Add to wishlist
                $wishlist[] = $product_id;
                update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
                
                wp_send_json_success(array(
                    'message' => esc_html__('Product added to wishlist', 'aqualuxe'),
                    'action'  => 'added',
                ));
            }
        } else {
            // If user is not logged in, save to cookie
            $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? explode(',', $_COOKIE['aqualuxe_wishlist']) : array();
            
            if (in_array($product_id, $wishlist)) {
                // Remove from wishlist
                $wishlist = array_diff($wishlist, array($product_id));
                setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/');
                
                wp_send_json_success(array(
                    'message' => esc_html__('Product removed from wishlist', 'aqualuxe'),
                    'action'  => 'removed',
                ));
            } else {
                // Add to wishlist
                $wishlist[] = $product_id;
                setcookie('aqualuxe_wishlist', implode(',', $wishlist), time() + (86400 * 30), '/');
                
                wp_send_json_success(array(
                    'message' => esc_html__('Product added to wishlist', 'aqualuxe'),
                    'action'  => 'added',
                ));
            }
        }
    }

    /**
     * Featured products shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function featured_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 4,
            'columns'  => 4,
            'orderby'  => 'date',
            'order'    => 'desc',
            'category' => '',
            'operator' => 'IN',
        ), $atts, 'aqualuxe_featured_products');
        
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'tax_query'           => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['category']),
                'operator' => $atts['operator'],
            );
        }
        
        ob_start();
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
        } else {
            echo '<p>' . esc_html__('No featured products found', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }

    /**
     * New products shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function new_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 4,
            'columns'  => 4,
            'orderby'  => 'date',
            'order'    => 'desc',
            'category' => '',
            'operator' => 'IN',
        ), $atts, 'aqualuxe_new_products');
        
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['category']),
                'operator' => $atts['operator'],
            );
        }
        
        ob_start();
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
        } else {
            echo '<p>' . esc_html__('No products found', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }

    /**
     * Sale products shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function sale_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 4,
            'columns'  => 4,
            'orderby'  => 'date',
            'order'    => 'desc',
            'category' => '',
            'operator' => 'IN',
        ), $atts, 'aqualuxe_sale_products');
        
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'orderby'             => $atts['orderby'],
            'order'               => $atts['order'],
            'meta_query'          => array(
                'relation' => 'OR',
                array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
                array(
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['category']),
                'operator' => $atts['operator'],
            );
        }
        
        ob_start();
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
        } else {
            echo '<p>' . esc_html__('No sale products found', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }

    /**
     * Best selling products shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function best_selling_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'per_page' => 4,
            'columns'  => 4,
            'category' => '',
            'operator' => 'IN',
        ), $atts, 'aqualuxe_best_selling_products');
        
        $args = array(
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $atts['per_page'],
            'meta_key'            => 'total_sales',
            'orderby'             => 'meta_value_num',
            'order'               => 'desc',
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => explode(',', $atts['category']),
                'operator' => $atts['operator'],
            );
        }
        
        ob_start();
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
        } else {
            echo '<p>' . esc_html__('No products found', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        return '<div class="woocommerce columns-' . $atts['columns'] . '">' . ob_get_clean() . '</div>';
    }

    /**
     * Product categories shortcode
     * 
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function product_categories_shortcode($atts) {
        $atts = shortcode_atts(array(
            'number'     => null,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'columns'    => 4,
            'hide_empty' => 1,
            'parent'     => '',
            'ids'        => '',
        ), $atts, 'aqualuxe_product_categories');
        
        $args = array(
            'orderby'    => $atts['orderby'],
            'order'      => $atts['order'],
            'hide_empty' => $atts['hide_empty'],
            'include'    => $atts['ids'] ? explode(',', $atts['ids']) : array(),
            'pad_counts' => true,
            'number'     => $atts['number'],
        );
        
        if ('' !== $atts['parent']) {
            $args['parent'] = $atts['parent'];
        }
        
        $categories = get_terms('product_cat', $args);
        
        if (is_wp_error($categories)) {
            return '';
        }
        
        if (empty($categories)) {
            return '<p>' . esc_html__('No product categories found', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        
        echo '<div class="woocommerce columns-' . $atts['columns'] . '">';
        echo '<ul class="products columns-' . $atts['columns'] . ' product-categories">';
        
        foreach ($categories as $category) {
            wc_get_template('content-product_cat.php', array(
                'category' => $category,
            ));
        }
        
        echo '</ul>';
        echo '</div>';
        
        return ob_get_clean();
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        // Register custom widgets
        register_widget('AquaLuxe_Widget_Featured_Products');
        register_widget('AquaLuxe_Widget_New_Products');
        register_widget('AquaLuxe_Widget_Sale_Products');
        register_widget('AquaLuxe_Widget_Best_Selling_Products');
        register_widget('AquaLuxe_Widget_Product_Categories');
        register_widget('AquaLuxe_Widget_Product_Filter');
        register_widget('AquaLuxe_Widget_Product_Search');
        register_widget('AquaLuxe_Widget_Product_Tags');
        register_widget('AquaLuxe_Widget_Product_Brands');
        register_widget('AquaLuxe_Widget_Product_Price_Filter');
        register_widget('AquaLuxe_Widget_Product_Rating_Filter');
        register_widget('AquaLuxe_Widget_Product_Attribute_Filter');
        register_widget('AquaLuxe_Widget_Product_Recently_Viewed');
        register_widget('AquaLuxe_Widget_Product_Wishlist');
        register_widget('AquaLuxe_Widget_Product_Compare');
    }

    /**
     * Modify product tabs
     * 
     * @param array $tabs Product tabs.
     * @return array
     */
    public function product_tabs($tabs) {
        // Reorder tabs
        $tabs['description']['priority'] = 10;
        $tabs['additional_information']['priority'] = 20;
        $tabs['reviews']['priority'] = 30;
        
        // Add custom tabs
        $tabs['shipping'] = array(
            'title'    => esc_html__('Shipping', 'aqualuxe'),
            'priority' => 40,
            'callback' => array($this, 'shipping_tab_content'),
        );
        
        $tabs['care'] = array(
            'title'    => esc_html__('Care Guide', 'aqualuxe'),
            'priority' => 50,
            'callback' => array($this, 'care_tab_content'),
        );
        
        return $tabs;
    }

    /**
     * Shipping tab content
     */
    public function shipping_tab_content() {
        global $product;
        
        $shipping_content = get_post_meta($product->get_id(), '_shipping_content', true);
        
        if ($shipping_content) {
            echo wpautop(wp_kses_post($shipping_content));
        } else {
            echo wpautop(wp_kses_post(aqualuxe_get_option('default_shipping_content', '')));
        }
    }

    /**
     * Care tab content
     */
    public function care_tab_content() {
        global $product;
        
        $care_content = get_post_meta($product->get_id(), '_care_content', true);
        
        if ($care_content) {
            echo wpautop(wp_kses_post($care_content));
        } else {
            echo wpautop(wp_kses_post(aqualuxe_get_option('default_care_content', '')));
        }
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_product_tabs',
            esc_html__('AquaLuxe Product Tabs', 'aqualuxe'),
            array($this, 'product_tabs_meta_box_callback'),
            'product',
            'normal',
            'high'
        );
    }

    /**
     * Product tabs meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function product_tabs_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_product_tabs_meta_box', 'aqualuxe_product_tabs_meta_box_nonce');
        
        // Get the saved values
        $shipping_content = get_post_meta($post->ID, '_shipping_content', true);
        $care_content = get_post_meta($post->ID, '_care_content', true);
        
        ?>
        <div class="aqualuxe-meta-box">
            <div class="aqualuxe-meta-field">
                <label for="shipping_content"><?php esc_html_e('Shipping Content', 'aqualuxe'); ?></label>
                <?php
                $settings = array(
                    'textarea_name' => 'shipping_content',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                );
                wp_editor($shipping_content, 'shipping_content', $settings);
                ?>
                <p class="description"><?php esc_html_e('Enter the content for the shipping tab', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-meta-field">
                <label for="care_content"><?php esc_html_e('Care Guide Content', 'aqualuxe'); ?></label>
                <?php
                $settings = array(
                    'textarea_name' => 'care_content',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                );
                wp_editor($care_content, 'care_content', $settings);
                ?>
                <p class="description"><?php esc_html_e('Enter the content for the care guide tab', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta box data
     * 
     * @param int $post_id The post ID.
     */
    public function save_meta_box_data($post_id) {
        // Check if we're doing an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_product_tabs_meta_box_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_product_tabs_meta_box_nonce'], 'aqualuxe_product_tabs_meta_box')) {
            return;
        }
        
        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Update the meta fields
        if (isset($_POST['shipping_content'])) {
            update_post_meta($post_id, '_shipping_content', wp_kses_post($_POST['shipping_content']));
        }
        
        if (isset($_POST['care_content'])) {
            update_post_meta($post_id, '_care_content', wp_kses_post($_POST['care_content']));
        }
    }

    /**
     * Modify product columns
     * 
     * @param array $columns Product columns.
     * @return array
     */
    public function product_columns($columns) {
        // Add custom columns
        $columns['featured'] = esc_html__('Featured', 'aqualuxe');
        $columns['stock'] = esc_html__('Stock', 'aqualuxe');
        
        return $columns;
    }

    /**
     * Product custom column
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function product_custom_column($column, $post_id) {
        $product = wc_get_product($post_id);
        
        if (!$product) {
            return;
        }
        
        switch ($column) {
            case 'featured':
                if ($product->is_featured()) {
                    echo '<span class="dashicons dashicons-star-filled" style="color: #f7d708;"></span>';
                } else {
                    echo '<span class="dashicons dashicons-star-empty"></span>';
                }
                break;
            
            case 'stock':
                if ($product->is_in_stock()) {
                    echo '<span class="in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
                } else {
                    echo '<span class="out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
                }
                break;
        }
    }

    /**
     * Modify product category columns
     * 
     * @param array $columns Product category columns.
     * @return array
     */
    public function product_cat_columns($columns) {
        // Add custom columns
        $columns['thumbnail'] = esc_html__('Thumbnail', 'aqualuxe');
        $columns['description'] = esc_html__('Description', 'aqualuxe');
        
        return $columns;
    }

    /**
     * Product category column
     * 
     * @param string $content Column content.
     * @param string $column  Column name.
     * @param int    $term_id Term ID.
     * @return string
     */
    public function product_cat_column($content, $column, $term_id) {
        switch ($column) {
            case 'thumbnail':
                $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    $content = wp_get_attachment_image($thumbnail_id, array(50, 50));
                } else {
                    $content = '<span class="dashicons dashicons-format-image"></span>';
                }
                break;
            
            case 'description':
                $term = get_term($term_id, 'product_cat');
                $content = $term->description;
                break;
        }
        
        return $content;
    }

    /**
     * Add product category fields
     */
    public function add_product_cat_fields() {
        ?>
        <div class="form-field">
            <label for="banner_image"><?php esc_html_e('Banner Image', 'aqualuxe'); ?></label>
            <div class="banner-image-preview"></div>
            <input type="hidden" name="banner_image" id="banner_image" value="" />
            <button type="button" class="button upload-banner-image"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
            <button type="button" class="button remove-banner-image" style="display:none;"><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
            <p class="description"><?php esc_html_e('Upload a banner image for this category', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="featured"><?php esc_html_e('Featured', 'aqualuxe'); ?></label>
            <input type="checkbox" name="featured" id="featured" value="1" />
            <p class="description"><?php esc_html_e('Check this to make this category featured', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label>
            <input type="text" name="icon" id="icon" value="" />
            <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fas fa-fish)', 'aqualuxe'); ?></p>
        </div>
        <?php
    }

    /**
     * Edit product category fields
     * 
     * @param WP_Term $term     Term object.
     * @param string  $taxonomy Taxonomy slug.
     */
    public function edit_product_cat_fields($term, $taxonomy) {
        $banner_image_id = get_term_meta($term->term_id, 'banner_image_id', true);
        $featured = get_term_meta($term->term_id, 'featured', true);
        $icon = get_term_meta($term->term_id, 'icon', true);
        
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="banner_image"><?php esc_html_e('Banner Image', 'aqualuxe'); ?></label></th>
            <td>
                <div class="banner-image-preview">
                    <?php if ($banner_image_id) : ?>
                        <?php echo wp_get_attachment_image($banner_image_id, 'thumbnail'); ?>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="banner_image" id="banner_image" value="<?php echo esc_attr($banner_image_id); ?>" />
                <button type="button" class="button upload-banner-image"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
                <button type="button" class="button remove-banner-image" <?php echo $banner_image_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
                <p class="description"><?php esc_html_e('Upload a banner image for this category', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row" valign="top"><label for="featured"><?php esc_html_e('Featured', 'aqualuxe'); ?></label></th>
            <td>
                <input type="checkbox" name="featured" id="featured" value="1" <?php checked($featured, 1); ?> />
                <p class="description"><?php esc_html_e('Check this to make this category featured', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row" valign="top"><label for="icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="icon" id="icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fas fa-fish)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save product category fields
     * 
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy slug.
     */
    public function save_product_cat_fields($term_id, $tt_id, $taxonomy) {
        if ('product_cat' !== $taxonomy) {
            return;
        }
        
        if (isset($_POST['banner_image'])) {
            update_term_meta($term_id, 'banner_image_id', absint($_POST['banner_image']));
        }
        
        if (isset($_POST['featured'])) {
            update_term_meta($term_id, 'featured', 1);
        } else {
            update_term_meta($term_id, 'featured', 0);
        }
        
        if (isset($_POST['icon'])) {
            update_term_meta($term_id, 'icon', sanitize_text_field($_POST['icon']));
        }
    }

    /**
     * Add product tag fields
     */
    public function add_product_tag_fields() {
        ?>
        <div class="form-field">
            <label for="icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label>
            <input type="text" name="icon" id="icon" value="" />
            <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fas fa-tag)', 'aqualuxe'); ?></p>
        </div>
        <?php
    }

    /**
     * Edit product tag fields
     * 
     * @param WP_Term $term     Term object.
     * @param string  $taxonomy Taxonomy slug.
     */
    public function edit_product_tag_fields($term, $taxonomy) {
        $icon = get_term_meta($term->term_id, 'icon', true);
        
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="icon"><?php esc_html_e('Icon', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" name="icon" id="icon" value="<?php echo esc_attr($icon); ?>" />
                <p class="description"><?php esc_html_e('Enter a Font Awesome icon class (e.g. fas fa-tag)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * Save product tag fields
     * 
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     * @param string $taxonomy Taxonomy slug.
     */
    public function save_product_tag_fields($term_id, $tt_id, $taxonomy) {
        if ('product_tag' !== $taxonomy) {
            return;
        }
        
        if (isset($_POST['icon'])) {
            update_term_meta($term_id, 'icon', sanitize_text_field($_POST['icon']));
        }
    }

    /**
     * Save attribute fields
     * 
     * @param int   $attribute_id   Attribute ID.
     * @param array $attribute_data Attribute data.
     */
    public function save_attribute_fields($attribute_id, $attribute_data) {
        if (isset($_POST['attribute_icon'])) {
            update_option('aqualuxe_attribute_icon_' . $attribute_id, sanitize_text_field($_POST['attribute_icon']));
        }
    }

    /**
     * Variation fields
     * 
     * @param int     $loop           Loop index.
     * @param array   $variation_data Variation data.
     * @param WP_Post $variation      Variation post object.
     */
    public function variation_fields($loop, $variation_data, $variation) {
        $variation_image = get_post_meta($variation->ID, '_variation_image', true);
        
        ?>
        <div class="form-row form-row-full">
            <label for="variation_image_<?php echo esc_attr($loop); ?>"><?php esc_html_e('Variation Image', 'aqualuxe'); ?></label>
            <div class="variation-image-preview">
                <?php if ($variation_image) : ?>
                    <?php echo wp_get_attachment_image($variation_image, 'thumbnail'); ?>
                <?php endif; ?>
            </div>
            <input type="hidden" name="variation_image[<?php echo esc_attr($loop); ?>]" id="variation_image_<?php echo esc_attr($loop); ?>" value="<?php echo esc_attr($variation_image); ?>" />
            <button type="button" class="button upload-variation-image"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
            <button type="button" class="button remove-variation-image" <?php echo $variation_image ? '' : 'style="display:none;"'; ?>><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
        </div>
        <?php
    }

    /**
     * Save variation fields
     * 
     * @param int $variation_id Variation ID.
     * @param int $loop         Loop index.
     */
    public function save_variation_fields($variation_id, $loop) {
        if (isset($_POST['variation_image'][$loop])) {
            update_post_meta($variation_id, '_variation_image', absint($_POST['variation_image'][$loop]));
        }
    }

    /**
     * Product data tabs
     * 
     * @param array $tabs Product data tabs.
     * @return array
     */
    public function product_data_tabs($tabs) {
        $tabs['aqualuxe'] = array(
            'label'    => esc_html__('AquaLuxe', 'aqualuxe'),
            'target'   => 'aqualuxe_product_data',
            'class'    => array('show_if_simple', 'show_if_variable'),
            'priority' => 21,
        );
        
        return $tabs;
    }

    /**
     * Product data panels
     */
    public function product_data_panels() {
        global $post;
        
        $product = wc_get_product($post->ID);
        
        $video_url = get_post_meta($post->ID, '_video_url', true);
        $video_thumbnail = get_post_meta($post->ID, '_video_thumbnail', true);
        $size_chart = get_post_meta($post->ID, '_size_chart', true);
        $custom_tab_title = get_post_meta($post->ID, '_custom_tab_title', true);
        $custom_tab_content = get_post_meta($post->ID, '_custom_tab_content', true);
        
        ?>
        <div id="aqualuxe_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <label for="video_url"><?php esc_html_e('Video URL', 'aqualuxe'); ?></label>
                    <input type="text" class="short" name="video_url" id="video_url" value="<?php echo esc_attr($video_url); ?>" />
                    <span class="description"><?php esc_html_e('Enter a YouTube or Vimeo URL', 'aqualuxe'); ?></span>
                </p>
                
                <p class="form-field">
                    <label for="video_thumbnail"><?php esc_html_e('Video Thumbnail', 'aqualuxe'); ?></label>
                    <div class="video-thumbnail-preview">
                        <?php if ($video_thumbnail) : ?>
                            <?php echo wp_get_attachment_image($video_thumbnail, 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="video_thumbnail" id="video_thumbnail" value="<?php echo esc_attr($video_thumbnail); ?>" />
                    <button type="button" class="button upload-video-thumbnail"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
                    <button type="button" class="button remove-video-thumbnail" <?php echo $video_thumbnail ? '' : 'style="display:none;"'; ?>><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
                </p>
                
                <p class="form-field">
                    <label for="size_chart"><?php esc_html_e('Size Chart', 'aqualuxe'); ?></label>
                    <div class="size-chart-preview">
                        <?php if ($size_chart) : ?>
                            <?php echo wp_get_attachment_image($size_chart, 'thumbnail'); ?>
                        <?php endif; ?>
                    </div>
                    <input type="hidden" name="size_chart" id="size_chart" value="<?php echo esc_attr($size_chart); ?>" />
                    <button type="button" class="button upload-size-chart"><?php esc_html_e('Upload/Add image', 'aqualuxe'); ?></button>
                    <button type="button" class="button remove-size-chart" <?php echo $size_chart ? '' : 'style="display:none;"'; ?>><?php esc_html_e('Remove image', 'aqualuxe'); ?></button>
                </p>
            </div>
            
            <div class="options_group">
                <p class="form-field">
                    <label for="custom_tab_title"><?php esc_html_e('Custom Tab Title', 'aqualuxe'); ?></label>
                    <input type="text" class="short" name="custom_tab_title" id="custom_tab_title" value="<?php echo esc_attr($custom_tab_title); ?>" />
                    <span class="description"><?php esc_html_e('Enter a title for the custom tab', 'aqualuxe'); ?></span>
                </p>
                
                <p class="form-field">
                    <label for="custom_tab_content"><?php esc_html_e('Custom Tab Content', 'aqualuxe'); ?></label>
                    <?php
                    $settings = array(
                        'textarea_name' => 'custom_tab_content',
                        'textarea_rows' => 10,
                        'media_buttons' => true,
                    );
                    wp_editor($custom_tab_content, 'custom_tab_content', $settings);
                    ?>
                    <span class="description"><?php esc_html_e('Enter the content for the custom tab', 'aqualuxe'); ?></span>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Save product data fields
     * 
     * @param int $post_id Post ID.
     */
    public function save_product_data_fields($post_id) {
        if (isset($_POST['video_url'])) {
            update_post_meta($post_id, '_video_url', sanitize_text_field($_POST['video_url']));
        }
        
        if (isset($_POST['video_thumbnail'])) {
            update_post_meta($post_id, '_video_thumbnail', absint($_POST['video_thumbnail']));
        }
        
        if (isset($_POST['size_chart'])) {
            update_post_meta($post_id, '_size_chart', absint($_POST['size_chart']));
        }
        
        if (isset($_POST['custom_tab_title'])) {
            update_post_meta($post_id, '_custom_tab_title', sanitize_text_field($_POST['custom_tab_title']));
        }
        
        if (isset($_POST['custom_tab_content'])) {
            update_post_meta($post_id, '_custom_tab_content', wp_kses_post($_POST['custom_tab_content']));
        }
    }

    /**
     * Product filters
     */
    public function product_filters() {
        global $typenow;
        
        if ('product' !== $typenow) {
            return;
        }
        
        // Filter by stock status
        $stock_statuses = array(
            'instock'    => esc_html__('In Stock', 'aqualuxe'),
            'outofstock' => esc_html__('Out of Stock', 'aqualuxe'),
            'onbackorder' => esc_html__('On Backorder', 'aqualuxe'),
        );
        
        $current_stock_status = isset($_GET['stock_status']) ? wc_clean($_GET['stock_status']) : '';
        
        ?>
        <select name="stock_status">
            <option value=""><?php esc_html_e('Filter by stock status', 'aqualuxe'); ?></option>
            <?php foreach ($stock_statuses as $status => $label) : ?>
                <option value="<?php echo esc_attr($status); ?>" <?php selected($current_stock_status, $status); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
        
        // Filter by featured
        $featured_options = array(
            'yes' => esc_html__('Featured', 'aqualuxe'),
            'no'  => esc_html__('Not Featured', 'aqualuxe'),
        );
        
        $current_featured = isset($_GET['featured']) ? wc_clean($_GET['featured']) : '';
        
        ?>
        <select name="featured">
            <option value=""><?php esc_html_e('Filter by featured', 'aqualuxe'); ?></option>
            <?php foreach ($featured_options as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($current_featured, $value); ?>><?php echo esc_html($label); ?></option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Product filters query
     * 
     * @param WP_Query $query Query object.
     * @return WP_Query
     */
    public function product_filters_query($query) {
        global $typenow;
        
        if ('product' !== $typenow || !is_admin()) {
            return $query;
        }
        
        // Filter by stock status
        if (isset($_GET['stock_status']) && !empty($_GET['stock_status'])) {
            $stock_status = wc_clean($_GET['stock_status']);
            
            $meta_query = $query->get('meta_query');
            $meta_query = $meta_query ? $meta_query : array();
            
            $meta_query[] = array(
                'key'   => '_stock_status',
                'value' => $stock_status,
            );
            
            $query->set('meta_query', $meta_query);
        }
        
        // Filter by featured
        if (isset($_GET['featured']) && !empty($_GET['featured'])) {
            $featured = wc_clean($_GET['featured']);
            
            $tax_query = $query->get('tax_query');
            $tax_query = $tax_query ? $tax_query : array();
            
            if ('yes' === $featured) {
                $tax_query[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                );
            } elseif ('no' === $featured) {
                $tax_query[] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'NOT IN',
                );
            }
            
            $query->set('tax_query', $tax_query);
        }
        
        return $query;
    }

    /**
     * Product bulk actions
     * 
     * @param array $actions Bulk actions.
     * @return array
     */
    public function product_bulk_actions($actions) {
        $actions['mark_featured'] = esc_html__('Mark as Featured', 'aqualuxe');
        $actions['unmark_featured'] = esc_html__('Remove Featured', 'aqualuxe');
        
        return $actions;
    }

    /**
     * Handle product bulk actions
     * 
     * @param string $redirect_to Redirect URL.
     * @param string $action      Bulk action.
     * @param array  $post_ids    Post IDs.
     * @return string
     */
    public function handle_product_bulk_actions($redirect_to, $action, $post_ids) {
        if ('mark_featured' === $action) {
            foreach ($post_ids as $post_id) {
                $product = wc_get_product($post_id);
                if ($product) {
                    $product->set_featured(true);
                    $product->save();
                }
            }
            
            $redirect_to = add_query_arg('mark_featured', count($post_ids), $redirect_to);
        } elseif ('unmark_featured' === $action) {
            foreach ($post_ids as $post_id) {
                $product = wc_get_product($post_id);
                if ($product) {
                    $product->set_featured(false);
                    $product->save();
                }
            }
            
            $redirect_to = add_query_arg('unmark_featured', count($post_ids), $redirect_to);
        }
        
        return $redirect_to;
    }

    /**
     * Product admin notices
     */
    public function product_admin_notices() {
        global $post_type, $pagenow;
        
        if ('edit.php' !== $pagenow || 'product' !== $post_type) {
            return;
        }
        
        if (isset($_REQUEST['mark_featured']) && $_REQUEST['mark_featured']) {
            $count = intval($_REQUEST['mark_featured']);
            printf(
                '<div class="updated"><p>' . _n('%s product marked as featured.', '%s products marked as featured.', $count, 'aqualuxe') . '</p></div>',
                $count
            );
        }
        
        if (isset($_REQUEST['unmark_featured']) && $_REQUEST['unmark_featured']) {
            $count = intval($_REQUEST['unmark_featured']);
            printf(
                '<div class="updated"><p>' . _n('%s product removed from featured.', '%s products removed from featured.', $count, 'aqualuxe') . '</p></div>',
                $count
            );
        }
    }

    /**
     * Add product meta boxes
     */
    public function add_product_meta_boxes() {
        add_meta_box(
            'aqualuxe_product_video',
            esc_html__('Product Video', 'aqualuxe'),
            array($this, 'product_video_meta_box_callback'),
            'product',
            'side',
            'default'
        );
    }

    /**
     * Product video meta box callback
     * 
     * @param WP_Post $post The post object.
     */
    public function product_video_meta_box_callback($post) {
        // Add nonce for security
        wp_nonce_field('aqualuxe_product_video_meta_box', 'aqualuxe_product_video_meta_box_nonce');
        
        // Get the saved values
        $video_url = get_post_meta($post->ID, '_video_url', true);
        
        ?>
        <p>
            <label for="video_url"><?php esc_html_e('Video URL', 'aqualuxe'); ?></label>
            <input type="text" class="widefat" name="video_url" id="video_url" value="<?php echo esc_attr($video_url); ?>" />
            <span class="description"><?php esc_html_e('Enter a YouTube or Vimeo URL', 'aqualuxe'); ?></span>
        </p>
        <?php
    }

    /**
     * Save product meta box data
     * 
     * @param int $post_id The post ID.
     */
    public function save_product_meta_box_data($post_id) {
        // Check if we're doing an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check if our nonce is set
        if (!isset($_POST['aqualuxe_product_video_meta_box_nonce'])) {
            return;
        }
        
        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['aqualuxe_product_video_meta_box_nonce'], 'aqualuxe_product_video_meta_box')) {
            return;
        }
        
        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Update the meta fields
        if (isset($_POST['video_url'])) {
            update_post_meta($post_id, '_video_url', sanitize_text_field($_POST['video_url']));
        }
    }

    /**
     * Product admin columns
     * 
     * @param array $columns Admin columns.
     * @return array
     */
    public function product_admin_columns($columns) {
        $columns['featured'] = esc_html__('Featured', 'aqualuxe');
        $columns['stock'] = esc_html__('Stock', 'aqualuxe');
        
        return $columns;
    }

    /**
     * Product admin custom column
     * 
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function product_admin_custom_column($column, $post_id) {
        $product = wc_get_product($post_id);
        
        if (!$product) {
            return;
        }
        
        switch ($column) {
            case 'featured':
                if ($product->is_featured()) {
                    echo '<span class="dashicons dashicons-star-filled" style="color: #f7d708;"></span>';
                } else {
                    echo '<span class="dashicons dashicons-star-empty"></span>';
                }
                break;
            
            case 'stock':
                if ($product->is_in_stock()) {
                    echo '<span class="in-stock">' . esc_html__('In Stock', 'aqualuxe') . '</span>';
                } else {
                    echo '<span class="out-of-stock">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
                }
                break;
        }
    }

    /**
     * Product admin sortable columns
     * 
     * @param array $columns Sortable columns.
     * @return array
     */
    public function product_admin_sortable_columns($columns) {
        $columns['featured'] = 'featured';
        $columns['stock'] = 'stock';
        
        return $columns;
    }

    /**
     * Product admin column orderby
     * 
     * @param WP_Query $query Query object.
     */
    public function product_admin_column_orderby($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
        
        if ('featured' === $query->get('orderby')) {
            $query->set('meta_key', '_featured');
            $query->set('orderby', 'meta_value');
        }
        
        if ('stock' === $query->get('orderby')) {
            $query->set('meta_key', '_stock_status');
            $query->set('orderby', 'meta_value');
        }
    }

    /**
     * Product bulk edit
     * 
     * @param string $column_name Column name.
     * @param string $post_type   Post type.
     */
    public function product_bulk_edit($column_name, $post_type) {
        if ('product' !== $post_type) {
            return;
        }
        
        if ('price' === $column_name) {
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php esc_html_e('Featured', 'aqualuxe'); ?></span>
                        <select name="_featured">
                            <option value=""><?php esc_html_e('— No Change —', 'aqualuxe'); ?></option>
                            <option value="yes"><?php esc_html_e('Yes', 'aqualuxe'); ?></option>
                            <option value="no"><?php esc_html_e('No', 'aqualuxe'); ?></option>
                        </select>
                    </label>
                </div>
            </fieldset>
            <?php
        }
    }

    /**
     * Product bulk edit save
     * 
     * @param int $post_id Post ID.
     */
    public function product_bulk_edit_save($post_id) {
        if ('product' !== get_post_type($post_id)) {
            return;
        }
        
        if (!isset($_REQUEST['_featured'])) {
            return;
        }
        
        $featured = wc_clean($_REQUEST['_featured']);
        
        if ('' !== $featured) {
            $product = wc_get_product($post_id);
            $product->set_featured('yes' === $featured);
            $product->save();
        }
    }

    /**
     * Product quick edit
     * 
     * @param string $column_name Column name.
     * @param string $post_type   Post type.
     */
    public function product_quick_edit($column_name, $post_type) {
        if ('product' !== $post_type) {
            return;
        }
        
        if ('price' === $column_name) {
            ?>
            <fieldset class="inline-edit-col-right">
                <div class="inline-edit-col">
                    <label>
                        <span class="title"><?php esc_html_e('Featured', 'aqualuxe'); ?></span>
                        <select name="_featured">
                            <option value=""><?php esc_html_e('— No Change —', 'aqualuxe'); ?></option>
                            <option value="yes"><?php esc_html_e('Yes', 'aqualuxe'); ?></option>
                            <option value="no"><?php esc_html_e('No', 'aqualuxe'); ?></option>
                        </select>
                    </label>
                </div>
            </fieldset>
            <?php
        }
    }

    /**
     * Product quick edit save
     * 
     * @param int $post_id Post ID.
     */
    public function product_quick_edit_save($post_id) {
        if ('product' !== get_post_type($post_id)) {
            return;
        }
        
        if (!isset($_REQUEST['_featured'])) {
            return;
        }
        
        $featured = wc_clean($_REQUEST['_featured']);
        
        if ('' !== $featured) {
            $product = wc_get_product($post_id);
            $product->set_featured('yes' === $featured);
            $product->save();
        }
    }

    /**
     * Product row actions
     * 
     * @param array   $actions Row actions.
     * @param WP_Post $post    Post object.
     * @return array
     */
    public function product_row_actions($actions, $post) {
        if ('product' === $post->post_type) {
            $product = wc_get_product($post->ID);
            
            if ($product) {
                if ($product->is_featured()) {
                    $actions['unmark_featured'] = sprintf(
                        '<a href="%s" class="unmark-featured">%s</a>',
                        wp_nonce_url(admin_url('admin-ajax.php?action=aqualuxe_product_admin_ajax&do=unmark_featured&product_id=' . $post->ID), 'aqualuxe_product_admin_ajax'),
                        esc_html__('Remove Featured', 'aqualuxe')
                    );
                } else {
                    $actions['mark_featured'] = sprintf(
                        '<a href="%s" class="mark-featured">%s</a>',
                        wp_nonce_url(admin_url('admin-ajax.php?action=aqualuxe_product_admin_ajax&do=mark_featured&product_id=' . $post->ID), 'aqualuxe_product_admin_ajax'),
                        esc_html__('Mark as Featured', 'aqualuxe')
                    );
                }
            }
        }
        
        return $actions;
    }

    /**
     * Product admin scripts
     */
    public function product_admin_scripts() {
        global $post_type;
        
        if ('product' !== $post_type) {
            return;
        }
        
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.mark-featured, .unmark-featured').on('click', function(e) {
                    e.preventDefault();
                    
                    var link = $(this);
                    var url = link.attr('href');
                    
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                window.location.reload();
                            } else {
                                alert(response.data.message);
                            }
                        }
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * Product admin enqueue scripts
     */
    public function product_admin_enqueue_scripts() {
        global $post_type;
        
        if ('product' !== $post_type) {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_script(
            'aqualuxe-product-admin',
            AQUALUXE_URI . 'assets/js/product-admin.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-product-admin',
            'aqualuxeProductAdmin',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-product-admin-nonce'),
            )
        );
    }

    /**
     * Product admin AJAX
     */
    public function product_admin_ajax() {
        // Check nonce
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'aqualuxe_product_admin_ajax')) {
            wp_send_json_error(array('message' => esc_html__('Invalid nonce', 'aqualuxe')));
        }
        
        // Check product ID
        if (!isset($_REQUEST['product_id']) || empty($_REQUEST['product_id'])) {
            wp_send_json_error(array('message' => esc_html__('Invalid product ID', 'aqualuxe')));
        }
        
        // Check action
        if (!isset($_REQUEST['do']) || empty($_REQUEST['do'])) {
            wp_send_json_error(array('message' => esc_html__('Invalid action', 'aqualuxe')));
        }
        
        $product_id = absint($_REQUEST['product_id']);
        $action = wc_clean($_REQUEST['do']);
        
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array('message' => esc_html__('Invalid product', 'aqualuxe')));
        }
        
        switch ($action) {
            case 'mark_featured':
                $product->set_featured(true);
                $product->save();
                wp_send_json_success(array('message' => esc_html__('Product marked as featured', 'aqualuxe')));
                break;
            
            case 'unmark_featured':
                $product->set_featured(false);
                $product->save();
                wp_send_json_success(array('message' => esc_html__('Product removed from featured', 'aqualuxe')));
                break;
            
            default:
                wp_send_json_error(array('message' => esc_html__('Invalid action', 'aqualuxe')));
                break;
        }
    }

    /**
     * Product settings
     * 
     * @param array  $settings Settings.
     * @param string $section  Section.
     * @return array
     */
    public function product_settings($settings, $section) {
        if ('aqualuxe' === $section) {
            $settings = array(
                array(
                    'title' => esc_html__('AquaLuxe Product Settings', 'aqualuxe'),
                    'type'  => 'title',
                    'id'    => 'aqualuxe_product_settings',
                ),
                
                array(
                    'title'   => esc_html__('Default Shipping Content', 'aqualuxe'),
                    'desc'    => esc_html__('Enter the default content for the shipping tab', 'aqualuxe'),
                    'id'      => 'aqualuxe_default_shipping_content',
                    'type'    => 'textarea',
                    'default' => '',
                ),
                
                array(
                    'title'   => esc_html__('Default Care Guide Content', 'aqualuxe'),
                    'desc'    => esc_html__('Enter the default content for the care guide tab', 'aqualuxe'),
                    'id'      => 'aqualuxe_default_care_content',
                    'type'    => 'textarea',
                    'default' => '',
                ),
                
                array(
                    'type' => 'sectionend',
                    'id'   => 'aqualuxe_product_settings',
                ),
            );
        }
        
        return $settings;
    }

    /**
     * Product settings sections
     * 
     * @param array $sections Settings sections.
     * @return array
     */
    public function product_settings_sections($sections) {
        $sections['aqualuxe'] = esc_html__('AquaLuxe', 'aqualuxe');
        
        return $sections;
    }

    /**
     * Product settings fields
     * 
     * @param array  $settings Settings.
     * @param string $section  Section.
     * @return array
     */
    public function product_settings_fields($settings, $section) {
        if ('aqualuxe' === $section) {
            $settings = array(
                array(
                    'title' => esc_html__('AquaLuxe Product Settings', 'aqualuxe'),
                    'type'  => 'title',
                    'id'    => 'aqualuxe_product_settings',
                ),
                
                array(
                    'title'   => esc_html__('Default Shipping Content', 'aqualuxe'),
                    'desc'    => esc_html__('Enter the default content for the shipping tab', 'aqualuxe'),
                    'id'      => 'aqualuxe_default_shipping_content',
                    'type'    => 'textarea',
                    'default' => '',
                ),
                
                array(
                    'title'   => esc_html__('Default Care Guide Content', 'aqualuxe'),
                    'desc'    => esc_html__('Enter the default content for the care guide tab', 'aqualuxe'),
                    'id'      => 'aqualuxe_default_care_content',
                    'type'    => 'textarea',
                    'default' => '',
                ),
                
                array(
                    'type' => 'sectionend',
                    'id'   => 'aqualuxe_product_settings',
                ),
            );
        }
        
        return $settings;
    }

    /**
     * Product settings tabs
     * 
     * @param array $tabs Settings tabs.
     * @return array
     */
    public function product_settings_tabs($tabs) {
        $tabs['aqualuxe'] = esc_html__('AquaLuxe', 'aqualuxe');
        
        return $tabs;
    }

    /**
     * Product settings tab content
     */
    public function product_settings_tab_content() {
        woocommerce_admin_fields($this->get_settings());
    }

    /**
     * Product settings save
     */
    public function product_settings_save() {
        woocommerce_update_options($this->get_settings());
    }

    /**
     * Product settings sections content
     */
    public function product_settings_sections_content() {
        global $current_section;
        
        echo '<ul class="subsubsub">';
        
        $sections = array(
            '' => esc_html__('General', 'aqualuxe'),
            'shipping' => esc_html__('Shipping', 'aqualuxe'),
            'care' => esc_html__('Care Guide', 'aqualuxe'),
        );
        
        $array_keys = array_keys($sections);
        
        foreach ($sections as $id => $label) {
            echo '<li><a href="' . admin_url('admin.php?page=wc-settings&tab=aqualuxe&section=' . $id) . '" class="' . ($current_section === $id ? 'current' : '') . '">' . $label . '</a> ' . (end($array_keys) === $id ? '' : '|') . ' </li>';
        }
        
        echo '</ul><br class="clear" />';
    }

    /**
     * Product settings fields content
     */
    public function product_settings_fields_content() {
        global $current_section;
        
        if ('' === $current_section) {
            $settings = $this->get_general_settings();
        } elseif ('shipping' === $current_section) {
            $settings = $this->get_shipping_settings();
        } elseif ('care' === $current_section) {
            $settings = $this->get_care_settings();
        } else {
            $settings = array();
        }
        
        woocommerce_admin_fields($settings);
    }

    /**
     * Product settings fields save
     */
    public function product_settings_fields_save() {
        global $current_section;
        
        if ('' === $current_section) {
            $settings = $this->get_general_settings();
        } elseif ('shipping' === $current_section) {
            $settings = $this->get_shipping_settings();
        } elseif ('care' === $current_section) {
            $settings = $this->get_care_settings();
        } else {
            $settings = array();
        }
        
        woocommerce_update_options($settings);
    }

    /**
     * Get general settings
     * 
     * @return array
     */
    public function get_general_settings() {
        return array(
            array(
                'title' => esc_html__('General Settings', 'aqualuxe'),
                'type'  => 'title',
                'id'    => 'aqualuxe_general_settings',
            ),
            
            array(
                'title'   => esc_html__('Show Quick View', 'aqualuxe'),
                'desc'    => esc_html__('Enable quick view button on products', 'aqualuxe'),
                'id'      => 'aqualuxe_show_quick_view',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'title'   => esc_html__('Show Wishlist', 'aqualuxe'),
                'desc'    => esc_html__('Enable wishlist button on products', 'aqualuxe'),
                'id'      => 'aqualuxe_show_wishlist',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'title'   => esc_html__('Show Recently Viewed', 'aqualuxe'),
                'desc'    => esc_html__('Enable recently viewed products on product page', 'aqualuxe'),
                'id'      => 'aqualuxe_show_recently_viewed',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'title'   => esc_html__('Show Related Products', 'aqualuxe'),
                'desc'    => esc_html__('Enable related products on product page', 'aqualuxe'),
                'id'      => 'aqualuxe_show_related_products',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'title'   => esc_html__('Related Products Count', 'aqualuxe'),
                'desc'    => esc_html__('Number of related products to display', 'aqualuxe'),
                'id'      => 'aqualuxe_related_products_count',
                'type'    => 'number',
                'default' => '4',
                'custom_attributes' => array(
                    'min'  => '1',
                    'max'  => '12',
                    'step' => '1',
                ),
            ),
            
            array(
                'title'   => esc_html__('Show Upsells', 'aqualuxe'),
                'desc'    => esc_html__('Enable upsell products on product page', 'aqualuxe'),
                'id'      => 'aqualuxe_show_upsells',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'title'   => esc_html__('Show Order Notes', 'aqualuxe'),
                'desc'    => esc_html__('Enable order notes field on checkout page', 'aqualuxe'),
                'id'      => 'aqualuxe_show_order_notes',
                'type'    => 'checkbox',
                'default' => 'yes',
            ),
            
            array(
                'type' => 'sectionend',
                'id'   => 'aqualuxe_general_settings',
            ),
        );
    }

    /**
     * Get shipping settings
     * 
     * @return array
     */
    public function get_shipping_settings() {
        return array(
            array(
                'title' => esc_html__('Shipping Settings', 'aqualuxe'),
                'type'  => 'title',
                'id'    => 'aqualuxe_shipping_settings',
            ),
            
            array(
                'title'   => esc_html__('Default Shipping Content', 'aqualuxe'),
                'desc'    => esc_html__('Enter the default content for the shipping tab', 'aqualuxe'),
                'id'      => 'aqualuxe_default_shipping_content',
                'type'    => 'textarea',
                'default' => '',
                'css'     => 'min-height: 200px;',
            ),
            
            array(
                'type' => 'sectionend',
                'id'   => 'aqualuxe_shipping_settings',
            ),
        );
    }

    /**
     * Get care settings
     * 
     * @return array
     */
    public function get_care_settings() {
        return array(
            array(
                'title' => esc_html__('Care Guide Settings', 'aqualuxe'),
                'type'  => 'title',
                'id'    => 'aqualuxe_care_settings',
            ),
            
            array(
                'title'   => esc_html__('Default Care Guide Content', 'aqualuxe'),
                'desc'    => esc_html__('Enter the default content for the care guide tab', 'aqualuxe'),
                'id'      => 'aqualuxe_default_care_content',
                'type'    => 'textarea',
                'default' => '',
                'css'     => 'min-height: 200px;',
            ),
            
            array(
                'type' => 'sectionend',
                'id'   => 'aqualuxe_care_settings',
            ),
        );
    }

    /**
     * Get settings
     * 
     * @return array
     */
    public function get_settings() {
        return array(
            array(
                'title' => esc_html__('AquaLuxe Product Settings', 'aqualuxe'),
                'type'  => 'title',
                'id'    => 'aqualuxe_product_settings',
            ),
            
            array(
                'title'   => esc_html__('Default Shipping Content', 'aqualuxe'),
                'desc'    => esc_html__('Enter the default content for the shipping tab', 'aqualuxe'),
                'id'      => 'aqualuxe_default_shipping_content',
                'type'    => 'textarea',
                'default' => '',
            ),
            
            array(
                'title'   => esc_html__('Default Care Guide Content', 'aqualuxe'),
                'desc'    => esc_html__('Enter the default content for the care guide tab', 'aqualuxe'),
                'id'      => 'aqualuxe_default_care_content',
                'type'    => 'textarea',
                'default' => '',
            ),
            
            array(
                'type' => 'sectionend',
                'id'   => 'aqualuxe_product_settings',
            ),
        );
    }

    /**
     * Wrapper start
     */
    public function wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<main id="main" class="site-main" role="main">';
    }

    /**
     * Wrapper end
     */
    public function wrapper_end() {
        echo '</main><!-- #main -->';
        echo '</div><!-- #primary -->';
    }

    /**
     * Get sidebar
     */
    public function get_sidebar() {
        if (is_shop() || is_product_category() || is_product_tag()) {
            if (aqualuxe_get_option('show_shop_sidebar', true)) {
                get_sidebar('shop');
            }
        } elseif (is_product()) {
            if (aqualuxe_get_option('show_product_sidebar', false)) {
                get_sidebar('shop');
            }
        }
    }

    /**
     * Breadcrumbs
     */
    public function breadcrumbs() {
        if (aqualuxe_get_option('enable_breadcrumbs', true)) {
            woocommerce_breadcrumb(array(
                'wrap_before' => '<div class="breadcrumbs">',
                'wrap_after'  => '</div>',
                'before'      => '<span>',
                'after'       => '</span>',
                'delimiter'   => '<span class="breadcrumb-delimiter">/</span>',
            ));
        }
    }

    /**
     * Result count
     */
    public function result_count() {
        woocommerce_result_count();
    }

    /**
     * Catalog ordering
     */
    public function catalog_ordering() {
        woocommerce_catalog_ordering();
    }

    /**
     * Pagination
     */
    public function pagination() {
        woocommerce_pagination();
    }

    /**
     * Template loop product title
     */
    public function template_loop_product_title() {
        echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
    }

    /**
     * Template loop rating
     */
    public function template_loop_rating() {
        woocommerce_template_loop_rating();
    }

    /**
     * Template loop price
     */
    public function template_loop_price() {
        woocommerce_template_loop_price();
    }

    /**
     * Template loop add to cart
     */
    public function template_loop_add_to_cart() {
        woocommerce_template_loop_add_to_cart();
    }

    /**
     * Template single title
     */
    public function template_single_title() {
        the_title('<h1 class="product_title entry-title">', '</h1>');
    }

    /**
     * Template single rating
     */
    public function template_single_rating() {
        woocommerce_template_single_rating();
    }

    /**
     * Template single price
     */
    public function template_single_price() {
        woocommerce_template_single_price();
    }

    /**
     * Template single excerpt
     */
    public function template_single_excerpt() {
        woocommerce_template_single_excerpt();
    }

    /**
     * Template single add to cart
     */
    public function template_single_add_to_cart() {
        woocommerce_template_single_add_to_cart();
    }

    /**
     * Template single meta
     */
    public function template_single_meta() {
        woocommerce_template_single_meta();
    }

    /**
     * Template single sharing
     */
    public function template_single_sharing() {
        woocommerce_template_single_sharing();
    }

    /**
     * Output product data tabs
     */
    public function output_product_data_tabs() {
        woocommerce_output_product_data_tabs();
    }

    /**
     * Output related products
     */
    public function output_related_products() {
        if (aqualuxe_get_option('show_related_products', true)) {
            woocommerce_output_related_products();
        }
    }

    /**
     * Upsell display
     */
    public function upsell_display() {
        if (aqualuxe_get_option('show_upsells', true)) {
            woocommerce_upsell_display();
        }
    }

    /**
     * Cross sell display
     */
    public function cross_sell_display() {
        woocommerce_cross_sell_display();
    }

    /**
     * Cart totals
     */
    public function cart_totals() {
        woocommerce_cart_totals();
    }

    /**
     * Checkout coupon form
     */
    public function checkout_coupon_form() {
        woocommerce_checkout_coupon_form();
    }

    /**
     * Checkout login form
     */
    public function checkout_login_form() {
        woocommerce_checkout_login_form();
    }

    /**
     * Checkout billing form
     */
    public function checkout_billing_form() {
        woocommerce_checkout_billing_form();
    }

    /**
     * Checkout shipping form
     */
    public function checkout_shipping_form() {
        woocommerce_checkout_shipping_form();
    }

    /**
     * Checkout payment
     */
    public function checkout_payment() {
        woocommerce_checkout_payment();
    }

    /**
     * Order review
     */
    public function order_review() {
        woocommerce_order_review();
    }

    /**
     * Account navigation
     */
    public function account_navigation() {
        woocommerce_account_navigation();
    }

    /**
     * Account content
     */
    public function account_content() {
        woocommerce_account_content();
    }

    /**
     * Account dashboard
     */
    public function account_dashboard() {
        woocommerce_account_dashboard();
    }

    /**
     * Account orders
     */
    public function account_orders() {
        woocommerce_account_orders();
    }

    /**
     * Account downloads
     */
    public function account_downloads() {
        woocommerce_account_downloads();
    }

    /**
     * Account edit address
     */
    public function account_edit_address() {
        woocommerce_account_edit_address();
    }

    /**
     * Account payment methods
     */
    public function account_payment_methods() {
        woocommerce_account_payment_methods();
    }

    /**
     * Account edit account
     */
    public function account_edit_account() {
        woocommerce_account_edit_account();
    }

    /**
     * Account view order
     */
    public function account_view_order() {
        woocommerce_account_view_order();
    }
}

// Initialize the class
new AquaLuxe_WooCommerce();