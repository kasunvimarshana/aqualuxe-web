/**
 * Main application JavaScript
 * 
 * @package AquaLuxe
 */

import Alpine from 'alpinejs';
import { Swiper } from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Configure Swiper
Swiper.use([Navigation, Pagination, Autoplay]);

// jQuery DOM ready
jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize theme
    AquaLuxe.init();
});

/**
 * Main AquaLuxe object
 */
window.AquaLuxe = {
    
    /**
     * Initialize theme
     */
    init: function() {
        this.setupNavigation();
        this.setupSearch();
        this.setupBackToTop();
        this.setupLazyLoading();
        this.setupSmoothScrolling();
        this.setupNewsletterForm();
        this.setupContactForm();
        this.setupMobileMenu();
        this.setupSwipers();
        this.setupParallax();
        this.setupAnimations();
    },
    
    /**
     * Setup navigation
     */
    setupNavigation: function() {
        const $nav = $('.main-navigation');
        const $header = $('.site-header');
        
        // Sticky header
        if (aqualuxe_vars.sticky_header) {
            let lastScrollTop = 0;
            
            $(window).scroll(function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > 100) {
                    $header.addClass('is-sticky');
                    
                    if (scrollTop > lastScrollTop) {
                        $header.addClass('is-hidden');
                    } else {
                        $header.removeClass('is-hidden');
                    }
                } else {
                    $header.removeClass('is-sticky is-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        }
        
        // Dropdown menus
        $nav.find('.menu-item-has-children > a').on('click', function(e) {
            if ($(window).width() < 1024) {
                e.preventDefault();
                $(this).next('.sub-menu').slideToggle();
                $(this).parent().toggleClass('is-open');
            }
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation').length) {
                $('.sub-menu').slideUp();
                $('.menu-item-has-children').removeClass('is-open');
            }
        });
    },
    
    /**
     * Setup search functionality
     */
    setupSearch: function() {
        const $searchToggle = $('#search-toggle');
        const $searchOverlay = $('#search-overlay');
        const $searchClose = $('#search-close');
        const $searchInput = $searchOverlay.find('input[type="search"]');
        
        // Open search overlay
        $searchToggle.on('click', function(e) {
            e.preventDefault();
            $searchOverlay.removeClass('hidden').addClass('flex');
            setTimeout(() => $searchInput.focus(), 100);
        });
        
        // Close search overlay
        $searchClose.on('click', function(e) {
            e.preventDefault();
            $searchOverlay.addClass('hidden').removeClass('flex');
        });
        
        // Close on overlay click
        $searchOverlay.on('click', function(e) {
            if (e.target === this) {
                $(this).addClass('hidden').removeClass('flex');
            }
        });
        
        // Close on escape key
        $(document).on('keyup', function(e) {
            if (e.key === 'Escape' && $searchOverlay.is(':visible')) {
                $searchOverlay.addClass('hidden').removeClass('flex');
            }
        });
    },
    
    /**
     * Setup mobile menu
     */
    setupMobileMenu: function() {
        const $toggle = $('#mobile-menu-toggle');
        const $menu = $('#mobile-menu');
        const $body = $('body');
        
        $toggle.on('click', function(e) {
            e.preventDefault();
            
            const isOpen = $menu.hasClass('hidden');
            
            if (isOpen) {
                $menu.removeClass('hidden');
                $toggle.attr('aria-expanded', 'true');
                $body.addClass('mobile-menu-open');
            } else {
                $menu.addClass('hidden');
                $toggle.attr('aria-expanded', 'false');
                $body.removeClass('mobile-menu-open');
            }
        });
        
        // Close mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() >= 1024) {
                $menu.addClass('hidden');
                $toggle.attr('aria-expanded', 'false');
                $body.removeClass('mobile-menu-open');
            }
        });
    },
    
    /**
     * Setup back to top button
     */
    setupBackToTop: function() {
        const $backToTop = $('#back-to-top');
        
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.removeClass('opacity-0 invisible').addClass('opacity-100 visible');
            } else {
                $backToTop.removeClass('opacity-100 visible').addClass('opacity-0 invisible');
            }
        });
        
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, 600);
        });
    },
    
    /**
     * Setup lazy loading
     */
    setupLazyLoading: function() {
        if ('IntersectionObserver' in window) {
            const lazyImages = document.querySelectorAll('img[data-src]');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            lazyImages.forEach(img => imageObserver.observe(img));
        }
    },
    
    /**
     * Setup smooth scrolling
     */
    setupSmoothScrolling: function() {
        $('a[href^="#"]').on('click', function(e) {
            const target = $(this.hash);
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 600);
            }
        });
    },
    
    /**
     * Setup newsletter form
     */
    setupNewsletterForm: function() {
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $email = $form.find('input[name="newsletter_email"]');
            const $button = $form.find('button[type="submit"]');
            
            // Basic validation
            if (!$email.val() || !$email[0].checkValidity()) {
                $email.addClass('border-red-500');
                return;
            }
            
            $email.removeClass('border-red-500');
            $button.addClass('btn-loading').prop('disabled', true);
            
            // AJAX subscription
            $.post(aqualuxe_vars.ajax_url, {
                action: 'aqualuxe_newsletter_subscribe',
                email: $email.val(),
                nonce: aqualuxe_vars.nonce
            })
            .done(function(response) {
                if (response.success) {
                    $form[0].reset();
                    alert(aqualuxe_vars.strings.success);
                } else {
                    alert(response.data || aqualuxe_vars.strings.error);
                }
            })
            .fail(function() {
                alert(aqualuxe_vars.strings.error);
            })
            .always(function() {
                $button.removeClass('btn-loading').prop('disabled', false);
            });
        });
    },
    
    /**
     * Setup contact form
     */
    setupContactForm: function() {
        $('.contact-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const formData = new FormData(this);
            formData.append('action', 'aqualuxe_contact_form');
            formData.append('nonce', aqualuxe_vars.nonce);
            
            $button.addClass('btn-loading').prop('disabled', true);
            
            $.post({
                url: aqualuxe_vars.ajax_url,
                data: formData,
                processData: false,
                contentType: false
            })
            .done(function(response) {
                if (response.success) {
                    $form[0].reset();
                    alert(aqualuxe_vars.strings.success);
                } else {
                    alert(response.data || aqualuxe_vars.strings.error);
                }
            })
            .fail(function() {
                alert(aqualuxe_vars.strings.error);
            })
            .always(function() {
                $button.removeClass('btn-loading').prop('disabled', false);
            });
        });
    },
    
    /**
     * Setup Swiper carousels
     */
    setupSwipers: function() {
        // Hero slider
        if ($('.hero-slider').length) {
            new Swiper('.hero-slider', {
                loop: true,
                autoplay: {
                    delay: 5000,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
        
        // Product carousel
        if ($('.product-carousel').length) {
            new Swiper('.product-carousel', {
                slidesPerView: 1,
                spaceBetween: 20,
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
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
        
        // Testimonials carousel
        if ($('.testimonials-carousel').length) {
            new Swiper('.testimonials-carousel', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 4000,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }
    },
    
    /**
     * Setup parallax effects
     */
    setupParallax: function() {
        const $parallaxElements = $('.parallax');
        
        if ($parallaxElements.length) {
            $(window).on('scroll', function() {
                const scrollTop = $(window).scrollTop();
                
                $parallaxElements.each(function() {
                    const $this = $(this);
                    const speed = $this.data('speed') || 0.5;
                    const yPos = -(scrollTop * speed);
                    $this.css('transform', `translateY(${yPos}px)`);
                });
            });
        }
    },
    
    /**
     * Setup scroll animations
     */
    setupAnimations: function() {
        if ('IntersectionObserver' in window) {
            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                        animationObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                animationObserver.observe(el);
            });
        }
    }
};