<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    add_theme_page(__('AquaLuxe Demo Import', 'aqualuxe'), __('Demo Import', 'aqualuxe'), 'manage_options', 'aqlx-demo', function () {
        if (!current_user_can('manage_options')) return;
        if (isset($_POST['aqlx_import']) && check_admin_referer('aqlx_demo')) {
            aqlx_run_demo_import();
            echo '<div class="notice notice-success"><p>' . esc_html__('Demo content imported.', 'aqualuxe') . '</p></div>';
        }
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Demo Import', 'aqualuxe') . '</h1>';
        echo '<form method="post">';
        wp_nonce_field('aqlx_demo');
        submit_button(__('Import Demo', 'aqualuxe'), 'primary', 'aqlx_import');
        echo '</form></div>';
    });
});

function aqlx_run_demo_import(): void
{
    $file = AQUALUXE_PATH . 'demo/demo.json';
    if (!file_exists($file)) return;
    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) return;

    $created = [];
    foreach ($data['pages'] as $page) {
        $exists = get_page_by_path($page['slug']);
        if ($exists) { $created[$page['slug']] = $exists->ID; continue; }
        $post_id = wp_insert_post([
            'post_title' => sanitize_text_field($page['title']),
            'post_name'  => sanitize_title($page['slug']),
            'post_type'  => 'page',
            'post_status'=> 'publish',
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            if (!empty($page['template'])) {
                update_post_meta($post_id, '_wp_page_template', $page['template']);
            }
            $created[$page['slug']] = $post_id;
        }
    }

    // Menus
    foreach (['primary','footer'] as $loc) {
        if (empty($data['menus'][$loc])) continue;
        $menu_name = 'AquaLuxe ' . ucfirst($loc);
        $menu_id = wp_create_nav_menu($menu_name);
        foreach ($data['menus'][$loc] as $slug) {
            if (!isset($created[$slug])) continue;
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-object-id' => $created[$slug],
                'menu-item-object' => 'page',
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish',
            ]);
        }
        $locations = get_theme_mod('nav_menu_locations');
        if (!is_array($locations)) $locations = [];
        $locations[$loc] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Set homepage
    if (isset($created['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $created['home']);
    }
    // Set posts page if Blog exists
    if (isset($created['blog'])) {
        update_option('page_for_posts', (int) $created['blog']);
    }

    // WooCommerce categories
    if (class_exists('WooCommerce')) {
        $cats = [
            'rare-fish' => __('Rare Fish Species', 'aqualuxe'),
            'aquatic-plants' => __('Aquatic Plants', 'aqualuxe'),
            'premium-equipment' => __('Premium Equipment', 'aqualuxe'),
            'care-supplies' => __('Care Supplies', 'aqualuxe'),
        ];
        foreach ($cats as $slug => $name) {
            if (!term_exists($name, 'product_cat')) {
                wp_insert_term($name, 'product_cat', ['slug' => $slug]);
            }
        }
    }
}
