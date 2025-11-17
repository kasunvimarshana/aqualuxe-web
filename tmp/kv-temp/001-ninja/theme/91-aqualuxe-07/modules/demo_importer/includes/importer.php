<?php
/**
 * Demo Importer Logic
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the demo import process.
 */
function aqualuxe_handle_demo_import() {
    if ( isset( $_POST['aqualuxe_import_demo'] ) && check_admin_referer( 'aqualuxe_import_demo', 'aqualuxe_import_nonce' ) ) {

        // Set a higher timeout limit for the import process to prevent script execution errors.
        if ( function_exists( 'set_time_limit' ) ) {
            set_time_limit( 0 );
        }

        if ( ! defined( 'WP_LOAD_IMPORTER' ) ) {
            define( 'WP_LOAD_IMPORTER', true );
        }

        // Load Importer API
        require_once ABSPATH . 'wp-admin/includes/import.php';

        $importer_error = false;

        // The WordPress Importer plugin is not active, so we need to manually load the necessary files.
        if ( ! class_exists( 'WP_Importer' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            if ( file_exists( $class_wp_importer ) ) {
                require_once $class_wp_importer;
            } else {
                $importer_error = true;
            }
        }
        if ( ! class_exists( 'WP_Import' ) ) {
            require_once get_template_directory() . '/inc/wordpress-importer/class-wp-import.php';
        }

        // Define the path to our bundled importer files
        $importer_dir = AQUALUXE_THEME_DIR . 'modules/demo_importer/includes/';

        // Manually load the importer's dependency files in the correct order
        if ( ! class_exists( 'WP_Import' ) ) {
            // These files are part of the WordPress Importer plugin.
            // We are loading them from our theme's bundled copy to avoid requiring the user to install the plugin.
            // The 'parsers.php' file is a dependency for 'wordpress-importer.php'.
            $parsers_file = $importer_dir . 'parsers.php';
            $importer_file = $importer_dir . 'wordpress-importer.php';

            if ( file_exists( $parsers_file ) ) {
                require_once $parsers_file;
            } else {
                $importer_error = true;
            }

            if ( file_exists( $importer_file ) ) {
                require_once $importer_file;
            } else {
                $importer_error = true;
            }
        }


        if ( $importer_error || ! class_exists( 'WP_Import' ) ) {
            wp_die( esc_html__( 'Error loading the WordPress Importer. Please try installing and activating the official "WordPress Importer" plugin as a fallback.', 'aqualuxe' ), '', array( 'back_link' => true ) );
        } else {
            $importer = new WP_Import();
            // Enable fetching attachments.
            $importer->fetch_attachments = true;
            // Suppress the importer's output.
            ob_start();
            $importer->import( AQUALUXE_THEME_DIR . 'modules/demo_importer/data/demo-content.xml' );
            ob_end_clean();

            // Add a success notice to the admin dashboard.
            add_action( 'admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Demo content imported successfully! Please refresh your site to see the changes.', 'aqualuxe' ) . '</p></div>';
            });
        }
    }
}
add_action( 'admin_init', 'aqualuxe_handle_demo_import' );

/**
 * Handle flushing all content.
 */
function aqualuxe_handle_flush_content() {
    if ( isset( $_POST['aqualuxe_flush_content'] ) && check_admin_referer( 'aqualuxe_flush_content', 'aqualuxe_flush_nonce' ) ) {
        global $wpdb;

        // Delete posts, pages, and custom post types
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}posts" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}postmeta" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}comments" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}commentmeta" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}terms" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}term_taxonomy" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}term_relationships" );
        $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}termmeta" );

        // Add success message
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'All content has been flushed.', 'aqualuxe' ) . '</p></div>';
        });
    }
}
add_action( 'admin_init', 'aqualuxe_handle_flush_content' );
