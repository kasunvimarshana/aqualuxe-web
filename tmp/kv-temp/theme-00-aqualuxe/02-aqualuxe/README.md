# AquaLuxe - Premium WooCommerce Child Theme

AquaLuxe is a premium WooCommerce child theme built on the official Storefront parent theme. Designed specifically for ornamental fish businesses, it offers a next-generation, elegant UI with seamless UX tailored for a high-end aquatic experience.

## Features

### Design & UI/UX
- Clean, modern, mobile-first responsive design
- Refined typography with Google Fonts (Inter & Playfair Display)
- Micro-interactions and smooth transitions
- Accessibility (ARIA) support
- Custom color palette with CSS variables

### WooCommerce Compatibility
- Full WooCommerce integration (shop, product, cart, checkout, account pages)
- Support for digital and physical goods
- Variable and grouped products support
- AJAX add-to-cart functionality
- Product quick view
- Custom product grid/list view toggle
- Built-in breadcrumbs

### Performance & SEO
- Schema markup implementation
- Semantic HTML5 structure
- Lazy loading for images
- Open Graph metadata for rich social sharing
- Minified assets for faster loading
- SEO-optimized template structure

### Security
- Content Security Policy (CSP)
- XSS protection
- User enumeration prevention
- REST API security for non-authenticated users
- File upload sanitization

### Customization
- Theme Customizer integration for logo, colors, typography
- Custom widget areas (sidebar, footer, shop filters)
- Sticky header & mobile-friendly navigation
- Custom mega menu support
- Demo content import system

## Installation

### Prerequisites
- WordPress 5.8 or higher
- PHP 7.4 or higher
- WooCommerce 6.0 or higher
- Storefront theme (parent theme)

### Installation Steps

1. **Install Storefront Parent Theme**
   - Go to Appearance > Themes in your WordPress admin
   - Click "Add New"
   - Search for "Storefront"
   - Install and activate the Storefront theme

2. **Install AquaLuxe Child Theme**
   - Download the AquaLuxe theme files
   - Go to Appearance > Themes in your WordPress admin
   - Click "Add New"
   - Click "Upload Theme"
   - Choose the AquaLuxe theme ZIP file
   - Click "Install Now"
   - Click "Activate"

3. **Install WooCommerce (if not already installed)**
   - Go to Plugins > Add New
   - Search for "WooCommerce"
   - Install and activate WooCommerce
   - Follow the setup wizard to configure your store

4. **Configure Theme Settings**
   - Go to Appearance > Customize
   - Adjust colors, typography, and layout settings
   - Upload your logo
   - Configure menu locations

5. **Import Demo Content (Optional)**
   - Go to Appearance > Demo Content
   - Click "Import Demo Content"
   - Confirm the import when prompted

## Customization

### Theme Customizer Options
- **Colors**: Primary, secondary, and accent colors
- **Typography**: Font families and sizes
- **Layout**: Sidebar positions, container width
- **Header**: Logo, tagline, navigation
- **Footer**: Widget areas, copyright text

### Adding Custom CSS
You can add custom CSS in several ways:
1. Appearance > Customize > Additional CSS
2. Create a child theme and add CSS to style.css
3. Use a custom CSS plugin

### Modifying Templates
To modify templates:
1. Copy the template file from `wp-content/themes/storefront/templates/` to `wp-content/themes/aqualuxe/templates/`
2. Make your modifications in the child theme file
3. The child theme version will override the parent theme

## Demo Content

The theme includes a demo content importer that will create:
- Sample products (Goldfish, Discus, Angelfish)
- Sample pages (Home, About, Contact)
- Sample menus and widgets
- Sample settings

To import demo content:
1. Go to Appearance > Demo Content
2. Click "Import Demo Content"
3. Confirm the import when prompted

## Performance Optimization

The theme includes several performance optimizations:
- Lazy loading for images
- Minified CSS and JavaScript
- WebP image format support
- Database query optimization
- Removal of unnecessary WordPress features

## Security Features

The theme implements several security measures:
- Content Security Policy
- XSS protection
- User enumeration prevention
- REST API security
- File upload sanitization

## Support

For support, please visit our [support page](https://example.com/support) or contact us at support@aqualuxe.com.

## Changelog

### 1.0.0
- Initial release

## License

This theme is licensed under the GNU General Public License v2 or later.

## Credits

- [Storefront](https://woocommerce.com/storefront/) by WooCommerce
- [WordPress](https://wordpress.org/)
- [WooCommerce](https://woocommerce.com/)

## Copyright

© 2023 AquaLuxe. All rights reserved.