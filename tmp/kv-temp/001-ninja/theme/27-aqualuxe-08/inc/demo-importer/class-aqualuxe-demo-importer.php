<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * A safe and secure demo content importer for the AquaLuxe theme.
 *
 * @package AquaLuxe
 * @subpackage Demo_Importer
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Demo content directory.
     *
     * @var string
     */
    protected $demo_content_dir;

    /**
     * Demo content URL.
     *
     * @var string
     */
    protected $demo_content_url;

    /**
     * Log of import operations.
     *
     * @var array
     */
    protected $import_log = array();

    /**
     * Error log of import operations.
     *
     * @var array
     */
    protected $error_log = array();

    /**
     * Import status.
     *
     * @var string
     */
    protected $import_status = 'idle';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->demo_content_dir = AQUALUXE_DIR . 'inc/demo-importer/demo-content/';
        $this->demo_content_url = AQUALUXE_URI . 'inc/demo-importer/demo-content/';

        // Create demo content directory if it doesn't exist
        if ( ! file_exists( $this->demo_content_dir ) ) {
            wp_mkdir_p( $this->demo_content_dir );
        }

        // Add actions
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'wp_ajax_aqualuxe_import_demo_content', array( $this, 'ajax_import_demo_content' ) );
    }

    /**
     * Get instance of this class.
     *
     * @return AquaLuxe_Demo_Importer
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add admin menu item.
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __( 'AquaLuxe Demo Importer', 'aqualuxe' ),
            __( 'Import Demo Content', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'render_admin_page' )
        );
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'aqualuxe-demo-importer-style',
            AQUALUXE_URI . 'inc/demo-importer/css/demo-importer.css',
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-demo-importer-script',
            AQUALUXE_URI . 'inc/demo-importer/js/demo-importer.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script(
            'aqualuxe-demo-importer-script',
            'aqualuxeDemoImporter',
            array(
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'nonce'     => wp_create_nonce( 'aqualuxe_demo_import_nonce' ),
                'importing' => __( 'Importing...', 'aqualuxe' ),
                'complete'  => __( 'Import Complete!', 'aqualuxe' ),
                'error'     => __( 'Import Error!', 'aqualuxe' ),
            )
        );
    }

    /**
     * Render admin page.
     */
    public function render_admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>

            <div class="aqualuxe-demo-importer-intro">
                <p><?php esc_html_e( 'This tool allows you to import demo content for the AquaLuxe theme. The demo content includes sample posts, pages, products, and other content types to help you get started with the theme.', 'aqualuxe' ); ?></p>

                <div class="aqualuxe-demo-importer-warning">
                    <h3><?php esc_html_e( 'Important Notes:', 'aqualuxe' ); ?></h3>
                    <ul>
                        <li><?php esc_html_e( 'It is recommended to run this importer on a fresh WordPress installation.', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'The importer will not delete any existing content, but it may modify some settings.', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'The import process may take several minutes to complete.', 'aqualuxe' ); ?></li>
                        <li><?php esc_html_e( 'All demo images are licensed for demo use only and should be replaced with your own images before publishing your site.', 'aqualuxe' ); ?></li>
                    </ul>
                </div>
            </div>

            <div class="aqualuxe-demo-content-options">
                <h2><?php esc_html_e( 'Select Content to Import', 'aqualuxe' ); ?></h2>

                <form id="aqualuxe-demo-import-form" method="post">
                    <?php
                    $content_options = array(
                        'posts'       => 'Blog Posts',
                        'pages'       => 'Pages',
                        'services'    => 'Services',
                        'team'        => 'Team Members',
                        'testimonials'=> 'Testimonials',
                        'projects'    => 'Projects',
                        'faqs'        => 'FAQs',
                        'care_guides' => 'Care Guides',
                        'auctions'    => 'Auctions',
                        'products'    => 'WooCommerce Products',
                        'settings'    => 'Theme Settings',
                        'widgets'     => 'Widgets',
                        'menus'       => 'Menus'
                    );

                    foreach ( $content_options as $value => $label ) {
                        ?>
                        <div class="aqualuxe-demo-content-option">
                            <input type="checkbox" id="import_<?php echo $value; ?>" name="import_content[]" value="<?php echo $value; ?>" checked>
                            <label for="import_<?php echo $value; ?>"><?php esc_html_e( $label, 'aqualuxe' ); ?></label>
                            <span class="description"><?php esc_html_e( 'Sample ' . strtolower( $label ) . ' content', 'aqualuxe' ); ?></span>
                        </div>
                        <?php
                    }
                    ?>

                    <?php wp_nonce_field( 'aqualuxe_demo_import_nonce', 'aqualuxe_demo_import_nonce' ); ?>

                    <div class="aqualuxe-demo-import-actions">
                        <button type="button" id="aqualuxe-demo-import-button" class="button button-primary">
                            <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                        </button>
                        <span class="spinner"></span>
                    </div>
                </form>
            </div>

            <div id="aqualuxe-demo-import-progress" class="aqualuxe-demo-import-progress" style="display: none;">
                <h3><?php esc_html_e( 'Import Progress', 'aqualuxe' ); ?></h3>
                <div class="aqualuxe-progress-bar">
                    <div class="aqualuxe-progress-bar-inner"></div>
                </div>
                <div class="aqualuxe-progress-status">
                    <span class="aqualuxe-progress-percentage">0%</span>
                    <span class="aqualuxe-progress-step"></span>
                </div>
            </div>

            <div id="aqualuxe-demo-import-log" class="aqualuxe-demo-import-log" style="display: none;">
                <h3><?php esc_html_e( 'Import Log', 'aqualuxe' ); ?></h3>
                <div class="aqualuxe-log-entries"></div>
            </div>
        </div>
        <?php
    }
}
