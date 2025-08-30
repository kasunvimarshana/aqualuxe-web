<?php

/**
 * Trade-in functionality for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize trade-in functionality
 */
function aqualuxe_trade_in_init()
{
    // Register trade-in post type if not already registered
    if (! post_type_exists('aqualuxe_trade_in')) {
        // The post type is likely registered elsewhere, but we'll add a safety check
        // This is just a placeholder function to prevent fatal errors
    }
}
add_action('init', 'aqualuxe_trade_in_init');

/**
 * Get trade-in status options
 *
 * @return array Array of trade-in status options
 */
function aqualuxe_get_trade_in_statuses()
{
    return apply_filters('aqualuxe_trade_in_statuses', array(
        'pending'    => __('Pending Review', 'aqualuxe'),
        'approved'   => __('Approved', 'aqualuxe'),
        'rejected'   => __('Rejected', 'aqualuxe'),
        'completed'  => __('Completed', 'aqualuxe'),
        'cancelled'  => __('Cancelled', 'aqualuxe'),
    ));
}

/**
 * Get trade-in status label
 *
 * @param string $status Trade-in status key
 * @return string Trade-in status label
 */
function aqualuxe_get_trade_in_status_label($status)
{
    $statuses = aqualuxe_get_trade_in_statuses();
    return isset($statuses[$status]) ? $statuses[$status] : __('Unknown', 'aqualuxe');
}

/**
 * Get trade-in details
 *
 * @param int $trade_in_id Trade-in ID
 * @return array|false Trade-in details or false if not found
 */
function aqualuxe_get_trade_in($trade_in_id)
{
    $trade_in = get_post($trade_in_id);

    if (! $trade_in || 'aqualuxe_trade_in' !== $trade_in->post_type) {
        return false;
    }

    return array(
        'id'            => $trade_in->ID,
        'title'         => $trade_in->post_title,
        'description'   => $trade_in->post_content,
        'status'        => get_post_meta($trade_in->ID, '_trade_in_status', true),
        'customer_name' => get_post_meta($trade_in->ID, '_trade_in_customer_name', true),
        'customer_email' => get_post_meta($trade_in->ID, '_trade_in_customer_email', true),
        'customer_phone' => get_post_meta($trade_in->ID, '_trade_in_customer_phone', true),
        'product_name'  => get_post_meta($trade_in->ID, '_trade_in_product_name', true),
        'product_model' => get_post_meta($trade_in->ID, '_trade_in_product_model', true),
        'product_year'  => get_post_meta($trade_in->ID, '_trade_in_product_year', true),
        'product_condition' => get_post_meta($trade_in->ID, '_trade_in_product_condition', true),
        'offered_value' => get_post_meta($trade_in->ID, '_trade_in_offered_value', true),
        'accepted_value' => get_post_meta($trade_in->ID, '_trade_in_accepted_value', true),
        'date_submitted' => $trade_in->post_date,
        'date_modified' => $trade_in->post_modified,
        'permalink'     => get_permalink($trade_in->ID),
        'images'        => aqualuxe_get_trade_in_images($trade_in->ID),
    );
}

/**
 * Get trade-in images
 *
 * @param int $trade_in_id Trade-in ID
 * @return array Array of image URLs
 */
function aqualuxe_get_trade_in_images($trade_in_id)
{
    $images = array();

    // Get featured image
    if (has_post_thumbnail($trade_in_id)) {
        $images[] = get_the_post_thumbnail_url($trade_in_id, 'large');
    }

    // Get additional images
    $additional_images = get_post_meta($trade_in_id, '_trade_in_additional_images', true);

    if ($additional_images && is_array($additional_images)) {
        foreach ($additional_images as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'large');
            if ($image_url) {
                $images[] = $image_url;
            }
        }
    }

    return $images;
}

/**
 * Get trade-ins
 *
 * @param array $args Query arguments
 * @return array Array of trade-ins
 */
