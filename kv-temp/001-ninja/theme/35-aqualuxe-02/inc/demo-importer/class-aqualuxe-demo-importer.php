<?php
/**
 * Demo Content Importer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Demo Content Importer Class
 */
class AquaLuxe_Demo_Importer {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Demo data directory.
     *
     * @var string
     */
    protected $demo_data_dir;

    /**
     * Demo data URL.
     *
     * @var string
     */
    protected $demo_data_url;

    /**
     * Available demos.
     *
     * @var array
     */
    protected $demos = array();

    /**
     * Current demo being imported.
     *
     * @var string
     */
    protected $current_demo = '';

    /**
     * Import steps.
     *
     * @var array
     */
    protected $import_steps = array();

    /**
     * Logger instance.
     *
     * @var object
     */
    protected $logger = null;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->demo_data_dir = AQUALUXE_THEME_DIR . 'inc/demo-importer/demos/';
        $this->demo_data_url = AQUALUXE_THEME_URI . 'inc/demo-importer/demos/';

        // Set up available demos.
        $this->setup_demos();

        // Set up import steps.
        $this->setup_import_steps();

        // Initialize logger.
        $this->init_logger();

        // Add actions and filters.
        $this->init_hooks();
    }

    /**
     * Get instance of this class.
     *
     * @return object
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set up available demos.
     */
    protected function setup_demos() {
        $this->demos = array(
            'main' => array(
                'name'        => __( 'Main Demo', 'aqualuxe' ),
                'description' => __( 'The main demo with all features.', 'aqualuxe' ),
                'preview_url' => 'https://example.com/aqualuxe-main-demo/',
                'screenshot'  => $this->demo_data_url . 'main/screenshot.jpg',
                'options'     => array(
                    'content'    => true,
                    'widgets'    => true,
                    'customizer' => true,
                    'menus'      => true,
                ),
            ),
            'business' => array(
                'name'        => __( 'Business Demo', 'aqualuxe' ),
                'description' => __( 'A demo for business websites.', 'aqualuxe' ),
                'preview_url' => 'https://example.com/aqualuxe-business-demo/',
                'screenshot'  => $this->demo_data_url . 'business/screenshot.jpg',
                'options'     => array(
                    'content'    => true,
                    'widgets'    => true,
                    'customizer' => true,
                    'menus'      => true,
                ),
            ),
            'shop' => array(
                'name'        => __( 'Shop Demo', 'aqualuxe' ),
                'description' => __( 'A demo for e-commerce websites.', 'aqualuxe' ),
                'preview_url' => 'https://example.com/aqualuxe-shop-demo/',
                'screenshot'  => $this->demo_data_url . 'shop/screenshot.jpg',
                'options'     => array(
                    'content'    => true,
                    'widgets'    => true,
                    'customizer' => true,
                    'menus'      => true,
                    'woocommerce' => true,
                ),
                'requires'    => array(
                    'woocommerce' => __( 'WooCommerce', 'aqualuxe' ),
                ),
            ),
            'blog' => array(
                'name'        => __( 'Blog Demo', 'aqualuxe' ),
                'description' => __( 'A demo for blog websites.', 'aqualuxe' ),
                'preview_url' => 'https://example.com/aqualuxe-blog-demo/',
                'screenshot'  => $this->demo_data_url . 'blog/screenshot.jpg',
                'options'     => array(
                    'content'    => true,
                    'widgets'    => true,
                    'customizer' => true,
                    'menus'      => true,
                ),
            ),
        );
    }

    /**
     * Set up import steps.
     */
    protected function setup_import_steps() {
        $this->import_steps = array(
            'validate'    => array(
                'name'     => __( 'Validation', 'aqualuxe' ),
                'callback' => array( $this, 'validate_import' ),
            ),
            'backup'      => array(
                'name'     => __( 'Backup', 'aqualuxe' ),
                'callback' => array( $this, 'backup_current_data' ),
            ),
            'content'     => array(
                'name'     => __( 'Content', 'aqualuxe' ),
                'callback' => array( $this, 'import_content' ),
            ),
            'widgets'     => array(
                'name'     => __( 'Widgets', 'aqualuxe' ),
                'callback' => array( $this, 'import_widgets' ),
            ),
            'customizer'  => array(
                'name'     => __( 'Customizer', 'aqualuxe' ),
                'callback' => array( $this, 'import_customizer' ),
            ),
            'menus'       => array(
                'name'     => __( 'Menus', 'aqualuxe' ),
                'callback' => array( $this, 'import_menus' ),
            ),
            'woocommerce' => array(
                'name'     => __( 'WooCommerce', 'aqualuxe' ),
                'callback' => array( $this, 'import_woocommerce' ),
            ),
            'finalize'    => array(
                'name'     => __( 'Finalize', 'aqualuxe' ),
                'callback' => array( $this, 'finalize_import' ),
            ),
        );
    }

    /**
     * Initialize logger.
     */
    protected function init_logger() {
        // Create logger class if needed.
        $this->logger = new AquaLuxe_Demo_Importer_Logger();
    }

    /**
     * Initialize hooks.
     */
    protected function init_hooks() {
        // Admin menu.
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

        // Admin scripts and styles.
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // AJAX handlers.
        add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'ajax_import_demo' ) );
        add_action( 'wp_ajax_aqualuxe_reset_demo', array( $this, 'ajax_reset_demo' ) );
        add_action( 'wp_ajax_aqualuxe_import_step', array( $this, 'ajax_import_step' ) );
        add_action( 'wp_ajax_aqualuxe_get_import_status', array( $this, 'ajax_get_import_status' ) );
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_theme_page(
            __( 'Demo Importer', 'aqualuxe' ),
            __( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'render_admin_page' )
        );
    }

    /**
     * Enqueue scripts and styles.
     *
     * @param string $hook Hook suffix.
     */
    public function enqueue_scripts( $hook ) {
        if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
            return;
        }

        // Styles.
        wp_enqueue_style(
            'aqualuxe-demo-importer',
            AQUALUXE_THEME_URI . 'inc/demo-importer/assets/css/demo-importer.css',
            array(),
            AQUALUXE_VERSION
        );

        // Scripts.
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_THEME_URI . 'inc/demo-importer/assets/js/demo-importer.js',
            array( 'jquery', 'wp-util' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script.
        wp_localize_script(
            'aqualuxe-demo-importer',
            'aqualuxeDemoImporter',
            array(
                'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
                'nonce'        => wp_create_nonce( 'aqualuxe-demo-importer' ),
                'importNonce'  => wp_create_nonce( 'aqualuxe-import-demo' ),
                'resetNonce'   => wp_create_nonce( 'aqualuxe-reset-demo' ),
                'stepNonce'    => wp_create_nonce( 'aqualuxe-import-step' ),
                'statusNonce'  => wp_create_nonce( 'aqualuxe-import-status' ),
                'confirmImport' => __( 'Are you sure you want to import this demo? This will overwrite your current content.', 'aqualuxe' ),
                'confirmReset'  => __( 'Are you sure you want to reset your site? This will delete all your content.', 'aqualuxe' ),
                'importError'   => __( 'An error occurred during the import process. Please try again.', 'aqualuxe' ),
                'resetError'    => __( 'An error occurred during the reset process. Please try again.', 'aqualuxe' ),
                'importSuccess' => __( 'Demo imported successfully!', 'aqualuxe' ),
                'resetSuccess'  => __( 'Site reset successfully!', 'aqualuxe' ),
                'importing'     => __( 'Importing...', 'aqualuxe' ),
                'resetting'     => __( 'Resetting...', 'aqualuxe' ),
                'import'        => __( 'Import', 'aqualuxe' ),
                'reset'         => __( 'Reset', 'aqualuxe' ),
            )
        );
    }

    /**
     * Render admin page.
     */
    public function render_admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>

            <div class="aqualuxe-demo-importer-header">
                <p class="about-description">
                    <?php esc_html_e( 'Import demo content to get started with your website.', 'aqualuxe' ); ?>
                </p>

                <div class="aqualuxe-demo-importer-actions">
                    <button id="aqualuxe-reset-site" class="button button-secondary">
                        <?php esc_html_e( 'Reset Site', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>

            <div class="aqualuxe-demo-importer-content">
                <div class="aqualuxe-demo-importer-notice notice notice-info inline">
                    <p>
                        <?php esc_html_e( 'Importing a demo will overwrite your current theme options, widgets, and other content. It is recommended to import on a fresh WordPress installation.', 'aqualuxe' ); ?>
                    </p>
                </div>

                <div class="aqualuxe-demo-importer-demos">
                    <?php $this->render_demos(); ?>
                </div>
            </div>

            <div id="aqualuxe-demo-importer-modal" class="aqualuxe-demo-importer-modal">
                <div class="aqualuxe-demo-importer-modal-content">
                    <span class="aqualuxe-demo-importer-modal-close">&times;</span>
                    <h2 class="aqualuxe-demo-importer-modal-title"></h2>
                    <div class="aqualuxe-demo-importer-modal-body">
                        <div class="aqualuxe-demo-importer-options">
                            <h3><?php esc_html_e( 'Import Options', 'aqualuxe' ); ?></h3>
                            <div class="aqualuxe-demo-importer-options-list">
                                <label>
                                    <input type="checkbox" name="content" checked>
                                    <?php esc_html_e( 'Content (Pages, Posts, etc.)', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="widgets" checked>
                                    <?php esc_html_e( 'Widgets', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="customizer" checked>
                                    <?php esc_html_e( 'Customizer Settings', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="menus" checked>
                                    <?php esc_html_e( 'Menus', 'aqualuxe' ); ?>
                                </label>
                                <label class="woocommerce-option">
                                    <input type="checkbox" name="woocommerce" checked>
                                    <?php esc_html_e( 'WooCommerce Settings', 'aqualuxe' ); ?>
                                </label>
                            </div>
                        </div>

                        <div class="aqualuxe-demo-importer-progress">
                            <div class="aqualuxe-demo-importer-progress-bar">
                                <div class="aqualuxe-demo-importer-progress-bar-inner"></div>
                            </div>
                            <div class="aqualuxe-demo-importer-progress-status">
                                <span class="aqualuxe-demo-importer-progress-percentage">0%</span>
                                <span class="aqualuxe-demo-importer-progress-step"></span>
                            </div>
                        </div>

                        <div class="aqualuxe-demo-importer-modal-actions">
                            <button class="button button-primary aqualuxe-demo-importer-import">
                                <?php esc_html_e( 'Import', 'aqualuxe' ); ?>
                            </button>
                            <button class="button button-secondary aqualuxe-demo-importer-cancel">
                                <?php esc_html_e( 'Cancel', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="aqualuxe-reset-modal" class="aqualuxe-demo-importer-modal">
                <div class="aqualuxe-demo-importer-modal-content">
                    <span class="aqualuxe-demo-importer-modal-close">&times;</span>
                    <h2 class="aqualuxe-demo-importer-modal-title"><?php esc_html_e( 'Reset Site', 'aqualuxe' ); ?></h2>
                    <div class="aqualuxe-demo-importer-modal-body">
                        <div class="aqualuxe-demo-importer-notice notice notice-warning inline">
                            <p>
                                <?php esc_html_e( 'This will delete all your content and reset your site to a fresh WordPress installation. This action cannot be undone.', 'aqualuxe' ); ?>
                            </p>
                        </div>

                        <div class="aqualuxe-demo-importer-options">
                            <h3><?php esc_html_e( 'Reset Options', 'aqualuxe' ); ?></h3>
                            <div class="aqualuxe-demo-importer-options-list">
                                <label>
                                    <input type="checkbox" name="reset_content" checked>
                                    <?php esc_html_e( 'Content (Pages, Posts, etc.)', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="reset_widgets" checked>
                                    <?php esc_html_e( 'Widgets', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="reset_customizer" checked>
                                    <?php esc_html_e( 'Customizer Settings', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="reset_menus" checked>
                                    <?php esc_html_e( 'Menus', 'aqualuxe' ); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="reset_woocommerce" checked>
                                    <?php esc_html_e( 'WooCommerce Settings', 'aqualuxe' ); ?>
                                </label>
                            </div>
                        </div>

                        <div class="aqualuxe-demo-importer-reset-confirmation">
                            <label>
                                <input type="checkbox" name="reset_confirmation">
                                <?php esc_html_e( 'I understand that this will delete all my content and cannot be undone.', 'aqualuxe' ); ?>
                            </label>
                        </div>

                        <div class="aqualuxe-demo-importer-progress">
                            <div class="aqualuxe-demo-importer-progress-bar">
                                <div class="aqualuxe-demo-importer-progress-bar-inner"></div>
                            </div>
                            <div class="aqualuxe-demo-importer-progress-status">
                                <span class="aqualuxe-demo-importer-progress-percentage">0%</span>
                                <span class="aqualuxe-demo-importer-progress-step"></span>
                            </div>
                        </div>

                        <div class="aqualuxe-demo-importer-modal-actions">
                            <button class="button button-primary aqualuxe-demo-importer-reset" disabled>
                                <?php esc_html_e( 'Reset Site', 'aqualuxe' ); ?>
                            </button>
                            <button class="button button-secondary aqualuxe-demo-importer-cancel">
                                <?php esc_html_e( 'Cancel', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render demos.
     */
    protected function render_demos() {
        if ( empty( $this->demos ) ) {
            echo '<p>' . esc_html__( 'No demos found.', 'aqualuxe' ) . '</p>';
            return;
        }

        echo '<div class="aqualuxe-demo-importer-demos-list">';

        foreach ( $this->demos as $demo_id => $demo ) {
            $this->render_demo_item( $demo_id, $demo );
        }

        echo '</div>';
    }

    /**
     * Render demo item.
     *
     * @param string $demo_id Demo ID.
     * @param array  $demo    Demo data.
     */
    protected function render_demo_item( $demo_id, $demo ) {
        $disabled = false;
        $disabled_message = '';

        // Check if demo requires plugins.
        if ( ! empty( $demo['requires'] ) ) {
            foreach ( $demo['requires'] as $plugin => $plugin_name ) {
                if ( 'woocommerce' === $plugin && ! class_exists( 'WooCommerce' ) ) {
                    $disabled = true;
                    $disabled_message = sprintf(
                        /* translators: %s: Plugin name */
                        __( 'This demo requires %s to be installed and activated.', 'aqualuxe' ),
                        $plugin_name
                    );
                    break;
                }
            }
        }

        ?>
        <div class="aqualuxe-demo-importer-demo <?php echo $disabled ? 'disabled' : ''; ?>" data-demo-id="<?php echo esc_attr( $demo_id ); ?>">
            <div class="aqualuxe-demo-importer-demo-screenshot">
                <img src="<?php echo esc_url( $demo['screenshot'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>">
            </div>
            <div class="aqualuxe-demo-importer-demo-info">
                <h3 class="aqualuxe-demo-importer-demo-name"><?php echo esc_html( $demo['name'] ); ?></h3>
                <p class="aqualuxe-demo-importer-demo-description"><?php echo esc_html( $demo['description'] ); ?></p>
                <div class="aqualuxe-demo-importer-demo-actions">
                    <?php if ( $disabled ) : ?>
                        <div class="aqualuxe-demo-importer-demo-disabled">
                            <p><?php echo esc_html( $disabled_message ); ?></p>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" class="button button-secondary" target="_blank">
                            <?php esc_html_e( 'Preview', 'aqualuxe' ); ?>
                        </a>
                        <button class="button button-primary aqualuxe-demo-importer-demo-import" data-demo-id="<?php echo esc_attr( $demo_id ); ?>">
                            <?php esc_html_e( 'Import', 'aqualuxe' ); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX import demo.
     */
    public function ajax_import_demo() {
        // Check nonce.
        check_ajax_referer( 'aqualuxe-import-demo', 'nonce' );

        // Check permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to import demos.', 'aqualuxe' ) ) );
        }

        // Get demo ID.
        $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['demo_id'] ) ) : '';

        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Demo not found.', 'aqualuxe' ) ) );
        }

        // Get import options.
        $options = isset( $_POST['options'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['options'] ) ) : array();

        // Set current demo.
        $this->current_demo = $demo_id;

        // Start import.
        $result = $this->start_import( $demo_id, $options );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Import started.', 'aqualuxe' ),
            'demo_id' => $demo_id,
        ) );
    }

    /**
     * AJAX reset demo.
     */
    public function ajax_reset_demo() {
        // Check nonce.
        check_ajax_referer( 'aqualuxe-reset-demo', 'nonce' );

        // Check permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to reset the site.', 'aqualuxe' ) ) );
        }

        // Get reset options.
        $options = isset( $_POST['options'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['options'] ) ) : array();

        // Start reset.
        $result = $this->reset_site( $options );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Site reset successfully.', 'aqualuxe' ),
        ) );
    }

    /**
     * AJAX import step.
     */
    public function ajax_import_step() {
        // Check nonce.
        check_ajax_referer( 'aqualuxe-import-step', 'nonce' );

        // Check permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to import demos.', 'aqualuxe' ) ) );
        }

        // Get step.
        $step = isset( $_POST['step'] ) ? sanitize_text_field( wp_unslash( $_POST['step'] ) ) : '';

        // Get demo ID.
        $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['demo_id'] ) ) : '';

        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Demo not found.', 'aqualuxe' ) ) );
        }

        // Set current demo.
        $this->current_demo = $demo_id;

        // Get import options.
        $options = isset( $_POST['options'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['options'] ) ) : array();

        // Run step.
        $result = $this->run_import_step( $step, $demo_id, $options );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array( 'message' => $result->get_error_message() ) );
        }

        wp_send_json_success( array(
            'message' => sprintf(
                /* translators: %s: Step name */
                __( 'Step "%s" completed.', 'aqualuxe' ),
                $this->import_steps[ $step ]['name']
            ),
            'step'    => $step,
            'next'    => $result['next'],
            'demo_id' => $demo_id,
        ) );
    }

    /**
     * AJAX get import status.
     */
    public function ajax_get_import_status() {
        // Check nonce.
        check_ajax_referer( 'aqualuxe-import-status', 'nonce' );

        // Check permissions.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to import demos.', 'aqualuxe' ) ) );
        }

        // Get demo ID.
        $demo_id = isset( $_POST['demo_id'] ) ? sanitize_text_field( wp_unslash( $_POST['demo_id'] ) ) : '';

        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            wp_send_json_error( array( 'message' => __( 'Demo not found.', 'aqualuxe' ) ) );
        }

        // Get import status.
        $status = $this->get_import_status( $demo_id );

        wp_send_json_success( $status );
    }

    /**
     * Start import.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    protected function start_import( $demo_id, $options ) {
        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            return new WP_Error( 'demo_not_found', __( 'Demo not found.', 'aqualuxe' ) );
        }

        // Set import options.
        update_option( 'aqualuxe_demo_import_options', $options );

        // Set import status.
        $status = array(
            'demo_id'   => $demo_id,
            'step'      => 'validate',
            'progress'  => 0,
            'completed' => false,
            'error'     => false,
            'message'   => __( 'Import started.', 'aqualuxe' ),
        );

        update_option( 'aqualuxe_demo_import_status', $status );

        return array(
            'status' => $status,
        );
    }

    /**
     * Run import step.
     *
     * @param string $step    Step name.
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    protected function run_import_step( $step, $demo_id, $options ) {
        // Check if step exists.
        if ( ! isset( $this->import_steps[ $step ] ) ) {
            return new WP_Error( 'step_not_found', __( 'Import step not found.', 'aqualuxe' ) );
        }

        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            return new WP_Error( 'demo_not_found', __( 'Demo not found.', 'aqualuxe' ) );
        }

        // Get step callback.
        $callback = $this->import_steps[ $step ]['callback'];

        // Run step.
        $result = call_user_func( $callback, $demo_id, $options );

        if ( is_wp_error( $result ) ) {
            // Update import status.
            $status = $this->get_import_status( $demo_id );
            $status['error'] = true;
            $status['message'] = $result->get_error_message();
            update_option( 'aqualuxe_demo_import_status', $status );

            return $result;
        }

        // Get next step.
        $next_step = $this->get_next_step( $step, $options );

        // Update import status.
        $status = $this->get_import_status( $demo_id );
        $status['step'] = $next_step;
        $status['progress'] = $this->calculate_progress( $step, $next_step );
        $status['completed'] = empty( $next_step );
        update_option( 'aqualuxe_demo_import_status', $status );

        return array(
            'next' => $next_step,
        );
    }

    /**
     * Get next step.
     *
     * @param string $current_step Current step.
     * @param array  $options      Import options.
     * @return string
     */
    protected function get_next_step( $current_step, $options ) {
        $steps = array_keys( $this->import_steps );
        $current_index = array_search( $current_step, $steps, true );

        if ( false === $current_index ) {
            return '';
        }

        $next_index = $current_index + 1;

        // Skip steps based on options.
        while ( isset( $steps[ $next_index ] ) ) {
            $next_step = $steps[ $next_index ];

            // Skip WooCommerce step if not selected or WooCommerce is not active.
            if ( 'woocommerce' === $next_step && ( ! isset( $options['woocommerce'] ) || ! class_exists( 'WooCommerce' ) ) ) {
                $next_index++;
                continue;
            }

            // Skip content step if not selected.
            if ( 'content' === $next_step && ! isset( $options['content'] ) ) {
                $next_index++;
                continue;
            }

            // Skip widgets step if not selected.
            if ( 'widgets' === $next_step && ! isset( $options['widgets'] ) ) {
                $next_index++;
                continue;
            }

            // Skip customizer step if not selected.
            if ( 'customizer' === $next_step && ! isset( $options['customizer'] ) ) {
                $next_index++;
                continue;
            }

            // Skip menus step if not selected.
            if ( 'menus' === $next_step && ! isset( $options['menus'] ) ) {
                $next_index++;
                continue;
            }

            return $next_step;
        }

        return '';
    }

    /**
     * Calculate progress.
     *
     * @param string $current_step Current step.
     * @param string $next_step    Next step.
     * @return int
     */
    protected function calculate_progress( $current_step, $next_step ) {
        $steps = array_keys( $this->import_steps );
        $total_steps = count( $steps );
        $current_index = array_search( $current_step, $steps, true );

        if ( false === $current_index ) {
            return 0;
        }

        if ( empty( $next_step ) ) {
            return 100;
        }

        $next_index = array_search( $next_step, $steps, true );

        if ( false === $next_index ) {
            return 0;
        }

        return intval( ( $next_index / $total_steps ) * 100 );
    }

    /**
     * Get import status.
     *
     * @param string $demo_id Demo ID.
     * @return array
     */
    protected function get_import_status( $demo_id ) {
        $status = get_option( 'aqualuxe_demo_import_status', array() );

        if ( empty( $status ) || $status['demo_id'] !== $demo_id ) {
            $status = array(
                'demo_id'   => $demo_id,
                'step'      => '',
                'progress'  => 0,
                'completed' => false,
                'error'     => false,
                'message'   => '',
            );
        }

        return $status;
    }

    /**
     * Reset site.
     *
     * @param array $options Reset options.
     * @return array|WP_Error
     */
    protected function reset_site( $options ) {
        global $wpdb;

        // Check if reset confirmation is checked.
        if ( ! isset( $options['reset_confirmation'] ) ) {
            return new WP_Error( 'reset_confirmation', __( 'Please confirm that you want to reset your site.', 'aqualuxe' ) );
        }

        // Start transaction.
        $wpdb->query( 'START TRANSACTION' );

        try {
            // Reset content.
            if ( isset( $options['reset_content'] ) ) {
                $this->reset_content();
            }

            // Reset widgets.
            if ( isset( $options['reset_widgets'] ) ) {
                $this->reset_widgets();
            }

            // Reset customizer.
            if ( isset( $options['reset_customizer'] ) ) {
                $this->reset_customizer();
            }

            // Reset menus.
            if ( isset( $options['reset_menus'] ) ) {
                $this->reset_menus();
            }

            // Reset WooCommerce.
            if ( isset( $options['reset_woocommerce'] ) && class_exists( 'WooCommerce' ) ) {
                $this->reset_woocommerce();
            }

            // Commit transaction.
            $wpdb->query( 'COMMIT' );

            return array(
                'success' => true,
            );
        } catch ( Exception $e ) {
            // Rollback transaction.
            $wpdb->query( 'ROLLBACK' );

            return new WP_Error( 'reset_error', $e->getMessage() );
        }
    }

    /**
     * Reset content.
     */
    protected function reset_content() {
        global $wpdb;

        // Delete all posts.
        $wpdb->query( "DELETE FROM {$wpdb->posts}" );
        $wpdb->query( "DELETE FROM {$wpdb->postmeta}" );

        // Delete all terms.
        $wpdb->query( "DELETE FROM {$wpdb->terms}" );
        $wpdb->query( "DELETE FROM {$wpdb->term_taxonomy}" );
        $wpdb->query( "DELETE FROM {$wpdb->term_relationships}" );
        $wpdb->query( "DELETE FROM {$wpdb->termmeta}" );

        // Delete all comments.
        $wpdb->query( "DELETE FROM {$wpdb->comments}" );
        $wpdb->query( "DELETE FROM {$wpdb->commentmeta}" );

        // Reset auto-increment values.
        $wpdb->query( "ALTER TABLE {$wpdb->posts} AUTO_INCREMENT = 1" );
        $wpdb->query( "ALTER TABLE {$wpdb->terms} AUTO_INCREMENT = 1" );
        $wpdb->query( "ALTER TABLE {$wpdb->comments} AUTO_INCREMENT = 1" );

        // Create default content.
        $this->create_default_content();
    }

    /**
     * Create default content.
     */
    protected function create_default_content() {
        // Create default page.
        $page_id = wp_insert_post( array(
            'post_title'     => __( 'Home', 'aqualuxe' ),
            'post_content'   => __( 'Welcome to your new site!', 'aqualuxe' ),
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ) );

        // Set as front page.
        update_option( 'page_on_front', $page_id );
        update_option( 'show_on_front', 'page' );

        // Create default post.
        wp_insert_post( array(
            'post_title'     => __( 'Hello World', 'aqualuxe' ),
            'post_content'   => __( 'Welcome to your new site! This is your first post.', 'aqualuxe' ),
            'post_status'    => 'publish',
            'post_type'      => 'post',
            'comment_status' => 'open',
            'ping_status'    => 'open',
        ) );

        // Create default category.
        wp_insert_term( __( 'Uncategorized', 'aqualuxe' ), 'category', array(
            'slug' => 'uncategorized',
        ) );
    }

    /**
     * Reset widgets.
     */
    protected function reset_widgets() {
        // Delete all widgets.
        update_option( 'sidebars_widgets', array( 'wp_inactive_widgets' => array() ) );

        // Delete all widget options.
        global $wpdb;
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'widget_%'" );
    }

    /**
     * Reset customizer.
     */
    protected function reset_customizer() {
        // Delete theme mods.
        remove_theme_mods();
    }

    /**
     * Reset menus.
     */
    protected function reset_menus() {
        // Delete all menus.
        $menus = wp_get_nav_menus();

        foreach ( $menus as $menu ) {
            wp_delete_nav_menu( $menu->term_id );
        }

        // Reset menu locations.
        set_theme_mod( 'nav_menu_locations', array() );
    }

    /**
     * Reset WooCommerce.
     */
    protected function reset_woocommerce() {
        global $wpdb;

        // Delete WooCommerce options.
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'woocommerce_%'" );

        // Delete WooCommerce tables.
        $tables = array(
            'woocommerce_attribute_taxonomies',
            'woocommerce_downloadable_product_permissions',
            'woocommerce_order_items',
            'woocommerce_order_itemmeta',
            'woocommerce_tax_rates',
            'woocommerce_tax_rate_locations',
            'woocommerce_shipping_zones',
            'woocommerce_shipping_zone_locations',
            'woocommerce_shipping_zone_methods',
            'woocommerce_payment_tokens',
            'woocommerce_payment_tokenmeta',
            'woocommerce_log',
            'woocommerce_sessions',
            'woocommerce_api_keys',
            'woocommerce_webhooks',
        );

        foreach ( $tables as $table ) {
            $table_name = $wpdb->prefix . $table;
            $wpdb->query( "TRUNCATE TABLE {$table_name}" );
        }
    }

    /**
     * Validate import.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function validate_import( $demo_id, $options ) {
        // Check if demo exists.
        if ( ! isset( $this->demos[ $demo_id ] ) ) {
            return new WP_Error( 'demo_not_found', __( 'Demo not found.', 'aqualuxe' ) );
        }

        // Check if demo requires plugins.
        if ( ! empty( $this->demos[ $demo_id ]['requires'] ) ) {
            foreach ( $this->demos[ $demo_id ]['requires'] as $plugin => $plugin_name ) {
                if ( 'woocommerce' === $plugin && ! class_exists( 'WooCommerce' ) ) {
                    return new WP_Error(
                        'plugin_required',
                        sprintf(
                            /* translators: %s: Plugin name */
                            __( 'This demo requires %s to be installed and activated.', 'aqualuxe' ),
                            $plugin_name
                        )
                    );
                }
            }
        }

        // Check if demo files exist.
        $demo_dir = $this->demo_data_dir . $demo_id;

        if ( ! is_dir( $demo_dir ) ) {
            return new WP_Error( 'demo_files_not_found', __( 'Demo files not found.', 'aqualuxe' ) );
        }

        // Check if content file exists.
        if ( isset( $options['content'] ) && ! file_exists( $demo_dir . '/content.xml' ) ) {
            return new WP_Error( 'content_file_not_found', __( 'Content file not found.', 'aqualuxe' ) );
        }

        // Check if widgets file exists.
        if ( isset( $options['widgets'] ) && ! file_exists( $demo_dir . '/widgets.json' ) ) {
            return new WP_Error( 'widgets_file_not_found', __( 'Widgets file not found.', 'aqualuxe' ) );
        }

        // Check if customizer file exists.
        if ( isset( $options['customizer'] ) && ! file_exists( $demo_dir . '/customizer.json' ) ) {
            return new WP_Error( 'customizer_file_not_found', __( 'Customizer file not found.', 'aqualuxe' ) );
        }

        // Check if menus file exists.
        if ( isset( $options['menus'] ) && ! file_exists( $demo_dir . '/menus.json' ) ) {
            return new WP_Error( 'menus_file_not_found', __( 'Menus file not found.', 'aqualuxe' ) );
        }

        // Check if WooCommerce file exists.
        if ( isset( $options['woocommerce'] ) && ! file_exists( $demo_dir . '/woocommerce.json' ) ) {
            return new WP_Error( 'woocommerce_file_not_found', __( 'WooCommerce file not found.', 'aqualuxe' ) );
        }

        // Log validation success.
        $this->logger->log( 'Validation successful for demo: ' . $demo_id );

        return array(
            'success' => true,
        );
    }

    /**
     * Backup current data.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function backup_current_data( $demo_id, $options ) {
        $backup_dir = AQUALUXE_THEME_DIR . 'inc/demo-importer/backups/' . date( 'Y-m-d-H-i-s' );

        // Create backup directory.
        if ( ! is_dir( $backup_dir ) ) {
            wp_mkdir_p( $backup_dir );
        }

        // Backup content.
        if ( isset( $options['content'] ) ) {
            $this->backup_content( $backup_dir );
        }

        // Backup widgets.
        if ( isset( $options['widgets'] ) ) {
            $this->backup_widgets( $backup_dir );
        }

        // Backup customizer.
        if ( isset( $options['customizer'] ) ) {
            $this->backup_customizer( $backup_dir );
        }

        // Backup menus.
        if ( isset( $options['menus'] ) ) {
            $this->backup_menus( $backup_dir );
        }

        // Backup WooCommerce.
        if ( isset( $options['woocommerce'] ) && class_exists( 'WooCommerce' ) ) {
            $this->backup_woocommerce( $backup_dir );
        }

        // Log backup success.
        $this->logger->log( 'Backup successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'backup_dir' => $backup_dir,
        );
    }

    /**
     * Backup content.
     *
     * @param string $backup_dir Backup directory.
     */
    protected function backup_content( $backup_dir ) {
        // Export content using WordPress exporter.
        require_once ABSPATH . 'wp-admin/includes/export.php';

        $args = array(
            'content' => 'all',
            'author' => false,
            'category' => false,
            'start_date' => false,
            'end_date' => false,
            'status' => false,
        );

        $content = export_wp( $args );

        // Save content to file.
        file_put_contents( $backup_dir . '/content.xml', $content );
    }

    /**
     * Backup widgets.
     *
     * @param string $backup_dir Backup directory.
     */
    protected function backup_widgets( $backup_dir ) {
        // Get all widgets.
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        $widget_options = array();

        // Get all widget options.
        global $wpdb;
        $widget_options_raw = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'widget_%'" );

        foreach ( $widget_options_raw as $option ) {
            $widget_options[ $option->option_name ] = get_option( $option->option_name );
        }

        // Save widgets to file.
        $widgets = array(
            'sidebars_widgets' => $sidebars_widgets,
            'widget_options' => $widget_options,
        );

        file_put_contents( $backup_dir . '/widgets.json', wp_json_encode( $widgets ) );
    }

    /**
     * Backup customizer.
     *
     * @param string $backup_dir Backup directory.
     */
    protected function backup_customizer( $backup_dir ) {
        // Get theme mods.
        $theme_mods = get_theme_mods();

        // Save theme mods to file.
        file_put_contents( $backup_dir . '/customizer.json', wp_json_encode( $theme_mods ) );
    }

    /**
     * Backup menus.
     *
     * @param string $backup_dir Backup directory.
     */
    protected function backup_menus( $backup_dir ) {
        // Get all menus.
        $menus = wp_get_nav_menus();
        $menu_items = array();

        foreach ( $menus as $menu ) {
            $menu_items[ $menu->term_id ] = wp_get_nav_menu_items( $menu->term_id );
        }

        // Get menu locations.
        $menu_locations = get_theme_mod( 'nav_menu_locations' );

        // Save menus to file.
        $menu_data = array(
            'menus' => $menus,
            'menu_items' => $menu_items,
            'menu_locations' => $menu_locations,
        );

        file_put_contents( $backup_dir . '/menus.json', wp_json_encode( $menu_data ) );
    }

    /**
     * Backup WooCommerce.
     *
     * @param string $backup_dir Backup directory.
     */
    protected function backup_woocommerce( $backup_dir ) {
        // Get WooCommerce options.
        global $wpdb;
        $woocommerce_options = array();

        $woocommerce_options_raw = $wpdb->get_results( "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'woocommerce_%'" );

        foreach ( $woocommerce_options_raw as $option ) {
            $woocommerce_options[ $option->option_name ] = get_option( $option->option_name );
        }

        // Save WooCommerce options to file.
        file_put_contents( $backup_dir . '/woocommerce.json', wp_json_encode( $woocommerce_options ) );
    }

    /**
     * Import content.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function import_content( $demo_id, $options ) {
        // Check if content import is enabled.
        if ( ! isset( $options['content'] ) ) {
            return array(
                'success' => true,
                'message' => __( 'Content import skipped.', 'aqualuxe' ),
            );
        }

        // Get content file.
        $content_file = $this->demo_data_dir . $demo_id . '/content.xml';

        if ( ! file_exists( $content_file ) ) {
            return new WP_Error( 'content_file_not_found', __( 'Content file not found.', 'aqualuxe' ) );
        }

        // Import content using WordPress importer.
        if ( ! class_exists( 'WP_Import' ) ) {
            require_once AQUALUXE_THEME_DIR . 'inc/demo-importer/includes/wordpress-importer/wordpress-importer.php';
        }

        $importer = new WP_Import();
        $importer->fetch_attachments = true;

        // Run the importer.
        ob_start();
        $importer->import( $content_file );
        ob_end_clean();

        // Log content import success.
        $this->logger->log( 'Content import successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'Content imported successfully.', 'aqualuxe' ),
        );
    }

    /**
     * Import widgets.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function import_widgets( $demo_id, $options ) {
        // Check if widgets import is enabled.
        if ( ! isset( $options['widgets'] ) ) {
            return array(
                'success' => true,
                'message' => __( 'Widgets import skipped.', 'aqualuxe' ),
            );
        }

        // Get widgets file.
        $widgets_file = $this->demo_data_dir . $demo_id . '/widgets.json';

        if ( ! file_exists( $widgets_file ) ) {
            return new WP_Error( 'widgets_file_not_found', __( 'Widgets file not found.', 'aqualuxe' ) );
        }

        // Get widgets data.
        $widgets_data = json_decode( file_get_contents( $widgets_file ), true );

        if ( ! $widgets_data || ! isset( $widgets_data['sidebars_widgets'] ) || ! isset( $widgets_data['widget_options'] ) ) {
            return new WP_Error( 'invalid_widgets_data', __( 'Invalid widgets data.', 'aqualuxe' ) );
        }

        // Import sidebars widgets.
        update_option( 'sidebars_widgets', $widgets_data['sidebars_widgets'] );

        // Import widget options.
        foreach ( $widgets_data['widget_options'] as $option_name => $option_value ) {
            update_option( $option_name, $option_value );
        }

        // Log widgets import success.
        $this->logger->log( 'Widgets import successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'Widgets imported successfully.', 'aqualuxe' ),
        );
    }

    /**
     * Import customizer.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function import_customizer( $demo_id, $options ) {
        // Check if customizer import is enabled.
        if ( ! isset( $options['customizer'] ) ) {
            return array(
                'success' => true,
                'message' => __( 'Customizer import skipped.', 'aqualuxe' ),
            );
        }

        // Get customizer file.
        $customizer_file = $this->demo_data_dir . $demo_id . '/customizer.json';

        if ( ! file_exists( $customizer_file ) ) {
            return new WP_Error( 'customizer_file_not_found', __( 'Customizer file not found.', 'aqualuxe' ) );
        }

        // Get customizer data.
        $customizer_data = json_decode( file_get_contents( $customizer_file ), true );

        if ( ! $customizer_data ) {
            return new WP_Error( 'invalid_customizer_data', __( 'Invalid customizer data.', 'aqualuxe' ) );
        }

        // Import customizer settings.
        foreach ( $customizer_data as $key => $value ) {
            set_theme_mod( $key, $value );
        }

        // Log customizer import success.
        $this->logger->log( 'Customizer import successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'Customizer settings imported successfully.', 'aqualuxe' ),
        );
    }

    /**
     * Import menus.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function import_menus( $demo_id, $options ) {
        // Check if menus import is enabled.
        if ( ! isset( $options['menus'] ) ) {
            return array(
                'success' => true,
                'message' => __( 'Menus import skipped.', 'aqualuxe' ),
            );
        }

        // Get menus file.
        $menus_file = $this->demo_data_dir . $demo_id . '/menus.json';

        if ( ! file_exists( $menus_file ) ) {
            return new WP_Error( 'menus_file_not_found', __( 'Menus file not found.', 'aqualuxe' ) );
        }

        // Get menus data.
        $menus_data = json_decode( file_get_contents( $menus_file ), true );

        if ( ! $menus_data || ! isset( $menus_data['menus'] ) || ! isset( $menus_data['menu_items'] ) || ! isset( $menus_data['menu_locations'] ) ) {
            return new WP_Error( 'invalid_menus_data', __( 'Invalid menus data.', 'aqualuxe' ) );
        }

        // Import menus.
        $menu_ids = array();

        foreach ( $menus_data['menus'] as $menu ) {
            $menu_exists = wp_get_nav_menu_object( $menu['slug'] );

            if ( $menu_exists ) {
                $menu_ids[ $menu['term_id'] ] = $menu_exists->term_id;
                continue;
            }

            $new_menu_id = wp_create_nav_menu( $menu['name'] );

            if ( is_wp_error( $new_menu_id ) ) {
                continue;
            }

            $menu_ids[ $menu['term_id'] ] = $new_menu_id;
        }

        // Import menu items.
        foreach ( $menus_data['menu_items'] as $menu_id => $menu_items ) {
            if ( ! isset( $menu_ids[ $menu_id ] ) ) {
                continue;
            }

            $new_menu_id = $menu_ids[ $menu_id ];

            foreach ( $menu_items as $menu_item ) {
                $menu_item_data = array(
                    'menu-item-title'     => $menu_item['title'],
                    'menu-item-url'       => $menu_item['url'],
                    'menu-item-status'    => 'publish',
                    'menu-item-type'      => $menu_item['type'],
                    'menu-item-position'  => $menu_item['menu_order'],
                    'menu-item-attr-title' => $menu_item['attr_title'],
                    'menu-item-target'    => $menu_item['target'],
                    'menu-item-classes'   => implode( ' ', $menu_item['classes'] ),
                    'menu-item-xfn'       => $menu_item['xfn'],
                    'menu-item-description' => $menu_item['description'],
                );

                if ( 'taxonomy' === $menu_item['type'] ) {
                    $menu_item_data['menu-item-object'] = $menu_item['object'];
                    $menu_item_data['menu-item-object-id'] = $menu_item['object_id'];
                } elseif ( 'post_type' === $menu_item['type'] ) {
                    $menu_item_data['menu-item-object'] = $menu_item['object'];
                    $menu_item_data['menu-item-object-id'] = $menu_item['object_id'];
                }

                wp_update_nav_menu_item( $new_menu_id, 0, $menu_item_data );
            }
        }

        // Import menu locations.
        $menu_locations = array();

        foreach ( $menus_data['menu_locations'] as $location => $menu_id ) {
            if ( isset( $menu_ids[ $menu_id ] ) ) {
                $menu_locations[ $location ] = $menu_ids[ $menu_id ];
            }
        }

        set_theme_mod( 'nav_menu_locations', $menu_locations );

        // Log menus import success.
        $this->logger->log( 'Menus import successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'Menus imported successfully.', 'aqualuxe' ),
        );
    }

    /**
     * Import WooCommerce.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function import_woocommerce( $demo_id, $options ) {
        // Check if WooCommerce import is enabled.
        if ( ! isset( $options['woocommerce'] ) ) {
            return array(
                'success' => true,
                'message' => __( 'WooCommerce import skipped.', 'aqualuxe' ),
            );
        }

        // Check if WooCommerce is active.
        if ( ! class_exists( 'WooCommerce' ) ) {
            return new WP_Error( 'woocommerce_not_active', __( 'WooCommerce is not active.', 'aqualuxe' ) );
        }

        // Get WooCommerce file.
        $woocommerce_file = $this->demo_data_dir . $demo_id . '/woocommerce.json';

        if ( ! file_exists( $woocommerce_file ) ) {
            return new WP_Error( 'woocommerce_file_not_found', __( 'WooCommerce file not found.', 'aqualuxe' ) );
        }

        // Get WooCommerce data.
        $woocommerce_data = json_decode( file_get_contents( $woocommerce_file ), true );

        if ( ! $woocommerce_data ) {
            return new WP_Error( 'invalid_woocommerce_data', __( 'Invalid WooCommerce data.', 'aqualuxe' ) );
        }

        // Import WooCommerce settings.
        foreach ( $woocommerce_data as $option_name => $option_value ) {
            update_option( $option_name, $option_value );
        }

        // Log WooCommerce import success.
        $this->logger->log( 'WooCommerce import successful for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'WooCommerce settings imported successfully.', 'aqualuxe' ),
        );
    }

    /**
     * Finalize import.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return array|WP_Error
     */
    public function finalize_import( $demo_id, $options ) {
        // Flush rewrite rules.
        flush_rewrite_rules();

        // Update import status.
        $status = $this->get_import_status( $demo_id );
        $status['completed'] = true;
        $status['progress'] = 100;
        $status['message'] = __( 'Import completed successfully.', 'aqualuxe' );
        update_option( 'aqualuxe_demo_import_status', $status );

        // Log import completion.
        $this->logger->log( 'Import completed successfully for demo: ' . $demo_id );

        return array(
            'success' => true,
            'message' => __( 'Import completed successfully.', 'aqualuxe' ),
        );
    }
}

