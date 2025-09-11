/**
 * Slider/Carousel JavaScript
 * 
 * Handles sliders and carousels throughout the theme
 */

(function($) {
    'use strict';

    const Slider = {
        
        init: function() {
            this.initHeroSliders();
            this.initProductCarousels();
            this.initTestimonialSliders();
            this.initImageGalleries();
            this.initCategorySliders();
        },

        initHeroSliders: function() {
            $('.hero-slider').each(function() {
                const slider = $(this);
                const slides = slider.find('.slide');
                const prevBtn = slider.find('.slider-prev');
                const nextBtn = slider.find('.slider-next');
                const indicators = slider.find('.slider-indicators .indicator');
                
                let currentSlide = 0;
                const totalSlides = slides.length;
                
                if (totalSlides <= 1) return;
                
                // Auto-play settings
                const autoplay = slider.data('autoplay') !== false;
                const autoplayDelay = slider.data('autoplay-delay') || 5000;
                let autoplayTimer;
                
                // Initialize
                showSlide(currentSlide);
                
                // Auto-play
                if (autoplay) {
                    startAutoplay();
                }
                
                // Navigation
                nextBtn.on('click', function(e) {
                    e.preventDefault();
                    nextSlide();
                });
                
                prevBtn.on('click', function(e) {
                    e.preventDefault();
                    prevSlide();
                });
                
                // Indicators
                indicators.on('click', function(e) {
                    e.preventDefault();
                    const index = $(this).index();
                    goToSlide(index);
                });
                
                // Keyboard navigation
                slider.on('keydown', function(e) {
                    switch (e.key) {
                        case 'ArrowLeft':
                            prevSlide();
                            break;
                        case 'ArrowRight':
                            nextSlide();
                            break;
                    }
                });
                
                // Pause on hover
                slider.on('mouseenter', stopAutoplay);
                slider.on('mouseleave', function() {
                    if (autoplay) startAutoplay();
                });
                
                // Touch/swipe support
                let startX, startY, currentX, currentY;
                
                slider.on('touchstart', function(e) {
                    startX = e.originalEvent.touches[0].clientX;
                    startY = e.originalEvent.touches[0].clientY;
                });
                
                slider.on('touchmove', function(e) {
                    if (!startX || !startY) return;
                    
                    currentX = e.originalEvent.touches[0].clientX;
                    currentY = e.originalEvent.touches[0].clientY;
                    
                    const diffX = startX - currentX;
                    const diffY = startY - currentY;
                    
                    if (Math.abs(diffX) > Math.abs(diffY)) {
                        e.preventDefault(); // Prevent scrolling
                    }
                });
                
                slider.on('touchend', function() {
                    if (!startX || !currentX) return;
                    
                    const diffX = startX - currentX;
                    const threshold = 50;
                    
                    if (Math.abs(diffX) > threshold) {
                        if (diffX > 0) {
                            nextSlide();
                        } else {
                            prevSlide();
                        }
                    }
                    
                    startX = startY = currentX = currentY = null;
                });
                
                function showSlide(index) {
                    slides.removeClass('active');
                    indicators.removeClass('active');
                    
                    $(slides[index]).addClass('active');
                    $(indicators[index]).addClass('active');
                    
                    // Update ARIA labels
                    slides.attr('aria-hidden', 'true');
                    $(slides[index]).attr('aria-hidden', 'false');
                }
                
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                    resetAutoplay();
                }
                
                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    showSlide(currentSlide);
                    resetAutoplay();
                }
                
                function goToSlide(index) {
                    currentSlide = index;
                    showSlide(currentSlide);
                    resetAutoplay();
                }
                
                function startAutoplay() {
                    if (!autoplay) return;
                    autoplayTimer = setInterval(nextSlide, autoplayDelay);
                }
                
                function stopAutoplay() {
                    if (autoplayTimer) {
                        clearInterval(autoplayTimer);
                        autoplayTimer = null;
                    }
                }
                
                function resetAutoplay() {
                    stopAutoplay();
                    if (autoplay) startAutoplay();
                }
            });
        },

        initProductCarousels: function() {
            $('.product-carousel').each(function() {
                const carousel = $(this);
                const container = carousel.find('.carousel-container');
                const items = carousel.find('.product-item');
                const prevBtn = carousel.find('.carousel-prev');
                const nextBtn = carousel.find('.carousel-next');
                
                const itemsToShow = carousel.data('items-to-show') || 4;
                const itemsToScroll = carousel.data('items-to-scroll') || 1;
                const responsive = carousel.data('responsive') !== false;
                
                let currentIndex = 0;
                const totalItems = items.length;
                
                if (totalItems <= itemsToShow) {
                    prevBtn.hide();
                    nextBtn.hide();
                    return;
                }
                
                // Calculate responsive items
                function getItemsToShow() {
                    if (!responsive) return itemsToShow;
                    
                    const width = carousel.width();
                    if (width < 640) return 1;
                    if (width < 768) return 2;
                    if (width < 1024) return 3;
                    return itemsToShow;
                }
                
                function updateCarousel() {
                    const visibleItems = getItemsToShow();
                    const itemWidth = 100 / visibleItems;
                    const translateX = -(currentIndex * itemWidth);
                    
                    items.css('flex', `0 0 ${itemWidth}%`);
                    container.css('transform', `translateX(${translateX}%)`);
                    
                    // Update button states
                    prevBtn.toggleClass('disabled', currentIndex === 0);
                    nextBtn.toggleClass('disabled', currentIndex >= totalItems - visibleItems);
                }
                
                // Navigation
                nextBtn.on('click', function(e) {
                    e.preventDefault();
                    const visibleItems = getItemsToShow();
                    const maxIndex = totalItems - visibleItems;
                    
                    if (currentIndex < maxIndex) {
                        currentIndex = Math.min(currentIndex + itemsToScroll, maxIndex);
                        updateCarousel();
                    }
                });
                
                prevBtn.on('click', function(e) {
                    e.preventDefault();
                    
                    if (currentIndex > 0) {
                        currentIndex = Math.max(currentIndex - itemsToScroll, 0);
                        updateCarousel();
                    }
                });
                
                // Responsive updates
                $(window).on('resize', function() {
                    updateCarousel();
                });
                
                // Initialize
                updateCarousel();
            });
        },

        initTestimonialSliders: function() {
            $('.testimonial-slider').each(function() {
                const slider = $(this);
                
                // Use Swiper if available
                if (typeof Swiper !== 'undefined') {
                    new Swiper(slider[0], {
                        slidesPerView: 1,
                        spaceBetween: 30,
                        loop: true,
                        autoplay: {
                            delay: 6000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                            },
                            1024: {
                                slidesPerView: 3,
                            }
                        }
                    });
                } else {
                    // Fallback implementation
                    this.initSimpleSlider(slider);
                }
            });
        },

        initImageGalleries: function() {
            $('.image-gallery-slider').each(function() {
                const gallery = $(this);
                const mainImage = gallery.find('.main-image img');
                const thumbnails = gallery.find('.thumbnails img');
                
                thumbnails.on('click', function() {
                    const newSrc = $(this).data('full-src') || $(this).attr('src');
                    const alt = $(this).attr('alt');
                    
                    mainImage.attr('src', newSrc).attr('alt', alt);
                    
                    thumbnails.removeClass('active');
                    $(this).addClass('active');
                });
                
                // Keyboard navigation
                thumbnails.on('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        $(this).click();
                    }
                });
                
                // Lightbox functionality
                if (gallery.hasClass('lightbox-enabled')) {
                    mainImage.on('click', function() {
                        Slider.openLightbox($(this).attr('src'), thumbnails);
                    });
                }
            });
        },

        initCategorySliders: function() {
            $('.category-slider').each(function() {
                const slider = $(this);
                const items = slider.find('.category-item');
                const itemsToShow = slider.data('items-to-show') || 6;
                
                if (items.length <= itemsToShow) return;
                
                // Use horizontal scrolling
                slider.addClass('horizontal-scroll');
                
                const scrollAmount = 200;
                
                slider.find('.scroll-left').on('click', function(e) {
                    e.preventDefault();
                    slider.find('.category-list')[0].scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });
                
                slider.find('.scroll-right').on('click', function(e) {
                    e.preventDefault();
                    slider.find('.category-list')[0].scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });
            });
        },

        initSimpleSlider: function(slider) {
            const slides = slider.find('.slide');
            const prevBtn = slider.find('.slider-prev');
            const nextBtn = slider.find('.slider-next');
            
            let currentSlide = 0;
            const totalSlides = slides.length;
            
            function showSlide(index) {
                slides.removeClass('active').eq(index).addClass('active');
            }
            
            nextBtn.on('click', function(e) {
                e.preventDefault();
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            });
            
            prevBtn.on('click', function(e) {
                e.preventDefault();
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            });
            
            // Auto-play
            setInterval(function() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }, 5000);
        },

        openLightbox: function(imageSrc, allImages) {
            const lightbox = $(`
                <div class="lightbox fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center">
                    <div class="lightbox-content relative max-w-4xl max-h-full p-4">
                        <img src="${imageSrc}" alt="" class="max-w-full max-h-full object-contain">
                        <button class="lightbox-close absolute top-4 right-4 text-white text-2xl">×</button>
                        <button class="lightbox-prev absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-2xl">‹</button>
                        <button class="lightbox-next absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-2xl">›</button>
                    </div>
                </div>
            `);
            
            $('body').append(lightbox).addClass('lightbox-open');
            
            // Close events
            lightbox.find('.lightbox-close').on('click', function() {
                lightbox.remove();
                $('body').removeClass('lightbox-open');
            });
            
            lightbox.on('click', function(e) {
                if (e.target === this) {
                    lightbox.remove();
                    $('body').removeClass('lightbox-open');
                }
            });
            
            // Keyboard events
            $(document).on('keydown.lightbox', function(e) {
                switch (e.key) {
                    case 'Escape':
                        lightbox.remove();
                        $('body').removeClass('lightbox-open');
                        $(document).off('keydown.lightbox');
                        break;
                    case 'ArrowLeft':
                        // Previous image logic
                        break;
                    case 'ArrowRight':
                        // Next image logic
                        break;
                }
            });
        }
    };

    // Initialize when ready
    $(document).ready(function() {
        Slider.init();
    });

    // Re-initialize on AJAX content load
    $(document).on('ajaxComplete', function() {
        Slider.init();
    });

    // Make available globally
    window.AquaLuxeSlider = Slider;

})(jQuery);