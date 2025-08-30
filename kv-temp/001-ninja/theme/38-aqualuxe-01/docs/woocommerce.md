# AquaLuxe WooCommerce Integration Guide

This guide explains how AquaLuxe integrates with WooCommerce and how to optimize your online store for the best customer experience.

## Overview

AquaLuxe is designed specifically for WooCommerce stores selling luxury bathroom and kitchen fixtures, spa products, and other water-related luxury items. The theme includes custom styling and enhanced functionality for all WooCommerce elements.

## WooCommerce Setup

### Installation and Activation

1. If WooCommerce isn't already installed, go to **Plugins > Add New**
2. Search for "WooCommerce"
3. Click **Install Now** and then **Activate**
4. Follow the WooCommerce setup wizard to configure your store basics

### Store Configuration

After completing the setup wizard, configure these essential settings:

1. **General Settings** (WooCommerce > Settings):
   - Set your store address, selling locations, and shipping zones
   - Configure currency options

2. **Product Settings** (WooCommerce > Settings > Products):
   - Set measurement units
   - Configure inventory management
   - Set up product display options

3. **Tax Settings** (WooCommerce > Settings > Tax):
   - Set up tax rates based on your location and requirements

4. **Shipping Settings** (WooCommerce > Settings > Shipping):
   - Create shipping zones
   - Add shipping methods
   - Set shipping rates

5. **Payment Settings** (WooCommerce > Settings > Payments):
   - Enable and configure payment gateways
   - Set up payment processing options

## AquaLuxe WooCommerce Features

### Enhanced Shop Page

AquaLuxe provides several layout options for your shop page:

1. **Shop Layout** (Customize > WooCommerce > Shop):
   - Grid Layout (2-4 columns)
   - List Layout
   - Masonry Grid Layout

2. **Filtering Options**:
   - AJAX filtering without page reload
   - Off-canvas filter sidebar on mobile
   - Filter products by price, attributes, categories, and tags

3. **Sorting Options**:
   - Custom sorting controls
   - Quick sort dropdown

### Product Cards

AquaLuxe includes three product card styles that you can choose from:

1. **Standard**: Clean design with image, title, price, and add to cart button
2. **Elegant**: Minimalist design with hover effects and quick actions
3. **Detailed**: More information including short description and rating

To change the product card style:

1. Go to **Customize > WooCommerce > Product Cards**
2. Select your preferred style
3. Configure additional options:
   - Quick view button
   - Wishlist button
   - Compare button
   - Quick add to cart
   - Image hover effect

### Single Product Page

The single product page has been optimized for luxury products:

1. **Gallery Options** (Customize > WooCommerce > Single Product):
   - Standard gallery with zoom
   - Gallery slider
   - Vertical thumbnails
   - Horizontal thumbnails

2. **Product Information Layout**:
   - Choose between tabs or accordion for product information
   - Customize tab/section titles and order
   - Enable/disable specific sections

3. **Additional Features**:
   - Size guide popup
   - Product video support
   - 360° product view (requires additional plugin)
   - Product inquiry form
   - Social sharing buttons

### Cart Page

The cart page has been enhanced with:

1. **Real-time Cart Updates**:
   - AJAX quantity updates
   - Automatic totals recalculation

2. **Cross-sell Display Options**:
   - Grid or carousel layout
   - Number of products to display
   - Position on page

3. **Additional Features**:
   - Cart notes
   - Gift wrapping option
   - Estimated delivery date
   - Save cart for later

### Checkout Page

The checkout experience has been optimized for:

1. **Layout Options**:
   - One-page checkout
   - Multi-step checkout
   - Distraction-free checkout

2. **Enhanced Fields**:
   - Address autocomplete
   - Inline field validation
   - Optional account creation
   - Guest checkout

3. **Additional Features**:
   - Order summary sidebar
   - Coupon code entry
   - Trust badges
   - Payment method icons

### My Account Page

The customer account area includes:

1. **Enhanced Dashboard**:
   - Visual order status
   - Quick reorder buttons
   - Recently viewed products

2. **Additional Endpoints**:
   - Wishlist
   - Saved addresses
   - Payment methods
   - Downloads

## Custom WooCommerce Templates

AquaLuxe includes the following customized WooCommerce templates:

- `archive-product.php` - Shop page layout
- `single-product.php` - Single product page layout
- `content-product.php` - Product card layout
- `cart/cart.php` - Cart page layout
- `checkout/form-checkout.php` - Checkout form layout
- `myaccount/my-account.php` - Customer account layout
- `global/quantity-input.php` - Quantity input styling
- `loop/price.php` - Product price display
- `loop/sale-flash.php` - Sale badge styling
- `single-product/add-to-cart/simple.php` - Add to cart button for simple products
- `single-product/add-to-cart/variable.php` - Add to cart form for variable products
- `single-product/price.php` - Product price display on single product pages
- `single-product/related.php` - Related products display
- `single-product/tabs/tabs.php` - Product information tabs

