<?php
/**
 * Skip Links Implementation
 *
 * Functions for implementing skip links and keyboard navigation.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add skip links to the theme.
 *
 * @return void
 */
function aqualuxe_skip_links() {
	// Check if skip links are enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_skip_links', true ) ) {
		return;
	}
	
	// Skip links HTML.
	?>
	<div class="skip-links">
		<a href="#primary" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>
		<a href="#site-navigation" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to navigation', 'aqualuxe' ); ?></a>
		<a href="#colophon" class="skip-link screen-reader-text"><?php esc_html_e( 'Skip to footer', 'aqualuxe' ); ?></a>
	</div>
	<?php
}
add_action( 'wp_body_open', 'aqualuxe_skip_links', 5 );

/**
 * Add skip link focus fix.
 *
 * @return void
 */
function aqualuxe_skip_link_focus_fix() {
	// Check if skip links are enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_skip_links', true ) ) {
		return;
	}
	
	// Skip link focus fix script.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'aqualuxe_skip_link_focus_fix' );

/**
 * Add keyboard navigation styles.
 *
 * @return void
 */
function aqualuxe_keyboard_navigation_styles() {
	// Check if keyboard navigation is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_keyboard_navigation', true ) ) {
		return;
	}
	
	// Keyboard navigation styles.
	?>
	<style>
	/* Keyboard navigation styles */
	:focus {
		outline: 2px solid #4a90e2;
		outline-offset: 2px;
	}
	
	.using-mouse :focus {
		outline: none;
	}
	
	.skip-link {
		position: absolute;
		top: -100px;
		left: 0;
		background-color: #f1f1f1;
		border-radius: 0 0 3px 3px;
		color: #21759b;
		display: block;
		font-size: 14px;
		font-weight: 700;
		height: auto;
		line-height: normal;
		padding: 15px 23px 14px;
		text-decoration: none;
		width: auto;
		z-index: 100000;
		transition: top 0.3s ease;
	}
	
	.skip-link:focus {
		top: 0;
	}
	
	.main-navigation .menu-item-has-children > a:focus + .sub-menu,
	.main-navigation .menu-item-has-children > a:focus-within + .sub-menu,
	.main-navigation .menu-item-has-children:hover > .sub-menu {
		display: block;
		opacity: 1;
		visibility: visible;
	}
	
	.main-navigation .menu-item-has-children > a:focus,
	.main-navigation .menu-item-has-children > a:focus-within {
		outline: 2px solid #4a90e2;
		outline-offset: 2px;
	}
	
	.main-navigation .sub-menu {
		display: none;
		opacity: 0;
		visibility: hidden;
		transition: opacity 0.3s ease, visibility 0.3s ease;
	}
	
	.main-navigation .sub-menu .menu-item > a:focus {
		outline: 2px solid #4a90e2;
		outline-offset: 2px;
	}
	
	@media (max-width: 1023px) {
		.main-navigation .menu-item-has-children.sub-menu-open > .sub-menu {
			display: block;
			opacity: 1;
			visibility: visible;
		}
	}
	</style>
	<?php
}
add_action( 'wp_head', 'aqualuxe_keyboard_navigation_styles' );

/**
 * Add keyboard navigation script.
 *
 * @return void
 */
