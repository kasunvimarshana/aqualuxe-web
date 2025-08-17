# AquaLuxe Subscription Management System

## Overview

The Subscription Management System is a comprehensive solution for managing recurring orders in the AquaLuxe WordPress theme. It allows customers to subscribe to regular deliveries of products such as fish food, water treatments, and maintenance supplies. The system integrates with WooCommerce to handle recurring payments and provides both customer-facing and admin interfaces for managing subscriptions.

## Features

### Customer Features

1. **Subscription Dashboard**
   - View active subscriptions
   - Manage subscription details (frequency, products, quantities)
   - Pause, resume, or cancel subscriptions
   - View subscription history and upcoming deliveries
   - Update payment methods

2. **Subscription Creation**
   - Convert regular orders to subscriptions
   - Choose from predefined subscription plans
   - Select delivery frequency (weekly, bi-weekly, monthly)
   - Customize product quantities

3. **Notifications**
   - Email notifications for subscription events
   - Upcoming delivery reminders
   - Payment processing alerts
   - Subscription status changes

### Admin Features

1. **Subscription Management**
   - View all active subscriptions
   - Filter and search subscriptions by status, customer, or product
   - Edit subscription details (products, frequency, status)
   - Process manual renewals
   - Apply discounts or adjustments

2. **Reporting**
   - Subscription revenue reports
   - Customer retention metrics
   - Product popularity in subscriptions
   - Churn rate analysis

3. **Configuration**
   - Define subscription plans
   - Set up frequency options
   - Configure email notifications
   - Set default subscription settings

## Technical Implementation

### Custom Post Types

1. **Subscription Post Type (`aqualuxe_subscription`)**
   - Stores subscription details
   - Custom meta fields for frequency, status, and renewal date
   - Relationship to WooCommerce orders

2. **Subscription Customer Post Type (`aqualuxe_subscription_customer`)**
   - Stores customer subscription preferences
   - Payment method information
   - Delivery preferences

### Database Tables

1. **Subscription Items Table**
   - Links products to subscriptions
   - Stores quantity and price information
   - Tracks product variations

2. **Subscription Logs Table**
   - Records subscription events
   - Tracks status changes
   - Documents payment processing

### Integration Points

1. **WooCommerce Integration**
   - Hooks into order processing
   - Extends product data with subscription options
   - Leverages WooCommerce payment gateways

2. **User Account Integration**
   - Adds subscription management to My Account area
   - Extends user profiles with subscription preferences

## Usage

### Adding Subscription Options to Products

```php
// Example code for adding subscription options to a product
function aqualuxe_add_subscription_options($product_id) {
    // Check if product is subscription-eligible
    $is_subscription_eligible = get_post_meta($product_id, '_subscription_eligible', true);
    
    if ($is_subscription_eligible) {
        // Add subscription options
        $frequency_options = aqualuxe_get_subscription_frequencies();
        
        // Display options in product page
        woocommerce_form_field('subscription_frequency', array(
            'type' => 'select',
            'class' => array('subscription-frequency'),
            'label' => __('Delivery Frequency', 'aqualuxe'),
            'options' => $frequency_options,
        ));
    }
}
add_action('woocommerce_before_add_to_cart_button', 'aqualuxe_add_subscription_options');
```

### Creating a Subscription

```php
// Example code for creating a subscription
function aqualuxe_create_subscription($order_id) {
    $order = wc_get_order($order_id);
    $customer_id = $order->get_customer_id();
    
    // Check if order contains subscription products
    $has_subscription = false;
    foreach ($order->get_items() as $item) {
        $product_id = $item->get_product_id();
        $is_subscription = get_post_meta($product_id, '_subscription_eligible', true);
        
        if ($is_subscription && isset($_POST['subscription_frequency'])) {
            $has_subscription = true;
            break;
        }
    }
    
    if ($has_subscription) {
        // Create subscription post
        $subscription_id = wp_insert_post(array(
            'post_type' => 'aqualuxe_subscription',
            'post_title' => sprintf(__('Subscription #%s', 'aqualuxe'), $order_id),
            'post_status' => 'publish',
            'meta_input' => array(
                '_customer_id' => $customer_id,
                '_original_order_id' => $order_id,
                '_frequency' => sanitize_text_field($_POST['subscription_frequency']),
                '_status' => 'active',
                '_next_payment_date' => aqualuxe_calculate_next_payment_date($_POST['subscription_frequency']),
            )
        ));
        
        // Add subscription items
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $is_subscription = get_post_meta($product_id, '_subscription_eligible', true);
            
            if ($is_subscription) {
                aqualuxe_add_subscription_item($subscription_id, $product_id, $item->get_quantity());
            }
        }
        
        // Send confirmation email
        aqualuxe_send_subscription_confirmation($subscription_id);
    }
}
add_action('woocommerce_checkout_order_processed', 'aqualuxe_create_subscription');
```

