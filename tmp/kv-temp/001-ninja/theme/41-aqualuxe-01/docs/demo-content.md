# AquaLuxe WordPress Theme - Demo Content Guide

This document provides instructions for setting up demo content for the AquaLuxe WordPress theme. Demo content helps users visualize how the theme looks with real content and understand how to structure their own content.

## Table of Contents

1. [Introduction](#introduction)
2. [Installing the Demo Content](#installing-the-demo-content)
3. [Demo Content Structure](#demo-content-structure)
4. [Customizing Demo Content](#customizing-demo-content)
5. [Removing Demo Content](#removing-demo-content)

## Introduction

The AquaLuxe theme comes with optional demo content that showcases the theme's features and capabilities. This demo content includes:

- Sample pages (Home, About, Contact, etc.)
- Sample blog posts
- Sample products (if WooCommerce is installed)
- Sample menus
- Sample widgets
- Sample customizer settings

## Installing the Demo Content

### Prerequisites

Before installing the demo content, ensure you have:

1. WordPress installed and activated
2. AquaLuxe theme installed and activated
3. Required plugins installed and activated:
   - WooCommerce (optional, but recommended for full demo)
   - Contact Form 7 (for contact forms)
   - Elementor (optional, for page builder layouts)

### Installation Methods

#### Method 1: One-Click Demo Import

1. Install and activate the "One Click Demo Import" plugin from the WordPress plugin repository
2. Go to **Appearance > Import Demo Data**
3. Click the "Import Demo Data" button
4. Wait for the import process to complete (this may take several minutes)

#### Method 2: Manual Import

1. Download the demo content XML file from the theme package (`demo-content/aqualuxe-demo-content.xml`)
2. Go to **Tools > Import** in your WordPress admin
3. Click on "WordPress" and install the WordPress importer if prompted
4. Choose the XML file you downloaded and click "Upload file and import"
5. Select "Download and import file attachments" to import images
6. Click "Submit" to start the import process

### Post-Import Setup

After importing the demo content, you'll need to:

1. Set up the homepage:
   - Go to **Settings > Reading**
   - Set "Your homepage displays" to "A static page"
   - Select "Home" as your homepage and "Blog" as your posts page

2. Set up menus:
   - Go to **Appearance > Menus**
   - The demo import should have created menus, but you may need to assign them to menu locations

3. Set up widgets:
   - Go to **Appearance > Widgets**
   - The demo import should have populated widget areas, but you may need to adjust them

4. Import customizer settings:
   - Go to **Appearance > Customize**
   - Click the "Import/Export" button (if available)
   - Import the customizer settings file (`demo-content/aqualuxe-customizer.dat`)

## Demo Content Structure

### Pages

The demo content includes the following pages:

1. **Home** - Main landing page with featured products, categories, and promotional sections
2. **About Us** - Information about the store and its mission
3. **Contact** - Contact form and store information
4. **Shop** - Main WooCommerce shop page
5. **Cart** - WooCommerce cart page
6. **Checkout** - WooCommerce checkout page
7. **My Account** - WooCommerce customer account page
8. **FAQ** - Frequently asked questions
9. **Terms & Conditions** - Legal terms and conditions
10. **Privacy Policy** - Privacy policy information

### Blog Posts

The demo content includes several blog posts in different categories:

1. **Aquarium Care** - Posts about maintaining aquariums
2. **Fish Species** - Posts about different fish species
3. **Aquascaping** - Posts about aquarium design and layout
4. **Product Reviews** - Reviews of aquarium products

### Products

If WooCommerce is installed, the demo content includes products in these categories:

1. **Aquariums** - Various aquarium tanks and setups
2. **Fish** - Different species of fish
3. **Plants** - Aquatic plants for aquariums
4. **Equipment** - Filters, heaters, lights, etc.
5. **Accessories** - Decorations, gravel, etc.
6. **Food** - Fish food and supplements

### Menus

The demo content includes these menus:

1. **Main Menu** - Primary navigation menu
2. **Footer Menu** - Links in the footer
3. **Top Bar Menu** - Links in the top bar (if enabled)

### Widgets

The demo content populates these widget areas:

1. **Sidebar** - Blog and shop sidebar widgets
2. **Footer Widgets** - Widgets in the footer columns
3. **Shop Filters** - Product filtering widgets

## Customizing Demo Content

After importing the demo content, you'll want to customize it to match your specific needs:

### Customizing Pages

1. Go to **Pages** in your WordPress admin
2. Edit each page to update content, images, and layout
3. For pages built with Elementor, click the "Edit with Elementor" button to make visual changes

### Customizing Products

1. Go to **Products** in your WordPress admin
2. Edit each product to update titles, descriptions, prices, and images
3. Update product categories and tags as needed

### Customizing Images

1. Replace demo images with your own images
2. Maintain the same image dimensions for consistent layout
3. Optimize images for web before uploading

### Customizing Colors and Styles

1. Go to **Appearance > Customize**
2. Navigate to the "Colors" section
3. Update colors to match your brand

## Removing Demo Content

If you want to remove the demo content and start fresh:

### Method 1: Manual Deletion

1. Go to **Pages** and delete all demo pages
2. Go to **Posts** and delete all demo posts
3. Go to **Products** and delete all demo products
4. Go to **Media** and delete all demo images
5. Go to **Appearance > Menus** and delete or edit demo menus
6. Go to **Appearance > Widgets** and remove demo widgets

### Method 2: WordPress Reset

1. Install and activate the "WordPress Reset" plugin
2. Go to **Tools > WordPress Reset**
3. Follow the instructions to reset your WordPress installation
4. After reset, activate the AquaLuxe theme again
5. Set up your site with your own content

## Support

If you encounter any issues with the demo content, please contact our support team at support@example.com or visit our support forum at https://example.com/support.