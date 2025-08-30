# AquaLuxe CSS Architecture

## Overview
This document outlines the CSS architecture for the AquaLuxe WooCommerce child theme. It provides a detailed view of the styling approach, methodology, components, and best practices used in the theme.

## 1. CSS Methodology

### 1.1 BEM (Block Element Modifier)
The AquaLuxe theme uses the BEM methodology for naming CSS classes:
- **Block**: Standalone entity (e.g., `.product-card`)
- **Element**: Part of a block (e.g., `.product-card__title`)
- **Modifier**: Variation of a block or element (e.g., `.product-card--featured`)

### 1.2 ITCSS (Inverted Triangle CSS)
The CSS is organized using the ITCSS architecture:
1. **Settings**: Variables and configuration
2. **Tools**: Mixins and functions
3. **Generic**: Ground-zero styles (resets, box-sizing)
4. **Elements**: Unclassed HTML elements (h1, p, ul, etc.)
5. **Objects**: Design patterns (grids, layout objects)
6. **Components**: Specific UI components
7. **Utilities**: Helpers and overrides

### 1.3 Mobile-First Approach
All styles are written mobile-first, with media queries added for larger breakpoints:
- Small: Base styles (0px - 767px)
- Medium: 768px and up
- Large: 1024px and up
- Extra Large: 1200px and up

## 2. File Structure

```
assets/css/
├── aqualuxe-styles.css     # Main theme styles
├── customizer.css          # Customizer preview styles
├── woocommerce.css         # WooCommerce-specific styles
├── components/             # Individual component styles
│   ├── header.css
│   ├── navigation.css
│   ├── product-card.css
│   ├── cart.css
│   └── footer.css
├── utilities/              # Utility classes
│   ├── spacing.css
│   ├── typography.css
│   └── helpers.css
└── vendor/                 # Third-party styles
    └── normalize.css
```

## 3. CSS Variables (Custom Properties)

### 3.1 Color Variables
```css
:root {
  /* Primary Colors */
  --aqualuxe-primary: #0073e6;
  --aqualuxe-primary-dark: #005cb3;
  --aqualuxe-primary-light: #3399ff;
  
  /* Secondary Colors */
  --aqualuxe-secondary: #00c896;
  --aqualuxe-secondary-dark: #009e60;
  --aqualuxe-secondary-light: #33d6ad;
  
  /* Neutral Colors */
  --aqualuxe-white: #ffffff;
  --aqualuxe-light: #f8f9fa;
  --aqualuxe-gray: #6c757d;
  --aqualuxe-dark: #343a40;
  --aqualuxe-black: #212529;
  
  /* Accent Colors */
  --aqualuxe-accent-1: #ff6b6b;
  --aqualuxe-accent-2: #4ecdc4;
  --aqualuxe-accent-3: #ffd166;
  
  /* Status Colors */
  --aqualuxe-success: #28a745;
  --aqualuxe-warning: #ffc107;
  --aqualuxe-error: #dc3545;
  --aqualuxe-info: #17a2b8;
}
```

### 3.2 Typography Variables
```css
:root {
  /* Font Families */
  --aqualuxe-font-primary: 'Open Sans', sans-serif;
  --aqualuxe-font-secondary: 'Playfair Display', serif;
  
  /* Font Sizes */
  --aqualuxe-font-size-xs: 0.75rem;
  --aqualuxe-font-size-sm: 0.875rem;
  --aqualuxe-font-size-base: 1rem;
  --aqualuxe-font-size-lg: 1.125rem;
  --aqualuxe-font-size-xl: 1.25rem;
  --aqualuxe-font-size-2xl: 1.5rem;
  --aqualuxe-font-size-3xl: 1.875rem;
  --aqualuxe-font-size-4xl: 2.25rem;
  
  /* Line Heights */
  --aqualuxe-line-height-tight: 1.25;
  --aqualuxe-line-height-snug: 1.375;
  --aqualuxe-line-height-normal: 1.5;
  --aqualuxe-line-height-relaxed: 1.625;
  --aqualuxe-line-height-loose: 2;
}
```

