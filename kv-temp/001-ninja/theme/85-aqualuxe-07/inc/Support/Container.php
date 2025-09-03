<?php
/**
 * Very small service container for the theme.
 *
 * @package Aqualuxe\Support
 */

namespace Aqualuxe\Support;

class Container
{
    /** @var self|null */
    private static $instance = null;

    /** @var array<string, object> */
    private array $bindings = [];

    /** @var array<int, object> */
    private array $providers = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $id, object $service): void
    {
        $this->bindings[$id] = $service;
    }

    public function get(string $id): ?object
    {
        return $this->bindings[$id] ?? null;
    }

    public function register(object $provider): void
    {
        $this->providers[] = $provider;
        if (method_exists($provider, 'register')) {
            $provider->register($this);
        }
    }

    public function boot(): void
    {
        foreach ($this->providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $provider->boot($this);
            }
        }
    }
}
