<?php
namespace AquaLuxe\Core;

class Template_Tags {
    public static function site_brand() : void {
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        } else {
            echo '<a class="site-title" href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>';
        }
    }

    public static function breadcrumbs() : void {
        if (is_front_page()) return;
        echo '<nav class="breadcrumbs" aria-label="Breadcrumbs">';
        echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a>';
        if (is_category() || is_single()) {
            $cat = get_the_category();
            if ($cat) {
                $c = $cat[0];
                echo ' / <a href="' . esc_url(get_category_link($c)) . '">' . esc_html($c->name) . '</a>';
            }
            if (is_single()) {
                echo ' / <span aria-current="page">' . esc_html(get_the_title()) . '</span>';
            }
        } elseif (is_page()) {
            echo ' / <span aria-current="page">' . esc_html(get_the_title()) . '</span>';
        }
        echo '</nav>';
    }
}
