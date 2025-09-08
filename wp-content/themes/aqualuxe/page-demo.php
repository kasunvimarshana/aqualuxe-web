<?php
/**
 * Theme Architecture Demo Page
 * 
 * Demonstrates the new AquaLuxe theme architecture with components
 * 
 * @package AquaLuxe
 * @since 1.2.0
 */

get_header();
?>

<main id="primary" class="site-main" role="main">
    <!-- Hero Section with Glass Morphism -->
    <section class="hero-section py-24 bg-gradient text-white position-relative overflow-hidden" data-animate="fade-in">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-animate="slide-right">
                    <h1 class="display-1 fw-bold mb-4">
                        Welcome to <span class="text-gradient">AquaLuxe</span>
                    </h1>
                    <p class="fs-4 mb-6 lh-lg">
                        Experience our ocean-inspired design system with modern architecture, 
                        accessibility features, and stunning glass morphism effects.
                    </p>
                    <div class="d-flex gap-4 flex-wrap">
                        <button class="btn btn-primary btn-lg" data-hover="button">
                            <span class="icon">🌊</span>
                            Explore Features
                        </button>
                        <button class="btn btn-outline btn-lg">
                            <span class="icon">📖</span>
                            Documentation
                        </button>
                    </div>
                </div>
                <div class="col-lg-6" data-animate="slide-left">
                    <div class="glass-morphism p-6 rounded-4 float">
                        <h3 class="mb-4">Architecture Highlights</h3>
                        <ul class="list-unstyled">
                            <li class="mb-3">🎯 <strong>SOLID Principles</strong> - Clean, maintainable code</li>
                            <li class="mb-3">♿ <strong>WCAG 2.1 AA</strong> - Full accessibility support</li>
                            <li class="mb-3">🎨 <strong>Glass Morphism</strong> - Modern UI design</li>
                            <li class="mb-3">🌙 <strong>Dark Mode</strong> - Automatic theme switching</li>
                            <li class="mb-3">🎬 <strong>GSAP Animations</strong> - Smooth interactions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave decoration -->
        <div class="wave-decoration"></div>
    </section>

    <!-- Component Showcase -->
    <section class="py-20" id="components">
        <div class="container">
            <div class="text-center mb-16" data-animate="fade-in">
                <h2 class="display-2 fw-bold mb-4">Component Library</h2>
                <p class="fs-5 text-muted">Explore our comprehensive set of reusable components</p>
            </div>

            <!-- Button Components -->
            <div class="mb-16" data-stagger="slide-up" data-stagger-delay="0.1">
                <h3 class="fs-2 fw-semibold mb-6">Buttons</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card glass-morphism">
                            <div class="card-body">
                                <h4 class="card-title">Primary Buttons</h4>
                                <div class="d-flex gap-3 flex-wrap mb-4">
                                    <button class="btn btn-primary">Primary</button>
                                    <button class="btn btn-secondary">Secondary</button>
                                    <button class="btn btn-success">Success</button>
                                    <button class="btn btn-warning">Warning</button>
                                    <button class="btn btn-danger">Danger</button>
                                </div>
                                <div class="d-flex gap-3 flex-wrap">
                                    <button class="btn btn-primary btn-sm">Small</button>
                                    <button class="btn btn-primary">Medium</button>
                                    <button class="btn btn-primary btn-lg">Large</button>
                                    <button class="btn btn-primary btn-xl">Extra Large</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card glass-morphism">
                            <div class="card-body">
                                <h4 class="card-title">Special Effects</h4>
                                <div class="d-flex gap-3 flex-wrap mb-4">
                                    <button class="btn btn-outline">Outline</button>
                                    <button class="btn btn-ghost">Ghost</button>
                                    <button class="btn btn-glass">Glass</button>
                                </div>
                                <div class="d-flex gap-3 flex-wrap">
                                    <button class="btn btn-primary btn-wave">Wave</button>
                                    <button class="btn btn-secondary btn-pulse">Pulse</button>
                                    <button class="btn btn-success btn-gradient-animate">Gradient</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Components -->
            <div class="mb-16" data-stagger="slide-up" data-stagger-delay="0.1">
                <h3 class="fs-2 fw-semibold mb-6">Forms</h3>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Contact Form Demo</h4>
                            </div>
                            <div class="card-body">
                                <form class="form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="firstName" class="form-label">
                                                First Name <span class="required-indicator">*</span>
                                            </label>
                                            <input type="text" id="firstName" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="lastName" class="form-label">
                                                Last Name <span class="required-indicator">*</span>
                                            </label>
                                            <input type="text" id="lastName" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            Email Address <span class="required-indicator">*</span>
                                        </label>
                                        <input type="email" id="email" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="service" class="form-label">Service Interest</label>
                                        <select id="service" class="form-control form-select">
                                            <option value="">Choose a service...</option>
                                            <option value="web-design">Web Design</option>
                                            <option value="development">Development</option>
                                            <option value="consulting">Consulting</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea id="message" class="form-control form-control-textarea" rows="4" placeholder="Tell us about your project..."></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" id="newsletter" class="form-check-input">
                                            <label for="newsletter" class="form-check-label">
                                                Subscribe to our newsletter
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary">
                                            Send Message
                                        </button>
                                        <button type="reset" class="btn btn-outline">
                                            Reset Form
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card glass-morphism">
                            <div class="card-body">
                                <h4 class="card-title">Form Features</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2">✅ Accessibility labels</li>
                                    <li class="mb-2">✅ Validation states</li>
                                    <li class="mb-2">✅ Glass morphism</li>
                                    <li class="mb-2">✅ Responsive design</li>
                                    <li class="mb-2">✅ Dark mode support</li>
                                </ul>
                                
                                <div class="mt-4">
                                    <h5>Theme Toggle</h5>
                                    <div class="form-check form-switch">
                                        <input type="checkbox" id="darkModeToggle" class="form-check-input">
                                        <label for="darkModeToggle" class="form-check-label">
                                            Enable Dark Mode
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Components -->
            <div class="mb-16" data-stagger="slide-up" data-stagger-delay="0.1">
                <h3 class="fs-2 fw-semibold mb-6">Cards</h3>
                <div class="card-deck">
                    <!-- Feature Card -->
                    <div class="card feature-card hover-lift">
                        <div class="card-body text-center">
                            <div class="feature-icon mb-4">🎨</div>
                            <h4 class="feature-title">Design System</h4>
                            <p class="feature-description">
                                Ocean-inspired design with consistent spacing, colors, and typography.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Glass Card -->
                    <div class="card glass-morphism hover-lift">
                        <div class="card-body">
                            <h4 class="card-title">Glass Morphism</h4>
                            <p class="card-text">
                                Beautiful translucent effects with backdrop blur for modern UI.
                            </p>
                            <button class="btn btn-outline btn-sm">Learn More</button>
                        </div>
                    </div>
                    
                    <!-- Product Card -->
                    <div class="product-card card hover-lift">
                        <div class="product-image">
                            <div class="bg-gradient" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-white fs-1">🌊</span>
                            </div>
                            <div class="product-badge">New</div>
                        </div>
                        <div class="product-info">
                            <h4 class="product-title">Ocean Theme</h4>
                            <div class="product-price">
                                <span class="current-price">$49</span>
                                <span class="original-price">$69</span>
                                <span class="discount">30% OFF</span>
                            </div>
                            <div class="product-rating">
                                <div class="stars">⭐⭐⭐⭐⭐</div>
                                <span class="rating-text">(24 reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Animation Showcase -->
            <div class="mb-16">
                <h3 class="fs-2 fw-semibold mb-6">Animations</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card" data-animate="scale-in">
                            <div class="card-body">
                                <h4 class="card-title">Scroll Animations</h4>
                                <p class="card-text mb-4">
                                    Elements animate as they enter the viewport with respect for reduced motion preferences.
                                </p>
                                <div class="d-flex gap-2 flex-wrap">
                                    <span class="badge bg-primary">fade-in</span>
                                    <span class="badge bg-secondary">slide-up</span>
                                    <span class="badge bg-success">scale-in</span>
                                    <span class="badge bg-info">rotate-in</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card glass-morphism float">
                            <div class="card-body">
                                <h4 class="card-title">Interactive Effects</h4>
                                <p class="card-text mb-4">
                                    Hover effects, floating animations, and smooth transitions enhance user experience.
                                </p>
                                <button class="btn btn-primary pulse-glow">
                                    Pulse Effect
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Architecture Info -->
            <div class="text-center" data-animate="fade-in">
                <div class="card card-lg ocean-gradient">
                    <div class="card-body">
                        <h3 class="card-title text-white mb-4">Built with Modern Architecture</h3>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <div class="text-white-75">
                                    <h4 class="fs-1 fw-bold">4</h4>
                                    <p class="mb-0">Core JS Modules</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-white-75">
                                    <h4 class="fs-1 fw-bold">50+</h4>
                                    <p class="mb-0">SCSS Components</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-white-75">
                                    <h4 class="fs-1 fw-bold">100%</h4>
                                    <p class="mb-0">WCAG 2.1 AA</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-white-75">
                                    <h4 class="fs-1 fw-bold">∞</h4>
                                    <p class="mb-0">Possibilities</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Initialize theme functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('change', function() {
            if (window.AquaLuxeApp && window.AquaLuxeApp.getModule('DarkMode')) {
                const darkMode = window.AquaLuxeApp.getModule('DarkMode');
                darkMode.setTheme(this.checked ? 'dark' : 'light');
            } else {
                // Fallback for direct theme toggling
                document.documentElement.setAttribute('data-theme', this.checked ? 'dark' : 'light');
                document.body.classList.toggle('theme-dark', this.checked);
                document.body.classList.toggle('theme-light', !this.checked);
            }
        });
        
        // Set initial state
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
        darkModeToggle.checked = currentTheme === 'dark';
    }
    
    // Form validation demo
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simple validation demo
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('invalid');
                    isValid = false;
                } else {
                    field.classList.remove('invalid');
                    field.classList.add('valid');
                }
            });
            
            if (isValid) {
                alert('✅ Form validation passed! (Demo only - form not actually submitted)');
                
                // Announce success to screen readers
                if (window.AquaLuxeApp && window.AquaLuxeApp.getModule('Accessibility')) {
                    const accessibility = window.AquaLuxeApp.getModule('Accessibility');
                    accessibility.announce('Form submitted successfully');
                }
            } else {
                alert('❌ Please fill in all required fields');
                
                // Announce errors to screen readers
                if (window.AquaLuxeApp && window.AquaLuxeApp.getModule('Accessibility')) {
                    const accessibility = window.AquaLuxeApp.getModule('Accessibility');
                    accessibility.announce('Form has validation errors. Please check required fields.');
                }
            }
        });
    }
});
</script>

<?php get_footer(); ?>
