/**
 * AquaLuxe Accessibility Module
 *
 * Comprehensive accessibility features and WCAG 2.1 compliance
 * Keyboard navigation, screen reader support, and inclusive design
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Accessibility Class
 * 
 * Implements accessibility features and ensures WCAG 2.1 compliance
 * Provides keyboard navigation, focus management, and screen reader support
 */
export class Accessibility {
    /**
     * Constructor
     * 
     * @param {Object} config Accessibility configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            enableSkipLinks: true,
            enableFocusTrapping: true,
            enableAriaLive: true,
            enableKeyboardNavigation: true,
            enableColorContrastCheck: false, // CPU intensive
            announcePageChanges: true,
            ...config
        };
        
        this.eventBus = eventBus;
        this.isInitialized = false;
        
        // Accessibility state
        this.focusStack = [];
        this.currentModal = null;
        this.ariaLiveRegion = null;
        this.reducedMotion = false;
        this.highContrast = false;
        
        // Keyboard navigation state
        this.keyboardUser = false;
        this.lastInteraction = 'mouse';
        
        // Focus trap elements
        this.focusableSelectors = [
            'a[href]',
            'button:not([disabled])',
            'textarea:not([disabled])',
            'input:not([disabled]):not([type="hidden"])',
            'select:not([disabled])',
            '[tabindex]:not([tabindex="-1"])',
            '[contenteditable="true"]'
        ].join(', ');
        
        // Bound methods
        this.handleKeyDown = this.handleKeyDown.bind(this);
        this.handleMouseDown = this.handleMouseDown.bind(this);
        this.handleFocusIn = this.handleFocusIn.bind(this);
        this.handleFocusOut = this.handleFocusOut.bind(this);
        
        this.init();
    }

    /**
     * Initialize accessibility features
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Detect user preferences
            this.detectUserPreferences();
            
            // Set up accessibility features
            this.setupSkipLinks();
            this.setupAriaLiveRegion();
            this.setupKeyboardNavigation();
            this.setupFocusManagement();
            this.setupLandmarks();
            this.setupFormAccessibility();
            this.setupMediaAccessibility();
            
            // Bind events
            this.bindEvents();
            
            // Add accessibility utilities to body
            this.updateBodyClasses();
            
            this.isInitialized = true;
            
            // Emit initialization event
            if (this.eventBus) {
                this.eventBus.emit('accessibility:initialized', {
                    features: this.getEnabledFeatures(),
                    userPreferences: this.getUserPreferences()
                });
            }
            
            if (this.config.debug) {
                console.log('♿ Accessibility features initialized');
            }
            
        } catch (error) {
            console.error('❌ Accessibility initialization failed:', error);
        }
    }

    /**
     * Detect user accessibility preferences
     */
    detectUserPreferences() {
        // Reduced motion preference
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        // High contrast preference
        this.highContrast = window.matchMedia('(prefers-contrast: high)').matches;
        
        // Monitor preference changes
        window.matchMedia('(prefers-reduced-motion: reduce)').addListener((e) => {
            this.reducedMotion = e.matches;
            this.updateBodyClasses();
            
            if (this.eventBus) {
                this.eventBus.emit('accessibility:reduced-motion-changed', { enabled: e.matches });
            }
        });
        
        window.matchMedia('(prefers-contrast: high)').addListener((e) => {
            this.highContrast = e.matches;
            this.updateBodyClasses();
            
            if (this.eventBus) {
                this.eventBus.emit('accessibility:high-contrast-changed', { enabled: e.matches });
            }
        });
    }

