/**
 * AquaLuxe Animations Module
 *
 * Animation management using GSAP and CSS animations
 * Respects user preferences for reduced motion
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

/**
 * Animations Class
 * 
 * Manages animations throughout the theme with accessibility support
 * Uses GSAP for complex animations and CSS for simple ones
 */
export class Animations {
    /**
     * Constructor
     * 
     * @param {Object} config Animation configuration
     * @param {EventBus} eventBus Event bus instance
     */
    constructor(config = {}, eventBus = null) {
        this.config = {
            debug: false,
            respectReducedMotion: true,
            enableScrollTrigger: true,
            enableMorphism: true,
            enablePageTransitions: true,
            enableHoverEffects: true,
            defaultDuration: 0.6,
            defaultEase: 'power2.out',
            staggerDelay: 0.1,
            ...config
        };
        
        this.eventBus = eventBus;
        this.isInitialized = false;
        
        // Animation state
        this.reducedMotion = false;
        this.animations = new Map(); // Store active animations
        this.timelines = new Map(); // Store GSAP timelines
        this.observers = new Map(); // Store intersection observers
        
        // GSAP availability
        this.hasGSAP = typeof gsap !== 'undefined';
        this.hasScrollTrigger = typeof ScrollTrigger !== 'undefined';
        
        // Animation registry
        this.animationTypes = {
            fade: this.createFadeAnimation.bind(this),
            slide: this.createSlideAnimation.bind(this),
            scale: this.createScaleAnimation.bind(this),
            rotate: this.createRotateAnimation.bind(this),
            morphism: this.createMorphismAnimation.bind(this),
            wave: this.createWaveAnimation.bind(this),
            float: this.createFloatAnimation.bind(this),
            glow: this.createGlowAnimation.bind(this)
        };
        
        this.init();
    }

    /**
     * Initialize animations
     */
    async init() {
        if (this.isInitialized) {
            return;
        }
        
        try {
            // Check user preferences
            this.checkReducedMotionPreference();
            
            // Initialize GSAP if available
            this.initializeGSAP();
            
            // Set up automatic animations
            this.setupScrollAnimations();
            this.setupHoverEffects();
            this.setupPageTransitions();
            this.setupMorphismEffects();
            
            // Bind events
            this.bindEvents();
            
            this.isInitialized = true;
            
            // Emit initialization event
            if (this.eventBus) {
                this.eventBus.emit('animations:initialized', {
                    hasGSAP: this.hasGSAP,
                    hasScrollTrigger: this.hasScrollTrigger,
                    reducedMotion: this.reducedMotion
                });
            }
            
            if (this.config.debug) {
                console.log('🎬 Animations initialized:', {
                    hasGSAP: this.hasGSAP,
                    hasScrollTrigger: this.hasScrollTrigger,
                    reducedMotion: this.reducedMotion
                });
            }
            
        } catch (error) {
            console.error('❌ Animations initialization failed:', error);
        }
    }

    /**
     * Check user's reduced motion preference
     */
    checkReducedMotionPreference() {
        if (!this.config.respectReducedMotion) return;
        
        // Check media query
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        // Listen for changes
        window.matchMedia('(prefers-reduced-motion: reduce)').addListener((e) => {
            this.reducedMotion = e.matches;
            this.updateAnimationsForMotionPreference();
            
            if (this.eventBus) {
                this.eventBus.emit('animations:reduced-motion-changed', { 
                    reducedMotion: this.reducedMotion 
                });
            }
        });
        
        if (this.config.debug && this.reducedMotion) {
            console.log('🎬 Reduced motion preference detected - animations will be simplified');
        }
    }

    /**
     * Initialize GSAP configuration
     */
    initializeGSAP() {
        if (!this.hasGSAP) {
            if (this.config.debug) {
                console.warn('🎬 GSAP not available - using CSS animations fallback');
            }
            return;
        }
        
        // Register GSAP plugins
        if (this.hasScrollTrigger && this.config.enableScrollTrigger) {
            gsap.registerPlugin(ScrollTrigger);
            
            // Configure ScrollTrigger defaults
            ScrollTrigger.defaults({
                toggleActions: 'play none none reverse',
                start: 'top 85%',
                end: 'bottom 15%'
            });
        }
        
        // Set GSAP defaults
        gsap.defaults({
            duration: this.config.defaultDuration,
            ease: this.config.defaultEase
        });
        
        // Reduce motion settings
        if (this.reducedMotion) {
            gsap.globalTimeline.timeScale(0.01); // Near-instant animations
        }
    }

