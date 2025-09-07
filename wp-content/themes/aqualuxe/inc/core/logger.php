<?php
namespace AquaLuxe\Core;

use AquaLuxe\Core\Contracts\LoggerInterface;

/**
 * Minimal logger with level filtering and context interpolation.
 * Uses error_log under the hood; can be swapped via Container.
 */
/**
 * @implements LoggerInterface
 */
class Logger implements LoggerInterface
{
    public const LEVELS = ['emergency','alert','critical','error','warning','notice','info','debug'];
    private string $minLevel;

    public function __construct(string $minLevel = 'warning')
    {
        $this->minLevel = in_array($minLevel, self::LEVELS, true) ? $minLevel : 'warning';
    }

    public function log(string $level, string $message, array $context = []): void
    {
        if ($this->shouldSkip($level)) { return; }
        $msg = $this->interpolate($message, $context);
        // Prefix with level and theme tag
        error_log('[AquaLuxe][' . strtoupper($level) . '] ' . $msg);
    }

    public function error(string $message, array $context = []): void { $this->log('error', $message, $context); }
    public function warning(string $message, array $context = []): void { $this->log('warning', $message, $context); }
    public function info(string $message, array $context = []): void { $this->log('info', $message, $context); }
    public function debug(string $message, array $context = []): void { $this->log('debug', $message, $context); }

    private function shouldSkip(string $level): bool
    {
        $idx = array_search($level, self::LEVELS, true);
        $min = array_search($this->minLevel, self::LEVELS, true);
        if ($idx === false || $min === false) { return false; }
        return $idx > $min; // skip lower-priority levels
    }

    private function interpolate(string $message, array $context): string
    {
        $replace = [];
        foreach ($context as $k => $v) {
            if (is_scalar($v) || $v === null) {
                $replace['{' . $k . '}'] = (string) $v;
            } else {
                $json = \function_exists('wp_json_encode') ? \call_user_func('wp_json_encode', $v) : json_encode($v);
                $replace['{' . $k . '}'] = (string) $json;
            }
        }
        return strtr($message, $replace);
    }
}
