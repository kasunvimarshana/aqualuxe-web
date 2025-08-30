<?php
/**
 * AquaLuxe Demo Content Importer
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_Demo_Content {
    
    /**
     * Initialize demo content
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
        add_action( 'wp_ajax_aqualuxe_import_demo_content', array( __CLASS__, 'import_demo_content' ) );
        add_action( 'wp_ajax_aqualuxe_reset_content', array( __CLASS__, 'reset_content' ) );
    }
    
    /**
     * Add admin menu
     */
    public static function add_admin_menu() {
        add_theme_page(
            __( 'AquaLuxe Demo Content', 'aqualuxe' ),
            __( 'Demo Content', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-content',
            array( __CLASS__, 'demo_content_page' )
        );
    }
    
    /**
     * Demo content page
     */
    public static function demo_content_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Demo Content', 'aqualuxe' ); ?></h1>
            
            <div class="aqualuxe-demo-content-wrapper">
                <div class="aqualuxe-demo-content-section">
                    <h2><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'This will import sample products, pages, and settings to help you get started with your AquaLuxe theme.', 'aqualuxe' ); ?></p>
                    
                    <button id="aqualuxe-import-demo" class="button button-primary">
                        <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                    </button>
                    
                    <div id="aqualuxe-import-progress" style="display: none; margin-top: 20px;">
                        <div class="spinner is-active"></div>
                        <span><?php esc_html_e( 'Importing demo content...', 'aqualuxe' ); ?></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-content-section">
                    <h2><?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'This will remove all existing content and reset to default settings. Use with caution!', 'aqualuxe' ); ?></p>
                    
                    <button id="aqualuxe-reset-content" class="button button-secondary">
                        <?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?>
                    </button>
                    
                    <div id="aqualuxe-reset-progress" style="display: none; margin-top: 20px;">
                        <div class="spinner is-active"></div>
                        <span><?php esc_html_e( 'Resetting content...', 'aqualuxe' ); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#aqualuxe-import-demo').click(function() {
                if (!confirm('<?php esc_html_e( 'Are you sure you want to import demo content? This will add sample products and pages to your site.', 'aqualuxe' ); ?>')) {
                    return;
                }
                
                $('#aqualuxe-import-progress').show();
                $(this).prop('disabled', true);
                
                $.post(ajaxurl, {
                    action: 'aqualuxe_import_demo_content',
                    nonce: '<?php echo wp_create_nonce( 'aqualuxe_demo_content_nonce' ); ?>'
                }, function(response) {
                    $('#aqualuxe-import-progress').hide();
                    $('#aqualuxe-import-demo').prop('disabled', false);
                    
                    if (response.success) {
                        alert('<?php esc_html_e( 'Demo content imported successfully!', 'aqualuxe' ); ?>');
                    } else {
                        alert('<?php esc_html_e( 'Error importing demo content: ', 'aqualuxe' ); ?>' + response.data);
                    }
                });
            });
            
            $('#aqualuxe-reset-content').click(function() {
                if (!confirm('<?php esc_html_e( 'Are you sure you want to reset all content? This cannot be undone.', 'aqualuxe' ); ?>')) {
                    return;
                }
                
                $('#aqualuxe-reset-progress').show();
                $(this).prop('disabled', true);
                
                $.post(ajaxurl, {
                    action: 'aqualuxe_reset_content',
                    nonce: '<?php echo wp_create_nonce( 'aqualuxe_reset_content_nonce' ); ?>'
                }, function(response) {
                    $('#aqualuxe-reset-progress').hide();
                    $('#aqualuxe-reset-content').prop('disabled', false);
                    
                    if (response.success) {
                        alert('<?php esc_html_e( 'Content reset successfully!', 'aqualuxe' ); ?>');
                    } else {
                        alert('<?php esc_html_e( 'Error resetting content: ', 'aqualuxe' ); ?>' + response.data);
                    }
                });
            });
        });
        </script>
        
        <style>
        .aqualuxe-demo-content-wrapper {
            max-width: 800px;
            margin-top: 20px;
        }
        
        .aqualuxe-demo-content-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .aqualuxe-demo-content-section h2 {
            margin-top: 0;
        }
        </style>
        <?php
    }
    
    /**
     * Import demo content
     */
    public static function import_demo_content() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_content_nonce' ) ) {
            wp_send_json_error( __( 'Invalid nonce.', 'aqualuxe' ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Insufficient permissions.', 'aqualuxe' ) );
        }
        
        // Import demo content
        try {
            self::import_demo_pages();
            self::import_demo_products();
            self::import_demo_menus();
            self::import_demo_widgets();
            
            wp_send_json_success( __( 'Demo content imported successfully!', 'aqualuxe' ) );
        } catch ( Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
    
    /**
     * Reset content
     */
    public static function reset_content() {
        // Verify nonce
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_reset_content_nonce' ) ) {
            wp_send_json_error( __( 'Invalid nonce.', 'aqualuxe' ) );
        }
        
        // Check permissions
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Insufficient permissions.', 'aqualuxe' ) );
        }
        
        // Reset content
        try {
            self::reset_pages();
            self::reset_products();
            self::reset_menus();
            self::reset_widgets();
            
            wp_send_json_success( __( 'Content reset successfully!', 'aqualuxe' ) );
        } catch ( Exception $e ) {
            wp_send_json_error( $e->getMessage() );
        }
    }
    
    /**
     * Import demo pages
     */
    private static function import_demo_pages() {
        // Home page
        $home_page_id = wp_insert_post( array(
            'post_title'   => __( 'Home', 'aqualuxe' ),
            'post_content' => self::get_home_page_content(),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        
        // About page
        $about_page_id = wp_insert_post( array(
            'post_title'   => __( 'About Us', 'aqualuxe' ),
            'post_content' => self::get_about_page_content(),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        
        // Contact page
        $contact_page_id = wp_insert_post( array(
            'post_title'   => __( 'Contact', 'aqualuxe' ),
            'post_content' => self::get_contact_page_content(),
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_author'  => 1,
        ) );
        
        // Set home page
        update_option( 'page_on_front', $home_page_id );
        update_option( 'show_on_front', 'page' );
        
        // Set posts page
        update_option( 'page_for_posts', $about_page_id );
    }
    
    /**
     * Import demo products
     */
    private static function import_demo_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        // Sample products
        $products = array(
            array(
                'name'        => __( 'Premium Goldfish', 'aqualuxe' ),
                'description' => __( 'Beautiful premium goldfish with vibrant colors and healthy fins.', 'aqualuxe' ),
                'price'       => '29.99',
                'sku'         => 'GF-001',
                'category'    => __( 'Goldfish', 'aqualuxe' ),
            ),
            array(
                'name'        => __( 'Rare Discus Fish', 'aqualuxe' ),
                'description' => __( 'Rare and exotic discus fish with unique patterns and colors.', 'aqualuxe' ),
                'price'       => '89.99',
                'sku'         => 'DS-001',
                'category'    => __( 'Discus', 'aqualuxe' ),
            ),
            array(
                'name'        => __( 'Tropical Angelfish', 'aqualuxe' ),
                'description' => __( 'Elegant angelfish with flowing fins and striking patterns.', 'aqualuxe' ),
                'price'       => '34.99',
                'sku'         => 'AF-001',
                'category'    => __( 'Angelfish', 'aqualuxe' ),
            ),
        );
        
        foreach ( $products as $product_data ) {
            // Create product
            $product_id = wp_insert_post( array(
                'post_title'   => $product_data['name'],
                'post_content' => $product_data['description'],
                'post_status'  => 'publish',
                'post_type'    => 'product',
                'post_author'  => 1,
            ) );
            
            // Set product meta
            update_post_meta( $product_id, '_price', $product_data['price'] );
            update_post_meta( $product_id, '_regular_price', $product_data['price'] );
            update_post_meta( $product_id, '_sku', $product_data['sku'] );
            update_post_meta( $product_id, '_manage_stock', 'no' );
            update_post_meta( $product_id, '_tax_status', 'taxable' );
            update_post_meta( $product_id, '_tax_class', '' );
            update_post_meta( $product_id, '_visibility', 'visible' );
            update_post_meta( $product_id, '_stock_status', 'instock' );
            
            // Set product category
            $category = wp_insert_term( $product_data['category'], 'product_cat' );
            if ( ! is_wp_error( $category ) ) {
                wp_set_post_terms( $product_id, array( $category['term_id'] ), 'product_cat' );
            }
            
            // Set product image placeholder
            update_post_meta( $product_id, '_thumbnail_id', 0 );
        }
    }
    
    /**
     * Import demo menus
     */
    private static function import_demo_menus() {
        // Create primary menu
        $menu_id = wp_create_nav_menu( __( 'Primary Menu', 'aqualuxe' ) );
        
        // Set menu locations
        $locations = get_theme_mod( 'nav_menu_locations' );
        $locations['primary'] = $menu_id;
        $locations['mobile'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
        
        // Add menu items
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'     => __( 'Home', 'aqualuxe' ),
            'menu-item-url'       => home_url( '/' ),
            'menu-item-status'    => 'publish',
            'menu-item-type'      => 'custom',
            'menu-item-object'    => 'custom',
        ) );
        
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'     => __( 'Shop', 'aqualuxe' ),
            'menu-item-url'       => get_permalink( wc_get_page_id( 'shop' ) ),
            'menu-item-status'    => 'publish',
            'menu-item-type'      => 'custom',
            'menu-item-object'    => 'custom',
        ) );
        
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'     => __( 'About Us', 'aqualuxe' ),
            'menu-item-url'       => get_permalink( get_page_by_title( 'About Us' )->ID ),
            'menu-item-status'    => 'publish',
            'menu-item-type'      => 'custom',
            'menu-item-object'    => 'custom',
        ) );
        
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'     => __( 'Contact', 'aqualuxe' ),
            'menu-item-url'       => get_permalink( get_page_by_title( 'Contact' )->ID ),
            'menu-item-status'    => 'publish',
            'menu-item-type'      => 'custom',
            'menu-item-object'    => 'custom',
        ) );
    }
    
    /**
     * Import demo widgets
     */
    private static function import_demo_widgets() {
        // Set up widget data
        $widget_data = array(
            'main-sidebar' => array(
                'search-2' => array(
                    'title' => __( 'Search', 'aqualuxe' ),
                ),
                'recent-posts-2' => array(
                    'title' => __( 'Recent Posts', 'aqualuxe' ),
                    'number' => 5,
                ),
            ),
            'shop-sidebar' => array(
                'woocommerce_product_categories-2' => array(
                    'title' => __( 'Product Categories', 'aqualuxe' ),
                ),
                'woocommerce_price_filter-2' => array(),
                'woocommerce_product_tag_cloud-2' => array(
                    'title' => __( 'Product Tags', 'aqualuxe' ),
                ),
            ),
            'footer-1' => array(
                'text-2' => array(
                    'title' => __( 'About AquaLuxe', 'aqualuxe' ),
                    'text' => __( 'Premium ornamental fish for discerning collectors. We specialize in rare and beautiful aquatic species.', 'aqualuxe' ),
                ),
            ),
            'footer-2' => array(
                'nav_menu-2' => array(
                    'title' => __( 'Quick Links', 'aqualuxe' ),
                    'nav_menu' => 0, // Will be set after menu creation
                ),
            ),
            'footer-3' => array(
                'text-3' => array(
                    'title' => __( 'Contact Info', 'aqualuxe' ),
                    'text' => __( '123 Aquarium Street
Fishville, FV 12345
Phone: (123) 456-7890
Email: info@aqualuxe.com', 'aqualuxe' ),
                ),
            ),
            'footer-4' => array(
                'text-4' => array(
                    'title' => __( 'Newsletter', 'aqualuxe' ),
                    'text' => __( 'Subscribe to our newsletter for updates on new arrivals and special offers.', 'aqualuxe' ),
                ),
            ),
        );
        
        // Update widget options
        foreach ( $widget_data as $sidebar_id => $widgets ) {
            $sidebar_widgets = array();
            
            foreach ( $widgets as $widget_id => $widget_options ) {
                // Get widget type
                $widget_type = explode( '-', $widget_id );
                $widget_type = $widget_type[0];
                
                // Get current widget options
                $options = get_option( 'widget_' . $widget_type, array() );
                
                // Add new widget options
                $widget_key = 0;
                foreach ( $options as $key => $value ) {
                    if ( is_int( $key ) ) {
                        $widget_key = $key;
                    }
                }
                
                $widget_key++;
                $options[$widget_key] = $widget_options;
                update_option( 'widget_' . $widget_type, $options );
                
                // Add to sidebar
                $sidebar_widgets[] = $widget_id;
            }
            
            // Update sidebar widgets
            update_option( 'sidebars_widgets', array_merge( get_option( 'sidebars_widgets', array() ), array( $sidebar_id => $sidebar_widgets ) ) );
        }
    }
    
    /**
     * Reset pages
     */
    private static function reset_pages() {
        $pages = get_pages();
        foreach ( $pages as $page ) {
            wp_delete_post( $page->ID, true );
        }
    }
    
    /**
     * Reset products
     */
    private static function reset_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        $products = get_posts( array(
            'post_type'   => 'product',
            'numberposts' => -1,
            'post_status' => 'any',
        ) );
        
        foreach ( $products as $product ) {
            wp_delete_post( $product->ID, true );
        }
    }
    
    /**
     * Reset menus
     */
    private static function reset_menus() {
        $menus = wp_get_nav_menus();
        foreach ( $menus as $menu ) {
            wp_delete_nav_menu( $menu->term_id );
        }
    }
    
    /**
     * Reset widgets
     */
    private static function reset_widgets() {
        update_option( 'sidebars_widgets', array() );
        
        // Reset widget options
        $widget_types = array( 'search', 'recent-posts', 'text', 'nav_menu', 'woocommerce_product_categories', 'woocommerce_price_filter', 'woocommerce_product_tag_cloud' );
        foreach ( $widget_types as $widget_type ) {
            update_option( 'widget_' . $widget_type, array() );
        }
    }
    
    /**
     * Get home page content
     */
    private static function get_home_page_content() {
        return '<!-- wp:cover {"url":"' . get_stylesheet_directory_uri() . '/assets/images/hero-bg.jpg","id":0,"dimRatio":50,"overlayColor":"black","focalPoint":{"x":0.5,"y":0.5},"minHeight":600,"minHeightUnit":"px","contentPosition":"center center","align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:600px"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="' . get_stylesheet_directory_uri() . '/assets/images/hero-bg.jpg" style="object-position:50% 50%" data-object-fit="cover" data-object-position="50% 50%"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"48px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<h1 class="has-text-align-center has-white-color has-text-color has-link-color" style="font-size:48px">Premium Ornamental Fish</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"20px"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color has-link-color" style="font-size:20px">Discover our exquisite collection of rare and beautiful aquatic species</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"primary","style":{"border":{"radius":"4px"}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-primary-background-color has-background" style="border-radius:4px">Shop Collection</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"backgroundColor":"light-gray","layout":{"inherit":true}} -->
<div class="wp-block-group alignfull has-light-gray-background-color has-background" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Featured Products</h2>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-new {"columns":4,"rows":1} /--></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Why Choose AquaLuxe?</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":0,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full"><img src="' . get_stylesheet_directory_uri() . '/assets/images/icon-quality.png" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Premium Quality</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">All our fish are carefully selected and bred for optimal health and beauty.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":0,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full"><img src="' . get_stylesheet_directory_uri() . '/assets/images/icon-expertise.png" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Expert Care</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Our team of aquatic specialists ensures the highest standards of care.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:image {"align":"center","id":0,"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image aligncenter size-full"><img src="' . get_stylesheet_directory_uri() . '/assets/images/icon-shipping.png" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Safe Shipping</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">We use specialized packaging and shipping methods to ensure safe delivery.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';
    }
    
    /**
     * Get about page content
     */
    private static function get_about_page_content() {
        return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">About AquaLuxe</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Founded in 2010, AquaLuxe has been at the forefront of the ornamental fish industry, specializing in rare and exotic aquatic species. Our passion for aquatic life and commitment to excellence has made us a trusted name among collectors and enthusiasts worldwide.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>We work directly with breeders and aquaculture facilities around the globe to source the finest specimens. Each fish in our collection is carefully selected for its health, genetic quality, and aesthetic appeal.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Our Mission</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>At AquaLuxe, our mission is to promote the responsible keeping and breeding of ornamental fish while providing our customers with exceptional service and the highest quality specimens. We believe that every aquarium should be a work of art, and we are dedicated to helping our customers create stunning aquatic displays.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Quality Assurance</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All our fish undergo rigorous health screening and quarantine procedures before being offered for sale. We maintain detailed records of lineage, health history, and care requirements for each specimen to ensure our customers receive accurate information and healthy fish.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->';
    }
    
    /**
     * Get contact page content
     */
    private static function get_contact_page_content() {
        return '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"inherit":true}} -->
<div class="wp-block-group alignfull" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">Contact Us</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:heading {"level":3} -->
<h3>Get in Touch</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We\'d love to hear from you! Whether you have questions about our fish, need care advice, or want to place a special order, our team is here to help.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Address:</strong><br>123 Aquarium Street<br>Fishville, FV 12345</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Phone:</strong><br>(123) 456-7890</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Email:</strong><br>info@aqualuxe.com</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Hours:</strong><br>Monday-Friday: 9am-6pm<br>Saturday: 10am-4pm<br>Sunday: Closed</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:heading {"level":3} -->
<h3>Send us a Message</h3>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[contact-form-7 id="123" title="Contact form 1"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->';
    }
}

// Initialize
AquaLuxe_Demo_Content::init();