<?php
/**
 * WooCommerce Module
 *
 * @package AquaLuxe
 * @subpackage Modules\WooCommerce
 */

namespace AquaLuxe\Modules\WooCommerce;

use AquaLuxe\Core\Module_Base;

/**
 * WooCommerce Module class
 */
class Module extends Module_Base {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'woocommerce';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'WooCommerce';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Adds WooCommerce support to the theme.';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $module_dependencies = [];

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Register hooks
        add_action('after_setup_theme', [$this, 'setup_woocommerce']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('customize_register', [$this, 'customize_register']);
        add_filter('body_class', [$this, 'body_classes']);
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragments']);

        // WooCommerce specific hooks
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products_args']);
        add_filter('woocommerce_product_thumbnails_columns', [$this, 'thumbnail_columns']);
        add_filter('woocommerce_breadcrumb_defaults', [$this, 'breadcrumb_defaults']);
        
        // Remove default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', [$this, 'wrapper_before']);
        add_action('woocommerce_after_main_content', [$this, 'wrapper_after']);
        
        // Shop columns and products per page
        add_filter('loop_shop_columns', [$this, 'loop_columns']);
        add_filter('loop_shop_per_page', [$this, 'products_per_page']);
        
        // Related products
        add_filter('woocommerce_output_related_products_args', [$this, 'related_products_args']);
        
        // Cart fragments
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_fragments']);
    }

    /**
     * Setup WooCommerce
     *
     * @return void
     */
    public function setup_woocommerce() {
        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_MODULES_DIR . 'woocommerce/assets/css/woocommerce.css',
            [],
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_MODULES_DIR . 'woocommerce/assets/js/woocommerce.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     * @return void
     */
    public function customize_register($wp_customize) {
        // WooCommerce Section
        $wp_customize->add_section(
            'woocommerce_section',
            [
                'title' => __('WooCommerce', 'aqualuxe'),
                'priority' => 40,
                'panel' => 'aqualuxe_theme_options',
            ]
        );

        // Shop Columns
        $wp_customize->add_setting(
            'shop_columns',
            [
                'default' => 4,
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            'shop_columns',
            [
                'label' => __('Shop Columns', 'aqualuxe'),
                'description' => __('Number of product columns on shop page.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                ],
            ]
        );

        // Products Per Page
        $wp_customize->add_setting(
            'products_per_page',
            [
                'default' => 12,
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            'products_per_page',
            [
                'label' => __('Products Per Page', 'aqualuxe'),
                'description' => __('Number of products per page on shop page.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 100,
                    'step' => 1,
                ],
            ]
        );

        // Related Products
        $wp_customize->add_setting(
            'related_products',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'related_products',
            [
                'label' => __('Show Related Products', 'aqualuxe'),
                'description' => __('Show related products on single product page.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'checkbox',
            ]
        );

        // Related Products Columns
        $wp_customize->add_setting(
            'related_products_columns',
            [
                'default' => 4,
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            'related_products_columns',
            [
                'label' => __('Related Products Columns', 'aqualuxe'),
                'description' => __('Number of related products columns.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 6,
                    'step' => 1,
                ],
            ]
        );

        // Related Products Count
        $wp_customize->add_setting(
            'related_products_count',
            [
                'default' => 4,
                'sanitize_callback' => 'absint',
            ]
        );

        $wp_customize->add_control(
            'related_products_count',
            [
                'label' => __('Related Products Count', 'aqualuxe'),
                'description' => __('Number of related products to display.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'number',
                'input_attrs' => [
                    'min' => 1,
                    'max' => 20,
                    'step' => 1,
                ],
            ]
        );

        // Mini Cart
        $wp_customize->add_setting(
            'mini_cart',
            [
                'default' => true,
                'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            ]
        );

        $wp_customize->add_control(
            'mini_cart',
            [
                'label' => __('Show Mini Cart', 'aqualuxe'),
                'description' => __('Show mini cart in header.', 'aqualuxe'),
                'section' => 'woocommerce_section',
                'type' => 'checkbox',
            ]
        );
    }

    /**
     * Add body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes($classes) {
        $classes[] = 'woocommerce-active';
        return $classes;
    }

    /**
     * Before Content
     *
     * @return void
     */
    public function wrapper_before() {
        ?>
        <main id="primary" class="<?php echo esc_attr(aqualuxe_get_main_class()); ?>">
        <?php
    }

    /**
     * After Content
     *
     * @return void
     */
    public function wrapper_after() {
        ?>
        </main><!-- #primary -->
        <?php
    }

    /**
     * Related Products Args
     *
     * @param array $args Related products args
     * @return array
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = get_theme_mod('related_products_count', 4);
        $args['columns'] = get_theme_mod('related_products_columns', 4);

        if (!aqualuxe_show_related_products()) {
            $args['posts_per_page'] = 0;
        }

        return $args;
    }

    /**
     * Product Thumbnail Columns
     *
     * @param int $columns Product thumbnail columns
     * @return int
     */
    public function thumbnail_columns($columns) {
        return 4;
    }

    /**
     * Shop Columns
     *
     * @param int $columns Shop columns
     * @return int
     */
    public function loop_columns($columns) {
        return aqualuxe_get_shop_columns();
    }

    /**
     * Products Per Page
     *
     * @param int $products Products per page
     * @return int
     */
    public function products_per_page($products) {
        return aqualuxe_get_products_per_page();
    }

    /**
     * Breadcrumb Defaults
     *
     * @param array $defaults Breadcrumb defaults
     * @return array
     */
    public function breadcrumb_defaults($defaults) {
        $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $defaults['wrap_before'] = '<div class="breadcrumbs woocommerce-breadcrumb">';
        $defaults['wrap_after'] = '</div>';
        $defaults['home'] = __('Home', 'aqualuxe');
        return $defaults;
    }

    /**
     * Cart Fragments
     *
     * @param array $fragments Cart fragments
     * @return array
     */
    public function cart_fragments($fragments) {
        ob_start();
        $this->render_mini_cart_count();
        $fragments['.mini-cart-count'] = ob_get_clean();
        return $fragments;
    }

    /**
     * Render mini cart
     *
     * @return void
     */
    public function render_mini_cart() {
        if (!get_theme_mod('mini_cart', true)) {
            return;
        }
        ?>
        <div class="mini-cart">
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mini-cart-link">
                <span class="mini-cart-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </span>
                <?php $this->render_mini_cart_count(); ?>
            </a>
            <div class="mini-cart-dropdown">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render mini cart count
     *
     * @return void
     */
    public function render_mini_cart_count() {
        $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
        ?>
        <span class="mini-cart-count"><?php echo esc_html($count); ?></span>
        <?php
    }
}

// Initialize the module
new Module();