### 3.3 Spacing Variables
```css
:root {
  /* Spacing Scale */
  --aqualuxe-space-0: 0;
  --aqualuxe-space-1: 0.25rem;
  --aqualuxe-space-2: 0.5rem;
  --aqualuxe-space-3: 0.75rem;
  --aqualuxe-space-4: 1rem;
  --aqualuxe-space-5: 1.25rem;
  --aqualuxe-space-6: 1.5rem;
  --aqualuxe-space-8: 2rem;
  --aqualuxe-space-10: 2.5rem;
  --aqualuxe-space-12: 3rem;
  --aqualuxe-space-16: 4rem;
  --aqualuxe-space-20: 5rem;
  --aqualuxe-space-24: 6rem;
  --aqualuxe-space-32: 8rem;
}
```

### 3.4 Breakpoint Variables
```css
:root {
  --aqualuxe-breakpoint-sm: 576px;
  --aqualuxe-breakpoint-md: 768px;
  --aqualuxe-breakpoint-lg: 992px;
  --aqualuxe-breakpoint-xl: 1200px;
}
```

## 4. Component Architecture

### 4.1 Header Component
```css
.site-header {
  background-color: var(--aqualuxe-white);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 100;
}

.site-header.sticky {
  position: sticky;
  top: 0;
}

.site-branding {
  display: flex;
  align-items: center;
}

.custom-logo {
  max-height: 60px;
  width: auto;
}
```

### 4.2 Navigation Component
```css
.main-navigation {
  display: flex;
  align-items: center;
}

.main-navigation ul {
  display: flex;
  list-style: none;
}

.main-navigation li {
  position: relative;
}

.main-navigation a {
  display: block;
  padding: var(--aqualuxe-space-3) var(--aqualuxe-space-4);
  text-decoration: none;
  color: var(--aqualuxe-dark);
  transition: color 0.3s ease;
}

.main-navigation a:hover {
  color: var(--aqualuxe-primary);
}

/* Mobile Navigation */
.mobile-navigation {
  display: none;
}

@media (max-width: 768px) {
  .main-navigation {
    display: none;
  }
  
  .mobile-navigation {
    display: block;
  }
}
```

### 4.3 Product Card Component
```css
.product-card {
  background: var(--aqualuxe-white);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.product-card__image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-card__content {
  padding: var(--aqualuxe-space-4);
}

.product-card__title {
  font-size: var(--aqualuxe-font-size-lg);
  font-weight: 600;
  margin-bottom: var(--aqualuxe-space-2);
}

.product-card__price {
  font-size: var(--aqualuxe-font-size-xl);
  font-weight: 700;
  color: var(--aqualuxe-primary);
}

.product-card--featured {
  border: 2px solid var(--aqualuxe-accent-1);
}
```

### 4.4 Button Component
```css
.button {
  display: inline-block;
  padding: var(--aqualuxe-space-3) var(--aqualuxe-space-6);
  background-color: var(--aqualuxe-primary);
  color: var(--aqualuxe-white);
  border: none;
  border-radius: 4px;
  font-size: var(--aqualuxe-font-size-base);
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.button:hover {
  background-color: var(--aqualuxe-primary-dark);
  transform: translateY(-1px);
}

.button:active {
  transform: translateY(0);
}

.button--secondary {
  background-color: var(--aqualuxe-secondary);
}

.button--secondary:hover {
  background-color: var(--aqualuxe-secondary-dark);
}

.button--large {
  padding: var(--aqualuxe-space-4) var(--aqualuxe-space-8);
  font-size: var(--aqualuxe-font-size-lg);
}

.button--small {
  padding: var(--aqualuxe-space-2) var(--aqualuxe-space-4);
  font-size: var(--aqualuxe-font-size-sm);
}

.button--full-width {
  width: 100%;
}
```

