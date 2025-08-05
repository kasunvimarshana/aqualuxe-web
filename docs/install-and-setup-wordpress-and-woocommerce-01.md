# Complete Step-by-Step Guide: Install and Setup WordPress & WooCommerce for AquaLuxe

## Table of Contents
1. [Prerequisites](#prerequisites)
2. [Setting Up Docker Environment](#setting-up-docker-environment)
3. [Installing WordPress](#installing-wordpress)
4. [Configuring WordPress Settings](#configuring-wordpress-settings)
5. [Installing WooCommerce](#installing-woocommerce)
6. [Setting Up AquaLuxe Child Theme](#setting-up-aqualuxe-child-theme)
7. [Creating Essential Pages](#creating-essential-pages)
8. [Configuring WooCommerce Products](#configuring-woocommerce-products)
9. [Setting Up Shipping](#setting-up-shipping)
10. [Configuring Taxes](#configuring-taxes)
11. [Setting Up Payment Methods](#setting-up-payment-methods)
12. [Installing Essential Plugins](#installing-essential-plugins)
13. [Setting Up Menus](#setting-up-menus)
14. [Configuring Widgets](#configuring-widgets)
15. [Final Configuration](#final-configuration)
16. [Testing Your Site](#testing-your-site)
17. [Preparing for Production](#preparing-for-production)

---

## Prerequisites

Before starting, ensure you have the following installed:
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
- A code editor (e.g., VS Code)

---

## Setting Up Docker Environment

### 1. Create Project Directory
```bash
mkdir aqualuxe-site
cd aqualuxe-site
```

### 2. Create Docker Files

**docker-compose.yml**
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
      - "8080:80"
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
      - "8081:80"
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
```bash
docker-compose up -d
```

---

## Installing WordPress

### 1. Access WordPress Setup
- Open your browser and go to: `http://localhost:8080`
- You'll see the WordPress setup screen

### 2. Complete WordPress Installation
1. Select your language and click **Continue**
2. Fill in the site information:
   - **Site Title**: `AquaLuxe`
   - **Username**: `admin` (or your preferred username)
   - **Password**: `your-secure-password`
   - **Your Email**: `your-email@example.com`
3. Check "Discourage search engines from indexing this site" (for development)
4. Click **Install WordPress**

### 3. Log In to WordPress
- After installation, click **Log In**
- Enter your credentials to access the WordPress dashboard

---

## Configuring WordPress Settings

### 1. General Settings
1. Go to **Settings → General**
2. Update:
   - **Site Title**: `AquaLuxe`
   - **Tagline**: `Premium Ornamental Fish for Enthusiasts and Collectors`
   - **Email Address**: `your-email@example.com`
   - **Timezone**: Select your timezone
3. Click **Save Changes**

### 2. Permalink Settings
1. Go to **Settings → Permalinks**
2. Select **Post name**
3. Click **Save Changes**

### 3. Reading Settings
1. Go to **Settings → Reading**
2. Set "Your homepage displays" to **A static page**
3. Select:
   - **Homepage**: (will create later)
   - **Posts page**: (will create later)
4. Click **Save Changes**

### 4. Discussion Settings
1. Go to **Settings → Discussion**
2. Uncheck "Allow people to post comments on new articles" (optional)
3. Click **Save Changes**

---

## Installing WooCommerce

### 1. Install WooCommerce Plugin
1. Go to **Plugins → Add New**
2. Search for "WooCommerce"
3. Click **Install Now** next to WooCommerce
4. After installation, click **Activate**

### 2. WooCommerce Setup Wizard
1. After activation, WooCommerce will launch the setup wizard
2. Click **Let's Go!** to begin

#### Store Setup
- Address fields: Fill in your business address
- Currency: Select your currency (e.g., USD, EUR, etc.)
- Product type: Select **Physical**
- Click **Continue**

#### Payment
- Select payment methods (e.g., Stripe, PayPal)
- For development, you can select "Cash on delivery" or "Direct bank transfer"
- Click **Continue**

#### Shipping
- Add shipping zone (e.g., "Domestic")
- Add shipping method (e.g., "Flat rate")
- Click **Continue**

#### Recommended
- Install recommended services (optional for development)
- Click **Continue**

#### Activate Jetpack
- Skip or activate Jetpack (optional)
- Click **Continue**

#### Ready!
- Click **Create your first product!**

### 3. Configure WooCommerce Settings
1. Go to **WooCommerce → Settings**
2. **General Tab**:
   - Selling location(s): Select your target locations
   - Default customer location: Select your default location
   - Currency: Select your currency
   - Currency position: Select **Left**
   - Thousand separator: `,`
   - Decimal separator: `.`
   - Number of decimals: `2`
3. **Products Tab**:
   - Measurements: Select appropriate units
   - Redirect to the cart after successful addition: Enable
4. Click **Save changes**

---

## Setting Up AquaLuxe Child Theme

### 1. Create Theme Directory
```bash
mkdir -p wordpress/wp-content/themes/aqualuxe
cd wordpress/wp-content/themes/aqualuxe
```

### 2. Create Theme Files

**style.css**
```css
/*
Theme Name: AquaLuxe
Theme URI: https://aqualuxe.com/
Description: Premium WooCommerce theme for ornamental fish
Author: AquaLuxe Team
Author URI: https://aqualuxe.com/
Template: storefront
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqualuxe
*/

/* =Variables
-------------------------------------------------------------- */
:root {
  --primary: #006994;
  --secondary: #00a8cc;
  --accent: #ffd166;
  --light: #f8f9fa;
  --dark: #343a40;
  --success: #28a745;
  --warning: #ffc107;
}

/* =Global Styles
-------------------------------------------------------------- */
body {
  background-color: var(--light);
  color: var(--dark);
  font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
  line-height: 1.6;
}

.site-header {
  background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
  padding: 1.5rem 0;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.site-branding h1 {
  color: white;
  font-size: 2.5rem;
  margin: 0;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.site-description {
  color: var(--accent);
  font-size: 1.1rem;
  font-style: italic;
}

/* =Navigation
-------------------------------------------------------------- */
.main-navigation {
  background-color: var(--dark);
}

.main-navigation ul li a {
  color: white;
  padding: 1rem 1.5rem;
  transition: all 0.3s ease;
}

.main-navigation ul li a:hover {
  background-color: var(--primary);
}

/* =WooCommerce Styles
-------------------------------------------------------------- */
.woocommerce ul.products li.product {
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 1.5rem;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.woocommerce ul.products li.product:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.woocommerce ul.products li.product .price {
  color: var(--primary);
  font-size: 1.4rem;
  font-weight: 700;
  margin: 1rem 0;
}

.woocommerce button.button,
.woocommerce a.button {
  background-color: var(--accent);
  color: var(--dark);
  padding: 0.8rem 1.5rem;
  border-radius: 30px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  transition: all 0.3s ease;
}

.woocommerce button.button:hover,
.woocommerce a.button:hover {
  background-color: #e6c25a;
  transform: translateY(-2px);
}

/* =Footer
-------------------------------------------------------------- */
.site-footer {
  background-color: var(--dark);
  color: white;
  padding: 3rem 0 1.5rem;
}

.site-footer a {
  color: var(--accent);
}

/* =Responsive
-------------------------------------------------------------- */
@media screen and (max-width: 768px) {
  .site-branding h1 {
    font-size: 2rem;
  }
  
  .woocommerce ul.products li.product {
    margin-bottom: 2rem;
  }
}
```

**functions.php**
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
 * Enqueue styles and scripts
 */
function aqualuxe_enqueue_styles() {
    // Parent theme stylesheet
    wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
    
    // Child theme stylesheet
    wp_enqueue_style( 'aqualuxe-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'storefront-style' ),
        AQUALUXE_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_styles' );

/**
 * Add WooCommerce support
 */
function aqualuxe_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_support' );

/**
 * Remove default WooCommerce styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Custom product loop thumbnail size
 */
function aqualuxe_product_thumbnail_size() {
    return 'medium';
}
add_filter( 'single_product_archive_thumbnail_size', 'aqualuxe_product_thumbnail_size' );

/**
 * Add custom body classes
 */
function aqualuxe_body_classes( $classes ) {
    if ( is_woocommerce() || is_cart() || is_checkout() ) {
        $classes[] = 'woocommerce-page';
    }
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );
```

**screenshot.png**
- Create a 1200×900px PNG image representing your theme

### 3. Install Parent Theme
1. Go to **Appearance → Themes**
2. Click **Add New**
3. Search for "Storefront"
4. Install and activate the Storefront theme

### 4. Activate Child Theme
1. Go to **Appearance → Themes**
2. You should see the AquaLuxe child theme
3. Click **Activate**

---

## Creating Essential Pages

### 1. Create Home Page
1. Go to **Pages → Add New**
2. Title: `Home`
3. In the Page Attributes box, set Template to **Front Page**
4. Click **Publish**

### 2. Create Shop Page
1. Go to **Pages → Add New**
2. Title: `Shop`
3. Click **Publish**

### 3. Create About Us Page
1. Go to **Pages → Add New**
2. Title: `About Us`
3. Add content about your business
4. Click **Publish**

### 4. Create Contact Us Page
1. Go to **Pages → Add New**
2. Title: `Contact Us`
3. Add contact information and form
4. Click **Publish**

### 5. Create Blog Page
1. Go to **Pages → Add New**
2. Title: `Blog`
3. Click **Publish**

### 6. Set Up Homepage
1. Go to **Settings → Reading**
2. Set "Your homepage displays" to **A static page**
3. Select:
   - **Homepage**: `Home`
   - **Posts page**: `Blog`
4. Click **Save Changes**

### 7. Set WooCommerce Pages
1. Go to **WooCommerce → Settings → Advanced**
2. Under "Page setup", assign:
   - **Shop page**: `Shop`
   - **Cart page**: `Cart` (will be created automatically)
   - **Checkout page**: `Checkout` (will be created automatically)
   - **My account page**: `My Account` (will be created automatically)
3. Click **Save changes**

---

## Configuring WooCommerce Products

### 1. Create Product Categories
1. Go to **Products → Categories**
2. Add categories relevant to ornamental fish:
   - Freshwater Fish
   - Saltwater Fish
   - Aquatic Plants
   - Fish Food
   - Aquarium Supplies
3. Click **Add New Category** for each

### 2. Create Sample Products
1. Go to **Products → Add New**
2. Product name: `Betta Fish - Blue`
3. Product description: Add detailed description
4. Product data:
   - Regular price: `24.99`
   - Categories: `Freshwater Fish`
   - Inventory: Set SKU and manage stock
5. Product image: Add an image
6. Click **Publish**

7. Repeat for other products

### 3. Set Up Product Attributes
1. Go to **Products → Attributes**
2. Add attributes like:
   - Color
   - Size
   - Difficulty Level
3. For each attribute, check "Used for variations" if applicable
4. Click **Add attribute**

---

## Setting Up Shipping

### 1. Set Up Shipping Zones
1. Go to **WooCommerce → Settings → Shipping**
2. Click on a shipping zone (e.g., "Domestic")
3. Add shipping methods:
   - Flat Rate: Set cost based on order value or weight
   - Free Shipping: Set minimum order amount for free shipping
4. Click **Save changes**

### 2. Set Up Shipping Classes
1. Go to **WooCommerce → Settings → Shipping → Shipping Classes**
2. Add classes like:
   - Standard
   - Express
   - International
3. Click **Save shipping classes**

---

## Configuring Taxes

### 1. Set Up Tax Rates
1. Go to **WooCommerce → Settings → Tax**
2. Check "Enable taxes"
3. Click **Save changes**
4. Go to "Standard Rates"
5. Add tax rates based on your location and requirements
6. Click **Save changes**

---

## Setting Up Payment Methods

### 1. Set Up Payment Gateways
1. Go to **WooCommerce → Settings → Payments**
2. Enable and configure payment methods:
   - Stripe: For credit card payments
   - PayPal: For PayPal payments
   - Direct Bank Transfer: For manual payments
   - Cash on Delivery: For local pickups
3. Click **Save changes**

---

## Installing Essential Plugins

### 1. Install Recommended Plugins
1. Go to **Plugins → Add New**
2. Install and activate:
   - **Yoast SEO**: For SEO optimization
   - **WP Super Cache**: For caching
   - **Contact Form 7**: For contact forms
   - **WooCommerce Product Add-ons**: For product customization
   - **WooCommerce Subscriptions**: For subscription products (if needed)
   - **WooCommerce Bookings**: For appointment booking (if needed)

### 2. Configure Plugins
1. Configure each plugin according to your needs
2. For Yoast SEO:
   - Go to **SEO → General**
   - Set up your site's basic information
   - Configure social media profiles
   - Set up XML sitemaps

---

## Setting Up Menus

### 1. Create Main Menu
1. Go to **Appearance → Menus**
2. Create a new menu called "Main Menu"
3. Add pages to the menu:
   - Home
   - Shop
   - About Us
   - Blog
   - Contact Us
4. Set "Main Menu" as the Primary Menu
5. Click **Save Menu**

### 2. Create Footer Menu
1. Create a new menu called "Footer Menu"
2. Add pages like:
   - Privacy Policy
   - Terms of Service
   - FAQ
3. Set "Footer Menu" as the Footer Menu
4. Click **Save Menu**

---

## Configuring Widgets

### 1. Set Up Sidebar Widgets
1. Go to **Appearance → Widgets**
2. Add widgets to the "Shop Sidebar":
   - Filter Products by Price
   - Product Categories
   - Product Search
   - Recent Products

### 2. Set Up Footer Widgets
1. Add widgets to the "Footer 1", "Footer 2", etc.:
   - Text widget with company info
   - Navigation menu widget
   - Social media icons widget

---

## Final Configuration

### 1. Set Up Logo
1. Go to **Appearance → Customize → Site Identity**
2. Upload your logo
3. Set site icon (favicon)
4. Click **Publish**

### 2. Customize Colors
1. Go to **Appearance → Customize → Colors**
2. Set colors to match the AquaLuxe brand:
   - Primary: `#006994`
   - Secondary: `#00a8cc`
   - Accent: `#ffd166`
3. Click **Publish**

### 3. Set Up Homepage
1. Go to **Appearance → Customize → Homepage Settings**
2. Configure homepage sections as needed
3. Click **Publish**

---

## Testing Your Site

### 1. Test Frontend
1. Visit your homepage at `http://localhost:8080`
2. Test navigation between pages
3. Test product browsing
4. Test adding products to cart
5. Test checkout process

### 2. Test Backend
1. Test adding new products
2. Test processing orders
3. Test managing inventory

---

## Preparing for Production

### 1. Export Content
1. Go to **Tools → Export**
2. Select "All content"
3. Click **Download Export File**

### 2. Back Up Database
1. Access phpMyAdmin at `http://localhost:8081`
2. Export the `aqualuxe` database

### 3. Prepare for Deployment
1. Update all plugins and themes
2. Optimize database
3. Clear cache
4. Document your setup process

---

## Conclusion

You now have a fully functional WordPress and WooCommerce site for your AquaLuxe ornamental fish business. The site includes:

- A custom AquaLuxe child theme with water-themed styling
- Essential pages (Home, Shop, About Us, Contact Us, Blog)
- WooCommerce configuration for selling products
- Shipping and tax settings
- Payment method configuration
- Menu and widget setup

To deploy this to a live server, you would:
1. Set up a hosting environment
2. Export your local database and import it to the live server
3. Upload your theme and plugins
4. Update site URLs in the database
5. Test everything on the live server

This setup provides a solid foundation for your ornamental fish business that you can further customize and expand as needed.