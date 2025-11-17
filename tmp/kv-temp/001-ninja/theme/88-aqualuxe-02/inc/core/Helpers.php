<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

final class Helpers
{
    /**
     * Safely call a WordPress function if available.
     *
     * @param string $fn   Function name, e.g., 'add_theme_support'.
     * @param array  $args Arguments.
     * @return mixed|null
     */
    public static function wp(string $fn, array $args = [])
    {
        if (\function_exists($fn)) {
            return \call_user_func_array($fn, $args);
        }
        return null;
    }
}
