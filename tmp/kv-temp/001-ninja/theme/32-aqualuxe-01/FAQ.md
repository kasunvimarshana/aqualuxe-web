# AquaLuxe Enhanced Theme - Frequently Asked Questions

## General Questions

### What is AquaLuxe Enhanced Theme?
AquaLuxe Enhanced is a premium WordPress + WooCommerce theme designed specifically for luxury aquatic retail businesses. It features advanced performance optimizations, enhanced WooCommerce functionality, and a modern, responsive design.

### What are the system requirements for AquaLuxe Enhanced?
- WordPress 5.9 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (optional, but recommended for full functionality)

### Is WooCommerce required to use AquaLuxe Enhanced?
No, WooCommerce is not required. AquaLuxe Enhanced has a dual-state architecture that works perfectly with or without WooCommerce. However, to access the e-commerce features, you'll need to install and activate WooCommerce.

### Does AquaLuxe Enhanced work with page builders?
Yes, AquaLuxe Enhanced is compatible with popular page builders like Elementor, Beaver Builder, and Divi. It has been specifically tested and optimized for Elementor.

### Is AquaLuxe Enhanced translation-ready?
Yes, AquaLuxe Enhanced is fully translation-ready. It includes proper text domains and supports popular translation plugins like WPML and Polylang. It also includes RTL (Right-to-Left) language support.

### Does AquaLuxe Enhanced support child themes?
Yes, AquaLuxe Enhanced is designed with child theme support in mind. You can create a child theme to make customizations that will persist through theme updates.

## Installation & Setup

### How do I install AquaLuxe Enhanced?
Please refer to the INSTALLATION.md file for detailed installation instructions. You can install the theme via the WordPress admin dashboard or via FTP.

### How do I import the demo content?
After activating the theme, navigate to Appearance > Import Demo Data and click the "Import Demo Data" button. The process may take a few minutes to complete.

### Why does the theme look different from the demo after installation?
This is likely because you haven't imported the demo content yet. The demo content includes pre-configured pages, posts, products, and customizer settings that make the theme look like the demo.

### How do I set up the homepage?
1. Create a new page and title it "Home"
2. Select the "Front Page" template from the Page Attributes section
3. Publish the page
4. Go to Settings > Reading
5. Select "A static page" for "Your homepage displays"
6. Choose your "Home" page for the Homepage setting
7. Save changes

### How do I customize the theme colors and fonts?
Navigate to Appearance > Customize to access the WordPress Customizer. From there, you can modify colors, fonts, layouts, and other theme settings.

## Performance Features

### What is lazy loading and how does it work?
Lazy loading is a technique that defers the loading of off-screen images, videos, and other content until the user scrolls to them. AquaLuxe Enhanced implements advanced lazy loading using the Intersection Observer API, which significantly improves page load times.

### What is critical CSS and how does it improve performance?
Critical CSS is the minimum CSS required to render the above-the-fold content of a page. By inlining this CSS in the head of the document, the initial render is much faster, improving perceived performance. AquaLuxe Enhanced automatically extracts and inlines critical CSS for key templates.

### What is a service worker and how does it help?
A service worker is a script that runs in the background and enables features like offline access, background sync, and push notifications. AquaLuxe Enhanced includes a service worker that caches important resources, allowing the site to work even when offline or on slow connections.

### How does WebP image support work?
AquaLuxe Enhanced automatically detects if a browser supports WebP images and serves them instead of JPEG or PNG when possible. WebP images are typically 25-30% smaller than JPEG or PNG, resulting in faster page loads. For browsers that don't support WebP, the theme falls back to JPEG or PNG.

### How can I optimize my images for the theme?
You can use the included image optimization script by running `npm run imagemin`. This will optimize all images in the `assets/src/images/` directory and generate WebP versions.

## WooCommerce Features

### How does the AJAX cart work?
The AJAX cart allows users to add products to their cart, update quantities, and remove items without reloading the page. This creates a smoother shopping experience. The mini cart will slide in when a product is added, showing the current cart contents.

### How does the product quick view work?
The product quick view allows customers to view product details in a modal without leaving the current page. It includes the product gallery, description, price, variations, and add to cart button. To enable it, make sure the "Enable Product Quick View" option is checked in the Customizer.

### How does the wishlist functionality work?
The wishlist allows customers to save products for later. They can add products to their wishlist, view their wishlist on a dedicated page, and share their wishlist via social media, email, or a direct link. The wishlist works for both logged-in and guest users.

### Does AquaLuxe Enhanced support variable products?
Yes, AquaLuxe Enhanced fully supports WooCommerce variable products. It enhances the variable product display with swatches for color and image attributes, and improves the variation selection experience.

### Can I customize the WooCommerce templates?
Yes, AquaLuxe Enhanced includes customized WooCommerce templates in the `woocommerce` directory. You can further customize these templates by copying them to your child theme's `woocommerce` directory.

## Customization

### How do I add custom CSS?
You can add custom CSS in two ways:
1. Through the WordPress Customizer: Navigate to Appearance > Customize > Additional CSS
2. By creating a child theme and adding your CSS to the child theme's style.css file

### How do I modify the theme's PHP files?
It's recommended to create a child theme and override the template files in your child theme. This ensures your changes won't be lost when the theme is updated.

### Can I change the header and footer layouts?
Yes, AquaLuxe Enhanced includes multiple header and footer layouts that you can select in the WordPress Customizer. Navigate to Appearance > Customize > Header (or Footer) to choose a layout and customize it.

### How do I add custom fonts?
You can add custom fonts through the WordPress Customizer. Navigate to Appearance > Customize > Typography and select from the available font options. If you need to add a font that's not included, you can do so by using a child theme or a custom plugin.

### Can I disable certain features I don't need?
Yes, most features can be enabled or disabled through the WordPress Customizer. Navigate to Appearance > Customize and look for the specific feature you want to disable.

## Troubleshooting

### Why is my site loading slowly?
There could be several reasons for slow loading:
1. Hosting issues - Make sure you're using a quality hosting provider
2. Large, unoptimized images - Use the image optimization script
3. Too many plugins - Deactivate unnecessary plugins
4. Caching not configured - Set up a caching plugin
5. External scripts - Minimize the use of external scripts and services

### Why don't my changes in the Customizer appear on the site?
This could be due to caching. Try clearing your browser cache and any caching plugins you might be using. Also, make sure you've clicked the "Publish" button in the Customizer to save your changes.

### Why are my WooCommerce pages not styled correctly?
This can happen if the WooCommerce templates need to be refreshed. Navigate to WooCommerce > Status > Tools and click "Regenerate product lookup tables" and "Regenerate product attributes lookup table".

### Why isn't the demo import working?
The demo import might fail due to server limitations. Try increasing your PHP memory limit and execution time in your wp-config.php file or contact your hosting provider for assistance.

### How do I get help if I encounter issues?
If you encounter any issues with AquaLuxe Enhanced, please contact our support team at support@aqualuxe.example.com or visit our support forum at https://aqualuxe.example.com/support.