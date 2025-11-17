<?php
/**
 * Subscription Custom Post Type
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Subscription Custom Post Type
 */
function aqualuxe_register_subscription_post_type() {
    $labels = array(
        'name'               => _x('Subscriptions', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Subscription', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Subscriptions', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Subscription', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'subscription', 'aqualuxe'),
        'add_new_item'       => __('Add New Subscription', 'aqualuxe'),
        'new_item'           => __('New Subscription', 'aqualuxe'),
        'edit_item'          => __('Edit Subscription', 'aqualuxe'),
        'view_item'          => __('View Subscription', 'aqualuxe'),
        'all_items'          => __('All Subscriptions', 'aqualuxe'),
        'search_items'       => __('Search Subscriptions', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Subscriptions:', 'aqualuxe'),
        'not_found'          => __('No subscriptions found.', 'aqualuxe'),
        'not_found_in_trash' => __('No subscriptions found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Subscription plans for recurring orders', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'subscription'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-update',
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('subscription', $args);
}
add_action('init', 'aqualuxe_register_subscription_post_type');

/**
 * Register Subscription Customer Custom Post Type
 */
function aqualuxe_register_subscription_customer_post_type() {
    $labels = array(
        'name'               => _x('Subscription Customers', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Subscription Customer', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Sub. Customers', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Subscription Customer', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'subscription customer', 'aqualuxe'),
        'add_new_item'       => __('Add New Subscription Customer', 'aqualuxe'),
        'new_item'           => __('New Subscription Customer', 'aqualuxe'),
        'edit_item'          => __('Edit Subscription Customer', 'aqualuxe'),
        'view_item'          => __('View Subscription Customer', 'aqualuxe'),
        'all_items'          => __('All Subscription Customers', 'aqualuxe'),
        'search_items'       => __('Search Subscription Customers', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Subscription Customers:', 'aqualuxe'),
        'not_found'          => __('No subscription customers found.', 'aqualuxe'),
        'not_found_in_trash' => __('No subscription customers found in Trash.', 'aqualuxe')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Customer subscriptions for recurring orders', 'aqualuxe'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=subscription',
        'query_var'          => true,
        'rewrite'            => array('slug' => 'subscription-customer'),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'custom-fields'),
        'show_in_rest'       => true, // Enable Gutenberg editor
    );

    register_post_type('sub_customer', $args);
}
add_action('init', 'aqualuxe_register_subscription_customer_post_type');

/**
 * Register taxonomies for Subscription post type
 */
function aqualuxe_register_subscription_taxonomies() {
    // Subscription Type Taxonomy
    $type_labels = array(
        'name'              => _x('Subscription Types', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Subscription Type', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Subscription Types', 'aqualuxe'),
        'all_items'         => __('All Subscription Types', 'aqualuxe'),
        'parent_item'       => __('Parent Subscription Type', 'aqualuxe'),
        'parent_item_colon' => __('Parent Subscription Type:', 'aqualuxe'),
        'edit_item'         => __('Edit Subscription Type', 'aqualuxe'),
        'update_item'       => __('Update Subscription Type', 'aqualuxe'),
        'add_new_item'      => __('Add New Subscription Type', 'aqualuxe'),
        'new_item_name'     => __('New Subscription Type Name', 'aqualuxe'),
        'menu_name'         => __('Subscription Types', 'aqualuxe'),
    );

    $type_args = array(
        'hierarchical'      => true,
        'labels'            => $type_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'subscription-type'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('subscription_type', array('subscription'), $type_args);

    // Subscription Frequency Taxonomy
    $frequency_labels = array(
        'name'              => _x('Frequencies', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Frequency', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Frequencies', 'aqualuxe'),
        'all_items'         => __('All Frequencies', 'aqualuxe'),
        'parent_item'       => __('Parent Frequency', 'aqualuxe'),
        'parent_item_colon' => __('Parent Frequency:', 'aqualuxe'),
        'edit_item'         => __('Edit Frequency', 'aqualuxe'),
        'update_item'       => __('Update Frequency', 'aqualuxe'),
        'add_new_item'      => __('Add New Frequency', 'aqualuxe'),
        'new_item_name'     => __('New Frequency Name', 'aqualuxe'),
        'menu_name'         => __('Frequencies', 'aqualuxe'),
    );

    $frequency_args = array(
        'hierarchical'      => true,
        'labels'            => $frequency_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'subscription-frequency'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('subscription_frequency', array('subscription'), $frequency_args);

    // Subscription Status Taxonomy
    $status_labels = array(
        'name'              => _x('Statuses', 'taxonomy general name', 'aqualuxe'),
        'singular_name'     => _x('Status', 'taxonomy singular name', 'aqualuxe'),
        'search_items'      => __('Search Statuses', 'aqualuxe'),
        'all_items'         => __('All Statuses', 'aqualuxe'),
        'parent_item'       => __('Parent Status', 'aqualuxe'),
        'parent_item_colon' => __('Parent Status:', 'aqualuxe'),
        'edit_item'         => __('Edit Status', 'aqualuxe'),
        'update_item'       => __('Update Status', 'aqualuxe'),
        'add_new_item'      => __('Add New Status', 'aqualuxe'),
        'new_item_name'     => __('New Status Name', 'aqualuxe'),
        'menu_name'         => __('Statuses', 'aqualuxe'),
    );

    $status_args = array(
        'hierarchical'      => true,
        'labels'            => $status_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'subscription-status'),
        'show_in_rest'      => true, // Enable in Gutenberg
    );

    register_taxonomy('subscription_status', array('sub_customer'), $status_args);
}
add_action('init', 'aqualuxe_register_subscription_taxonomies');

/**
 * Add custom meta boxes for Subscription post type
 */
function aqualuxe_add_subscription_meta_boxes() {
    add_meta_box(
        'subscription_details',
        __('Subscription Details', 'aqualuxe'),
        'aqualuxe_subscription_details_callback',
        'subscription',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_subscription_meta_boxes');

/**
 * Render Subscription Details meta box
 */
function aqualuxe_subscription_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_subscription_details', 'aqualuxe_subscription_details_nonce');

    // Retrieve existing values
    $price = get_post_meta($post->ID, '_subscription_price', true);
    $trial_period = get_post_meta($post->ID, '_trial_period', true);
    $signup_fee = get_post_meta($post->ID, '_signup_fee', true);
    $product_ids = get_post_meta($post->ID, '_product_ids', true);
    $max_products = get_post_meta($post->ID, '_max_products', true);
    $discount_percentage = get_post_meta($post->ID, '_discount_percentage', true);
    $features = get_post_meta($post->ID, '_features', true);
    
    // Output fields
    ?>
    <div class="subscription-meta-box">
        <style>
            .subscription-meta-box {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 15px;
            }
            .subscription-field {
                margin-bottom: 15px;
            }
            .subscription-field label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .subscription-field input[type="text"],
            .subscription-field input[type="number"],
            .subscription-field select,
            .subscription-field textarea {
                width: 100%;
            }
            .subscription-full-width {
                grid-column: 1 / span 2;
            }
        </style>
        
        <div class="subscription-field">
            <label for="subscription_price"><?php _e('Subscription Price', 'aqualuxe'); ?></label>
            <input type="number" id="subscription_price" name="subscription_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0">
            <p class="description"><?php _e('Regular price for this subscription plan.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field">
            <label for="trial_period"><?php _e('Trial Period (days)', 'aqualuxe'); ?></label>
            <input type="number" id="trial_period" name="trial_period" value="<?php echo esc_attr($trial_period); ?>" min="0">
            <p class="description"><?php _e('Number of days for the trial period. Set to 0 for no trial.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field">
            <label for="signup_fee"><?php _e('Sign-up Fee', 'aqualuxe'); ?></label>
            <input type="number" id="signup_fee" name="signup_fee" value="<?php echo esc_attr($signup_fee); ?>" step="0.01" min="0">
            <p class="description"><?php _e('One-time fee charged at the beginning of the subscription.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field">
            <label for="discount_percentage"><?php _e('Discount Percentage', 'aqualuxe'); ?></label>
            <input type="number" id="discount_percentage" name="discount_percentage" value="<?php echo esc_attr($discount_percentage); ?>" min="0" max="100">
            <p class="description"><?php _e('Discount percentage applied to products in this subscription.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field">
            <label for="max_products"><?php _e('Maximum Products', 'aqualuxe'); ?></label>
            <input type="number" id="max_products" name="max_products" value="<?php echo esc_attr($max_products); ?>" min="0">
            <p class="description"><?php _e('Maximum number of products allowed in this subscription. Set to 0 for unlimited.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field subscription-full-width">
            <label for="product_ids"><?php _e('Included Product IDs', 'aqualuxe'); ?></label>
            <input type="text" id="product_ids" name="product_ids" value="<?php echo esc_attr($product_ids); ?>">
            <p class="description"><?php _e('Comma-separated list of product IDs included in this subscription.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-field subscription-full-width">
            <label for="features"><?php _e('Subscription Features', 'aqualuxe'); ?></label>
            <textarea id="features" name="features" rows="5"><?php echo esc_textarea($features); ?></textarea>
            <p class="description"><?php _e('Enter features for this subscription plan, one per line.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Save Subscription meta box data
 */
function aqualuxe_save_subscription_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_subscription_details_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_subscription_details_nonce'], 'aqualuxe_subscription_details')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && 'subscription' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save meta box data
    $fields = array(
        'subscription_price' => 'sanitize_text_field',
        'trial_period' => 'absint',
        'signup_fee' => 'sanitize_text_field',
        'product_ids' => 'sanitize_text_field',
        'max_products' => 'absint',
        'discount_percentage' => 'absint',
        'features' => 'sanitize_textarea_field',
    );

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_callback($_POST[$field]));
        }
    }
}
add_action('save_post', 'aqualuxe_save_subscription_meta_box_data');

/**
 * Add custom meta boxes for Subscription Customer post type
 */
function aqualuxe_add_subscription_customer_meta_boxes() {
    add_meta_box(
        'subscription_customer_details',
        __('Subscription Customer Details', 'aqualuxe'),
        'aqualuxe_subscription_customer_details_callback',
        'sub_customer',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_subscription_customer_meta_boxes');

/**
 * Render Subscription Customer Details meta box
 */
function aqualuxe_subscription_customer_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_subscription_customer_details', 'aqualuxe_subscription_customer_details_nonce');

    // Retrieve existing values
    $user_id = get_post_meta($post->ID, '_user_id', true);
    $subscription_id = get_post_meta($post->ID, '_subscription_id', true);
    $start_date = get_post_meta($post->ID, '_start_date', true);
    $next_payment_date = get_post_meta($post->ID, '_next_payment_date', true);
    $end_date = get_post_meta($post->ID, '_end_date', true);
    $payment_method = get_post_meta($post->ID, '_payment_method', true);
    $billing_amount = get_post_meta($post->ID, '_billing_amount', true);
    $total_payments = get_post_meta($post->ID, '_total_payments', true);
    $notes = get_post_meta($post->ID, '_notes', true);
    
    // Get users for dropdown
    $users = get_users(array('fields' => array('ID', 'display_name')));
    
    // Get subscriptions for dropdown
    $subscriptions = get_posts(array(
        'post_type' => 'subscription',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    
    // Output fields
    ?>
    <div class="subscription-customer-meta-box">
        <style>
            .subscription-customer-meta-box {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 15px;
            }
            .subscription-customer-field {
                margin-bottom: 15px;
            }
            .subscription-customer-field label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .subscription-customer-field input[type="text"],
            .subscription-customer-field input[type="number"],
            .subscription-customer-field select,
            .subscription-customer-field textarea {
                width: 100%;
            }
            .subscription-customer-full-width {
                grid-column: 1 / span 2;
            }
        </style>
        
        <div class="subscription-customer-field">
            <label for="user_id"><?php _e('Customer', 'aqualuxe'); ?></label>
            <select id="user_id" name="user_id">
                <option value=""><?php _e('Select a customer', 'aqualuxe'); ?></option>
                <?php foreach ($users as $user) : ?>
                    <option value="<?php echo esc_attr($user->ID); ?>" <?php selected($user_id, $user->ID); ?>>
                        <?php echo esc_html($user->display_name) . ' (' . esc_html($user->ID) . ')'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="subscription-customer-field">
            <label for="subscription_id"><?php _e('Subscription Plan', 'aqualuxe'); ?></label>
            <select id="subscription_id" name="subscription_id">
                <option value=""><?php _e('Select a subscription plan', 'aqualuxe'); ?></option>
                <?php foreach ($subscriptions as $subscription) : ?>
                    <option value="<?php echo esc_attr($subscription->ID); ?>" <?php selected($subscription_id, $subscription->ID); ?>>
                        <?php echo esc_html($subscription->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="subscription-customer-field">
            <label for="start_date"><?php _e('Start Date', 'aqualuxe'); ?></label>
            <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>">
        </div>
        
        <div class="subscription-customer-field">
            <label for="next_payment_date"><?php _e('Next Payment Date', 'aqualuxe'); ?></label>
            <input type="date" id="next_payment_date" name="next_payment_date" value="<?php echo esc_attr($next_payment_date); ?>">
        </div>
        
        <div class="subscription-customer-field">
            <label for="end_date"><?php _e('End Date', 'aqualuxe'); ?></label>
            <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>">
            <p class="description"><?php _e('Leave empty for ongoing subscription.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="subscription-customer-field">
            <label for="payment_method"><?php _e('Payment Method', 'aqualuxe'); ?></label>
            <input type="text" id="payment_method" name="payment_method" value="<?php echo esc_attr($payment_method); ?>">
        </div>
        
        <div class="subscription-customer-field">
            <label for="billing_amount"><?php _e('Billing Amount', 'aqualuxe'); ?></label>
            <input type="number" id="billing_amount" name="billing_amount" value="<?php echo esc_attr($billing_amount); ?>" step="0.01" min="0">
        </div>
        
        <div class="subscription-customer-field">
            <label for="total_payments"><?php _e('Total Payments Made', 'aqualuxe'); ?></label>
            <input type="number" id="total_payments" name="total_payments" value="<?php echo esc_attr($total_payments); ?>" min="0">
        </div>
        
        <div class="subscription-customer-field subscription-customer-full-width">
            <label for="notes"><?php _e('Notes', 'aqualuxe'); ?></label>
            <textarea id="notes" name="notes" rows="5"><?php echo esc_textarea($notes); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Save Subscription Customer meta box data
 */
function aqualuxe_save_subscription_customer_meta_box_data($post_id) {
    // Check if nonce is set
    if (!isset($_POST['aqualuxe_subscription_customer_details_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['aqualuxe_subscription_customer_details_nonce'], 'aqualuxe_subscription_customer_details')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (isset($_POST['post_type']) && 'sub_customer' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save meta box data
    $fields = array(
        'user_id' => 'absint',
        'subscription_id' => 'absint',
        'start_date' => 'sanitize_text_field',
        'next_payment_date' => 'sanitize_text_field',
        'end_date' => 'sanitize_text_field',
        'payment_method' => 'sanitize_text_field',
        'billing_amount' => 'sanitize_text_field',
        'total_payments' => 'absint',
        'notes' => 'sanitize_textarea_field',
    );

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, $sanitize_callback($_POST[$field]));
        }
    }
    
    // Update post title based on user and subscription
    if (isset($_POST['user_id']) && isset($_POST['subscription_id'])) {
        $user_id = absint($_POST['user_id']);
        $subscription_id = absint($_POST['subscription_id']);
        
        if ($user_id && $subscription_id) {
            $user = get_user_by('id', $user_id);
            $subscription = get_post($subscription_id);
            
            if ($user && $subscription) {
                $title = sprintf(
                    '%s - %s (#%d)',
                    $user->display_name,
                    $subscription->post_title,
                    $post_id
                );
                
                // Update post title
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $title,
                ));
            }
        }
    }
}
add_action('save_post', 'aqualuxe_save_subscription_customer_meta_box_data');

/**
 * Add custom columns to subscription list
 */
function aqualuxe_subscription_columns($columns) {
    $new_columns = array();
    
    // Keep checkbox and title
    if (isset($columns['cb'])) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    $new_columns['title'] = __('Subscription Plan', 'aqualuxe');
    $new_columns['price'] = __('Price', 'aqualuxe');
    $new_columns['frequency'] = __('Frequency', 'aqualuxe');
    $new_columns['type'] = __('Type', 'aqualuxe');
    $new_columns['subscribers'] = __('Subscribers', 'aqualuxe');
    $new_columns['date'] = __('Date', 'aqualuxe');
    
    return $new_columns;
}
add_filter('manage_subscription_posts_columns', 'aqualuxe_subscription_columns');

/**
 * Add content to custom columns for subscription list
 */
function aqualuxe_subscription_custom_column($column, $post_id) {
    switch ($column) {
        case 'price':
            $price = get_post_meta($post_id, '_subscription_price', true);
            echo !empty($price) ? '$' . number_format((float)$price, 2) : '—';
            break;
            
        case 'frequency':
            $terms = get_the_terms($post_id, 'subscription_frequency');
            if (!empty($terms) && !is_wp_error($terms)) {
                $frequency_links = array();
                foreach ($terms as $term) {
                    $frequency_links[] = '<a href="' . esc_url(add_query_arg(array('post_type' => 'subscription', 'subscription_frequency' => $term->slug), 'edit.php')) . '">' . esc_html($term->name) . '</a>';
                }
                echo implode(', ', $frequency_links);
            } else {
                echo '—';
            }
            break;
            
        case 'type':
            $terms = get_the_terms($post_id, 'subscription_type');
            if (!empty($terms) && !is_wp_error($terms)) {
                $type_links = array();
                foreach ($terms as $term) {
                    $type_links[] = '<a href="' . esc_url(add_query_arg(array('post_type' => 'subscription', 'subscription_type' => $term->slug), 'edit.php')) . '">' . esc_html($term->name) . '</a>';
                }
                echo implode(', ', $type_links);
            } else {
                echo '—';
            }
            break;
            
        case 'subscribers':
            $subscribers = get_posts(array(
                'post_type' => 'sub_customer',
                'meta_key' => '_subscription_id',
                'meta_value' => $post_id,
                'posts_per_page' => -1,
            ));
            
            $count = count($subscribers);
            echo '<a href="' . esc_url(add_query_arg(array('post_type' => 'sub_customer', 'subscription_id' => $post_id), 'edit.php')) . '">' . $count . '</a>';
            break;
    }
}
add_action('manage_subscription_posts_custom_column', 'aqualuxe_subscription_custom_column', 10, 2);

/**
 * Add custom columns to subscription customer list
 */
function aqualuxe_subscription_customer_columns($columns) {
    $new_columns = array();
    
    // Keep checkbox and title
    if (isset($columns['cb'])) {
        $new_columns['cb'] = $columns['cb'];
    }
    
    $new_columns['title'] = __('Customer', 'aqualuxe');
    $new_columns['subscription'] = __('Subscription Plan', 'aqualuxe');
    $new_columns['status'] = __('Status', 'aqualuxe');
    $new_columns['start_date'] = __('Start Date', 'aqualuxe');
    $new_columns['next_payment'] = __('Next Payment', 'aqualuxe');
    $new_columns['amount'] = __('Amount', 'aqualuxe');
    $new_columns['date'] = __('Created', 'aqualuxe');
    
    return $new_columns;
}
add_filter('manage_sub_customer_posts_columns', 'aqualuxe_subscription_customer_columns');

/**
 * Add content to custom columns for subscription customer list
 */
function aqualuxe_subscription_customer_custom_column($column, $post_id) {
    switch ($column) {
        case 'subscription':
            $subscription_id = get_post_meta($post_id, '_subscription_id', true);
            if ($subscription_id) {
                $subscription = get_post($subscription_id);
                if ($subscription) {
                    echo '<a href="' . esc_url(get_edit_post_link($subscription_id)) . '">' . esc_html($subscription->post_title) . '</a>';
                } else {
                    echo '—';
                }
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $terms = get_the_terms($post_id, 'subscription_status');
            if (!empty($terms) && !is_wp_error($terms)) {
                $status_links = array();
                foreach ($terms as $term) {
                    $status_class = 'status-' . $term->slug;
                    $status_links[] = '<span class="' . esc_attr($status_class) . '"><a href="' . esc_url(add_query_arg(array('post_type' => 'sub_customer', 'subscription_status' => $term->slug), 'edit.php')) . '">' . esc_html($term->name) . '</a></span>';
                }
                echo implode(', ', $status_links);
            } else {
                echo '—';
            }
            break;
            
        case 'start_date':
            $start_date = get_post_meta($post_id, '_start_date', true);
            echo !empty($start_date) ? date_i18n(get_option('date_format'), strtotime($start_date)) : '—';
            break;
            
        case 'next_payment':
            $next_payment_date = get_post_meta($post_id, '_next_payment_date', true);
            echo !empty($next_payment_date) ? date_i18n(get_option('date_format'), strtotime($next_payment_date)) : '—';
            break;
            
        case 'amount':
            $billing_amount = get_post_meta($post_id, '_billing_amount', true);
            echo !empty($billing_amount) ? '$' . number_format((float)$billing_amount, 2) : '—';
            break;
    }
}
add_action('manage_sub_customer_posts_custom_column', 'aqualuxe_subscription_customer_custom_column', 10, 2);

/**
 * Add admin CSS for subscription status colors
 */
function aqualuxe_subscription_admin_css() {
    $screen = get_current_screen();
    
    if ($screen && $screen->post_type === 'sub_customer') {
        ?>
        <style>
            .status-active {
                color: #28a745;
                font-weight: bold;
            }
            .status-pending {
                color: #ffc107;
                font-weight: bold;
            }
            .status-cancelled {
                color: #dc3545;
                font-weight: bold;
            }
            .status-expired {
                color: #6c757d;
                font-weight: bold;
            }
            .status-on-hold {
                color: #17a2b8;
                font-weight: bold;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'aqualuxe_subscription_admin_css');

/**
 * Add default terms for subscription taxonomies
 */
function aqualuxe_add_default_subscription_terms() {
    // Add default subscription types
    $default_types = array(
        'product' => __('Product Subscription', 'aqualuxe'),
        'service' => __('Service Subscription', 'aqualuxe'),
        'membership' => __('Membership', 'aqualuxe'),
    );
    
    foreach ($default_types as $slug => $name) {
        if (!term_exists($slug, 'subscription_type')) {
            wp_insert_term($name, 'subscription_type', array('slug' => $slug));
        }
    }
    
    // Add default subscription frequencies
    $default_frequencies = array(
        'weekly' => __('Weekly', 'aqualuxe'),
        'biweekly' => __('Bi-weekly', 'aqualuxe'),
        'monthly' => __('Monthly', 'aqualuxe'),
        'quarterly' => __('Quarterly', 'aqualuxe'),
        'biannually' => __('Bi-annually', 'aqualuxe'),
        'annually' => __('Annually', 'aqualuxe'),
    );
    
    foreach ($default_frequencies as $slug => $name) {
        if (!term_exists($slug, 'subscription_frequency')) {
            wp_insert_term($name, 'subscription_frequency', array('slug' => $slug));
        }
    }
    
    // Add default subscription statuses
    $default_statuses = array(
        'active' => __('Active', 'aqualuxe'),
        'pending' => __('Pending', 'aqualuxe'),
        'cancelled' => __('Cancelled', 'aqualuxe'),
        'expired' => __('Expired', 'aqualuxe'),
        'on-hold' => __('On Hold', 'aqualuxe'),
    );
    
    foreach ($default_statuses as $slug => $name) {
        if (!term_exists($slug, 'subscription_status')) {
            wp_insert_term($name, 'subscription_status', array('slug' => $slug));
        }
    }
}
register_activation_hook(__FILE__, 'aqualuxe_add_default_subscription_terms');

// Add default terms when plugin is loaded (in case activation hook doesn't run)
add_action('init', 'aqualuxe_add_default_subscription_terms', 20);