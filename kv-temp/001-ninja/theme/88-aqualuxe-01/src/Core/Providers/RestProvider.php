<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;
use Aqualuxe\Http\Rest\ContactController;
use Aqualuxe\Http\Ajax\ContactAjax;

class RestProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('rest_api_init', function () {
			(new ContactController())->register();
		});

		// No-JS fallback via admin-ajax.
		add_action('wp_ajax_aqualuxe_contact', [new ContactAjax(), 'handle']);
		add_action('wp_ajax_nopriv_aqualuxe_contact', [new ContactAjax(), 'handle']);
	}
}
