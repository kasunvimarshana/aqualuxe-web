<?php
/**
 * WooCommerce Integration Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * WooCommerce Integration Class
 * 
 * This class is responsible for integrating WooCommerce with the theme.
 */
class WooCommerce {
    /**
     * Instance of this class
     *
     * @var WooCommerce
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return WooCommerce
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->setup_hooks();
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        // Theme support
        add_action( 'after_setup_theme', [ $this, 'woocommerce_setup' ] );
        
        // Scripts and styles
        add_action( 'wp_enqueue_scripts', [ $this, 'woocommerce_scripts' ], 20 );
        
        // Layout
        add_filter( 'body_class', [ $this, 'woocommerce_body_classes' ] );
        add_filter( 'woocommerce_output_related_products_args', [ $this, 'related_products_args' ] );
        
        // Breadcrumbs
        add_filter( 'woocommerce_breadcrumb_defaults', [ $this, 'breadcrumb_defaults' ] );
        
        // Product columns
        add_filter( 'loop_shop_columns', [ $this, 'loop_columns' ] );
        add_filter( 'woocommerce_product_thumbnails_columns', [ $this, 'thumbnail_columns' ] );
        
        // Products per page
        add_filter( 'loop_shop_per_page', [ $this, 'products_per_page' ] );
        
        // Product gallery
        add_filter( 'woocommerce_gallery_thumbnail_size', [ $this, 'gallery_thumbnail_size' ] );
        
        // Cart fragments
        add_filter( 'woocommerce_add_to_cart_fragments', [ $this, 'cart_link_fragment' ] );
        
        // Checkout fields
        add_filter( 'woocommerce_checkout_fields', [ $this, 'checkout_fields' ] );
        
        // Wishlist
        add_action( 'wp_ajax_aqualuxe_add_to_wishlist', [ $this, 'add_to_wishlist' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_add_to_wishlist', [ $this, 'add_to_wishlist' ] );
        add_action( 'wp_ajax_aqualuxe_remove_from_wishlist', [ $this, 'remove_from_wishlist' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_remove_from_wishlist', [ $this, 'remove_from_wishlist' ] );
        
        // Quick view
        add_action( 'wp_ajax_aqualuxe_product_quick_view', [ $this, 'product_quick_view' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_product_quick_view', [ $this, 'product_quick_view' ] );
        
        // Product filtering
        add_action( 'wp_ajax_aqualuxe_filter_products', [ $this, 'filter_products' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_filter_products', [ $this, 'filter_products' ] );
        
        // Currency switcher
        add_filter( 'woocommerce_currency', [ $this, 'change_currency' ] );
        add_filter( 'woocommerce_currency_symbol', [ $this, 'change_currency_symbol' ] );
        
        // International shipping
        add_filter( 'woocommerce_package_rates', [ $this, 'international_shipping_rates' ], 10, 2 );
        
        // Checkout optimization
        add_action( 'woocommerce_checkout_process', [ $this, 'optimize_checkout' ] );
        
        // Product structured data
        add_filter( 'woocommerce_structured_data_product', [ $this, 'product_structured_data' ], 10, 2 );
    }

    /**
     * WooCommerce setup
     *
     * @return void
     */
    public function woocommerce_setup() {
        // Add theme support for WooCommerce
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
        
        // Remove default WooCommerce styles
        add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
    }

