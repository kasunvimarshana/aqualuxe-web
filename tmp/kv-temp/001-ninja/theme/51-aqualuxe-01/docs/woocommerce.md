# AquaLuxe Theme WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and provides extensive customization options for your online store. This document explains how to set up and customize the WooCommerce integration.

## Table of Contents

1. [Installation](#installation)
2. [Theme Features](#theme-features)
3. [Customization Options](#customization-options)
4. [Templates](#templates)
5. [Hooks and Filters](#hooks-and-filters)
6. [Custom Features](#custom-features)
7. [Multivendor Support](#multivendor-support)
8. [Multi-currency Support](#multi-currency-support)
9. [Troubleshooting](#troubleshooting)

## Installation

1. Install and activate WooCommerce
2. Follow the WooCommerce setup wizard
3. Go to **WooCommerce > Settings** to configure your store

AquaLuxe will automatically detect WooCommerce and enable all the necessary features.

## Theme Features

AquaLuxe enhances WooCommerce with the following features:

### Shop Page

- **Grid/List View**: Toggle between grid and list views
- **Advanced Filters**: Filter products by various attributes
- **AJAX Filtering**: Filter products without page reload
- **Infinite Scroll**: Load more products as you scroll
- **Quick View**: View product details without leaving the page
- **Wishlist**: Add products to your wishlist

### Product Page

- **Image Zoom**: Zoom in on product images
- **Image Gallery**: Browse product images in a gallery
- **Image Lightbox**: View product images in a lightbox
- **Custom Tabs**: Care Guide and Shipping tabs for aquatic products
- **Related Products**: Show related products
- **Upsells**: Show upsell products
- **Cross-sells**: Show cross-sell products
- **AJAX Add to Cart**: Add products to cart without page reload
- **Sticky Add to Cart**: Always visible add to cart button
- **Quantity Buttons**: Easily adjust product quantity
- **Variation Swatches**: Visual representation of product variations

### Cart Page

- **AJAX Cart Updates**: Update cart without page reload
- **Mini Cart**: Show cart contents in a dropdown
- **Cart Totals**: Show cart totals
- **Shipping Calculator**: Calculate shipping costs
- **Coupon Code**: Apply coupon codes

### Checkout Page

- **One-page Checkout**: Complete checkout on a single page
- **Multi-step Checkout**: Break checkout into multiple steps
- **Order Review**: Review order before payment
- **Payment Methods**: Support for various payment methods
- **Shipping Methods**: Support for various shipping methods

### Account Page

- **Dashboard**: Customer dashboard
- **Orders**: View and manage orders
- **Downloads**: Access downloadable products
- **Addresses**: Manage shipping and billing addresses
- **Account Details**: Update account details
- **Wishlist**: View and manage wishlist

## Customization Options

AquaLuxe provides extensive customization options for WooCommerce in the WordPress Customizer:

1. Go to **Appearance > Customize**
2. Navigate to **WooCommerce**

### Shop Options

- **Shop Layout**: Choose from different shop layouts
- **Products Per Page**: Set the number of products per page
- **Product Columns**: Set the number of product columns
- **Product Card Style**: Choose from different product card styles
- **Sale Badge**: Customize the sale badge
- **Out of Stock Badge**: Customize the out of stock badge
- **Quick View**: Enable/disable quick view
- **Wishlist**: Enable/disable wishlist
- **AJAX Add to Cart**: Enable/disable AJAX add to cart
- **Product Hover Effect**: Choose from different product hover effects

### Product Options

- **Product Layout**: Choose from different product layouts
- **Image Zoom**: Enable/disable image zoom
- **Image Lightbox**: Enable/disable image lightbox
- **Related Products**: Show/hide related products
- **Upsells**: Show/hide upsell products
- **Cross-sells**: Show/hide cross-sell products
- **Custom Tabs**: Enable/disable custom tabs
- **Sticky Add to Cart**: Enable/disable sticky add to cart
- **Quantity Buttons**: Enable/disable quantity buttons
- **Variation Swatches**: Enable/disable variation swatches

### Cart Options

- **Cart Layout**: Choose from different cart layouts
- **Mini Cart**: Enable/disable mini cart
- **AJAX Cart Updates**: Enable/disable AJAX cart updates
- **Shipping Calculator**: Enable/disable shipping calculator
- **Cross-sells**: Show/hide cross-sells on cart page

### Checkout Options

- **Checkout Layout**: Choose from different checkout layouts
- **One-page Checkout**: Enable/disable one-page checkout
- **Multi-step Checkout**: Enable/disable multi-step checkout
- **Order Notes**: Enable/disable order notes
- **Terms and Conditions**: Enable/disable terms and conditions

### Account Options

- **Account Layout**: Choose from different account layouts
- **Dashboard**: Customize the dashboard
- **Orders**: Customize the orders page
- **Downloads**: Customize the downloads page
- **Addresses**: Customize the addresses page
- **Account Details**: Customize the account details page
- **Wishlist**: Customize the wishlist page

## Templates

AquaLuxe overrides the following WooCommerce templates:

### Archive Templates

- `archive-product.php`: Main shop page template
- `content-product.php`: Product card template
- `loop/loop-start.php`: Shop loop start template
- `loop/loop-end.php`: Shop loop end template
- `loop/orderby.php`: Orderby dropdown template
- `loop/result-count.php`: Result count template
- `loop/sale-flash.php`: Sale flash template
- `loop/price.php`: Price template
- `loop/rating.php`: Rating template
- `loop/title.php`: Title template
- `loop/add-to-cart.php`: Add to cart button template

### Single Product Templates

- `single-product.php`: Single product template
- `content-single-product.php`: Single product content template
- `single-product/add-to-cart/simple.php`: Simple product add to cart template
- `single-product/add-to-cart/variable.php`: Variable product add to cart template
- `single-product/add-to-cart/grouped.php`: Grouped product add to cart template
- `single-product/add-to-cart/external.php`: External product add to cart template
- `single-product/price.php`: Price template
- `single-product/rating.php`: Rating template
- `single-product/title.php`: Title template
- `single-product/short-description.php`: Short description template
- `single-product/meta.php`: Meta template
- `single-product/tabs/tabs.php`: Tabs template
- `single-product/tabs/description.php`: Description tab template
- `single-product/tabs/additional-information.php`: Additional information tab template
- `single-product/tabs/reviews.php`: Reviews tab template
- `single-product/tabs/care-guide.php`: Care guide tab template
- `single-product/tabs/shipping.php`: Shipping tab template
- `single-product/related.php`: Related products template
- `single-product/up-sells.php`: Upsells template

### Cart Templates

- `cart/cart.php`: Cart template
- `cart/cart-empty.php`: Empty cart template
- `cart/cart-item-data.php`: Cart item data template
- `cart/cart-shipping.php`: Cart shipping template
- `cart/cart-totals.php`: Cart totals template
- `cart/cross-sells.php`: Cross-sells template
- `cart/mini-cart.php`: Mini cart template
- `cart/proceed-to-checkout-button.php`: Proceed to checkout button template

### Checkout Templates

- `checkout/form-checkout.php`: Checkout form template
- `checkout/form-billing.php`: Billing form template
- `checkout/form-shipping.php`: Shipping form template
- `checkout/form-coupon.php`: Coupon form template
- `checkout/form-login.php`: Login form template
- `checkout/review-order.php`: Review order template
- `checkout/payment.php`: Payment template
- `checkout/thankyou.php`: Thank you template

### Account Templates

- `myaccount/dashboard.php`: Dashboard template
- `myaccount/orders.php`: Orders template
- `myaccount/view-order.php`: View order template
- `myaccount/downloads.php`: Downloads template
- `myaccount/my-address.php`: My address template
- `myaccount/form-edit-account.php`: Edit account form template
- `myaccount/form-login.php`: Login form template
- `myaccount/form-lost-password.php`: Lost password form template
- `myaccount/form-reset-password.php`: Reset password form template
- `myaccount/navigation.php`: Navigation template

## Hooks and Filters

AquaLuxe provides numerous hooks and filters for customizing WooCommerce. Here are the main ones:

### Action Hooks

#### Shop Hooks

- `aqualuxe_before_shop`: Fires before the shop content
- `aqualuxe_shop_start`: Fires at the start of the shop content
- `aqualuxe_shop_end`: Fires at the end of the shop content
- `aqualuxe_after_shop`: Fires after the shop content

#### Product Hooks

- `aqualuxe_before_product`: Fires before each product
- `aqualuxe_product_start`: Fires at the start of each product
- `aqualuxe_product_end`: Fires at the end of each product
- `aqualuxe_after_product`: Fires after each product

#### Single Product Hooks

- `aqualuxe_before_single_product`: Fires before the single product
- `aqualuxe_single_product_start`: Fires at the start of the single product
- `aqualuxe_single_product_summary_start`: Fires at the start of the single product summary
- `aqualuxe_single_product_summary_end`: Fires at the end of the single product summary
- `aqualuxe_single_product_end`: Fires at the end of the single product
- `aqualuxe_after_single_product`: Fires after the single product

#### Cart Hooks

- `aqualuxe_before_cart`: Fires before the cart
- `aqualuxe_cart_start`: Fires at the start of the cart
- `aqualuxe_cart_end`: Fires at the end of the cart
- `aqualuxe_after_cart`: Fires after the cart

#### Checkout Hooks

- `aqualuxe_before_checkout`: Fires before the checkout
- `aqualuxe_checkout_start`: Fires at the start of the checkout
- `aqualuxe_checkout_end`: Fires at the end of the checkout
- `aqualuxe_after_checkout`: Fires after the checkout

#### Account Hooks

- `aqualuxe_before_account`: Fires before the account
- `aqualuxe_account_start`: Fires at the start of the account
- `aqualuxe_account_end`: Fires at the end of the account
- `aqualuxe_after_account`: Fires after the account

### Filter Hooks

- `aqualuxe_shop_layout`: Filter the shop layout
- `aqualuxe_product_layout`: Filter the product layout
- `aqualuxe_cart_layout`: Filter the cart layout
- `aqualuxe_checkout_layout`: Filter the checkout layout
- `aqualuxe_account_layout`: Filter the account layout
- `aqualuxe_product_tabs`: Filter the product tabs
- `aqualuxe_related_products_args`: Filter the related products arguments
- `aqualuxe_upsell_products_args`: Filter the upsell products arguments
- `aqualuxe_cross_sell_products_args`: Filter the cross-sell products arguments
- `aqualuxe_product_gallery_args`: Filter the product gallery arguments
- `aqualuxe_add_to_cart_args`: Filter the add to cart arguments
- `aqualuxe_cart_item_thumbnail`: Filter the cart item thumbnail
- `aqualuxe_cart_item_name`: Filter the cart item name
- `aqualuxe_cart_item_price`: Filter the cart item price
- `aqualuxe_cart_item_quantity`: Filter the cart item quantity
- `aqualuxe_cart_item_subtotal`: Filter the cart item subtotal

## Custom Features

AquaLuxe adds several custom WooCommerce features:

### Quick View

The quick view feature allows customers to view product details without leaving the page. It's implemented in the `inc/woocommerce/quick-view.php` file.

#### Adding Quick View Button

The quick view button is added to products using the `aqualuxe_after_shop_loop_item` hook:

```php
function aqualuxe_add_quick_view_button() {
    global $product;
    
    echo '<button class="quick-view-button" data-product-id="' . esc_attr($product->get_id()) . '">' . esc_html__('Quick View', 'aqualuxe') . '</button>';
}
add_action('aqualuxe_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 10);
```

#### Quick View AJAX Handler

The quick view AJAX handler is registered in the `inc/woocommerce/quick-view.php` file:

```php
function aqualuxe_quick_view_ajax_handler() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-nonce')) {
        wp_send_json_error(array('message' => __('Invalid request', 'aqualuxe')));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }
    
    ob_start();
    include(get_template_directory() . '/woocommerce/quick-view.php');
    $html = ob_get_clean();
    
    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');
```

### Wishlist

The wishlist feature allows customers to add products to their wishlist. It's implemented in the `inc/woocommerce/wishlist.php` file.

#### Adding Wishlist Button

The wishlist button is added to products using the `aqualuxe_after_shop_loop_item` hook:

```php
function aqualuxe_add_wishlist_button() {
    global $product;
    
    $product_id = $product->get_id();
    $in_wishlist = aqualuxe_is_product_in_wishlist($product_id);
    $class = $in_wishlist ? 'add-to-wishlist in-wishlist' : 'add-to-wishlist';
    
    echo '<button class="' . esc_attr($class) . '" data-product-id="' . esc_attr($product_id) . '">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</button>';
}
add_action('aqualuxe_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 20);
```

#### Wishlist AJAX Handler

The wishlist AJAX handler is registered in the `inc/woocommerce/wishlist.php` file:

```php
function aqualuxe_add_to_wishlist_ajax_handler() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-nonce')) {
        wp_send_json_error(array('message' => __('Invalid request', 'aqualuxe')));
    }
    
    $product_id = absint($_POST['product_id']);
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error(array('message' => __('Product not found', 'aqualuxe')));
    }
    
    $user_id = get_current_user_id();
    
    if ($user_id > 0) {
        // User is logged in, store wishlist in user meta
        $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
        
        if (!$wishlist) {
            $wishlist = array();
        }
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
        }
    } else {
        // User is not logged in, store wishlist in cookie
        $wishlist = isset($_COOKIE['aqualuxe_wishlist']) ? json_decode(stripslashes($_COOKIE['aqualuxe_wishlist']), true) : array();
        
        if (!in_array($product_id, $wishlist)) {
            $wishlist[] = $product_id;
            setcookie('aqualuxe_wishlist', json_encode($wishlist), time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
        }
    }
    
    wp_send_json_success(array(
        'message' => __('Product added to wishlist', 'aqualuxe'),
        'count' => count($wishlist)
    ));
}
add_action('wp_ajax_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax_handler');
add_action('wp_ajax_nopriv_aqualuxe_add_to_wishlist', 'aqualuxe_add_to_wishlist_ajax_handler');
```

### AJAX Add to Cart

The AJAX add to cart feature allows customers to add products to cart without page reload. It's implemented in the `inc/woocommerce/woocommerce.php` file.

#### Adding AJAX Add to Cart Button

The AJAX add to cart button is added to products using the `woocommerce_loop_add_to_cart_link` filter:

```php
function aqualuxe_ajax_add_to_cart_button($html, $product) {
    if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
        $html = sprintf(
            '<button type="button" class="button ajax_add_to_cart" data-product_id="%s" data-quantity="1">%s</button>',
            esc_attr($product->get_id()),
            esc_html__('Add to cart', 'aqualuxe')
        );
    }
    
    return $html;
}
add_filter('woocommerce_loop_add_to_cart_link', 'aqualuxe_ajax_add_to_cart_button', 10, 2);
```

### Custom Tabs

AquaLuxe adds custom tabs to product pages. The custom tabs are implemented in the `inc/woocommerce/woocommerce.php` file.

#### Adding Custom Tabs

The custom tabs are added to product pages using the `woocommerce_product_tabs` filter:

```php
function aqualuxe_custom_product_tabs($tabs) {
    global $product;
    
    // Add Care Guide tab
    if (get_post_meta($product->get_id(), '_care_guide', true)) {
        $tabs['care_guide'] = array(
            'title'    => __('Care Guide', 'aqualuxe'),
            'priority' => 30,
            'callback' => 'aqualuxe_care_guide_tab_content'
        );
    }
    
    // Add Shipping tab
    if (get_post_meta($product->get_id(), '_shipping_info', true)) {
        $tabs['shipping'] = array(
            'title'    => __('Shipping', 'aqualuxe'),
            'priority' => 40,
            'callback' => 'aqualuxe_shipping_tab_content'
        );
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_custom_product_tabs');

function aqualuxe_care_guide_tab_content() {
    global $product;
    
    echo '<div class="care-guide-content">';
    echo wpautop(get_post_meta($product->get_id(), '_care_guide', true));
    echo '</div>';
}

function aqualuxe_shipping_tab_content() {
    global $product;
    
    echo '<div class="shipping-content">';
    echo wpautop(get_post_meta($product->get_id(), '_shipping_info', true));
    echo '</div>';
}
```

### Advanced Filters

AquaLuxe adds advanced filtering options for products. The advanced filters are implemented in the `inc/woocommerce/advanced-filters.php` file.

#### Adding Advanced Filters

The advanced filters are added to the shop page using the `aqualuxe_before_shop_loop` hook:

```php
function aqualuxe_add_advanced_filters() {
    include(get_template_directory() . '/woocommerce/advanced-filters.php');
}
add_action('aqualuxe_before_shop_loop', 'aqualuxe_add_advanced_filters');
```

#### Advanced Filters AJAX Handler

The advanced filters AJAX handler is registered in the `inc/woocommerce/advanced-filters.php` file:

```php
function aqualuxe_advanced_filters_ajax_handler() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-nonce')) {
        wp_send_json_error(array('message' => __('Invalid request', 'aqualuxe')));
    }
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => get_option('posts_per_page'),
        'paged' => isset($_POST['page']) ? absint($_POST['page']) : 1,
        'tax_query' => array(),
        'meta_query' => array()
    );
    
    // Add category filter
    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['category'])
        );
    }
    
    // Add tag filter
    if (isset($_POST['tag']) && !empty($_POST['tag'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => sanitize_text_field($_POST['tag'])
        );
    }
    
    // Add attribute filters
    if (isset($_POST['attributes']) && is_array($_POST['attributes'])) {
        foreach ($_POST['attributes'] as $taxonomy => $terms) {
            if (!empty($terms)) {
                $args['tax_query'][] = array(
                    'taxonomy' => sanitize_key($taxonomy),
                    'field' => 'slug',
                    'terms' => array_map('sanitize_text_field', $terms)
                );
            }
        }
    }
    
    // Add price filter
    if (isset($_POST['min_price']) && isset($_POST['max_price'])) {
        $args['meta_query'][] = array(
            'key' => '_price',
            'value' => array(floatval($_POST['min_price']), floatval($_POST['max_price'])),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN'
        );
    }
    
    // Add rating filter
    if (isset($_POST['rating']) && !empty($_POST['rating'])) {
        $args['meta_query'][] = array(
            'key' => '_wc_average_rating',
            'value' => absint($_POST['rating']),
            'type' => 'NUMERIC',
            'compare' => '>='
        );
    }
    
    // Add orderby
    if (isset($_POST['orderby']) && !empty($_POST['orderby'])) {
        switch ($_POST['orderby']) {
            case 'price':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'ASC';
                break;
            case 'price-desc':
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'rating':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'popularity':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'DESC';
                break;
            case 'date':
                $args['orderby'] = 'date';
                $args['order'] = 'DESC';
                break;
            default:
                $args['orderby'] = 'menu_order title';
                $args['order'] = 'ASC';
                break;
        }
    }
    
    $products_query = new WP_Query($args);
    
    ob_start();
    
    if ($products_query->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products_query->have_posts()) {
            $products_query->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        woocommerce_pagination();
    } else {
        wc_get_template('loop/no-products-found.php');
    }
    
    $products_html = ob_get_clean();
    
    wp_reset_postdata();
    
    // Get pagination
    ob_start();
    woocommerce_pagination();
    $pagination = ob_get_clean();
    
    // Get result count
    ob_start();
    woocommerce_result_count();
    $result_count = ob_get_clean();
    
    // Build URL for browser history
    $url = home_url('?post_type=product');
    
    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $url = add_query_arg('product_cat', sanitize_text_field($_POST['category']), $url);
    }
    
    if (isset($_POST['tag']) && !empty($_POST['tag'])) {
        $url = add_query_arg('product_tag', sanitize_text_field($_POST['tag']), $url);
    }
    
    if (isset($_POST['min_price']) && isset($_POST['max_price'])) {
        $url = add_query_arg('min_price', floatval($_POST['min_price']), $url);
        $url = add_query_arg('max_price', floatval($_POST['max_price']), $url);
    }
    
    if (isset($_POST['rating']) && !empty($_POST['rating'])) {
        $url = add_query_arg('rating_filter', absint($_POST['rating']), $url);
    }
    
    if (isset($_POST['orderby']) && !empty($_POST['orderby'])) {
        $url = add_query_arg('orderby', sanitize_text_field($_POST['orderby']), $url);
    }
    
    if (isset($_POST['attributes']) && is_array($_POST['attributes'])) {
        foreach ($_POST['attributes'] as $taxonomy => $terms) {
            if (!empty($terms)) {
                $url = add_query_arg($taxonomy, implode(',', array_map('sanitize_text_field', $terms)), $url);
            }
        }
    }
    
    wp_send_json_success(array(
        'products' => $products_html,
        'pagination' => $pagination,
        'result_count' => $result_count,
        'url' => $url
    ));
}
add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_advanced_filters_ajax_handler');
add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_advanced_filters_ajax_handler');
```

## Multivendor Support

AquaLuxe is compatible with popular multivendor plugins like Dokan and WC Vendors. The multivendor support is implemented in the `inc/multivendor.php` file.

### Dokan Integration

AquaLuxe enhances Dokan with a custom design and additional features:

```php
function aqualuxe_dokan_setup() {
    // Add theme support for Dokan
    add_theme_support('dokan');
    
    // Add custom styles for Dokan
    wp_enqueue_style('aqualuxe-dokan', get_template_directory_uri() . '/assets/css/dokan.css', array(), AQUALUXE_VERSION);
    
    // Add custom scripts for Dokan
    wp_enqueue_script('aqualuxe-dokan', get_template_directory_uri() . '/assets/js/dokan.js', array('jquery'), AQUALUXE_VERSION, true);
}
add_action('after_setup_theme', 'aqualuxe_dokan_setup');
```

### WC Vendors Integration

AquaLuxe enhances WC Vendors with a custom design and additional features:

```php
function aqualuxe_wc_vendors_setup() {
    // Add theme support for WC Vendors
    add_theme_support('wc-vendors');
    
    // Add custom styles for WC Vendors
    wp_enqueue_style('aqualuxe-wc-vendors', get_template_directory_uri() . '/assets/css/wc-vendors.css', array(), AQUALUXE_VERSION);
    
    // Add custom scripts for WC Vendors
    wp_enqueue_script('aqualuxe-wc-vendors', get_template_directory_uri() . '/assets/js/wc-vendors.js', array('jquery'), AQUALUXE_VERSION, true);
}
add_action('after_setup_theme', 'aqualuxe_wc_vendors_setup');
```

## Multi-currency Support

AquaLuxe supports multiple currencies for international sales. The multi-currency support is implemented in the `inc/multi-currency.php` file.

### Currency Switcher

AquaLuxe adds a currency switcher to the header:

```php
function aqualuxe_currency_switcher() {
    $currencies = aqualuxe_get_currencies();
    $current_currency = aqualuxe_get_current_currency();
    
    if (count($currencies) <= 1) {
        return;
    }
    
    echo '<div class="currency-switcher">';
    echo '<button class="currency-switcher-button">' . esc_html($current_currency) . '</button>';
    echo '<div class="currency-switcher-dropdown hidden">';
    
    foreach ($currencies as $code => $name) {
        $class = $code === $current_currency ? 'currency-switcher-item active' : 'currency-switcher-item';
        echo '<button class="' . esc_attr($class) . '" data-currency="' . esc_attr($code) . '">' . esc_html($name) . '</button>';
    }
    
    echo '</div>';
    echo '</div>';
}
add_action('aqualuxe_header_content', 'aqualuxe_currency_switcher', 30);
```

### Price Formatting

AquaLuxe formats prices according to the selected currency:

```php
function aqualuxe_formatted_price($price, $args = array()) {
    $currency = aqualuxe_get_current_currency();
    $currency_symbol = aqualuxe_get_currency_symbol($currency);
    $currency_position = aqualuxe_get_currency_position($currency);
    $decimal_separator = aqualuxe_get_decimal_separator($currency);
    $thousand_separator = aqualuxe_get_thousand_separator($currency);
    $decimals = aqualuxe_get_decimals($currency);
    
    $args = wp_parse_args($args, array(
        'currency' => $currency,
        'currency_symbol' => $currency_symbol,
        'currency_position' => $currency_position,
        'decimal_separator' => $decimal_separator,
        'thousand_separator' => $thousand_separator,
        'decimals' => $decimals,
    ));
    
    $formatted_price = number_format($price, $args['decimals'], $args['decimal_separator'], $args['thousand_separator']);
    
    switch ($args['currency_position']) {
        case 'left':
            return $args['currency_symbol'] . $formatted_price;
        case 'right':
            return $formatted_price . $args['currency_symbol'];
        case 'left_space':
            return $args['currency_symbol'] . ' ' . $formatted_price;
        case 'right_space':
            return $formatted_price . ' ' . $args['currency_symbol'];
        default:
            return $args['currency_symbol'] . $formatted_price;
    }
}
```

## Troubleshooting

### Common Issues

#### WooCommerce Templates Not Loading

If WooCommerce templates are not loading, make sure:

1. WooCommerce is installed and activated
2. The theme is properly registered as a WooCommerce theme:

```php
add_theme_support('woocommerce');
```

3. The template files are in the correct location:

```
aqualuxe-theme/woocommerce/
```

#### AJAX Add to Cart Not Working

If AJAX add to cart is not working, make sure:

1. The AJAX add to cart feature is enabled in the Customizer
2. The JavaScript is properly enqueued
3. The AJAX URL and nonce are correctly localized:

```php
wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWooCommerce', array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('aqualuxe-nonce'),
));
```

#### Quick View Not Working

If quick view is not working, make sure:

1. The quick view feature is enabled in the Customizer
2. The JavaScript is properly enqueued
3. The quick view template exists:

```
aqualuxe-theme/woocommerce/quick-view.php
```

#### Wishlist Not Working

If wishlist is not working, make sure:

1. The wishlist feature is enabled in the Customizer
2. The JavaScript is properly enqueued
3. The wishlist template exists:

```
aqualuxe-theme/woocommerce/wishlist.php
```

### Getting Help

If you encounter any issues with the WooCommerce integration, please contact our support team at support@example.com or visit our [support forum](https://example.com/support).