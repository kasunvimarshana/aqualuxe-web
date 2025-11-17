<?php
namespace Aqualuxe\Modules\SEO;

defined('ABSPATH') || exit;

final class SEOModule {
    public static function register(): void {
        \add_action( 'wp_head', [ __CLASS__, 'meta_tags' ], 1 );
        \add_filter( 'document_title_separator', [ __CLASS__, 'title_separator' ] );
        \add_action( 'wp_head', [ __CLASS__, 'jsonld' ], 20 );
    }

    public static function title_separator( string $sep ): string {
        return apply_filters( 'aqlx/seo/title_separator', '|' );
    }

    public static function meta_tags(): void {
        echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n"; // phpcs:ignore WordPress.Security.EscapeOutput
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">' . "\n"; // phpcs:ignore
        echo '<meta name="theme-color" content="#0c4a6e">' . "\n"; // phpcs:ignore
    }

    public static function jsonld(): void {
        $data = [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
    }
}
