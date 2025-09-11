<?php
/**
 * WooCommerce Fallback
 *
 * This file provides fallback functionality when WooCommerce is not active.
 * It's part of the dual-state architecture of the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define fallback constants and functions that would normally be provided by WooCommerce.
 */

if ( ! function_exists( 'is_woocommerce' ) ) {
    /**
     * Fallback for is_woocommerce function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_woocommerce() {
        return false;
    }
}

if ( ! function_exists( 'is_shop' ) ) {
    /**
     * Fallback for is_shop function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_shop() {
        return false;
    }
}

if ( ! function_exists( 'is_product_category' ) ) {
    /**
     * Fallback for is_product_category function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product_category() {
        return false;
    }
}

if ( ! function_exists( 'is_product_tag' ) ) {
    /**
     * Fallback for is_product_tag function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product_tag() {
        return false;
    }
}

if ( ! function_exists( 'is_product' ) ) {
    /**
     * Fallback for is_product function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product() {
        return false;
    }
}

if ( ! function_exists( 'is_cart' ) ) {
    /**
     * Fallback for is_cart function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_cart() {
        return false;
    }
}

if ( ! function_exists( 'is_checkout' ) ) {
    /**
     * Fallback for is_checkout function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_checkout() {
        return false;
    }
}

if ( ! function_exists( 'is_account_page' ) ) {
    /**
     * Fallback for is_account_page function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_account_page() {
        return false;
    }
}

if ( ! function_exists( 'wc_get_cart_url' ) ) {
    /**
     * Fallback for wc_get_cart_url function.
     *
     * @return string Empty string when WooCommerce is not active.
     */
    function wc_get_cart_url() {
        return '';
    }
}

if ( ! function_exists( 'wc_get_checkout_url' ) ) {
    /**
     * Fallback for wc_get_checkout_url function.
     *
     * @return string Empty string when WooCommerce is not active.
     */
    function wc_get_checkout_url() {
        return '';
    }
}

if ( ! function_exists( 'get_woocommerce_currency_symbol' ) ) {
    /**
     * Fallback for get_woocommerce_currency_symbol function.
     *
     * @return string Default currency symbol.
     */
    function get_woocommerce_currency_symbol() {
        return '$';
    }
}

/**
 * Register fallback shop page.
 *
 * @return void
 */
function aqualuxe_register_shop_page() {
    // Check if the shop page exists.
    $shop_page = get_page_by_path( 'shop' );

    // If the shop page doesn't exist, create it.
    if ( ! $shop_page ) {
        $shop_page_id = wp_insert_post( array(
            'post_title'     => __( 'Shop', 'aqualuxe' ),
            'post_content'   => aqualuxe_get_shop_page_content(),
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_name'      => 'shop',
            'comment_status' => 'closed',
        ) );

        if ( $shop_page_id && ! is_wp_error( $shop_page_id ) ) {
            update_post_meta( $shop_page_id, '_wp_page_template', 'page-shop.php' );
        }
    }
}
add_action( 'after_setup_theme', 'aqualuxe_register_shop_page' );

/**
 * Get fallback shop page content.
 *
 * @return string
 */
