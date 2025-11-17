<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Admin {
    public static function boot(): void {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_post_aqlx_save_modules', [__CLASS__, 'save']);
        add_filter('aqualuxe/config', [__CLASS__, 'merge_config']);
    }

    public static function menu(): void {
        add_theme_page(__('AquaLuxe Modules','aqualuxe'), __('AquaLuxe Modules','aqualuxe'), 'manage_options', 'aqlx-modules', [__CLASS__, 'page']);
    }

    public static function page(): void {
        if (!current_user_can('manage_options')) return;
        $conf = Config::instance();
        $all = [
            'AquaLuxe\\Modules\\DarkMode\\Module' => 'Dark Mode',
            'AquaLuxe\\Modules\\Multilingual\\Module' => 'Multilingual',
            'AquaLuxe\\Modules\\DemoImporter\\Module' => 'Demo Importer',
            'AquaLuxe\\Modules\\Woo\\Module' => 'WooCommerce Enhancements',
            'AquaLuxe\\Modules\\Wishlist\\Module' => 'Wishlist',
            'AquaLuxe\\Modules\\QuickView\\Module' => 'Quick View',
            'AquaLuxe\\Modules\\Filtering\\Module' => 'Shop Filters',
            'AquaLuxe\\Modules\\MultiCurrency\\Module' => 'Multi Currency',
            'AquaLuxe\\Modules\\Subscriptions\\Module' => 'Subscriptions',
            'AquaLuxe\\Modules\\Events\\Module' => 'Events',
            'AquaLuxe\\Modules\\Services\\Module' => 'Services',
        ];
        $saved = get_option('aqualuxe_modules', []);
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Modules','aqualuxe') . '</h1>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_save_modules');
        echo '<input type="hidden" name="action" value="aqlx_save_modules" />';
        echo '<table class="form-table"><tbody>';
        foreach ($all as $class => $label) {
            $on = isset($saved[$class]) ? (bool)$saved[$class] : $conf->is_module_enabled($class);
            echo '<tr><th scope="row">' . esc_html($label) . '</th><td>';
            echo '<label><input type="checkbox" name="modules[' . esc_attr($class) . ']" value="1" ' . checked($on, true, false) . ' /> ' . esc_html__('Enabled','aqualuxe') . '</label>';
            echo '</td></tr>';
        }
        echo '</tbody></table>';
        submit_button(__('Save Changes','aqualuxe'));
        echo '</form></div>';
    }

    public static function save(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
        check_admin_referer('aqlx_save_modules');
        $mods = isset($_POST['modules']) && is_array($_POST['modules']) ? array_map('boolval', $_POST['modules']) : [];
        update_option('aqualuxe_modules', $mods);
        wp_safe_redirect(admin_url('themes.php?page=aqlx-modules&updated=1'));
        exit;
    }

    public static function merge_config(array $defaults): array {
        $saved = get_option('aqualuxe_modules', []);
        if (!empty($saved) && isset($defaults['modules'])) {
            foreach ($defaults['modules'] as $class => $on) {
                if (isset($saved[$class])) {
                    $defaults['modules'][$class] = (bool)$saved[$class];
                }
            }
        }
        return $defaults;
    }
}
