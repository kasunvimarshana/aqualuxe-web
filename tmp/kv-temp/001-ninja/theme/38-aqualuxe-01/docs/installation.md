# AquaLuxe Theme Installation Guide

This guide will walk you through the process of installing and setting up the AquaLuxe WordPress theme for your website.

## Requirements

Before installing AquaLuxe, ensure your hosting environment meets the following requirements:

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher or MariaDB 10.1 or higher
- WooCommerce 7.0 or higher
- Memory limit of at least 128 MB
- Post max size of at least 64 MB
- Upload max filesize of at least 32 MB
- Max execution time of at least 120 seconds

## Installation Methods

### Method 1: Install via WordPress Admin Panel

1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Themes**
3. Click the **Add New** button at the top of the page
4. Click the **Upload Theme** button
5. Click **Choose File** and select the `aqualuxe.zip` file from your computer
6. Click **Install Now**
7. After installation is complete, click **Activate** to activate the theme

### Method 2: Install via FTP

1. Extract the `aqualuxe.zip` file on your computer
2. Connect to your website using an FTP client
3. Navigate to the `/wp-content/themes/` directory
4. Upload the `aqualuxe` folder to this directory
5. Log in to your WordPress admin dashboard
6. Navigate to **Appearance > Themes**
7. Find AquaLuxe in the list of available themes and click **Activate**

## Required Plugins

After activating the theme, you'll be prompted to install and activate the following recommended plugins:

1. **WooCommerce** - Essential for e-commerce functionality
2. **Advanced Custom Fields** - For additional customization options
3. **Elementor** - For drag-and-drop page building (optional)
4. **Yoast SEO** - For search engine optimization
5. **WP Rocket** - For performance optimization (optional)
6. **Wordfence Security** - For enhanced security (optional)

To install these plugins:

1. After theme activation, you'll see a notification at the top of your dashboard
2. Click on **Begin installing plugins**
3. Select all recommended plugins
4. Choose **Install** from the dropdown menu
5. Click **Apply**

## Post-Installation Setup

### 1. Import Demo Content (Optional)

To make your site look like the demo:

1. Navigate to **Appearance > AquaLuxe Options**
2. Click the **Demo Import** tab
3. Choose which demo content you want to import
4. Click **Import Demo Content**

### 2. Configure Theme Settings

1. Navigate to **Appearance > Customize**
2. Configure the following settings:
   - **Site Identity**: Upload your logo, favicon, and set site title
   - **Colors**: Customize the color scheme
   - **Typography**: Choose fonts and sizes
   - **Layout Options**: Set global layout preferences
   - **Header Options**: Configure header layout and elements
   - **Footer Options**: Set up footer columns and content
   - **WooCommerce Options**: Customize shop pages and product displays

### 3. Set Up Homepage

1. Create a new page by going to **Pages > Add New**
2. Give it a title (e.g., "Home")
3. Use the block editor or Elementor to design your homepage
   - Add hero section, featured products, testimonials, etc.
   - Use the pre-designed block patterns in the editor
4. Publish the page
5. Go to **Settings > Reading**
6. Set "Your homepage displays" to "A static page"
7. Select your newly created page as the Homepage
8. Click **Save Changes**

### 4. Configure WooCommerce

1. Navigate to **WooCommerce > Settings**
2. Set up your store details, payment gateways, shipping methods, etc.
3. Create product categories at **Products > Categories**
4. Add products at **Products > Add New**

## Troubleshooting

If you encounter any issues during installation:

1. **Theme not appearing**: Check that the theme folder is correctly named `aqualuxe` and is directly inside the `/wp-content/themes/` directory

2. **Broken layout**: Try regenerating thumbnails by installing the "Regenerate Thumbnails" plugin and running it

3. **Plugin conflicts**: Temporarily deactivate all plugins to see if the issue persists, then reactivate them one by one

4. **Server requirements**: Check with your hosting provider to ensure your server meets all requirements

5. **Import errors**: Make sure your PHP settings allow for larger file uploads and longer execution times

## Getting Support

If you need assistance with the installation process:

- Visit our [documentation portal](https://aqualuxetheme.com/docs)
- Contact our support team at support@aqualuxetheme.com
- Submit a support ticket through your account on our website

## Next Steps

After completing the installation, we recommend:

1. Reviewing the [Theme Customization](customization.md) guide to personalize your site
2. Exploring the [WooCommerce Integration](woocommerce.md) documentation to optimize your store
3. Checking the [Performance Optimization](performance.md) guide to ensure your site loads quickly