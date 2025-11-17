<?php
namespace AquaLuxe\Core;

class Assets
{
    public static function enqueue(): void
    {
        if (!\defined('AQUALUXE_ASSETS_DIST')) {
            \define('AQUALUXE_ASSETS_DIST', \get_template_directory() . '/assets/dist');
        }
        if (!\defined('AQUALUXE_ASSETS_URI')) {
            \define('AQUALUXE_ASSETS_URI', \get_template_directory_uri() . '/assets/dist');
        }
        $manifest = self::manifest();
        $style = self::asset_uri($manifest, 'css/app.css');
        $script = self::asset_uri($manifest, 'js/app.js');

        if ($style) {
            \wp_enqueue_style('aqualuxe-app', $style, [], AQUALUXE_VERSION);
        }
        if ($script) {
            \wp_enqueue_script('aqualuxe-app', $script, [], AQUALUXE_VERSION, true);
            // Set defer for performance
            \add_filter('script_loader_tag', function ($tag, $handle) use ($script) {
                if ($handle === 'aqualuxe-app') {
                    $tag = str_replace(' src', ' defer src', $tag);
                }
                return $tag;
            }, 10, 2);
        }

        // Localize runtime config
        $ajaxUrl = \function_exists('admin_url') ? \call_user_func('admin_url', 'admin-ajax.php') : '/wp-admin/admin-ajax.php';
        $restUrl = '\/wp-json';
        if (\function_exists('get_rest_url')) {
            $restUrl = \call_user_func('get_rest_url', null, '/aqualuxe/v1');
            $restUrl = \function_exists('esc_url_raw') ? \call_user_func('esc_url_raw', $restUrl) : (string) $restUrl;
        }
        $nonce = \function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'wp_rest') : '';
        \wp_localize_script('aqualuxe-app', 'AQUALUXE', [
            'ajaxUrl' => $ajaxUrl,
            'restUrl' => $restUrl,
            'nonce'   => $nonce,
            'i18n'    => [
                'addedToWishlist' => \function_exists('__') ? \call_user_func('__', 'Added to wishlist', 'aqualuxe') : 'Added to wishlist',
            ],
        ]);

        // Resource hints to help the browser warm up connections early.
        \add_filter('wp_resource_hints', function(array $urls, string $relation): array {
            if ($relation === 'preconnect' || $relation === 'dns-prefetch') {
                $endpoints = [];
                if (\function_exists('home_url')) { $endpoints[] = parse_url(\call_user_func('home_url'), PHP_URL_HOST); }
                if (\function_exists('rest_url')) { $endpoints[] = parse_url(\call_user_func('rest_url'), PHP_URL_HOST); }
                $endpoints = array_filter(array_unique(array_map('strval', $endpoints)));
                foreach ($endpoints as $host) {
                    if ($host && !in_array('//' . $host, $urls, true)) {
                        $urls[] = '//' . $host;
                    }
                }
            }
            return $urls;
        }, 10, 2);
    }

    private static function manifest(): array
    {
        $file = AQUALUXE_ASSETS_DIST . '/mix-manifest.json';
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $data = json_decode($json, true);
            if (is_array($data)) {
                return $data;
            }
        }
        return [];
    }

    private static function asset_uri(array $manifest, string $logical): ?string
    {
        $key = '/' . ltrim($logical, '/');
        if (isset($manifest[$key])) {
            return AQUALUXE_ASSETS_URI . $manifest[$key];
        }
        return null;
    }
}
