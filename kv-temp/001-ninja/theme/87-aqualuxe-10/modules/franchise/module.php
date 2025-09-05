<?php
add_action('init', function(){
    register_post_type('franchise_inquiry', [
        'labels' => [ 'name' => __('Franchise Inquiries','aqualuxe'), 'singular_name' => __('Franchise Inquiry','aqualuxe') ],
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor','custom-fields'],
    ]);
});

add_shortcode('aqualuxe_franchise_inquiry', function(){
    ob_start();
    ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="grid gap-3 max-w-xl">
      <?php wp_nonce_field('aqlx_franchise'); ?>
      <input type="hidden" name="action" value="aqlx_franchise_submit">
      <label><?php esc_html_e('Company','aqualuxe'); ?> <input required name="company" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Contact Name','aqualuxe'); ?> <input required name="name" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Email','aqualuxe'); ?> <input type="email" required name="email" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Country','aqualuxe'); ?> <input required name="country" class="border rounded px-3 py-2 w-full"></label>
      <label><?php esc_html_e('Message','aqualuxe'); ?> <textarea name="message" class="border rounded px-3 py-2 w-full" rows="4"></textarea></label>
      <button class="btn btn-primary"><?php esc_html_e('Submit Inquiry','aqualuxe'); ?></button>
    </form>
    <?php
    return (string) ob_get_clean();
});

function aqlx_franchise_handle_submit(){
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'aqlx_franchise')) {
        wp_die(__('Invalid request','aqualuxe'));
    }
    $company = sanitize_text_field(wp_unslash($_POST['company'] ?? ''));
    $name = sanitize_text_field(wp_unslash($_POST['name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $country = sanitize_text_field(wp_unslash($_POST['country'] ?? ''));
    $message = wp_kses_post(wp_unslash($_POST['message'] ?? ''));
    $title = trim($company . ' - ' . $country);
    wp_insert_post([
        'post_type' => 'franchise_inquiry',
        'post_title' => $title ?: __('Franchise Inquiry','aqualuxe'),
        'post_status' => 'publish',
        'post_content' => $message,
        'meta_input' => compact('name','email','country'),
    ]);
    wp_safe_redirect(wp_get_referer() ?: home_url('/'));
    exit;
}
add_action('admin_post_nopriv_aqlx_franchise_submit', 'aqlx_franchise_handle_submit');
add_action('admin_post_aqlx_franchise_submit', 'aqlx_franchise_handle_submit');
