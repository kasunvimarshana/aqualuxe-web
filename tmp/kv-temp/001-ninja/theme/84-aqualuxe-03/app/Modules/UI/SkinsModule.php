<?php
namespace Aqualuxe\Modules\UI;

defined('ABSPATH') || exit;

final class SkinsModule {
    public static function register(): void {
        \add_filter( 'aqlx/assets/enqueue_front', [ __CLASS__, 'enqueue_skin' ] );
        \add_filter( 'body_class', [ __CLASS__, 'body_class' ] );
    }

    public static function get_active_skin(): string {
        $skin = get_theme_mod( 'aqlx_skin', 'default' );
        return is_string( $skin ) && $skin ? $skin : 'default';
    }

    public static function enqueue_skin(): void {
        $skin = self::get_active_skin();
    // Use resolver to pick hashed asset if present; file is optional.
    $file = function_exists('aqlx_asset') ? aqlx_asset('css/skin-' . $skin . '.css') : ( get_template_directory_uri() . '/assets/dist/css/skin-' . $skin . '.css' );
    wp_enqueue_style( 'aqualuxe-skin', $file, [ 'aqualuxe-style' ], AQUALUXE_VERSION );
    }

    public static function body_class( array $classes ): array {
        $classes[] = 'skin-' . self::get_active_skin();
        return $classes;
    }
}