    /**
     * WooCommerce scripts
     *
     * @return void
     */
    public function woocommerce_scripts() {
        // Enqueue WooCommerce styles
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'css/woocommerce.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce scripts
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            AQUALUXE_ASSETS_URI . 'js/woocommerce.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        
        // Add WooCommerce settings to JavaScript
        wp_localize_script(
            'aqualuxe-woocommerce',
            'aqualuxeWooCommerce',
            [
                'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
                'nonce'               => wp_create_nonce( 'aqualuxe-woocommerce' ),
                'addToCartText'       => esc_html__( 'Add to cart', 'aqualuxe' ),
                'addingToCartText'    => esc_html__( 'Adding...', 'aqualuxe' ),
                'addedToCartText'     => esc_html__( 'Added to cart', 'aqualuxe' ),
                'viewCartText'        => esc_html__( 'View cart', 'aqualuxe' ),
                'addToWishlistText'   => esc_html__( 'Add to wishlist', 'aqualuxe' ),
                'addingToWishlistText' => esc_html__( 'Adding...', 'aqualuxe' ),
                'addedToWishlistText' => esc_html__( 'Added to wishlist', 'aqualuxe' ),
                'viewWishlistText'    => esc_html__( 'View wishlist', 'aqualuxe' ),
                'quickViewText'       => esc_html__( 'Quick view', 'aqualuxe' ),
                'loadingText'         => esc_html__( 'Loading...', 'aqualuxe' ),
                'filteringText'       => esc_html__( 'Filtering...', 'aqualuxe' ),
                'currencySymbol'      => get_woocommerce_currency_symbol(),
                'currencyPosition'    => get_option( 'woocommerce_currency_pos' ),
                'decimalSeparator'    => wc_get_price_decimal_separator(),
                'thousandSeparator'   => wc_get_price_thousand_separator(),
                'decimals'            => wc_get_price_decimals(),
            ]
        );
    }

