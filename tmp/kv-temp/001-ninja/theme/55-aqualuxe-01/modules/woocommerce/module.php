<?php
/**
 * WooCommerce Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Module Class
 */
class AquaLuxe_Module_WooCommerce extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'woocommerce';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'WooCommerce';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds WooCommerce integration and customizations to the theme.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [
        [
            'type' => 'plugin',
            'name' => 'woocommerce/woocommerce.php',
            'message' => 'The WooCommerce module requires the WooCommerce plugin to be installed and activated.',
        ],
    ];

    /**
     * Initialize module
     */
    protected function init() {
        // Include module files
        $this->include_files();

        // Add theme support for WooCommerce
        add_action('after_setup_theme', [$this, 'add_theme_support']);

        // Enqueue WooCommerce styles and scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles_scripts']);

        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');

        // Customize WooCommerce templates
        add_filter('woocommerce_locate_template', [$this, 'locate_template'], 10, 3);

        // Customize WooCommerce wrapper
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        add_action('woocommerce_before_main_content', [$this, 'wrapper_start'], 10);
        add_action('woocommerce_after_main_content', [$this, 'wrapper_end'], 10);

        // Customize product loop
        add_action('woocommerce_before_shop_loop_item', [$this, 'product_card_start'], 5);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_card_end'], 15);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'product_image_wrapper_start'], 5);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'product_image_wrapper_end'], 15);
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'product_actions_start'], 20);
        add_action('woocommerce_after_shop_loop_item', [$this, 'product_actions_end'], 5);

        // Customize product gallery
        add_filter('woocommerce_single_product_image_gallery_classes', [$this, 'product_gallery_classes']);

        // Customize checkout
        add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);

        // Customize cart
        add_action('woocommerce_before_cart', [$this, 'cart_wrapper_start']);
        add_action('woocommerce_after_cart', [$this, 'cart_wrapper_end']);

        // Add AJAX cart update
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragments']);

        // Add mini cart to header
        add_action('aqualuxe_header_actions', [$this, 'mini_cart'], 60);

        // Add product quick view
        add_action('woocommerce_after_shop_loop_item', [$this, 'quick_view_button'], 7);
        add_action('wp_ajax_aqualuxe_quick_view', [$this, 'quick_view_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [$this, 'quick_view_ajax']);

        // Add wishlist functionality
        add_action('woocommerce_after_shop_loop_item', [$this, 'wishlist_button'], 8);
        add_action('woocommerce_single_product_summary', [$this, 'wishlist_button'], 35);
        add_action('wp_ajax_aqualuxe_wishlist_toggle', [$this, 'wishlist_toggle_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist_toggle', [$this, 'wishlist_toggle_ajax']);

        // Add compare functionality
        add_action('woocommerce_after_shop_loop_item', [$this, 'compare_button'], 9);
        add_action('woocommerce_single_product_summary', [$this, 'compare_button'], 36);
        add_action('wp_ajax_aqualuxe_compare_toggle', [$this, 'compare_toggle_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_compare_toggle', [$this, 'compare_toggle_ajax']);

        // Add size guide
        add_action('woocommerce_before_add_to_cart_form', [$this, 'size_guide_button'], 5);
        add_action('wp_footer', [$this, 'size_guide_modal']);

        // Add product tabs
        add_filter('woocommerce_product_tabs', [$this, 'customize_product_tabs']);

        // Add recently viewed products
        add_action('template_redirect', [$this, 'track_product_view']);
        add_action('woocommerce_after_single_product', [$this, 'recently_viewed_products']);

        // Add related products customization
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products_args']);

        // Add upsells customization
        add_filter('woocommerce_upsell_display_args', [$this, 'upsell_products_args']);

        // Add cross-sells customization
        add_filter('woocommerce_cross_sells_total', [$this, 'cross_sells_total']);
        add_filter('woocommerce_cross_sells_columns', [$this, 'cross_sells_columns']);

        // Add product badges
        add_action('woocommerce_before_shop_loop_item_title', [$this, 'product_badges'], 10);
        add_action('woocommerce_before_single_product_summary', [$this, 'product_badges'], 10);
    }

    /**
     * Include module files
     */
    private function include_files() {
        // Include template functions
        require_once $this->dir . '/includes/template-functions.php';

        // Include template hooks
        require_once $this->dir . '/includes/template-hooks.php';

        // Include widgets
        require_once $this->dir . '/includes/widgets.php';
    }

    /**
     * Register module settings in customizer
     *
     * @param WP_Customize_Manager $wp_customize
     */
    public function customize_register($wp_customize) {
        // Call parent method
        parent::customize_register($wp_customize);

        // Add shop layout setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[shop_layout]', [
            'default' => 'grid',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_shop_layout'],
        ]);

        // Add shop layout control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_shop_layout', [
            'label' => __('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[shop_layout]',
            'type' => 'select',
            'choices' => [
                'grid' => __('Grid', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'masonry' => __('Masonry', 'aqualuxe'),
            ],
            'priority' => 20,
        ]);

        // Add products per row setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[products_per_row]', [
            'default' => 3,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ]);

        // Add products per row control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_products_per_row', [
            'label' => __('Products Per Row', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[products_per_row]',
            'type' => 'number',
            'input_attrs' => [
                'min' => 2,
                'max' => 6,
                'step' => 1,
            ],
            'priority' => 30,
        ]);

        // Add product card style setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[product_card_style]', [
            'default' => 'standard',
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => [$this, 'sanitize_product_card_style'],
        ]);

        // Add product card style control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_product_card_style', [
            'label' => __('Product Card Style', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[product_card_style]',
            'type' => 'select',
            'choices' => [
                'standard' => __('Standard', 'aqualuxe'),
                'minimal' => __('Minimal', 'aqualuxe'),
                'elegant' => __('Elegant', 'aqualuxe'),
                'modern' => __('Modern', 'aqualuxe'),
            ],
            'priority' => 40,
        ]);

        // Add quick view setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[quick_view]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add quick view control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_quick_view', [
            'label' => __('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[quick_view]',
            'type' => 'checkbox',
            'priority' => 50,
        ]);

        // Add wishlist setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[wishlist]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add wishlist control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_wishlist', [
            'label' => __('Enable Wishlist', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[wishlist]',
            'type' => 'checkbox',
            'priority' => 60,
        ]);

        // Add compare setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[compare]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add compare control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_compare', [
            'label' => __('Enable Compare', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[compare]',
            'type' => 'checkbox',
            'priority' => 70,
        ]);

        // Add size guide setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[size_guide]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add size guide control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_size_guide', [
            'label' => __('Enable Size Guide', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[size_guide]',
            'type' => 'checkbox',
            'priority' => 80,
        ]);

        // Add recently viewed products setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[recently_viewed]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add recently viewed products control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_recently_viewed', [
            'label' => __('Enable Recently Viewed Products', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[recently_viewed]',
            'type' => 'checkbox',
            'priority' => 90,
        ]);

        // Add product badges setting
        $wp_customize->add_setting('aqualuxe_module_' . $this->id . '[product_badges]', [
            'default' => true,
            'type' => 'option',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);

        // Add product badges control
        $wp_customize->add_control('aqualuxe_module_' . $this->id . '_product_badges', [
            'label' => __('Enable Product Badges', 'aqualuxe'),
            'section' => 'aqualuxe_module_' . $this->id,
            'settings' => 'aqualuxe_module_' . $this->id . '[product_badges]',
            'type' => 'checkbox',
            'priority' => 100,
        ]);
    }

    /**
     * Register module assets
     */
    public function register_assets() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Register WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            $this->url . '/assets/css/woocommerce.css',
            [],
            $this->version
        );

        // Register WooCommerce script
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            $this->url . '/assets/js/woocommerce.js',
            ['jquery', 'alpine', 'wc-add-to-cart', 'wc-cart-fragments'],
            $this->version,
            true
        );

        // Add script data
        wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWooCommerce', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-woocommerce-nonce'),
            'quickView' => $this->get_option('quick_view', true),
            'wishlist' => $this->get_option('wishlist', true),
            'compare' => $this->get_option('compare', true),
            'i18n' => [
                'addToCart' => esc_html__('Add to Cart', 'aqualuxe'),
                'addingToCart' => esc_html__('Adding...', 'aqualuxe'),
                'addedToCart' => esc_html__('Added to Cart', 'aqualuxe'),
                'viewCart' => esc_html__('View Cart', 'aqualuxe'),
                'quickView' => esc_html__('Quick View', 'aqualuxe'),
                'addToWishlist' => esc_html__('Add to Wishlist', 'aqualuxe'),
                'removeFromWishlist' => esc_html__('Remove from Wishlist', 'aqualuxe'),
                'addToCompare' => esc_html__('Add to Compare', 'aqualuxe'),
                'removeFromCompare' => esc_html__('Remove from Compare', 'aqualuxe'),
                'sizeGuide' => esc_html__('Size Guide', 'aqualuxe'),
                'outOfStock' => esc_html__('Out of Stock', 'aqualuxe'),
                'sale' => esc_html__('Sale', 'aqualuxe'),
                'new' => esc_html__('New', 'aqualuxe'),
                'featured' => esc_html__('Featured', 'aqualuxe'),
            ],
        ]);
    }

    /**
     * Add theme support for WooCommerce
     */
    public function add_theme_support() {
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');

        // Add support for WooCommerce features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Enqueue WooCommerce styles and scripts
     */
    public function enqueue_styles_scripts() {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return;
        }

        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            $this->url . '/assets/css/woocommerce.css',
            [],
            $this->version
        );

        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            $this->url . '/assets/js/woocommerce.js',
            ['jquery', 'alpine', 'wc-add-to-cart', 'wc-cart-fragments'],
            $this->version,
            true
        );
    }

    /**
     * Locate WooCommerce template
     *
     * @param string $template
     * @param string $template_name
     * @param string $template_path
     * @return string
     */
    public function locate_template($template, $template_name, $template_path) {
        // Check if module is enabled
        if (!$this->is_enabled()) {
            return $template;
        }

        // Set template path
        $template_path = $template_path ? $template_path : WC()->template_path();

        // Look for template in theme
        $theme_template = locate_template([
            trailingslashit($template_path) . $template_name,
            'modules/woocommerce/templates/' . $template_name,
        ]);

        // Use theme template if found
        if ($theme_template) {
            return $theme_template;
        }

        // Look for template in module
        $module_template = $this->dir . '/templates/' . $template_name;
        if (file_exists($module_template)) {
            return $module_template;
        }

        // Return default template
        return $template;
    }

    /**
     * WooCommerce wrapper start
     */
    public function wrapper_start() {
        // Get template
        $this->get_template_part('wrapper-start');
    }

    /**
     * WooCommerce wrapper end
     */
    public function wrapper_end() {
        // Get template
        $this->get_template_part('wrapper-end');
    }

    /**
     * Product card start
     */
    public function product_card_start() {
        // Get product card style
        $style = $this->get_option('product_card_style', 'standard');

        // Get template
        $this->get_template_part('product-card-start', $style);
    }

    /**
     * Product card end
     */
    public function product_card_end() {
        // Get product card style
        $style = $this->get_option('product_card_style', 'standard');

        // Get template
        $this->get_template_part('product-card-end', $style);
    }

    /**
     * Product image wrapper start
     */
    public function product_image_wrapper_start() {
        // Get template
        $this->get_template_part('product-image-wrapper-start');
    }

    /**
     * Product image wrapper end
     */
    public function product_image_wrapper_end() {
        // Get template
        $this->get_template_part('product-image-wrapper-end');
    }

    /**
     * Product actions start
     */
    public function product_actions_start() {
        // Get template
        $this->get_template_part('product-actions-start');
    }

    /**
     * Product actions end
     */
    public function product_actions_end() {
        // Get template
        $this->get_template_part('product-actions-end');
    }

    /**
     * Product gallery classes
     *
     * @param array $classes
     * @return array
     */
    public function product_gallery_classes($classes) {
        $classes[] = 'aqualuxe-product-gallery';
        return $classes;
    }

    /**
     * Customize checkout fields
     *
     * @param array $fields
     * @return array
     */
    public function customize_checkout_fields($fields) {
        // Add placeholder to fields
        foreach ($fields as $section => $section_fields) {
            foreach ($section_fields as $key => $field) {
                if (!isset($field['placeholder']) && isset($field['label'])) {
                    $fields[$section][$key]['placeholder'] = $field['label'];
                }
            }
        }

        return $fields;
    }

    /**
     * Cart wrapper start
     */
    public function cart_wrapper_start() {
        // Get template
        $this->get_template_part('cart-wrapper-start');
    }

    /**
     * Cart wrapper end
     */
    public function cart_wrapper_end() {
        // Get template
        $this->get_template_part('cart-wrapper-end');
    }

    /**
     * Cart fragments
     *
     * @param array $fragments
     * @return array
     */
    public function cart_fragments($fragments) {
        // Get cart count
        $cart_count = WC()->cart->get_cart_contents_count();

        // Add cart count fragment
        $fragments['.cart-count'] = '<span class="cart-count">' . $cart_count . '</span>';

        return $fragments;
    }

    /**
     * Mini cart
     */
    public function mini_cart() {
        // Get template
        $this->get_template_part('mini-cart');
    }

    /**
     * Quick view button
     */
    public function quick_view_button() {
        // Check if quick view is enabled
        if (!$this->get_option('quick_view', true)) {
            return;
        }

        // Get template
        $this->get_template_part('quick-view-button');
    }

    /**
     * Quick view AJAX
     */
    public function quick_view_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Check product ID
        if (!isset($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
        }

        // Get product ID
        $product_id = absint($_POST['product_id']);

        // Get product
        $product = wc_get_product($product_id);

        // Check if product exists
        if (!$product) {
            wp_send_json_error('Product not found');
        }

        // Get template
        ob_start();
        $this->get_template('quick-view.php', ['product' => $product]);
        $html = ob_get_clean();

        // Send response
        wp_send_json_success(['html' => $html]);
    }

    /**
     * Wishlist button
     */
    public function wishlist_button() {
        // Check if wishlist is enabled
        if (!$this->get_option('wishlist', true)) {
            return;
        }

        // Get template
        $this->get_template_part('wishlist-button');
    }

    /**
     * Wishlist toggle AJAX
     */
    public function wishlist_toggle_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Check product ID
        if (!isset($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
        }

        // Get product ID
        $product_id = absint($_POST['product_id']);

        // Get wishlist
        $wishlist = $this->get_wishlist();

        // Check if product is in wishlist
        $in_wishlist = in_array($product_id, $wishlist);

        // Toggle product in wishlist
        if ($in_wishlist) {
            $wishlist = array_diff($wishlist, [$product_id]);
        } else {
            $wishlist[] = $product_id;
        }

        // Save wishlist
        $this->save_wishlist($wishlist);

        // Send response
        wp_send_json_success([
            'in_wishlist' => !$in_wishlist,
            'count' => count($wishlist),
        ]);
    }

    /**
     * Compare button
     */
    public function compare_button() {
        // Check if compare is enabled
        if (!$this->get_option('compare', true)) {
            return;
        }

        // Get template
        $this->get_template_part('compare-button');
    }

    /**
     * Compare toggle AJAX
     */
    public function compare_toggle_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce-nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Check product ID
        if (!isset($_POST['product_id'])) {
            wp_send_json_error('Invalid product ID');
        }

        // Get product ID
        $product_id = absint($_POST['product_id']);

        // Get compare
        $compare = $this->get_compare();

        // Check if product is in compare
        $in_compare = in_array($product_id, $compare);

        // Toggle product in compare
        if ($in_compare) {
            $compare = array_diff($compare, [$product_id]);
        } else {
            $compare[] = $product_id;
        }

        // Save compare
        $this->save_compare($compare);

        // Send response
        wp_send_json_success([
            'in_compare' => !$in_compare,
            'count' => count($compare),
        ]);
    }

    /**
     * Size guide button
     */
    public function size_guide_button() {
        // Check if size guide is enabled
        if (!$this->get_option('size_guide', true)) {
            return;
        }

        // Get product
        global $product;

        // Check if product is variable
        if (!$product || !$product->is_type('variable')) {
            return;
        }

        // Get template
        $this->get_template_part('size-guide-button');
    }

    /**
     * Size guide modal
     */
    public function size_guide_modal() {
        // Check if size guide is enabled
        if (!$this->get_option('size_guide', true)) {
            return;
        }

        // Check if we're on a product page
        if (!is_product()) {
            return;
        }

        // Get product
        global $product;

        // Check if product is variable
        if (!$product || !$product->is_type('variable')) {
            return;
        }

        // Get template
        $this->get_template_part('size-guide-modal');
    }

    /**
     * Customize product tabs
     *
     * @param array $tabs
     * @return array
     */
    public function customize_product_tabs($tabs) {
        // Reorder tabs
        if (isset($tabs['description'])) {
            $tabs['description']['priority'] = 10;
        }

        if (isset($tabs['additional_information'])) {
            $tabs['additional_information']['priority'] = 20;
        }

        if (isset($tabs['reviews'])) {
            $tabs['reviews']['priority'] = 30;
        }

        return $tabs;
    }

    /**
     * Track product view
     */
    public function track_product_view() {
        // Check if recently viewed products is enabled
        if (!$this->get_option('recently_viewed', true)) {
            return;
        }

        // Check if we're on a product page
        if (!is_product()) {
            return;
        }

        // Get product ID
        $product_id = get_the_ID();

        // Get recently viewed products
        $viewed_products = $this->get_recently_viewed();

        // Remove current product from list
        $viewed_products = array_diff($viewed_products, [$product_id]);

        // Add current product to start of list
        array_unshift($viewed_products, $product_id);

        // Limit to 10 products
        $viewed_products = array_slice($viewed_products, 0, 10);

        // Save recently viewed products
        $this->save_recently_viewed($viewed_products);
    }

    /**
     * Recently viewed products
     */
    public function recently_viewed_products() {
        // Check if recently viewed products is enabled
        if (!$this->get_option('recently_viewed', true)) {
            return;
        }

        // Get recently viewed products
        $viewed_products = $this->get_recently_viewed();

        // Remove current product from list
        if (is_product()) {
            $product_id = get_the_ID();
            $viewed_products = array_diff($viewed_products, [$product_id]);
        }

        // Check if we have any products
        if (empty($viewed_products)) {
            return;
        }

        // Get template
        $this->get_template('recently-viewed.php', ['viewed_products' => $viewed_products]);
    }

    /**
     * Related products args
     *
     * @param array $args
     * @return array
     */
    public function related_products_args($args) {
        // Get products per row
        $products_per_row = $this->get_option('products_per_row', 3);

        // Set columns
        $args['columns'] = $products_per_row;

        // Set posts per page
        $args['posts_per_page'] = $products_per_row * 2;

        return $args;
    }

    /**
     * Upsell products args
     *
     * @param array $args
     * @return array
     */
    public function upsell_products_args($args) {
        // Get products per row
        $products_per_row = $this->get_option('products_per_row', 3);

        // Set columns
        $args['columns'] = $products_per_row;

        // Set posts per page
        $args['posts_per_page'] = $products_per_row * 2;

        return $args;
    }

    /**
     * Cross-sells total
     *
     * @param int $limit
     * @return int
     */
    public function cross_sells_total($limit) {
        // Get products per row
        $products_per_row = $this->get_option('products_per_row', 3);

        return $products_per_row * 2;
    }

    /**
     * Cross-sells columns
     *
     * @param int $columns
     * @return int
     */
    public function cross_sells_columns($columns) {
        // Get products per row
        $products_per_row = $this->get_option('products_per_row', 3);

        return $products_per_row;
    }

    /**
     * Product badges
     */
    public function product_badges() {
        // Check if product badges is enabled
        if (!$this->get_option('product_badges', true)) {
            return;
        }

        // Get template
        $this->get_template_part('product-badges');
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    public function get_wishlist() {
        // Get wishlist from cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? $_COOKIE['aqualuxe_wishlist'] : '';

        // Decode wishlist
        $wishlist = $wishlist ? json_decode(stripslashes($wishlist), true) : [];

        // Ensure wishlist is an array
        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        return $wishlist;
    }

    /**
     * Save wishlist
     *
     * @param array $wishlist
     */
    public function save_wishlist($wishlist) {
        // Ensure wishlist is an array
        if (!is_array($wishlist)) {
            $wishlist = [];
        }

        // Remove duplicates
        $wishlist = array_unique($wishlist);

        // Encode wishlist
        $wishlist_json = json_encode($wishlist);

        // Set cookie
        setcookie('aqualuxe_wishlist', $wishlist_json, time() + (86400 * 30), '/');
    }

    /**
     * Get compare
     *
     * @return array
     */
    public function get_compare() {
        // Get compare from cookie
        $compare = isset($_COOKIE['aqualuxe_compare']) ? $_COOKIE['aqualuxe_compare'] : '';

        // Decode compare
        $compare = $compare ? json_decode(stripslashes($compare), true) : [];

        // Ensure compare is an array
        if (!is_array($compare)) {
            $compare = [];
        }

        return $compare;
    }

    /**
     * Save compare
     *
     * @param array $compare
     */
    public function save_compare($compare) {
        // Ensure compare is an array
        if (!is_array($compare)) {
            $compare = [];
        }

        // Remove duplicates
        $compare = array_unique($compare);

        // Encode compare
        $compare_json = json_encode($compare);

        // Set cookie
        setcookie('aqualuxe_compare', $compare_json, time() + (86400 * 30), '/');
    }

    /**
     * Get recently viewed products
     *
     * @return array
     */
    public function get_recently_viewed() {
        // Get recently viewed from cookie
        $viewed_products = isset($_COOKIE['aqualuxe_recently_viewed']) ? $_COOKIE['aqualuxe_recently_viewed'] : '';

        // Decode recently viewed
        $viewed_products = $viewed_products ? json_decode(stripslashes($viewed_products), true) : [];

        // Ensure recently viewed is an array
        if (!is_array($viewed_products)) {
            $viewed_products = [];
        }

        return $viewed_products;
    }

    /**
     * Save recently viewed products
     *
     * @param array $viewed_products
     */
    public function save_recently_viewed($viewed_products) {
        // Ensure recently viewed is an array
        if (!is_array($viewed_products)) {
            $viewed_products = [];
        }

        // Remove duplicates
        $viewed_products = array_unique($viewed_products);

        // Encode recently viewed
        $viewed_products_json = json_encode($viewed_products);

        // Set cookie
        setcookie('aqualuxe_recently_viewed', $viewed_products_json, time() + (86400 * 30), '/');
    }

    /**
     * Sanitize shop layout
     *
     * @param string $value
     * @return string
     */
    public function sanitize_shop_layout($value) {
        $allowed_values = ['grid', 'list', 'masonry'];
        return in_array($value, $allowed_values) ? $value : 'grid';
    }

    /**
     * Sanitize product card style
     *
     * @param string $value
     * @return string
     */
    public function sanitize_product_card_style($value) {
        $allowed_values = ['standard', 'minimal', 'elegant', 'modern'];
        return in_array($value, $allowed_values) ? $value : 'standard';
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function get_default_options() {
        return array_merge(parent::get_default_options(), [
            'shop_layout' => 'grid',
            'products_per_row' => 3,
            'product_card_style' => 'standard',
            'quick_view' => true,
            'wishlist' => true,
            'compare' => true,
            'size_guide' => true,
            'recently_viewed' => true,
            'product_badges' => true,
        ]);
    }
}

// Initialize module
new AquaLuxe_Module_WooCommerce();