function aqualuxe_get_trade_ins($args = array())
{
    $defaults = array(
        'status'    => '',
        'limit'     => 10,
        'offset'    => 0,
        'orderby'   => 'date',
        'order'     => 'DESC',
    );

    $args = wp_parse_args($args, $defaults);

    $query_args = array(
        'post_type'      => 'aqualuxe_trade_in',
        'posts_per_page' => $args['limit'],
        'offset'         => $args['offset'],
        'orderby'        => $args['orderby'],
        'order'          => $args['order'],
    );

    // Add meta query for status
    if (! empty($args['status'])) {
        $query_args['meta_query'][] = array(
            'key'     => '_trade_in_status',
            'value'   => $args['status'],
            'compare' => '=',
        );
    }

    $trade_ins_query = new WP_Query($query_args);
    $trade_ins = array();

    if ($trade_ins_query->have_posts()) {
        while ($trade_ins_query->have_posts()) {
            $trade_ins_query->the_post();
            $trade_ins[] = aqualuxe_get_trade_in(get_the_ID());
        }
        wp_reset_postdata();
    }

    return $trade_ins;
}

/**
 * Create a new trade-in
 *
 * @param array $trade_in_data Trade-in data
 * @return int|WP_Error Trade-in ID on success, WP_Error on failure
 */
function aqualuxe_create_trade_in($trade_in_data)
{
    // Required fields
    $required_fields = array('customer_name', 'customer_email', 'product_name');

    foreach ($required_fields as $field) {
        if (empty($trade_in_data[$field])) {
            return new WP_Error('missing_field', sprintf(__('Missing required field: %s', 'aqualuxe'), $field));
        }
    }

    // Create post
    $post_data = array(
        'post_title'    => sprintf(__('Trade-in: %s - %s', 'aqualuxe'), $trade_in_data['customer_name'], $trade_in_data['product_name']),
        'post_content'  => isset($trade_in_data['description']) ? $trade_in_data['description'] : '',
        'post_status'   => 'publish',
        'post_type'     => 'aqualuxe_trade_in',
        'meta_input'    => array(
            '_trade_in_status'           => 'pending',
            '_trade_in_customer_name'    => sanitize_text_field($trade_in_data['customer_name']),
            '_trade_in_customer_email'   => sanitize_email($trade_in_data['customer_email']),
            '_trade_in_customer_phone'   => isset($trade_in_data['customer_phone']) ? sanitize_text_field($trade_in_data['customer_phone']) : '',
            '_trade_in_product_name'     => sanitize_text_field($trade_in_data['product_name']),
            '_trade_in_product_model'    => isset($trade_in_data['product_model']) ? sanitize_text_field($trade_in_data['product_model']) : '',
            '_trade_in_product_year'     => isset($trade_in_data['product_year']) ? sanitize_text_field($trade_in_data['product_year']) : '',
            '_trade_in_product_condition' => isset($trade_in_data['product_condition']) ? sanitize_text_field($trade_in_data['product_condition']) : '',
        ),
    );

    // Insert post
    $trade_in_id = wp_insert_post($post_data);

    if (is_wp_error($trade_in_id)) {
        return $trade_in_id;
    }

    // Handle featured image
    if (isset($trade_in_data['featured_image']) && ! empty($trade_in_data['featured_image'])) {
        set_post_thumbnail($trade_in_id, $trade_in_data['featured_image']);
    }

    // Handle additional images
    if (isset($trade_in_data['additional_images']) && is_array($trade_in_data['additional_images'])) {
        update_post_meta($trade_in_id, '_trade_in_additional_images', $trade_in_data['additional_images']);
    }

    // Send notification emails
    aqualuxe_send_trade_in_notification_emails($trade_in_id);

    return $trade_in_id;
}

/**
 * Send trade-in notification emails
 *
 * @param int $trade_in_id Trade-in ID
 */
function aqualuxe_send_trade_in_notification_emails($trade_in_id)
{
    // This is a placeholder function
    // In a real implementation, this would send emails to admin and customer
}

/**
 * Update trade-in status
 *
 * @param int $trade_in_id Trade-in ID
 * @param string $status New status
 * @return bool True on success, false on failure
 */
