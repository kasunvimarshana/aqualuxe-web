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

	const button = siteNavigation.getElementsByTagName( 'button' )[ 0 ];

	// Return early if the button doesn't exist.
	if ( 'undefined' === typeof button ) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName( 'ul' )[ 0 ];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	if ( ! menu.classList.contains( 'nav-menu' ) ) {
		menu.classList.add( 'nav-menu' );
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener( 'click', function() {
		siteNavigation.classList.toggle( 'toggled' );

		if ( button.getAttribute( 'aria-expanded' ) === 'true' ) {
			button.setAttribute( 'aria-expanded', 'false' );
		} else {
			button.setAttribute( 'aria-expanded', 'true' );
		}
	} );

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener( 'click', function( event ) {
		const isClickInside = siteNavigation.contains( event.target );

		if ( ! isClickInside ) {
			siteNavigation.classList.remove( 'toggled' );
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

	// Add keyboard navigation for dropdown menus
	document.addEventListener('keydown', function(e) {
		// If not tab key, return
		if (e.key !== 'Tab') {
			return;
		}

		// If the menu is not toggled, return
		if (!siteNavigation.classList.contains('toggled')) {
			return;
		}

		// Get all visible links
		const visibleLinks = Array.from(links).filter(link => {
			return window.getComputedStyle(link).display !== 'none';
		});

		// If no visible links, return
		if (visibleLinks.length === 0) {
			return;
		}

		// Get the first and last visible links
		const firstLink = visibleLinks[0];
		const lastLink = visibleLinks[visibleLinks.length - 1];

		// If shift key is pressed and the first link is focused, close the menu
		if (e.shiftKey && document.activeElement === firstLink) {
			button.click();
			button.focus();
			e.preventDefault();
		}

		// If no shift key and the last link is focused, close the menu
		if (!e.shiftKey && document.activeElement === lastLink) {
			button.click();
			e.preventDefault();
		}
	});

	// Close menu when escape key is pressed
	document.addEventListener('keyup', function(e) {
		if (e.key === 'Escape') {
			if (siteNavigation.classList.contains('toggled')) {
				siteNavigation.classList.remove('toggled');
				button.setAttribute('aria-expanded', 'false');
				button.focus();
			}
		}
	});

	// Handle submenu accessibility
	const subMenuParents = document.querySelectorAll('.menu-item-has-children');

	subMenuParents.forEach(function(parent) {
		// Add dropdown toggle button
		const dropdownToggle = document.createElement('button');
		dropdownToggle.className = 'dropdown-toggle';
		dropdownToggle.setAttribute('aria-expanded', 'false');
		dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand child menu</span><svg aria-hidden="true" focusable="false" class="dropdown-symbol" width="12" height="12" viewBox="0 0 12 12"><path d="M1.5 4L6 8.5 10.5 4" stroke="currentColor" stroke-width="2" fill="none" /></svg>';
		
		const link = parent.querySelector('a');
		link.after(dropdownToggle);

		// Toggle submenu on click
		dropdownToggle.addEventListener('click', function(e) {
			e.preventDefault();
			const expanded = this.getAttribute('aria-expanded') === 'true';
			this.setAttribute('aria-expanded', !expanded);
			
			const submenu = this.nextElementSibling;
			if (submenu && submenu.classList.contains('sub-menu')) {
				if (expanded) {
					submenu.classList.remove('toggled-on');
					this.setAttribute('aria-label', 'Expand child menu');
				} else {
					submenu.classList.add('toggled-on');
					this.setAttribute('aria-label', 'Collapse child menu');
				}
			}
		});

		// Close submenu when clicking outside
		document.addEventListener('click', function(e) {
			if (!parent.contains(e.target)) {
				const submenu = parent.querySelector('.sub-menu');
				if (submenu && submenu.classList.contains('toggled-on')) {
					submenu.classList.remove('toggled-on');
					dropdownToggle.setAttribute('aria-expanded', 'false');
					dropdownToggle.setAttribute('aria-label', 'Expand child menu');
				}
			}
		});

		// Handle keyboard navigation
		dropdownToggle.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				this.click();
				
				const submenu = this.nextElementSibling;
				if (submenu && submenu.classList.contains('toggled-on')) {
					const firstLink = submenu.querySelector('a');
					if (firstLink) {
						firstLink.focus();
					}
				}
			}
		});
	});

	// Close all submenus when window is resized
	window.addEventListener('resize', function() {
		const submenus = document.querySelectorAll('.sub-menu');
		const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
		
		submenus.forEach(function(submenu) {
			submenu.classList.remove('toggled-on');
		});
		
		dropdownToggles.forEach(function(toggle) {
			toggle.setAttribute('aria-expanded', 'false');
			toggle.setAttribute('aria-label', 'Expand child menu');
		});
		
		if (window.innerWidth > 768) {
			siteNavigation.classList.remove('toggled');
			button.setAttribute('aria-expanded', 'false');
		}
	});
}() );