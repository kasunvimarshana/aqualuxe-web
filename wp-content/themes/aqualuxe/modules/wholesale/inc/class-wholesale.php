<?php
/**
 * Wholesale/B2B Module Main Class
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Wholesale_Module {
    public function __construct() {
        add_action( 'init', [ $this, 'register_roles' ] );
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_pricing_fields' ] );
    }

    public function register_roles() {
        add_role( 'wholesale_customer', __( 'Wholesale Customer', 'aqualuxe' ), [
            'read' => true,
            'edit_posts' => false,
        ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Wholesale Products', 'aqualuxe' ),
            'singular_name' => __( 'Wholesale Product', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Wholesale Product', 'aqualuxe' ),
            'edit_item' => __( 'Edit Wholesale Product', 'aqualuxe' ),
            'new_item' => __( 'New Wholesale Product', 'aqualuxe' ),
            'view_item' => __( 'View Wholesale Product', 'aqualuxe' ),
            'search_items' => __( 'Search Wholesale Products', 'aqualuxe' ),
            'not_found' => __( 'No wholesale products found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No wholesale products found in Trash', 'aqualuxe' ),
            'menu_name' => __( 'Wholesale', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'has_archive' => false,
            'rewrite' => [ 'slug' => 'wholesale-products' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-cart',
        ];
        register_post_type( 'aqualuxe_wholesale_product', $args );
    }

    public function register_pricing_fields() {
        // Placeholder for custom fields (e.g., ACF or custom meta boxes for wholesale pricing)
    }
}
