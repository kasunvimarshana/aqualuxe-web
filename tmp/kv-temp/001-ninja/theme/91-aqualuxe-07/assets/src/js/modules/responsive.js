/**
 * AquaLuxe Responsive Module
 *
 * Responsive utilities and viewport management
 * Handles breakpoints, media queries, and responsive behavior
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Responsive Class
 * 
 * Manages responsive behavior, breakpoints, and viewport utilities
 */
export class Responsive {
    /**
     * Constructor
     * 
     * @param {Object} config Responsive configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            breakpoints: {
                xs: 0,
                sm: 576,
                md: 768,
                lg: 992,
                xl: 1200,
                xxl: 1400
            },
            debounceDelay: 250,
            ...config
        };
        
        this.eventBus = eventBus;
        this.isInitialized = false;
        
        // Current state
        this.currentBreakpoint = null;
        this.viewport = {
            width: 0,
            height: 0
        };
        
        // Media query listeners
        this.mediaQueries = new Map();
        this.resizeTimeout = null;
        
        // Bound methods
        this.handleResize = this.handleResize.bind(this);
        this.handleOrientationChange = this.handleOrientationChange.bind(this);
        
        this.init();
    }

    /**
     * Initialize responsive utilities
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Set initial viewport
            this.updateViewport();
            
            // Set up breakpoint detection
            this.setupBreakpoints();
            
            // Set up media queries
            this.setupMediaQueries();
            
            // Set up viewport classes
            this.updateViewportClasses();
            
            // Bind events
            this.bindEvents();
            
            this.isInitialized = true;
            
            // Emit initialization event
            if (this.eventBus) {
                this.eventBus.emit('responsive:initialized', {
                    breakpoint: this.currentBreakpoint,
                    viewport: this.viewport
                });
            }
            
            if (this.config.debug) {
                console.log('📱 Responsive utilities initialized');
            }
            
        } catch (error) {
            console.error('❌ Responsive initialization failed:', error);
        }
    }

    /**
     * Update viewport dimensions
     */
    updateViewport() {
        this.viewport = {
            width: window.innerWidth,
            height: window.innerHeight
        };
        
        // Update CSS custom properties
        document.documentElement.style.setProperty('--viewport-width', `${this.viewport.width}px`);
        document.documentElement.style.setProperty('--viewport-height', `${this.viewport.height}px`);
        
        // Calculate viewport units for mobile browsers
        const vh = this.viewport.height * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }

    /**
     * Set up breakpoint detection
     */
    setupBreakpoints() {
        const previousBreakpoint = this.currentBreakpoint;
        this.currentBreakpoint = this.getCurrentBreakpoint();
        
        if (previousBreakpoint !== this.currentBreakpoint) {
            this.handleBreakpointChange(previousBreakpoint, this.currentBreakpoint);
        }
    }

    /**
     * Get current breakpoint
     * 
     * @return {string} Current breakpoint name
     */
    getCurrentBreakpoint() {
        const width = this.viewport.width;
        const breakpoints = Object.entries(this.config.breakpoints)
            .sort(([,a], [,b]) => b - a); // Sort descending
        
        for (const [name, minWidth] of breakpoints) {
            if (width >= minWidth) {
                return name;
            }
        }
        
        return 'xs';
    }

    /**
     * Handle breakpoint change
     * 
     * @param {string} previous Previous breakpoint
     * @param {string} current Current breakpoint
     */
    handleBreakpointChange(previous, current) {
        // Update body class
        if (previous) {
            document.body.classList.remove(`breakpoint-${previous}`);
        }
        document.body.classList.add(`breakpoint-${current}`);
        
        // Emit breakpoint change event
        if (this.eventBus) {
            this.eventBus.emit('responsive:breakpoint-changed', {
                previous,
                current,
                viewport: this.viewport
            });
        }
        
        if (this.config.debug) {
            console.log(`📱 Breakpoint changed: ${previous} → ${current}`);
        }
    }

    /**
     * Set up media queries
     */
    setupMediaQueries() {
        // Set up breakpoint media queries
        Object.entries(this.config.breakpoints).forEach(([name, width]) => {
            if (width > 0) {
                this.addMediaQuery(`(min-width: ${width}px)`, `breakpoint-${name}-up`);
            }
        });
        
        // Common media queries
        this.addMediaQuery('(orientation: portrait)', 'orientation-portrait');
        this.addMediaQuery('(orientation: landscape)', 'orientation-landscape');
        this.addMediaQuery('(prefers-reduced-motion: reduce)', 'prefers-reduced-motion');
        this.addMediaQuery('(prefers-color-scheme: dark)', 'prefers-dark-mode');
        this.addMediaQuery('(hover: hover)', 'hover-support');
        this.addMediaQuery('(pointer: coarse)', 'touch-device');
        this.addMediaQuery('(display-mode: standalone)', 'pwa-mode');
    }

