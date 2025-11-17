<?php
/**
 * Demo Importer Module
 * Handles importing of demo content, widgets, and theme options.
 */

namespace AquaLuxe\Modules\DemoImporter;

if ( ! defined( 'ABSPATH' ) ) exit;

class Demo_Importer {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_admin_page' ] );
        add_action( 'admin_post_aqualuxe_import_demo', [ $this, 'handle_import' ] );
    }

    public function register_admin_page() {
        add_submenu_page(
            'themes.php',
            __( 'AquaLuxe Demo Importer', 'aqualuxe' ),
            __( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            [ $this, 'render_admin_page' ]
        );
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
            <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
                <input type="hidden" name="action" value="aqualuxe_import_demo">
                <?php submit_button( __( 'Import Demo Content', 'aqualuxe' ) ); ?>
            </form>
        </div>
        <?php
    }

    public function handle_import() {
        // TODO: Implement demo content import logic (XML/WXR import, widgets, theme options)
        // For now, just display a notice
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Demo import completed (placeholder).', 'aqualuxe' ) . '</p></div>';
        } );
        wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-importer' ) );
        exit;
    }
}

// Initialize the module
new Demo_Importer();
