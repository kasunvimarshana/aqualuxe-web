<?php
namespace AquaLuxe;

class Admin {
    public static function init(): void {
        \add_action('admin_menu', [self::class, 'menu']);
        \add_action('admin_post_aqualuxe_import_demo', [self::class, 'import_demo']);
    }

    public static function menu(): void {
        \add_theme_page(\__('AquaLuxe Setup', 'aqualuxe'), \__('AquaLuxe Setup', 'aqualuxe'), 'manage_options', 'aqualuxe-setup', [self::class, 'render']);
    }

    public static function render(): void {
        if (!\current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>'.\esc_html__('AquaLuxe Setup', 'aqualuxe').'</h1>';
        echo '<p>'.\esc_html__('Import demo content and assign menus/pages.', 'aqualuxe').'</p>';
        echo '<form method="post" action="'.\esc_url(\admin_url('admin-post.php')).'">';
        \wp_nonce_field('aqualuxe_demo');
        echo '<input type="hidden" name="action" value="aqualuxe_import_demo" />';
        \submit_button(\__('Import Demo', 'aqualuxe'));
        echo '</form></div>';
    }

    public static function import_demo(): void {
        \check_admin_referer('aqualuxe_demo');
        $pages = [
            'Home' => ['post_content' => '[aqualuxe_home]'],
            'About' => ['post_content' => 'Bringing elegance to aquatic life – globally.'],
            'Services' => ['post_content' => '[aqualuxe_services]'],
            'Blog' => [],
            'Contact' => ['post_content' => '[aqualuxe_contact]'],
            'FAQ' => [],
            'Privacy Policy' => [],
            'Terms & Conditions' => [],
            'Shipping & Returns' => [],
            'Cookie Policy' => [],
        ];
        $created = [];
        foreach ($pages as $title => $args) {
            $page = \get_page_by_title($title);
            if (!$page) {
                $id = \wp_insert_post(array_merge(['post_title' => $title, 'post_type' => 'page', 'post_status' => 'publish'], $args));
                if ($title === 'Home') \update_option('page_on_front', $id);
                $created[] = $title;
            }
        }
        \update_option('show_on_front', 'page');
        $menu = \wp_get_nav_menu_object('Primary');
        if (!$menu) { $menu_id = \wp_create_nav_menu('Primary'); } else { $menu_id = $menu->term_id; }
        $home = \get_page_by_title('Home');
        if ($menu_id && $home) {
            if (!\wp_get_nav_menu_items($menu_id)) {
                \wp_update_nav_menu_item($menu_id, 0, ['menu-item-title' => 'Home', 'menu-item-object' => 'page', 'menu-item-object-id' => $home->ID, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish']);
            }
            \set_theme_mod('nav_menu_locations', ['primary' => $menu_id]);
        }
        if (\function_exists('wc_get_page_id')) {
            $cats = ['Rare Fish Species','Aquatic Plants','Premium Equipment','Care Supplies'];
            foreach ($cats as $c) {
                if (!\term_exists($c, 'product_cat')) { \wp_insert_term($c, 'product_cat'); }
            }
        }
        \wp_safe_redirect(\add_query_arg(['imported' => 1], \wp_get_referer() ?: \admin_url('themes.php')));
        exit;
    }
}

Admin::init();
