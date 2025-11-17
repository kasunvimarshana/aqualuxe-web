# AquaLuxe Theme - Installation Guide v1.3.2

## Requirements

Before installing AquaLuxe Theme, please ensure your hosting environment meets the following requirements:

- WordPress 6.0 or higher
- PHP 8.0 or higher
- MySQL 5.6 or higher
- WooCommerce 7.0 or higher (optional, but recommended for full functionality)
- Node.js 16.x or higher (for development)
- npm 8.x or higher (for development)

## Installation

### Method 1: WordPress Admin Dashboard

1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Themes**
3. Click the **Add New** button at the top of the page
4. Click the **Upload Theme** button
5. Click **Choose File** and select the `aqualuxe-v1.3.1.zip` file
6. Click **Install Now**
7. After installation is complete, click **Activate**

### Method 2: FTP Upload

1. Extract the `aqualuxe-v1.3.1.zip` file on your computer
2. Using an FTP client, connect to your web server
3. Navigate to the `/wp-content/themes/` directory
4. Upload the `aqualuxe` folder to this directory
5. Log in to your WordPress admin dashboard
6. Navigate to **Appearance > Themes**
7. Find AquaLuxe and click **Activate**

## Required Plugins

AquaLuxe works best with the following plugins:

1. **WooCommerce** - For e-commerce functionality
2. **Elementor** - For drag-and-drop page building (optional)
3. **Contact Form 7** - For contact forms (optional)
4. **Yoast SEO** - For SEO optimization (optional)
5. **WPML** or **Polylang** - For multilingual support (optional)

After activating the theme, you'll see a notice recommending these plugins. You can install them directly from that notice or manually from the Plugins section.

## Demo Content Import

AquaLuxe comes with demo content to help you get started quickly:

1. Navigate to **Appearance > Import Demo Data**
2. Click the **Import Demo Data** button
3. Wait for the import process to complete
4. You'll see a success message when the import is finished

## Theme Customization

AquaLuxe offers extensive customization options through the WordPress Customizer:

1. Navigate to **Appearance > Customize**
2. Explore the following sections:
   - **Site Identity**: Logo, site title, favicon
   - **Colors**: Primary, secondary, accent colors
   - **Typography**: Font families, sizes, weights
   - **Layout**: Header, footer, sidebar options
   - **WooCommerce**: Shop and product display options
   - **Blog**: Post layout and display options
   - **Dark Mode**: Dark mode settings and colors
   - **Multilingual**: Language switcher options
   - **Performance**: Performance optimization settings
   - **Advanced**: Custom CSS and JavaScript

## Development Setup

If you want to modify the theme or contribute to its development:

1. Navigate to the theme directory
2. Run `npm install` to install dependencies
3. Run `npm run dev` to compile assets for development
4. Run `npm run watch` to watch for changes and recompile
5. Run `npm run prod` to compile assets for production

## Performance Features

### Critical CSS

To generate critical CSS for your specific content:

1. Update the URLs in `critical-css.js` to match your site structure
2. Run `npm run critical` to generate critical CSS files
3. The critical CSS files will be saved in `assets/css/critical/`

### WebP Images

To convert your images to WebP format:

1. Place your images in `assets/src/images/`
2. Run `npm run imagemin` to optimize images and generate WebP versions
3. The optimized images will be saved in `assets/images/`

### SVG Sprites

To generate SVG sprites:

1. Place your SVG icons in `assets/src/images/icons/`
2. Run `npm run svg-sprite` to generate the sprite
3. The sprite will be saved as `assets/images/sprite.svg`

## Troubleshooting

### Common Issues

1. **White Screen of Death**:
   - Increase PHP memory limit in wp-config.php
   - Disable plugins to identify conflicts
   - Switch to a default theme and re-activate AquaLuxe

2. **WooCommerce Pages Not Styled**:
   - Navigate to **WooCommerce > Status > Tools**
   - Click "Regenerate product lookup tables"
   - Click "Regenerate product attributes lookup table"

3. **Demo Import Fails**:
   - Increase PHP memory limit and execution time
   - Try importing in parts (content, widgets, customizer settings)
   - Check server error logs for specific issues

4. **Mobile Menu Not Working**:
   - Clear browser cache
   - Check for JavaScript errors in browser console
   - Ensure jQuery is loaded properly

5. **Custom Fonts Not Loading**:
   - Check if font files are properly uploaded
   - Verify font paths in CSS
   - Clear browser cache

## Support

If you need assistance with the AquaLuxe theme:

- **Documentation**: Comprehensive documentation is available in the `docs` folder
- **Support**: For theme support, please contact support@aqualuxe.example.com
- **Updates**: Theme updates will be available through the WordPress admin dashboard

## License

AquaLuxe is licensed under GPL-2.0-or-later.