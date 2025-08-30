<?php
namespace AquaLuxe\Modules\Bookings;

\add_action('init', function(){
    \register_post_type('booking_request', [
        'label' => \__('Bookings', 'aqualuxe'),
        'public' => false,
        'show_ui' => true,
        'supports' => ['title','editor'],
    ]);
});

\add_shortcode('aqualuxe_booking', function(){
    \ob_start(); ?>
    <form method="post" action="<?php echo \esc_url(\admin_url('admin-post.php')); ?>" class="grid gap-3 max-w-xl">
      <input type="hidden" name="action" value="aqualuxe_booking" />
      <?php \wp_nonce_field('aqualuxe_booking'); ?>
      <label><?php \esc_html_e('Name', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="text" name="name" required /></label>
      <label><?php \esc_html_e('Email', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="email" name="email" required /></label>
      <label><?php \esc_html_e('Preferred Date', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="date" name="date" required /></label>
      <label><?php \esc_html_e('Notes', 'aqualuxe'); ?><textarea class="block w-full px-3 py-2 border rounded" name="notes" rows="4"></textarea></label>
      <button class="btn-primary"><?php \esc_html_e('Request Booking', 'aqualuxe'); ?></button>
    </form>
    <?php return \ob_get_clean();
});

\add_action('admin_post_nopriv_aqualuxe_booking', __NAMESPACE__.'\handle');
\add_action('admin_post_aqualuxe_booking', __NAMESPACE__.'\handle');
function handle(){
    \check_admin_referer('aqualuxe_booking');
    $name = \sanitize_text_field($_POST['name'] ?? '');
    $email = \sanitize_email($_POST['email'] ?? '');
    $date = \sanitize_text_field($_POST['date'] ?? '');
    $notes = \wp_kses_post($_POST['notes'] ?? '');
    $id = \wp_insert_post(['post_type' => 'booking_request', 'post_status' => 'pending', 'post_title' => $name.' - '.$date, 'post_content' => $notes]);
    if ($id) \update_post_meta($id, '_email', $email);
    \wp_safe_redirect(\wp_get_referer() ?: \home_url('/'));
    exit;
}
