<?php
/**
 * WooCommerce Stock Notification System
 *
 * @package AquaLuxe
 */

/**
 * Register stock notification post type.
 */
function aqualuxe_register_stock_notification_post_type() {
    $labels = array(
        'name'               => _x('Stock Notifications', 'post type general name', 'aqualuxe'),
        'singular_name'      => _x('Stock Notification', 'post type singular name', 'aqualuxe'),
        'menu_name'          => _x('Stock Notifications', 'admin menu', 'aqualuxe'),
        'name_admin_bar'     => _x('Stock Notification', 'add new on admin bar', 'aqualuxe'),
        'add_new'            => _x('Add New', 'stock notification', 'aqualuxe'),
        'add_new_item'       => __('Add New Stock Notification', 'aqualuxe'),
        'new_item'           => __('New Stock Notification', 'aqualuxe'),
        'edit_item'          => __('Edit Stock Notification', 'aqualuxe'),
        'view_item'          => __('View Stock Notification', 'aqualuxe'),
        'all_items'          => __('All Stock Notifications', 'aqualuxe'),
        'search_items'       => __('Search Stock Notifications', 'aqualuxe'),
        'parent_item_colon'  => __('Parent Stock Notifications:', 'aqualuxe'),
        'not_found'          => __('No stock notifications found.', 'aqualuxe'),
        'not_found_in_trash' => __('No stock notifications found in Trash.', 'aqualuxe'),
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Stock notifications for out-of-stock products.', 'aqualuxe'),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => 'edit.php?post_type=product',
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title'),
    );

    register_post_type('stock_notification', $args);
}
add_action('init', 'aqualuxe_register_stock_notification_post_type');

/**
 * Add meta boxes for stock notification post type.
 */
function aqualuxe_add_stock_notification_meta_boxes() {
    add_meta_box(
        'stock_notification_details',
        __('Notification Details', 'aqualuxe'),
        'aqualuxe_stock_notification_details_callback',
        'stock_notification',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_stock_notification_meta_boxes');

/**
 * Render stock notification details meta box.
 *
 * @param WP_Post $post Post object.
 */
function aqualuxe_stock_notification_details_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_stock_notification_nonce', 'stock_notification_nonce');

    // Get saved values
    $product_id = get_post_meta($post->ID, '_product_id', true);
    $email = get_post_meta($post->ID, '_email', true);
    $name = get_post_meta($post->ID, '_name', true);
    $phone = get_post_meta($post->ID, '_phone', true);
    $date_registered = get_post_meta($post->ID, '_date_registered', true);
    $notified = get_post_meta($post->ID, '_notified', true);
    $date_notified = get_post_meta($post->ID, '_date_notified', true);

    // Get product details
    $product = wc_get_product($product_id);
    $product_title = $product ? $product->get_name() : __('Product not found', 'aqualuxe');
    $product_sku = $product ? $product->get_sku() : '';
    $product_stock_status = $product ? $product->get_stock_status() : '';

    // Format dates
    $date_registered_formatted = !empty($date_registered) ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($date_registered)) : '';
    $date_notified_formatted = !empty($date_notified) ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($date_notified)) : '';

    ?>
    <style>
        .stock-notification-field {
            margin-bottom: 15px;
        }
        .stock-notification-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .stock-notification-field input[type="text"],
        .stock-notification-field input[type="email"],
        .stock-notification-field select {
            width: 100%;
            max-width: 400px;
        }
        .stock-notification-product {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }
        .stock-notification-status {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .stock-notification-status.notified {
            background-color: #ecf7ed;
            border-color: #46b450;
        }
        .stock-notification-status.not-notified {
            background-color: #fef8ee;
            border-color: #ffb900;
        }
    </style>

    <div class="stock-notification-product">
        <div class="stock-notification-field">
            <label for="product_id"><?php _e('Product', 'aqualuxe'); ?>:</label>
            <?php if ($product) : ?>
                <a href="<?php echo esc_url(get_edit_post_link($product_id)); ?>" target="_blank"><?php echo esc_html($product_title); ?></a>
                <?php if (!empty($product_sku)) : ?>
                    (<?php echo esc_html($product_sku); ?>)
                <?php endif; ?>
                <input type="hidden" name="product_id" id="product_id" value="<?php echo esc_attr($product_id); ?>">
            <?php else : ?>
                <select name="product_id" id="product_id" required>
                    <option value=""><?php _e('Select a product', 'aqualuxe'); ?></option>
                    <?php
                    $products = wc_get_products(array(
                        'limit' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC',
                    ));

                    foreach ($products as $product) {
                        echo '<option value="' . esc_attr($product->get_id()) . '" ' . selected($product_id, $product->get_id(), false) . '>' . esc_html($product->get_name()) . ' (' . esc_html($product->get_sku()) . ')</option>';
                    }
                    ?>
                </select>
            <?php endif; ?>
        </div>

        <?php if ($product) : ?>
            <div class="stock-notification-field">
                <label><?php _e('Current Stock Status', 'aqualuxe'); ?>:</label>
                <?php
                switch ($product_stock_status) {
                    case 'instock':
                        echo '<span style="color: #46b450;">' . __('In Stock', 'aqualuxe') . '</span>';
                        break;
                    case 'outofstock':
                        echo '<span style="color: #dc3232;">' . __('Out of Stock', 'aqualuxe') . '</span>';
                        break;
                    case 'onbackorder':
                        echo '<span style="color: #ffb900;">' . __('On Backorder', 'aqualuxe') . '</span>';
                        break;
                    default:
                        echo esc_html($product_stock_status);
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="stock-notification-field">
        <label for="email"><?php _e('Email', 'aqualuxe'); ?>:</label>
        <input type="email" name="email" id="email" value="<?php echo esc_attr($email); ?>" required>
    </div>

    <div class="stock-notification-field">
        <label for="name"><?php _e('Name', 'aqualuxe'); ?>:</label>
        <input type="text" name="name" id="name" value="<?php echo esc_attr($name); ?>">
    </div>

    <div class="stock-notification-field">
        <label for="phone"><?php _e('Phone', 'aqualuxe'); ?>:</label>
        <input type="text" name="phone" id="phone" value="<?php echo esc_attr($phone); ?>">
    </div>

    <div class="stock-notification-field">
        <label><?php _e('Date Registered', 'aqualuxe'); ?>:</label>
        <?php echo !empty($date_registered_formatted) ? esc_html($date_registered_formatted) : __('Not set', 'aqualuxe'); ?>
    </div>

    <div class="stock-notification-status <?php echo !empty($notified) ? 'notified' : 'not-notified'; ?>">
        <div class="stock-notification-field">
            <label for="notified"><?php _e('Notification Status', 'aqualuxe'); ?>:</label>
            <select name="notified" id="notified">
                <option value=""><?php _e('Not Notified', 'aqualuxe'); ?></option>
                <option value="1" <?php selected($notified, '1'); ?>><?php _e('Notified', 'aqualuxe'); ?></option>
            </select>
        </div>

        <?php if (!empty($date_notified)) : ?>
            <div class="stock-notification-field">
                <label><?php _e('Date Notified', 'aqualuxe'); ?>:</label>
                <?php echo esc_html($date_notified_formatted); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save stock notification meta box data.
 *
 * @param int $post_id Post ID.
 */
function aqualuxe_save_stock_notification_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['stock_notification_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['stock_notification_nonce'], 'aqualuxe_stock_notification_nonce')) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save product ID
    if (isset($_POST['product_id'])) {
        update_post_meta($post_id, '_product_id', sanitize_text_field($_POST['product_id']));
    }

    // Save email
    if (isset($_POST['email'])) {
        update_post_meta($post_id, '_email', sanitize_email($_POST['email']));
    }

    // Save name
    if (isset($_POST['name'])) {
        update_post_meta($post_id, '_name', sanitize_text_field($_POST['name']));
    }

    // Save phone
    if (isset($_POST['phone'])) {
        update_post_meta($post_id, '_phone', sanitize_text_field($_POST['phone']));
    }

    // Save notification status
    $old_notified = get_post_meta($post_id, '_notified', true);
    $new_notified = isset($_POST['notified']) ? sanitize_text_field($_POST['notified']) : '';

    update_post_meta($post_id, '_notified', $new_notified);

    // If notification status changed to notified, update date notified
    if ($old_notified !== '1' && $new_notified === '1') {
        update_post_meta($post_id, '_date_notified', current_time('mysql'));
    }

    // If this is a new notification, set the date registered
    if (empty(get_post_meta($post_id, '_date_registered', true))) {
        update_post_meta($post_id, '_date_registered', current_time('mysql'));
    }
}
add_action('save_post_stock_notification', 'aqualuxe_save_stock_notification_meta_box');

/**
 * Add custom columns to stock notification list table.
 *
 * @param array $columns Columns.
 * @return array
 */
function aqualuxe_stock_notification_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = __('ID', 'aqualuxe');
            $new_columns['product'] = __('Product', 'aqualuxe');
            $new_columns['email'] = __('Email', 'aqualuxe');
            $new_columns['name'] = __('Name', 'aqualuxe');
            $new_columns['date_registered'] = __('Date Registered', 'aqualuxe');
            $new_columns['status'] = __('Status', 'aqualuxe');
        } else {
            $new_columns[$key] = $value;
        }
    }

    return $new_columns;
}
add_filter('manage_stock_notification_posts_columns', 'aqualuxe_stock_notification_columns');

