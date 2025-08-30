<?php
namespace AquaLuxe\Modules\Contact;

\add_shortcode('aqualuxe_contact', __NAMESPACE__.'\contact_form');
\add_action('admin_post_nopriv_aqualuxe_contact', __NAMESPACE__.'\handle');
\add_action('admin_post_aqualuxe_contact', __NAMESPACE__.'\handle');

function contact_form(){
    \ob_start();
    ?>
    <form method="post" action="<?php echo \esc_url(\admin_url('admin-post.php')); ?>" class="grid gap-3 max-w-xl">
      <input type="hidden" name="action" value="aqualuxe_contact" />
      <?php \wp_nonce_field('aqualuxe_contact'); ?>
      <label><?php \esc_html_e('Name', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="text" name="name" required /></label>
      <label><?php \esc_html_e('Email', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="email" name="email" required /></label>
      <label><?php \esc_html_e('Message', 'aqualuxe'); ?><textarea class="block w-full px-3 py-2 border rounded" name="message" rows="5" required></textarea></label>
      <button class="btn-primary"><?php \esc_html_e('Send', 'aqualuxe'); ?></button>
    </form>
    <?php
    // Map (opt-in): show only if Place/Embed key exists in Customizer
    $place = \get_theme_mod('aqualuxe_map_embed');
    if ($place) {
        echo '<div class="mt-6 aspect-video">';
        echo '<iframe class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="'.\esc_url($place).'"></iframe>';
        echo '</div>';
    }
    return \ob_get_clean();
}

function handle(){
    \check_admin_referer('aqualuxe_contact');
    $name = \sanitize_text_field($_POST['name'] ?? '');
    $email = \sanitize_email($_POST['email'] ?? '');
    $message = \wp_kses_post($_POST['message'] ?? '');
    if (!$name || !$email || !$message) \wp_die(\__('Invalid data', 'aqualuxe'));
    \wp_mail(\get_option('admin_email'), 'AquaLuxe Contact', $message, ['Reply-To: '.$email]);
    \wp_safe_redirect(\wp_get_referer() ?: \home_url('/'));
    exit;
}
