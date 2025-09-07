<?php
namespace AquaLuxe\Core;

/**
 * Tiny service container to support dependency inversion.
 *
 * Keep it minimal (KISS/YAGNI): string keys map to values or factories.
 * Factories are callables that receive the container and return the instance.
 */
class Container
{
    /** @var array<string, mixed> */
    private static array $entries = [];

    /**
     * Register a service or factory.
     *
     * @param string               $id     Identifier (e.g., 'logger').
     * @param mixed|callable(self):mixed $value Value or factory callable.
     */
    public static function set(string $id, $value): void
    {
        self::$entries[$id] = $value;
    }

    /**
     * Retrieve a service; if a factory was registered, it will be invoked once and cached.
     *
     * @template T
     * @param string $id Identifier.
     * @return mixed Returns the stored instance/value or null when missing.
     */
    public static function get(string $id)
    {
        if (!array_key_exists($id, self::$entries)) {
            return null;
        }
        $val = self::$entries[$id];
        if (is_callable($val)) {
            // Lazy instantiate and memoize. Support 0-arg and 1-arg factory styles.
            try {
                $ref = is_array($val)
                    ? new \ReflectionMethod($val[0], $val[1])
                    : (is_object($val) ? new \ReflectionMethod($val, '__invoke') : new \ReflectionFunction($val));
                $expects = max(0, (int) $ref->getNumberOfRequiredParameters());
            } catch (\Throwable $e) {
                $expects = 0;
            }
            if ($expects >= 1) {
                $val = $val(self::class);
            } else {
                $val = $val();
            }
            self::$entries[$id] = $val;
        }
        return $val;
    }

    /**
     * Check if an entry exists.
     */
    public static function has(string $id): bool
    {
        return array_key_exists($id, self::$entries);
    }
}

use Aqualuxe\Core\Services\ModuleService;
use Aqualuxe\Core\Services\OptionsService;
use Aqualuxe\Core\Services\MultitenancyService;

$this->services = [
    'theme'         => new ThemeService(),
    'modules'       => new ModuleService(),
    'options'       => new OptionsService(),
    'multitenancy'  => new MultitenancyService(),
    'affiliate'     => new AffiliateService(),
];
