<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;
use Aqualuxe\Admin\SettingsPage;

class AdminProvider implements ServiceProviderInterface
{
	public function register(): void
	{
		if (is_admin()) {
			add_action('admin_menu', [new SettingsPage(), 'registerMenu']);
			add_action('admin_init', [new SettingsPage(), 'registerSettings']);
		}
	}
}
