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
			document.documentElement.style.setProperty('--aqualuxe-primary-color', to);
		} );
	} );

	// Secondary color.
	wp.customize( 'aqualuxe_secondary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--aqualuxe-secondary-color', to);
		} );
	} );

	// Accent color.
	wp.customize( 'aqualuxe_accent_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--aqualuxe-accent-color', to);
		} );
	} );

	// Container width.
	wp.customize( 'aqualuxe_container_width', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--aqualuxe-container-width', to + 'px');
		} );
	} );

	// Base font size.
	wp.customize( 'aqualuxe_base_font_size', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--aqualuxe-base-font-size', to + 'px');
		} );
	} );

	// Footer copyright text.
	wp.customize( 'aqualuxe_footer_copyright', function( value ) {
		value.bind( function( to ) {
			$( '.copyright' ).html( to );
		} );
	} );

	// Sticky header.
	wp.customize( 'aqualuxe_sticky_header', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.site-header' ).addClass( 'sticky-header' );
			} else {
				$( '.site-header' ).removeClass( 'sticky-header' );
			}
		} );
	} );

	// Header style.
	wp.customize( 'aqualuxe_header_style', function( value ) {
		value.bind( function( to ) {
			// Remove all header style classes
			$( '.site-header' ).removeClass( 'header-standard header-centered header-split header-minimal' );
			// Add the selected header style class
			$( '.site-header' ).addClass( 'header-' + to );
		} );
	} );

	// Sidebar position.
	wp.customize( 'aqualuxe_sidebar_position', function( value ) {
		value.bind( function( to ) {
			// Remove all sidebar position classes
			$( 'body' ).removeClass( 'sidebar-right sidebar-left no-sidebar' );
			// Add the selected sidebar position class
			$( 'body' ).addClass( 'sidebar-' + to );
		} );
	} );

	// Dark mode.
	wp.customize( 'aqualuxe_enable_dark_mode', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( 'body' ).addClass( 'dark-mode' );
			} else {
				$( 'body' ).removeClass( 'dark-mode' );
			}
		} );
	} );

	// Footer columns.
	wp.customize( 'aqualuxe_footer_columns', function( value ) {
		value.bind( function( to ) {
			// Remove all footer column classes
			$( '.site-footer .grid' ).removeClass( 'grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4' );
			// Add the selected footer column class
			$( '.site-footer .grid' ).addClass( 'grid-cols-' + to );
		} );
	} );

	// WooCommerce products per row.
	wp.customize( 'aqualuxe_products_per_row', function( value ) {
		value.bind( function( to ) {
			// This requires a page reload to take effect
			// But we can add a visual indicator
			$( '.woocommerce ul.products' ).attr( 'data-columns', to );
		} );
	} );

	// Related products.
	wp.customize( 'aqualuxe_related_products', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.related.products' ).show();
			} else {
				$( '.related.products' ).hide();
			}
		} );
	} );

	// Quick view.
	wp.customize( 'aqualuxe_quick_view', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.aqualuxe-quick-view-button' ).show();
			} else {
				$( '.aqualuxe-quick-view-button' ).hide();
			}
		} );
	} );

	// Wishlist.
	wp.customize( 'aqualuxe_wishlist', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.aqualuxe-wishlist-button' ).show();
			} else {
				$( '.aqualuxe-wishlist-button' ).hide();
			}
		} );
	} );

} )( jQuery );