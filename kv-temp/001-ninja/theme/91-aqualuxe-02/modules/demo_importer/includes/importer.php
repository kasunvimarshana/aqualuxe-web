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
        if ( ! defined( 'WP_LOAD_IMPORTER' ) ) {
            define( 'WP_LOAD_IMPORTER', true );
        }

        // Load Importer API
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
            $class_wp_import = AQUALUXE_DEMO_IMPORTER_DIR . 'includes/wordpress-importer.php';
            if ( file_exists( $class_wp_import ) ) {
                require_once $class_wp_import;
            } else {
                $importer_error = true;
            }
        }

        if ( $importer_error ) {
            wp_die( esc_html__( 'Error loading the WordPress Importer.', 'aqualuxe' ), '', array( 'back_link' => true ) );
        } else {
            $importer = new WP_Import();
            $importer->fetch_attachments = true;
            ob_start();
            $importer->import( AQUALUXE_DEMO_IMPORTER_DIR . 'data/demo-content.xml' );
            ob_end_clean();

            // Add success message
            add_action( 'admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Demo content imported successfully!', 'aqualuxe' ) . '</p></div>';
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
