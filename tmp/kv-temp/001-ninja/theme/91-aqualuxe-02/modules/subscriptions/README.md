# Subscriptions Module

This module provides integration and custom features for the [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin.

## Features

*   Provides a basic structure for adding custom styles and templates for subscription products.
*   Includes a shortcode `[aqualuxe_subscription_pricing]` to display a subscription pricing table.

## Setup

1.  **Install and activate the WooCommerce Subscriptions plugin.**
2.  **Create Subscription Products:** Create your subscription products in WooCommerce.
3.  **Create a Pricing Page:** Create a new page and add the `[aqualuxe_subscription_pricing]` shortcode to display the pricing table.

## Template Overrides

To customize the appearance of subscription-related pages, you can override the default WooCommerce Subscriptions templates. Copy the template files from the plugin's `templates` directory to the `aqualuxe/woocommerce/` directory in your theme and then customize them.

Common templates to override include:
*   `myaccount/my-subscriptions.php`
*   `myaccount/view-subscription.php`
