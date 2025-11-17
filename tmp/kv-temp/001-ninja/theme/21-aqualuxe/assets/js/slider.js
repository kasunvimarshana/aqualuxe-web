/**
 * AquaLuxe Theme Slider
 * 
 * This file handles the slider/carousel functionality using Swiper.js.
 */

// Import Swiper from node_modules
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, Thumbs } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/thumbs';

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    initSliders();
});

/**
 * Initialize all sliders
 */
function initSliders() {
    // Initialize hero slider
    initHeroSlider();
    
    // Initialize featured products slider
    initFeaturedProductsSlider();
    
    // Initialize testimonials slider
    initTestimonialsSlider();
    
    // Initialize product gallery slider
    initProductGallerySlider();
    
    // Initialize related products slider
    initRelatedProductsSlider();
    
    // Initialize category products slider
    initCategoryProductsSlider();
}

/**
 * Initialize hero slider
 */
function initHeroSlider() {
    const heroSlider = document.querySelector('.hero-slider');
    if (!heroSlider) return;
    
    new Swiper(heroSlider, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        speed: 800,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.hero-slider-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.hero-slider-next',
            prevEl: '.hero-slider-prev',
        },
        on: {
            init: function() {
                heroSlider.classList.add('slider-initialized');
            },
        },
    });
}

/**
 * Initialize featured products slider
 */
function initFeaturedProductsSlider() {
    const featuredSlider = document.querySelector('.featured-products-slider');
    if (!featuredSlider) return;
    
    new Swiper(featuredSlider, {
        modules: [Navigation, Pagination],
        slidesPerView: 1,
        spaceBetween: 20,
        loop: false,
        speed: 600,
        pagination: {
            el: '.featured-products-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.featured-products-next',
            prevEl: '.featured-products-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        },
        on: {
            init: function() {
                featuredSlider.classList.add('slider-initialized');
            },
        },
    });
}

/**
 * Initialize testimonials slider
 */
function initTestimonialsSlider() {
    const testimonialsSlider = document.querySelector('.testimonials-slider');
    if (!testimonialsSlider) return;
    
    new Swiper(testimonialsSlider, {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        speed: 600,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.testimonials-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.testimonials-next',
            prevEl: '.testimonials-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
        on: {
            init: function() {
                testimonialsSlider.classList.add('slider-initialized');
            },
        },
    });
}

/**
 * Initialize product gallery slider
 */
function initProductGallerySlider() {
    const galleryThumbs = document.querySelector('.product-gallery-thumbs');
    const galleryMain = document.querySelector('.product-gallery-main');
    
    if (!galleryThumbs || !galleryMain) return;
    
    // Initialize thumbnail slider
    const thumbsSwiper = new Swiper(galleryThumbs, {
        modules: [Navigation],
        slidesPerView: 4,
        spaceBetween: 10,
        watchSlidesProgress: true,
        navigation: {
            nextEl: '.gallery-thumbs-next',
            prevEl: '.gallery-thumbs-prev',
        },
        breakpoints: {
            480: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 5,
            },
        },
    });
    
    // Initialize main slider
    new Swiper(galleryMain, {
        modules: [Navigation, Thumbs],
        slidesPerView: 1,
        spaceBetween: 0,
        thumbs: {
            swiper: thumbsSwiper,
        },
        navigation: {
            nextEl: '.gallery-main-next',
            prevEl: '.gallery-main-prev',
        },
        on: {
            init: function() {
                galleryMain.classList.add('slider-initialized');
            },
        },
    });
}

/**
 * Initialize related products slider
 */
function initRelatedProductsSlider() {
    const relatedSlider = document.querySelector('.related-products-slider');
    if (!relatedSlider) return;
    
    new Swiper(relatedSlider, {
        modules: [Navigation, Pagination],
        slidesPerView: 1,
        spaceBetween: 20,
        loop: false,
        speed: 600,
        pagination: {
            el: '.related-products-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.related-products-next',
            prevEl: '.related-products-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        },
        on: {
            init: function() {
                relatedSlider.classList.add('slider-initialized');
            },
        },
    });
}

/**
 * Initialize category products slider
 */
function initCategoryProductsSlider() {
    const categorySliders = document.querySelectorAll('.category-products-slider');
    if (categorySliders.length === 0) return;
    
    categorySliders.forEach((slider, index) => {
        new Swiper(slider, {
            modules: [Navigation, Pagination],
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            speed: 600,
            pagination: {
                el: `.category-products-pagination-${index}`,
                clickable: true,
            },
            navigation: {
                nextEl: `.category-products-next-${index}`,
                prevEl: `.category-products-prev-${index}`,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
            on: {
                init: function() {
                    slider.classList.add('slider-initialized');
                },
            },
        });
    });
}

// Listen for DOM changes to initialize sliders on dynamically added content
const observer = new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        if (mutation.addedNodes.length) {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1 && node.classList) {
                    // Check for specific slider classes
                    if (node.classList.contains('hero-slider') || 
                        node.classList.contains('featured-products-slider') ||
                        node.classList.contains('testimonials-slider') ||
                        node.classList.contains('product-gallery-main') ||
                        node.classList.contains('related-products-slider') ||
                        node.classList.contains('category-products-slider')) {
                        initSliders();
                    }
                    
                    // Check for quick view modal
                    if (node.classList.contains('quick-view-modal')) {
                        const galleryMain = node.querySelector('.product-gallery-main');
                        const galleryThumbs = node.querySelector('.product-gallery-thumbs');
                        
                        if (galleryMain && galleryThumbs) {
                            initProductGallerySlider();
                        }
                    }
                }
            });
        }
    });
});

// Start observing the document with the configured parameters
observer.observe(document.body, { childList: true, subtree: true });

// Export functions for use in other files
export {
    initSliders,
    initHeroSlider,
    initFeaturedProductsSlider,
    initTestimonialsSlider,
    initProductGallerySlider,
    initRelatedProductsSlider,
    initCategoryProductsSlider
};