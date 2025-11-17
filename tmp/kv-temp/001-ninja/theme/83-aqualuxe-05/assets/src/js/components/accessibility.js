/**
 * Accessibility Component
 * 
 * Comprehensive accessibility features including keyboard navigation,
 * screen reader support, focus management, and WCAG compliance.
 */

class Accessibility {
    constructor() {
        this.isReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.isHighContrast = window.matchMedia('(prefers-contrast: high)').matches;
        this.announcements = [];
        
        this.init();
    }

    init() {
        this.createLiveRegion();
        this.initKeyboardNavigation();
        this.initFocusManagement();
        this.initSkipLinks();
        this.initReducedMotion();
        this.initHighContrast();
        this.initAriaEnhancements();
        this.monitorChanges();
        
        console.log('Accessibility features initialized');
    }

    createLiveRegion() {
        // Create ARIA live region for announcements
        this.liveRegion = document.createElement('div');
        this.liveRegion.setAttribute('aria-live', 'polite');
        this.liveRegion.setAttribute('aria-atomic', 'true');
        this.liveRegion.className = 'sr-only';
        this.liveRegion.id = 'live-region';
        document.body.appendChild(this.liveRegion);
        
        // Create assertive live region for urgent announcements
        this.assertiveLiveRegion = document.createElement('div');
        this.assertiveLiveRegion.setAttribute('aria-live', 'assertive');
        this.assertiveLiveRegion.setAttribute('aria-atomic', 'true');
        this.assertiveLiveRegion.className = 'sr-only';
        this.assertiveLiveRegion.id = 'assertive-live-region';
        document.body.appendChild(this.assertiveLiveRegion);
    }

    announce(message, priority = 'polite') {
        if (!message) return;
        
        const region = priority === 'assertive' ? this.assertiveLiveRegion : this.liveRegion;
        
        // Clear previous message
        region.textContent = '';
        
        // Add new message after a brief delay to ensure it's announced
        setTimeout(() => {
            region.textContent = message;
            this.announcements.push({
                message,
                priority,
                timestamp: Date.now()
            });
        }, 100);
        
        // Auto-clear after 5 seconds
        setTimeout(() => {
            if (region.textContent === message) {
                region.textContent = '';
            }
        }, 5000);
    }

