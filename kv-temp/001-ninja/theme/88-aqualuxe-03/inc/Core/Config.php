<?php
namespace AquaLuxe\Core;

class Config {
    /**
     * Determine enabled modules. Can be filtered by admins.
     *
     * @return array<class-string>
     */
    public function enabled_modules(): array {
        $modules = [
            \AquaLuxe\Modules\Multilingual\Module::class,
            \AquaLuxe\Modules\DarkMode\Module::class,
            \AquaLuxe\Modules\CPT\Module::class,
            \AquaLuxe\Modules\WooFallback\Module::class,
                \AquaLuxe\Modules\MultiCurrency\Module::class,
        ];
        /**
         * Filter enabled modules.
         *
         * @param array $modules
         */
        return (array) apply_filters( 'aqlx_enabled_modules', $modules );
    }
}
