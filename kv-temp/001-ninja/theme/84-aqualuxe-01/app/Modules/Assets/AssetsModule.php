<?php
namespace Aqualuxe\Modules\Assets;

defined('ABSPATH') || exit;

final class AssetsModule {
    public static function register(): void {
        \add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_front' ], 20 );
        \add_action( 'enqueue_block_editor_assets', [ __CLASS__, 'enqueue_editor' ] );
    }

    public static function enqueue_front(): void {
        // Front assets are enqueued in functions.php; this hook allows extensions.
        \do_action( 'aqlx/assets/enqueue_front' );
    }

    public static function enqueue_editor(): void {
        \wp_enqueue_style( 'aqualuxe-editor', get_template_directory_uri() . '/assets/dist/css/editor.css', [], AQUALUXE_VERSION );
    }
}
