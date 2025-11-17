<?php
namespace AquaLuxe\Core;

/**
 * SEO basics: schema, og tags, meta cleanup.
 */
final class SEO {
	public static function init(): void {
		\add_action( 'wp_head', [ __CLASS__, 'meta' ], 2 );
		\remove_action( 'wp_head', 'wp_generator' );
	}

	public static function meta(): void {
		// Basic OG tags; production sites might delegate to SEO plugins.
		echo "\n" . '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
		echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	}
}