    /**
     * Set up skip links
     */
    setupSkipLinks() {
        if (!this.config.enableSkipLinks) return;
        
        // Check if skip links already exist
        if (document.querySelector('.skip-links')) return;
        
        // Create skip links container
        const skipLinks = document.createElement('div');
        skipLinks.className = 'skip-links';
        skipLinks.setAttribute('aria-label', 'Skip navigation links');
        
        // Define skip link targets
        const skipTargets = [
            { target: '#main', text: 'Skip to main content' },
            { target: '.site-navigation', text: 'Skip to navigation' },
            { target: '.sidebar', text: 'Skip to sidebar' },
            { target: '.site-footer', text: 'Skip to footer' }
        ];
        
        // Create skip links
        skipTargets.forEach(({ target, text }) => {
            const targetElement = document.querySelector(target);
            if (targetElement) {
                const link = document.createElement('a');
                link.href = target;
                link.className = 'skip-link screen-reader-text';
                link.textContent = text;
                
                // Focus target when link is activated
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    targetElement.focus();
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                });
                
                skipLinks.appendChild(link);
            }
        });
        
        // Insert skip links at the beginning of body
        document.body.insertBefore(skipLinks, document.body.firstChild);
    }

    /**
     * Set up ARIA live region for announcements
     */
    setupAriaLiveRegion() {
        if (!this.config.enableAriaLive) return;
        
        // Check if live region already exists
        if (document.querySelector('#a11y-announcements')) return;
        
        // Create ARIA live region
        this.ariaLiveRegion = document.createElement('div');
        this.ariaLiveRegion.id = 'a11y-announcements';
        this.ariaLiveRegion.className = 'screen-reader-text';
        this.ariaLiveRegion.setAttribute('aria-live', 'polite');
        this.ariaLiveRegion.setAttribute('aria-atomic', 'true');
        
        document.body.appendChild(this.ariaLiveRegion);
    }

    /**
     * Set up keyboard navigation
     */
    setupKeyboardNavigation() {
        if (!this.config.enableKeyboardNavigation) return;
        
        // Add roving tabindex to complex widgets
        this.setupRovingTabindex();
        
        // Add arrow key navigation to menus
        this.setupMenuNavigation();
        
        // Add escape key handling
        this.setupEscapeHandling();
    }

    /**
     * Set up roving tabindex for complex widgets
     */
    setupRovingTabindex() {
        const widgets = document.querySelectorAll('[data-roving-tabindex]');
        
        widgets.forEach(widget => {
            const items = widget.querySelectorAll('[role="tab"], [role="option"], [role="menuitem"]');
            
            if (items.length === 0) return;
            
            // Set initial tabindex
            items.forEach((item, index) => {
                item.setAttribute('tabindex', index === 0 ? '0' : '-1');
            });
            
            // Handle arrow key navigation
            widget.addEventListener('keydown', (e) => {
                if (!['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(e.key)) {
                    return;
                }
                
                e.preventDefault();
                
                const currentIndex = Array.from(items).indexOf(document.activeElement);
                let nextIndex = currentIndex;
                
                switch (e.key) {
                    case 'ArrowUp':
                    case 'ArrowLeft':
                        nextIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
                        break;
                    case 'ArrowDown':
                    case 'ArrowRight':
                        nextIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
                        break;
                    case 'Home':
                        nextIndex = 0;
                        break;
                    case 'End':
                        nextIndex = items.length - 1;
                        break;
                }
                
                // Update tabindex and focus
                items.forEach((item, index) => {
                    item.setAttribute('tabindex', index === nextIndex ? '0' : '-1');
                });
                
                items[nextIndex].focus();
            });
        });
    }

    /**
     * Set up menu navigation
     */
    setupMenuNavigation() {
        const menus = document.querySelectorAll('[role="menu"], [role="menubar"]');
        
        menus.forEach(menu => {
            menu.addEventListener('keydown', (e) => {
                const menuItems = menu.querySelectorAll('[role="menuitem"]');
                const currentIndex = Array.from(menuItems).indexOf(document.activeElement);
                
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        const nextIndex = currentIndex < menuItems.length - 1 ? currentIndex + 1 : 0;
                        menuItems[nextIndex].focus();
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        const prevIndex = currentIndex > 0 ? currentIndex - 1 : menuItems.length - 1;
                        menuItems[prevIndex].focus();
                        break;
                        
                    case 'Home':
                        e.preventDefault();
                        menuItems[0].focus();
                        break;
                        
                    case 'End':
                        e.preventDefault();
                        menuItems[menuItems.length - 1].focus();
                        break;
                }
            });
        });
    }

    /**
     * Set up escape key handling
     */
    setupEscapeHandling() {
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                // Close modals, dropdowns, etc.
                this.handleEscapeKey(e);
            }
        });
    }

    /**
     * Handle escape key press
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleEscapeKey(e) {
        // Close current modal if open
        if (this.currentModal) {
            this.closeModal(this.currentModal);
            return;
        }
        
        // Close dropdowns
        const openDropdowns = document.querySelectorAll('.dropdown-open, [aria-expanded="true"]');
        if (openDropdowns.length > 0) {
            openDropdowns.forEach(dropdown => {
                if (dropdown.hasAttribute('aria-expanded')) {
                    dropdown.setAttribute('aria-expanded', 'false');
                }
                dropdown.classList.remove('dropdown-open');
            });
            
            // Focus the trigger element
            const trigger = document.querySelector('[aria-haspopup="true"]:focus');
            if (trigger) {
                trigger.focus();
            }
        }
        
        // Emit escape key event
        if (this.eventBus) {
            this.eventBus.emit('accessibility:escape-pressed', { target: e.target });
        }
    }

    /**
     * Set up focus management
     */
    setupFocusManagement() {
        if (!this.config.enableFocusTrapping) return;
        
        // Track focus changes
        document.addEventListener('focusin', this.handleFocusIn);
        document.addEventListener('focusout', this.handleFocusOut);
        
        // Add focus-visible polyfill behavior
        this.setupFocusVisible();
    }

    /**
     * Set up focus-visible polyfill behavior
     */
    setupFocusVisible() {
        let hadKeyboardEvent = true;
        
        const keyboardEvents = ['keydown', 'keyup'];
        const pointerEvents = ['mousedown', 'pointerdown', 'touchstart'];
        
        // Track keyboard usage
        keyboardEvents.forEach(eventType => {
            document.addEventListener(eventType, () => {
                hadKeyboardEvent = true;
            }, true);
        });
        
        // Track pointer usage
        pointerEvents.forEach(eventType => {
            document.addEventListener(eventType, () => {
                hadKeyboardEvent = false;
            }, true);
        });
        
        // Apply focus-visible class
        document.addEventListener('focus', (e) => {
            if (hadKeyboardEvent || e.target.matches(':focus-visible')) {
                e.target.classList.add('focus-visible');
            }
        }, true);
        
        document.addEventListener('blur', (e) => {
            e.target.classList.remove('focus-visible');
        }, true);
    }

    /**
     * Handle focus in events
     * 
     * @param {FocusEvent} e Focus event
     */
    handleFocusIn(e) {
        // Track keyboard users
        if (this.lastInteraction === 'keyboard') {
            this.keyboardUser = true;
            document.body.classList.add('keyboard-user');
        }
        
        // Emit focus event
        if (this.eventBus) {
            this.eventBus.emit('accessibility:focus-in', { target: e.target });
        }
    }

    /**
     * Handle focus out events
     * 
     * @param {FocusEvent} e Focus event
     */
    handleFocusOut(e) {
        // Emit focus event
        if (this.eventBus) {
            this.eventBus.emit('accessibility:focus-out', { target: e.target });
        }
    }

    /**
     * Set up landmarks and page structure
     */
    setupLandmarks() {
        // Ensure main content area has proper role
        const main = document.querySelector('main, #main, .main-content');
        if (main && !main.getAttribute('role')) {
            main.setAttribute('role', 'main');
        }
        
        // Ensure navigation has proper role
        const nav = document.querySelector('nav, .navigation, .main-navigation');
        if (nav && !nav.getAttribute('role')) {
            nav.setAttribute('role', 'navigation');
            if (!nav.getAttribute('aria-label')) {
                nav.setAttribute('aria-label', 'Main navigation');
            }
        }
        
        // Ensure footer has proper role
        const footer = document.querySelector('footer, .site-footer');
        if (footer && !footer.getAttribute('role')) {
            footer.setAttribute('role', 'contentinfo');
        }
        
        // Ensure complementary content has proper role
        const sidebar = document.querySelector('aside, .sidebar');
        if (sidebar && !sidebar.getAttribute('role')) {
            sidebar.setAttribute('role', 'complementary');
        }
    }

    /**
     * Set up form accessibility
     */
    setupFormAccessibility() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            // Associate labels with inputs
            this.associateLabelsWithInputs(form);
            
            // Add error handling
            this.setupFormErrorHandling(form);
            
            // Add required field indicators
            this.setupRequiredFieldIndicators(form);
        });
    }

    /**
     * Associate labels with inputs
     * 
     * @param {Element} form Form element
     */
    associateLabelsWithInputs(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            if (input.type === 'hidden') return;
            
            // Skip if already has label association
            if (input.getAttribute('aria-labelledby') || input.getAttribute('aria-label')) {
                return;
            }
            
            // Find associated label
            let label = form.querySelector(`label[for="${input.id}"]`);
            
            if (!label && input.id) {
                // Try to find label by proximity
                label = input.parentElement.querySelector('label');
            }
            
            if (label && !input.getAttribute('aria-labelledby')) {
                // Ensure label has ID
                if (!label.id) {
                    label.id = `label-${input.id || Date.now()}`;
                }
                
                input.setAttribute('aria-labelledby', label.id);
            } else if (!label && !input.getAttribute('aria-label')) {
                // Create accessible name from placeholder or context
                const placeholder = input.getAttribute('placeholder');
                if (placeholder) {
                    input.setAttribute('aria-label', placeholder);
                }
            }
        });
    }

    /**
     * Set up form error handling
     * 
     * @param {Element} form Form element
     */
    setupFormErrorHandling(form) {
        // Create error container if it doesn't exist
        let errorContainer = form.querySelector('.form-errors');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.className = 'form-errors';
            errorContainer.setAttribute('role', 'alert');
            errorContainer.setAttribute('aria-live', 'polite');
            form.insertBefore(errorContainer, form.firstChild);
        }
        
        // Handle form validation
        form.addEventListener('submit', (e) => {
            this.validateForm(form, errorContainer);
        });
        
        // Handle real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    }

    /**
     * Validate form
     * 
     * @param {Element} form Form element
     * @param {Element} errorContainer Error container
     */
    validateForm(form, errorContainer) {
        const errors = [];
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                const label = this.getFieldLabel(input);
                errors.push(`${label} is required`);
                this.markFieldAsInvalid(input);
            } else {
                this.markFieldAsValid(input);
            }
        });
        
        // Display errors
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.map(error => `<p>${error}</p>`).join('');
            errorContainer.focus();
            
            // Announce errors
            this.announce(`Form validation failed. ${errors.length} errors found.`);
        } else {
            errorContainer.innerHTML = '';
        }
    }

    /**
     * Validate individual field
     * 
     * @param {Element} field Input field
     */
    validateField(field) {
        if (field.hasAttribute('required') && !field.value.trim()) {
            this.markFieldAsInvalid(field);
        } else {
            this.markFieldAsValid(field);
        }
    }

    /**
     * Mark field as invalid
     * 
     * @param {Element} field Input field
     */
    markFieldAsInvalid(field) {
        field.setAttribute('aria-invalid', 'true');
        field.classList.add('invalid');
        
        // Add error message if it doesn't exist
        let errorMessage = document.getElementById(`${field.id}-error`);
        if (!errorMessage) {
            errorMessage = document.createElement('span');
            errorMessage.id = `${field.id}-error`;
            errorMessage.className = 'field-error';
            errorMessage.setAttribute('role', 'alert');
            
            const label = this.getFieldLabel(field);
            errorMessage.textContent = `${label} is required`;
            
            field.parentElement.appendChild(errorMessage);
            field.setAttribute('aria-describedby', errorMessage.id);
        }
    }

    /**
     * Mark field as valid
     * 
     * @param {Element} field Input field
     */
    markFieldAsValid(field) {
        field.setAttribute('aria-invalid', 'false');
        field.classList.remove('invalid');
        
        // Remove error message
        const errorMessage = document.getElementById(`${field.id}-error`);
        if (errorMessage) {
            errorMessage.remove();
            field.removeAttribute('aria-describedby');
        }
    }

    /**
     * Get field label text
     * 
     * @param {Element} field Input field
     * @return {string} Label text
     */
    getFieldLabel(field) {
        const labelId = field.getAttribute('aria-labelledby');
        if (labelId) {
            const label = document.getElementById(labelId);
            if (label) {
                return label.textContent.trim();
            }
        }
        
        const ariaLabel = field.getAttribute('aria-label');
        if (ariaLabel) {
            return ariaLabel;
        }
        
        const placeholder = field.getAttribute('placeholder');
        if (placeholder) {
            return placeholder;
        }
        
        return field.name || 'Field';
    }

    /**
     * Set up required field indicators
     * 
     * @param {Element} form Form element
     */
    setupRequiredFieldIndicators(form) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required], select[required]');
        
        requiredFields.forEach(field => {
            const label = this.getFieldLabelElement(field);
            if (label && !label.querySelector('.required-indicator')) {
                const indicator = document.createElement('span');
                indicator.className = 'required-indicator';
                indicator.setAttribute('aria-label', 'required');
                indicator.textContent = '*';
                label.appendChild(indicator);
            }
        });
    }

    /**
     * Get field label element
     * 
     * @param {Element} field Input field
     * @return {Element|null} Label element
     */
    getFieldLabelElement(field) {
        const labelId = field.getAttribute('aria-labelledby');
        if (labelId) {
            return document.getElementById(labelId);
        }
        
        if (field.id) {
            return document.querySelector(`label[for="${field.id}"]`);
        }
        
        return field.parentElement.querySelector('label');
    }

    /**
     * Set up media accessibility
     */
    setupMediaAccessibility() {
        // Add captions to videos
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            if (!video.querySelector('track[kind="captions"]')) {
                // Video should have captions - log for content creators
                if (this.config.debug) {
                    console.warn('♿ Video missing captions:', video);
                }
            }
        });
        
        // Add alt text validation for images
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (!img.getAttribute('alt') && !img.getAttribute('aria-hidden')) {
                if (this.config.debug) {
                    console.warn('♿ Image missing alt text:', img);
                }
                
                // Add empty alt for decorative images
                if (img.closest('.decoration, .background')) {
                    img.setAttribute('alt', '');
                }
            }
        });
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Track interaction types
        document.addEventListener('keydown', this.handleKeyDown);
        document.addEventListener('mousedown', this.handleMouseDown);
        
        // App events
        if (this.eventBus) {
            this.eventBus.on('app:route-changed', this.handleRouteChange.bind(this));
            this.eventBus.on('modal:opened', this.handleModalOpened.bind(this));
            this.eventBus.on('modal:closed', this.handleModalClosed.bind(this));
        }
    }

    /**
     * Handle keyboard events
     * 
     * @param {KeyboardEvent} e Keyboard event
     */
    handleKeyDown(e) {
        this.lastInteraction = 'keyboard';
        
        // Track if user is navigating with keyboard
        if (e.key === 'Tab') {
            this.keyboardUser = true;
            document.body.classList.add('keyboard-user');
        }
    }

    /**
     * Handle mouse events
     * 
     * @param {MouseEvent} e Mouse event
     */
    handleMouseDown(e) {
        this.lastInteraction = 'mouse';
        
        // Remove keyboard user class after mouse interaction
        if (this.keyboardUser) {
            this.keyboardUser = false;
            document.body.classList.remove('keyboard-user');
        }
    }

    /**
     * Handle route changes (for SPAs)
     * 
     * @param {Object} event Route change event
     */
    handleRouteChange(event) {
        if (this.config.announcePageChanges) {
            const pageName = event.data.title || 'Page';
            this.announce(`Navigated to ${pageName}`);
        }
        
        // Reset focus to main content
        setTimeout(() => {
            const main = document.querySelector('main, #main, .main-content');
            if (main) {
                main.focus();
            }
        }, 100);
    }

    /**
     * Handle modal opened
     * 
     * @param {Object} event Modal event
     */
    handleModalOpened(event) {
        const modal = event.data.modal;
        this.currentModal = modal;
        
        // Save current focus
        this.focusStack.push(document.activeElement);
        
        // Set up focus trap
        this.setupFocusTrap(modal);
        
        // Focus first focusable element in modal
        const firstFocusable = modal.querySelector(this.focusableSelectors);
        if (firstFocusable) {
            firstFocusable.focus();
        }
        
        // Announce modal opening
        this.announce('Dialog opened');
    }

    /**
     * Handle modal closed
     * 
     * @param {Object} event Modal event
     */
    handleModalClosed(event) {
        this.currentModal = null;
        
        // Restore focus
        const previousFocus = this.focusStack.pop();
        if (previousFocus) {
            previousFocus.focus();
        }
        
        // Announce modal closing
        this.announce('Dialog closed');
    }

    /**
     * Set up focus trap for modal
     * 
     * @param {Element} modal Modal element
     */
    setupFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(this.focusableSelectors);
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
        const handleTabKey = (e) => {
            if (e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    }
                } else {
                    if (document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        };
        
        modal.addEventListener('keydown', handleTabKey);
        
        // Store handler for cleanup
        modal._focusTrapHandler = handleTabKey;
    }

    /**
     * Close modal
     * 
     * @param {Element} modal Modal element
     */
    closeModal(modal) {
        // Remove focus trap
        if (modal._focusTrapHandler) {
            modal.removeEventListener('keydown', modal._focusTrapHandler);
            delete modal._focusTrapHandler;
        }
        
        // Hide modal
        modal.style.display = 'none';
        modal.setAttribute('aria-hidden', 'true');
        
        // Emit close event
        if (this.eventBus) {
            this.eventBus.emit('modal:closed', { modal });
        }
    }

    /**
     * Announce message to screen readers
     * 
     * @param {string} message Message to announce
     * @param {string} priority Priority level (polite|assertive)
     */
    announce(message, priority = 'polite') {
        if (!this.ariaLiveRegion) return;
        
        // Set priority
        this.ariaLiveRegion.setAttribute('aria-live', priority);
        
        // Clear previous announcement
        this.ariaLiveRegion.textContent = '';
        
        // Announce new message
        setTimeout(() => {
            this.ariaLiveRegion.textContent = message;
        }, 100);
        
        // Clear after announcement
        setTimeout(() => {
            this.ariaLiveRegion.textContent = '';
        }, 1000);
        
        if (this.config.debug) {
            console.log(`♿ Announced: ${message}`);
        }
    }

    /**
     * Update body classes based on accessibility state
     */
    updateBodyClasses() {
        const body = document.body;
        
        // Reduced motion
        if (this.reducedMotion) {
            body.classList.add('prefers-reduced-motion');
        } else {
            body.classList.remove('prefers-reduced-motion');
        }
        
        // High contrast
        if (this.highContrast) {
            body.classList.add('prefers-high-contrast');
        } else {
            body.classList.remove('prefers-high-contrast');
        }
        
        // Keyboard user
        if (this.keyboardUser) {
            body.classList.add('keyboard-user');
        } else {
            body.classList.remove('keyboard-user');
        }
    }

    /**
     * Get enabled features
     * 
     * @return {Array} Enabled feature names
     */
    getEnabledFeatures() {
        const features = [];
        
        Object.keys(this.config).forEach(key => {
            if (key.startsWith('enable') && this.config[key]) {
                features.push(key.replace('enable', '').toLowerCase());
            }
        });
        
        return features;
    }

    /**
     * Get user preferences
     * 
     * @return {Object} User accessibility preferences
     */
    getUserPreferences() {
        return {
            reducedMotion: this.reducedMotion,
            highContrast: this.highContrast,
            keyboardUser: this.keyboardUser,
            lastInteraction: this.lastInteraction
        };
    }

    /**
     * Get accessibility state
     * 
     * @return {Object} Current accessibility state
     */
    getState() {
        return {
            isInitialized: this.isInitialized,
            features: this.getEnabledFeatures(),
            userPreferences: this.getUserPreferences(),
            currentModal: this.currentModal,
            focusStackSize: this.focusStack.length
        };
    }

    /**
     * Cleanup accessibility features
     */
    cleanup() {
        // Remove event listeners
        document.removeEventListener('keydown', this.handleKeyDown);
        document.removeEventListener('mousedown', this.handleMouseDown);
        document.removeEventListener('focusin', this.handleFocusIn);
        document.removeEventListener('focusout', this.handleFocusOut);
        
        // Remove ARIA live region
        if (this.ariaLiveRegion) {
            this.ariaLiveRegion.remove();
        }
        
        // Clear focus stack
        this.focusStack = [];
        
        // Remove body classes
        document.body.classList.remove('keyboard-user', 'prefers-reduced-motion', 'prefers-high-contrast');
        
        if (this.config.debug) {
            console.log('♿ Accessibility features cleaned up');
        }
    }
}

// Export for module loader
export default Accessibility;
