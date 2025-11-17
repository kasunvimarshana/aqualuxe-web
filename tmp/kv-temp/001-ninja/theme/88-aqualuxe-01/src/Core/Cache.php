<?php
namespace Aqualuxe\Core;

class Cache
{
	private const PREFIX = 'aqlx_';

	public static function remember(string $key, int $ttl, callable $callback)
	{
		$k = self::key($key);
		$cached = get_transient($k);
		if ($cached !== false) { return $cached; }
		$value = $callback();
		set_transient($k, $value, $ttl);
		return $value;
	}

	public static function delete(string $key): void
	{
		delete_transient(self::key($key));
	}

	public static function key(string $key): string
	{
		return self::PREFIX . md5($key);
	}
}
