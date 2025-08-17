<?php
/**
 * AquaLuxe Accessibility Functions
 *
 * Functions to improve accessibility in the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add ARIA landmarks and roles to theme elements
 */
function aqualuxe_add_aria_landmarks() {
	// Add ARIA landmark roles to theme elements via filters
	add_filter( 'aqualuxe_header_class', 'aqualuxe_add_header_landmark' );
	add_filter( 'aqualuxe_main_content_class', 'aqualuxe_add_main_landmark' );
	add_filter( 'aqualuxe_sidebar_class', 'aqualuxe_add_sidebar_landmark' );
	add_filter( 'aqualuxe_footer_class', 'aqualuxe_add_footer_landmark' );
	add_filter( 'aqualuxe_navigation_class', 'aqualuxe_add_navigation_landmark' );
	add_filter( 'aqualuxe_search_form_class', 'aqualuxe_add_search_landmark' );
}
add_action( 'after_setup_theme', 'aqualuxe_add_aria_landmarks' );

/**
 * Add ARIA landmark role to header
 *
 * @param string $classes Header classes.
 * @return string
 */
function aqualuxe_add_header_landmark( $classes ) {
	return $classes . ' role="banner"';
}

/**
 * Add ARIA landmark role to main content
 *
 * @param string $classes Main content classes.
 * @return string
 */
function aqualuxe_add_main_landmark( $classes ) {
	return $classes . ' role="main"';
}

/**
 * Add ARIA landmark role to sidebar
 *
 * @param string $classes Sidebar classes.
 * @return string
 */
function aqualuxe_add_sidebar_landmark( $classes ) {
	return $classes . ' role="complementary"';
}

/**
 * Add ARIA landmark role to footer
 *
 * @param string $classes Footer classes.
 * @return string
 */
function aqualuxe_add_footer_landmark( $classes ) {
	return $classes . ' role="contentinfo"';
}

/**
 * Add ARIA landmark role to navigation
 *
 * @param string $classes Navigation classes.
 * @return string
 */
function aqualuxe_add_navigation_landmark( $classes ) {
	return $classes . ' role="navigation"';
}

/**
 * Add ARIA landmark role to search form
 *
 * @param string $classes Search form classes.
 * @return string
 */
function aqualuxe_add_search_landmark( $classes ) {
	return $classes . ' role="search"';
}

/**
 * Add skip links to the theme
 */
function aqualuxe_skip_links() {
	// Only show skip links if enabled in theme options
	if ( ! get_theme_mod( 'aqualuxe_enable_skip_links', true ) ) {
		return;
	}
	?>
	<div class="skip-links">
		<a href="#primary" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<a href="#site-navigation" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to navigation', 'aqualuxe' ); ?></a>
		<?php endif; ?>
		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<a href="#secondary" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to sidebar', 'aqualuxe' ); ?></a>
		<?php endif; ?>
		<a href="#colophon" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to footer', 'aqualuxe' ); ?></a>
	</div>
	<?php
}
add_action( 'aqualuxe_before_header', 'aqualuxe_skip_links', 1 );

/**
 * Add screen reader text class
 */
