<?php
namespace Aqualuxe\Core;

/**
 * Minimal application container managing service providers.
 */
class App
{
	private static ?App $instance = null;
	/** @var ServiceProviderInterface[] */
	private array $providers = [];
	private bool $booted = false;

	private function __construct() {}

	public static function getInstance(): App
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function register(ServiceProviderInterface $provider): void
	{
		$this->providers[] = $provider;
	}

	public function boot(): void
	{
		if ($this->booted) { return; }
		foreach ($this->providers as $provider) {
			$provider->register();
			if (method_exists($provider, 'boot')) {
				$provider->boot();
			}
		}
		$this->booted = true;
	}
}
