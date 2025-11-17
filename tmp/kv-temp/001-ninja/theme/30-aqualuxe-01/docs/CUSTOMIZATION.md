# AquaLuxe WordPress Theme Customization Guide

This document provides detailed instructions for customizing the AquaLuxe WordPress theme to match your brand and requirements.

## WordPress Customizer

The easiest way to customize AquaLuxe is through the WordPress Customizer. To access it:

1. Log in to your WordPress admin dashboard
2. Navigate to Appearance > Customize

### Site Identity

In the Site Identity section, you can:

- Upload your logo (recommended size: 250px × 100px)
- Set your site title and tagline
- Upload a site icon (favicon)

#### Logo Recommendations
- Use a transparent PNG or SVG format
- Ensure the logo is legible in both light and dark mode
- For best results, use a logo with a height of 60-80px

### Colors

In the Colors section, you can customize:

- Primary Color: Used for buttons, links, and accents
- Secondary Color: Used for secondary buttons and accents
- Background Color: Used for the site background
- Text Color: Used for body text
- Link Color: Used for links within content
- Button Color: Used for primary buttons

#### Color Scheme Recommendations
- Choose colors that reflect your brand identity
- Ensure sufficient contrast between text and background colors
- Consider how colors will appear in dark mode

### Typography

In the Typography section, you can customize:

- Heading Font: Used for all headings (h1-h6)
- Body Font: Used for body text and paragraphs
- Font Sizes: Adjust sizes for headings and body text
- Line Heights: Adjust line spacing for better readability

#### Font Recommendations
- Choose readable fonts for body text
- Limit your site to 2-3 font families for consistency
- Consider web-safe fonts or Google Fonts for better performance

### Header

In the Header section, you can customize:

- Header Layout: Choose between different header layouts
- Header Background: Set a color or image for the header background
- Header Text Color: Set the color for text in the header
- Sticky Header: Enable/disable a header that stays at the top when scrolling
- Transparent Header: Enable/disable a transparent header on specific pages

### Footer

In the Footer section, you can customize:

- Footer Layout: Choose between different footer layouts
- Footer Background: Set a color or image for the footer background
- Footer Text Color: Set the color for text in the footer
- Footer Widgets: Configure the number of widget columns
- Copyright Text: Customize the copyright text in the footer

### Blog

In the Blog section, you can customize:

- Blog Layout: Choose between different blog layouts
- Featured Image Size: Set the size for featured images
- Excerpt Length: Set the number of words in excerpts
- Read More Text: Customize the "Read More" link text
- Post Meta Display: Choose which meta information to display

### WooCommerce

If WooCommerce is active, you can customize:

- Shop Layout: Choose between different shop layouts
- Product Grid Columns: Set the number of columns in product grids
- Related Products Count: Set the number of related products to display
- Upsell Products Count: Set the number of upsell products to display
- Cross-Sell Products Count: Set the number of cross-sell products to display
- Quick View: Enable/disable and customize the quick view feature
- Wishlist: Enable/disable and customize the wishlist feature
- Multi-Currency: Configure currency options

### Dark Mode

In the Dark Mode section, you can customize:

- Dark Mode Toggle: Enable/disable the dark mode toggle
- Dark Mode Colors: Customize colors for dark mode
- Auto Dark Mode: Enable/disable automatic dark mode based on user's system preferences

### Multilingual

In the Multilingual section, you can customize:

- Language Switcher: Enable/disable and position the language switcher
- RTL Support: Configure support for right-to-left languages

## Custom CSS

For more advanced customizations, you can add custom CSS:

1. In the WordPress Customizer, navigate to Additional CSS
2. Add your custom CSS code
3. The changes will be previewed in real-time
4. Click "Publish" to save your changes

### Example Custom CSS

```css
/* Change the primary button hover color */
.button.button-primary:hover {
    background-color: #0056b3;
}

/* Adjust the spacing between menu items */
.main-navigation .menu > li {
    margin-right: 20px;
}

/* Customize the footer widget titles */
.footer-widget h2 {
    font-size: 18px;
    border-bottom: 2px solid #4299e1;
    padding-bottom: 10px;
}
```

## Page Templates

AquaLuxe includes several page templates that you can use to create different page layouts:

### Default Template
The standard template with a sidebar.

### Full Width Template
A template without a sidebar, where content spans the full width of the container.

### Homepage Template
A special template for the homepage with sections for featured products, categories, testimonials, etc.

### About Template
A template designed for about pages with sections for team members, company history, etc.

### Services Template
A template designed for service pages with sections for different services.

### Contact Template
A template designed for contact pages with a contact form and map.