function aqualuxe_update_trade_in_status($trade_in_id, $status)
{
    // Validate status
    $valid_statuses = array_keys(aqualuxe_get_trade_in_statuses());

    if (! in_array($status, $valid_statuses)) {
        return false;
    }

    // Update status
    $result = update_post_meta($trade_in_id, '_trade_in_status', $status);

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_status_updated', $trade_in_id, $status);
    }

    return (bool) $result;
}

/**
 * Set trade-in offered value
 *
 * @param int $trade_in_id Trade-in ID
 * @param float $value Offered value
 * @return bool True on success, false on failure
 */
function aqualuxe_set_trade_in_offered_value($trade_in_id, $value)
{
    $result = update_post_meta($trade_in_id, '_trade_in_offered_value', floatval($value));

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_offered_value_set', $trade_in_id, $value);
    }

    return (bool) $result;
}

/**
 * Accept trade-in offer
 *
 * @param int $trade_in_id Trade-in ID
 * @param float $accepted_value Accepted value
 * @return bool True on success, false on failure
 */
function aqualuxe_accept_trade_in_offer($trade_in_id, $accepted_value = null)
{
    // If no accepted value provided, use the offered value
    if (null === $accepted_value) {
        $accepted_value = get_post_meta($trade_in_id, '_trade_in_offered_value', true);
    }

    // Update accepted value
    update_post_meta($trade_in_id, '_trade_in_accepted_value', floatval($accepted_value));

    // Update status
    $result = aqualuxe_update_trade_in_status($trade_in_id, 'approved');

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_offer_accepted', $trade_in_id, $accepted_value);
    }

    return $result;
}

/**
 * Reject trade-in offer
 *
 * @param int $trade_in_id Trade-in ID
 * @return bool True on success, false on failure
 */
function aqualuxe_reject_trade_in_offer($trade_in_id)
{
    // Update status
    $result = aqualuxe_update_trade_in_status($trade_in_id, 'rejected');

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_offer_rejected', $trade_in_id);
    }

    return $result;
}

/**
 * Complete trade-in
 *
 * @param int $trade_in_id Trade-in ID
 * @return bool True on success, false on failure
 */
function aqualuxe_complete_trade_in($trade_in_id)
{
    // Update status
    $result = aqualuxe_update_trade_in_status($trade_in_id, 'completed');

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_completed', $trade_in_id);
    }

    return $result;
}

/**
 * Cancel trade-in
 *
 * @param int $trade_in_id Trade-in ID
 * @return bool True on success, false on failure
 */
function aqualuxe_cancel_trade_in($trade_in_id)
{
    // Update status
    $result = aqualuxe_update_trade_in_status($trade_in_id, 'cancelled');

    // Trigger actions
    if ($result) {
        do_action('aqualuxe_trade_in_cancelled', $trade_in_id);
    }

    return $result;
}

/**
 * Get product condition options
 *
 * @return array Array of product condition options
 */
function aqualuxe_get_product_condition_options()
{
    return apply_filters('aqualuxe_product_condition_options', array(
        'new'       => __('New', 'aqualuxe'),
        'like-new'  => __('Like New', 'aqualuxe'),
        'excellent' => __('Excellent', 'aqualuxe'),
        'good'      => __('Good', 'aqualuxe'),
        'fair'      => __('Fair', 'aqualuxe'),
        'poor'      => __('Poor', 'aqualuxe'),
    ));
}

/**
 * Register trade-in shortcodes
 */
function aqualuxe_register_trade_in_shortcodes()
{
    add_shortcode('aqualuxe_trade_in_form', 'aqualuxe_trade_in_form_shortcode');
    add_shortcode('aqualuxe_trade_in_status', 'aqualuxe_trade_in_status_shortcode');
}
add_action('init', 'aqualuxe_register_trade_in_shortcodes');

