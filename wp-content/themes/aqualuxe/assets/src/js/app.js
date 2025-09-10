/**
 * AquaLuxe Theme - Main JavaScript File
 *
 * This file contains the main JavaScript functionality for the AquaLuxe theme.
 * It includes modern ES6+ features, performance optimizations, and accessibility enhancements.
 */

// Initialize theme when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("AquaLuxe Theme Loaded");

    // Initialize all theme components
    initializeNavigation();
    initializeAnimations();
    initializeScrollEffects();
    initializeForms();
    initializeWooCommerce();
    initializeAccessibility();
    initializePerformanceOptimizations();
});

/**
 * Navigation Enhancement
 */
function initializeNavigation() {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", function () {
            const isExpanded = this.getAttribute("aria-expanded") === "true";
            this.setAttribute("aria-expanded", !isExpanded);
            mobileMenu.classList.toggle("hidden");

            // Update button icon
            const icon = this.querySelector("svg");
            if (icon) {
                icon.classList.toggle("rotate-90");
            }
        });
    }

    // Legacy menu toggle support
    const menuToggle = document.querySelector(".menu-toggle");
    const menu = document.querySelector("#primary-menu");

    if (menuToggle && menu) {
        menuToggle.addEventListener("click", function () {
            menu.classList.toggle("hidden");
        });
    }

    // Dropdown menus
    const dropdownButtons = document.querySelectorAll(".dropdown-button");
    dropdownButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const dropdown = this.nextElementSibling;
            if (dropdown) {
                dropdown.classList.toggle("hidden");

                // Update aria-expanded
                const isExpanded =
                    this.getAttribute("aria-expanded") === "true";
                this.setAttribute("aria-expanded", !isExpanded);
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (e) {
        if (!e.target.closest(".dropdown")) {
            const dropdowns = document.querySelectorAll(".dropdown-menu");
            dropdowns.forEach((dropdown) => {
                dropdown.classList.add("hidden");
            });

            const buttons = document.querySelectorAll(".dropdown-button");
            buttons.forEach((button) => {
                button.setAttribute("aria-expanded", "false");
            });
        }
    });

    // Smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach((link) => {
        link.addEventListener("click", function (e) {
            const targetId = this.getAttribute("href").substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });

                // Update focus for accessibility
                targetElement.focus({ preventScroll: true });
            }
        });
    });
}

/**
 * Animation and Scroll Effects
 */
function initializeAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px",
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("animate-fade-in");
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe elements with animation classes
    const animatedElements = document.querySelectorAll(
        ".animate-on-scroll, .card, .service-item, .project-item, .team-member, .testimonial-item"
    );

    animatedElements.forEach((element) => {
        observer.observe(element);
    });

    // Parallax effect for hero sections
    const heroElements = document.querySelectorAll(".hero-parallax");

    function updateParallax() {
        const scrollY = window.pageYOffset;

        heroElements.forEach((element) => {
            const speed = element.dataset.speed || 0.5;
            const yPos = -(scrollY * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    }

    // Throttled scroll listener for performance
    let ticking = false;
    window.addEventListener("scroll", function () {
        if (!ticking) {
            requestAnimationFrame(function () {
                updateParallax();
                ticking = false;
            });
            ticking = true;
        }
    });
}

/**
 * Scroll Effects and Sticky Navigation
 */
function initializeScrollEffects() {
    const header = document.querySelector(".site-header");
    const scrollThreshold = 100;

    function handleScroll() {
        const scrollY = window.pageYOffset;

        if (header) {
            if (scrollY > scrollThreshold) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        }

        // Update progress indicator if present
        const progressBar = document.querySelector(".scroll-progress");
        if (progressBar) {
            const docHeight =
                document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollY / docHeight) * 100;
            progressBar.style.width = `${scrollPercent}%`;
        }
    }

    // Throttled scroll listener
    let scrollTicking = false;
    window.addEventListener("scroll", function () {
        if (!scrollTicking) {
            requestAnimationFrame(function () {
                handleScroll();
                scrollTicking = false;
            });
            scrollTicking = true;
        }
    });

    // Back to top button
    const backToTopButton = document.querySelector(".back-to-top");
    if (backToTopButton) {
        backToTopButton.addEventListener("click", function (e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        });
    }
}

/**
 * Form Enhancement
 */
function initializeForms() {
    // Enhanced form validation
    const forms = document.querySelectorAll("form");

    forms.forEach((form) => {
        const inputs = form.querySelectorAll("input, textarea, select");

        inputs.forEach((input) => {
            // Real-time validation
            input.addEventListener("blur", function () {
                validateField(this);
            });

            input.addEventListener("input", function () {
                clearFieldErrors(this);
            });
        });

        form.addEventListener("submit", function (e) {
            let isValid = true;

            inputs.forEach((input) => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();

                // Focus first invalid field
                const firstInvalid = form.querySelector(".field-error");
                if (firstInvalid) {
                    firstInvalid.focus();
                }
            }
        });
    });

    // Newsletter signup enhancement
    const newsletterForms = document.querySelectorAll(".newsletter-form");
    newsletterForms.forEach((form) => {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const email = form.querySelector('input[type="email"]');
            const button = form.querySelector('button[type="submit"]');

            if (email && button) {
                const originalText = button.textContent;
                button.textContent = "Subscribing...";
                button.disabled = true;

                // Simulate API call (replace with actual implementation)
                setTimeout(() => {
                    showNotification("Thank you for subscribing!", "success");
                    form.reset();
                    button.textContent = originalText;
                    button.disabled = false;
                }, 1000);
            }
        });
    });
}

