<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Modules
{
    public static function boot(): void
    {
        $config = Config::get('modules');
        if (!\is_array($config)) {
            return;
        }

        foreach ($config as $module => $enabled) {
            if (! $enabled) {
                continue;
            }
            $class = __NAMESPACE__ . '\\Modules\\' . $module . '\\Bootstrap';
            if (\class_exists($class) && \method_exists($class, 'init')) {
                $class::init();
            }
        }
    }
}