/**
 * Trade-in form shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_trade_in_form_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'title' => __('Trade-in Your Equipment', 'aqualuxe'),
    ), $atts, 'aqualuxe_trade_in_form');

    ob_start();

    // Check if form was submitted
    if (isset($_POST['aqualuxe_trade_in_submit'])) {
        // Process form submission
        // This is just a placeholder - in a real implementation, this would validate and save the trade-in
        echo '<div class="aqualuxe-message success">' . __('Thank you for your trade-in request. We will review your submission and contact you shortly.', 'aqualuxe') . '</div>';
    } else {
        // Display form
?>
        <div class="aqualuxe-trade-in-form-container">
            <h2><?php echo esc_html($atts['title']); ?></h2>

            <form class="aqualuxe-trade-in-form" method="post" enctype="multipart/form-data">
                <h3><?php esc_html_e('Your Information', 'aqualuxe'); ?></h3>

                <div class="form-row">
                    <label for="customer_name"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" name="customer_name" id="customer_name" required>
                </div>

                <div class="form-row">
                    <label for="customer_email"><?php esc_html_e('Email Address', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" name="customer_email" id="customer_email" required>
                </div>

                <div class="form-row">
                    <label for="customer_phone"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                    <input type="tel" name="customer_phone" id="customer_phone">
                </div>

                <h3><?php esc_html_e('Product Information', 'aqualuxe'); ?></h3>

                <div class="form-row">
                    <label for="product_name"><?php esc_html_e('Product Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" name="product_name" id="product_name" required>
                </div>

                <div class="form-row">
                    <label for="product_model"><?php esc_html_e('Model Number', 'aqualuxe'); ?></label>
                    <input type="text" name="product_model" id="product_model">
                </div>

                <div class="form-row">
                    <label for="product_year"><?php esc_html_e('Year of Purchase', 'aqualuxe'); ?></label>
                    <input type="text" name="product_year" id="product_year">
                </div>

                <div class="form-row">
                    <label for="product_condition"><?php esc_html_e('Condition', 'aqualuxe'); ?></label>
                    <select name="product_condition" id="product_condition">
                        <option value=""><?php esc_html_e('Select condition', 'aqualuxe'); ?></option>
                        <?php foreach (aqualuxe_get_product_condition_options() as $value => $label) : ?>
                            <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="description"><?php esc_html_e('Description', 'aqualuxe'); ?></label>
                    <textarea name="description" id="description" rows="4"></textarea>
                </div>

                <div class="form-row">
                    <label for="featured_image"><?php esc_html_e('Main Product Image', 'aqualuxe'); ?></label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*">
                </div>

                <div class="form-row">
                    <label for="additional_images"><?php esc_html_e('Additional Images', 'aqualuxe'); ?></label>
                    <input type="file" name="additional_images[]" id="additional_images" accept="image/*" multiple>
                    <p class="description"><?php esc_html_e('You can select multiple images.', 'aqualuxe'); ?></p>
                </div>

                <div class="form-row">
                    <button type="submit" name="aqualuxe_trade_in_submit" class="aqualuxe-button"><?php esc_html_e('Submit Trade-in Request', 'aqualuxe'); ?></button>
                </div>
            </form>
        </div>
    <?php
    }

    return ob_get_clean();
}

/**
 * Trade-in status shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_trade_in_status_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'id' => 0,
    ), $atts, 'aqualuxe_trade_in_status');

    if (! $atts['id']) {
        return '<p>' . esc_html__('No trade-in ID specified.', 'aqualuxe') . '</p>';
    }

    $trade_in = aqualuxe_get_trade_in($atts['id']);

    if (! $trade_in) {
        return '<p>' . esc_html__('Trade-in not found.', 'aqualuxe') . '</p>';
    }

    ob_start();
    ?>
    <div class="aqualuxe-trade-in-status">
        <h2><?php esc_html_e('Trade-in Status', 'aqualuxe'); ?></h2>

        <div class="aqualuxe-trade-in-status-details">
            <div class="status-item">
                <span class="label"><?php esc_html_e('Status:', 'aqualuxe'); ?></span>
                <span class="value status-<?php echo esc_attr($trade_in['status']); ?>"><?php echo esc_html(aqualuxe_get_trade_in_status_label($trade_in['status'])); ?></span>
            </div>

            <div class="status-item">
                <span class="label"><?php esc_html_e('Product:', 'aqualuxe'); ?></span>
                <span class="value"><?php echo esc_html($trade_in['product_name']); ?></span>
            </div>

            <?php if ($trade_in['product_model']) : ?>
                <div class="status-item">
                    <span class="label"><?php esc_html_e('Model:', 'aqualuxe'); ?></span>
                    <span class="value"><?php echo esc_html($trade_in['product_model']); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($trade_in['offered_value'] && in_array($trade_in['status'], array('approved', 'completed'))) : ?>
                <div class="status-item">
                    <span class="label"><?php esc_html_e('Offered Value:', 'aqualuxe'); ?></span>
                    <span class="value"><?php echo esc_html('$' . number_format($trade_in['offered_value'], 2)); ?></span>
                </div>
            <?php endif; ?>

            <div class="status-item">
                <span class="label"><?php esc_html_e('Submitted:', 'aqualuxe'); ?></span>
                <span class="value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($trade_in['date_submitted']))); ?></span>
            </div>
        </div>

        <?php if ($trade_in['status'] === 'approved') : ?>
            <div class="aqualuxe-trade-in-actions">
                <p><?php esc_html_e('Your trade-in has been approved! Please contact us to complete the process.', 'aqualuxe'); ?></p>
                <a href="<?php echo esc_url(get_permalink(get_option('aqualuxe_contact_page'))); ?>" class="aqualuxe-button"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a>
            </div>
        <?php elseif ($trade_in['status'] === 'pending') : ?>
            <p><?php esc_html_e('Your trade-in request is being reviewed. We will contact you soon with an offer.', 'aqualuxe'); ?></p>
        <?php elseif ($trade_in['status'] === 'rejected') : ?>
            <p><?php esc_html_e('We are unable to accept your trade-in at this time. Please contact us for more information.', 'aqualuxe'); ?></p>
        <?php elseif ($trade_in['status'] === 'completed') : ?>
            <p><?php esc_html_e('Your trade-in has been completed. Thank you for choosing AquaLuxe!', 'aqualuxe'); ?></p>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

/**
 * Process trade-in form submission
 */
