<?php

namespace App\Modules\DemoImporter\Util;

/**
 * Class Logger
 *
 * A simple logger for the import process.
 *
 * @package App\Modules\DemoImporter\Util
 */
class Logger
{
    private static $log_transient = 'aqualuxe_import_log';
    private static $log = [];

    /**
     * Log a message.
     *
     * @param string $message
     */
    public static function log(string $message)
    {
        self::$log[] = sprintf('[%s] %s', date('Y-m-d H:i:s'), $message);
        // For immediate debugging during development
        error_log($message);
        self::save_log();
    }

    /**
     * Save the log to a transient.
     */
    private static function save_log()
    {
        set_transient(self::$log_transient, self::$log, DAY_IN_SECONDS);
    }

    /**
     * Get the entire log.
     *
     * @return array
     */
    public static function get_log(): array
    {
        return get_transient(self::$log_transient) ?: [];
    }

    /**
     * Clear the log.
     */
    public static function clear_log()
    {
        delete_transient(self::$log_transient);
        self::$log = [];
    }
}
