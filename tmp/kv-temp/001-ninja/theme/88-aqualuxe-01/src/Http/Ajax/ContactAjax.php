<?php
namespace Aqualuxe\Http\Ajax;

class ContactAjax
{
	public function handle(): void
	{
		check_ajax_referer('aqualuxe_contact');
		$name = sanitize_text_field($_POST['name'] ?? '');
		$email = sanitize_email($_POST['email'] ?? '');
		$message = wp_kses_post($_POST['message'] ?? '');

		if (!$name || !$email || !$message) {
			wp_send_json_error(['message' => __('All fields are required.', 'aqualuxe')], 400);
		}

		wp_mail(get_option('admin_email'), sprintf(__('New contact from %s', 'aqualuxe'), $name), $message, ['Reply-To: ' . $email]);
		wp_send_json_success(['ok' => true]);
	}
}
