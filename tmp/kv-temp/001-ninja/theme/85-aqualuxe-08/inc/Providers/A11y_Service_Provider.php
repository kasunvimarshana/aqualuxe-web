<?php
/**
 * Accessibility hooks and improvements (WCAG 2.1 AA)
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class A11y_Service_Provider
{
    public function register(Container $c): void
    {
        add_filter('body_class', [$this, 'body_classes']);
    }

    public function boot(Container $c): void {}

    public function body_classes(array $classes): array
    {
        $classes[] = 'focus-outline-visible';
        return $classes;
    }
}
