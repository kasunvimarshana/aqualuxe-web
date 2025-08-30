# AquaLuxe Theme Developer Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Theme Architecture](#theme-architecture)
3. [Development Environment](#development-environment)
4. [Build System](#build-system)
5. [Theme Structure](#theme-structure)
6. [Template Hierarchy](#template-hierarchy)
7. [Hooks & Filters](#hooks--filters)
8. [Customizer API](#customizer-api)
9. [Custom Blocks](#custom-blocks)
10. [WooCommerce Integration](#woocommerce-integration)
11. [Performance Optimization](#performance-optimization)
12. [Accessibility](#accessibility)
13. [Internationalization](#internationalization)
14. [Testing](#testing)
15. [Deployment](#deployment)
16. [Contributing](#contributing)

## Introduction

AquaLuxe is a premium WordPress theme built with modern development practices. This guide is intended for developers who want to customize or extend the theme's functionality.

### Key Technical Features

- **Component-Based Architecture**: Modular design for easy maintenance and extension
- **Modern Build Pipeline**: Laravel Mix (Webpack) for asset compilation
- **Tailwind CSS**: Utility-first CSS framework for rapid UI development
- **Alpine.js**: Lightweight JavaScript framework for interactive components
- **Custom Gutenberg Blocks**: Extend the WordPress editor with custom blocks
- **WooCommerce Integration**: Deep integration with WooCommerce for e-commerce
- **Dual-State Architecture**: Graceful fallback for WooCommerce functionality
- **Performance Optimization**: Built-in tools for optimizing assets and performance
- **Accessibility**: WCAG 2.1 compliant with extensive accessibility features

## Theme Architecture

AquaLuxe follows a modular, component-based architecture that separates concerns and makes the codebase easier to maintain and extend.

### Core Principles

1. **Separation of Concerns**: Each component handles a specific functionality
2. **DRY (Don't Repeat Yourself)**: Reusable components and functions
3. **Progressive Enhancement**: Core functionality works without JavaScript
4. **Mobile-First**: Designed for mobile devices first, then enhanced for larger screens
5. **Performance-First**: Optimized for speed and efficiency
6. **Accessibility-First**: Built with accessibility in mind from the ground up

### File Organization

The theme follows a logical file organization that groups related files together:

```
aqualuxe/
├── assets/           # Compiled assets (CSS, JS, images)
├── bin/              # Build and utility scripts
├── docs/             # Documentation
├── inc/              # PHP includes
│   ├── assets/       # Asset management
│   ├── blocks/       # Custom Gutenberg blocks
│   ├── core/         # Core functionality
│   ├── customizer/   # Customizer settings
│   ├── hooks/        # Action and filter hooks
│   ├── integrations/ # Third-party integrations
│   ├── performance/  # Performance optimizations
│   ├── security/     # Security hardening
│   ├── seo/          # SEO optimizations
│   ├── setup/        # Theme setup
│   └── utils/        # Utility functions
├── languages/        # Translation files
├── src/              # Source files
│   ├── css/          # CSS/SCSS source files
│   ├── js/           # JavaScript source files
│   ├── img/          # Image source files
│   └── fonts/        # Font files
├── template-parts/   # Template parts
│   ├── content/      # Content template parts
│   ├── footer/       # Footer template parts
│   ├── header/       # Header template parts
│   ├── navigation/   # Navigation template parts
│   └── woocommerce/  # WooCommerce template parts
├── templates/        # Page templates
└── woocommerce/      # WooCommerce template overrides
```

## Development Environment

### Requirements

- Node.js 16.x or higher
- npm 8.x or higher
- PHP 7.4 or higher
- WordPress 5.8 or higher
- WooCommerce 6.0 or higher (if using e-commerce features)
- Composer (for PHP dependencies)

### Setup

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/example/aqualuxe.git
   cd aqualuxe
   ```

2. **Install Dependencies**:
   ```bash
   npm install
   composer install
   ```

3. **Configure Local Environment**:
   - Set up a local WordPress installation
   - Symlink or copy the theme to the WordPress themes directory
   - Activate the theme in WordPress admin

4. **Start Development Server**:
   ```bash
   npm run watch
   ```

### Development Workflow

1. Make changes to source files in the `src` directory
2. The build system will automatically compile assets and refresh the browser
3. Test changes in the browser
4. Commit changes to version control

## Build System

AquaLuxe uses Laravel Mix (a wrapper around Webpack) for asset compilation and optimization.

### Available Commands

- `npm run dev`: Compile assets for development
- `npm run watch`: Compile assets and watch for changes
- `npm run hot`: Compile assets with hot module replacement
- `npm run production`: Compile and optimize assets for production
- `npm run optimize`: Run additional optimization on compiled assets
- `npm run build`: Run production build and optimization
- `npm run lint`: Run linting on CSS, JS, and PHP files
- `npm run format`: Format CSS and JS files
- `npm run pot`: Generate translation template
- `npm run zip`: Create a distribution zip file
- `npm run test`: Run tests

### Configuration Files

- `webpack.mix.js`: Laravel Mix configuration
- `tailwind.config.js`: Tailwind CSS configuration
- `postcss.config.js`: PostCSS configuration
- `package.json`: npm dependencies and scripts
- `composer.json`: PHP dependencies

### Asset Pipeline

1. **CSS Processing**:
   - PostCSS processes CSS files
   - Tailwind CSS generates utility classes
   - Autoprefixer adds vendor prefixes
   - CSS is minified in production

2. **JavaScript Processing**:
   - Babel transpiles modern JavaScript
   - Modules are bundled
   - Code is minified in production

3. **Image Processing**:
   - Images are optimized
   - WebP versions are generated

4. **Font Processing**:
   - Fonts are copied to the assets directory
   - Font subsetting is applied in production

## Theme Structure

### Core Files

- `style.css`: Theme metadata and main stylesheet
- `functions.php`: Theme functions and includes
- `index.php`: Main template file
- `header.php`: Header template
- `footer.php`: Footer template
- `sidebar.php`: Sidebar template
- `single.php`: Single post template
- `page.php`: Page template
- `archive.php`: Archive template
- `search.php`: Search results template
- `404.php`: 404 error template
- `comments.php`: Comments template

### Template Parts

Template parts are reusable components that can be included in multiple templates:

- `template-parts/header/site-header.php`: Main header component
- `template-parts/footer/site-footer.php`: Main footer component
- `template-parts/content/content.php`: Default content component
- `template-parts/content/content-page.php`: Page content component
- `template-parts/content/content-single.php`: Single post content component
- `template-parts/content/content-none.php`: No content found component
- `template-parts/navigation/navigation-main.php`: Main navigation component
- `template-parts/navigation/navigation-post.php`: Post navigation component
- `template-parts/navigation/pagination.php`: Pagination component

### Include Files

Include files contain PHP functions and classes organized by functionality:

- `inc/core/i18n.php`: Internationalization functions
- `inc/core/accessibility.php`: Accessibility functions
- `inc/setup/theme-setup.php`: Theme setup functions
- `inc/assets/enqueue.php`: Asset enqueuing functions
- `inc/security/hardening.php`: Security hardening functions
- `inc/seo/schema.php`: Schema.org markup functions
- `inc/customizer/register.php`: Customizer registration
- `inc/hooks/hooks.php`: Action and filter hooks
- `inc/utils/template-tags.php`: Template tag functions
- `inc/blocks/class-aqualuxe-blocks.php`: Custom blocks registration

## Template Hierarchy

AquaLuxe follows the WordPress template hierarchy with some enhancements:

### Standard Templates

- `index.php`: Default template
- `single.php`: Single post template
- `page.php`: Page template
- `archive.php`: Archive template
- `search.php`: Search results template
- `404.php`: 404 error template

### Custom Templates

- `templates/template-full-width.php`: Full width template
- `templates/template-landing-page.php`: Landing page template
- `templates/template-sidebar-left.php`: Left sidebar template
- `templates/template-sidebar-right.php`: Right sidebar template

### WooCommerce Templates

- `woocommerce.php`: Main WooCommerce template
- `woocommerce/` directory: WooCommerce template overrides

## Hooks & Filters

AquaLuxe provides a comprehensive set of hooks and filters to extend and customize the theme's functionality.

### Action Hooks

- `aqualuxe_before_header`: Before the header
- `aqualuxe_after_header`: After the header
- `aqualuxe_before_content`: Before the main content
- `aqualuxe_after_content`: After the main content
- `aqualuxe_before_footer`: Before the footer
- `aqualuxe_after_footer`: After the footer
- `aqualuxe_before_sidebar`: Before the sidebar
- `aqualuxe_after_sidebar`: After the sidebar
- `aqualuxe_before_post`: Before each post
- `aqualuxe_after_post`: After each post
- `aqualuxe_before_post_content`: Before the post content
- `aqualuxe_after_post_content`: After the post content
- `aqualuxe_before_comments`: Before the comments
- `aqualuxe_after_comments`: After the comments

### Filter Hooks

- `aqualuxe_body_classes`: Filter body classes
- `aqualuxe_post_classes`: Filter post classes
- `aqualuxe_comment_classes`: Filter comment classes
- `aqualuxe_excerpt_length`: Filter excerpt length
- `aqualuxe_excerpt_more`: Filter excerpt more text
- `aqualuxe_content_width`: Filter content width
- `aqualuxe_sidebar_position`: Filter sidebar position
- `aqualuxe_header_layout`: Filter header layout
- `aqualuxe_footer_layout`: Filter footer layout
- `aqualuxe_google_fonts`: Filter Google Fonts
- `aqualuxe_custom_css`: Filter custom CSS

### Example Usage

```php
// Add a custom body class
function my_custom_body_class( $classes ) {
    $classes[] = 'my-custom-class';
    return $classes;
}
add_filter( 'aqualuxe_body_classes', 'my_custom_body_class' );

// Add content before the header
function my_custom_header_content() {
    echo '<div class="announcement-bar">Special offer: Free shipping on all orders!</div>';
}
add_action( 'aqualuxe_before_header', 'my_custom_header_content' );
```

## Customizer API

AquaLuxe uses the WordPress Customizer API to provide theme options. The Customizer settings are organized into panels and sections for better user experience.

### Customizer Structure

- **Site Identity**: Logo, site title, tagline, site icon
- **Colors**: Primary, secondary, accent, text, background colors
- **Typography**: Body font, heading font, font sizes
- **Layout Options**: Container width, sidebar position, header layout, footer layout
- **Header Options**: Sticky header, search icon, dark mode toggle, header actions
- **Footer Options**: Footer text, footer widgets, back to top button
- **Blog Options**: Blog layout, featured image, post meta, related posts, author bio
- **WooCommerce Options**: Shop layout, product columns, products per page, product details
- **Performance Options**: Lazy loading, preconnect, critical CSS, asset optimization
- **Advanced Options**: Custom CSS, custom JavaScript, header code, footer code

### Adding Custom Customizer Settings

```php
/**
 * Add custom Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function my_custom_customizer_settings( $wp_customize ) {
    // Add a new section
    $wp_customize->add_section( 'my_custom_section', array(
        'title'    => __( 'My Custom Section', 'my-textdomain' ),
        'priority' => 160,
    ) );
    
    // Add a setting
    $wp_customize->add_setting( 'my_custom_setting', array(
        'default'           => 'default_value',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    
    // Add a control
    $wp_customize->add_control( 'my_custom_setting', array(
        'label'    => __( 'My Custom Setting', 'my-textdomain' ),
        'section'  => 'my_custom_section',
        'type'     => 'text',
        'priority' => 10,
    ) );
}
add_action( 'customize_register', 'my_custom_customizer_settings' );
```

### Using Customizer Settings

```php
// Get a Customizer setting
$my_setting = get_theme_mod( 'my_custom_setting', 'default_value' );

// Use the setting in your template
echo esc_html( $my_setting );
```

## Custom Blocks

AquaLuxe includes custom Gutenberg blocks to enhance the content editing experience. These blocks are built using the WordPress Block API and React.

### Block Registration

Custom blocks are registered in `inc/blocks/class-aqualuxe-blocks.php`:

```php
/**
 * Register custom blocks
 */
public function register_blocks() {
    // Check if Gutenberg is active
    if ( ! function_exists( 'register_block_type' ) ) {
        return;
    }

    // Register blocks
    register_block_type(
        'aqualuxe/feature-box',
        array(
            'editor_script' => 'aqualuxe-editor-script',
            'editor_style'  => 'aqualuxe-editor-style',
            'style'         => 'aqualuxe-style',
        )
    );
}
```

### Block Implementation

Custom blocks are implemented in `src/js/blocks/`:

```javascript
// src/js/blocks/feature-box.js
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { 
    RichText, 
    InspectorControls, 
    ColorPalette 
} from '@wordpress/block-editor';
import { 
    PanelBody, 
    SelectControl 
} from '@wordpress/components';

// Register the block
registerBlockType('aqualuxe/feature-box', {
    title: __('Feature Box', 'aqualuxe'),
    description: __('Display a feature box with icon, title, and description.', 'aqualuxe'),
    category: 'aqualuxe',
    icon: 'star-filled',
    attributes: {
        // Block attributes
    },
    edit: ({ attributes, setAttributes }) => {
        // Edit component
    },
    save: ({ attributes }) => {
        // Save component
    },
});
```

### Creating Custom Blocks

To create a new custom block:

1. Create a new JavaScript file in `src/js/blocks/`
2. Register the block using `registerBlockType`
3. Implement the edit and save components
4. Import the block in `src/js/blocks/index.js`
5. Register the block in `inc/blocks/class-aqualuxe-blocks.php`

## WooCommerce Integration

AquaLuxe includes deep integration with WooCommerce for e-commerce functionality.

### Template Overrides

WooCommerce templates are overridden in the `woocommerce/` directory:

```
woocommerce/
├── archive-product.php
├── content-product.php
├── content-single-product.php
├── single-product.php
├── cart/
├── checkout/
├── emails/
├── loop/
├── myaccount/
└── single-product/
```

### Custom WooCommerce Functionality

Custom WooCommerce functionality is implemented in `inc/integrations/woocommerce.php`:

```php
/**
 * Setup WooCommerce integration
 */
function aqualuxe_woocommerce_setup() {
    // Add theme support for WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    
    // Remove default WooCommerce styles
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
    
    // Add custom WooCommerce styles
    add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );
    
    // Add custom WooCommerce hooks
    add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_cart_fragments' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );
```

### Dual-State Architecture

AquaLuxe uses a dual-state architecture for WooCommerce integration, which means the theme works with or without WooCommerce activated:

```php
// Check if WooCommerce is active
if ( class_exists( 'WooCommerce' ) ) {
    // WooCommerce is active, load integration
    require_once AQUALUXE_DIR . 'inc/integrations/woocommerce.php';
} else {
    // WooCommerce is not active, load fallback
    require_once AQUALUXE_DIR . 'inc/integrations/woocommerce-fallback.php';
}
```

## Performance Optimization

AquaLuxe includes several performance optimization features to ensure fast loading times.

### Asset Optimization

Assets are optimized using the `bin/optimize-assets.js` script:

```javascript
// Optimize images
async function optimizeImages() {
    // Image optimization code
}

// Generate critical CSS
async function generateCriticalCSS() {
    // Critical CSS generation code
}

// Create asset manifest
async function createAssetManifest() {
    // Asset manifest creation code
}
```

### Lazy Loading

Images and iframes are lazy loaded using the `loading="lazy"` attribute:

```php
/**
 * Add lazy loading attribute to images
 *
 * @param string $html Image HTML.
 * @return string Modified image HTML.
 */
function aqualuxe_lazy_load_images( $html ) {
    if ( strpos( $html, 'loading=' ) === false ) {
        $html = str_replace( '<img', '<img loading="lazy"', $html );
    }
    return $html;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images' );
```

### Preloading & Prefetching

Critical resources are preloaded and likely navigation targets are prefetched:

```php
/**
 * Add resource hints
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        // Add preconnect for Google Fonts
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );
```

### Critical CSS

Critical CSS is generated for different templates and inlined in the head:

```php
/**
 * Add critical CSS
 */
function aqualuxe_add_critical_css() {
    $template = get_template();
    $critical_css_file = get_template_directory() . '/assets/css/critical/' . $template . '.css';
    
    if ( file_exists( $critical_css_file ) ) {
        $critical_css = file_get_contents( $critical_css_file );
        echo '<style id="critical-css">' . $critical_css . '</style>';
    }
}
add_action( 'wp_head', 'aqualuxe_add_critical_css', 1 );
```

## Accessibility

AquaLuxe is built with accessibility in mind and follows WCAG 2.1 guidelines.

### Keyboard Navigation

Keyboard navigation is implemented in `inc/core/accessibility.php`:

```php
/**
 * Add skip link focus fix
 */
function aqualuxe_skip_link_focus_fix() {
    // Skip link focus fix JS
    echo '<script>
        /(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
    </script>';
}
add_action( 'wp_print_footer_scripts', 'aqualuxe_skip_link_focus_fix' );
```

### Screen Readers

Screen reader text is implemented using the `.sr-only` class:

```css
.sr-only {
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
```

### ARIA Landmarks

ARIA landmarks are used throughout the theme:

```html
<header id="masthead" class="site-header" role="banner">
    <!-- Header content -->
</header>

<main id="content" class="site-content" role="main">
    <!-- Main content -->
</main>

<aside id="secondary" class="widget-area" role="complementary">
    <!-- Sidebar content -->
</aside>

<footer id="colophon" class="site-footer" role="contentinfo">
    <!-- Footer content -->
</footer>
```

### Focus Styles

Focus styles are implemented for keyboard users:

```css
:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

.user-is-tabbing :focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

.user-is-tabbing .focus-visible {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}
```

## Internationalization

AquaLuxe is fully translatable and supports RTL languages.

### Translation Functions

All strings are wrapped in translation functions:

```php
// Simple string
__( 'String to translate', 'aqualuxe' );

// String with context
_x( 'String to translate', 'context', 'aqualuxe' );

// String with plural form
_n( 'Singular', 'Plural', $count, 'aqualuxe' );

// Escaped string
esc_html__( 'String to translate', 'aqualuxe' );
esc_attr__( 'String to translate', 'aqualuxe' );
```

### Translation Files

Translation files are stored in the `languages/` directory:

```
languages/
├── aqualuxe.pot
├── en_US.po
├── en_US.mo
└── README.md
```

### RTL Support

RTL support is implemented using the `rtl.css` file and the `dir="rtl"` attribute:

```php
/**
 * Add RTL support
 */
function aqualuxe_rtl_support() {
    if ( is_rtl() ) {
        wp_enqueue_style( 'aqualuxe-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array( 'aqualuxe-style' ), AQUALUXE_VERSION );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_rtl_support' );
```

## Testing

AquaLuxe includes a comprehensive testing suite to ensure code quality and functionality.

### Unit Tests

Unit tests are implemented using PHPUnit:

```php
/**
 * Test case for theme functions
 */
class AqualuxeTest extends WP_UnitTestCase {
    /**
     * Test theme setup function
     */
    public function test_theme_setup() {
        // Call the function
        aqualuxe_setup();
        
        // Check if theme support is added
        $this->assertTrue( current_theme_supports( 'automatic-feed-links' ) );
        $this->assertTrue( current_theme_supports( 'title-tag' ) );
        $this->assertTrue( current_theme_supports( 'post-thumbnails' ) );
        $this->assertTrue( current_theme_supports( 'html5' ) );
        $this->assertTrue( current_theme_supports( 'customize-selective-refresh-widgets' ) );
        $this->assertTrue( current_theme_supports( 'editor-styles' ) );
        $this->assertTrue( current_theme_supports( 'responsive-embeds' ) );
        $this->assertTrue( current_theme_supports( 'align-wide' ) );
    }
}
```

### Integration Tests

Integration tests are implemented using WP Browser:

```php
/**
 * Test case for theme integration
 */
class AqualuxeIntegrationTest extends WPTestCase {
    /**
     * Test WooCommerce integration
     */
    public function test_woocommerce_integration() {
        // Activate WooCommerce
        activate_plugin( 'woocommerce/woocommerce.php' );
        
        // Check if WooCommerce support is added
        $this->assertTrue( current_theme_supports( 'woocommerce' ) );
        $this->assertTrue( current_theme_supports( 'wc-product-gallery-zoom' ) );
        $this->assertTrue( current_theme_supports( 'wc-product-gallery-lightbox' ) );
        $this->assertTrue( current_theme_supports( 'wc-product-gallery-slider' ) );
    }
}
```

### End-to-End Tests

End-to-end tests are implemented using Cypress:

```javascript
// cypress/integration/theme.spec.js
describe('Theme', () => {
    beforeEach(() => {
        cy.visit('/');
    });
    
    it('should have the correct title', () => {
        cy.title().should('include', 'AquaLuxe');
    });
    
    it('should have a header', () => {
        cy.get('header').should('be.visible');
    });
    
    it('should have a footer', () => {
        cy.get('footer').should('be.visible');
    });
    
    it('should have a main navigation', () => {
        cy.get('nav').should('be.visible');
    });
});
```

### Accessibility Testing

Accessibility testing is implemented using axe-core:

```javascript
// cypress/integration/accessibility.spec.js
describe('Accessibility', () => {
    beforeEach(() => {
        cy.visit('/');
        cy.injectAxe();
    });
    
    it('should have no accessibility violations', () => {
        cy.checkA11y();
    });
});
```

## Deployment

AquaLuxe includes a deployment script to create a production-ready zip file.

### Creating a Distribution Package

```bash
npm run zip
```

This command runs the `bin/create-zip.js` script, which:

1. Runs a production build
2. Optimizes assets
3. Removes development files
4. Creates a zip file

### Continuous Integration

AquaLuxe includes a GitHub Actions workflow for continuous integration:

```yaml
# .github/workflows/ci.yml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup Node.js
      uses: actions/setup-node@v2
      with:
        node-version: '16'
    
    - name: Install dependencies
      run: npm ci
    
    - name: Lint
      run: npm run lint
    
    - name: Build
      run: npm run build
    
    - name: Test
      run: npm run test
    
    - name: Create zip
      run: npm run zip
    
    - name: Upload artifact
      uses: actions/upload-artifact@v2
      with:
        name: aqualuxe
        path: ./aqualuxe.zip
```

## Contributing

### Code Standards

AquaLuxe follows the WordPress coding standards:

- PHP: WordPress Coding Standards
- JavaScript: ESLint with WordPress configuration
- CSS: Stylelint with WordPress configuration

### Development Workflow

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

### Pull Request Process

1. Ensure your code follows the coding standards
2. Update the documentation if necessary
3. Add tests for your changes
4. Make sure all tests pass
5. Submit a pull request with a clear description of the changes

### Code Review

All pull requests are reviewed by the maintainers. The review process includes:

1. Code quality review
2. Functionality testing
3. Documentation review
4. Performance review
5. Accessibility review

### Release Process

1. Update version number in `style.css` and `package.json`
2. Update changelog
3. Create a production build
4. Create a zip file
5. Create a GitHub release
6. Upload the zip file to the release