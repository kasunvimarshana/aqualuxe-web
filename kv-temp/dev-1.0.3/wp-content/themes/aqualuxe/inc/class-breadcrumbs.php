<?php

/**
 * AquaLuxe Breadcrumbs
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Breadcrumbs
{

    public static function display()
    {
        if (is_front_page()) return;

        echo '<nav class="breadcrumb">';
        echo '<a href="' . esc_url(home_url()) . '">' . __('Home', 'aqualuxe') . '</a> &raquo; ';

        if (is_category() || is_single()) {
            the_category(', ');
            if (is_single()) {
                echo ' &raquo; ' . get_the_title();
            }
        } elseif (is_page()) {
            echo get_the_title();
        }
        echo '</nav>';
    }
}