    /**
     * Add media query listener
     * 
     * @param {string} query Media query string
     * @param {string} name Query name
     */
    addMediaQuery(query, name) {
        if (!window.matchMedia) {
            return;
        }
        
        const mq = window.matchMedia(query);
        
        const handler = (e) => {
            const className = `mq-${name}`;
            
            if (e.matches) {
                document.body.classList.add(className);
            } else {
                document.body.classList.remove(className);
            }
            
            // Emit media query change event
            if (this.eventBus) {
                this.eventBus.emit('responsive:media-query-changed', {
                    query,
                    name,
                    matches: e.matches
                });
            }
        };
        
        // Set initial state
        handler(mq);
        
        // Add listener
        mq.addListener(handler);
        
        // Store for cleanup
        this.mediaQueries.set(name, { mq, handler });
    }

    /**
     * Update viewport classes
     */
    updateViewportClasses() {
        const body = document.body;
        
        // Remove existing viewport classes
        body.classList.forEach(className => {
            if (className.startsWith('vw-') || className.startsWith('vh-')) {
                body.classList.remove(className);
            }
        });
        
        // Add viewport width classes
        const widthClass = this.getViewportSizeClass(this.viewport.width, 'vw');
        if (widthClass) {
            body.classList.add(widthClass);
        }
        
        // Add viewport height classes
        const heightClass = this.getViewportSizeClass(this.viewport.height, 'vh');
        if (heightClass) {
            body.classList.add(heightClass);
        }
        
        // Add aspect ratio class
        const aspectRatio = this.viewport.width / this.viewport.height;
        const aspectClass = aspectRatio > 1 ? 'aspect-landscape' : 'aspect-portrait';
        body.classList.add(aspectClass);
    }

    /**
     * Get viewport size class
     * 
     * @param {number} size Viewport dimension
     * @param {string} prefix Class prefix
     * @return {string|null} Size class
     */
    getViewportSizeClass(size, prefix) {
        if (size < 480) return `${prefix}-xs`;
        if (size < 768) return `${prefix}-sm`;
        if (size < 1024) return `${prefix}-md`;
        if (size < 1200) return `${prefix}-lg`;
        if (size < 1600) return `${prefix}-xl`;
        return `${prefix}-xxl`;
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Resize event with debouncing
        window.addEventListener('resize', this.handleResize, { passive: true });
        
        // Orientation change
        window.addEventListener('orientationchange', this.handleOrientationChange);
        
        // App events
        if (this.eventBus) {
            this.eventBus.on('app:resize', this.handleAppResize.bind(this));
        }
    }

    /**
     * Handle resize events
     */
    handleResize() {
        // Debounce resize events
        clearTimeout(this.resizeTimeout);
        
        this.resizeTimeout = setTimeout(() => {
            this.updateViewport();
            this.setupBreakpoints();
            this.updateViewportClasses();
            
            // Emit resize event
            if (this.eventBus) {
                this.eventBus.emit('responsive:resize', {
                    viewport: this.viewport,
                    breakpoint: this.currentBreakpoint
                });
            }
        }, this.config.debounceDelay);
    }

    /**
     * Handle orientation change
     */
    handleOrientationChange() {
        // Small delay to allow for viewport changes
        setTimeout(() => {
            this.updateViewport();
            this.setupBreakpoints();
            this.updateViewportClasses();
            
            // Emit orientation change event
            if (this.eventBus) {
                this.eventBus.emit('responsive:orientation-changed', {
                    viewport: this.viewport,
                    breakpoint: this.currentBreakpoint,
                    orientation: this.viewport.width > this.viewport.height ? 'landscape' : 'portrait'
                });
            }
        }, 100);
    }

    /**
     * Handle app resize events
     * 
     * @param {Object} event Event data
     */
    handleAppResize(event) {
        // App already provides viewport data
        this.viewport = {
            width: event.data.width,
            height: event.data.height
        };
        
        this.setupBreakpoints();
        this.updateViewportClasses();
    }

    /**
     * Check if current viewport matches breakpoint
     * 
     * @param {string} breakpoint Breakpoint name
     * @return {boolean} True if matches
     */
    isBreakpoint(breakpoint) {
        return this.currentBreakpoint === breakpoint;
    }

    /**
     * Check if current viewport is at or above breakpoint
     * 
     * @param {string} breakpoint Breakpoint name
     * @return {boolean} True if at or above
     */
    isBreakpointUp(breakpoint) {
        const currentWidth = this.viewport.width;
        const breakpointWidth = this.config.breakpoints[breakpoint];
        
        return currentWidth >= breakpointWidth;
    }

    /**
     * Check if current viewport is below breakpoint
     * 
     * @param {string} breakpoint Breakpoint name
     * @return {boolean} True if below
     */
    isBreakpointDown(breakpoint) {
        const currentWidth = this.viewport.width;
        const breakpointWidth = this.config.breakpoints[breakpoint];
        
        return currentWidth < breakpointWidth;
    }

    /**
     * Check if current viewport is between breakpoints
     * 
     * @param {string} minBreakpoint Minimum breakpoint
     * @param {string} maxBreakpoint Maximum breakpoint
     * @return {boolean} True if between
     */
    isBreakpointBetween(minBreakpoint, maxBreakpoint) {
        const currentWidth = this.viewport.width;
        const minWidth = this.config.breakpoints[minBreakpoint];
        const maxWidth = this.config.breakpoints[maxBreakpoint];
        
        return currentWidth >= minWidth && currentWidth < maxWidth;
    }

