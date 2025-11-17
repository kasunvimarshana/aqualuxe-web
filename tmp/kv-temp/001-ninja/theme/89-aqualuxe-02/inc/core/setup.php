<?php
/**
 * Theme setup
 *
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }


\add_action( 'after_setup_theme', function () {
	// i18n
	\load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . '/languages' );

	// Core supports
	\add_theme_support( 'automatic-feed-links' );
	\add_theme_support( 'title-tag' );
	\add_theme_support( 'post-thumbnails' );
	\add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ] );
	\add_theme_support( 'custom-logo', [ 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ] );
	\add_theme_support( 'customize-selective-refresh-widgets' );
	\add_theme_support( 'align-wide' );
	\add_theme_support( 'responsive-embeds' );

	// Menus
	\register_nav_menus( [
		'primary'   => __( 'Primary Menu', 'aqualuxe' ),
		'footer'    => __( 'Footer Menu', 'aqualuxe' ),
		'account'   => __( 'Account Menu', 'aqualuxe' ),
	] );

	// Image sizes
	\add_image_size( 'alx_card', 640, 400, true );
	\add_image_size( 'alx_hero', 1920, 900, true );
} );

// Widgets
\add_action( 'widgets_init', function () {
	\register_sidebar( [
		'name'          => __( 'Sidebar', 'aqualuxe' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	] );
} );