### FAQ Template
A template designed for FAQ pages with accordion-style questions and answers.

To use a template:

1. Edit a page in WordPress
2. In the Page Attributes section, select the desired template from the Template dropdown
3. Update the page

## Widget Areas

AquaLuxe includes several widget areas that you can customize:

### Sidebar
Appears on blog posts, archives, and pages with the default template.

### Footer 1-4
Appears in the footer, with the number of visible areas depending on your footer layout setting.

### Shop Sidebar
Appears on WooCommerce shop pages and product archives.

To customize widgets:

1. Navigate to Appearance > Widgets
2. Drag and drop widgets into the desired widget areas
3. Configure each widget's settings as needed

## Menus

AquaLuxe supports multiple menu locations:

### Primary Menu
The main navigation menu in the header.

### Footer Menu
A simple menu in the footer.

### Social Links Menu
A menu for social media links.

### Mobile Menu
A menu specifically for mobile devices.

To customize menus:

1. Navigate to Appearance > Menus
2. Create or edit a menu
3. Add pages, categories, custom links, etc. to the menu
4. Under Menu Settings, check the location(s) where you want the menu to appear
5. Click "Save Menu"

## Homepage Sections

The homepage template includes several customizable sections:

### Hero Slider
A full-width slider at the top of the homepage.

To customize:
1. Navigate to Appearance > Customize > Homepage > Hero Slider
2. Add, remove, or edit slides
3. Configure slider settings (autoplay, speed, etc.)

### Featured Categories
A grid of product categories.

To customize:
1. Navigate to Appearance > Customize > Homepage > Featured Categories
2. Select which categories to feature
3. Choose the layout and number of categories to display

### Featured Products
A carousel of featured products.

To customize:
1. Navigate to Appearance > Customize > Homepage > Featured Products
2. Choose which products to feature (featured, best-selling, on sale, etc.)
3. Set the number of products to display

### Promo Banner
A promotional banner with a call-to-action.

To customize:
1. Navigate to Appearance > Customize > Homepage > Promo Banner
2. Set the banner text, button text, and link
3. Choose a background image or color

### Latest Products
A grid of the latest products.

To customize:
1. Navigate to Appearance > Customize > Homepage > Latest Products
2. Set the number of products to display
3. Choose the layout and sorting order

### Testimonials
A carousel of customer testimonials.

To customize:
1. Navigate to Appearance > Customize > Homepage > Testimonials
2. Choose which testimonials to display
3. Set the number of testimonials and display options

### Features
A section highlighting key features or benefits.

To customize:
1. Navigate to Appearance > Customize > Homepage > Features
2. Add, remove, or edit features
3. Choose icons and layout options

### Newsletter
A newsletter signup form.

To customize:
1. Navigate to Appearance > Customize > Homepage > Newsletter
2. Set the heading, description, and form shortcode
3. Choose a background image or color

## WooCommerce Customization

### Product Archive Page

To customize the shop page and product category pages:

1. Navigate to Appearance > Customize > WooCommerce > Product Archives
2. Configure the following settings:
   - Products per page
   - Columns in product grid
   - Product image size
   - Sale badge position
   - "New" badge display
   - Quick view button
   - Wishlist button
   - Product information to display

### Single Product Page

To customize individual product pages:

1. Navigate to Appearance > Customize > WooCommerce > Single Product
2. Configure the following settings:
   - Product image size
   - Gallery thumbnail size
   - Related products display
   - Upsell products display
   - Product tabs to display
   - Social sharing buttons
   - Stock status display
   - SKU display

### Cart and Checkout

To customize the cart and checkout pages:

1. Navigate to Appearance > Customize > WooCommerce > Cart & Checkout
2. Configure the following settings:
   - Cross-sells display
   - Cart totals position
   - Checkout layout
   - Order notes field
   - Terms and conditions display
   - Express checkout options

### My Account

To customize the my account page:

1. Navigate to Appearance > Customize > WooCommerce > My Account
2. Configure the following settings:
   - Dashboard welcome message
   - Account menu items
   - Order history display
   - Downloads display
   - Address form fields

## Advanced Customization

For more advanced customizations, you may need to create a child theme or modify template files. Please refer to the [DEVELOPER.md](DEVELOPER.md) document for detailed information on theme structure, hooks, filters, and more.

## Getting Help

If you need assistance with customizing your AquaLuxe theme:

- Check our [documentation](https://aqualuxe.example.com/docs)
- Visit our [support forum](https://aqualuxe.example.com/support)
- Contact our [support team](mailto:support@aqualuxe.example.com)