    /**
     * Add WooCommerce specific classes to the body tag
     *
     * @param array $classes Body classes
     * @return array
     */
    public function woocommerce_body_classes( $classes ) {
        // Add a class for the shop layout
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'left-sidebar' );
            $classes[] = 'shop-layout-' . $shop_layout;
        }
        
        // Add a class for the product layout
        if ( is_product() ) {
            $product_layout = get_theme_mod( 'aqualuxe_product_layout', 'full-width' );
            $classes[] = 'product-layout-' . $product_layout;
        }
        
        // Add a class for the cart layout
        if ( is_cart() ) {
            $classes[] = 'cart-layout';
        }
        
        // Add a class for the checkout layout
        if ( is_checkout() ) {
            $classes[] = 'checkout-layout';
        }
        
        // Add a class for the account layout
        if ( is_account_page() ) {
            $classes[] = 'account-layout';
        }
        
        return $classes;
    }

    /**
     * Related products arguments
     *
     * @param array $args Related products args
     * @return array
     */
    public function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }

    /**
     * Breadcrumb defaults
     *
     * @param array $defaults Breadcrumb defaults
     * @return array
     */
    public function breadcrumb_defaults( $defaults ) {
        $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
        $defaults['wrap_after'] = '</nav>';
        $defaults['home'] = esc_html__( 'Home', 'aqualuxe' );
        return $defaults;
    }

    /**
     * Product columns
     *
     * @return int
     */
    public function loop_columns() {
        return 4;
    }

    /**
     * Product thumbnail columns
     *
     * @return int
     */
    public function thumbnail_columns() {
        return 4;
    }

    /**
     * Products per page
     *
     * @return int
     */
    public function products_per_page() {
        return 12;
    }

    /**
     * Gallery thumbnail size
     *
     * @return string
     */
    public function gallery_thumbnail_size() {
        return 'woocommerce_thumbnail';
    }

    /**
     * Cart link fragment
     *
     * @param array $fragments Cart fragments
     * @return array
     */
    public function cart_link_fragment( $fragments ) {
        ob_start();
        ?>
        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
            <span class="cart-contents-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            <span class="cart-contents-total"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
        </a>
        <?php
        $fragments['a.cart-contents'] = ob_get_clean();
        
        ob_start();
        ?>
        <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
        <?php
        $fragments['span.cart-count'] = ob_get_clean();
        
        return $fragments;
    }

    /**
     * Checkout fields
     *
     * @param array $fields Checkout fields
     * @return array
     */
    public function checkout_fields( $fields ) {
        // Add placeholder to the billing fields
        foreach ( $fields['billing'] as $key => $field ) {
            if ( ! isset( $field['placeholder'] ) && isset( $field['label'] ) ) {
                $fields['billing'][ $key ]['placeholder'] = $field['label'];
            }
        }
        
        // Add placeholder to the shipping fields
        foreach ( $fields['shipping'] as $key => $field ) {
            if ( ! isset( $field['placeholder'] ) && isset( $field['label'] ) ) {
                $fields['shipping'][ $key ]['placeholder'] = $field['label'];
            }
        }
        
        return $fields;
    }

    /**
     * Add to wishlist
     *
     * @return void
     */
    public function add_to_wishlist() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        $product_id = absint( $_POST['product_id'] );
        
        // Get current wishlist
        $wishlist = $this->get_wishlist();
        
        // Add product to wishlist
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
        }
        
        // Update wishlist
        $this->update_wishlist( $wishlist );
        
        wp_send_json_success( [
            'message'  => __( 'Product added to wishlist', 'aqualuxe' ),
            'wishlist' => $wishlist,
        ] );
    }

    /**
     * Remove from wishlist
     *
     * @return void
     */
    public function remove_from_wishlist() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        $product_id = absint( $_POST['product_id'] );
        
        // Get current wishlist
        $wishlist = $this->get_wishlist();
        
        // Remove product from wishlist
        $wishlist = array_diff( $wishlist, [ $product_id ] );
        
        // Update wishlist
        $this->update_wishlist( $wishlist );
        
        wp_send_json_success( [
            'message'  => __( 'Product removed from wishlist', 'aqualuxe' ),
            'wishlist' => $wishlist,
        ] );
    }

    /**
     * Get wishlist
     *
     * @return array
     */
    public function get_wishlist() {
        $wishlist = [];
        
        if ( is_user_logged_in() ) {
            // Get wishlist from user meta
            $user_id = get_current_user_id();
            $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
            
            if ( ! is_array( $wishlist ) ) {
                $wishlist = [];
            }
        } else {
            // Get wishlist from cookie
            if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
                $wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true );
                
                if ( ! is_array( $wishlist ) ) {
                    $wishlist = [];
                }
            }
        }
        
        return $wishlist;
    }

    /**
     * Update wishlist
     *
     * @param array $wishlist
     * @return void
     */
    public function update_wishlist( $wishlist ) {
        if ( is_user_logged_in() ) {
            // Update user meta
            $user_id = get_current_user_id();
            update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
        } else {
            // Update cookie
            setcookie( 'aqualuxe_wishlist', wp_json_encode( $wishlist ), time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
        }
    }

    /**
     * Product quick view
     *
     * @return void
     */
    public function product_quick_view() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        // Check product ID
        if ( ! isset( $_POST['product_id'] ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid product ID', 'aqualuxe' ) ] );
        }
        
        $product_id = absint( $_POST['product_id'] );
        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( [ 'message' => __( 'Product not found', 'aqualuxe' ) ] );
        }
        
        ob_start();
        
        // Include the quick view template
        include get_template_directory() . '/woocommerce/quick-view.php';
        
        $html = ob_get_clean();
        
        wp_send_json_success( [
            'html' => $html,
        ] );
    }

    /**
     * Filter products
     *
     * @return void
     */
    public function filter_products() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce' ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid nonce', 'aqualuxe' ) ] );
        }
        
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 12,
            'paged'          => isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1,
        ];
        
        // Category filter
        if ( isset( $_POST['category'] ) && ! empty( $_POST['category'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( wp_unslash( $_POST['category'] ) ),
            ];
        }
        
        // Tag filter
        if ( isset( $_POST['tag'] ) && ! empty( $_POST['tag'] ) ) {
            $args['tax_query'][] = [
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( wp_unslash( $_POST['tag'] ) ),
            ];
        }
        
        // Price filter
        if ( isset( $_POST['min_price'] ) && isset( $_POST['max_price'] ) ) {
            $args['meta_query'][] = [
                'key'     => '_price',
                'value'   => [ floatval( $_POST['min_price'] ), floatval( $_POST['max_price'] ) ],
                'type'    => 'DECIMAL',
                'compare' => 'BETWEEN',
            ];
        }
        
        // Attribute filter
        if ( isset( $_POST['attributes'] ) && is_array( $_POST['attributes'] ) ) {
            foreach ( $_POST['attributes'] as $taxonomy => $terms ) {
                if ( ! empty( $terms ) ) {
                    $args['tax_query'][] = [
                        'taxonomy' => sanitize_key( $taxonomy ),
                        'field'    => 'slug',
                        'terms'    => array_map( 'sanitize_text_field', wp_unslash( $terms ) ),
                        'operator' => 'IN',
                    ];
                }
            }
        }
        
        // Order by
        if ( isset( $_POST['orderby'] ) && ! empty( $_POST['orderby'] ) ) {
            $orderby = sanitize_text_field( wp_unslash( $_POST['orderby'] ) );
            
            switch ( $orderby ) {
                case 'price':
                    $args['meta_key'] = '_price';
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = 'ASC';
                    break;
                case 'price-desc':
                    $args['meta_key'] = '_price';
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = 'DESC';
                    break;
                case 'rating':
                    $args['meta_key'] = '_wc_average_rating';
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = 'DESC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby']  = 'meta_value_num';
                    $args['order']    = 'DESC';
                    break;
                case 'date':
                    $args['orderby'] = 'date';
                    $args['order']   = 'DESC';
                    break;
                default:
                    $args['orderby'] = 'menu_order title';
                    $args['order']   = 'ASC';
                    break;
            }
        }
        
        // Search
        if ( isset( $_POST['search'] ) && ! empty( $_POST['search'] ) ) {
            $args['s'] = sanitize_text_field( wp_unslash( $_POST['search'] ) );
        }
        
        // Query products
        $query = new \WP_Query( $args );
        
        ob_start();
        
        if ( $query->have_posts() ) {
            woocommerce_product_loop_start();
            
            while ( $query->have_posts() ) {
                $query->the_post();
                wc_get_template_part( 'content', 'product' );
            }
            
            woocommerce_product_loop_end();
            
            woocommerce_pagination();
        } else {
            echo '<p class="woocommerce-info">' . esc_html__( 'No products found', 'aqualuxe' ) . '</p>';
        }
        
        wp_reset_postdata();
        
        $html = ob_get_clean();
        
        wp_send_json_success( [
            'html'       => $html,
            'found_posts' => $query->found_posts,
        ] );
    }

    /**
     * Change currency
     *
     * @param string $currency
     * @return string
     */
    public function change_currency( $currency ) {
        // Check if a currency is selected
        if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
            $selected_currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
            
            // Check if the selected currency is valid
            $available_currencies = $this->get_available_currencies();
            
            if ( isset( $available_currencies[ $selected_currency ] ) ) {
                return $selected_currency;
            }
        }
        
        return $currency;
    }

    /**
     * Change currency symbol
     *
     * @param string $symbol
     * @return string
     */
    public function change_currency_symbol( $symbol ) {
        // Check if a currency is selected
        if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
            $selected_currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
            
            // Check if the selected currency is valid
            $available_currencies = $this->get_available_currencies();
            
            if ( isset( $available_currencies[ $selected_currency ] ) ) {
                return $available_currencies[ $selected_currency ]['symbol'];
            }
        }
        
        return $symbol;
    }

    /**
     * Get available currencies
     *
     * @return array
     */
    public function get_available_currencies() {
        return [
            'USD' => [
                'name'   => __( 'US Dollar', 'aqualuxe' ),
                'symbol' => '$',
            ],
            'EUR' => [
                'name'   => __( 'Euro', 'aqualuxe' ),
                'symbol' => '€',
            ],
            'GBP' => [
                'name'   => __( 'British Pound', 'aqualuxe' ),
                'symbol' => '£',
            ],
            'JPY' => [
                'name'   => __( 'Japanese Yen', 'aqualuxe' ),
                'symbol' => '¥',
            ],
            'AUD' => [
                'name'   => __( 'Australian Dollar', 'aqualuxe' ),
                'symbol' => 'A$',
            ],
            'CAD' => [
                'name'   => __( 'Canadian Dollar', 'aqualuxe' ),
                'symbol' => 'C$',
            ],
            'CHF' => [
                'name'   => __( 'Swiss Franc', 'aqualuxe' ),
                'symbol' => 'CHF',
            ],
            'CNY' => [
                'name'   => __( 'Chinese Yuan', 'aqualuxe' ),
                'symbol' => '¥',
            ],
            'INR' => [
                'name'   => __( 'Indian Rupee', 'aqualuxe' ),
                'symbol' => '₹',
            ],
            'BRL' => [
                'name'   => __( 'Brazilian Real', 'aqualuxe' ),
                'symbol' => 'R$',
            ],
        ];
    }

    /**
     * International shipping rates
     *
     * @param array $rates
     * @param array $package
     * @return array
     */
    public function international_shipping_rates( $rates, $package ) {
        // Check if the shipping is international
        if ( ! isset( $package['destination']['country'] ) ) {
            return $rates;
        }
        
        $ship_to_country = $package['destination']['country'];
        $base_country = WC()->countries->get_base_country();
        
        // If shipping to the same country, return the original rates
        if ( $ship_to_country === $base_country ) {
            return $rates;
        }
        
        // Get the shipping zones
        $shipping_zones = [
            'zone1' => [ 'US', 'CA' ],
            'zone2' => [ 'GB', 'DE', 'FR', 'IT', 'ES', 'NL', 'BE', 'AT', 'CH' ],
            'zone3' => [ 'AU', 'NZ', 'JP', 'SG', 'KR' ],
            'zone4' => [ 'BR', 'MX', 'AR', 'CL', 'CO', 'PE' ],
        ];
        
        // Find the shipping zone for the destination country
        $shipping_zone = 'rest';
        
        foreach ( $shipping_zones as $zone => $countries ) {
            if ( in_array( $ship_to_country, $countries, true ) ) {
                $shipping_zone = $zone;
                break;
            }
        }
        
        // Get the shipping rates for the zone
        $zone_rates = [
            'zone1' => [
                'standard' => 15,
                'express'  => 25,
            ],
            'zone2' => [
                'standard' => 20,
                'express'  => 35,
            ],
            'zone3' => [
                'standard' => 25,
                'express'  => 45,
            ],
            'zone4' => [
                'standard' => 30,
                'express'  => 55,
            ],
            'rest' => [
                'standard' => 35,
                'express'  => 65,
            ],
        ];
        
        // Clear the original rates
        $rates = [];
        
        // Add the international shipping rates
        $rates['international_standard'] = new \WC_Shipping_Rate(
            'international_standard',
            __( 'International Standard', 'aqualuxe' ),
            $zone_rates[ $shipping_zone ]['standard'],
            [],
            'international_standard'
        );
        
        $rates['international_express'] = new \WC_Shipping_Rate(
            'international_express',
            __( 'International Express', 'aqualuxe' ),
            $zone_rates[ $shipping_zone ]['express'],
            [],
            'international_express'
        );
        
        return $rates;
    }

    /**
     * Optimize checkout
     *
     * @return void
     */
    public function optimize_checkout() {
        // Add custom validation if needed
    }

    /**
     * Product structured data
     *
     * @param array $markup
     * @param \WC_Product $product
     * @return array
     */
    public function product_structured_data( $markup, $product ) {
        // Add brand to the structured data
        $brand = get_post_meta( $product->get_id(), '_product_brand', true );
        
        if ( $brand ) {
            $markup['brand'] = [
                '@type' => 'Brand',
                'name'  => $brand,
            ];
        }
        
        // Add SKU to the structured data
        $sku = $product->get_sku();
        
        if ( $sku ) {
            $markup['sku'] = $sku;
        }
        
        // Add MPN to the structured data
        $mpn = get_post_meta( $product->get_id(), '_product_mpn', true );
        
        if ( $mpn ) {
            $markup['mpn'] = $mpn;
        }
        
        // Add GTIN to the structured data
        $gtin = get_post_meta( $product->get_id(), '_product_gtin', true );
        
        if ( $gtin ) {
            $markup['gtin'] = $gtin;
        }
        
        return $markup;
    }
}