<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class RolesProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		// Example granular capability for vendors.
		add_action('after_switch_theme', function () {
			$role = get_role('shop_manager');
			if ($role && !$role->has_cap('manage_listings')) {
				$role->add_cap('manage_listings');
			}
		});
	}
}
