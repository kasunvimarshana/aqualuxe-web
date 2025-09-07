<?php
/**
 * Demo Importer Module
 *
 * @package AquaLuxe
 */

// Add admin menu page
function aqualuxe_demo_importer_menu() {
    add_theme_page(
        __( 'AquaLuxe Demo Importer', 'aqualuxe' ),
        __( 'Demo Importer', 'aqualuxe' ),
        'manage_options',
        'aqualuxe-demo-importer',
        'aqualuxe_demo_importer_page'
    );
}
add_action( 'admin_menu', 'aqualuxe_demo_importer_menu' );

// Demo importer page
function aqualuxe_demo_importer_page() {
    ?>
    <div class="wrap aqualuxe-demo-importer">
        <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
        <p><?php esc_html_e( 'Click the button below to import the demo content. This will install demo posts, pages, and other content.', 'aqualuxe' ); ?></p>
        <p><?php esc_html_e( 'Please make sure you have installed and activated all the required plugins before importing the demo content.', 'aqualuxe' ); ?></p>
        <form method="post">
            <?php wp_nonce_field( 'aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce' ); ?>
            <input type="hidden" name="aqualuxe_import_demo" value="1">
            <p>
                <button type="submit" class="button button-primary"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></button>
            </p>
        </form>
    </div>
    <?php
}

// Handle the import
function aqualuxe_handle_demo_import() {
    if ( isset( $_POST['aqualuxe_import_demo'] ) && check_admin_referer( 'aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce' ) ) {
        if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
            define( 'WP_LOAD_IMPORTERS', true );
        }

        // Load importer API
        require_once ABSPATH . 'wp-admin/includes/import.php';

        $importer_error = false;

        if ( ! class_exists( 'WP_Importer' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if ( file_exists( $class_wp_importer ) ) {
                require_once $class_wp_importer;
            } else {
                $importer_error = true;
            }
        }

        if ( ! class_exists( 'WP_Import' ) ) {
            $class_wp_import = get_template_directory() . '/inc/lib/wordpress-importer/wordpress-importer.php';
            if ( file_exists( $class_wp_import ) ) {
                require_once $class_wp_import;
            } else {
                $importer_error = true;
            }
        }

        if ( $importer_error ) {
            wp_die( esc_html__( 'Error! The WordPress Importer is not available.', 'aqualuxe' ), '', array( 'back_link' => true ) );
        } else {
            $import = new WP_Import();
            $import->fetch_attachments = true;
            $import->import( get_template_directory() . '/demo/demo-content.xml' );

            // Redirect to a success page
            wp_redirect( admin_url( 'themes.php?page=aqualuxe-demo-importer&import=success' ) );
            exit;
        }
    }
}
add_action( 'admin_init', 'aqualuxe_handle_demo_import' );

// Display success message
function aqualuxe_demo_importer_success_message() {
    if ( isset( $_GET['import'] ) && $_GET['import'] === 'success' ) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e( 'Demo content imported successfully!', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'aqualuxe_demo_importer_success_message' );

// Enqueue styles
function aqualuxe_demo_importer_styles() {
    wp_enqueue_style( 'aqualuxe-demo-importer', get_template_directory_uri() . '/modules/demo_importer/demo_importer.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_demo_importer_styles' );
