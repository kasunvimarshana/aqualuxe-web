# AquaLuxe Customization Options

## Overview
This document details all available customization options for the AquaLuxe WooCommerce child theme. These options can be accessed through the WordPress Customizer interface.

## Accessing Customization Options
To access these options:
1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Customize**
3. Select the appropriate section from the left sidebar

## Customization Sections

### 1. Site Identity
Customize your site's identity elements.

#### Options Available:
- **Site Title**: Your website's title
- **Tagline**: Your website's description
- **Logo**: Upload a custom logo (recommended size: 400x100px)
- **Retina Logo**: Upload a high-resolution logo for retina displays
- **Site Icon**: Upload a favicon (recommended size: 512x512px)
- **Display Site Title and Tagline**: Toggle visibility of text elements

### 2. Colors
Customize the color scheme of your theme.

#### Color Schemes:
- **Ocean Blue**: Professional blue-based scheme
- **Aquamarine**: Teal and green-based scheme
- **Coral Purple**: Warm purple-based scheme

#### Individual Color Controls:
- **Primary Color**: Main accent color for buttons and links
- **Secondary Color**: Supporting accent color
- **Header Background Color**: Background color for site header
- **Footer Background Color**: Background color for site footer
- **Button Background Color**: Background color for buttons
- **Button Text Color**: Text color for buttons

### 3. Header Options
Customize the header layout and elements.

#### Layout Options:
- **Header Layout**: Choose between Standard, Sticky, or Minimal layouts
- **Header Background**: Set header background color or image
- **Header Padding**: Adjust top and bottom padding
- **Sticky Header**: Enable/disable sticky header functionality

#### Navigation Options:
- **Primary Navigation Style**: Choose navigation style
- **Mobile Navigation Style**: Choose mobile navigation style
- **Search Bar Position**: Position search bar in header
- **Header Cart Icon**: Toggle cart icon visibility

### 4. AquaLuxe Theme Options
Customize theme-specific features.

#### Design Options:
- **Color Scheme**: Select from predefined color schemes
- **Typography**: Choose font combinations
- **Layout Width**: Set maximum layout width
- **Content Background Color**: Set background color for content area

#### Feature Toggles:
- **Enable Quick View**: Toggle product quick view functionality
- **Enable AJAX Add to Cart**: Toggle AJAX add to cart on shop pages
- **Display Breadcrumbs**: Toggle breadcrumb navigation
- **Product Hover Effect**: Choose hover effect for product images

### 5. WooCommerce Options
Customize WooCommerce-specific elements.

#### Product Catalog:
- **Products per Page**: Number of products to display per page
- **Product Columns**: Number of columns in product grid
- **Product Image Size**: Size of product images in catalog
- **Display Product Ratings**: Toggle product rating display

#### Product Page:
- **Product Image Zoom**: Enable/disable image zoom feature
- **Product Gallery Slider**: Enable/disable gallery slider
- **Lightbox for Product Images**: Enable/disable lightbox view
- **Display Related Products**: Toggle related products section

#### Cart and Checkout:
- **Cross-Sells Display**: Toggle cross-sells in cart
- **Checkout Layout**: Choose checkout page layout
- **Display Coupon Form**: Toggle coupon form visibility
- **Enable AJAX Cart Update**: Toggle AJAX cart updates

### 6. Background Image
Set a custom background image for your site.

#### Options:
- **Background Image**: Upload a background image
- **Background Repeat**: Choose how background repeats
- **Background Size**: Choose background sizing
- **Background Position**: Choose background positioning
- **Background Attachment**: Choose background attachment

### 7. Menus
Configure site navigation menus.

#### Menu Locations:
- **Primary Menu**: Main navigation menu
- **Secondary Menu**: Additional navigation menu
- **Handheld Menu**: Mobile navigation menu
- **Footer Menu**: Footer navigation menu

### 8. Widgets
Manage widget areas.

#### Widget Areas:
- **Sidebar**: Main sidebar for pages and posts
- **Below Header**: Widget area below header
- **Above Footer**: Widget area above footer
- **Footer 1, 2, 3, 4**: Four footer widget areas

### 9. Static Front Page
Set your front page display.

#### Options:
- **Front Page Displays**: Choose latest posts or static page
- **Front Page**: Select page to display as front page
- **Posts Page**: Select page to display blog posts

## Advanced Customization

### Custom CSS
Add custom CSS through **Appearance > Customize > Additional CSS**.

Example customizations:
```css
/* Change product title color */
.woocommerce-loop-product__title {
    color: #ff6b6b;
}

/* Adjust button styling */
.button {
    border-radius: 30px;
    text-transform: uppercase;
}

/* Modify header height */
.site-header {
    padding: 20px 0;
}
```

