<?php
namespace AquaLuxe\Core;

final class Accessibility {
	public static function init(): void {
		\add_filter( 'nav_menu_link_attributes', [ __CLASS__, 'aria_current' ], 10, 3 );
		\add_filter( 'the_content', [ __CLASS__, 'skip_link' ], 1 );
	}

	public static function aria_current( array $atts, $item, $args ): array {
		if ( ! empty( $atts['aria-current'] ) ) {
			$atts['aria-current'] = 'page';
		}
		return $atts;
	}

	public static function skip_link( string $content ): string {
		if ( is_singular() ) {
			$skip = '<a class="skip-link sr-only focus:not-sr-only" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
			return $skip . $content;
		}
		return $content;
	}
}
