<?php
/**
 * Demo Importer Admin Page
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add the demo importer page to the admin menu.
 */
function aqualuxe_demo_importer_admin_menu() {
    add_theme_page(
        __( 'Demo Importer', 'aqualuxe' ),
        __( 'Demo Importer', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-demo-importer',
        'aqualuxe_demo_importer_page_content'
    );
}
add_action( 'admin_menu', 'aqualuxe_demo_importer_admin_menu' );

/**
 * Render the demo importer page.
 */
function aqualuxe_demo_importer_page_content() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
        <p><?php esc_html_e( 'Import demo content to get your site started.', 'aqualuxe' ); ?></p>

        <div class="aqualuxe-importer-buttons">
            <form method="post">
                <?php wp_nonce_field( 'aqualuxe_import_demo', 'aqualuxe_import_nonce' ); ?>
                <button type="submit" name="aqualuxe_import_demo" class="button button-primary">
                    <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                </button>
            </form>

            <form method="post" onsubmit="return confirm('<?php esc_attr_e( 'Are you sure you want to delete all content? This cannot be undone.', 'aqualuxe' ); ?>');">
                <?php wp_nonce_field( 'aqualuxe_flush_content', 'aqualuxe_flush_nonce' ); ?>
                <button type="submit" name="aqualuxe_flush_content" class="button button-secondary">
                    <?php esc_html_e( 'Flush All Content', 'aqualuxe' ); ?>
                </button>
            </form>
        </div>
    </div>
    <?php
}
