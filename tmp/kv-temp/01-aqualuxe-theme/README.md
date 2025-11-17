# AquaLuxe WooCommerce Child Theme

A premium, responsive WooCommerce child theme designed specifically for ornamental fish businesses. Built on the Storefront parent theme with modern design principles and aquatic-inspired aesthetics.

## Features

### Design & Layout
- **Responsive Design**: Fully responsive layout that adapts to all screen sizes
- **Modern UI**: Clean, contemporary design with aquatic color scheme
- **Custom Typography**: Google Fonts integration with Inter font family
- **Smooth Animations**: CSS transitions and hover effects
- **Mobile-First**: Optimized for mobile devices with touch-friendly navigation

### WooCommerce Integration
- **Product Showcase**: Beautiful product grid with hover effects
- **Custom Product Badges**: Sale, Featured, and Out of Stock badges
- **Quick View**: AJAX-powered product quick view functionality
- **Enhanced Product Pages**: Custom tabs for care instructions and compatibility
- **Smart Recommendations**: Related products and cart recommendations
- **Custom Checkout**: Streamlined checkout process with fish-specific fields

### Performance & SEO
- **Optimized Loading**: Lazy loading for images and content
- **Clean Code**: Follows WordPress coding standards
- **SEO Friendly**: Semantic HTML structure
- **Fast Loading**: Optimized CSS and JavaScript
- **Accessibility**: WCAG compliant design elements

### Customization Options
- **Theme Customizer**: Easy color and typography customization
- **Layout Options**: Configurable container width and product columns
- **Hero Section**: Customizable hero section with background image support
- **Widget Areas**: Multiple footer widget areas
- **Custom Menus**: Primary and footer navigation menus

## Installation

### Prerequisites
- WordPress 5.0 or higher
- WooCommerce plugin installed and activated
- Storefront parent theme installed

### Installation Steps

1. **Download the theme files**
   - Download all theme files to your computer

2. **Upload to WordPress**
   \`\`\`
   wp-content/themes/aqualuxe/
   \`\`\`

3. **Activate the theme**
   - Go to Appearance > Themes in your WordPress admin
   - Find "AquaLuxe Child" and click "Activate"

4. **Configure the theme**
   - Go to Appearance > Customize to configure colors, typography, and layout
   - Go to Appearance > AquaLuxe Options for additional settings

## File Structure

\`\`\`
aqualuxe/
├── style.css                          # Main stylesheet
├── functions.php                      # Theme functions
├── index.php                          # Main template
├── header.php                         # Header template
├── footer.php                         # Footer template
├── woocommerce.php                    # WooCommerce template
├── single-product.php                 # Single product template
├── assets/
│   ├── css/
│   │   ├── responsive.css             # Responsive styles
│   │   └── admin.css                  # Admin styles
│   └── js/
│       ├── custom.js                  # Frontend JavaScript
│       └── admin.js                   # Admin JavaScript
├── inc/
│   ├── customizer.php                 # Extended customizer settings
│   ├── woocommerce-functions.php      # WooCommerce customizations
│   └── admin-functions.php            # Admin customizations
├── template-parts/
│   └── hero-section.php               # Hero section template
├── languages/
│   └── aqualuxe.pot                   # Translation template
└── README.md                          # This file
\`\`\`

## Customization

### Colors
The theme uses CSS custom properties for easy color customization:

\`\`\`css
:root {
  --primary-color: #0077be;    /* Ocean Blue */
  --secondary-color: #00a8cc;  /* Aqua Blue */
  --accent-color: #ffd700;     /* Gold */
  --dark-blue: #003d5c;        /* Deep Ocean */
  --light-blue: #e6f3ff;       /* Light Aqua */
}
\`\`\`

### Typography
Default fonts can be changed in the customizer or by modifying the CSS:

\`\`\`css
body {
  font-family: 'Inter', sans-serif;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Inter', sans-serif;
}
\`\`\`

### Layout
Container width and product columns are customizable through the WordPress Customizer.

## WooCommerce Customizations

### Product Meta Fields
The theme adds custom meta fields for fish-specific information:
- Care Instructions
- Tank Compatibility
- Water Parameters
- Adult Size
- Temperament

### Custom Product Tabs
Additional product tabs are automatically added:
- Care Instructions
- Tank Compatibility

### Enhanced Checkout
Custom checkout fields include:
- Fish Keeping Experience level

## JavaScript Features

### Mobile Menu
Responsive hamburger menu with smooth animations.

### Quick View
AJAX-powered product quick view without page reload.

### Smooth Scrolling
Smooth scrolling for anchor links.

### Lazy Loading
Intersection Observer API for efficient image loading.

### Cart Updates
Animated cart counter updates.

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Performance

### Optimization Features
- Minified CSS and JavaScript
- Optimized images with lazy loading
- Efficient database queries
- Cached customizer settings

### Loading Speed
- First Contentful Paint: < 1.5s
- Largest Contentful Paint: < 2.5s
- Cumulative Layout Shift: < 0.1

## Accessibility

The theme follows WCAG 2.1 AA guidelines:
- Semantic HTML structure
- Proper heading hierarchy
- Alt text for images
- Keyboard navigation support
- Screen reader compatibility
- Color contrast compliance

## SEO Features

- Semantic HTML5 structure
- Proper meta tags
- Schema.org markup
- Optimized images
- Fast loading times
- Mobile-friendly design

## Translation Ready

The theme is fully translation ready with:
- Text domain: `aqualuxe`
- POT file included
- All strings properly escaped
- RTL language support

## Support

For theme support and customization:
- Documentation: Available in theme files
- WordPress Codex: https://codex.wordpress.org/
- WooCommerce Docs: https://docs.woocommerce.com/

## Changelog

### Version 1.0.0
- Initial release
- Responsive design implementation
- WooCommerce integration
- Custom product fields
- Theme customizer options
- Performance optimizations

## License

This theme is licensed under the GPL v2 or later.

## Credits

- Built on Storefront theme by Automattic
- Google Fonts: Inter font family
- Icons: Custom CSS icons and Unicode symbols
- Inspiration: Modern e-commerce design trends

---

**AquaLuxe** - Premium Ornamental Fish WooCommerce Theme
