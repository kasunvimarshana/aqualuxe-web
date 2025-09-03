<?php
namespace Aqualuxe\Helpers;

class Templating
{
    /**
     * Render a template part with variables.
     */
    public static function view(string $slug, array $args = []): void
    {
        $file = get_template_directory() . '/components/' . ltrim($slug, '/');
        if (!str_ends_with($file, '.php')) {
            $file .= '.php';
        }
        if (file_exists($file)) {
            extract($args, EXTR_SKIP);
            include $file;
        }
    }
}
