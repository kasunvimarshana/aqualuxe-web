<?php
if (!defined('ABSPATH')) exit;

function aqualuxe_site_branding() {
    echo '<div class="site-branding">';
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a class="site-title" href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
    }
    echo '</div>';
}

function aqualuxe_primary_nav() {
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => 'nav',
        'container_class' => 'primary-nav',
        'menu_class' => 'menu',
        'fallback_cb' => '__return_empty_string',
        'depth' => 2,
    ]);
}

function aqualuxe_footer_nav() {
    wp_nav_menu([
        'theme_location' => 'footer',
        'container' => 'nav',
        'container_class' => 'footer-nav',
        'menu_class' => 'menu',
        'fallback_cb' => '__return_empty_string',
    ]);
}
