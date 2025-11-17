<?php
defined('ABSPATH') || exit;

// Custom hook wrappers for extensibility.
function aqualuxe_do_header_top() { do_action('aqualuxe/header_top'); }
function aqualuxe_do_header_bottom() { do_action('aqualuxe/header_bottom'); }
function aqualuxe_do_footer_top() { do_action('aqualuxe/footer_top'); }
function aqualuxe_do_footer_bottom() { do_action('aqualuxe/footer_bottom'); }

add_filter('body_class', function ($classes) {
    $dark = get_theme_mod('aqualuxe_dark_mode_default', false) ? 'dark' : '';
    if (!empty($_COOKIE['aqlx_dark']) && $_COOKIE['aqlx_dark'] === '1') {
        $dark = 'dark';
    }
    if ($dark) $classes[] = 'dark';
    return $classes;
});
