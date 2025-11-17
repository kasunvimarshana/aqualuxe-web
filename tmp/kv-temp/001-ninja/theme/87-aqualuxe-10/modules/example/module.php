<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Container;

/**
 * An example class-based module.
 *
 * The module loader will automatically instantiate this class and call boot()
 * because its name follows the convention AquaLuxe\Modules\{Slug} where
 * {Slug} is the StudlyCase version of the folder name.
 */
class Example implements ModuleInterface
{
    /**
     * Bootstrap the module.
     */
    public function boot(): void
    {
        // You can resolve dependencies from the container.
        $logger = Container::get('logger');
        if ($logger) {
            $logger->info('Example module booted via class.', ['module' => 'example']);
        }

        // Add theme hooks.
        \add_action('wp_footer', [$this, 'render_footer_comment']);
    }

    /**
     * Example hook callback to demonstrate the module is active.
     */
    public function render_footer_comment(): void
    {
        echo "<!-- AquaLuxe Example Module is active -->\n";
    }
}
