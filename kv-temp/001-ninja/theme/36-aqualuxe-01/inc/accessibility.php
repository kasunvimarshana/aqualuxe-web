<?php
/**
 * Accessibility Functions
 *
 * Functions for improving accessibility in the theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load accessibility files.
 */
function aqualuxe_load_accessibility_files() {
	// Include accessibility functions.
	require_once get_template_directory() . '/inc/accessibility/accessibility-functions.php';
	require_once get_template_directory() . '/inc/accessibility/accessibility.php';
	require_once get_template_directory() . '/inc/accessibility/skip-links.php';
}
add_action( 'after_setup_theme', 'aqualuxe_load_accessibility_files' );

/**
 * Enqueue accessibility scripts and styles.
 */
function aqualuxe_enqueue_accessibility_assets() {
	// Enqueue accessibility styles.
	wp_enqueue_style(
		'aqualuxe-accessibility',
		get_template_directory_uri() . '/assets/css/accessibility.css',
		array(),
		AQUALUXE_VERSION
	);

	// Enqueue skip link focus fix script.
	wp_enqueue_script(
		'aqualuxe-skip-link-focus-fix',
		get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js',
		array(),
		AQUALUXE_VERSION,
		true
	);

	// Enqueue dropdown keyboard navigation script.
	wp_enqueue_script(
		'aqualuxe-dropdown-keyboard-navigation',
		get_template_directory_uri() . '/assets/js/dropdown-keyboard-navigation.js',
		array(),
		AQUALUXE_VERSION,
		true
	);

	// Enqueue accessibility toolbar script if enabled.
	if ( get_theme_mod( 'aqualuxe_accessibility_toolbar', false ) ) {
		wp_enqueue_script(
			'aqualuxe-accessibility-toolbar',
			get_template_directory_uri() . '/assets/js/accessibility-toolbar.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		// Add script to enable the accessibility toolbar.
		wp_add_inline_script(
			'aqualuxe-accessibility-toolbar',
			'window.aqualuxeAccessibilityToolbar = true;'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_accessibility_assets' );

/**
 * Add accessibility settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_accessibility_customizer_settings( $wp_customize ) {
	// Add accessibility section if it doesn't exist.
	if ( ! $wp_customize->get_section( 'aqualuxe_accessibility' ) ) {
		$wp_customize->add_section(
			'aqualuxe_accessibility',
			array(
				'title'    => __( 'Accessibility', 'aqualuxe' ),
				'priority' => 90,
			)
		);
	}

	// Add accessibility toolbar setting.
	$wp_customize->add_setting(
		'aqualuxe_accessibility_toolbar',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_accessibility_toolbar',
		array(
			'label'       => __( 'Enable Accessibility Toolbar', 'aqualuxe' ),
			'description' => __( 'Adds a toolbar with accessibility options like high contrast mode, font size adjustment, and reduced motion.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);

	// Add skip links setting.
	$wp_customize->add_setting(
		'aqualuxe_skip_links',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_skip_links',
		array(
			'label'       => __( 'Enable Skip Links', 'aqualuxe' ),
			'description' => __( 'Adds skip links for keyboard navigation.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);

	// Add keyboard navigation setting.
	$wp_customize->add_setting(
		'aqualuxe_keyboard_navigation',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_keyboard_navigation',
		array(
			'label'       => __( 'Enable Keyboard Navigation', 'aqualuxe' ),
			'description' => __( 'Enhances keyboard navigation for dropdown menus and other interactive elements.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);

	// Add focus styles setting.
	$wp_customize->add_setting(
		'aqualuxe_focus_styles',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_focus_styles',
		array(
			'label'       => __( 'Enable Focus Styles', 'aqualuxe' ),
			'description' => __( 'Adds visible focus styles for keyboard navigation.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);

	// Add reduced motion setting.
	$wp_customize->add_setting(
		'aqualuxe_reduced_motion',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_reduced_motion',
		array(
			'label'       => __( 'Enable Reduced Motion by Default', 'aqualuxe' ),
			'description' => __( 'Reduces or eliminates animations and transitions by default.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);

	// Add high contrast setting.
	$wp_customize->add_setting(
		'aqualuxe_high_contrast',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'aqualuxe_high_contrast',
		array(
			'label'       => __( 'Enable High Contrast Mode by Default', 'aqualuxe' ),
			'description' => __( 'Increases contrast for better readability by default.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_customizer_settings' );

/**
 * Apply default accessibility settings.
 */
function aqualuxe_apply_default_accessibility_settings() {
	// Add reduced motion class if enabled by default.
	if ( get_theme_mod( 'aqualuxe_reduced_motion', false ) ) {
		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'reduced-motion';
			return $classes;
		} );
	}

	// Add high contrast class if enabled by default.
	if ( get_theme_mod( 'aqualuxe_high_contrast', false ) ) {
		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'high-contrast';
			return $classes;
		} );
	}
}
add_action( 'wp', 'aqualuxe_apply_default_accessibility_settings' );