/**
 * Render custom column content for stock notification list table.
 *
 * @param string $column Column name.
 * @param int $post_id Post ID.
 */
function aqualuxe_stock_notification_column_content($column, $post_id) {
    switch ($column) {
        case 'product':
            $product_id = get_post_meta($post_id, '_product_id', true);
            $product = wc_get_product($product_id);

            if ($product) {
                echo '<a href="' . esc_url(get_edit_post_link($product_id)) . '">' . esc_html($product->get_name()) . '</a>';
            } else {
                echo __('Product not found', 'aqualuxe');
            }
            break;

        case 'email':
            echo esc_html(get_post_meta($post_id, '_email', true));
            break;

        case 'name':
            echo esc_html(get_post_meta($post_id, '_name', true));
            break;

        case 'date_registered':
            $date_registered = get_post_meta($post_id, '_date_registered', true);
            if (!empty($date_registered)) {
                echo esc_html(date_i18n(get_option('date_format'), strtotime($date_registered)));
            } else {
                echo '—';
            }
            break;

        case 'status':
            $notified = get_post_meta($post_id, '_notified', true);
            if ($notified === '1') {
                echo '<span class="stock-status notified">' . __('Notified', 'aqualuxe') . '</span>';
            } else {
                echo '<span class="stock-status not-notified">' . __('Not Notified', 'aqualuxe') . '</span>';
            }
            break;
    }
}
add_action('manage_stock_notification_posts_custom_column', 'aqualuxe_stock_notification_column_content', 10, 2);

/**
 * Add sortable columns to stock notification list table.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function aqualuxe_stock_notification_sortable_columns($columns) {
    $columns['product'] = 'product';
    $columns['email'] = 'email';
    $columns['date_registered'] = 'date_registered';
    $columns['status'] = 'status';
    return $columns;
}
add_filter('manage_edit-stock_notification_sortable_columns', 'aqualuxe_stock_notification_sortable_columns');

/**
 * Handle custom sorting for stock notification list table.
 *
 * @param WP_Query $query Query object.
 */
function aqualuxe_stock_notification_sort_columns($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'stock_notification') {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'product':
            $query->set('meta_key', '_product_id');
            $query->set('orderby', 'meta_value_num');
            break;

        case 'email':
            $query->set('meta_key', '_email');
            $query->set('orderby', 'meta_value');
            break;

        case 'date_registered':
            $query->set('meta_key', '_date_registered');
            $query->set('orderby', 'meta_value');
            break;

        case 'status':
            $query->set('meta_key', '_notified');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'aqualuxe_stock_notification_sort_columns');

/**
 * Add custom admin CSS for stock notification list table.
 */
function aqualuxe_stock_notification_admin_css() {
    $screen = get_current_screen();

    if ($screen && $screen->post_type === 'stock_notification') {
        ?>
        <style>
            .stock-status {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 3px;
                font-weight: 500;
            }
            .stock-status.notified {
                background-color: #ecf7ed;
                color: #46b450;
            }
            .stock-status.not-notified {
                background-color: #fef8ee;
                color: #ffb900;
            }
            .column-title {
                width: 80px;
            }
            .column-product {
                width: 25%;
            }
            .column-email {
                width: 20%;
            }
            .column-name {
                width: 15%;
            }
            .column-date_registered {
                width: 15%;
            }
            .column-status {
                width: 10%;
            }
        </style>
        <?php
    }
}
add_action('admin_head', 'aqualuxe_stock_notification_admin_css');

/**
 * Add stock notification form to out-of-stock products.
 */
function aqualuxe_add_stock_notification_form() {
    global $product;

    if (!$product || $product->is_in_stock() || $product->is_type('grouped')) {
        return;
    }

    // Check if stock notifications are enabled for this product
    $enable_stock_notifications = get_post_meta($product->get_id(), '_enable_stock_notifications', true);
    
    if ($enable_stock_notifications !== 'yes') {
        return;
    }

    ?>
    <div class="stock-notification-form-wrapper">
        <h3><?php _e('Get notified when this product is back in stock', 'aqualuxe'); ?></h3>
        
        <form class="stock-notification-form" method="post">
            <p class="stock-notification-description">
                <?php _e('Enter your email address below to be notified when this item is back in stock.', 'aqualuxe'); ?>
            </p>
            
            <div class="stock-notification-fields">
                <p class="stock-notification-field">
                    <label for="stock_notification_email"><?php _e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" name="stock_notification_email" id="stock_notification_email" required>
                </p>
                
                <p class="stock-notification-field">
                    <label for="stock_notification_name"><?php _e('Name', 'aqualuxe'); ?></label>
                    <input type="text" name="stock_notification_name" id="stock_notification_name">
                </p>
                
                <p class="stock-notification-field">
                    <label for="stock_notification_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
                    <input type="text" name="stock_notification_phone" id="stock_notification_phone">
                </p>
            </div>
            
            <p class="stock-notification-submit">
                <input type="hidden" name="stock_notification_product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="stock_notification_nonce" value="<?php echo wp_create_nonce('stock_notification_form'); ?>">
                <button type="submit" name="stock_notification_submit" class="button"><?php _e('Notify Me', 'aqualuxe'); ?></button>
            </p>
        </form>
    </div>

    <style>
        .stock-notification-form-wrapper {
            margin: 30px 0;
            padding: 20px;
            background-color: rgba(0, 115, 170, 0.05);
            border-radius: 4px;
        }
        
        .stock-notification-form-wrapper h3 {
            margin-top: 0;
            color: #0073aa;
        }
        
        .stock-notification-description {
            margin-bottom: 20px;
        }
        
        .stock-notification-fields {
            margin-bottom: 20px;
        }
        
        .stock-notification-field {
            margin-bottom: 15px;
        }
        
        .stock-notification-field label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .stock-notification-field input {
            width: 100%;
            max-width: 400px;
        }
        
        .stock-notification-submit .button {
            background-color: #0073aa;
            color: #fff;
            border-color: #0073aa;
        }
        
        .stock-notification-submit .button:hover {
            background-color: #005177;
            border-color: #005177;
        }
        
        .stock-notification-message {
            margin: 20px 0;
            padding: 10px 15px;
            border-radius: 4px;
        }
        
        .stock-notification-message.success {
            background-color: #ecf7ed;
            color: #46b450;
            border: 1px solid #46b450;
        }
        
        .stock-notification-message.error {
            background-color: #fbeaea;
            color: #dc3232;
            border: 1px solid #dc3232;
        }
    </style>
    <?php
}
add_action('woocommerce_single_product_summary', 'aqualuxe_add_stock_notification_form', 31);

