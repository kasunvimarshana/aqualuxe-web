# Complete Step-by-Step Guide: Install and Setup WordPress & WooCommerce for AquaLuxe

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Setting Up Docker Environment](#setting-up-docker-environment)
3. [Installing WordPress](#installing-wordpress)
4. [Configuring WordPress Settings](#configuring-wordpress-settings)
5. [Installing and Setting Up WooCommerce](#installing-and-setting-up-woocommerce)
6. [Installing the AquaLuxe Theme](#installing-the-aqualuxe-theme)
7. [Configuring WooCommerce Settings](#configuring-woocommerce-settings)
8. [Setting Up Payment Methods](#setting-up-payment-methods)
9. [Setting Up Shipping Methods](#setting-up-shipping-methods)
10. [Creating Essential Pages](#creating-essential-pages)
11. [Setting Up Products](#setting-up-products)
12. [Configuring Taxes](#configuring-taxes)
13. [Setting Up Email Notifications](#setting-up-email-notifications)
14. [Installing Essential Plugins](#installing-essential-plugins)
15. [Optimizing Performance](#optimizing-performance)
16. [Setting Up Security](#setting-up-security)
17. [Backing Up Your Site](#backing-up-your-site)
18. [Going Live](#going-live)

---

## Prerequisites

Before you begin, make sure you have the following:

- A computer running Windows, macOS, or Linux
- Docker installed on your system
- Basic knowledge of command line interface
- A text editor (VS Code, Sublime Text, etc.)
- An internet connection

---

## Setting Up Docker Environment

### 1. Create Project Directory

Create a new directory for your AquaLuxe project:

```bash
mkdir aqualuxe-site
cd aqualuxe-site
```

### 2. Create Docker Compose File

Create a file named `docker-compose.yml` with the following content:

```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:latest
    container_name: aqualuxe-wordpress
    depends_on:
      - mysql
      - redis
    ports:
      - '8080:80'
    environment:
      WORDPRESS_DB_HOST: mysql:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
      WORDPRESS_DB_NAME: aqualuxe
      WORDPRESS_CONFIG_EXTRA: |
        define('WP_REDIS_HOST', 'redis');
        define('WP_REDIS_PORT', 6379);
        define('FS_METHOD', 'direct');
    volumes:
      - ./wordpress:/var/www/html
      - ./wordpress/wp-content:/var/www/html/wp-content
    networks:
      - aqualuxe-network

  mysql:
    image: mysql:5.7
    container_name: aqualuxe-mysql
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword
      MYSQL_DATABASE: aqualuxe
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    volumes:
      - ./mysql:/var/lib/mysql
    networks:
      - aqualuxe-network

  phpmyadmin:
    image: phpmyadmin/phpmyadmin:latest
    container_name: aqualuxe-phpmyadmin
    depends_on:
      - mysql
    environment:
      PMA_HOST: mysql
      PMA_PORT: 3306
    ports:
      - '8081:80'
    networks:
      - aqualuxe-network

  redis:
    image: redis:latest
    container_name: aqualuxe-redis
    volumes:
      - ./redis:/data
    networks:
      - aqualuxe-network

networks:
  aqualuxe-network:
    driver: bridge
```

### 3. Start Docker Containers

Run the following command to start all the containers:

```bash
docker-compose up -d
```

This will download the necessary Docker images and start the containers in the background.

### 4. Verify Containers are Running

Check that all containers are running:

```bash
docker-compose ps
```

You should see four containers running: aqualuxe-wordpress, aqualuxe-mysql, aqualuxe-phpmyadmin, and aqualuxe-redis.

---

## Installing WordPress

### 1. Access WordPress Installation

Open your web browser and navigate to:

```
http://localhost:8080
```

You should see the WordPress installation screen.

### 2. Complete WordPress Installation

1. Select your language and click "Continue".
2. Fill in the following information:
   - Site Title: AquaLuxe
   - Username: admin (or your preferred username)
   - Password: Choose a strong password
   - Your Email: Your email address
   - Search engine visibility: Uncheck this box for now (you can change it later)
3. Click "Install WordPress".
4. After the installation is complete, click "Log In".
5. Enter your username and password to access the WordPress dashboard.

---

## Configuring WordPress Settings

### 1. General Settings

1. In the WordPress dashboard, go to **Settings > General**.
2. Update the following settings:
   - Site Title: AquaLuxe
   - Tagline: Premium Ornamental Fish
   - WordPress Address (URL): http://localhost:8080
   - Site Address (URL): http://localhost:8080
   - Email Address: Your email address
   - Membership: Uncheck "Anyone can register"
   - New User Default Role: Subscriber
   - Timezone: Select your timezone
   - Date Format: Choose your preferred format
   - Time Format: Choose your preferred format
   - Week Starts On: Select your preferred day
3. Click "Save Changes".

### 2. Reading Settings

1. Go to **Settings > Reading**.
2. Update the following settings:
   - Your homepage displays: Select "A static page"
   - Homepage: Select "Create a new page" and name it "Home"
   - Posts page: Select "Create a new page" and name it "Blog"
   - Blog pages show at most: 10 posts
   - Syndication feeds show the most recent: 10 items
   - For each article in a feed, show: Full text
   - Search engine visibility: Uncheck this box for development
3. Click "Save Changes".

### 3. Permalink Settings

1. Go to **Settings > Permalinks**.
2. Select "Post name".
3. Click "Save Changes".

### 4. Discussion Settings

1. Go to **Settings > Discussion**.
2. Update the following settings:
   - Default article settings: Uncheck "Allow people to post comments on new articles"
   - Other comment settings: Configure as needed
   - Email me whenever: Configure as needed
   - Before a comment appears: Select "Comment must be manually approved"
   - Comment Moderation: Add keywords to moderate
   - Comment Blacklist: Add keywords to blacklist
3. Click "Save Changes".

### 5. Media Settings

1. Go to **Settings > Media**.
2. Update the following settings:
   - Thumbnail size: Width 150, Height 150
   - Medium size: Width 300, Height 300
   - Large size: Width 1024, Height 1024
   - Uploading files: Uncheck "Organize my uploads into month- and year-based folders"
3. Click "Save Changes".

---

## Installing and Setting Up WooCommerce

### 1. Install WooCommerce

1. In the WordPress dashboard, go to **Plugins > Add New**.
2. Search for "WooCommerce".
3. Click "Install Now" next to WooCommerce.
4. After installation, click "Activate".

### 2. WooCommerce Setup Wizard

After activating WooCommerce, you'll be guided through a setup wizard:

1. **Store Setup**:

   - Store Location: Enter your business address
   - Industry: Select "Other"
   - Product Type: Select "Physical products"
   - Click "Continue".

2. **Business Details**:

   - Business Address: Confirm your address
   - Additional Address Details: Fill in as needed
   - Currency: Select your preferred currency
   - Product Units: Select "Each"
   - Click "Continue".

3. **Payment**:

   - Select payment methods you plan to use (you can configure them later)
   - Click "Continue".

4. **Shipping**:

   - Select shipping zones and methods (you can configure them later)
   - Click "Continue".

5. **Recommended**:

   - Select recommended plugins (you can install them later)
   - Click "Continue".

6. **Ready!**:
   - Click "Create your first product!" or "Return to the dashboard".

---

## Installing the AquaLuxe Theme

### 1. Create Theme Directory

1. Navigate to the WordPress theme directory:

   ```bash
   cd wordpress/wp-content/themes
   ```

2. Create a new directory for the AquaLuxe theme:
   ```bash
   mkdir aqualuxe
   cd aqualuxe
   ```

### 2. Create Theme Files

Create the following files in the `aqualuxe` directory:

1. **style.css**:

   ```css
   /*
   Theme Name: AquaLuxe
   Theme URI: https://aqualuxe.com/
   Description: Premium WooCommerce Theme for Ornamental Fish
   Author: AquaLuxe Team
   Author URI: https://aqualuxe.com/
   Template: storefront
   Version: 1.0.0
   License: GNU General Public License v2 or later
   License URI: http://www.gnu.org/licenses/gpl-2.0.html
   Text Domain: aqualuxe
   */
   ```

2. **functions.php**:

   ```php
   <?php
   /**
    * AquaLuxe Child Theme Functions
    *
    * @package aqualuxe
    */

   if ( ! defined( 'ABSPATH' ) ) {
       exit; // Exit if accessed directly.
   }

   /**
    * Define constants
    */
   define( 'AQUALUXE_VERSION', '1.0.0' );
   define( 'AQUALUXE_THEME_DIR', get_stylesheet_directory() );
   define( 'AQUALUXE_THEME_URI', get_stylesheet_directory_uri() );

   /**
    * Include required files
    */
   require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-functions.php';
   require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-template-functions.php';
   require_once AQUALUXE_THEME_DIR . '/inc/aqualuxe-template-hooks.php';

   // WooCommerce specific functions
   if ( class_exists( 'WooCommerce' ) ) {
       require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/aqualuxe-woocommerce-functions.php';
       require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/aqualuxe-woocommerce-hooks.php';
       require_once AQUALUXE_THEME_DIR . '/inc/woocommerce/class-aqualuxe-woocommerce.php';
   }

   // Customizer functions
   require_once AQUALUXE_THEME_DIR . '/inc/customizer/aqualuxe-customizer-functions.php';
   require_once AQUALUXE_THEME_DIR . '/inc/customizer/aqualuxe-customizer-options.php';
   ```

3. Create the necessary directories and files as described in the previous sections.

### 3. Activate the Theme

1. In the WordPress dashboard, go to **Appearance > Themes**.
2. You should see the AquaLuxe theme listed.
3. Click "Activate" to activate the theme.

---

## Configuring WooCommerce Settings

### 1. General Settings

1. Go to **WooCommerce > Settings > General**.
2. Update the following settings:
   - Store Address: Enter your business address
   - Currency Options: Select your currency and position
   - Selling Location: Select "Sell to all countries"
   - Shipping Location: Select "Ship to all countries"
   - Default Customer Location: Select "Shop base address"
   - Enable Taxes: Select "Enable taxes and tax calculations"
   - Coupon Usage: Select "Enable the use of coupon codes"
   - Calculated Shipping: Select "Enable shipping"
   - Guest Checkout: Select "Allow customers to place orders without an account"
   - Account Creation: Select "Allow customers to create an account during checkout"
   - Secure Checkout: Select "Force secure checkout"
   - Checkout Style: Select "Two-column checkout"
   - Add to cart behavior: Select "Redirect to cart page after successful addition"
3. Click "Save changes".

### 2. Products Settings

1. Go to **WooCommerce > Settings > Products**.
2. Update the following settings:
   - Shop Page: Select the page you want to use as your shop page
   - Add to cart behavior: Select "Redirect to cart page after successful addition"
   - Redirect to the cart page after successful addition: Check this box
   - Measurements: Select your preferred units
   - Reviews: Select "Enable reviews"
   - Product Images: Set your desired image sizes
3. Click "Save changes".

### 3. Inventory Settings

1. Go to **WooCommerce > Settings > Products > Inventory**.
2. Update the following settings:
   - Manage Stock: Select "Enable stock management"
   - Hold Stock (minutes): Enter the number of minutes to hold stock for pending orders
   - Notifications: Select "Enable low stock notifications" and "Enable out of stock notifications"
   - Low Stock Threshold: Enter the number of products remaining when you want to be notified
   - Out of Stock Threshold: Enter the number of products remaining when you want to mark them as out of stock
   - Out of Stock Visibility: Select "Hide out of stock items from the catalog"
3. Click "Save changes".

---

## Setting Up Payment Methods

### 1. PayPal Standard

1. Go to **WooCommerce > Settings > Payments**.
2. Click "Manage" next to PayPal.
3. Enable PayPal:
   - Check the "Enable PayPal" box
   - Enter your PayPal email address
   - Select "PayPal Sandbox" for testing or "PayPal Live" for production
   - Set the title and description as needed
   - Configure advanced settings as needed
4. Click "Save changes".

### 2. Stripe

1. Go to **WooCommerce > Settings > Payments**.
2. Click "Manage" next to Stripe.
3. Enable Stripe:
   - Check the "Enable Stripe" box
   - Enter your Stripe API keys
   - Select "Test Mode" for testing or uncheck for production
   - Configure payment methods (credit cards, Apple Pay, Google Pay, etc.)
   - Set the title and description as needed
   - Configure advanced settings as needed
4. Click "Save changes".

### 3. Bank Transfer (BACS)

1. Go to **WooCommerce > Settings > Payments**.
2. Click "Manage" next to Direct bank transfer.
3. Enable Bank Transfer:
   - Check the "Enable Direct bank transfer" box
   - Enter your bank account details
   - Set the title and description as needed
   - Configure instructions as needed
4. Click "Save changes".

---

## Setting Up Shipping Methods

### 1. Shipping Zones

1. Go to **WooCommerce > Settings > Shipping**.
2. Click on the shipping zone you want to configure (e.g., "Locations not covered by your other zones").
3. Add shipping methods to the zone:
   - Click "Add shipping method".
   - Select a shipping method (e.g., Flat rate, Free shipping, Local pickup).
   - Click "Add shipping method".

### 2. Flat Rate

1. Click "Edit" next to Flat rate.
2. Configure Flat Rate:
   - Method Title: Enter a title (e.g., "Standard Shipping")
   - Tax Status: Select "Taxable" or "None"
   - Cost: Enter the cost (e.g., 5.00)
   - Configure additional costs as needed
3. Click "Save changes".

### 3. Free Shipping

1. Click "Edit" next to Free shipping.
2. Configure Free Shipping:
   - Method Title: Enter a title (e.g., "Free Shipping")
   - Enable free shipping: Select "A minimum order amount" or "A valid free shipping coupon"
   - Minimum order amount: Enter the minimum order amount (e.g., 50.00)
3. Click "Save changes".

### 4. Local Pickup

1. Click "Edit" next to Local pickup.
2. Configure Local Pickup:
   - Method Title: Enter a title (e.g., "Local Pickup")
   - Tax Status: Select "Taxable" or "None"
   - Cost: Enter the cost (e.g., 0.00)
   - Configure additional costs as needed
3. Click "Save changes".

---

## Creating Essential Pages

### 1. Home Page

1. Go to **Pages > Add New**.
2. Enter the title "Home".
3. Select "Home Page" from the Template dropdown (if available).
4. Click "Publish".
5. Go to **Settings > Reading**.
6. For "Your homepage displays", select "A static page".
7. For "Homepage", select the "Home" page you just created.
8. Click "Save changes".

### 2. Shop Page

1. Go to **Pages > Add New**.
2. Enter the title "Shop".
3. Click "Publish".
4. Go to **WooCommerce > Settings > Products**.
5. For "Shop Page", select the "Shop" page you just created.
6. Click "Save changes".

### 3. Cart Page

1. Go to **Pages > Add New**.
2. Enter the title "Cart".
3. Add the shortcode `[woocommerce_cart]` to the page content.
4. Click "Publish".
5. Go to **WooCommerce > Settings > Advanced**.
6. For "Cart Page", select the "Cart" page you just created.
7. Click "Save changes".

### 4. Checkout Page

1. Go to **Pages > Add New**.
2. Enter the title "Checkout".
3. Add the shortcode `[woocommerce_checkout]` to the page content.
4. Click "Publish".
5. Go to **WooCommerce > Settings > Advanced**.
6. For "Checkout Page", select the "Checkout" page you just created.
7. Click "Save changes".

### 5. My Account Page

1. Go to **Pages > Add New**.
2. Enter the title "My Account".
3. Add the shortcode `[woocommerce_my_account]` to the page content.
4. Click "Publish".
5. Go to **WooCommerce > Settings > Advanced**.
6. For "My Account Page", select the "My Account" page you just created.
7. Click "Save changes".

### 6. About Us Page

1. Go to **Pages > Add New**.
2. Enter the title "About Us".
3. Add content about your business, mission, and team.
4. Click "Publish".

### 7. Contact Us Page

1. Go to **Pages > Add New**.
2. Enter the title "Contact Us".
3. Add your contact information and a contact form (using a plugin like Contact Form 7).
4. Click "Publish".

### 8. Blog Page

1. Go to **Pages > Add New**.
2. Enter the title "Blog".
3. Click "Publish".
4. Go to **Settings > Reading**.
5. For "Posts page", select the "Blog" page you just created.
6. Click "Save changes".

---

## Setting Up Products

### 1. Product Categories

1. Go to **Products > Categories**.
2. Add categories for your products (e.g., "Freshwater Fish", "Saltwater Fish", "Aquatic Plants", "Fish Food").
3. For each category, enter a name, slug, description, and upload an image.
4. Click "Add New Category".

### 2. Product Attributes

1. Go to **Products > Attributes**.
2. Add attributes for your products (e.g., "Color", "Size", "Difficulty").
3. For each attribute, enter a name and slug.
4. Select "Visible on the product page" and "Used for variations" as needed.
5. Click "Add attribute".

### 3. Simple Products

1. Go to **Products > Add New**.
2. Enter a product name (e.g., "Neon Tetra").
3. Enter a product description and short description.
4. Select a product category.
5. Set the product image and gallery images.
6. In the "Product Data" box:
   - Select "Simple product".
   - Set the regular price and sale price (if applicable).
   - Set the inventory (SKU, stock status, stock quantity).
   - Set the shipping information (weight, dimensions).
   - Set the linked products (upsells, cross-sells).
   - Set the attributes (select the attributes you created).
   - Set the advanced options (purchase note, menu order).
7. Click "Publish".

### 4. Variable Products

1. Go to **Products > Add New**.
2. Enter a product name (e.g., "Betta Fish").
3. Enter a product description and short description.
4. Select a product category.
5. Set the product image and gallery images.
6. In the "Product Data" box:
   - Select "Variable product".
   - Go to the "Attributes" tab.
   - Select the attributes you want to use for variations (e.g., "Color", "Size").
   - Check "Used for variations".
   - Click "Save attributes".
   - Go to the "Variations" tab.
   - Click "Create variations from all attributes".
   - Select "Go".
   - Configure each variation (price, SKU, stock status, etc.).
7. Click "Publish".

### 5. Grouped Products

1. Go to **Products > Add New**.
2. Enter a product name (e.g., "Starter Fish Pack").
3. Enter a product description and short description.
4. Select a product category.
5. Set the product image and gallery images.
6. In the "Product Data" box:
   - Select "Grouped product".
   - Go to the "Linked Products" tab.
   - Search for and select the products you want to include in the group.
7. Click "Publish".

### 6. External/Affiliate Products

1. Go to **Products > Add New**.
2. Enter a product name (e.g., "Fish Tank Cleaner").
3. Enter a product description and short description.
4. Select a product category.
5. Set the product image and gallery images.
6. In the "Product Data" box:
   - Select "External/Affiliate product".
   - Set the product URL.
   - Set the button text (e.g., "Buy on Amazon").
7. Click "Publish".

---

## Configuring Taxes

### 1. Tax Settings

1. Go to **WooCommerce > Settings > Tax**.
2. Update the following settings:
   - Prices entered with tax: Select "Yes, I will enter prices inclusive of tax" or "No, I will enter prices exclusive of tax".
   - Calculate tax based on: Select "Customer shipping address" or "Customer billing address".
   - Shipping tax class: Select "Shipping tax class" or "Inherit product tax class".
   - Rounding: Select "Round tax at subtotal level" or "Round per line" or "Round per line (inclusive)".
   - Additional tax classes: Enter additional tax classes as needed.
   - Display prices in the shop: Select "Including tax" or "Excluding tax" or "Both".
   - Display prices during cart and checkout: Select "Including tax" or "Excluding tax" or "Both".
   - Price display suffix: Enter a suffix (e.g., "incl. tax").
   - Display tax totals: Select "As a single total" or "Itemized".
3. Click "Save changes".

### 2. Tax Rates

1. Go to **WooCommerce > Settings > Tax > Standard Rates**.
2. Add tax rates:
   - Enter country code, state code, postcode/city, rate, tax name, priority, compound, and shipping.
   - Click "Add row" for each tax rate.
3. Click "Save changes".

### 3. Tax Classes

1. Go to **WooCommerce > Settings > Tax**.
2. Under "Additional tax classes", enter tax classes (e.g., "Reduced Rate", "Zero Rate").
3. Click "Save changes".
4. Go to the tax class tab (e.g., "Reduced Rate").
5. Add tax rates for the class.
6. Click "Save changes".

---

## Setting Up Email Notifications

### 1. Email Settings

1. Go to **WooCommerce > Settings > Emails**.
2. Configure email options:
   - From Name: Enter your store name (e.g., "AquaLuxe").
   - From Address: Enter your email address.
   - Email Template: Select a template.
   - Footer Text: Enter footer text (e.g., "AquaLuxe - Premium Ornamental Fish").
   - Click "Save changes".

### 2. Configure Individual Emails

1. For each email type (e.g., "New Order", "Processing Order", "Completed Order", etc.):
   - Click "Manage".
   - Enable or disable the email.
   - Set the recipient, subject, heading, and email type.
   - Configure additional options as needed.
   - Click "Save changes".

### 3. Email Templates

1. Go to **WooCommerce > Settings > Emails**.
2. Click "Email Template" to preview and customize the email template.
3. Customize the header, footer, and body as needed.
4. Click "Save changes".

---

## Installing Essential Plugins

### 1. Contact Form 7

1. Go to **Plugins > Add New**.
2. Search for "Contact Form 7".
3. Click "Install Now" next to Contact Form 7.
4. After installation, click "Activate".
5. Create a contact form:
   - Go to **Contact > Add New**.
   - Configure the form fields.
   - Click "Save".
   - Copy the shortcode.
   - Go to your "Contact Us" page and paste the shortcode.

### 2. Yoast SEO

1. Go to **Plugins > Add New**.
2. Search for "Yoast SEO".
3. Click "Install Now" next to Yoast SEO.
4. After installation, click "Activate".
5. Configure Yoast SEO:
   - Go to **SEO > General**.
   - Configure the settings as needed.
   - Click "Save changes".

### 3. Redis Object Cache

1. Go to **Plugins > Add New**.
2. Search for "Redis Object Cache".
3. Click "Install Now" next to Redis Object Cache.
4. After installation, click "Activate".
5. Enable Redis:
   - Go to **Settings > Redis**.
   - Click "Enable Redis Object Cache".

### 4. WP Super Cache

1. Go to **Plugins > Add New**.
2. Search for "WP Super Cache".
3. Click "Install Now" next to WP Super Cache.
4. After installation, click "Activate".
5. Configure WP Super Cache:
   - Go to **Settings > WP Super Cache**.
   - Click "Caching On".
   - Configure the settings as needed.
   - Click "Update Status".

### 5. WooCommerce Product Add-ons

1. Go to **Plugins > Add New**.
2. Search for "WooCommerce Product Add-ons".
3. Click "Install Now" next to WooCommerce Product Add-ons.
4. After installation, click "Activate".
5. Configure product add-ons:
   - Go to **WooCommerce > Product Add-ons**.
   - Create global add-ons or add add-ons to individual products.

### 6. WooCommerce Subscriptions

1. Go to **Plugins > Add New**.
2. Search for "WooCommerce Subscriptions".
3. Click "Install Now" next to WooCommerce Subscriptions.
4. After installation, click "Activate".
5. Configure subscriptions:
   - Go to **WooCommerce > Settings > Subscriptions**.
   - Configure the settings as needed.
   - Click "Save changes".

---

## Optimizing Performance

### 1. Enable Caching

1. Go to **Settings > WP Super Cache**.
2. Click "Caching On".
3. Select "Mod Rewrite" caching method.
4. Check "Compress pages so they're served more quickly to visitors".
5. Check "Don't cache pages for known users".
6. Check "Cache rebuild. Serve a supercache file to anonymous users while a new file is being generated".
7. Click "Update Status".

### 2. Enable Redis Object Cache

1. Go to **Settings > Redis**.
2. Click "Enable Redis Object Cache".

### 3. Optimize Images

1. Go to **Settings > Media**.
2. Set appropriate image sizes for your theme.
3. Click "Save changes".
4. Install an image optimization plugin like "Smush" or "EWWW Image Optimizer".
5. Optimize your images using the plugin.

### 4. Minify CSS and JavaScript

1. Install a minification plugin like "Autoptimize".
2. Go to **Settings > Autoptimize**.
3. Check "Optimize HTML Code", "Optimize JavaScript Code", and "Optimize CSS Code".
4. Click "Save changes and empty cache".

### 5. Enable GZIP Compression

1. Add the following code to your `.htaccess` file:
   ```
   <IfModule mod_deflate.c>
     AddOutputFilterByType DEFLATE text/plain
     AddOutputFilterByType DEFLATE text/html
     AddOutputFilterByType DEFLATE text/xml
     AddOutputFilterByType DEFLATE text/css
     AddOutputFilterByType DEFLATE application/xml
     AddOutputFilterByType DEFLATE application/xhtml+xml
     AddOutputFilterByType DEFLATE application/rss+xml
     AddOutputFilterByType DEFLATE application/javascript
     AddOutputFilterByType DEFLATE application/x-javascript
   </IfModule>
   ```

### 6. Enable Browser Caching

1. Add the following code to your `.htaccess` file:
   ```
   <IfModule mod_expires.c>
     ExpiresActive On
     ExpiresByType text/css "access plus 1 year"
     ExpiresByType application/javascript "access plus 1 year"
     ExpiresByType image/jpeg "access plus 1 year"
     ExpiresByType image/png "access plus 1 year"
     ExpiresByType image/gif "access plus 1 year"
   </IfModule>
   ```

---

## Setting Up Security

### 1. Install a Security Plugin

1. Go to **Plugins > Add New**.
2. Search for "Wordfence Security" or "Sucuri Security".
3. Click "Install Now" next to the plugin.
4. After installation, click "Activate".
5. Configure the security plugin:
   - Go to the plugin's settings page.
   - Configure the settings as needed.
   - Click "Save changes".

### 2. Change Default Login URL

1. Install a plugin like "WPS Hide Login".
2. Go to **Settings > WPS Hide Login**.
3. Enter a new login URL (e.g., "aqualuxe-login").
4. Click "Save changes".

### 3. Limit Login Attempts

1. Install a plugin like "Limit Login Attempts Reloaded".
2. Go to **Settings > Limit Login Attempts**.
3. Configure the settings as needed.
4. Click "Save changes".

### 4. Disable File Editing

1. Add the following code to your `wp-config.php` file:
   ```php
   define('DISALLOW_FILE_EDIT', true);
   ```

### 5. Secure wp-config.php

1. Add the following code to your `.htaccess` file:
   ```
   <files wp-config.php>
     order allow,deny
     deny from all
   </files>
   ```

### 6. Disable XML-RPC

1. Add the following code to your `.htaccess` file:
   ```
   <files xmlrpc.php>
     order allow,deny
     deny from all
   </files>
   ```

---

## Backing Up Your Site

### 1. Install a Backup Plugin

1. Go to **Plugins > Add New**.
2. Search for "UpdraftPlus" or "VaultPress".
3. Click "Install Now" next to the plugin.
4. After installation, click "Activate".
5. Configure the backup plugin:
   - Go to the plugin's settings page.
   - Configure the settings as needed.
   - Click "Save changes".

### 2. Manual Backup

1. Backup your database:

   - Go to **phpMyAdmin** (http://localhost:8081).
   - Select your database.
   - Click "Export".
   - Select "Custom" and "SQL".
   - Click "Go".

2. Backup your files:
   - Copy the `wordpress` directory to a safe location.

### 3. Schedule Regular Backups

1. Go to your backup plugin's settings.
2. Configure a backup schedule (e.g., daily, weekly).
3. Configure a remote storage location (e.g., Google Drive, Dropbox).
4. Click "Save changes".

---

## Going Live

### 1. Prepare for Production

1. Update WordPress and plugins to the latest versions.
2. Test all functionality (products, cart, checkout, etc.).
3. Optimize images and database.
4. Create a full backup.
5. Prepare your production environment.

### 2. Move to Production

1. Export your database:

   - Go to **phpMyAdmin**.
   - Select your database.
   - Click "Export".
   - Select "Custom" and "SQL".
   - Click "Go".

2. Copy your files:

   - Copy the `wordpress` directory to your production server.

3. Import your database:

   - Go to **phpMyAdmin** on your production server.
   - Create a new database.
   - Click "Import".
   - Select the SQL file you exported.
   - Click "Go".

4. Update the site URL:

   - Edit the `wp_options` table in your database.
   - Update the `siteurl` and `home` options to your production URL.

5. Update the `.env` file:

   - Update the database credentials and other settings for production.

6. Update the `docker-compose.yml` file:

   - Update the ports and other settings for production.

7. Start the Docker containers:
   ```bash
   docker-compose up -d
   ```

### 3. Post-Launch Tasks

1. Test all functionality on the production site.
2. Set up monitoring and analytics.
3. Set up security measures (SSL, firewall, etc.).
4. Set up regular backups.
5. Submit your sitemap to search engines.
6. Set up email marketing.

---

## Conclusion

Congratulations! You have successfully installed and set up WordPress and WooCommerce for your AquaLuxe ornamental fish business. Your site is now ready to start selling products online. Remember to regularly update your site, monitor its performance, and make improvements as needed.

For further customization and optimization, consider exploring additional plugins, themes, and services that can enhance your online store. Good luck with your AquaLuxe business!
