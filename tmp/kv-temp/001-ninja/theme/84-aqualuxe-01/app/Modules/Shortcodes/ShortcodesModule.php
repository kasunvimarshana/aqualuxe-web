<?php
namespace Aqualuxe\Modules\Shortcodes;

defined('ABSPATH') || exit;

final class ShortcodesModule {
    public static function register(): void {
        \add_action( 'init', [ __CLASS__, 'shortcodes' ] );
    }

    public static function shortcodes(): void {
        \add_shortcode( 'aqlx_listings', [ __CLASS__, 'listings' ] );
    }

    public static function listings( $atts = [] ): string {
        $atts = shortcode_atts( [ 'per_page' => 10 ], $atts );
        $per_page = max( 1, (int) $atts['per_page'] );
        $q = new \WP_Query([
            'post_type' => 'listing',
            'posts_per_page' => $per_page,
        ]);
        ob_start();
        echo '<ul class="aqlx-listings">';
        foreach ( $q->posts as $post ) {
            echo '<li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( get_the_title( $post ) ) . '</a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput
        }
        echo '</ul>';
        return (string) ob_get_clean();
    }
}
