/**
 * Accessibility Toolbar
 *
 * Provides user-controlled accessibility options.
 *
 * @package AquaLuxe
 */

(function() {
	document.addEventListener('DOMContentLoaded', function() {
		// Create accessibility toolbar
		createAccessibilityToolbar();
		
		// Initialize user preferences
		initializeUserPreferences();
		
		/**
		 * Create accessibility toolbar.
		 */
		function createAccessibilityToolbar() {
			// Create toolbar container
			var toolbar = document.createElement('div');
			toolbar.className = 'accessibility-toolbar';
			toolbar.setAttribute('aria-label', 'Accessibility Tools');
			
			// Create toggle button
			var toggleButton = document.createElement('button');
			toggleButton.className = 'accessibility-toolbar-toggle';
			toggleButton.setAttribute('aria-expanded', 'false');
			toggleButton.setAttribute('aria-controls', 'accessibility-toolbar-controls');
			toggleButton.innerHTML = '<span class="screen-reader-text">Accessibility Tools</span><i class="fas fa-universal-access" aria-hidden="true"></i>';
			
			// Create controls container
			var controls = document.createElement('div');
			controls.id = 'accessibility-toolbar-controls';
			controls.className = 'accessibility-toolbar-controls';
			controls.setAttribute('hidden', '');
			
			// Create contrast button
			var contrastButton = createToolbarButton('toggle-contrast', 'High Contrast');
			
			// Create font size buttons
			var increaseFontButton = createToolbarButton('increase-font-size', 'Increase Font Size');
			var decreaseFontButton = createToolbarButton('decrease-font-size', 'Decrease Font Size');
			var resetFontButton = createToolbarButton('reset-font-size', 'Reset Font Size');
			
			// Create reduced motion button
			var reducedMotionButton = createToolbarButton('toggle-reduced-motion', 'Reduce Motion');
			
			// Create focus visibility button
			var focusVisibilityButton = createToolbarButton('toggle-focus-visibility', 'Focus Visibility');
			
			// Add buttons to controls
			controls.appendChild(contrastButton);
			controls.appendChild(increaseFontButton);
			controls.appendChild(decreaseFontButton);
			controls.appendChild(resetFontButton);
			controls.appendChild(reducedMotionButton);
			controls.appendChild(focusVisibilityButton);
			
			// Add toggle button and controls to toolbar
			toolbar.appendChild(toggleButton);
			toolbar.appendChild(controls);
			
			// Add toolbar to body
			document.body.appendChild(toolbar);
			
			// Add event listeners
			toggleButton.addEventListener('click', function() {
				var expanded = this.getAttribute('aria-expanded') === 'true' || false;
				this.setAttribute('aria-expanded', !expanded);
				controls.hidden = expanded;
			});
			
			// Handle button actions
			contrastButton.addEventListener('click', toggleHighContrast);
			increaseFontButton.addEventListener('click', increaseFontSize);
			decreaseFontButton.addEventListener('click', decreaseFontSize);
			resetFontButton.addEventListener('click', resetFontSize);
			reducedMotionButton.addEventListener('click', toggleReducedMotion);
			focusVisibilityButton.addEventListener('click', toggleFocusVisibility);
			
			// Close toolbar when clicking outside
			document.addEventListener('click', function(event) {
				if (!toolbar.contains(event.target) && toggleButton.getAttribute('aria-expanded') === 'true') {
					toggleButton.setAttribute('aria-expanded', 'false');
					controls.hidden = true;
				}
			});
			
			// Close toolbar when pressing Escape
			document.addEventListener('keydown', function(event) {
				if (event.key === 'Escape' && toggleButton.getAttribute('aria-expanded') === 'true') {
					toggleButton.setAttribute('aria-expanded', 'false');
					controls.hidden = true;
					toggleButton.focus();
				}
			});
		}
		
		/**
		 * Create toolbar button.
		 *
		 * @param {string} action Button action.
		 * @param {string} text Button text.
		 * @return {HTMLElement} Button element.
		 */
		function createToolbarButton(action, text) {
			var button = document.createElement('button');
			button.className = 'accessibility-toolbar-button accessibility-toolbar-button-' + action;
			button.setAttribute('data-action', action);
			button.innerHTML = '<span class="accessibility-toolbar-button-text">' + text + '</span>';
			return button;
		}
		
		/**
		 * Initialize user preferences.
		 */
		function initializeUserPreferences() {
			// High contrast
			if (localStorage.getItem('aqualuxe-high-contrast') === 'true') {
				document.body.classList.add('high-contrast');
				updateButtonState('toggle-contrast', true);
			}
			
			// Font size
			var fontSize = localStorage.getItem('aqualuxe-font-size');
			if (fontSize) {
				document.documentElement.style.fontSize = fontSize;
			}
			
			// Reduced motion
			if (localStorage.getItem('aqualuxe-reduced-motion') === 'true') {
				document.body.classList.add('reduced-motion');
				updateButtonState('toggle-reduced-motion', true);
			}
			
			// Focus visibility
			if (localStorage.getItem('aqualuxe-focus-visibility') === 'true') {
				document.body.classList.add('focus-visible');
				updateButtonState('toggle-focus-visibility', true);
			}
		}
		
		/**
		 * Toggle high contrast mode.
		 */
		function toggleHighContrast() {
			document.body.classList.toggle('high-contrast');
			var isActive = document.body.classList.contains('high-contrast');
			localStorage.setItem('aqualuxe-high-contrast', isActive);
			updateButtonState('toggle-contrast', isActive);
		}
		
		/**
		 * Increase font size.
		 */
		function increaseFontSize() {
			var currentSize = window.getComputedStyle(document.documentElement).fontSize;
			var newSize = parseFloat(currentSize) * 1.1;
			document.documentElement.style.fontSize = newSize + 'px';
			localStorage.setItem('aqualuxe-font-size', newSize + 'px');
		}
		
		/**
		 * Decrease font size.
		 */
		function decreaseFontSize() {
			var currentSize = window.getComputedStyle(document.documentElement).fontSize;
			var newSize = parseFloat(currentSize) / 1.1;
			document.documentElement.style.fontSize = newSize + 'px';
			localStorage.setItem('aqualuxe-font-size', newSize + 'px');
		}
		
		/**
		 * Reset font size.
		 */
		function resetFontSize() {
			document.documentElement.style.fontSize = '';
			localStorage.removeItem('aqualuxe-font-size');
		}
		
		/**
		 * Toggle reduced motion.
		 */
		function toggleReducedMotion() {
			document.body.classList.toggle('reduced-motion');
			var isActive = document.body.classList.contains('reduced-motion');
			localStorage.setItem('aqualuxe-reduced-motion', isActive);
			updateButtonState('toggle-reduced-motion', isActive);
		}
		
		/**
		 * Toggle focus visibility.
		 */
		function toggleFocusVisibility() {
			document.body.classList.toggle('focus-visible');
			var isActive = document.body.classList.contains('focus-visible');
			localStorage.setItem('aqualuxe-focus-visibility', isActive);
			updateButtonState('toggle-focus-visibility', isActive);
		}
		
		/**
		 * Update button state.
		 *
		 * @param {string} action Button action.
		 * @param {boolean} isActive Whether the button is active.
		 */
		function updateButtonState(action, isActive) {
			var button = document.querySelector('.accessibility-toolbar-button-' + action);
			if (button) {
				if (isActive) {
					button.classList.add('active');
					button.setAttribute('aria-pressed', 'true');
				} else {
					button.classList.remove('active');
					button.setAttribute('aria-pressed', 'false');
				}
			}
		}
	});
})();