<?php
/**
 * WooCommerce integration for AquaLuxe theme
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
     * Constructor
     */
    public function __construct() {
        // Include required files
        $this->includes();
        
        // Add theme support for WooCommerce
        add_action('after_setup_theme', array($this, 'woocommerce_setup'));
        
        // Unhook default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', array($this, 'before_content'), 10);
        add_action('woocommerce_after_main_content', array($this, 'after_content'), 10);
        
        // Remove default WooCommerce sidebar
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        
        // Add custom sidebar
        add_action('woocommerce_sidebar', array($this, 'get_sidebar'), 10);
        
        // Modify product loop
        add_filter('woocommerce_product_loop_start', array($this, 'product_loop_start'));
        add_filter('woocommerce_product_loop_end', array($this, 'product_loop_end'));
        
        // Modify number of products per row
        add_filter('loop_shop_columns', array($this, 'loop_columns'));
        
        // Modify number of products per page
        add_filter('loop_shop_per_page', array($this, 'products_per_page'));
        
        // Add custom product classes
        add_filter('woocommerce_post_class', array($this, 'product_classes'), 10, 2);
        
        // Modify product thumbnails
        add_filter('woocommerce_product_get_image', array($this, 'product_thumbnail'), 10, 6);
        
        // Add quick view
        add_action('woocommerce_after_shop_loop_item', array($this, 'quick_view_button'), 15);
        
        // Add wishlist
        add_action('woocommerce_after_shop_loop_item', array($this, 'wishlist_button'), 20);
        
        // Modify add to cart button
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'add_to_cart_button'), 10, 3);
        
        // Modify sale flash
        add_filter('woocommerce_sale_flash', array($this, 'sale_flash'), 10, 3);
        
        // Modify breadcrumbs
        add_filter('woocommerce_breadcrumb_defaults', array($this, 'breadcrumb_defaults'));
        
        // Modify related products
        add_filter('woocommerce_output_related_products_args', array($this, 'related_products_args'));
        
        // Modify upsells
        add_filter('woocommerce_upsell_display_args', array($this, 'upsell_products_args'));
        
        // Modify cross-sells
        add_filter('woocommerce_cross_sells_columns', array($this, 'cross_sells_columns'));
        
        // Modify checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
        
        // Add custom tabs
        add_filter('woocommerce_product_tabs', array($this, 'product_tabs'));
        
        // Enqueue WooCommerce scripts
        add_action('wp_enqueue_scripts', array($this, 'woocommerce_scripts'));
        
        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));
    }

    /**
     * Include required files
     */
    private function includes() {
        // Include template functions
        require_once get_template_directory() . '/inc/woocommerce/woocommerce-template-functions.php';
        
        // Include template hooks
        require_once get_template_directory() . '/inc/woocommerce/woocommerce-template-hooks.php';
        
        // Include quick view
        require_once get_template_directory() . '/inc/woocommerce/class-aqualuxe-wc-quick-view.php';
        
        // Include wishlist
        require_once get_template_directory() . '/inc/woocommerce/class-aqualuxe-wc-wishlist.php';
        
        // Include advanced filters
        require_once get_template_directory() . '/inc/woocommerce/class-aqualuxe-wc-filters.php';
    }

    /**
     * WooCommerce setup function.
     */
    public function woocommerce_setup() {
        // Add WooCommerce theme support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
        
        // Register WooCommerce sidebars
        register_sidebar(array(
            'name' => __('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => __('Widgets in this area will be shown on shop pages.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
            'after_title' => '</h3>',
        ));
        
        register_sidebar(array(
            'name' => __('Product Sidebar', 'aqualuxe'),
            'id' => 'product-sidebar',
            'description' => __('Widgets in this area will be shown on single product pages.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title text-lg font-bold mb-4">',
            'after_title' => '</h3>',
        ));
    }

    /**
     * Before Content.
     */
    public function before_content() {
        ?>
        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-wrap -mx-4">
                <?php if (is_active_sidebar('shop-sidebar') && !is_product() && get_theme_mod('sidebar_position', 'right') === 'left') : ?>
                    <div class="w-full lg:w-1/4 px-4 order-2 lg:order-1">
                        <?php $this->get_sidebar(); ?>
                    </div>
                    <div class="w-full lg:w-3/4 px-4 order-1 lg:order-2">
                <?php elseif (is_active_sidebar('product-sidebar') && is_product() && get_theme_mod('sidebar_position', 'right') === 'left') : ?>
                    <div class="w-full lg:w-1/4 px-4 order-2 lg:order-1">
                        <?php $this->get_sidebar(); ?>
                    </div>
                    <div class="w-full lg:w-3/4 px-4 order-1 lg:order-2">
                <?php else : ?>
                    <div class="w-full <?php echo is_active_sidebar('shop-sidebar') && !is_product() ? 'lg:w-3/4' : 'lg:w-full'; ?> <?php echo is_active_sidebar('product-sidebar') && is_product() ? 'lg:w-3/4' : 'lg:w-full'; ?> px-4">
                <?php endif; ?>
        <?php
    }

    /**
     * After Content.
     */
    public function after_content() {
        ?>
                    </div>
                    <?php if (is_active_sidebar('shop-sidebar') && !is_product() && get_theme_mod('sidebar_position', 'right') === 'right') : ?>
                        <div class="w-full lg:w-1/4 px-4 mt-8 lg:mt-0">
                            <?php $this->get_sidebar(); ?>
                        </div>
                    <?php elseif (is_active_sidebar('product-sidebar') && is_product() && get_theme_mod('sidebar_position', 'right') === 'right') : ?>
                        <div class="w-full lg:w-1/4 px-4 mt-8 lg:mt-0">
                            <?php $this->get_sidebar(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php
    }

    /**
     * Get sidebar.
     */
    public function get_sidebar() {
        if (is_product()) {
            dynamic_sidebar('product-sidebar');
        } else {
            dynamic_sidebar('shop-sidebar');
        }
    }

    /**
     * Product loop start.
     *
     * @param string $html Product loop start HTML.
     * @return string
     */
    public function product_loop_start($html) {
        $columns = $this->get_loop_columns();
        
        return '<div class="products grid grid-cols-2 md:grid-cols-3 lg:grid-cols-' . esc_attr($columns) . ' gap-6">';
    }

    /**
     * Product loop end.
     *
     * @param string $html Product loop end HTML.
     * @return string
     */
    public function product_loop_end($html) {
        return '</div>';
    }

    /**
     * Get number of columns for product loop.
     *
     * @return int
     */
    private function get_loop_columns() {
        return get_theme_mod('product_columns', 4);
    }

    /**
     * Default loop columns.
     *
     * @return int
     */
    public function loop_columns() {
        return $this->get_loop_columns();
    }

    /**
     * Products per page.
     *
     * @return int
     */
    public function products_per_page() {
        return get_theme_mod('products_per_page', 12);
    }

    /**
     * Product classes.
     *
     * @param array $classes Product classes.
     * @param WC_Product $product Product object.
     * @return array
     */
    public function product_classes($classes, $product) {
        $classes[] = 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg';
        
        return $classes;
    }

    /**
     * Product thumbnail.
     *
     * @param string $image Product image HTML.
     * @param WC_Product $product Product object.
     * @param string $size Image size.
     * @param array $attr Image attributes.
     * @param bool $placeholder Whether to use placeholder.
     * @param string $image_id Image ID.
     * @return string
     */
    public function product_thumbnail($image, $product, $size, $attr, $placeholder, $image_id) {
        // Add custom classes to product image
        $image = str_replace('class="', 'class="w-full h-64 object-cover ', $image);
        
        return $image;
    }

    /**
     * Quick view button.
     */
    public function quick_view_button() {
        // Only show quick view if enabled
        if (!get_theme_mod('quick_view', true)) {
            return;
        }
        
        global $product;
        
        echo '<a href="#" class="quick-view-button inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-secondary hover:bg-secondary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary transition-colors duration-300 mr-2" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
        echo '</svg>';
        echo esc_html__('Quick View', 'aqualuxe');
        echo '</a>';
    }

    /**
     * Wishlist button.
     */
    public function wishlist_button() {
        // Only show wishlist if enabled
        if (!get_theme_mod('wishlist', true)) {
            return;
        }
        
        global $product;
        
        echo '<a href="#" class="wishlist-button inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
        echo '</svg>';
        echo esc_html__('Add to Wishlist', 'aqualuxe');
        echo '</a>';
    }

    /**
     * Add to cart button.
     *
     * @param string $html Button HTML.
     * @param WC_Product $product Product object.
     * @param array $args Button arguments.
     * @return string
     */
    public function add_to_cart_button($html, $product, $args) {
        // Add custom classes to add to cart button
        $html = str_replace('class="button', 'class="button inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors duration-300', $html);
        
        // Add cart icon
        $html = str_replace('Add to cart', '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>Add to cart', $html);
        
        return $html;
    }

    /**
     * Sale flash.
     *
     * @param string $html Sale flash HTML.
     * @param WP_Post $post Post object.
     * @param WC_Product $product Product object.
     * @return string
     */
    public function sale_flash($html, $post, $product) {
        return '<span class="onsale absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">' . esc_html__('Sale!', 'aqualuxe') . '</span>';
    }

    /**
     * Breadcrumb defaults.
     *
     * @param array $args Breadcrumb arguments.
     * @return array
     */
    public function breadcrumb_defaults($args) {
        $args['delimiter'] = '<span class="mx-2">/</span>';
        $args['wrap_before'] = '<nav class="woocommerce-breadcrumb text-sm mb-6" itemprop="breadcrumb">';
        $args['wrap_after'] = '</nav>';
        
        return $args;
    }

    /**
     * Related products args.
     *
     * @param array $args Related products arguments.
     * @return array
     */
    public function related_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        // Hide related products if disabled
        if (!get_theme_mod('related_products', true)) {
            $args['posts_per_page'] = 0;
        }
        
        return $args;
    }

    /**
     * Upsell products args.
     *
     * @param array $args Upsell products arguments.
     * @return array
     */
    public function upsell_products_args($args) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        
        return $args;
    }

    /**
     * Cross-sells columns.
     *
     * @param int $columns Cross-sells columns.
     * @return int
     */
    public function cross_sells_columns($columns) {
        return 2;
    }

    /**
     * Checkout fields.
     *
     * @param array $fields Checkout fields.
     * @return array
     */
    public function checkout_fields($fields) {
        // Add classes to input fields
        foreach ($fields as $section => $section_fields) {
            foreach ($section_fields as $key => $field) {
                $fields[$section][$key]['input_class'][] = 'w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300';
            }
        }
        
        return $fields;
    }

    /**
     * Product tabs.
     *
     * @param array $tabs Product tabs.
     * @return array
     */
    public function product_tabs($tabs) {
        // Add custom tab
        $tabs['care_instructions'] = array(
            'title' => __('Care Instructions', 'aqualuxe'),
            'priority' => 30,
            'callback' => array($this, 'care_instructions_tab'),
        );
        
        return $tabs;
    }

    /**
     * Care instructions tab content.
     */
    public function care_instructions_tab() {
        global $product;
        
        // Get care instructions from product meta
        $care_instructions = get_post_meta($product->get_id(), '_care_instructions', true);
        
        if (empty($care_instructions)) {
            // Default care instructions
            echo '<h3>' . esc_html__('General Care Instructions', 'aqualuxe') . '</h3>';
            echo '<p>' . esc_html__('Proper care is essential for the health and longevity of your aquatic pets. Here are some general guidelines:', 'aqualuxe') . '</p>';
            echo '<ul class="list-disc pl-6 space-y-2 mt-4">';
            echo '<li>' . esc_html__('Maintain consistent water temperature appropriate for your species.', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Regularly test water parameters including pH, ammonia, nitrite, and nitrate levels.', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Perform partial water changes of 20-30% weekly or bi-weekly.', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Feed appropriate food in small amounts 1-2 times daily.', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Maintain proper filtration and aeration systems.', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Provide appropriate lighting cycles (8-12 hours daily).', 'aqualuxe') . '</li>';
            echo '<li>' . esc_html__('Monitor fish behavior for signs of stress or illness.', 'aqualuxe') . '</li>';
            echo '</ul>';
            echo '<p class="mt-4">' . esc_html__('For species-specific care instructions, please contact our customer support team.', 'aqualuxe') . '</p>';
        } else {
            echo wp_kses_post($care_instructions);
        }
    }

    /**
     * WooCommerce specific scripts & stylesheets.
     */
    public function woocommerce_scripts() {
        wp_enqueue_style('aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION);
        
        $font_path = WC()->plugin_url() . '/assets/fonts/';
        $inline_font = '@font-face {
            font-family: "star";
            src: url("' . $font_path . 'star.eot");
            src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
                url("' . $font_path . 'star.woff") format("woff"),
                url("' . $font_path . 'star.ttf") format("truetype"),
                url("' . $font_path . 'star.svg#star") format("svg");
            font-weight: normal;
            font-style: normal;
        }';
        
        wp_add_inline_style('aqualuxe-woocommerce-style', $inline_font);
        
        wp_enqueue_script('aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    }

    /**
     * Add 'woocommerce-active' class to the body tag.
     *
     * @param array $classes CSS classes applied to the body tag.
     * @return array
     */
    public function body_classes($classes) {
        $classes[] = 'woocommerce-active';
        
        return $classes;
    }
}

// Initialize the class
new AquaLuxe_WooCommerce();