<?php
// Example template tag: get the site logo or fallback to site name
defined('ABSPATH') || exit;
function aqualuxe_site_logo() {
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    } else {
        echo '<span class="site-title">' . esc_html(get_bloginfo('name')) . '</span>';
    }
}
