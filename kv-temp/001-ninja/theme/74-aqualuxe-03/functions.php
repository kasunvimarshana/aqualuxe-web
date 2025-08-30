<?php
/**
 * AquaLuxe Theme bootstrap
 */

if (!defined('AQUALUXE_VERSION')) {
    define('AQUALUXE_VERSION', '1.0.0');
}

require_once __DIR__ . '/inc/helpers.php';
require_once __DIR__ . '/inc/class-assets.php';
require_once __DIR__ . '/inc/class-theme.php';
require_once __DIR__ . '/inc/class-customizer.php';
require_once __DIR__ . '/inc/class-modules.php';
require_once __DIR__ . '/inc/class-admin.php';
require_once __DIR__ . '/inc/class-shortcodes.php';
if (\AquaLuxe\is_wc_active()) { require_once __DIR__ . '/inc/class-woocommerce.php'; }

\add_action('after_setup_theme', function(){
    \AquaLuxe\Theme::setup();
});

\add_action('widgets_init', function(){
    \register_sidebar([
        'name' => \__('Primary Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
});

\add_action('wp_enqueue_scripts', function(){
    \AquaLuxe\Assets::enqueue();
});

\add_action('init', function(){
    \load_theme_textdomain('aqualuxe', \get_template_directory() . '/languages');
    \AquaLuxe\Modules::init();
});

// SEO & OpenGraph basics
\add_action('wp_head', function(){
    echo '<meta name="theme-color" content="#0e1a23" />';
    if (\is_singular()) {
        global $post;
        $title = \esc_attr(\get_the_title($post));
        $desc = \esc_attr(\wp_strip_all_tags(\get_the_excerpt($post)));
        $url  = \esc_url(\get_permalink($post));
        echo "<meta property='og:title' content='{$title}' />\n";
        echo "<meta property='og:description' content='{$desc}' />\n";
        echo "<meta property='og:url' content='{$url}' />\n";
    }
});