/**
 * WooCommerce Enhancements
 */
function initializeWooCommerce() {
    // Add to cart with AJAX feedback
    const addToCartButtons = document.querySelectorAll(".add_to_cart_button");

    addToCartButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const originalText = this.textContent;
            this.textContent = "Adding...";
            this.disabled = true;

            // Re-enable after timeout (WooCommerce handles the actual AJAX)
            setTimeout(() => {
                this.textContent = "Added!";
                setTimeout(() => {
                    this.textContent = originalText;
                    this.disabled = false;
                }, 1000);
            }, 500);
        });
    });
}

/**
 * Accessibility Enhancements
 */
function initializeAccessibility() {
    // Skip to main content link
    const skipLink = document.createElement("a");
    skipLink.href = "#main";
    skipLink.textContent = "Skip to main content";
    skipLink.className =
        "sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded z-50";
    document.body.insertBefore(skipLink, document.body.firstChild);
}

/**
 * Performance Optimizations
 */
function initializePerformanceOptimizations() {
    // Lazy load images
    const images = document.querySelectorAll("img[data-src]");

    if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove("lazy");
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach((img) => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        images.forEach((img) => {
            img.src = img.dataset.src;
        });
    }
}

/**
 * Field Validation Helper
 */
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const required = field.hasAttribute("required");

    clearFieldErrors(field);

    // Required field validation
    if (required && !value) {
        showFieldError(field, "This field is required");
        return false;
    }

    // Email validation
    if (type === "email" && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showFieldError(field, "Please enter a valid email address");
            return false;
        }
    }

    return true;
}

function showFieldError(field, message) {
    field.classList.add("field-error");
    field.setAttribute("aria-invalid", "true");

    // Remove existing error message
    const existingError = field.parentNode.querySelector(".error-message");
    if (existingError) {
        existingError.remove();
    }

    // Add error message
    const errorElement = document.createElement("div");
    errorElement.className = "error-message text-red-600 text-sm mt-1";
    errorElement.textContent = message;
    errorElement.setAttribute("role", "alert");

    field.parentNode.appendChild(errorElement);
}

function clearFieldErrors(field) {
    field.classList.remove("field-error");
    field.removeAttribute("aria-invalid");

    const errorMessage = field.parentNode.querySelector(".error-message");
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * Utility Functions
 */
function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 ${getNotificationClasses(
        type
    )}`;
    notification.textContent = message;
    notification.setAttribute("role", "alert");

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.add("opacity-100", "translate-x-0");
    }, 100);

    // Auto remove
    setTimeout(() => {
        notification.classList.add("opacity-0", "translate-x-full");
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

function getNotificationClasses(type) {
    const classes = {
        success: "bg-green-600 text-white",
        error: "bg-red-600 text-white",
        warning: "bg-yellow-600 text-white",
        info: "bg-blue-600 text-white",
    };

    return classes[type] || classes.info;
}

// Export functions for external use
window.AquaLuxe = {
    showNotification,
};
