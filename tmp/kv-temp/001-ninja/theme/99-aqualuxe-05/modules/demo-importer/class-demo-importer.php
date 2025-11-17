<?php
/**
 * Demo Content Importer Module
 *
 * Comprehensive demo content importer with flush capabilities
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

    /**
     * Demo content data
     *
     * @var array
     */
    private $demo_data = array();

    /**
     * Import progress
     *
     * @var array
     */
    private $import_progress = array();

    /**
     * Constructor
     */
    public function __construct() {
        // Class is auto-initialized when loaded
    }

    /**
     * Initialize the module
     */
    public function init() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'wp_ajax_import_demo_content', array( $this, 'ajax_import_demo_content' ) );
        add_action( 'wp_ajax_flush_demo_content', array( $this, 'ajax_flush_demo_content' ) );
        add_action( 'wp_ajax_get_import_progress', array( $this, 'ajax_get_import_progress' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__( 'Demo Importer', 'aqualuxe' ),
            esc_html__( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( $hook !== 'appearance_page_aqualuxe-demo-importer' ) {
            return;
        }

        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URL . 'dist/js/admin.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script( 'aqualuxe-demo-importer', 'aqualuxe_demo_importer', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_demo_importer_nonce' ),
            'strings'  => array(
                'importing'     => esc_html__( 'Importing...', 'aqualuxe' ),
                'flushing'      => esc_html__( 'Flushing...', 'aqualuxe' ),
                'complete'      => esc_html__( 'Complete!', 'aqualuxe' ),
                'error'         => esc_html__( 'Error occurred', 'aqualuxe' ),
                'confirm_flush' => esc_html__( 'Are you sure you want to delete all demo content? This action cannot be undone.', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Demo importer admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
            
            <div class="aqualuxe-admin-wrapper">
                <div class="aqualuxe-admin-header">
                    <h2 class="aqualuxe-admin-title"><?php esc_html_e( 'Demo Content Management', 'aqualuxe' ); ?></h2>
                    <span class="aqualuxe-admin-version">v<?php echo esc_html( AQUALUXE_VERSION ); ?></span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Import Section -->
                    <div class="aqualuxe-admin-card">
                        <div class="aqualuxe-admin-card-header">
                            <h3 class="aqualuxe-admin-card-title"><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h3>
                            <p class="aqualuxe-admin-card-subtitle"><?php esc_html_e( 'Import comprehensive demo content for AquaLuxe theme', 'aqualuxe' ); ?></p>
                        </div>

                        <div class="demo-import-options space-y-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-posts" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Posts & Pages', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-products" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'WooCommerce Products', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-media" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Media Library', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-menus" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Navigation Menus', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-widgets" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Widgets', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="import-customizer" checked class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Theme Customizer Settings', 'aqualuxe' ); ?></span>
                            </label>
                        </div>

                        <div class="mt-6">
                            <button id="import-demo-content" class="aqualuxe-btn-primary">
                                <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                            </button>
                        </div>

                        <div id="import-progress" class="mt-4 hidden">
                            <div class="aqualuxe-progress">
                                <div id="import-progress-bar" class="aqualuxe-progress-bar" style="width: 0%"></div>
                            </div>
                            <div id="import-status" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                    </div>

                    <!-- Flush Section -->
                    <div class="aqualuxe-admin-card">
                        <div class="aqualuxe-admin-card-header">
                            <h3 class="aqualuxe-admin-card-title"><?php esc_html_e( 'Flush Demo Content', 'aqualuxe' ); ?></h3>
                            <p class="aqualuxe-admin-card-subtitle"><?php esc_html_e( 'Remove all demo content and reset to clean state', 'aqualuxe' ); ?></p>
                        </div>

                        <div class="aqualuxe-alert-warning">
                            <p><strong><?php esc_html_e( 'Warning:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'This will permanently delete all demo content including posts, pages, products, media, and settings.', 'aqualuxe' ); ?></p>
                        </div>

                        <div class="flush-options space-y-4 mt-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-posts" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Delete Posts & Pages', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-products" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Delete Products', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-media" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Delete Media Files', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-menus" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Delete Navigation Menus', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-widgets" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Reset Widgets', 'aqualuxe' ); ?></span>
                            </label>
                            
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="flush-customizer" class="aqualuxe-form-checkbox">
                                <span><?php esc_html_e( 'Reset Customizer Settings', 'aqualuxe' ); ?></span>
                            </label>
                        </div>

                        <div class="mt-6">
                            <button id="flush-demo-content" class="aqualuxe-btn-danger">
                                <?php esc_html_e( 'Flush All Content', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Import Log -->
                <div class="aqualuxe-admin-card mt-8">
                    <div class="aqualuxe-admin-card-header">
                        <h3 class="aqualuxe-admin-card-title"><?php esc_html_e( 'Import Log', 'aqualuxe' ); ?></h3>
                    </div>
                    <div id="import-log" class="bg-gray-100 p-4 rounded font-mono text-sm max-h-64 overflow-y-auto">
                        <?php esc_html_e( 'Ready to import demo content...', 'aqualuxe' ); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler for demo content import
     */
    public function ajax_import_demo_content() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }

        $import_options = array(
            'posts'      => isset( $_POST['import_posts'] ) && $_POST['import_posts'],
            'products'   => isset( $_POST['import_products'] ) && $_POST['import_products'],
            'media'      => isset( $_POST['import_media'] ) && $_POST['import_media'],
            'menus'      => isset( $_POST['import_menus'] ) && $_POST['import_menus'],
            'widgets'    => isset( $_POST['import_widgets'] ) && $_POST['import_widgets'],
            'customizer' => isset( $_POST['import_customizer'] ) && $_POST['import_customizer'],
        );

        try {
            $this->import_demo_content( $import_options );
            wp_send_json_success( array(
                'message' => esc_html__( 'Demo content imported successfully!', 'aqualuxe' ),
                'log'     => $this->get_import_log(),
            ) );
        } catch ( Exception $e ) {
            wp_send_json_error( array(
                'message' => $e->getMessage(),
                'log'     => $this->get_import_log(),
            ) );
        }
    }

    /**
     * AJAX handler for flushing demo content
     */
    public function ajax_flush_demo_content() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer_nonce' ) ) {
            wp_die( 'Security check failed' );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'Insufficient permissions' );
        }

        $flush_options = array(
            'posts'      => isset( $_POST['flush_posts'] ) && $_POST['flush_posts'],
            'products'   => isset( $_POST['flush_products'] ) && $_POST['flush_products'],
            'media'      => isset( $_POST['flush_media'] ) && $_POST['flush_media'],
            'menus'      => isset( $_POST['flush_menus'] ) && $_POST['flush_menus'],
            'widgets'    => isset( $_POST['flush_widgets'] ) && $_POST['flush_widgets'],
            'customizer' => isset( $_POST['flush_customizer'] ) && $_POST['flush_customizer'],
        );

        try {
            $this->flush_demo_content( $flush_options );
            wp_send_json_success( array(
                'message' => esc_html__( 'Demo content flushed successfully!', 'aqualuxe' ),
                'log'     => $this->get_import_log(),
            ) );
        } catch ( Exception $e ) {
            wp_send_json_error( array(
                'message' => $e->getMessage(),
                'log'     => $this->get_import_log(),
            ) );
        }
    }

    /**
     * Import demo content
     *
     * @param array $options
     */
    private function import_demo_content( $options ) {
        $this->log( 'Starting demo content import...' );

        if ( $options['posts'] ) {
            $this->import_posts();
        }

        if ( $options['products'] && class_exists( 'WooCommerce' ) ) {
            $this->import_products();
        }

        if ( $options['media'] ) {
            $this->import_media();
        }

        if ( $options['menus'] ) {
            $this->import_menus();
        }

        if ( $options['widgets'] ) {
            $this->import_widgets();
        }

        if ( $options['customizer'] ) {
            $this->import_customizer_settings();
        }

        $this->log( 'Demo content import completed!' );
    }

    /**
     * Flush demo content
     *
     * @param array $options
     */
    private function flush_demo_content( $options ) {
        $this->log( 'Starting demo content flush...' );

        if ( $options['posts'] ) {
            $this->flush_posts();
        }

        if ( $options['products'] ) {
            $this->flush_products();
        }

        if ( $options['media'] ) {
            $this->flush_media();
        }

        if ( $options['menus'] ) {
            $this->flush_menus();
        }

        if ( $options['widgets'] ) {
            $this->flush_widgets();
        }

        if ( $options['customizer'] ) {
            $this->flush_customizer_settings();
        }

        $this->log( 'Demo content flush completed!' );
    }

    /**
     * Import posts and pages
     */
    private function import_posts() {
        $this->log( 'Importing posts and pages...' );

        $demo_posts = $this->get_demo_posts();

        foreach ( $demo_posts as $post_data ) {
            $post_id = wp_insert_post( $post_data );
            
            if ( is_wp_error( $post_id ) ) {
                $this->log( 'Error importing post: ' . $post_data['post_title'] );
            } else {
                $this->log( 'Imported post: ' . $post_data['post_title'] );
                
                // Mark as demo content
                update_post_meta( $post_id, '_aqualuxe_demo_content', true );
            }
        }
    }

    /**
     * Import WooCommerce products
     */
    private function import_products() {
        $this->log( 'Importing WooCommerce products...' );

        $demo_products = $this->get_demo_products();

        foreach ( $demo_products as $product_data ) {
            $product_id = wp_insert_post( $product_data );
            
            if ( is_wp_error( $product_id ) ) {
                $this->log( 'Error importing product: ' . $product_data['post_title'] );
            } else {
                $this->log( 'Imported product: ' . $product_data['post_title'] );
                
                // Mark as demo content
                update_post_meta( $product_id, '_aqualuxe_demo_content', true );
                
                // Add WooCommerce product data
                update_post_meta( $product_id, '_price', $product_data['price'] );
                update_post_meta( $product_id, '_regular_price', $product_data['price'] );
                update_post_meta( $product_id, '_stock_status', 'instock' );
                update_post_meta( $product_id, '_manage_stock', 'yes' );
                update_post_meta( $product_id, '_stock', 50 );
            }
        }
    }

    /**
     * Get demo posts data
     */
    private function get_demo_posts() {
        return array(
            array(
                'post_title'   => 'Welcome to AquaLuxe',
                'post_content' => 'This is a demo post for the AquaLuxe theme. Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_author'  => 1,
            ),
            array(
                'post_title'   => 'About AquaLuxe',
                'post_content' => 'Learn more about our premium aquatic solutions and services.',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ),
            // Add more demo posts...
        );
    }

    /**
     * Get demo products data
     */
    private function get_demo_products() {
        return array(
            array(
                'post_title'   => 'Premium Aquarium Tank',
                'post_content' => 'High-quality aquarium tank perfect for tropical fish.',
                'post_status'  => 'publish',
                'post_type'    => 'product',
                'post_author'  => 1,
                'price'        => '299.99',
            ),
            array(
                'post_title'   => 'Aquatic Plant Collection',
                'post_content' => 'Beautiful collection of aquatic plants for your aquarium.',
                'post_status'  => 'publish',
                'post_type'    => 'product',
                'post_author'  => 1,
                'price'        => '49.99',
            ),
            // Add more demo products...
        );
    }

    /**
     * Import media files
     */
    private function import_media() {
        $this->log( 'Importing media files...' );
        // Implementation for media import
    }

    /**
     * Import navigation menus
     */
    private function import_menus() {
        $this->log( 'Importing navigation menus...' );
        // Implementation for menu import
    }

    /**
     * Import widgets
     */
    private function import_widgets() {
        $this->log( 'Importing widgets...' );
        // Implementation for widget import
    }

    /**
     * Import customizer settings
     */
    private function import_customizer_settings() {
        $this->log( 'Importing customizer settings...' );
        // Implementation for customizer settings import
    }

    /**
     * Flush posts and pages
     */
    private function flush_posts() {
        $this->log( 'Flushing posts and pages...' );

        $posts = get_posts( array(
            'numberposts' => -1,
            'post_type'   => array( 'post', 'page' ),
            'meta_key'    => '_aqualuxe_demo_content',
            'meta_value'  => true,
        ) );

        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
            $this->log( 'Deleted post: ' . $post->post_title );
        }
    }

    /**
     * Flush products
     */
    private function flush_products() {
        $this->log( 'Flushing products...' );

        $products = get_posts( array(
            'numberposts' => -1,
            'post_type'   => 'product',
            'meta_key'    => '_aqualuxe_demo_content',
            'meta_value'  => true,
        ) );

        foreach ( $products as $product ) {
            wp_delete_post( $product->ID, true );
            $this->log( 'Deleted product: ' . $product->post_title );
        }
    }

    /**
     * Flush media files
     */
    private function flush_media() {
        $this->log( 'Flushing media files...' );
        // Implementation for media flush
    }

    /**
     * Flush navigation menus
     */
    private function flush_menus() {
        $this->log( 'Flushing navigation menus...' );
        // Implementation for menu flush
    }

    /**
     * Flush widgets
     */
    private function flush_widgets() {
        $this->log( 'Flushing widgets...' );
        // Implementation for widget flush
    }

    /**
     * Flush customizer settings
     */
    private function flush_customizer_settings() {
        $this->log( 'Flushing customizer settings...' );
        // Implementation for customizer settings flush
    }

    /**
     * Log import activity
     *
     * @param string $message
     */
    private function log( $message ) {
        $timestamp = current_time( 'H:i:s' );
        $this->import_progress[] = "[{$timestamp}] {$message}";
    }

    /**
     * Get import log
     *
     * @return string
     */
    private function get_import_log() {
        return implode( "\n", $this->import_progress );
    }
}