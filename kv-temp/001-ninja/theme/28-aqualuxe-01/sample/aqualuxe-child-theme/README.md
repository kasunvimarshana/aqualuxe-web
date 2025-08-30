# AquaLuxe Child Theme

A child theme for the AquaLuxe WordPress theme, providing customization options without modifying the parent theme.

## Description

This child theme allows you to customize the AquaLuxe theme while maintaining the ability to update the parent theme without losing your modifications. It includes examples of common customizations such as:

- Custom CSS styles
- Custom JavaScript functionality
- Additional widget areas
- WooCommerce customizations
- Custom shortcodes
- Function overrides

## Installation

1. Make sure the parent AquaLuxe theme is installed in your WordPress themes directory.
2. Upload the `aqualuxe-child` folder to your WordPress themes directory (`wp-content/themes/`).
3. Activate the AquaLuxe Child theme from the WordPress admin dashboard (Appearance > Themes).

## Customization

### CSS Customization

Edit the `style.css` file to add your custom styles. The file already includes some example customizations.

### PHP Customization

The `functions.php` file includes examples of:

- Enqueuing parent and child theme styles
- Adding custom widget areas
- Adding WooCommerce customizations
- Creating custom shortcodes
- Overriding parent theme functions

### JavaScript Customization

The `js/custom.js` file includes examples of:

- Smooth scrolling
- Product gallery enhancements
- Accessibility improvements
- Custom effects and animations

## Usage

### Custom Shortcode

This child theme includes a custom shortcode to display a featured product:

```
[featured_product id="123"]
```

Replace `123` with the ID of the product you want to feature.

### Custom Widget Area

A custom sidebar is registered and can be used in your templates:

```php
<?php if (is_active_sidebar('custom-sidebar')) : ?>
    <div class="custom-sidebar-wrapper">
        <?php dynamic_sidebar('custom-sidebar'); ?>
    </div>
<?php endif; ?>
```

## Structure

```
aqualuxe-child/
├── js/
│   └── custom.js
├── languages/
│   └── (translation files)
├── functions.php
├── README.md
├── screenshot.png
└── style.css
```

## Support

For support with this child theme, please contact:

- Email: support@yoursite.com
- Website: https://yoursite.com

## License

This child theme is licensed under the GPL v2 or later, just like its parent theme AquaLuxe.