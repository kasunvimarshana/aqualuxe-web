# AquaLuxe Coding Standards

## Overview
This document outlines the coding standards and best practices that must be followed when implementing the AquaLuxe WooCommerce child theme. These standards ensure code quality, maintainability, and consistency throughout the project.

## 1. General Principles

### 1.1 SOLID Principles
- **Single Responsibility Principle**: Each class and function should have one reason to change
- **Open/Closed Principle**: Classes should be open for extension but closed for modification
- **Liskov Substitution Principle**: Subtypes must be substitutable for their base types
- **Interface Segregation Principle**: Clients should not be forced to depend on interfaces they do not use
- **Dependency Inversion Principle**: Depend on abstractions, not concretions

### 1.2 DRY (Don't Repeat Yourself)
- Avoid code duplication
- Extract reusable code into functions or classes
- Use template parts for repeated markup
- Create helper functions for common operations

### 1.3 KISS (Keep It Simple, Stupid)
- Favor simple solutions over complex ones
- Write code that is easy to understand
- Avoid unnecessary complexity
- Prioritize readability

## 2. PHP Coding Standards

### 2.1 WordPress Coding Standards
Follow the official [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/):

#### File Structure
- Opening PHP tag `<?php` at start of file
- No closing `?>` tag at end of file
- One file per class
- Proper file naming conventions

#### Naming Conventions
```php
// Functions
function aqualuxe_function_name() {}

// Classes
class AquaLuxe_Class_Name {}

// Constants
define( 'AQUALUXE_CONSTANT_NAME', 'value' );

// Variables
$variable_name = 'value';
```

#### Code Indentation
- Use tabs for indentation
- One tab per indentation level
- Align continuation lines appropriately

#### Control Structures
```php
// if/else
if ( condition ) {
    // code
} else {
    // code
}

// Loops
foreach ( $items as $item ) {
    // code
}

// Switch
switch ( $value ) {
    case 'one':
        // code
        break;
    default:
        // code
        break;
}
```

#### Function Calls
```php
// Parameters on same line
do_action( 'action_name', $param1, $param2 );

// Parameters on separate lines for readability
do_action(
    'action_name',
    $param1,
    $param2,
    $param3
);
```

#### Class Definitions
```php
class AquaLuxe_Class_Name {

    /**
     * Class property description.
     *
     * @var string
     */
    private $property_name;

    /**
     * Constructor.
     */
    public function __construct() {
        // code
    }

    /**
     * Method description.
     *
     * @param string $param Parameter description.
     * @return string Return description.
     */
    public function method_name( $param ) {
        // code
        return $value;
    }
}
```

### 2.2 WooCommerce Coding Standards
Follow WooCommerce coding standards where applicable:
- Use WooCommerce hooks and filters
- Follow WooCommerce template structure
- Implement proper data sanitization
- Use WooCommerce helper functions

### 2.3 Security Best Practices

#### Data Validation
```php
// Validate and sanitize input
$user_input = sanitize_text_field( $_POST['input'] );
$numeric_input = absint( $_POST['number'] );
$email_input = sanitize_email( $_POST['email'] );
```

#### Output Escaping
```php
// Always escape output
echo esc_html( $variable );
echo esc_url( $url );
echo esc_attr( $attribute );
```

#### Nonce Verification
```php
// Verify nonce for form submissions
if ( ! wp_verify_nonce( $_POST['nonce'], 'action_name' ) ) {
    wp_die( 'Security check failed' );
}
```

#### Capability Checks
```php
// Check user capabilities
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Insufficient permissions' );
}
```

## 3. JavaScript Coding Standards

### 3.1 WordPress JavaScript Standards
Follow the official [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/):

#### File Structure
- Use camelCase for variable and function names
- Use PascalCase for constructors
- Use UPPER_CASE for constants
- Use descriptive variable names

#### JSDoc Comments
```javascript
/**
 * Function description.
 *
 * @param {string} param1 - Parameter description.
 * @param {number} param2 - Parameter description.
 * @return {boolean} Return description.
 */
function functionName( param1, param2 ) {
    // code
    return true;
}
```

#### jQuery Best Practices
```javascript
jQuery(document).ready(function($) {
    // Use $ inside ready function
    $('.selector').on('click', function() {
        // Event handling
    });
});
```

#### Event Handling
```javascript
// Use event delegation for dynamically added elements
$(document).on('click', '.dynamic-element', function() {
    // Event handling
});

// Namespace events
$('.selector').on('click.aqualuxe', function() {
    // Event handling
});
```

