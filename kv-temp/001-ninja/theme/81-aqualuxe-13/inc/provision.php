<?php
/** Site provisioning: pages, menus, assignments */
if (!defined('ABSPATH')) { exit; }

// Helper: create or fetch a page by slug
function aqualuxe_provision_get_or_create_page($title, $slug, $content = '') {
    $existing = get_page_by_path($slug);
    if ($existing && $existing->ID) { return (int) $existing->ID; }
    $id = wp_insert_post([
        'post_type'   => 'page',
        'post_title'  => $title,
        'post_name'   => $slug,
        'post_status' => 'publish',
        'post_content'=> $content,
    ]);
    return (int) $id;
}

// Helper: create/get a nav menu by name
function aqualuxe_provision_get_or_create_menu($name) {
    $menu = wp_get_nav_menu_object($name);
    if ($menu && !is_wp_error($menu)) { return (int) $menu->term_id; }
    $id = wp_create_nav_menu($name);
    return (int) $id;
}

// Helper: ensure a menu item exists (by title to a page ID)
function aqualuxe_provision_menu_item($menu_id, $title, $url_or_page_id, $position = 0) {
    $items = wp_get_nav_menu_items($menu_id);
    $match = null; $url = '';
    if (is_numeric($url_or_page_id)) { $url = get_permalink((int)$url_or_page_id); }
    else { $url = esc_url_raw($url_or_page_id); }
    if (is_array($items)) {
        foreach ($items as $it) {
            if ($it->title === $title) { $match = $it; break; }
        }
    }
    if ($match) { return (int) $match->ID; }
    $args = [
        'menu-item-title'     => $title,
        'menu-item-url'       => $url,
        'menu-item-status'    => 'publish',
    ];
    if (is_numeric($url_or_page_id)) {
        $args['menu-item-object-id'] = (int) $url_or_page_id;
        $args['menu-item-object'] = 'page';
        $args['menu-item-type']   = 'post_type';
    } else {
        $args['menu-item-type']   = 'custom';
    }
    return (int) wp_update_nav_menu_item($menu_id, 0, $args);
}

