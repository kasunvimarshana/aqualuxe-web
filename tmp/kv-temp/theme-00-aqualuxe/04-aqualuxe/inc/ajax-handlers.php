<?php
/**
 * AquaLuxe AJAX Handlers
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handle AJAX add to cart
 */
function aqualuxe_ajax_add_to_cart() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get product ID
    $product_id = apply_filters('aqualuxe_add_to_cart_product_id', absint($_POST['product_id']));
    
    // Get quantity
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    
    // Get variation ID
    $variation_id = empty($_POST['variation_id']) ? '' : absint($_POST['variation_id']);
    
    // Get variation data
    $variation = empty($_POST['variation']) ? '' : (array) $_POST['variation'];
    
    // Add to cart
    $passed_validation = apply_filters('aqualuxe_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation)) {
        // Return updated cart data
        $data = array(
            'success' => true,
            'message' => __('Product added to cart successfully!', 'aqualuxe'),
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
        );
        
        // Send JSON response
        wp_send_json($data);
    } else {
        // Return error message
        $data = array(
            'success' => false,
            'message' => __('Failed to add product to cart.', 'aqualuxe'),
        );
        
        // Send JSON response
        wp_send_json($data);
    }
}
add_action('wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart');
add_action('wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_ajax_add_to_cart');

/**
 * Handle quick view
 */
function aqualuxe_quick_view() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get product ID
    $product_id = intval($_POST['product_id']);
    
    // Get product
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_die('Product not found');
    }
    
    // Set up global post data
    global $post, $product;
    $post = get_post($product_id);
    $product = wc_get_product($product_id);
    
    // Start output buffering
    ob_start();
    
    // Load quick view template
    wc_get_template('quick-view.php', array(
        'product' => $product,
    ), '', AQUALUXE_CHILD_THEME_DIR . '/templates/');
    
    // Get output buffer contents
    $content = ob_get_clean();
    
    // Return content
    wp_send_json(array(
        'success' => true,
        'content' => $content,
    ));
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view');

/**
 * Handle search suggestions
 */
function aqualuxe_search_suggestions() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get search term
    $term = sanitize_text_field($_POST['term']);
    
    if (strlen($term) < 3) {
        wp_send_json(array('suggestions' => array()));
    }
    
    // Search products
    $products = wc_get_products(array(
        'limit' => 10,
        'search' => $term,
        'status' => 'publish',
    ));
    
    $suggestions = array();
    
    foreach ($products as $product) {
        $suggestions[] = array(
            'id' => $product->get_id(),
            'title' => $product->get_name(),
            'price' => $product->get_price_html(),
            'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
            'url' => $product->get_permalink(),
        );
    }
    
    // Send JSON response
    wp_send_json(array('suggestions' => $suggestions));
}
add_action('wp_ajax_aqualuxe_search_suggestions', 'aqualuxe_search_suggestions');
add_action('wp_ajax_nopriv_aqualuxe_search_suggestions', 'aqualuxe_search_suggestions');

/**
 * Handle wishlist functionality
 */
function aqualuxe_toggle_wishlist() {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json(array(
            'success' => false,
            'message' => __('Please log in to use the wishlist.', 'aqualuxe'),
        ));
    }
    
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get product ID
    $product_id = intval($_POST['product_id']);
    
    // Get user ID
    $user_id = get_current_user_id();
    
    // Get user wishlist
    $wishlist = get_user_meta($user_id, 'aqualuxe_wishlist', true);
    
    if (!$wishlist) {
        $wishlist = array();
    }
    
    // Check if product is already in wishlist
    if (in_array($product_id, $wishlist)) {
        // Remove from wishlist
        $wishlist = array_diff($wishlist, array($product_id));
        $message = __('Product removed from wishlist.', 'aqualuxe');
        $added = false;
    } else {
        // Add to wishlist
        $wishlist[] = $product_id;
        $message = __('Product added to wishlist.', 'aqualuxe');
        $added = true;
    }
    
    // Update user wishlist
    update_user_meta($user_id, 'aqualuxe_wishlist', $wishlist);
    
    // Send JSON response
    wp_send_json(array(
        'success' => true,
        'message' => $message,
        'added' => $added,
    ));
}
add_action('wp_ajax_aqualuxe_toggle_wishlist', 'aqualuxe_toggle_wishlist');

