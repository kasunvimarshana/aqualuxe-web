<?php
/** Franchise/Licensing module: inquiry form (nonces + mail) */

add_shortcode('aqualuxe_franchise_form', function(){
    ob_start(); ?>
        <?php if (!empty($_GET['sent']) && $_GET['sent']==='1'): ?>
            <div class="aqlx-notice aqlx-notice--success" role="status"><?php echo esc_html__('Thanks! We\'ll be in touch shortly.','aqualuxe'); ?></div>
        <?php endif; ?>
        <?php if (!empty($_GET['fr_err']) && $_GET['fr_err']==='rate'): ?>
            <div class="aqlx-notice aqlx-notice--error" role="alert"><?php echo esc_html__('You\'re sending requests too quickly. Please wait a minute and try again.','aqualuxe'); ?></div>
        <?php endif; ?>
    <form method="post" class="grid gap-3 max-w-xl">
      <?php aqualuxe_nonce_field('aqlx_franchise'); ?>
            <input type="text" name="company" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;" tabindex="-1" autocomplete="off" aria-hidden="true" />
      <input class="border px-3 py-2" name="name" placeholder="<?php echo esc_attr__('Full Name','aqualuxe'); ?>" required />
      <input class="border px-3 py-2" name="email" type="email" placeholder="<?php echo esc_attr__('Email','aqualuxe'); ?>" required />
      <textarea class="border px-3 py-2" name="message" placeholder="<?php echo esc_attr__('Tell us about your market and plans','aqualuxe'); ?>" required></textarea>
      <button class="btn"><?php esc_html_e('Request Franchise Info','aqualuxe'); ?></button>
    </form>
    <?php return ob_get_clean();
});

add_action('init', function(){
    if (!empty($_POST['_aqualuxe_nonce']) && wp_verify_nonce($_POST['_aqualuxe_nonce'], 'aqlx_franchise')){
                // Honeypot
                if (!empty($_POST['company'])) return;
                // Simple rate limit per IP
            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
                $key = 'aqlx_franchise_' . md5($ip);
                $last = (int) get_transient($key);
            if ($last && (time() - $last) < 60) { wp_safe_redirect(add_query_arg('fr_err','rate')); exit; } // 1/minute
                set_transient($key, time(), MINUTE_IN_SECONDS);
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $msg = wp_strip_all_tags($_POST['message'] ?? '');
        wp_mail(get_option('admin_email'), 'Franchise Inquiry: ' . $name, $msg . "\nFrom: " . $email);
        wp_safe_redirect(add_query_arg('sent','1'));
        exit;
    }
});
