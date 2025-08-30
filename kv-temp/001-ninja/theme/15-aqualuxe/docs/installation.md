# AquaLuxe WordPress Theme - Installation Guide

This guide will walk you through the process of installing and setting up the AquaLuxe WordPress theme for your aquarium business website.

## Table of Contents
1. [Requirements](#requirements)
2. [Installation](#installation)
3. [Demo Content](#demo-content)
4. [Initial Setup](#initial-setup)
5. [Plugin Recommendations](#plugin-recommendations)
6. [Troubleshooting](#troubleshooting)

## Requirements <a name="requirements"></a>

Before installing AquaLuxe, ensure your hosting environment meets these requirements:

- WordPress 5.6 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Memory limit of at least 128MB
- Post max size of at least 64MB
- Upload max filesize of at least 64MB
- Max execution time of at least 120 seconds
- WooCommerce 5.0 or higher (if using e-commerce features)

## Installation <a name="installation"></a>

### Method 1: WordPress Admin Panel

1. Log in to your WordPress admin panel
2. Navigate to **Appearance > Themes**
3. Click the **Add New** button at the top of the page
4. Click the **Upload Theme** button
5. Click **Choose File** and select the `aqualuxe.zip` file from your computer
6. Click **Install Now**
7. After installation completes, click **Activate** to activate the theme

### Method 2: FTP Upload

1. Extract the `aqualuxe.zip` file on your computer
2. Using an FTP client (like FileZilla), connect to your web server
3. Navigate to the `/wp-content/themes/` directory
4. Upload the extracted `aqualuxe` folder to this directory
5. Log in to your WordPress admin panel
6. Navigate to **Appearance > Themes**
7. Find AquaLuxe in the list of available themes
8. Click the **Activate** button

## Demo Content <a name="demo-content"></a>

AquaLuxe comes with demo content to help you get started quickly. To import the demo content:

### Prerequisites
1. Install and activate the WordPress Importer plugin:
   - Go to **Plugins > Add New**
   - Search for "WordPress Importer"
   - Click **Install Now** and then **Activate**

### Import Process
1. Go to **Tools > Import**
2. Click on **WordPress** (install the importer if prompted)
3. Click **Choose File** and select the `demo-content.xml` file from the `demo` folder in the theme package
4. Click **Upload file and import**
5. On the next screen:
   - Set the author for imported content
   - Check the box to **Download and import file attachments**
   - Click **Submit**
6. Wait for the import process to complete

### After Import
1. Go to **Settings > Reading**
2. Set **Your homepage displays** to **A static page**
3. Select **Home** for the Homepage and **Blog** for the Posts page
4. Click **Save Changes**

## Initial Setup <a name="initial-setup"></a>

After activating AquaLuxe, follow these steps to set up your site:

### 1. Theme Customization
1. Go to **Appearance > Customize**
2. Configure the following settings:
   - **Site Identity**: Upload your logo, set site title and tagline
   - **Colors**: Choose your color scheme
   - **Typography**: Select fonts for headings and body text
   - **Layout**: Configure layout options
   - **Header**: Set up header options
   - **Footer**: Configure footer options
   - **Blog**: Set blog display options
   - **WooCommerce**: Configure shop settings (if using WooCommerce)
3. Click **Publish** to save your changes

### 2. Menu Setup
1. Go to **Appearance > Menus**
2. Create a new menu or edit an existing one
3. Add pages, categories, custom links, etc. to your menu
4. Under **Menu Settings**, select the display location (e.g., Primary Menu)
5. Click **Save Menu**

### 3. Widget Setup
1. Go to **Appearance > Widgets**
2. Add widgets to the available widget areas:
   - Blog Sidebar
   - Shop Sidebar
   - Footer 1-4
3. Configure each widget as needed
4. Click **Save** for each widget

### 4. Homepage Setup
If you didn't import the demo content or want to create a custom homepage:
1. Go to **Pages > Add New**
2. Create a new page titled "Home"
3. Select the **Front Page** template from the Page Attributes section
4. Add your content using the block editor
5. Click **Publish**
6. Go to **Settings > Reading**
7. Set **Your homepage displays** to **A static page**
8. Select your new "Home" page as the homepage
9. Click **Save Changes**

## Plugin Recommendations <a name="plugin-recommendations"></a>

AquaLuxe works best with these recommended plugins:

### Essential Plugins
- **WooCommerce**: For e-commerce functionality
- **Yoast SEO** or **Rank Math**: For search engine optimization
- **Contact Form 7** or **WPForms**: For contact forms
- **Wordfence Security**: For website security
- **UpdraftPlus**: For backups

### Optional Plugins
- **WP Super Cache** or **W3 Total Cache**: For performance optimization
- **WPML** or **Polylang**: For multilingual support
- **Advanced Custom Fields**: For extended customization
- **WooCommerce Product Add-Ons**: For customizable products
- **WooCommerce Subscriptions**: For recurring payments (maintenance services)

## Troubleshooting <a name="troubleshooting"></a>

### Common Issues and Solutions

#### White Screen After Activation
1. Increase PHP memory limit in your wp-config.php file:
   ```php
   define('WP_MEMORY_LIMIT', '256M');
   ```
2. Disable all plugins and re-activate them one by one
3. Switch to a default WordPress theme, then back to AquaLuxe

#### Styling Issues
1. Clear your browser cache
2. Clear any caching plugins
3. Regenerate CSS files by going to **Appearance > Customize** and clicking **Publish**

#### Demo Content Import Fails
1. Increase PHP memory limit and max execution time
2. Import in smaller batches by editing the XML file
3. Check for and resolve any plugin conflicts

#### WooCommerce Integration Issues
1. Ensure you're using a compatible WooCommerce version
2. Go to **WooCommerce > Status** and check for any issues
3. Reset WooCommerce templates: **WooCommerce > Status > Tools > Template Debug Mode**

### Getting Support

If you encounter any issues not covered in this guide:

1. Check the documentation in the `docs` folder
2. Visit our support forum at [forum.aqualuxetheme.com](https://forum.aqualuxetheme.com)
3. Submit a support ticket at [support.aqualuxetheme.com](https://support.aqualuxetheme.com)
4. Email us at support@aqualuxetheme.com

When requesting support, please provide:
- WordPress version
- PHP version
- List of active plugins
- Description of the issue
- Screenshots if applicable

---

Thank you for choosing AquaLuxe! We hope this installation guide helps you get started with your new theme. For more detailed information, please refer to the user guide and developer documentation included with the theme.