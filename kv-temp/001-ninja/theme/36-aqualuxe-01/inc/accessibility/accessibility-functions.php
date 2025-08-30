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
 * Add skip link at the beginning of the page.
 *
 * @return void
 */
function aqualuxe_skip_link() {
	// The skip link is already added in header.php, but we'll ensure it's properly implemented.
	// This function can be used to customize the skip link if needed.
}

/**
 * Add ARIA attributes to menu toggles.
 *
 * @param string $nav_menu The HTML content for the navigation menu.
 * @param object $args An object containing wp_nav_menu() arguments.
 * @return string Modified HTML content for the navigation menu.
 */
function aqualuxe_nav_menu_aria_attributes( $nav_menu, $args ) {
	if ( 'primary-menu' === $args->menu_id ) {
		// Add ARIA attributes to the menu toggle button.
		$nav_menu = str_replace( 'class="menu-toggle"', 'class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"', $nav_menu );
	}

	return $nav_menu;
}
add_filter( 'wp_nav_menu', 'aqualuxe_nav_menu_aria_attributes', 10, 2 );

/**
 * Add screen reader text for icons.
 *
 * @param string $html The HTML content.
 * @return string Modified HTML content.
 */
function aqualuxe_add_screen_reader_text( $html ) {
	// Add screen reader text for social icons.
	$html = preg_replace_callback( '/<a([^>]+)class="([^"]*)(social-icon|social-link)([^"]*)"><i([^>]+)><\/i><\/a>/i', function( $matches ) {
		$link_attrs = $matches[1];
		$classes = $matches[2] . $matches[3] . $matches[4];
		$icon_attrs = $matches[5];
		
		// Extract the social network name from the class.
		preg_match( '/\b(facebook|twitter|instagram|linkedin|youtube|pinterest|github|dribbble|behance|reddit|tumblr|vimeo|flickr|soundcloud|spotify|apple|android|whatsapp|telegram|tiktok|snapchat|vk|weibo|wechat|discord|slack|medium|quora|twitch|foursquare|yelp|tripadvisor|rss)\b/i', $classes, $network );
		
		if ( ! empty( $network[1] ) ) {
			$network_name = ucfirst( $network[1] );
			return '<a' . $link_attrs . 'class="' . $classes . '"><span class="screen-reader-text">' . esc_html( $network_name ) . '</span><i' . $icon_attrs . '></i></a>';
		}
		
		return $matches[0];
	}, $html );
	
	// Add screen reader text for search icon.
	$html = preg_replace_callback( '/<button([^>]+)class="([^"]*)(search-toggle|search-submit)([^"]*)"><i([^>]+)><\/i><\/button>/i', function( $matches ) {
		$button_attrs = $matches[1];
		$classes = $matches[2] . $matches[3] . $matches[4];
		$icon_attrs = $matches[5];
		
		return '<button' . $button_attrs . 'class="' . $classes . '"><span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span><i' . $icon_attrs . '></i></button>';
	}, $html );
	
	// Add screen reader text for cart icon.
	$html = preg_replace_callback( '/<a([^>]+)class="([^"]*)(cart-contents|cart-link)([^"]*)"><i([^>]+)><\/i><\/a>/i', function( $matches ) {
		$link_attrs = $matches[1];
		$classes = $matches[2] . $matches[3] . $matches[4];
		$icon_attrs = $matches[5];
		
		return '<a' . $link_attrs . 'class="' . $classes . '"><span class="screen-reader-text">' . esc_html__( 'View your shopping cart', 'aqualuxe' ) . '</span><i' . $icon_attrs . '></i></a>';
	}, $html );
	
	// Add screen reader text for menu toggle.
	$html = preg_replace_callback( '/<button([^>]+)class="([^"]*)(menu-toggle|navbar-toggle)([^"]*)"><span([^>]+)><\/span><\/button>/i', function( $matches ) {
		$button_attrs = $matches[1];
		$classes = $matches[2] . $matches[3] . $matches[4];
		$span_attrs = $matches[5];
		
		return '<button' . $button_attrs . 'class="' . $classes . '"><span' . $span_attrs . '></span><span class="screen-reader-text">' . esc_html__( 'Toggle Menu', 'aqualuxe' ) . '</span></button>';
	}, $html );
	
	return $html;
}
add_filter( 'the_content', 'aqualuxe_add_screen_reader_text' );
add_filter( 'get_search_form', 'aqualuxe_add_screen_reader_text' );
add_filter( 'wp_nav_menu', 'aqualuxe_add_screen_reader_text' );
add_filter( 'widget_text', 'aqualuxe_add_screen_reader_text' );

