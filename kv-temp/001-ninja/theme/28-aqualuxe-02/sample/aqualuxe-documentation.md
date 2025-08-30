# AquaLuxe WordPress + WooCommerce Theme Documentation

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for aquatic-focused e-commerce businesses. Built with a focus on elegance, performance, and flexibility, AquaLuxe provides a complete solution for businesses selling ornamental fish, aquarium equipment, and related products and services.

The theme is built following WordPress.org and WooCommerce coding standards and adheres to SOLID, DRY, and KISS principles for clean, modular, and maintainable code architecture.

## Theme Features

### Core Features

- **Fully Responsive Design**: Optimized for all devices from mobile phones to large desktop screens
- **WooCommerce Integration**: Complete e-commerce functionality with custom styling and enhanced features
- **WooCommerce Fallbacks**: Graceful degradation when WooCommerce is not active
- **Dark Mode**: Toggle between light and dark themes with persistent user preferences
- **Multilingual Support**: Compatible with WPML and Polylang for multilingual websites
- **Custom Post Types**: Services, Events, Team Members, Projects, Testimonials, FAQs
- **Tailwind CSS**: Modern utility-first CSS framework for flexible styling
- **Theme Customizer**: Extensive options for customizing colors, typography, layouts, and more
- **Demo Content Importer**: One-click demo content import for quick setup
- **SEO Optimized**: Schema.org markup, Open Graph metadata, and clean code structure
- **Accessibility Ready**: ARIA attributes and semantic HTML5 markup

### E-commerce Features

- **Complete WooCommerce Support**: All product types, shop pages, cart, checkout, account dashboard
- **Product Quick View**: View product details without leaving the current page
- **Advanced Filtering**: Filter products by various attributes
- **Wishlist Functionality**: Allow customers to save products for later
- **Multi-Currency Ready**: Support for multiple currencies
- **Optimized Checkout**: Streamlined checkout process for better conversion rates
- **International Shipping**: Support for global shipping options

### Extended Functionality

- **Products**: Support for physical, digital, variable, and grouped products
- **Services**: Booking, scheduling, and consultation features
- **Events & Experiences**: Calendar integration and registration
- **Subscriptions**: Recurring payments and membership tiers
- **Franchise & Licensing**: Inquiry forms and partner portals
- **Sales Channels**: Support for wholesale, retail, export, B2B, and B2C
- **Professional Services**: Design, installation, maintenance, and training

## Theme Structure

AquaLuxe follows a modular and organized file structure:

```
aqualuxe/
├── assets/               # CSS, JS, images, and fonts
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   ├── images/          # Theme images
│   └── fonts/           # Font files
├── inc/                 # Theme functionality
│   ├── admin/           # Admin-specific functionality
│   ├── analytics/       # Analytics functionality
│   ├── api/             # API integration
│   ├── customizer/      # Theme customizer options
│   ├── demo-importer/   # Demo content import
│   ├── helpers/         # Helper functions
│   ├── post-types/      # Custom post type functionality
│   └── widgets/         # Custom widgets
├── languages/           # Translation files
├── template-parts/      # Reusable template parts
│   ├── analytics/       # Analytics template parts
│   ├── blocks/          # Block template parts
│   ├── content/         # Content template parts
│   ├── footer/          # Footer template parts
│   ├── header/          # Header template parts
│   └── woocommerce/     # WooCommerce fallback templates
├── templates/           # Page templates
│   ├── analytics/       # Analytics templates
│   └── content/         # Content templates
├── woocommerce/         # WooCommerce template overrides
│   ├── cart/            # Cart templates
│   ├── checkout/        # Checkout templates
│   ├── global/          # Global templates
│   └── myaccount/       # My Account templates
├── src/                 # Source files for development
├── 404.php              # 404 error page
├── archive.php          # Archive template
├── comments.php         # Comments template
├── footer.php           # Footer template
├── functions.php        # Theme functions
├── header.php           # Header template
├── index.php            # Main template
├── page.php             # Page template
├── search.php           # Search results template
├── sidebar.php          # Sidebar template
├── single.php           # Single post template
├── style.css            # Theme information and basic styles
└── tailwind.config.js   # Tailwind CSS configuration
```

## Installation

### Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- WooCommerce 6.0 or higher (optional but recommended)

### Installation Steps

1. **Upload the Theme**:
   - Go to Appearance > Themes > Add New > Upload Theme
   - Select the `aqualuxe.zip` file and click "Install Now"
   - Activate the theme after installation

2. **Install Required Plugins**:
   - After activating the theme, you'll see a notification to install recommended plugins
   - Install and activate WooCommerce for full e-commerce functionality
   - Other recommended plugins include Contact Form 7 and Yoast SEO

3. **Import Demo Content** (Optional):
   - Go to Appearance > AquaLuxe Demo Importer
   - Click "Import Demo Content" to set up your site with sample content

4. **Configure Theme Settings**:
   - Go to Appearance > Customize to configure theme options
   - Set up your logo, colors, typography, and other settings

## Theme Customization

### Theme Customizer

AquaLuxe provides extensive customization options through the WordPress Customizer:

1. **Site Identity**:
   - Logo
   - Site title and tagline
   - Site icon (favicon)

2. **Colors**:
   - Primary color
   - Secondary color
   - Accent color
   - Background color
   - Text color