### 4.5 Form Component
```css
.form-group {
  margin-bottom: var(--aqualuxe-space-4);
}

.form-label {
  display: block;
  margin-bottom: var(--aqualuxe-space-2);
  font-weight: 600;
  color: var(--aqualuxe-dark);
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: var(--aqualuxe-space-3);
  border: 1px solid #ced4da;
  border-radius: 4px;
  font-size: var(--aqualuxe-font-size-base);
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--aqualuxe-primary);
  box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.1);
}

.form-input--error {
  border-color: var(--aqualuxe-error);
}

.form-error {
  color: var(--aqualuxe-error);
  font-size: var(--aqualuxe-font-size-sm);
  margin-top: var(--aqualuxe-space-2);
}
```

## 5. Utility Classes

### 5.1 Spacing Utilities
```css
/* Margin Utilities */
.m-0 { margin: 0; }
.m-1 { margin: var(--aqualuxe-space-1); }
.m-2 { margin: var(--aqualuxe-space-2); }
.m-3 { margin: var(--aqualuxe-space-3); }
.m-4 { margin: var(--aqualuxe-space-4); }

.mx-auto { margin-left: auto; margin-right: auto; }

/* Padding Utilities */
.p-0 { padding: 0; }
.p-1 { padding: var(--aqualuxe-space-1); }
.p-2 { padding: var(--aqualuxe-space-2); }
.p-3 { padding: var(--aqualuxe-space-3); }
.p-4 { padding: var(--aqualuxe-space-4); }

.px-4 { padding-left: var(--aqualuxe-space-4); padding-right: var(--aqualuxe-space-4); }
.py-4 { padding-top: var(--aqualuxe-space-4); padding-bottom: var(--aqualuxe-space-4); }
```

### 5.2 Typography Utilities
```css
.text-center { text-align: center; }
.text-left { text-align: left; }
.text-right { text-align: right; }

.text-uppercase { text-transform: uppercase; }
.text-lowercase { text-transform: lowercase; }
.text-capitalize { text-transform: capitalize; }

.font-weight-light { font-weight: 300; }
.font-weight-normal { font-weight: 400; }
.font-weight-medium { font-weight: 500; }
.font-weight-semibold { font-weight: 600; }
.font-weight-bold { font-weight: 700; }
```

### 5.3 Display Utilities
```css
.d-none { display: none; }
.d-block { display: block; }
.d-inline { display: inline; }
.d-inline-block { display: inline-block; }
.d-flex { display: flex; }
.d-grid { display: grid; }

@media (min-width: 768px) {
  .md\:d-none { display: none; }
  .md\:d-block { display: block; }
  .md\:d-flex { display: flex; }
}
```

## 6. Responsive Design

### 6.1 Breakpoint Mixins
```css
/* Media Query Mixins */
@media (min-width: 576px) {
  /* Small devices (landscape phones, 576px and up) */
}

@media (min-width: 768px) {
  /* Medium devices (tablets, 768px and up) */
}

@media (min-width: 992px) {
  /* Large devices (desktops, 992px and up) */
}

@media (min-width: 1200px) {
  /* Extra large devices (large desktops, 1200px and up) */
}
```

### 6.2 Responsive Grid System
```css
.grid {
  display: grid;
  gap: var(--aqualuxe-space-4);
}

.grid-cols-1 {
  grid-template-columns: 1fr;
}

@media (min-width: 768px) {
  .md\:grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .md\:grid-cols-3 {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 992px) {
  .lg\:grid-cols-4 {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

## 7. Performance Optimization

### 7.1 Critical CSS
Critical CSS is inlined in the document head for above-the-fold content:
```css
/* Critical CSS for above-the-fold content */
.site-header, .main-navigation, .hero-section {
  /* Styles loaded immediately */
}
```

### 7.2 CSS Minification
Production CSS is minified to reduce file size:
- Remove whitespace and comments
- Shorten color values
- Optimize selectors

### 7.3 Efficient Selectors
Use efficient CSS selectors:
```css
/* Good - Direct descendant */
.product-card > .product-card__title { }

/* Avoid - Complex descendant */
.product-list .product-card .product-card__content .product-card__title { }

/* Good - Class-based */
.product-card__title { }

/* Avoid - Element-based */
div#product-card h3 { }
```

## 8. Accessibility

### 8.1 Focus Styles
```css
:focus {
  outline: 2px solid var(--aqualuxe-primary);
  outline-offset: 2px;
}

