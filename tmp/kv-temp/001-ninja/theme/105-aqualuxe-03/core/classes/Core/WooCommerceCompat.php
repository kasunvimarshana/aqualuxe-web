<?php
/**
 * WooCommerce Compatibility Class
 *
 * Handles WooCommerce integration with dual-state architecture
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * WooCommerce compatibility handler
 */
class WooCommerceCompat {
    
    /**
     * Single instance of the class
     *
     * @var WooCommerceCompat
     */
    private static $instance = null;

    /**
     * WooCommerce active status
     *
     * @var bool
     */
    private $woocommerce_active = false;

    /**
     * Get instance
     *
     * @return WooCommerceCompat
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
        $this->woocommerce_active = class_exists('WooCommerce');
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', array($this, 'setup_woocommerce_support'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_woocommerce_styles'));
        add_filter('body_class', array($this, 'add_woocommerce_body_classes'));
        
        if ($this->woocommerce_active) {
            $this->init_woocommerce_hooks();
        } else {
            $this->init_fallback_hooks();
        }
    }

    /**
     * Setup WooCommerce theme support
     */
    public function setup_woocommerce_support() {
        if (!$this->woocommerce_active) {
            return;
        }

        add_theme_support('woocommerce', array(
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => array(
                'default_rows'    => 3,
                'min_rows'        => 2,
                'max_rows'        => 8,
                'default_columns' => 4,
                'min_columns'     => 2,
                'max_columns'     => 5,
            ),
        ));

        // Add support for WC features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Initialize WooCommerce-specific hooks
     */
    private function init_woocommerce_hooks() {
        // Remove default WooCommerce wrappers
        remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
        remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
        
        // Add custom wrappers
        add_action('woocommerce_before_main_content', array($this, 'wrapper_start'), 10);
        add_action('woocommerce_after_main_content', array($this, 'wrapper_end'), 10);
        
        // Customize breadcrumbs
        add_filter('woocommerce_breadcrumb_defaults', array($this, 'breadcrumb_args'));
        
        // Customize pagination
        add_filter('woocommerce_pagination_args', array($this, 'pagination_args'));
        
        // Product loop customizations
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
        add_action('woocommerce_before_shop_loop_item', array($this, 'loop_product_link_open'), 10);
        add_action('woocommerce_after_shop_loop_item', array($this, 'loop_product_link_close'), 5);
        
        // Disable default WooCommerce styles (we handle them in our CSS)
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Add custom cart fragments for AJAX cart updates
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'cart_count_fragments'));
        
        // Enhance single product page
        add_action('woocommerce_single_product_summary', array($this, 'add_product_meta'), 25);
        
        // Add quick view support
        add_action('woocommerce_after_shop_loop_item', array($this, 'add_quick_view_button'), 15);
        
