<?php
namespace AquaLuxe\Modules\Franchise;

class Module {
    public static function init(): void {
        add_shortcode('aqlx_franchise_form', [__CLASS__, 'form']);
        add_action('admin_post_nopriv_aqlx_franchise', [__CLASS__, 'handle']);
        add_action('admin_post_aqlx_franchise', [__CLASS__, 'handle']);
    }
    public static function form(): string {
        $action = esc_url(admin_url('admin-post.php'));
        $nonce = wp_create_nonce('aqlx_franchise');
        return '<form method="post" action="' . $action . '" class="grid gap-3 max-w-xl">'
            . '<input type="hidden" name="action" value="aqlx_franchise" />'
            . '<input type="hidden" name="_wpnonce" value="' . esc_attr($nonce) . '" />'
            . '<label><span>' . esc_html__('Company','aqualuxe') . '</span><input class="border rounded px-3 py-2 w-full" name="company" required></label>'
            . '<label><span>' . esc_html__('Country','aqualuxe') . '</span><input class="border rounded px-3 py-2 w-full" name="country" required></label>'
            . '<label><span>' . esc_html__('Email','aqualuxe') . '</span><input class="border rounded px-3 py-2 w-full" type="email" name="email" required></label>'
            . '<button class="btn-primary" type="submit">' . esc_html__('Submit Inquiry','aqualuxe') . '</button>'
            . '</form>';
    }
    public static function handle(): void {
        check_admin_referer('aqlx_franchise');
        $msg = sprintf("Company: %s\nCountry: %s\nEmail: %s",
            sanitize_text_field($_POST['company'] ?? ''),
            sanitize_text_field($_POST['country'] ?? ''),
            sanitize_email($_POST['email'] ?? '')
        );
        wp_mail(get_option('admin_email'), '[AquaLuxe] Franchise Inquiry', $msg);
        wp_safe_redirect(add_query_arg('sent','1', wp_get_referer() ?: home_url('/')));
        exit;
    }
}
