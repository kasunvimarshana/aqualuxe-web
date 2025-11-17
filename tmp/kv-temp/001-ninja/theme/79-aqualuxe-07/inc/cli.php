<?php
declare(strict_types=1);

namespace Aqualuxe\Inc;

if (! defined('ABSPATH') || ! defined('WP_CLI')) {
    return;
}

// Ensure modules (including importer) are loaded for CLI context
if (function_exists(__NAMESPACE__ . '\load_modules')) {
    load_modules();
}

/**
 * AquaLuxe demo data commands
 */
class Aqlx_Demo_Command {
    /**
     * Run the demo import with optional parameters.
     *
     * [--entities=<list>]
     * : Comma-separated list (pages,posts,media,menus,widgets,products,services,events,users)
     *
     * [--volume=<n>]
     * : Volume factor, default 50
     *
     * [--locale=<code>]
     * : Locale code, default en
     *
     * [--media=<0|1>]
     * : Generate placeholder media (default 1)
     *
     * [--rollback=<0|1>]
     * : Rollback on error (default 1)
     */
    public function import(array $args, array $assoc): void {
        $entities = isset($assoc['entities']) ? array_filter(array_map('sanitize_text_field', explode(',', (string) $assoc['entities']))) : ['pages','posts','media','menus','widgets','products','services','events','users'];
        $params = [
            'entities' => $entities,
            'volume' => max(1, (int) ($assoc['volume'] ?? 50)),
            'locale' => (string) ($assoc['locale'] ?? 'en'),
            'media' => isset($assoc['media']) ? (bool) (int) $assoc['media'] : true,
            'rollback' => isset($assoc['rollback']) ? (bool) (int) $assoc['rollback'] : true,
        ];
        $job = [
            'status' => 'running',
            'progress' => 0,
            'params' => $params,
            'created' => ['posts'=>[], 'menus'=>[], 'users'=>[], 'terms'=>[], 'media'=>[]],
            'step' => 0,
            'total_steps' => 10,
            'log' => [],
        ];
        \Aqualuxe\Modules\Importer\save_job($job);
        \call_user_func('WP_CLI::log', 'Starting import...');
        while (true) {
            $res = \Aqualuxe\Modules\Importer\run_next_step();
            $state = \Aqualuxe\Modules\Importer\get_job();
            \call_user_func('WP_CLI::log', sprintf('Progress: %d%% | Step: %d | Status: %s', (int) ($state['progress'] ?? 0), (int) ($state['step'] ?? 0), (string) ($state['status'] ?? 'unknown')));
            if (! empty($res['done'])) { break; }
            usleep(200000); // 200ms
        }
        $final = \Aqualuxe\Modules\Importer\get_job();
        if (($final['status'] ?? '') === 'error') {
            \call_user_func('WP_CLI::error', 'Import failed. Check logs.');
        }
        \call_user_func('WP_CLI::success', 'Import complete.');
    }

    /**
     * Flush demo content created by the importer.
     */
    public function flush(): void {
    \call_user_func('WP_CLI::log', 'Flushing demo content...');
        \Aqualuxe\Modules\Importer\hard_flush();
        \Aqualuxe\Modules\Importer\reset_job();
    \call_user_func('WP_CLI::success', 'Flush complete.');
    }

    /**
     * Show current importer job status.
     */
    public function status(): void {
    $job = \Aqualuxe\Modules\Importer\get_job();
    \call_user_func('WP_CLI::line', wp_json_encode($job, JSON_PRETTY_PRINT));
    }
}
\call_user_func('WP_CLI::add_command', 'aqlx demo', Aqlx_Demo_Command::class);