## Customizing WooCommerce Templates

To customize any WooCommerce template:

1. Create a `woocommerce` folder in your child theme
2. Copy the template file from the parent theme's `woocommerce` folder to your child theme's `woocommerce` folder
3. Edit the copied file as needed

For example, to customize the product card:

1. Create folder: `aqualuxe-child/woocommerce/`
2. Copy file: `aqualuxe/woocommerce/content-product.php` to `aqualuxe-child/woocommerce/content-product.php`
3. Edit the file in your child theme

## WooCommerce Hooks

AquaLuxe adds several custom hooks that you can use to add content to specific areas:

### Shop Page Hooks

- `aqualuxe_before_shop_loop_grid` - Before the product grid
- `aqualuxe_after_shop_loop_grid` - After the product grid
- `aqualuxe_before_shop_sidebar` - Before the shop sidebar
- `aqualuxe_after_shop_sidebar` - After the shop sidebar

### Product Card Hooks

- `aqualuxe_before_shop_loop_item_thumbnail` - Before the product thumbnail
- `aqualuxe_after_shop_loop_item_thumbnail` - After the product thumbnail
- `aqualuxe_before_shop_loop_item_title` - Before the product title
- `aqualuxe_after_shop_loop_item_title` - After the product title
- `aqualuxe_product_card_actions` - For product card action buttons
- `aqualuxe_after_shop_loop_item` - After the product card content

### Single Product Hooks

- `aqualuxe_before_single_product` - Before the single product content
- `aqualuxe_after_single_product` - After the single product content
- `aqualuxe_single_product_recently_viewed` - For recently viewed products section
- `aqualuxe_single_product_featured_products` - For featured products section

### Cart and Checkout Hooks

- `aqualuxe_before_cart_collaterals` - Before cart collaterals
- `aqualuxe_after_cart_collaterals` - After cart collaterals
- `aqualuxe_before_checkout_customer_details` - Before checkout customer details
- `aqualuxe_after_checkout_customer_details` - After checkout customer details

### Account Page Hooks

- `aqualuxe_before_account_content` - Before account content
- `aqualuxe_after_account_content` - After account content

## Example: Adding Content to Hooks

Here's an example of how to add content to one of the custom hooks. Add this code to your child theme's `functions.php` file:

```php
/**
 * Add product brand to product card
 */
function aqualuxe_child_add_product_brand() {
    global $product;
    
    // Get product brand (requires a taxonomy or custom field)
    $brands = get_the_terms($product->get_id(), 'product_brand');
    
    if ($brands && !is_wp_error($brands)) {
        echo '<div class="product-brand">';
        echo esc_html($brands[0]->name);
        echo '</div>';
    }
}
add_action('aqualuxe_before_shop_loop_item_title', 'aqualuxe_child_add_product_brand');
```

## WooCommerce Block Support

AquaLuxe supports WooCommerce blocks for the block editor:

1. **Product Grid Block**: Styled to match the theme's design
2. **Featured Product Block**: Enhanced with custom styling
3. **Handpicked Products Block**: Customized layout options
4. **Best Selling Products Block**: Styled carousel option
5. **Product Categories Block**: Multiple display styles
6. **Product Search Block**: Styled to match the theme

## Recommended WooCommerce Extensions

AquaLuxe works well with these WooCommerce extensions:

1. **WooCommerce Product Bundles**: Create product bundles for bathroom sets
2. **WooCommerce Product Add-ons**: Allow customization options for products
3. **WooCommerce Measurement Price Calculator**: For products sold by dimensions
4. **WooCommerce Subscriptions**: For recurring services or product subscriptions
5. **WooCommerce Bookings**: For installation or consultation services
6. **WooCommerce Brands**: Add and showcase product brands

## Performance Optimization

To ensure your WooCommerce store performs well:

1. **Enable AJAX Cart**: Go to Customize > WooCommerce > Cart and enable AJAX cart updates
2. **Optimize Images**: Use WebP format and appropriate sizes for product images
3. **Lazy Loading**: Enable lazy loading for product images
4. **Product Catalog Caching**: Enable product catalog caching in the theme options
5. **Limit Products Per Page**: Set a reasonable number of products per page (12-24)

## Getting Help

If you need assistance with WooCommerce:

- Check our [WooCommerce tutorials](https://aqualuxetheme.com/woocommerce-tutorials)
- Visit the [WooCommerce documentation](https://docs.woocommerce.com/)
- Contact our support team for theme-specific WooCommerce questions