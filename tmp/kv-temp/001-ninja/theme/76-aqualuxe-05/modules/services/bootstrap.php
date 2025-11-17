<?php
defined('ABSPATH') || exit;

add_action('init', function () {
    // Service CPT
    register_post_type('service', [
        'label' => __('Services', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-hammer',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'services'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('service_type', 'service', [
        'label' => __('Service Types', 'aqualuxe'),
        'hierarchical' => true,
        'rewrite' => ['slug' => 'service-type'],
        'show_in_rest' => true,
    ]);

    // Booking CPT (simplified)
    register_post_type('booking', [
        'label' => __('Bookings', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-calendar',
        'supports' => ['title', 'editor'],
        'show_in_rest' => true,
    ]);
});

// Simple booking form shortcode
add_shortcode('aqlx_booking_form', function ($atts) {
    $atts = shortcode_atts(['service' => 0], $atts, 'aqlx_booking_form');
    ob_start();
    ?>
    <form method="post" class="grid gap-3 max-w-lg">
      <?php wp_nonce_field('aqlx_booking'); ?>
      <input type="hidden" name="aqlx_booking" value="1" />
      <input type="hidden" name="service_id" value="<?php echo esc_attr((int)$atts['service']); ?>" />
      <label><?php esc_html_e('Your Name', 'aqualuxe'); ?>
        <input type="text" name="name" class="border rounded px-3 py-2 w-full" required />
      </label>
      <label><?php esc_html_e('Email', 'aqualuxe'); ?>
        <input type="email" name="email" class="border rounded px-3 py-2 w-full" required />
      </label>
      <label><?php esc_html_e('Preferred Date', 'aqualuxe'); ?>
        <input type="date" name="date" class="border rounded px-3 py-2 w-full" required />
      </label>
      <button class="aqlx-btn" type="submit"><?php esc_html_e('Request Booking', 'aqualuxe'); ?></button>
    </form>
    <?php
    return ob_get_clean();
});

// Handle booking submissions
add_action('init', function () {
    if (!isset($_POST['aqlx_booking'])) return;
    if (!wp_verify_nonce($_POST['_wpnonce'] ?? '', 'aqlx_booking')) return;
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $date = sanitize_text_field($_POST['date'] ?? '');
    $service_id = absint($_POST['service_id'] ?? 0);
    $post_id = wp_insert_post([
        'post_type' => 'booking',
        'post_title' => sprintf(__('Booking: %s on %s', 'aqualuxe'), $name, $date),
        'post_content' => sprintf("Service #%d\nEmail: %s", $service_id, $email),
        'post_status' => 'publish',
    ]);
    if ($post_id && !is_wp_error($post_id)) {
        wp_safe_redirect(add_query_arg('booked', '1', wp_get_referer() ?: home_url('/')));
        exit;
    }
});
