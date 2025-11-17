<?php
/**
 * Performance improvements: lazy loading, cleanup, caching hooks.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Performance_Service_Provider
{
    public function register(Container $c): void
    {
        // Dequeue emojis for performance (can be re-enabled via filter)
        if (apply_filters('aqualuxe_disable_emojis', true)) {
            add_action('init', function () {
                remove_action('wp_head', 'print_emoji_detection_script', 7);
                remove_action('wp_print_styles', 'print_emoji_styles');
            });
        }
    }

    public function boot(Container $c): void {}
}
