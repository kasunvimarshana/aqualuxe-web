# AquaLuxe WordPress Theme

AquaLuxe is a modern, multitenant, multivendor WordPress theme with a dual-state architecture that works with or without WooCommerce.

## Features

### Core Features
- **Responsive Design**: Fully responsive layout that works on all devices
- **Dual-State Architecture**: Works with or without WooCommerce activated
- **Multivendor Support**: Built-in support for marketplace functionality
- **Multitenant Ready**: Can be used for multiple sites with different configurations
- **Modern Build System**: Uses Laravel Mix for asset compilation
- **Tailwind CSS**: Utility-first CSS framework for rapid UI development
- **Alpine.js**: Lightweight JavaScript framework for modern interactivity
- **Customizer Options**: Extensive theme customization options
- **Block Editor Support**: Full support for the WordPress block editor
- **Accessibility Ready**: Built with accessibility in mind
- **SEO Optimized**: Follows best practices for search engine optimization
- **Performance Focused**: Optimized for speed and performance

### Advanced Features
- **Asset Loading Optimization**: Optimized asset loading for faster page loads
- **Lazy Loading**: Lazy loading for images and other elements
- **Critical CSS Loading**: Critical CSS loading for faster initial render
- **Block Editor Compatibility Styles**: Custom styles for the block editor
- **Custom Block Patterns**: Pre-designed block patterns for easy page building
- **Theme Color Palette Support**: Custom color palette for the block editor
- **Custom Block Styles**: Additional styles for core blocks
- **Multiple Page Templates**: Various page templates for different use cases
- **Enhanced WooCommerce Integration**: Mini-cart, quick view, and more
- **Comprehensive Documentation**: Detailed documentation for users and developers

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Node.js 14.x or higher (for development)

## Installation

1. Download the theme zip file or clone the repository
2. Upload the theme to your WordPress site via the admin panel or FTP
3. Activate the theme through the WordPress admin panel
4. Install recommended plugins when prompted

## Development

### Setup

1. Clone the repository
2. Install dependencies:
   ```
   npm install
   ```

### Available Commands

- `npm run dev`: Compile assets for development
- `npm run watch`: Compile assets and watch for changes
- `npm run hot`: Compile assets with hot reloading
- `npm run prod`: Compile assets for production

### File Structure

```
aqualuxe/
├── assets/
│   ├── dist/           # Compiled assets (generated)
│   └── src/            # Source assets
│       ├── js/         # JavaScript files
│       └── scss/       # SCSS files
├── inc/               # Theme functionality
│   ├── customizer/     # Customizer settings
│   ├── integrations/   # Third-party integrations
│   ├── security/       # Security features
│   ├── seo/            # SEO functionality
│   ├── setup/          # Theme setup
│   └── utils/          # Utility functions
├── languages/         # Translation files
├── templates/         # Template files
├── woocommerce/       # WooCommerce template overrides
├── functions.php      # Theme functions
├── index.php          # Main template file
├── style.css          # Theme information
└── README.md          # Theme documentation
```

## Page Templates

AquaLuxe includes several custom page templates:

### Full Width Template
A template for displaying full width pages without sidebar.

### Landing Page Template
A template for landing pages without header and footer.

### Portfolio Template
A template for displaying portfolio items in a grid layout with filtering options.

### Blog Grid Template
A template for displaying blog posts in a grid layout with category filtering.

### Contact Page Template
A template for contact pages with a built-in contact form and Google Maps integration.

## Block Patterns

AquaLuxe includes several custom block patterns:

### Hero Section
A hero section with heading, text, and button.

### Features Section
A section displaying features in columns.

### Testimonials Section
A section displaying customer testimonials.

### Call to Action
A call to action section with heading and button.

### Pricing Table
A pricing table with multiple columns.

## Customization

### Customizer

AquaLuxe comes with extensive customizer options:

1. Go to Appearance > Customize
2. Explore the various sections:
   - Site Identity
   - Colors
   - Typography
   - Layout Options
   - Header Settings
   - Footer Settings
   - WooCommerce Settings (when active)
   - Performance Options
   - Block Editor Options

### Theme Constants

You can define the following constants in your wp-config.php file:

- `AQUALUXE_USE_WC_STYLES`: Set to true to use default WooCommerce styles
- `AQUALUXE_DISABLE_CUSTOM_COLORS`: Set to true to disable custom color options
- `AQUALUXE_DISABLE_CUSTOM_FONTS`: Set to true to disable custom font options

## WooCommerce Integration

AquaLuxe is designed to work seamlessly with WooCommerce:

- When WooCommerce is active, all e-commerce features are enabled
- When WooCommerce is not active, the theme provides fallback functionality
- Custom product templates and styles
- Enhanced shop features like quick view, wishlist, and AJAX filtering

### Mini Cart
A mini cart that displays in the header with AJAX functionality for adding and removing products.

### Quick View
A quick view modal for products that allows customers to view product details without leaving the current page.

### Custom Product Page Layouts
Multiple product page layouts including standard, full width, and gallery layouts.

### Enhanced Shop Page
Enhanced shop page with advanced filtering options, infinite scroll, and AJAX loading.

### Optimized Checkout
Streamlined checkout process with optimized form fields and layout.

## Hooks and Filters

AquaLuxe provides numerous hooks and filters for extending the theme:

### Actions

- `aqualuxe_before_header`: Executes before the header
- `aqualuxe_after_header`: Executes after the header
- `aqualuxe_before_content`: Executes before the main content
- `aqualuxe_after_content`: Executes after the main content
- `aqualuxe_before_footer`: Executes before the footer
- `aqualuxe_after_footer`: Executes after the footer

### Filters

- `aqualuxe_body_classes`: Modifies body classes
- `aqualuxe_sidebar_id`: Modifies the sidebar ID
- `aqualuxe_comment_form_args`: Modifies comment form arguments
- `aqualuxe_excerpt_length`: Modifies excerpt length
- `aqualuxe_excerpt_more`: Modifies excerpt more text

## Performance Optimization

AquaLuxe includes several performance optimization features:

### Asset Loading Optimization
- Conditional loading of assets based on page type
- Deferred loading of non-critical JavaScript
- Asynchronous loading of CSS files
- Minification of CSS and JavaScript files

### Lazy Loading
- Lazy loading for images using native browser support
- Lazy loading for background images
- Lazy loading for iframes and videos
- Threshold-based loading for better user experience

### Critical CSS
- Inline critical CSS for faster initial render
- Deferred loading of non-critical CSS
- Separate stylesheets for different page types
- Reduced render-blocking resources

### Additional Optimizations
- Optimized Google Fonts loading
- Preconnect and DNS prefetch for external resources
- Optimized SVG icons
- Reduced DOM size and complexity

## License

AquaLuxe is licensed under the GPL v2 or later.

## Credits

- [Laravel Mix](https://laravel-mix.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Normalize.css](https://necolas.github.io/normalize.css/)