/**
 * Add ARIA attributes to dropdown menus.
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array Modified HTML attributes.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	// Add ARIA attributes to menu items with children.
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-haspopup'] = 'true';
		$atts['aria-expanded'] = 'false';
	}
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );

/**
 * Add ARIA attributes to comment form.
 *
 * @param array $defaults The default comment form arguments.
 * @return array Modified comment form arguments.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
	// Add ARIA attributes to comment form fields.
	$defaults['comment_field'] = str_replace( '<textarea', '<textarea aria-required="true" aria-label="' . esc_attr__( 'Comment', 'aqualuxe' ) . '"', $defaults['comment_field'] );
	
	// Add ARIA attributes to comment form submit button.
	$defaults['submit_button'] = str_replace( 'class="submit"', 'class="submit" aria-label="' . esc_attr__( 'Submit Comment', 'aqualuxe' ) . '"', $defaults['submit_button'] );
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Add ARIA attributes to search form.
 *
 * @param string $form The search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form_aria( $form ) {
	// Add ARIA attributes to search form.
	$form = str_replace( 'class="search-form"', 'class="search-form" role="search"', $form );
	
	// Add ARIA attributes to search input.
	$form = str_replace( 'placeholder="', 'aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '" placeholder="', $form );
	
	// Add screen reader text to search submit button.
	$form = str_replace( 'class="search-submit"', 'class="search-submit" aria-label="' . esc_attr__( 'Submit Search', 'aqualuxe' ) . '"', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form_aria' );

/**
 * Add focus styles for keyboard navigation.
 */
function aqualuxe_focus_styles() {
	?>
	<style>
		/* Focus styles for keyboard navigation */
		a:focus,
		button:focus,
		input:focus,
		textarea:focus,
		select:focus,
		[tabindex]:focus {
			outline: 2px solid #4a90e2 !important;
			outline-offset: 2px !important;
			box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.3) !important;
		}
		
		/* Hide focus styles for mouse users */
		.js-focus-visible :focus:not(.focus-visible) {
			outline: none !important;
			box-shadow: none !important;
		}
		
		/* Show focus styles for keyboard users */
		.js-focus-visible .focus-visible {
			outline: 2px solid #4a90e2 !important;
			outline-offset: 2px !important;
			box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.3) !important;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'aqualuxe_focus_styles' );

/**
 * Enqueue focus-visible polyfill for better keyboard navigation.
 */
function aqualuxe_enqueue_focus_visible() {
	wp_enqueue_script(
		'focus-visible',
		get_template_directory_uri() . '/assets/js/focus-visible.min.js',
		array(),
		'5.2.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_focus_visible' );

/**
 * Add ARIA attributes to pagination.
 *
 * @param string $template The pagination template.
 * @return string Modified pagination template.
 */
function aqualuxe_pagination_aria( $template ) {
	// Add ARIA attributes to pagination.
	$template = str_replace( '<nav class="navigation', '<nav class="navigation" aria-label="' . esc_attr__( 'Posts Navigation', 'aqualuxe' ) . '"', $template );
	
	// Add screen reader text to pagination links.
	$template = str_replace( 'class="prev', 'aria-label="' . esc_attr__( 'Previous Page', 'aqualuxe' ) . '" class="prev', $template );
	$template = str_replace( 'class="next', 'aria-label="' . esc_attr__( 'Next Page', 'aqualuxe' ) . '" class="next', $template );
	
	return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_pagination_aria' );

/**
 * Add ARIA attributes to post navigation.
 *
 * @param string $link The post navigation link.
 * @param string $direction The direction of the post navigation.
 * @return string Modified post navigation link.
 */
function aqualuxe_post_navigation_aria( $link, $direction ) {
	if ( 'previous' === $direction ) {
		$link = str_replace( 'rel="prev"', 'rel="prev" aria-label="' . esc_attr__( 'Previous Post', 'aqualuxe' ) . '"', $link );
	} elseif ( 'next' === $direction ) {
		$link = str_replace( 'rel="next"', 'rel="next" aria-label="' . esc_attr__( 'Next Post', 'aqualuxe' ) . '"', $link );
	}
	
	return $link;
}
add_filter( 'previous_post_link', function( $link ) { return aqualuxe_post_navigation_aria( $link, 'previous' ); } );
add_filter( 'next_post_link', function( $link ) { return aqualuxe_post_navigation_aria( $link, 'next' ); } );

/**
 * Add ARIA attributes to menu toggle button.
 *
 * @return void
 */
function aqualuxe_menu_toggle_script() {
	?>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Menu toggle button
			var menuToggle = document.querySelector('.menu-toggle');
			var primaryMenu = document.getElementById('primary-menu');
			
			if (menuToggle && primaryMenu) {
				menuToggle.addEventListener('click', function() {
					var expanded = this.getAttribute('aria-expanded') === 'true' || false;
					this.setAttribute('aria-expanded', !expanded);
					primaryMenu.classList.toggle('toggled');
				});
			}
			
			// Dropdown menu toggle
			var dropdownToggle = document.querySelectorAll('.menu-item-has-children > a');
			
			dropdownToggle.forEach(function(toggle) {
				toggle.addEventListener('click', function(e) {
					var parent = this.parentNode;
					var expanded = this.getAttribute('aria-expanded') === 'true' || false;
					
					if (window.innerWidth < 1024) {
						e.preventDefault();
						this.setAttribute('aria-expanded', !expanded);
						parent.classList.toggle('sub-menu-open');
					}
				});
			});
			
			// Close menu when pressing Escape key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && primaryMenu.classList.contains('toggled')) {
					menuToggle.setAttribute('aria-expanded', 'false');
					primaryMenu.classList.remove('toggled');
					menuToggle.focus();
				}
			});
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_menu_toggle_script' );

