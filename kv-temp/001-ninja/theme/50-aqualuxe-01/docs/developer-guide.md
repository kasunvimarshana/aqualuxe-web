# AquaLuxe Theme Developer Documentation

## Introduction

AquaLuxe is a premium WordPress theme designed for aquatic businesses such as aquarium shops, pool supply stores, and marine equipment retailers. This developer documentation provides detailed information about the theme's architecture, customization options, and extension capabilities.

## Theme Structure

### Directory Structure

```
aqualuxe-theme/
├── assets/
│   ├── dist/           # Compiled assets
│   └── src/            # Source files
│       ├── css/        # SCSS files
│       ├── js/         # JavaScript files
│       └── images/     # Image files
├── docs/               # Documentation
├── inc/                # Theme functionality
│   ├── customizer/     # Theme customizer options
│   ├── woocommerce/    # WooCommerce integration
│   └── demo-data/      # Demo content
├── languages/          # Translation files
├── page-templates/     # Custom page templates
├── templates/          # Template parts
│   ├── content/        # Content templates
│   ├── footer/         # Footer templates
│   ├── header/         # Header templates
│   ├── homepage/       # Homepage section templates
│   ├── parts/          # Reusable template parts
│   └── woocommerce/    # WooCommerce template parts
├── woocommerce/        # WooCommerce template overrides
├── functions.php       # Theme functions
├── index.php           # Main template file
└── style.css           # Theme information
```

### Core Files

- **functions.php**: Main theme functionality and includes
- **style.css**: Theme metadata and basic styling
- **index.php**: Main template file
- **header.php**: Header template
- **footer.php**: Footer template
- **sidebar.php**: Sidebar template

## Theme Features

### Dual-State Architecture

AquaLuxe is designed to work both with and without WooCommerce. The theme automatically adapts its functionality based on whether WooCommerce is active.

```php
// Check if WooCommerce is active
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

// Load WooCommerce specific functionality
if (aqualuxe_is_woocommerce_active()) {
    // WooCommerce specific code
}
```

### Theme Hooks

AquaLuxe provides a comprehensive set of action and filter hooks that allow developers to modify or extend the theme's functionality without editing core files.

#### Action Hooks

- `aqualuxe_before_header`: Executes before the header
- `aqualuxe_after_header`: Executes after the header
- `aqualuxe_before_content`: Executes before the main content
- `aqualuxe_after_content`: Executes after the main content
- `aqualuxe_before_footer`: Executes before the footer
- `aqualuxe_after_footer`: Executes after the footer

#### Filter Hooks

- `aqualuxe_body_classes`: Modifies body classes
- `aqualuxe_sidebar_position`: Modifies sidebar position
- `aqualuxe_excerpt_length`: Modifies excerpt length
- `aqualuxe_excerpt_more`: Modifies excerpt more text

### Theme Customizer

AquaLuxe uses the WordPress Customizer API to provide a user-friendly interface for theme customization. The customizer options are organized into the following sections:

- **General Settings**: Basic theme settings
- **Header Options**: Header layout and content
- **Footer Options**: Footer layout and content
- **Typography**: Font settings
- **Colors**: Color scheme settings
- **Blog Settings**: Blog layout and content
- **WooCommerce Settings**: WooCommerce specific settings
- **API Integrations**: Third-party API integration settings

#### Adding Custom Customizer Options

```php
function aqualuxe_custom_customizer_options($wp_customize) {
    // Add section
    $wp_customize->add_section('custom_section', array(
        'title'    => __('Custom Section', 'aqualuxe'),
        'priority' => 130,
    ));

    // Add setting
    $wp_customize->add_setting('custom_setting', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    // Add control
    $wp_customize->add_control('custom_setting', array(
        'label'    => __('Custom Setting', 'aqualuxe'),
        'section'  => 'custom_section',
        'type'     => 'text',
    ));
}
add_action('customize_register', 'aqualuxe_custom_customizer_options');
```

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides custom styling and functionality for WooCommerce pages and components.

### Template Overrides

The theme includes custom templates for WooCommerce pages in the `woocommerce/` directory. These templates override the default WooCommerce templates.

### Custom WooCommerce Functionality

#### Product Quick View

AquaLuxe includes a product quick view feature that allows customers to view product details without leaving the current page.

```php
// Add quick view button
function aqualuxe_add_quick_view_button() {
    global $product;
    echo '<button class="aqualuxe-quick-view" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
}
add_action('woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15);
```

#### Wishlist

The theme includes a wishlist feature that allows customers to save products for later.

```php
// Add product to wishlist
function aqualuxe_add_to_wishlist() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wishlist_nonce')) {
        wp_send_json_error();
    }

    // Get product ID
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;

    // Add to wishlist
    $wishlist = aqualuxe_get_wishlist();
    $wishlist[] = $product_id;
    $wishlist = array_unique($wishlist);

    // Save wishlist
    aqualuxe_save_wishlist($wishlist);

    wp_send_json_success();
}
add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist');
add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist');
```

