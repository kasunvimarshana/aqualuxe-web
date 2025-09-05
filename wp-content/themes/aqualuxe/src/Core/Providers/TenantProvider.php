<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class TenantProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		add_action('init', function () {
			$host = wp_parse_url(home_url(), PHP_URL_HOST);
			$tenantId = apply_filters('aqualuxe_tenant_id', $host);
			if (!defined('AQUALUXE_TENANT_ID')) {
				define('AQUALUXE_TENANT_ID', sanitize_key($tenantId ?: 'default'));
			}
		});
	}
}
