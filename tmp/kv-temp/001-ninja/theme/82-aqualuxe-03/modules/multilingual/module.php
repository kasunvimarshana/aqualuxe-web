<?php
// Multilingual integration with graceful fallback.
// If Polylang/WPML are present, use their APIs; otherwise render a simple placeholder.
add_filter( 'wp_nav_menu_items', function( $items, $args ){
	if ( 'primary' !== ($args->theme_location ?? '') ) return $items;

	// Polylang
	if ( function_exists('pll_the_languages') ) {
		ob_start();
		call_user_func('pll_the_languages', [ 'show_flags'=>1, 'show_names'=>1, 'hide_current'=>0 ]);
		$html = ob_get_clean();
		if ( $html ) {
			return $items . '<li class="menu-item menu-item-language">' . $html . '</li>';
		}
	}
	// WPML
	if ( function_exists('icl_get_languages') ) {
		$langs = call_user_func('icl_get_languages', 'skip_missing=0&orderby=code');
		if ( ! empty($langs) && is_array($langs) ) {
			$o = '<ul class="sub-menu">';
			foreach ( $langs as $l ) {
				$o .= '<li><a href="' . esc_url($l['url']) . '">' . esc_html($l['native_name']) . '</a></li>';
			}
			$o .= '</ul>';
			return $items . '<li class="menu-item menu-item-language"><a href="#">' . esc_html__('Language','aqualuxe') . '</a>' . $o . '</li>';
		}
	}
	// Fallback
	return $items . '<li class="menu-item"><a href="#" aria-haspopup="true">' . esc_html__( 'Language', 'aqualuxe' ) . '</a></li>';
}, 10, 2 );
