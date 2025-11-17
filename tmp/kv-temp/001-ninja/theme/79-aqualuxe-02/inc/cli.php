<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

if (! class_exists('WP_CLI')) {
    return;
}

class Import_CLI {
    public function import($args, $assoc_args)
    {
        if (! empty($assoc_args['flush'])) {
            echo "Flushing content...\n";
            \do_action('aqualuxe/importer/flush');
        }
        echo "Running demo import...\n";
        \do_action('aqualuxe/importer/run', $assoc_args);
        echo "Done\n";
    }
}

if (class_exists('WP_CLI')) {
    // Dynamically register without static type reference for linters
    call_user_func('WP_CLI::add_command', 'aqlx', Import_CLI::class);
}
