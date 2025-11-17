<?php
/** Output CSS variables based on Customizer settings */

add_action('wp_head', function(){
    $container = get_theme_mod('aqualuxe_container_width', '1280px');
    $primary = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
    $css = ':root{--container-max:' . esc_attr($container) . '; --brand:' . esc_attr($primary) . ';}';
    $ui = get_option('aqlx_ui', []);
    $badges_on = !isset($ui['badges']) || !empty($ui['badges']);
    if ($badges_on) {
        $css .= '.woocommerce ul.products li.product{position:relative}';
        $css .= '.aqlx-badges{position:absolute;top:.5rem;left:.5rem;display:flex;gap:.25rem;z-index:2}';
        $css .= '.aqlx-badge{background:var(--brand);color:#fff;border-radius:.25rem;padding:.125rem .375rem;font-size:.75rem;line-height:1;box-shadow:0 1px 2px rgba(0,0,0,.15)}';
    }
    // Skip link minimal a11y styles
    $css .= '.skip-link, .screen-reader-text{position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden}';
    $css .= '.skip-link:focus, .screen-reader-text:focus{left:8px;top:8px;width:auto;height:auto;padding:.5rem .75rem;background:#000;color:#fff;z-index:10000}';
    // Notices for forms
    $css .= '.aqlx-notice{padding:.5rem .75rem;margin:0 0 .75rem;border-radius:.25rem}';
    $css .= '.aqlx-notice--success{background:#ecfdf5;color:#065f46}';
    $css .= '.aqlx-notice--error{background:#fef2f2;color:#991b1b}';
    // Active link hint for menus
    $css .= '.site-footer nav a[aria-current], .primary-nav a[aria-current]{text-decoration:underline;text-underline-offset:3px}';
    echo '<style id="aqualuxe-customizer-vars">' . $css . '</style>';
}, 20);
