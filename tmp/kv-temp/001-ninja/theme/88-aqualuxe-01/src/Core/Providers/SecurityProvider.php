<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class SecurityProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_filter('the_generator', '__return_empty_string');
		add_filter('xmlrpc_enabled', '__return_false');
		add_action('init', function () { if (!is_admin()) { nocache_headers(); } });
		add_action('send_headers', [$this, 'headers']);
	}

	public function headers(): void
	{
		header('X-Frame-Options: SAMEORIGIN');
		header('X-Content-Type-Options: nosniff');
		header('Referrer-Policy: no-referrer-when-downgrade');
		if (is_ssl()) {
			header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
		}
		// Conservative CSP avoiding inline/script-src breaks; adjust as needed
		header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob: https:; img-src 'self' data: https:; media-src 'self' https:; connect-src 'self' https: ");
	}
}