### 3.2 AJAX Implementation
```javascript
// AJAX request with proper error handling
$.ajax({
    url: aqualuxe_ajax.ajax_url,
    type: 'POST',
    data: {
        action: 'aqualuxe_action',
        nonce: aqualuxe_ajax.nonce,
        // other data
    },
    success: function(response) {
        if (response.success) {
            // Handle success
        } else {
            // Handle error
        }
    },
    error: function() {
        // Handle AJAX error
    }
});
```

## 4. CSS Coding Standards

### 4.1 WordPress CSS Standards
Follow the official [WordPress CSS Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/css/):

#### Naming Conventions
```css
/* Use hyphens for class names */
.aqualuxe-product-grid {
    /* styles */
}

/* Use BEM methodology */
.aqualuxe-product-card {
    /* styles */
}

.aqualuxe-product-card__title {
    /* styles */
}

.aqualuxe-product-card--featured {
    /* modifier styles */
}
```

#### Property Order
Organize CSS properties in the following order:
1. Positioning (`position`, `top`, `left`, etc.)
2. Display & Box Model (`display`, `width`, `margin`, `padding`, etc.)
3. Typography (`font-family`, `font-size`, `line-height`, etc.)
4. Visual (`color`, `background`, `border`, etc.)
5. Misc (`opacity`, `z-index`, etc.)

#### Example
```css
.aqualuxe-product-card {
    position: relative;
    display: block;
    width: 100%;
    margin: 0 0 20px;
    padding: 20px;
    font-family: Arial, sans-serif;
    font-size: 16px;
    color: #333;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}
```

### 4.2 Responsive Design
```css
/* Mobile-first approach */
.aqualuxe-product-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}

/* Tablet */
@media (min-width: 768px) {
    .aqualuxe-product-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .aqualuxe-product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}
```

## 5. HTML Coding Standards

### 5.1 Semantic HTML5
Use appropriate semantic elements:
```html
<header class="site-header">
    <nav class="main-navigation">
        <!-- navigation -->
    </nav>
</header>

<main class="site-main">
    <article class="product">
        <header class="product-header">
            <h1 class="product-title">Product Name</h1>
        </header>
        <div class="product-content">
            <!-- content -->
        </div>
    </article>
</main>

<footer class="site-footer">
    <!-- footer content -->
</footer>
```

### 5.2 Accessibility Markup
```html
<!-- Proper heading hierarchy -->
<h1>Main Heading</h1>
<h2>Section Heading</h2>
<h3>Subsection Heading</h3>

<!-- Accessible navigation -->
<nav role="navigation" aria-label="Main menu">
    <ul>
        <li><a href="#main">Skip to content</a></li>
    </ul>
</nav>

<!-- Form accessibility -->
<label for="email">Email Address</label>
<input type="email" id="email" name="email" required aria-describedby="email-error">
<div id="email-error" class="error-message">Please enter a valid email</div>

<!-- Image accessibility -->
<img src="product.jpg" alt="Red Discus Fish Swimming" />
```

## 6. Performance Optimization Standards

### 6.1 Asset Loading
```php
// Properly enqueue scripts and styles
function aqualuxe_enqueue_scripts() {
    // Enqueue styles
    wp_enqueue_style(
        'aqualuxe-style',
        get_stylesheet_uri(),
        array( 'storefront-style' ),
        AQUALUXE_VERSION
    );
    
    // Enqueue scripts
    wp_enqueue_script(
        'aqualuxe-scripts',
        get_stylesheet_directory_uri() . '/assets/js/aqualuxe-scripts.js',
        array( 'jquery' ),
        AQUALUXE_VERSION,
        true // Load in footer
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_scripts' );
```

### 6.2 Conditional Loading
```php
// Only load scripts on specific pages
function aqualuxe_enqueue_scripts() {
    if ( is_product() ) {
        wp_enqueue_script(
            'aqualuxe-product-scripts',
            get_stylesheet_directory_uri() . '/assets/js/product-scripts.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
    }
}
```

## 7. Documentation Standards

### 7.1 Inline Comments
```php
// Brief comment about what the code does
$variable = get_option( 'aqualuxe_option' );

/*
 * Longer comment explaining complex logic
 * that spans multiple lines
 */
if ( $complex_condition ) {
    // code
}
```

### 7.2 PHPDoc Comments
```php
/**
 * Class description.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
class AquaLuxe_Main_Class {

    /**
     * Property description.
     *
     * @var string
     */
    private $property;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
        // code
    }

    /**
     * Method description.
     *
     * @param string $param Parameter description.
     * @return bool Return description.
     * @since 1.0.0
     */
    public function method_name( $param ) {
        // code
        return true;
    }
}
```

## 8. Version Control Standards

