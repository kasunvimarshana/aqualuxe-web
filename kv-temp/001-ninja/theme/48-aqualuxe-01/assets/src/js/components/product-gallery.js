/**
 * Product Gallery Functionality
 * 
 * Handles the product gallery functionality for the AquaLuxe theme.
 */

(function($) {
    'use strict';

    const ProductGallery = {
        /**
         * Initialize the product gallery functionality
         */
        init: function() {
            this.cacheDom();
            this.bindEvents();
            this.initGallery();
        },

        /**
         * Cache DOM elements
         */
        cacheDom: function() {
            this.$productGallery = $('.woocommerce-product-gallery');
            this.$galleryWrapper = this.$productGallery.find('.woocommerce-product-gallery__wrapper');
            this.$galleryImages = this.$galleryWrapper.find('.woocommerce-product-gallery__image');
            this.$galleryThumbs = this.$productGallery.find('.flex-control-thumbs');
            this.$galleryTrigger = this.$productGallery.find('.woocommerce-product-gallery__trigger');
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Click on thumbnail
            this.$galleryThumbs.on('click', 'li', this.handleThumbClick.bind(this));
            
            // Zoom trigger
            this.$galleryTrigger.on('click', this.handleZoomTrigger.bind(this));
            
            // Swipe events for mobile
            if (this.$galleryImages.length > 1) {
                this.$galleryWrapper.on('swipeleft', this.nextSlide.bind(this));
                this.$galleryWrapper.on('swiperight', this.prevSlide.bind(this));
            }
            
            // Keyboard navigation
            $(document).on('keydown', this.handleKeyboardNav.bind(this));
        },

        /**
         * Initialize gallery
         */
        initGallery: function() {
            // Only initialize if there are multiple images
            if (this.$galleryImages.length > 1) {
                // Initialize thumbnails slider
                this.initThumbnailsSlider();
                
                // Add navigation arrows
                this.addNavigationArrows();
                
                // Add active class to first image
                this.$galleryImages.first().addClass('active');
                this.$galleryThumbs.find('li').first().addClass('active');
            }
            
            // Initialize zoom
            this.initZoom();
            
            // Initialize lightbox
            this.initLightbox();
        },

        /**
         * Initialize thumbnails slider
         */
        initThumbnailsSlider: function() {
            // Only initialize if there are thumbnails
            if (this.$galleryThumbs.length && this.$galleryThumbs.find('li').length > 4) {
                // Check if slick is available
                if ($.fn.slick) {
                    const isVertical = window.innerWidth >= 992;
                    
                    this.$galleryThumbs.slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        vertical: isVertical,
                        arrows: true,
                        infinite: false,
                        prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-' + (isVertical ? 'chevron-up' : 'chevron-left') + '"></i></button>',
                        nextArrow: '<button type="button" class="slick-next"><i class="fas fa-' + (isVertical ? 'chevron-down' : 'chevron-right') + '"></i></button>',
                        responsive: [
                            {
                                breakpoint: 992,
                                settings: {
                                    vertical: false,
                                    slidesToShow: 4,
                                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                                }
                            },
                            {
                                breakpoint: 576,
                                settings: {
                                    vertical: false,
                                    slidesToShow: 3,
                                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                                }
                            }
                        ]
                    });
                    
                    // Handle window resize
                    $(window).on('resize', this.handleResize.bind(this));
                }
            }
        },

        /**
         * Add navigation arrows
         */
        addNavigationArrows: function() {
            // Add navigation arrows
            this.$galleryWrapper.append('<button type="button" class="gallery-nav gallery-prev"><i class="fas fa-chevron-left"></i></button>');
            this.$galleryWrapper.append('<button type="button" class="gallery-nav gallery-next"><i class="fas fa-chevron-right"></i></button>');
            
            // Cache nav elements
            this.$galleryPrev = this.$galleryWrapper.find('.gallery-prev');
            this.$galleryNext = this.$galleryWrapper.find('.gallery-next');
            
            // Bind events
            this.$galleryPrev.on('click', this.prevSlide.bind(this));
            this.$galleryNext.on('click', this.nextSlide.bind(this));
            
            // Show/hide arrows based on current slide
            this.updateNavArrows(0);
        },

        /**
         * Initialize zoom
         */
        initZoom: function() {
            // Check if zoom is enabled
            if (typeof wc_single_product_params !== 'undefined' && wc_single_product_params.zoom_enabled) {
                // Check if zoom is available
                if ($.fn.zoom) {
                    this.$galleryImages.each(function() {
                        const $image = $(this).find('img');
                        const zoomSrc = $image.attr('data-large_image');
                        
                        if (zoomSrc) {
                            $(this).zoom({
                                url: zoomSrc,
                                touch: false,
                                magnify: 1
                            });
                        }
                    });
                }
            }
        },

        /**
         * Initialize lightbox
         */
        initLightbox: function() {
            // Check if photoswipe is available
            if (typeof PhotoSwipe !== 'undefined' && typeof PhotoSwipeUI_Default !== 'undefined') {
                // Add photoswipe markup
                this.addPhotoSwipeMarkup();
                
                // Bind click event to gallery images
                this.$galleryImages.on('click', 'a', this.openPhotoSwipe.bind(this));
            }
        },

        /**
         * Add PhotoSwipe markup
         */
        addPhotoSwipeMarkup: function() {
            // Add PhotoSwipe markup if it doesn't exist
            if (!$('.pswp').length) {
                const photoswipeMarkup = `
                    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="pswp__bg"></div>
                        <div class="pswp__scroll-wrap">
                            <div class="pswp__container">
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                                <div class="pswp__item"></div>
                            </div>
                            <div class="pswp__ui pswp__ui--hidden">
                                <div class="pswp__top-bar">
                                    <div class="pswp__counter"></div>
                                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                    <button class="pswp__button pswp__button--share" title="Share"></button>
                                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                    <div class="pswp__preloader">
                                        <div class="pswp__preloader__icn">
                                            <div class="pswp__preloader__cut">
                                                <div class="pswp__preloader__donut"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                    <div class="pswp__share-tooltip"></div>
                                </div>
                                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                                <div class="pswp__caption">
                                    <div class="pswp__caption__center"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(photoswipeMarkup);
            }
        },

        /**
         * Open PhotoSwipe
         * 
         * @param {Event} e The event object
         */
        openPhotoSwipe: function(e) {
            e.preventDefault();
            
            const pswpElement = document.querySelector('.pswp');
            const items = this.getGalleryItems();
            const currentIndex = this.getCurrentIndex();
            
            const options = {
                index: currentIndex,
                shareEl: false,
                closeOnScroll: false,
                history: false,
                hideAnimationDuration: 0,
                showAnimationDuration: 0
            };
            
            // Initialize PhotoSwipe
            const gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
            gallery.init();
            
            // Update gallery index when PhotoSwipe changes slides
            gallery.listen('afterChange', () => {
                this.goToSlide(gallery.getCurrentIndex());
            });
        },

        /**
         * Get gallery items for PhotoSwipe
         * 
         * @return {Array} Gallery items
         */
        getGalleryItems: function() {
            const items = [];
            
            this.$galleryImages.each(function() {
                const $image = $(this).find('img');
                const largeImage = $image.attr('data-large_image');
                const largeWidth = $image.attr('data-large_image_width');
                const largeHeight = $image.attr('data-large_image_height');
                
                if (largeImage) {
                    items.push({
                        src: largeImage,
                        w: largeWidth,
                        h: largeHeight,
                        title: $image.attr('alt')
                    });
                }
            });
            
            return items;
        },

        /**
         * Handle thumbnail click
         * 
         * @param {Event} e The event object
         */
        handleThumbClick: function(e) {
            const $thumb = $(e.currentTarget);
            const index = $thumb.index();
            
            this.goToSlide(index);
        },

        /**
         * Handle zoom trigger click
         * 
         * @param {Event} e The event object
         */
        handleZoomTrigger: function(e) {
            e.preventDefault();
            
            // Trigger click on current image
            this.$galleryImages.filter('.active').find('a').trigger('click');
        },

        /**
         * Handle keyboard navigation
         * 
         * @param {Event} e The event object
         */
        handleKeyboardNav: function(e) {
            // Only handle keyboard nav if gallery is in focus
            if (this.$productGallery.is(':focus-within')) {
                if (e.keyCode === 37) { // Left arrow
                    this.prevSlide();
                } else if (e.keyCode === 39) { // Right arrow
                    this.nextSlide();
                }
            }
        },

        /**
         * Handle window resize
         */
        handleResize: function() {
            // Reinitialize thumbnails slider
            if ($.fn.slick && this.$galleryThumbs.hasClass('slick-initialized')) {
                this.$galleryThumbs.slick('unslick');
                this.initThumbnailsSlider();
            }
        },

        /**
         * Go to previous slide
         */
        prevSlide: function() {
            const currentIndex = this.getCurrentIndex();
            
            if (currentIndex > 0) {
                this.goToSlide(currentIndex - 1);
            }
        },

        /**
         * Go to next slide
         */
        nextSlide: function() {
            const currentIndex = this.getCurrentIndex();
            const lastIndex = this.$galleryImages.length - 1;
            
            if (currentIndex < lastIndex) {
                this.goToSlide(currentIndex + 1);
            }
        },

        /**
         * Go to specific slide
         * 
         * @param {number} index The slide index
         */
        goToSlide: function(index) {
            // Update gallery images
            this.$galleryImages.removeClass('active');
            this.$galleryImages.eq(index).addClass('active');
            
            // Update thumbnails
            this.$galleryThumbs.find('li').removeClass('active');
            this.$galleryThumbs.find('li').eq(index).addClass('active');
            
            // Scroll thumbnail into view if using slick
            if ($.fn.slick && this.$galleryThumbs.hasClass('slick-initialized')) {
                this.$galleryThumbs.slick('slickGoTo', index);
            }
            
            // Update navigation arrows
            this.updateNavArrows(index);
            
            // Dispatch custom event
            this.dispatchEvent('productGalleryChanged', {
                index: index
            });
        },

        /**
         * Get current index
         * 
         * @return {number} Current index
         */
        getCurrentIndex: function() {
            return this.$galleryImages.filter('.active').index();
        },

        /**
         * Update navigation arrows
         * 
         * @param {number} index The current index
         */
        updateNavArrows: function(index) {
            const lastIndex = this.$galleryImages.length - 1;
            
            // Show/hide prev arrow
            if (index === 0) {
                this.$galleryPrev.addClass('disabled');
            } else {
                this.$galleryPrev.removeClass('disabled');
            }
            
            // Show/hide next arrow
            if (index === lastIndex) {
                this.$galleryNext.addClass('disabled');
            } else {
                this.$galleryNext.removeClass('disabled');
            }
        },

        /**
         * Dispatch custom event
         * 
         * @param {string} eventName The event name
         * @param {Object} detail The event detail
         */
        dispatchEvent: function(eventName, detail = {}) {
            const event = new CustomEvent(eventName, {
                detail: detail
            });
            
            document.dispatchEvent(event);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Only initialize if product gallery exists
        if ($('.woocommerce-product-gallery').length) {
            ProductGallery.init();
        }
    });

})(jQuery);