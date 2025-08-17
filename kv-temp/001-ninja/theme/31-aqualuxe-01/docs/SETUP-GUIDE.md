# AquaLuxe WordPress Theme Setup Guide

This guide will walk you through the process of setting up the AquaLuxe WordPress theme to match the demo site.

## Prerequisites

Before you begin, make sure you have:

1. WordPress 5.9 or higher installed
2. PHP 7.4 or higher
3. WooCommerce 6.0 or higher (for e-commerce functionality)
4. The AquaLuxe theme installed and activated

## Step 1: Install Required Plugins

AquaLuxe works best with the following plugins:

1. **WooCommerce** - For e-commerce functionality
2. **Contact Form 7** - For contact forms
3. **Yoast SEO** - For SEO optimization
4. **WP Rocket** - For performance optimization (optional)

To install these plugins:

1. Go to **Plugins > Add New**
2. Search for each plugin
3. Click **Install Now** and then **Activate**

## Step 2: Import Demo Content

To make your site look like the demo:

1. Go to **Tools > Import**
2. Click on **WordPress** (install the importer if prompted)
3. Click **Choose File** and select the `aqualuxe-demo-content.xml` file from the `demo` folder
4. Click **Upload file and import**
5. Map the authors as desired
6. Check **Download and import file attachments**
7. Click **Submit**

## Step 3: Set Up Homepage

1. Go to **Pages** and make sure the "Home" page was imported
2. Go to **Settings > Reading**
3. Set **Your homepage displays** to **A static page**
4. Select **Home** as your homepage
5. Click **Save Changes**

## Step 4: Set Up Menus

1. Go to **Appearance > Menus**
2. Create a new menu called "Primary Menu"
3. Add the following pages to the menu:
   - Home
   - About
   - Services
   - Shop (if WooCommerce is installed)
   - Blog
   - Contact
   - FAQ
4. Set the menu location to "Primary Menu"
5. Click **Save Menu**
6. Repeat the process to create a "Footer Menu" and "Mobile Menu" if desired

## Step 5: Configure WooCommerce (if installed)

1. Go through the WooCommerce setup wizard if prompted
2. Go to **WooCommerce > Settings**
3. Configure your store settings:
   - General: Set your store address, currency, etc.
   - Products: Configure product display options
   - Shipping: Set up shipping zones and methods
   - Payments: Configure payment gateways
   - Accounts: Set up customer account options
   - Emails: Customize email notifications

## Step 6: Customize Theme Settings

1. Go to **Appearance > Customize**
2. Configure the following settings:

### Site Identity
- Upload your logo (recommended size: 250px × 100px)
- Set your site title and tagline
- Upload a site icon (favicon)

### Colors
- Set your primary and secondary colors
- Adjust background and text colors

### Typography
- Choose your preferred fonts for headings and body text
- Adjust font sizes and line heights

### Header
- Choose your preferred header layout
- Configure header options (sticky header, transparent header, etc.)

### Footer
- Choose your preferred footer layout
- Configure footer options (widget columns, copyright text, etc.)

### Blog
- Configure blog layout and options
- Set featured image size and excerpt length

### WooCommerce (if installed)
- Configure shop layout and options
- Set product grid columns and related products count

### Dark Mode
- Configure dark mode options
- Set dark mode colors

### Multilingual
- Configure language switcher options
- Set default language

3. Click **Publish** to save your changes

## Step 7: Set Up Widgets

1. Go to **Appearance > Widgets**
2. Add widgets to the following areas:
   - Sidebar
   - Footer 1-4
   - Shop Sidebar (if WooCommerce is installed)

Recommended widgets:
- Recent Posts
- Categories
- Search
- Custom HTML (for contact information, social links, etc.)
- WooCommerce widgets (if WooCommerce is installed)

## Step 8: Configure Contact Form

1. Go to **Contact Forms** (if Contact Form 7 is installed)
2. Edit the default contact form or create a new one
3. Copy the shortcode (e.g., `[contact-form-7 id="123" title="Contact form 1"]`)
4. Go to the Contact page
5. Edit the page and paste the shortcode in the appropriate section

## Step 9: Set Up Google Maps (for Contact Page)

1. Get a Google Maps API key from the [Google Cloud Platform Console](https://console.cloud.google.com/)
2. Go to **Appearance > Customize > Contact Page**
3. Enter your Google Maps API key
4. Set your business location coordinates
5. Configure map options (zoom level, marker, etc.)

## Step 10: Configure SEO Settings (if using Yoast SEO)

1. Go to **SEO > General**
2. Go through the configuration wizard
3. Configure your site's SEO settings:
   - Set your site title and meta description
   - Connect to Google Search Console
   - Configure social media profiles
   - Set up XML sitemaps

## Step 11: Performance Optimization

1. If using WP Rocket, go through its configuration wizard
2. Enable the following features:
   - Page caching
   - Browser caching
   - GZIP compression
   - Lazy loading
   - Minification of HTML, CSS, and JavaScript
   - Defer JavaScript loading
   - CDN integration (if using a CDN)

## Step 12: Final Checks

Before launching your site, perform the following checks:

1. Test all pages on desktop and mobile devices
2. Test all forms and make sure they submit correctly
3. Test the checkout process if using WooCommerce
4. Check all links to make sure they work
5. Verify that all images are displaying correctly
6. Test the site in different browsers (Chrome, Firefox, Safari, Edge)
7. Test the site with JavaScript disabled
8. Run performance tests using tools like Google PageSpeed Insights
9. Check for broken links using a tool like Broken Link Checker
10. Verify that all SEO meta tags are set correctly

## Additional Resources

- [AquaLuxe Documentation](./README.md)
- [AquaLuxe Installation Guide](./INSTALLATION.md)
- [AquaLuxe Developer Documentation](./DEVELOPER.md)
- [AquaLuxe Customization Guide](./CUSTOMIZATION.md)
- [AquaLuxe Changelog](./CHANGELOG.md)

If you need further assistance, please contact our support team at support@aqualuxe.example.com.