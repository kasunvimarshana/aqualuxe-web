# AquaLuxe Developer Guide

This guide provides detailed information for developers working with the AquaLuxe WordPress theme. It covers the theme's architecture, coding standards, hooks and filters, and customization options.

## Table of Contents

1. [Theme Architecture](#theme-architecture)
2. [Coding Standards](#coding-standards)
3. [Theme Hooks and Filters](#theme-hooks-and-filters)
4. [Customizer API](#customizer-api)
5. [WooCommerce Integration](#woocommerce-integration)
6. [JavaScript Components](#javascript-components)
7. [CSS Architecture](#css-architecture)
8. [Template Structure](#template-structure)
9. [Performance Optimization](#performance-optimization)
10. [Accessibility](#accessibility)

## Theme Architecture

AquaLuxe follows a modular architecture based on SOLID principles:

- **Single Responsibility**: Each file and function has a single responsibility
- **Open/Closed**: Code is open for extension but closed for modification
- **Liskov Substitution**: Child classes can stand in for parent classes
- **Interface Segregation**: Many client-specific interfaces are better than one general-purpose interface
- **Dependency Inversion**: Depend on abstractions, not on concretions

### Directory Structure

```
aqualuxe/
├── assets/               # Compiled and static assets
│   ├── css/             # Compiled CSS files
│   ├── js/              # Compiled JavaScript files
│   ├── images/          # Theme images
│   └── fonts/           # Theme fonts
├── assets/src/          # Source files for compilation
│   ├── css/             # CSS/SCSS source files
│   └── js/              # JavaScript source files
│       └── components/  # JavaScript components
├── inc/                 # Theme functionality
│   ├── admin/           # Admin-specific functionality
│   ├── customizer/      # Theme customizer options
│   ├── helpers/         # Helper functions
│   └── widgets/         # Custom widgets
├── languages/           # Translation files
├── template-parts/      # Reusable template parts
│   ├── content/         # Content templates
│   ├── header/          # Header templates
│   └── footer/          # Footer templates
├── woocommerce/         # WooCommerce template overrides
└── [template files]     # WordPress template files
```

### Core Files

- **functions.php**: The theme's main file that loads all other components
- **style.css**: Contains theme metadata and is used for basic styling
- **index.php**: The main template file
- **header.php**: The header template
- **footer.php**: The footer template
- **sidebar.php**: The sidebar template
- **inc/enqueue.php**: Handles script and style enqueuing
- **inc/customizer/customizer.php**: Customizer settings and controls

## Coding Standards

AquaLuxe follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) with some additional guidelines:

### PHP

- Use namespaces for organization
- Follow PSR-4 autoloading standards
- Use type hints where possible
- Document all functions, classes, and methods with PHPDoc
- Use meaningful variable and function names

### JavaScript

- Follow ESLint rules
- Use ES6+ features
- Organize code into modules
- Document functions with JSDoc
- Use meaningful variable and function names

### CSS

- Follow Tailwind CSS conventions
- Use BEM methodology for custom components
- Organize styles logically
- Use CSS variables for theming

## Theme Hooks and Filters

AquaLuxe provides numerous hooks and filters for extending functionality:

### Action Hooks

- `aqualuxe_before_header`: Fires before the header
- `aqualuxe_after_header`: Fires after the header
- `aqualuxe_before_footer`: Fires before the footer
- `aqualuxe_after_footer`: Fires after the footer
- `aqualuxe_before_content`: Fires before the main content
- `aqualuxe_after_content`: Fires after the main content
- `aqualuxe_sidebar`: Fires inside the sidebar
- `aqualuxe_before_post`: Fires before each post
- `aqualuxe_after_post`: Fires after each post
- `aqualuxe_post_meta`: Fires when displaying post meta
- `aqualuxe_before_shop`: Fires before the shop content
- `aqualuxe_after_shop`: Fires after the shop content

### Filter Hooks

- `aqualuxe_body_classes`: Filters the body classes
- `aqualuxe_post_classes`: Filters the post classes
- `aqualuxe_excerpt_length`: Filters the excerpt length
- `aqualuxe_excerpt_more`: Filters the excerpt "more" text
- `aqualuxe_comment_form_args`: Filters the comment form arguments
- `aqualuxe_sidebar_position`: Filters the sidebar position
- `aqualuxe_page_layout`: Filters the page layout
- `aqualuxe_google_fonts`: Filters the Google Fonts to load

### Example Usage

```php
// Add a class to the body
add_filter('aqualuxe_body_classes', function($classes) {
    $classes[] = 'custom-class';
    return $classes;
});

// Add content before the header
add_action('aqualuxe_before_header', function() {
    echo '<div class="announcement-bar">Special offer today!</div>';
});
```

## Customizer API

AquaLuxe uses the WordPress Customizer API for theme options. The customizer is organized into the following panels and sections:

### Panels

- **General**: Basic theme settings
- **Header**: Header layout and options
- **Footer**: Footer layout and options
- **Typography**: Font settings
- **Colors**: Color settings
- **Blog**: Blog layout and options
- **WooCommerce**: WooCommerce settings

### Sections

- **Site Identity**: Logo, site title, and tagline
- **Header Layout**: Header layout options
- **Header Elements**: Header elements options
- **Footer Layout**: Footer layout options
- **Footer Elements**: Footer elements options
- **Typography Settings**: Font family and size settings
- **Color Settings**: Color scheme settings
- **Blog Layout**: Blog layout options
- **Single Post**: Single post options
- **WooCommerce Layout**: WooCommerce layout options
- **Product Archives**: Product archive options
- **Single Product**: Single product options

### Example: Adding a Custom Setting

```php
add_action('customize_register', function($wp_customize) {
    // Add a section
    $wp_customize->add_section('custom_section', [
        'title' => __('Custom Section', 'aqualuxe'),
        'priority' => 30,
    ]);
    
    // Add a setting
    $wp_customize->add_setting('custom_setting', [
        'default' => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    // Add a control
    $wp_customize->add_control('custom_setting', [
        'label' => __('Custom Setting', 'aqualuxe'),
        'section' => 'custom_section',
        'type' => 'text',
    ]);
});
```

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides custom templates and functionality:

### Template Overrides

AquaLuxe overrides the following WooCommerce templates:

- `content-product.php`: Product card template
- `single-product.php`: Single product template
- `archive-product.php`: Product archive template
- `cart/cart.php`: Cart template
- `checkout/form-checkout.php`: Checkout form template

### Custom WooCommerce Functionality

- **Quick View**: Product quick view modal
- **Wishlist**: Product wishlist functionality
- **Product Comparison**: Product comparison functionality
- **Advanced Filtering**: AJAX product filtering
- **Custom Product Badges**: Sale, new, featured badges

### WooCommerce Hooks

AquaLuxe adds the following WooCommerce hooks:

- `aqualuxe_before_shop_loop`: Fires before the shop loop
- `aqualuxe_after_shop_loop`: Fires after the shop loop
- `aqualuxe_before_product_card`: Fires before the product card
- `aqualuxe_after_product_card`: Fires after the product card
- `aqualuxe_product_card_badges`: Fires when displaying product badges
- `aqualuxe_product_card_actions`: Fires when displaying product actions

## JavaScript Components

AquaLuxe uses a modular JavaScript architecture with the following components:

### Core Components

- **navigation.js**: Handles navigation menu functionality
- **dark-mode.js**: Handles dark mode toggle functionality
- **animations.js**: Handles scroll animations
- **utils.js**: Utility functions

### WooCommerce Components

- **woocommerce.js**: General WooCommerce functionality
- **quick-view.js**: Product quick view functionality
- **wishlist.js**: Product wishlist functionality
- **product-comparison.js**: Product comparison functionality

### Example: Using JavaScript Components

```javascript
// Import components
import Navigation from './components/navigation';
import DarkMode from './components/dark-mode';

// Initialize components
document.addEventListener('DOMContentLoaded', function() {
    Navigation.init();
    DarkMode.init();
});
```

## CSS Architecture

AquaLuxe uses Tailwind CSS for styling with a custom configuration:

### Tailwind Configuration

The Tailwind configuration is located in `tailwind.config.js` and includes:

- Custom colors
- Custom fonts
- Custom spacing
- Custom breakpoints
- Dark mode support

### Custom Components

Custom components are defined in the `assets/src/css/main.css` file using the `@layer components` directive:

```css
@layer components {
  .btn {
    @apply inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md font-medium transition-colors duration-200;
  }
  
  .btn-primary {
    @apply bg-primary text-white hover:bg-primary-dark;
  }
}
```

### Utility Classes

Custom utility classes are defined in the `assets/src/css/main.css` file using the `@layer utilities` directive:

```css
@layer utilities {
  .text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
}
```

## Template Structure

AquaLuxe uses a modular template structure with template parts:

### Main Templates

- **index.php**: The main template file
- **single.php**: Single post template
- **page.php**: Page template
- **archive.php**: Archive template
- **search.php**: Search results template
- **404.php**: 404 template

### Template Parts

- **template-parts/content/content.php**: Default content template
- **template-parts/content/content-single.php**: Single post content template
- **template-parts/content/content-page.php**: Page content template
- **template-parts/content/content-none.php**: No content template
- **template-parts/header/header-default.php**: Default header template
- **template-parts/header/header-centered.php**: Centered header template
- **template-parts/header/header-transparent.php**: Transparent header template
- **template-parts/header/header-minimal.php**: Minimal header template

### Example: Using Template Parts

```php
// Get the header template part
get_template_part('template-parts/header/header', get_theme_mod('header_layout', 'default'));

// Get the content template part
get_template_part('template-parts/content/content', get_post_type());
```

## Performance Optimization

AquaLuxe includes several performance optimizations:

### Asset Optimization

- **CSS Minification**: CSS files are minified in production
- **JavaScript Minification**: JavaScript files are minified in production
- **Critical CSS**: Critical CSS is inlined in the head
- **Deferred JavaScript**: Non-critical JavaScript is deferred
- **Preloading**: Key assets are preloaded

### Image Optimization

- **Lazy Loading**: Images are lazy loaded
- **Responsive Images**: Images use srcset for responsive loading
- **WebP Support**: WebP images are used when supported

### Caching

- **Browser Caching**: Assets are cached in the browser
- **Resource Hints**: Preconnect and dns-prefetch are used for external resources

## Accessibility

AquaLuxe is built with accessibility in mind and follows WCAG guidelines:

### Keyboard Navigation

- All interactive elements are keyboard accessible
- Focus states are clearly visible
- Skip links are provided for keyboard users

### Screen Readers

- ARIA landmarks are used for page structure
- ARIA attributes are used for interactive elements
- Screen reader text is provided for visual elements

### Color Contrast

- All text meets WCAG AA contrast requirements
- Color is not used as the only means of conveying information

### Forms

- Form fields have associated labels
- Form errors are clearly indicated
- Required fields are marked