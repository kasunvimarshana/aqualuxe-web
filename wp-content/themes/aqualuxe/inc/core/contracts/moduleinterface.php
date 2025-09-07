<?php
namespace AquaLuxe\Core\Contracts;

/**
 * Optional contract for class-based modules.
 *
 * File-based modules (modules/<slug>/module.php) continue to work unchanged.
 * If a module also provides a class implementing this interface, the loader
 * will instantiate it and call boot() after including module.php.
 */
interface ModuleInterface
{
    /** Bootstrap the module: add hooks, REST endpoints, etc. */
    public function boot(): void;
}
