<?php
namespace Aqualuxe\Shortcodes;

use Aqualuxe\Support\Container;
use Aqualuxe\Modules\Forms\Contact_Form;

class Shortcodes_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('init', function () {
            add_shortcode('aqualuxe_contact_form', [Contact_Form::class, 'render']);
        });

        add_action('admin_post_nopriv_aqualuxe_contact_submit', [$this, 'handle_contact']);
        add_action('admin_post_aqualuxe_contact_submit', [$this, 'handle_contact']);
    }

    public function boot(Container $c): void {}

    public function handle_contact(): void
    {
        check_admin_referer('aqualuxe_contact', '_aqlx');
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        // In real projects, send email or create a post, with error handling.
        wp_safe_redirect(add_query_arg('submitted', '1', wp_get_referer() ?: home_url('/')));
        exit;
    }
}