### 8.1 Git Commit Messages
Follow conventional commit message format:
```
feat: Add quick view functionality
fix: Resolve cart update issue
docs: Update installation instructions
style: Format CSS files
refactor: Optimize template functions
test: Add unit tests for AJAX functions
chore: Update dependencies
```

### 8.2 Branch Naming
```
feature/quick-view-modal
fix/cart-ajax-issue
docs/installation-guide
```

## 9. Testing Standards

### 9.1 Unit Testing
- Write tests for all custom functions
- Test edge cases and error conditions
- Use PHPUnit for PHP testing
- Use Jest for JavaScript testing

### 9.2 Integration Testing
- Test theme with various WordPress versions
- Test with popular plugins
- Test across different browsers
- Test on various screen sizes

## 10. Code Review Standards

### 10.1 Review Checklist
Before merging code, ensure:
- [ ] Code follows all coding standards
- [ ] All functions have proper documentation
- [ ] Security best practices are implemented
- [ ] Performance considerations are addressed
- [ ] Accessibility features are included
- [ ] Code is properly tested
- [ ] No syntax errors or warnings
- [ ] Code is readable and maintainable

### 10.2 Review Process
1. Author creates pull request with description
2. Reviewer checks code against standards
3. Reviewer tests functionality if possible
4. Reviewer provides feedback or approves
5. Author addresses feedback
6. Code merged after approval

## 11. File Organization Standards

### 11.1 Directory Structure
```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── aqualuxe-styles.css
│   │   └── customizer.css
│   ├── js/
│   │   ├── aqualuxe-scripts.js
│   │   ├── navigation.js
│   │   └── customizer.js
│   └── images/
├── inc/
│   ├── customizer.php
│   ├── template-hooks.php
│   ├── template-functions.php
│   └── class-aqualuxe.php
├── woocommerce/
│   ├── cart/
│   ├── checkout/
│   ├── global/
│   ├── loop/
│   ├── myaccount/
│   ├── single-product/
│   └── archive-product.php
├── template-parts/
│   ├── header/
│   ├── footer/
│   └── content/
├── functions.php
├── style.css
└── readme.txt
```

### 11.2 File Naming
- Use lowercase letters and hyphens
- Be descriptive but concise
- Match WordPress conventions where applicable
- Use singular names for classes, plural for collections

## 12. Error Handling Standards

### 12.1 PHP Error Handling
```php
// Proper error handling
function aqualuxe_process_data( $data ) {
    // Validate input
    if ( empty( $data ) ) {
        return new WP_Error( 'invalid_data', 'Data is required' );
    }
    
    // Process data
    try {
        $result = some_processing_function( $data );
        return $result;
    } catch ( Exception $e ) {
        error_log( 'AquaLuxe processing error: ' . $e->getMessage() );
        return new WP_Error( 'processing_error', 'Failed to process data' );
    }
}
```

### 12.2 JavaScript Error Handling
```javascript
// AJAX error handling
$.ajax({
    url: aqualuxe_ajax.ajax_url,
    type: 'POST',
    data: {
        action: 'aqualuxe_action',
        nonce: aqualuxe_ajax.nonce
    },
    success: function(response) {
        if (response.success) {
            // Handle success
            console.log('Success:', response.data);
        } else {
            // Handle WordPress error
            console.error('Error:', response.data);
        }
    },
    error: function(xhr, status, error) {
        // Handle AJAX error
        console.error('AJAX Error:', error);
    }
});
```

## 13. Internationalization Standards

### 13.1 Text Domain
Always use the theme's text domain:
```php
// Correct
__( 'Hello World', 'aqualuxe' );

// Incorrect
__( 'Hello World' );
```

### 13.2 Translatable Strings
```php
// Simple strings
__( 'Add to Cart', 'aqualuxe' );

// Strings with placeholders
sprintf( __( 'Add %s to Cart', 'aqualuxe' ), $product_name );

// Plural strings
_n( '1 Item', '%s Items', $count, 'aqualuxe' );
```

## 14. Compatibility Standards

### 14.1 WordPress Version Compatibility
- Support current WordPress version and 2 previous versions
- Test with latest WordPress release
- Check deprecated function usage

### 14.2 Browser Compatibility
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- iOS Safari
- Android Chrome

### 14.3 Plugin Compatibility
- Test with popular plugins
- Ensure no conflicts with WooCommerce extensions
- Check compatibility with SEO plugins
- Verify caching plugin compatibility

## Conclusion

Following these coding standards ensures that the AquaLuxe theme will be:
- Maintainable and scalable
- Secure and performant
- Accessible and user-friendly
- Compatible across different environments
- Easy for other developers to understand and contribute to

All developers working on this project should familiarize themselves with these standards and adhere to them throughout the development process.