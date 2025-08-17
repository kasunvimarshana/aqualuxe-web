# AquaLuxe WordPress Theme - Installation Instructions

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Demo Content Import](#demo-content-import)
4. [Plugin Setup](#plugin-setup)
5. [Initial Configuration](#initial-configuration)
6. [Troubleshooting](#troubleshooting)

## Requirements

Before installing the AquaLuxe theme, please ensure your hosting environment meets the following requirements:

### Server Requirements
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WordPress 5.8 or higher
- PHP memory limit of at least 128MB
- max_execution_time of at least 120 seconds

### Recommended Plugins
- WooCommerce 6.0 or higher (optional but recommended)
- Elementor Page Builder (optional)
- Contact Form 7 (optional)
- Yoast SEO or Rank Math (optional)
- WP Rocket or another caching plugin (optional)

### Browser Support
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Opera (latest)

## Installation

### Standard Installation

1. **Download the Theme**
   - Log in to your account at [aqualuxe.com](https://aqualuxe.com)
   - Navigate to "Downloads" or "My Account"
   - Download the `aqualuxe.zip` file

2. **Upload and Install**
   - Log in to your WordPress admin panel
   - Navigate to Appearance > Themes
   - Click "Add New"
   - Click "Upload Theme"
   - Choose the `aqualuxe.zip` file you downloaded
   - Click "Install Now"
   - After installation completes, click "Activate"

### FTP Installation

If you encounter issues with the standard installation method (e.g., file size limitations), you can use FTP:

1. **Extract the Theme**
   - Extract the `aqualuxe.zip` file on your computer

2. **Upload via FTP**
   - Connect to your server using an FTP client (e.g., FileZilla)
   - Navigate to `/wp-content/themes/` directory
   - Upload the entire `aqualuxe-theme` folder to this directory

3. **Activate the Theme**
   - Log in to your WordPress admin panel
   - Navigate to Appearance > Themes
   - Find "AquaLuxe" and click "Activate"

## Demo Content Import

AquaLuxe comes with pre-built demo content to help you get started quickly:

### Using the Setup Wizard

1. **Access the Setup Wizard**
   - After activating the theme, you'll see a notice to start the setup wizard
   - Click "Start Setup Wizard"
   - Alternatively, go to Appearance > AquaLuxe Setup

2. **Follow the Wizard Steps**
   - Welcome screen: Click "Let's Go!"
   - Plugin Installation: Install and activate recommended plugins
   - Demo Import: Choose a demo to import
   - Theme Options: Configure basic theme options
   - Finalization: Complete the setup

### Manual Demo Import

If you prefer to import the demo content manually:

1. **Install Required Plugins**
   - Go to Plugins > Add New
   - Install and activate the following plugins:
     - One Click Demo Import
     - WooCommerce (if you're importing the shop demo)

2. **Import Demo Content**
   - Go to Appearance > Import Demo Data
   - Choose the demo you want to import
   - Check the options you want to import (content, widgets, customizer settings)
   - Click "Import Demo Data"
   - Wait for the import process to complete

### Available Demos

AquaLuxe includes several demo options:

1. **Main Demo**
   - Complete website with all features
   - Includes homepage, shop, blog, and other pages
   - Requires WooCommerce

2. **Basic Demo**
   - Simplified version without WooCommerce
   - Includes homepage, blog, and basic pages

3. **Aquarium Shop**
   - Specialized for aquarium equipment and supplies
   - Requires WooCommerce

4. **Fish Store**
   - Specialized for live fish sales
   - Requires WooCommerce

5. **Aquatic Plants**
   - Specialized for aquatic plants
   - Requires WooCommerce

## Plugin Setup

### WooCommerce Setup

If you plan to use WooCommerce with AquaLuxe:

1. **Install and Activate WooCommerce**
   - Go to Plugins > Add New
   - Search for "WooCommerce"
   - Click "Install Now"
   - After installation, click "Activate"

2. **Run the WooCommerce Setup Wizard**
   - After activation, the WooCommerce setup wizard will start automatically
   - Follow the steps to configure:
     - Store location
     - Currency
     - Shipping options
     - Payment methods
     - Tax settings

3. **Configure WooCommerce Settings**
   - Go to WooCommerce > Settings
   - Review and adjust settings as needed

### Elementor Setup (Optional)

If you want to use Elementor for page building:

1. **Install and Activate Elementor**
   - Go to Plugins > Add New
   - Search for "Elementor"
   - Click "Install Now"
   - After installation, click "Activate"

2. **Configure Elementor Settings**
   - Go to Elementor > Settings
   - Review and adjust settings as needed

3. **Install Elementor Pro (Optional)**
   - If you have Elementor Pro, install and activate it
   - Enter your license key in Elementor > License

### Contact Form 7 Setup (Optional)

If you want to use Contact Form 7 for forms:

1. **Install and Activate Contact Form 7**
   - Go to Plugins > Add New
   - Search for "Contact Form 7"
   - Click "Install Now"
   - After installation, click "Activate"

2. **Create a Contact Form**
   - Go to Contact > Add New
   - Configure your form
   - Copy the shortcode
   - Add the shortcode to your contact page

### SEO Plugin Setup (Optional)

For better search engine optimization:

1. **Install and Activate an SEO Plugin**
   - Go to Plugins > Add New
   - Search for "Yoast SEO" or "Rank Math"
   - Click "Install Now"
   - After installation, click "Activate"

2. **Configure SEO Settings**
   - Follow the plugin's setup wizard
   - Configure basic SEO settings

## Initial Configuration

After installing the theme and plugins, you should configure the following settings:

### General Settings

1. **Site Title and Tagline**
   - Go to Settings > General
   - Set your site title and tagline
   - Configure timezone and date format

2. **Reading Settings**
   - Go to Settings > Reading
   - Set your homepage display (static page or latest posts)
   - If using a static page, select your homepage and posts page

3. **Permalink Settings**
   - Go to Settings > Permalinks
   - Choose a permalink structure (recommended: Post name)

### Theme Customization

1. **Access the Customizer**
   - Go to Appearance > Customize

2. **Configure Theme Options**
   - Colors: Set primary and secondary colors
   - Typography: Choose fonts and sizes
   - Header: Configure header layout and options
   - Footer: Configure footer layout and options
   - Layout: Set container width and sidebar options
   - Blog: Configure blog layout and options
   - WooCommerce: Configure shop settings (if using WooCommerce)
   - Performance: Enable optimization options

### Menu Setup

1. **Create Menus**
   - Go to Appearance > Menus
   - Create a new menu
   - Add pages, categories, or custom links
   - Assign the menu to a location (Primary Menu, Footer Menu, etc.)

2. **Configure Menu Options**
   - Enable/disable menu items
   - Create dropdown menus
   - Add custom CSS classes if needed

### Widget Setup

1. **Configure Widgets**
   - Go to Appearance > Widgets
   - Add widgets to the available widget areas:
     - Sidebar
     - Footer 1-4
     - Shop Sidebar (if using WooCommerce)

2. **Recommended Widgets**
   - Search
   - Recent Posts
   - Categories
   - Tag Cloud
   - Custom HTML (for custom content)
   - WooCommerce widgets (if using WooCommerce)

## Troubleshooting

### Common Installation Issues

#### Theme Upload Fails

**Issue**: You receive an error when trying to upload the theme zip file.

**Solution**:
1. Check the file size limit of your hosting provider
2. Try the FTP installation method instead
3. Contact your hosting provider to increase the upload limit

#### White Screen After Activation

**Issue**: You see a white screen or error message after activating the theme.

**Solution**:
1. Increase PHP memory limit in wp-config.php:
   ```php
   define('WP_MEMORY_LIMIT', '256M');
   ```
2. Enable debugging to see the error:
   ```php
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);
   define('WP_DEBUG_DISPLAY', false);
   ```
3. Check for plugin conflicts by deactivating all plugins

#### Demo Import Fails

**Issue**: The demo import process fails or times out.

**Solution**:
1. Increase PHP execution time in php.ini or .htaccess:
   ```
   max_execution_time = 300
   ```
2. Try importing parts of the demo separately (content, widgets, customizer settings)
3. Make sure you have all required plugins activated

#### WooCommerce Templates Not Working

**Issue**: WooCommerce pages don't display correctly.

**Solution**:
1. Make sure WooCommerce is activated
2. Go to WooCommerce > Status > Tools
3. Click "Clear template cache"
4. Regenerate product thumbnails if needed

### Getting Help

If you encounter any issues not covered in this guide:

1. **Check Documentation**
   - Visit our documentation site: [docs.aqualuxe.com](https://docs.aqualuxe.com)

2. **Support Forum**
   - Post your question in our support forum: [forum.aqualuxe.com](https://forum.aqualuxe.com)

3. **Submit a Support Ticket**
   - For premium support, submit a ticket: [support.aqualuxe.com](https://support.aqualuxe.com)
   - Include the following information:
     - WordPress version
     - PHP version
     - Active plugins
     - Steps to reproduce the issue
     - Screenshots if applicable

4. **Contact Information**
   - Email: support@aqualuxe.com
   - Hours: Monday-Friday, 9am-5pm EST