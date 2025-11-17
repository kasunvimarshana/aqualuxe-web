<?php
/**
 * Admin customizations
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add demo content importer page.
 */
function aqualuxe_add_demo_importer_page() {
    add_theme_page(
        __( 'Demo Content Importer', 'aqualuxe' ),
        __( 'Demo Importer', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-demo-importer',
        'aqualuxe_demo_importer_page_content'
    );
}
add_action( 'admin_menu', 'aqualuxe_add_demo_importer_page' );

/**
 * Demo content importer page content.
 */
function aqualuxe_demo_importer_page_content() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
        <p><?php _e( 'Import demo content to get your site started.', 'aqualuxe' ); ?></p>
        <button class="button button-primary" id="aqualuxe-import-demo"><?php _e( 'Import Demo Content', 'aqualuxe' ); ?></button>
        <div id="aqualuxe-importer-response"></div>
    </div>
    <?php
}
