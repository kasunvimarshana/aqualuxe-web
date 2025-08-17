# Quick View Feature Documentation

The AquaLuxe theme includes a Quick View feature that allows customers to preview products without navigating away from the current page. This enhances the shopping experience by providing a faster way to view product details.

## Features

- View product details in a modal popup
- See product images and gallery
- Read product description
- View price and availability
- Select product variations (for variable products)
- Adjust quantity
- Add products to cart directly from the quick view modal
- Responsive design that works on all devices

## How It Works

1. When a customer hovers over a product in the shop or product listing pages, a "Quick View" button appears.
2. Clicking this button opens a modal window with essential product information.
3. The customer can view images, read details, select variations, and add the product to their cart.
4. The modal can be closed by clicking the close button, clicking outside the modal, or pressing the ESC key.

## Implementation Details

### PHP Components

- `woocommerce-ajax.php`: Handles the AJAX request for loading product data in the quick view modal
- `woocommerce-hooks.php`: Adds the quick view button to product listings

### JavaScript Components

- `initializeQuickView()`: Sets up event listeners for quick view buttons
- `initializeQuickViewGallery()`: Handles the product image gallery in the quick view modal
- `initializeQuantityInputs()`: Adds plus/minus buttons to quantity inputs
- `initializeQuickViewAddToCart()`: Handles AJAX add to cart functionality

### CSS Components

- `quick-view.scss`: Styles for the quick view modal and its components
- `notifications.scss`: Styles for success/error notifications when adding products to cart

## Customization

### Modifying the Quick View Button

The quick view button is added via the `aqualuxe_woocommerce_template_loop_quick_view_button()` function in `woocommerce-hooks.php`. You can modify this function to change the button text, styling, or position.

### Changing the Quick View Content

The content of the quick view modal is generated in the `aqualuxe_quick_view_ajax()` function in `woocommerce-ajax.php`. You can modify this function to add, remove, or rearrange elements in the quick view modal.

### Styling the Quick View Modal

The styling for the quick view modal is defined in `assets/src/css/components/quick-view.scss`. You can modify this file to change the appearance of the modal.

## Troubleshooting

### Quick View Button Not Appearing

- Make sure WooCommerce is active
- Check if the theme's JavaScript is properly enqueued
- Verify that the product is properly configured in WooCommerce

### Quick View Modal Not Loading

- Check browser console for JavaScript errors
- Verify that the AJAX URL is correct
- Ensure the security nonce is properly generated and verified

### Products Not Adding to Cart

- Check if the product is in stock
- Verify that all required variation attributes are selected (for variable products)
- Check browser console for AJAX errors

## Compatibility

The quick view feature is compatible with:

- WooCommerce 3.0+
- All modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile devices and responsive layouts
- Variable products, simple products, and grouped products