    /**
     * Set up scroll-triggered animations
     */
    setupScrollAnimations() {
        // Fade in elements on scroll
        this.setupScrollAnimation('[data-animate="fade-in"]', 'fade');
        this.setupScrollAnimation('[data-animate="slide-up"]', 'slide', { direction: 'up' });
        this.setupScrollAnimation('[data-animate="slide-down"]', 'slide', { direction: 'down' });
        this.setupScrollAnimation('[data-animate="slide-left"]', 'slide', { direction: 'left' });
        this.setupScrollAnimation('[data-animate="slide-right"]', 'slide', { direction: 'right' });
        this.setupScrollAnimation('[data-animate="scale-in"]', 'scale');
        this.setupScrollAnimation('[data-animate="rotate-in"]', 'rotate');
        
        // Staggered animations
        this.setupStaggeredAnimations();
        
        // Parallax effects (if not reduced motion)
        if (!this.reducedMotion) {
            this.setupParallaxEffects();
        }
    }

    /**
     * Set up scroll animation for elements
     * 
     * @param {string} selector Element selector
     * @param {string} animationType Animation type
     * @param {Object} options Animation options
     */
    setupScrollAnimation(selector, animationType, options = {}) {
        const elements = document.querySelectorAll(selector);
        if (elements.length === 0) return;
        
        elements.forEach((element, index) => {
            if (this.hasGSAP && this.hasScrollTrigger && !this.reducedMotion) {
                this.createGSAPScrollAnimation(element, animationType, options, index);
            } else {
                this.createIntersectionObserverAnimation(element, animationType, options);
            }
        });
    }

