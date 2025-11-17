<?php
/**
 * Security hardening: headers, sanitization helpers, nonce utilities.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Security_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('send_headers', [$this, 'send_security_headers']);
    }

    public function boot(Container $c): void {}

    public function send_security_headers(): void
    {
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 0');
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }

    public static function sanitize_text_field_deep($value)
    {
        if (is_array($value)) {
            return array_map([__CLASS__, __FUNCTION__], $value);
        }
        return sanitize_text_field((string) $value);
    }
}
