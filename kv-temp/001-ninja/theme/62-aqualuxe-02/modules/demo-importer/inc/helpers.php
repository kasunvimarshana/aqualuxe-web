<?php
/**
 * AquaLuxe Demo Importer Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Demo_Importer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if a plugin is installed
 *
 * @param string $slug Plugin slug
 * @return bool
 */
function aqualuxe_demo_importer_is_plugin_installed( $slug ) {
    if ( ! function_exists( 'get_plugins' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    $all_plugins = get_plugins();
    
    if ( $slug === 'woocommerce' ) {
        return array_key_exists( 'woocommerce/woocommerce.php', $all_plugins );
    }
    
    if ( $slug === 'contact-form-7' ) {
        return array_key_exists( 'contact-form-7/wp-contact-form-7.php', $all_plugins );
    }
    
    // Add more plugin checks as needed
    
    return false;
}

/**
 * Check if a plugin is active
 *
 * @param string $slug Plugin slug
 * @return bool
 */
function aqualuxe_demo_importer_is_plugin_active( $slug ) {
    if ( $slug === 'woocommerce' ) {
        return class_exists( 'WooCommerce' );
    }
    
    if ( $slug === 'contact-form-7' ) {
        return class_exists( 'WPCF7' );
    }
    
    // Add more plugin checks as needed
    
    return false;
}

/**
 * Install a plugin
 *
 * @param string $slug Plugin slug
 * @return bool|WP_Error
 */
function aqualuxe_demo_importer_install_plugin( $slug ) {
    if ( ! function_exists( 'plugins_api' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    }
    
    if ( ! class_exists( 'WP_Upgrader' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    }
    
    if ( ! class_exists( 'Plugin_Upgrader' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
    }
    
    if ( ! class_exists( 'WP_Upgrader_Skin' ) ) {
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
    }
    
    // Check if plugin is already installed
    if ( aqualuxe_demo_importer_is_plugin_installed( $slug ) ) {
        return true;
    }
    
    // Get plugin info
    $api = plugins_api( 'plugin_information', array(
        'slug' => $slug,
        'fields' => array(
            'short_description' => false,
            'sections' => false,
            'requires' => false,
            'rating' => false,
            'ratings' => false,
            'downloaded' => false,
            'last_updated' => false,
            'added' => false,
            'tags' => false,
            'compatibility' => false,
            'homepage' => false,
            'donate_link' => false,
        ),
    ) );
    
    if ( is_wp_error( $api ) ) {
        return $api;
    }
    
    // Install plugin
    $upgrader = new Plugin_Upgrader( new WP_Upgrader_Skin() );
    $result = $upgrader->install( $api->download_link );
    
    if ( is_wp_error( $result ) ) {
        return $result;
    }
    
    return true;
}

/**
 * Activate a plugin
 *
 * @param string $slug Plugin slug
 * @return bool|WP_Error
 */
function aqualuxe_demo_importer_activate_plugin( $slug ) {
    if ( ! function_exists( 'activate_plugin' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    
    // Check if plugin is already active
    if ( aqualuxe_demo_importer_is_plugin_active( $slug ) ) {
        return true;
    }
    
    // Get plugin file
    $plugin_file = '';
    
    if ( $slug === 'woocommerce' ) {
        $plugin_file = 'woocommerce/woocommerce.php';
    } elseif ( $slug === 'contact-form-7' ) {
        $plugin_file = 'contact-form-7/wp-contact-form-7.php';
    }
    
    // Add more plugin files as needed
    
    if ( empty( $plugin_file ) ) {
        return new WP_Error( 'plugin_not_found', __( 'Plugin not found.', 'aqualuxe' ) );
    }
    
    // Activate plugin
    $result = activate_plugin( $plugin_file );
    
    if ( is_wp_error( $result ) ) {
        return $result;
    }
    
    return true;
}

/**
 * Download a file from a URL
 *
 * @param string $url URL to download
 * @param string $file_name File name to save as
 * @return string|WP_Error Path to downloaded file or WP_Error
 */
function aqualuxe_demo_importer_download_file( $url, $file_name = '' ) {
    // Get file name from URL if not provided
    if ( empty( $file_name ) ) {
        $file_name = basename( $url );
    }
    
    // Create temporary directory
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/aqualuxe-demo-importer';
    
    // Create directory if it doesn't exist
    if ( ! file_exists( $temp_dir ) ) {
        wp_mkdir_p( $temp_dir );
    }
    
    // Create .htaccess file to protect directory
    if ( ! file_exists( $temp_dir . '/.htaccess' ) ) {
        $htaccess_content = "Options -Indexes\nDeny from all";
        file_put_contents( $temp_dir . '/.htaccess', $htaccess_content );
    }
    
    // Create index.php file to protect directory
    if ( ! file_exists( $temp_dir . '/index.php' ) ) {
        $index_content = "<?php\n// Silence is golden.";
        file_put_contents( $temp_dir . '/index.php', $index_content );
    }
    
    // Set file path
    $file_path = $temp_dir . '/' . $file_name;
    
    // Download file
    $response = wp_remote_get( $url, array(
        'timeout' => 60,
        'stream' => true,
        'filename' => $file_path,
    ) );
    
    // Check for errors
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    
    // Check response code
    $response_code = wp_remote_retrieve_response_code( $response );
    if ( $response_code !== 200 ) {
        return new WP_Error(
            'download_failed',
            sprintf(
                /* translators: %d: HTTP response code */
                __( 'Download failed. Server responded with %d.', 'aqualuxe' ),
                $response_code
            )
        );
    }
    
    return $file_path;
}

/**
 * Get all available widgets
 *
 * @return array
 */
function aqualuxe_demo_importer_get_available_widgets() {
    global $wp_registered_widget_controls;
    
    $widget_controls = $wp_registered_widget_controls;
    $available_widgets = array();
    
    foreach ( $widget_controls as $widget ) {
        if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
            $available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
            $available_widgets[ $widget['id_base'] ]['name'] = $widget['name'];
        }
    }
    
    return $available_widgets;
}

/**
 * Import widgets from WIE file
 *
 * @param string $file_path Path to WIE file
 * @return bool|WP_Error
 */
function aqualuxe_demo_importer_import_widgets( $file_path ) {
    // Check if file exists
    if ( ! file_exists( $file_path ) ) {
        return new WP_Error( 'file_not_found', __( 'Widget import file not found.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $data = file_get_contents( $file_path );
    $data = json_decode( $data, true );
    
    // Check if data is valid
    if ( empty( $data ) || ! is_array( $data ) ) {
        return new WP_Error( 'invalid_data', __( 'Widget import file is invalid.', 'aqualuxe' ) );
    }
    
    // Get available widgets
    $available_widgets = aqualuxe_demo_importer_get_available_widgets();
    
    // Get all sidebars
    $sidebars_widgets = get_option( 'sidebars_widgets' );
    
    // Loop through widgets
    foreach ( $data as $sidebar_id => $widgets ) {
        // Skip inactive widgets
        if ( 'wp_inactive_widgets' === $sidebar_id ) {
            continue;
        }
        
        // Check if sidebar exists
        if ( ! isset( $sidebars_widgets[ $sidebar_id ] ) ) {
            continue;
        }
        
        // Clear sidebar
        $sidebars_widgets[ $sidebar_id ] = array();
        
        // Loop through widgets
        foreach ( $widgets as $widget_instance_id => $widget ) {
            // Get widget ID base
            $id_base = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
            
            // Check if widget is available
            if ( ! isset( $available_widgets[ $id_base ] ) ) {
                continue;
            }
            
            // Get widget instance options
            $instance_options = get_option( 'widget_' . $id_base );
            
            // If widget instance doesn't exist, create it
            if ( ! is_array( $instance_options ) ) {
                $instance_options = array();
            }
            
            // Get widget instance ID
            $instance_id = preg_replace( '/^' . $id_base . '-/', '', $widget_instance_id );
            
            // Add widget instance
            $instance_options[ $instance_id ] = $widget;
            
            // Update widget instance options
            update_option( 'widget_' . $id_base, $instance_options );
            
            // Add widget to sidebar
            $sidebars_widgets[ $sidebar_id ][] = $widget_instance_id;
        }
    }
    
    // Update sidebars widgets
    update_option( 'sidebars_widgets', $sidebars_widgets );
    
    return true;
}

/**
 * Import customizer settings from DAT file
 *
 * @param string $file_path Path to DAT file
 * @return bool|WP_Error
 */
function aqualuxe_demo_importer_import_customizer( $file_path ) {
    // Check if file exists
    if ( ! file_exists( $file_path ) ) {
        return new WP_Error( 'file_not_found', __( 'Customizer import file not found.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $data = file_get_contents( $file_path );
    $data = unserialize( $data );
    
    // Check if data is valid
    if ( empty( $data ) || ! is_array( $data ) ) {
        return new WP_Error( 'invalid_data', __( 'Customizer import file is invalid.', 'aqualuxe' ) );
    }
    
    // Loop through customizer settings
    foreach ( $data as $option_key => $option_value ) {
        // Skip non-theme mods
        if ( strpos( $option_key, 'theme_mod_' ) !== 0 ) {
            continue;
        }
        
        // Get theme mod name
        $theme_mod_name = str_replace( 'theme_mod_', '', $option_key );
        
        // Update theme mod
        set_theme_mod( $theme_mod_name, $option_value );
    }
    
    return true;
}

/**
 * Clean up temporary files
 *
 * @return void
 */
function aqualuxe_demo_importer_cleanup() {
    // Get temporary directory
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/aqualuxe-demo-importer';
    
    // Check if directory exists
    if ( ! file_exists( $temp_dir ) ) {
        return;
    }
    
    // Get all files in directory
    $files = glob( $temp_dir . '/*' );
    
    // Loop through files
    foreach ( $files as $file ) {
        // Skip .htaccess and index.php
        if ( basename( $file ) === '.htaccess' || basename( $file ) === 'index.php' ) {
            continue;
        }
        
        // Delete file
        if ( is_file( $file ) ) {
            unlink( $file );
        }
    }
}