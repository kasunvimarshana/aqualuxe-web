<?php
// Placeholder multilingual module hooks (integration-ready for Polylang/WPML).
// Provide language switcher location and filters without requiring plugins.
add_action( 'wp_nav_menu_items', function( $items, $args ){
	if ( 'primary' !== ($args->theme_location ?? '') ) return $items;
	// Simple pseudo-switcher (extend when plugin active)
	$items .= '<li class="menu-item"><a href="#" aria-haspopup="true">' . esc_html__( 'Language', 'aqualuxe' ) . '</a></li>';
	return $items;
}, 10, 2 );
