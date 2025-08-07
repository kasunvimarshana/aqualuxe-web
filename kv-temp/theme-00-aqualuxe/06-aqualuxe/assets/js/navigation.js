/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function () {
	const siteNavigation = document.getElementById('site-navigation');

	// Return early if the navigation doesn't exist.
	if (!siteNavigation) {
		return;
	}

	const button = siteNavigation.getElementsByTagName('button')[0];

	// Return early if the button doesn't exist.
	if ('undefined' === typeof button) {
		return;
	}

	const menu = siteNavigation.getElementsByTagName('ul')[0];

	// Hide menu toggle button if menu is empty and return early.
	if ('undefined' === typeof menu) {
		button.style.display = 'none';
		return;
	}

	if (!menu.classList.contains('nav-menu')) {
		menu.classList.add('nav-menu');
	}

	// Toggle the .toggled class and the aria-expanded value each time the button is clicked.
	button.addEventListener('click', function () {
		siteNavigation.classList.toggle('toggled');

		if (button.getAttribute('aria-expanded') === 'true') {
			button.setAttribute('aria-expanded', 'false');
		} else {
			button.setAttribute('aria-expanded', 'true');
		}
	});

	// Remove the .toggled class and set aria-expanded to false when the user clicks outside the navigation.
	document.addEventListener('click', function (event) {
		const isClickInside = siteNavigation.contains(event.target);

		if (!isClickInside) {
			siteNavigation.classList.remove('toggled');
			button.setAttribute('aria-expanded', 'false');
		}
	});

	// Get all the link elements within the menu.
	const links = menu.getElementsByTagName('a');

	// Get all the link elements with children within the menu.
	const linksWithChildren = menu.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

	// Toggle focus each time a menu link is focused or blurred.
	for (let i = 0; i < links.length; i++) {
		links[i].addEventListener('focus', toggleFocus);
		links[i].addEventListener('blur', toggleFocus);
	}

	// Toggle focus each time a menu link with children is focused or blurred.
	for (let i = 0; i < linksWithChildren.length; i++) {
		linksWithChildren[i].addEventListener('touchstart', function (event) {
			const menuItem = this.parentNode;

			if (!menuItem.classList.contains('focus')) {
				event.preventDefault();
				for (let j = 0; j < menuItem.parentNode.children.length; j++) {
					if (menuItem === menuItem.parentNode.children[j]) {
						continue;
					}
					menuItem.parentNode.children[j].classList.remove('focus');
				}
				menuItem.classList.add('focus');
			} else {
				menuItem.classList.remove('focus');
			}
		});
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		if (event.type === 'focus' || event.type === 'blur') {
			let self = this;
			// Move up through the ancestors of the current link until we hit .nav-menu.
			while (-1 === self.className.indexOf('nav-menu')) {
				// On li elements toggle the class .focus.
				if ('li' === self.tagName.toLowerCase()) {
					if (event.type === 'focus') {
						self.classList.add('focus');
					} else {
						self.classList.remove('focus');
					}
				}
				self = self.parentElement;
			}
		}
	}

	/**
	 * Add or remove sticky class to header on scroll
	 */
	window.addEventListener('scroll', function () {
		const header = document.getElementById('masthead');
		if (header) {
			if (window.scrollY > 100) {
				header.classList.add('sticky');
			} else {
				header.classList.remove('sticky');
			}
		}
	});
})();