function aqualuxe_process_trade_in_form()
{
    if (! isset($_POST['aqualuxe_trade_in_submit'])) {
        return;
    }

    // Verify nonce (in a real implementation)
    // if ( ! wp_verify_nonce( $_POST['aqualuxe_trade_in_nonce'], 'aqualuxe_trade_in' ) ) {
    //     wp_die( __( 'Security check failed', 'aqualuxe' ) );
    // }

    // Collect form data
    $trade_in_data = array(
        'customer_name'     => isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '',
        'customer_email'    => isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '',
        'customer_phone'    => isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '',
        'product_name'      => isset($_POST['product_name']) ? sanitize_text_field($_POST['product_name']) : '',
        'product_model'     => isset($_POST['product_model']) ? sanitize_text_field($_POST['product_model']) : '',
        'product_year'      => isset($_POST['product_year']) ? sanitize_text_field($_POST['product_year']) : '',
        'product_condition' => isset($_POST['product_condition']) ? sanitize_text_field($_POST['product_condition']) : '',
        'description'       => isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '',
    );

    // Handle file uploads (in a real implementation)
    // This is just a placeholder - in a real implementation, this would handle file uploads

    // Create trade-in
    $trade_in_id = aqualuxe_create_trade_in($trade_in_data);

    if (is_wp_error($trade_in_id)) {
        wp_die($trade_in_id->get_error_message());
    }

    // Redirect to thank you page
    wp_redirect(add_query_arg('trade_in_submitted', '1', get_permalink(get_option('aqualuxe_trade_in_page'))));
    exit;
}
add_action('template_redirect', 'aqualuxe_process_trade_in_form');

/**
 * Display trade-in success message
 */
function aqualuxe_display_trade_in_success_message()
{
    if (isset($_GET['trade_in_submitted']) && $_GET['trade_in_submitted'] === '1') {
    ?>
        <div class="aqualuxe-message success">
            <?php esc_html_e('Your trade-in request has been submitted successfully! We will review your submission and contact you shortly.', 'aqualuxe'); ?>
        </div>
<?php
    }
}
add_action('aqualuxe_before_content', 'aqualuxe_display_trade_in_success_message');
