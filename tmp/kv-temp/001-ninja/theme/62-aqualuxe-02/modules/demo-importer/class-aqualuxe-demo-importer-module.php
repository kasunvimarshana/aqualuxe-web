<?php
/**
 * AquaLuxe Demo Importer Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Demo Importer Module Class
 */
class AquaLuxe_Demo_Importer_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $module_id = 'demo-importer';

    /**
     * Module name
     *
     * @var string
     */
    protected $module_name = 'Demo Importer';

    /**
     * Module description
     *
     * @var string
     */
    protected $module_description = 'Imports demo content for the AquaLuxe theme with one-click installation.';

    /**
     * Module version
     *
     * @var string
     */
    protected $module_version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = array();

    /**
     * Demo packages
     *
     * @var array
     */
    private $demo_packages = array();

    /**
     * Initialize module
     *
     * @return void
     */
    public function init() {
        // Include required files
        $this->includes();
        
        // Register hooks
        $this->register_hooks();
        
        // Register demo packages
        $this->register_demo_packages();
        
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        
        // Register AJAX handlers
        add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'ajax_import_demo' ) );
        
        // Add admin notices
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Include required files
     *
     * @return void
     */
    private function includes() {
        // Include helper functions
        require_once $this->get_module_dir() . 'inc/helpers.php';
        
        // Include importer class
        require_once $this->get_module_dir() . 'inc/class-aqualuxe-demo-importer.php';
    }

    /**
     * Register hooks
     *
     * @return void
     */
    private function register_hooks() {
        // Register assets
        add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
        
        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }

    /**
     * Register assets
     *
     * @return void
     */
    public function register_assets() {
        // Register styles
        wp_register_style(
            'aqualuxe-demo-importer-admin',
            $this->get_module_uri() . 'assets/css/demo-importer-admin.css',
            array(),
            $this->get_module_version()
        );
        
        // Register scripts
        wp_register_script(
            'aqualuxe-demo-importer-admin',
            $this->get_module_uri() . 'assets/js/demo-importer-admin.js',
            array( 'jquery' ),
            $this->get_module_version(),
            true
        );
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets( $hook ) {
        // Only enqueue on demo importer page
        if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style( 'aqualuxe-demo-importer-admin' );
        
        // Enqueue scripts
        wp_enqueue_script( 'aqualuxe-demo-importer-admin' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-demo-importer-admin',
            'aqualuxeDemoImporter',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe_demo_importer' ),
                'i18n' => array(
                    'importing' => __( 'Importing demo content...', 'aqualuxe' ),
                    'imported' => __( 'Demo content imported successfully!', 'aqualuxe' ),
                    'error' => __( 'Error importing demo content.', 'aqualuxe' ),
                    'confirm' => __( 'Are you sure you want to import this demo? This will overwrite your current content.', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Register demo packages
     *
     * @return void
     */
    private function register_demo_packages() {
        $this->demo_packages = array(
            'main' => array(
                'name' => __( 'Main Demo', 'aqualuxe' ),
                'description' => __( 'The main demo with all features and pages.', 'aqualuxe' ),
                'preview_url' => 'https://demo.aqualuxetheme.com/',
                'screenshot' => $this->get_module_uri() . 'assets/images/main-demo.jpg',
                'import_file_url' => 'https://demo.aqualuxetheme.com/demo-content/main-demo.xml',
                'import_widget_file_url' => 'https://demo.aqualuxetheme.com/demo-content/main-widgets.wie',
                'import_customizer_file_url' => 'https://demo.aqualuxetheme.com/demo-content/main-customizer.dat',
                'required_plugins' => array(
                    'woocommerce' => array(
                        'name' => 'WooCommerce',
                        'slug' => 'woocommerce',
                        'required' => true,
                    ),
                    'contact-form-7' => array(
                        'name' => 'Contact Form 7',
                        'slug' => 'contact-form-7',
                        'required' => false,
                    ),
                ),
            ),
            'minimal' => array(
                'name' => __( 'Minimal Demo', 'aqualuxe' ),
                'description' => __( 'A minimal demo without WooCommerce.', 'aqualuxe' ),
                'preview_url' => 'https://demo.aqualuxetheme.com/minimal/',
                'screenshot' => $this->get_module_uri() . 'assets/images/minimal-demo.jpg',
                'import_file_url' => 'https://demo.aqualuxetheme.com/demo-content/minimal-demo.xml',
                'import_widget_file_url' => 'https://demo.aqualuxetheme.com/demo-content/minimal-widgets.wie',
                'import_customizer_file_url' => 'https://demo.aqualuxetheme.com/demo-content/minimal-customizer.dat',
                'required_plugins' => array(
                    'contact-form-7' => array(
                        'name' => 'Contact Form 7',
                        'slug' => 'contact-form-7',
                        'required' => false,
                    ),
                ),
            ),
            'blog' => array(
                'name' => __( 'Blog Demo', 'aqualuxe' ),
                'description' => __( 'A blog-focused demo with multiple layouts.', 'aqualuxe' ),
                'preview_url' => 'https://demo.aqualuxetheme.com/blog/',
                'screenshot' => $this->get_module_uri() . 'assets/images/blog-demo.jpg',
                'import_file_url' => 'https://demo.aqualuxetheme.com/demo-content/blog-demo.xml',
                'import_widget_file_url' => 'https://demo.aqualuxetheme.com/demo-content/blog-widgets.wie',
                'import_customizer_file_url' => 'https://demo.aqualuxetheme.com/demo-content/blog-customizer.dat',
                'required_plugins' => array(),
            ),
        );
    }

    /**
     * Add admin menu
     *
     * @return void
     */
    public function add_admin_menu() {
        add_theme_page(
            __( 'Import Demo Content', 'aqualuxe' ),
            __( 'Import Demo Content', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Admin page
     *
     * @return void
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
            
            <div class="aqualuxe-demo-importer-intro">
                <p><?php esc_html_e( 'Import demo content to get started with the AquaLuxe theme. Choose a demo below and click "Import Demo" to begin.', 'aqualuxe' ); ?></p>
                <p class="aqualuxe-demo-importer-warning"><?php esc_html_e( 'Warning: Importing demo content will overwrite your current theme settings and content. It is recommended to use this on a fresh WordPress installation.', 'aqualuxe' ); ?></p>
            </div>
            
            <div class="aqualuxe-demo-packages">
                <?php foreach ( $this->demo_packages as $demo_id => $demo ) : ?>
                    <div class="aqualuxe-demo-package">
                        <div class="aqualuxe-demo-package-screenshot">
                            <img src="<?php echo esc_url( $demo['screenshot'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>">
                        </div>
                        <div class="aqualuxe-demo-package-details">
                            <h2><?php echo esc_html( $demo['name'] ); ?></h2>
                            <p><?php echo esc_html( $demo['description'] ); ?></p>
                            
                            <?php if ( ! empty( $demo['required_plugins'] ) ) : ?>
                                <div class="aqualuxe-demo-package-plugins">
                                    <h3><?php esc_html_e( 'Required Plugins', 'aqualuxe' ); ?></h3>
                                    <ul>
                                        <?php foreach ( $demo['required_plugins'] as $plugin ) : ?>
                                            <li>
                                                <?php echo esc_html( $plugin['name'] ); ?>
                                                <?php if ( $plugin['required'] ) : ?>
                                                    <span class="aqualuxe-demo-package-required"><?php esc_html_e( '(Required)', 'aqualuxe' ); ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <div class="aqualuxe-demo-package-actions">
                                <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="button" target="_blank"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></a>
                                <button class="button button-primary aqualuxe-import-demo" data-demo-id="<?php echo esc_attr( $demo_id ); ?>"><?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="aqualuxe-demo-importer-modal">
                <div class="aqualuxe-demo-importer-modal-content">
                    <span class="aqualuxe-demo-importer-modal-close">&times;</span>
                    <h2><?php esc_html_e( 'Importing Demo Content', 'aqualuxe' ); ?></h2>
                    <div class="aqualuxe-demo-importer-modal-progress">
                        <div class="aqualuxe-demo-importer-progress-bar"></div>
                    </div>
                    <div class="aqualuxe-demo-importer-modal-status"></div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX import demo
     *
     * @return void
     */
    public function ajax_import_demo() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_demo_importer' ) ) {
            wp_send_json_error( array( 'message' => __( 'Invalid nonce.', 'aqualuxe' ) ) );
        }
        
        // Check if user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to import demo content.', 'aqualuxe' ) ) );
        }
        
        // Check if demo ID is set
        if ( ! isset( $_POST['demo_id'] ) ) {
            wp_send_json_error( array( 'message' => __( 'No demo ID specified.', 'aqualuxe' ) ) );
        }
        
        // Get demo ID
        $demo_id = sanitize_text_field( wp_unslash( $_POST['demo_id'] ) );
        
        // Check if demo exists
        if ( ! isset( $this->demo_packages[ $demo_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Demo not found.', 'aqualuxe' ) ) );
        }
        
        // Get demo package
        $demo = $this->demo_packages[ $demo_id ];
        
        // Check required plugins
        $missing_plugins = array();
        foreach ( $demo['required_plugins'] as $plugin_slug => $plugin ) {
            if ( $plugin['required'] && ! $this->is_plugin_active( $plugin_slug ) ) {
                $missing_plugins[] = $plugin['name'];
            }
        }
        
        // If there are missing required plugins, return error
        if ( ! empty( $missing_plugins ) ) {
            wp_send_json_error( array(
                'message' => sprintf(
                    /* translators: %s: list of plugin names */
                    __( 'The following required plugins are missing: %s', 'aqualuxe' ),
                    implode( ', ', $missing_plugins )
                ),
            ) );
        }
        
        // Create importer instance
        $importer = new AquaLuxe_Demo_Importer();
        
        // Import demo content
        $result = $importer->import_demo( $demo );
        
        // Check if import was successful
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }
        
        // Set transient to show success notice
        set_transient( 'aqualuxe_demo_imported', $demo_id, 60 );
        
        // Return success
        wp_send_json_success( array(
            'message' => sprintf(
                /* translators: %s: demo name */
                __( '%s demo imported successfully!', 'aqualuxe' ),
                $demo['name']
            ),
        ) );
    }

    /**
     * Admin notices
     *
     * @return void
     */
    public function admin_notices() {
        // Check if demo was imported
        $demo_id = get_transient( 'aqualuxe_demo_imported' );
        
        if ( $demo_id ) {
            // Delete transient
            delete_transient( 'aqualuxe_demo_imported' );
            
            // Get demo name
            $demo_name = isset( $this->demo_packages[ $demo_id ] ) ? $this->demo_packages[ $demo_id ]['name'] : '';
            
            // Show success notice
            ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html( sprintf(
                    /* translators: %s: demo name */
                    __( '%s demo imported successfully!', 'aqualuxe' ),
                    $demo_name
                ) ); ?></p>
            </div>
            <?php
        }
    }

    /**
     * Check if plugin is active
     *
     * @param string $slug Plugin slug
     * @return bool
     */
    private function is_plugin_active( $slug ) {
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
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return array(
            'active' => true,
        );
    }
}