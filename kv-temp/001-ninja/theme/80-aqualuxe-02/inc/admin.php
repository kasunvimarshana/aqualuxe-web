<?php
/** Admin settings for AquaLuxe: toggle modules */

add_action('admin_menu', function(){
    add_theme_page(__('AquaLuxe Settings','aqualuxe'), __('AquaLuxe Settings','aqualuxe'), 'manage_options', 'aqualuxe-settings', 'aqlx_settings_page');
});

function aqlx_settings_page(){
    if (!current_user_can('manage_options')) return;
    if (!empty($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'aqlx_save_modules')){
        $cfg = [];
        foreach ((array)($_POST['modules'] ?? []) as $k=>$v){ $cfg[sanitize_key($k)] = (bool)$v; }
        update_option('aqlx_modules', $cfg);
        echo '<div class="updated"><p>' . esc_html__('Settings saved','aqualuxe') . '</p></div>';
    }
    $enabled = aqualuxe_get_modules_config();
    echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Settings','aqualuxe') . '</h1>';
    echo '<form method="post">'; wp_nonce_field('aqlx_save_modules');
    echo '<h2>' . esc_html__('Modules','aqualuxe') . '</h2><table class="form-table">';
    foreach ($enabled as $slug=>$on){
        echo '<tr><th scope="row">' . esc_html($slug) . '</th><td>';
        echo '<label><input type="checkbox" name="modules[' . esc_attr($slug) . ']" value="1" ' . checked($on, true, false) . ' /> ' . esc_html__('Enabled','aqualuxe') . '</label>';
        echo '</td></tr>';
    }
    echo '</table><p><button class="button button-primary">' . esc_html__('Save','aqualuxe') . '</button></p></form></div>';
}
