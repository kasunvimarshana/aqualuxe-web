<?php
// Custom roles and granular capabilities with filters for extensibility.
// Idempotent: safe on repeated loads. Remove cautiously only via plugin/migration.

add_action('init', function(){
    $defaults = [
        'vendor' => [
            'label' => 'Vendor',
            'caps'  => [
                'read' => true,
                'upload_files' => true,
                // Custom capability examples
                'manage_vendor_products' => true,
                'manage_vendor_orders'   => true,
            ],
        ],
        'b2b_customer' => [
            'label' => 'B2B Customer',
            'caps'  => [ 'read' => true ]
        ],
        'franchise_partner' => [
            'label' => 'Franchise Partner',
            'caps'  => [ 'read' => true, 'upload_files' => true, 'edit_pages' => true ]
        ],
    ];
    $roles = apply_filters('aqualuxe_roles', $defaults);
    foreach ( (array)$roles as $slug => $def ) {
        $label = isset($def['label']) ? (string)$def['label'] : ucwords(str_replace('_',' ',$slug));
        $caps  = isset($def['caps']) && is_array($def['caps']) ? $def['caps'] : [];
        $role  = get_role($slug);
        if ( ! $role ) {
            add_role( $slug, $label, $caps );
        } else {
            // Ensure required caps exist
            foreach ( $caps as $c => $grant ) { if ( $grant ) { $role->add_cap( $c ); } }
        }
    }
});

// Example: map a meta capability to core ones; extend via filter if needed.
add_filter('map_meta_cap', function( $caps, $cap, $user_id, $args ){
    if ( $cap === 'manage_vendor_content' ) {
        // Grant if user has our custom caps or is admin
        $user = get_userdata( $user_id );
        if ( $user && ( user_can($user, 'manage_vendor_products') || user_can($user, 'manage_options') ) ) {
            return [ 'exist' ];
        }
        return [ 'do_not_allow' ];
    }
    return $caps;
}, 10, 4);