/**
 * Add accessibility settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_accessibility_customizer( $wp_customize ) {
	// Add accessibility section.
	$wp_customize->add_section(
		'aqualuxe_accessibility',
		array(
			'title'    => __( 'Accessibility', 'aqualuxe' ),
			'priority' => 90,
		)
	);
	
	// Add skip link setting.
	$wp_customize->add_setting(
		'aqualuxe_skip_link',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_skip_link',
		array(
			'label'   => __( 'Enable skip link', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
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
			'label'   => __( 'Enable keyboard navigation', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
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
			'label'   => __( 'Enable focus styles', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	
	// Add screen reader text setting.
	$wp_customize->add_setting(
		'aqualuxe_screen_reader_text',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_screen_reader_text',
		array(
			'label'   => __( 'Enable screen reader text', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	
	// Add ARIA attributes setting.
	$wp_customize->add_setting(
		'aqualuxe_aria_attributes',
		array(
			'default'           => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_aria_attributes',
		array(
			'label'   => __( 'Enable ARIA attributes', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	
	// Add high contrast mode setting.
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
			'label'   => __( 'Enable high contrast mode', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
	
	// Add font size adjustment setting.
	$wp_customize->add_setting(
		'aqualuxe_font_size_adjustment',
		array(
			'default'           => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);
	
	$wp_customize->add_control(
		'aqualuxe_font_size_adjustment',
		array(
			'label'   => __( 'Enable font size adjustment', 'aqualuxe' ),
			'section' => 'aqualuxe_accessibility',
			'type'    => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_accessibility_customizer' );

/**
 * Add high contrast mode styles.
 *
 * @return void
 */
function aqualuxe_high_contrast_mode() {
	// Check if high contrast mode is enabled.
	if ( ! get_theme_mod( 'aqualuxe_high_contrast', false ) ) {
		return;
	}
	
	?>
	<style>
		/* High contrast mode styles */
		body.high-contrast {
			background-color: #000 !important;
			color: #fff !important;
		}
		
		body.high-contrast a {
			color: #ffff00 !important;
		}
		
		body.high-contrast a:hover,
		body.high-contrast a:focus {
			color: #ffff99 !important;
			text-decoration: underline !important;
		}
		
		body.high-contrast button,
		body.high-contrast input[type="button"],
		body.high-contrast input[type="reset"],
		body.high-contrast input[type="submit"] {
			background-color: #fff !important;
			color: #000 !important;
			border: 2px solid #fff !important;
		}
		
		body.high-contrast button:hover,
		body.high-contrast input[type="button"]:hover,
		body.high-contrast input[type="reset"]:hover,
		body.high-contrast input[type="submit"]:hover {
			background-color: #000 !important;
			color: #fff !important;
		}
		
		body.high-contrast input[type="text"],
		body.high-contrast input[type="email"],
		body.high-contrast input[type="url"],
		body.high-contrast input[type="password"],
		body.high-contrast input[type="search"],
		body.high-contrast input[type="number"],
		body.high-contrast input[type="tel"],
		body.high-contrast input[type="range"],
		body.high-contrast input[type="date"],
		body.high-contrast input[type="month"],
		body.high-contrast input[type="week"],
		body.high-contrast input[type="time"],
		body.high-contrast input[type="datetime"],
		body.high-contrast input[type="datetime-local"],
		body.high-contrast input[type="color"],
		body.high-contrast textarea {
			background-color: #000 !important;
			color: #fff !important;
			border: 2px solid #fff !important;
		}
		
		body.high-contrast img {
			filter: grayscale(100%) contrast(150%) !important;
		}
	</style>
	
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add high contrast toggle button
			var header = document.querySelector('.site-header');
			
			if (header) {
				var highContrastButton = document.createElement('button');
				highContrastButton.className = 'high-contrast-toggle';
				highContrastButton.setAttribute('aria-label', '<?php echo esc_attr__( 'Toggle High Contrast Mode', 'aqualuxe' ); ?>');
				highContrastButton.innerHTML = '<span class="screen-reader-text"><?php echo esc_html__( 'Toggle High Contrast Mode', 'aqualuxe' ); ?></span>';
				
				header.appendChild(highContrastButton);
				
				// Toggle high contrast mode
				highContrastButton.addEventListener('click', function() {
					document.body.classList.toggle('high-contrast');
					
					// Save preference in localStorage
					if (document.body.classList.contains('high-contrast')) {
						localStorage.setItem('aqualuxe-high-contrast', 'true');
					} else {
						localStorage.setItem('aqualuxe-high-contrast', 'false');
					}
				});
				
				// Check if high contrast mode is enabled
				if (localStorage.getItem('aqualuxe-high-contrast') === 'true') {
					document.body.classList.add('high-contrast');
				}
			}
		});
	</script>
	<?php
}
add_action( 'wp_head', 'aqualuxe_high_contrast_mode' );

