# AquaLuxe WordPress Theme - Installation Guide

## Table of Contents

1. [Introduction](#introduction)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Demo Content Import](#demo-content-import)
5. [Plugin Installation](#plugin-installation)
6. [Theme Setup](#theme-setup)
7. [Troubleshooting](#troubleshooting)

## Introduction

Thank you for purchasing AquaLuxe, a premium WordPress theme for businesses, online stores, and blogs. This installation guide will walk you through the process of installing and setting up the AquaLuxe theme on your WordPress website.

## Requirements

Before installing AquaLuxe, please ensure your hosting environment meets the following requirements:

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Memory limit of at least 128 MB
- max_execution_time of at least 60 seconds
- post_max_size of at least 32 MB
- upload_max_filesize of at least 32 MB

To check your server's PHP configuration, you can install a plugin like "Health Check & Troubleshooting" or create a PHP info file.

## Installation

### Method 1: WordPress Admin Panel

1. **Download the Theme**
   - After purchase, download the `aqualuxe.zip` file from your account or the email you received.
   - Do not unzip the file.

2. **Upload and Install**
   - Log in to your WordPress admin panel.
   - Navigate to **Appearance > Themes**.
   - Click the **Add New** button at the top of the page.
   - Click the **Upload Theme** button.
   - Click **Choose File**, select the `aqualuxe.zip` file you downloaded, and click **Install Now**.
   - Wait for the installation to complete.

3. **Activate the Theme**
   - After installation, click the **Activate** button.
   - You'll be redirected to the AquaLuxe welcome page.

### Method 2: FTP Upload

1. **Extract the Theme**
   - Extract the `aqualuxe.zip` file to your computer.
   - You should now have an `aqualuxe` folder.

2. **Upload via FTP**
   - Connect to your server using an FTP client (like FileZilla).
   - Navigate to the `/wp-content/themes/` directory.
   - Upload the `aqualuxe` folder to this directory.

3. **Activate the Theme**
   - Log in to your WordPress admin panel.
   - Navigate to **Appearance > Themes**.
   - Find AquaLuxe in the list of available themes.
   - Click the **Activate** button.
   - You'll be redirected to the AquaLuxe welcome page.

## Demo Content Import

AquaLuxe comes with demo content to help you get started quickly. You can import this content to make your site look like the demo.

### Before Importing

1. **Install Required Plugins**
   - Before importing demo content, make sure you've installed all required plugins (see [Plugin Installation](#plugin-installation)).

2. **Backup Your Site**
   - It's always a good idea to backup your site before importing demo content.
   - You can use a plugin like UpdraftPlus or BackupBuddy for this.

### Import Process

1. **Access Demo Importer**
   - Navigate to **Appearance > AquaLuxe**.
   - Click on the **Demo Import** tab.

2. **Select a Demo**
   - AquaLuxe comes with multiple demo options:
     - **Main Demo**: The full demo as seen on the demo site
     - **Business Demo**: Focused on business websites
     - **Shop Demo**: Focused on WooCommerce stores
     - **Blog Demo**: Focused on blogs
     - **Portfolio Demo**: Focused on portfolios
   - Click on the demo you want to import.

3. **Choose Import Options**
   - Select what you want to import:
     - **Content**: Pages, posts, custom post types
     - **Media**: Images and other media files
     - **Widgets**: Sidebar and footer widgets
     - **Customizer Settings**: Theme options and customizations
     - **Menus**: Navigation menus
     - **Forms**: Contact and other forms
   - Click **Import**.

4. **Wait for Completion**
   - The import process may take several minutes depending on your server.
   - Do not close the browser window during import.
   - You'll see a success message when the import is complete.

### After Importing

1. **Review Your Site**
   - Visit your site to see how it looks with the demo content.
   - Note that some images may be replaced with placeholder images due to copyright restrictions.

2. **Customize Content**
   - Replace the demo content with your own content.
   - Update the site title, tagline, and logo.
   - Customize the colors and typography to match your brand.

## Plugin Installation

AquaLuxe works best with certain plugins. Some are required, while others are recommended.

### Required Plugins

These plugins are necessary for the theme to function properly:

1. **Kirki Customizer Framework**
   - Used for the advanced customizer options
   - Will be installed automatically during theme activation

2. **One Click Demo Import**
   - Used for importing demo content
   - Will be installed automatically during theme activation

### Recommended Plugins

These plugins are not required but are recommended for the best experience:

1. **WooCommerce**
   - For e-commerce functionality
   - Navigate to **Plugins > Add New**
   - Search for "WooCommerce"
   - Click **Install Now**, then **Activate**

2. **Elementor Page Builder**
   - For easy page building
   - Navigate to **Plugins > Add New**
   - Search for "Elementor"
   - Click **Install Now**, then **Activate**

3. **Contact Form 7**
   - For contact forms
   - Navigate to **Plugins > Add New**
   - Search for "Contact Form 7"
   - Click **Install Now**, then **Activate**

4. **Yoast SEO**
   - For search engine optimization
   - Navigate to **Plugins > Add New**
   - Search for "Yoast SEO"
   - Click **Install Now**, then **Activate**

5. **WP Super Cache**
   - For improved performance
   - Navigate to **Plugins > Add New**
   - Search for "WP Super Cache"
   - Click **Install Now**, then **Activate**

### Automatic Plugin Installation

AquaLuxe can install all recommended plugins automatically:

1. Navigate to **Appearance > AquaLuxe**.
2. Click on the **Plugins** tab.
3. Click the **Install Plugins** button.
4. Wait for the installation to complete.
5. Activate all plugins.

## Theme Setup

After installing and activating AquaLuxe, you'll want to set up the theme to match your needs.

### Initial Setup

1. **Welcome Page**
   - After activating the theme, you'll be redirected to the welcome page.
   - This page provides an overview of the theme and links to documentation.
   - You can always access this page by navigating to **Appearance > AquaLuxe**.

2. **Setup Wizard**
   - Click the **Setup Wizard** button on the welcome page.
   - Follow the step-by-step instructions to set up your theme.
   - The wizard will guide you through:
     - Plugin installation
     - Demo content import
     - Logo upload
     - Color selection
     - Typography selection
     - Menu setup
     - Homepage setup

### Manual Setup

If you prefer to set up the theme manually:

1. **Logo and Site Identity**
   - Navigate to **Appearance > Customize**.
   - Click on **Site Identity**.
   - Upload your logo, set the site title and tagline, and upload a favicon.
   - Click **Publish** to save changes.

2. **Colors and Typography**
   - Navigate to **Appearance > Customize**.
   - Click on **Colors** to set your color scheme.
   - Click on **Typography** to set your fonts.
   - Click **Publish** to save changes.

3. **Header and Footer**
   - Navigate to **Appearance > Customize**.
   - Click on **Header** to configure your header.
   - Click on **Footer** to configure your footer.
   - Click **Publish** to save changes.

4. **Homepage Setup**
   - Create a new page by navigating to **Pages > Add New**.
   - Give it a title (e.g., "Home").
   - Select the "Homepage" template from the Page Attributes panel.
   - Publish the page.
   - Navigate to **Settings > Reading**.
   - Set "Your homepage displays" to "A static page".
   - Select your new page as the Homepage.
   - Click **Save Changes**.

5. **Menu Setup**
   - Navigate to **Appearance > Menus**.
   - Create a new menu or edit an existing one.
   - Add pages, categories, or custom links to your menu.
   - Assign the menu to a location (Primary, Footer, or Mobile).
   - Click **Save Menu**.

6. **Widget Setup**
   - Navigate to **Appearance > Widgets**.
   - Add widgets to the sidebar and footer widget areas.
   - Configure each widget as needed.
   - Click **Save**.

## Troubleshooting

### Common Installation Issues

#### Theme Won't Upload

If you're having trouble uploading the theme:

1. **Check File Size**
   - Make sure your server allows uploading files of the theme's size.
   - You may need to increase the `upload_max_filesize` and `post_max_size` in your PHP configuration.

2. **Check Permissions**
   - Make sure the `/wp-content/themes/` directory is writable.
   - Set permissions to 755 for directories and 644 for files.

3. **Use FTP**
   - If uploading through the WordPress admin panel doesn't work, try using FTP as described in [Method 2](#method-2-ftp-upload).

#### Theme Won't Activate

If the theme won't activate:

1. **Check PHP Version**
   - Make sure your server meets the [requirements](#requirements).
   - You may need to upgrade PHP on your server.

2. **Check for Errors**
   - Check for PHP errors in your server's error log.
   - Enable WP_DEBUG in your wp-config.php file to see more detailed error messages.

3. **Check Theme Files**
   - Make sure all theme files were uploaded correctly.
   - Try re-uploading the theme.

#### Demo Import Fails

If the demo import fails:

1. **Check Server Resources**
   - Make sure your server has enough memory and execution time.
   - You may need to increase the `memory_limit` and `max_execution_time` in your PHP configuration.

2. **Check File Permissions**
   - Make sure the `/wp-content/uploads/` directory is writable.
   - Set permissions to 755 for directories and 644 for files.

3. **Try Manual Import**
   - If the automatic import fails, you can try importing the demo content manually.
   - Contact support for assistance with this.

### Getting Help

If you're still having trouble installing AquaLuxe:

1. **Check Documentation**
   - Review this installation guide and the user guide.
   - Check the FAQ section on the theme's website.

2. **Contact Support**
   - Visit the support forum at [support.aqualuxe.com](https://support.aqualuxe.com).
   - Create a new support ticket.
   - Provide your purchase code.
   - Describe your issue in detail.
   - Include screenshots if possible.

3. **Hire a Developer**
   - If you're not comfortable with technical tasks, consider hiring a WordPress developer to help you install and set up the theme.
   - You can find developers on platforms like Upwork, Fiverr, or Codeable.

Thank you for choosing AquaLuxe! We hope you enjoy using our theme.