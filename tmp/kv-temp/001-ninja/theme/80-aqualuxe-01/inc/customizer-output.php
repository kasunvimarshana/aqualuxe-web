<?php
/** Output CSS variables based on Customizer settings */

add_action('wp_head', function(){
    $container = get_theme_mod('aqualuxe_container_width', '1280px');
    $primary = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    echo '<style id="aqualuxe-customizer-vars">:root{--container-max:' . esc_attr($container) . '; --brand:' . esc_attr($primary) . ';}</style>';
}, 20);
