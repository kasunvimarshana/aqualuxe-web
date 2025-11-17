<?php
namespace AquaLuxe;

class Theme {
    public static function setup(): void {
        \add_theme_support('title-tag');
        \add_theme_support('post-thumbnails');
        \add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
        \add_theme_support('custom-logo');
        \add_theme_support('customize-selective-refresh-widgets');
        \add_theme_support('automatic-feed-links');
        \add_theme_support('align-wide');
        \add_theme_support('woocommerce');

        \register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer'  => __('Footer Menu', 'aqualuxe'),
        ]);
    }
}
