<?php

declare(strict_types=1);

namespace AquaLuxe\Core\Modules\DarkMode;

use AquaLuxe\Core\Helpers;

class Bootstrap
{
    public static function init(): void
    {
        // Body class.
        Helpers::wp('add_filter', ['body_class', [self::class, 'body_class']]);
        // Toggle endpoint for progressive enhancement (non-JS fallback via GET form).
        Helpers::wp('add_action', ['init', [self::class, 'handle_toggle']]);
    }

    public static function body_class(array $classes): array
    {
        $pref = isset($_COOKIE['alx_dark']) ? sanitize_text_field((string) $_COOKIE['alx_dark']) : '';
        if ($pref === '1') {
            $classes[] = 'theme-dark';
        }
        return $classes;
    }

    public static function handle_toggle(): void
    {
        if (! isset($_GET['alx_dark_toggle'])) {
            return;
        }
    if (! Helpers::wp('wp_verify_nonce', [$_GET['_wpnonce'] ?? '', 'alx_dark_toggle'])) {
            return;
        }
        $current = isset($_COOKIE['alx_dark']) ? (string) $_COOKIE['alx_dark'] : '0';
        $next = $current === '1' ? '0' : '1';
    $cookiePath = \defined('COOKIEPATH') && \constant('COOKIEPATH') ? \constant('COOKIEPATH') : '/';
    $cookieDomain = \defined('COOKIE_DOMAIN') ? \constant('COOKIE_DOMAIN') : '';
    $isSsl = (bool) Helpers::wp('is_ssl');
    \setcookie('alx_dark', $next, time() + 31536000, $cookiePath, $cookieDomain, $isSsl, true);
    $target = Helpers::wp('remove_query_arg', [['alx_dark_toggle', '_wpnonce']]);
    Helpers::wp('wp_safe_redirect', [$target]);
        exit;
    }
}
