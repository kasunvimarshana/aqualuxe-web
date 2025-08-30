# AquaLuxe Installation Guide

## Overview
This guide provides step-by-step instructions for installing and setting up the AquaLuxe WooCommerce child theme. Follow these instructions carefully to ensure proper installation and configuration.

## System Requirements

### Minimum Requirements
- WordPress 5.0 or higher
- WooCommerce 4.0 or higher
- PHP 7.2 or higher
- MySQL 5.6 or higher
- Apache or Nginx web server

### Recommended Requirements
- WordPress 5.8 or higher
- WooCommerce 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher
- HTTPS support

## Installation Methods

### Method 1: WordPress Admin Dashboard (Recommended)

#### Step 1: Install Storefront Parent Theme
1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Themes**
3. Click **Add New**
4. Search for "Storefront"
5. Click **Install** and then **Activate**

#### Step 2: Install AquaLuxe Child Theme
1. Navigate to **Appearance > Themes**
2. Click **Add New**
3. Click **Upload Theme**
4. Click **Choose File** and select the `aqualuxe.zip` file
5. Click **Install Now**
6. Click **Activate** to activate the theme

### Method 2: FTP/SFTP Installation

#### Step 1: Upload Storefront Parent Theme
1. Download Storefront from [woocommerce.com](https://woocommerce.com/storefront/)
2. Extract the downloaded file
3. Connect to your server via FTP/SFTP
4. Navigate to `/wp-content/themes/`
5. Upload the `storefront` folder to this directory
6. Activate Storefront in **Appearance > Themes**

#### Step 2: Upload AquaLuxe Child Theme
1. Extract the `aqualuxe.zip` file
2. Connect to your server via FTP/SFTP
3. Navigate to `/wp-content/themes/`
4. Upload the `aqualuxe` folder to this directory
5. Activate AquaLuxe in **Appearance > Themes**

### Method 3: Manual Installation via Server

#### Step 1: Install Storefront via WP-CLI
```bash
wp theme install storefront --activate
```

#### Step 2: Install AquaLuxe via WP-CLI
```bash
wp theme install path/to/aqualuxe.zip --activate
```

## Initial Setup

### 1. WooCommerce Setup Wizard
After activating the theme, you'll be prompted to run the WooCommerce setup wizard:
1. Click **Run the Setup Wizard**
2. Follow the steps to configure:
   - Store location
   - Currency and tax settings
   - Shipping zones
   - Payment methods
3. Complete the wizard

### 2. Import Demo Content (Optional)
To quickly set up sample content:

#### Via WordPress Admin:
1. Navigate to **Appearance > Import Demo Data**
2. Click **Import Demo Data**
3. Wait for the import process to complete
4. Review the imported content

#### Via WP-CLI:
```bash
wp aqualuxe import-demo-content
```

### 3. Customize Theme Options
Navigate to **Appearance > Customize** to configure theme options:

#### Site Identity
- Upload your logo
- Set site title and tagline
- Configure site icon

#### Colors
- Choose a color scheme
- Customize accent colors
- Set background colors

#### Header Options
- Select header layout
- Configure navigation settings
- Set header background

#### WooCommerce Options
- Configure product grid settings
- Set catalog display options
- Configure cart and checkout settings

## Configuration Steps

### 1. Configure Menus
1. Navigate to **Appearance > Menus**
2. Create or edit menus for:
   - Primary navigation
   - Footer navigation
   - Mobile navigation
3. Assign menus to theme locations

### 2. Set Up Widgets
1. Navigate to **Appearance > Widgets**
2. Configure widget areas:
   - Sidebar (Shop)
   - Product Filters
   - Footer Widget Areas
3. Add widgets to appropriate areas

### 3. Configure WooCommerce Settings
1. Navigate to **WooCommerce > Settings**
2. Review and configure:
   - General settings
   - Product settings
   - Tax settings
   - Shipping settings
   - Payment settings
   - Account settings
   - Email settings

### 4. Set Up Products
1. Navigate to **Products > Add New**
2. Create product categories
3. Add products with:
   - Product images
   - Descriptions
   - Pricing
   - Inventory settings
   - Shipping settings

### 5. Configure Pages
Ensure the following pages exist and are properly configured:
- Shop page
- Cart page
- Checkout page
- My Account page
- Terms and Conditions page
- Privacy Policy page

## Customization Options

### Theme Customizer
Access all customization options via **Appearance > Customize**:

#### Layout Settings
- Sidebar positioning
- Content width
- Container width

#### Typography
- Font selection
- Font sizes
- Line heights
- Text colors

#### Buttons
- Button styles
- Button colors
- Button hover effects

#### Product Display
- Product grid columns
- Product image sizes
- Quick view settings

### Custom CSS
Add custom CSS via **Appearance > Customize > Additional CSS** or by creating a child theme CSS file.

## Plugin Recommendations

### Essential Plugins
- **WooCommerce** (Required)
- **Storefront Powerpack** (Enhances Storefront features)
- **WooCommerce PayPal Payments** (Payment gateway)
- **WooCommerce Stripe Payment Gateway** (Payment gateway)

### Recommended Plugins
- **Yoast SEO** (Search engine optimization)
- **WooCommerce Google Analytics Integration** (Analytics)
- **UpdraftPlus** (Backup solution)
- **WP Rocket** (Caching and performance)

### Optional Plugins
- **WooCommerce Subscriptions** (For subscription products)
- **WooCommerce Bookings** (For booking services)
- **WooCommerce Memberships** (For membership sites)
- **WooCommerce Product Add-Ons** (For product customizations)

## Troubleshooting

### Common Issues and Solutions

#### Issue: Theme shows "Template is missing"
**Solution**: Ensure Storefront parent theme is installed and activated before activating AquaLuxe.

#### Issue: Customizer options not saving
**Solution**: 
1. Check file permissions for theme directory
2. Clear browser cache
3. Clear WordPress cache
4. Deactivate and reactivate theme

#### Issue: AJAX features not working
**Solution**:
1. Check for JavaScript errors in browser console
2. Ensure WordPress permalinks are set correctly
3. Check for plugin conflicts
4. Verify server configuration allows AJAX requests

#### Issue: Products not displaying correctly
**Solution**:
1. Check WooCommerce setup
2. Verify product categories and tags
3. Check product visibility settings
4. Clear any caching plugins

### Debugging Steps
1. Enable WordPress debug mode by adding to `wp-config.php`:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );
   define( 'WP_DEBUG_DISPLAY', false );
   ```

2. Check browser console for JavaScript errors
3. Check server error logs
4. Deactivate plugins to identify conflicts
5. Switch to default theme to identify theme conflicts

## Performance Optimization

### Caching
- Install a caching plugin (WP Rocket, W3 Total Cache)
- Enable browser caching
- Enable server-side caching

### Image Optimization
- Compress images before upload
- Use WebP format when possible
- Implement lazy loading
- Use appropriate image sizes

### Minification
- Minify CSS and JavaScript files
- Combine CSS and JavaScript files when possible
- Use a CDN for static assets

## Security Considerations

### Theme Security
- Keep WordPress, themes, and plugins updated
- Use strong passwords
- Limit login attempts
- Use SSL/HTTPS

### File Permissions
- Set correct file permissions:
  - Directories: 755
  - Files: 644
  - wp-config.php: 600

### Regular Maintenance
- Regular backups
- Security scans
- Update monitoring
- Performance monitoring

## Updating the Theme

### Backup Before Updating
1. Backup your website files
2. Backup your database
3. Note current customizations

### Update Process
1. Download the latest version of AquaLuxe
2. Navigate to **Appearance > Themes**
3. Activate a different theme temporarily
4. Delete the old AquaLuxe theme
5. Upload and activate the new version

### Child Theme Considerations
If you've made customizations:
1. Document all customizations
2. Use a child theme for custom code
3. Test customizations after update
4. Update child theme if needed

## Support

### Documentation
- Refer to theme documentation files
- Check the `readme.txt` file
- Review inline help in WordPress admin

### Community Support
- WordPress support forums
- WooCommerce community forums
- Theme developer support channels

### Professional Support
For premium support, contact the theme developer or hire a WordPress professional.

## Frequently Asked Questions

### Q: Do I need to install Storefront separately?
A: Yes, Storefront is a required parent theme and must be installed and activated before using AquaLuxe.

### Q: Can I use this theme without WooCommerce?
A: While the theme will work without WooCommerce, it's specifically designed for e-commerce and many features will be unused.

### Q: How do I customize the theme colors?
A: Navigate to **Appearance > Customize > Colors** to adjust the color scheme and individual color settings.

### Q: Can I add my own custom code?
A: Yes, you can add custom CSS via **Appearance > Customize > Additional CSS** or create a child theme for more extensive customizations.

### Q: How do I get demo content?
A: Navigate to **Appearance > Import Demo Data** to import sample products, pages, and settings.

### Q: What browsers are supported?
A: The theme supports all modern browsers including Chrome, Firefox, Safari, Edge, iOS Safari, and Android Chrome.

### Q: How do I report bugs or request features?
A: Contact the theme developer through the official support channels or submit issues through the theme's GitHub repository.

## Conclusion

Following this installation guide will help you successfully set up and configure the AquaLuxe WooCommerce child theme. Take your time with each step and refer to the documentation if you encounter any issues.

For the best experience:
1. Ensure all system requirements are met
2. Follow the installation steps in order
3. Configure settings according to your needs
4. Regularly update and maintain your site
5. Refer to documentation for advanced customizations

If you encounter any issues not covered in this guide, please contact support or consult the WordPress and WooCommerce communities for additional assistance.