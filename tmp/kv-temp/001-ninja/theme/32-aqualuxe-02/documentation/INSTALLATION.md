# AquaLuxe WordPress Theme - Installation Guide

## Version 1.3.3

Thank you for choosing AquaLuxe, a premium WordPress + WooCommerce theme designed for luxury aquatic retail businesses. This guide will help you install and set up the theme on your WordPress website.

## Package Contents

- `aqualuxe/` - The main theme directory to be installed in WordPress
- `INSTALLATION.md` - This installation guide
- `README.md` - General information about the theme

## Requirements

Before installing the AquaLuxe theme, ensure your hosting environment meets the following requirements:

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.6 or higher (or MariaDB 10.0 or higher)
- WooCommerce 7.0 or higher (optional, but recommended for full functionality)
- Node.js 20.x or higher (for development only)
- npm 10.x or higher (for development only)

## Installation Methods

### Method 1: WordPress Admin Panel

1. **Download the Theme**
   - If you haven't already, extract the `aqualuxe.zip` file you received.

2. **Upload and Install**
   - Log in to your WordPress admin panel.
   - Navigate to **Appearance > Themes**.
   - Click the **Add New** button at the top of the page.
   - Click the **Upload Theme** button.
   - Click **Choose File**, select the `aqualuxe.zip` file, and click **Install Now**.
   - After installation completes, click **Activate** to activate the theme.

### Method 2: FTP Installation

1. **Extract the Theme**
   - Extract the `aqualuxe.zip` file to your computer.

2. **Upload via FTP**
   - Connect to your server using an FTP client (like FileZilla).
   - Navigate to the `/wp-content/themes/` directory.
   - Upload the entire `aqualuxe` folder to this directory.

3. **Activate the Theme**
   - Log in to your WordPress admin panel.
   - Navigate to **Appearance > Themes**.
   - Find the AquaLuxe theme and click **Activate**.

## Post-Installation Setup

### 1. Required Plugins

For optimal functionality, we recommend installing the following plugins:

- **WooCommerce** - For e-commerce functionality
- **Contact Form 7** - For contact forms
- **Yoast SEO** - For SEO optimization
- **W3 Total Cache** - For performance optimization
- **Regenerate Thumbnails** - For generating image thumbnails

After activating the theme, you'll see a notice recommending these plugins. You can install them directly from that notice or manually from the Plugins section.

### 2. Import Demo Content (Optional)

To make your site look like our demo:

1. Navigate to **Appearance > AquaLuxe Demo Import**.
2. Click **Import Demo Data**.
3. Wait for the import process to complete.

### 3. Configure Theme Settings

1. **General Theme Settings**
   - Navigate to **Appearance > Customize**.
   - Configure general settings like logo, site identity, colors, and typography.

2. **Header Settings**
   - In the Customizer, navigate to the **Header** section.
   - Configure header layout, navigation, and other header elements.

3. **Footer Settings**
   - In the Customizer, navigate to the **Footer** section.
   - Configure footer layout, widgets, and copyright information.

4. **WooCommerce Settings** (if applicable)
   - In the Customizer, navigate to the **WooCommerce** section.
   - Configure shop layout, product display, and checkout options.

5. **Blog Settings**
   - In the Customizer, navigate to the **Blog** section.
   - Configure blog layout, post display, and archive settings.

### 4. Set Up Pages

1. **Create Essential Pages**
   - Home
   - About
   - Services
   - Blog
   - Contact
   - Shop (if using WooCommerce)
   - Cart (if using WooCommerce)
   - Checkout (if using WooCommerce)
   - My Account (if using WooCommerce)

2. **Set Up Front Page**
   - Navigate to **Settings > Reading**.
   - Select "A static page" for "Your homepage displays".
   - Choose your Home page from the dropdown.
   - Select your Blog page from the "Posts page" dropdown.
   - Click **Save Changes**.

## Development Setup

If you want to modify the theme or contribute to its development:

1. Navigate to the theme directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to compile assets for development
4. Run `npm run watch` to watch for changes and recompile
5. Run `npm run prod` to compile assets for production

### Performance Features

#### Critical CSS

To generate critical CSS for your specific content:

1. Update the URLs in `critical-css.js` to match your site structure
2. Run `npm run critical` to generate critical CSS files
3. The critical CSS files will be saved in `assets/css/critical/`

#### WebP Images

To convert your images to WebP format:

1. Place your images in `assets/src/images/`
2. Run `npm run imagemin` to optimize images and generate WebP versions
3. The optimized images will be saved in `assets/images/`

#### SVG Sprites

To generate SVG sprites:

1. Place your SVG icons in `assets/src/images/icons/`
2. Run `npm run svg-sprite` to generate the sprite
3. The sprite will be saved as `assets/images/sprite.svg`

## Troubleshooting

If you encounter any issues during installation or setup, please check the following:

1. **Theme Not Appearing**
   - Make sure the theme files are properly uploaded to the `/wp-content/themes/aqualuxe/` directory.
   - Check that the theme folder structure is intact.

2. **Broken Layout**
   - Ensure your WordPress and PHP versions meet the requirements.
   - Try regenerating thumbnails using the Regenerate Thumbnails plugin.
   - Clear your browser cache and any caching plugins.

3. **WooCommerce Integration Issues**
   - Make sure WooCommerce is installed and activated.
   - Check that your WooCommerce version is compatible (7.0+).
   - Navigate to WooCommerce > Status to check for any issues.

4. **Plugin Conflicts**
   - Temporarily deactivate all plugins to see if the issue persists.
   - Reactivate plugins one by one to identify the conflicting plugin.

5. **PHP Errors**
   - If you encounter PHP errors related to duplicate function declarations, make sure you're using the latest version of the theme (1.3.3) which has fixed these issues.
   - Check your PHP error logs for any other issues.

## Support

If you need further assistance, please contact our support team:

- **Email**: support@example.com
- **Support Forum**: https://example.com/support
- **Documentation**: See the `documentation` folder for detailed guides

## License

AquaLuxe is licensed under the GPL-2.0-or-later license. See the LICENSE.txt file for more information.