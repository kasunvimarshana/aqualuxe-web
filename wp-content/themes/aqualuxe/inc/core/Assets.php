<?php

declare(strict_types=1);

namespace AquaLuxe\Core;

class Assets
{
    private static array $manifest = [];

    private static function manifest_path(string $asset): string
    {
        if (empty(self::$manifest)) {
            $file = AQUALUXE_ASSETS_DIST . 'mix-manifest.json';
            if (file_exists($file)) {
                $json = \file_get_contents($file) ?: '';
                self::$manifest = json_decode($json, true) ?: [];
            }
        }

        $key = '/' . ltrim($asset, '/');
        if (isset(self::$manifest[$key])) {
            return AQUALUXE_ASSETS_URI . ltrim(self::$manifest[$key], '/');
        }
        // Fallback to non-hashed file with version query.
        return AQUALUXE_ASSETS_URI . ltrim($asset, '/') . '?ver=' . rawurlencode(AQUALUXE_VERSION);
    }

    public static function enqueue(): void
    {
        $style = self::manifest_path('css/app.css');
        $script = self::manifest_path('js/app.js');

        Helpers::wp('wp_register_style', ['aqualuxe-app', $style, [], null]);
        Helpers::wp('wp_style_add_data', ['aqualuxe-app', 'rtl', 'replace']);
        Helpers::wp('wp_enqueue_style', ['aqualuxe-app']);

        Helpers::wp('wp_register_script', ['aqualuxe-app', $script, [], null, true]);
        Helpers::wp('wp_script_add_data', ['aqualuxe-app', 'defer', true]);
        $restUrl = Helpers::wp('rest_url', ['aqualuxe/v1']);
        $restUrl = $restUrl ? \esc_url_raw((string) $restUrl) : '';
        Helpers::wp('wp_localize_script', ['aqualuxe-app', 'AquaLuxe', [
            'restUrl' => $restUrl,
            'nonce'   => Helpers::wp('wp_create_nonce', ['wp_rest']) ?? '',
            'i18n'    => [
                'close' => __('Close', 'aqualuxe'),
            ],
        ]]);
        Helpers::wp('wp_enqueue_script', ['aqualuxe-app']);
    }

    public static function enqueue_admin(): void
    {
        $style = self::manifest_path('css/admin.css');
        $script = self::manifest_path('js/admin.js');

    Helpers::wp('wp_enqueue_style', ['aqualuxe-admin', $style, [], null]);
    Helpers::wp('wp_enqueue_script', ['aqualuxe-admin', $script, ['jquery'], null, true]);
    }
}
