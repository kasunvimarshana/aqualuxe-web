<?php
namespace Aqualuxe\Http\Rest;

class ContactController
{
	public function register(): void
	{
		register_rest_route('aqualuxe/v1', '/contact', [
			'methods'  => 'POST',
			'permission_callback' => function () { return wp_verify_nonce($_SERVER['HTTP_X_WP_NONCE'] ?? '', 'wp_rest'); },
			'callback' => [$this, 'handle'],
		]);
	}

	public function handle($request)
	{
		$params = $request->get_params();
		$name = sanitize_text_field($params['name'] ?? '');
		$email = sanitize_email($params['email'] ?? '');
		$message = wp_kses_post($params['message'] ?? '');

		if (!$name || !$email || !$message) {
			return new \WP_Error('invalid', __('All fields are required.', 'aqualuxe'), ['status' => 400]);
		}

		wp_mail(get_option('admin_email'), sprintf(__('New contact from %s', 'aqualuxe'), $name), $message, ['Reply-To: ' . $email]);
		return rest_ensure_response(['ok' => true]);
	}
}
