<?php
/**
 * Module Loader Class
 *
 * Handles loading and management of theme modules
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Module Loader Class
 */
class AquaLuxe_Module_Loader {

    /**
     * Loaded modules
     *
     * @var array
     */
    private $modules = array();

    /**
     * Available modules list
     *
     * @var array
     */
    private $available_modules = array(
        'dark-mode' => array(
            'name'        => 'Dark Mode',
            'description' => 'Toggle between light and dark themes with persistent user preference',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'dark-mode/class-dark-mode.php',
        ),
        'demo-importer' => array(
            'name'        => 'Demo Content Importer',
            'description' => 'Import and manage demo content with flush capabilities',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'demo-importer/class-demo-importer.php',
        ),
        'multilingual' => array(
            'name'        => 'Multilingual Support',
            'description' => 'Multi-language content management and translation support',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'multilingual/class-multilingual.php',
        ),
        'multicurrency' => array(
            'name'        => 'Multi-Currency',
            'description' => 'Multiple currency support for international commerce',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'multicurrency/class-multicurrency.php',
        ),
        'multivendor' => array(
            'name'        => 'Multi-Vendor',
            'description' => 'Multi-vendor marketplace functionality',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'multivendor/class-multivendor.php',
        ),
        'subscriptions' => array(
            'name'        => 'Subscriptions',
            'description' => 'Recurring payments and subscription management',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'subscriptions/class-subscriptions.php',
        ),
        'bookings' => array(
            'name'        => 'Bookings & Scheduling',
            'description' => 'Appointment and service booking system',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'bookings/class-bookings.php',
        ),
        'events' => array(
            'name'        => 'Events & Ticketing',
            'description' => 'Event management and ticket sales',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'events/class-events.php',
        ),
        'wholesale' => array(
            'name'        => 'Wholesale/B2B',
            'description' => 'Wholesale pricing and B2B functionality',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'wholesale/class-wholesale.php',
        ),
        'auctions' => array(
            'name'        => 'Auctions & Trade-ins',
            'description' => 'Auction system and trade-in functionality',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'auctions/class-auctions.php',
        ),
        'affiliates' => array(
            'name'        => 'Affiliate & Referrals',
            'description' => 'Affiliate program and referral system',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'affiliates/class-affiliates.php',
        ),
        'franchise' => array(
            'name'        => 'Franchise & Licensing',
            'description' => 'Franchise management and licensing system',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'franchise/class-franchise.php',
        ),
        'sustainability' => array(
            'name'        => 'R&D & Sustainability',
            'description' => 'Research, development and sustainability features',
            'version'     => '1.0.0',
            'enabled'     => true,
            'file'        => 'sustainability/class-sustainability.php',
        ),
    );

    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initialize the module loader
     */
    public function init() {
        add_action( 'after_setup_theme', array( $this, 'load_modules' ), 20 );
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'wp_ajax_toggle_module', array( $this, 'ajax_toggle_module' ) );
    }

    /**
     * Load all enabled modules
     */
    public function load_modules() {
        $enabled_modules = $this->get_enabled_modules();

        foreach ( $enabled_modules as $module_id => $module_config ) {
            $this->load_module( $module_id, $module_config );
        }

        do_action( 'aqualuxe_modules_loaded', $this->modules );
    }

    /**
     * Load a specific module
     *
     * @param string $module_id Module ID
     * @param array  $module_config Module configuration
     * @return bool Success status
     */
    public function load_module( $module_id, $module_config ) {
        if ( isset( $this->modules[ $module_id ] ) ) {
            return true; // Already loaded
        }

        $module_path = AQUALUXE_MODULES_DIR . $module_config['file'];

        if ( ! file_exists( $module_path ) ) {
            return false;
        }

        try {
            require_once $module_path;

            // Get the module class name
            $class_name = $this->get_module_class_name( $module_id );

            if ( class_exists( $class_name ) ) {
                $this->modules[ $module_id ] = new $class_name();
                
                // Initialize the module if it has an init method
                if ( method_exists( $this->modules[ $module_id ], 'init' ) ) {
                    $this->modules[ $module_id ]->init();
                }

                do_action( 'aqualuxe_module_loaded', $module_id, $this->modules[ $module_id ] );
                
                return true;
            }
        } catch ( Exception $e ) {
            error_log( 'AquaLuxe Module Loading Error: ' . $e->getMessage() );
            return false;
        }

        return false;
    }

    /**
     * Get module class name from module ID
     *
     * @param string $module_id Module ID
     * @return string Class name
     */
    private function get_module_class_name( $module_id ) {
        $class_name = str_replace( '-', '_', $module_id );
        return 'AquaLuxe_' . ucwords( $class_name, '_' );
    }

    /**
     * Get enabled modules
     *
     * @return array Enabled modules
     */
    public function get_enabled_modules() {
        $enabled = array();
        
        foreach ( $this->available_modules as $module_id => $module_config ) {
            if ( $this->is_module_enabled( $module_id ) ) {
                $enabled[ $module_id ] = $module_config;
            }
        }

        return $enabled;
    }

    /**
     * Check if a module is enabled
     *
     * @param string $module_id Module ID
     * @return bool
     */
    public function is_module_enabled( $module_id ) {
        $saved_modules = get_option( 'aqualuxe_enabled_modules', array() );
        
        // If no saved preference, use default from module config
        if ( empty( $saved_modules ) ) {
            return isset( $this->available_modules[ $module_id ] ) && $this->available_modules[ $module_id ]['enabled'];
        }

        return isset( $saved_modules[ $module_id ] ) && $saved_modules[ $module_id ];
    }

    /**
     * Enable a module
     *
     * @param string $module_id Module ID
     * @return bool Success status
     */
    public function enable_module( $module_id ) {
        if ( ! isset( $this->available_modules[ $module_id ] ) ) {
            return false;
        }

        $saved_modules = get_option( 'aqualuxe_enabled_modules', array() );
        $saved_modules[ $module_id ] = true;
        
        return update_option( 'aqualuxe_enabled_modules', $saved_modules );
    }

    /**
     * Disable a module
     *
     * @param string $module_id Module ID
     * @return bool Success status
     */
    public function disable_module( $module_id ) {
        $saved_modules = get_option( 'aqualuxe_enabled_modules', array() );
        $saved_modules[ $module_id ] = false;
        
        return update_option( 'aqualuxe_enabled_modules', $saved_modules );
    }

    /**
     * Get all available modules
     *
     * @return array Available modules
     */
    public function get_available_modules() {
        return $this->available_modules;
    }

    /**
     * Get loaded modules
     *
     * @return array Loaded modules
     */
    public function get_loaded_modules() {
        return $this->modules;
    }

    /**
     * Get a specific module instance
     *
     * @param string $module_id Module ID
     * @return object|null Module instance or null
     */
    public function get_module( $module_id ) {
        return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : null;
    }

    /**
     * Add admin menu for module management
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__( 'AquaLuxe Modules', 'aqualuxe' ),
            esc_html__( 'Theme Modules', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-modules',
            array( $this, 'modules_admin_page' )
        );
    }

    /**
     * Modules admin page
     */
    public function modules_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Theme Modules', 'aqualuxe' ); ?></h1>
            <p><?php esc_html_e( 'Enable or disable theme modules to customize functionality.', 'aqualuxe' ); ?></p>
            
            <div class="aqualuxe-modules-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                <?php foreach ( $this->available_modules as $module_id => $module_config ) : ?>
                    <div class="module-card" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px; background: #fff;">
                        <div class="module-header" style="display: flex; justify-content: between; align-items: center; margin-bottom: 15px;">
                            <h3 style="margin: 0; font-size: 18px;"><?php echo esc_html( $module_config['name'] ); ?></h3>
                            <label class="toggle-switch" style="margin-left: auto;">
                                <input type="checkbox" 
                                       class="module-toggle" 
                                       data-module="<?php echo esc_attr( $module_id ); ?>"
                                       <?php checked( $this->is_module_enabled( $module_id ) ); ?>>
                                <span class="slider" style="position: relative; display: inline-block; width: 50px; height: 24px; background-color: #ccc; border-radius: 24px; transition: .4s; cursor: pointer;"></span>
                            </label>
                        </div>
                        <p style="color: #666; margin-bottom: 10px;"><?php echo esc_html( $module_config['description'] ); ?></p>
                        <div class="module-meta" style="font-size: 12px; color: #999;">
                            <span><?php esc_html_e( 'Version:', 'aqualuxe' ); ?> <?php echo esc_html( $module_config['version'] ); ?></span>
                            <span style="margin-left: 15px;">
                                <?php esc_html_e( 'Status:', 'aqualuxe' ); ?> 
                                <span class="module-status" style="font-weight: bold; color: <?php echo $this->is_module_enabled( $module_id ) ? '#28a745' : '#dc3545'; ?>;">
                                    <?php echo $this->is_module_enabled( $module_id ) ? esc_html__( 'Enabled', 'aqualuxe' ) : esc_html__( 'Disabled', 'aqualuxe' ); ?>
                                </span>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            $('.module-toggle').on('change', function() {
                const moduleId = $(this).data('module');
                const enabled = $(this).is(':checked');
                const card = $(this).closest('.module-card');
                const status = card.find('.module-status');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'toggle_module',
                        module_id: moduleId,
                        enabled: enabled,
                        nonce: '<?php echo wp_create_nonce( 'aqualuxe_modules_nonce' ); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            status.text(enabled ? '<?php esc_html_e( 'Enabled', 'aqualuxe' ); ?>' : '<?php esc_html_e( 'Disabled', 'aqualuxe' ); ?>');
                            status.css('color', enabled ? '#28a745' : '#dc3545');
                        } else {
                            alert('<?php esc_html_e( 'Error updating module status', 'aqualuxe' ); ?>');
                            $(this).prop('checked', !enabled);
                        }
                    },
                    error: function() {
                        alert('<?php esc_html_e( 'Error updating module status', 'aqualuxe' ); ?>');
                        $(this).prop('checked', !enabled);
                    }
                });
            });
        });
        </script>

        <style>
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            border-radius: 50%;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }
        </style>
        <?php
    }

    /**
     * AJAX handler for toggling modules
     */
    public function ajax_toggle_module() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_modules_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }

        $module_id = sanitize_text_field( $_POST['module_id'] );
        $enabled = (bool) $_POST['enabled'];

        if ( $enabled ) {
            $success = $this->enable_module( $module_id );
        } else {
            $success = $this->disable_module( $module_id );
        }

        if ( $success ) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}