/**
 * Main JavaScript file
 *
 * This file contains the main JavaScript code for the theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Import dependencies
import 'jquery';
import AOS from 'aos';
import GLightbox from 'glightbox';
import Swiper from 'swiper/bundle';

// Import modules
import './modules/navigation';
import './modules/dark-mode';
import './modules/scroll-to-top';
import './modules/sticky-header';
import './modules/preloader';
import './modules/cookie-notice';
import './modules/newsletter';
import './modules/search';

// Initialize AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});

// Initialize GLightbox
const lightbox = GLightbox({
    selector: '.glightbox',
    touchNavigation: true,
    loop: true,
    autoplayVideos: true
});

// Initialize Swiper sliders
document.addEventListener('DOMContentLoaded', () => {
    // Hero slider
    if (document.querySelector('.hero-slider')) {
        new Swiper('.hero-slider', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            effect: 'fade',
            autoplay: {
                delay: 5000,
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
        });
    }

    // Testimonials slider
    if (document.querySelector('.testimonials-slider')) {
        new Swiper('.testimonials-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
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
        });
    }

    // Products slider
    if (document.querySelector('.products-slider')) {
        new Swiper('.products-slider', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
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
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
        });
    }

    // Gallery slider
    if (document.querySelector('.gallery-slider')) {
        new Swiper('.gallery-slider', {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
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
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
            },
        });
    }
});

// Handle accordion functionality
const accordions = document.querySelectorAll('.accordion');
if (accordions.length > 0) {
    accordions.forEach(accordion => {
        const accordionHeaders = accordion.querySelectorAll('.accordion-header');
        
        accordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const accordionItem = header.parentElement;
                const accordionContent = header.nextElementSibling;
                
                // Toggle active class
                accordionItem.classList.toggle('active');
                
                // Toggle content visibility
                if (accordionItem.classList.contains('active')) {
                    accordionContent.style.maxHeight = accordionContent.scrollHeight + 'px';
                } else {
                    accordionContent.style.maxHeight = '0';
                }
            });
        });
    });
}

// Handle tabs functionality
const tabGroups = document.querySelectorAll('.tabs');
if (tabGroups.length > 0) {
    tabGroups.forEach(tabGroup => {
        const tabButtons = tabGroup.querySelectorAll('.tab-button');
        const tabContents = tabGroup.querySelectorAll('.tab-content');
        
        tabButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to current button and content
                button.classList.add('active');
                tabContents[index].classList.add('active');
            });
        });
    });
}

// Handle modal functionality
const modalTriggers = document.querySelectorAll('[data-modal]');
if (modalTriggers.length > 0) {
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.add('active');
                document.body.classList.add('modal-open');
                
                // Close modal when clicking on close button or overlay
                const closeButtons = modal.querySelectorAll('.modal-close, .modal-overlay');
                closeButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        modal.classList.remove('active');
                        document.body.classList.remove('modal-open');
                    });
                });
                
                // Close modal when pressing ESC key
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal.classList.contains('active')) {
                        modal.classList.remove('active');
                        document.body.classList.remove('modal-open');
                    }
                });
            }
        });
    });
}

// Handle counter animation
const counters = document.querySelectorAll('.counter');
if (counters.length > 0) {
    const counterObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'), 10);
                const duration = parseInt(counter.getAttribute('data-duration') || '2000', 10);
                const increment = target / (duration / 16);
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    counter.textContent = Math.floor(current);
                    
                    if (current < target) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Handle parallax effect
const parallaxElements = document.querySelectorAll('.parallax');
if (parallaxElements.length > 0) {
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-speed') || 0.5;
            element.style.transform = `translateY(${scrollY * speed}px)`;
        });
    });
}

// Handle smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            const headerHeight = document.querySelector('.site-header').offsetHeight;
            const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - headerHeight;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Handle form validation
const forms = document.querySelectorAll('form.validate');
if (forms.length > 0) {
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    
                    // Add error message if it doesn't exist
                    let errorMessage = field.nextElementSibling;
                    if (!errorMessage || !errorMessage.classList.contains('error-message')) {
                        errorMessage = document.createElement('div');
                        errorMessage.classList.add('error-message');
                        errorMessage.textContent = 'This field is required';
                        field.parentNode.insertBefore(errorMessage, field.nextSibling);
                    }
                } else {
                    field.classList.remove('error');
                    
                    // Remove error message if it exists
                    const errorMessage = field.nextElementSibling;
                    if (errorMessage && errorMessage.classList.contains('error-message')) {
                        errorMessage.remove();
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
}

// Initialize custom select elements
const customSelects = document.querySelectorAll('.custom-select');
if (customSelects.length > 0) {
    customSelects.forEach(select => {
        const selectElement = select.querySelector('select');
        const selectedOption = document.createElement('div');
        selectedOption.classList.add('selected-option');
        selectedOption.textContent = selectElement.options[selectElement.selectedIndex].text;
        
        const optionsList = document.createElement('div');
        optionsList.classList.add('options-list');
        
        Array.from(selectElement.options).forEach(option => {
            const optionElement = document.createElement('div');
            optionElement.classList.add('option');
            optionElement.textContent = option.text;
            optionElement.setAttribute('data-value', option.value);
            
            optionElement.addEventListener('click', () => {
                selectElement.value = option.value;
                selectedOption.textContent = option.text;
                optionsList.classList.remove('active');
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                selectElement.dispatchEvent(event);
            });
            
            optionsList.appendChild(optionElement);
        });
        
        selectedOption.addEventListener('click', () => {
            optionsList.classList.toggle('active');
        });
        
        // Close options list when clicking outside
        document.addEventListener('click', (e) => {
            if (!select.contains(e.target)) {
                optionsList.classList.remove('active');
            }
        });
        
        select.appendChild(selectedOption);
        select.appendChild(optionsList);
    });
}

// Handle quantity input
const quantityInputs = document.querySelectorAll('.quantity');
if (quantityInputs.length > 0) {
    quantityInputs.forEach(quantity => {
        const input = quantity.querySelector('input');
        const decreaseButton = quantity.querySelector('.decrease');
        const increaseButton = quantity.querySelector('.increase');
        
        decreaseButton.addEventListener('click', () => {
            const currentValue = parseInt(input.value, 10);
            if (currentValue > parseInt(input.getAttribute('min') || '1', 10)) {
                input.value = currentValue - 1;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        });
        
        increaseButton.addEventListener('click', () => {
            const currentValue = parseInt(input.value, 10);
            const max = parseInt(input.getAttribute('max') || '999', 10);
            if (currentValue < max) {
                input.value = currentValue + 1;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        });
    });
}

// Handle masonry layout
const masonryGrids = document.querySelectorAll('.masonry-grid');
if (masonryGrids.length > 0 && window.Masonry) {
    masonryGrids.forEach(grid => {
        new Masonry(grid, {
            itemSelector: '.masonry-item',
            columnWidth: '.masonry-sizer',
            percentPosition: true
        });
    });
}

// Handle isotope filtering
const isotopeGrids = document.querySelectorAll('.isotope-grid');
if (isotopeGrids.length > 0 && window.Isotope) {
    isotopeGrids.forEach(grid => {
        const iso = new Isotope(grid, {
            itemSelector: '.isotope-item',
            layoutMode: 'fitRows'
        });
        
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filterValue = button.getAttribute('data-filter');
                iso.arrange({ filter: filterValue });
                
                // Update active class
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
            });
        });
    });
}

// Export global functions
window.AquaLuxe = {
    // Add any functions that need to be accessible globally
};