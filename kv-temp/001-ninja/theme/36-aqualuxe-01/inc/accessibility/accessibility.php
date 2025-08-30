<?php
/**
 * Accessibility Functions
 *
 * Functions for improving accessibility in the theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add skip link for keyboard navigation.
 */
function aqualuxe_skip_link() {
	// Output the skip link.
	echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
}
add_action( 'wp_body_open', 'aqualuxe_skip_link', 5 );

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
 * Add screen reader text for social icons.
 *
 * @param string $link_html The HTML for the social link.
 * @param string $network The name of the social network.
 * @param string $url The URL for the social link.
 * @return string Modified HTML for the social link.
 */
function aqualuxe_social_icon_screen_reader_text( $link_html, $network, $url ) {
	// Add screen reader text for social icons.
	$network_name = ucfirst( $network );
	
	// Special cases for network names.
	switch ( $network ) {
		case 'facebook':
			$network_name = 'Facebook';
			break;
		case 'twitter':
			$network_name = 'Twitter';
			break;
		case 'instagram':
			$network_name = 'Instagram';
			break;
		case 'linkedin':
			$network_name = 'LinkedIn';
			break;
		case 'youtube':
			$network_name = 'YouTube';
			break;
		case 'pinterest':
			$network_name = 'Pinterest';
			break;
	}
	
	/* translators: %s: social network name */
	$link_html = str_replace( '</a>', '<span class="screen-reader-text">' . sprintf( esc_html__( 'Visit our %s page', 'aqualuxe' ), $network_name ) . '</span></a>', $link_html );
	
	return $link_html;
}
add_filter( 'aqualuxe_social_link_html', 'aqualuxe_social_icon_screen_reader_text', 10, 3 );

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
 * Add keyboard navigation for dropdown menus.
 */
