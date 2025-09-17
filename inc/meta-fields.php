<?php
/**
 * Meta Fields and Custom Fields
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add meta boxes for custom post types
 */
function aqualuxe_add_meta_boxes()
{
    // Service Meta Box
    add_meta_box(
        'aqualuxe_service_details',
        esc_html__('Service Details', 'aqualuxe'),
        'aqualuxe_service_meta_box_callback',
        'aqualuxe_service',
        'normal',
        'high'
    );

    // Project Meta Box
    add_meta_box(
        'aqualuxe_project_details',
        esc_html__('Project Details', 'aqualuxe'),
        'aqualuxe_project_meta_box_callback',
        'aqualuxe_project',
        'normal',
        'high'
    );

    // Testimonial Meta Box
    add_meta_box(
        'aqualuxe_testimonial_details',
        esc_html__('Testimonial Details', 'aqualuxe'),
        'aqualuxe_testimonial_meta_box_callback',
        'aqualuxe_testimonial',
        'normal',
        'high'
    );

    // Event Meta Box
    add_meta_box(
        'aqualuxe_event_details',
        esc_html__('Event Details', 'aqualuxe'),
        'aqualuxe_event_meta_box_callback',
        'aqualuxe_event',
        'normal',
        'high'
    );

    // FAQ Meta Box
    add_meta_box(
        'aqualuxe_faq_details',
        esc_html__('FAQ Details', 'aqualuxe'),
        'aqualuxe_faq_meta_box_callback',
        'aqualuxe_faq',
        'normal',
        'high'
    );

    // Team Meta Box
    add_meta_box(
        'aqualuxe_team_details',
        esc_html__('Team Member Details', 'aqualuxe'),
        'aqualuxe_team_meta_box_callback',
        'aqualuxe_team',
        'normal',
        'high'
    );

    // Booking Meta Box
    add_meta_box(
        'aqualuxe_booking_details',
        esc_html__('Booking Details', 'aqualuxe'),
        'aqualuxe_booking_meta_box_callback',
        'aqualuxe_booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_meta_boxes');

/**
 * Service meta box callback
 */
function aqualuxe_service_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_service_meta', 'aqualuxe_service_meta_nonce');
    
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $capacity = get_post_meta($post->ID, '_aqualuxe_service_capacity', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_service_featured', true);
    $includes = get_post_meta($post->ID, '_aqualuxe_service_includes', true);
    $requirements = get_post_meta($post->ID, '_aqualuxe_service_requirements', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_service_price" name="aqualuxe_service_price" value="<?php echo esc_attr($price); ?>" placeholder="$99.99" />
                <p class="description"><?php esc_html_e('Service price (leave empty for quote-based services)', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_service_duration" name="aqualuxe_service_duration" value="<?php echo esc_attr($duration); ?>" placeholder="2 hours" />
                <p class="description"><?php esc_html_e('Estimated service duration', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_service_capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label></th>
            <td>
                <input type="number" id="aqualuxe_service_capacity" name="aqualuxe_service_capacity" value="<?php echo esc_attr($capacity); ?>" min="1" />
                <p class="description"><?php esc_html_e('Maximum number of participants', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_service_featured"><?php esc_html_e('Featured Service', 'aqualuxe'); ?></label></th>
            <td>
                <input type="checkbox" id="aqualuxe_service_featured" name="aqualuxe_service_featured" value="1" <?php checked($featured, '1'); ?> />
                <label for="aqualuxe_service_featured"><?php esc_html_e('Mark as featured service', 'aqualuxe'); ?></label>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_service_includes"><?php esc_html_e('What\'s Included', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_service_includes" name="aqualuxe_service_includes" rows="4" cols="50" placeholder="List what's included in this service..."><?php echo esc_textarea($includes); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_service_requirements"><?php esc_html_e('Requirements', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_service_requirements" name="aqualuxe_service_requirements" rows="4" cols="50" placeholder="List any requirements or prerequisites..."><?php echo esc_textarea($requirements); ?></textarea>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Project meta box callback
 */
function aqualuxe_project_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_project_meta', 'aqualuxe_project_meta_nonce');
    
    $client = get_post_meta($post->ID, '_aqualuxe_project_client', true);
    $start_date = get_post_meta($post->ID, '_aqualuxe_project_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_project_end_date', true);
    $budget = get_post_meta($post->ID, '_aqualuxe_project_budget', true);
    $gallery = get_post_meta($post->ID, '_aqualuxe_project_gallery', true);
    $testimonial = get_post_meta($post->ID, '_aqualuxe_project_testimonial', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_project_featured', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_project_client"><?php esc_html_e('Client Name', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_project_client" name="aqualuxe_project_client" value="<?php echo esc_attr($client); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label></th>
            <td>
                <input type="date" id="aqualuxe_project_start_date" name="aqualuxe_project_start_date" value="<?php echo esc_attr($start_date); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label></th>
            <td>
                <input type="date" id="aqualuxe_project_end_date" name="aqualuxe_project_end_date" value="<?php echo esc_attr($end_date); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_budget"><?php esc_html_e('Project Budget', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_project_budget" name="aqualuxe_project_budget" value="<?php echo esc_attr($budget); ?>" placeholder="$10,000" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_gallery"><?php esc_html_e('Gallery Images', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_project_gallery" name="aqualuxe_project_gallery" rows="3" cols="50" placeholder="Comma-separated image IDs or URLs"><?php echo esc_textarea($gallery); ?></textarea>
                <p class="description"><?php esc_html_e('Additional project images for gallery display', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_testimonial"><?php esc_html_e('Client Testimonial', 'aqualuxe'); ?></label></th>
            <td>
                <textarea id="aqualuxe_project_testimonial" name="aqualuxe_project_testimonial" rows="4" cols="50"><?php echo esc_textarea($testimonial); ?></textarea>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_project_featured"><?php esc_html_e('Featured Project', 'aqualuxe'); ?></label></th>
            <td>
                <input type="checkbox" id="aqualuxe_project_featured" name="aqualuxe_project_featured" value="1" <?php checked($featured, '1'); ?> />
                <label for="aqualuxe_project_featured"><?php esc_html_e('Mark as featured project', 'aqualuxe'); ?></label>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Testimonial meta box callback
 */
function aqualuxe_testimonial_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_testimonial_meta', 'aqualuxe_testimonial_meta_nonce');
    
    $author = get_post_meta($post->ID, '_aqualuxe_testimonial_author', true);
    $position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_testimonial_featured', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_testimonial_author"><?php esc_html_e('Author Name', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_testimonial_author" name="aqualuxe_testimonial_author" value="<?php echo esc_attr($author); ?>" required />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_testimonial_position"><?php esc_html_e('Position/Title', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_testimonial_position" name="aqualuxe_testimonial_position" value="<?php echo esc_attr($position); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_testimonial_company"><?php esc_html_e('Company', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_testimonial_company" name="aqualuxe_testimonial_company" value="<?php echo esc_attr($company); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_testimonial_rating"><?php esc_html_e('Rating', 'aqualuxe'); ?></label></th>
            <td>
                <select id="aqualuxe_testimonial_rating" name="aqualuxe_testimonial_rating">
                    <option value=""><?php esc_html_e('Select Rating', 'aqualuxe'); ?></option>
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>" <?php selected($rating, $i); ?>>
                            <?php echo str_repeat('★', $i) . str_repeat('☆', 5 - $i) . " ({$i}/5)"; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_testimonial_featured"><?php esc_html_e('Featured Testimonial', 'aqualuxe'); ?></label></th>
            <td>
                <input type="checkbox" id="aqualuxe_testimonial_featured" name="aqualuxe_testimonial_featured" value="1" <?php checked($featured, '1'); ?> />
                <label for="aqualuxe_testimonial_featured"><?php esc_html_e('Mark as featured testimonial', 'aqualuxe'); ?></label>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Event meta box callback
 */
function aqualuxe_event_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_event_meta', 'aqualuxe_event_meta_nonce');
    
    $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $start_time = get_post_meta($post->ID, '_aqualuxe_event_start_time', true);
    $end_time = get_post_meta($post->ID, '_aqualuxe_event_end_time', true);
    $venue = get_post_meta($post->ID, '_aqualuxe_event_venue', true);
    $address = get_post_meta($post->ID, '_aqualuxe_event_address', true);
    $price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    $capacity = get_post_meta($post->ID, '_aqualuxe_event_capacity', true);
    $registration_url = get_post_meta($post->ID, '_aqualuxe_event_registration_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_event_start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label></th>
            <td><input type="date" id="aqualuxe_event_start_date" name="aqualuxe_event_start_date" value="<?php echo esc_attr($start_date); ?>" required /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label></th>
            <td><input type="date" id="aqualuxe_event_end_date" name="aqualuxe_event_end_date" value="<?php echo esc_attr($end_date); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_start_time"><?php esc_html_e('Start Time', 'aqualuxe'); ?></label></th>
            <td><input type="time" id="aqualuxe_event_start_time" name="aqualuxe_event_start_time" value="<?php echo esc_attr($start_time); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_end_time"><?php esc_html_e('End Time', 'aqualuxe'); ?></label></th>
            <td><input type="time" id="aqualuxe_event_end_time" name="aqualuxe_event_end_time" value="<?php echo esc_attr($end_time); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_venue"><?php esc_html_e('Venue', 'aqualuxe'); ?></label></th>
            <td><input type="text" id="aqualuxe_event_venue" name="aqualuxe_event_venue" value="<?php echo esc_attr($venue); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_address"><?php esc_html_e('Address', 'aqualuxe'); ?></label></th>
            <td><textarea id="aqualuxe_event_address" name="aqualuxe_event_address" rows="3" cols="50"><?php echo esc_textarea($address); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_price"><?php esc_html_e('Ticket Price', 'aqualuxe'); ?></label></th>
            <td>
                <input type="text" id="aqualuxe_event_price" name="aqualuxe_event_price" value="<?php echo esc_attr($price); ?>" placeholder="$25.00" />
                <p class="description"><?php esc_html_e('Leave empty for free events', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_capacity"><?php esc_html_e('Capacity', 'aqualuxe'); ?></label></th>
            <td><input type="number" id="aqualuxe_event_capacity" name="aqualuxe_event_capacity" value="<?php echo esc_attr($capacity); ?>" min="1" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_event_registration_url"><?php esc_html_e('Registration URL', 'aqualuxe'); ?></label></th>
            <td><input type="url" id="aqualuxe_event_registration_url" name="aqualuxe_event_registration_url" value="<?php echo esc_attr($registration_url); ?>" /></td>
        </tr>
    </table>
    <?php
}

/**
 * FAQ meta box callback
 */
function aqualuxe_faq_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_faq_meta', 'aqualuxe_faq_meta_nonce');
    
    $order = get_post_meta($post->ID, '_aqualuxe_faq_order', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_faq_featured', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_faq_order"><?php esc_html_e('Display Order', 'aqualuxe'); ?></label></th>
            <td>
                <input type="number" id="aqualuxe_faq_order" name="aqualuxe_faq_order" value="<?php echo esc_attr($order ?: 0); ?>" min="0" />
                <p class="description"><?php esc_html_e('Lower numbers appear first', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_faq_featured"><?php esc_html_e('Featured FAQ', 'aqualuxe'); ?></label></th>
            <td>
                <input type="checkbox" id="aqualuxe_faq_featured" name="aqualuxe_faq_featured" value="1" <?php checked($featured, '1'); ?> />
                <label for="aqualuxe_faq_featured"><?php esc_html_e('Mark as featured FAQ', 'aqualuxe'); ?></label>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Team meta box callback
 */
function aqualuxe_team_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_team_meta', 'aqualuxe_team_meta_nonce');
    
    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $bio = get_post_meta($post->ID, '_aqualuxe_team_bio', true);
    $social_facebook = get_post_meta($post->ID, '_aqualuxe_team_social_facebook', true);
    $social_twitter = get_post_meta($post->ID, '_aqualuxe_team_social_twitter', true);
    $social_linkedin = get_post_meta($post->ID, '_aqualuxe_team_social_linkedin', true);
    $social_instagram = get_post_meta($post->ID, '_aqualuxe_team_social_instagram', true);
    $order = get_post_meta($post->ID, '_aqualuxe_team_order', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_team_position"><?php esc_html_e('Position/Title', 'aqualuxe'); ?></label></th>
            <td><input type="text" id="aqualuxe_team_position" name="aqualuxe_team_position" value="<?php echo esc_attr($position); ?>" required /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label></th>
            <td><input type="email" id="aqualuxe_team_email" name="aqualuxe_team_email" value="<?php echo esc_attr($email); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_phone"><?php esc_html_e('Phone', 'aqualuxe'); ?></label></th>
            <td><input type="tel" id="aqualuxe_team_phone" name="aqualuxe_team_phone" value="<?php echo esc_attr($phone); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_bio"><?php esc_html_e('Bio', 'aqualuxe'); ?></label></th>
            <td><textarea id="aqualuxe_team_bio" name="aqualuxe_team_bio" rows="5" cols="50"><?php echo esc_textarea($bio); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_social_facebook"><?php esc_html_e('Facebook URL', 'aqualuxe'); ?></label></th>
            <td><input type="url" id="aqualuxe_team_social_facebook" name="aqualuxe_team_social_facebook" value="<?php echo esc_attr($social_facebook); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_social_twitter"><?php esc_html_e('Twitter URL', 'aqualuxe'); ?></label></th>
            <td><input type="url" id="aqualuxe_team_social_twitter" name="aqualuxe_team_social_twitter" value="<?php echo esc_attr($social_twitter); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_social_linkedin"><?php esc_html_e('LinkedIn URL', 'aqualuxe'); ?></label></th>
            <td><input type="url" id="aqualuxe_team_social_linkedin" name="aqualuxe_team_social_linkedin" value="<?php echo esc_attr($social_linkedin); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_social_instagram"><?php esc_html_e('Instagram URL', 'aqualuxe'); ?></label></th>
            <td><input type="url" id="aqualuxe_team_social_instagram" name="aqualuxe_team_social_instagram" value="<?php echo esc_attr($social_instagram); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_team_order"><?php esc_html_e('Display Order', 'aqualuxe'); ?></label></th>
            <td>
                <input type="number" id="aqualuxe_team_order" name="aqualuxe_team_order" value="<?php echo esc_attr($order ?: 0); ?>" min="0" />
                <p class="description"><?php esc_html_e('Lower numbers appear first', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Booking meta box callback
 */
function aqualuxe_booking_meta_box_callback($post)
{
    wp_nonce_field('aqualuxe_save_booking_meta', 'aqualuxe_booking_meta_nonce');
    
    $customer_name = get_post_meta($post->ID, '_aqualuxe_booking_customer_name', true);
    $customer_email = get_post_meta($post->ID, '_aqualuxe_booking_customer_email', true);
    $customer_phone = get_post_meta($post->ID, '_aqualuxe_booking_customer_phone', true);
    $service_id = get_post_meta($post->ID, '_aqualuxe_booking_service_id', true);
    $booking_date = get_post_meta($post->ID, '_aqualuxe_booking_date', true);
    $booking_time = get_post_meta($post->ID, '_aqualuxe_booking_time', true);
    $status = get_post_meta($post->ID, '_aqualuxe_booking_status', true);
    $notes = get_post_meta($post->ID, '_aqualuxe_booking_notes', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="aqualuxe_booking_customer_name"><?php esc_html_e('Customer Name', 'aqualuxe'); ?></label></th>
            <td><input type="text" id="aqualuxe_booking_customer_name" name="aqualuxe_booking_customer_name" value="<?php echo esc_attr($customer_name); ?>" required /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_customer_email"><?php esc_html_e('Customer Email', 'aqualuxe'); ?></label></th>
            <td><input type="email" id="aqualuxe_booking_customer_email" name="aqualuxe_booking_customer_email" value="<?php echo esc_attr($customer_email); ?>" required /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_customer_phone"><?php esc_html_e('Customer Phone', 'aqualuxe'); ?></label></th>
            <td><input type="tel" id="aqualuxe_booking_customer_phone" name="aqualuxe_booking_customer_phone" value="<?php echo esc_attr($customer_phone); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_service_id"><?php esc_html_e('Service', 'aqualuxe'); ?></label></th>
            <td>
                <select id="aqualuxe_booking_service_id" name="aqualuxe_booking_service_id" required>
                    <option value=""><?php esc_html_e('Select Service', 'aqualuxe'); ?></option>
                    <?php
                    $services = get_posts([
                        'post_type' => 'aqualuxe_service',
                        'numberposts' => -1,
                        'post_status' => 'publish'
                    ]);
                    foreach ($services as $service) :
                        ?>
                        <option value="<?php echo esc_attr($service->ID); ?>" <?php selected($service_id, $service->ID); ?>>
                            <?php echo esc_html($service->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_date"><?php esc_html_e('Booking Date', 'aqualuxe'); ?></label></th>
            <td><input type="date" id="aqualuxe_booking_date" name="aqualuxe_booking_date" value="<?php echo esc_attr($booking_date); ?>" required /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_time"><?php esc_html_e('Booking Time', 'aqualuxe'); ?></label></th>
            <td><input type="time" id="aqualuxe_booking_time" name="aqualuxe_booking_time" value="<?php echo esc_attr($booking_time); ?>" /></td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_status"><?php esc_html_e('Status', 'aqualuxe'); ?></label></th>
            <td>
                <select id="aqualuxe_booking_status" name="aqualuxe_booking_status">
                    <option value="pending" <?php selected($status, 'pending'); ?>><?php esc_html_e('Pending', 'aqualuxe'); ?></option>
                    <option value="confirmed" <?php selected($status, 'confirmed'); ?>><?php esc_html_e('Confirmed', 'aqualuxe'); ?></option>
                    <option value="completed" <?php selected($status, 'completed'); ?>><?php esc_html_e('Completed', 'aqualuxe'); ?></option>
                    <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php esc_html_e('Cancelled', 'aqualuxe'); ?></option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="aqualuxe_booking_notes"><?php esc_html_e('Notes', 'aqualuxe'); ?></label></th>
            <td><textarea id="aqualuxe_booking_notes" name="aqualuxe_booking_notes" rows="4" cols="50"><?php echo esc_textarea($notes); ?></textarea></td>
        </tr>
    </table>
    <?php
}

/**
 * Save meta box data
 */
function aqualuxe_save_meta_boxes($post_id)
{
    // Check if our nonce is set and verify it
    $nonces = [
        'aqualuxe_service' => 'aqualuxe_service_meta_nonce',
        'aqualuxe_project' => 'aqualuxe_project_meta_nonce',
        'aqualuxe_testimonial' => 'aqualuxe_testimonial_meta_nonce',
        'aqualuxe_event' => 'aqualuxe_event_meta_nonce',
        'aqualuxe_faq' => 'aqualuxe_faq_meta_nonce',
        'aqualuxe_team' => 'aqualuxe_team_meta_nonce',
        'aqualuxe_booking' => 'aqualuxe_booking_meta_nonce',
    ];

    $post_type = get_post_type($post_id);
    
    if (!isset($nonces[$post_type])) {
        return;
    }

    if (!isset($_POST[$nonces[$post_type]]) || !wp_verify_nonce($_POST[$nonces[$post_type]], "aqualuxe_save_{$post_type}_meta")) {
        return;
    }

    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if not an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Save meta fields based on post type
    switch ($post_type) {
        case 'aqualuxe_service':
            aqualuxe_save_service_meta($post_id);
            break;
        case 'aqualuxe_project':
            aqualuxe_save_project_meta($post_id);
            break;
        case 'aqualuxe_testimonial':
            aqualuxe_save_testimonial_meta($post_id);
            break;
        case 'aqualuxe_event':
            aqualuxe_save_event_meta($post_id);
            break;
        case 'aqualuxe_faq':
            aqualuxe_save_faq_meta($post_id);
            break;
        case 'aqualuxe_team':
            aqualuxe_save_team_meta($post_id);
            break;
        case 'aqualuxe_booking':
            aqualuxe_save_booking_meta($post_id);
            break;
    }
}
add_action('save_post', 'aqualuxe_save_meta_boxes');

/**
 * Save service meta
 */
function aqualuxe_save_service_meta($post_id)
{
    $fields = [
        'aqualuxe_service_price' => 'text',
        'aqualuxe_service_duration' => 'text',
        'aqualuxe_service_capacity' => 'number',
        'aqualuxe_service_featured' => 'checkbox',
        'aqualuxe_service_includes' => 'textarea',
        'aqualuxe_service_requirements' => 'textarea',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save project meta
 */
function aqualuxe_save_project_meta($post_id)
{
    $fields = [
        'aqualuxe_project_client' => 'text',
        'aqualuxe_project_start_date' => 'date',
        'aqualuxe_project_end_date' => 'date',
        'aqualuxe_project_budget' => 'text',
        'aqualuxe_project_gallery' => 'textarea',
        'aqualuxe_project_testimonial' => 'textarea',
        'aqualuxe_project_featured' => 'checkbox',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save testimonial meta
 */
function aqualuxe_save_testimonial_meta($post_id)
{
    $fields = [
        'aqualuxe_testimonial_author' => 'text',
        'aqualuxe_testimonial_position' => 'text',
        'aqualuxe_testimonial_company' => 'text',
        'aqualuxe_testimonial_rating' => 'number',
        'aqualuxe_testimonial_featured' => 'checkbox',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save event meta
 */
function aqualuxe_save_event_meta($post_id)
{
    $fields = [
        'aqualuxe_event_start_date' => 'date',
        'aqualuxe_event_end_date' => 'date',
        'aqualuxe_event_start_time' => 'time',
        'aqualuxe_event_end_time' => 'time',
        'aqualuxe_event_venue' => 'text',
        'aqualuxe_event_address' => 'textarea',
        'aqualuxe_event_price' => 'text',
        'aqualuxe_event_capacity' => 'number',
        'aqualuxe_event_registration_url' => 'url',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? sanitize_textarea_field($_POST[$field]) : 
                    ($type === 'url' ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]));
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save FAQ meta
 */
function aqualuxe_save_faq_meta($post_id)
{
    $fields = [
        'aqualuxe_faq_order' => 'number',
        'aqualuxe_faq_featured' => 'checkbox',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save team meta
 */
function aqualuxe_save_team_meta($post_id)
{
    $fields = [
        'aqualuxe_team_position' => 'text',
        'aqualuxe_team_email' => 'email',
        'aqualuxe_team_phone' => 'text',
        'aqualuxe_team_bio' => 'textarea',
        'aqualuxe_team_social_facebook' => 'url',
        'aqualuxe_team_social_twitter' => 'url',
        'aqualuxe_team_social_linkedin' => 'url',
        'aqualuxe_team_social_instagram' => 'url',
        'aqualuxe_team_order' => 'number',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? sanitize_textarea_field($_POST[$field]) : 
                    ($type === 'url' ? esc_url_raw($_POST[$field]) : 
                    ($type === 'email' ? sanitize_email($_POST[$field]) : sanitize_text_field($_POST[$field])));
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}

/**
 * Save booking meta
 */
function aqualuxe_save_booking_meta($post_id)
{
    $fields = [
        'aqualuxe_booking_customer_name' => 'text',
        'aqualuxe_booking_customer_email' => 'email',
        'aqualuxe_booking_customer_phone' => 'text',
        'aqualuxe_booking_service_id' => 'number',
        'aqualuxe_booking_date' => 'date',
        'aqualuxe_booking_time' => 'time',
        'aqualuxe_booking_status' => 'text',
        'aqualuxe_booking_notes' => 'textarea',
    ];

    foreach ($fields as $field => $type) {
        $meta_key = '_' . $field;
        if (isset($_POST[$field])) {
            $value = $type === 'textarea' ? sanitize_textarea_field($_POST[$field]) : 
                    ($type === 'email' ? sanitize_email($_POST[$field]) : sanitize_text_field($_POST[$field]));
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }
}