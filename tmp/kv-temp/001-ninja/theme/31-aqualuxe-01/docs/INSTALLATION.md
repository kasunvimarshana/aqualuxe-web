# AquaLuxe WordPress Theme Installation Guide

This document provides detailed instructions for installing and setting up the AquaLuxe WordPress theme.

## Requirements

Before installing AquaLuxe, ensure your hosting environment meets the following requirements:

- WordPress 5.9 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (for e-commerce functionality)
- Memory limit of at least 128MB
- Post max size of at least 32MB
- Upload max filesize of at least 32MB
- Max execution time of at least 120 seconds

## Installation

### Method 1: Install via WordPress Admin

1. Log in to your WordPress admin dashboard
2. Navigate to Appearance > Themes
3. Click "Add New"
4. Click "Upload Theme"
5. Click "Choose File" and select the `aqualuxe.zip` file
6. Click "Install Now"
7. After installation completes, click "Activate"

### Method 2: Install via FTP

1. Extract the `aqualuxe.zip` file on your computer
2. Connect to your server using an FTP client
3. Navigate to the `/wp-content/themes/` directory
4. Upload the `aqualuxe` folder to this directory
5. Log in to your WordPress admin dashboard
6. Navigate to Appearance > Themes
7. Find AquaLuxe in the list and click "Activate"

## Post-Installation Setup

### 1. Install Required Plugins

After activating the theme, you'll be prompted to install the recommended plugins. We recommend installing the following:

- WooCommerce (required for e-commerce functionality)
- Contact Form 7 (for contact forms)
- Yoast SEO (for SEO optimization)
- WP Rocket (for performance optimization)

To install these plugins:

1. Navigate to Appearance > Install Plugins
2. Select the plugins you want to install
3. Click "Install" and then "Activate"

### 2. Import Demo Content

To make your site look like the demo, you can import the demo content:

1. Navigate to Tools > Import
2. Click "WordPress" and install the WordPress importer if prompted
3. Click "Run Importer"
4. Upload the `aqualuxe-demo-content.xml` file from the `demo` folder
5. Click "Upload file and import"
6. Map the authors as desired
7. Check "Download and import file attachments"
8. Click "Submit"

### 3. Configure Theme Settings

1. Navigate to Appearance > Customize
2. Configure the following settings:
   - Site Identity: Upload your logo, set site title and tagline
   - Colors: Set your primary and secondary colors
   - Typography: Choose your preferred fonts
   - Header: Configure header layout and navigation
   - Footer: Configure footer layout and widgets
   - Blog: Configure blog layout and settings
   - WooCommerce: Configure shop layout and settings
   - Dark Mode: Configure dark mode settings
   - Multilingual: Configure language settings
3. Click "Publish" to save your changes

### 4. Set Up Homepage

1. Create a new page or edit the existing "Home" page
2. Set the template to "Homepage" in the Page Attributes section
3. Navigate to Settings > Reading
4. Set "Your homepage displays" to "A static page"
5. Select your homepage from the dropdown
6. Click "Save Changes"

### 5. Set Up WooCommerce

If you're using WooCommerce:

1. Complete the WooCommerce setup wizard
2. Navigate to WooCommerce > Settings
3. Configure your store settings (general, products, shipping, payments, etc.)
4. Create product categories and add products
5. Configure tax settings if applicable

### 6. Set Up Menus

1. Navigate to Appearance > Menus
2. Create the following menus:
   - Primary Menu: Main navigation menu
   - Footer Menu: Links in the footer
   - Mobile Menu: Navigation for mobile devices
3. Assign each menu to its respective location

### 7. Configure Widgets

1. Navigate to Appearance > Widgets
2. Add widgets to the following areas:
   - Sidebar: Appears on blog and archive pages
   - Footer 1-4: Appears in the footer
   - Shop Sidebar: Appears on shop pages

## Troubleshooting

### Common Issues

#### Theme doesn't look like the demo
- Make sure you've imported the demo content
- Check that you've configured the theme settings correctly
- Verify that all required plugins are installed and activated

#### WooCommerce pages look incorrect
- Navigate to WooCommerce > Status > Tools
- Click "Regenerate product lookup tables"
- Click "Regenerate product attributes lookup table"
- Click "Create missing WooCommerce pages"

#### Performance issues
- Install and configure a caching plugin like WP Rocket
- Optimize your images using WebP format
- Enable GZIP compression on your server
- Use a CDN for static assets

#### Multilingual issues
- Verify that your language files are correctly installed
- Check that your translations are complete
- Ensure that your language switcher is correctly configured

## Support

If you encounter any issues during installation or setup, please contact our support team:

- Email: support@aqualuxe.example.com
- Support Forum: https://aqualuxe.example.com/support
- Documentation: https://aqualuxe.example.com/docs