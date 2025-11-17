<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

function site_branding(): void
{
    echo '<div class="site-branding">';
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<a class="site-title" href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
    }
    echo '</div>';
}

function primary_menu(): void
{
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => 'nav',
        'container_class' => 'primary-nav',
        'fallback_cb' => static function () {
            echo '<nav class="primary-nav"><ul><li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li></ul></nav>';
        },
    ]);
}
