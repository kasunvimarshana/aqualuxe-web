<?php
/**
 * AquaLuxe Demo Importer Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Demo Importer Module Class
 */
class AquaLuxe_Module_Demo_Importer {
    /**
     * Demo data
     *
     * @var array
     */
    private $demos = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Register demos
        $this->register_demos();
        
        // Add admin page
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        
        // Register scripts and styles
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        
        // Add AJAX handlers
        add_action( 'wp_ajax_aqualuxe_import_demo', [ $this, 'import_demo_ajax' ] );
    }

    /**
     * Register demos
     */
    private function register_demos() {
        $this->demos = [
            'default' => [
                'name' => esc_html__( 'Default Demo', 'aqualuxe' ),
                'description' => esc_html__( 'The default AquaLuxe demo with all features.', 'aqualuxe' ),
                'preview' => get_template_directory_uri() . '/modules/demo-importer/previews/default.jpg',
                'import_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/default.xml',
                'import_widget_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/default-widgets.wie',
                'import_customizer_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/default-customizer.dat',
                'import_preview_image_url' => get_template_directory_uri() . '/modules/demo-importer/previews/default.jpg',
                'preview_url' => 'https://aqualuxe.com/demo/default/',
            ],
            'minimal' => [
                'name' => esc_html__( 'Minimal Demo', 'aqualuxe' ),
                'description' => esc_html__( 'A minimal version of AquaLuxe with a clean design.', 'aqualuxe' ),
                'preview' => get_template_directory_uri() . '/modules/demo-importer/previews/minimal.jpg',
                'import_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/minimal.xml',
                'import_widget_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/minimal-widgets.wie',
                'import_customizer_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/minimal-customizer.dat',
                'import_preview_image_url' => get_template_directory_uri() . '/modules/demo-importer/previews/minimal.jpg',
                'preview_url' => 'https://aqualuxe.com/demo/minimal/',
            ],
            'shop' => [
                'name' => esc_html__( 'Shop Demo', 'aqualuxe' ),
                'description' => esc_html__( 'A demo focused on the shop features of AquaLuxe.', 'aqualuxe' ),
                'preview' => get_template_directory_uri() . '/modules/demo-importer/previews/shop.jpg',
                'import_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/shop.xml',
                'import_widget_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/shop-widgets.wie',
                'import_customizer_file_url' => get_template_directory_uri() . '/modules/demo-importer/data/shop-customizer.dat',
                'import_preview_image_url' => get_template_directory_uri() . '/modules/demo-importer/previews/shop.jpg',
                'preview_url' => 'https://aqualuxe.com/demo/shop/',
            ],
        ];
    }

    /**
     * Add admin page
     */
    public function add_admin_page() {
        add_theme_page(
            esc_html__( 'AquaLuxe Demo Importer', 'aqualuxe' ),
            esc_html__( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            [ $this, 'render_admin_page' ]
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
            
            <div class="aqualuxe-demo-importer-notice notice notice-warning">
                <p>
                    <strong><?php esc_html_e( 'Important:', 'aqualuxe' ); ?></strong>
                    <?php esc_html_e( 'Importing demo data will overwrite your current theme options, sliders, widgets, and other settings. It is recommended to use this feature on a fresh WordPress installation. Please backup your database and files before importing demo data.', 'aqualuxe' ); ?>
                </p>
            </div>
            
            <div class="aqualuxe-demo-importer-demos">
                <?php foreach ( $this->demos as $id => $demo ) : ?>
                    <div class="aqualuxe-demo-importer-demo">
                        <div class="aqualuxe-demo-importer-demo-preview">
                            <img src="<?php echo esc_url( $demo['preview'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>">
                        </div>
                        <div class="aqualuxe-demo-importer-demo-info">
                            <h2><?php echo esc_html( $demo['name'] ); ?></h2>
                            <p><?php echo esc_html( $demo['description'] ); ?></p>
                            <div class="aqualuxe-demo-importer-demo-actions">
                                <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="button" target="_blank">
                                    <?php esc_html_e( 'Preview', 'aqualuxe' ); ?>
                                </a>
                                <button class="button button-primary aqualuxe-demo-importer-import" data-demo-id="<?php echo esc_attr( $id ); ?>">
                                    <?php esc_html_e( 'Import', 'aqualuxe' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue assets
     *
     * @param string $hook_suffix Hook suffix
     */
    public function enqueue_assets( $hook_suffix ) {
        if ( $hook_suffix !== 'appearance_page_aqualuxe-demo-importer' ) {
            return;
        }
        
        // Get the asset manager instance
        $assets = AquaLuxe_Assets::instance();
        
        // Enqueue style
        $assets->enqueue_style( 'aqualuxe-demo-importer', 'css/demo-importer.css' );
        
        // Enqueue script
        $assets->enqueue_script( 'aqualuxe-demo-importer', 'js/demo-importer.js', [ 'jquery' ], true );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-demo-importer',
            'aqualuxeDemoImporter',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'aqualuxe-demo-importer' ),
                'demos' => $this->demos,
                'i18n' => [
                    'importing' => esc_html__( 'Importing...', 'aqualuxe' ),
                    'imported' => esc_html__( 'Imported', 'aqualuxe' ),
                    'importError' => esc_html__( 'Import Error', 'aqualuxe' ),
                    'confirmImport' => esc_html__( 'Are you sure you want to import this demo? All current theme settings, widgets, and customizer options will be overwritten.', 'aqualuxe' ),
                ],
            ]
        );
    }

    /**
     * Import demo AJAX
     */
    public function import_demo_ajax() {
        // Check nonce
        if ( ! check_ajax_referer( 'aqualuxe-demo-importer', 'nonce', false ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ] );
        }
        
        // Check if user has permission
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'You do not have permission to import demos.', 'aqualuxe' ) ] );
        }
        
        // Get demo ID
        $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( $_POST['demo_id'] ) : '';
        
        if ( empty( $demo_id ) || ! isset( $this->demos[ $demo_id ] ) ) {
            wp_send_json_error( [ 'message' => esc_html__( 'Invalid demo ID.', 'aqualuxe' ) ] );
        }
        
        // Get demo data
        $demo = $this->demos[ $demo_id ];
        
        // Import demo
        $result = $this->import_demo( $demo );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }
        
        wp_send_json_success( [ 'message' => esc_html__( 'Demo imported successfully.', 'aqualuxe' ) ] );
    }

    /**
     * Import demo
     *
     * @param array $demo Demo data
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    private function import_demo( $demo ) {
        // Check if required plugins are active
        if ( ! $this->check_required_plugins() ) {
            return new WP_Error( 'missing_plugins', esc_html__( 'Required plugins are not active.', 'aqualuxe' ) );
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
        
        // Import customizer
        $customizer_result = $this->import_customizer( $demo );
        
        if ( is_wp_error( $customizer_result ) ) {
            return $customizer_result;
        }
        
        // Set up pages
        $this->set_up_pages();
        
        // Set up menus
        $this->set_up_menus();
        
        return true;
    }

    /**
     * Check if required plugins are active
     *
     * @return bool
     */
    private function check_required_plugins() {
        // Check if WordPress Importer is active
        if ( ! class_exists( 'WP_Importer' ) ) {
            return false;
        }
        
        // Check if Widget Importer & Exporter is active
        if ( ! class_exists( 'Widget_Importer_Exporter' ) ) {
            return false;
        }
        
        return true;
    }

    /**
     * Import content
     *
     * @param array $demo Demo data
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    private function import_content( $demo ) {
        // Check if import file exists
        if ( empty( $demo['import_file_url'] ) ) {
            return new WP_Error( 'missing_import_file', esc_html__( 'Import file not found.', 'aqualuxe' ) );
        }
        
        // Download import file
        $import_file = download_url( $demo['import_file_url'] );
        
        if ( is_wp_error( $import_file ) ) {
            return $import_file;
        }
        
        // Include WordPress Importer
        if ( ! class_exists( 'WP_Importer' ) ) {
            return new WP_Error( 'missing_importer', esc_html__( 'WordPress Importer not found.', 'aqualuxe' ) );
        }
        
        if ( ! class_exists( 'WP_Import' ) ) {
            $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
            
            if ( file_exists( $class_wp_importer ) ) {
                require_once $class_wp_importer;
            }
            
            $class_wp_import = ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
            
            if ( file_exists( $class_wp_import ) ) {
                require_once $class_wp_import;
            } else {
                return new WP_Error( 'missing_importer', esc_html__( 'WordPress Importer not found.', 'aqualuxe' ) );
            }
        }
        
        // Import content
        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        
        ob_start();
        $wp_import->import( $import_file );
        ob_end_clean();
        
        // Delete import file
        unlink( $import_file );
        
        return true;
    }

    /**
     * Import widgets
     *
     * @param array $demo Demo data
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    private function import_widgets( $demo ) {
        // Check if import file exists
        if ( empty( $demo['import_widget_file_url'] ) ) {
            return new WP_Error( 'missing_widget_file', esc_html__( 'Widget import file not found.', 'aqualuxe' ) );
        }
        
        // Download import file
        $import_file = download_url( $demo['import_widget_file_url'] );
        
        if ( is_wp_error( $import_file ) ) {
            return $import_file;
        }
        
        // Include Widget Importer & Exporter
        if ( ! class_exists( 'Widget_Importer_Exporter' ) ) {
            return new WP_Error( 'missing_widget_importer', esc_html__( 'Widget Importer & Exporter not found.', 'aqualuxe' ) );
        }
        
        // Import widgets
        $wie = new Widget_Importer_Exporter();
        $wie->import_widgets( $import_file );
        
        // Delete import file
        unlink( $import_file );
        
        return true;
    }

    /**
     * Import customizer
     *
     * @param array $demo Demo data
     * @return bool|WP_Error True on success, WP_Error on failure
     */
    private function import_customizer( $demo ) {
        // Check if import file exists
        if ( empty( $demo['import_customizer_file_url'] ) ) {
            return new WP_Error( 'missing_customizer_file', esc_html__( 'Customizer import file not found.', 'aqualuxe' ) );
        }
        
        // Download import file
        $import_file = download_url( $demo['import_customizer_file_url'] );
        
        if ( is_wp_error( $import_file ) ) {
            return $import_file;
        }
        
        // Import customizer
        $customizer_data = file_get_contents( $import_file );
        $customizer_data = json_decode( $customizer_data, true );
        
        if ( ! is_array( $customizer_data ) ) {
            return new WP_Error( 'invalid_customizer_data', esc_html__( 'Invalid customizer data.', 'aqualuxe' ) );
        }
        
        // Update customizer options
        foreach ( $customizer_data as $key => $value ) {
            set_theme_mod( $key, $value );
        }
        
        // Delete import file
        unlink( $import_file );
        
        return true;
    }

    /**
     * Set up pages
     */
    private function set_up_pages() {
        // Set front page
        $front_page = get_page_by_title( 'Home' );
        
        if ( $front_page ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $front_page->ID );
        }
        
        // Set blog page
        $blog_page = get_page_by_title( 'Blog' );
        
        if ( $blog_page ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
        
        // Set shop page
        if ( function_exists( 'wc_get_page_id' ) ) {
            $shop_page = get_page_by_title( 'Shop' );
            
            if ( $shop_page ) {
                update_option( 'woocommerce_shop_page_id', $shop_page->ID );
            }
            
            // Set cart page
            $cart_page = get_page_by_title( 'Cart' );
            
            if ( $cart_page ) {
                update_option( 'woocommerce_cart_page_id', $cart_page->ID );
            }
            
            // Set checkout page
            $checkout_page = get_page_by_title( 'Checkout' );
            
            if ( $checkout_page ) {
                update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
            }
            
            // Set account page
            $account_page = get_page_by_title( 'My Account' );
            
            if ( $account_page ) {
                update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
            }
        }
    }

    /**
     * Set up menus
     */
    private function set_up_menus() {
        // Get menus
        $primary_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );
        $secondary_menu = get_term_by( 'name', 'Secondary Menu', 'nav_menu' );
        $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
        $mobile_menu = get_term_by( 'name', 'Mobile Menu', 'nav_menu' );
        
        // Set menu locations
        $locations = [];
        
        if ( $primary_menu ) {
            $locations['primary'] = $primary_menu->term_id;
        }
        
        if ( $secondary_menu ) {
            $locations['secondary'] = $secondary_menu->term_id;
        }
        
        if ( $footer_menu ) {
            $locations['footer'] = $footer_menu->term_id;
        }
        
        if ( $mobile_menu ) {
            $locations['mobile'] = $mobile_menu->term_id;
        }
        
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}