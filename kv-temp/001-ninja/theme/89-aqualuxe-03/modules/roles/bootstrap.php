<?php
/** Roles & Capabilities module */
namespace AquaLuxe\Modules\Roles;
if (!defined('ABSPATH')) { exit; }

// Define/default roles via filter
function role_definitions(): array {
    $defs = [
        'alx_vendor' => [
            'label' => __('Vendor','aqualuxe'),
            'caps' => [
                'read' => true,
                'upload_files' => true,
                'edit_products' => true,
                'publish_products' => true,
                'edit_published_products' => true,
            ],
        ],
        'alx_moderator' => [
            'label' => __('Moderator','aqualuxe'),
            'caps' => [
                'read' => true,
                'edit_posts' => true,
                'edit_others_posts' => true,
                'edit_published_posts' => true,
                'moderate_comments' => true,
            ],
        ],
        'alx_support' => [
            'label' => __('Support','aqualuxe'),
            'caps' => [
                'read' => true,
                'edit_posts' => false,
                'read_private_pages' => true,
                'read_private_posts' => true,
            ],
        ],
    ];
    if (\function_exists('apply_filters')) {
        $defs = (array) \call_user_func('apply_filters','aqualuxe/roles/definitions',$defs);
    }
    return $defs;
}

\add_action('init', function(){
    $defs = role_definitions();
    foreach ($defs as $key => $conf) {
        $key = sanitize_key($key);
        $label = isset($conf['label']) ? (string) $conf['label'] : ucfirst($key);
        $caps = isset($conf['caps']) && is_array($conf['caps']) ? $conf['caps'] : [];
        $role = \get_role($key);
        if (!$role) {
            \add_role($key, $label, []);
            $role = \get_role($key);
        }
        if ($role) {
            foreach ($caps as $cap => $grant) {
                if ($grant) { $role->add_cap($cap); } else { $role->remove_cap($cap); }
            }
        }
    }
});

// Woo-specific extra caps when Woo is active
\add_action('init', function(){
    if (!\class_exists('WooCommerce')) { return; }
    $vendor = \get_role('alx_vendor');
    if ($vendor) {
        foreach (['manage_woocommerce','view_woocommerce_reports'] as $cap) { $vendor->add_cap($cap); }
    }
}, 20);