/**
 * Process stock notification form submission.
 */
function aqualuxe_process_stock_notification_form() {
    if (!isset($_POST['stock_notification_submit'])) {
        return;
    }

    // Verify nonce
    if (!isset($_POST['stock_notification_nonce']) || !wp_verify_nonce($_POST['stock_notification_nonce'], 'stock_notification_form')) {
        wc_add_notice(__('Security check failed. Please try again.', 'aqualuxe'), 'error');
        return;
    }

    // Get form data
    $product_id = isset($_POST['stock_notification_product_id']) ? absint($_POST['stock_notification_product_id']) : 0;
    $email = isset($_POST['stock_notification_email']) ? sanitize_email($_POST['stock_notification_email']) : '';
    $name = isset($_POST['stock_notification_name']) ? sanitize_text_field($_POST['stock_notification_name']) : '';
    $phone = isset($_POST['stock_notification_phone']) ? sanitize_text_field($_POST['stock_notification_phone']) : '';

    // Validate product ID
    $product = wc_get_product($product_id);
    if (!$product) {
        wc_add_notice(__('Invalid product. Please try again.', 'aqualuxe'), 'error');
        return;
    }

    // Validate email
    if (empty($email) || !is_email($email)) {
        wc_add_notice(__('Please enter a valid email address.', 'aqualuxe'), 'error');
        return;
    }

    // Check if notification already exists
    $existing_notifications = get_posts(array(
        'post_type' => 'stock_notification',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_product_id',
                'value' => $product_id,
                'compare' => '=',
            ),
            array(
                'key' => '_email',
                'value' => $email,
                'compare' => '=',
            ),
            array(
                'key' => '_notified',
                'value' => '1',
                'compare' => '!=',
            ),
        ),
        'posts_per_page' => 1,
    ));

    if (!empty($existing_notifications)) {
        wc_add_notice(__('You are already subscribed to notifications for this product.', 'aqualuxe'), 'notice');
        return;
    }

    // Create new notification
    $notification_id = wp_insert_post(array(
        'post_title' => 'SN-' . $product_id . '-' . substr(md5($email), 0, 8),
        'post_type' => 'stock_notification',
        'post_status' => 'publish',
    ));

    if (is_wp_error($notification_id)) {
        wc_add_notice(__('An error occurred. Please try again.', 'aqualuxe'), 'error');
        return;
    }

    // Save notification meta
    update_post_meta($notification_id, '_product_id', $product_id);
    update_post_meta($notification_id, '_email', $email);
    update_post_meta($notification_id, '_name', $name);
    update_post_meta($notification_id, '_phone', $phone);
    update_post_meta($notification_id, '_date_registered', current_time('mysql'));
    update_post_meta($notification_id, '_notified', '');

    // Send confirmation email
    aqualuxe_send_stock_notification_confirmation($notification_id);

    wc_add_notice(__('Thank you! You will be notified when this product is back in stock.', 'aqualuxe'), 'success');
}
add_action('template_redirect', 'aqualuxe_process_stock_notification_form');

/**
 * Send stock notification confirmation email.
 *
 * @param int $notification_id Notification ID.
 */
function aqualuxe_send_stock_notification_confirmation($notification_id) {
    $product_id = get_post_meta($notification_id, '_product_id', true);
    $email = get_post_meta($notification_id, '_email', true);
    $name = get_post_meta($notification_id, '_name', true);

    $product = wc_get_product($product_id);
    if (!$product || empty($email)) {
        return;
    }

    $product_name = $product->get_name();
    $product_url = get_permalink($product_id);

    $subject = sprintf(__('Your Stock Notification for %s', 'aqualuxe'), $product_name);

    $message = sprintf(__('Hello%s,', 'aqualuxe'), !empty($name) ? ' ' . $name : '') . "\n\n";
    $message .= sprintf(__('You have successfully subscribed to stock notifications for %s.', 'aqualuxe'), $product_name) . "\n\n";
    $message .= __('We will notify you as soon as this product is back in stock.', 'aqualuxe') . "\n\n";
    $message .= sprintf(__('Product: %s', 'aqualuxe'), $product_name) . "\n";
    $message .= sprintf(__('Product URL: %s', 'aqualuxe'), $product_url) . "\n\n";
    $message .= __('Thank you for your interest in our products.', 'aqualuxe') . "\n\n";
    $message .= get_bloginfo('name');

    // HTML email
    $html_message = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
    $html_message .= '<h2 style="color: #0073aa;">' . __('Stock Notification Confirmation', 'aqualuxe') . '</h2>';
    $html_message .= '<p>' . sprintf(__('Hello%s,', 'aqualuxe'), !empty($name) ? ' <strong>' . esc_html($name) . '</strong>' : '') . '</p>';
    $html_message .= '<p>' . sprintf(__('You have successfully subscribed to stock notifications for <strong>%s</strong>.', 'aqualuxe'), esc_html($product_name)) . '</p>';
    $html_message .= '<p>' . __('We will notify you as soon as this product is back in stock.', 'aqualuxe') . '</p>';
    $html_message .= '<div style="margin: 20px 0; padding: 15px; background-color: #f8f8f8; border-left: 4px solid #0073aa;">';
    $html_message .= '<p><strong>' . __('Product Details:', 'aqualuxe') . '</strong></p>';
    $html_message .= '<p>' . __('Product:', 'aqualuxe') . ' ' . esc_html($product_name) . '</p>';
    
    // Add product image if available
    $image_id = $product->get_image_id();
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
        if ($image_url) {
            $html_message .= '<p><img src="' . esc_url($image_url) . '" alt="' . esc_attr($product_name) . '" style="max-width: 150px; height: auto;"></p>';
        }
    }
    
    $html_message .= '<p><a href="' . esc_url($product_url) . '" style="color: #0073aa;">' . __('View Product', 'aqualuxe') . '</a></p>';
    $html_message .= '</div>';
    $html_message .= '<p>' . __('Thank you for your interest in our products.', 'aqualuxe') . '</p>';
    $html_message .= '<p style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #eee; font-size: 12px; color: #666;">';
    $html_message .= get_bloginfo('name');
    $html_message .= '</p>';
    $html_message .= '</div>';

    // Send email
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
    );

    wp_mail($email, $subject, $html_message, $headers);
}

/**
 * Send stock notification email when product is back in stock.
 *
 * @param int $product_id Product ID.
 */
