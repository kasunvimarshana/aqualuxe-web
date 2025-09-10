<?php

namespace App\Modules\DemoImporter\Util;

/**
 * Class Logger
 *
 * Handles logging for the import process.
 *
 * @package App\Modules\DemoImporter\Util
 */
class Logger
{
    /**
     * Log a message.
     *
     * @param string $message
     * @param string $level
     */
    public static function log($message, $level = 'info')
    {
        // Simple logging to a file for now.
        // In a real-world scenario, this would be more robust.
        $file = AQUALUXE_DIR . '/logs/importer.log';
        $formatted_message = sprintf(
            "[%s] [%s]: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message
        );
        file_put_contents($file, $formatted_message, FILE_APPEND);
    }
}
