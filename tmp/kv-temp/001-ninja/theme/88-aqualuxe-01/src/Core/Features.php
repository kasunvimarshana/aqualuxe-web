<?php
namespace Aqualuxe\Core;

class Features
{
	public static function enabled(string $feature, $default = true): bool
	{
		$config = (array) Config::get('features', []);
		$opts = (array) get_option('aqualuxe_features', []);
		$value = $opts[$feature] ?? $config[$feature] ?? $default;
		return (bool) $value;
	}
}