// Core provisioning routine (idempotent)
function aqualuxe_provision_site() {
    if (!current_user_can('manage_options')) { return new WP_Error('forbidden','Insufficient permissions'); }

    // Create key pages
    $pages = [];
    $pages['home']      = aqualuxe_provision_get_or_create_page(__('Home','aqualuxe'), 'home', '<!-- wp:heading --><h2>' . esc_html__('Welcome to AquaLuxe','aqualuxe') . "</h2><!-- /wp:heading -->\n<!-- wp:paragraph --><p>" . esc_html__('Premium ornamental fish, plants, and custom aquariums.','aqualuxe') . "</p><!-- /wp:paragraph -->");
    $pages['about']     = aqualuxe_provision_get_or_create_page(__('About Us','aqualuxe'), 'about-us');
    $pages['shop']      = aqualuxe_provision_get_or_create_page(__('Shop','aqualuxe'), 'shop');
    $pages['wholesale'] = aqualuxe_provision_get_or_create_page(__('Wholesale & B2B','aqualuxe'), 'wholesale-b2b');
    $pages['trade']     = aqualuxe_provision_get_or_create_page(__('Buy, Sell & Trade','aqualuxe'), 'buy-sell-trade');
    $pages['services']  = aqualuxe_provision_get_or_create_page(__('Services','aqualuxe'), 'services');
    $pages['export']    = aqualuxe_provision_get_or_create_page(__('Export','aqualuxe'), 'export');
    $pages['events']    = aqualuxe_provision_get_or_create_page(__('Events & Experiences','aqualuxe'), 'events-experiences');
    $pages['blog']      = aqualuxe_provision_get_or_create_page(__('Learning Hub','aqualuxe'), 'learning-hub');
    $pages['contact']   = aqualuxe_provision_get_or_create_page(__('Contact Us','aqualuxe'), 'contact');
    $pages['account']   = aqualuxe_provision_get_or_create_page(__('My Account','aqualuxe'), 'my-account');

    // Legal pages
    $pages['privacy']   = aqualuxe_provision_get_or_create_page(__('Privacy Policy','aqualuxe'), 'privacy-policy');
    $pages['terms']     = aqualuxe_provision_get_or_create_page(__('Terms & Conditions','aqualuxe'), 'terms-conditions');
    $pages['shipping']  = aqualuxe_provision_get_or_create_page(__('Shipping & Return Policy','aqualuxe'), 'shipping-returns');
    $pages['exporttc']  = aqualuxe_provision_get_or_create_page(__('Export & Import Terms','aqualuxe'), 'export-import-terms');
    $pages['cookies']   = aqualuxe_provision_get_or_create_page(__('Cookie Policy','aqualuxe'), 'cookie-policy');

    // Assign front page and posts page
    update_option('show_on_front', 'page');
    update_option('page_on_front', (int)$pages['home']);
    update_option('page_for_posts', (int)$pages['blog']);

    // If WooCommerce exists, map Shop page to Woo setting
    if (class_exists('WooCommerce')) {
        update_option('woocommerce_shop_page_id', (int)$pages['shop']);
    }

    // Create menus and assign to locations
    $primary_id = aqualuxe_provision_get_or_create_menu(__('Primary','aqualuxe'));
    $footer_id  = aqualuxe_provision_get_or_create_menu(__('Footer','aqualuxe'));
    $account_id = aqualuxe_provision_get_or_create_menu(__('Account','aqualuxe'));

    // Build Primary menu
    aqualuxe_provision_menu_item($primary_id, __('Shop','aqualuxe'),      $pages['shop']);
    aqualuxe_provision_menu_item($primary_id, __('Services','aqualuxe'),  $pages['services']);
    aqualuxe_provision_menu_item($primary_id, __('Wholesale & B2B','aqualuxe'), $pages['wholesale']);
    aqualuxe_provision_menu_item($primary_id, __('Buy, Sell & Trade','aqualuxe'), $pages['trade']);
    aqualuxe_provision_menu_item($primary_id, __('Export','aqualuxe'),    $pages['export']);
    aqualuxe_provision_menu_item($primary_id, __('Events','aqualuxe'),    $pages['events']);
    aqualuxe_provision_menu_item($primary_id, __('Learning Hub','aqualuxe'), $pages['blog']);
    aqualuxe_provision_menu_item($primary_id, __('About Us','aqualuxe'),  $pages['about']);
    aqualuxe_provision_menu_item($primary_id, __('Contact','aqualuxe'),   $pages['contact']);

    // Footer menu (legal)
    aqualuxe_provision_menu_item($footer_id,  __('Privacy Policy','aqualuxe'),        $pages['privacy']);
    aqualuxe_provision_menu_item($footer_id,  __('Terms & Conditions','aqualuxe'),    $pages['terms']);
    aqualuxe_provision_menu_item($footer_id,  __('Shipping & Returns','aqualuxe'),    $pages['shipping']);
    aqualuxe_provision_menu_item($footer_id,  __('Export & Import Terms','aqualuxe'), $pages['exporttc']);
    aqualuxe_provision_menu_item($footer_id,  __('Cookie Policy','aqualuxe'),         $pages['cookies']);

    // Account menu
    aqualuxe_provision_menu_item($account_id, __('My Account','aqualuxe'), $pages['account']);
    aqualuxe_provision_menu_item($account_id, __('Wholesale Portal','aqualuxe'), get_permalink($pages['wholesale']));
    aqualuxe_provision_menu_item($account_id, __('Service Bookings','aqualuxe'), get_permalink($pages['services']).'#book');
    aqualuxe_provision_menu_item($account_id, __('Trade-In Submissions','aqualuxe'), get_permalink($pages['trade']).'#submit');

    // Assign menu locations
    $locations = get_theme_mod('nav_menu_locations');
    if (!is_array($locations)) { $locations = []; }
    $locations['primary'] = (int) $primary_id;
    $locations['footer']  = (int) $footer_id;
    $locations['account'] = (int) $account_id;
    set_theme_mod('nav_menu_locations', $locations);

    // Done
    return [ 'pages' => $pages, 'menus' => [ 'primary'=>$primary_id, 'footer'=>$footer_id, 'account'=>$account_id ] ];
}

// Admin action handler
add_action('admin_post_aqualuxe_provision_run', function(){
    if (!current_user_can('manage_options')) { wp_die(__('Insufficient permissions','aqualuxe')); }
    check_admin_referer('aqualuxe_provision');
    $res = aqualuxe_provision_site();
    $ok = !is_wp_error($res);
    $qs = $ok ? 'status=success' : 'status=error';
    wp_safe_redirect( add_query_arg($qs, admin_url('admin.php?page=aqualuxe-provision')) );
    exit;
});

// Admin notice
add_action('admin_notices', function(){
    if (!isset($_GET['page']) || $_GET['page'] !== 'aqualuxe-provision') return;
    if (!isset($_GET['status'])) return;
    $class = $_GET['status'] === 'success' ? 'updated' : 'error';
    $msg = $_GET['status'] === 'success' ? __('Provisioning completed.','aqualuxe') : __('Provisioning failed. Check logs.','aqualuxe');
    echo '<div class="notice '.$class.' is-dismissible"><p>'.esc_html($msg).'</p></div>';
});
