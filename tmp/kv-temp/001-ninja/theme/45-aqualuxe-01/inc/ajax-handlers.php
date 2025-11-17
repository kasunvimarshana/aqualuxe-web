<?php
/**
 * AJAX handlers for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register AJAX actions
 */
function aqualuxe_register_ajax_actions() {
    // Quick view AJAX handler
    add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax_handler');

    // Load more posts AJAX handler
    add_action('wp_ajax_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts_ajax_handler');

    // Newsletter subscription AJAX handler
    add_action('wp_ajax_aqualuxe_newsletter_subscribe', 'aqualuxe_newsletter_subscribe_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_newsletter_subscribe', 'aqualuxe_newsletter_subscribe_ajax_handler');

    // Contact form AJAX handler
    add_action('wp_ajax_aqualuxe_contact_form', 'aqualuxe_contact_form_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_contact_form', 'aqualuxe_contact_form_ajax_handler');

    // Filter products AJAX handler
    add_action('wp_ajax_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_filter_products', 'aqualuxe_filter_products_ajax_handler');

    // Search autocomplete AJAX handler
    add_action('wp_ajax_aqualuxe_search_autocomplete', 'aqualuxe_search_autocomplete_ajax_handler');
    add_action('wp_ajax_nopriv_aqualuxe_search_autocomplete', 'aqualuxe_search_autocomplete_ajax_handler');
}
add_action('init', 'aqualuxe_register_ajax_actions');

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_quick_view_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Check product ID
    if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
        wp_send_json_error('Invalid product ID');
        wp_die();
    }

    $product_id = absint($_POST['product_id']);
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        wp_send_json_error('WooCommerce is not active');
        wp_die();
    }

    // Get product data
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Product not found');
        wp_die();
    }

    // Start output buffering
    ob_start();
    
    // Include quick view template
    include get_template_directory() . '/template-parts/components/quick-view.php';
    
    // Get the buffered content
    $html = ob_get_clean();
    
    // Send the response
    wp_send_json_success(array(
        'html' => $html,
    ));
    
    wp_die();
}

/**
 * Load more posts AJAX handler
 */
function aqualuxe_load_more_posts_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_load_more_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Get parameters
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    $posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : get_option('posts_per_page');
    $category = isset($_POST['category']) ? absint($_POST['category']) : 0;

    // Query arguments
    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    // Add category if specified
    if ($category > 0) {
        if ($post_type === 'post') {
            $args['cat'] = $category;
        } else {
            // For custom post types, determine the taxonomy
            $taxonomy = '';
            switch ($post_type) {
                case 'service':
                    $taxonomy = 'service_category';
                    break;
                case 'event':
                    $taxonomy = 'event_category';
                    break;
                case 'project':
                    $taxonomy = 'project_category';
                    break;
                case 'faq':
                    $taxonomy = 'faq_category';
                    break;
            }

            if ($taxonomy) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $taxonomy,
                        'field'    => 'term_id',
                        'terms'    => $category,
                    ),
                );
            }
        }
    }

    // Get posts
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Include appropriate template part based on post type
            get_template_part('template-parts/content/content', $post_type);
        }
        
        wp_reset_postdata();
    }
    
    // Get the buffered content
    $html = ob_get_clean();
    
    // Send the response
    wp_send_json_success(array(
        'html'       => $html,
        'found'      => $query->found_posts,
        'remaining'  => $query->found_posts - ($paged * $posts_per_page),
        'max_pages'  => $query->max_num_pages,
    ));
    
    wp_die();
}

/**
 * Newsletter subscription AJAX handler
 */
function aqualuxe_newsletter_subscribe_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_newsletter_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Check email
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        wp_send_json_error('Email is required');
        wp_die();
    }

    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
        wp_die();
    }

    // This is a placeholder for actual newsletter subscription logic
    // In a real implementation, you would integrate with your newsletter service
    // such as Mailchimp, ConvertKit, etc.
    
    // For now, we'll just simulate a successful subscription
    do_action('aqualuxe_newsletter_subscribe', $email);
    
    wp_send_json_success(array(
        'message' => __('Thank you for subscribing to our newsletter!', 'aqualuxe'),
    ));
    
    wp_die();
}

/**
 * Contact form AJAX handler
 */
