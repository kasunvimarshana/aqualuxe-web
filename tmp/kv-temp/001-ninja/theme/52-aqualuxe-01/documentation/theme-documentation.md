# AquaLuxe WordPress Theme Documentation

## Table of Contents

1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Theme Setup](#theme-setup)
4. [Customizer Options](#customizer-options)
5. [Page Templates](#page-templates)
6. [WooCommerce Integration](#woocommerce-integration)
7. [Multilingual Support](#multilingual-support)
8. [Multicurrency Support](#multicurrency-support)
9. [Multivendor Support](#multivendor-support)
10. [Performance Optimization](#performance-optimization)
11. [Theme Structure](#theme-structure)
12. [CSS Structure](#css-structure)
13. [JavaScript Features](#javascript-features)
14. [Hooks & Filters](#hooks-filters)
15. [Troubleshooting](#troubleshooting)
16. [Support](#support)

## Introduction <a name="introduction"></a>

AquaLuxe is a premium WordPress theme designed specifically for aquatic businesses. With its elegant design and comprehensive feature set, AquaLuxe provides everything you need to create a stunning online presence for your aquatic business.

**Key Features:**

- Fully responsive design that looks great on all devices
- WooCommerce integration for e-commerce functionality
- Multilingual support with WPML compatibility
- Multicurrency support for international sales
- Multivendor capabilities for marketplace functionality
- Dark mode toggle for enhanced user experience
- Performance optimized for fast loading times
- Customizable header and footer layouts
- Multiple blog and shop layouts
- Advanced product display options
- SEO optimized structure

## Installation <a name="installation"></a>

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (if using e-commerce features)

### Installation Steps

1. **Upload the theme:**
   - Go to your WordPress admin panel
   - Navigate to Appearance > Themes
   - Click "Add New" and then "Upload Theme"
   - Choose the aqualuxe.zip file and click "Install Now"
   - After installation, click "Activate"

2. **Install required plugins:**
   After activating the theme, you'll be prompted to install the recommended plugins:
   - WooCommerce (for e-commerce functionality)
   - Elementor (for page building)
   - Contact Form 7 (for contact forms)
   - Follow the on-screen instructions to install and activate these plugins

3. **Import demo content (optional):**
   - Go to Appearance > AquaLuxe Options
   - Navigate to the "Demo Import" tab
   - Choose the demo you want to import
   - Click "Import Demo" and wait for the process to complete

## Theme Setup <a name="theme-setup"></a>

### Initial Setup

1. **Set up your logo:**
   - Go to Appearance > Customize
   - Navigate to "Site Identity"
   - Upload your logo and adjust the size as needed
   - Set your site title and tagline

2. **Configure menus:**
   - Go to Appearance > Menus
   - Create a new menu or edit existing ones
   - Assign menus to the available locations:
     - Primary Menu (main navigation)
     - Secondary Menu (top bar navigation)
     - Footer Menu
     - Mobile Menu

3. **Set up widgets:**
   - Go to Appearance > Widgets
   - Add widgets to the available widget areas:
     - Sidebar
     - Footer Widgets (1-4)
     - Shop Sidebar
     - Product Filters

4. **Homepage setup:**
   - Create a new page or edit the existing homepage
   - Set it as your front page in Settings > Reading
   - Use Elementor to design your homepage with the included elements

### Header Setup

1. Go to Appearance > Customize > AquaLuxe Theme Options > Header Options
2. Choose your preferred header layout
3. Configure header elements (search, cart, account, etc.)
4. Set up the top bar content and visibility
5. Configure sticky header behavior

### Footer Setup

1. Go to Appearance > Customize > AquaLuxe Theme Options > Footer Options
2. Choose your preferred footer layout
3. Configure footer widgets display
4. Set up copyright text
5. Configure payment icons display

## Customizer Options <a name="customizer-options"></a>

AquaLuxe provides extensive customization options through the WordPress Customizer. To access these options, go to Appearance > Customize.

### Site Identity

- Logo upload
- Site title and tagline
- Site icon (favicon)

### AquaLuxe Theme Options

#### Header Options

- Header Layout: Choose between Default, Centered, Transparent, or Minimal
- Sticky Header: Enable/disable sticky header functionality
- Header Search: Show/hide search in header
- Header Cart: Show/hide cart in header
- Header Account: Show/hide account in header
- Top Bar: Show/hide top bar
- Top Bar Content: Customize the content displayed in the top bar

#### Footer Options

- Footer Layout: Choose between 1, 2, 3, or 4 columns
- Copyright Text: Customize the copyright text in the footer
- Payment Icons: Show/hide payment icons in the footer

#### Color Options

- Primary Color: Main theme color
- Secondary Color: Secondary theme color
- Accent Color: Accent color for highlights
- Dark Mode: Enable/disable dark mode toggle
- Default Color Scheme: Choose between Light, Dark, or Auto (system preference)

#### Typography Options

- Heading Font: Choose from various font options
- Body Font: Choose from various font options
- Base Font Size: Set the base font size in pixels

#### Layout Options

- Container Width: Set the maximum width of the content container
- Blog Layout: Choose between Grid, List, or Masonry
- Sidebar Position: Choose between Right, Left, or None

#### WooCommerce Options

- Products Per Row: Set the number of products displayed per row
- Products Per Page: Set the number of products displayed per page
- Related Products: Show/hide related products
- Product Gallery Zoom: Enable/disable product gallery zoom
- Product Gallery Lightbox: Enable/disable product gallery lightbox
- Product Gallery Slider: Enable/disable product gallery slider
- Quick View: Enable/disable product quick view

### Colors

- Background Color: Set the site background color
- Header Text Color: Set the color of the header text

### Background

- Background Image: Set a background image for the site
- Background Position, Repeat, Attachment, and Size

### Menus

- Assign menus to available locations
- Configure menu options

### Widgets

- Add and configure widgets in available widget areas

### Homepage Settings

- Choose what to display on the homepage (latest posts or a static page)
- Select the homepage and posts page

### Additional CSS

- Add custom CSS to further customize the theme

## Page Templates <a name="page-templates"></a>

AquaLuxe includes several page templates to help you create different types of pages:

1. **Default Template**
   - Standard page layout with sidebar (if enabled)

2. **Full Width Template**
   - Page without sidebar, content spans the full width

3. **Elementor Canvas**
   - Blank template for use with Elementor page builder
   - No header, footer, or sidebar

4. **Elementor Full Width**
   - Full width template for use with Elementor
   - Includes header and footer

5. **Landing Page**
   - Minimal template for landing pages
   - No header navigation or sidebar
   - Includes simplified footer

To use a template:
1. Create or edit a page
2. In the Page Attributes section, select the desired template from the dropdown
3. Update or publish the page

## WooCommerce Integration <a name="woocommerce-integration"></a>

AquaLuxe is fully compatible with WooCommerce and includes enhanced styling and features for e-commerce.

### Shop Page

The shop page has been customized with:
- Grid/list view toggle
- Advanced filtering options
- Quick view functionality
- Add to cart buttons
- Wishlist integration
- Product comparison

### Product Page

The single product page includes:
- Gallery slider with zoom and lightbox
- Product tabs with custom styling
- Related products carousel
- Cross-sells display
- Size guide popup
- Product sharing options

### Cart and Checkout

The cart and checkout pages have been optimized for:
- User-friendly layout
- Multi-step checkout process
- Order summary sidebar
- Payment method icons
- Trust badges

### My Account

The my account page includes:
- Dashboard overview
- Order history with detailed view
- Download management
- Address management
- Account details
- Wishlist integration

### WooCommerce Settings

To configure WooCommerce-specific theme settings:
1. Go to Appearance > Customize > AquaLuxe Theme Options > WooCommerce Options
2. Adjust the settings according to your preferences

## Multilingual Support <a name="multilingual-support"></a>

AquaLuxe is fully compatible with WPML (WordPress Multilingual) plugin for creating a multilingual website.

### Setting Up WPML

1. Install and activate the WPML plugin
2. Follow the WPML setup wizard to configure your languages
3. Translate your content using WPML's translation management

### Theme Integration

AquaLuxe provides special integration with WPML:
- Language switcher in the header
- Language switcher in the mobile menu
- Language switcher in the footer (optional)
- Translatable theme options
- RTL (Right-to-Left) support for languages like Arabic and Hebrew

### Translating Theme Options

To translate theme options:
1. Go to WPML > String Translation
2. Filter by "Theme Mod" domain
3. Translate the theme options strings

## Multicurrency Support <a name="multicurrency-support"></a>

AquaLuxe supports multiple currencies for international e-commerce.

### Supported Plugins

The theme is compatible with the following multicurrency plugins:
- WooCommerce Multilingual (WCML) with WPML
- WooCommerce Currency Switcher (WOOCS)
- Currency Switcher for WooCommerce by WP Wham

### Currency Switcher Display

The currency switcher can be displayed in:
- Header (next to the language switcher)
- Mobile menu
- Footer (optional)

### Setting Up Multicurrency

1. Install and activate your preferred currency plugin
2. Configure the currencies and exchange rates
3. The theme will automatically display the currency switcher in the designated locations

## Multivendor Support <a name="multivendor-support"></a>

AquaLuxe supports multivendor functionality, allowing you to create a marketplace with multiple sellers.

### Supported Plugins

The theme is compatible with the following multivendor plugins:
- WC Marketplace
- Dokan
- WC Vendors
- WCFM Marketplace

### Vendor Features

- Customized vendor shop pages
- Vendor information on product pages
- Vendor tab in product details
- Vendor registration form
- Vendor dashboard integration
- Vendor filtering on shop page

### Setting Up Multivendor

1. Install and activate your preferred multivendor plugin
2. Configure the plugin settings according to your requirements
3. The theme will automatically integrate with the plugin and display vendor information

## Performance Optimization <a name="performance-optimization"></a>

AquaLuxe is optimized for performance to ensure fast loading times and a smooth user experience.

### Performance Features

- Optimized asset loading
- Lazy loading of images
- Deferred JavaScript loading
- Critical CSS inline loading
- Image optimization
- Database query optimization
- Cache-friendly structure
- Minified CSS and JavaScript
- Optimized HTTP headers
- WebP image support

### Performance Settings

To configure performance settings:
1. Go to Appearance > AquaLuxe Options > Performance
2. Adjust the settings according to your preferences:
   - Enable/disable emoji
   - Enable/disable embeds
   - Configure asset loading
   - Set up image optimization
   - Configure caching behavior

### Additional Recommendations

For optimal performance, we recommend:
- Using a caching plugin (e.g., WP Rocket, W3 Total Cache)
- Implementing a CDN (Content Delivery Network)
- Optimizing images before uploading
- Using a quality hosting provider
- Keeping plugins to a minimum

## Theme Structure <a name="theme-structure"></a>

Understanding the theme structure can help you make customizations more effectively.

### File Structure

```
aqualuxe/
├── assets/
│   ├── dist/
│   │   ├── css/
│   │   ├── js/
│   │   ├── fonts/
│   │   └── images/
│   └── src/
│       ├── sass/
│       ├── js/
│       └── images/
├── documentation/
├── inc/
│   ├── compatibility/
│   ├── core/
│   ├── customizer/
│   └── widgets/
├── languages/
├── template-parts/
│   ├── content/
│   ├── header/
│   └── footer/
├── woocommerce/
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── sidebar.php
├── single.php
├── style.css
└── woocommerce.php
```

### Key Files and Directories

- **style.css**: Theme information and main stylesheet
- **functions.php**: Theme setup and functionality
- **header.php**: Header template
- **footer.php**: Footer template
- **index.php**: Main template file
- **page.php**: Page template
- **single.php**: Single post template
- **archive.php**: Archive template
- **woocommerce.php**: WooCommerce main template
- **assets/**: Contains CSS, JavaScript, fonts, and images
- **inc/**: Contains theme functionality
- **template-parts/**: Contains template partials
- **woocommerce/**: Contains WooCommerce template overrides

## CSS Structure <a name="css-structure"></a>

AquaLuxe uses a modular SCSS structure for better organization and maintainability.

### Main SCSS Files

- **style.scss**: Main stylesheet that imports all other files
- **_variables.scss**: Variables for colors, typography, spacing, etc.
- **_mixins.scss**: Reusable mixins for responsive design, buttons, etc.
- **_reset.scss**: CSS reset and base styles
- **_typography.scss**: Typography styles
- **_buttons.scss**: Button styles
- **_forms.scss**: Form styles
- **_navigation.scss**: Navigation styles
- **_layout.scss**: Layout styles
- **_utilities.scss**: Utility classes

### Component Files

- **_header.scss**: Header styles
- **_footer.scss**: Footer styles
- **_sidebar.scss**: Sidebar styles
- **_cards.scss**: Card component styles
- **_hero.scss**: Hero section styles
- **_products.scss**: Product styles
- **_blog.scss**: Blog styles
- **_widgets.scss**: Widget styles

### WooCommerce Files

- **_woocommerce.scss**: Main WooCommerce styles
- **_shop.scss**: Shop page styles
- **_product.scss**: Single product styles
- **_cart.scss**: Cart styles
- **_checkout.scss**: Checkout styles
- **_account.scss**: My account styles

### Customizing CSS

To customize the theme's CSS:
1. Use the Additional CSS section in the Customizer for small changes
2. For larger changes, create a child theme and override the styles
3. If you're comfortable with SCSS, you can modify the source files and recompile

## JavaScript Features <a name="javascript-features"></a>

AquaLuxe includes several JavaScript features to enhance the user experience.

### Core Features

- **Mobile Menu**: Responsive mobile menu with submenu toggles
- **Sticky Header**: Header that sticks to the top on scroll
- **Dark Mode Toggle**: Switch between light and dark modes
- **Search Toggle**: Expandable search form in the header
- **Accordion**: Collapsible content sections
- **Tabs**: Tabbed content display
- **Sliders**: Product and content sliders

### WooCommerce Features

- **Product Quick View**: View product details in a popup
- **Quantity Buttons**: Easy quantity adjustment
- **Ajax Add to Cart**: Add products to cart without page reload
- **Mini Cart**: Dropdown cart in the header
- **Product Gallery**: Image zoom, lightbox, and slider
- **Variation Swatches**: Visual representation of product variations
- **Filter Accordions**: Collapsible product filters

### Customizing JavaScript

To customize the theme's JavaScript:
1. For small changes, add custom JavaScript through a child theme
2. For larger changes, modify the source files and recompile
3. Use the theme's hooks and filters to modify behavior

## Hooks & Filters <a name="hooks-filters"></a>

AquaLuxe provides numerous hooks and filters to allow for customization without modifying core files.

### Action Hooks

- **aqualuxe_before_header**: Before the header
- **aqualuxe_after_header**: After the header
- **aqualuxe_before_content**: Before the main content
- **aqualuxe_after_content**: After the main content
- **aqualuxe_before_footer**: Before the footer
- **aqualuxe_after_footer**: After the footer
- **aqualuxe_before_sidebar**: Before the sidebar
- **aqualuxe_after_sidebar**: After the sidebar
- **aqualuxe_header_actions**: Inside the header actions area
- **aqualuxe_mobile_menu_after**: After the mobile menu
- **aqualuxe_footer_before**: Before the footer content
- **aqualuxe_footer_after**: After the footer content

### Filter Hooks

- **aqualuxe_header_classes**: Filter header classes
- **aqualuxe_footer_classes**: Filter footer classes
- **aqualuxe_sidebar_classes**: Filter sidebar classes
- **aqualuxe_post_classes**: Filter post classes
- **aqualuxe_product_classes**: Filter product classes
- **aqualuxe_comment_form_args**: Filter comment form arguments
- **aqualuxe_related_products_args**: Filter related products arguments
- **aqualuxe_breadcrumb_args**: Filter breadcrumb arguments

### Using Hooks

Example of using an action hook:

```php
function my_custom_header_content() {
    echo '<div class="custom-header-content">Custom content</div>';
}
add_action( 'aqualuxe_after_header', 'my_custom_header_content' );
```

Example of using a filter hook:

```php
function my_custom_sidebar_classes( $classes ) {
    $classes[] = 'my-custom-class';
    return $classes;
}
add_filter( 'aqualuxe_sidebar_classes', 'my_custom_sidebar_classes' );
```

## Troubleshooting <a name="troubleshooting"></a>

### Common Issues and Solutions

#### Layout Issues

**Issue**: The layout appears broken or elements are misaligned.
**Solution**: 
1. Check if you have any custom CSS that might be interfering
2. Try disabling plugins to see if there's a conflict
3. Reset theme options to defaults and reconfigure

#### Mobile Menu Not Working

**Issue**: The mobile menu doesn't open or close properly.
**Solution**:
1. Check for JavaScript errors in the browser console
2. Make sure jQuery is properly loaded
3. Check if another plugin is conflicting with the menu script

#### WooCommerce Styling Issues

**Issue**: WooCommerce pages don't match the theme style.
**Solution**:
1. Make sure you're using a compatible WooCommerce version
2. Try regenerating the WooCommerce CSS by going to WooCommerce > Status > Tools > Regenerate CSS
3. Check if you have any WooCommerce plugins that might be overriding the styles

#### Performance Issues

**Issue**: The site is loading slowly.
**Solution**:
1. Enable performance optimization features in the theme options
2. Use a caching plugin
3. Optimize images
4. Check for plugins that might be causing slowdowns

#### Translation Issues

**Issue**: Some text is not being translated.
**Solution**:
1. Make sure you're using the correct translation files
2. Check if the text is properly wrapped in translation functions
3. Use WPML's String Translation to translate theme options

### Debug Mode

To troubleshoot issues, you can enable WordPress debug mode:

1. Add the following to your wp-config.php file:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

2. Check the debug.log file in the wp-content directory for errors

## Support <a name="support"></a>

If you need help with the AquaLuxe theme, there are several support options available:

### Documentation

- This documentation covers most aspects of the theme
- Check the FAQ section for common questions
- Video tutorials are available for visual guidance

### Support Channels

- **Support Forum**: Visit our support forum at [support.aqualuxetheme.com](https://support.aqualuxetheme.com)
- **Email Support**: Contact us at support@aqualuxetheme.com
- **Live Chat**: Available on our website during business hours

### Updates

- The theme is regularly updated with new features and bug fixes
- Updates can be installed through the WordPress admin panel
- Make sure to back up your site before updating

### Customization Services

If you need custom modifications beyond what's possible with the theme options:
- Custom development services are available
- Contact us at customization@aqualuxetheme.com for a quote
- Provide detailed requirements for an accurate estimate

---

Thank you for choosing AquaLuxe! We hope this documentation helps you make the most of your theme. If you have any questions or feedback, please don't hesitate to contact us.