<?php
/**
 * Custom Fields Registration
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom meta boxes
 */
function aqualuxe_add_meta_boxes() {
    // Service meta box
    add_meta_box(
        'aqualuxe_service_details',
        __('Service Details', 'aqualuxe'),
        'aqualuxe_service_details_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Event meta box
    add_meta_box(
        'aqualuxe_event_details',
        __('Event Details', 'aqualuxe'),
        'aqualuxe_event_details_callback',
        'aqualuxe_event',
        'normal',
        'high'
    );

    // Testimonial meta box
    add_meta_box(
        'aqualuxe_testimonial_details',
        __('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_details_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );

    // Team member meta box
    add_meta_box(
        'aqualuxe_team_details',
        __('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_details_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Booking meta box
    add_meta_box(
        'aqualuxe_booking_details',
        __('Booking Details', 'aqualuxe'),
        'aqualuxe_booking_details_callback',
        'aqualuxe_booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Service details meta box callback
 */
function aqualuxe_service_details_callback($post) {
    wp_nonce_field('aqualuxe_service_details_nonce', 'aqualuxe_service_details_nonce');

    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $features = get_post_meta($post->ID, '_aqualuxe_service_features', true);
    $booking_enabled = get_post_meta($post->ID, '_aqualuxe_service_booking_enabled', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="aqualuxe_service_price"><?php _e('Price', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" class="regular-text" />
                <p class="description"><?php _e('Service price (e.g., $99 or Contact for quote)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_service_duration"><?php _e('Duration', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" class="regular-text" />
                <p class="description"><?php _e('Service duration (e.g., 2 hours, 1 day)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_service_features"><?php _e('Features', 'aqualuxe'); ?></label>
            </th>
            <td>
                <textarea id="aqualuxe_service_features" name="aqualuxe_service_features" rows="5" class="large-text"><?php echo esc_textarea($features); ?></textarea>
                <p class="description"><?php _e('Service features (one per line)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_service_booking_enabled"><?php _e('Enable Booking', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="checkbox" id="aqualuxe_service_booking_enabled" name="aqualuxe_service_booking_enabled" value="1" <?php checked($booking_enabled, '1'); ?> />
                <p class="description"><?php _e('Allow customers to book this service online', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Event details meta box callback
 */
function aqualuxe_event_details_callback($post) {
    wp_nonce_field('aqualuxe_event_details_nonce', 'aqualuxe_event_details_nonce');

    $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    $capacity = get_post_meta($post->ID, '_aqualuxe_event_capacity', true);
    $registration_enabled = get_post_meta($post->ID, '_aqualuxe_event_registration_enabled', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_start_date"><?php _e('Start Date & Time', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="datetime-local" id="aqualuxe_event_start_date" name="aqualuxe_event_start_date" value="<?php echo esc_attr($start_date); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_end_date"><?php _e('End Date & Time', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="datetime-local" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($end_date); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_location"><?php _e('Location', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_event_location" name="aqualuxe_event_location" value="<?php echo esc_attr($location); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_price"><?php _e('Ticket Price', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($price); ?>" class="regular-text" />
                <p class="description"><?php _e('Ticket price (e.g., $25 or Free)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_capacity"><?php _e('Capacity', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="number" id="aqualuxe_event_capacity" name="aqualuxe_event_capacity" value="<?php echo esc_attr($capacity); ?>" class="small-text" min="1" />
                <p class="description"><?php _e('Maximum number of attendees', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_event_registration_enabled"><?php _e('Enable Registration', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="checkbox" id="aqualuxe_event_registration_enabled" name="aqualuxe_event_registration_enabled" value="1" <?php checked($registration_enabled, '1'); ?> />
                <p class="description"><?php _e('Allow visitors to register for this event', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Testimonial details meta box callback
 */
function aqualuxe_testimonial_details_callback($post) {
    wp_nonce_field('aqualuxe_testimonial_details_nonce', 'aqualuxe_testimonial_details_nonce');

    $client_name = get_post_meta($post->ID, '_aqualuxe_testimonial_client_name', true);
    $client_position = get_post_meta($post->ID, '_aqualuxe_testimonial_client_position', true);
    $client_company = get_post_meta($post->ID, '_aqualuxe_testimonial_client_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="aqualuxe_testimonial_client_name"><?php _e('Client Name', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_testimonial_client_name" name="aqualuxe_testimonial_client_name" value="<?php echo esc_attr($client_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_testimonial_client_position"><?php _e('Client Position', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_testimonial_client_position" name="aqualuxe_testimonial_client_position" value="<?php echo esc_attr($client_position); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_testimonial_client_company"><?php _e('Client Company', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_testimonial_client_company" name="aqualuxe_testimonial_client_company" value="<?php echo esc_attr($client_company); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_testimonial_rating"><?php _e('Rating', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
                    <option value=""><?php _e('Select Rating', 'aqualuxe'); ?></option>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                            <?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i) . ' (' . $i . '/5)'; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Team member details meta box callback
 */
function aqualuxe_team_details_callback($post) {
    wp_nonce_field('aqualuxe_team_details_nonce', 'aqualuxe_team_details_nonce');

    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $social_facebook = get_post_meta($post->ID, '_aqualuxe_team_social_facebook', true);
    $social_twitter = get_post_meta($post->ID, '_aqualuxe_team_social_twitter', true);
    $social_linkedin = get_post_meta($post->ID, '_aqualuxe_team_social_linkedin', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_position"><?php _e('Position', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_email"><?php _e('Email', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="tel" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_social_facebook"><?php _e('Facebook URL', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="url" id="aqualuxe_team_social_facebook" name="aqualuxe_team_social_facebook" value="<?php echo esc_attr($social_facebook); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_social_twitter"><?php _e('Twitter URL', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="url" id="aqualuxe_team_social_twitter" name="aqualuxe_team_social_twitter" value="<?php echo esc_attr($social_twitter); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_team_social_linkedin"><?php _e('LinkedIn URL', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="url" id="aqualuxe_team_social_linkedin" name="aqualuxe_team_social_linkedin" value="<?php echo esc_attr($social_linkedin); ?>" class="regular-text" />
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Booking details meta box callback
 */
function aqualuxe_booking_details_callback($post) {
    wp_nonce_field('aqualuxe_booking_details_nonce', 'aqualuxe_booking_details_nonce');

    $service_id = get_post_meta($post->ID, '_aqualuxe_booking_service_id', true);
    $customer_name = get_post_meta($post->ID, '_aqualuxe_booking_customer_name', true);
    $customer_email = get_post_meta($post->ID, '_aqualuxe_booking_customer_email', true);
    $booking_date = get_post_meta($post->ID, '_aqualuxe_booking_date', true);
    $status = get_post_meta($post->ID, '_aqualuxe_booking_status', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="aqualuxe_booking_service_id"><?php _e('Service', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select id="aqualuxe_booking_service_id" name="aqualuxe_booking_service_id" class="regular-text">
                    <option value=""><?php _e('Select Service', 'aqualuxe'); ?></option>
                    <?php
                    $services = get_posts(array(
                        'post_type' => 'aqualuxe_service',
                        'numberposts' => -1,
                        'post_status' => 'publish'
                    ));
                    foreach ($services as $service) :
                    ?>
                        <option value="<?php echo $service->ID; ?>" <?php selected($service_id, $service->ID); ?>>
                            <?php echo esc_html($service->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_booking_customer_name"><?php _e('Customer Name', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="text" id="aqualuxe_booking_customer_name" name="aqualuxe_booking_customer_name" value="<?php echo esc_attr($customer_name); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_booking_customer_email"><?php _e('Customer Email', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="email" id="aqualuxe_booking_customer_email" name="aqualuxe_booking_customer_email" value="<?php echo esc_attr($customer_email); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_booking_date"><?php _e('Booking Date & Time', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="datetime-local" id="aqualuxe_booking_date" name="aqualuxe_booking_date" value="<?php echo esc_attr($booking_date); ?>" class="regular-text" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="aqualuxe_booking_status"><?php _e('Status', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select id="aqualuxe_booking_status" name="aqualuxe_booking_status">
                    <option value="pending" <?php selected($status, 'pending'); ?>><?php _e('Pending', 'aqualuxe'); ?></option>
                    <option value="confirmed" <?php selected($status, 'confirmed'); ?>><?php _e('Confirmed', 'aqualuxe'); ?></option>
                    <option value="completed" <?php selected($status, 'completed'); ?>><?php _e('Completed', 'aqualuxe'); ?></option>
                    <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Cancelled', 'aqualuxe'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function aqualuxe_save_meta_boxes($post_id) {
    // Check if nonce is valid
    $nonces = array(
        'aqualuxe_service_details_nonce',
        'aqualuxe_event_details_nonce',
        'aqualuxe_testimonial_details_nonce',
        'aqualuxe_team_details_nonce',
        'aqualuxe_booking_details_nonce'
    );

    $valid_nonce = false;
    foreach ($nonces as $nonce) {
        if (isset($_POST[$nonce]) && wp_verify_nonce($_POST[$nonce], $nonce)) {
            $valid_nonce = true;
            break;
        }
    }

    if (!$valid_nonce) {
        return;
    }

    // Check if user has permissions to edit
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Don't save during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Service fields
    if (get_post_type($post_id) === 'aqualuxe_service') {
        $service_fields = array(
            'aqualuxe_service_price',
            'aqualuxe_service_duration',
            'aqualuxe_service_features',
            'aqualuxe_service_booking_enabled'
        );

        foreach ($service_fields as $field) {
            if (isset($_POST[$field])) {
                $value = $_POST[$field];
                if ($field === 'aqualuxe_service_features') {
                    $value = sanitize_textarea_field($value);
                } elseif ($field === 'aqualuxe_service_booking_enabled') {
                    $value = $value ? '1' : '';
                } else {
                    $value = sanitize_text_field($value);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Event fields
    if (get_post_type($post_id) === 'aqualuxe_event') {
        $event_fields = array(
            'aqualuxe_event_start_date',
            'aqualuxe_event_end_date',
            'aqualuxe_event_location',
            'aqualuxe_event_price',
            'aqualuxe_event_capacity',
            'aqualuxe_event_registration_enabled'
        );

        foreach ($event_fields as $field) {
            if (isset($_POST[$field])) {
                $value = $_POST[$field];
                if ($field === 'aqualuxe_event_capacity') {
                    $value = intval($value);
                } elseif ($field === 'aqualuxe_event_registration_enabled') {
                    $value = $value ? '1' : '';
                } else {
                    $value = sanitize_text_field($value);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Testimonial fields
    if (get_post_type($post_id) === 'aqualuxe_testimonial') {
        $testimonial_fields = array(
            'aqualuxe_testimonial_client_name',
            'aqualuxe_testimonial_client_position',
            'aqualuxe_testimonial_client_company',
            'aqualuxe_testimonial_rating'
        );

        foreach ($testimonial_fields as $field) {
            if (isset($_POST[$field])) {
                $value = sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Team fields
    if (get_post_type($post_id) === 'aqualuxe_team') {
        $team_fields = array(
            'aqualuxe_team_position',
            'aqualuxe_team_email',
            'aqualuxe_team_phone',
            'aqualuxe_team_social_facebook',
            'aqualuxe_team_social_twitter',
            'aqualuxe_team_social_linkedin'
        );

        foreach ($team_fields as $field) {
            if (isset($_POST[$field])) {
                if (strpos($field, 'email') !== false) {
                    $value = sanitize_email($_POST[$field]);
                } elseif (strpos($field, 'social') !== false) {
                    $value = esc_url_raw($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }

    // Booking fields
    if (get_post_type($post_id) === 'aqualuxe_booking') {
        $booking_fields = array(
            'aqualuxe_booking_service_id',
            'aqualuxe_booking_customer_name',
            'aqualuxe_booking_customer_email',
            'aqualuxe_booking_date',
            'aqualuxe_booking_status'
        );

        foreach ($booking_fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'aqualuxe_booking_service_id') {
                    $value = intval($_POST[$field]);
                } elseif ($field === 'aqualuxe_booking_customer_email') {
                    $value = sanitize_email($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_post_meta($post_id, '_' . $field, $value);
            }
        }
    }
}
add_action('save_post', 'aqualuxe_save_meta_boxes');