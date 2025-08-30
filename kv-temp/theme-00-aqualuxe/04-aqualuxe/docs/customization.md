# AquaLuxe Customization Guide

## Customizer Options

AquaLuxe provides several customization options through the WordPress Customizer. To access these options:

1. Go to **Appearance > Customize** in your WordPress admin panel
2. Use the left sidebar to navigate through the different customization sections

### Color Options

- **Primary Color**: Sets the main color for buttons, links, and other primary elements
- **Secondary Color**: Sets the secondary color for hover states and other secondary elements

### Layout Options

- **Product Columns**: Choose the number of product columns to display on shop pages (2-5 columns)
- **Sidebar Position**: Choose where to display the sidebar (left, right, or none)

### Typography Options

- **Heading Font**: Select the font to use for headings
- **Body Font**: Select the font to use for body text

## Custom CSS

If you need to add custom CSS that isn't available through the Customizer:

1. Go to **Appearance > Customize**
2. Click **Additional CSS**
3. Add your custom CSS code
4. Click **Publish**

## Custom Hooks

AquaLuxe provides several custom hooks that allow you to add your own functionality:

### Action Hooks

- `aqualuxe_before_header`: Before the header
- `aqualuxe_after_header`: After the header
- `aqualuxe_before_content`: Before the main content
- `aqualuxe_after_content`: After the main content
- `aqualuxe_before_footer`: Before the footer
- `aqualuxe_after_footer`: After the footer

### Filter Hooks

- `aqualuxe_site_title`: Filter the site title
- `aqualuxe_site_description`: Filter the site description
- `aqualuxe_breadcrumb_args`: Filter the breadcrumb arguments

## Child Theme Customization

For extensive customizations, we recommend creating a child theme of AquaLuxe. This ensures your customizations won't be lost when updating the theme.

To create a child theme:

1. Create a new folder in `/wp-content/themes/` named `aqualuxe-child`
2. Create a `style.css` file in the child theme folder with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxe.com
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com
Template: aqualuxe
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe-child
*/
```

3. Create a `functions.php` file in the child theme folder
4. Activate the child theme from **Appearance > Themes**

## Template Files

AquaLuxe uses template files to control the display of content. You can override any template file by creating a copy in your child theme:

1. Copy the template file from `/wp-content/themes/aqualuxe/` to `/wp-content/themes/aqualuxe-child/`
2. Make your changes to the copied file
3. The child theme version will be used instead of the parent theme version

Common template files you might want to customize:

- `header.php`: Site header
- `footer.php`: Site footer
- `index.php`: Blog index
- `single.php`: Single post
- `page.php`: Static pages
- `archive.php`: Archive pages

## WooCommerce Customization

AquaLuxe includes WooCommerce template files in the `/woocommerce/` folder. You can override these templates in your child theme:

1. Copy the template file from `/wp-content/themes/aqualuxe/woocommerce/` to `/wp-content/themes/aqualuxe-child/woocommerce/`
2. Make your changes to the copied file
3. The child theme version will be used instead of the parent theme version

Common WooCommerce template files you might want to customize:

- `archive-product.php`: Shop page
- `single-product.php`: Single product page
- `cart.php`: Cart page
- `checkout.php`: Checkout page
- `myaccount.php`: My account page

## Adding Custom Functionality

To add custom functionality to your site, you can use the `functions.php` file in your child theme:

```php
<?php
// Add custom functionality here
function my_custom_function() {
    // Your custom code here
}
add_action('init', 'my_custom_function');
```

## Performance Optimization

To optimize the performance of your site with AquaLuxe:

1. Use a caching plugin like WP Rocket or W3 Total Cache
2. Optimize your images using a plugin like Smush or EWWW Image Optimizer
3. Minify CSS and JavaScript files
4. Use a Content Delivery Network (CDN) for static assets
5. Enable Gzip compression on your server

## SEO Optimization

AquaLuxe includes built-in SEO features, but you can further optimize your site:

1. Install an SEO plugin like Yoast SEO or Rank Math
2. Optimize your page titles and meta descriptions
3. Use descriptive alt text for images
4. Create an XML sitemap
5. Submit your sitemap to Google Search Console

## Accessibility

AquaLuxe follows accessibility best practices, but you can improve accessibility further:

1. Use descriptive link text
2. Provide alternative text for images
3. Ensure sufficient color contrast
4. Use proper heading structure
5. Test with screen readers

## Support

If you need help customizing AquaLuxe, please contact our support team at support@aqualuxe.com or visit our support forum at https://aqualuxe.com/support.