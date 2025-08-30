/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	const siteNavigation = document.getElementById( 'site-navigation' );

	// Return early if the navigation doesn't exist.
	if ( ! siteNavigation ) {
		return;
	}

	const button = document.getElementById( 'mobile-menu-toggle' );

	// Return early if the button doesn't exist.
	if ( ! button ) {
		return;
	}

	const menu = document.getElementById( 'mobile-menu' );

	// Hide menu toggle button if menu is empty and return early.
	if ( ! menu ) {
		button.style.display = 'none';
		return;
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		menu.classList.toggle( 'hidden' );
		
		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target ) || button.contains( event.target );

		if ( ! isClickInside ) {
			menu.classList.add( 'hidden' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName( 'a' );

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

	// Toggle focus each time a menu link is focused or blurred.
	for ( const link of links ) {
		link.addEventListener( 'focus', toggleFocus, true );
		link.addEventListener( 'blur', toggleFocus, true );
	}

	// Toggle focus each time a menu link with children receive a touch event.
	for ( const link of linksWithChildren ) {
		link.addEventListener( 'touchstart', toggleFocus, false );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		if ( event.type === 'focus' || event.type === 'blur' ) {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while ( ! self.classList.contains( 'nav-menu' ) ) {
				// On li elements toggle the class .focus.
				if ( 'li' === self.tagName.toLowerCase() ) {
					self.classList.toggle( 'focus' );
				}
				self = self.parentNode;
			}
		}

		if ( event.type === 'touchstart' ) {
			const menuItem = this.parentNode;
			event.preventDefault();
			for ( const link of menuItem.parentNode.children ) {
				if ( menuItem !== link ) {
					link.classList.remove( 'focus' );
				}
			}
			menuItem.classList.toggle( 'focus' );
		}
	}

	// Header search toggle
	const searchToggle = document.getElementById( 'header-search-toggle' );
	const headerSearch = document.getElementById( 'header-search' );

	if ( searchToggle && headerSearch ) {
		searchToggle.addEventListener( 'click', function() {
			headerSearch.classList.toggle( 'hidden' );
		} );

		// Close search when clicking outside
		document.addEventListener( 'click', function( event ) {
			const isClickInside = searchToggle.contains( event.target ) || headerSearch.contains( event.target );

			if ( ! isClickInside && ! headerSearch.classList.contains( 'hidden' ) ) {
				headerSearch.classList.add( 'hidden' );
			}
		} );
	}
}() );