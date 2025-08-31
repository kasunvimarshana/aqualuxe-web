<?php
/** Franchise/Licensing module: inquiry form (nonces + mail) */

add_shortcode('aqualuxe_franchise_form', function(){
    ob_start(); ?>
    <form method="post" class="grid gap-3 max-w-xl">
      <?php aqualuxe_nonce_field('aqlx_franchise'); ?>
      <input class="border px-3 py-2" name="name" placeholder="<?php echo esc_attr__('Full Name','aqualuxe'); ?>" required />
      <input class="border px-3 py-2" name="email" type="email" placeholder="<?php echo esc_attr__('Email','aqualuxe'); ?>" required />
      <textarea class="border px-3 py-2" name="message" placeholder="<?php echo esc_attr__('Tell us about your market and plans','aqualuxe'); ?>" required></textarea>
      <button class="btn"><?php esc_html_e('Request Franchise Info','aqualuxe'); ?></button>
    </form>
    <?php return ob_get_clean();
});

add_action('init', function(){
    if (!empty($_POST['_aqualuxe_nonce']) && wp_verify_nonce($_POST['_aqualuxe_nonce'], 'aqlx_franchise')){
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $msg = wp_strip_all_tags($_POST['message'] ?? '');
        wp_mail(get_option('admin_email'), 'Franchise Inquiry: ' . $name, $msg . "\nFrom: " . $email);
        wp_safe_redirect(add_query_arg('sent','1'));
        exit;
    }
});
