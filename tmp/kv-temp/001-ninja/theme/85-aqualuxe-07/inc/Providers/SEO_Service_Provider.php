<?php
/**
 * SEO basics: meta tags, schema.org, sitemaps hook points.
 */

namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class SEO_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('wp_head', [$this, 'add_meta_tags'], 1);
    }

    public function boot(Container $c): void {}

    public function add_meta_tags(): void
    {
        echo "\n<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">\n";
        echo "<meta name=\"theme-color\" content=\"#0a84ff\">\n";
        // Basic JSON-LD Organization schema placeholder.
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'url' => home_url('/'),
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