function aqualuxe_keyboard_navigation_script() {
	// Check if keyboard navigation is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_keyboard_navigation', true ) ) {
		return;
	}
	
	// Keyboard navigation script.
	?>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		// Add 'using-mouse' class to body when mouse is used
		document.body.addEventListener('mousedown', function() {
			document.body.classList.add('using-mouse');
		});
		
		// Remove 'using-mouse' class when keyboard is used
		document.body.addEventListener('keydown', function(e) {
			if (e.key === 'Tab') {
				document.body.classList.remove('using-mouse');
			}
		});
		
		// Handle dropdown menu keyboard navigation
		var menuItems = document.querySelectorAll('.menu-item-has-children');
		
		menuItems.forEach(function(menuItem) {
			var link = menuItem.querySelector('a');
			var subMenu = menuItem.querySelector('.sub-menu');
			
			if (link && subMenu) {
				// Add aria attributes
				link.setAttribute('aria-haspopup', 'true');
				link.setAttribute('aria-expanded', 'false');
				
				// Toggle submenu on Enter or Space key
				link.addEventListener('keydown', function(e) {
					if (e.key === 'Enter' || e.key === ' ') {
						e.preventDefault();
						
						var expanded = this.getAttribute('aria-expanded') === 'true' || false;
						this.setAttribute('aria-expanded', !expanded);
						
						if (expanded) {
							menuItem.classList.remove('sub-menu-open');
						} else {
							menuItem.classList.add('sub-menu-open');
							
							// Focus first item in submenu
							var firstSubMenuItem = subMenu.querySelector('a');
							if (firstSubMenuItem) {
								firstSubMenuItem.focus();
							}
						}
					}
				});
				
				// Close submenu when tabbing out of last item
				var subMenuItems = subMenu.querySelectorAll('a');
				var lastSubMenuItem = subMenuItems[subMenuItems.length - 1];
				
				if (lastSubMenuItem) {
					lastSubMenuItem.addEventListener('keydown', function(e) {
						if (e.key === 'Tab' && !e.shiftKey) {
							link.setAttribute('aria-expanded', 'false');
							menuItem.classList.remove('sub-menu-open');
						}
					});
				}
				
				// Close submenu when pressing Escape
				subMenu.addEventListener('keydown', function(e) {
					if (e.key === 'Escape') {
						link.setAttribute('aria-expanded', 'false');
						menuItem.classList.remove('sub-menu-open');
						link.focus();
					}
				});
			}
		});
		
		// Handle mobile menu toggle
		var menuToggle = document.querySelector('.menu-toggle');
		var primaryMenu = document.getElementById('primary-menu');
		
		if (menuToggle && primaryMenu) {
			menuToggle.setAttribute('aria-controls', 'primary-menu');
			menuToggle.setAttribute('aria-expanded', 'false');
			
			menuToggle.addEventListener('click', function() {
				var expanded = this.getAttribute('aria-expanded') === 'true' || false;
				this.setAttribute('aria-expanded', !expanded);
				
				if (expanded) {
					primaryMenu.classList.remove('toggled');
				} else {
					primaryMenu.classList.add('toggled');
					
					// Focus first item in menu
					var firstMenuItem = primaryMenu.querySelector('a');
					if (firstMenuItem) {
						firstMenuItem.focus();
					}
				}
			});
			
			// Close menu when pressing Escape
			primaryMenu.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && primaryMenu.classList.contains('toggled')) {
					menuToggle.setAttribute('aria-expanded', 'false');
					primaryMenu.classList.remove('toggled');
					menuToggle.focus();
				}
			});
		}
		
		// Handle search toggle
		var searchToggle = document.querySelector('.search-toggle');
		var searchForm = document.querySelector('.search-form');
		
		if (searchToggle && searchForm) {
			var searchInput = searchForm.querySelector('input[type="search"]');
			
			searchToggle.setAttribute('aria-controls', 'search-form');
			searchToggle.setAttribute('aria-expanded', 'false');
			
			searchToggle.addEventListener('click', function() {
				var expanded = this.getAttribute('aria-expanded') === 'true' || false;
				this.setAttribute('aria-expanded', !expanded);
				
				if (expanded) {
					searchForm.classList.remove('toggled');
				} else {
					searchForm.classList.add('toggled');
					
					// Focus search input
					if (searchInput) {
						searchInput.focus();
					}
				}
			});
			
			// Close search when pressing Escape
			searchForm.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' && searchForm.classList.contains('toggled')) {
					searchToggle.setAttribute('aria-expanded', 'false');
					searchForm.classList.remove('toggled');
					searchToggle.focus();
				}
			});
		}
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_keyboard_navigation_script' );

/**
 * Add screen reader text class.
 *
 * @return void
 */
function aqualuxe_screen_reader_text_styles() {
	?>
	<style>
	/* Screen reader text styles */
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
		font-weight: 700;
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
add_action( 'wp_head', 'aqualuxe_screen_reader_text_styles' );

/**
 * Add skip links settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 * @return void
 */
function aqualuxe_skip_links_customizer( $wp_customize ) {
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
			'label'       => __( 'Enable skip links', 'aqualuxe' ),
			'description' => __( 'Add skip links for keyboard navigation.', 'aqualuxe' ),
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
			'label'       => __( 'Enable keyboard navigation', 'aqualuxe' ),
			'description' => __( 'Add keyboard navigation support.', 'aqualuxe' ),
			'section'     => 'aqualuxe_accessibility',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_skip_links_customizer' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}