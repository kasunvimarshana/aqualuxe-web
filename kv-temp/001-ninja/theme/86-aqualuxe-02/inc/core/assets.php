<?php
namespace AquaLuxe\Core;

class Assets
{
    public static function enqueue(): void
    {
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
        \wp_localize_script('aqualuxe-app', 'AQUALUXE', [
            'ajaxUrl' => \admin_url('admin-ajax.php'),
            'restUrl' => \esc_url_raw(\get_rest_url(null, '/aqualuxe/v1')),
            'nonce'   => \wp_create_nonce('wp_rest'),
            'i18n'    => [
                'addedToWishlist' => \__('Added to wishlist', 'aqualuxe'),
            ],
        ]);
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