/**
 * Demo Importer Logger Class
 */
class AquaLuxe_Demo_Importer_Logger {
    /**
     * Log file path.
     *
     * @var string
     */
    protected $log_file;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->log_file = AQUALUXE_THEME_DIR . 'inc/demo-importer/logs/import-' . date( 'Y-m-d' ) . '.log';

        // Create log directory if it doesn't exist.
        $log_dir = dirname( $this->log_file );
        if ( ! is_dir( $log_dir ) ) {
            wp_mkdir_p( $log_dir );
        }
    }

    /**
     * Log message.
     *
     * @param string $message Message to log.
     */
    public function log( $message ) {
        $timestamp = date( 'Y-m-d H:i:s' );
        $log_message = "[{$timestamp}] {$message}" . PHP_EOL;

        file_put_contents( $this->log_file, $log_message, FILE_APPEND );
    }

    /**
     * Get log file content.
     *
     * @return string
     */
    public function get_log() {
        if ( ! file_exists( $this->log_file ) ) {
            return '';
        }

        return file_get_contents( $this->log_file );
    }

    /**
     * Clear log file.
     */
    public function clear_log() {
        if ( file_exists( $this->log_file ) ) {
            unlink( $this->log_file );
        }
    }
}

// Initialize Demo Importer.
AquaLuxe_Demo_Importer::get_instance();