function aqualuxe_send_stock_notification_emails($product_id) {
    $product = wc_get_product($product_id);
    if (!$product || !$product->is_in_stock()) {
        return;
    }

    // Get pending notifications
    $notifications = get_posts(array(
        'post_type' => 'stock_notification',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_product_id',
                'value' => $product_id,
                'compare' => '=',
            ),
            array(
                'key' => '_notified',
                'value' => '1',
                'compare' => '!=',
            ),
        ),
        'posts_per_page' => -1,
    ));

    if (empty($notifications)) {
        return;
    }

    $product_name = $product->get_name();
    $product_url = get_permalink($product_id);
    $product_price = $product->get_price_html();

    foreach ($notifications as $notification) {
        $notification_id = $notification->ID;
        $email = get_post_meta($notification_id, '_email', true);
        $name = get_post_meta($notification_id, '_name', true);

        if (empty($email)) {
            continue;
        }

        $subject = sprintf(__('%s is now back in stock!', 'aqualuxe'), $product_name);

        // HTML email
        $html_message = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
        $html_message .= '<h2 style="color: #0073aa;">' . __('Product Back in Stock', 'aqualuxe') . '</h2>';
        $html_message .= '<p>' . sprintf(__('Hello%s,', 'aqualuxe'), !empty($name) ? ' <strong>' . esc_html($name) . '</strong>' : '') . '</p>';
        $html_message .= '<p>' . sprintf(__('Good news! <strong>%s</strong> is now back in stock.', 'aqualuxe'), esc_html($product_name)) . '</p>';
        $html_message .= '<div style="margin: 20px 0; padding: 15px; background-color: #f8f8f8; border-left: 4px solid #0073aa;">';
        $html_message .= '<p><strong>' . __('Product Details:', 'aqualuxe') . '</strong></p>';
        $html_message .= '<p>' . __('Product:', 'aqualuxe') . ' ' . esc_html($product_name) . '</p>';
        $html_message .= '<p>' . __('Price:', 'aqualuxe') . ' ' . wp_kses_post($product_price) . '</p>';
        
        // Add product image if available
        $image_id = $product->get_image_id();
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            if ($image_url) {
                $html_message .= '<p><img src="' . esc_url($image_url) . '" alt="' . esc_attr($product_name) . '" style="max-width: 150px; height: auto;"></p>';
            }
        }
        
        $html_message .= '<p><a href="' . esc_url($product_url) . '" style="display: inline-block; padding: 10px 15px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 3px;">' . __('View Product', 'aqualuxe') . '</a></p>';
        $html_message .= '</div>';
        $html_message .= '<p>' . __('This product is now available for purchase. Don\'t wait too long, as stock may be limited.', 'aqualuxe') . '</p>';
        $html_message .= '<p>' . __('Thank you for your patience and interest in our products.', 'aqualuxe') . '</p>';
        $html_message .= '<p style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #eee; font-size: 12px; color: #666;">';
        $html_message .= get_bloginfo('name');
        $html_message .= '</p>';
        $html_message .= '</div>';

        // Send email
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
        );

        $sent = wp_mail($email, $subject, $html_message, $headers);

        if ($sent) {
            // Update notification status
            update_post_meta($notification_id, '_notified', '1');
            update_post_meta($notification_id, '_date_notified', current_time('mysql'));
        }
    }
}

/**
 * Trigger stock notification emails when product stock status changes.
 *
 * @param int $product_id Product ID.
 * @param string $status Stock status.
 */
function aqualuxe_trigger_stock_notification($product_id, $status) {
    if ($status === 'instock') {
        aqualuxe_send_stock_notification_emails($product_id);
    }
}
add_action('woocommerce_product_set_stock_status', 'aqualuxe_trigger_stock_notification', 10, 2);

/**
 * Add stock notification settings to product data tabs.
 *
 * @param array $tabs Product data tabs.
 * @return array
 */
function aqualuxe_stock_notification_product_tab($tabs) {
    $tabs['stock_notification'] = array(
        'label' => __('Stock Notifications', 'aqualuxe'),
        'target' => 'stock_notification_options',
        'class' => array(),
    );
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'aqualuxe_stock_notification_product_tab');

/**
 * Add stock notification settings to product data panels.
 */
function aqualuxe_stock_notification_product_panel() {
    global $post;
    
    ?>
    <div id="stock_notification_options" class="panel woocommerce_options_panel">
        <div class="options_group">
            <?php
            woocommerce_wp_checkbox(array(
                'id' => '_enable_stock_notifications',
                'label' => __('Enable Stock Notifications', 'aqualuxe'),
                'description' => __('Enable stock notifications for this product.', 'aqualuxe'),
            ));
            ?>
        </div>
        
        <div class="options_group">
            <p class="form-field">
                <label><?php _e('Current Notifications', 'aqualuxe'); ?></label>
                <?php
                $notifications = get_posts(array(
                    'post_type' => 'stock_notification',
                    'meta_query' => array(
                        array(
                            'key' => '_product_id',
                            'value' => $post->ID,
                            'compare' => '=',
                        ),
                    ),
                    'posts_per_page' => -1,
                ));
                
                $notified_count = 0;
                $pending_count = 0;
                
                foreach ($notifications as $notification) {
                    $notified = get_post_meta($notification->ID, '_notified', true);
                    if ($notified === '1') {
                        $notified_count++;
                    } else {
                        $pending_count++;
                    }
                }
                
                echo '<span class="description">';
                printf(
                    __('Pending: %1$s, Notified: %2$s', 'aqualuxe'),
                    '<strong>' . $pending_count . '</strong>',
                    '<strong>' . $notified_count . '</strong>'
                );
                echo '</span>';
                
                if ($pending_count > 0) {
                    echo '<p><a href="' . esc_url(admin_url('edit.php?post_type=stock_notification&meta_key=_product_id&meta_value=' . $post->ID)) . '" class="button">' . __('View Notifications', 'aqualuxe') . '</a></p>';
                }
                ?>
            </p>
        </div>
    </div>
    <?php
}
add_action('woocommerce_product_data_panels', 'aqualuxe_stock_notification_product_panel');

/**
 * Save stock notification settings.
 *
 * @param int $post_id Product ID.
 */
function aqualuxe_save_stock_notification_settings($post_id) {
    $enable_stock_notifications = isset($_POST['_enable_stock_notifications']) ? 'yes' : 'no';
    update_post_meta($post_id, '_enable_stock_notifications', $enable_stock_notifications);
}
add_action('woocommerce_process_product_meta', 'aqualuxe_save_stock_notification_settings');

/**
 * Add stock notification bulk actions.
 *
 * @param array $bulk_actions Bulk actions.
 * @return array
 */
function aqualuxe_stock_notification_bulk_actions($bulk_actions) {
    $bulk_actions['mark_notified'] = __('Mark as Notified', 'aqualuxe');
    $bulk_actions['mark_not_notified'] = __('Mark as Not Notified', 'aqualuxe');
    return $bulk_actions;
}
add_filter('bulk_actions-edit-stock_notification', 'aqualuxe_stock_notification_bulk_actions');

/**
 * Handle stock notification bulk actions.
 *
 * @param string $redirect_to Redirect URL.
 * @param string $action Action.
 * @param array $post_ids Post IDs.
 * @return string
 */
