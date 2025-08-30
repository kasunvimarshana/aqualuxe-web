# Installation Instructions

## Prerequisites

Before installing the AquaLuxe theme, ensure you have the following:

1. WordPress 5.0 or higher
2. WooCommerce 4.0 or higher
3. Storefront theme (parent theme)
4. PHP 7.2 or higher
5. MySQL 5.6 or higher

## Installation Steps

### 1. Install Storefront Parent Theme

1. Log in to your WordPress admin dashboard
2. Go to "Appearance > Themes"
3. Click "Add New"
4. Search for "Storefront"
5. Click "Install" and then "Activate"

### 2. Install AquaLuxe Child Theme

#### Method 1: WordPress Admin Upload

1. Download the AquaLuxe theme package
2. Go to "Appearance > Themes" in your WordPress admin
3. Click "Add New" then "Upload Theme"
4. Choose the AquaLuxe theme .zip file
5. Click "Install Now"
6. Click "Activate"

#### Method 2: Manual Installation

1. Extract the AquaLuxe theme files
2. Upload the `aqualuxe` folder to your `/wp-content/themes/` directory
3. Go to "Appearance > Themes" in your WordPress admin
4. Find "AquaLuxe" in the list of themes
5. Click "Activate"

### 3. Initial Setup

1. After activation, go to "Appearance > Customize"
2. Configure the theme options:
   - Site Identity (logo, title, tagline)
   - Colors (primary, secondary)
   - Typography (fonts, sizes)
   - Header options (sticky header)
3. Publish your changes

### 4. WooCommerce Setup

1. Go to "WooCommerce > Settings"
2. Configure your store settings:
   - General settings
   - Products settings
   - Tax settings
   - Shipping zones
   - Payment gateways
3. Import demo products (optional):
   - Go to "Appearance > Import Demo Content"
   - Click "Import Demo Content"

### 5. Recommended Plugins

For optimal performance and functionality, we recommend installing:

- **WooCommerce** (required)
- **Regenerate Thumbnails** (for proper image sizing)
- **WP Super Cache** or **W3 Total Cache** (for caching)
- **Yoast SEO** or **Rank Math** (for SEO)
- **Contact Form 7** (for contact forms)

## Customization

### Customizer Options

AquaLuxe provides extensive customization options through the WordPress Customizer:

1. Go to "Appearance > Customize"
2. Explore the available sections:
   - Site Identity
   - Colors
   - Typography
   - Header Options
   - WooCommerce Options

### Child Theme Modifications

As this is a child theme, you can safely make modifications by:

1. Creating a child theme folder
2. Adding custom CSS to `style.css`
3. Adding custom functions to `functions.php`
4. Overriding template files by copying them from the parent theme

## Troubleshooting

### Common Issues

1. **Theme not activating**: Ensure Storefront is installed and activated first
2. **Missing styles**: Check that all files were uploaded correctly
3. **WooCommerce compatibility**: Ensure you're using compatible versions

### Support

If you encounter any issues:

1. Check the [documentation](README.md)
2. Review the [changelog](CHANGELOG.md)
3. Contact support through the theme repository

## Updates

To update the theme:

1. Download the latest version
2. Backup your current theme files
3. Replace the theme files via FTP or your hosting file manager
4. Check for any breaking changes in the changelog

**Note**: Always backup your site before performing updates.