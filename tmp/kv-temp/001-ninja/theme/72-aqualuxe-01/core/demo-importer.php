<?php
namespace AquaLuxe\Core;

class Demo_Importer {
    public static function register_page(): void {
        add_submenu_page('themes.php', __('AquaLuxe Demo Importer','aqualuxe'), __('Demo Importer','aqualuxe'), 'manage_options', 'aqlx-demo', [__CLASS__, 'render']);
        add_action('admin_post_aqlx_import_content', [__CLASS__, 'handle_import']);
    }

    public static function render(): void {
        if (!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Demo Importer','aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Import demo pages, menus, and settings. This will create content if not present.','aqualuxe') . '</p>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_import','aqlx_nonce');
        echo '<input type="hidden" name="action" value="aqlx_import_content" />';
        submit_button(__('Run Import','aqualuxe'));
        echo '</form></div>';
    }

    public static function handle_import(): void {
        if (!current_user_can('manage_options')) wp_die('');
        check_admin_referer('aqlx_demo_import','aqlx_nonce');

        // Minimal importer: create core pages if missing
        $pages = [
            'Home' => 'home',
            'About' => 'about',
            'Services' => 'services',
            'Blog' => 'blog',
            'Contact' => 'contact',
            'FAQ' => 'faq',
            'Privacy Policy' => 'privacy-policy',
            'Terms & Conditions' => 'terms',
            'Shipping & Returns' => 'shipping-returns',
            'Cookie Policy' => 'cookies',
        ];
        $ids = [];
        foreach ($pages as $title => $slug) {
            $post = get_page_by_path($slug);
            if (!$post) {
                $ids[$slug] = wp_insert_post([
                    'post_title' => $title,
                    'post_name'  => $slug,
                    'post_status'=> 'publish',
                    'post_type'  => 'page',
                    'post_content' => ($slug==='home') ? __('Welcome to AquaLuxe.','aqualuxe') : '',
                ]);
            } else { $ids[$slug] = $post->ID; }
        }
        // Set homepage
        if (!empty($ids['home'])) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $ids['home']);
        }
        // Assign templates
        if (!empty($ids['contact'])) {
            update_post_meta($ids['contact'], '_wp_page_template', 'templates/contact-map.php');
        }

        // Menus
        $primary_menu = wp_get_nav_menu_object('Primary');
        if (!$primary_menu) {
            $menu_id = wp_create_nav_menu('Primary');
            // Add items
            $to_add = ['home','shop','services','blog','contact'];
            foreach ($to_add as $slug) {
                $obj_id = $ids[$slug] ?? 0;
                if ($slug==='shop' && class_exists('WooCommerce')) { $obj_id = wc_get_page_id('shop'); }
                if ($obj_id && $obj_id > 0) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => ucfirst($slug),
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $obj_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                    ]);
                }
            }
            set_theme_mod('nav_menu_locations', array_merge((array) get_theme_mod('nav_menu_locations'), ['primary' => $menu_id]));
        }

        // WooCommerce seed categories
        if (class_exists('WooCommerce')) {
            $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
            foreach ($cats as $name) {
                if (!term_exists($name, 'product_cat')) {
                    wp_insert_term($name, 'product_cat');
                }
            }
        }

        wp_safe_redirect(add_query_arg(['page'=>'aqlx-demo','imported'=>1], admin_url('themes.php')));
        exit;
    }
}
