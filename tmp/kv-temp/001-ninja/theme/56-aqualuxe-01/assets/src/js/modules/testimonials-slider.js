/**
 * Testimonials Slider Module
 * 
 * Handles testimonials slider functionality including carousel and masonry layout
 */

const TestimonialsSlider = {
    /**
     * Initialize the testimonials slider module
     */
    init() {
        this.initSlider();
        this.initMasonry();
    },

    /**
     * Initialize slider for testimonials
     */
    initSlider() {
        const sliders = document.querySelectorAll('.testimonials-slider--slider .testimonials-slider__carousel');
        
        if (!sliders.length || typeof Swiper === 'undefined') {
            return;
        }

        sliders.forEach(slider => {
            // Get slider settings from data attributes
            const autoplay = slider.dataset.autoplay === 'true';
            const autoplaySpeed = parseInt(slider.dataset.autoplaySpeed) || 5000;
            const animation = slider.dataset.animation || 'slide';
            
            // Configure Swiper options based on animation type
            let swiperOptions = {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: '.testimonials-slider__pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.testimonials-slider__button-next',
                    prevEl: '.testimonials-slider__button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                loop: true,
            };
            
            // Add autoplay if enabled
            if (autoplay) {
                swiperOptions.autoplay = {
                    delay: autoplaySpeed,
                    disableOnInteraction: false,
                };
            }
            
            // Configure animation effect
            switch (animation) {
                case 'fade':
                    swiperOptions.effect = 'fade';
                    swiperOptions.fadeEffect = {
                        crossFade: true
                    };
                    break;
                case 'coverflow':
                    swiperOptions.effect = 'coverflow';
                    swiperOptions.coverflowEffect = {
                        rotate: 50,
                        stretch: 0,
                        depth: 100,
                        modifier: 1,
                        slideShadows: true,
                    };
                    break;
                case 'flip':
                    swiperOptions.effect = 'flip';
                    swiperOptions.flipEffect = {
                        slideShadows: true,
                    };
                    break;
                case 'cube':
                    swiperOptions.effect = 'cube';
                    swiperOptions.cubeEffect = {
                        slideShadows: true,
                        shadow: true,
                        shadowOffset: 20,
                        shadowScale: 0.94,
                    };
                    // For cube effect, we need to show only one slide at a time
                    swiperOptions.breakpoints = {};
                    break;
                default:
                    // Default slide effect
                    break;
            }
            
            // Initialize Swiper
            new Swiper(slider, swiperOptions);
        });
    },

    /**
     * Initialize masonry layout for testimonials grid
     */
    initMasonry() {
        const masonryGrids = document.querySelectorAll('.testimonials-slider--masonry .testimonials-slider__container');
        
        if (!masonryGrids.length || typeof Masonry === 'undefined') {
            return;
        }

        masonryGrids.forEach(grid => {
            new Masonry(grid, {
                itemSelector: '.testimonials-slider__item',
                columnWidth: '.testimonials-slider__item',
                percentPosition: true,
                gutter: 30
            });
        });
    },

    /**
     * Refresh masonry layout (useful after content changes)
     */
    refreshMasonry() {
        const masonryGrids = document.querySelectorAll('.testimonials-slider--masonry .testimonials-slider__container');
        
        if (!masonryGrids.length || typeof Masonry === 'undefined') {
            return;
        }

        masonryGrids.forEach(grid => {
            if (grid.masonry) {
                grid.masonry.layout();
            }
        });
    },

    /**
     * Update slider when content changes
     */
    updateSlider() {
        const sliders = document.querySelectorAll('.testimonials-slider--slider .testimonials-slider__carousel');
        
        if (!sliders.length || typeof Swiper === 'undefined') {
            return;
        }

        sliders.forEach(slider => {
            if (slider.swiper) {
                slider.swiper.update();
            }
        });
    }
};

// Initialize the testimonials slider module when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    TestimonialsSlider.init();
    
    // Refresh masonry layout on window resize
    window.addEventListener('resize', () => {
        TestimonialsSlider.refreshMasonry();
    });
    
    // Update slider on window resize
    window.addEventListener('resize', () => {
        TestimonialsSlider.updateSlider();
    });
});

export default TestimonialsSlider;