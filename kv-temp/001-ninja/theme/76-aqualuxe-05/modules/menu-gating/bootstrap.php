<?php
defined('ABSPATH') || exit;

// Helpers
if (!function_exists('aqlx_user_has_role')) {
    function aqlx_user_has_role($roles): bool
    {
        if (!is_user_logged_in()) return false;
        $user = wp_get_current_user();
        $roles = is_array($roles) ? $roles : [$roles];
        return (bool) array_intersect((array) $user->roles, $roles);
    }
}

// Menu gating via XFN or CSS class markers
// Mark menu items:
//  - logged-in-only:   Add CSS class `aqlx-logged-in-only` OR XFN contains `logged-in`
//  - guest-only:       Add CSS class `aqlx-guest-only` OR XFN contains `logged-out`
//  - b2b-only:         Add CSS class `aqlx-b2b-only` OR XFN contains `b2b`
// B2B roles default: ['wholesale_customer','shop_manager'] (filter: aqlx_b2b_roles)
add_filter('wp_nav_menu_objects', function ($items) {
    if (!is_array($items)) return $items;
    $b2b_roles = apply_filters('aqlx_b2b_roles', ['wholesale_customer', 'shop_manager']);
    $out = [];
    foreach ($items as $item) {
        $classes = is_array($item->classes) ? $item->classes : [];
        $xfn = preg_split('/\s+/', trim((string) $item->xfn)) ?: [];
        $is_logged_in_only = in_array('aqlx-logged-in-only', $classes, true) || in_array('logged-in', $xfn, true);
        $is_guest_only     = in_array('aqlx-guest-only', $classes, true) || in_array('logged-out', $xfn, true);
        $is_b2b_only       = in_array('aqlx-b2b-only', $classes, true) || in_array('b2b', $xfn, true);

        // Apply rules
        if ($is_logged_in_only && !is_user_logged_in()) continue;
        if ($is_guest_only && is_user_logged_in()) continue;
        if ($is_b2b_only && !aqlx_user_has_role($b2b_roles)) continue;

        $out[] = $item;
    }
    return $out;
}, 20);
