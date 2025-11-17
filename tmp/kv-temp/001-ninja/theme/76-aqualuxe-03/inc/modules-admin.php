<?php
defined('ABSPATH') || exit;

add_action('admin_menu', function () {
    add_theme_page(__('AquaLuxe Modules', 'aqualuxe'), __('Modules', 'aqualuxe'), 'manage_options', 'aqlx-modules', function () {
        if (!current_user_can('manage_options')) return;
        if (isset($_POST['aqlx_save_modules']) && check_admin_referer('aqlx_modules')) {
            $mods = isset($_POST['modules']) && is_array($_POST['modules']) ? array_map('sanitize_text_field', $_POST['modules']) : [];
            set_theme_mod('aqlx_modules_enabled', array_values(array_unique($mods)));
            echo '<div class="notice notice-success"><p>' . esc_html__('Modules updated.', 'aqualuxe') . '</p></div>';
        }

        $dir = AQUALUXE_PATH . 'modules/';
        $folders = glob($dir . '*', GLOB_ONLYDIR) ?: [];
        $enabled = get_theme_mod('aqlx_modules_enabled', array_map('basename', $folders));
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Modules', 'aqualuxe') . '</h1><form method="post">';
        wp_nonce_field('aqlx_modules');
        echo '<table class="widefat striped"><thead><tr><th>' . esc_html__('Module', 'aqualuxe') . '</th><th>' . esc_html__('Enabled', 'aqualuxe') . '</th></tr></thead><tbody>';
        foreach ($folders as $folder) {
            $name = basename($folder);
            $checked = in_array($name, $enabled, true) ? 'checked' : '';
            echo '<tr><td>' . esc_html($name) . '</td><td><input type="checkbox" name="modules[]" value="' . esc_attr($name) . '" ' . $checked . '></td></tr>';
        }
        echo '</tbody></table>';
        submit_button(__('Save Modules', 'aqualuxe'), 'primary', 'aqlx_save_modules');
        echo '</form></div>';
    });
});
