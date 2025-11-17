<?php
/**
 * AquaLuxe Demo Importer Class
 *
 * @package AquaLuxe
 * @subpackage Modules/Demo_Importer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {
    /**
     * Import demo content
     *
     * @param array $demo Demo package data
     * @return bool|WP_Error
     */
    public function import_demo( $demo ) {
        // Check if required plugins are active
        $missing_plugins = $this->check_required_plugins( $demo );
        
        if ( ! empty( $missing_plugins ) ) {
            return new WP_Error(
                'missing_plugins',
                sprintf(
                    /* translators: %s: list of plugin names */
                    __( 'The following required plugins are missing: %s', 'aqualuxe' ),
                    implode( ', ', $missing_plugins )
                )
            );
        }
        
        // Import content
        $content_result = $this->import_content( $demo );
        if ( is_wp_error( $content_result ) ) {
            return $content_result;
        }
        
        // Import widgets
        $widgets_result = $this->import_widgets( $demo );
        if ( is_wp_error( $widgets_result ) ) {
            return $widgets_result;
        }
        
        // Import customizer settings
        $customizer_result = $this->import_customizer( $demo );
        if ( is_wp_error( $customizer_result ) ) {
            return $customizer_result;
        }
        
        // Clean up
        aqualuxe_demo_importer_cleanup();
        
        return true;
    }
    
    /**
     * Check required plugins
     *
     * @param array $demo Demo package data
     * @return array Missing plugins
     */
    private function check_required_plugins( $demo ) {
        $missing_plugins = array();
        
        // Check if demo has required plugins
        if ( empty( $demo['required_plugins'] ) ) {
            return $missing_plugins;
        }
        
        // Loop through required plugins
        foreach ( $demo['required_plugins'] as $plugin_slug => $plugin ) {
            // Skip if plugin is not required
            if ( ! $plugin['required'] ) {
                continue;
            }
            
            // Check if plugin is active
            if ( ! aqualuxe_demo_importer_is_plugin_active( $plugin_slug ) ) {
                $missing_plugins[] = $plugin['name'];
            }
        }
        
        return $missing_plugins;
    }
    
    /**
     * Import content
     *
     * @param array $demo Demo package data
     * @return bool|WP_Error
     */
    private function import_content( $demo ) {
        // Check if demo has content file
        if ( empty( $demo['import_file_url'] ) ) {
            return new WP_Error( 'missing_content_file', __( 'Demo content file not found.', 'aqualuxe' ) );
        }
        
        // Download content file
        $content_file = aqualuxe_demo_importer_download_file( $demo['import_file_url'] );
        if ( is_wp_error( $content_file ) ) {
            return $content_file;
        }
        
        // Load WordPress Importer
        if ( ! class_exists( 'WP_Importer' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        }
        
        if ( ! class_exists( 'WP_Import' ) ) {
            require_once ABSPATH . 'wp-admin/includes/import.php';
            
            $importer_error = false;
            $importer_error = apply_filters( 'wp_import_error', $importer_error );
            
            if ( $importer_error ) {
                return new WP_Error( 'importer_error', __( 'WordPress Importer not found.', 'aqualuxe' ) );
            }
            
            $wp_import = new WP_Import();
            $wp_import->fetch_attachments = true;
            
            // Run the importer
            ob_start();
            $wp_import->import( $content_file );
            ob_end_clean();
            
            return true;
        } else {
            return new WP_Error( 'importer_error', __( 'WordPress Importer not found.', 'aqualuxe' ) );
        }
    }
    
    /**
     * Import widgets
     *
     * @param array $demo Demo package data
     * @return bool|WP_Error
     */
    private function import_widgets( $demo ) {
        // Check if demo has widgets file
        if ( empty( $demo['import_widget_file_url'] ) ) {
            return true; // Skip if no widgets file
        }
        
        // Download widgets file
        $widgets_file = aqualuxe_demo_importer_download_file( $demo['import_widget_file_url'] );
        if ( is_wp_error( $widgets_file ) ) {
            return $widgets_file;
        }
        
        // Import widgets
        $result = aqualuxe_demo_importer_import_widgets( $widgets_file );
        
        return $result;
    }
    
    /**
     * Import customizer settings
     *
     * @param array $demo Demo package data
     * @return bool|WP_Error
     */
    private function import_customizer( $demo ) {
        // Check if demo has customizer file
        if ( empty( $demo['import_customizer_file_url'] ) ) {
            return true; // Skip if no customizer file
        }
        
        // Download customizer file
        $customizer_file = aqualuxe_demo_importer_download_file( $demo['import_customizer_file_url'] );
        if ( is_wp_error( $customizer_file ) ) {
            return $customizer_file;
        }
        
        // Import customizer settings
        $result = aqualuxe_demo_importer_import_customizer( $customizer_file );
        
        return $result;
    }
}