    /**
     * Create GSAP scroll animation
     * 
     * @param {Element} element Target element
     * @param {string} animationType Animation type
     * @param {Object} options Animation options
     * @param {number} index Element index for stagger
     */
    createGSAPScrollAnimation(element, animationType, options, index) {
        const animation = this.animationTypes[animationType];
        if (!animation) return;
        
        // Create initial state
        const { from, to } = animation(element, options);
        
        // Set initial state
        gsap.set(element, from);
        
        // Create scroll trigger animation
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: element,
                start: options.start || 'top 85%',
                end: options.end || 'bottom 15%',
                toggleActions: options.toggleActions || 'play none none reverse',
                onToggle: (self) => {
                    if (this.eventBus) {
                        this.eventBus.emit('animations:scroll-toggle', {
                            element,
                            isActive: self.isActive,
                            direction: self.direction
                        });
                    }
                }
            }
        });
        
        // Add stagger delay
        const delay = options.stagger ? index * this.config.staggerDelay : 0;
        
        // Add animation to timeline
        tl.to(element, {
            ...to,
            duration: options.duration || this.config.defaultDuration,
            ease: options.ease || this.config.defaultEase,
            delay: delay
        });
        
        // Store timeline
        this.timelines.set(`scroll-${element.dataset.animateId || index}`, tl);
    }

    /**
     * Create intersection observer animation fallback
     * 
     * @param {Element} element Target element
     * @param {string} animationType Animation type
     * @param {Object} options Animation options
     */
    createIntersectionObserverAnimation(element, animationType, options) {
        const animation = this.animationTypes[animationType];
        if (!animation) return;
        
        const { from } = animation(element, options);
        
        // Apply initial state via CSS
        Object.assign(element.style, from);
        
        // Create intersection observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.triggerCSSAnimation(entry.target, animationType, options);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -10% 0px'
        });
        
        observer.observe(element);
        this.observers.set(`scroll-${element.dataset.animateId || Date.now()}`, observer);
    }

    /**
     * Trigger CSS animation
     * 
     * @param {Element} element Target element
     * @param {string} animationType Animation type
     * @param {Object} options Animation options
     */
    triggerCSSAnimation(element, animationType, options) {
        const duration = this.reducedMotion ? 0.01 : (options.duration || this.config.defaultDuration);
        
        element.style.transition = `all ${duration}s ${options.ease || 'ease-out'}`;
        element.classList.add(`animate-${animationType}`);
        
        // Add CSS classes for specific animations
        if (options.direction) {
            element.classList.add(`animate-${animationType}-${options.direction}`);
        }
        
        // Emit event
        if (this.eventBus) {
            this.eventBus.emit('animations:css-triggered', {
                element,
                animationType,
                options
            });
        }
    }

    /**
     * Set up staggered animations
     */
    setupStaggeredAnimations() {
        const staggerContainers = document.querySelectorAll('[data-stagger]');
        
        staggerContainers.forEach(container => {
            const children = container.children;
            const staggerType = container.dataset.stagger || 'fade';
            const staggerDelay = parseFloat(container.dataset.staggerDelay) || this.config.staggerDelay;
            
            Array.from(children).forEach((child, index) => {
                child.dataset.animate = staggerType;
                child.style.animationDelay = `${index * staggerDelay}s`;
                
                if (this.hasGSAP && !this.reducedMotion) {
                    this.setupScrollAnimation(child, staggerType, { 
                        stagger: true, 
                        staggerDelay 
                    });
                } else {
                    this.createIntersectionObserverAnimation(child, staggerType, { 
                        delay: index * staggerDelay 
                    });
                }
            });
        });
    }

    /**
     * Set up parallax effects
     */
    setupParallaxEffects() {
        if (!this.hasGSAP || !this.hasScrollTrigger || this.reducedMotion) return;
        
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        
        parallaxElements.forEach(element => {
            const speed = parseFloat(element.dataset.parallax) || 0.5;
            const direction = element.dataset.parallaxDirection || 'up';
            
            gsap.to(element, {
                yPercent: direction === 'up' ? -50 * speed : 50 * speed,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                }
            });
        });
    }

    /**
     * Set up hover effects
     */
    setupHoverEffects() {
        if (!this.config.enableHoverEffects) return;
        
        // Card hover effects
        this.setupCardHoverEffects();
        
        // Button hover effects
        this.setupButtonHoverEffects();
        
        // Image hover effects
        this.setupImageHoverEffects();
    }

    /**
     * Set up card hover effects
     */
    setupCardHoverEffects() {
        const cards = document.querySelectorAll('.card, .product-card, [data-hover="card"]');
        
        cards.forEach(card => {
            if (this.hasGSAP && !this.reducedMotion) {
                card.addEventListener('mouseenter', () => {
                    gsap.to(card, {
                        y: -8,
                        scale: 1.02,
                        boxShadow: '0 20px 40px rgba(0, 0, 0, 0.15)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
                
                card.addEventListener('mouseleave', () => {
                    gsap.to(card, {
                        y: 0,
                        scale: 1,
                        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
                        duration: 0.3,
                        ease: 'power2.out'
                    });
                });
            } else {
                card.classList.add('hover-lift');
            }
        });
    }

    /**
     * Set up button hover effects
     */
    setupButtonHoverEffects() {
        const buttons = document.querySelectorAll('.btn-animate, [data-hover="button"]');
        
        buttons.forEach(button => {
            if (this.hasGSAP && !this.reducedMotion) {
                const originalBg = getComputedStyle(button).backgroundColor;
                
                button.addEventListener('mouseenter', () => {
                    gsap.to(button, {
                        scale: 1.05,
                        duration: 0.2,
                        ease: 'power2.out'
                    });
                });
                
                button.addEventListener('mouseleave', () => {
                    gsap.to(button, {
                        scale: 1,
                        duration: 0.2,
                        ease: 'power2.out'
                    });
                });
            } else {
                button.classList.add('hover-scale');
            }
        });
    }

    /**
     * Set up image hover effects
     */
    setupImageHoverEffects() {
        const images = document.querySelectorAll('.image-hover, [data-hover="image"]');
        
        images.forEach(imageContainer => {
            const image = imageContainer.querySelector('img');
            if (!image) return;
            
            if (this.hasGSAP && !this.reducedMotion) {
                imageContainer.addEventListener('mouseenter', () => {
                    gsap.to(image, {
                        scale: 1.1,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
                
                imageContainer.addEventListener('mouseleave', () => {
                    gsap.to(image, {
                        scale: 1,
                        duration: 0.4,
                        ease: 'power2.out'
                    });
                });
            } else {
                imageContainer.classList.add('hover-zoom');
            }
        });
    }

    /**
     * Set up page transitions
     */
    setupPageTransitions() {
        if (!this.config.enablePageTransitions || this.reducedMotion) return;
        
        // Page load animation
        this.animatePageLoad();
        
        // Link transition effects
        this.setupLinkTransitions();
    }

    /**
     * Animate page load
     */
    animatePageLoad() {
        if (this.hasGSAP) {
            // Fade in main content
            gsap.from('main', {
                opacity: 0,
                y: 30,
                duration: 0.8,
                ease: 'power2.out'
            });
            
            // Stagger in navigation items
            gsap.from('.main-navigation a', {
                opacity: 0,
                y: -20,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out',
                delay: 0.2
            });
            
        } else {
            document.body.classList.add('page-loaded');
        }
    }

    /**
     * Set up link transitions
     */
    setupLinkTransitions() {
        const transitionLinks = document.querySelectorAll('a[data-transition]');
        
        transitionLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                if (this.shouldInterceptLink(link)) {
                    e.preventDefault();
                    this.transitionToPage(link.href);
                }
            });
        });
    }

    /**
     * Check if link should be intercepted for transitions
     * 
     * @param {Element} link Link element
     * @return {boolean} Should intercept
     */
    shouldInterceptLink(link) {
        // Don't intercept external links
        if (link.hostname !== window.location.hostname) return false;
        
        // Don't intercept if target is set
        if (link.target) return false;
        
        // Don't intercept if modifier keys are pressed
        if (event.metaKey || event.ctrlKey || event.shiftKey) return false;
        
        return true;
    }

    /**
     * Transition to new page
     * 
     * @param {string} url Target URL
     */
    transitionToPage(url) {
        if (this.hasGSAP) {
            const tl = gsap.timeline({
                onComplete: () => {
                    window.location.href = url;
                }
            });
            
            tl.to('main', {
                opacity: 0,
                y: -30,
                duration: 0.4,
                ease: 'power2.in'
            });
            
        } else {
            document.body.classList.add('page-transitioning');
            setTimeout(() => {
                window.location.href = url;
            }, 300);
        }
    }

    /**
     * Set up morphism effects
     */
    setupMorphismEffects() {
        if (!this.config.enableMorphism || this.reducedMotion) return;
        
        const morphElements = document.querySelectorAll('.glass-morphism, [data-morphism]');
        
        morphElements.forEach(element => {
            this.createMorphismEffect(element);
        });
    }

    /**
     * Create morphism effect
     * 
     * @param {Element} element Target element
     */
    createMorphismEffect(element) {
        if (this.hasGSAP) {
            // Subtle floating animation
            gsap.to(element, {
                y: '+=10',
                duration: 2,
                ease: 'power1.inOut',
                yoyo: true,
                repeat: -1
            });
            
            // Gentle glow pulse
            gsap.to(element, {
                boxShadow: '0 8px 32px rgba(31, 38, 135, 0.37)',
                duration: 3,
                ease: 'power1.inOut',
                yoyo: true,
                repeat: -1
            });
            
        } else {
            element.classList.add('morphism-float');
        }
    }

    // Animation creators for different types
    
    /**
     * Create fade animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createFadeAnimation(element, options = {}) {
        return {
            from: { opacity: 0 },
            to: { opacity: 1 }
        };
    }

    /**
     * Create slide animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createSlideAnimation(element, options = {}) {
        const direction = options.direction || 'up';
        const distance = options.distance || 50;
        
        const transforms = {
            up: { y: distance },
            down: { y: -distance },
            left: { x: distance },
            right: { x: -distance }
        };
        
        return {
            from: { 
                opacity: 0,
                ...transforms[direction]
            },
            to: { 
                opacity: 1,
                x: 0,
                y: 0
            }
        };
    }

    /**
     * Create scale animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createScaleAnimation(element, options = {}) {
        const startScale = options.startScale || 0.8;
        
        return {
            from: { 
                opacity: 0,
                scale: startScale
            },
            to: { 
                opacity: 1,
                scale: 1
            }
        };
    }

    /**
     * Create rotate animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createRotateAnimation(element, options = {}) {
        const rotation = options.rotation || 180;
        
        return {
            from: { 
                opacity: 0,
                rotation: rotation
            },
            to: { 
                opacity: 1,
                rotation: 0
            }
        };
    }

    /**
     * Create morphism animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createMorphismAnimation(element, options = {}) {
        return {
            from: { 
                opacity: 0,
                scale: 0.9,
                filter: 'blur(10px)'
            },
            to: { 
                opacity: 1,
                scale: 1,
                filter: 'blur(0px)'
            }
        };
    }

    /**
     * Create wave animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createWaveAnimation(element, options = {}) {
        return {
            from: { 
                opacity: 0,
                x: -100,
                skewX: 15
            },
            to: { 
                opacity: 1,
                x: 0,
                skewX: 0
            }
        };
    }

    /**
     * Create float animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createFloatAnimation(element, options = {}) {
        if (this.hasGSAP) {
            gsap.to(element, {
                y: '+=15',
                duration: 2 + Math.random(),
                ease: 'power1.inOut',
                yoyo: true,
                repeat: -1,
                delay: Math.random() * 2
            });
        }
        
        return {
            from: { opacity: 0 },
            to: { opacity: 1 }
        };
    }

    /**
     * Create glow animation
     * 
     * @param {Element} element Target element
     * @param {Object} options Animation options
     * @return {Object} Animation properties
     */
    createGlowAnimation(element, options = {}) {
        const color = options.color || 'rgba(0, 183, 255, 0.5)';
        
        return {
            from: { 
                opacity: 0,
                boxShadow: 'none'
            },
            to: { 
                opacity: 1,
                boxShadow: `0 0 20px ${color}`
            }
        };
    }

    /**
     * Update animations based on motion preference
     */
    updateAnimationsForMotionPreference() {
        if (this.reducedMotion) {
            // Pause or speed up all animations
            if (this.hasGSAP) {
                gsap.globalTimeline.timeScale(0.01);
            }
            
            // Add reduced motion class
            document.body.classList.add('reduced-motion');
            
        } else {
            // Restore normal animation speed
            if (this.hasGSAP) {
                gsap.globalTimeline.timeScale(1);
            }
            
            // Remove reduced motion class
            document.body.classList.remove('reduced-motion');
        }
    }

    /**
     * Manually trigger animation
     * 
     * @param {Element|string} target Target element or selector
     * @param {string} animationType Animation type
     * @param {Object} options Animation options
     */
    animate(target, animationType, options = {}) {
        const element = typeof target === 'string' ? document.querySelector(target) : target;
        if (!element) return;
        
        const animation = this.animationTypes[animationType];
        if (!animation) return;
        
        const { from, to } = animation(element, options);
        
        if (this.hasGSAP && !this.reducedMotion) {
            gsap.fromTo(element, from, {
                ...to,
                duration: options.duration || this.config.defaultDuration,
                ease: options.ease || this.config.defaultEase,
                onComplete: options.onComplete
            });
        } else {
            this.triggerCSSAnimation(element, animationType, options);
        }
    }

    /**
     * Get animation state
     * 
     * @return {Object} Animation state
     */
    getState() {
        return {
            isInitialized: this.isInitialized,
            hasGSAP: this.hasGSAP,
            hasScrollTrigger: this.hasScrollTrigger,
            reducedMotion: this.reducedMotion,
            activeAnimations: this.animations.size,
            activeTimelines: this.timelines.size,
            activeObservers: this.observers.size
        };
    }

    /**
     * Cleanup animations
     */
    cleanup() {
        // Kill all GSAP animations
        if (this.hasGSAP) {
            gsap.killTweensOf('*');
            
            // Clear timelines
            this.timelines.forEach(tl => tl.kill());
            this.timelines.clear();
        }
        
        // Disconnect intersection observers
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();
        
        // Clear animations map
        this.animations.clear();
        
        // Remove classes
        document.body.classList.remove('reduced-motion', 'page-loaded', 'page-transitioning');
        
        if (this.config.debug) {
            console.log('🎬 Animations cleaned up');
        }
    }
}

// Export for module loader
export default Animations;