#### Multi-Currency Support

AquaLuxe includes multi-currency support that allows customers to switch between different currencies.

```php
// Switch currency
function aqualuxe_switch_currency() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_currency_nonce')) {
        wp_send_json_error();
    }

    // Get currency
    $currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : '';

    // Save currency
    if ($currency) {
        setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/');
    }

    wp_send_json_success();
}
add_action('wp_ajax_aqualuxe_switch_currency', 'aqualuxe_switch_currency');
add_action('wp_ajax_nopriv_aqualuxe_switch_currency', 'aqualuxe_switch_currency');
```

### Order Tracking

AquaLuxe includes a custom order tracking system that allows customers to track their orders.

```php
// Get tracking URL
function aqualuxe_get_tracking_url($provider, $tracking_number) {
    $provider = strtolower($provider);
    
    $tracking_urls = array(
        'usps'      => 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . $tracking_number,
        'ups'       => 'https://www.ups.com/track?tracknum=' . $tracking_number,
        'fedex'     => 'https://www.fedex.com/apps/fedextrack/?tracknumbers=' . $tracking_number,
        'dhl'       => 'https://www.dhl.com/en/express/tracking.html?AWB=' . $tracking_number . '&brand=DHL',
        // Add more providers as needed
    );
    
    if (isset($tracking_urls[$provider])) {
        return $tracking_urls[$provider];
    }
    
    return '';
}
```

## Custom Page Templates

AquaLuxe includes several custom page templates that can be selected when creating or editing a page.

### Available Templates

- **Homepage Template**: A custom template for the homepage with multiple sections
- **About Page Template**: A template for the about page with team members and company information
- **Services Page Template**: A template for the services page with service listings
- **Contact Page Template**: A template for the contact page with contact form and Google Maps integration
- **FAQ Page Template**: A template for the FAQ page with accordion sections
- **Full Width Template**: A template without sidebars

### Using Custom Templates

Custom templates can be selected from the Page Attributes meta box when editing a page.

## Theme Customization

### Adding Custom CSS

Custom CSS can be added through the WordPress Customizer under the "Additional CSS" section.

### Child Theme

For extensive customizations, it is recommended to create a child theme.

```php
/*
 Theme Name:   AquaLuxe Child
 Theme URI:    https://example.com/aqualuxe-child/
 Description:  AquaLuxe Child Theme
 Author:       Your Name
 Author URI:   https://example.com
 Template:     aqualuxe
 Version:      1.0.0
 License:      GNU General Public License v2 or later
 License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 Text Domain:  aqualuxe-child
*/

/* Import parent theme style */
@import url("../aqualuxe/style.css");

/* Add your custom styles below this line */
```

## Demo Content Importer

AquaLuxe includes a demo content importer that allows users to import pre-designed demo content.

### Available Demos

- **Aquarium Shop**: A demo for an aquarium and fish supply store
- **Pool Supplies**: A demo for a pool maintenance and supplies store
- **Marine Equipment**: A demo for a marine equipment and boat accessories store

### Import Process

The demo import process includes the following steps:

1. Import content (posts, pages, products, etc.)
2. Import widgets
3. Import theme options
4. Import menus

## Multilingual Support

AquaLuxe is translation-ready and compatible with popular translation plugins like WPML and Polylang.

### Translation Files

The theme includes translation files in the `languages/` directory.

### Translating Strings

All text strings in the theme are wrapped in translation functions.

```php
// Translatable string
echo esc_html__('Translatable string', 'aqualuxe');

// Translatable string with context
echo esc_html_x('Post', 'noun', 'aqualuxe');

// Translatable string with plural forms
echo sprintf(
    _n('%s comment', '%s comments', $comment_count, 'aqualuxe'),
    number_format_i18n($comment_count)
);
```

## Performance Optimization

AquaLuxe is optimized for performance with the following features:

- **Asset Minification**: CSS and JavaScript files are minified
- **Lazy Loading**: Images are lazy loaded
- **Responsive Images**: Images are served in appropriate sizes for different devices
- **Caching**: The theme is compatible with popular caching plugins

## Security Best Practices

AquaLuxe follows WordPress security best practices:

- **Data Sanitization**: All user input is sanitized
- **Data Validation**: All user input is validated
- **Nonce Verification**: All forms include nonce verification
- **Capability Checks**: All actions check user capabilities
- **Escaping Output**: All output is escaped

## Support and Updates

### Support

For theme support, please contact our support team at support@example.com or visit our support forum at https://example.com/support.

### Updates

The theme can be updated through the WordPress admin dashboard. Make sure to backup your site before updating.

## Changelog

### Version 1.0.0 (2023-08-16)
- Initial release