function aqualuxe_contact_form_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_contact_form_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Check required fields
    $required_fields = array('name', 'email', 'message');
    $missing_fields = array();
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        wp_send_json_error(array(
            'message' => __('Please fill in all required fields.', 'aqualuxe'),
            'fields'  => $missing_fields,
        ));
        wp_die();
    }

    // Sanitize input
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $subject = isset($_POST['subject']) ? sanitize_text_field($_POST['subject']) : __('New contact form submission', 'aqualuxe');
    $message = sanitize_textarea_field($_POST['message']);
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';

    // Validate email
    if (!is_email($email)) {
        wp_send_json_error(array(
            'message' => __('Please enter a valid email address.', 'aqualuxe'),
            'fields'  => array('email'),
        ));
        wp_die();
    }

    // Build email content
    $email_content = sprintf(
        __("Name: %s\nEmail: %s\nPhone: %s\n\nMessage:\n%s", 'aqualuxe'),
        $name,
        $email,
        $phone,
        $message
    );

    // Get admin email
    $admin_email = get_option('admin_email');
    
    // Send email
    $sent = wp_mail($admin_email, $subject, $email_content, array(
        'From: ' . $name . ' <' . $email . '>',
        'Reply-To: ' . $email,
    ));

    if ($sent) {
        wp_send_json_success(array(
            'message' => __('Thank you for your message! We will get back to you soon.', 'aqualuxe'),
        ));
    } else {
        wp_send_json_error(array(
            'message' => __('There was a problem sending your message. Please try again later.', 'aqualuxe'),
        ));
    }
    
    wp_die();
}

/**
 * Filter products AJAX handler
 */
function aqualuxe_filter_products_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_filter_products_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        wp_send_json_error('WooCommerce is not active');
        wp_die();
    }

    // Get filter parameters
    $category = isset($_POST['category']) ? absint($_POST['category']) : 0;
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : '';
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'menu_order';
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? absint($_POST['posts_per_page']) : get_option('posts_per_page');
    $attributes = isset($_POST['attributes']) ? $_POST['attributes'] : array();

    // Build query args
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
    );

    // Add category if specified
    if ($category > 0) {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => $category,
        );
    }

    // Add price filter
    if ($min_price > 0 || $max_price !== '') {
        $args['meta_query'][] = array(
            'key'     => '_price',
            'value'   => array($min_price, $max_price === '' ? 9999999 : $max_price),
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
    }

    // Add attribute filters
    if (!empty($attributes) && is_array($attributes)) {
        foreach ($attributes as $taxonomy => $terms) {
            if (!empty($terms)) {
                $args['tax_query'][] = array(
                    'taxonomy' => sanitize_key($taxonomy),
                    'field'    => 'term_id',
                    'terms'    => array_map('absint', $terms),
                    'operator' => 'IN',
                );
            }
        }
    }

    // Add orderby
    switch ($orderby) {
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
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'menu_order title';
            $args['order'] = 'ASC';
            break;
    }

    // Get products
    $query = new WP_Query($args);
    
    // Start output buffering
    ob_start();
    
    if ($query->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        
        wp_reset_postdata();
    } else {
        echo '<p class="woocommerce-info">' . esc_html__('No products found.', 'aqualuxe') . '</p>';
    }
    
    // Get the buffered content
    $html = ob_get_clean();
    
    // Send the response
    wp_send_json_success(array(
        'html'       => $html,
        'found'      => $query->found_posts,
        'remaining'  => $query->found_posts - ($paged * $posts_per_page),
        'max_pages'  => $query->max_num_pages,
    ));
    
    wp_die();
}

/**
 * Search autocomplete AJAX handler
 */
function aqualuxe_search_autocomplete_ajax_handler() {
    // Check nonce
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'aqualuxe_search_nonce')) {
        wp_send_json_error('Invalid security token');
        wp_die();
    }

    // Check search term
    if (!isset($_GET['term']) || empty($_GET['term'])) {
        wp_send_json_error('Search term is required');
        wp_die();
    }

    $search_term = sanitize_text_field($_GET['term']);
    $post_types = array('post', 'page', 'service', 'event', 'project', 'faq');
    
    // Add product post type if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $post_types[] = 'product';
    }

    // Query arguments
    $args = array(
        'post_type'      => $post_types,
        'posts_per_page' => 10,
        'post_status'    => 'publish',
        's'              => $search_term,
    );

    // Get results
    $query = new WP_Query($args);
    $results = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $post_type = get_post_type();
            $post_type_obj = get_post_type_object($post_type);
            
            $results[] = array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'post_type' => $post_type_obj->labels->singular_name,
                'image'     => has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') : '',
                'excerpt'   => wp_trim_words(get_the_excerpt(), 10),
            );
        }
        
        wp_reset_postdata();
    }
    
    // Send the response
    wp_send_json_success(array(
        'results' => $results,
    ));
    
    wp_die();
}