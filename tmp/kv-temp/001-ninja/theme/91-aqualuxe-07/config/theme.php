<?php
/**
 * AquaLuxe Theme Configuration
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	/*
	|--------------------------------------------------------------------------
	| Theme Support Features
	|--------------------------------------------------------------------------
	*/
	'theme_support' => [
		'post-thumbnails' => true,
		'custom-logo' => [
			'height'      => 100,
			'width'       => 400,
			'flex-height' => true,
			'flex-width'  => true,
		],
		'custom-background' => [
			'default-color' => 'ffffff',
		],
		'html5' => [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		],
		'post-formats' => [
			'aside',
			'gallery',
			'quote',
			'image',
			'video',
		],
		'title-tag' => true,
		'editor-styles' => true,
		'align-wide' => true,
		'custom-line-height' => true,
		'custom-spacing' => true,
		'custom-units' => true,
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Image Sizes
	|--------------------------------------------------------------------------
	*/
	'image_sizes' => [
		'aqualuxe-small' => [
			'width' => 300,
			'height' => 200,
			'crop' => true,
		],
		'aqualuxe-medium' => [
			'width' => 600,
			'height' => 400,
			'crop' => true,
		],
		'aqualuxe-large' => [
			'width' => 1200,
			'height' => 800,
			'crop' => true,
		],
		'aqualuxe-hero' => [
			'width' => 1920,
			'height' => 1080,
			'crop' => true,
		],
		'aqualuxe-product-thumb' => [
			'width' => 300,
			'height' => 300,
			'crop' => true,
		],
		'aqualuxe-product-medium' => [
			'width' => 600,
			'height' => 600,
			'crop' => true,
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Navigation Menus
	|--------------------------------------------------------------------------
	*/
	'nav_menus' => [
		'primary' => __( 'Primary Navigation', 'aqualuxe' ),
		'secondary' => __( 'Secondary Navigation', 'aqualuxe' ),
		'footer' => __( 'Footer Navigation', 'aqualuxe' ),
		'mobile' => __( 'Mobile Navigation', 'aqualuxe' ),
		'account' => __( 'Account Navigation', 'aqualuxe' ),
	],

	/*
	|--------------------------------------------------------------------------
	| Widget Areas (Sidebars)
	|--------------------------------------------------------------------------
	*/
	'widget_areas' => [
		'sidebar-main' => [
			'name' => __( 'Main Sidebar', 'aqualuxe' ),
			'description' => __( 'Main sidebar widget area', 'aqualuxe' ),
		],
		'sidebar-shop' => [
			'name' => __( 'Shop Sidebar', 'aqualuxe' ),
			'description' => __( 'Shop and product pages sidebar', 'aqualuxe' ),
		],
		'footer-1' => [
			'name' => __( 'Footer Column 1', 'aqualuxe' ),
			'description' => __( 'First footer widget area', 'aqualuxe' ),
		],
		'footer-2' => [
			'name' => __( 'Footer Column 2', 'aqualuxe' ),
			'description' => __( 'Second footer widget area', 'aqualuxe' ),
		],
		'footer-3' => [
			'name' => __( 'Footer Column 3', 'aqualuxe' ),
			'description' => __( 'Third footer widget area', 'aqualuxe' ),
		],
		'footer-4' => [
			'name' => __( 'Footer Column 4', 'aqualuxe' ),
			'description' => __( 'Fourth footer widget area', 'aqualuxe' ),
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Customizer Configuration
	|--------------------------------------------------------------------------
	*/
	'customizer' => [
		'panels' => [
			'aqualuxe_theme' => [
				'title' => __( 'AquaLuxe Theme Options', 'aqualuxe' ),
				'priority' => 30,
			],
			'aqualuxe_layout' => [
				'title' => __( 'Layout Options', 'aqualuxe' ),
				'priority' => 40,
			],
		],
		'sections' => [
			'aqualuxe_general' => [
				'title' => __( 'General Settings', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme',
				'priority' => 10,
			],
			'aqualuxe_colors' => [
				'title' => __( 'Color Scheme', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme',
				'priority' => 20,
			],
			'aqualuxe_typography' => [
				'title' => __( 'Typography', 'aqualuxe' ),
				'panel' => 'aqualuxe_theme',
				'priority' => 30,
			],
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Color Palette
	|--------------------------------------------------------------------------
	*/
	'colors' => [
		'primary' => '#1e40af',     // Blue
		'secondary' => '#0891b2',   // Cyan
		'accent' => '#06b6d4',      // Light Blue
		'success' => '#10b981',     // Green
		'warning' => '#f59e0b',     // Amber
		'error' => '#ef4444',       // Red
		'info' => '#3b82f6',        // Blue
		'light' => '#f8fafc',       // Light Gray
		'dark' => '#1e293b',        // Dark Gray
		'text' => '#334155',        // Slate
		'background' => '#ffffff',   // White
	],

	/*
	|--------------------------------------------------------------------------
	| Typography Configuration
	|--------------------------------------------------------------------------
	*/
	'typography' => [
		'font_families' => [
			'primary' => 'Inter, system-ui, sans-serif',
			'secondary' => 'Merriweather, Georgia, serif',
			'heading' => 'Poppins, system-ui, sans-serif',
			'mono' => 'JetBrains Mono, Consolas, monospace',
		],
		'font_sizes' => [
			'xs' => '0.75rem',   // 12px
			'sm' => '0.875rem',  // 14px
			'base' => '1rem',    // 16px
			'lg' => '1.125rem',  // 18px
			'xl' => '1.25rem',   // 20px
			'2xl' => '1.5rem',   // 24px
			'3xl' => '1.875rem', // 30px
			'4xl' => '2.25rem',  // 36px
			'5xl' => '3rem',     // 48px
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Layout Configuration
	|--------------------------------------------------------------------------
	*/
	'layout' => [
		'container_width' => '1200px',
		'content_width' => 1200,
		'sidebar_width' => '300px',
		'grid_columns' => 12,
		'breakpoints' => [
			'sm' => '640px',
			'md' => '768px',
			'lg' => '1024px',
			'xl' => '1280px',
			'2xl' => '1536px',
		],
	],
];