        // AJAX handlers
        aqualuxe_secure_ajax_handler('add_to_cart', array($this, 'ajax_add_to_cart'), false, true);
        aqualuxe_secure_ajax_handler('quick_view', array($this, 'ajax_quick_view'), false, true);
        aqualuxe_secure_ajax_handler('wishlist_toggle', array($this, 'ajax_wishlist_toggle'), false, true);
    }

    /**
     * Initialize fallback hooks when WooCommerce is not active
     */
    private function init_fallback_hooks() {
        // Add product-like functionality without WooCommerce
        add_action('init', array($this, 'register_product_post_type'));
        add_action('init', array($this, 'register_product_taxonomies'));
        add_shortcode('aqualuxe_products', array($this, 'products_shortcode'));
        add_shortcode('aqualuxe_cart', array($this, 'cart_fallback_shortcode'));
    }

    /**
     * Enqueue WooCommerce-specific styles
     */
    public function enqueue_woocommerce_styles() {
        if ($this->is_woocommerce_page() && file_exists(AQUALUXE_THEME_DIR . '/assets/dist/css/woocommerce.css')) {
            $manifest = aqualuxe_get_mix_manifest();
            $wc_css = aqualuxe_get_manifest_file($manifest, 'css/woocommerce.css');
            
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . '/css/' . $wc_css,
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Add WooCommerce-related body classes
     */
    public function add_woocommerce_body_classes($classes) {
        if ($this->woocommerce_active) {
            $classes[] = 'has-woocommerce';
            
            if (is_woocommerce()) {
                $classes[] = 'woocommerce-page';
            }
            
            if (is_shop()) {
                $classes[] = 'woocommerce-shop';
            }
            
            if (is_product()) {
                $classes[] = 'woocommerce-product';
                global $product;
                if ($product) {
                    $classes[] = 'product-type-' . $product->get_type();
                }
            }
            
            if (is_cart()) {
                $classes[] = 'woocommerce-cart';
                if (WC()->cart->is_empty()) {
                    $classes[] = 'cart-empty';
                }
            }
            
            if (is_checkout()) {
                $classes[] = 'woocommerce-checkout';
            }
            
            if (is_account_page()) {
                $classes[] = 'woocommerce-account';
            }
        } else {
            $classes[] = 'no-woocommerce';
        }
        
        return $classes;
    }

    /**
     * WooCommerce wrapper start
     */
    public function wrapper_start() {
        echo '<div id="primary" class="content-area">';
        echo '<div class="container mx-auto px-4 py-8">';
        echo '<main id="main" class="site-main" role="main">';
    }

    /**
     * WooCommerce wrapper end
     */
    public function wrapper_end() {
        echo '</main>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Customize WooCommerce breadcrumb arguments
     */
    public function breadcrumb_args($defaults) {
        return array_merge($defaults, array(
            'delimiter'   => ' <span class="separator mx-2 text-gray-400">/</span> ',
            'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumb text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">',
            'wrap_after'  => '</nav>',
            'before'      => '<span class="breadcrumb-item">',
            'after'       => '</span>',
            'home'        => _x('Home', 'breadcrumb', 'aqualuxe'),
        ));
    }

    /**
     * Customize pagination arguments
     */
    public function pagination_args($args) {
        return array_merge($args, array(
            'prev_text' => '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
            'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
        ));
    }

    /**
     * Custom product loop link open
     */
    public function loop_product_link_open() {
        global $product;
        $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
        echo '<a href="' . esc_url($link) . '" class="woocommerce-LoopProduct-link block group relative overflow-hidden">';
    }

    /**
     * Custom product loop link close
     */
    public function loop_product_link_close() {
        echo '</a>';
    }

    /**
     * Add cart count fragments for AJAX updates
     */
    public function cart_count_fragments($fragments) {
        if (!$this->woocommerce_active) {
            return $fragments;
        }

        $cart_count = WC()->cart->get_cart_contents_count();
        
        $fragments['.cart-count'] = '<span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center' . ($cart_count > 0 ? '' : ' hidden') . '">' . esc_html($cart_count) . '</span>';
        
        return $fragments;
    }

    /**
     * Add enhanced product meta
     */
    public function add_product_meta() {
        global $product;
        
        if (!$product) {
            return;
        }

        echo '<div class="aqualuxe-product-meta mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">';
        
        // Product SKU
        if ($product->get_sku()) {
            echo '<div class="product-sku mb-2">';
            echo '<span class="label font-medium text-gray-600 dark:text-gray-400">' . __('SKU:', 'aqualuxe') . '</span> ';
            echo '<span class="value text-gray-900 dark:text-gray-100">' . esc_html($product->get_sku()) . '</span>';
            echo '</div>';
        }
        
        // Product categories
        $categories = get_the_terms($product->get_id(), 'product_cat');
        if ($categories && !is_wp_error($categories)) {
            echo '<div class="product-categories mb-2">';
            echo '<span class="label font-medium text-gray-600 dark:text-gray-400">' . __('Categories:', 'aqualuxe') . '</span> ';
            $category_links = array();
            foreach ($categories as $category) {
                $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($category->name) . '</a>';
            }
            echo implode(', ', $category_links);
            echo '</div>';
        }
        
        // Product tags
        $tags = get_the_terms($product->get_id(), 'product_tag');
        if ($tags && !is_wp_error($tags)) {
            echo '<div class="product-tags">';
            echo '<span class="label font-medium text-gray-600 dark:text-gray-400">' . __('Tags:', 'aqualuxe') . '</span> ';
            $tag_links = array();
            foreach ($tags as $tag) {
                $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($tag->name) . '</a>';
            }
            echo implode(', ', $tag_links);
            echo '</div>';
        }
        
        echo '</div>';
    }

    /**
     * Add quick view button to product loop
     */
    public function add_quick_view_button() {
        global $product;
        
        if (!$product) {
            return;
        }

        echo '<button type="button" class="quick-view-btn absolute top-2 right-2 bg-white dark:bg-gray-800 rounded-full p-2 shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-200" data-product-id="' . esc_attr($product->get_id()) . '" title="' . esc_attr__('Quick View', 'aqualuxe') . '">';
        echo '<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        echo '</svg>';
        echo '</button>';
    }

    /**
     * AJAX add to cart handler
     */
    public function ajax_add_to_cart($data) {
        if (!$this->woocommerce_active) {
            wp_send_json_error(__('WooCommerce is not active.', 'aqualuxe'));
        }

        $product_id = isset($data['product_id']) ? absint($data['product_id']) : 0;
        $quantity = isset($data['quantity']) ? absint($data['quantity']) : 1;
        $variation_id = isset($data['variation_id']) ? absint($data['variation_id']) : 0;
        $variation = isset($data['variation']) ? (array) $data['variation'] : array();

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        $result = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);

        if ($result) {
            wp_send_json_success(array(
                'message' => __('Product added to cart.', 'aqualuxe'),
                'cart_count' => WC()->cart->get_cart_contents_count(),
                'cart_total' => WC()->cart->get_cart_total(),
            ));
        } else {
            wp_send_json_error(__('Failed to add product to cart.', 'aqualuxe'));
        }
    }

    /**
     * AJAX quick view handler
     */
    public function ajax_quick_view($data) {
        if (!$this->woocommerce_active) {
            wp_send_json_error(__('WooCommerce is not active.', 'aqualuxe'));
        }

        $product_id = isset($data['product_id']) ? absint($data['product_id']) : 0;

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        $product = wc_get_product($product_id);

        if (!$product) {
            wp_send_json_error(__('Product not found.', 'aqualuxe'));
        }

        ob_start();
        $this->render_quick_view_content($product);
        $content = ob_get_clean();

        wp_send_json_success(array(
            'content' => $content,
            'title' => $product->get_name(),
        ));
    }

    /**
     * AJAX wishlist toggle handler
     */
    public function ajax_wishlist_toggle($data) {
        $product_id = isset($data['product_id']) ? absint($data['product_id']) : 0;
        $action = isset($data['wishlist_action']) ? sanitize_text_field($data['wishlist_action']) : 'add';

        if (!$product_id) {
            wp_send_json_error(__('Invalid product.', 'aqualuxe'));
        }

        $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
        $wishlist = is_array($wishlist) ? $wishlist : array();

        if ($action === 'add' && !in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            $message = __('Product added to wishlist.', 'aqualuxe');
        } elseif ($action === 'remove' && in_array($product_id, $wishlist)) {
            $wishlist = array_diff($wishlist, array($product_id));
            $message = __('Product removed from wishlist.', 'aqualuxe');
        } else {
            wp_send_json_error(__('Invalid action.', 'aqualuxe'));
        }

        update_user_meta(get_current_user_id(), 'aqualuxe_wishlist', $wishlist);

        wp_send_json_success(array(
            'message' => $message,
            'wishlist_count' => count($wishlist),
            'in_wishlist' => in_array($product_id, $wishlist),
        ));
    }

    /**
     * Render quick view content
     */
    private function render_quick_view_content($product) {
        ?>
        <div class="quick-view-product" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="product-images">
                    <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    if (has_post_thumbnail($product->get_id())) {
                        echo get_the_post_thumbnail($product->get_id(), 'medium_large', array('class' => 'w-full h-auto rounded-lg'));
                    }
                    ?>
                </div>
                
                <div class="product-summary">
                    <h2 class="product-title text-2xl font-bold mb-4"><?php echo esc_html($product->get_name()); ?></h2>
                    
                    <div class="price mb-4 text-xl font-semibold text-primary-600">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <?php if ($product->get_short_description()) : ?>
                        <div class="product-short-description mb-4 text-gray-600 dark:text-gray-400">
                            <?php echo wp_kses_post($product->get_short_description()); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="cart" method="post" enctype='multipart/form-data'>
                        <?php do_action('woocommerce_before_add_to_cart_button'); ?>
                        
                        <div class="quantity-input mb-4">
                            <label for="quantity" class="sr-only"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
                            <input type="number" id="quantity" name="quantity" value="1" min="1" class="quantity-field">
                        </div>
                        
                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn btn-primary w-full">
                            <?php echo esc_html($product->single_add_to_cart_text()); ?>
                        </button>
                        
                        <?php do_action('woocommerce_after_add_to_cart_button'); ?>
                    </form>
                    
                    <div class="product-meta mt-6">
                        <?php if ($product->get_sku()) : ?>
                            <span class="sku_wrapper block">
                                <span class="font-medium"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span>
                                <span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Check if current page is WooCommerce related
     */
    private function is_woocommerce_page() {
        if (!$this->woocommerce_active) {
            return false;
        }

        return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
    }

    /**
     * Register fallback product post type (when WooCommerce is not active)
     */
    public function register_product_post_type() {
        $labels = array(
            'name'                  => _x('Products', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Product', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Products', 'Admin Menu text', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Product', 'aqualuxe'),
            'new_item'              => __('New Product', 'aqualuxe'),
            'edit_item'             => __('Edit Product', 'aqualuxe'),
            'view_item'             => __('View Product', 'aqualuxe'),
            'all_items'             => __('All Products', 'aqualuxe'),
            'search_items'          => __('Search Products', 'aqualuxe'),
            'not_found'             => __('No products found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No products found in Trash.', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'products'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 26,
            'menu_icon'          => 'dashicons-products',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        );

        register_post_type('aqualuxe_product', $args);
    }

    /**
     * Register fallback product taxonomies
     */
    public function register_product_taxonomies() {
        // Product Categories
        register_taxonomy('aqualuxe_product_cat', 'aqualuxe_product', array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => _x('Product Categories', 'taxonomy general name', 'aqualuxe'),
                'singular_name'     => _x('Product Category', 'taxonomy singular name', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'product-category'),
        ));

        // Product Tags
        register_taxonomy('aqualuxe_product_tag', 'aqualuxe_product', array(
            'hierarchical'      => false,
            'labels'            => array(
                'name'                       => _x('Product Tags', 'taxonomy general name', 'aqualuxe'),
                'singular_name'              => _x('Product Tag', 'taxonomy singular name', 'aqualuxe'),
            ),
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'product-tag'),
        ));
    }

    /**
     * Products shortcode (fallback when WooCommerce is not active)
     */
    public function products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 8,
            'columns' => 4,
            'category' => '',
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_product',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );

        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'aqualuxe_product_cat',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ),
            );
        }

        $products = new \WP_Query($args);

        if (!$products->have_posts()) {
            return '<p>' . __('No products found.', 'aqualuxe') . '</p>';
        }

        ob_start();
        ?>
        <div class="aqualuxe-products grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-6">
            <?php while ($products->have_posts()) : $products->the_post(); ?>
                <div class="product-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="product-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('aqualuxe-medium', array('class' => 'w-full h-48 object-cover')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-content p-4">
                        <h3 class="product-title text-lg font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-primary-600">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        
                        <div class="product-excerpt text-gray-600 dark:text-gray-400 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <div class="product-actions">
                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                <?php esc_html_e('View Details', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Cart fallback shortcode
     */
    public function cart_fallback_shortcode($atts) {
        if ($this->woocommerce_active) {
            return do_shortcode('[woocommerce_cart]');
        }

        return '<div class="cart-fallback p-6 bg-gray-50 dark:bg-gray-800 rounded-lg text-center">
            <h3 class="text-xl font-semibold mb-4">' . __('Shopping Cart', 'aqualuxe') . '</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">' . __('To enable full e-commerce functionality, please install and activate WooCommerce.', 'aqualuxe') . '</p>
            <a href="' . esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')) . '" class="btn btn-primary">' . __('Install WooCommerce', 'aqualuxe') . '</a>
        </div>';
    }

    /**
     * Check if WooCommerce is active
     */
    public function is_woocommerce_active() {
        return $this->woocommerce_active;
    }
}