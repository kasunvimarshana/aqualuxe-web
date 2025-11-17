<?php
namespace AquaLuxe\Core;

/**
 * Simple service container.
 */
class Container {
    /** @var array<string,mixed> */
    private static array $services = [];

    public static function init(): void {
        // Reserved for future boot logic.
    }

    /**
     * Bind a service instance.
     *
     * @param string $id
     * @param mixed  $service
     */
    public static function bind( string $id, $service ): void {
        self::$services[ $id ] = $service;
    }

    /**
     * Get a service by id.
     *
     * @template T
     * @param string $id
     * @return mixed
     */
    public static function get( string $id ) {
        return self::$services[ $id ] ?? null;
    }
}