function aqualuxe_handle_stock_notification_bulk_actions($redirect_to, $action, $post_ids) {
    if ($action !== 'mark_notified' && $action !== 'mark_not_notified') {
        return $redirect_to;
    }

    $updated = 0;

    foreach ($post_ids as $post_id) {
        if ($action === 'mark_notified') {
            update_post_meta($post_id, '_notified', '1');
            update_post_meta($post_id, '_date_notified', current_time('mysql'));
            $updated++;
        } elseif ($action === 'mark_not_notified') {
            update_post_meta($post_id, '_notified', '');
            $updated++;
        }
    }

    $redirect_to = add_query_arg('bulk_updated', $updated, $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-stock_notification', 'aqualuxe_handle_stock_notification_bulk_actions', 10, 3);

/**
 * Display admin notice after bulk action.
 */
function aqualuxe_stock_notification_bulk_action_admin_notice() {
    if (!empty($_REQUEST['bulk_updated'])) {
        $updated_count = intval($_REQUEST['bulk_updated']);
        
        printf(
            '<div class="updated notice is-dismissible"><p>' .
            _n(
                '%s stock notification updated.',
                '%s stock notifications updated.',
                $updated_count,
                'aqualuxe'
            ) . '</p></div>',
            $updated_count
        );
    }
}
add_action('admin_notices', 'aqualuxe_stock_notification_bulk_action_admin_notice');

/**
 * Add stock notification dashboard widget.
 */
function aqualuxe_stock_notification_dashboard_widget() {
    wp_add_dashboard_widget(
        'aqualuxe_stock_notification_widget',
        __('Stock Notifications', 'aqualuxe'),
        'aqualuxe_stock_notification_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'aqualuxe_stock_notification_dashboard_widget');

/**
 * Render stock notification dashboard widget content.
 */
function aqualuxe_stock_notification_dashboard_widget_content() {
    // Get pending notifications
    $pending_notifications = get_posts(array(
        'post_type' => 'stock_notification',
        'meta_query' => array(
            array(
                'key' => '_notified',
                'value' => '1',
                'compare' => '!=',
            ),
        ),
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    // Get total counts
    $pending_count = wp_count_posts('stock_notification')->publish;
    $notified_count = 0;

    $notifications = get_posts(array(
        'post_type' => 'stock_notification',
        'meta_query' => array(
            array(
                'key' => '_notified',
                'value' => '1',
                'compare' => '=',
            ),
        ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    ));

    $notified_count = count($notifications);
    $pending_count -= $notified_count;

    ?>
    <div class="stock-notification-widget">
        <div class="stock-notification-stats">
            <div class="stat-item">
                <span class="stat-value"><?php echo esc_html($pending_count); ?></span>
                <span class="stat-label"><?php _e('Pending', 'aqualuxe'); ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-value"><?php echo esc_html($notified_count); ?></span>
                <span class="stat-label"><?php _e('Notified', 'aqualuxe'); ?></span>
            </div>
        </div>

        <?php if (!empty($pending_notifications)) : ?>
            <h4><?php _e('Recent Pending Notifications', 'aqualuxe'); ?></h4>
            <ul class="stock-notification-list">
                <?php foreach ($pending_notifications as $notification) : ?>
                    <?php
                    $product_id = get_post_meta($notification->ID, '_product_id', true);
                    $email = get_post_meta($notification->ID, '_email', true);
                    $date_registered = get_post_meta($notification->ID, '_date_registered', true);
                    $date_registered_formatted = !empty($date_registered) ? date_i18n(get_option('date_format'), strtotime($date_registered)) : '';
                    
                    $product = wc_get_product($product_id);
                    $product_name = $product ? $product->get_name() : __('Product not found', 'aqualuxe');
                    ?>
                    <li>
                        <a href="<?php echo esc_url(get_edit_post_link($notification->ID)); ?>">
                            <span class="product-name"><?php echo esc_html($product_name); ?></span>
                            <span class="notification-email"><?php echo esc_html($email); ?></span>
                            <span class="notification-date"><?php echo esc_html($date_registered_formatted); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <p class="stock-notification-more">
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=stock_notification')); ?>"><?php _e('View All', 'aqualuxe'); ?></a>
            </p>
        <?php else : ?>
            <p><?php _e('No pending stock notifications.', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </div>

    <style>
        .stock-notification-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stat-item {
            padding: 10px;
        }
        
        .stat-value {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #0073aa;
        }
        
        .stat-label {
            display: block;
            font-size: 12px;
            color: #666;
        }
        
        .stock-notification-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .stock-notification-list li {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .stock-notification-list li:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .stock-notification-list a {
            display: block;
            text-decoration: none;
        }
        
        .product-name {
            display: block;
            font-weight: bold;
        }
        
        .notification-email {
            display: block;
            font-size: 12px;
            color: #666;
        }
        
        .notification-date {
            display: block;
            font-size: 11px;
            color: #999;
        }
        
        .stock-notification-more {
            margin-top: 15px;
            text-align: right;
        }
    </style>
    <?php
}

/**
 * Add stock notification menu icon.
 */
function aqualuxe_stock_notification_menu_icon() {
    ?>
    <style>
        #adminmenu .menu-icon-stock_notification div.wp-menu-image:before {
            content: '\f316';
        }
    </style>
    <?php
}
add_action('admin_head', 'aqualuxe_stock_notification_menu_icon');

/**
 * Add stock notification count to admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar object.
 */
function aqualuxe_stock_notification_admin_bar($wp_admin_bar) {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }

    // Get pending notifications count
    $pending_count = 0;
    
    $notifications = get_posts(array(
        'post_type' => 'stock_notification',
        'meta_query' => array(
            array(
                'key' => '_notified',
                'value' => '1',
                'compare' => '!=',
            ),
        ),
        'posts_per_page' => -1,
        'fields' => 'ids',
    ));
    
    $pending_count = count($notifications);
    
    if ($pending_count > 0) {
        $wp_admin_bar->add_node(array(
            'id' => 'stock-notifications',
            'title' => sprintf(
                __('Stock Notifications %s', 'aqualuxe'),
                '<span class="ab-icon"></span><span class="ab-label">' . number_format_i18n($pending_count) . '</span>'
            ),
            'href' => admin_url('edit.php?post_type=stock_notification'),
            'meta' => array(
                'title' => __('Stock Notifications', 'aqualuxe'),
            ),
        ));
    }
}
add_action('admin_bar_menu', 'aqualuxe_stock_notification_admin_bar', 90);

/**
 * Add stock notification admin bar icon.
 */
function aqualuxe_stock_notification_admin_bar_icon() {
    ?>
    <style>
        #wpadminbar #wp-admin-bar-stock-notifications .ab-icon:before {
            content: '\f316';
            top: 2px;
        }
        
        #wpadminbar #wp-admin-bar-stock-notifications .ab-label {
            display: inline-block;
            background-color: #d54e21;
            color: #fff;
            font-size: 11px;
            line-height: 1.2;
            padding: 1px 5px;
            border-radius: 10px;
            margin-left: 5px;
        }
    </style>
    <?php
}
add_action('admin_head', 'aqualuxe_stock_notification_admin_bar_icon');
add_action('wp_head', 'aqualuxe_stock_notification_admin_bar_icon');

/**
 * Add stock notification settings to WooCommerce settings.
 *
 * @param array $settings WooCommerce settings.
 * @return array
 */
function aqualuxe_stock_notification_settings($settings) {
    $settings[] = array(
        'title' => __('Stock Notifications', 'aqualuxe'),
        'type' => 'title',
        'desc' => __('Settings for stock notifications.', 'aqualuxe'),
        'id' => 'stock_notification_settings',
    );
    
    $settings[] = array(
        'title' => __('Enable Stock Notifications', 'aqualuxe'),
        'desc' => __('Enable stock notifications for all products by default.', 'aqualuxe'),
        'id' => 'aqualuxe_enable_stock_notifications',
        'default' => 'yes',
        'type' => 'checkbox',
    );
    
    $settings[] = array(
        'title' => __('Notification Form Title', 'aqualuxe'),
        'desc' => __('Title for the stock notification form.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_title',
        'default' => __('Get notified when this product is back in stock', 'aqualuxe'),
        'type' => 'text',
    );
    
    $settings[] = array(
        'title' => __('Notification Form Description', 'aqualuxe'),
        'desc' => __('Description for the stock notification form.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_description',
        'default' => __('Enter your email address below to be notified when this item is back in stock.', 'aqualuxe'),
        'type' => 'textarea',
    );
    
    $settings[] = array(
        'title' => __('Require Name', 'aqualuxe'),
        'desc' => __('Require name field in the stock notification form.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_require_name',
        'default' => 'no',
        'type' => 'checkbox',
    );
    
    $settings[] = array(
        'title' => __('Show Phone Field', 'aqualuxe'),
        'desc' => __('Show phone field in the stock notification form.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_show_phone',
        'default' => 'yes',
        'type' => 'checkbox',
    );
    
    $settings[] = array(
        'title' => __('Require Phone', 'aqualuxe'),
        'desc' => __('Require phone field in the stock notification form.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_require_phone',
        'default' => 'no',
        'type' => 'checkbox',
    );
    
    $settings[] = array(
        'title' => __('Button Text', 'aqualuxe'),
        'desc' => __('Text for the notification form submit button.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_button_text',
        'default' => __('Notify Me', 'aqualuxe'),
        'type' => 'text',
    );
    
    $settings[] = array(
        'title' => __('Success Message', 'aqualuxe'),
        'desc' => __('Message displayed after successful form submission.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_success_message',
        'default' => __('Thank you! You will be notified when this product is back in stock.', 'aqualuxe'),
        'type' => 'textarea',
    );
    
    $settings[] = array(
        'title' => __('Admin Email Notification', 'aqualuxe'),
        'desc' => __('Send email notification to admin when a new stock notification request is submitted.', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_admin_email',
        'default' => 'yes',
        'type' => 'checkbox',
    );
    
    $settings[] = array(
        'title' => __('Admin Email Recipients', 'aqualuxe'),
        'desc' => __('Email addresses to receive admin notifications (comma separated).', 'aqualuxe'),
        'id' => 'aqualuxe_stock_notification_admin_recipients',
        'default' => get_option('admin_email'),
        'type' => 'text',
    );
    
    $settings[] = array(
        'type' => 'sectionend',
        'id' => 'stock_notification_settings',
    );
    
    return $settings;
}
add_filter('woocommerce_inventory_settings', 'aqualuxe_stock_notification_settings');

/**
 * Send admin notification when a new stock notification request is submitted.
 *
 * @param int $notification_id Notification ID.
 */
function aqualuxe_send_admin_stock_notification($notification_id) {
    // Check if admin notifications are enabled
    if (get_option('aqualuxe_stock_notification_admin_email') !== 'yes') {
        return;
    }
    
    $product_id = get_post_meta($notification_id, '_product_id', true);
    $email = get_post_meta($notification_id, '_email', true);
    $name = get_post_meta($notification_id, '_name', true);
    $phone = get_post_meta($notification_id, '_phone', true);
    
    $product = wc_get_product($product_id);
    if (!$product || empty($email)) {
        return;
    }
    
    $product_name = $product->get_name();
    $product_url = get_permalink($product_id);
    
    // Get admin recipients
    $recipients = get_option('aqualuxe_stock_notification_admin_recipients', get_option('admin_email'));
    $recipients = array_map('trim', explode(',', $recipients));
    
    $subject = sprintf(__('New Stock Notification Request for %s', 'aqualuxe'), $product_name);
    
    // HTML email
    $html_message = '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">';
    $html_message .= '<h2 style="color: #0073aa;">' . __('New Stock Notification Request', 'aqualuxe') . '</h2>';
    $html_message .= '<p>' . __('A new stock notification request has been submitted.', 'aqualuxe') . '</p>';
    $html_message .= '<div style="margin: 20px 0; padding: 15px; background-color: #f8f8f8; border-left: 4px solid #0073aa;">';
    $html_message .= '<p><strong>' . __('Product:', 'aqualuxe') . '</strong> ' . esc_html($product_name) . '</p>';
    $html_message .= '<p><strong>' . __('Email:', 'aqualuxe') . '</strong> ' . esc_html($email) . '</p>';
    
    if (!empty($name)) {
        $html_message .= '<p><strong>' . __('Name:', 'aqualuxe') . '</strong> ' . esc_html($name) . '</p>';
    }
    
    if (!empty($phone)) {
        $html_message .= '<p><strong>' . __('Phone:', 'aqualuxe') . '</strong> ' . esc_html($phone) . '</p>';
    }
    
    $html_message .= '<p><a href="' . esc_url($product_url) . '" style="color: #0073aa;">' . __('View Product', 'aqualuxe') . '</a></p>';
    $html_message .= '<p><a href="' . esc_url(get_edit_post_link($notification_id)) . '" style="color: #0073aa;">' . __('View Notification', 'aqualuxe') . '</a></p>';
    $html_message .= '</div>';
    $html_message .= '</div>';
    
    // Send email
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
    );
    
    foreach ($recipients as $recipient) {
        wp_mail($recipient, $subject, $html_message, $headers);
    }
}
add_action('aqualuxe_stock_notification_created', 'aqualuxe_send_admin_stock_notification');

/**
 * Trigger admin notification when a new stock notification is created.
 *
 * @param int $notification_id Notification ID.
 */
function aqualuxe_trigger_admin_notification($notification_id) {
    do_action('aqualuxe_stock_notification_created', $notification_id);
}
add_action('wp_insert_post', function($post_id, $post) {
    if ($post->post_type === 'stock_notification' && $post->post_status === 'publish') {
        aqualuxe_trigger_admin_notification($post_id);
    }
}, 10, 2);

/**
 * Add stock notification export functionality.
 */
function aqualuxe_stock_notification_export() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'stock_notification_export') {
        return;
    }
    
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
    }
    
    // Process export
    if (isset($_POST['export_stock_notifications']) && isset($_POST['export_nonce']) && wp_verify_nonce($_POST['export_nonce'], 'stock_notification_export')) {
        $status = isset($_POST['notification_status']) ? sanitize_text_field($_POST['notification_status']) : 'all';
        $format = isset($_POST['export_format']) ? sanitize_text_field($_POST['export_format']) : 'csv';
        
        // Build query
        $args = array(
            'post_type' => 'stock_notification',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        // Filter by status
        if ($status === 'notified') {
            $args['meta_query'] = array(
                array(
                    'key' => '_notified',
                    'value' => '1',
                    'compare' => '=',
                ),
            );
        } elseif ($status === 'pending') {
            $args['meta_query'] = array(
                array(
                    'key' => '_notified',
                    'value' => '1',
                    'compare' => '!=',
                ),
            );
        }
        
        $notifications = get_posts($args);
        
        if (empty($notifications)) {
            wp_redirect(add_query_arg('message', 'no-data', admin_url('edit.php?post_type=stock_notification&page=stock_notification_export')));
            exit;
        }
        
        // Prepare data
        $data = array();
        
        // Add header row
        $data[] = array(
            __('ID', 'aqualuxe'),
            __('Product ID', 'aqualuxe'),
            __('Product Name', 'aqualuxe'),
            __('Email', 'aqualuxe'),
            __('Name', 'aqualuxe'),
            __('Phone', 'aqualuxe'),
            __('Date Registered', 'aqualuxe'),
            __('Status', 'aqualuxe'),
            __('Date Notified', 'aqualuxe'),
        );
        
        // Add data rows
        foreach ($notifications as $notification) {
            $product_id = get_post_meta($notification->ID, '_product_id', true);
            $email = get_post_meta($notification->ID, '_email', true);
            $name = get_post_meta($notification->ID, '_name', true);
            $phone = get_post_meta($notification->ID, '_phone', true);
            $date_registered = get_post_meta($notification->ID, '_date_registered', true);
            $notified = get_post_meta($notification->ID, '_notified', true);
            $date_notified = get_post_meta($notification->ID, '_date_notified', true);
            
            $product = wc_get_product($product_id);
            $product_name = $product ? $product->get_name() : __('Product not found', 'aqualuxe');
            
            $data[] = array(
                $notification->ID,
                $product_id,
                $product_name,
                $email,
                $name,
                $phone,
                $date_registered,
                $notified === '1' ? __('Notified', 'aqualuxe') : __('Pending', 'aqualuxe'),
                $date_notified,
            );
        }
        
        // Generate file
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="stock-notifications-' . date('Y-m-d') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $output = fopen('php://output', 'w');
            
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            
            fclose($output);
            exit;
        } elseif ($format === 'json') {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="stock-notifications-' . date('Y-m-d') . '.json"');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            $json_data = array();
            
            // Skip header row
            for ($i = 1; $i < count($data); $i++) {
                $json_data[] = array(
                    'id' => $data[$i][0],
                    'product_id' => $data[$i][1],
                    'product_name' => $data[$i][2],
                    'email' => $data[$i][3],
                    'name' => $data[$i][4],
                    'phone' => $data[$i][5],
                    'date_registered' => $data[$i][6],
                    'status' => $data[$i][7],
                    'date_notified' => $data[$i][8],
                );
            }
            
            echo json_encode($json_data);
            exit;
        }
    }
    
    // Display export page
    add_action('admin_menu', function() {
        add_submenu_page(
            null,
            __('Export Stock Notifications', 'aqualuxe'),
            __('Export', 'aqualuxe'),
            'manage_woocommerce',
            'stock_notification_export',
            'aqualuxe_stock_notification_export_page'
        );
    });
}
add_action('admin_init', 'aqualuxe_stock_notification_export');

/**
 * Render stock notification export page.
 */
function aqualuxe_stock_notification_export_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Export Stock Notifications', 'aqualuxe'); ?></h1>
        
        <?php if (isset($_GET['message']) && $_GET['message'] === 'no-data') : ?>
            <div class="notice notice-error">
                <p><?php _e('No notifications found matching your criteria.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2><?php _e('Export Options', 'aqualuxe'); ?></h2>
            
            <form method="post" action="">
                <?php wp_nonce_field('stock_notification_export', 'export_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Notification Status', 'aqualuxe'); ?></th>
                        <td>
                            <select name="notification_status">
                                <option value="all"><?php _e('All Notifications', 'aqualuxe'); ?></option>
                                <option value="pending"><?php _e('Pending Notifications', 'aqualuxe'); ?></option>
                                <option value="notified"><?php _e('Notified Notifications', 'aqualuxe'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Export Format', 'aqualuxe'); ?></th>
                        <td>
                            <select name="export_format">
                                <option value="csv"><?php _e('CSV', 'aqualuxe'); ?></option>
                                <option value="json"><?php _e('JSON', 'aqualuxe'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="export_stock_notifications" class="button button-primary" value="<?php _e('Export', 'aqualuxe'); ?>">
                </p>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Add export link to stock notification list table.
 *
 * @param array $actions Bulk actions.
 * @return array
 */
function aqualuxe_stock_notification_export_link($actions) {
    $actions['export'] = '<a href="' . esc_url(admin_url('edit.php?post_type=stock_notification&page=stock_notification_export')) . '">' . __('Export', 'aqualuxe') . '</a>';
    return $actions;
}
add_filter('bulk_actions-edit-stock_notification', 'aqualuxe_stock_notification_export_link');

/**
 * Add stock notification import functionality.
 */
function aqualuxe_stock_notification_import() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'stock_notification_import') {
        return;
    }
    
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
    }
    
    // Process import
    if (isset($_POST['import_stock_notifications']) && isset($_POST['import_nonce']) && wp_verify_nonce($_POST['import_nonce'], 'stock_notification_import')) {
        if (!isset($_FILES['import_file']) || empty($_FILES['import_file']['tmp_name'])) {
            wp_redirect(add_query_arg('message', 'no-file', admin_url('edit.php?post_type=stock_notification&page=stock_notification_import')));
            exit;
        }
        
        $file = $_FILES['import_file']['tmp_name'];
        $file_type = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
        
        if ($file_type !== 'csv' && $file_type !== 'json') {
            wp_redirect(add_query_arg('message', 'invalid-format', admin_url('edit.php?post_type=stock_notification&page=stock_notification_import')));
            exit;
        }
        
        $imported = 0;
        $skipped = 0;
        
        if ($file_type === 'csv') {
            $handle = fopen($file, 'r');
            
            // Skip header row
            fgetcsv($handle);
            
            while (($data = fgetcsv($handle)) !== false) {
                $product_id = isset($data[1]) ? absint($data[1]) : 0;
                $email = isset($data[3]) ? sanitize_email($data[3]) : '';
                $name = isset($data[4]) ? sanitize_text_field($data[4]) : '';
                $phone = isset($data[5]) ? sanitize_text_field($data[5]) : '';
                $date_registered = isset($data[6]) ? sanitize_text_field($data[6]) : '';
                $status = isset($data[7]) ? sanitize_text_field($data[7]) : '';
                $date_notified = isset($data[8]) ? sanitize_text_field($data[8]) : '';
                
                // Validate data
                if (empty($product_id) || empty($email) || !is_email($email)) {
                    $skipped++;
                    continue;
                }
                
                // Check if product exists
                $product = wc_get_product($product_id);
                if (!$product) {
                    $skipped++;
                    continue;
                }
                
                // Check if notification already exists
                $existing_notifications = get_posts(array(
                    'post_type' => 'stock_notification',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_product_id',
                            'value' => $product_id,
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_email',
                            'value' => $email,
                            'compare' => '=',
                        ),
                    ),
                    'posts_per_page' => 1,
                ));
                
                if (!empty($existing_notifications)) {
                    $skipped++;
                    continue;
                }
                
                // Create notification
                $notification_id = wp_insert_post(array(
                    'post_title' => 'SN-' . $product_id . '-' . substr(md5($email), 0, 8),
                    'post_type' => 'stock_notification',
                    'post_status' => 'publish',
                ));
                
                if (is_wp_error($notification_id)) {
                    $skipped++;
                    continue;
                }
                
                // Save notification meta
                update_post_meta($notification_id, '_product_id', $product_id);
                update_post_meta($notification_id, '_email', $email);
                update_post_meta($notification_id, '_name', $name);
                update_post_meta($notification_id, '_phone', $phone);
                update_post_meta($notification_id, '_date_registered', !empty($date_registered) ? $date_registered : current_time('mysql'));
                update_post_meta($notification_id, '_notified', $status === __('Notified', 'aqualuxe') ? '1' : '');
                
                if (!empty($date_notified) && $status === __('Notified', 'aqualuxe')) {
                    update_post_meta($notification_id, '_date_notified', $date_notified);
                }
                
                $imported++;
            }
            
            fclose($handle);
        } elseif ($file_type === 'json') {
            $json_data = json_decode(file_get_contents($file), true);
            
            if (!is_array($json_data)) {
                wp_redirect(add_query_arg('message', 'invalid-json', admin_url('edit.php?post_type=stock_notification&page=stock_notification_import')));
                exit;
            }
            
            foreach ($json_data as $item) {
                $product_id = isset($item['product_id']) ? absint($item['product_id']) : 0;
                $email = isset($item['email']) ? sanitize_email($item['email']) : '';
                $name = isset($item['name']) ? sanitize_text_field($item['name']) : '';
                $phone = isset($item['phone']) ? sanitize_text_field($item['phone']) : '';
                $date_registered = isset($item['date_registered']) ? sanitize_text_field($item['date_registered']) : '';
                $status = isset($item['status']) ? sanitize_text_field($item['status']) : '';
                $date_notified = isset($item['date_notified']) ? sanitize_text_field($item['date_notified']) : '';
                
                // Validate data
                if (empty($product_id) || empty($email) || !is_email($email)) {
                    $skipped++;
                    continue;
                }
                
                // Check if product exists
                $product = wc_get_product($product_id);
                if (!$product) {
                    $skipped++;
                    continue;
                }
                
                // Check if notification already exists
                $existing_notifications = get_posts(array(
                    'post_type' => 'stock_notification',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => '_product_id',
                            'value' => $product_id,
                            'compare' => '=',
                        ),
                        array(
                            'key' => '_email',
                            'value' => $email,
                            'compare' => '=',
                        ),
                    ),
                    'posts_per_page' => 1,
                ));
                
                if (!empty($existing_notifications)) {
                    $skipped++;
                    continue;
                }
                
                // Create notification
                $notification_id = wp_insert_post(array(
                    'post_title' => 'SN-' . $product_id . '-' . substr(md5($email), 0, 8),
                    'post_type' => 'stock_notification',
                    'post_status' => 'publish',
                ));
                
                if (is_wp_error($notification_id)) {
                    $skipped++;
                    continue;
                }
                
                // Save notification meta
                update_post_meta($notification_id, '_product_id', $product_id);
                update_post_meta($notification_id, '_email', $email);
                update_post_meta($notification_id, '_name', $name);
                update_post_meta($notification_id, '_phone', $phone);
                update_post_meta($notification_id, '_date_registered', !empty($date_registered) ? $date_registered : current_time('mysql'));
                update_post_meta($notification_id, '_notified', $status === __('Notified', 'aqualuxe') ? '1' : '');
                
                if (!empty($date_notified) && $status === __('Notified', 'aqualuxe')) {
                    update_post_meta($notification_id, '_date_notified', $date_notified);
                }
                
                $imported++;
            }
        }
        
        wp_redirect(add_query_arg(array(
            'message' => 'imported',
            'imported' => $imported,
            'skipped' => $skipped,
        ), admin_url('edit.php?post_type=stock_notification&page=stock_notification_import')));
        exit;
    }
    
    // Display import page
    add_action('admin_menu', function() {
        add_submenu_page(
            null,
            __('Import Stock Notifications', 'aqualuxe'),
            __('Import', 'aqualuxe'),
            'manage_woocommerce',
            'stock_notification_import',
            'aqualuxe_stock_notification_import_page'
        );
    });
}
add_action('admin_init', 'aqualuxe_stock_notification_import');

/**
 * Render stock notification import page.
 */
function aqualuxe_stock_notification_import_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Import Stock Notifications', 'aqualuxe'); ?></h1>
        
        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] === 'no-file') : ?>
                <div class="notice notice-error">
                    <p><?php _e('Please select a file to import.', 'aqualuxe'); ?></p>
                </div>
            <?php elseif ($_GET['message'] === 'invalid-format') : ?>
                <div class="notice notice-error">
                    <p><?php _e('Invalid file format. Please upload a CSV or JSON file.', 'aqualuxe'); ?></p>
                </div>
            <?php elseif ($_GET['message'] === 'invalid-json') : ?>
                <div class="notice notice-error">
                    <p><?php _e('Invalid JSON file. Please check the file format.', 'aqualuxe'); ?></p>
                </div>
            <?php elseif ($_GET['message'] === 'imported') : ?>
                <div class="notice notice-success">
                    <p>
                        <?php
                        $imported = isset($_GET['imported']) ? absint($_GET['imported']) : 0;
                        $skipped = isset($_GET['skipped']) ? absint($_GET['skipped']) : 0;
                        
                        printf(
                            __('Import completed. %1$s notifications imported, %2$s skipped.', 'aqualuxe'),
                            $imported,
                            $skipped
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="card">
            <h2><?php _e('Import Options', 'aqualuxe'); ?></h2>
            
            <form method="post" action="" enctype="multipart/form-data">
                <?php wp_nonce_field('stock_notification_import', 'import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Import File', 'aqualuxe'); ?></th>
                        <td>
                            <input type="file" name="import_file" accept=".csv,.json">
                            <p class="description"><?php _e('Upload a CSV or JSON file to import stock notifications.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="import_stock_notifications" class="button button-primary" value="<?php _e('Import', 'aqualuxe'); ?>">
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2><?php _e('Import Format', 'aqualuxe'); ?></h2>
            
            <p><?php _e('The import file should have the following columns:', 'aqualuxe'); ?></p>
            
            <ul>
                <li><strong><?php _e('ID', 'aqualuxe'); ?></strong> - <?php _e('Notification ID (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Product ID', 'aqualuxe'); ?></strong> - <?php _e('Product ID (required)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Product Name', 'aqualuxe'); ?></strong> - <?php _e('Product Name (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Email', 'aqualuxe'); ?></strong> - <?php _e('Email address (required)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Name', 'aqualuxe'); ?></strong> - <?php _e('Customer name (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Phone', 'aqualuxe'); ?></strong> - <?php _e('Customer phone (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Date Registered', 'aqualuxe'); ?></strong> - <?php _e('Date registered (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Status', 'aqualuxe'); ?></strong> - <?php _e('Notification status (optional)', 'aqualuxe'); ?></li>
                <li><strong><?php _e('Date Notified', 'aqualuxe'); ?></strong> - <?php _e('Date notified (optional)', 'aqualuxe'); ?></li>
            </ul>
            
            <p><?php _e('You can download a sample import file from the export page.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Add import link to stock notification list table.
 *
 * @param array $actions Bulk actions.
 * @return array
 */
function aqualuxe_stock_notification_import_link($actions) {
    $actions['import'] = '<a href="' . esc_url(admin_url('edit.php?post_type=stock_notification&page=stock_notification_import')) . '">' . __('Import', 'aqualuxe') . '</a>';
    return $actions;
}
add_filter('bulk_actions-edit-stock_notification', 'aqualuxe_stock_notification_import_link');

/**
 * Add stock notification submenu.
 */
function aqualuxe_stock_notification_submenu() {
    add_submenu_page(
        'edit.php?post_type=stock_notification',
        __('Export Stock Notifications', 'aqualuxe'),
        __('Export', 'aqualuxe'),
        'manage_woocommerce',
        'stock_notification_export',
        'aqualuxe_stock_notification_export_page'
    );
    
    add_submenu_page(
        'edit.php?post_type=stock_notification',
        __('Import Stock Notifications', 'aqualuxe'),
        __('Import', 'aqualuxe'),
        'manage_woocommerce',
        'stock_notification_import',
        'aqualuxe_stock_notification_import_page'
    );
}
add_action('admin_menu', 'aqualuxe_stock_notification_submenu');