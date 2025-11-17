<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Theme
{
    public static function init(): void
    {
    Helpers::wp('load_theme_textdomain', ['aqualuxe', AQUALUXE_LANG_DIR]);

    Helpers::wp('add_theme_support', ['title-tag']);
    Helpers::wp('add_theme_support', ['post-thumbnails']);
    Helpers::wp('add_theme_support', ['html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']]);
    Helpers::wp('add_theme_support', ['custom-logo']);
    Helpers::wp('add_theme_support', ['customize-selective-refresh-widgets']);
    Helpers::wp('add_theme_support', ['align-wide']);
    Helpers::wp('add_theme_support', ['automatic-feed-links']);
    Helpers::wp('add_theme_support', ['wp-block-styles']);
    Helpers::wp('add_theme_support', ['responsive-embeds']);
    Helpers::wp('add_theme_support', ['editor-styles']);

        // WooCommerce support (graceful if not active).
    Helpers::wp('add_theme_support', ['woocommerce']);
    Helpers::wp('add_theme_support', ['wc-product-gallery-zoom']);
    Helpers::wp('add_theme_support', ['wc-product-gallery-lightbox']);
    Helpers::wp('add_theme_support', ['wc-product-gallery-slider']);

    Helpers::wp('register_nav_menus', [[
            'primary'   => __('Primary Menu', 'aqualuxe'),
            'secondary' => __('Secondary Menu', 'aqualuxe'),
            'footer'    => __('Footer Menu', 'aqualuxe'),
    ]]);

    Helpers::wp('add_image_size', ['alx-card', 800, 600, true]);
    Helpers::wp('add_image_size', ['alx-hero', 1920, 1080, true]);

        // Widgets.
    Helpers::wp('add_action', ['widgets_init', [self::class, 'register_sidebars']]);

        // Customizer.
    Helpers::wp('add_action', ['customize_register', [Customizer::class, 'register']]);
    }

    public static function register_sidebars(): void
    {
    Helpers::wp('register_sidebar', [[
            'name'          => __('Primary Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => __('Main sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s" aria-label="Widget">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
    ]]);
    Helpers::wp('register_sidebar', [[
            'name'          => __('Footer Widgets', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => __('Footer widget area.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s" aria-label="Footer widget">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
    ]]);
    }
}