/**
 * Add font size adjustment controls.
 *
 * @return void
 */
function aqualuxe_font_size_adjustment() {
	// Check if font size adjustment is enabled.
	if ( ! get_theme_mod( 'aqualuxe_font_size_adjustment', false ) ) {
		return;
	}
	
	?>
	<style>
		/* Font size adjustment styles */
		body.font-size-small {
			font-size: 14px !important;
		}
		
		body.font-size-medium {
			font-size: 16px !important;
		}
		
		body.font-size-large {
			font-size: 18px !important;
		}
		
		body.font-size-xlarge {
			font-size: 20px !important;
		}
		
		.font-size-controls {
			display: flex;
			align-items: center;
			margin-left: 1rem;
		}
		
		.font-size-controls button {
			background: none;
			border: 1px solid #ccc;
			border-radius: 3px;
			padding: 0.25rem 0.5rem;
			margin: 0 0.25rem;
			font-size: 0.875rem;
			cursor: pointer;
		}
		
		.font-size-controls button:hover,
		.font-size-controls button:focus {
			background-color: #f0f0f0;
		}
		
		.font-size-controls button.active {
			background-color: #4a90e2;
			color: #fff;
			border-color: #4a90e2;
		}
	</style>
	
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Add font size adjustment controls
			var header = document.querySelector('.site-header');
			
			if (header) {
				var fontSizeControls = document.createElement('div');
				fontSizeControls.className = 'font-size-controls';
				fontSizeControls.innerHTML = '<span class="screen-reader-text"><?php echo esc_html__( 'Adjust Font Size', 'aqualuxe' ); ?></span>' +
					'<button type="button" data-size="small" aria-label="<?php echo esc_attr__( 'Small Font Size', 'aqualuxe' ); ?>">A</button>' +
					'<button type="button" data-size="medium" aria-label="<?php echo esc_attr__( 'Medium Font Size', 'aqualuxe' ); ?>">A</button>' +
					'<button type="button" data-size="large" aria-label="<?php echo esc_attr__( 'Large Font Size', 'aqualuxe' ); ?>">A</button>' +
					'<button type="button" data-size="xlarge" aria-label="<?php echo esc_attr__( 'Extra Large Font Size', 'aqualuxe' ); ?>">A</button>';
				
				header.appendChild(fontSizeControls);
				
				// Set font size
				var fontSizeButtons = fontSizeControls.querySelectorAll('button');
				
				fontSizeButtons.forEach(function(button) {
					button.addEventListener('click', function() {
						var size = this.getAttribute('data-size');
						
						// Remove all font size classes
						document.body.classList.remove('font-size-small', 'font-size-medium', 'font-size-large', 'font-size-xlarge');
						
						// Add selected font size class
						document.body.classList.add('font-size-' + size);
						
						// Update active button
						fontSizeButtons.forEach(function(btn) {
							btn.classList.remove('active');
						});
						
						this.classList.add('active');
						
						// Save preference in localStorage
						localStorage.setItem('aqualuxe-font-size', size);
					});
				});
				
				// Check if font size preference is saved
				var savedFontSize = localStorage.getItem('aqualuxe-font-size');
				
				if (savedFontSize) {
					document.body.classList.add('font-size-' + savedFontSize);
					
					// Update active button
					fontSizeButtons.forEach(function(button) {
						if (button.getAttribute('data-size') === savedFontSize) {
							button.classList.add('active');
						}
					});
				} else {
					// Set default font size
					document.body.classList.add('font-size-medium');
					
					// Update active button
					fontSizeButtons.forEach(function(button) {
						if (button.getAttribute('data-size') === 'medium') {
							button.classList.add('active');
						}
					});
				}
			}
		});
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_font_size_adjustment' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}