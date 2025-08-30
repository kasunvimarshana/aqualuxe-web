# AquaLuxe WordPress + WooCommerce Theme SCSS Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [SCSS Architecture](#scss-architecture)
3. [File Structure](#file-structure)
4. [Theme Variables](#theme-variables)
5. [Component Customization](#component-customization)
6. [WooCommerce Customization](#woocommerce-customization)
7. [Responsive Design](#responsive-design)
8. [Dark Mode](#dark-mode)
9. [Build Process](#build-process)
10. [Best Practices](#best-practices)

## Introduction

This documentation provides a comprehensive guide to the SCSS structure and customization options for the AquaLuxe WordPress + WooCommerce theme. The theme uses a modular SCSS architecture following the 7-1 pattern (with some adaptations) to organize styles in a maintainable and scalable way.

## SCSS Architecture

The AquaLuxe theme follows a modified version of the 7-1 pattern for organizing SCSS files:

1. **abstracts/**: Contains variables, mixins, functions, and placeholders
2. **base/**: Contains reset, typography, and other base styles
3. **components/**: Contains reusable UI components
4. **layout/**: Contains layout-specific styles
5. **pages/**: Contains page-specific styles
6. **themes/**: Contains theme variations (default and dark mode)
7. **vendors/**: Contains third-party styles and integrations
8. **woocommerce/**: Contains WooCommerce-specific styles

## File Structure

```
assets/src/scss/
├── abstracts/
│   ├── _functions.scss
│   ├── _mixins.scss
│   ├── _placeholders.scss
│   └── _variables.scss
├── base/
│   ├── _animations.scss
│   ├── _reset.scss
│   └── _typography.scss
├── components/
│   ├── _accordions.scss
│   ├── _alerts.scss
│   ├── _badges.scss
│   ├── _breadcrumbs.scss
│   ├── _buttons.scss
│   ├── _cards.scss
│   ├── _dropdowns.scss
│   ├── _forms.scss
│   ├── _hero.scss
│   ├── _icons.scss
│   ├── _loaders.scss
│   ├── _modals.scss
│   ├── _newsletter.scss
│   ├── _offcanvas.scss
│   ├── _pagination.scss
│   ├── _search.scss
│   ├── _sliders.scss
│   ├── _social-icons.scss
│   ├── _tables.scss
│   ├── _tabs.scss
│   ├── _testimonials.scss
│   ├── _tooltips.scss
│   └── woocommerce/
│       ├── _quick-view.scss
│       └── _wishlist.scss
├── layout/
│   ├── _footer.scss
│   ├── _grid.scss
│   ├── _header.scss
│   ├── _navigation.scss
│   └── _sidebar.scss
├── pages/
│   ├── _404.scss
│   ├── _about.scss
│   ├── _blog.scss
│   ├── _contact.scss
│   ├── _faq.scss
│   ├── _home.scss
│   ├── _services.scss
│   ├── _single-post.scss
│   └── woocommerce/
│       ├── _account.scss
│       ├── _cart.scss
│       ├── _checkout.scss
│       ├── _order.scss
│       ├── _shop.scss
│       ├── _single-product.scss
│       └── _thankyou.scss
├── themes/
│   ├── _dark-mode.scss
│   └── _default.scss
├── vendors/
│   └── _tailwind.scss
├── admin.scss
├── customizer-controls.scss
├── dark-mode.scss
├── editor.scss
├── main.scss
├── print.scss
└── woocommerce.scss
```

## Theme Variables

The theme uses CSS custom properties (variables) for consistent styling across the theme. These are defined in `themes/_default.scss` and can be overridden for different theme variations.

### Key Variable Categories

#### Colors
```scss
--color-primary: #0072bc;
--color-primary-light: #0090ef;
--color-primary-dark: #005a94;
--color-secondary: #00b2a9;
--color-accent: #f7941d;
--color-success: #10b981;
--color-warning: #f59e0b;
--color-error: #ef4444;
--color-info: #3b82f6;
```

#### Typography
```scss
--font-family-base: 'Poppins', sans-serif;
--font-family-heading: 'Playfair Display', serif;
--font-size-base: 1rem;
--line-height-normal: 1.5;
```

#### Spacing
```scss
--spacing-0: 0;
--spacing-1: 0.25rem;
--spacing-2: 0.5rem;
--spacing-4: 1rem;
--spacing-8: 2rem;
```

#### Borders & Shadows
```scss
--border-radius: 0.25rem;
--shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
--shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
```

## Component Customization

### Buttons

The theme includes several button variations that can be customized:

```scss
// Base button styles
.btn {
  display: inline-block;
  padding: var(--button-padding-y) var(--button-padding-x);
  font-weight: var(--button-font-weight);
  border-radius: var(--button-border-radius);
  transition: var(--button-transition);
}

// Button variations
.btn-primary {
  background-color: var(--color-primary);
  color: var(--color-white);
}

.btn-secondary {
  background-color: var(--color-secondary);
  color: var(--color-white);
}

.btn-outline {
  border: 1px solid var(--color-primary);
  color: var(--color-primary);
  background-color: transparent;
}
```

### Cards

Cards are versatile components used throughout the theme:

```scss
.card {
  background-color: var(--card-background);
  border-radius: var(--card-border-radius);
  box-shadow: var(--card-box-shadow);
  overflow: hidden;
}

.card-body {
  padding: var(--card-padding);
}

.card-title {
  margin-bottom: var(--spacing-2);
}

.card-image img {
  width: 100%;
  height: auto;
  display: block;
}
```

## WooCommerce Customization

The theme includes comprehensive WooCommerce styling that can be customized to match your brand.

### Product Grid

```scss
.products {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: var(--spacing-4);
}

.product {
  position: relative;
  transition: transform 0.3s ease;
  
  &:hover {
    transform: translateY(-5px);
  }
}
```

### Single Product

```scss
.single-product {
  .product-gallery {
    // Product gallery styles
  }
  
  .product-summary {
    // Product summary styles
  }
  
  .variations {
    // Product variations styles
  }
}
```

### Quick View

The quick view feature allows customers to preview products without navigating to the product page:

```scss
.quick-view-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: var(--z-50);
  display: flex;
  align-items: center;
  justify-content: center;
  
  .quick-view-content {
    background-color: var(--color-white);
    border-radius: var(--border-radius-lg);
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
  }
}
```

### Wishlist

The wishlist feature allows customers to save products for later:

```scss
.wishlist-icon {
  position: absolute;
  top: var(--spacing-2);
  right: var(--spacing-2);
  z-index: var(--z-10);
  
  button {
    background-color: var(--color-white);
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    box-shadow: var(--shadow-sm);
    
    &.in-wishlist {
      color: var(--color-error);
    }
  }
}
```

## Responsive Design

The theme uses a mobile-first approach with responsive breakpoints:

```scss
// Breakpoints
$breakpoints: (
  'sm': 640px,
  'md': 768px,
  'lg': 1024px,
  'xl': 1280px,
  '2xl': 1536px
);

// Media query mixin
@mixin media-breakpoint-up($breakpoint) {
  @if map-has-key($breakpoints, $breakpoint) {
    @media (min-width: map-get($breakpoints, $breakpoint)) {
      @content;
    }
  } @else {
    @media (min-width: $breakpoint) {
      @content;
    }
  }
}
```

Usage example:

```scss
.container {
  width: 100%;
  padding: 0 1rem;
  
  @include media-breakpoint-up('md') {
    padding: 0 2rem;
  }
  
  @include media-breakpoint-up('lg') {
    max-width: 1024px;
    margin: 0 auto;
  }
  
  @include media-breakpoint-up('xl') {
    max-width: 1280px;
  }
}
```

## Dark Mode

The theme includes a dark mode variation that can be toggled by users:

```scss
// Dark mode variables in themes/_dark-mode.scss
[data-theme="dark"] {
  --color-background: #121212;
  --color-text: #e5e7eb;
  --color-text-light: #9ca3af;
  --color-border: #374151;
  // Other dark mode variables
}
```

## Build Process

The theme uses Laravel Mix (webpack wrapper) for the build process:

1. Development build: `npm run dev`
2. Production build: `npm run prod`
3. Watch for changes: `npm run watch`

### Key Build Features

- SCSS compilation and minification
- JavaScript bundling and minification
- Autoprefixing for browser compatibility
- Source maps for debugging
- Image optimization
- SVG sprite generation
- Critical CSS extraction

## Best Practices

### 1. Use Variables for Consistency

Always use CSS variables for colors, spacing, and other design tokens to maintain consistency:

```scss
// Good
color: var(--color-primary);
margin-bottom: var(--spacing-4);

// Avoid
color: #0072bc;
margin-bottom: 1rem;
```

### 2. Follow BEM Naming Convention

Use BEM (Block, Element, Modifier) naming convention for CSS classes:

```scss
// Block
.card {
  // Card styles
}

// Element
.card__title {
  // Card title styles
}

// Modifier
.card--featured {
  // Featured card styles
}
```

### 3. Mobile-First Approach

Always start with mobile styles and then add responsive breakpoints for larger screens:

```scss
.product-grid {
  // Mobile styles (default)
  grid-template-columns: 1fr;
  
  // Tablet styles
  @include media-breakpoint-up('md') {
    grid-template-columns: repeat(2, 1fr);
  }
  
  // Desktop styles
  @include media-breakpoint-up('lg') {
    grid-template-columns: repeat(4, 1fr);
  }
}
```

### 4. Avoid Deep Nesting

Limit SCSS nesting to 3 levels to improve maintainability and reduce specificity issues:

```scss
// Good
.accordion {
  &__item {
    margin-bottom: var(--spacing-2);
  }
  
  &__header {
    padding: var(--spacing-2);
  }
}

// Avoid
.accordion {
  &__item {
    margin-bottom: var(--spacing-2);
    
    &__header {
      padding: var(--spacing-2);
      
      &__title {
        font-weight: bold;
        
        &__icon {
          // Too deep!
        }
      }
    }
  }
}
```

### 5. Use Mixins for Repeated Patterns

Create mixins for commonly used patterns to reduce code duplication:

```scss
// Define mixin
@mixin visually-hidden {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}

// Use mixin
.screen-reader-text {
  @include visually-hidden;
}
```