/**
 * Handle newsletter signup
 */
function aqualuxe_newsletter_signup() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get email
    $email = sanitize_email($_POST['email']);
    
    // Validate email
    if (!is_email($email)) {
        wp_send_json(array(
            'success' => false,
            'message' => __('Please enter a valid email address.', 'aqualuxe'),
        ));
    }
    
    // Add email to newsletter list (this would typically integrate with a service like MailChimp)
    // For now, we'll just store it as an option
    $subscribers = get_option('aqualuxe_newsletter_subscribers', array());
    
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('aqualuxe_newsletter_subscribers', $subscribers);
        
        $message = __('Thank you for subscribing to our newsletter!', 'aqualuxe');
    } else {
        $message = __('You are already subscribed to our newsletter.', 'aqualuxe');
    }
    
    // Send JSON response
    wp_send_json(array(
        'success' => true,
        'message' => $message,
    ));
}
add_action('wp_ajax_aqualuxe_newsletter_signup', 'aqualuxe_newsletter_signup');
add_action('wp_ajax_nopriv_aqualuxe_newsletter_signup', 'aqualuxe_newsletter_signup');

/**
 * Handle contact form submission
 */
function aqualuxe_contact_form_submit() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Validate form data
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        wp_send_json(array(
            'success' => false,
            'message' => __('Please fill in all required fields.', 'aqualuxe'),
        ));
    }
    
    if (!is_email($email)) {
        wp_send_json(array(
            'success' => false,
            'message' => __('Please enter a valid email address.', 'aqualuxe'),
        ));
    }
    
    // Send email
    $to = get_option('admin_email');
    $email_subject = sprintf(__('Contact Form: %s', 'aqualuxe'), $subject);
    $email_message = sprintf(
        __("Name: %s\nEmail: %s\nSubject: %s\nMessage: %s", 'aqualuxe'),
        $name,
        $email,
        $subject,
        $message
    );
    
    $headers = array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    );
    
    $sent = wp_mail($to, $email_subject, $email_message, $headers);
    
    if ($sent) {
        $response = array(
            'success' => true,
            'message' => __('Thank you for your message. We will get back to you soon.', 'aqualuxe'),
        );
    } else {
        $response = array(
            'success' => false,
            'message' => __('There was an error sending your message. Please try again later.', 'aqualuxe'),
        );
    }
    
    // Send JSON response
    wp_send_json($response);
}
add_action('wp_ajax_aqualuxe_contact_form_submit', 'aqualuxe_contact_form_submit');
add_action('wp_ajax_nopriv_aqualuxe_contact_form_submit', 'aqualuxe_contact_form_submit');

/**
 * Handle product filter
 */
function aqualuxe_product_filter() {
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }
    
    // Get filter data
    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 0;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order';
    $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'asc';
    
    // Build query args
    $args = array(
        'limit' => 12,
        'status' => 'publish',
    );
    
    // Add category filter
    if (!empty($category)) {
        $args['category'] = array($category);
    }
    
    // Add price filter
    if ($min_price > 0 || $max_price > 0) {
        $args['meta_query'] = array(
            'relation' => 'AND',
        );
        
        if ($min_price > 0) {
            $args['meta_query'][] = array(
                'key' => '_price',
                'value' => $min_price,
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        
        if ($max_price > 0) {
            $args['meta_query'][] = array(
                'key' => '_price',
                'value' => $max_price,
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
    }
    
    // Add order by
    $args['orderby'] = $orderby;
    $args['order'] = $order;
    
    // Get products
    $products = wc_get_products($args);
    
    // Start output buffering
    ob_start();
    
    // Display products
    if ($products) {
        foreach ($products as $product) {
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p class="no-products-found">' . __('No products found matching your criteria.', 'aqualuxe') . '</p>';
    }
    
    // Get output buffer contents
    $content = ob_get_clean();
    
    // Send JSON response
    wp_send_json(array(
        'success' => true,
        'content' => $content,
    ));
}
add_action('wp_ajax_aqualuxe_product_filter', 'aqualuxe_product_filter');
add_action('wp_ajax_nopriv_aqualuxe_product_filter', 'aqualuxe_product_filter');