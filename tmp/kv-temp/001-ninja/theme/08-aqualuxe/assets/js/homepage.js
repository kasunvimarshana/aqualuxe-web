/**
 * AquaLuxe Theme Homepage JavaScript
 * 
 * Contains all the JavaScript functionality specific to the homepage
 */

(function() {
    'use strict';

    /**
     * Initialize all homepage scripts
     */
    function init() {
        setupHeroSlider();
        setupTestimonialsSlider();
        setupFeaturedProductsSlider();
        setupCounters();
        setupParallaxEffects();
        setupAnimations();
    }

    /**
     * Hero Slider functionality
     */
    function setupHeroSlider() {
        const heroSlider = document.querySelector('.hero-slider');
        if (!heroSlider) return;

        const slides = heroSlider.querySelectorAll('.hero-slide');
        const totalSlides = slides.length;
        
        if (totalSlides <= 1) return;
        
        let currentIndex = 0;
        let slideInterval;
        
        // Create navigation dots
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'slider-dots';
        
        for (let i = 0; i < totalSlides; i++) {
            const dot = document.createElement('button');
            dot.className = 'slider-dot';
            dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
            if (i === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => {
                goToSlide(i);
            });
            
            dotsContainer.appendChild(dot);
        }
        
        heroSlider.appendChild(dotsContainer);
        
        // Create prev/next buttons
        const prevButton = document.createElement('button');
        prevButton.className = 'slider-nav slider-prev';
        prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        prevButton.setAttribute('aria-label', 'Previous slide');
        
        const nextButton = document.createElement('button');
        nextButton.className = 'slider-nav slider-next';
        nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        nextButton.setAttribute('aria-label', 'Next slide');
        
        heroSlider.appendChild(prevButton);
        heroSlider.appendChild(nextButton);
        
        prevButton.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
        });
        
        nextButton.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
        });
        
        function goToSlide(index) {
            // Handle circular navigation
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            
            // Update active slide
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
            
            // Update dots
            const dots = dotsContainer.querySelectorAll('.slider-dot');
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
            
            currentIndex = index;
        }
        
        // Initialize first slide
        goToSlide(0);
        
        // Auto-rotate slides
        startSlideInterval();
        
        // Pause on hover
        heroSlider.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        heroSlider.addEventListener('mouseleave', () => {
            startSlideInterval();
        });
        
        function startSlideInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 6000);
        }
        
        // Handle keyboard navigation
        heroSlider.setAttribute('tabindex', '0');
        heroSlider.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                goToSlide(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                goToSlide(currentIndex + 1);
            }
        });
        
        // Handle touch events
        let touchStartX = 0;
        let touchEndX = 0;
        
        heroSlider.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        heroSlider.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left
                goToSlide(currentIndex + 1);
            } else if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right
                goToSlide(currentIndex - 1);
            }
        }
    }

    /**
     * Testimonials Slider functionality
     */
    function setupTestimonialsSlider() {
        const testimonialsSlider = document.querySelector('.testimonials-slider');
        if (!testimonialsSlider) return;

        const testimonialItems = testimonialsSlider.querySelectorAll('.testimonial-item');
        const totalItems = testimonialItems.length;
        
        if (totalItems <= 1) return;
        
        let currentIndex = 0;
        let slideInterval;
        
        // Create navigation dots
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'slider-dots';
        
        for (let i = 0; i < totalItems; i++) {
            const dot = document.createElement('button');
            dot.className = 'slider-dot';
            dot.setAttribute('aria-label', `Go to testimonial ${i + 1}`);
            if (i === 0) dot.classList.add('active');
            
            dot.addEventListener('click', () => {
                goToSlide(i);
            });
            
            dotsContainer.appendChild(dot);
        }
        
        testimonialsSlider.appendChild(dotsContainer);
        
        // Create prev/next buttons
        const prevButton = document.createElement('button');
        prevButton.className = 'slider-nav slider-prev';
        prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
        prevButton.setAttribute('aria-label', 'Previous testimonial');
        
        const nextButton = document.createElement('button');
        nextButton.className = 'slider-nav slider-next';
        nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
        nextButton.setAttribute('aria-label', 'Next testimonial');
        
        testimonialsSlider.appendChild(prevButton);
        testimonialsSlider.appendChild(nextButton);
        
        prevButton.addEventListener('click', () => {
            goToSlide(currentIndex - 1);
        });
        
        nextButton.addEventListener('click', () => {
            goToSlide(currentIndex + 1);
        });
        
        function goToSlide(index) {
            // Handle circular navigation
            if (index < 0) index = totalItems - 1;
            if (index >= totalItems) index = 0;
            
            // Update active slide with fade effect
            testimonialItems.forEach((item, i) => {
                if (i === index) {
                    item.classList.add('active');
                    item.classList.add('fade-in');
                    setTimeout(() => {
                        item.classList.remove('fade-in');
                    }, 500);
                } else {
                    item.classList.remove('active');
                }
            });
            
            // Update dots
            const dots = dotsContainer.querySelectorAll('.slider-dot');
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
            
            currentIndex = index;
        }
        
        // Initialize first slide
        testimonialItems[0].classList.add('active');
        
        // Auto-rotate slides
        startSlideInterval();
        
        // Pause on hover
        testimonialsSlider.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        testimonialsSlider.addEventListener('mouseleave', () => {
            startSlideInterval();
        });
        
        function startSlideInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 5000);
        }
    }

    /**
     * Featured Products Slider functionality
     */
    function setupFeaturedProductsSlider() {
        const productsSlider = document.querySelector('.featured-products .products-grid');
        if (!productsSlider) return;

        // Only convert to slider on mobile
        function checkAndInitSlider() {
            if (window.innerWidth < 768) {
                initializeSlider();
            } else {
                destroySlider();
            }
        }
        
        function initializeSlider() {
            if (productsSlider.classList.contains('slider-initialized')) return;
            
            const products = productsSlider.querySelectorAll('li.product');
            const totalProducts = products.length;
            
            if (totalProducts <= 1) return;
            
            // Add slider classes
            productsSlider.classList.add('slider-initialized');
            productsSlider.classList.add('products-slider');
            
            // Wrap products in slider container
            const sliderWrapper = document.createElement('div');
            sliderWrapper.className = 'slider-wrapper';
            
            // Move products to wrapper
            products.forEach(product => {
                product.classList.add('slider-item');
                sliderWrapper.appendChild(product);
            });
            
            productsSlider.appendChild(sliderWrapper);
            
            // Create navigation
            const navContainer = document.createElement('div');
            navContainer.className = 'slider-navigation';
            
            const prevButton = document.createElement('button');
            prevButton.className = 'slider-nav slider-prev';
            prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>';
            prevButton.setAttribute('aria-label', 'Previous product');
            
            const nextButton = document.createElement('button');
            nextButton.className = 'slider-nav slider-next';
            nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
            nextButton.setAttribute('aria-label', 'Next product');
            
            navContainer.appendChild(prevButton);
            navContainer.appendChild(nextButton);
            
            productsSlider.appendChild(navContainer);
            
            // Add scroll functionality
            let scrollAmount = 0;
            const productWidth = products[0].offsetWidth;
            
            prevButton.addEventListener('click', () => {
                scrollAmount -= productWidth;
                if (scrollAmount < 0) scrollAmount = 0;
                sliderWrapper.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
            
            nextButton.addEventListener('click', () => {
                scrollAmount += productWidth;
                const maxScroll = sliderWrapper.scrollWidth - sliderWrapper.clientWidth;
                if (scrollAmount > maxScroll) scrollAmount = maxScroll;
                sliderWrapper.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });
        }
        
        function destroySlider() {
            if (!productsSlider.classList.contains('slider-initialized')) return;
            
            // Remove slider classes
            productsSlider.classList.remove('slider-initialized');
            productsSlider.classList.remove('products-slider');
            
            // Get the wrapper and products
            const sliderWrapper = productsSlider.querySelector('.slider-wrapper');
            const products = sliderWrapper.querySelectorAll('.slider-item');
            
            // Move products back to main container
            products.forEach(product => {
                product.classList.remove('slider-item');
                productsSlider.appendChild(product);
            });
            
            // Remove wrapper and navigation
            productsSlider.removeChild(sliderWrapper);
            
            const navContainer = productsSlider.querySelector('.slider-navigation');
            if (navContainer) {
                productsSlider.removeChild(navContainer);
            }
        }
        
        // Initialize based on current window size
        checkAndInitSlider();
        
        // Update on window resize
        window.addEventListener('resize', checkAndInitSlider);
    }

    /**
     * Counters Animation functionality
     */
    function setupCounters() {
        const counters = document.querySelectorAll('.counter-value');
        if (!counters.length) return;

        // Use Intersection Observer to trigger counter animation when in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-count'));
                    const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
                    
                    let start = 0;
                    const startTime = performance.now();
                    
                    function updateCounter(currentTime) {
                        const elapsedTime = currentTime - startTime;
                        const progress = Math.min(elapsedTime / duration, 1);
                        
                        const currentCount = Math.floor(progress * target);
                        counter.textContent = currentCount.toLocaleString();
                        
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            counter.textContent = target.toLocaleString();
                        }
                    }
                    
                    requestAnimationFrame(updateCounter);
                    
                    // Only trigger once
                    observer.unobserve(counter);
                }
            });
        }, {
            threshold: 0.1
        });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }

    /**
     * Parallax Effects functionality
     */
    function setupParallaxEffects() {
        const parallaxElements = document.querySelectorAll('.parallax');
        if (!parallaxElements.length) return;

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset;
            
            parallaxElements.forEach(element => {
                const speed = parseFloat(element.getAttribute('data-parallax-speed')) || 0.5;
                const offset = scrollTop * speed;
                
                element.style.transform = `translateY(${offset}px)`;
            });
        });
    }

    /**
     * Animations functionality
     */
    function setupAnimations() {
        const animatedElements = document.querySelectorAll('.animate-on-scroll');
        if (!animatedElements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });
        
        animatedElements.forEach(element => {
            // Add initial animation class based on data attribute
            const animationType = element.getAttribute('data-animation') || 'fade-in';
            element.classList.add(animationType);
            
            // Add delay if specified
            const delay = element.getAttribute('data-delay');
            if (delay) {
                element.style.animationDelay = `${delay}ms`;
            }
            
            observer.observe(element);
        });
    }

    // Initialize when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();