    /**
     * Check if device is mobile
     * 
     * @return {boolean} True if mobile
     */
    isMobile() {
        return this.isBreakpointDown('md');
    }

    /**
     * Check if device is tablet
     * 
     * @return {boolean} True if tablet
     */
    isTablet() {
        return this.isBreakpointBetween('md', 'lg');
    }

    /**
     * Check if device is desktop
     * 
     * @return {boolean} True if desktop
     */
    isDesktop() {
        return this.isBreakpointUp('lg');
    }

    /**
     * Check if device is touch-enabled
     * 
     * @return {boolean} True if touch-enabled
     */
    isTouchDevice() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    }

    /**
     * Check if device supports hover
     * 
     * @return {boolean} True if supports hover
     */
    supportsHover() {
        return window.matchMedia('(hover: hover)').matches;
    }

    /**
     * Get current viewport info
     * 
     * @return {Object} Viewport information
     */
    getViewportInfo() {
        return {
            width: this.viewport.width,
            height: this.viewport.height,
            breakpoint: this.currentBreakpoint,
            isMobile: this.isMobile(),
            isTablet: this.isTablet(),
            isDesktop: this.isDesktop(),
            isTouchDevice: this.isTouchDevice(),
            supportsHover: this.supportsHover(),
            orientation: this.viewport.width > this.viewport.height ? 'landscape' : 'portrait',
            aspectRatio: this.viewport.width / this.viewport.height
        };
    }

    /**
     * Get responsive image sizes
     * 
     * @param {Object} options Size options
     * @return {string} Sizes attribute string
     */
    getImageSizes(options = {}) {
        const {
            mobile = '100vw',
            tablet = '50vw',
            desktop = '33vw',
            breakpoints = this.config.breakpoints
        } = options;
        
        const sizes = [];
        
        // Add breakpoint-specific sizes
        if (breakpoints.lg) {
            sizes.push(`(min-width: ${breakpoints.lg}px) ${desktop}`);
        }
        
        if (breakpoints.md) {
            sizes.push(`(min-width: ${breakpoints.md}px) ${tablet}`);
        }
        
        // Default size
        sizes.push(mobile);
        
        return sizes.join(', ');
    }

    /**
     * Create responsive utility functions
     * 
     * @return {Object} Utility functions
     */
    createUtils() {
        return {
            // Breakpoint utilities
            isBreakpoint: this.isBreakpoint.bind(this),
            isBreakpointUp: this.isBreakpointUp.bind(this),
            isBreakpointDown: this.isBreakpointDown.bind(this),
            isBreakpointBetween: this.isBreakpointBetween.bind(this),
            
            // Device utilities
            isMobile: this.isMobile.bind(this),
            isTablet: this.isTablet.bind(this),
            isDesktop: this.isDesktop.bind(this),
            isTouchDevice: this.isTouchDevice.bind(this),
            supportsHover: this.supportsHover.bind(this),
            
            // Info utilities
            getViewportInfo: this.getViewportInfo.bind(this),
            getImageSizes: this.getImageSizes.bind(this),
            
            // Current state
            get viewport() { return this.viewport; },
            get breakpoint() { return this.currentBreakpoint; }
        };
    }

    /**
     * Add global responsive utilities
     */
    addGlobalUtils() {
        // Add utilities to window for global access
        window.aqualuxeResponsive = this.createUtils();
        
        // Add CSS custom properties for JavaScript access
        const root = document.documentElement;
        Object.entries(this.config.breakpoints).forEach(([name, value]) => {
            root.style.setProperty(`--breakpoint-${name}`, `${value}px`);
        });
    }

    /**
     * Get responsive state
     * 
     * @return {Object} Current responsive state
     */
    getState() {
        return {
            isInitialized: this.isInitialized,
            viewport: this.viewport,
            breakpoint: this.currentBreakpoint,
            mediaQueries: Array.from(this.mediaQueries.keys()),
            deviceInfo: {
                isMobile: this.isMobile(),
                isTablet: this.isTablet(),
                isDesktop: this.isDesktop(),
                isTouchDevice: this.isTouchDevice(),
                supportsHover: this.supportsHover()
            }
        };
    }

    /**
     * Cleanup responsive utilities
     */
    cleanup() {
        // Remove event listeners
        window.removeEventListener('resize', this.handleResize);
        window.removeEventListener('orientationchange', this.handleOrientationChange);
        
        // Clear resize timeout
        clearTimeout(this.resizeTimeout);
        
        // Clean up media queries
        this.mediaQueries.forEach(({ mq, handler }) => {
            mq.removeListener(handler);
        });
        this.mediaQueries.clear();
        
        // Remove global utilities
        if (window.aqualuxeResponsive) {
            delete window.aqualuxeResponsive;
        }
        
        if (this.config.debug) {
            console.log('📱 Responsive utilities cleaned up');
        }
    }
}

// Export for module loader
export default Responsive;
