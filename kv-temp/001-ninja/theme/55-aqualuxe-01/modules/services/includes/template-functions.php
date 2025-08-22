<?php
/**
 * Services Template Functions
 *
 * Functions for the services templates.
 *
 * @package AquaLuxe
 * @subpackage Modules/Services
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get services
 *
 * @param array $args
 * @return WP_Query
 */
function aqualuxe_get_services($args = []) {
    // Default arguments
    $defaults = [
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    // Parse arguments
    $args = wp_parse_args($args, $defaults);

    // Create query
    $query = new WP_Query($args);

    return $query;
}

/**
 * Get service categories
 *
 * @param array $args
 * @return array
 */
function aqualuxe_get_service_categories($args = []) {
    // Default arguments
    $defaults = [
        'taxonomy' => 'service_category',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ];

    // Parse arguments
    $args = wp_parse_args($args, $defaults);

    // Get terms
    $terms = get_terms($args);

    return $terms;
}

/**
 * Get service tags
 *
 * @param array $args
 * @return array
 */
function aqualuxe_get_service_tags($args = []) {
    // Default arguments
    $defaults = [
        'taxonomy' => 'service_tag',
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC',
    ];

    // Parse arguments
    $args = wp_parse_args($args, $defaults);

    // Get terms
    $terms = get_terms($args);

    return $terms;
}

/**
 * Get related services
 *
 * @param int $post_id
 * @param int $limit
 * @return WP_Query
 */
function aqualuxe_get_related_services($post_id, $limit = 3) {
    // Get service categories
    $categories = get_the_terms($post_id, 'service_category');
    $category_ids = [];

    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
    }

    // Get service tags
    $tags = get_the_terms($post_id, 'service_tag');
    $tag_ids = [];

    if ($tags && !is_wp_error($tags)) {
        foreach ($tags as $tag) {
            $tag_ids[] = $tag->term_id;
        }
    }

    // Set up query arguments
    $args = [
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'post__not_in' => [$post_id],
        'orderby' => 'rand',
    ];

    // Add tax query if we have categories or tags
    if (!empty($category_ids) || !empty($tag_ids)) {
        $args['tax_query'] = ['relation' => 'OR'];

        if (!empty($category_ids)) {
            $args['tax_query'][] = [
                'taxonomy' => 'service_category',
                'field' => 'term_id',
                'terms' => $category_ids,
            ];
        }

        if (!empty($tag_ids)) {
            $args['tax_query'][] = [
                'taxonomy' => 'service_tag',
                'field' => 'term_id',
                'terms' => $tag_ids,
            ];
        }
    }

    // Create query
    $query = new WP_Query($args);

    return $query;
}

/**
 * Get service meta
 *
 * @param array $post
 * @return array
 */
function aqualuxe_get_service_meta($post) {
    // Get post ID
    $post_id = $post['id'];

    // Get service meta
    $meta = [
        'duration' => get_post_meta($post_id, '_service_duration', true),
        'location' => get_post_meta($post_id, '_service_location', true),
        'availability' => get_post_meta($post_id, '_service_availability', true),
        'price' => get_post_meta($post_id, '_service_price', true),
        'sale_price' => get_post_meta($post_id, '_service_sale_price', true),
        'price_type' => get_post_meta($post_id, '_service_price_type', true),
        'booking_enabled' => get_post_meta($post_id, '_service_booking_enabled', true),
        'booking_type' => get_post_meta($post_id, '_service_booking_type', true),
        'booking_form' => get_post_meta($post_id, '_service_booking_form', true),
    ];

    return $meta;
}

/**
 * Get service featured image
 *
 * @param array $post
 * @return array
 */
function aqualuxe_get_service_featured_image($post) {
    // Get post ID
    $post_id = $post['id'];

    // Get featured image
    $featured_image = [
        'id' => 0,
        'url' => '',
        'alt' => '',
    ];

    if (has_post_thumbnail($post_id)) {
        $image_id = get_post_thumbnail_id($post_id);
        $image = wp_get_attachment_image_src($image_id, 'full');

        if ($image) {
            $featured_image = [
                'id' => $image_id,
                'url' => $image[0],
                'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
            ];
        }
    }

    return $featured_image;
}

/**
 * Format price
 *
 * @param float $price
 * @param string $currency
 * @return string
 */
