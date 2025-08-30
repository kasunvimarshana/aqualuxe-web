<?php
/**
 * Sidebar setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Register WooCommerce sidebar.
	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
				'id'            => 'shop',
				'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	// Register footer widget areas.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
	$footer_columns = 4;

	if ( '3-columns' === $footer_layout ) {
		$footer_columns = 3;
	} elseif ( '2-columns' === $footer_layout ) {
		$footer_columns = 2;
	} elseif ( '1-column' === $footer_layout ) {
		$footer_columns = 1;
	}

	for ( $i = 1; $i <= $footer_columns; $i++ ) {
		register_sidebar(
			array(
				'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
				'id'            => 'footer-' . $i,
				'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'aqualuxe_widgets_init' );

/**
 * Determine if a sidebar is active.
 *
 * @param string $sidebar_id The ID of the sidebar.
 * @return bool True if the sidebar is active, false otherwise.
 */
function aqualuxe_is_sidebar_active( $sidebar_id ) {
	return is_active_sidebar( $sidebar_id );
}

/**
 * Get the sidebar ID for the current page.
 *
 * @return string The sidebar ID.
 */
function aqualuxe_get_sidebar_id() {
	$sidebar_id = 'sidebar-1';

	// Blog sidebar.
	if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
		$blog_sidebar = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
		if ( 'none' === $blog_sidebar ) {
			return '';
		}
	}

	// WooCommerce sidebar.
	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
		$shop_sidebar = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
		if ( 'none' === $shop_sidebar ) {
			return '';
		}
		$sidebar_id = 'shop';
	}

	// Apply filters.
	$sidebar_id = apply_filters( 'aqualuxe_sidebar_id', $sidebar_id );

	return $sidebar_id;
}

/**
 * Get the sidebar position for the current page.
 *
 * @return string The sidebar position.
 */
function aqualuxe_get_sidebar_position() {
	$position = 'right';

	// Blog sidebar.
	if ( is_home() || is_archive() || is_search() || is_singular( 'post' ) ) {
		$position = get_theme_mod( 'aqualuxe_blog_sidebar', 'right' );
	}

	// WooCommerce sidebar.
	if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() || is_product() ) ) {
		$position = get_theme_mod( 'aqualuxe_shop_sidebar', 'right' );
	}

	// Apply filters.
	$position = apply_filters( 'aqualuxe_sidebar_position', $position );

	return $position;
}

/**
 * Check if the current page has a sidebar.
 *
 * @return bool True if the current page has a sidebar, false otherwise.
 */
function aqualuxe_has_sidebar() {
	$sidebar_id = aqualuxe_get_sidebar_id();
	$position = aqualuxe_get_sidebar_position();

	if ( empty( $sidebar_id ) || 'none' === $position ) {
		return false;
	}

	return is_active_sidebar( $sidebar_id );
}

/**
 * Get the content width based on the sidebar.
 *
 * @return string The content width class.
 */
function aqualuxe_get_content_width() {
	$class = 'content-width-full';

	if ( aqualuxe_has_sidebar() ) {
		$class = 'content-width-sidebar';
	}

	// Apply filters.
	$class = apply_filters( 'aqualuxe_content_width', $class );

	return $class;
}