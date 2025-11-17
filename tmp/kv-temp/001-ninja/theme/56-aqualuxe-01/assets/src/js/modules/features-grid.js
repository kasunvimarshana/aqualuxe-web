/**
 * Features Grid Module
 * 
 * Handles features grid functionality including animations and carousel
 */

const FeaturesGrid = {
    /**
     * Initialize the features grid module
     */
    init() {
        this.initCarousel();
        this.initAnimations();
        this.initMasonry();
    },

    /**
     * Initialize carousel for features grid
     */
    initCarousel() {
        const carousels = document.querySelectorAll('.features-grid--carousel .features-grid__carousel');
        
        if (!carousels.length || typeof Swiper === 'undefined') {
            return;
        }

        carousels.forEach(carousel => {
            const columnsClass = carousel.closest('.features-grid').className.match(/features-grid--cols-(\d+)/);
            const columns = columnsClass ? parseInt(columnsClass[1]) : 3;
            
            new Swiper(carousel, {
                slidesPerView: 1,
                spaceBetween: 30,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: Math.min(2, columns),
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: Math.min(2, columns),
                        spaceBetween: 30,
                    },
                    1024: {
                        slidesPerView: Math.min(3, columns),
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: columns,
                        spaceBetween: 30,
                    },
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                loop: true,
            });
        });
    },

    /**
     * Initialize animations for features grid
     */
    initAnimations() {
        const featuresGrids = document.querySelectorAll('.features-grid');
        
        if (!featuresGrids.length) {
            return;
        }

        // Setup Intersection Observer to trigger animations when grid is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        featuresGrids.forEach(grid => {
            if (!grid.classList.contains('features-grid--animate-none')) {
                observer.observe(grid);
            }
        });
    },

    /**
     * Initialize masonry layout for features grid
     */
    initMasonry() {
        const masonryGrids = document.querySelectorAll('.features-grid--masonry');
        
        if (!masonryGrids.length || typeof Masonry === 'undefined') {
            return;
        }

        masonryGrids.forEach(grid => {
            const container = grid.querySelector('.features-grid__container');
            
            if (container) {
                new Masonry(container, {
                    itemSelector: '.features-grid__item',
                    columnWidth: '.features-grid__item',
                    percentPosition: true,
                    gutter: 30
                });
            }
        });
    },

    /**
     * Refresh masonry layout (useful after content changes)
     */
    refreshMasonry() {
        const masonryGrids = document.querySelectorAll('.features-grid--masonry');
        
        if (!masonryGrids.length || typeof Masonry === 'undefined') {
            return;
        }

        masonryGrids.forEach(grid => {
            const container = grid.querySelector('.features-grid__container');
            
            if (container && container.masonry) {
                container.masonry.layout();
            }
        });
    }
};

// Initialize the features grid module when the DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    FeaturesGrid.init();
    
    // Refresh masonry layout on window resize
    window.addEventListener('resize', () => {
        FeaturesGrid.refreshMasonry();
    });
});

export default FeaturesGrid;