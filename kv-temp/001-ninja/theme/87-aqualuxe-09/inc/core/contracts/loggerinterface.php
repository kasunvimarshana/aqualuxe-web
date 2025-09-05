<?php
namespace AquaLuxe\Core\Contracts;

/**
 * Minimal logger contract inspired by PSR-3.
 *
 * Implementations must accept a log level and a message with optional context
 * placeholders like {name} which will be interpolated from the context array.
 */
interface LoggerInterface
{
    /**
     * Generic logger method.
     *
     * @param string $level   One of: emergency, alert, critical, error, warning, notice, info, debug.
     * @param string $message Message with optional {placeholders}.
     * @param array  $context Context data for interpolation.
     */
    public function log(string $level, string $message, array $context = []): void;

    public function error(string $message, array $context = []): void;
    public function warning(string $message, array $context = []): void;
    public function info(string $message, array $context = []): void;
    public function debug(string $message, array $context = []): void;
}