/* Custom focus styles for specific components */
.button:focus {
  outline: 2px solid var(--aqualuxe-primary);
  outline-offset: 2px;
}
```

### 8.2 ARIA Attributes
CSS support for ARIA attributes:
```css
[aria-hidden="true"] {
  display: none;
}

[aria-busy="true"] {
  opacity: 0.5;
  pointer-events: none;
}
```

### 8.3 Color Contrast
Ensure sufficient color contrast:
```css
/* WCAG AA compliance */
.text-on-primary {
  color: var(--aqualuxe-white);
}

.text-on-secondary {
  color: var(--aqualuxe-white);
}

/* WCAG AAA compliance for large text */
.text-on-dark {
  color: var(--aqualuxe-white);
}
```

## 9. Customizer Integration

### 9.1 Live Preview Classes
```css
/* Customizer live preview */
.customize-partial-edit-shortcut {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 1000;
}

.customize-control-color {
  /* Color picker styles */
}

.customize-control-range {
  /* Range slider styles */
}
```

### 9.2 Dynamic CSS Generation
PHP generates dynamic CSS based on Customizer settings:
```php
function aqualuxe_dynamic_css() {
  $color_primary = get_theme_mod('color_primary', '#0073e6');
  
  $css = "
    :root {
      --aqualuxe-primary: {$color_primary};
    }
    
    .button {
      background-color: {$color_primary};
    }
  ";
  
  return $css;
}
```

## 10. WooCommerce Integration

### 10.1 Product Archive Styles
```css
.woocommerce-products-header {
  margin-bottom: var(--aqualuxe-space-8);
}

.woocommerce-ordering {
  margin-bottom: var(--aqualuxe-space-4);
}

.products {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: var(--aqualuxe-space-6);
}

.product {
  background: var(--aqualuxe-white);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
```

### 10.2 Single Product Styles
```css
.single-product .product_title {
  font-size: var(--aqualuxe-font-size-3xl);
  margin-bottom: var(--aqualuxe-space-4);
}

.single-product .price {
  font-size: var(--aqualuxe-font-size-2xl);
  font-weight: 700;
  color: var(--aqualuxe-primary);
  margin-bottom: var(--aqualuxe-space-6);
}

.single-product .cart {
  margin-bottom: var(--aqualuxe-space-8);
}

.single-product .woocommerce-tabs {
  margin-top: var(--aqualuxe-space-8);
}
```

### 10.3 Cart and Checkout Styles
```css
.woocommerce-cart-form {
  background: var(--aqualuxe-white);
  padding: var(--aqualuxe-space-6);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.cart_totals {
  background: var(--aqualuxe-white);
  padding: var(--aqualuxe-space-6);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.woocommerce-checkout {
  background: var(--aqualuxe-white);
  padding: var(--aqualuxe-space-6);
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
```

## 11. Best Practices

### 11.1 CSS Coding Standards
- Use consistent indentation (2 spaces)
- Write meaningful class names
- Comment complex code sections
- Organize CSS properties logically
- Use shorthand properties when possible

### 11.2 Performance Guidelines
- Minimize CSS file size
- Use efficient selectors
- Avoid unnecessary specificity
- Leverage CSS custom properties
- Optimize for critical rendering path

### 11.3 Maintainability Guidelines
- Modular component structure
- Clear naming conventions
- Consistent code formatting
- Comprehensive documentation
- Version control with meaningful commits

## Conclusion

The AquaLuxe CSS architecture provides a scalable, maintainable, and performant styling system that follows modern best practices. By using BEM methodology, ITCSS organization, and CSS custom properties, the theme ensures consistency and ease of customization while maintaining high performance standards.

Key features of this architecture include:
1. **Modular Components**: Reusable, self-contained components
2. **Responsive Design**: Mobile-first approach with flexible breakpoints
3. **Accessibility**: WCAG compliant styling with proper focus management
4. **Performance**: Optimized for fast loading and rendering
5. **Customization**: Easy to modify through CSS custom properties
6. **Maintainability**: Clear structure and consistent naming conventions

This architecture supports the theme's goal of providing a premium e-commerce experience while ensuring long-term maintainability and extensibility.