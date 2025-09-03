<?php
namespace Aqualuxe\Shortcodes;

use Aqualuxe\Support\Container;
use Aqualuxe\Modules\Forms\Contact_Form;
use Aqualuxe\Modules\Forms\Wholesale_Form;
use Aqualuxe\Modules\Forms\Export_Quote_Form;

class Shortcodes_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('init', function () {
            add_shortcode('aqualuxe_contact_form', [Contact_Form::class, 'render']);
            add_shortcode('aqualuxe_wholesale_form', [Wholesale_Form::class, 'render']);
            add_shortcode('aqualuxe_export_quote', [Export_Quote_Form::class, 'render']);
        });

        add_action('admin_post_nopriv_aqualuxe_contact_submit', [$this, 'handle_contact']);
        add_action('admin_post_aqualuxe_contact_submit', [$this, 'handle_contact']);

        add_action('admin_post_nopriv_aqualuxe_wholesale_submit', [$this, 'handle_wholesale']);
        add_action('admin_post_aqualuxe_wholesale_submit', [$this, 'handle_wholesale']);

        add_action('admin_post_nopriv_aqualuxe_export_submit', [$this, 'handle_export']);
        add_action('admin_post_aqualuxe_export_submit', [$this, 'handle_export']);
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

    public function handle_wholesale(): void
    {
        check_admin_referer('aqualuxe_wholesale', '_aqlx');
        $data = [
            'business_name' => sanitize_text_field($_POST['business_name'] ?? ''),
            'contact_person' => sanitize_text_field($_POST['contact_person'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'country' => sanitize_text_field($_POST['country'] ?? ''),
            'website' => esc_url_raw($_POST['website'] ?? ''),
            'business_type' => sanitize_text_field($_POST['business_type'] ?? ''),
            'monthly_volume' => sanitize_text_field($_POST['monthly_volume'] ?? ''),
            'message' => sanitize_textarea_field($_POST['message'] ?? ''),
        ];
        if (empty($data['business_name']) || empty($data['contact_person']) || empty($data['email']) || empty($data['country'])) {
            wp_safe_redirect(add_query_arg('error', '1', wp_get_referer() ?: home_url('/')));
            exit;
        }
        $to = get_option('admin_email');
        $subject = sprintf('[AquaLuxe] Wholesale Application – %s', $data['business_name']);
        $body = "Business: {$data['business_name']}\nContact: {$data['contact_person']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nCountry: {$data['country']}\nWebsite: {$data['website']}\nType: {$data['business_type']}\nVolume: {$data['monthly_volume']}\n\nMessage:\n{$data['message']}\n";
        wp_mail($to, $subject, $body);
        wp_safe_redirect(add_query_arg('submitted', '1', wp_get_referer() ?: home_url('/')));
        exit;
    }

    public function handle_export(): void
    {
        check_admin_referer('aqualuxe_export', '_aqlx');
        $data = [
            'name' => sanitize_text_field($_POST['name'] ?? ''),
            'email' => sanitize_email($_POST['email'] ?? ''),
            'phone' => sanitize_text_field($_POST['phone'] ?? ''),
            'country' => sanitize_text_field($_POST['country'] ?? ''),
            'airport' => sanitize_text_field($_POST['airport'] ?? ''),
            'incoterm' => sanitize_text_field($_POST['incoterm'] ?? ''),
            'date' => sanitize_text_field($_POST['date'] ?? ''),
            'items' => sanitize_textarea_field($_POST['items'] ?? ''),
            'message' => sanitize_textarea_field($_POST['message'] ?? ''),
        ];
        if (empty($data['name']) || empty($data['email']) || empty($data['country'])) {
            wp_safe_redirect(add_query_arg('error', '1', wp_get_referer() ?: home_url('/')));
            exit;
        }
        $to = get_option('admin_email');
        $subject = sprintf('[AquaLuxe] Export Quote – %s', $data['name']);
        $body = "Name: {$data['name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nCountry: {$data['country']}\nAirport: {$data['airport']}\nIncoterm: {$data['incoterm']}\nDate: {$data['date']}\n\nItems:\n{$data['items']}\n\nMessage:\n{$data['message']}\n";
        wp_mail($to, $subject, $body);
        wp_safe_redirect(add_query_arg('submitted', '1', wp_get_referer() ?: home_url('/')));
        exit;
    }
}