function aqualuxe_dropdown_keyboard_navigation() {
	// Skip if in admin.
	if ( is_admin() ) {
		return;
	}
	
	// Add keyboard navigation script.
	wp_enqueue_script(
		'aqualuxe-dropdown-keyboard-navigation',
		get_template_directory_uri() . '/assets/js/dropdown-keyboard-navigation.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dropdown_keyboard_navigation' );

/**
 * Add focus styles for keyboard navigation.
 */
function aqualuxe_focus_styles() {
	// Skip if in admin.
	if ( is_admin() ) {
		return;
	}
	
	// Add focus styles.
	$focus_styles = '
		:focus {
			outline: 2px solid #4a90e2;
			outline-offset: 2px;
		}
		
		:focus:not(:focus-visible) {
			outline: none;
		}
		
		:focus-visible {
			outline: 2px solid #4a90e2;
			outline-offset: 2px;
		}
	';
	
	// Add focus styles to head.
	wp_add_inline_style( 'aqualuxe-style', $focus_styles );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_focus_styles' );

/**
 * Add screen reader text CSS.
 */
function aqualuxe_screen_reader_text_css() {
	// Skip if in admin.
	if ( is_admin() ) {
		return;
	}
	
	// Add screen reader text CSS.
	$screen_reader_text_css = '
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
	';
	
	// Add screen reader text CSS to head.
	wp_add_inline_style( 'aqualuxe-style', $screen_reader_text_css );
}
add_action( 'wp_head', 'aqualuxe_screen_reader_text_css' );

/**
 * Add skip link focus fix.
 */
function aqualuxe_skip_link_focus_fix() {
	// Skip if in admin.
	if ( is_admin() ) {
		return;
	}
	
	// Add skip link focus fix script.
	wp_enqueue_script(
		'aqualuxe-skip-link-focus-fix',
		get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js',
		array(),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_skip_link_focus_fix' );

/**
 * Add accessibility settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_accessibility_customizer_settings( $wp_customize ) {
	// Add accessibility section.
	$wp_customize->add_section(
		'aqualuxe_accessibility',
		array(
			'title'    => __( 'Accessibility', 'aqualuxe' ),
			'priority' => 90,
		)
	);
	
	// Add high contrast mode setting.
	$wp_customize->add_setting(
		'aqualuxe_high_contrast_mode',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_high_contrast_mode',
		array(
			'label'       => __( 'Enable High Contrast Mode', 'aqualuxe' ),
			'description' => __( 'Increases contrast for better readability.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
	
	// Add font size setting.
	$wp_customize->add_setting(
		'aqualuxe_font_size',
		array(
			'default'           => 'medium',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_font_size',
		array(
			'label'       => __( 'Base Font Size', 'aqualuxe' ),
			'description' => __( 'Set the base font size for better readability.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'select',
			'choices'     => array(
				'small'  => __( 'Small', 'aqualuxe' ),
				'medium' => __( 'Medium', 'aqualuxe' ),
				'large'  => __( 'Large', 'aqualuxe' ),
			),
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
			'label'       => __( 'Reduce Motion', 'aqualuxe' ),
			'description' => __( 'Reduces or eliminates animations and transitions.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
	
	// Add focus indicator setting.
	$wp_customize->add_setting(
		'aqualuxe_focus_indicator',
		array(
			'default'           => 'default',
			'sanitize_callback' => 'aqualuxe_sanitize_select',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_focus_indicator',
		array(
			'label'       => __( 'Focus Indicator Style', 'aqualuxe' ),
			'description' => __( 'Choose the style of the focus indicator for keyboard navigation.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'select',
			'choices'     => array(
				'default' => __( 'Default', 'aqualuxe' ),
				'bold'    => __( 'Bold', 'aqualuxe' ),
				'subtle'  => __( 'Subtle', 'aqualuxe' ),
			),
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_customizer_settings' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Sanitize select values.
 *
 * @param string $input The select value.
 * @param WP_Customize_Setting $setting The setting object.
 * @return string The sanitized select value.
 */
function aqualuxe_sanitize_select( $input, $setting ) {
	// Get the list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// Return input if valid or return default if not.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Apply high contrast mode.
 */
function aqualuxe_high_contrast_mode() {
	// Skip if high contrast mode is disabled.
	if ( ! get_theme_mod( 'aqualuxe_high_contrast_mode', false ) ) {
		return;
	}
	
	// Add high contrast mode CSS.
	$high_contrast_css = '
		body {
			color: #000000;
			background-color: #ffffff;
		}
		
		a {
			color: #0000ee;
		}
		
		a:visited {
			color: #551a8b;
		}
		
		a:hover, a:focus, a:active {
			color: #0000ee;
			text-decoration: underline;
		}
		
		.site-header {
			background-color: #000000;
			color: #ffffff;
		}
		
		.site-header a {
			color: #ffffff;
		}
		
		.site-header a:hover, .site-header a:focus, .site-header a:active {
			color: #ffffff;
			text-decoration: underline;
		}
		
		.site-footer {
			background-color: #000000;
			color: #ffffff;
		}
		
		.site-footer a {
			color: #ffffff;
		}
		
		.site-footer a:hover, .site-footer a:focus, .site-footer a:active {
			color: #ffffff;
			text-decoration: underline;
		}
		
		button, input[type="button"], input[type="reset"], input[type="submit"], .button {
			background-color: #000000;
			color: #ffffff;
			border: 2px solid #000000;
		}
		
		button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .button:hover,
		button:focus, input[type="button"]:focus, input[type="reset"]:focus, input[type="submit"]:focus, .button:focus,
		button:active, input[type="button"]:active, input[type="reset"]:active, input[type="submit"]:active, .button:active {
			background-color: #ffffff;
			color: #000000;
			border: 2px solid #000000;
		}
	';
	
	// Add high contrast mode CSS to head.
	wp_add_inline_style( 'aqualuxe-style', $high_contrast_css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_high_contrast_mode' );

/**
 * Apply font size.
 */
function aqualuxe_font_size() {
	// Get font size setting.
	$font_size = get_theme_mod( 'aqualuxe_font_size', 'medium' );
	
	// Skip if font size is medium.
	if ( 'medium' === $font_size ) {
		return;
	}
	
	// Add font size CSS.
	$font_size_css = '';
	
	switch ( $font_size ) {
		case 'small':
			$font_size_css = '
				html {
					font-size: 14px;
				}
			';
			break;
		case 'large':
			$font_size_css = '
				html {
					font-size: 18px;
				}
			';
			break;
	}
	
	// Add font size CSS to head.
	wp_add_inline_style( 'aqualuxe-style', $font_size_css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_font_size' );

/**
 * Apply reduced motion.
 */
function aqualuxe_reduced_motion() {
	// Skip if reduced motion is disabled.
	if ( ! get_theme_mod( 'aqualuxe_reduced_motion', false ) ) {
		return;
	}
	
	// Add reduced motion CSS.
	$reduced_motion_css = '
		* {
			transition: none !important;
			animation: none !important;
		}
	';
	
	// Add reduced motion CSS to head.
	wp_add_inline_style( 'aqualuxe-style', $reduced_motion_css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_reduced_motion' );

/**
 * Apply focus indicator style.
 */
function aqualuxe_focus_indicator_style() {
	// Get focus indicator style setting.
	$focus_indicator = get_theme_mod( 'aqualuxe_focus_indicator', 'default' );
	
	// Skip if focus indicator is default.
	if ( 'default' === $focus_indicator ) {
		return;
	}
	
	// Add focus indicator CSS.
	$focus_indicator_css = '';
	
	switch ( $focus_indicator ) {
		case 'bold':
			$focus_indicator_css = '
				:focus {
					outline: 3px solid #4a90e2;
					outline-offset: 3px;
				}
				
				:focus:not(:focus-visible) {
					outline: none;
				}
				
				:focus-visible {
					outline: 3px solid #4a90e2;
					outline-offset: 3px;
				}
			';
			break;
		case 'subtle':
			$focus_indicator_css = '
				:focus {
					outline: 1px dotted #4a90e2;
					outline-offset: 1px;
				}
				
				:focus:not(:focus-visible) {
					outline: none;
				}
				
				:focus-visible {
					outline: 1px dotted #4a90e2;
					outline-offset: 1px;
				}
			';
			break;
	}
	
	// Add focus indicator CSS to head.
	wp_add_inline_style( 'aqualuxe-style', $focus_indicator_css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_focus_indicator_style' );

/**
 * Add accessibility toolbar.
 */
function aqualuxe_accessibility_toolbar() {
	// Skip if in admin.
	if ( is_admin() ) {
		return;
	}
	
	// Skip if accessibility toolbar is disabled.
	if ( ! get_theme_mod( 'aqualuxe_accessibility_toolbar', false ) ) {
		return;
	}
	
	// Add accessibility toolbar HTML.
	?>
	<div class="accessibility-toolbar" aria-label="<?php esc_attr_e( 'Accessibility Tools', 'aqualuxe' ); ?>">
		<button class="accessibility-toolbar-toggle" aria-expanded="false" aria-controls="accessibility-toolbar-controls">
			<span class="screen-reader-text"><?php esc_html_e( 'Accessibility Tools', 'aqualuxe' ); ?></span>
			<i class="fas fa-universal-access" aria-hidden="true"></i>
		</button>
		
		<div id="accessibility-toolbar-controls" class="accessibility-toolbar-controls" hidden>
			<button class="accessibility-toolbar-button accessibility-toolbar-button-contrast" data-action="toggle-contrast">
				<span class="accessibility-toolbar-button-text"><?php esc_html_e( 'High Contrast', 'aqualuxe' ); ?></span>
			</button>
			
			<button class="accessibility-toolbar-button accessibility-toolbar-button-font-size-increase" data-action="increase-font-size">
				<span class="accessibility-toolbar-button-text"><?php esc_html_e( 'Increase Font Size', 'aqualuxe' ); ?></span>
			</button>
			
			<button class="accessibility-toolbar-button accessibility-toolbar-button-font-size-decrease" data-action="decrease-font-size">
				<span class="accessibility-toolbar-button-text"><?php esc_html_e( 'Decrease Font Size', 'aqualuxe' ); ?></span>
			</button>
			
			<button class="accessibility-toolbar-button accessibility-toolbar-button-reduced-motion" data-action="toggle-reduced-motion">
				<span class="accessibility-toolbar-button-text"><?php esc_html_e( 'Reduce Motion', 'aqualuxe' ); ?></span>
			</button>
		</div>
	</div>
	<?php
	
	// Add accessibility toolbar script.
	wp_enqueue_script(
		'aqualuxe-accessibility-toolbar',
		get_template_directory_uri() . '/assets/js/accessibility-toolbar.js',
		array( 'jquery' ),
		AQUALUXE_VERSION,
		true
	);
}
add_action( 'wp_footer', 'aqualuxe_accessibility_toolbar' );

/**
 * Add accessibility toolbar setting to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_accessibility_toolbar_customizer_setting( $wp_customize ) {
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
			'description' => __( 'Adds a toolbar with accessibility options.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_toolbar_customizer_setting' );