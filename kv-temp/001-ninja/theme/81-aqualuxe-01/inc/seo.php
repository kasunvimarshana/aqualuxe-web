<?php
/** SEO: meta tags, Open Graph */
if (!defined('ABSPATH')) { exit; }

add_action('wp_head', function(){
    echo "\n<meta name=\"theme-color\" content=\"" . esc_attr(get_theme_mod('aqualuxe_primary_color', '#0ea5e9')) . "\">\n";
    echo "<meta property=\"og:site_name\" content=\"" . esc_attr(get_bloginfo('name')) . "\">\n";
    echo "<meta property=\"og:locale\" content=\"" . esc_attr(get_locale()) . "\">\n";
}, 5);
