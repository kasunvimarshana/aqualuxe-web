/**
 * AquaLuxe Theme Customizer JavaScript
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
			document.documentElement.style.setProperty('--color-primary', to);
		} );
	} );

	// Secondary color.
	wp.customize( 'aqualuxe_secondary_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--color-primary-dark', to);
		} );
	} );

	// Accent color.
	wp.customize( 'aqualuxe_accent_color', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--color-accent', to);
		} );
	} );

	// Base font size.
	wp.customize( 'aqualuxe_base_font_size', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--font-base-size', to + 'px');
		} );
	} );

	// Heading font.
	wp.customize( 'aqualuxe_heading_font', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--font-heading', "'" + to + "', sans-serif");
		} );
	} );

	// Body font.
	wp.customize( 'aqualuxe_body_font', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--font-body', "'" + to + "', sans-serif");
		} );
	} );

	// Container width.
	wp.customize( 'aqualuxe_container_width', function( value ) {
		value.bind( function( to ) {
			document.documentElement.style.setProperty('--container-width', to + 'px');
		} );
	} );

	// Header layout.
	wp.customize( 'aqualuxe_header_layout', function( value ) {
		value.bind( function( to ) {
			// Remove all layout classes
			$( '.site-header' ).removeClass( 'header-default header-centered header-transparent header-minimal' );
			// Add selected layout class
			$( '.site-header' ).addClass( 'header-' + to );
		} );
	} );

	// Sticky header.
	wp.customize( 'aqualuxe_sticky_header', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.site-header' ).addClass( 'sticky-enabled' );
			} else {
				$( '.site-header' ).removeClass( 'sticky-enabled' );
			}
		} );
	} );

	// Header search.
	wp.customize( 'aqualuxe_header_search', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.header-search' ).removeClass( 'hidden' );
			} else {
				$( '.header-search' ).addClass( 'hidden' );
			}
		} );
	} );

	// Header cart.
	wp.customize( 'aqualuxe_header_cart', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.header-cart' ).removeClass( 'hidden' );
			} else {
				$( '.header-cart' ).addClass( 'hidden' );
			}
		} );
	} );

	// Header account.
	wp.customize( 'aqualuxe_header_account', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.header-account' ).removeClass( 'hidden' );
			} else {
				$( '.header-account' ).addClass( 'hidden' );
			}
		} );
	} );

	// Header top bar.
	wp.customize( 'aqualuxe_header_top_bar', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.header-top-bar' ).removeClass( 'hidden' );
			} else {
				$( '.header-top-bar' ).addClass( 'hidden' );
			}
		} );
	} );

	// Top bar content.
	wp.customize( 'aqualuxe_top_bar_content', function( value ) {
		value.bind( function( to ) {
			$( '.top-bar-content' ).html( to );
		} );
	} );

	// Footer layout.
	wp.customize( 'aqualuxe_footer_layout', function( value ) {
		value.bind( function( to ) {
			// Remove all layout classes
			$( '.footer-widgets' ).removeClass( 'footer-1-column footer-2-columns footer-3-columns footer-4-columns' );
			// Add selected layout class
			$( '.footer-widgets' ).addClass( 'footer-' + to );
		} );
	} );

	// Footer copyright.
	wp.customize( 'aqualuxe_footer_copyright', function( value ) {
		value.bind( function( to ) {
			$( '.copyright-text' ).html( to );
		} );
	} );

	// Payment icons.
	wp.customize( 'aqualuxe_show_payment_icons', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.payment-icons' ).removeClass( 'hidden' );
			} else {
				$( '.payment-icons' ).addClass( 'hidden' );
			}
		} );
	} );

	// Dark mode toggle.
	wp.customize( 'aqualuxe_enable_dark_mode', function( value ) {
		value.bind( function( to ) {
			if ( to ) {
				$( '.dark-mode-toggle-wrapper' ).removeClass( 'hidden' );
			} else {
				$( '.dark-mode-toggle-wrapper' ).addClass( 'hidden' );
			}
		} );
	} );

	// Default color scheme.
	wp.customize( 'aqualuxe_default_color_scheme', function( value ) {
		value.bind( function( to ) {
			document.documentElement.setAttribute('data-default-theme', to);
			
			// Only apply if user hasn't set a preference
			if (!localStorage.getItem('aqualuxe-theme')) {
				if (to === 'dark') {
					document.documentElement.classList.add('dark');
				} else if (to === 'light') {
					document.documentElement.classList.remove('dark');
				} else if (to === 'auto') {
					// Check system preference
					const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
					if (prefersDark) {
						document.documentElement.classList.add('dark');
					} else {
						document.documentElement.classList.remove('dark');
					}
				}
			}
		} );
	} );

	// Blog layout.
	wp.customize( 'aqualuxe_blog_layout', function( value ) {
		value.bind( function( to ) {
			// Remove all layout classes
			$( '.blog-posts' ).removeClass( 'layout-grid layout-list layout-masonry' );
			// Add selected layout class
			$( '.blog-posts' ).addClass( 'layout-' + to );
		} );
	} );

	// Sidebar position.
	wp.customize( 'aqualuxe_sidebar_position', function( value ) {
		value.bind( function( to ) {
			// Remove all position classes
			$( '.site-content' ).removeClass( 'sidebar-right sidebar-left sidebar-none' );
			// Add selected position class
			$( '.site-content' ).addClass( 'sidebar-' + to );
		} );
	} );

	// Products per row.
	wp.customize( 'aqualuxe_products_per_row', function( value ) {
		value.bind( function( to ) {
			// Remove all grid classes
			$( '.products' ).removeClass( 'grid-2 grid-3 grid-4 grid-5 grid-6' );
			// Add selected grid class
			$( '.products' ).addClass( 'grid-' + to );
		} );
	} );

} )( jQuery );