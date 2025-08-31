<?php
/** Admin settings for AquaLuxe: toggle modules */

add_action('admin_menu', function(){
    add_theme_page(__('AquaLuxe Settings','aqualuxe'), __('AquaLuxe Settings','aqualuxe'), 'manage_options', 'aqualuxe-settings', 'aqlx_settings_page');
});

function aqlx_settings_page(){
    if (!current_user_can('manage_options')) return;
    if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'aqlx_save_modules')){
        // Modules
        $cfg = [];
        foreach ((array)($_POST['modules'] ?? []) as $k=>$v){ $cfg[sanitize_key($k)] = (bool)$v; }
        update_option('aqlx_modules', $cfg);
        // UI
        $ui = [
            'badges' => !empty($_POST['ui']['badges']),
            'title_badges' => !empty($_POST['ui']['title_badges']),
        ];
        update_option('aqlx_ui', $ui);
        // Performance
        $perf = [
            'defer_scripts' => !empty($_POST['perf']['defer_scripts']),
        ];
        update_option('aqlx_perf', $perf);
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved','aqualuxe') . '</p></div>';
    }
    $enabled = aqualuxe_get_modules_config();
    $ui = get_option('aqlx_ui', []);
    $perf = get_option('aqlx_perf', []);
    echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Settings','aqualuxe') . '</h1>';
    echo '<form method="post">'; wp_nonce_field('aqlx_save_modules');
    echo '<h2>' . esc_html__('Modules','aqualuxe') . '</h2><table class="form-table">';
    foreach ($enabled as $slug=>$on){
        echo '<tr><th scope="row">' . esc_html($slug) . '</th><td>';
        echo '<label><input type="checkbox" name="modules[' . esc_attr($slug) . ']" value="1" ' . checked($on, true, false) . ' /> ' . esc_html__('Enabled','aqualuxe') . '</label>';
        echo '</td></tr>';
    }
    echo '</table>';

    echo '<h2>' . esc_html__('UI','aqualuxe') . '</h2><table class="form-table">';
    echo '<tr><th scope="row">' . esc_html__('Product badges','aqualuxe') . '</th><td>';
    echo '<label><input type="checkbox" name="ui[badges]" value="1" ' . checked(!empty($ui['badges']) || !isset($ui['badges']), true, false) . ' /> ' . esc_html__('Show badges on product cards','aqualuxe') . '</label>';
    echo '</td></tr>';
    echo '<tr><th scope="row">' . esc_html__('Title badges (dot style)','aqualuxe') . '</th><td>';
    echo '<label><input type="checkbox" name="ui[title_badges]" value="1" ' . checked(!empty($ui['title_badges']), true, false) . ' /> ' . esc_html__('Append badge text to product titles','aqualuxe') . '</label>';
    echo '</td></tr>';
    echo '</table>';

    echo '<p><button class="button button-primary">' . esc_html__('Save','aqualuxe') . '</button></p></form></div>';

    // Performance section
    echo '<h2>' . esc_html__('Performance','aqualuxe') . '</h2><table class="form-table">';
    echo '<tr><th scope="row">' . esc_html__('Defer theme scripts','aqualuxe') . '</th><td>';
    echo '<label><input type="checkbox" name="perf[defer_scripts]" value="1" ' . checked(!isset($perf['defer_scripts']) || !empty($perf['defer_scripts']), true, false) . ' /> ' . esc_html__('Defer manifest/vendor/theme scripts to improve first paint','aqualuxe') . '</label>';
    echo '</td></tr>';
    echo '</table>';
}