function aqualuxe_get_shop_page_content() {
    return '
    <div class="shop-fallback-notice">
        <div class="notice-content">
            <h2>' . esc_html__( 'Shop Coming Soon!', 'aqualuxe' ) . '</h2>
            <p>' . esc_html__( 'Our aquatic shop is currently being set up. Install WooCommerce to enable full e-commerce functionality.', 'aqualuxe' ) . '</p>
        </div>
    </div>
    
    <div class="featured-products-fallback">
        <h3>' . esc_html__( 'Featured Products', 'aqualuxe' ) . '</h3>
        <div class="products-grid">
            <div class="product-item">
                <div class="product-image">
                    <img src="' . esc_url( AQUALUXE_THEME_URI . '/assets/src/images/placeholder-product.jpg' ) . '" alt="' . esc_attr__( 'Premium Aquarium Fish', 'aqualuxe' ) . '">
                </div>
                <h4>' . esc_html__( 'Premium Tropical Fish', 'aqualuxe' ) . '</h4>
                <p class="price">$29.99</p>
                <p>' . esc_html__( 'Beautiful tropical fish for your aquarium.', 'aqualuxe' ) . '</p>
            </div>
            
            <div class="product-item">
                <div class="product-image">
                    <img src="' . esc_url( AQUALUXE_THEME_URI . '/assets/src/images/placeholder-product.jpg' ) . '" alt="' . esc_attr__( 'Aquatic Plants', 'aqualuxe' ) . '">
                </div>
                <h4>' . esc_html__( 'Live Aquatic Plants', 'aqualuxe' ) . '</h4>
                <p class="price">$15.99</p>
                <p>' . esc_html__( 'Fresh live plants for natural aquascaping.', 'aqualuxe' ) . '</p>
            </div>
            
            <div class="product-item">
                <div class="product-image">
                    <img src="' . esc_url( AQUALUXE_THEME_URI . '/assets/src/images/placeholder-product.jpg' ) . '" alt="' . esc_attr__( 'Aquarium Equipment', 'aqualuxe' ) . '">
                </div>
                <h4>' . esc_html__( 'Premium Aquarium Filter', 'aqualuxe' ) . '</h4>
                <p class="price">$89.99</p>
                <p>' . esc_html__( 'High-quality filtration system for crystal clear water.', 'aqualuxe' ) . '</p>
            </div>
        </div>
    </div>';
}

/**
 * Display admin notice about WooCommerce.
 */
function aqualuxe_woocommerce_notice() {
    if ( class_exists( 'WooCommerce' ) || ! current_user_can( 'install_plugins' ) ) {
        return;
    }
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <?php
            printf(
                wp_kses(
                    /* translators: %s: Link to install WooCommerce */
                    __( '<strong>AquaLuxe:</strong> For the full e-commerce experience, please %s to unlock shop functionality.', 'aqualuxe' ),
                    array(
                        'strong' => array(),
                        'a'      => array(
                            'href' => array(),
                        ),
                    )
                ),
                '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">' . esc_html__( 'install and activate WooCommerce', 'aqualuxe' ) . '</a>'
            );
            ?>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'aqualuxe_woocommerce_notice' );

/**
 * Add CSS for WooCommerce fallback.
 *
 * @return void
 */
function aqualuxe_woocommerce_fallback_styles() {
    // Only load on shop page.
    if ( ! is_page( 'shop' ) && ! is_front_page() ) {
        return;
    }

    ?>
    <style type="text/css">
        /* Shop Fallback Styles */
        .shop-fallback-notice {
            background: linear-gradient(135deg, #0077b6, #00b4d8);
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 12px;
        }
        
        .shop-fallback-notice h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        
        .shop-fallback-notice p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Featured Products Fallback */
        .featured-products-fallback {
            padding: 40px 0;
        }
        
        .featured-products-fallback h3 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 2rem;
            color: #333;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .product-item {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        
        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-item h4 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .product-item .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0077b6;
            margin-bottom: 0.5rem;
        }
        
        .product-item p:last-child {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .shop-fallback-notice h2 {
                font-size: 2rem;
            }
            
            .shop-fallback-notice p {
                font-size: 1rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_woocommerce_fallback_styles' );

/**
 * Fallback cart icon for header.
 */
function aqualuxe_cart_icon_fallback() {
    ?>
    <div id="cart-icon-wrapper" class="cart-icon-wrapper fixed top-20 right-6 z-40">
        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'shop' ) ) ?: home_url( '/shop' ) ); ?>" class="cart-icon bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl p-3 rounded-full transition-all duration-300 border border-gray-200 dark:border-gray-700 relative">
            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m2.6 8L6 5H3m4 8v6a2 2 0 002 2h8a2 2 0 002-2v-6m-10 4h10"></path>
            </svg>
            <span class="cart-count hidden absolute -top-2 -right-2 bg-aqua-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
        </a>
    </div>
    <?php
}

/**
 * Helper function to get cart icon (fallback version).
 */
function aqualuxe_cart_icon() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        aqualuxe_cart_icon_fallback();
    }
}