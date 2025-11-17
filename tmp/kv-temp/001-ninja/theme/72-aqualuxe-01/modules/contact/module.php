<?php
namespace AquaLuxe\Modules\Contact;

class Module {
    public static function init(): void {
        add_shortcode('aqlx_contact_form', [__CLASS__, 'render_form']);
        add_action('admin_post_nopriv_aqlx_contact', [__CLASS__, 'handle']);
        add_action('admin_post_aqlx_contact', [__CLASS__, 'handle']);
    }

    public static function render_form($atts = []): string {
        $action = esc_url(admin_url('admin-post.php'));
        $nonce  = wp_create_nonce('aqlx_contact');
        ob_start();
        ?>
        <form method="post" action="<?php echo $action; ?>" class="grid gap-3 max-w-xl">
            <input type="hidden" name="action" value="aqlx_contact" />
            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>" />
            <label>
                <span><?php esc_html_e('Name','aqualuxe'); ?></span>
                <input required class="border rounded px-3 py-2 w-full" type="text" name="name" />
            </label>
            <label>
                <span><?php esc_html_e('Email','aqualuxe'); ?></span>
                <input required class="border rounded px-3 py-2 w-full" type="email" name="email" />
            </label>
            <label>
                <span><?php esc_html_e('Message','aqualuxe'); ?></span>
                <textarea required class="border rounded px-3 py-2 w-full" name="message"></textarea>
            </label>
            <button class="btn-primary" type="submit"><?php esc_html_e('Send','aqualuxe'); ?></button>
        </form>
        <?php
        return ob_get_clean();
    }

    public static function handle(): void {
        check_admin_referer('aqlx_contact');
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email= sanitize_email($_POST['email'] ?? '');
        $msg  = wp_kses_post($_POST['message'] ?? '');
        wp_mail(get_option('admin_email'), '[AquaLuxe] Contact: ' . $name, $msg . "\n\nFrom: " . $email);
        wp_safe_redirect(add_query_arg('sent', '1', wp_get_referer() ?: home_url('/contact')));
        exit;
    }
}