### Child Theme Modifications
For extensive customizations, create a child theme of AquaLuxe:
1. Create a new directory: `/wp-content/themes/aqualuxe-custom/`
2. Create `style.css` with theme header:
   ```css
   /*
   Theme Name: AquaLuxe Custom
   Description: Custom child theme of AquaLuxe
   Template: aqualuxe
   */
   ```
3. Create `functions.php` for custom functionality

### Hook Customizations
AquaLuxe provides numerous hooks for customization:

#### Header Hooks:
- `aqualuxe_before_header`
- `aqualuxe_header`
- `aqualuxe_after_header`

#### Content Hooks:
- `aqualuxe_before_content`
- `aqualuxe_content_top`
- `aqualuxe_content_bottom`
- `aqualuxe_after_content`

#### Footer Hooks:
- `aqualuxe_before_footer`
- `aqualuxe_footer`
- `aqualuxe_after_footer`

#### WooCommerce Hooks:
- `aqualuxe_before_shop_loop`
- `aqualuxe_after_shop_loop`
- `aqualuxe_product_quick_view`

## Customization Examples

### Example 1: Changing Color Scheme
1. Navigate to **Appearance > Customize > Colors**
2. Select "Aquamarine" color scheme
3. Adjust primary color to #4ECDC4
4. Click "Publish"

### Example 2: Enabling Sticky Header
1. Navigate to **Appearance > Customize > Header Options**
2. Set "Header Layout" to "Sticky"
3. Click "Publish"

### Example 3: Customizing Product Display
1. Navigate to **Appearance > Customize > WooCommerce Options**
2. Set "Product Columns" to 4
3. Set "Products per Page" to 12
4. Enable "Product Image Zoom"
5. Click "Publish"

## Presets and Starter Content

### Theme Presets
AquaLuxe includes several preset configurations:
- **Default**: Standard configuration with Ocean Blue scheme
- **Minimal**: Clean layout with reduced elements
- **Bold**: High-contrast design with prominent buttons
- **Elegant**: Soft colors with refined typography

### Importing Demo Content
To import demo content:
1. Navigate to **Tools > AquaLuxe Demo Import**
2. Click "Import Demo Data"
3. Wait for import to complete
4. Customize imported content as needed

## Customization Best Practices

### 1. Start with Presets
Begin with one of the preset configurations and then make adjustments.

### 2. Mobile-First Approach
Always check customizations on mobile devices after making changes.

### 3. Performance Considerations
- Avoid heavy background images
- Limit custom CSS rules
- Use efficient color schemes

### 4. Accessibility
- Ensure sufficient color contrast
- Maintain logical heading hierarchy
- Test keyboard navigation

### 5. Brand Consistency
- Choose colors that match your brand
- Maintain consistent typography
- Keep design elements cohesive

## Customization API

### PHP Functions
```php
// Get theme option
$color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'blue' );

// Set theme option
set_theme_mod( 'aqualuxe_color_scheme', 'green' );

// Remove theme option
remove_theme_mod( 'aqualuxe_color_scheme' );
```

### JavaScript Customization
```javascript
// Listen for customizer changes
wp.customize( 'aqualuxe_color_scheme', function( value ) {
    value.bind( function( newval ) {
        // Update color scheme
        document.body.className = document.body.className.replace( /\w+-scheme/, newval + '-scheme' );
    } );
} );
```

## Resetting Customizations

To reset customizations to default:
1. Navigate to **Appearance > Customize**
2. Click "Reset" in each section
3. Or delete all theme mods:
   ```php
   delete_option( 'theme_mods_aqualuxe' );
   ```

## Exporting and Importing Customizations

### Export Customizations
```php
// Get all theme mods
$theme_mods = get_theme_mods();

// Save to file or database
update_option( 'aqualuxe_customizations_backup', $theme_mods );
```

### Import Customizations
```php
// Get saved customizations
$theme_mods = get_option( 'aqualuxe_customizations_backup' );

// Apply customizations
foreach ( $theme_mods as $mod_name => $mod_value ) {
    set_theme_mod( $mod_name, $mod_value );
}
```

## Conclusion

The AquaLuxe theme provides extensive customization options through the WordPress Customizer interface. These options allow you to tailor the theme to your specific brand and business needs without requiring code modifications.

For best results:
1. Use the Customizer preview to see changes in real-time
2. Start with presets and make incremental adjustments
3. Test on different devices and screen sizes
4. Maintain accessibility and performance standards
5. Document customizations for future reference

All customizations made through the Customizer are automatically saved and applied to your site. For more advanced customizations, consider using a child theme or consulting with a WordPress developer.