/**
 * Add ARIA attributes to navigation menus.
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array Modified HTML attributes.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	// Add ARIA attributes to menu items with children.
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}
	
	// Add current state for screen readers.
	if ( in_array( 'current-menu-item', $item->classes, true ) || in_array( 'current-menu-parent', $item->classes, true ) ) {
		$atts['aria-current'] = 'page';
	}
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );

/**
 * Add ARIA attributes to navigation menu containers.
 *
 * @param array $args An array of arguments for wp_nav_menu().
 * @return array Modified arguments.
 */
function aqualuxe_nav_menu_args( $args ) {
	// Add ARIA attributes to menu containers.
	if ( ! isset( $args['container_aria_label'] ) ) {
		$args['container_aria_label'] = esc_attr( $args['theme_location'] === 'primary' ? __( 'Primary Navigation', 'aqualuxe' ) : __( 'Navigation', 'aqualuxe' ) );
	}
	
	return $args;
}
add_filter( 'wp_nav_menu_args', 'aqualuxe_nav_menu_args' );

/**
 * Add ARIA attributes to search form.
 *
 * @param string $form The search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form_aria( $form ) {
	// Add ARIA attributes to search form.
	$form = str_replace( 'class="search-form"', 'class="search-form" role="search" aria-label="' . esc_attr__( 'Site Search', 'aqualuxe' ) . '"', $form );
	$form = str_replace( 'class="search-field"', 'class="search-field" aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '"', $form );
	$form = str_replace( 'class="search-submit"', 'class="search-submit" aria-label="' . esc_attr__( 'Submit Search', 'aqualuxe' ) . '"', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form_aria' );

/**
 * Add ARIA attributes to pagination.
 *
 * @param string $template The pagination template.
 * @return string Modified pagination template.
 */
function aqualuxe_pagination_aria( $template ) {
	// Add ARIA attributes to pagination.
	$template = str_replace( '<nav class="navigation', '<nav class="navigation" aria-label="' . esc_attr__( 'Posts Navigation', 'aqualuxe' ) . '"', $template );
	$template = str_replace( '<div class="nav-links">', '<div class="nav-links" role="menubar">', $template );
	$template = str_replace( 'class="page-numbers', 'class="page-numbers" role="menuitem" aria-label="' . esc_attr__( 'Page', 'aqualuxe' ) . ' {{page}}"', $template );
	$template = str_replace( 'class="prev page-numbers', 'class="prev page-numbers" role="menuitem" aria-label="' . esc_attr__( 'Previous Page', 'aqualuxe' ) . '"', $template );
	$template = str_replace( 'class="next page-numbers', 'class="next page-numbers" role="menuitem" aria-label="' . esc_attr__( 'Next Page', 'aqualuxe' ) . '"', $template );
	
	return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_pagination_aria' );

/**
 * Add ARIA attributes to comment form.
 *
 * @param array $args Comment form arguments.
 * @return array Modified comment form arguments.
 */
function aqualuxe_comment_form_args( $args ) {
	// Add ARIA attributes to comment form.
	$args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title" aria-label="' . esc_attr__( 'Leave a comment', 'aqualuxe' ) . '">';
	$args['comment_notes_before'] = '<p class="comment-notes" aria-label="' . esc_attr__( 'Comment form notes', 'aqualuxe' ) . '">' . ( empty( $args['comment_notes_before'] ) ? '' : $args['comment_notes_before'] ) . '</p>';
	
	// Add ARIA attributes to comment form fields.
	$args['fields']['author'] = str_replace( '<input', '<input aria-required="true" aria-label="' . esc_attr__( 'Name', 'aqualuxe' ) . '"', $args['fields']['author'] );
	$args['fields']['email'] = str_replace( '<input', '<input aria-required="true" aria-label="' . esc_attr__( 'Email', 'aqualuxe' ) . '"', $args['fields']['email'] );
	$args['fields']['url'] = str_replace( '<input', '<input aria-label="' . esc_attr__( 'Website', 'aqualuxe' ) . '"', $args['fields']['url'] );
	$args['comment_field'] = str_replace( '<textarea', '<textarea aria-required="true" aria-label="' . esc_attr__( 'Comment', 'aqualuxe' ) . '"', $args['comment_field'] );
	
	return $args;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_args' );

/**
 * Add screen reader text for icons.
 *
 * @param string $icon_html The HTML for the icon.
 * @param string $icon_name The name of the icon.
 * @param array $args Arguments for the icon.
 * @return string Modified HTML for the icon.
 */
function aqualuxe_icon_screen_reader_text( $icon_html, $icon_name, $args ) {
	// Add screen reader text for icons.
	if ( ! isset( $args['aria-hidden'] ) ) {
		$icon_html = str_replace( '<i class', '<i aria-hidden="true" class', $icon_html );
	}
	
	// Add screen reader text if provided.
	if ( isset( $args['screen_reader_text'] ) ) {
		$icon_html .= '<span class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</span>';
	}
	
	return $icon_html;
}
add_filter( 'aqualuxe_icon_html', 'aqualuxe_icon_screen_reader_text', 10, 3 );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}