3. **Typography**:
   - Font families
   - Font sizes
   - Line heights
   - Font weights

4. **Layout Options**:
   - Container width
   - Sidebar position
   - Header layout
   - Footer layout

5. **WooCommerce Options**:
   - Product grid layout
   - Product page layout
   - Cart and checkout layout
   - Shop sidebar options

6. **Advanced Options**:
   - Dark mode toggle
   - Custom CSS
   - Performance options

### Tailwind CSS

AquaLuxe uses Tailwind CSS for styling. To customize the theme's appearance beyond the Customizer options:

1. **Edit the Tailwind Configuration**:
   - Modify `tailwind.config.js` to change colors, spacing, typography, etc.

2. **Compile CSS**:
   - Run `npm run dev` for development
   - Run `npm run build` for production

## WooCommerce Integration

AquaLuxe is fully integrated with WooCommerce, providing enhanced styling and functionality for all e-commerce features.

### WooCommerce Templates

The theme includes custom templates for all WooCommerce pages:

- Shop page (`archive-product.php`)
- Single product page (`single-product.php`)
- Cart page (`cart/cart.php`)
- Checkout page (`checkout/form-checkout.php`)
- My Account page (`myaccount/my-account.php`)

### WooCommerce Fallbacks

AquaLuxe includes fallback templates for when WooCommerce is not active, ensuring a consistent user experience:

- Shop fallback (`template-parts/woocommerce/fallback-shop.php`)
- Product fallback (`template-parts/woocommerce/fallback-product.php`)
- Cart fallback (`template-parts/woocommerce/fallback-cart.php`)
- Checkout fallback (`template-parts/woocommerce/fallback-checkout.php`)
- Account fallback (`template-parts/woocommerce/fallback-account.php`)

## Custom Post Types

AquaLuxe includes several custom post types to enhance your site's functionality:

1. **Services**:
   - Display your services with details like price, duration, and features
   - Categorize services with `service_category` taxonomy

2. **Events**:
   - Showcase upcoming events with date, time, location, and registration details
   - Organize events with `event_category` taxonomy

3. **Team Members**:
   - Present your team with positions, contact information, and social media links
   - Group team members with `team_category` taxonomy

4. **Projects**:
   - Display your completed projects with client, location, and gallery
   - Categorize projects with `project_category` taxonomy

5. **Testimonials**:
   - Share client testimonials with ratings and client information

6. **FAQs**:
   - Organize frequently asked questions with `faq_category` taxonomy

## Shortcodes

AquaLuxe provides several shortcodes to display content from custom post types:

1. **Services Shortcode**:
   ```
   [aqualuxe_services count="3" category="maintenance" columns="3" layout="grid"]
   ```

2. **Events Shortcode**:
   ```
   [aqualuxe_events count="3" category="workshops" show_past="no" layout="grid"]
   ```

3. **Testimonials Shortcode**:
   ```
   [aqualuxe_testimonials count="3" layout="slider"]
   ```

4. **Team Shortcode**:
   ```
   [aqualuxe_team count="4" category="management" columns="4"]
   ```

5. **Projects Shortcode**:
   ```
   [aqualuxe_projects count="6" category="residential" layout="masonry"]
   ```

6. **FAQs Shortcode**:
   ```
   [aqualuxe_faqs count="10" category="shipping"]
   ```

## Multilingual Support

AquaLuxe is fully compatible with popular multilingual plugins:

### WPML Compatibility

- Register strings for translation
- Language switcher integration
- RTL support for right-to-left languages

### Polylang Compatibility

- Register strings for translation
- Language switcher integration
- Custom post type translation support

## Dark Mode

AquaLuxe includes a built-in dark mode feature:

- Toggle between light and dark themes
- Persistent user preference using localStorage
- Automatic detection of system preference
- Customizable dark mode colors

## Performance Optimization

AquaLuxe is optimized for performance:

- Minified CSS and JavaScript
- Lazy loading of images
- Optimized asset loading
- Efficient code structure

## Child Theme Development

To customize AquaLuxe without modifying the parent theme, use a child theme:

1. **Create a Child Theme Directory**:
   - Create a new directory named `aqualuxe-child` in your themes folder

2. **Create style.css**:
   ```css
   /*
   Theme Name: AquaLuxe Child
   Theme URI: https://aqualuxe.com
   Description: Child theme for AquaLuxe
   Author: Your Name
   Author URI: https://yoursite.com
   Template: aqualuxe
   Version: 1.0.0
   */
   ```

3. **Create functions.php**:
   ```php
   <?php
   function aqualuxe_child_enqueue_styles() {
       wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
       wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-parent-style'));
   }
   add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
   ```

4. **Activate the Child Theme**:
   - Go to Appearance > Themes and activate your child theme

## Support and Updates

For support and updates, please contact:

- **Support Email**: support@aqualuxe.com
- **Documentation**: https://aqualuxe.com/documentation
- **Updates**: Theme updates are available through WordPress or by downloading from your account

## Credits

AquaLuxe was developed by NinjaTech AI and uses the following libraries and resources:

- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **Swiper**: Modern mobile touch slider
- **Chart.js**: JavaScript charting library
- **Font Awesome**: Icon library

## License

AquaLuxe is licensed under the GPL v2 or later. See the LICENSE file for more information.