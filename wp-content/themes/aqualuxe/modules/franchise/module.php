<?php
namespace AquaLuxe\Modules\Franchise;

add_action('init', function(){
    register_post_type('partner', [
        'label' => __('Partners', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor'],
        'show_in_rest' => true,
    ]);
});

add_shortcode('franchise_inquiry', function(){
    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="grid gap-3 max-w-xl">
      <input type="hidden" name="action" value="aqualuxe_franchise" />
      <?php wp_nonce_field('aqualuxe_franchise'); ?>
      <label><?php esc_html_e('Company', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="text" name="company" required /></label>
      <label><?php esc_html_e('Email', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="email" name="email" required /></label>
      <label><?php esc_html_e('Message', 'aqualuxe'); ?><textarea class="block w-full px-3 py-2 border rounded" name="message" rows="5" required></textarea></label>
      <button class="btn-primary"><?php esc_html_e('Submit', 'aqualuxe'); ?></button>
    </form>
    <?php
    return ob_get_clean();
});

add_action('admin_post_nopriv_aqualuxe_franchise', __NAMESPACE__.'\handle');
add_action('admin_post_aqualuxe_franchise', __NAMESPACE__.'\handle');
function handle(){
    check_admin_referer('aqualuxe_franchise');
    $title = sanitize_text_field($_POST['company'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $msg = wp_kses_post($_POST['message'] ?? '');
    $id = wp_insert_post(['post_type' => 'partner', 'post_status' => 'pending', 'post_title' => $title, 'post_content' => $msg]);
    if ($id) update_post_meta($id, '_email', $email);
    wp_safe_redirect(wp_get_referer() ?: home_url('/'));
    exit;
}
