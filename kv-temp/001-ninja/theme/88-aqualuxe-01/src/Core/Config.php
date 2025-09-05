<?php
namespace Aqualuxe\Core;

class Config
{
	public static function get(string $key, $default = null)
	{
		$all = self::load();
		return $all[$key] ?? $default;
	}

	public static function all(): array
	{
		return self::load();
	}

	private static function load(): array
	{
		$base = AQUALUXE_PATH . 'config/default.php';
		$data = file_exists($base) ? (array) include $base : [];

		if (defined('AQUALUXE_TENANT_ID')) {
			$tenant = AQUALUXE_PATH . 'config/tenants/' . AQUALUXE_TENANT_ID . '.php';
			if (file_exists($tenant)) {
				$override = (array) include $tenant;
				$data = array_replace_recursive($data, $override);
			}
		}
		return $data;
	}
}
