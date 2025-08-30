# AquaLuxe WordPress Theme Documentation

## Introduction

AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses, aquarium stores, and aquatic-related services. This elegant and functional theme combines beautiful design with powerful features to create an engaging online presence for your aquatic business.

## Table of Contents

1. [Installation](#installation)
2. [Theme Setup](#theme-setup)
3. [Customizer Options](#customizer-options)
4. [Page Templates](#page-templates)
5. [Custom Post Types](#custom-post-types)
6. [WooCommerce Integration](#woocommerce-integration)
7. [Theme Features](#theme-features)
8. [Shortcodes](#shortcodes)
9. [Widget Areas](#widget-areas)
10. [Theme Options](#theme-options)
11. [Customization](#customization)
12. [Troubleshooting](#troubleshooting)
13. [Support](#support)

## Installation

### Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

### Installation Steps

1. **Upload the theme**
   - Go to Appearance > Themes > Add New > Upload Theme
   - Choose the `aqualuxe.zip` file and click "Install Now"
   - Activate the theme after installation

2. **Install required plugins**
   - After activation, you'll be prompted to install required plugins
   - Click "Begin Installing Plugins" and follow the instructions
   - Required plugins include:
     - WooCommerce
     - Elementor
     - Contact Form 7
     - AquaLuxe Core (included with the theme)

3. **Import demo content (optional)**
   - Go to Appearance > AquaLuxe Setup
   - Click on the "Import Demo Content" tab
   - Click "Import Demo Content" button

## Theme Setup

### Initial Setup

1. **Set up your homepage**
   - Go to Pages > Add New
   - Create a new page titled "Home"
   - Set the template to "Front Page" in the Page Attributes section
   - Go to Settings > Reading
   - Set "Your homepage displays" to "A static page"
   - Select your newly created "Home" page as the homepage

2. **Set up menus**
   - Go to Appearance > Menus
   - Create a new menu and add your pages
   - Assign the menu to the "Primary Menu" location
   - Optionally create additional menus for "Footer Menu" and "Top Bar Menu"

3. **Configure WooCommerce (if using)**
   - Follow the WooCommerce setup wizard
   - Set up payment methods, shipping zones, and tax options
   - Create product categories and add products

## Customizer Options

AquaLuxe offers extensive customization options through the WordPress Customizer. Access these by going to Appearance > Customize.

### Site Identity

- **Logo**: Upload your business logo (recommended size: 180×60px)
- **Site Title & Tagline**: Set your site name and tagline
- **Site Icon**: Upload a favicon (site icon)

### Colors

- **Primary Color**: Main accent color used throughout the site
- **Secondary Color**: Secondary accent color for buttons and highlights
- **Text Color**: Default color for body text
- **Heading Color**: Color for all headings
- **Link Color**: Color for text links
- **Link Hover Color**: Color when links are hovered

### Typography

- **Body Font**: Choose from 800+ Google Fonts for your main text
- **Heading Font**: Select a font for all headings
- **Font Sizes**: Adjust sizes for body text and various heading levels

### Header Options

- **Header Layout**: Choose from 3 header layouts
- **Sticky Header**: Enable/disable sticky header on scroll
- **Header Transparency**: Make header transparent on homepage
- **Top Bar**: Enable/disable top bar with contact info and social icons
- **Shopping Cart**: Show/hide cart icon in header
- **Search Icon**: Show/hide search icon in header

### Footer Options

- **Footer Layout**: Choose from 4 footer layouts
- **Footer Columns**: Select number of widget columns (1-4)
- **Footer Text**: Edit copyright text and footer credits
- **Footer Background**: Change background color or image
- **Footer Widgets**: Show/hide footer widget area

### Homepage Sections

- **Hero Section**: Configure hero slider or static image
- **Featured Products**: Select products to feature
- **Categories Section**: Highlight product categories
- **About Section**: Add image and text about your business
- **Services Section**: Display your services
- **Testimonials Section**: Show customer testimonials
- **Blog Section**: Display recent blog posts
- **CTA Section**: Configure call-to-action section

### Blog Options

- **Blog Layout**: Choose grid, list, or standard layout
- **Sidebar Position**: Left, right, or no sidebar
- **Featured Images**: Show/hide and configure size
- **Meta Information**: Show/hide author, date, categories, tags
- **Excerpt Length**: Set number of words in excerpts
- **Read More Text**: Customize the "Read More" button text

### WooCommerce Options

- **Shop Layout**: Choose product display layout
- **Products Per Page**: Set number of products per page
- **Product Columns**: Set number of columns (2-6)
- **Related Products**: Show/hide and configure
- **Quick View**: Enable/disable product quick view
- **Wishlist**: Enable/disable wishlist functionality
- **Product Zoom**: Enable/disable image zoom on hover

## Page Templates

AquaLuxe includes several specialized page templates:

### Front Page Template (front-page.php)
- Designed for your homepage with multiple customizable sections
- Configure sections through the Customizer

### Full Width Template
- No sidebar, content spans the full width of the page
- Ideal for landing pages or content-heavy pages

### Services Template
- Displays your services in a grid layout with icons
- Automatically pulls from Services custom post type

### Team Template
- Displays team members in a grid with photos and info
- Pulls from Team custom post type

### Contact Template
- Includes Google Map integration
- Contact form section
- Business hours and contact information

### Projects Template
- Showcases your aquarium projects in a filterable portfolio
- Pulls from Projects custom post type

## Custom Post Types

AquaLuxe includes several custom post types to organize specialized content:

### Services
- Used to showcase services you offer
- Fields include:
  - Title
  - Description
  - Icon
  - Price
  - Duration
  - Featured image

### Events
- For workshops, seminars, and other events
- Fields include:
  - Title
  - Description
  - Date and time
  - Location
  - Cost
  - Registration link
  - Featured image

### Projects
- Showcase completed aquarium installations
- Fields include:
  - Title
  - Description
  - Client name
  - Completion date
  - Project type
  - Gallery images
  - Featured image

### Testimonials
- Display customer reviews and feedback
- Fields include:
  - Client name
  - Client location
  - Rating (1-5 stars)
  - Testimonial text
  - Client photo

### Team
- Showcase your staff members
- Fields include:
  - Name
  - Position
  - Bio
  - Expertise
  - Social media links
  - Photo

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and includes enhanced styling and features:

### Custom Shop Pages
- Beautifully styled product archives
- Custom category display
- Enhanced product filtering

### Product Quick View
- View product details in a popup
- Add to cart without leaving the page
- Configurable in Customizer

### Wishlist Functionality
- Allow customers to save products for later
- Persistent across sessions when logged in
- Share wishlist via social media or email

### Enhanced Product Gallery
- Zoom on hover
- Lightbox for full-size images
- Gallery thumbnails with custom navigation

### Custom Product Tabs
- Additional information tabs
- Customizable tab content
- Add custom tabs as needed

## Theme Features

### Dark Mode
- Toggle between light and dark color schemes
- Automatic detection of system preference
- User selection saved in localStorage
- Configure default mode in Customizer

### Responsive Design
- Fully responsive on all devices
- Custom breakpoints for optimal display
- Touch-friendly navigation on mobile
- Configurable mobile menu behavior

### Performance Optimization
- Optimized asset loading
- Lazy loading for images
- Minified CSS and JavaScript
- Browser caching recommendations

### Accessibility
- WCAG 2.1 AA compliant
- Keyboard navigation support
- Screen reader friendly markup
- Proper contrast ratios

### SEO Friendly
- Schema.org markup
- Clean, semantic HTML
- Optimized heading structure
- Fast loading times

### Social Media Integration
- Social sharing buttons for products and posts
- Social profile links in header and footer
- Instagram feed integration
- Facebook page integration

## Shortcodes

AquaLuxe includes several useful shortcodes:

### Button Shortcode
```
[aqualuxe_button url="https://example.com" style="primary" size="medium"]Button Text[/aqualuxe_button]
```
- Options:
  - `url`: Button link
  - `style`: primary, secondary, outline
  - `size`: small, medium, large
  - `target`: _self, _blank

### Products Shortcode
```
[aqualuxe_products count="4" columns="4" category="fish"]
```
- Options:
  - `count`: Number of products to display
  - `columns`: Number of columns (1-6)
  - `category`: Product category slug
  - `featured`: true/false to show only featured products

### Services Shortcode
```
[aqualuxe_services count="3" columns="3" style="grid"]
```
- Options:
  - `count`: Number of services to display
  - `columns`: Number of columns (1-4)
  - `style`: grid, list, carousel

### Testimonials Shortcode
```
[aqualuxe_testimonials count="3" style="slider"]
```
- Options:
  - `count`: Number of testimonials to display
  - `style`: grid, slider
  - `columns`: Number of columns for grid style (1-4)

### Team Shortcode
```
[aqualuxe_team count="4" columns="4"]
```
- Options:
  - `count`: Number of team members to display
  - `columns`: Number of columns (1-4)
  - `orderby`: name, position, order

## Widget Areas

AquaLuxe includes several widget areas for customization:

### Sidebar
- Appears on blog posts and pages with sidebar layout
- Default widgets: Search, Recent Posts, Categories, Tags

### Shop Sidebar
- Appears on WooCommerce shop pages and product archives
- Default widgets: Product Categories, Product Filter, Price Filter

### Footer Widgets
- 1-4 columns in footer (configurable)
- Appears on all pages
- Default widgets vary by column

### Top Bar Widgets
- Appears in the top bar area
- Ideal for contact information and social icons

### Page Bottom Widgets
- Appears before the footer on all pages
- Good for newsletter signup or promotional content

## Theme Options

AquaLuxe includes a Theme Options panel accessible via Appearance > AquaLuxe Options:

### General Settings
- Site layout (boxed or full-width)
- Content width
- Scroll to top button
- Preloader settings

### Header Settings
- Additional header options not in Customizer
- Mega menu configuration
- Header scripts

### Footer Settings
- Additional footer options
- Footer scripts

### Blog Settings
- Advanced blog configuration
- Social sharing options
- Related posts settings

### WooCommerce Settings
- Advanced shop settings
- Product display options
- Checkout customization

### Performance
- Asset optimization
- Lazy loading settings
- Minification options
- Browser caching

### Custom Code
- Custom CSS
- Custom JavaScript
- Header and footer code injection

## Customization

### Child Theme
For extensive customization, we recommend using a child theme. A starter child theme is included in the package.

1. Upload the `aqualuxe-child` folder to your themes directory
2. Activate the AquaLuxe Child theme
3. Customize the child theme files as needed

### CSS Customization
For minor CSS tweaks:

1. Go to Appearance > Customize > Additional CSS
2. Add your custom CSS
3. Save changes

### PHP Customization
To modify theme functionality:

1. Create a child theme (recommended)
2. Override template files by copying them from the parent theme to the child theme
3. Modify functions by using the appropriate WordPress hooks

## Troubleshooting

### Common Issues

#### White Screen or Fatal Error
- Deactivate all plugins to identify conflicts
- Switch to a default WordPress theme temporarily
- Check PHP error logs

#### Layout Issues
- Clear browser cache
- Disable caching plugins temporarily
- Check for CSS conflicts with browser inspector

#### WooCommerce Problems
- Ensure WooCommerce is updated to the latest version
- Reset WooCommerce permalinks (WooCommerce > Settings > Advanced > Reset Permalinks)
- Verify theme compatibility in System Status report

#### Customizer Not Saving
- Increase PHP memory limit
- Disable plugins temporarily
- Try a different browser

## Support

### Getting Help

- **Documentation**: Refer to this documentation for detailed instructions
- **Knowledge Base**: Visit our online knowledge base at https://aqualuxetheme.com/kb/
- **Video Tutorials**: Watch tutorial videos at https://aqualuxetheme.com/tutorials/
- **Support Forum**: Get help from our support team at https://aqualuxetheme.com/support/
- **Email Support**: Contact us directly at support@aqualuxetheme.com

### Updates

AquaLuxe is regularly updated with new features, bug fixes, and compatibility improvements. To update:

1. Go to Appearance > Themes
2. Click on the update notification if available
3. Alternatively, upload the new version through the WordPress admin

Always back up your site before updating themes or plugins.

---

Thank you for choosing AquaLuxe! We hope this theme helps you create a beautiful and effective online presence for your aquatic business.