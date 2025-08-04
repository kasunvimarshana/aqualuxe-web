<?php
/**
 * AquaLuxe Child Theme for WooCommerce
 *
 * @package AquaLuxe
 * @author  Your Name
 * @license GPL-2.0+
 * @link    https://yourwebsite.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme Constants
 */
define( 'AQUALUXE_VERSION', '1.0.0' );
define( 'AQUALUXE_DIR', get_stylesheet_directory() );
define( 'AQUALUXE_URI', get_stylesheet_directory_uri() );

/**
 * Theme Setup
 */
function aqualuxe_theme_setup() {
    // Load text domain
    load_child_theme_textdomain( 'aqualuxe', get_stylesheet_directory() . '/languages' );
    
    // Add theme support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    
    // Register navigation menus
    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
        'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
        'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
    ) );
    
    // Add custom image sizes
    add_image_size( 'aqualuxe-product-thumb', 300, 300, true );
    add_image_size( 'aqualuxe-category-thumb', 400, 300, true );
}
add_action( 'after_setup_theme', 'aqualuxe_theme_setup' );

/**
 * Enqueue Styles and Scripts
 */
function aqualuxe_enqueue_scripts() {
    // Parent theme stylesheet
    wp_enqueue_style( 'storefront-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Main stylesheet
    wp_enqueue_style( 'aqualuxe-style', AQUALUXE_URI . '/assets/css/style.css', array(), AQUALUXE_VERSION );
    
    // Responsive stylesheet
    wp_enqueue_style( 'aqualuxe-responsive', AQUALUXE_URI . '/assets/css/responsive.css', array(), AQUALUXE_VERSION );
    
    // Font Awesome
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );
    
    // Google Fonts
    wp_enqueue_style( 'aqualuxe-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap', array(), null );
    
    // Custom JavaScript
    wp_enqueue_script( 'aqualuxe-custom', AQUALUXE_URI . '/assets/js/custom.js', array( 'jquery' ), AQUALUXE_VERSION, true );
    
    // Localize script
    wp_localize_script( 'aqualuxe-custom', 'aqualuxe_vars', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_scripts' );

/**
 * WooCommerce Customizations
 */

// Remove default WooCommerce styles
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Change number of products per row
add_filter( 'loop_shop_columns', 'aqualuxe_loop_columns', 999 );
function aqualuxe_loop_columns() {
    return 4; // 4 products per row
}

// Change number of related products
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args' );
function aqualuxe_related_products_args( $args ) {
    $args['posts_per_page'] = 4; // 4 related products
    $args['columns'] = 4; // 4 columns
    return $args;
}

// Add custom product tabs
add_filter( 'woocommerce_product_tabs', 'aqualuxe_custom_product_tabs' );
function aqualuxe_custom_product_tabs( $tabs ) {
    // Add care guide tab
    $tabs['care_guide'] = array(
        'title'    => __( 'Care Guide', 'aqualuxe' ),
        'priority' => 20,
        'callback' => 'aqualuxe_care_guide_tab_content'
    );
    
    return $tabs;
}

function aqualuxe_care_guide_tab_content() {
    echo '<h2>' . esc_html__( 'Fish Care Guide', 'aqualuxe' ) . '</h2>';
    echo '<p>' . esc_html__( 'Detailed care instructions for your ornamental fish will appear here.', 'aqualuxe' ) . '</p>';
    // In a real implementation, this would pull from product meta fields
}

// Customize product thumbnail
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_template_loop_product_thumbnail', 10 );
function aqualuxe_template_loop_product_thumbnail() {
    echo woocommerce_get_product_thumbnail();
}

// Add custom body classes
add_filter( 'body_class', 'aqualuxe_body_classes' );
function aqualuxe_body_classes( $classes ) {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        $classes[] = 'aqualuxe-shop-page';
    }
    
    if ( is_product() ) {
        $classes[] = 'aqualuxe-product-page';
    }
    
    return $classes;
}

/**
 * Custom Header
 */
function aqualuxe_custom_header() {
    ?>
    <header class="aqualuxe-header">
        <div class="container">
            <div class="header-top">
                <div class="site-branding">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="header-actions">
                    <div class="search-form">
                        <?php get_product_search_form(); ?>
                    </div>
                    
                    <div class="header-cart">
                        <?php global $woocommerce; ?>
                        <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'aqualuxe' ); ?>">
                            <i class="fas fa-shopping-bag"></i>
                            <span class="cart-count"><?php echo sprintf ( _n( '%d', '%d', $woocommerce->cart->cart_contents_count, 'aqualuxe' ), $woocommerce->cart->cart_contents_count ); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            
            <nav class="main-navigation">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-menu',
                    'container'      => false,
                ) );
                ?>
            </nav>
        </div>
    </header>
    <?php
}
add_action( 'storefront_header', 'aqualuxe_custom_header', 5 );

/**
 * Custom Footer
 */
function aqualuxe_custom_footer() {
    ?>
    <footer class="aqualuxe-footer">
        <div class="container">
            <div class="footer-widgets">
                <div class="footer-widget about-widget">
                    <h3><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h3>
                    <p><?php esc_html_e( 'Premium ornamental fish for discerning aquarists worldwide.', 'aqualuxe' ); ?></p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="footer-widget links-widget">
                    <h3><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h3>
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_class'     => 'footer-menu',
                        'container'      => false,
                    ) );
                    ?>
                </div>
                
                <div class="footer-widget contact-widget">
                    <h3><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h3>
                    <p><i class="fas fa-map-marker-alt"></i> <?php esc_html_e( '123 Aquarium Lane, Ocean City', 'aqualuxe' ); ?></p>
                    <p><i class="fas fa-phone"></i> <?php esc_html_e( '+1 (555) 123-4567', 'aqualuxe' ); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php esc_html_e( 'info@aqualuxe.com', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="copyright">
                    <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'aqualuxe' ); ?></p>
                </div>
                
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                </div>
            </div>
        </div>
    </footer>
    <?php
}
add_action( 'storefront_footer', 'aqualuxe_custom_footer', 20 );

/**
 * AJAX Quick View
 */
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view' );
function aqualuxe_quick_view() {
    if ( ! isset( $_REQUEST['product_id'] ) ) {
        die();
    }
    
    $product_id = intval( $_REQUEST['product_id'] );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        die();
    }
    
    wc_get_template_part( 'content', 'quick-view' );
    
    die();
}