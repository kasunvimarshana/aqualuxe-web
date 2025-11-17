<?php
/**
 * AquaLuxe Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe_Child
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent and child theme styles
 */
function aqualuxe_child_enqueue_styles() {
    // Enqueue parent theme's style.css
    wp_enqueue_style( 'aqualuxe-parent-style', get_template_directory_uri() . '/style.css' );
    
    // Enqueue child theme's style.css with parent dependency
    wp_enqueue_style( 'aqualuxe-child-style', 
        get_stylesheet_directory_uri() . '/style.css',
        array( 'aqualuxe-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
    
    // Optional: Enqueue custom JavaScript
    wp_enqueue_script(
        'aqualuxe-child-script',
        get_stylesheet_directory_uri() . '/js/custom.js',
        array( 'jquery' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles' );

/**
 * Example: Add custom footer text
 */
function aqualuxe_child_custom_footer_text( $text ) {
    $text = 'Customized by ' . wp_get_theme()->get( 'Author' ) . ' &copy; ' . date( 'Y' );
    return $text;
}
add_filter( 'aqualuxe_footer_text', 'aqualuxe_child_custom_footer_text' );

/**
 * Example: Add a new widget area
 */
function aqualuxe_child_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Custom Sidebar', 'aqualuxe-child' ),
            'id'            => 'custom-sidebar',
            'description'   => esc_html__( 'Add widgets here for your custom sidebar.', 'aqualuxe-child' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action( 'widgets_init', 'aqualuxe_child_widgets_init' );

/**
 * Example: Add custom WooCommerce functionality
 */
function aqualuxe_child_woocommerce_setup() {
    // Only run if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        // Example: Add a new tab to single product pages
        add_filter( 'woocommerce_product_tabs', 'aqualuxe_child_add_custom_product_tab' );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_child_woocommerce_setup' );

/**
 * Add a custom product tab
 */
function aqualuxe_child_add_custom_product_tab( $tabs ) {
    $tabs['custom_tab'] = array(
        'title'    => __( 'Care Instructions', 'aqualuxe-child' ),
        'priority' => 25,
        'callback' => 'aqualuxe_child_custom_tab_content'
    );
    return $tabs;
}

/**
 * Custom tab content
 */
function aqualuxe_child_custom_tab_content() {
    // Get custom field value if it exists
    $care_instructions = get_post_meta( get_the_ID(), '_care_instructions', true );
    
    if ( ! empty( $care_instructions ) ) {
        echo wp_kses_post( wpautop( $care_instructions ) );
    } else {
        // Default content if no custom field is set
        echo '<p>' . esc_html__( 'Care instructions for this product will be added soon.', 'aqualuxe-child' ) . '</p>';
    }
}

/**
 * Example: Add custom image size
 */
function aqualuxe_child_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_child_add_image_sizes' );

/**
 * Example: Modify parent theme functionality
 * 
 * This demonstrates how to override a parent theme function
 * using the same function name in the child theme.
 * 
 * Note: Only do this if you need to completely replace a parent function.
 * For most customizations, use hooks and filters instead.
 */
if ( ! function_exists( 'aqualuxe_custom_excerpt_length' ) ) {
    /**
     * Filter the excerpt length
     */
    function aqualuxe_custom_excerpt_length( $length ) {
        return 30; // Custom excerpt length of 30 words
    }
}

/**
 * Example: Add custom shortcode
 */
function aqualuxe_child_featured_product_shortcode( $atts ) {
    // Parse shortcode attributes
    $atts = shortcode_atts(
        array(
            'id' => '',
        ),
        $atts,
        'featured_product'
    );
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) || empty( $atts['id'] ) ) {
        return '<p>' . esc_html__( 'Product not found or WooCommerce is not active.', 'aqualuxe-child' ) . '</p>';
    }
    
    $product_id = absint( $atts['id'] );
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        return '<p>' . esc_html__( 'Product not found.', 'aqualuxe-child' ) . '</p>';
    }
    
    ob_start();
    ?>
    <div class="featured-product-wrapper">
        <div class="featured-product">
            <div class="featured-product-image">
                <?php echo $product->get_image( 'medium' ); ?>
            </div>
            <div class="featured-product-content">
                <h3 class="featured-product-title">
                    <a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
                        <?php echo esc_html( $product->get_name() ); ?>
                    </a>
                </h3>
                <div class="featured-product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                <div class="featured-product-description">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
                <a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="button add_to_cart_button">
                    <?php echo esc_html( $product->add_to_cart_text() ); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'featured_product', 'aqualuxe_child_featured_product_shortcode' );

/**
 * Load child theme text domain
 */
function aqualuxe_child_load_textdomain() {
    load_child_theme_textdomain( 'aqualuxe-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'aqualuxe_child_load_textdomain' );