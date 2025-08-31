<?php
namespace AquaLuxe\Core;

if (!defined('ABSPATH')) exit;

class Setup {
    public static function boot() : void {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script','navigation-widgets']);
        add_theme_support('custom-logo', [
            'height'      => 80,
            'width'       => 240,
            'flex-height' => true,
            'flex-width'  => true,
        ]);
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/editor.css');

        register_nav_menus([
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer'  => __('Footer Menu', 'aqualuxe'),
            'account' => __('Account Menu', 'aqualuxe'),
        ]);
    }
}
