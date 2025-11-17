<?php
namespace Aqualuxe\Shortcodes;

use Aqualuxe\Support\Container;
use Aqualuxe\Modules\Forms\Contact_Form;
use Aqualuxe\Modules\Forms\Wholesale_Form;
use Aqualuxe\Modules\Forms\Export_Quote_Form;
use Aqualuxe\Shortcodes\Wishlist_Shortcode;

class Shortcodes_Service_Provider
{
    public function register(Container $c): void
    {
        if (\function_exists('add_action')) {
            \call_user_func('add_action', 'init', function () {
                if (\function_exists('add_shortcode')) {
                    \call_user_func('add_shortcode', 'aqualuxe_contact_form', [Contact_Form::class, 'render']);
                    \call_user_func('add_shortcode', 'aqualuxe_wholesale_form', [Wholesale_Form::class, 'render']);
                    \call_user_func('add_shortcode', 'aqualuxe_export_quote', [Export_Quote_Form::class, 'render']);
                    \call_user_func('add_shortcode', 'aqualuxe_wishlist', [Wishlist_Shortcode::class, 'render']);
                }
            });

            \call_user_func('add_action', 'admin_post_nopriv_aqualuxe_contact_submit', [$this, 'handle_contact']);
            \call_user_func('add_action', 'admin_post_aqualuxe_contact_submit', [$this, 'handle_contact']);

            \call_user_func('add_action', 'admin_post_nopriv_aqualuxe_wholesale_submit', [$this, 'handle_wholesale']);
            \call_user_func('add_action', 'admin_post_aqualuxe_wholesale_submit', [$this, 'handle_wholesale']);

            \call_user_func('add_action', 'admin_post_nopriv_aqualuxe_export_submit', [$this, 'handle_export']);
            \call_user_func('add_action', 'admin_post_aqualuxe_export_submit', [$this, 'handle_export']);
        }
    }

    public function boot(Container $c): void {}

    public function handle_contact(): void
    {
    if (\function_exists('check_admin_referer')) { \call_user_func('check_admin_referer', 'aqualuxe_contact', '_aqlx'); }
    $name = \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['name'] ?? '') : ($_POST['name'] ?? '');
    $email = \function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? '');
    $message = \function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['message'] ?? '') : ($_POST['message'] ?? '');

        // In real projects, send email or create a post, with error handling.
    $ref = \function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null;
    $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
    $redir = \function_exists('add_query_arg') ? \call_user_func('add_query_arg', 'submitted', '1', $ref ?: $home) : ($home . '?submitted=1');
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
        exit;
    }

    public function handle_wholesale(): void
    {
        if (\function_exists('check_admin_referer')) { \call_user_func('check_admin_referer', 'aqualuxe_wholesale', '_aqlx'); }
        $data = [
            'business_name' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['business_name'] ?? '') : ($_POST['business_name'] ?? ''),
            'contact_person' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['contact_person'] ?? '') : ($_POST['contact_person'] ?? ''),
            'email' => \function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? ''),
            'phone' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['phone'] ?? '') : ($_POST['phone'] ?? ''),
            'country' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['country'] ?? '') : ($_POST['country'] ?? ''),
            'website' => \function_exists('esc_url_raw') ? \call_user_func('esc_url_raw', $_POST['website'] ?? '') : ($_POST['website'] ?? ''),
            'business_type' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['business_type'] ?? '') : ($_POST['business_type'] ?? ''),
            'monthly_volume' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['monthly_volume'] ?? '') : ($_POST['monthly_volume'] ?? ''),
            'message' => \function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['message'] ?? '') : ($_POST['message'] ?? ''),
        ];
        if (empty($data['business_name']) || empty($data['contact_person']) || empty($data['email']) || empty($data['country'])) {
            $ref = \function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null;
            $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
            $redir = \function_exists('add_query_arg') ? \call_user_func('add_query_arg', 'error', '1', $ref ?: $home) : ($home . '?error=1');
            if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
            exit;
        }
        $to = \function_exists('get_option') ? \call_user_func('get_option', 'admin_email') : '';
        $subject = sprintf('[AquaLuxe] Wholesale Application – %s', $data['business_name']);
        $body = "Business: {$data['business_name']}\nContact: {$data['contact_person']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nCountry: {$data['country']}\nWebsite: {$data['website']}\nType: {$data['business_type']}\nVolume: {$data['monthly_volume']}\n\nMessage:\n{$data['message']}\n";
        if (\function_exists('wp_mail')) { \call_user_func('wp_mail', $to, $subject, $body); }
        $ref = \function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null;
        $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
        $redir = \function_exists('add_query_arg') ? \call_user_func('add_query_arg', 'submitted', '1', $ref ?: $home) : ($home . '?submitted=1');
        if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
        exit;
    }

    public function handle_export(): void
    {
        if (\function_exists('check_admin_referer')) { \call_user_func('check_admin_referer', 'aqualuxe_export', '_aqlx'); }
        $data = [
            'name' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['name'] ?? '') : ($_POST['name'] ?? ''),
            'email' => \function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? ''),
            'phone' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['phone'] ?? '') : ($_POST['phone'] ?? ''),
            'country' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['country'] ?? '') : ($_POST['country'] ?? ''),
            'airport' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['airport'] ?? '') : ($_POST['airport'] ?? ''),
            'incoterm' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['incoterm'] ?? '') : ($_POST['incoterm'] ?? ''),
            'date' => \function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['date'] ?? '') : ($_POST['date'] ?? ''),
            'items' => \function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['items'] ?? '') : ($_POST['items'] ?? ''),
            'message' => \function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['message'] ?? '') : ($_POST['message'] ?? ''),
        ];
        if (empty($data['name']) || empty($data['email']) || empty($data['country'])) {
            $ref = \function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null;
            $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
            $redir = \function_exists('add_query_arg') ? \call_user_func('add_query_arg', 'error', '1', $ref ?: $home) : ($home . '?error=1');
            if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
            exit;
        }
        $to = \function_exists('get_option') ? \call_user_func('get_option', 'admin_email') : '';
        $subject = sprintf('[AquaLuxe] Export Quote – %s', $data['name']);
        $body = "Name: {$data['name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nCountry: {$data['country']}\nAirport: {$data['airport']}\nIncoterm: {$data['incoterm']}\nDate: {$data['date']}\n\nItems:\n{$data['items']}\n\nMessage:\n{$data['message']}\n";
        if (\function_exists('wp_mail')) { \call_user_func('wp_mail', $to, $subject, $body); }
        $ref = \function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null;
        $home = \function_exists('home_url') ? \call_user_func('home_url', '/') : '/';
        $redir = \function_exists('add_query_arg') ? \call_user_func('add_query_arg', 'submitted', '1', $ref ?: $home) : ($home . '?submitted=1');
        if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
        exit;
    }
}
