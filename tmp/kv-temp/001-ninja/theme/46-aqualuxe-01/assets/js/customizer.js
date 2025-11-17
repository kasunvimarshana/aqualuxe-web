/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Container width.
	wp.customize( 'aqualuxe_container_width', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--container-width', to + 'px' );
		} );
	} );

	// Body font size.
	wp.customize( 'aqualuxe_body_font_size', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--body-font-size', to + 'px' );
		} );
	} );

	// Line height.
	wp.customize( 'aqualuxe_line_height', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--body-line-height', to );
		} );
	} );

	// Heading line height.
	wp.customize( 'aqualuxe_heading_line_height', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--heading-line-height', to );
		} );
	} );

	// Font weight.
	wp.customize( 'aqualuxe_font_weight', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--body-font-weight', to );
		} );
	} );

	// Heading font weight.
	wp.customize( 'aqualuxe_heading_font_weight', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--heading-font-weight', to );
		} );
	} );

	// Primary color.
	wp.customize( 'aqualuxe_primary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-primary', to );
		} );
	} );

	// Secondary color.
	wp.customize( 'aqualuxe_secondary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-secondary', to );
		} );
	} );

	// Body text color.
	wp.customize( 'aqualuxe_body_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-text', to );
		} );
	} );

	// Heading color.
	wp.customize( 'aqualuxe_heading_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-heading', to );
		} );
	} );

	// Link color.
	wp.customize( 'aqualuxe_link_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-link', to );
		} );
	} );

	// Link hover color.
	wp.customize( 'aqualuxe_link_hover_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-link-hover', to );
		} );
	} );

	// Button text color.
	wp.customize( 'aqualuxe_button_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-button-text', to );
		} );
	} );

	// Button background color.
	wp.customize( 'aqualuxe_button_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-button-bg', to );
		} );
	} );

	// Button hover background color.
	wp.customize( 'aqualuxe_button_hover_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-button-bg-hover', to );
		} );
	} );

	// Header background color.
	wp.customize( 'aqualuxe_header_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-header-bg', to );
		} );
	} );

	// Header text color.
	wp.customize( 'aqualuxe_header_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-header-text', to );
		} );
	} );

	// Footer background color.
	wp.customize( 'aqualuxe_footer_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-footer-bg', to );
		} );
	} );

	// Footer text color.
	wp.customize( 'aqualuxe_footer_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-footer-text', to );
		} );
	} );

	// Dark mode background color.
	wp.customize( 'aqualuxe_dark_mode_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-dark-mode-bg', to );
		} );
	} );

	// Dark mode text color.
	wp.customize( 'aqualuxe_dark_mode_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty( '--color-dark-mode-text', to );
		} );
	} );

} )( jQuery );