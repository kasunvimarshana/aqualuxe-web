<?php
/**
 * AquaLuxe WooCommerce Class
 *
 * Handles all WooCommerce customizations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce Class
 */
class AquaLuxe_WooCommerce {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_WooCommerce
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_WooCommerce
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Only proceed if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Setup WooCommerce
        add_action('after_setup_theme', array($this, 'setup_woocommerce'));
        
        // Modify WooCommerce templates
        add_filter('woocommerce_locate_template', array($this, 'woocommerce_locate_template'), 10, 3);
        
        // Modify product columns
        add_filter('loop_shop_columns', array($this, 'loop_shop_columns'));
        
        // Modify products per page
        add_filter('loop_shop_per_page', array($this, 'products_per_page'));
        
        // Modify related products
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        
        // Add product hover effect
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'product_image_wrapper_open'), 5);
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'product_image_wrapper_close'), 15);
        
        // Add quick view button
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'quick_view_button'), 10);
        
        // Add wishlist button
        add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 15);
        
        // Modify add to cart button
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'modify_add_to_cart_button'), 10, 2);
        
        // AJAX add to cart
        add_action('wp_ajax_aqualuxe_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_aqualuxe_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        
        // AJAX quick view
        add_action('wp_ajax_aqualuxe_quick_view', array($this, 'load_quick_view'));
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', array($this, 'load_quick_view'));
        
        // Add quick view modal container
        add_action('wp_footer', array($this, 'quick_view_modal'));
        
        // Modify checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'customize_checkout_fields'));
        
        // Add custom order fields
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_custom_checkout_fields'));
        
        // Display custom order fields in admin
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'display_custom_order_fields_in_admin'), 10, 1);
        
        // Add custom product tabs
        add_filter('woocommerce_product_tabs', array($this, 'custom_product_tabs'));
        
        // Add size guide
        add_action('woocommerce_before_add_to_cart_form', array($this, 'size_guide_button'));
        add_action('wp_footer', array($this, 'size_guide_modal'));
        
        // Add product navigation
        add_action('woocommerce_before_single_product_summary', array($this, 'product_navigation'), 5);
        
        // Modify product gallery
        add_filter('woocommerce_single_product_image_gallery_classes', array($this, 'product_gallery_classes'));
        
        // Add social sharing
        add_action('woocommerce_share', array($this, 'product_sharing'));
        
        // Add recently viewed products
        add_action('template_redirect', array($this, 'track_product_view'));
        add_action('woocommerce_after_single_product', array($this, 'recently_viewed_products'));
        
        // Add mini cart AJAX update
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_fragments'));
        
        // Add product filter widgets
        add_action('widgets_init', array($this, 'register_product_filter_widgets'));
        
        // Add AJAX product filtering
        add_action('wp_ajax_aqualuxe_filter_products', array($this, 'ajax_filter_products'));
        add_action('wp_ajax_nopriv_aqualuxe_filter_products', array($this, 'ajax_filter_products'));
        
        // Add product sorting options
        add_filter('woocommerce_catalog_orderby', array($this, 'custom_product_sorting_options'));
        
        // Add product layout switcher
        add_action('woocommerce_before_shop_loop', array($this, 'product_layout_switcher'), 35);
        
        // Add shop sidebar toggle
        add_action('woocommerce_before_shop_loop', array($this, 'shop_sidebar_toggle'), 5);
        
        // Add shop filters
        add_action('woocommerce_before_shop_loop', array($this, 'shop_filters'), 20);
        
        // Add shop breadcrumbs
        add_action('woocommerce_before_main_content', array($this, 'shop_breadcrumbs'), 10);
        
        // Add shop title
        add_action('woocommerce_archive_description', array($this, 'shop_title'), 5);
        
        // Add shop description
        add_action('woocommerce_archive_description', array($this, 'shop_description'), 10);
        
        // Add shop banner
        add_action('woocommerce_before_main_content', array($this, 'shop_banner'), 5);
        
        // Add shop sidebar
        add_action('woocommerce_sidebar', array($this, 'shop_sidebar'), 10);
        
        // Add shop pagination
        add_action('woocommerce_after_shop_loop', array($this, 'shop_pagination'), 10);
        
        // Add shop empty message
        add_action('woocommerce_no_products_found', array($this, 'shop_empty_message'), 10);
        
        // Add shop categories
        add_action('woocommerce_before_shop_loop', array($this, 'shop_categories'), 15);
        
        // Add shop brands
        add_action('woocommerce_before_shop_loop', array($this, 'shop_brands'), 16);
        
        // Add shop tags
        add_action('woocommerce_before_shop_loop', array($this, 'shop_tags'), 17);
        
        // Add shop featured products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_featured_products'), 18);
        
        // Add shop new products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_new_products'), 19);
        
        // Add shop sale products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_sale_products'), 20);
        
        // Add shop best selling products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_best_selling_products'), 21);
        
        // Add shop top rated products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_top_rated_products'), 22);
        
        // Add shop popular products
        add_action('woocommerce_before_shop_loop', array($this, 'shop_popular_products'), 23);
        
        // Add shop related products
        add_action('woocommerce_after_shop_loop', array($this, 'shop_related_products'), 15);
        
        // Add shop upsell products
        add_action('woocommerce_after_shop_loop', array($this, 'shop_upsell_products'), 16);
        
        // Add shop cross sell products
        add_action('woocommerce_after_shop_loop', array($this, 'shop_cross_sell_products'), 17);
        
        // Add shop recently viewed products
        add_action('woocommerce_after_shop_loop', array($this, 'shop_recently_viewed_products'), 18);
        
        // Add shop newsletter
        add_action('woocommerce_after_shop_loop', array($this, 'shop_newsletter'), 19);
        
        // Add shop social sharing
        add_action('woocommerce_after_shop_loop', array($this, 'shop_social_sharing'), 20);
        
        // Add shop back to top button
        add_action('woocommerce_after_shop_loop', array($this, 'shop_back_to_top'), 25);
        
        // Add shop mobile filter button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_filter_button'), 5);
        
        // Add shop mobile filter sidebar
        add_action('wp_footer', array($this, 'shop_mobile_filter_sidebar'));
        
        // Add shop mobile sort button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_sort_button'), 6);
        
        // Add shop mobile sort sidebar
        add_action('wp_footer', array($this, 'shop_mobile_sort_sidebar'));
        
        // Add shop mobile categories button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_categories_button'), 7);
        
        // Add shop mobile categories sidebar
        add_action('wp_footer', array($this, 'shop_mobile_categories_sidebar'));
        
        // Add shop mobile brands button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_brands_button'), 8);
        
        // Add shop mobile brands sidebar
        add_action('wp_footer', array($this, 'shop_mobile_brands_sidebar'));
        
        // Add shop mobile tags button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_tags_button'), 9);
        
        // Add shop mobile tags sidebar
        add_action('wp_footer', array($this, 'shop_mobile_tags_sidebar'));
        
        // Add shop mobile search button
        add_action('woocommerce_before_shop_loop', array($this, 'shop_mobile_search_button'), 10);
        
        // Add shop mobile search sidebar
        add_action('wp_footer', array($this, 'shop_mobile_search_sidebar'));
    }

    /**
     * Setup WooCommerce
     */
    public function setup_woocommerce() {
        // Declare WooCommerce support
        add_theme_support('woocommerce');
        
        // Add product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }

    /**
     * Locate WooCommerce templates in theme
     *
     * @param string $template      Template file.
     * @param string $template_name Template name.
     * @param string $template_path Template path.
     * @return string Template file path.
     */
    public function woocommerce_locate_template($template, $template_name, $template_path) {
        $theme_template = AQUALUXE_DIR . '/woocommerce/' . $template_name;
        
        if (file_exists($theme_template)) {
            return $theme_template;
        }
        
        return $template;
    }

    /**
     * Modify product columns
     *
     * @return int Number of columns.
     */
    public function loop_shop_columns() {
        return intval(get_theme_mod('aqualuxe_products_per_row', 3));
    }

    /**
     * Modify products per page
     *
     * @return int Number of products.
     */
    public function products_per_page() {
        return 12;
    }

    /**
     * Modify related products args
     *
     * @param array $args Related products args.
     * @return array Modified args.
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }

    /**
     * Product image wrapper open
     */
    public function product_image_wrapper_open() {
        echo '<div class="product-image-wrapper">';
    }

    /**
     * Product image wrapper close
     */
    public function product_image_wrapper_close() {
        echo '</div>';
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        global $product;
        
        echo '<div class="aqualuxe-quick-view-button">';
        echo '<a href="#" class="button quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</a>';
        echo '</div>';
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        global $product;
        
        echo '<div class="aqualuxe-wishlist-button">';
        echo '<a href="#" class="button wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</a>';
        echo '</div>';
    }

    /**
     * Modify add to cart button
     *
     * @param string $button Button HTML.
     * @param object $product Product object.
     * @return string Modified button HTML.
     */
    public function modify_add_to_cart_button($button, $product) {
        // Add AJAX class to simple products
        if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
            $button = str_replace('add_to_cart_button', 'add_to_cart_button aqualuxe-ajax-add-to-cart', $button);
        }
        
        return $button;
    }

    /**
     * AJAX add to cart
     */
    public function ajax_add_to_cart() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-ajax-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error(array('message' => esc_html__('No product selected.', 'aqualuxe')));
            exit;
        }
        
        $product_id = absint($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
        
        // Add to cart
        $added = WC()->cart->add_to_cart($product_id, $quantity);
        
        if ($added) {
            $data = array(
                'message' => esc_html__('Product added to cart.', 'aqualuxe'),
                'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array()),
            );
            wp_send_json_success($data);
        } else {
            wp_send_json_error(array('message' => esc_html__('Failed to add product to cart.', 'aqualuxe')));
        }
        
        exit;
    }

    /**
     * Load quick view
     */
    public function load_quick_view() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-ajax-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Check product ID
        if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
            wp_send_json_error(array('message' => esc_html__('No product selected.', 'aqualuxe')));
            exit;
        }
        
        $product_id = absint($_POST['product_id']);
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_send_json_error(array('message' => esc_html__('Product not found.', 'aqualuxe')));
            exit;
        }
        
        // Start output buffering
        ob_start();
        
        // Include quick view template
        include(AQUALUXE_DIR . '/woocommerce/quick-view.php');
        
        // Get the buffered content
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
        exit;
    }

    /**
     * Quick view modal
     */
    public function quick_view_modal() {
        ?>
        <div id="aqualuxe-quick-view-modal" class="aqualuxe-modal">
            <div class="aqualuxe-modal-overlay"></div>
            <div class="aqualuxe-modal-container">
                <div class="aqualuxe-modal-content">
                    <span class="aqualuxe-modal-close">&times;</span>
                    <div class="aqualuxe-modal-body"></div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Customize checkout fields
     *
     * @param array $fields Checkout fields.
     * @return array Modified fields.
     */
    public function customize_checkout_fields($fields) {
        // Make company field optional
        if (isset($fields['billing']['billing_company'])) {
            $fields['billing']['billing_company']['required'] = false;
        }
        
        if (isset($fields['shipping']['shipping_company'])) {
            $fields['shipping']['shipping_company']['required'] = false;
        }
        
        // Add placeholder to fields
        foreach ($fields as $section => $section_fields) {
            foreach ($section_fields as $key => $field) {
                if (!isset($field['placeholder']) && isset($field['label'])) {
                    $fields[$section][$key]['placeholder'] = $field['label'];
                }
            }
        }
        
        // Add custom fields
        $fields['order']['order_comments']['placeholder'] = esc_html__('Notes about your order, e.g. special notes for delivery.', 'aqualuxe');
        
        $fields['order']['order_gift'] = array(
            'type'        => 'checkbox',
            'label'       => esc_html__('This is a gift', 'aqualuxe'),
            'class'       => array('form-row-wide'),
            'priority'    => 110,
        );
        
        $fields['order']['gift_message'] = array(
            'type'        => 'textarea',
            'label'       => esc_html__('Gift Message', 'aqualuxe'),
            'placeholder' => esc_html__('Enter your gift message here', 'aqualuxe'),
            'class'       => array('form-row-wide'),
            'priority'    => 120,
            'required'    => false,
        );
        
        return $fields;
    }

    /**
     * Save custom checkout fields
     *
     * @param int $order_id Order ID.
     */
    public function save_custom_checkout_fields($order_id) {
        if (isset($_POST['order_gift'])) {
            update_post_meta($order_id, '_order_gift', 'yes');
        }
        
        if (isset($_POST['gift_message']) && !empty($_POST['gift_message'])) {
            update_post_meta($order_id, '_gift_message', sanitize_textarea_field($_POST['gift_message']));
        }
    }

    /**
     * Display custom order fields in admin
     *
     * @param WC_Order $order Order object.
     */
    public function display_custom_order_fields_in_admin($order) {
        $order_id = $order->get_id();
        
        if (get_post_meta($order_id, '_order_gift', true) === 'yes') {
            echo '<p><strong>' . esc_html__('This is a gift', 'aqualuxe') . ':</strong> ' . esc_html__('Yes', 'aqualuxe') . '</p>';
        }
        
        $gift_message = get_post_meta($order_id, '_gift_message', true);
        if (!empty($gift_message)) {
            echo '<p><strong>' . esc_html__('Gift Message', 'aqualuxe') . ':</strong> ' . esc_html($gift_message) . '</p>';
        }
    }

    /**
     * Custom product tabs
     *
     * @param array $tabs Product tabs.
     * @return array Modified tabs.
     */
    public function custom_product_tabs($tabs) {
        // Add shipping tab
        $tabs['shipping'] = array(
            'title'    => esc_html__('Shipping & Returns', 'aqualuxe'),
            'priority' => 30,
            'callback' => array($this, 'shipping_tab_content'),
        );
        
        // Add care tab for fish products
        if (has_term('fish', 'product_cat')) {
            $tabs['care'] = array(
                'title'    => esc_html__('Care Instructions', 'aqualuxe'),
                'priority' => 40,
                'callback' => array($this, 'care_tab_content'),
            );
        }
        
        return $tabs;
    }

    /**
     * Shipping tab content
     */
    public function shipping_tab_content() {
        // Get shipping content from theme mod or use default
        $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');
        
        if (empty($shipping_content)) {
            $shipping_content = '<h3>' . esc_html__('Shipping Information', 'aqualuxe') . '</h3>';
            $shipping_content .= '<p>' . esc_html__('We ship our ornamental fish worldwide with special care to ensure they arrive healthy and safe.', 'aqualuxe') . '</p>';
            $shipping_content .= '<ul>';
            $shipping_content .= '<li>' . esc_html__('Domestic orders: 1-2 business days', 'aqualuxe') . '</li>';
            $shipping_content .= '<li>' . esc_html__('International orders: 3-5 business days', 'aqualuxe') . '</li>';
            $shipping_content .= '</ul>';
            
            $shipping_content .= '<h3>' . esc_html__('Returns Policy', 'aqualuxe') . '</h3>';
            $shipping_content .= '<p>' . esc_html__('If your fish arrives dead or severely injured, please contact us within 24 hours with photos for a replacement or refund.', 'aqualuxe') . '</p>';
        }
        
        echo wp_kses_post($shipping_content);
    }

    /**
     * Care tab content
     */
    public function care_tab_content() {
        global $product;
        
        // Get care instructions from product meta or use default
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if (empty($care_instructions)) {
            $care_instructions = '<h3>' . esc_html__('Basic Care Instructions', 'aqualuxe') . '</h3>';
            $care_instructions .= '<p>' . esc_html__('Follow these guidelines to keep your fish healthy and happy:', 'aqualuxe') . '</p>';
            $care_instructions .= '<ul>';
            $care_instructions .= '<li>' . esc_html__('Water Temperature: 72-82°F (22-28°C)', 'aqualuxe') . '</li>';
            $care_instructions .= '<li>' . esc_html__('pH Level: 6.5-7.5', 'aqualuxe') . '</li>';
            $care_instructions .= '<li>' . esc_html__('Tank Size: Minimum 10 gallons', 'aqualuxe') . '</li>';
            $care_instructions .= '<li>' . esc_html__('Diet: High-quality flake food, frozen or live foods', 'aqualuxe') . '</li>';
            $care_instructions .= '<li>' . esc_html__('Feeding: 2-3 times daily in small amounts', 'aqualuxe') . '</li>';
            $care_instructions .= '</ul>';
            
            $care_instructions .= '<h3>' . esc_html__('Water Maintenance', 'aqualuxe') . '</h3>';
            $care_instructions .= '<p>' . esc_html__('Regular water changes of 25% every 2 weeks are recommended to maintain water quality.', 'aqualuxe') . '</p>';
        }
        
        echo wp_kses_post($care_instructions);
    }

    /**
     * Size guide button
     */
    public function size_guide_button() {
        global $product;
        
        // Only show for specific categories
        if (!has_term(array('aquariums', 'tanks', 'equipment'), 'product_cat')) {
            return;
        }
        
        echo '<div class="aqualuxe-size-guide-button">';
        echo '<a href="#" class="button size-guide-button">' . esc_html__('Size Guide', 'aqualuxe') . '</a>';
        echo '</div>';
    }

    /**
     * Size guide modal
     */
    public function size_guide_modal() {
        ?>
        <div id="aqualuxe-size-guide-modal" class="aqualuxe-modal">
            <div class="aqualuxe-modal-overlay"></div>
            <div class="aqualuxe-modal-container">
                <div class="aqualuxe-modal-content">
                    <span class="aqualuxe-modal-close">&times;</span>
                    <div class="aqualuxe-modal-body">
                        <h2><?php esc_html_e('Aquarium Size Guide', 'aqualuxe'); ?></h2>
                        
                        <table class="size-guide-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Tank Size', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Dimensions (L×W×H)', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Recommended Fish', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>5 Gallons</td>
                                    <td>16" × 8" × 10"</td>
                                    <td>Bettas, Small Tetras</td>
                                </tr>
                                <tr>
                                    <td>10 Gallons</td>
                                    <td>20" × 10" × 12"</td>
                                    <td>Guppies, Tetras, Dwarf Gouramis</td>
                                </tr>
                                <tr>
                                    <td>20 Gallons</td>
                                    <td>24" × 12" × 16"</td>
                                    <td>Angelfish, Gouramis, Small Cichlids</td>
                                </tr>
                                <tr>
                                    <td>30 Gallons</td>
                                    <td>36" × 12" × 16"</td>
                                    <td>Larger Tetras, Barbs, Small Catfish</td>
                                </tr>
                                <tr>
                                    <td>55 Gallons</td>
                                    <td>48" × 13" × 21"</td>
                                    <td>Cichlids, Larger Gouramis, Discus</td>
                                </tr>
                                <tr>
                                    <td>75 Gallons</td>
                                    <td>48" × 18" × 21"</td>
                                    <td>Larger Cichlids, Rainbowfish, Catfish</td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <h3><?php esc_html_e('Fish Stocking Guidelines', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('As a general rule, stock 1 inch of fish per gallon of water for small fish, and 1 inch per 2 gallons for larger fish.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Product navigation
     */
    public function product_navigation() {
        if (!is_product()) {
            return;
        }
        
        $previous = get_previous_post(true, '', 'product_cat');
        $next = get_next_post(true, '', 'product_cat');
        
        echo '<div class="aqualuxe-product-navigation">';
        
        if ($previous) {
            echo '<div class="product-nav product-prev">';
            echo '<a href="' . esc_url(get_permalink($previous->ID)) . '" title="' . esc_attr(get_the_title($previous->ID)) . '">';
            echo '<i class="fas fa-chevron-left"></i>';
            echo '<span>' . esc_html__('Previous', 'aqualuxe') . '</span>';
            echo '</a>';
            echo '</div>';
        }
        
        if ($next) {
            echo '<div class="product-nav product-next">';
            echo '<a href="' . esc_url(get_permalink($next->ID)) . '" title="' . esc_attr(get_the_title($next->ID)) . '">';
            echo '<span>' . esc_html__('Next', 'aqualuxe') . '</span>';
            echo '<i class="fas fa-chevron-right"></i>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
    }

    /**
     * Product gallery classes
     *
     * @param array $classes Gallery classes.
     * @return array Modified classes.
     */
    public function product_gallery_classes($classes) {
        $classes[] = 'aqualuxe-product-gallery';
        
        return $classes;
    }

    /**
     * Product sharing
     */
    public function product_sharing() {
        global $product;
        
        $product_url = get_permalink();
        $product_title = get_the_title();
        $product_image = wp_get_attachment_url(get_post_thumbnail_id());
        
        echo '<div class="aqualuxe-product-sharing">';
        echo '<h4>' . esc_html__('Share This Product', 'aqualuxe') . '</h4>';
        echo '<ul class="social-icons">';
        
        // Facebook
        echo '<li><a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_url($product_url) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Share on Facebook', 'aqualuxe') . '"><i class="fab fa-facebook-f"></i></a></li>';
        
        // Twitter
        echo '<li><a href="https://twitter.com/intent/tweet?url=' . esc_url($product_url) . '&text=' . esc_attr($product_title) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Share on Twitter', 'aqualuxe') . '"><i class="fab fa-twitter"></i></a></li>';
        
        // Pinterest
        echo '<li><a href="https://pinterest.com/pin/create/button/?url=' . esc_url($product_url) . '&media=' . esc_url($product_image) . '&description=' . esc_attr($product_title) . '" target="_blank" rel="noopener noreferrer" title="' . esc_attr__('Pin on Pinterest', 'aqualuxe') . '"><i class="fab fa-pinterest-p"></i></a></li>';
        
        // Email
        echo '<li><a href="mailto:?subject=' . esc_attr($product_title) . '&body=' . esc_attr__('Check out this product: ', 'aqualuxe') . esc_url($product_url) . '" title="' . esc_attr__('Share via Email', 'aqualuxe') . '"><i class="fas fa-envelope"></i></a></li>';
        
        echo '</ul>';
        echo '</div>';
    }

    /**
     * Track product view
     */
    public function track_product_view() {
        if (!is_singular('product')) {
            return;
        }
        
        global $post;
        
        if (empty($_COOKIE['woocommerce_recently_viewed'])) {
            $viewed_products = array();
        } else {
            $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
        }
        
        // Remove current product
        $viewed_products = array_diff($viewed_products, array($post->ID));
        
        // Add current product to start of array
        array_unshift($viewed_products, $post->ID);
        
        // Limit to 15 items
        if (count($viewed_products) > 15) {
            $viewed_products = array_slice($viewed_products, 0, 15);
        }
        
        // Store in cookie
        wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
    }

    /**
     * Recently viewed products
     */
    public function recently_viewed_products() {
        if (!is_singular('product')) {
            return;
        }
        
        if (empty($_COOKIE['woocommerce_recently_viewed'])) {
            return;
        }
        
        $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
        
        // Remove current product
        global $post;
        $current_product_id = $post->ID;
        $viewed_products = array_diff($viewed_products, array($current_product_id));
        
        if (empty($viewed_products)) {
            return;
        }
        
        $title = esc_html__('Recently Viewed Products', 'aqualuxe');
        $products_per_row = 4;
        
        $args = array(
            'posts_per_page' => $products_per_row,
            'no_found_rows'  => 1,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'post__in'       => $viewed_products,
            'orderby'        => 'post__in',
        );
        
        $products = new WP_Query($args);
        
        if ($products->have_posts()) {
            echo '<section class="aqualuxe-recently-viewed-products">';
            echo '<h2>' . esc_html($title) . '</h2>';
            
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
            
            echo '</section>';
            
            wp_reset_postdata();
        }
    }

    /**
     * Cart fragments
     *
     * @param array $fragments Cart fragments.
     * @return array Modified fragments.
     */
    public function cart_fragments($fragments) {
        $fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        
        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();
        
        $fragments['div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
        
        return $fragments;
    }

    /**
     * Register product filter widgets
     */
    public function register_product_filter_widgets() {
        register_widget('AquaLuxe_Product_Filter_Widget');
        register_widget('AquaLuxe_Product_Categories_Widget');
        register_widget('AquaLuxe_Product_Brands_Widget');
        register_widget('AquaLuxe_Product_Tags_Widget');
        register_widget('AquaLuxe_Product_Price_Filter_Widget');
        register_widget('AquaLuxe_Product_Rating_Filter_Widget');
        register_widget('AquaLuxe_Product_Attribute_Filter_Widget');
    }

    /**
     * AJAX filter products
     */
    public function ajax_filter_products() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-ajax-nonce')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Get filter parameters
        $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
        $brand = isset($_POST['brand']) ? sanitize_text_field($_POST['brand']) : '';
        $tag = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';
        $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : '';
        $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : '';
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : '';
        $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'desc';
        $attributes = isset($_POST['attributes']) ? $_POST['attributes'] : array();
        
        // Build query args
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page()),
            'orderby'        => $orderby,
            'order'          => $order,
        );
        
        // Add tax query
        $tax_query = array();
        
        // Category filter
        if (!empty($category)) {
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category,
            );
        }
        
        // Brand filter
        if (!empty($brand)) {
            $tax_query[] = array(
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => $brand,
            );
        }
        
        // Tag filter
        if (!empty($tag)) {
            $tax_query[] = array(
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $tag,
            );
        }
        
        // Attribute filters
        if (!empty($attributes) && is_array($attributes)) {
            foreach ($attributes as $taxonomy => $terms) {
                if (!empty($terms)) {
                    $tax_query[] = array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'slug',
                        'terms'    => $terms,
                    );
                }
            }
        }
        
        // Add tax query to args
        if (!empty($tax_query)) {
            $args['tax_query'] = array_merge(array('relation' => 'AND'), $tax_query);
        }
        
        // Price filter
        if (!empty($min_price) || !empty($max_price)) {
            $args['meta_query'] = array(
                array(
                    'key'     => '_price',
                    'value'   => array($min_price, $max_price),
                    'compare' => 'BETWEEN',
                    'type'    => 'NUMERIC',
                ),
            );
        }
        
        // Rating filter
        if (!empty($rating)) {
            $args['meta_query'][] = array(
                'key'     => '_wc_average_rating',
                'value'   => $rating,
                'compare' => '>=',
                'type'    => 'NUMERIC',
            );
        }
        
        // Run query
        $products = new WP_Query($args);
        
        // Start output buffering
        ob_start();
        
        if ($products->have_posts()) {
            woocommerce_product_loop_start();
            
            while ($products->have_posts()) {
                $products->the_post();
                wc_get_template_part('content', 'product');
            }
            
            woocommerce_product_loop_end();
            
            // Pagination
            woocommerce_pagination();
        } else {
            echo '<p class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</p>';
        }
        
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success(array('html' => $html));
        exit;
    }

    /**
     * Custom product sorting options
     *
     * @param array $options Sorting options.
     * @return array Modified options.
     */
    public function custom_product_sorting_options($options) {
        // Add new sorting options
        $options['price-asc'] = esc_html__('Price: Low to High', 'aqualuxe');
        $options['price-desc'] = esc_html__('Price: High to Low', 'aqualuxe');
        $options['rating'] = esc_html__('Average Rating', 'aqualuxe');
        $options['popularity'] = esc_html__('Popularity', 'aqualuxe');
        $options['date'] = esc_html__('Newest Arrivals', 'aqualuxe');
        
        return $options;
    }

    /**
     * Product layout switcher
     */
    public function product_layout_switcher() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        echo '<div class="aqualuxe-layout-switcher">';
        echo '<span>' . esc_html__('View as:', 'aqualuxe') . '</span>';
        echo '<a href="#" data-layout="grid" class="active"><i class="fas fa-th"></i></a>';
        echo '<a href="#" data-layout="list"><i class="fas fa-list"></i></a>';
        echo '</div>';
    }

    /**
     * Shop sidebar toggle
     */
    public function shop_sidebar_toggle() {
        if (!is_shop() && !is_product_category() && !is_product_tag()) {
            return;
        }
        
        echo '<div class="aqualuxe-sidebar-toggle">';
        echo '<a href="#" class="toggle-sidebar-button"><i class="fas fa-sliders-h"></i> ' . esc_html__('Filters', 'aqualuxe') . '</a>';
        echo '</div>';
    }

    /**
     * Shop filters
     */
    public function shop_filters() {
        // Implement shop filters
    }

    /**
     * Shop breadcrumbs
     */
    public function shop_breadcrumbs() {
        // Implement shop breadcrumbs
    }

    /**
     * Shop title
     */
    public function shop_title() {
        // Implement shop title
    }

    /**
     * Shop description
     */
    public function shop_description() {
        // Implement shop description
    }

    /**
     * Shop banner
     */
    public function shop_banner() {
        // Implement shop banner
    }

    /**
     * Shop sidebar
     */
    public function shop_sidebar() {
        // Implement shop sidebar
    }

    /**
     * Shop pagination
     */
    public function shop_pagination() {
        // Implement shop pagination
    }

    /**
     * Shop empty message
     */
    public function shop_empty_message() {
        // Implement shop empty message
    }

    /**
     * Shop categories
     */
    public function shop_categories() {
        // Implement shop categories
    }

    /**
     * Shop brands
     */
    public function shop_brands() {
        // Implement shop brands
    }

    /**
     * Shop tags
     */
    public function shop_tags() {
        // Implement shop tags
    }

    /**
     * Shop featured products
     */
    public function shop_featured_products() {
        // Implement shop featured products
    }

    /**
     * Shop new products
     */
    public function shop_new_products() {
        // Implement shop new products
    }

    /**
     * Shop sale products
     */
    public function shop_sale_products() {
        // Implement shop sale products
    }

    /**
     * Shop best selling products
     */
    public function shop_best_selling_products() {
        // Implement shop best selling products
    }

    /**
     * Shop top rated products
     */
    public function shop_top_rated_products() {
        // Implement shop top rated products
    }

    /**
     * Shop popular products
     */
    public function shop_popular_products() {
        // Implement shop popular products
    }

    /**
     * Shop related products
     */
    public function shop_related_products() {
        // Implement shop related products
    }

    /**
     * Shop upsell products
     */
    public function shop_upsell_products() {
        // Implement shop upsell products
    }

    /**
     * Shop cross sell products
     */
    public function shop_cross_sell_products() {
        // Implement shop cross sell products
    }

    /**
     * Shop recently viewed products
     */
    public function shop_recently_viewed_products() {
        // Implement shop recently viewed products
    }

    /**
     * Shop newsletter
     */
    public function shop_newsletter() {
        // Implement shop newsletter
    }

    /**
     * Shop social sharing
     */
    public function shop_social_sharing() {
        // Implement shop social sharing
    }

    /**
     * Shop back to top button
     */
    public function shop_back_to_top() {
        // Implement shop back to top button
    }

    /**
     * Shop mobile filter button
     */
    public function shop_mobile_filter_button() {
        // Implement shop mobile filter button
    }

    /**
     * Shop mobile filter sidebar
     */
    public function shop_mobile_filter_sidebar() {
        // Implement shop mobile filter sidebar
    }

    /**
     * Shop mobile sort button
     */
    public function shop_mobile_sort_button() {
        // Implement shop mobile sort button
    }

    /**
     * Shop mobile sort sidebar
     */
    public function shop_mobile_sort_sidebar() {
        // Implement shop mobile sort sidebar
    }

    /**
     * Shop mobile categories button
     */
    public function shop_mobile_categories_button() {
        // Implement shop mobile categories button
    }

    /**
     * Shop mobile categories sidebar
     */
    public function shop_mobile_categories_sidebar() {
        // Implement shop mobile categories sidebar
    }

    /**
     * Shop mobile brands button
     */
    public function shop_mobile_brands_button() {
        // Implement shop mobile brands button
    }

    /**
     * Shop mobile brands sidebar
     */
    public function shop_mobile_brands_sidebar() {
        // Implement shop mobile brands sidebar
    }

    /**
     * Shop mobile tags button
     */
    public function shop_mobile_tags_button() {
        // Implement shop mobile tags button
    }

    /**
     * Shop mobile tags sidebar
     */
    public function shop_mobile_tags_sidebar() {
        // Implement shop mobile tags sidebar
    }

    /**
     * Shop mobile search button
     */
    public function shop_mobile_search_button() {
        // Implement shop mobile search button
    }

    /**
     * Shop mobile search sidebar
     */
    public function shop_mobile_search_sidebar() {
        // Implement shop mobile search sidebar
    }
}