/**
 * Dropdown Keyboard Navigation
 *
 * Enhances dropdown menu accessibility for keyboard users.
 */
(function() {
	var container, button, menu, links, i, len;

	container = document.getElementById('site-navigation');
	if (!container) {
		return;
	}

	button = container.getElementsByTagName('button')[0];
	if ('undefined' === typeof button) {
		return;
	}

	menu = container.getElementsByTagName('ul')[0];

	// Hide menu toggle button if menu is empty and return early.
	if ('undefined' === typeof menu) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute('aria-expanded', 'false');
	if (-1 === menu.className.indexOf('nav-menu')) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if (-1 !== container.className.indexOf('toggled')) {
			container.className = container.className.replace(' toggled', '');
			button.setAttribute('aria-expanded', 'false');
			menu.setAttribute('aria-expanded', 'false');
		} else {
			container.className += ' toggled';
			button.setAttribute('aria-expanded', 'true');
			menu.setAttribute('aria-expanded', 'true');
		}
	};

	// Get all the link elements within the menu.
	links = menu.getElementsByTagName('a');

	// Each time a menu link is focused or blurred, toggle focus.
	for (i = 0, len = links.length; i < len; i++) {
		links[i].addEventListener('focus', toggleFocus, true);
		links[i].addEventListener('blur', toggleFocus, true);
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while (-1 === self.className.indexOf('nav-menu')) {
			// On li elements toggle the class .focus.
			if ('li' === self.tagName.toLowerCase()) {
				if (-1 !== self.className.indexOf('focus')) {
					self.className = self.className.replace(' focus', '');
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	(function(container) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll('.menu-item-has-children > a, .page_item_has_children > a');

		if ('ontouchstart' in window) {
			touchStartFn = function(e) {
				var menuItem = this.parentNode,
					i;

				if (!menuItem.classList.contains('focus')) {
					e.preventDefault();
					for (i = 0; i < menuItem.parentNode.children.length; ++i) {
						if (menuItem === menuItem.parentNode.children[i]) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove('focus');
					}
					menuItem.classList.add('focus');
				} else {
					menuItem.classList.remove('focus');
				}
			};

			for (i = 0; i < parentLink.length; ++i) {
				parentLink[i].addEventListener('touchstart', touchStartFn, false);
			}
		}
	}(container));

	// Close menu when pressing Escape key
	document.addEventListener('keydown', function(e) {
		if (e.key === 'Escape' && container.className.indexOf('toggled') !== -1) {
			container.className = container.className.replace(' toggled', '');
			button.setAttribute('aria-expanded', 'false');
			menu.setAttribute('aria-expanded', 'false');
			button.focus();
		}
	});

	// Handle arrow key navigation in dropdown menus
	document.addEventListener('keydown', function(e) {
		// Find all visible menu items
		var visibleItems = Array.from(menu.querySelectorAll('li:not(.hidden) > a'));
		if (!visibleItems.length) return;

		var currentIndex = visibleItems.indexOf(document.activeElement);
		if (currentIndex < 0) return;

		// Handle arrow keys
		switch (e.key) {
			case 'ArrowDown':
				e.preventDefault();
				if (currentIndex < visibleItems.length - 1) {
					visibleItems[currentIndex + 1].focus();
				}
				break;
			case 'ArrowUp':
				e.preventDefault();
				if (currentIndex > 0) {
					visibleItems[currentIndex - 1].focus();
				}
				break;
			case 'Home':
				e.preventDefault();
				visibleItems[0].focus();
				break;
			case 'End':
				e.preventDefault();
				visibleItems[visibleItems.length - 1].focus();
				break;
		}
	});
})();