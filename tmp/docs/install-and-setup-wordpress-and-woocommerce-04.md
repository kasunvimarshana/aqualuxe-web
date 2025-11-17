# Complete Step-by-Step Guide: Install and Setup WordPress & WooCommerce for AquaLuxe

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Setting Up Docker Environment](#setting-up-docker-environment)
3. [Install WordPress](#install-wordpress)
4. [Configure WordPress Settings](#configure-wordpress-settings)
5. [Install WooCommerce](#install-woocommerce)
6. [Set Up AquaLuxe Child Theme](#set-up-aqualuxe-child-theme)
7. [Create Essential Pages](#create-essential-pages)
8. [Configure WooCommerce Products](#configure-woocommerce-products)
9. [Configure Shipping](#configure-shipping)
10. [Configure Taxes](#configure-taxes)
11. [Configure Payment Methods](#configure-payment-methods)
12. [Install Essential Plugins](#install-essential-plugins)
13. [Set Up Menu](#set-up-menu)
14. [Configure Widgets](#configure-widgets)
15. [Final Configuration](#final-configuration)
16. [Test Your Site](#test-your-site)
17. [Prepare for Production](#prepare-for-production)
18. [Conclusion](#conclusion)

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

Create a file named `docker-compose.yml` with the necessary configuration to set up WordPress, MySQL, phpMyAdmin, and Redis containers.

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

## Install WordPress

### 2.1 Access WordPress Setup

Open your browser and go to: http://localhost:8080

You'll see the WordPress setup screen.

### 2.2 Complete WordPress Installation

- Select your language and click "Continue"
- Fill in the site information:
  - Site Title: AquaLuxe
  - Username: admin (or your preferred username)
  - Password: your-secure-password
  - Your Email: your-email@example.com
  - Check "Discourage search engines from indexing this site" (for development)
- Click "Install WordPress"

### 2.3 Log In to WordPress

- After installation, click "Log In"
- Enter your credentials to access the WordPress dashboard

---

## Configure WordPress Settings

### 3.1 General Settings

- Go to Settings → General
- Update:
  - Site Title: AquaLuxe
  - Tagline: Premium Ornamental Fish for Enthusiasts and Collectors
  - Email Address: your-email@example.com
  - Timezone: Select your timezone
- Click "Save Changes"

### 3.2 Permalink Settings

- Go to Settings → Permalinks
- Select "Post name"
- Click "Save Changes"

### 3.3 Reading Settings

- Go to Settings → Reading
- Set "Your homepage displays" to "A static page"
- Select:
  - Homepage: (will create later)
  - Posts page: (will create later)
- Click "Save Changes"

### 3.4 Discussion Settings

- Go to Settings → Discussion
- Uncheck "Allow people to post comments on new articles" (optional)
- Click "Save Changes"

---

## Install WooCommerce

### 4.1 Install WooCommerce Plugin

- Go to Plugins → Add New
- Search for "WooCommerce"
- Click "Install Now" next to WooCommerce
- After installation, click "Activate"

### 4.2 WooCommerce Setup Wizard

After activation, WooCommerce will launch the setup wizard.

- Click "Let's Go!" to begin

#### 4.2.1 Store Setup

- Address fields: Fill in your business address
- Currency: Select your currency (e.g., USD, EUR, etc.)
- Product type: Select "Physical"
- Click "Continue"

#### 4.2.2 Payment

- Select payment methods (e.g., Stripe, PayPal)
- For development, you can select "Cash on delivery" or "Direct bank transfer"
- Click "Continue"

#### 4.2.3 Shipping

- Add shipping zone (e.g., "Domestic")
- Add shipping method (e.g., "Flat rate")
- Click "Continue"

#### 4.2.4 Recommended

- Install recommended services (optional for development)
- Click "Continue"

#### 4.2.5 Activate Jetpack

- Skip or activate Jetpack (optional)
- Click "Continue"

#### 4.2.6 Ready!

- Click "Create your first product!"

### 4.3 Configure WooCommerce Settings

- Go to WooCommerce → Settings

#### General Tab:

- Selling location(s): Select your target locations
- Default customer location: Select your default location
- Currency: Select your currency
- Currency position: Select "Left"
- Thousand separator: ,
- Decimal separator: .
- Number of decimals: 2

#### Products Tab:

- Measurements: Select appropriate units
- Redirect to the cart after successful addition: Enable
- Click "Save changes"

---

## Set Up AquaLuxe Child Theme

### 5.1 Create Theme Directory Structure

1. Navigate to the WordPress theme directory:

   ```bash
   cd wordpress/wp-content/themes
   ```

2. Create a new directory for the AquaLuxe theme:
   ```bash
   mkdir aqualuxe
   cd aqualuxe
   ```

### 5.2 Create Theme Files

Create the following files in the `aqualuxe` directory:

#### style.css

Create a style.css file with the required header information for a child theme.

#### functions.php

Create a functions.php file with basic theme setup functions.

#### screenshot.png

Create a 1200×900px image with a preview of your theme and save it as `screenshot.png`.

### 5.3 Install Parent Theme

- Go to Appearance → Themes
- Click "Add New"
- Search for "Storefront"
- Install and activate the Storefront theme

### 5.4 Activate Child Theme

- Go to Appearance → Themes
- You should see the AquaLuxe child theme
- Click "Activate"

---

## Create Essential Pages

### 6.1 Create Home Page

- Go to Pages → Add New
- Title: Home
- In the Page Attributes box, set Template to Front Page
- Click "Publish"

### 6.2 Create Shop Page

- Go to Pages → Add New
- Title: Shop
- Click "Publish"

### 6.3 Create About Us Page

- Go to Pages → Add New
- Title: About Us
- Add content about your business
- Click "Publish"

### 6.4 Create Contact Us Page

- Go to Pages → Add New
- Title: Contact Us
- Add contact information and form
- Click "Publish"

### 6.5 Create Blog Page

- Go to Pages → Add New
- Title: Blog
- Click "Publish"

### 6.6 Set Up Homepage

- Go to Settings → Reading
- Set "Your homepage displays" to "A static page"
- Select:
  - Homepage: Home
  - Posts page: Blog
- Click "Save Changes"

### 6.7 Set WooCommerce Pages

- Go to WooCommerce → Settings → Advanced
- Under "Page setup", assign:
  - Shop page: Shop
  - Cart page: Cart (will be created automatically)
  - Checkout page: Checkout (will be created automatically)
  - My account page: My Account (will be created automatically)
- Click "Save changes"

---

## Configure WooCommerce Products

### 7.1 Create Product Categories

- Go to Products → Categories
- Add categories relevant to ornamental fish:
  - Freshwater Fish
  - Saltwater Fish
  - Aquatic Plants
  - Fish Food
  - Aquarium Supplies
- Click "Add New Category" for each

### 7.2 Create Sample Products

- Go to Products → Add New
- Product name: Betta Fish - Blue
- Product description: Add detailed description
- Product data:
  - Regular price: 24.99
  - Categories: Freshwater Fish
  - Inventory: Set SKU and manage stock
- Product image: Add an image
- Click "Publish"
- Repeat for other products

### 7.3 Set Up Product Attributes

- Go to Products → Attributes
- Add attributes like:
  - Color
  - Size
  - Difficulty Level
- For each attribute, check "Used for variations" if applicable
- Click "Add attribute"

---

## Configure Shipping

### 8.1 Set Up Shipping Zones

- Go to WooCommerce → Settings → Shipping
- Click on a shipping zone (e.g., "Domestic")
- Add shipping methods:
  - Flat Rate: Set cost based on order value or weight
  - Free Shipping: Set minimum order amount for free shipping
- Click "Save changes"

### 8.2 Set Up Shipping Classes

- Go to WooCommerce → Settings → Shipping → Shipping Classes
- Add classes like:
  - Standard
  - Express
  - International
- Click "Save shipping classes"

---

## Configure Taxes

### 9.1 Set Up Tax Rates

- Go to WooCommerce → Settings → Tax
- Check "Enable taxes"
- Click "Save changes"
- Go to "Standard Rates"
- Add tax rates based on your location and requirements
- Click "Save changes"

---

## Configure Payment Methods

### 10.1 Set Up Payment Gateways

- Go to WooCommerce → Settings → Payments
- Enable and configure payment methods:
  - Stripe: For credit card payments
  - PayPal: For PayPal payments
  - Direct Bank Transfer: For manual payments
  - Cash on Delivery: For local pickups
- Click "Save changes"

---

## Install Essential Plugins

### 11.1 Install Recommended Plugins

- Go to Plugins → Add New
- Install and activate:
  - Yoast SEO: For SEO optimization
  - WP Super Cache: For caching
  - Contact Form 7: For contact forms
  - WooCommerce Product Add-ons: For product customization
  - WooCommerce Subscriptions: For subscription products (if needed)
  - WooCommerce Bookings: For appointment booking (if needed)

### 11.2 Configure Plugins

#### For Yoast SEO:

- Go to SEO → General
- Set up your site's basic information
- Configure social media profiles
- Set up XML sitemaps

#### For Contact Form 7:

- Go to Contact → Add New
- Create a contact form with fields for name, email, subject, and message
- Copy the shortcode
- Paste it into your Contact Us page

---

## Set Up Menu

### 12.1 Create Main Menu

- Go to Appearance → Menus
- Create a new menu called "Main Menu"
- Add pages to the menu:
  - Home
  - Shop
  - About Us
  - Blog
  - Contact Us
- Set "Main Menu" as the Primary Menu
- Click "Save Menu"

### 12.2 Create Footer Menu

- Create a new menu called "Footer Menu"
- Add pages like:
  - Privacy Policy
  - Terms of Service
  - FAQ
- Set "Footer Menu" as the Footer Menu
- Click "Save Menu"

---

## Configure Widgets

### 13.1 Set Up Sidebar Widgets

- Go to Appearance → Widgets
- Add widgets to the "Shop Sidebar":
  - Filter Products by Price
  - Product Categories
  - Product Search
  - Recent Products

### 13.2 Set Up Footer Widgets

- Add widgets to the "Footer 1", "Footer 2", etc.:
  - Text widget with company info
  - Navigation menu widget
  - Social media icons widget

---

## Final Configuration

### 14.1 Set Up Logo

- Go to Appearance → Customize → Site Identity
- Upload your logo
- Set site icon (favicon)
- Click "Publish"

### 14.2 Customize Colors

- Go to Appearance → Customize → Colors
- Set colors to match the AquaLuxe brand:
  - Primary: #006994
  - Secondary: #00a8cc
  - Accent: #ffd166
- Click "Publish"

### 14.3 Set Up Homepage

- Go to Appearance → Customize → Homepage Settings
- Configure homepage sections as needed
- Click "Publish"

---

## Test Your Site

### 15.1 Test Frontend

- Visit your homepage at http://localhost:8080
- Test navigation between pages
- Test product browsing
- Test adding products to cart
- Test checkout process

### 15.2 Test Backend

- Test adding new products
- Test processing orders
- Test managing inventory

---

## Prepare for Production

### 16.1 Export Content

- Go to Tools → Export
- Select "All content"
- Click "Download Export File"

### 16.2 Back Up Database

- Access phpMyAdmin at http://localhost:8081
- Export the aqualuxe database

### 16.3 Prepare for Deployment

- Update all plugins and themes
- Optimize database
- Clear cache
- Document your setup process

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
