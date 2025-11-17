<?php
/**
 * WooCommerce Integration
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WooCommerce Theme Integration Class
 */
class AquaLuxe_WooCommerce_Integration
{
    /**
     * Initialize WooCommerce integration
     */
    public static function init()
    {
        // Only proceed if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Theme setup
        add_action('after_setup_theme', [__CLASS__, 'setup']);
        
        // Customize WooCommerce
        add_action('init', [__CLASS__, 'customize_woocommerce']);
        
        // Enqueue WooCommerce assets
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
        
        // Product customizations
        add_action('woocommerce_before_shop_loop_item_title', [__CLASS__, 'product_badges'], 5);
        add_action('woocommerce_shop_loop_item_title', [__CLASS__, 'product_rating'], 5);
        
        // Cart customizations
        add_filter('woocommerce_add_to_cart_fragments', [__CLASS__, 'cart_fragments']);
        
        // Checkout customizations
        add_filter('woocommerce_checkout_fields', [__CLASS__, 'customize_checkout_fields']);
        
        // My Account customizations
        add_filter('woocommerce_account_menu_items', [__CLASS__, 'customize_account_menu']);
        
        // Product gallery
        add_action('woocommerce_before_single_product_summary', [__CLASS__, 'product_gallery_wrapper_start'], 19);
        add_action('woocommerce_before_single_product_summary', [__CLASS__, 'product_gallery_wrapper_end'], 21);
        
        // Quick view
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'quick_view_button'], 15);
        add_action('wp_footer', [__CLASS__, 'quick_view_modal']);
        
        // Wishlist (if enabled)
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'wishlist_button'], 25);
        
        // Currency switcher
        add_action('wp_head', [__CLASS__, 'currency_switcher_styles']);
        add_action('aqualuxe_header_actions', [__CLASS__, 'currency_switcher']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_quick_view', [__CLASS__, 'ajax_quick_view']);
        add_action('wp_ajax_nopriv_aqualuxe_quick_view', [__CLASS__, 'ajax_quick_view']);
    }

    /**
     * WooCommerce theme setup
     */
    public static function setup()
    {
        // Declare WooCommerce support
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'gallery_thumbnail_image_width' => 100,
            'single_image_width' => 600,
        ]);

        // Product gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Customize WooCommerce behavior
     */
    public static function customize_woocommerce()
    {
        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Customize products per page
        $products_per_page = get_theme_mod('aqualuxe_products_per_page', 12);
        add_filter('loop_shop_per_page', function() use ($products_per_page) {
            return $products_per_page;
        });
        
        // Customize product columns
        add_filter('loop_shop_columns', function() {
            return get_theme_mod('aqualuxe_shop_columns', 4);
        });
        
        // Remove default WooCommerce actions
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
        
        // Add custom actions
        add_action('woocommerce_before_shop_loop_item', [__CLASS__, 'product_wrapper_start'], 5);
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'product_wrapper_end'], 35);
        add_action('woocommerce_shop_loop_item_title', [__CLASS__, 'product_title'], 10);
    }

    /**
     * Enqueue WooCommerce assets
     */
    public static function enqueue_assets()
    {
        if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
            $manifest = self::get_asset_manifest();
            
            if (isset($manifest['css/woocommerce.css'])) {
                wp_enqueue_style(
                    'aqualuxe-woocommerce',
                    AQUALUXE_ASSETS_URL . '/' . $manifest['css/woocommerce.css'],
                    ['aqualuxe-main'],
                    AQUALUXE_VERSION
                );
            }
        }
    }

    /**
     * Get asset manifest
     */
    private static function get_asset_manifest()
    {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
                $manifest = array_map(function($path) {
                    return ltrim($path, '/');
                }, $manifest);
            } else {
                $manifest = [];
            }
        }
        
        return $manifest;
    }

    /**
     * Product wrapper start
     */
    public static function product_wrapper_start()
    {
        echo '<div class="product-item relative bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">';
        echo '<div class="product-image relative overflow-hidden group">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="block">';
    }

    /**
     * Product wrapper end
     */
    public static function product_wrapper_end()
    {
        echo '</div>'; // .product-item
    }

    /**
     * Custom product title
     */
    public static function product_title()
    {
        echo '</a>'; // Close image link
        echo '</div>'; // Close product-image
        echo '<div class="product-content p-4">';
        echo '<h3 class="product-title text-lg font-semibold mb-2">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="text-gray-900 hover:text-primary-600 transition-colors">';
        echo esc_html(get_the_title());
        echo '</a>';
        echo '</h3>';
    }

    /**
     * Product badges
     */
    public static function product_badges()
    {
        global $product;
        
        echo '<div class="product-badges absolute top-2 left-2 z-10 space-y-1">';
        
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = $percentage ? "-{$percentage}%" : '';
            }
            echo '<span class="badge bg-red-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo $percentage ?: esc_html__('Sale', 'aqualuxe');
            echo '</span>';
        }
        
        if ($product->is_featured()) {
            echo '<span class="badge bg-yellow-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo esc_html__('Featured', 'aqualuxe');
            echo '</span>';
        }
        
        if (!$product->is_in_stock()) {
            echo '<span class="badge bg-gray-500 text-white px-2 py-1 text-xs font-semibold rounded">';
            echo esc_html__('Out of Stock', 'aqualuxe');
            echo '</span>';
        }
        
        echo '</div>';
    }

    /**
     * Product rating
     */
    public static function product_rating()
    {
        global $product;
        
        if (get_option('woocommerce_enable_review_rating') === 'no') {
            return;
        }
        
        $rating_count = $product->get_rating_count();
        $review_count = $product->get_review_count();
        $average = $product->get_average_rating();
        
        if ($rating_count > 0) {
            echo '<div class="product-rating flex items-center mb-2">';
            echo wc_get_rating_html($average, $rating_count);
            echo '<span class="rating-count text-sm text-gray-600 ml-2">(' . esc_html($review_count) . ')</span>';
            echo '</div>';
        }
    }

    /**
     * Cart fragments
     */
    public static function cart_fragments($fragments)
    {
        ob_start();
        ?>
        <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
            <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
        </span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        
        return $fragments;
    }

    /**
     * Customize checkout fields
     */
    public static function customize_checkout_fields($fields)
    {
        // Add custom CSS classes to fields
        foreach ($fields as $fieldset_key => $fieldset) {
            foreach ($fieldset as $key => $field) {
                $fields[$fieldset_key][$key]['input_class'] = ['form-input w-full'];
                $fields[$fieldset_key][$key]['label_class'] = ['form-label'];
            }
        }
        
        return $fields;
    }

    /**
     * Customize account menu
     */
    public static function customize_account_menu($items)
    {
        // Add custom menu items if needed
        $custom_items = [];
        
        if (class_exists('YITH_WCWL')) {
            $custom_items['wishlist'] = esc_html__('Wishlist', 'aqualuxe');
        }
        
        // Insert custom items before logout
        $logout = $items['customer-logout'];
        unset($items['customer-logout']);
        $items = array_merge($items, $custom_items, ['customer-logout' => $logout]);
        
        return $items;
    }

    /**
     * Product gallery wrapper start
     */
    public static function product_gallery_wrapper_start()
    {
        echo '<div class="product-gallery-wrapper relative">';
    }

    /**
     * Product gallery wrapper end
     */
    public static function product_gallery_wrapper_end()
    {
        echo '</div>';
    }

    /**
     * Quick view button
     */
    public static function quick_view_button()
    {
        global $product;
        
        echo '<div class="product-actions absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">';
        echo '<button type="button" class="quick-view-btn btn btn-primary" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo esc_html__('Quick View', 'aqualuxe');
        echo '</button>';
        echo '</div>';
    }

    /**
     * Quick view modal
     */
    public static function quick_view_modal()
    {
        ?>
        <div id="quick-view-modal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
            <div class="modal-content bg-white rounded-lg max-w-4xl w-full m-4 max-h-screen overflow-auto">
                <div class="modal-header flex justify-between items-center p-6 border-b">
                    <h3 class="text-xl font-semibold"><?php esc_html_e('Quick View', 'aqualuxe'); ?></h3>
                    <button type="button" class="modal-close text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body p-6">
                    <!-- Quick view content will be loaded here -->
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Wishlist button
     */
    public static function wishlist_button()
    {
        if (!class_exists('YITH_WCWL')) {
            return;
        }
        
        global $product;
        
        echo '<button type="button" class="wishlist-btn absolute top-2 right-2 z-10 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors" data-product-id="' . esc_attr($product->get_id()) . '">';
        echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">';
        echo '<path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>';
        echo '</svg>';
        echo '</button>';
    }

    /**
     * Currency switcher styles
     */
    public static function currency_switcher_styles()
    {
        if (!class_exists('WOOCS')) {
            return;
        }
        ?>
        <style>
        .currency-switcher .woocs_selector {
            background: none;
            border: none;
            color: inherit;
            font-size: inherit;
        }
        </style>
        <?php
    }

    /**
     * Currency switcher
     */
    public static function currency_switcher()
    {
        if (!class_exists('WOOCS')) {
            return;
        }
        
        echo '<div class="currency-switcher">';
        echo do_shortcode('[woocs]');
        echo '</div>';
    }

    /**
     * AJAX Quick view
     */
    public static function ajax_quick_view()
    {
        check_ajax_referer('aqualuxe_nonce', 'nonce');
        
        $product_id = intval($_POST['product_id']);
        
        if (!$product_id) {
            wp_die();
        }
        
        global $product, $woocommerce;
        
        $product = wc_get_product($product_id);
        
        if (!$product) {
            wp_die();
        }
        
        ob_start();
        ?>
        <div class="quick-view-content grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="product-images">
                <?php
                if (has_post_thumbnail($product_id)) {
                    echo get_the_post_thumbnail($product_id, 'woocommerce_single', ['class' => 'w-full rounded-lg']);
                } else {
                    echo wc_placeholder_img('woocommerce_single', 'w-full rounded-lg');
                }
                ?>
            </div>
            <div class="product-details">
                <h2 class="text-2xl font-semibold mb-4"><?php echo esc_html($product->get_name()); ?></h2>
                
                <div class="price mb-4 text-xl font-bold text-primary-600">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <?php if ($product->get_short_description()) : ?>
                    <div class="short-description mb-4 text-gray-600">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->is_type('simple')) : ?>
                    <form class="cart mb-4" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                        <?php
                        do_action('woocommerce_before_add_to_cart_button');
                        
                        if (!$product->is_sold_individually()) {
                            woocommerce_quantity_input([
                                'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                            ]);
                        }
                        ?>
                        
                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary w-full single_add_to_cart_button button alt">
                            <?php echo esc_html($product->single_add_to_cart_text()); ?>
                        </button>
                        
                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                    </form>
                <?php endif; ?>
                
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-details inline-block text-primary-600 hover:text-primary-700 font-medium">
                    <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
        <?php
        
        $content = ob_get_clean();
        
        wp_send_json_success($content);
    }

    /**
     * Check if WooCommerce is active but gracefully degrade if not
     */
    public static function is_woocommerce_active()
    {
        return class_exists('WooCommerce');
    }

    /**
     * Fallback shop page content when WooCommerce is not active
     */
    public static function fallback_shop_content()
    {
        if (self::is_woocommerce_active()) {
            return;
        }
        
        ?>
        <div class="fallback-shop-content bg-gradient-to-br from-primary-50 to-aqua-50 py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">
                    <?php esc_html_e('Premium Aquatic Products', 'aqualuxe'); ?>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                    <?php esc_html_e('Discover our curated collection of rare fish species, aquatic plants, premium equipment, and care supplies. Contact us for availability and custom orders.', 'aqualuxe'); ?>
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                    <?php
                    $categories = [
                        [
                            'title' => esc_html__('Rare Fish Species', 'aqualuxe'),
                            'description' => esc_html__('Exotic and rare aquatic species', 'aqualuxe'),
                            'icon' => '🐠'
                        ],
                        [
                            'title' => esc_html__('Aquatic Plants', 'aqualuxe'),
                            'description' => esc_html__('Premium aquascaping plants', 'aqualuxe'),
                            'icon' => '🌱'
                        ],
                        [
                            'title' => esc_html__('Equipment', 'aqualuxe'),
                            'description' => esc_html__('Professional-grade aquarium equipment', 'aqualuxe'),
                            'icon' => '⚙️'
                        ],
                        [
                            'title' => esc_html__('Care Supplies', 'aqualuxe'),
                            'description' => esc_html__('Premium maintenance products', 'aqualuxe'),
                            'icon' => '🧪'
                        ],
                    ];
                    
                    foreach ($categories as $category) :
                        ?>
                        <div class="category-card bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <div class="text-4xl mb-4"><?php echo $category['icon']; ?></div>
                            <h3 class="text-xl font-semibold mb-2"><?php echo $category['title']; ?></h3>
                            <p class="text-gray-600"><?php echo $category['description']; ?></p>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <div class="mt-12">
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Contact Us for Pricing', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize WooCommerce integration
AquaLuxe_WooCommerce_Integration::init();