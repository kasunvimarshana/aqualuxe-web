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

	// Primary color.
	wp.customize( 'aqualuxe_primary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--primary-color', to);
		} );
	} );

	// Secondary color.
	wp.customize( 'aqualuxe_secondary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--secondary-color', to);
		} );
	} );

	// Accent color.
	wp.customize( 'aqualuxe_accent_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--accent-color', to);
		} );
	} );

	// Text color.
	wp.customize( 'aqualuxe_text_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--text-color', to);
		} );
	} );

	// Background color.
	wp.customize( 'aqualuxe_background_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--background-color', to);
		} );
	} );

	// Body font.
	wp.customize( 'aqualuxe_body_font', function( value ) {
		value.bind( function( to ) {
			let fontFamily = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
			
			if (to === 'inter') {
				fontFamily = '"Inter", sans-serif';
			} else if (to === 'roboto') {
				fontFamily = '"Roboto", sans-serif';
			} else if (to === 'open-sans') {
				fontFamily = '"Open Sans", sans-serif';
			} else if (to === 'lato') {
				fontFamily = '"Lato", sans-serif';
			} else if (to === 'montserrat') {
				fontFamily = '"Montserrat", sans-serif';
			} else if (to === 'poppins') {
				fontFamily = '"Poppins", sans-serif';
			}
			
			document.documentElement.style.setProperty('--body-font', fontFamily);
		} );
	} );

	// Heading font.
	wp.customize( 'aqualuxe_heading_font', function( value ) {
		value.bind( function( to ) {
			let fontFamily = 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
			
			if (to === 'inter') {
				fontFamily = '"Inter", sans-serif';
			} else if (to === 'roboto') {
				fontFamily = '"Roboto", sans-serif';
			} else if (to === 'open-sans') {
				fontFamily = '"Open Sans", sans-serif';
			} else if (to === 'lato') {
				fontFamily = '"Lato", sans-serif';
			} else if (to === 'montserrat') {
				fontFamily = '"Montserrat", sans-serif';
			} else if (to === 'poppins') {
				fontFamily = '"Poppins", sans-serif';
			} else if (to === 'playfair-display') {
				fontFamily = '"Playfair Display", serif';
			} else if (to === 'merriweather') {
				fontFamily = '"Merriweather", serif';
			}
			
			document.documentElement.style.setProperty('--heading-font', fontFamily);
		} );
	} );

	// Body font size.
	wp.customize( 'aqualuxe_body_font_size', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--body-font-size', to + 'px');
		} );
	} );

	// Heading font weight.
	wp.customize( 'aqualuxe_heading_font_weight', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--heading-font-weight', to);
		} );
	} );

	// Container width.
	wp.customize( 'aqualuxe_container_width', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--container-width', to + 'px');
		} );
	} );

	// Footer copyright.
	wp.customize( 'aqualuxe_footer_copyright', function( value ) {
		value.bind( function( to ) {
			$( '.copyright' ).html( to );
		} );
	} );

} )( jQuery );