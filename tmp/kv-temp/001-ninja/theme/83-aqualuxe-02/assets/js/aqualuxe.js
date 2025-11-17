/**
 * AquaLuxe Enterprise Theme JavaScript
 * 
 * Frontend functionality for ornamental aquatic solutions platform
 * Handles interactive features, animations, and e-commerce functionality
 * 
 * @package AquaLuxe_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * AquaLuxe Theme Object
     */
    const AquaLuxe = {
        
        /**
         * Initialize all theme functionality
         */
        init: function() {
            this.initHeroVideo();
            this.initProductFilters();
            this.initExportQuotes();
            this.initAnimations();
            this.initNavigation();
            this.initModals();
            this.initTooltips();
            this.initCounters();
            this.initTestimonialSlider();
            this.initAquariumCalculator();
            this.initCareGuides();
        },
        
        /**
         * Initialize hero video functionality
         */
        initHeroVideo: function() {
            const heroVideo = document.querySelector('.hero-video');
            if (!heroVideo) return;
            
            // Play/Pause controls
            $('.hero-video-controls .play-pause').on('click', function() {
                const video = heroVideo.querySelector('video');
                const icon = $(this).find('i');
                
                if (video.paused) {
                    video.play();
                    icon.removeClass('fa-play').addClass('fa-pause');
                } else {
                    video.pause();
                    icon.removeClass('fa-pause').addClass('fa-play');
                }
            });
            
            // Mute/Unmute controls
            $('.hero-video-controls .mute-unmute').on('click', function() {
                const video = heroVideo.querySelector('video');
                const icon = $(this).find('i');
                
                video.muted = !video.muted;
                if (video.muted) {
                    icon.removeClass('fa-volume-up').addClass('fa-volume-mute');
                } else {
                    icon.removeClass('fa-volume-mute').addClass('fa-volume-up');
                }
            });
        },
        
        /**
         * Initialize product filtering
         */
        initProductFilters: function() {
            const filterForm = $('.product-filters');
            if (!filterForm.length) return;
            
            // Water type filter
            $('.filter-water-type input[type="checkbox"]').on('change', function() {
                this.applyFilters();
            }.bind(this));
            
            // Care level filter
            $('.filter-care-level select').on('change', function() {
                this.applyFilters();
            }.bind(this));
            
            // Price range filter
            $('.filter-price-range input[type="range"]').on('input', function() {
                const min = $('#price-min').val();
                const max = $('#price-max').val();
                $('.price-range-display').text(`$${min} - $${max}`);
                
                // Debounced filter application
                clearTimeout(this.priceFilterTimeout);
                this.priceFilterTimeout = setTimeout(() => {
                    this.applyFilters();
                }, 500);
            }.bind(this));
            
            // Clear filters
            $('.clear-filters').on('click', function(e) {
                e.preventDefault();
                filterForm[0].reset();
                this.applyFilters();
            }.bind(this));
        },
        
        /**
         * Apply product filters
         */
        applyFilters: function() {
            const formData = new FormData($('.product-filters')[0]);
            const searchParams = new URLSearchParams();
            
            for (const [key, value] of formData.entries()) {
                searchParams.append(key, value);
            }
            
            // Update URL without page reload
            const newUrl = window.location.pathname + '?' + searchParams.toString();
            history.pushState({}, '', newUrl);
            
            // Load filtered products via AJAX
            this.loadFilteredProducts(searchParams);
        },
        
        /**
         * Load filtered products
         */
        loadFilteredProducts: function(searchParams) {
            const productsContainer = $('.products-grid');
            
            // Show loading state
            productsContainer.addClass('loading');
            
            $.ajax({
                url: aqualuxeEnterprise.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'filter_products',
                    nonce: aqualuxeEnterprise.nonce,
                    filters: Object.fromEntries(searchParams)
                },
                success: function(response) {
                    if (response.success) {
                        productsContainer.html(response.data.html);
                        this.initProductCards();
                    }
                }.bind(this),
                complete: function() {
                    productsContainer.removeClass('loading');
                }
            });
        },
        
        /**
         * Initialize export quote functionality
         */
        initExportQuotes: function() {
            $('.request-export-quote').on('click', function(e) {
                e.preventDefault();
                
                const productId = $(this).data('product-id');
                const modal = $('#export-quote-modal');
                
                // Set product ID in modal
                modal.find('input[name="product_id"]').val(productId);
                
                // Show modal
                modal.modal('show');
            });
            
            // Handle quote form submission
            $('#export-quote-form').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                
                // Disable submit button
                submitBtn.prop('disabled', true).text('Submitting...');
                
                $.ajax({
                    url: aqualuxeEnterprise.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'request_export_quote',
                        nonce: aqualuxeEnterprise.nonce,
                        ...Object.fromEntries(new FormData(this))
                    },
                    success: function(response) {
                        if (response.success) {
                            form[0].reset();
                            $('#export-quote-modal').modal('hide');
                            this.showNotification('Export quote requested successfully!', 'success');
                        } else {
                            this.showNotification('Error submitting quote request.', 'error');
                        }
                    }.bind(this),
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Submit Quote Request');
                    }
                });
            }.bind(this));
        },
        
        /**
         * Initialize animations
         */
        initAnimations: function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);
            
            // Observe elements with animation classes
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
            
            // Fish swimming animations
            this.initFishAnimations();
            
            // Bubble animations
            this.initBubbleAnimations();
            
            // Water ripple effects
            this.initWaterEffects();
        },
        
        /**
         * Initialize fish swimming animations
         */
        initFishAnimations: function() {
            const fishContainer = $('.fish-animation-container');
            if (!fishContainer.length) return;
            
            // Create swimming fish elements
            for (let i = 0; i < 5; i++) {
                const fish = $('<div class="swimming-fish"></div>');
                fish.css({
                    'animation-delay': `${i * 2}s`,
                    'animation-duration': `${8 + Math.random() * 4}s`
                });
                fishContainer.append(fish);
            }
        },
        
        /**
         * Initialize bubble animations
         */
        initBubbleAnimations: function() {
            const createBubble = () => {
                const bubble = $('<div class="bubble"></div>');
                const size = Math.random() * 20 + 10;
                const left = Math.random() * 100;
                const duration = Math.random() * 3 + 2;
                
                bubble.css({
                    width: size + 'px',
                    height: size + 'px',
                    left: left + '%',
                    'animation-duration': duration + 's'
                });
                
                $('.bubble-container').append(bubble);
                
                setTimeout(() => bubble.remove(), duration * 1000);
            };
            
            // Create bubbles periodically
            setInterval(createBubble, 1000);
        },
        
        /**
         * Initialize water ripple effects
         */
        initWaterEffects: function() {
            $('.water-surface').on('click', function(e) {
                const ripple = $('<div class="ripple"></div>');
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                ripple.css({
                    left: x + 'px',
                    top: y + 'px'
                });
                
                $(this).append(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        },
        
        /**
         * Initialize navigation functionality
         */
        initNavigation: function() {
            // Mobile menu toggle
            $('.mobile-menu-toggle').on('click', function() {
                $(this).toggleClass('active');
                $('.main-navigation').toggleClass('open');
                $('body').toggleClass('menu-open');
            });
            
            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            });
            
            // Sticky header
            this.initStickyHeader();
        },
        
        /**
         * Initialize sticky header
         */
        initStickyHeader: function() {
            const header = $('.main-header');
            const headerHeight = header.outerHeight();
            let isSticky = false;
            
            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > headerHeight && !isSticky) {
                    header.addClass('sticky');
                    isSticky = true;
                } else if (scrollTop <= headerHeight && isSticky) {
                    header.removeClass('sticky');
                    isSticky = false;
                }
            });
        },
        
        /**
         * Initialize modal functionality
         */
        initModals: function() {
            // Close modal on backdrop click
            $('.modal-backdrop').on('click', function() {
                $(this).closest('.modal').removeClass('show');
            });
            
            // Close modal on close button click
            $('.modal-close').on('click', function() {
                $(this).closest('.modal').removeClass('show');
            });
            
            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    $('.modal.show').removeClass('show');
                }
            });
        },
        
        /**
         * Initialize tooltips
         */
        initTooltips: function() {
            $('[data-tooltip]').each(function() {
                const $this = $(this);
                const tooltip = $('<div class="tooltip">' + $this.data('tooltip') + '</div>');
                
                $this.on('mouseenter', function() {
                    $('body').append(tooltip);
                    tooltip.addClass('show');
                }).on('mouseleave', function() {
                    tooltip.remove();
                }).on('mousemove', function(e) {
                    tooltip.css({
                        left: e.pageX + 10,
                        top: e.pageY - 30
                    });
                });
            });
        },
        
        /**
         * Initialize counters
         */
        initCounters: function() {
            $('.counter').each(function() {
                const $this = $(this);
                const target = parseInt($this.data('target'));
                const duration = parseInt($this.data('duration') || 2000);
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.animateCounter($this, target, duration);
                            observer.unobserve(entry.target);
                        }
                    });
                });
                
                observer.observe(this);
            }.bind(this));
        },
        
        /**
         * Animate counter
         */
        animateCounter: function($element, target, duration) {
            let current = 0;
            const increment = target / (duration / 16);
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                $element.text(Math.floor(current));
            }, 16);
        },
        
        /**
         * Initialize testimonial slider
         */
        initTestimonialSlider: function() {
            const slider = $('.testimonial-slider');
            if (!slider.length) return;
            
            let currentSlide = 0;
            const slides = slider.find('.testimonial-slide');
            const totalSlides = slides.length;
            
            const showSlide = (index) => {
                slides.removeClass('active');
                slides.eq(index).addClass('active');
                
                // Update pagination
                $('.testimonial-pagination .dot').removeClass('active');
                $('.testimonial-pagination .dot').eq(index).addClass('active');
            };
            
            // Auto-play
            const autoPlay = () => {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            };
            
            let autoPlayInterval = setInterval(autoPlay, 5000);
            
            // Navigation
            $('.testimonial-prev').on('click', function() {
                clearInterval(autoPlayInterval);
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
                autoPlayInterval = setInterval(autoPlay, 5000);
            });
            
            $('.testimonial-next').on('click', function() {
                clearInterval(autoPlayInterval);
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
                autoPlayInterval = setInterval(autoPlay, 5000);
            });
            
            // Pagination dots
            $('.testimonial-pagination .dot').on('click', function() {
                clearInterval(autoPlayInterval);
                currentSlide = $(this).index();
                showSlide(currentSlide);
                autoPlayInterval = setInterval(autoPlay, 5000);
            });
        },
        
        /**
         * Initialize aquarium calculator
         */
        initAquariumCalculator: function() {
            $('#aquarium-calculator').on('submit', function(e) {
                e.preventDefault();
                
                const length = parseFloat($('#tank-length').val());
                const width = parseFloat($('#tank-width').val());
                const height = parseFloat($('#tank-height').val());
                
                if (length && width && height) {
                    const gallons = (length * width * height) / 231; // Convert cubic inches to gallons
                    const liters = gallons * 3.78541; // Convert gallons to liters
                    
                    $('#calculator-result').html(`
                        <h4>Tank Capacity:</h4>
                        <p><strong>${gallons.toFixed(1)} gallons</strong></p>
                        <p><strong>${liters.toFixed(1)} liters</strong></p>
                    `).show();
                }
            });
        },
        
        /**
         * Initialize care guides
         */
        initCareGuides: function() {
            $('.care-guide-tabs .tab').on('click', function() {
                const target = $(this).data('target');
                
                // Update active tab
                $('.care-guide-tabs .tab').removeClass('active');
                $(this).addClass('active');
                
                // Show target content
                $('.care-guide-content').removeClass('active');
                $(target).addClass('active');
            });
            
            // Collapsible care sections
            $('.care-section-header').on('click', function() {
                $(this).next('.care-section-content').slideToggle();
                $(this).find('.icon').toggleClass('rotate');
            });
        },
        
        /**
         * Initialize product cards
         */
        initProductCards: function() {
            $('.product-card').on('mouseenter', function() {
                $(this).find('.product-overlay').fadeIn(200);
            }).on('mouseleave', function() {
                $(this).find('.product-overlay').fadeOut(200);
            });
            
            // Quick view functionality
            $('.quick-view-btn').on('click', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                this.loadProductQuickView(productId);
            }.bind(this));
        },
        
        /**
         * Load product quick view
         */
        loadProductQuickView: function(productId) {
            $.ajax({
                url: aqualuxeEnterprise.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'load_product_quick_view',
                    nonce: aqualuxeEnterprise.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        $('#quick-view-modal .modal-body').html(response.data.html);
                        $('#quick-view-modal').modal('show');
                    }
                }
            });
        },
        
        /**
         * Show notification
         */
        showNotification: function(message, type = 'info') {
            const notification = $(`
                <div class="notification notification-${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            `);
            
            $('body').append(notification);
            
            setTimeout(() => {
                notification.addClass('show');
            }, 10);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                notification.removeClass('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
            
            // Manual close
            notification.find('.notification-close').on('click', function() {
                notification.removeClass('show');
                setTimeout(() => notification.remove(), 300);
            });
        }
    };
    
    /**
     * Initialize AquaLuxe when document is ready
     */
    $(document).ready(function() {
        AquaLuxe.init();
    });
    
    /**
     * Expose AquaLuxe to global scope
     */
    window.AquaLuxe = AquaLuxe;
    
})(jQuery);
