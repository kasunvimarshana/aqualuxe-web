<?php
/**
 * Affiliate/Referrals Module Main Class
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Affiliate_Module {
    public function __construct() {
        add_action( 'init', [ $this, 'register_roles' ] );
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_tracking_fields' ] );
    }

    public function register_roles() {
        add_role( 'affiliate_user', __( 'Affiliate User', 'aqualuxe' ), [
            'read' => true,
            'edit_posts' => false,
        ] );
    }

    public function register_post_type() {
        $labels = [
            'name' => __( 'Affiliates', 'aqualuxe' ),
            'singular_name' => __( 'Affiliate', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Affiliate', 'aqualuxe' ),
            'edit_item' => __( 'Edit Affiliate', 'aqualuxe' ),
            'new_item' => __( 'New Affiliate', 'aqualuxe' ),
            'view_item' => __( 'View Affiliate', 'aqualuxe' ),
            'search_items' => __( 'Search Affiliates', 'aqualuxe' ),
            'not_found' => __( 'No affiliates found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No affiliates found in Trash', 'aqualuxe' ),
            'menu_name' => __( 'Affiliates', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'has_archive' => false,
            'rewrite' => [ 'slug' => 'affiliates' ],
            'supports' => [ 'title', 'editor', 'custom-fields' ],
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-networking',
        ];
        register_post_type( 'aqualuxe_affiliate', $args );
    }

    public function register_tracking_fields() {
        // Placeholder for custom fields (e.g., referral tracking, commission, dashboard)
    }
}
