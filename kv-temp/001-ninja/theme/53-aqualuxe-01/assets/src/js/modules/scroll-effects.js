/**
 * Scroll Effects Module
 * 
 * Handles various scroll-based effects including:
 * - Sticky header
 * - Scroll to top button
 * - Reveal animations
 * - Parallax effects
 */

class ScrollEffects {
    constructor() {
        this.header = document.querySelector('.site-header');
        this.scrollToTopBtn = document.querySelector('.scroll-to-top');
        this.revealElements = document.querySelectorAll('.reveal-on-scroll');
        this.parallaxElements = document.querySelectorAll('.parallax');
        this.lastScrollTop = 0;
        this.scrollThreshold = 100;
        this.ticking = false;
    }

    init() {
        if (!this.header && !this.scrollToTopBtn && !this.revealElements.length && !this.parallaxElements.length) {
            return;
        }

        this.setupScrollListener();
        this.setupScrollToTop();
        this.setupIntersectionObserver();
        
        // Initial check for elements in viewport
        this.handleScroll();
    }

    setupScrollListener() {
        window.addEventListener('scroll', () => {
            if (!this.ticking) {
                window.requestAnimationFrame(() => {
                    this.handleScroll();
                    this.ticking = false;
                });
                this.ticking = true;
            }
        });
    }

    handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Handle sticky header
        if (this.header) {
            if (scrollTop > this.scrollThreshold) {
                this.header.classList.add('sticky');
                
                // Add hide class when scrolling down
                if (scrollTop > this.lastScrollTop && scrollTop > this.header.offsetHeight) {
                    this.header.classList.add('header-hidden');
                } else {
                    this.header.classList.remove('header-hidden');
                }
            } else {
                this.header.classList.remove('sticky');
            }
        }
        
        // Handle scroll to top button
        if (this.scrollToTopBtn) {
            if (scrollTop > this.scrollThreshold * 2) {
                this.scrollToTopBtn.classList.add('visible');
            } else {
                this.scrollToTopBtn.classList.remove('visible');
            }
        }
        
        // Handle parallax elements
        if (this.parallaxElements.length) {
            this.parallaxElements.forEach(element => {
                const speed = element.dataset.parallaxSpeed || 0.5;
                const yPos = -(scrollTop * speed);
                element.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
        }
        
        this.lastScrollTop = scrollTop;
    }

    setupScrollToTop() {
        if (!this.scrollToTopBtn) {
            return;
        }
        
        this.scrollToTopBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    setupIntersectionObserver() {
        if (!this.revealElements.length) {
            return;
        }
        
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    
                    // Stop observing after revealing
                    if (!entry.target.dataset.alwaysObserve) {
                        observer.unobserve(entry.target);
                    }
                } else if (entry.target.dataset.alwaysObserve) {
                    entry.target.classList.remove('revealed');
                }
            });
        }, options);
        
        this.revealElements.forEach(element => {
            observer.observe(element);
        });
    }
}

export default ScrollEffects;