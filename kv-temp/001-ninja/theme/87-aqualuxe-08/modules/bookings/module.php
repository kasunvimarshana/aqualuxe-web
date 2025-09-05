<?php
// Booking Requests CPT + shortcode form (progressive enhancement)
add_action('init', function(){
    register_post_type('booking_request', [
        'labels' => [ 'name' => __('Booking Requests','aqualuxe'), 'singular_name' => __('Booking Request','aqualuxe') ],
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor','custom-fields'],
    ]);
});

// Shortcode: [aqualuxe_booking_form]
add_shortcode('aqualuxe_booking_form', function(){
    ob_start();
    ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="grid gap-3 max-w-xl">
      <?php wp_nonce_field('aqlx_booking'); ?>
      <input type="hidden" name="action" value="aqlx_booking_submit">
      <label><?php esc_html_e('Name','aqualuxe'); ?> <input required name="name" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Email','aqualuxe'); ?> <input type="email" required name="email" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Service','aqualuxe'); ?> <input required name="service" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Preferred Date','aqualuxe'); ?> <input type="date" name="date" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Notes','aqualuxe'); ?> <textarea name="notes" class="border rounded px-3 py-2 w-full" rows="4"></textarea></label>
      <button class="btn btn-primary"><?php esc_html_e('Request Booking','aqualuxe'); ?></button>
    </form>
    <?php
    return (string) ob_get_clean();
});

function aqlx_booking_handle_submit(){
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'aqlx_booking')) {
        wp_die(__('Invalid request','aqualuxe'));
    }
    if (!is_user_logged_in()) {
        // Allow guests but still sanitize
    }
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $service = sanitize_text_field(wp_unslash($_POST['service'] ?? ''));
    $date = sanitize_text_field(wp_unslash($_POST['date'] ?? ''));
    $notes = wp_kses_post(wp_unslash($_POST['notes'] ?? ''));
    $title = trim($name . ' - ' . $service);
    $post_id = wp_insert_post([
        'post_type' => 'booking_request',
        'post_title' => $title ?: __('Booking Request','aqualuxe'),
        'post_status' => 'publish',
        'post_content' => $notes,
        'meta_input' => compact('email','service','date'),
    ]);
    wp_safe_redirect(wp_get_referer() ?: home_url('/'));
    exit;
}
add_action('admin_post_nopriv_aqlx_booking_submit', 'aqlx_booking_handle_submit');
add_action('admin_post_aqlx_booking_submit', 'aqlx_booking_handle_submit');