    initKeyboardNavigation() {
        // Enhanced tab navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                this.handleTabNavigation(e);
            }
        });
        
        // Focus visible indicator
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Tab' || e.key.startsWith('Arrow')) {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', () => {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Custom keyboard shortcuts
        this.initKeyboardShortcuts();
    }

    handleTabNavigation(e) {
        const focusableElements = this.getFocusableElements();
        const currentIndex = focusableElements.indexOf(document.activeElement);
        
        if (currentIndex === -1) return;
        
        let nextIndex;
        if (e.shiftKey) {
            // Reverse tab
            nextIndex = currentIndex === 0 ? focusableElements.length - 1 : currentIndex - 1;
        } else {
            // Forward tab
            nextIndex = currentIndex === focusableElements.length - 1 ? 0 : currentIndex + 1;
        }
        
        // Check if next element is visible and not disabled
        const nextElement = focusableElements[nextIndex];
        if (nextElement && this.isElementVisible(nextElement) && !nextElement.disabled) {
            return; // Let default behavior handle it
        }
        
        // Find next valid focusable element
        this.focusNextValidElement(focusableElements, nextIndex, e.shiftKey);
        e.preventDefault();
    }

    getFocusableElements() {
        const selectors = [
            'a[href]',
            'button:not([disabled])',
            'input:not([disabled])',
            'select:not([disabled])',
            'textarea:not([disabled])',
            '[tabindex]:not([tabindex="-1"])',
            '[contenteditable="true"]'
        ].join(', ');
        
        return Array.from(document.querySelectorAll(selectors))
            .filter(el => this.isElementVisible(el));
    }

    isElementVisible(element) {
        const style = window.getComputedStyle(element);
        const rect = element.getBoundingClientRect();
        
        return style.display !== 'none' &&
               style.visibility !== 'hidden' &&
               style.opacity !== '0' &&
               rect.width > 0 &&
               rect.height > 0;
    }

    focusNextValidElement(elements, startIndex, reverse = false) {
        let index = startIndex;
        let attempts = 0;
        
        while (attempts < elements.length) {
            const element = elements[index];
            
            if (element && this.isElementVisible(element) && !element.disabled) {
                element.focus();
                return;
            }
            
            if (reverse) {
                index = index === 0 ? elements.length - 1 : index - 1;
            } else {
                index = index === elements.length - 1 ? 0 : index + 1;
            }
            
            attempts++;
        }
    }

    initKeyboardShortcuts() {
        const shortcuts = {
            'Alt+M': () => this.focusMainContent(),
            'Alt+N': () => this.focusNavigation(),
            'Alt+S': () => this.focusSearch(),
            'Alt+F': () => this.focusFooter(),
            'Alt+1': () => this.focusHeading(1),
            'Alt+2': () => this.focusHeading(2),
            'Alt+3': () => this.focusHeading(3)
        };
        
        document.addEventListener('keydown', (e) => {
            const key = this.getKeyCombo(e);
            if (shortcuts[key]) {
                e.preventDefault();
                shortcuts[key]();
                this.announce(`Navigated to ${key.split('+')[1]} landmark`);
            }
        });
    }

    getKeyCombo(e) {
        const keys = [];
        if (e.altKey) keys.push('Alt');
        if (e.ctrlKey) keys.push('Ctrl');
        if (e.shiftKey) keys.push('Shift');
        if (e.metaKey) keys.push('Meta');
        keys.push(e.key);
        return keys.join('+');
    }

    focusMainContent() {
        const main = document.querySelector('main, [role="main"], .main-content');
        if (main) {
            main.focus();
            main.scrollIntoView();
        }
    }

    focusNavigation() {
        const nav = document.querySelector('nav, [role="navigation"], .main-navigation');
        if (nav) {
            const firstLink = nav.querySelector('a, button');
            if (firstLink) {
                firstLink.focus();
            }
        }
    }

    focusSearch() {
        const search = document.querySelector('[role="search"] input, .search-form input, input[type="search"]');
        if (search) {
            search.focus();
        }
    }

    focusFooter() {
        const footer = document.querySelector('footer, [role="contentinfo"]');
        if (footer) {
            footer.focus();
            footer.scrollIntoView();
        }
    }

    focusHeading(level) {
        const heading = document.querySelector(`h${level}`);
        if (heading) {
            heading.focus();
            heading.scrollIntoView();
        }
    }

    initFocusManagement() {
        // Focus trap for modals and overlays
        this.initFocusTraps();
        
        // Focus indicators
        this.enhanceFocusIndicators();
        
        // Route change focus management
        this.initRouteChangeFocus();
    }

    initFocusTraps() {
        document.addEventListener('keydown', (e) => {
            const activeModal = document.querySelector('.modal.modal-active');
            const activeOverlay = document.querySelector('.overlay.active');
            
            if (activeModal) {
                this.trapFocus(e, activeModal);
            } else if (activeOverlay) {
                this.trapFocus(e, activeOverlay);
            }
        });
    }

    trapFocus(e, container) {
        if (e.key !== 'Tab') return;
        
        const focusableElements = container.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;
        
        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];
        
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

    enhanceFocusIndicators() {
        // Add enhanced focus styles
        const style = document.createElement('style');
        style.textContent = `
            .keyboard-navigation *:focus {
                outline: 3px solid var(--color-aqua-500);
                outline-offset: 2px;
                box-shadow: 0 0 0 5px rgba(6, 182, 212, 0.2);
            }
            
            .focus-trap {
                position: relative;
            }
            
            .focus-trap::before,
            .focus-trap::after {
                content: '';
                position: absolute;
                width: 1px;
                height: 1px;
                opacity: 0;
                pointer-events: none;
            }
        `;
        document.head.appendChild(style);
    }

    initRouteChangeFocus() {
        // Handle focus management on route changes (for SPAs)
        window.addEventListener('popstate', () => {
            this.handleRouteChange();
        });
        
        // Override pushState and replaceState
        const originalPushState = history.pushState;
        const originalReplaceState = history.replaceState;
        
        history.pushState = (...args) => {
            originalPushState.apply(history, args);
            this.handleRouteChange();
        };
        
        history.replaceState = (...args) => {
            originalReplaceState.apply(history, args);
            this.handleRouteChange();
        };
    }

    handleRouteChange() {
        // Focus the main content area or first heading
        setTimeout(() => {
            const main = document.querySelector('main, [role="main"]');
            const firstHeading = document.querySelector('h1');
            
            if (main) {
                main.focus();
                this.announce('Page content updated');
            } else if (firstHeading) {
                firstHeading.focus();
                this.announce(`Navigated to ${firstHeading.textContent}`);
            }
        }, 100);
    }

    initSkipLinks() {
        // Enhance skip links functionality
        const skipLinks = document.querySelectorAll('.skip-link');
        
        skipLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                    this.announce(`Skipped to ${target.textContent || targetId}`);
                }
            });
        });
    }

    initReducedMotion() {
        if (this.isReducedMotion) {
            document.body.classList.add('reduce-motion');
        }
        
        // Listen for changes in motion preference
        const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
        mediaQuery.addEventListener('change', (e) => {
            this.isReducedMotion = e.matches;
            document.body.classList.toggle('reduce-motion', e.matches);
            
            if (e.matches) {
                this.announce('Reduced motion enabled');
            }
        });
    }

    initHighContrast() {
        if (this.isHighContrast) {
            document.body.classList.add('high-contrast');
        }
        
        // Listen for changes in contrast preference
        const mediaQuery = window.matchMedia('(prefers-contrast: high)');
        mediaQuery.addEventListener('change', (e) => {
            this.isHighContrast = e.matches;
            document.body.classList.toggle('high-contrast', e.matches);
            
            if (e.matches) {
                this.announce('High contrast mode enabled');
            }
        });
    }

    initAriaEnhancements() {
        // Automatically enhance common patterns
        this.enhanceButtons();
        this.enhanceLinks();
        this.enhanceForms();
        this.enhanceImages();
        this.enhanceHeadings();
    }

    enhanceButtons() {
        const buttons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
        
        buttons.forEach(button => {
            // Add aria-label if button only contains icons
            if (this.isIconOnlyButton(button)) {
                const icon = button.querySelector('i, svg, .icon');
                if (icon) {
                    const iconClass = icon.className;
                    let label = this.getIconLabel(iconClass);
                    if (label) {
                        button.setAttribute('aria-label', label);
                    }
                }
            }
            
            // Add role if needed
            if (button.type === 'submit') {
                button.setAttribute('role', 'button');
            }
        });
    }

    isIconOnlyButton(button) {
        const text = button.textContent.trim();
        return text === '' || text.length < 2;
    }

    getIconLabel(iconClass) {
        const iconMap = {
            'fa-search': 'Search',
            'fa-menu': 'Menu',
            'fa-close': 'Close',
            'fa-cart': 'Shopping Cart',
            'fa-user': 'User Account',
            'fa-heart': 'Favorites',
            'fa-play': 'Play',
            'fa-pause': 'Pause',
            'fa-arrow-left': 'Previous',
            'fa-arrow-right': 'Next'
        };
        
        for (const [icon, label] of Object.entries(iconMap)) {
            if (iconClass.includes(icon)) {
                return label;
            }
        }
        
        return null;
    }

    enhanceLinks() {
        const links = document.querySelectorAll('a[href]');
        
        links.forEach(link => {
            // Add context for links that open in new window
            if (link.target === '_blank' && !link.getAttribute('aria-label')) {
                const text = link.textContent.trim();
                link.setAttribute('aria-label', `${text} (opens in new window)`);
            }
            
            // Mark external links
            if (this.isExternalLink(link)) {
                link.classList.add('external-link');
                if (!link.getAttribute('aria-label')) {
                    const text = link.textContent.trim();
                    link.setAttribute('aria-label', `${text} (external link)`);
                }
            }
        });
    }

    isExternalLink(link) {
        return link.hostname !== window.location.hostname;
    }

    enhanceForms() {
        // Associate labels with inputs
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (!input.getAttribute('aria-label') && !input.getAttribute('aria-labelledby')) {
                const label = this.findAssociatedLabel(input);
                if (label) {
                    if (!label.id) {
                        label.id = `label-${Math.random().toString(36).substr(2, 9)}`;
                    }
                    input.setAttribute('aria-labelledby', label.id);
                }
            }
            
            // Add required indicator
            if (input.required && !input.getAttribute('aria-required')) {
                input.setAttribute('aria-required', 'true');
            }
            
            // Add invalid state
            if (input.validity && !input.validity.valid) {
                input.setAttribute('aria-invalid', 'true');
            }
        });
    }

    findAssociatedLabel(input) {
        // Check for explicit label association
        if (input.id) {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (label) return label;
        }
        
        // Check for implicit label association
        const label = input.closest('label');
        if (label) return label;
        
        // Check for nearby label
        const previousLabel = input.previousElementSibling;
        if (previousLabel && previousLabel.tagName === 'LABEL') {
            return previousLabel;
        }
        
        return null;
    }

    enhanceImages() {
        const images = document.querySelectorAll('img:not([alt])');
        
        images.forEach(img => {
            // Add empty alt for decorative images
            if (this.isDecorativeImage(img)) {
                img.setAttribute('alt', '');
            } else {
                // Try to generate alt text from context
                const altText = this.generateAltText(img);
                if (altText) {
                    img.setAttribute('alt', altText);
                }
            }
        });
    }

    isDecorativeImage(img) {
        const decorativeClasses = ['decoration', 'ornament', 'spacer', 'divider'];
        return decorativeClasses.some(cls => img.className.includes(cls));
    }

    generateAltText(img) {
        // Try to get alt text from filename or title
        const src = img.src;
        const filename = src.substring(src.lastIndexOf('/') + 1);
        const title = img.title;
        
        if (title) return title;
        
        // Generate from filename
        return filename
            .replace(/\.[^/.]+$/, '') // Remove extension
            .replace(/[-_]/g, ' ')    // Replace dashes and underscores
            .replace(/\b\w/g, l => l.toUpperCase()); // Capitalize words
    }

    enhanceHeadings() {
        // Ensure heading hierarchy
        const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
        let currentLevel = 0;
        
        headings.forEach(heading => {
            const level = parseInt(heading.tagName.charAt(1));
            
            if (level > currentLevel + 1) {
                console.warn(`Heading hierarchy issue: ${heading.tagName} follows h${currentLevel}`);
            }
            
            currentLevel = level;
            
            // Add landmark role to first heading
            if (level === 1 && !heading.getAttribute('role')) {
                heading.setAttribute('role', 'banner');
            }
        });
    }

    monitorChanges() {
        // Monitor DOM changes and enhance new elements
        const observer = new MutationObserver((mutations) => {
            mutations.forEach(mutation => {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        this.enhanceNewElement(node);
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    enhanceNewElement(element) {
        // Apply accessibility enhancements to new elements
        if (element.tagName === 'BUTTON') {
            this.enhanceButtons();
        } else if (element.tagName === 'A') {
            this.enhanceLinks();
        } else if (['INPUT', 'SELECT', 'TEXTAREA'].includes(element.tagName)) {
            this.enhanceForms();
        } else if (element.tagName === 'IMG') {
            this.enhanceImages();
        }
        
        // Check for new elements within the added element
        const buttons = element.querySelectorAll('button');
        const links = element.querySelectorAll('a');
        const formElements = element.querySelectorAll('input, select, textarea');
        const images = element.querySelectorAll('img');
        
        if (buttons.length) this.enhanceButtons();
        if (links.length) this.enhanceLinks();
        if (formElements.length) this.enhanceForms();
        if (images.length) this.enhanceImages();
    }

    // Public API methods
    setFocusTrap(element) {
        element.classList.add('focus-trap');
    }

    removeFocusTrap(element) {
        element.classList.remove('focus-trap');
    }

    addLandmark(element, role, label) {
        element.setAttribute('role', role);
        if (label) {
            element.setAttribute('aria-label', label);
        }
    }

    makeClickable(element, callback) {
        element.setAttribute('role', 'button');
        element.setAttribute('tabindex', '0');
        
        element.addEventListener('click', callback);
        element.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                callback(e);
            }
        });
    }

    getAccessibilityReport() {
        return {
            announcements: this.announcements,
            reducedMotion: this.isReducedMotion,
            highContrast: this.isHighContrast,
            focusableElements: this.getFocusableElements().length,
            missingAltImages: document.querySelectorAll('img:not([alt])').length,
            unlabeledInputs: document.querySelectorAll('input:not([aria-label]):not([aria-labelledby])').length
        };
    }
}

export default Accessibility;
