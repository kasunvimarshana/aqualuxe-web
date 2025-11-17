<?php
namespace AquaLuxe;

defined('ABSPATH') || exit;

class Core
{
    public static function init(): void
    {
        // Setup theme supports and menus.
    \add_action('after_setup_theme', [__CLASS__, 'theme_setup']);
    \add_action('init', [__CLASS__, 'register_menus']);

        // Head cleanup and meta
    \add_action('wp_head', [__CLASS__, 'meta_tags'], 1);


        // Init modules
    \add_action('init', ['\\AquaLuxe\\Modules', 'boot'], 20);
    }

    public static function theme_setup(): void
    {
    \load_theme_textdomain(AQUALUXE_TEXT, AQUALUXE_PATH . 'languages');
    \add_theme_support('title-tag');
    \add_theme_support('post-thumbnails');
    \add_theme_support('custom-logo');
    \add_theme_support('customize-selective-refresh-widgets');
    \add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    \add_theme_support('align-wide');
    \add_theme_support('editor-styles');
    \add_theme_support('automatic-feed-links');

        if (\class_exists('WooCommerce')) {
            \add_theme_support('woocommerce');
            \add_theme_support('wc-product-gallery-zoom');
            \add_theme_support('wc-product-gallery-lightbox');
            \add_theme_support('wc-product-gallery-slider');
        }

        // Set content width.
    $GLOBALS['content_width'] = \apply_filters('aqualuxe_content_width', 1200);
    }

    public static function register_menus(): void
    {
        \register_nav_menus([
            'primary'   => \__('Primary Menu', AQUALUXE_TEXT),
            'secondary' => \__('Secondary Menu', AQUALUXE_TEXT),
            'footer'    => \__('Footer Menu', AQUALUXE_TEXT),
        ]);
    }

    public static function meta_tags(): void
    {
        echo '<meta charset="' . \esc_attr(\get_bloginfo('charset')) . '" />' . "\n";
        echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
        echo '<meta property="og:site_name" content="' . \esc_attr(\get_bloginfo('name')) . '" />' . "\n";
        if (\is_singular()) {
            global $post;
            $title = \wp_get_document_title();
            $desc = \get_the_excerpt($post) ?: \get_bloginfo('description');
            echo '<meta property="og:title" content="' . \esc_attr($title) . '" />' . "\n";
            echo '<meta property="og:description" content="' . \esc_attr(\wp_strip_all_tags($desc)) . '" />' . "\n";
        }
        echo '<script type="application/ld+json">' . \wp_json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => \get_bloginfo('name'),
            'url' => \home_url('/'),
            'logo' => \get_theme_mod('custom_logo') ? \wp_get_attachment_image_url(\get_theme_mod('custom_logo'), 'full') : '',
        ]) . '</script>' . "\n";
    }

    // CPTs are registered via modules.
}
