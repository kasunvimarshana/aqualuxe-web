# AquaLuxe Child Theme

A child theme for the AquaLuxe WordPress theme, allowing for customization without modifying the parent theme.

## Overview

The AquaLuxe Child theme inherits all functionality and styling from the parent AquaLuxe theme while allowing you to make customizations that won't be lost during parent theme updates.

## Installation

1. Make sure the parent AquaLuxe theme is installed in your WordPress themes directory.
2. Upload the `aqualuxe-child` folder to your WordPress themes directory (`wp-content/themes/`).
3. Activate the AquaLuxe Child theme from the WordPress admin dashboard under Appearance > Themes.

## Customization

### CSS Customization

The child theme includes two methods for CSS customization:

1. **style.css**: You can add custom CSS directly to the `style.css` file in the root of the child theme.
2. **Custom CSS file**: For more extensive customizations, use the `assets/css/custom.css` file.

### PHP Customization

The `functions.php` file includes examples of how to:

- Enqueue parent and child theme styles and scripts
- Add custom image sizes
- Add custom body classes
- Override parent theme functions
- Add WooCommerce-specific functionality

### JavaScript Customization

Custom JavaScript functionality can be added to the `assets/js/custom.js` file. The file already includes examples for:

- Header scroll effects
- Smooth scrolling for anchor links
- Mobile submenu toggles
- WooCommerce quick view functionality
- Product image zoom effects

## Structure

```
aqualuxe-child/
├── assets/
│   ├── css/
│   │   └── custom.css
│   ├── js/
│   │   └── custom.js
│   └── img/
├── functions.php
├── style.css
├── screenshot.png
└── README.md
```

## Template Overrides

To override a template from the parent theme:

1. Locate the template file in the parent theme.
2. Create a file with the same name in the child theme.
3. Customize the new file as needed.

For WooCommerce templates, create a `woocommerce` folder in the child theme and place your overridden templates there, maintaining the same directory structure as in the WooCommerce plugin.

## Examples Included

The child theme includes examples of:

- Custom button styles
- Card component styling
- Header scroll effects
- WooCommerce product customizations
- Quick view modal functionality
- Responsive design adjustments

## Support

For support with the AquaLuxe Child theme, please contact the AquaLuxe theme support team at support@aqualuxe.example.com.