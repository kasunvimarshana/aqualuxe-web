# AquaLuxe WooCommerce Integration

This document outlines the WooCommerce integration for the AquaLuxe WordPress theme, designed specifically for ornamental fish farming businesses.

## Overview

AquaLuxe provides comprehensive WooCommerce integration with custom styling and enhanced functionality to create a seamless shopping experience for customers. The integration includes custom templates, styling, and JavaScript functionality.

## Features

### Custom Templates
- **Shop Page**: Enhanced shop page with filtering options and product grid
- **Single Product**: Redesigned product page with custom meta fields for fish specifications
- **Cart**: Styled cart page with AJAX functionality
- **Checkout**: Optimized checkout process with custom fields
- **My Account**: Enhanced account dashboard and pages

### Custom Styling
- Consistent styling with the overall theme design
- Responsive design for all device sizes
- Custom product cards with hover effects
- Enhanced product gallery
- Custom form styling for a better user experience

### Enhanced Functionality
- AJAX add to cart
- Quick view functionality
- Wishlist functionality
- Compare functionality
- Custom product filters
- Enhanced product gallery with zoom and lightbox
- Custom product tabs
- Mini cart with dropdown

### Custom Fields for Fish Products
- Size
- Diet
- Temperament
- Water parameters
- Care instructions
- Delivery time

## File Structure

```
aqualuxe/
├── woocommerce/
│   ├── archive-product.php
│   ├── content-product.php
│   ├── content-single-product.php
│   ├── single-product.php
│   ├── content-quick-view.php
│   ├── cart/
│   │   └── cart.php
│   ├── checkout/
│   │   └── form-checkout.php
│   ├── myaccount/
│   │   └── dashboard.php
│   ├── single-product/
│   ├── loop/
│   └── global/
├── src/
│   ├── scss/
│   │   └── components/
│   │       └── woocommerce/
│   │           ├── _products.scss
│   │           ├── _single-product.scss
│   │           ├── _cart.scss
│   │           ├── _checkout.scss
│   │           ├── _account.scss
│   │           └── _widgets.scss
│   └── js/
│       └── woocommerce.js
└── inc/
    └── woocommerce.php
```

## Template Customization

### Shop Page
The shop page template (`archive-product.php`) has been customized to include:
- Shop filters sidebar
- Enhanced product grid
- Custom product cards
- AJAX filtering

### Single Product
The single product template (`content-single-product.php`) has been customized to include:
- Enhanced product gallery
- Custom meta fields for fish specifications
- Custom tabs for care instructions and water parameters
- Related products section
- Wishlist and compare buttons

### Cart
The cart template (`cart/cart.php`) has been customized to include:
- Enhanced styling
- Quantity increment/decrement buttons
- Cross-sells section

### Checkout
The checkout template (`checkout/form-checkout.php`) has been customized to include:
- Two-column layout
- Enhanced form styling
- Custom fields for delivery date

### My Account
The my account dashboard (`myaccount/dashboard.php`) has been customized to include:
- Dashboard cards for quick navigation
- Recent orders section
- Account information

## JavaScript Functionality

The `woocommerce.js` file includes the following functionality:
- AJAX add to cart
- Quick view modal
- Wishlist functionality
- Compare functionality
- Mini cart dropdown
- Product gallery with zoom and lightbox
- Product tabs
- Quantity increment/decrement buttons
- Shop filters

## SCSS Styling

The WooCommerce styling is organized into separate SCSS files:
- `_products.scss`: Styling for product grid and cards
- `_single-product.scss`: Styling for single product page
- `_cart.scss`: Styling for cart page
- `_checkout.scss`: Styling for checkout page
- `_account.scss`: Styling for my account pages
- `_widgets.scss`: Styling for WooCommerce widgets

## Custom Fields

Custom fields have been added to products to store fish-specific information:
- Size: The size of the fish
- Diet: The diet of the fish
- Temperament: The temperament of the fish
- Water Parameters: Recommended water parameters for the fish
- Care Instructions: Care instructions for the fish
- Delivery Time: Estimated delivery time for the fish

## Hooks and Filters

The `woocommerce.php` file in the `inc` directory includes all the necessary hooks and filters to customize WooCommerce functionality:
- Custom wrapper for WooCommerce pages
- Custom product badges
- Custom fields for products
- Custom tabs for single product
- Custom checkout fields
- Custom order meta
- Custom product sorting and filtering

## Customizer Options

The theme includes customizer options for WooCommerce:
- Shop columns
- Products per page
- Shop sidebar
- Shop filters
- New badge days
- Featured badge

## Responsive Design

All WooCommerce templates and styles are fully responsive and optimized for:
- Desktop
- Tablet
- Mobile

## Browser Compatibility

The WooCommerce integration has been tested and is compatible with:
- Chrome
- Firefox
- Safari
- Edge

## Future Enhancements

Planned future enhancements for the WooCommerce integration:
- Product bundles for complete aquarium setups
- Subscription options for fish food and supplies
- Advanced filtering by fish compatibility
- Water parameter calculator
- Care guide PDF downloads
- Video tutorials integration