function aqualuxe_format_price($price, $currency = '') {
    // Get currency
    if (empty($currency)) {
        $currency = get_option('aqualuxe_currency', 'USD');
    }

    // Format price
    $formatted_price = '';

    switch ($currency) {
        case 'USD':
            $formatted_price = '$' . number_format($price, 2);
            break;

        case 'EUR':
            $formatted_price = '€' . number_format($price, 2);
            break;

        case 'GBP':
            $formatted_price = '£' . number_format($price, 2);
            break;

        default:
            $formatted_price = $currency . ' ' . number_format($price, 2);
            break;
    }

    return $formatted_price;
}

/**
 * Services dashboard widget callback
 */
function aqualuxe_services_dashboard_widget_callback() {
    // Get services count
    $services_count = wp_count_posts('service');
    $published_services = $services_count->publish;

    // Get categories count
    $categories_count = wp_count_terms('service_category', ['hide_empty' => false]);

    // Get tags count
    $tags_count = wp_count_terms('service_tag', ['hide_empty' => false]);

    // Get recent services
    $recent_services = aqualuxe_get_services([
        'posts_per_page' => 5,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    // Output widget content
    echo '<div class="aqualuxe-services-dashboard-widget">';

    // Output stats
    echo '<div class="aqualuxe-services-stats">';
    echo '<div class="aqualuxe-services-stat">';
    echo '<span class="aqualuxe-services-stat-count">' . esc_html($published_services) . '</span>';
    echo '<span class="aqualuxe-services-stat-label">' . esc_html__('Services', 'aqualuxe') . '</span>';
    echo '</div>';

    echo '<div class="aqualuxe-services-stat">';
    echo '<span class="aqualuxe-services-stat-count">' . esc_html($categories_count) . '</span>';
    echo '<span class="aqualuxe-services-stat-label">' . esc_html__('Categories', 'aqualuxe') . '</span>';
    echo '</div>';

    echo '<div class="aqualuxe-services-stat">';
    echo '<span class="aqualuxe-services-stat-count">' . esc_html($tags_count) . '</span>';
    echo '<span class="aqualuxe-services-stat-label">' . esc_html__('Tags', 'aqualuxe') . '</span>';
    echo '</div>';
    echo '</div>';

    // Output recent services
    echo '<div class="aqualuxe-services-recent">';
    echo '<h3>' . esc_html__('Recent Services', 'aqualuxe') . '</h3>';

    if ($recent_services->have_posts()) {
        echo '<ul>';
        while ($recent_services->have_posts()) {
            $recent_services->the_post();
            echo '<li>';
            echo '<a href="' . esc_url(get_edit_post_link()) . '">' . esc_html(get_the_title()) . '</a>';
            echo '<span class="aqualuxe-services-recent-date">' . esc_html(get_the_date()) . '</span>';
            echo '</li>';
        }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No services found.', 'aqualuxe') . '</p>';
    }
    echo '</div>';

    // Output links
    echo '<div class="aqualuxe-services-links">';
    echo '<a href="' . esc_url(admin_url('post-new.php?post_type=service')) . '" class="button button-primary">' . esc_html__('Add New Service', 'aqualuxe') . '</a>';
    echo '<a href="' . esc_url(admin_url('edit.php?post_type=service')) . '" class="button">' . esc_html__('View All Services', 'aqualuxe') . '</a>';
    echo '</div>';

    echo '</div>';
}

/**
 * Service details meta box callback
 *
 * @param WP_Post $post
 */
function aqualuxe_service_details_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_service_meta_box', 'aqualuxe_service_meta_box_nonce');

    // Get service details
    $duration = get_post_meta($post->ID, '_service_duration', true);
    $location = get_post_meta($post->ID, '_service_location', true);
    $availability = get_post_meta($post->ID, '_service_availability', true);

    // Output fields
    ?>
    <div class="aqualuxe-service-meta-box">
        <div class="aqualuxe-service-meta-field">
            <label for="service_duration"><?php esc_html_e('Duration', 'aqualuxe'); ?></label>
            <input type="text" id="service_duration" name="service_duration" value="<?php echo esc_attr($duration); ?>" placeholder="<?php esc_attr_e('e.g. 1 hour, 30 minutes', 'aqualuxe'); ?>" />
            <p class="description"><?php esc_html_e('Enter the duration of the service.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_location"><?php esc_html_e('Location', 'aqualuxe'); ?></label>
            <input type="text" id="service_location" name="service_location" value="<?php echo esc_attr($location); ?>" placeholder="<?php esc_attr_e('e.g. In-store, Online, At home', 'aqualuxe'); ?>" />
            <p class="description"><?php esc_html_e('Enter the location where the service is provided.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_availability"><?php esc_html_e('Availability', 'aqualuxe'); ?></label>
            <input type="text" id="service_availability" name="service_availability" value="<?php echo esc_attr($availability); ?>" placeholder="<?php esc_attr_e('e.g. Monday-Friday, 9am-5pm', 'aqualuxe'); ?>" />
            <p class="description"><?php esc_html_e('Enter the availability of the service.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <style>
        .aqualuxe-service-meta-box {
            margin: -6px -12px -12px;
        }
        .aqualuxe-service-meta-field {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }
        .aqualuxe-service-meta-field:last-child {
            border-bottom: none;
        }
        .aqualuxe-service-meta-field label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .aqualuxe-service-meta-field input[type="text"],
        .aqualuxe-service-meta-field select {
            width: 100%;
            max-width: 400px;
        }
        .aqualuxe-service-meta-field .description {
            margin-top: 5px;
            color: #666;
        }
    </style>
    <?php
}

/**
 * Service pricing meta box callback
 *
 * @param WP_Post $post
 */
function aqualuxe_service_pricing_meta_box_callback($post) {
    // Get service pricing
    $price = get_post_meta($post->ID, '_service_price', true);
    $sale_price = get_post_meta($post->ID, '_service_sale_price', true);
    $price_type = get_post_meta($post->ID, '_service_price_type', true);

    // Output fields
    ?>
    <div class="aqualuxe-service-meta-box">
        <div class="aqualuxe-service-meta-field">
            <label for="service_price"><?php esc_html_e('Price', 'aqualuxe'); ?></label>
            <input type="text" id="service_price" name="service_price" value="<?php echo esc_attr($price); ?>" placeholder="<?php esc_attr_e('e.g. 99.99', 'aqualuxe'); ?>" />
            <p class="description"><?php esc_html_e('Enter the price of the service.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_sale_price"><?php esc_html_e('Sale Price', 'aqualuxe'); ?></label>
            <input type="text" id="service_sale_price" name="service_sale_price" value="<?php echo esc_attr($sale_price); ?>" placeholder="<?php esc_attr_e('e.g. 79.99', 'aqualuxe'); ?>" />
            <p class="description"><?php esc_html_e('Enter the sale price of the service.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_price_type"><?php esc_html_e('Price Type', 'aqualuxe'); ?></label>
            <select id="service_price_type" name="service_price_type">
                <option value=""><?php esc_html_e('Select Price Type', 'aqualuxe'); ?></option>
                <option value="per_hour" <?php selected($price_type, 'per_hour'); ?>><?php esc_html_e('Per Hour', 'aqualuxe'); ?></option>
                <option value="per_session" <?php selected($price_type, 'per_session'); ?>><?php esc_html_e('Per Session', 'aqualuxe'); ?></option>
                <option value="per_day" <?php selected($price_type, 'per_day'); ?>><?php esc_html_e('Per Day', 'aqualuxe'); ?></option>
                <option value="per_week" <?php selected($price_type, 'per_week'); ?>><?php esc_html_e('Per Week', 'aqualuxe'); ?></option>
                <option value="per_month" <?php selected($price_type, 'per_month'); ?>><?php esc_html_e('Per Month', 'aqualuxe'); ?></option>
                <option value="per_year" <?php selected($price_type, 'per_year'); ?>><?php esc_html_e('Per Year', 'aqualuxe'); ?></option>
                <option value="one_time" <?php selected($price_type, 'one_time'); ?>><?php esc_html_e('One Time', 'aqualuxe'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Select the price type of the service.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Service booking meta box callback
 *
 * @param WP_Post $post
 */
function aqualuxe_service_booking_meta_box_callback($post) {
    // Get service booking
    $booking_enabled = get_post_meta($post->ID, '_service_booking_enabled', true);
    $booking_type = get_post_meta($post->ID, '_service_booking_type', true);
    $booking_form = get_post_meta($post->ID, '_service_booking_form', true);

    // Output fields
    ?>
    <div class="aqualuxe-service-meta-box">
        <div class="aqualuxe-service-meta-field">
            <label for="service_booking_enabled">
                <input type="checkbox" id="service_booking_enabled" name="service_booking_enabled" value="yes" <?php checked($booking_enabled, 'yes'); ?> />
                <?php esc_html_e('Enable Booking', 'aqualuxe'); ?>
            </label>
            <p class="description"><?php esc_html_e('Check this box to enable booking for this service.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_booking_type"><?php esc_html_e('Booking Type', 'aqualuxe'); ?></label>
            <select id="service_booking_type" name="service_booking_type">
                <option value=""><?php esc_html_e('Select Booking Type', 'aqualuxe'); ?></option>
                <option value="form" <?php selected($booking_type, 'form'); ?>><?php esc_html_e('Contact Form', 'aqualuxe'); ?></option>
                <option value="calendar" <?php selected($booking_type, 'calendar'); ?>><?php esc_html_e('Calendar Booking', 'aqualuxe'); ?></option>
                <option value="external" <?php selected($booking_type, 'external'); ?>><?php esc_html_e('External Booking', 'aqualuxe'); ?></option>
            </select>
            <p class="description"><?php esc_html_e('Select the booking type for this service.', 'aqualuxe'); ?></p>
        </div>

        <div class="aqualuxe-service-meta-field">
            <label for="service_booking_form"><?php esc_html_e('Booking Form', 'aqualuxe'); ?></label>
            <select id="service_booking_form" name="service_booking_form">
                <option value=""><?php esc_html_e('Select Booking Form', 'aqualuxe'); ?></option>
                <?php
                // Check if Contact Form 7 is active
                if (class_exists('WPCF7_ContactForm')) {
                    $forms = WPCF7_ContactForm::find();
                    foreach ($forms as $form) {
                        echo '<option value="' . esc_attr($form->id()) . '" ' . selected($booking_form, $form->id(), false) . '>' . esc_html($form->title()) . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>' . esc_html__('Contact Form 7 not installed', 'aqualuxe') . '</option>';
                }
                ?>
            </select>
            <p class="description"><?php esc_html_e('Select the booking form for this service.', 'aqualuxe'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Services shortcode
 *
 * @param array $atts
 * @return string
 */
function aqualuxe_services_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts([
        'layout' => 'grid',
        'columns' => 3,
        'limit' => -1,
        'category' => '',
        'tag' => '',
        'orderby' => 'date',
        'order' => 'DESC',
        'show_image' => true,
        'show_excerpt' => true,
        'show_button' => true,
        'button_text' => __('View Details', 'aqualuxe'),
    ], $atts);

    // Convert string values to boolean
    $atts['show_image'] = filter_var($atts['show_image'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_excerpt'] = filter_var($atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_button'] = filter_var($atts['show_button'], FILTER_VALIDATE_BOOLEAN);

    // Set up query arguments
    $args = [
        'post_type' => 'service',
        'post_status' => 'publish',
        'posts_per_page' => $atts['limit'],
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    ];

    // Add category if specified
    if (!empty($atts['category'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'service_category',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
        ];
    }

    // Add tag if specified
    if (!empty($atts['tag'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'service_tag',
            'field' => 'slug',
            'terms' => explode(',', $atts['tag']),
        ];
    }

    // Get services
    $services = aqualuxe_get_services($args);

    // Start output buffer
    ob_start();

    // Check if we have services
    if ($services->have_posts()) {
        // Get module instance
        global $aqualuxe_theme;
        $module = isset($aqualuxe_theme->modules['services']) ? $aqualuxe_theme->modules['services'] : null;

        // Output services
        echo '<div class="aqualuxe-services-shortcode aqualuxe-services-layout-' . esc_attr($atts['layout']) . ' aqualuxe-services-columns-' . esc_attr($atts['columns']) . '">';
        echo '<div class="aqualuxe-services-grid">';

        while ($services->have_posts()) {
            $services->the_post();

            // Get service meta
            $price = get_post_meta(get_the_ID(), '_service_price', true);
            $sale_price = get_post_meta(get_the_ID(), '_service_sale_price', true);
            $price_type = get_post_meta(get_the_ID(), '_service_price_type', true);
            $duration = get_post_meta(get_the_ID(), '_service_duration', true);
            $location = get_post_meta(get_the_ID(), '_service_location', true);

            // Output service
            echo '<div class="aqualuxe-service">';
            echo '<div class="aqualuxe-service-inner">';

            // Output image
            if ($atts['show_image'] && has_post_thumbnail()) {
                echo '<div class="aqualuxe-service-image">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('aqualuxe-card');
                echo '</a>';
                echo '</div>';
            }

            // Output content
            echo '<div class="aqualuxe-service-content">';

            // Output title
            echo '<h3 class="aqualuxe-service-title">';
            echo '<a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a>';
            echo '</h3>';

            // Output meta
            echo '<div class="aqualuxe-service-meta">';

            // Output price
            if ($price) {
                echo '<div class="aqualuxe-service-price">';
                if ($sale_price) {
                    echo '<del>' . esc_html(aqualuxe_format_price($price)) . '</del> ';
                    echo '<ins>' . esc_html(aqualuxe_format_price($sale_price)) . '</ins>';
                } else {
                    echo esc_html(aqualuxe_format_price($price));
                }

                if ($price_type) {
                    echo ' <span class="aqualuxe-service-price-type">' . esc_html($price_type) . '</span>';
                }
                echo '</div>';
            }

            // Output duration
            if ($duration) {
                echo '<div class="aqualuxe-service-duration">';
                echo '<span class="aqualuxe-service-duration-icon"></span>';
                echo '<span class="aqualuxe-service-duration-text">' . esc_html($duration) . '</span>';
                echo '</div>';
            }

            // Output location
            if ($location) {
                echo '<div class="aqualuxe-service-location">';
                echo '<span class="aqualuxe-service-location-icon"></span>';
                echo '<span class="aqualuxe-service-location-text">' . esc_html($location) . '</span>';
                echo '</div>';
            }

            echo '</div>';

            // Output excerpt
            if ($atts['show_excerpt']) {
                echo '<div class="aqualuxe-service-excerpt">';
                the_excerpt();
                echo '</div>';
            }

            // Output button
            if ($atts['show_button']) {
                echo '<div class="aqualuxe-service-button">';
                echo '<a href="' . esc_url(get_permalink()) . '" class="button">' . esc_html($atts['button_text']) . '</a>';
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        // Reset post data
        wp_reset_postdata();
    } else {
        echo '<p>' . esc_html__('No services found.', 'aqualuxe') . '</p>';
    }

    // Return output buffer
    return ob_get_clean();
}

/**
 * Service shortcode
 *
 * @param array $atts
 * @return string
 */
function aqualuxe_service_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts([
        'id' => 0,
        'show_image' => true,
        'show_excerpt' => true,
        'show_button' => true,
        'button_text' => __('View Details', 'aqualuxe'),
    ], $atts);

    // Convert string values to boolean
    $atts['show_image'] = filter_var($atts['show_image'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_excerpt'] = filter_var($atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_button'] = filter_var($atts['show_button'], FILTER_VALIDATE_BOOLEAN);

    // Check if we have an ID
    if (empty($atts['id'])) {
        return '';
    }

    // Get service
    $service = get_post($atts['id']);

    // Check if service exists
    if (!$service || $service->post_type !== 'service' || $service->post_status !== 'publish') {
        return '';
    }

    // Get service meta
    $price = get_post_meta($service->ID, '_service_price', true);
    $sale_price = get_post_meta($service->ID, '_service_sale_price', true);
    $price_type = get_post_meta($service->ID, '_service_price_type', true);
    $duration = get_post_meta($service->ID, '_service_duration', true);
    $location = get_post_meta($service->ID, '_service_location', true);

    // Start output buffer
    ob_start();

    // Output service
    echo '<div class="aqualuxe-service-shortcode">';
    echo '<div class="aqualuxe-service">';
    echo '<div class="aqualuxe-service-inner">';

    // Output image
    if ($atts['show_image'] && has_post_thumbnail($service->ID)) {
        echo '<div class="aqualuxe-service-image">';
        echo '<a href="' . esc_url(get_permalink($service->ID)) . '">';
        echo get_the_post_thumbnail($service->ID, 'aqualuxe-card');
        echo '</a>';
        echo '</div>';
    }

    // Output content
    echo '<div class="aqualuxe-service-content">';

    // Output title
    echo '<h3 class="aqualuxe-service-title">';
    echo '<a href="' . esc_url(get_permalink($service->ID)) . '">' . esc_html($service->post_title) . '</a>';
    echo '</h3>';

    // Output meta
    echo '<div class="aqualuxe-service-meta">';

    // Output price
    if ($price) {
        echo '<div class="aqualuxe-service-price">';
        if ($sale_price) {
            echo '<del>' . esc_html(aqualuxe_format_price($price)) . '</del> ';
            echo '<ins>' . esc_html(aqualuxe_format_price($sale_price)) . '</ins>';
        } else {
            echo esc_html(aqualuxe_format_price($price));
        }

        if ($price_type) {
            echo ' <span class="aqualuxe-service-price-type">' . esc_html($price_type) . '</span>';
        }
        echo '</div>';
    }

    // Output duration
    if ($duration) {
        echo '<div class="aqualuxe-service-duration">';
        echo '<span class="aqualuxe-service-duration-icon"></span>';
        echo '<span class="aqualuxe-service-duration-text">' . esc_html($duration) . '</span>';
        echo '</div>';
    }

    // Output location
    if ($location) {
        echo '<div class="aqualuxe-service-location">';
        echo '<span class="aqualuxe-service-location-icon"></span>';
        echo '<span class="aqualuxe-service-location-text">' . esc_html($location) . '</span>';
        echo '</div>';
    }

    echo '</div>';

    // Output excerpt
    if ($atts['show_excerpt']) {
        echo '<div class="aqualuxe-service-excerpt">';
        echo wpautop(get_the_excerpt($service->ID));
        echo '</div>';
    }

    // Output button
    if ($atts['show_button']) {
        echo '<div class="aqualuxe-service-button">';
        echo '<a href="' . esc_url(get_permalink($service->ID)) . '" class="button">' . esc_html($atts['button_text']) . '</a>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    // Return output buffer
    return ob_get_clean();
}

/**
 * Service categories shortcode
 *
 * @param array $atts
 * @return string
 */
function aqualuxe_service_categories_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts([
        'layout' => 'grid',
        'columns' => 3,
        'show_count' => true,
        'show_image' => true,
        'show_description' => true,
        'parent' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
    ], $atts);

    // Convert string values to boolean
    $atts['show_count'] = filter_var($atts['show_count'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_image'] = filter_var($atts['show_image'], FILTER_VALIDATE_BOOLEAN);
    $atts['show_description'] = filter_var($atts['show_description'], FILTER_VALIDATE_BOOLEAN);

    // Set up query arguments
    $args = [
        'taxonomy' => 'service_category',
        'hide_empty' => true,
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'parent' => $atts['parent'],
    ];

    // Get categories
    $categories = aqualuxe_get_service_categories($args);

    // Start output buffer
    ob_start();

    // Check if we have categories
    if (!empty($categories) && !is_wp_error($categories)) {
        // Output categories
        echo '<div class="aqualuxe-service-categories-shortcode aqualuxe-service-categories-layout-' . esc_attr($atts['layout']) . ' aqualuxe-service-categories-columns-' . esc_attr($atts['columns']) . '">';
        echo '<div class="aqualuxe-service-categories-grid">';

        foreach ($categories as $category) {
            // Get category image
            $image = '';
            $image_id = get_term_meta($category->term_id, 'thumbnail_id', true);

            if ($image_id) {
                $image = wp_get_attachment_image($image_id, 'aqualuxe-card');
            }

            // Output category
            echo '<div class="aqualuxe-service-category">';
            echo '<div class="aqualuxe-service-category-inner">';

            // Output image
            if ($atts['show_image'] && $image) {
                echo '<div class="aqualuxe-service-category-image">';
                echo '<a href="' . esc_url(get_term_link($category)) . '">';
                echo $image;
                echo '</a>';
                echo '</div>';
            }

            // Output content
            echo '<div class="aqualuxe-service-category-content">';

            // Output title
            echo '<h3 class="aqualuxe-service-category-title">';
            echo '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
            echo '</h3>';

            // Output count
            if ($atts['show_count']) {
                echo '<div class="aqualuxe-service-category-count">';
                echo sprintf(
                    _n('%s service', '%s services', $category->count, 'aqualuxe'),
                    number_format_i18n($category->count)
                );
                echo '</div>';
            }

            // Output description
            if ($atts['show_description'] && $category->description) {
                echo '<div class="aqualuxe-service-category-description">';
                echo wpautop($category->description);
                echo '</div>';
            }

            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo '<p>' . esc_html__('No service categories found.', 'aqualuxe') . '</p>';
    }

    // Return output buffer
    return ob_get_clean();
}

/**
 * Service booking form shortcode
 *
 * @param array $atts
 * @return string
 */
function aqualuxe_service_booking_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts([
        'id' => 0,
        'title' => __('Book This Service', 'aqualuxe'),
        'button_text' => __('Book Now', 'aqualuxe'),
    ], $atts);

    // Check if we have an ID
    if (empty($atts['id'])) {
        return '';
    }

    // Get service
    $service = get_post($atts['id']);

    // Check if service exists
    if (!$service || $service->post_type !== 'service' || $service->post_status !== 'publish') {
        return '';
    }

    // Get service booking
    $booking_enabled = get_post_meta($service->ID, '_service_booking_enabled', true);
    $booking_type = get_post_meta($service->ID, '_service_booking_type', true);
    $booking_form = get_post_meta($service->ID, '_service_booking_form', true);

    // Check if booking is enabled
    if ($booking_enabled !== 'yes') {
        return '';
    }

    // Start output buffer
    ob_start();

    // Output booking form
    echo '<div class="aqualuxe-service-booking-shortcode">';
    echo '<div class="aqualuxe-service-booking">';

    // Output title
    if ($atts['title']) {
        echo '<h3 class="aqualuxe-service-booking-title">' . esc_html($atts['title']) . '</h3>';
    }

    // Output form
    if ($booking_type === 'form' && $booking_form && function_exists('wpcf7_contact_form')) {
        // Get Contact Form 7 form
        $form = wpcf7_contact_form($booking_form);

        if ($form) {
            echo '<div class="aqualuxe-service-booking-form">';
            echo do_shortcode('[contact-form-7 id="' . esc_attr($booking_form) . '"]');
            echo '</div>';
        } else {
            echo '<p>' . esc_html__('Booking form not found.', 'aqualuxe') . '</p>';
        }
    } elseif ($booking_type === 'calendar') {
        // Output calendar booking form
        echo '<div class="aqualuxe-service-booking-calendar">';
        echo '<p>' . esc_html__('Calendar booking is not available yet.', 'aqualuxe') . '</p>';
        echo '</div>';
    } elseif ($booking_type === 'external') {
        // Output external booking button
        echo '<div class="aqualuxe-service-booking-external">';
        echo '<a href="' . esc_url(get_permalink($service->ID)) . '" class="button">' . esc_html($atts['button_text']) . '</a>';
        echo '</div>';
    } else {
        echo '<p>' . esc_html__('Booking is not available for this service.', 'aqualuxe') . '</p>';
    }

    echo '</div>';
    echo '</div>';

    // Return output buffer
    return ob_get_clean();
}

/**
 * Services block render
 *
 * @param array $attributes
 * @return string
 */
function aqualuxe_services_block_render($attributes) {
    // Set up shortcode attributes
    $shortcode_atts = [
        'layout' => isset($attributes['layout']) ? $attributes['layout'] : 'grid',
        'columns' => isset($attributes['columns']) ? $attributes['columns'] : 3,
        'limit' => isset($attributes['limit']) ? $attributes['limit'] : -1,
        'orderby' => isset($attributes['orderby']) ? $attributes['orderby'] : 'date',
        'order' => isset($attributes['order']) ? $attributes['order'] : 'DESC',
        'show_image' => isset($attributes['showImage']) ? $attributes['showImage'] : true,
        'show_excerpt' => isset($attributes['showExcerpt']) ? $attributes['showExcerpt'] : true,
        'show_button' => isset($attributes['showButton']) ? $attributes['showButton'] : true,
        'button_text' => isset($attributes['buttonText']) ? $attributes['buttonText'] : __('View Details', 'aqualuxe'),
    ];

    // Add categories if specified
    if (isset($attributes['categories']) && !empty($attributes['categories'])) {
        $category_slugs = [];
        foreach ($attributes['categories'] as $category) {
            $term = get_term($category, 'service_category');
            if ($term && !is_wp_error($term)) {
                $category_slugs[] = $term->slug;
            }
        }
        $shortcode_atts['category'] = implode(',', $category_slugs);
    }

    // Add tags if specified
    if (isset($attributes['tags']) && !empty($attributes['tags'])) {
        $tag_slugs = [];
        foreach ($attributes['tags'] as $tag) {
            $term = get_term($tag, 'service_tag');
            if ($term && !is_wp_error($term)) {
                $tag_slugs[] = $term->slug;
            }
        }
        $shortcode_atts['tag'] = implode(',', $tag_slugs);
    }

    // Add class if specified
    $class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';

    // Build shortcode
    $shortcode = '[services';
    foreach ($shortcode_atts as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    $shortcode .= ']';

    // Return shortcode output with wrapper
    return '<div class="wp-block-aqualuxe-services' . esc_attr($class) . '">' . do_shortcode($shortcode) . '</div>';
}

/**
 * Service block render
 *
 * @param array $attributes
 * @return string
 */
function aqualuxe_service_block_render($attributes) {
    // Set up shortcode attributes
    $shortcode_atts = [
        'id' => isset($attributes['id']) ? $attributes['id'] : 0,
        'show_image' => isset($attributes['showImage']) ? $attributes['showImage'] : true,
        'show_excerpt' => isset($attributes['showExcerpt']) ? $attributes['showExcerpt'] : true,
        'show_button' => isset($attributes['showButton']) ? $attributes['showButton'] : true,
        'button_text' => isset($attributes['buttonText']) ? $attributes['buttonText'] : __('View Details', 'aqualuxe'),
    ];

    // Add class if specified
    $class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';

    // Build shortcode
    $shortcode = '[service';
    foreach ($shortcode_atts as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    $shortcode .= ']';

    // Return shortcode output with wrapper
    return '<div class="wp-block-aqualuxe-service' . esc_attr($class) . '">' . do_shortcode($shortcode) . '</div>';
}

/**
 * Service categories block render
 *
 * @param array $attributes
 * @return string
 */
function aqualuxe_service_categories_block_render($attributes) {
    // Set up shortcode attributes
    $shortcode_atts = [
        'layout' => isset($attributes['layout']) ? $attributes['layout'] : 'grid',
        'columns' => isset($attributes['columns']) ? $attributes['columns'] : 3,
        'show_count' => isset($attributes['showCount']) ? $attributes['showCount'] : true,
        'show_image' => isset($attributes['showImage']) ? $attributes['showImage'] : true,
        'show_description' => isset($attributes['showDescription']) ? $attributes['showDescription'] : true,
    ];

    // Add class if specified
    $class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';

    // Build shortcode
    $shortcode = '[service_categories';
    foreach ($shortcode_atts as $key => $value) {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    $shortcode .= ']';

    // Return shortcode output with wrapper
    return '<div class="wp-block-aqualuxe-service-categories' . esc_attr($class) . '">' . do_shortcode($shortcode) . '</div>';
}

/**
 * Service booking block render
 *
 * @param array $attributes
 * @return string
 */
function aqualuxe_service_booking_block_render($attributes) {
    // Set up shortcode attributes
    $shortcode_atts = [
        'id' => isset($attributes['serviceId']) ? $attributes['serviceId'] : 0,
        'title' => isset($attributes['title']) ? $attributes['title'] : __('Book This Service', 'aqualuxe'),
        'button_text' => isset($attributes['buttonText']) ? $attributes['buttonText'] : __('Book Now', 'aqualuxe'),
    ];

    // Add class if specified
    $class = isset($attributes['className']) ? ' ' . $attributes['className'] : '';

    // Build shortcode
    $shortcode = '[service_booking';
    foreach ($shortcode_atts as $key => $value) {
        $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    $shortcode .= ']';

    // Return shortcode output with wrapper
    return '<div class="wp-block-aqualuxe-service-booking' . esc_attr($class) . '">' . do_shortcode($shortcode) . '</div>';
}