function aqualuxe_screen_reader_text() {
	?>
	<style>
		.screen-reader-text {
			border: 0;
			clip: rect(1px, 1px, 1px, 1px);
			clip-path: inset(50%);
			height: 1px;
			margin: -1px;
			overflow: hidden;
			padding: 0;
			position: absolute !important;
			width: 1px;
			word-wrap: normal !important;
		}
		.screen-reader-text:focus {
			background-color: #f1f1f1;
			border-radius: 3px;
			box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
			clip: auto !important;
			clip-path: none;
			color: #21759b;
			display: block;
			font-size: 14px;
			font-weight: bold;
			height: auto;
			left: 5px;
			line-height: normal;
			padding: 15px 23px 14px;
			text-decoration: none;
			top: 5px;
			width: auto;
			z-index: 100000;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'aqualuxe_screen_reader_text', 1 );

/**
 * Add keyboard navigation support
 */
function aqualuxe_keyboard_navigation() {
	// Only enqueue if keyboard navigation is enabled in theme options
	if ( ! get_theme_mod( 'aqualuxe_enable_keyboard_navigation', true ) ) {
		return;
	}
	wp_enqueue_script(
		'aqualuxe-keyboard-navigation',
		get_template_directory_uri() . '/assets/js/keyboard-navigation.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_keyboard_navigation' );

/**
 * Add focus styles to theme elements
 */
function aqualuxe_focus_styles() {
	// Only add focus styles if enabled in theme options
	if ( ! get_theme_mod( 'aqualuxe_enable_focus_styles', true ) ) {
		return;
	}
	?>
	<style>
		/* Focus styles */
		a:focus,
		button:focus,
		input:focus,
		textarea:focus,
		select:focus,
		[tabindex]:focus {
			outline: 2px solid #4a90e2;
			outline-offset: 2px;
		}
		/* Remove focus styles for mouse users */
		.js-focus-visible :focus:not(.focus-visible) {
			outline: none;
		}
		/* Custom focus styles for focus-visible */
		.js-focus-visible .focus-visible {
			outline: 2px solid #4a90e2;
			outline-offset: 2px;
		}
	</style>
	<?php
	// Enqueue focus-visible polyfill
	wp_enqueue_script(
		'focus-visible',
		get_template_directory_uri() . '/assets/js/focus-visible.min.js',
		array(),
		'5.2.0',
		true
	);
}
add_action( 'wp_head', 'aqualuxe_focus_styles', 2 );

/**
 * Add ARIA attributes to WordPress comment form
 *
 * @param array $args Comment form arguments.
 * @return array
 */
function aqualuxe_comment_form_args( $args ) {
	// Add ARIA required attribute to required fields
	if ( isset( $args['fields']['author'] ) ) {
		$args['fields']['author'] = str_replace( 'name="author"', 'name="author" aria-required="true"', $args['fields']['author'] );
	}
	if ( isset( $args['fields']['email'] ) ) {
		$args['fields']['email'] = str_replace( 'name="email"', 'name="email" aria-required="true"', $args['fields']['email'] );
	}
	if ( isset( $args['fields']['url'] ) ) {
		$args['fields']['url'] = str_replace( 'name="url"', 'name="url" aria-required="false"', $args['fields']['url'] );
	}
	// Add ARIA required attribute to comment textarea
	if ( isset( $args['comment_field'] ) ) {
		$args['comment_field'] = str_replace( 'name="comment"', 'name="comment" aria-required="true"', $args['comment_field'] );
	}
	return $args;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_args' );

/**
 * Add ARIA attributes to WordPress search form
 *
 * @param string $form Search form HTML.
 * @return string
 */
function aqualuxe_search_form( $form ) {
	$form = str_replace( 'class="search-field"', 'class="search-field" aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '"', $form );
	$form = str_replace( 'class="search-submit"', 'class="search-submit" aria-label="' . esc_attr__( 'Submit search', 'aqualuxe' ) . '"', $form );
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Add ARIA attributes to WordPress menu
 *
 * @param array $atts Menu item attributes.
 * @param object $item Menu item object.
 * @param object $args Menu arguments.
 * @return array
 */
function aqualuxe_menu_item_atts( $atts, $item, $args ) {
	// Add ARIA current attribute to current menu item
	if ( in_array( 'current-menu-item', $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_menu_item_atts', 10, 3 );

/**
 * Add ARIA attributes to WordPress pagination
 *
 * @param string $template Pagination template.
 * @return string
 */
function aqualuxe_pagination_template( $template ) {
	$template = str_replace( '<nav class="navigation', '<nav aria-label="' . esc_attr__( 'Posts navigation', 'aqualuxe' ) . '" class="navigation', $template );
	return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_pagination_template' );

/**
 * Add color contrast checker to Customizer
 */
function aqualuxe_color_contrast_customizer() {
	// Only add color contrast checker if enabled in theme options
	if ( ! get_theme_mod( 'aqualuxe_enable_color_contrast_checker', true ) ) {
		return;
	}
	wp_enqueue_script(
		'aqualuxe-color-contrast',
		get_template_directory_uri() . '/assets/js/color-contrast.js',
		array( 'jquery', 'customize-controls' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'aqualuxe_color_contrast_customizer' );

/**
 * Add accessibility checker to admin
 */
function aqualuxe_accessibility_checker() {
	// Only add accessibility checker if enabled in theme options
	if ( ! get_theme_mod( 'aqualuxe_enable_accessibility_checker', true ) ) {
		return;
	}
	// Only add to post/page edit screens
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->base, array( 'post', 'page' ), true ) ) {
		return;
	}
	wp_enqueue_script(
		'aqualuxe-accessibility-checker',
		get_template_directory_uri() . '/assets/js/accessibility-checker.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_accessibility_checker' );

/**
 * Add accessibility settings to Customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function aqualuxe_accessibility_customizer( $wp_customize ) {
	// Add accessibility section
	$wp_customize->add_section(
		'aqualuxe_accessibility',
		array(
			'title'    => __( 'Accessibility', 'aqualuxe' ),
			'priority' => 80,
		)
	);
	// Add skip links setting
	$wp_customize->add_setting(
		'aqualuxe_enable_skip_links',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_enable_skip_links',
		array(
			'label'   => __( 'Enable skip links', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	// Add keyboard navigation setting
	$wp_customize->add_setting(
		'aqualuxe_enable_keyboard_navigation',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_enable_keyboard_navigation',
		array(
			'label'   => __( 'Enable keyboard navigation', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	// Add focus styles setting
	$wp_customize->add_setting(
		'aqualuxe_enable_focus_styles',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_enable_focus_styles',
		array(
			'label'   => __( 'Enable focus styles', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	// Add color contrast checker setting
	$wp_customize->add_setting(
		'aqualuxe_enable_color_contrast_checker',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_enable_color_contrast_checker',
		array(
			'label'   => __( 'Enable color contrast checker in Customizer', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	// Add accessibility checker setting
	$wp_customize->add_setting(
		'aqualuxe_enable_accessibility_checker',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'aqualuxe_enable_accessibility_checker',
		array(
			'label'   => __( 'Enable accessibility checker in editor', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_customizer' );