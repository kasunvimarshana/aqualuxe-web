<?php
/**
 * AquaLuxe Demo Content Importer Module
 * Modular, toggleable demo importer for WXR/JSON
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Demo_Importer {
    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'register_admin_page' ] );
        add_action( 'admin_post_aqualuxe_import_demo', [ __CLASS__, 'handle_import' ] );
    }

    public static function register_admin_page() {
        add_submenu_page(
            'themes.php',
            __( 'Import Demo Content', 'aqualuxe' ),
            __( 'Import Demo', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-import',
            [ __CLASS__, 'render_admin_page' ]
        );
    }

    public static function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
            <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'aqualuxe_import_demo', 'aqualuxe_import_demo_nonce' ); ?>
                <input type="hidden" name="action" value="aqualuxe_import_demo">
                <p><input type="file" name="demo_file" accept=".xml,.json" required></p>
                <p><input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Import Demo Content', 'aqualuxe' ); ?>"></p>
            </form>
        </div>
        <?php
    }

    public static function handle_import() {
        if ( ! current_user_can( 'manage_options' ) || ! isset( $_FILES['demo_file'] ) ) {
            wp_die( __( 'Permission denied or no file uploaded.', 'aqualuxe' ) );
        }
        check_admin_referer( 'aqualuxe_import_demo', 'aqualuxe_import_demo_nonce' );
        $file = $_FILES['demo_file'];
        $ext = pathinfo( $file['name'], PATHINFO_EXTENSION );
        if ( $ext === 'xml' ) {
            // Use WP_Import if available
            if ( ! class_exists( 'WP_Import' ) ) {
                require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                // Optionally require WP_Import plugin if not present
            }
            // Import logic (stub)
            // ...
        } elseif ( $ext === 'json' ) {
            // Parse and import JSON demo data (stub)
            // ...
        } else {
            wp_die( __( 'Unsupported file type.', 'aqualuxe' ) );
        }
        wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-import&imported=1' ) );
        exit;
    }
}

AquaLuxe_Demo_Importer::init();
