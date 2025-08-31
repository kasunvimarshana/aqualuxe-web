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

	// Primary Color
	wp.customize( 'aqualuxe_primary_color', function( value ) {
		value.bind( function( to ) {
			// Update the CSS variable
			document.documentElement.style.setProperty( '--aqualuxe-primary-color', to );
		} );
	} );

	// Secondary Color
	wp.customize( 'aqualuxe_secondary_color', function( value ) {
		value.bind( function( to ) {
			// Update the CSS variable
			document.documentElement.style.setProperty( '--aqualuxe-secondary-color', to );
		} );
	} );

	// Body Font
	wp.customize( 'aqualuxe_body_font', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'font-family', to );
		} );
	} );

	// Heading Font
	wp.customize( 'aqualuxe_heading_font', function( value ) {
		value.bind( function( to ) {
			$( 'h1, h2, h3, h4, h5, h6' ).css( 'font-family', to );
		} );
	} );

} )( jQuery );
