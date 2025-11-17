<?php
/**
 * Sidebars setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Setup;

/**
 * Sidebars setup class
 */
class Sidebars {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('widgets_init', [$this, 'register_sidebars']);
    }

    /**
     * Register sidebars
     *
     * @return void
     */
    public function register_sidebars() {
        // Main sidebar
        register_sidebar([
            'name' => esc_html__('Sidebar', 'aqualuxe'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ]);

        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name' => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                'id' => 'footer-' . $i,
                'description' => sprintf(esc_html__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ]);
        }

        // Shop sidebar
        register_sidebar([
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ]);
    }
}