### Processing Subscription Renewals

```php
// Example code for processing subscription renewals
function aqualuxe_process_subscription_renewals() {
    $args = array(
        'post_type' => 'aqualuxe_subscription',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_status',
                'value' => 'active',
            ),
            array(
                'key' => '_next_payment_date',
                'value' => date('Y-m-d'),
                'compare' => '<=',
                'type' => 'DATE',
            ),
        ),
        'posts_per_page' => -1,
    );
    
    $subscriptions = get_posts($args);
    
    foreach ($subscriptions as $subscription) {
        $subscription_id = $subscription->ID;
        $customer_id = get_post_meta($subscription_id, '_customer_id', true);
        $frequency = get_post_meta($subscription_id, '_frequency', true);
        
        // Create renewal order
        $order_id = aqualuxe_create_renewal_order($subscription_id);
        
        if ($order_id) {
            // Process payment
            $payment_result = aqualuxe_process_subscription_payment($order_id);
            
            if ($payment_result) {
                // Update next payment date
                update_post_meta($subscription_id, '_next_payment_date', aqualuxe_calculate_next_payment_date($frequency));
                
                // Send renewal confirmation
                aqualuxe_send_renewal_confirmation($subscription_id, $order_id);
            } else {
                // Handle failed payment
                aqualuxe_handle_failed_payment($subscription_id);
            }
        }
    }
}
add_action('aqualuxe_daily_subscription_check', 'aqualuxe_process_subscription_renewals');
```

## Shortcodes

The subscription system provides several shortcodes for displaying subscription-related information:

1. **Subscription Dashboard**
   ```
   [aqualuxe_subscription_dashboard]
   ```
   Displays the customer's subscription dashboard with all active subscriptions.

2. **Subscription Form**
   ```
   [aqualuxe_subscription_form product_id="123"]
   ```
   Displays a form for subscribing to a specific product.

3. **Subscription Plans**
   ```
   [aqualuxe_subscription_plans]
   ```
   Displays available subscription plans with pricing and features.

## Templates

The subscription system includes the following templates:

1. **Single Subscription Template**
   - `single-aqualuxe_subscription.php`
   - Displays detailed information about a specific subscription

2. **Subscription Archive Template**
   - `archive-aqualuxe_subscription.php`
   - Displays a list of all subscriptions for the current user

3. **My Account Subscription Tab Template**
   - `woocommerce/myaccount/subscriptions.php`
   - Integrates subscription management into the WooCommerce My Account area

## CSS Classes

The subscription system uses the following CSS classes for styling:

1. **Subscription Dashboard**
   - `.aqualuxe-subscription-dashboard`
   - `.subscription-card`
   - `.subscription-status`
   - `.subscription-actions`

2. **Subscription Forms**
   - `.subscription-form`
   - `.frequency-selector`
   - `.subscription-product`
   - `.subscription-summary`

3. **Status Indicators**
   - `.status-active`
   - `.status-paused`
   - `.status-cancelled`
   - `.status-expired`

## JavaScript Functions

The subscription system includes JavaScript functionality for:

1. **Dynamic Price Calculation**
   - Updates price based on frequency selection
   - Applies subscription discounts

2. **Form Validation**
   - Validates subscription form inputs
   - Handles error messages

3. **Subscription Management**
   - Handles pause/resume/cancel actions
   - Updates subscription details via AJAX

## Hooks and Filters

The subscription system provides the following hooks and filters:

1. **Actions**
   - `aqualuxe_subscription_created`
   - `aqualuxe_subscription_renewed`
   - `aqualuxe_subscription_cancelled`
   - `aqualuxe_subscription_paused`
   - `aqualuxe_subscription_resumed`

2. **Filters**
   - `aqualuxe_subscription_frequency_options`
   - `aqualuxe_subscription_price`
   - `aqualuxe_subscription_renewal_date`
   - `aqualuxe_subscription_email_content`

## Troubleshooting

Common issues and their solutions:

1. **Subscription Not Renewing**
   - Check that the WP-Cron is functioning properly
   - Verify payment method is valid
   - Check for subscription status changes

2. **Payment Processing Errors**
   - Verify WooCommerce payment gateway settings
   - Check customer payment method details
   - Review error logs for specific payment failures

3. **Email Notifications Not Sending**
   - Check WordPress email settings
   - Verify email templates exist
   - Test email functionality with a plugin like Check Email

## Conclusion

The AquaLuxe Subscription Management System provides a comprehensive solution for managing recurring orders. It integrates seamlessly with WooCommerce and provides both customers and administrators with powerful tools for managing subscriptions. The system is highly customizable and can be extended to meet specific business needs.