/**
 * AquaLuxe WordPress Theme
 * Carousel Component
 */

/**
 * Carousel component for the AquaLuxe theme.
 */
(function() {
  /**
   * Carousel class
   */
  class Carousel {
    /**
     * Constructor
     * @param {HTMLElement|string} element - Carousel element or selector
     * @param {Object} options - Carousel options
     */
    constructor(element, options = {}) {
      // Get element
      this.carousel = typeof element === 'string' ? document.querySelector(element) : element;
      
      if (!this.carousel) {
        console.error('Carousel element not found');
        return;
      }
      
      // Default options
      const defaultOptions = {
        autoplay: true,
        autoplaySpeed: 5000,
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        pauseOnHover: true,
        swipe: true,
        responsive: []
      };
      
      // Merge options
      this.options = { ...defaultOptions, ...options };
      
      // Get elements
      this.track = this.carousel.querySelector('.carousel-track');
      this.slides = Array.from(this.carousel.querySelectorAll('.carousel-slide'));
      
      if (!this.track || !this.slides.length) {
        console.error('Carousel track or slides not found');
        return;
      }
      
      // Set state
      this.currentSlide = 0;
      this.slideCount = this.slides.length;
      this.slideWidth = 100 / this.options.slidesToShow;
      this.autoplayInterval = null;
      this.isAnimating = false;
      
      // Initialize
      this.init();
    }
    
    /**
     * Initialize carousel
     */
    init() {
      // Set up slides
      this.setupSlides();
      
      // Create navigation
      if (this.options.arrows) {
        this.createArrows();
      }
      
      if (this.options.dots) {
        this.createDots();
      }
      
      // Set up events
      this.setupEvents();
      
      // Start autoplay
      if (this.options.autoplay) {
        this.startAutoplay();
      }
      
      // Set initial slide
      this.goToSlide(0);
      
      // Add initialized class
      this.carousel.classList.add('carousel-initialized');
    }
    
    /**
     * Set up slides
     */
    setupSlides() {
      // Set track width
      this.track.style.width = `${this.slideCount * 100}%`;
      
      // Set slide width
      this.slides.forEach(slide => {
        slide.style.width = `${this.slideWidth}%`;
      });
      
      // Clone slides for infinite loop
      if (this.options.infinite) {
        const slidesToClone = Math.min(this.options.slidesToShow, this.slideCount);
        
        // Clone first slides
        for (let i = 0; i < slidesToClone; i++) {
          const clone = this.slides[i].cloneNode(true);
          clone.classList.add('carousel-slide-cloned');
          this.track.appendChild(clone);
        }
        
        // Clone last slides
        for (let i = this.slideCount - 1; i >= this.slideCount - slidesToClone; i--) {
          const clone = this.slides[i].cloneNode(true);
          clone.classList.add('carousel-slide-cloned');
          this.track.insertBefore(clone, this.track.firstChild);
        }
        
        // Update slides
        this.slides = Array.from(this.carousel.querySelectorAll('.carousel-slide'));
        
        // Set initial position
        this.currentSlide = slidesToClone;
      }
    }
    
    /**
     * Create arrow navigation
     */
    createArrows() {
      // Create previous button
      this.prevButton = document.createElement('button');
      this.prevButton.className = 'carousel-prev';
      this.prevButton.setAttribute('aria-label', 'Previous slide');
      this.prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>';
      
      // Create next button
      this.nextButton = document.createElement('button');
      this.nextButton.className = 'carousel-next';
      this.nextButton.setAttribute('aria-label', 'Next slide');
      this.nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>';
      
      // Add buttons to carousel
      const arrowsContainer = document.createElement('div');
      arrowsContainer.className = 'carousel-arrows';
      arrowsContainer.appendChild(this.prevButton);
      arrowsContainer.appendChild(this.nextButton);
      this.carousel.appendChild(arrowsContainer);
      
      // Add event listeners
      this.prevButton.addEventListener('click', () => this.prevSlide());
      this.nextButton.addEventListener('click', () => this.nextSlide());
    }
    
    /**
     * Create dot navigation
     */
    createDots() {
      // Create dots container
      this.dotsContainer = document.createElement('div');
      this.dotsContainer.className = 'carousel-dots';
      
      // Create dots
      for (let i = 0; i < this.slideCount; i++) {
        const dot = document.createElement('button');
        dot.className = 'carousel-dot';
        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
        
        // Add event listener
        dot.addEventListener('click', () => this.goToSlide(i));
        
        // Add dot to container
        this.dotsContainer.appendChild(dot);
      }
      
      // Add dots container to carousel
      this.carousel.appendChild(this.dotsContainer);
      
      // Store dots
      this.dots = Array.from(this.dotsContainer.querySelectorAll('.carousel-dot'));
    }
    
    /**
     * Set up events
     */
    setupEvents() {
      // Pause autoplay on hover
      if (this.options.autoplay && this.options.pauseOnHover) {
        this.carousel.addEventListener('mouseenter', () => this.stopAutoplay());
        this.carousel.addEventListener('mouseleave', () => this.startAutoplay());
      }
      
      // Touch events
      if (this.options.swipe) {
        let touchStartX = 0;
        let touchEndX = 0;
        
        this.carousel.addEventListener('touchstart', e => {
          touchStartX = e.touches[0].clientX;
        }, { passive: true });
        
        this.carousel.addEventListener('touchmove', e => {
          touchEndX = e.touches[0].clientX;
        }, { passive: true });
        
        this.carousel.addEventListener('touchend', () => {
          const diff = touchStartX - touchEndX;
          
          if (diff > 50) {
            this.nextSlide();
          } else if (diff < -50) {
            this.prevSlide();
          }
        });
      }
      
      // Keyboard navigation
      this.carousel.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft' || e.keyCode === 37) {
          this.prevSlide();
        } else if (e.key === 'ArrowRight' || e.keyCode === 39) {
          this.nextSlide();
        }
      });
      
      // Resize event
      window.addEventListener('resize', AquaLuxe.utils.debounce(() => {
        this.updateCarousel();
      }, 250));
      
      // Visibility change
      document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
          this.stopAutoplay();
        } else if (this.options.autoplay) {
          this.startAutoplay();
        }
      });
    }
    
    /**
     * Start autoplay
     */
    startAutoplay() {
      if (this.options.autoplay && !this.autoplayInterval) {
        this.autoplayInterval = setInterval(() => {
          this.nextSlide();
        }, this.options.autoplaySpeed);
      }
    }
    
    /**
     * Stop autoplay
     */
    stopAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }
    }
    
    /**
     * Go to previous slide
     */
    prevSlide() {
      if (this.isAnimating) return;
      
      const prevSlide = this.currentSlide - this.options.slidesToScroll;
      
      if (prevSlide < 0 && !this.options.infinite) {
        this.goToSlide(0);
      } else {
        this.goToSlide(prevSlide);
      }
    }
    
    /**
     * Go to next slide
     */
    nextSlide() {
      if (this.isAnimating) return;
      
      const nextSlide = this.currentSlide + this.options.slidesToScroll;
      
      if (nextSlide >= this.slideCount && !this.options.infinite) {
        this.goToSlide(this.slideCount - 1);
      } else {
        this.goToSlide(nextSlide);
      }
    }
    
    /**
     * Go to specific slide
     * @param {number} slideIndex - Slide index
     */
    goToSlide(slideIndex) {
      if (this.isAnimating) return;
      
      this.isAnimating = true;
      
      // Handle infinite loop
      if (this.options.infinite) {
        if (slideIndex < 0) {
          slideIndex = this.slideCount - 1;
        } else if (slideIndex >= this.slideCount) {
          slideIndex = 0;
        }
      } else {
        // Clamp slide index
        slideIndex = Math.max(0, Math.min(slideIndex, this.slideCount - 1));
      }
      
      // Update current slide
      this.currentSlide = slideIndex;
      
      // Calculate position
      const position = -this.currentSlide * this.slideWidth;
      
      // Animate slide
      this.track.style.transition = 'transform 300ms ease';
      this.track.style.transform = `translateX(${position}%)`;
      
      // Update dots
      if (this.options.dots) {
        this.dots.forEach((dot, index) => {
          dot.classList.toggle('active', index === this.currentSlide % this.slideCount);
        });
      }
      
      // Reset animation flag after transition
      setTimeout(() => {
        this.isAnimating = false;
        
        // Handle infinite loop reset
        if (this.options.infinite) {
          if (this.currentSlide >= this.slideCount) {
            this.currentSlide = 0;
            this.track.style.transition = 'none';
            this.track.style.transform = `translateX(0)`;
          } else if (this.currentSlide < 0) {
            this.currentSlide = this.slideCount - 1;
            this.track.style.transition = 'none';
            this.track.style.transform = `translateX(${-this.currentSlide * this.slideWidth}%)`;
          }
        }
      }, 300);
      
      // Trigger slide change event
      this.carousel.dispatchEvent(new CustomEvent('slideChange', {
        detail: {
          currentSlide: this.currentSlide % this.slideCount
        }
      }));
    }
    
    /**
     * Update carousel on resize
     */
    updateCarousel() {
      // Update slide width
      this.slideWidth = 100 / this.options.slidesToShow;
      
      // Update slides
      this.slides.forEach(slide => {
        slide.style.width = `${this.slideWidth}%`;
      });
      
      // Update position
      const position = -this.currentSlide * this.slideWidth;
      this.track.style.transform = `translateX(${position}%)`;
    }
    
    /**
     * Destroy carousel
     */
    destroy() {
      // Stop autoplay
      this.stopAutoplay();
      
      // Remove arrows
      if (this.prevButton && this.nextButton) {
        this.prevButton.parentNode.remove();
      }
      
      // Remove dots
      if (this.dotsContainer) {
        this.dotsContainer.remove();
      }
      
      // Reset track
      this.track.style.width = '';
      this.track.style.transform = '';
      
      // Reset slides
      this.slides.forEach(slide => {
        slide.style.width = '';
      });
      
      // Remove cloned slides
      const clonedSlides = this.carousel.querySelectorAll('.carousel-slide-cloned');
      clonedSlides.forEach(slide => slide.remove());
      
      // Remove initialized class
      this.carousel.classList.remove('carousel-initialized');
    }
  }
  
  // Add Carousel to AquaLuxe object
  window.AquaLuxe = window.AquaLuxe || {};
  window.AquaLuxe.Carousel = Carousel;
  
  // Initialize carousels
  document.addEventListener('DOMContentLoaded', () => {
    const carousels = document.querySelectorAll('.carousel');
    
    carousels.forEach(carousel => {
      // Get options from data attributes
      const options = {};
      
      if (carousel.dataset.autoplay === 'false') {
        options.autoplay = false;
      }
      
      if (carousel.dataset.autoplaySpeed) {
        options.autoplaySpeed = parseInt(carousel.dataset.autoplaySpeed);
      }
      
      if (carousel.dataset.infinite === 'false') {
        options.infinite = false;
      }
      
      if (carousel.dataset.slidesToShow) {
        options.slidesToShow = parseInt(carousel.dataset.slidesToShow);
      }
      
      if (carousel.dataset.slidesToScroll) {
        options.slidesToScroll = parseInt(carousel.dataset.slidesToScroll);
      }
      
      if (carousel.dataset.arrows === 'false') {
        options.arrows = false;
      }
      
      if (carousel.dataset.dots === 'false') {
        options.dots = false;
      }
      
      if (carousel.dataset.pauseOnHover === 'false') {
        options.pauseOnHover = false;
      }
      
      if (carousel.dataset.swipe === 'false') {
        options.swipe = false;
      }
      
      // Initialize carousel
      new Carousel(carousel, options);
    });
  });
})();