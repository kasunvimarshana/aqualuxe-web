# AquaLuxe Theme Customization Guide

This guide will help you customize the AquaLuxe theme to match your brand and create a unique look for your online store.

## Theme Customizer

The WordPress Customizer is the primary tool for customizing your AquaLuxe theme. It provides a live preview of your changes before you publish them.

To access the Customizer:

1. Log in to your WordPress admin dashboard
2. Navigate to **Appearance > Customize**

### Site Identity

- **Logo**: Upload your brand logo (recommended size: 250px × 100px)
- **Site Title**: Set your website name
- **Tagline**: Add a short description of your site
- **Site Icon (Favicon)**: Upload a small icon (at least 512px × 512px) that appears in browser tabs

### Colors

AquaLuxe comes with a carefully selected color palette that you can customize:

- **Primary Color**: Used for buttons, links, and accents (default: #0e7490)
- **Secondary Color**: Used for hover states and secondary elements (default: #0891b2)
- **Accent Color**: Used for highlights and special elements (default: #06b6d4)
- **Text Color**: Main text color (default: #0f172a)
- **Background Color**: Site background color (default: #ffffff)

You can also customize colors for specific elements:

- **Header Background**
- **Footer Background**
- **Button Colors**
- **Sale Badge Color**
- **Price Color**

### Typography

Customize the fonts used throughout your site:

- **Body Font**: Choose from 20+ Google Fonts for main text
- **Heading Font**: Select a font for all headings
- **Font Sizes**: Adjust sizes for body text, headings, buttons, etc.
- **Line Height**: Control the spacing between lines of text
- **Letter Spacing**: Adjust the spacing between letters

### Layout Options

Control the overall layout of your site:

- **Container Width**: Set the maximum width of your site content
- **Content Layout**: Choose between full width or boxed layout
- **Sidebar Position**: Left, right, or none (for specific page types)
- **Content Padding**: Adjust spacing around content areas

### Header Options

Customize your site header:

- **Header Layout**: Choose from 5 pre-designed header layouts
- **Sticky Header**: Enable/disable a header that stays at the top when scrolling
- **Transparent Header**: Enable on specific pages for a header that overlays content
- **Top Bar**: Enable/disable and customize the top information bar
- **Search Icon**: Show/hide the search icon in the header
- **Cart Icon**: Customize the cart icon display and mini-cart behavior
- **Mobile Header**: Configure how the header appears on mobile devices

### Footer Options

Customize your site footer:

- **Footer Layout**: Choose from 4 pre-designed footer layouts
- **Widget Areas**: Select the number of footer widget columns (1-4)
- **Footer Text**: Edit the copyright text and credits
- **Footer Menu**: Enable/disable and position the footer menu
- **Newsletter Form**: Add and configure a newsletter signup form
- **Social Icons**: Add and arrange social media links

### WooCommerce Options

Customize your shop and product pages:

- **Shop Layout**: Grid or list view, number of columns
- **Product Card Style**: Choose from 3 product card designs
- **Sale Badge Style**: Customize the appearance of sale badges
- **Quick View**: Enable/disable product quick view feature
- **Wishlist**: Configure wishlist functionality
- **Product Gallery**: Choose between different gallery styles
- **Checkout Layout**: Customize the checkout process layout
- **Related Products**: Control the display of related products

## Advanced Customization

### Custom CSS

For more advanced customization, you can add custom CSS:

1. In the Customizer, navigate to **Additional CSS**
2. Add your custom CSS code
3. See the changes in real-time in the preview
4. Click **Publish** when satisfied

Example custom CSS:

```css
/* Make primary buttons rounded */
.wp-block-button__link,
.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button {
    border-radius: 50px;
    padding-left: 2rem;
    padding-right: 2rem;
}

/* Change the hover color of menu items */
.primary-menu a:hover {
    color: #06b6d4;
}
```

### Child Theme

For extensive customizations, we recommend using a child theme:

1. Create a new folder named `aqualuxe-child` in your `/wp-content/themes/` directory
2. Create a `style.css` file in that folder with the following content:

```css
/*
Theme Name: AquaLuxe Child
Theme URI: https://aqualuxetheme.com/
Description: Child theme for AquaLuxe
Author: Your Name
Author URI: https://yourwebsite.com/
Template: aqualuxe
Version: 1.0.0
*/

/* Add your custom styles below this line */
```

3. Create a `functions.php` file with:

```php
<?php
/**
 * AquaLuxe Child Theme functions and definitions
 */

// Enqueue parent and child theme styles
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');

// Add your custom functions below this line
```

4. Activate the child theme from **Appearance > Themes**

### Block Patterns

AquaLuxe includes custom block patterns that you can use to quickly build pages:

1. In the block editor, click the **+** button to add a new block
2. Click the **Patterns** tab
3. Browse the **AquaLuxe** category to find pre-designed patterns
4. Click on a pattern to insert it into your page
5. Customize the content as needed

### Block Styles

The theme also includes custom block styles that you can apply to blocks:

1. Add or select a block in the editor
2. In the block settings sidebar, look for the **Styles** section
3. Choose from the available custom styles
4. Some blocks with custom styles include:
   - Buttons (Fill Primary, Outline Primary, etc.)
   - Headings (Underline, Highlight, etc.)
   - Lists (Check List, Arrow List, etc.)
   - Images (Rounded, Frame, etc.)
   - Groups (Card, Border, etc.)

## Theme Options Panel

AquaLuxe includes a dedicated options panel for additional settings:

1. Navigate to **Appearance > AquaLuxe Options**
2. Explore the following tabs:
   - **General**: Global theme settings
   - **Header**: Advanced header options
   - **Footer**: Advanced footer options
   - **WooCommerce**: Shop customization
   - **Performance**: Optimization settings
   - **Import/Export**: Backup and restore settings

## Customizing Templates

To customize specific templates:

1. Create a folder named `aqualuxe` in your child theme
2. Copy the template file you want to modify from the parent theme to this folder
3. Make your changes to the copied file
4. The child theme version will now override the parent theme version

For WooCommerce templates:

1. Create a folder named `woocommerce` in your child theme
2. Copy the WooCommerce template file from the parent theme's `woocommerce` folder
3. Make your changes to the copied file

## Getting Help

If you need assistance with customization:

- Check our [knowledge base](https://aqualuxetheme.com/kb) for tutorials
- Join our [community forum](https://aqualuxetheme.com/community) to connect with other users
- Contact our support team for personalized help