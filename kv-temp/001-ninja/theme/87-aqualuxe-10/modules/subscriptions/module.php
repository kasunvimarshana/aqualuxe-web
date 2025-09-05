<?php
// Membership lead capture
add_action('init', function(){
    register_post_type('membership_lead', [
        'labels' => [ 'name' => __('Membership Leads','aqualuxe'), 'singular_name' => __('Membership Lead','aqualuxe') ],
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','custom-fields'],
    ]);
});

add_shortcode('aqualuxe_subscribe_form', function(){
    ob_start();
    ?>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex gap-2 max-w-xl">
      <?php wp_nonce_field('aqlx_subscribe'); ?>
      <input type="hidden" name="action" value="aqlx_subscribe_submit">
      <label class="sr-only" for="aqlx-sub-email"><?php esc_html_e('Email','aqualuxe'); ?></label>
      <input id="aqlx-sub-email" name="email" type="email" required class="flex-1 rounded border px-3 py-2" placeholder="you@example.com" />
      <button class="btn btn-primary"><?php esc_html_e('Subscribe','aqualuxe'); ?></button>
    </form>
    <?php
    return (string) ob_get_clean();
});

function aqlx_subscribe_handle_submit(){
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'aqlx_subscribe')) {
        wp_die(__('Invalid request','aqualuxe'));
    }
    $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    if ($email) {
        wp_insert_post([
            'post_type' => 'membership_lead',
            'post_title' => $email,
            'post_status' => 'publish',
        ]);
    }
    wp_safe_redirect(wp_get_referer() ?: home_url('/'));
    exit;
}
add_action('admin_post_nopriv_aqlx_subscribe_submit', 'aqlx_subscribe_handle_submit');
add_action('admin_post_aqlx_subscribe_submit', 'aqlx_subscribe_handle_submit');
