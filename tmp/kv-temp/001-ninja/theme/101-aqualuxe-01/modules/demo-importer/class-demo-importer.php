<?php
/**
 * Demo Content Importer Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Demo Content Importer functionality
 */
class AquaLuxe_Demo_Importer {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'ajax_import_demo' ) );
        add_action( 'wp_ajax_aqualuxe_reset_content', array( $this, 'ajax_reset_content' ) );
        add_action( 'wp_ajax_aqualuxe_import_progress', array( $this, 'ajax_import_progress' ) );
        add_action( 'wp_ajax_aqualuxe_export_content', array( $this, 'ajax_export_content' ) );
        add_action( 'wp_ajax_aqualuxe_validate_import', array( $this, 'ajax_validate_import' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __( 'Demo Importer', 'aqualuxe' ),
            __( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'admin_page' )
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( 'appearance_page_aqualuxe-demo-importer' !== $hook ) {
            return;
        }

        // Load from compiled assets with version from manifest
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        $script_url = AQUALUXE_THEME_URI . '/assets/dist/js/demo-importer.js';
        $version = AQUALUXE_VERSION;

        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );
            if ( isset( $manifest['/js/demo-importer.js'] ) ) {
                $script_url = AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/demo-importer.js'];
                $version = null; // Use file timestamp when manifest versioning is used
            }
        }

        wp_enqueue_script( 'aqualuxe-demo-importer', $script_url, array( 'jquery' ), $version, true );
        wp_localize_script( 'aqualuxe-demo-importer', 'aqualuxeImporter', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'aqualuxe_demo_importer' ),
            'strings' => array(
                'importing'      => __( 'Importing...', 'aqualuxe' ),
                'success'        => __( 'Import completed successfully!', 'aqualuxe' ),
                'error'          => __( 'Import failed. Please try again.', 'aqualuxe' ),
                'resetting'      => __( 'Resetting content...', 'aqualuxe' ),
                'reset_success'  => __( 'Content reset successfully!', 'aqualuxe' ),
                'reset_error'    => __( 'Reset failed. Please try again.', 'aqualuxe' ),
                'starting'       => __( 'Starting import...', 'aqualuxe' ),
                'confirmImport'  => __( 'Are you sure you want to import demo content? This may take a few minutes and will add new content to your site.', 'aqualuxe' ),
                'confirmExport'  => __( 'Export current content for backup?', 'aqualuxe' ),
                'exporting'      => __( 'Exporting content...', 'aqualuxe' ),
                'validating'     => __( 'Validating import data...', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Admin page HTML
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Demo Importer', 'aqualuxe' ); ?></h1>
            
            <div class="demo-importer-container">
                <div class="card">
                    <h2><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'This will import demo content including pages, posts, products, and media files. This process may take a few minutes.', 'aqualuxe' ); ?></p>
                    
                    <div class="demo-options">
                        <label>
                            <input type="checkbox" id="import-content" checked>
                            <?php esc_html_e( 'Import Content (Pages, Posts, Products)', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="import-media" checked>
                            <?php esc_html_e( 'Import Media Files', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="import-customizer">
                            <?php esc_html_e( 'Import Customizer Settings', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="import-widgets">
                            <?php esc_html_e( 'Import Widgets', 'aqualuxe' ); ?>
                        </label>
                    </div>
                    
                    <div class="import-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%;"></div>
                        </div>
                        <div class="progress-text">0%</div>
                        <div class="progress-status"></div>
                    </div>
                    
                    <p class="submit">
                        <button type="button" id="import-demo" class="button button-primary button-large">
                            <?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
                        </button>
                    </p>
                </div>

                <div class="card">
                    <h2><?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?></h2>
                    <p><strong><?php esc_html_e( 'Warning:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'This will permanently delete all content and reset your site to its initial state. This action cannot be undone.', 'aqualuxe' ); ?></p>
                    
                    <div class="reset-options">
                        <label>
                            <input type="checkbox" id="reset-posts">
                            <?php esc_html_e( 'Delete all posts and pages', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="reset-media">
                            <?php esc_html_e( 'Delete all media files', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="reset-products">
                            <?php esc_html_e( 'Delete all products', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="reset-customizer">
                            <?php esc_html_e( 'Reset customizer settings', 'aqualuxe' ); ?>
                        </label>
                    </div>
                    
                    <p class="submit">
                        <button type="button" id="reset-content" class="button button-secondary" disabled>
                            <?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?>
                        </button>
                    </p>
                    
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const resetButton = document.getElementById('reset-content');
                        const resetCheckboxes = document.querySelectorAll('.reset-options input[type="checkbox"]');
                        
                        function updateResetButton() {
                            const anyChecked = Array.from(resetCheckboxes).some(cb => cb.checked);
                            resetButton.disabled = !anyChecked;
                        }
                        
                        resetCheckboxes.forEach(cb => {
                            cb.addEventListener('change', updateResetButton);
                        });
                    });
                    </script>
                </div>

                <div class="card">
                    <h2><?php esc_html_e( 'Export & Backup', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'Create backups of your current content before importing demo data or for safekeeping.', 'aqualuxe' ); ?></p>
                    
                    <div class="export-options">
                        <label>
                            <input type="checkbox" id="export-posts" checked>
                            <?php esc_html_e( 'Export Posts and Pages', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="export-products" checked>
                            <?php esc_html_e( 'Export Products', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="export-media">
                            <?php esc_html_e( 'Export Media Library', 'aqualuxe' ); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" id="export-customizer">
                            <?php esc_html_e( 'Export Customizer Settings', 'aqualuxe' ); ?>
                        </label>
                    </div>
                    
                    <p class="submit">
                        <button type="button" id="export-content" class="button button-secondary">
                            <?php esc_html_e( 'Export Content', 'aqualuxe' ); ?>
                        </button>
                    </p>
                </div>

                <div class="card">
                    <h2><?php esc_html_e( 'Import Validation', 'aqualuxe' ); ?></h2>
                    <p><?php esc_html_e( 'Validate your system before importing to ensure compatibility and identify potential issues.', 'aqualuxe' ); ?></p>
                    
                    <div class="validation-results" style="display: none;">
                        <h3><?php esc_html_e( 'System Check Results', 'aqualuxe' ); ?></h3>
                        <div id="validation-output"></div>
                    </div>
                    
                    <p class="submit">
                        <button type="button" id="validate-system" class="button">
                            <?php esc_html_e( 'Run System Check', 'aqualuxe' ); ?>
                        </button>
                    </p>
                </div>
                    <table class="widefat">
                        <tbody>
                            <tr>
                                <td><?php esc_html_e( 'Posts', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts()->publish; ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e( 'Pages', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts('page')->publish; ?></td>
                            </tr>
                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                            <tr>
                                <td><?php esc_html_e( 'Products', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts('product')->publish; ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <td><?php esc_html_e( 'Media Files', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts('attachment')->inherit; ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e( 'Services', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts('service')->publish; ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e( 'Events', 'aqualuxe' ); ?></td>
                                <td><?php echo wp_count_posts('event')->publish; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <style>
            .demo-importer-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-top: 20px;
            }
            
            .demo-importer-container .card {
                padding: 20px;
            }
            
            .demo-importer-container .card:nth-child(3),
            .demo-importer-container .card:nth-child(4),
            .demo-importer-container .card:nth-child(5) {
                grid-column: 1 / -1;
            }
            
            .demo-options, .reset-options {
                margin: 15px 0;
            }
            
            .demo-options label, .reset-options label {
                display: block;
                margin-bottom: 10px;
                font-weight: 500;
            }
            
            .progress-bar {
                width: 100%;
                height: 20px;
                background: #f0f0f0;
                border-radius: 10px;
                overflow: hidden;
                margin: 10px 0;
            }
            
            .progress-fill {
                height: 100%;
                background: linear-gradient(90deg, #0ea5e9, #06b6d4);
                transition: width 0.3s ease;
            }
            
            .progress-text {
                text-align: center;
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .progress-status {
                text-align: center;
                color: #666;
                font-style: italic;
            }
            
            @media (max-width: 768px) {
                .demo-importer-container {
                    grid-template-columns: 1fr;
                }
            }
            </style>
        </div>
        <?php
    }

    /**
     * AJAX import demo content
     */
    public function ajax_import_demo() {
        check_ajax_referer( 'aqualuxe_demo_importer', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Permission denied', 'aqualuxe' ) );
        }

        $import_content = isset( $_POST['import_content'] ) ? (bool) $_POST['import_content'] : false;
        $import_media = isset( $_POST['import_media'] ) ? (bool) $_POST['import_media'] : false;
        $import_customizer = isset( $_POST['import_customizer'] ) ? (bool) $_POST['import_customizer'] : false;
        $import_widgets = isset( $_POST['import_widgets'] ) ? (bool) $_POST['import_widgets'] : false;

        $progress = array(
            'step'    => 0,
            'total'   => 0,
            'message' => __( 'Starting import...', 'aqualuxe' ),
        );

        // Calculate total steps
        if ( $import_content ) $progress['total'] += 4; // Pages, Posts, Products, Custom Post Types
        if ( $import_media ) $progress['total'] += 1;
        if ( $import_customizer ) $progress['total'] += 1;
        if ( $import_widgets ) $progress['total'] += 1;

        set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );

        try {
            if ( $import_content ) {
                $this->import_pages();
                $progress['step']++;
                $progress['message'] = __( 'Importing pages...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );

                $this->import_posts();
                $progress['step']++;
                $progress['message'] = __( 'Importing posts...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );

                if ( class_exists( 'WooCommerce' ) ) {
                    $this->import_products();
                }
                $progress['step']++;
                $progress['message'] = __( 'Importing products...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );

                $this->import_custom_post_types();
                $progress['step']++;
                $progress['message'] = __( 'Importing custom content...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );
            }

            if ( $import_media ) {
                $this->import_media_files();
                $progress['step']++;
                $progress['message'] = __( 'Importing media files...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );
            }

            if ( $import_customizer ) {
                $this->import_customizer_settings();
                $progress['step']++;
                $progress['message'] = __( 'Importing customizer settings...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );
            }

            if ( $import_widgets ) {
                $this->import_widgets();
                $progress['step']++;
                $progress['message'] = __( 'Importing widgets...', 'aqualuxe' );
                set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );
            }

            // Set up menus
            $this->setup_menus();

            // Set homepage
            $this->setup_homepage();

            $progress['step'] = $progress['total'];
            $progress['message'] = __( 'Import completed successfully!', 'aqualuxe' );
            set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );

            wp_send_json_success( array( 'message' => __( 'Demo content imported successfully!', 'aqualuxe' ) ) );

        } catch ( Exception $e ) {
            wp_send_json_error( sprintf( __( 'Import failed: %s', 'aqualuxe' ), $e->getMessage() ) );
        }
    }

    /**
     * AJAX reset content
     */
    public function ajax_reset_content() {
        check_ajax_referer( 'aqualuxe_demo_importer', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Permission denied', 'aqualuxe' ) );
        }

        $reset_posts = isset( $_POST['reset_posts'] ) ? (bool) $_POST['reset_posts'] : false;
        $reset_media = isset( $_POST['reset_media'] ) ? (bool) $_POST['reset_media'] : false;
        $reset_products = isset( $_POST['reset_products'] ) ? (bool) $_POST['reset_products'] : false;
        $reset_customizer = isset( $_POST['reset_customizer'] ) ? (bool) $_POST['reset_customizer'] : false;

        try {
            if ( $reset_posts ) {
                $this->reset_posts_and_pages();
            }

            if ( $reset_media ) {
                $this->reset_media_files();
            }

            if ( $reset_products && class_exists( 'WooCommerce' ) ) {
                $this->reset_products();
            }

            if ( $reset_customizer ) {
                $this->reset_customizer_settings();
            }

            wp_send_json_success( array( 'message' => __( 'Content reset successfully!', 'aqualuxe' ) ) );

        } catch ( Exception $e ) {
            wp_send_json_error( sprintf( __( 'Reset failed: %s', 'aqualuxe' ), $e->getMessage() ) );
        }
    }

    /**
     * AJAX get import progress
     */
    public function ajax_import_progress() {
        check_ajax_referer( 'aqualuxe_demo_importer', 'nonce' );

        $progress = get_transient( 'aqualuxe_import_progress' );
        if ( ! $progress ) {
            $progress = array(
                'step'    => 0,
                'total'   => 1,
                'message' => __( 'Ready to import', 'aqualuxe' ),
            );
        }

        $percentage = $progress['total'] > 0 ? round( ( $progress['step'] / $progress['total'] ) * 100 ) : 0;

        wp_send_json_success( array(
            'step'       => $progress['step'],
            'total'      => $progress['total'],
            'percentage' => $percentage,
            'message'    => $progress['message'],
        ) );
    }

    /**
     * AJAX export content
     */
    public function ajax_export_content() {
        check_ajax_referer( 'aqualuxe_demo_importer', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Permission denied', 'aqualuxe' ) );
        }

        $export_posts = isset( $_POST['export_posts'] ) ? (bool) $_POST['export_posts'] : false;
        $export_products = isset( $_POST['export_products'] ) ? (bool) $_POST['export_products'] : false;
        $export_media = isset( $_POST['export_media'] ) ? (bool) $_POST['export_media'] : false;
        $export_customizer = isset( $_POST['export_customizer'] ) ? (bool) $_POST['export_customizer'] : false;

        try {
            $export_data = array();
            $export_file = $this->create_export_file( array(
                'posts'      => $export_posts,
                'products'   => $export_products,
                'media'      => $export_media,
                'customizer' => $export_customizer,
            ) );

            if ( $export_file ) {
                wp_send_json_success( array( 
                    'message' => __( 'Export completed successfully!', 'aqualuxe' ),
                    'file_url' => $export_file,
                ) );
            } else {
                wp_send_json_error( __( 'Export failed. Please try again.', 'aqualuxe' ) );
            }

        } catch ( Exception $e ) {
            wp_send_json_error( sprintf( __( 'Export failed: %s', 'aqualuxe' ), $e->getMessage() ) );
        }
    }

    /**
     * AJAX validate import system
     */
    public function ajax_validate_import() {
        check_ajax_referer( 'aqualuxe_demo_importer', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( __( 'Permission denied', 'aqualuxe' ) );
        }

        try {
            $validation_results = $this->validate_system();
            wp_send_json_success( array( 'results' => $validation_results ) );

        } catch ( Exception $e ) {
            wp_send_json_error( sprintf( __( 'Validation failed: %s', 'aqualuxe' ), $e->getMessage() ) );
        }
    }

    /**
     * Import pages
     */
    private function import_pages() {
        $pages = array(
            array(
                'post_title'   => 'Home',
                'post_content' => $this->get_homepage_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 1,
                'meta_input'   => array(
                    '_wp_page_template' => 'templates/page-homepage.php',
                    '_aqualuxe_hero_enabled' => 'yes',
                    '_aqualuxe_hero_background' => 'gradient',
                ),
            ),
            array(
                'post_title'   => 'About Us',
                'post_content' => $this->get_about_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 2,
                'meta_input'   => array(
                    '_aqualuxe_page_header_style' => 'image',
                    '_aqualuxe_enable_breadcrumbs' => 'yes',
                ),
            ),
            array(
                'post_title'   => 'Services',
                'post_content' => $this->get_services_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 3,
                'meta_input'   => array(
                    '_aqualuxe_page_layout' => 'full-width',
                ),
            ),
            array(
                'post_title'   => 'Shop',
                'post_content' => $this->get_shop_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 4,
                'meta_input'   => array(
                    '_wp_page_template' => 'page-shop.php',
                ),
            ),
            array(
                'post_title'   => 'Contact',
                'post_content' => $this->get_contact_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 5,
                'meta_input'   => array(
                    '_aqualuxe_contact_form_enabled' => 'yes',
                    '_aqualuxe_google_maps_enabled' => 'yes',
                ),
            ),
            array(
                'post_title'   => 'Blog',
                'post_content' => $this->get_blog_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 6,
                'meta_input'   => array(
                    '_wp_page_template' => 'page-blog.php',
                ),
            ),
            array(
                'post_title'   => 'FAQ',
                'post_content' => $this->get_faq_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 7,
            ),
            array(
                'post_title'   => 'Privacy Policy',
                'post_content' => $this->get_privacy_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 8,
            ),
            array(
                'post_title'   => 'Terms & Conditions',
                'post_content' => $this->get_terms_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 9,
            ),
            array(
                'post_title'   => 'Shipping & Returns',
                'post_content' => $this->get_shipping_content(),
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'menu_order'   => 10,
            ),
        );

        foreach ( $pages as $page ) {
            $existing_page = get_page_by_title( $page['post_title'] );
            if ( ! $existing_page ) {
                $page_id = wp_insert_post( $page );
                
                // Set featured image if available
                if ( $page_id && ! is_wp_error( $page_id ) ) {
                    $this->set_page_featured_image( $page_id, $page['post_title'] );
                }
            }
        }
    }

    /**
     * Import posts
     */
    private function import_posts() {
        $posts = array(
            array(
                'post_title'   => 'The Ultimate Guide to Aquarium Setup for Beginners',
                'post_content' => $this->get_post_content( 'aquarium_setup' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Learn the essential steps for setting up a beautiful and healthy aquarium from scratch. This comprehensive guide covers everything from tank selection to water cycling.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '8',
                    '_aqualuxe_featured_post' => 'yes',
                ),
            ),
            array(
                'post_title'   => 'Top 10 Tropical Fish Species for Community Tanks',
                'post_content' => $this->get_post_content( 'tropical_fish' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Discover the best tropical fish species for new aquarium enthusiasts. These peaceful, hardy species are perfect for community tank setups.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '6',
                ),
            ),
            array(
                'post_title'   => 'Aquascaping: Creating Underwater Art in Your Home',
                'post_content' => $this->get_post_content( 'aquascaping' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Learn the principles of creating stunning underwater landscapes. Master the art of aquascaping with expert tips and techniques.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '7',
                ),
            ),
            array(
                'post_title'   => 'Marine vs. Freshwater: Choosing Your First Aquarium',
                'post_content' => $this->get_post_content( 'marine_vs_freshwater' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Deciding between marine and freshwater aquariums? Learn the pros and cons of each to make the best choice for your situation.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '5',
                ),
            ),
            array(
                'post_title'   => 'Essential Equipment for Professional Aquarium Maintenance',
                'post_content' => $this->get_post_content( 'equipment_guide' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Discover the professional-grade equipment that makes aquarium maintenance easier and more effective.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '9',
                ),
            ),
            array(
                'post_title'   => 'Breeding Rare Fish: Conservation Through Aquaculture',
                'post_content' => $this->get_post_content( 'fish_breeding' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Learn how responsible breeding programs help conserve rare species while providing sustainable sources for aquarists.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '10',
                    '_aqualuxe_featured_post' => 'yes',
                ),
            ),
            array(
                'post_title'   => 'Water Chemistry 101: Testing and Balancing Your Aquarium',
                'post_content' => $this->get_post_content( 'water_chemistry' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Master the fundamentals of aquarium water chemistry. Learn how to test, monitor, and maintain optimal water conditions.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '12',
                ),
            ),
            array(
                'post_title'   => 'LED vs. Traditional Lighting: What\'s Best for Your Tank?',
                'post_content' => $this->get_post_content( 'lighting_comparison' ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
                'post_excerpt' => 'Compare LED and traditional aquarium lighting options. Discover which lighting solution offers the best value and performance.',
                'meta_input'   => array(
                    '_aqualuxe_reading_time' => '6',
                ),
            ),
        );

        foreach ( $posts as $index => $post ) {
            $existing_post = get_page_by_title( $post['post_title'], OBJECT, 'post' );
            if ( ! $existing_post ) {
                $post_id = wp_insert_post( $post );
                
                if ( $post_id && ! is_wp_error( $post_id ) ) {
                    // Set categories
                    $this->set_post_category( $post_id, $post['post_title'] );
                    
                    // Set tags
                    $this->set_post_tags( $post_id, $post['post_title'] );
                    
                    // Set featured image
                    $this->set_post_featured_image( $post_id, $index );
                    
                    // Set publish date (spread over last 3 months)
                    $days_ago = ( $index * 10 ) + rand( 1, 5 );
                    $post_date = date( 'Y-m-d H:i:s', strtotime( "-{$days_ago} days" ) );
                    wp_update_post( array(
                        'ID'            => $post_id,
                        'post_date'     => $post_date,
                        'post_date_gmt' => get_gmt_from_date( $post_date ),
                    ) );
                }
            }
        }
    }

    /**
     * Import products
     */
    private function import_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        // Create product categories first
        $this->create_product_categories();

        // Create product attributes
        $this->create_product_attributes();

        // Import simple products
        $this->import_simple_products();

        // Import variable products
        $this->import_variable_products();

        // Import grouped products
        $this->import_grouped_products();

        // Set up shop page
        $this->setup_shop_page();
    }

    /**
     * Create product categories
     */
    private function create_product_categories() {
        $categories = array(
            array(
                'name'        => 'Tropical Fish',
                'slug'        => 'tropical-fish',
                'description' => 'Beautiful tropical fish species for community and species-specific tanks.',
                'parent'      => 0,
            ),
            array(
                'name'        => 'Marine Fish',
                'slug'        => 'marine-fish', 
                'description' => 'Saltwater fish for reef and fish-only marine aquariums.',
                'parent'      => 0,
            ),
            array(
                'name'        => 'Aquatic Plants',
                'slug'        => 'aquatic-plants',
                'description' => 'Live aquatic plants for natural aquascaping and planted tanks.',
                'parent'      => 0,
            ),
            array(
                'name'        => 'Equipment',
                'slug'        => 'equipment',
                'description' => 'Professional-grade aquarium equipment and accessories.',
                'parent'      => 0,
            ),
            array(
                'name'        => 'Lighting',
                'slug'        => 'lighting',
                'description' => 'LED and specialized lighting systems for aquariums.',
                'parent'      => 'equipment',
            ),
            array(
                'name'        => 'Filtration',
                'slug'        => 'filtration',
                'description' => 'Filters and filtration media for water quality management.',
                'parent'      => 'equipment',
            ),
            array(
                'name'        => 'Care Supplies',
                'slug'        => 'care-supplies',
                'description' => 'Water treatments, food, and maintenance supplies.',
                'parent'      => 0,
            ),
        );

        foreach ( $categories as $category ) {
            $parent_id = 0;
            if ( ! empty( $category['parent'] ) && is_string( $category['parent'] ) ) {
                $parent_term = get_term_by( 'slug', $category['parent'], 'product_cat' );
                if ( $parent_term ) {
                    $parent_id = $parent_term->term_id;
                }
            }

            $existing_term = get_term_by( 'slug', $category['slug'], 'product_cat' );
            if ( ! $existing_term ) {
                wp_insert_term( $category['name'], 'product_cat', array(
                    'description' => $category['description'],
                    'slug'        => $category['slug'],
                    'parent'      => $parent_id,
                ) );
            }
        }
    }

    /**
     * Create product attributes
     */
    private function create_product_attributes() {
        $attributes = array(
            array(
                'name'         => 'Size',
                'slug'         => 'size',
                'type'         => 'select',
                'order_by'     => 'menu_order',
                'has_archives' => false,
            ),
            array(
                'name'         => 'Color',
                'slug'         => 'color',
                'type'         => 'select',
                'order_by'     => 'name',
                'has_archives' => false,
            ),
            array(
                'name'         => 'Tank Size',
                'slug'         => 'tank-size',
                'type'         => 'select',
                'order_by'     => 'menu_order',
                'has_archives' => true,
            ),
            array(
                'name'         => 'Water Type',
                'slug'         => 'water-type',
                'type'         => 'select',
                'order_by'     => 'name',
                'has_archives' => true,
            ),
        );

        foreach ( $attributes as $attribute ) {
            if ( ! wc_attribute_taxonomy_name_by_id( wc_attribute_taxonomy_id_by_name( $attribute['slug'] ) ) ) {
                $attribute_id = wc_create_attribute( $attribute );
                
                if ( ! is_wp_error( $attribute_id ) ) {
                    // Add terms to the attribute
                    $this->add_attribute_terms( $attribute['slug'] );
                }
            }
        }
    }

    /**
     * Add terms to product attributes
     *
     * @param string $attribute_slug Attribute slug
     */
    private function add_attribute_terms( $attribute_slug ) {
        $terms_map = array(
            'size' => array( 'Small (2-4 inches)', 'Medium (4-6 inches)', 'Large (6+ inches)' ),
            'color' => array( 'Red', 'Blue', 'Yellow', 'Green', 'Orange', 'Purple', 'Black', 'White', 'Mixed' ),
            'tank-size' => array( '10-20 Gallons', '20-40 Gallons', '40-75 Gallons', '75+ Gallons' ),
            'water-type' => array( 'Freshwater', 'Marine', 'Brackish' ),
        );

        if ( isset( $terms_map[ $attribute_slug ] ) ) {
            $taxonomy = wc_attribute_taxonomy_name( $attribute_slug );
            
            foreach ( $terms_map[ $attribute_slug ] as $term_name ) {
                if ( ! term_exists( $term_name, $taxonomy ) ) {
                    wp_insert_term( $term_name, $taxonomy );
                }
            }
        }
    }

    /**
     * Import simple products
     */
    private function import_simple_products() {
        $products = array(
            array(
                'name'           => 'Premium Angelfish Pair',
                'description'    => $this->get_product_description( 'angelfish' ),
                'short_description' => 'Stunning angelfish breeding pair with excellent coloration and peaceful temperament.',
                'price'          => '89.99',
                'sale_price'     => '79.99',
                'manage_stock'   => true,
                'stock_quantity' => 8,
                'category'       => 'tropical-fish',
                'attributes'     => array( 'size' => 'Large (6+ inches)', 'water-type' => 'Freshwater' ),
                'featured'       => true,
            ),
            array(
                'name'           => 'Neon Tetra School (6 pieces)',
                'description'    => $this->get_product_description( 'neon_tetras' ),
                'short_description' => 'Vibrant neon tetras perfect for community tanks. Sold as a school of 6.',
                'price'          => '24.99',
                'manage_stock'   => true,
                'stock_quantity' => 15,
                'category'       => 'tropical-fish',
                'attributes'     => array( 'size' => 'Small (2-4 inches)', 'water-type' => 'Freshwater' ),
            ),
            array(
                'name'           => 'Premium LED Aquarium Light 48"',
                'description'    => $this->get_product_description( 'led_light' ),
                'short_description' => 'Full-spectrum LED lighting with programmable controls and natural shimmer effects.',
                'price'          => '299.99',
                'sale_price'     => '249.99',
                'manage_stock'   => true,
                'stock_quantity' => 12,
                'category'       => 'lighting',
                'attributes'     => array( 'tank-size' => '75+ Gallons' ),
                'featured'       => true,
            ),
            array(
                'name'           => 'Canister Filter Pro 400',
                'description'    => $this->get_product_description( 'canister_filter' ),
                'short_description' => 'Professional-grade canister filter with 4-stage filtration and quiet operation.',
                'price'          => '189.99',
                'manage_stock'   => true,
                'stock_quantity' => 6,
                'category'       => 'filtration',
                'attributes'     => array( 'tank-size' => '40-75 Gallons' ),
            ),
            array(
                'name'           => 'Aquascaping Plant Bundle - Beginner',
                'description'    => $this->get_product_description( 'plant_bundle' ),
                'short_description' => 'Perfect starter collection of easy-to-grow aquatic plants for new aquascapers.',
                'price'          => '49.99',
                'manage_stock'   => true,
                'stock_quantity' => 20,
                'category'       => 'aquatic-plants',
                'attributes'     => array( 'tank-size' => '20-40 Gallons', 'water-type' => 'Freshwater' ),
            ),
            array(
                'name'           => 'Marine Clownfish Pair (Captive Bred)',
                'description'    => $this->get_product_description( 'clownfish' ),
                'short_description' => 'Healthy captive-bred clownfish pair, perfect for reef aquariums.',
                'price'          => '129.99',
                'manage_stock'   => true,
                'stock_quantity' => 5,
                'category'       => 'marine-fish',
                'attributes'     => array( 'size' => 'Medium (4-6 inches)', 'water-type' => 'Marine' ),
                'featured'       => true,
            ),
        );

        foreach ( $products as $product_data ) {
            $this->create_simple_product( $product_data );
        }
    }

    /**
     * Import variable products
     */
    private function import_variable_products() {
        $variable_products = array(
            array(
                'name'           => 'Aquarium Heater',
                'description'    => $this->get_product_description( 'heater' ),
                'short_description' => 'Reliable submersible aquarium heater with adjustable temperature control.',
                'category'       => 'equipment',
                'variations'     => array(
                    array(
                        'attributes' => array( 'tank-size' => '10-20 Gallons' ),
                        'price'      => '29.99',
                        'stock'      => 25,
                        'sku'        => 'HEAT-50W',
                    ),
                    array(
                        'attributes' => array( 'tank-size' => '20-40 Gallons' ),
                        'price'      => '39.99',
                        'stock'      => 20,
                        'sku'        => 'HEAT-100W',
                    ),
                    array(
                        'attributes' => array( 'tank-size' => '40-75 Gallons' ),
                        'price'      => '49.99',
                        'stock'      => 15,
                        'sku'        => 'HEAT-150W',
                    ),
                    array(
                        'attributes' => array( 'tank-size' => '75+ Gallons' ),
                        'price'      => '69.99',
                        'stock'      => 10,
                        'sku'        => 'HEAT-200W',
                    ),
                ),
            ),
            array(
                'name'           => 'Aquascaping Rock Bundle',
                'description'    => $this->get_product_description( 'rock_bundle' ),
                'short_description' => 'Natural aquascaping rocks for creating stunning underwater landscapes.',
                'category'       => 'aquatic-plants',
                'variations'     => array(
                    array(
                        'attributes' => array( 'size' => 'Small (2-4 inches)', 'tank-size' => '10-20 Gallons' ),
                        'price'      => '19.99',
                        'stock'      => 30,
                        'sku'        => 'ROCK-SM',
                    ),
                    array(
                        'attributes' => array( 'size' => 'Medium (4-6 inches)', 'tank-size' => '20-40 Gallons' ),
                        'price'      => '29.99',
                        'stock'      => 25,
                        'sku'        => 'ROCK-MD',
                    ),
                    array(
                        'attributes' => array( 'size' => 'Large (6+ inches)', 'tank-size' => '40-75 Gallons' ),
                        'price'      => '39.99',
                        'stock'      => 20,
                        'sku'        => 'ROCK-LG',
                    ),
                ),
            ),
        );

        foreach ( $variable_products as $product_data ) {
            $this->create_variable_product( $product_data );
        }
    }

    /**
     * Import grouped products
     */
    private function import_grouped_products() {
        // First create individual products that will be part of the group
        $group_products = array(
            array(
                'name'           => 'Water Test Kit - pH',
                'description'    => 'Accurate pH testing kit for monitoring water chemistry.',
                'short_description' => 'Essential pH testing for aquarium water management.',
                'price'          => '12.99',
                'category'       => 'care-supplies',
                'for_group'      => true,
            ),
            array(
                'name'           => 'Water Test Kit - Ammonia',
                'description'    => 'Ammonia test kit for nitrogen cycle monitoring.',
                'short_description' => 'Critical ammonia testing for fish health.',
                'price'          => '14.99',
                'category'       => 'care-supplies',
                'for_group'      => true,
            ),
            array(
                'name'           => 'Water Test Kit - Nitrite',
                'description'    => 'Nitrite test kit for aquarium cycle monitoring.',
                'short_description' => 'Essential nitrite testing for new aquariums.',
                'price'          => '13.99',
                'category'       => 'care-supplies',
                'for_group'      => true,
            ),
            array(
                'name'           => 'Water Test Kit - Nitrate',
                'description'    => 'Nitrate test kit for long-term water quality.',
                'short_description' => 'Monitor nitrate levels for fish health.',
                'price'          => '13.99',
                'category'       => 'care-supplies',
                'for_group'      => true,
            ),
        );

        $group_product_ids = array();
        foreach ( $group_products as $product_data ) {
            $product_id = $this->create_simple_product( $product_data );
            if ( $product_id ) {
                $group_product_ids[] = $product_id;
            }
        }

        // Create the grouped product
        if ( ! empty( $group_product_ids ) ) {
            $grouped_product = array(
                'name'           => 'Complete Water Test Kit Bundle',
                'description'    => $this->get_product_description( 'test_kit_bundle' ),
                'short_description' => 'Everything you need to monitor aquarium water chemistry. Save 15% vs buying individually.',
                'category'       => 'care-supplies',
                'grouped_products' => $group_product_ids,
                'featured'       => true,
            );
            
            $this->create_grouped_product( $grouped_product );
        }
    }

    /**
     * Import custom post types
     */
    private function import_custom_post_types() {
        // Import services
        $services = array(
            array(
                'post_title'   => 'Aquarium Design & Installation',
                'post_content' => 'Professional aquarium design and installation services.',
                'post_excerpt' => 'Custom aquarium solutions for your space.',
                'meta_input'   => array(
                    '_service_price'    => 'From $299',
                    '_service_duration' => '2-4 hours',
                ),
            ),
            array(
                'post_title'   => 'Maintenance & Cleaning',
                'post_content' => 'Regular maintenance to keep your aquarium healthy.',
                'post_excerpt' => 'Professional maintenance services.',
                'meta_input'   => array(
                    '_service_price'    => '$89/month',
                    '_service_duration' => '1 hour',
                ),
            ),
        );

        foreach ( $services as $service ) {
            $service['post_type'] = 'service';
            $service['post_status'] = 'publish';
            wp_insert_post( $service );
        }

        // Import testimonials
        $testimonials = array(
            array(
                'post_title'   => 'Outstanding Service',
                'post_content' => 'AquaLuxe transformed my office with a stunning aquarium. The fish are healthy and the design is beautiful.',
                'meta_input'   => array(
                    '_testimonial_author'  => 'Sarah Johnson',
                    '_testimonial_company' => 'Tech Solutions Inc.',
                    '_testimonial_rating'  => 5,
                ),
            ),
            array(
                'post_title'   => 'Expert Knowledge',
                'post_content' => 'The team at AquaLuxe really knows their stuff. They helped me choose the perfect fish for my setup.',
                'meta_input'   => array(
                    '_testimonial_author'  => 'Mike Chen',
                    '_testimonial_company' => 'Aquarium Enthusiast',
                    '_testimonial_rating'  => 5,
                ),
            ),
        );

        foreach ( $testimonials as $testimonial ) {
            $testimonial['post_type'] = 'testimonial';
            $testimonial['post_status'] = 'publish';
            wp_insert_post( $testimonial );
        }
    }

    /**
     * Import media files from external sources
     */
    private function import_media_files() {
        // Define demo media files with free, copyright-compliant sources
        $media_files = array(
            array(
                'url'         => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'tropical-fish-aquarium.jpg',
                'title'       => 'Tropical Fish in Aquarium',
                'alt_text'    => 'Beautiful tropical fish swimming in a well-maintained aquarium',
                'description' => 'Colorful tropical fish in their natural aquatic environment',
                'caption'     => 'Tropical fish showcase the beauty of aquatic life',
                'attribution' => 'Photo by David Clode on Unsplash',
            ),
            array(
                'url'         => 'https://images.unsplash.com/photo-1520637836862-4d197d17c699?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'aquascaping-plants.jpg',
                'title'       => 'Aquascaping with Live Plants',
                'alt_text'    => 'Professional aquascaping featuring lush aquatic plants',
                'description' => 'Beautiful aquascape design with various aquatic plants',
                'caption'     => 'Expert aquascaping creates underwater gardens',
                'attribution' => 'Photo by Zoltan Tasi on Unsplash',
            ),
            array(
                'url'         => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'angelfish-pair.jpg',
                'title'       => 'Premium Angelfish Pair',
                'alt_text'    => 'Elegant angelfish pair perfect for community aquariums',
                'description' => 'High-quality angelfish breeding pair with excellent coloration',
                'caption'     => 'Premium angelfish for discerning aquarists',
                'attribution' => 'Photo by Kyaw Tun on Unsplash',
            ),
            array(
                'url'         => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'coral-reef-aquarium.jpg',
                'title'       => 'Coral Reef Aquarium',
                'alt_text'    => 'Stunning coral reef aquarium with marine life',
                'description' => 'Professional marine aquarium featuring coral reef ecosystem',
                'caption'     => 'Marine aquariums bring ocean beauty indoors',
                'attribution' => 'Photo by Francesco Ungaro on Unsplash',
            ),
            array(
                'url'         => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'aquarium-maintenance.jpg',
                'title'       => 'Professional Aquarium Maintenance',
                'alt_text'    => 'Expert aquarium maintenance and care services',
                'description' => 'Professional maintenance ensuring optimal aquarium health',
                'caption'     => 'Expert care keeps aquariums thriving',
                'attribution' => 'Photo by Tengyart on Unsplash',
            ),
            array(
                'url'         => 'https://images.unsplash.com/photo-1535591273668-578e31182c4f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80',
                'filename'    => 'aquarium-equipment.jpg',
                'title'       => 'Premium Aquarium Equipment',
                'alt_text'    => 'High-quality aquarium equipment and accessories',
                'description' => 'Professional-grade equipment for optimal aquarium performance',
                'caption'     => 'Quality equipment ensures aquarium success',
                'attribution' => 'Photo by Zoltan Tasi on Unsplash',
            ),
        );

        $imported_count = 0;
        $total_files = count( $media_files );

        foreach ( $media_files as $index => $file ) {
            try {
                $attachment_id = $this->download_and_import_media( $file );
                
                if ( $attachment_id ) {
                    $imported_count++;
                    
                    // Update progress
                    $progress = get_transient( 'aqualuxe_import_progress' );
                    if ( $progress ) {
                        $progress['message'] = sprintf( 
                            __( 'Importing media files... (%d/%d)', 'aqualuxe' ), 
                            $imported_count, 
                            $total_files 
                        );
                        set_transient( 'aqualuxe_import_progress', $progress, HOUR_IN_SECONDS );
                    }
                }
                
                // Small delay to prevent overwhelming external servers
                usleep( 500000 ); // 0.5 seconds
                
            } catch ( Exception $e ) {
                error_log( sprintf( 
                    'AquaLuxe Media Import Error for %s: %s', 
                    $file['filename'], 
                    $e->getMessage() 
                ) );
                // Continue with next file instead of failing completely
                continue;
            }
        }

        return $imported_count;
    }

    /**
     * Download and import a single media file
     *
     * @param array $file_data File information
     * @return int|false Attachment ID on success, false on failure
     */
    private function download_and_import_media( $file_data ) {
        if ( empty( $file_data['url'] ) || empty( $file_data['filename'] ) ) {
            return false;
        }

        // Check if file already exists
        $existing = $this->get_attachment_by_filename( $file_data['filename'] );
        if ( $existing ) {
            return $existing->ID;
        }

        // Download the file
        $temp_file = $this->download_remote_file( $file_data['url'] );
        if ( is_wp_error( $temp_file ) ) {
            throw new Exception( $temp_file->get_error_message() );
        }

        // Prepare file data for upload
        $file_array = array(
            'name'     => $file_data['filename'],
            'tmp_name' => $temp_file,
        );

        // Import to WordPress media library
        $attachment_id = media_handle_sideload( $file_array, 0 );
        
        // Clean up temp file
        if ( file_exists( $temp_file ) ) {
            @unlink( $temp_file );
        }

        if ( is_wp_error( $attachment_id ) ) {
            throw new Exception( $attachment_id->get_error_message() );
        }

        // Set metadata
        $this->set_media_metadata( $attachment_id, $file_data );

        return $attachment_id;
    }

    /**
     * Download a remote file to temporary location
     *
     * @param string $url Remote file URL
     * @return string|WP_Error Path to temp file on success, WP_Error on failure
     */
    private function download_remote_file( $url ) {
        if ( ! function_exists( 'download_url' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        // Set timeout and user agent for better compatibility
        add_filter( 'http_request_timeout', function() { return 30; } );
        add_filter( 'http_request_args', function( $args ) {
            $args['user-agent'] = 'AquaLuxe Theme Demo Importer/1.0';
            return $args;
        } );

        $temp_file = download_url( $url );

        remove_all_filters( 'http_request_timeout' );
        remove_all_filters( 'http_request_args' );

        if ( is_wp_error( $temp_file ) ) {
            return $temp_file;
        }

        // Validate file type
        $file_type = wp_check_filetype( basename( $url ) );
        if ( ! $file_type['type'] ) {
            @unlink( $temp_file );
            return new WP_Error( 'invalid_file_type', __( 'Invalid file type', 'aqualuxe' ) );
        }

        // Check file size (limit to 10MB)
        $file_size = filesize( $temp_file );
        if ( $file_size > 10 * 1024 * 1024 ) {
            @unlink( $temp_file );
            return new WP_Error( 'file_too_large', __( 'File too large', 'aqualuxe' ) );
        }

        return $temp_file;
    }

    /**
     * Set media metadata
     *
     * @param int   $attachment_id Attachment ID
     * @param array $file_data     File metadata
     */
    private function set_media_metadata( $attachment_id, $file_data ) {
        // Set alt text
        if ( ! empty( $file_data['alt_text'] ) ) {
            update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $file_data['alt_text'] ) );
        }

        // Update post data
        $post_data = array(
            'ID' => $attachment_id,
        );

        if ( ! empty( $file_data['title'] ) ) {
            $post_data['post_title'] = sanitize_text_field( $file_data['title'] );
        }

        if ( ! empty( $file_data['description'] ) ) {
            $post_data['post_content'] = sanitize_textarea_field( $file_data['description'] );
        }

        if ( ! empty( $file_data['caption'] ) ) {
            $post_data['post_excerpt'] = sanitize_textarea_field( $file_data['caption'] );
        }

        wp_update_post( $post_data );

        // Store attribution info
        if ( ! empty( $file_data['attribution'] ) ) {
            update_post_meta( $attachment_id, '_aqualuxe_attribution', sanitize_text_field( $file_data['attribution'] ) );
        }

        // Add to AquaLuxe media collection
        wp_set_object_terms( $attachment_id, 'aqualuxe-demo', 'media_category', true );
    }

    /**
     * Get attachment by filename
     *
     * @param string $filename Filename to search for
     * @return WP_Post|null Attachment post object or null
     */
    private function get_attachment_by_filename( $filename ) {
        global $wpdb;

        $attachment = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM {$wpdb->posts} WHERE post_type = 'attachment' AND guid LIKE %s",
            '%' . $wpdb->esc_like( $filename )
        ) );

        return $attachment ? get_post( $attachment->ID ) : null;
    }

    /**
     * Import customizer settings
     */
    private function import_customizer_settings() {
        $settings = array(
            // Colors
            'aqualuxe_primary_color'      => '#0ea5e9',
            'aqualuxe_secondary_color'    => '#64748b',
            'aqualuxe_accent_color'       => '#06b6d4',
            'aqualuxe_text_color'         => '#1e293b',
            'aqualuxe_heading_color'      => '#0f172a',
            'aqualuxe_background_color'   => '#ffffff',
            'aqualuxe_border_color'       => '#e2e8f0',
            
            // Typography
            'aqualuxe_body_font'          => 'Inter',
            'aqualuxe_heading_font'       => 'Playfair Display',
            'aqualuxe_body_font_size'     => '16',
            'aqualuxe_h1_font_size'       => '48',
            'aqualuxe_h2_font_size'       => '36',
            'aqualuxe_h3_font_size'       => '24',
            
            // Hero Section
            'aqualuxe_hero_title'         => 'Bringing Elegance to Aquatic Life',
            'aqualuxe_hero_subtitle'      => 'Discover premium aquatic solutions, rare species, and expert care services for hobbyists and professionals worldwide.',
            'aqualuxe_hero_button_text'   => 'Explore Our Collection',
            'aqualuxe_hero_button_url'    => '#featured-products',
            'aqualuxe_hero_overlay_opacity' => '0.6',
            
            // Layout
            'aqualuxe_site_layout'        => 'wide',
            'aqualuxe_sidebar_position'   => 'right',
            'aqualuxe_container_width'    => '1200',
            'aqualuxe_enable_breadcrumbs' => true,
            'aqualuxe_enable_search'      => true,
            
            // Header
            'aqualuxe_header_layout'      => 'default',
            'aqualuxe_header_sticky'      => true,
            'aqualuxe_header_transparent' => false,
            'aqualuxe_mobile_menu_style'  => 'slide',
            
            // Footer
            'aqualuxe_footer_layout'      => 'columns',
            'aqualuxe_footer_columns'     => '3',
            'aqualuxe_footer_copyright'   => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
            'aqualuxe_footer_show_social' => true,
            
            // Blog
            'aqualuxe_blog_layout'        => 'grid',
            'aqualuxe_blog_columns'       => '2',
            'aqualuxe_excerpt_length'     => '25',
            'aqualuxe_show_author'        => true,
            'aqualuxe_show_date'          => true,
            'aqualuxe_show_categories'    => true,
            'aqualuxe_show_reading_time'  => true,
            
            // Shop (WooCommerce)
            'aqualuxe_shop_layout'        => 'grid',
            'aqualuxe_shop_columns'       => '3',
            'aqualuxe_products_per_page'  => '12',
            'aqualuxe_enable_quick_view'  => true,
            'aqualuxe_enable_wishlist'    => true,
            'aqualuxe_enable_compare'     => true,
            
            // Performance
            'aqualuxe_enable_lazy_loading' => true,
            'aqualuxe_enable_minification' => true,
            'aqualuxe_enable_compression'  => true,
            
            // Social Media
            'aqualuxe_facebook_url'       => '',
            'aqualuxe_twitter_url'        => '',
            'aqualuxe_instagram_url'      => '',
            'aqualuxe_youtube_url'        => '',
            'aqualuxe_linkedin_url'       => '',
            
            // Contact Information
            'aqualuxe_contact_phone'      => '+1 (555) 123-4567',
            'aqualuxe_contact_email'      => 'info@aqualuxe.com',
            'aqualuxe_contact_address'    => '123 Aquatic Avenue, Ocean City, CA 90210',
            'aqualuxe_business_hours'     => 'Mon-Fri: 9AM-6PM, Sat: 10AM-4PM',
            
            // SEO & Analytics
            'aqualuxe_enable_schema'      => true,
            'aqualuxe_enable_og_tags'     => true,
            'aqualuxe_google_analytics'   => '',
            'aqualuxe_facebook_pixel'     => '',
            
            // Advanced
            'aqualuxe_enable_dark_mode'   => true,
            'aqualuxe_enable_rtl'         => false,
            'aqualuxe_enable_animations'  => true,
            'aqualuxe_animation_duration' => '300',
        );

        foreach ( $settings as $setting => $value ) {
            set_theme_mod( $setting, $value );
        }

        // Import theme colors for block editor
        $this->import_block_editor_colors();
        
        // Import custom CSS if any
        $this->import_custom_css();
    }

    /**
     * Import block editor color palette
     */
    private function import_block_editor_colors() {
        $color_palette = array(
            array(
                'name'  => __( 'Primary', 'aqualuxe' ),
                'slug'  => 'primary',
                'color' => get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' ),
            ),
            array(
                'name'  => __( 'Secondary', 'aqualuxe' ),
                'slug'  => 'secondary',
                'color' => get_theme_mod( 'aqualuxe_secondary_color', '#64748b' ),
            ),
            array(
                'name'  => __( 'Accent', 'aqualuxe' ),
                'slug'  => 'accent',
                'color' => get_theme_mod( 'aqualuxe_accent_color', '#06b6d4' ),
            ),
            array(
                'name'  => __( 'Dark', 'aqualuxe' ),
                'slug'  => 'dark',
                'color' => '#1e293b',
            ),
            array(
                'name'  => __( 'Light', 'aqualuxe' ),
                'slug'  => 'light',
                'color' => '#f8fafc',
            ),
            array(
                'name'  => __( 'White', 'aqualuxe' ),
                'slug'  => 'white',
                'color' => '#ffffff',
            ),
        );

        set_theme_mod( 'aqualuxe_color_palette', $color_palette );
    }

    /**
     * Import custom CSS
     */
    private function import_custom_css() {
        $custom_css = '
/* AquaLuxe Demo Custom CSS */
.hero-section {
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
}

.card-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.aquatic-gradient {
    background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #0891b2 100%);
}

.text-gradient {
    background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.ripple-effect {
    position: relative;
    overflow: hidden;
}

.ripple-effect::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ripple-effect:hover::before {
    width: 300px;
    height: 300px;
}
        ';

        wp_update_custom_css_post( $custom_css );
    }

    /**
     * Import widgets
     */
    private function import_widgets() {
        $widgets = array(
            'sidebar-1' => array(
                'search-2' => array(
                    'title' => __( 'Search', 'aqualuxe' ),
                ),
                'recent-posts-2' => array(
                    'title'  => __( 'Recent Posts', 'aqualuxe' ),
                    'number' => 5,
                ),
                'categories-2' => array(
                    'title'    => __( 'Categories', 'aqualuxe' ),
                    'dropdown' => 0,
                ),
                'archives-2' => array(
                    'title'    => __( 'Archives', 'aqualuxe' ),
                    'dropdown' => 0,
                ),
            ),
            'footer-1' => array(
                'text-2' => array(
                    'title' => __( 'About AquaLuxe', 'aqualuxe' ),
                    'text'  => __( 'Bringing elegance to aquatic life globally. We provide premium aquatic solutions for hobbyists and professionals.', 'aqualuxe' ),
                ),
            ),
            'footer-2' => array(
                'nav_menu-2' => array(
                    'title' => __( 'Quick Links', 'aqualuxe' ),
                    'nav_menu' => $this->get_menu_id_by_name( 'Primary Navigation' ),
                ),
            ),
            'footer-3' => array(
                'text-3' => array(
                    'title' => __( 'Contact Info', 'aqualuxe' ),
                    'text'  => __( 'Email: info@aqualuxe.com<br>Phone: +1 (555) 123-4567<br>Address: 123 Aquatic Ave, Ocean City', 'aqualuxe' ),
                ),
            ),
        );

        foreach ( $widgets as $sidebar_id => $sidebar_widgets ) {
            $this->import_sidebar_widgets( $sidebar_id, $sidebar_widgets );
        }
    }

    /**
     * Import widgets for a specific sidebar
     *
     * @param string $sidebar_id      Sidebar ID
     * @param array  $sidebar_widgets Widget configuration
     */
    private function import_sidebar_widgets( $sidebar_id, $sidebar_widgets ) {
        $sidebars_widgets = get_option( 'sidebars_widgets', array() );
        $widget_instances = array();

        if ( ! isset( $sidebars_widgets[ $sidebar_id ] ) ) {
            $sidebars_widgets[ $sidebar_id ] = array();
        }

        foreach ( $sidebar_widgets as $widget_id => $widget_settings ) {
            $widget_type = explode( '-', $widget_id )[0];
            $widget_number = explode( '-', $widget_id )[1] ?? 2;
            
            // Get existing widget instances
            $widget_data = get_option( 'widget_' . $widget_type, array() );
            
            // Add new widget instance
            $widget_data[ $widget_number ] = $widget_settings;
            
            // Update widget data
            update_option( 'widget_' . $widget_type, $widget_data );
            
            // Add widget to sidebar
            $sidebars_widgets[ $sidebar_id ][] = $widget_id;
        }

        update_option( 'sidebars_widgets', $sidebars_widgets );
    }

    /**
     * Get menu ID by name
     *
     * @param string $menu_name Menu name
     * @return int
     */
    private function get_menu_id_by_name( $menu_name ) {
        $menu = wp_get_nav_menu_object( $menu_name );
        return $menu ? $menu->term_id : 0;
    }

    /**
     * Setup menus
     */
    private function setup_menus() {
        $menu_name = 'Primary Navigation';
        $menu_exists = wp_get_nav_menu_object( $menu_name );

        if ( ! $menu_exists ) {
            $menu_id = wp_create_nav_menu( $menu_name );
            
            // Add menu items
            $pages = get_pages();
            foreach ( $pages as $page ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'  => $page->post_title,
                    'menu-item-object-id' => $page->ID,
                    'menu-item-object' => 'page',
                    'menu-item-type'   => 'post_type',
                    'menu-item-status' => 'publish',
                ) );
            }

            // Assign menu to location
            $locations = get_theme_mod( 'nav_menu_locations' );
            $locations['primary'] = $menu_id;
            set_theme_mod( 'nav_menu_locations', $locations );
        }
    }

    /**
     * Setup homepage
     */
    private function setup_homepage() {
        $homepage = get_page_by_title( 'Home' );
        if ( $homepage ) {
            update_option( 'page_on_front', $homepage->ID );
            update_option( 'show_on_front', 'page' );
        }
    }

    /**
     * Reset posts and pages
     */
    private function reset_posts_and_pages() {
        $post_types = array( 'post', 'page', 'service', 'event', 'team', 'testimonial', 'portfolio', 'faq' );
        
        foreach ( $post_types as $post_type ) {
            $posts = get_posts( array(
                'post_type'      => $post_type,
                'posts_per_page' => -1,
                'post_status'    => 'any',
            ) );
            
            foreach ( $posts as $post ) {
                wp_delete_post( $post->ID, true );
            }
        }
    }

    /**
     * Reset media files
     */
    private function reset_media_files() {
        // Get all attachments, but prioritize demo content
        $demo_attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'meta_query'     => array(
                array(
                    'key'     => '_aqualuxe_attribution',
                    'compare' => 'EXISTS',
                ),
            ),
        ) );

        // Delete demo-specific media first
        foreach ( $demo_attachments as $attachment ) {
            wp_delete_attachment( $attachment->ID, true );
        }

        // If user specifically wants to delete ALL media
        $all_attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );
        
        foreach ( $all_attachments as $attachment ) {
            // Skip if this seems to be user-uploaded content (not demo)
            $upload_date = get_the_date( 'Y-m-d', $attachment->ID );
            $today = date( 'Y-m-d' );
            
            // Only delete if uploaded today (likely demo content) or has demo attribution
            if ( $upload_date === $today || get_post_meta( $attachment->ID, '_aqualuxe_attribution', true ) ) {
                wp_delete_attachment( $attachment->ID, true );
            }
        }
    }

    /**
     * Reset products
     */
    private function reset_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $products = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );
        
        foreach ( $products as $product ) {
            wp_delete_post( $product->ID, true );
        }
    }

    /**
     * Reset customizer settings
     */
    private function reset_customizer_settings() {
        remove_theme_mods();
    }

    /**
     * Get detailed post content based on topic
     *
     * @param string $topic Content topic
     * @return string
     */
    private function get_post_content( $topic ) {
        $content_map = array(
            'aquarium_setup' => '
<div class="prose max-w-none">
    <h2>Getting Started with Your First Aquarium</h2>
    
    <p>Setting up your first aquarium is an exciting journey into the world of aquatic life. Whether you\'re planning a simple freshwater community tank or dreaming of a complex reef system, proper planning and preparation are essential for success.</p>
    
    <h3>Step 1: Choose the Right Tank Size</h3>
    <p>Contrary to popular belief, larger tanks are actually easier to maintain than smaller ones. A 20-gallon tank is the minimum we recommend for beginners, with 40-55 gallons being ideal for your first setup.</p>
    
    <h3>Step 2: Essential Equipment</h3>
    <ul>
        <li><strong>Filtration System:</strong> The heart of your aquarium</li>
        <li><strong>Heater:</strong> Maintains stable temperature</li>
        <li><strong>Lighting:</strong> Essential for plants and fish health</li>
        <li><strong>Water Testing Kit:</strong> Monitor water parameters</li>
        <li><strong>Substrate:</strong> Gravel or sand for the bottom</li>
    </ul>
    
    <h3>Step 3: The Nitrogen Cycle</h3>
    <p>Understanding the nitrogen cycle is crucial for aquarium success. This natural process converts toxic ammonia from fish waste into less harmful nitrates through beneficial bacteria.</p>
    
    <h3>Step 4: Cycling Your Tank</h3>
    <p>Before adding fish, your tank must be cycled to establish beneficial bacteria. This process typically takes 4-6 weeks but is essential for fish health.</p>
    
    <h3>Step 5: Choosing Your First Fish</h3>
    <p>Start with hardy, peaceful species that can tolerate minor water parameter fluctuations. Good beginner fish include tetras, corydoras, and peaceful barbs.</p>
    
    <h3>Maintenance Schedule</h3>
    <ul>
        <li><strong>Daily:</strong> Check temperature, feed fish</li>
        <li><strong>Weekly:</strong> Test water parameters, 25% water change</li>
        <li><strong>Monthly:</strong> Clean filter media, check equipment</li>
        <li><strong>Quarterly:</strong> Deep cleaning, equipment inspection</li>
    </ul>
    
    <p>Remember, patience is key to aquarium success. Take your time with each step, and don\'t rush the process. Your fish will thank you for it!</p>
</div>
            ',
            'tropical_fish' => '
<div class="prose max-w-none">
    <h2>Best Community Fish for Beginners</h2>
    
    <p>Choosing the right fish for your community tank is crucial for creating a peaceful, thriving aquatic environment. Here are our top 10 recommendations for beginner-friendly tropical fish.</p>
    
    <h3>1. Neon Tetras (Paracheirodon innesi)</h3>
    <p>These small, colorful fish are perfect for community tanks. Their peaceful nature and stunning blue and red coloration make them a favorite among aquarists.</p>
    
    <h3>2. Corydoras Catfish</h3>
    <p>Bottom-dwelling cleaners that help keep your tank tidy. They\'re social fish that do best in groups of 6 or more.</p>
    
    <h3>3. Guppies (Poecilia reticulata)</h3>
    <p>Hardy and colorful, guppies are livebearers that are very forgiving of water parameter fluctuations.</p>
    
    <h3>4. Platy Fish (Xiphophorus maculatus)</h3>
    <p>Peaceful and easy to care for, platys come in many color varieties and breed readily in captivity.</p>
    
    <h3>5. Cherry Barbs (Puntius titteya)</h3>
    <p>Less aggressive than other barb species, cherry barbs add beautiful red coloration to your tank.</p>
    
    <h3>6. Zebra Danios (Danio rerio)</h3>
    <p>Active schooling fish that are extremely hardy and can tolerate a wide range of water conditions.</p>
    
    <h3>7. Mollies (Poecilia sphenops)</h3>
    <p>Available in many color variations, mollies are peaceful and can adapt to both fresh and slightly brackish water.</p>
    
    <h3>8. Swordtails (Xiphophorus hellerii)</h3>
    <p>Named for the male\'s sword-like tail extension, these fish are hardy and come in various colors.</p>
    
    <h3>9. Rasboras (Trigonostigma heteromorpha)</h3>
    <p>Small, peaceful schooling fish that add gentle movement and subtle colors to your aquarium.</p>
    
    <h3>10. Bristlenose Plecos (Ancistrus sp.)</h3>
    <p>Excellent algae eaters that stay relatively small compared to common plecos, making them perfect for community tanks.</p>
    
    <h3>Tank Compatibility Tips</h3>
    <ul>
        <li>Always research adult sizes before purchasing</li>
        <li>Introduce new fish gradually</li>
        <li>Quarantine new additions for 2-4 weeks</li>
        <li>Maintain proper school sizes for schooling species</li>
        <li>Monitor for aggression and be prepared to rehome problem fish</li>
    </ul>
</div>
            ',
            'aquascaping' => '
<div class="prose max-w-none">
    <h2>The Art of Aquascaping</h2>
    
    <p>Aquascaping transforms your aquarium from a simple fish tank into a living work of art. This ancient practice combines horticultural knowledge with artistic vision to create stunning underwater landscapes.</p>
    
    <h3>Popular Aquascaping Styles</h3>
    
    <h4>Nature Style (Takashi Amano)</h4>
    <p>Inspired by natural landscapes, this style emphasizes asymmetry and the golden ratio to create peaceful, balanced compositions.</p>
    
    <h4>Dutch Style</h4>
    <p>Focuses on lush plant growth with strict attention to color, texture, and plant placement. Known for its "streets" of plants creating depth.</p>
    
    <h4>Iwagumi Style</h4>
    <p>A minimalist approach using rocks as the primary hardscape element. Typically features a single carpet plant species.</p>
    
    <h3>Essential Elements</h3>
    
    <h4>Hardscape Materials</h4>
    <ul>
        <li><strong>Rocks:</strong> Dragon stone, Seiryu stone, lava rock</li>
        <li><strong>Driftwood:</strong> Spiderwood, Manzanita, Malaysian driftwood</li>
        <li><strong>Substrate:</strong> Aqua soil, sand, gravel combinations</li>
    </ul>
    
    <h4>Plant Categories</h4>
    <ul>
        <li><strong>Foreground:</strong> Carpet plants like HC Cuba, Monte Carlo</li>
        <li><strong>Midground:</strong> Anubias, Cryptocoryne, smaller sword plants</li>
        <li><strong>Background:</strong> Vallisneria, Rotala, stem plants</li>
    </ul>
    
    <h3>Design Principles</h3>
    
    <h4>Rule of Thirds</h4>
    <p>Divide your tank into nine sections and place focal points at the intersections for natural-looking compositions.</p>
    
    <h4>Creating Depth</h4>
    <ul>
        <li>Use smaller plants in the foreground</li>
        <li>Layer plants by height</li>
        <li>Create pathways and open spaces</li>
        <li>Use perspective to make the tank appear larger</li>
    </ul>
    
    <h3>Maintenance for Success</h3>
    <p>Beautiful aquascapes require consistent maintenance:</p>
    <ul>
        <li>Regular pruning to maintain shape</li>
        <li>Proper fertilization for plant health</li>
        <li>CO2 injection for demanding plants</li>
        <li>Consistent lighting schedule</li>
        <li>Water parameter stability</li>
    </ul>
    
    <h3>Getting Started</h3>
    <p>Begin with easier plants and simple layouts. As your skills develop, you can tackle more complex designs and demanding plant species. Remember, aquascaping is a journey of continuous learning and improvement.</p>
</div>
            ',
        );

        return isset( $content_map[ $topic ] ) ? $content_map[ $topic ] : '<p>Content coming soon...</p>';
    }

    /**
     * Set post category based on title
     *
     * @param int    $post_id    Post ID
     * @param string $post_title Post title
     */
    private function set_post_category( $post_id, $post_title ) {
        $category_map = array(
            'Setup'      => array( 'Aquarium Setup', 'Marine vs. Freshwater' ),
            'Care'       => array( 'Tropical Fish', 'Water Chemistry' ),
            'Design'     => array( 'Aquascaping' ),
            'Equipment'  => array( 'Essential Equipment', 'LED vs. Traditional' ),
            'Advanced'   => array( 'Breeding Rare Fish' ),
        );

        foreach ( $category_map as $category => $titles ) {
            foreach ( $titles as $title_part ) {
                if ( strpos( $post_title, $title_part ) !== false ) {
                    $cat_id = $this->create_or_get_category( $category );
                    wp_set_post_categories( $post_id, array( $cat_id ) );
                    return;
                }
            }
        }

        // Default category
        $default_cat = $this->create_or_get_category( 'Aquarium Tips' );
        wp_set_post_categories( $post_id, array( $default_cat ) );
    }

    /**
     * Set post tags based on title
     *
     * @param int    $post_id    Post ID
     * @param string $post_title Post title
     */
    private function set_post_tags( $post_id, $post_title ) {
        $all_tags = array(
            'aquarium', 'fish', 'tropical-fish', 'freshwater', 'marine', 'saltwater',
            'aquascaping', 'plants', 'equipment', 'setup', 'maintenance', 'care',
            'beginner', 'advanced', 'lighting', 'filtration', 'breeding', 'conservation'
        );

        $post_tags = array();
        $title_lower = strtolower( $post_title );

        // Add relevant tags based on content
        if ( strpos( $title_lower, 'setup' ) !== false || strpos( $title_lower, 'beginner' ) !== false ) {
            $post_tags = array_merge( $post_tags, array( 'aquarium', 'setup', 'beginner' ) );
        }
        if ( strpos( $title_lower, 'tropical' ) !== false || strpos( $title_lower, 'fish' ) !== false ) {
            $post_tags = array_merge( $post_tags, array( 'fish', 'tropical-fish', 'freshwater' ) );
        }
        if ( strpos( $title_lower, 'aquascaping' ) !== false ) {
            $post_tags = array_merge( $post_tags, array( 'aquascaping', 'plants', 'design' ) );
        }
        if ( strpos( $title_lower, 'equipment' ) !== false ) {
            $post_tags = array_merge( $post_tags, array( 'equipment', 'maintenance' ) );
        }
        if ( strpos( $title_lower, 'breeding' ) !== false ) {
            $post_tags = array_merge( $post_tags, array( 'breeding', 'conservation', 'advanced' ) );
        }

        // Always add general tags
        $post_tags = array_merge( $post_tags, array( 'aquarium', 'care' ) );

        wp_set_post_tags( $post_id, array_unique( $post_tags ) );
    }

    /**
     * Create or get category ID
     *
     * @param string $category_name Category name
     * @return int Category ID
     */
    private function create_or_get_category( $category_name ) {
        $category = get_category_by_slug( sanitize_title( $category_name ) );
        
        if ( ! $category ) {
            $category_id = wp_create_category( $category_name );
            return $category_id;
        }
        
        return $category->term_id;
    }

    /**
     * Set featured image for post
     *
     * @param int $post_id Post ID
     * @param int $index   Post index for image selection
     */
    private function set_post_featured_image( $post_id, $index ) {
        $image_filenames = array(
            'tropical-fish-aquarium.jpg',
            'aquascaping-plants.jpg',
            'coral-reef-aquarium.jpg',
            'aquarium-maintenance.jpg',
            'aquarium-equipment.jpg',
        );
        
        $image_filename = $image_filenames[ $index % count( $image_filenames ) ];
        $attachment = $this->get_attachment_by_filename( $image_filename );
        
        if ( $attachment ) {
            set_post_thumbnail( $post_id, $attachment->ID );
        }
    }

    /**
     * Get product description based on type
     *
     * @param string $product_type Product type
     * @return string
     */
    private function get_product_description( $product_type ) {
        $descriptions = array(
            'angelfish' => '
<h3>Premium Angelfish Breeding Pair</h3>
<p>These stunning angelfish represent the pinnacle of aquarium breeding, featuring exceptional coloration and perfect finnage. Our breeding pairs are carefully selected for genetic diversity and temperament.</p>

<h4>Key Features:</h4>
<ul>
    <li>Hand-selected breeding pair with proven genetics</li>
    <li>Excellent coloration and fin development</li>
    <li>Peaceful temperament suitable for community tanks</li>
    <li>Quarantined and health-tested</li>
    <li>Ready to breed in appropriate conditions</li>
</ul>

<h4>Care Requirements:</h4>
<ul>
    <li><strong>Tank Size:</strong> Minimum 40 gallons for breeding pair</li>
    <li><strong>Temperature:</strong> 76-82°F (24-28°C)</li>
    <li><strong>pH:</strong> 6.0-7.0</li>
    <li><strong>Diet:</strong> High-quality flakes, pellets, and live/frozen foods</li>
</ul>

<h4>Breeding Information:</h4>
<p>Angelfish are excellent parents when conditions are right. Provide broad-leafed plants or breeding cones for spawning. Remove other fish during breeding for best results.</p>
            ',
            'neon_tetras' => '
<h3>Neon Tetra School (6 pieces)</h3>
<p>The iconic neon tetra is one of the most popular aquarium fish, known for their brilliant blue and red coloration. These peaceful schooling fish create stunning displays in community aquariums.</p>

<h4>Why Choose Our Neon Tetras:</h4>
<ul>
    <li>Healthy, disease-free specimens</li>
    <li>Vibrant natural coloration</li>
    <li>Sold in proper school size for natural behavior</li>
    <li>Perfect for beginners and experts alike</li>
    <li>Captive-bred for hardiness</li>
</ul>

<h4>Tank Requirements:</h4>
<ul>
    <li><strong>Minimum Tank:</strong> 20 gallons for school of 6</li>
    <li><strong>Temperature:</strong> 70-81°F (21-27°C)</li>
    <li><strong>pH:</strong> 6.0-7.0</li>
    <li><strong>Water:</strong> Soft to moderately hard</li>
</ul>

<h4>Compatibility:</h4>
<p>Excellent community fish that do well with other peaceful species. Avoid keeping with large or aggressive fish that might see them as food.</p>
            ',
            'led_light' => '
<h3>Premium LED Aquarium Light System</h3>
<p>Our flagship LED lighting system combines cutting-edge technology with aquarist-friendly features. Perfect for planted tanks and general aquarium illumination.</p>

<h4>Advanced Features:</h4>
<ul>
    <li>Full spectrum LED technology (6500K-10000K)</li>
    <li>Programmable sunrise/sunset simulation</li>
    <li>Wireless smartphone app control</li>
    <li>Weather effect simulation (clouds, storms)</li>
    <li>Energy efficient - uses 75% less power than T5</li>
    <li>50,000+ hour lifespan</li>
</ul>

<h4>Technical Specifications:</h4>
<ul>
    <li><strong>Length:</strong> 48 inches</li>
    <li><strong>Power:</strong> 180W actual draw</li>
    <li><strong>PAR:</strong> 150+ at 18" depth</li>
    <li><strong>Mounting:</strong> Adjustable legs included</li>
    <li><strong>Warranty:</strong> 3 years manufacturer warranty</li>
</ul>

<h4>Perfect For:</h4>
<p>Planted aquariums, reef tanks, and any setup requiring high-quality, controllable lighting. The natural shimmer effects create stunning visual appeal.</p>
            ',
            'canister_filter' => '
<h3>Canister Filter Pro 400</h3>
<p>Professional-grade canister filtration for serious aquarists. This 4-stage filtration system provides superior water quality for tanks up to 75 gallons.</p>

<h4>Filtration Stages:</h4>
<ol>
    <li><strong>Mechanical:</strong> Coarse and fine filter pads</li>
    <li><strong>Biological:</strong> High-capacity bio-media</li>
    <li><strong>Chemical:</strong> Activated carbon compartment</li>
    <li><strong>Polish:</strong> Final polishing pad for crystal-clear water</li>
</ol>

<h4>Key Benefits:</h4>
<ul>
    <li>Silent operation - under 35dB</li>
    <li>Self-priming pump system</li>
    <li>Tool-free media access</li>
    <li>Leak-proof quick-disconnect valves</li>
    <li>Energy efficient motor</li>
</ul>

<h4>Specifications:</h4>
<ul>
    <li><strong>Flow Rate:</strong> 400 GPH maximum</li>
    <li><strong>Tank Capacity:</strong> Up to 75 gallons</li>
    <li><strong>Power:</strong> 18W energy consumption</li>
    <li><strong>Dimensions:</strong> 13" x 11" x 16"</li>
</ul>
            ',
            'plant_bundle' => '
<h3>Aquascaping Plant Bundle - Beginner</h3>
<p>Perfect introduction to planted aquariums. This carefully curated selection includes easy-to-grow species that will thrive in most aquarium conditions.</p>

<h4>Included Species:</h4>
<ul>
    <li><strong>Amazon Sword (2 plants):</strong> Classic background plant</li>
    <li><strong>Java Fern (3 plants):</strong> Low-light tolerant</li>
    <li><strong>Anubias Nana (2 plants):</strong> Slow-growing, hardy</li>
    <li><strong>Vallisneria (5 stems):</strong> Fast-growing background</li>
    <li><strong>Java Moss (1 portion):</strong> Carpeting/decoration</li>
</ul>

<h4>Care Requirements:</h4>
<ul>
    <li><strong>Lighting:</strong> Low to moderate (basic LED sufficient)</li>
    <li><strong>CO2:</strong> Not required (but beneficial)</li>
    <li><strong>Fertilizer:</strong> Root tabs recommended</li>
    <li><strong>Substrate:</strong> Any aquarium gravel or sand</li>
</ul>

<h4>Benefits:</h4>
<p>Live plants improve water quality, provide natural filtration, and create a healthy environment for fish while adding natural beauty to your aquarium.</p>
            ',
            'clownfish' => '
<h3>Marine Clownfish Pair (Captive Bred)</h3>
<p>These beautiful captive-bred clownfish are perfect for marine aquariums. Hardy, colorful, and with fascinating behavior, they\'re ideal for both beginners and experienced marine aquarists.</p>

<h4>Captive-Bred Advantages:</h4>
<ul>
    <li>Adapted to aquarium conditions</li>
    <li>Disease resistant and hardy</li>
    <li>No impact on wild reef populations</li>
    <li>Already acclimated to prepared foods</li>
    <li>Better survival rates in home aquariums</li>
</ul>

<h4>Marine Requirements:</h4>
<ul>
    <li><strong>Tank Size:</strong> Minimum 20 gallons for pair</li>
    <li><strong>Salinity:</strong> 1.020-1.025 specific gravity</li>
    <li><strong>Temperature:</strong> 72-78°F (22-26°C)</li>
    <li><strong>pH:</strong> 8.1-8.4</li>
</ul>

<h4>Behavior:</h4>
<p>Clownfish are known for their symbiotic relationship with anemones, though this is not required in aquariums. They exhibit interesting social behaviors and are generally peaceful with other marine fish.</p>
            ',
            'heater' => '
<h3>Adjustable Aquarium Heater</h3>
<p>Reliable temperature control is essential for tropical fish health. Our heaters feature precise thermostatic controls and safety features for worry-free operation.</p>

<h4>Safety Features:</h4>
<ul>
    <li>Automatic shut-off if removed from water</li>
    <li>Shatter-resistant borosilicate glass</li>
    <li>Thermal cut-off protection</li>
    <li>Double-wall construction</li>
    <li>Suction cup mounting system</li>
</ul>

<h4>Performance:</h4>
<ul>
    <li>±1°F temperature accuracy</li>
    <li>Fully submersible design</li>
    <li>Quick heating response</li>
    <li>Suitable for fresh and saltwater</li>
    <li>Energy efficient operation</li>
</ul>

<p>Available in multiple wattages to match your tank size. Choose the appropriate size for efficient heating and energy savings.</p>
            ',
            'rock_bundle' => '
<h3>Natural Aquascaping Rock Collection</h3>
<p>Create stunning underwater landscapes with our hand-selected natural rocks. Perfect for both aesthetic appeal and beneficial biological functions.</p>

<h4>Rock Types Included:</h4>
<ul>
    <li><strong>Dragon Stone:</strong> Intricate textures and holes</li>
    <li><strong>Seiryu Stone:</strong> Blue-gray coloration with white veining</li>
    <li><strong>Lava Rock:</strong> Porous surface for beneficial bacteria</li>
</ul>

<h4>Benefits:</h4>
<ul>
    <li>Natural biological filtration</li>
    <li>pH buffering properties</li>
    <li>Fish hiding places and territory markers</li>
    <li>Professional aquascaping appearance</li>
    <li>Completely aquarium safe</li>
</ul>

<h4>Aquascaping Tips:</h4>
<p>Follow the rule of thirds and create depth with varying rock sizes. Ensure stable placement and allow for fish swimming space. Rocks can be secured with aquarium-safe adhesive if needed.</p>
            ',
            'test_kit_bundle' => '
<h3>Complete Water Testing Solution</h3>
<p>Monitor all essential water parameters with this comprehensive testing bundle. Regular testing is the key to maintaining a healthy aquarium environment.</p>

<h4>Why Test Water Parameters:</h4>
<ul>
    <li><strong>Fish Health:</strong> Poor water quality is the #1 cause of fish problems</li>
    <li><strong>Early Detection:</strong> Catch problems before they become serious</li>
    <li><strong>Cycle Monitoring:</strong> Track nitrogen cycle progress</li>
    <li><strong>Maintenance Planning:</strong> Know when water changes are needed</li>
</ul>

<h4>Testing Schedule:</h4>
<ul>
    <li><strong>New Tanks:</strong> Daily during cycling period</li>
    <li><strong>Established Tanks:</strong> Weekly routine testing</li>
    <li><strong>Problem Diagnosis:</strong> As needed for troubleshooting</li>
</ul>

<h4>Bundle Savings:</h4>
<p>Save 15% compared to purchasing individual test kits. Each kit includes detailed instructions and color charts for accurate results.</p>
            ',
        );

        return isset( $descriptions[ $product_type ] ) ? $descriptions[ $product_type ] : '<p>High-quality aquarium product from AquaLuxe.</p>';
    }

    /**
     * Create simple product
     *
     * @param array $product_data Product data
     * @return int|false Product ID on success, false on failure
     */
    private function create_simple_product( $product_data ) {
        $existing_product = get_page_by_title( $product_data['name'], OBJECT, 'product' );
        if ( $existing_product ) {
            return $existing_product->ID;
        }

        $product = new WC_Product_Simple();
        $product->set_name( $product_data['name'] );
        $product->set_description( $product_data['description'] );
        $product->set_short_description( $product_data['short_description'] );
        $product->set_regular_price( $product_data['price'] );
        
        if ( isset( $product_data['sale_price'] ) ) {
            $product->set_sale_price( $product_data['sale_price'] );
        }
        
        if ( isset( $product_data['manage_stock'] ) && $product_data['manage_stock'] ) {
            $product->set_manage_stock( true );
            $product->set_stock_quantity( $product_data['stock_quantity'] ?? 10 );
        }
        
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        
        if ( isset( $product_data['featured'] ) && $product_data['featured'] ) {
            $product->set_featured( true );
        }

        $product_id = $product->save();

        if ( $product_id ) {
            // Set category
            if ( isset( $product_data['category'] ) ) {
                $category_term = get_term_by( 'slug', $product_data['category'], 'product_cat' );
                if ( $category_term ) {
                    wp_set_object_terms( $product_id, $category_term->term_id, 'product_cat' );
                }
            }

            // Set attributes
            if ( isset( $product_data['attributes'] ) ) {
                $this->set_product_attributes( $product_id, $product_data['attributes'] );
            }

            // Set product image
            $this->set_product_image( $product_id, $product_data['category'] ?? 'equipment' );
        }

        return $product_id;
    }

    /**
     * Create variable product
     *
     * @param array $product_data Product data
     * @return int|false Product ID on success, false on failure
     */
    private function create_variable_product( $product_data ) {
        $existing_product = get_page_by_title( $product_data['name'], OBJECT, 'product' );
        if ( $existing_product ) {
            return $existing_product->ID;
        }

        $product = new WC_Product_Variable();
        $product->set_name( $product_data['name'] );
        $product->set_description( $product_data['description'] );
        $product->set_short_description( $product_data['short_description'] );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );

        $product_id = $product->save();

        if ( $product_id ) {
            // Set category
            if ( isset( $product_data['category'] ) ) {
                $category_term = get_term_by( 'slug', $product_data['category'], 'product_cat' );
                if ( $category_term ) {
                    wp_set_object_terms( $product_id, $category_term->term_id, 'product_cat' );
                }
            }

            // Create variations
            if ( isset( $product_data['variations'] ) ) {
                $this->create_product_variations( $product_id, $product_data['variations'] );
            }

            // Set product image
            $this->set_product_image( $product_id, $product_data['category'] ?? 'equipment' );
        }

        return $product_id;
    }

    /**
     * Create grouped product
     *
     * @param array $product_data Product data
     * @return int|false Product ID on success, false on failure
     */
    private function create_grouped_product( $product_data ) {
        $existing_product = get_page_by_title( $product_data['name'], OBJECT, 'product' );
        if ( $existing_product ) {
            return $existing_product->ID;
        }

        $product = new WC_Product_Grouped();
        $product->set_name( $product_data['name'] );
        $product->set_description( $product_data['description'] );
        $product->set_short_description( $product_data['short_description'] );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        
        if ( isset( $product_data['featured'] ) && $product_data['featured'] ) {
            $product->set_featured( true );
        }

        if ( isset( $product_data['grouped_products'] ) ) {
            $product->set_children( $product_data['grouped_products'] );
        }

        $product_id = $product->save();

        if ( $product_id ) {
            // Set category
            if ( isset( $product_data['category'] ) ) {
                $category_term = get_term_by( 'slug', $product_data['category'], 'product_cat' );
                if ( $category_term ) {
                    wp_set_object_terms( $product_id, $category_term->term_id, 'product_cat' );
                }
            }

            // Set product image
            $this->set_product_image( $product_id, $product_data['category'] ?? 'equipment' );
        }

        return $product_id;
    }

    /**
     * Set product attributes
     *
     * @param int   $product_id Product ID
     * @param array $attributes Attributes array
     */
    private function set_product_attributes( $product_id, $attributes ) {
        $product_attributes = array();

        foreach ( $attributes as $attribute_name => $attribute_value ) {
            $taxonomy = wc_attribute_taxonomy_name( $attribute_name );
            
            if ( taxonomy_exists( $taxonomy ) ) {
                $term = get_term_by( 'name', $attribute_value, $taxonomy );
                if ( $term ) {
                    $product_attributes[ $taxonomy ] = array(
                        'name'         => $taxonomy,
                        'value'        => array( $term->term_id ),
                        'is_visible'   => 1,
                        'is_variation' => 0,
                        'is_taxonomy'  => 1,
                    );
                    
                    wp_set_object_terms( $product_id, $term->term_id, $taxonomy );
                }
            }
        }

        update_post_meta( $product_id, '_product_attributes', $product_attributes );
    }

    /**
     * Create product variations
     *
     * @param int   $product_id Product ID
     * @param array $variations Variations data
     */
    private function create_product_variations( $product_id, $variations ) {
        $product = wc_get_product( $product_id );
        $variation_attributes = array();

        // First, collect all attributes used in variations
        foreach ( $variations as $variation_data ) {
            foreach ( $variation_data['attributes'] as $attr_name => $attr_value ) {
                $taxonomy = wc_attribute_taxonomy_name( $attr_name );
                if ( ! isset( $variation_attributes[ $taxonomy ] ) ) {
                    $variation_attributes[ $taxonomy ] = array();
                }
                $variation_attributes[ $taxonomy ][] = $attr_value;
            }
        }

        // Set product attributes for variations
        $product_attributes = array();
        foreach ( $variation_attributes as $taxonomy => $values ) {
            $values = array_unique( $values );
            $term_ids = array();
            
            foreach ( $values as $value ) {
                $term = get_term_by( 'name', $value, $taxonomy );
                if ( $term ) {
                    $term_ids[] = $term->term_id;
                    wp_set_object_terms( $product_id, $term->term_id, $taxonomy, true );
                }
            }
            
            $product_attributes[ $taxonomy ] = array(
                'name'         => $taxonomy,
                'value'        => $term_ids,
                'is_visible'   => 1,
                'is_variation' => 1,
                'is_taxonomy'  => 1,
            );
        }

        update_post_meta( $product_id, '_product_attributes', $product_attributes );

        // Create individual variations
        foreach ( $variations as $variation_data ) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id( $product_id );
            $variation->set_regular_price( $variation_data['price'] );
            $variation->set_status( 'publish' );
            
            if ( isset( $variation_data['sku'] ) ) {
                $variation->set_sku( $variation_data['sku'] );
            }
            
            if ( isset( $variation_data['stock'] ) ) {
                $variation->set_manage_stock( true );
                $variation->set_stock_quantity( $variation_data['stock'] );
            }

            // Set variation attributes
            $variation_attributes_formatted = array();
            foreach ( $variation_data['attributes'] as $attr_name => $attr_value ) {
                $taxonomy = wc_attribute_taxonomy_name( $attr_name );
                $term = get_term_by( 'name', $attr_value, $taxonomy );
                if ( $term ) {
                    $variation_attributes_formatted[ $taxonomy ] = $term->slug;
                }
            }
            $variation->set_attributes( $variation_attributes_formatted );

            $variation->save();
        }

        // Sync the product to update price ranges
        WC_Product_Variable::sync( $product_id );
    }

    /**
     * Set product image
     *
     * @param int    $product_id Product ID
     * @param string $category   Product category for image selection
     */
    private function set_product_image( $product_id, $category ) {
        $image_map = array(
            'tropical-fish' => 'tropical-fish-aquarium.jpg',
            'marine-fish'   => 'coral-reef-aquarium.jpg',
            'aquatic-plants' => 'aquascaping-plants.jpg',
            'equipment'     => 'aquarium-equipment.jpg',
            'lighting'      => 'aquarium-equipment.jpg',
            'filtration'    => 'aquarium-equipment.jpg',
            'care-supplies' => 'aquarium-maintenance.jpg',
        );

        $image_filename = $image_map[ $category ] ?? 'aquarium-equipment.jpg';
        $attachment = $this->get_attachment_by_filename( $image_filename );

        if ( $attachment ) {
            set_post_thumbnail( $product_id, $attachment->ID );
        }
    }

    /**
     * Setup shop page
     */
    private function setup_shop_page() {
        $shop_page = get_page_by_title( 'Shop' );
        if ( $shop_page ) {
            update_option( 'woocommerce_shop_page_id', $shop_page->ID );
        }

        // Set other WooCommerce pages
        $cart_page = get_page_by_title( 'Cart' );
        $checkout_page = get_page_by_title( 'Checkout' );
        $account_page = get_page_by_title( 'My Account' );

        if ( ! $cart_page ) {
            $cart_page_id = wp_insert_post( array(
                'post_title'   => 'Cart',
                'post_content' => '[woocommerce_cart]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
            update_option( 'woocommerce_cart_page_id', $cart_page_id );
        }

        if ( ! $checkout_page ) {
            $checkout_page_id = wp_insert_post( array(
                'post_title'   => 'Checkout',
                'post_content' => '[woocommerce_checkout]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
            update_option( 'woocommerce_checkout_page_id', $checkout_page_id );
        }

        if ( ! $account_page ) {
            $account_page_id = wp_insert_post( array(
                'post_title'   => 'My Account',
                'post_content' => '[woocommerce_my_account]',
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
            update_option( 'woocommerce_myaccount_page_id', $account_page_id );
        }
    }

    /**
     * Get homepage content
     *
     * @return string
     */
    private function get_homepage_content() {
        return '
<!-- Hero Section -->
<section class="hero-section bg-gradient-to-br from-blue-50 to-cyan-50 py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold text-gray-900 mb-6">Bringing Elegance to Aquatic Life</h1>
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">Discover premium aquatic solutions, rare species, and expert care services for hobbyists and professionals worldwide.</p>
        <a href="#featured-products" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">Explore Our Collection</a>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured-products" class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Featured Products</h2>
        [aqualuxe_featured_products limit="6"]
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Our Services</h2>
        [aqualuxe_services limit="3"]
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">What Our Customers Say</h2>
        [aqualuxe_testimonials limit="3"]
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Stay Updated</h2>
        <p class="text-lg mb-8">Get the latest news, tips, and exclusive offers delivered to your inbox.</p>
        [aqualuxe_newsletter_form]
    </div>
</section>
        ';
    }

    /**
     * Get about page content
     *
     * @return string
     */
    private function get_about_content() {
        return '
<div class="prose max-w-none">
    <h2>About AquaLuxe</h2>
    
    <p>Welcome to AquaLuxe, where elegance meets aquatic life. Since our founding, we have been passionate about bringing you the finest selection of aquatic species, premium equipment, and expert services to create stunning underwater environments.</p>
    
    <h3>Our Mission</h3>
    <p>At AquaLuxe, our mission is to bring elegance to aquatic life globally. We believe that every aquarium should be a masterpiece, combining the beauty of nature with the finest craftsmanship and care.</p>
    
    <h3>Our Values</h3>
    <ul>
        <li><strong>Quality:</strong> We source only the healthiest fish and highest-quality equipment</li>
        <li><strong>Expertise:</strong> Our team brings decades of experience in aquatic care</li>
        <li><strong>Sustainability:</strong> We promote responsible aquaculture and environmental stewardship</li>
        <li><strong>Innovation:</strong> We continuously explore new technologies and techniques</li>
        <li><strong>Customer Focus:</strong> Your success and satisfaction are our top priorities</li>
    </ul>
    
    <h3>Our Story</h3>
    <p>Founded by marine biology enthusiasts, AquaLuxe began as a small local aquarium shop with a vision to transform how people experience aquatic life. Today, we serve customers worldwide, from hobbyists creating their first aquarium to professional aquarists designing large-scale installations.</p>
    
    <h3>Sustainability Focus</h3>
    <p>We are committed to sustainable practices that protect our oceans and freshwater ecosystems. Our breeding programs reduce pressure on wild populations, and we partner with conservation organizations to support aquatic habitat preservation.</p>
    
    <h3>Our Team</h3>
    <p>Our expert team includes marine biologists, aquaculture specialists, and aquascaping artists who are passionate about sharing their knowledge and helping you succeed with your aquatic projects.</p>
</div>
        ';
    }

    /**
     * Get services page content
     *
     * @return string
     */
    private function get_services_content() {
        return '
<div class="prose max-w-none">
    <h2>Our Services</h2>
    <p>AquaLuxe offers comprehensive aquatic solutions for hobbyists, professionals, and commercial clients worldwide.</p>
    
    [aqualuxe_services_grid]
    
    <h3>Why Choose AquaLuxe Services?</h3>
    <ul>
        <li>Expert consultation and planning</li>
        <li>Professional installation and setup</li>
        <li>Ongoing maintenance and support</li>
        <li>Emergency response services</li>
        <li>Custom design solutions</li>
        <li>Educational workshops and training</li>
    </ul>
    
    <h3>Service Areas</h3>
    <p>We provide services locally and internationally, with specialized shipping and support for remote installations.</p>
</div>
        ';
    }

    /**
     * Get shop page content
     *
     * @return string
     */
    private function get_shop_content() {
        return '
<div class="prose max-w-none mb-8">
    <h2>Premium Aquatic Collection</h2>
    <p>Explore our carefully curated selection of rare fish species, aquatic plants, premium equipment, and care supplies.</p>
</div>

<!-- Product Categories -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
    <div class="text-center">
        <h3 class="font-semibold mb-2">Tropical Fish</h3>
        <p class="text-sm text-gray-600">Rare and exotic species</p>
    </div>
    <div class="text-center">
        <h3 class="font-semibold mb-2">Aquatic Plants</h3>
        <p class="text-sm text-gray-600">Living aquascaping elements</p>
    </div>
    <div class="text-center">
        <h3 class="font-semibold mb-2">Premium Equipment</h3>
        <p class="text-sm text-gray-600">Professional-grade systems</p>
    </div>
    <div class="text-center">
        <h3 class="font-semibold mb-2">Care Supplies</h3>
        <p class="text-sm text-gray-600">Everything for maintenance</p>
    </div>
</div>
        ';
    }

    /**
     * Get contact page content
     *
     * @return string
     */
    private function get_contact_content() {
        return '
<div class="prose max-w-none">
    <h2>Get in Touch</h2>
    <p>We would love to hear from you. Contact us today for all your aquatic needs, from consultation to custom installations.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 not-prose">
        <div>
            <h3 class="text-xl font-semibold mb-4">Contact Information</h3>
            <div class="space-y-3">
                <div>
                    <strong>Phone:</strong> +1 (555) 123-4567
                </div>
                <div>
                    <strong>Email:</strong> info@aqualuxe.com
                </div>
                <div>
                    <strong>Address:</strong> 123 Aquatic Avenue<br>Ocean City, CA 90210
                </div>
                <div>
                    <strong>Business Hours:</strong><br>
                    Monday - Friday: 9:00 AM - 6:00 PM<br>
                    Saturday: 10:00 AM - 4:00 PM<br>
                    Sunday: Closed
                </div>
            </div>
        </div>
        
        <div>
            <h3 class="text-xl font-semibold mb-4">Send us a Message</h3>
            [aqualuxe_contact_form]
        </div>
    </div>
    
    <h3>Visit Our Showroom</h3>
    <p>Experience our aquatic displays in person at our state-of-the-art showroom and aquatic center.</p>
    
    [aqualuxe_google_map]
</div>
        ';
    }

    /**
     * Get blog page content
     *
     * @return string
     */
    private function get_blog_content() {
        return '
<div class="prose max-w-none">
    <h2>AquaLuxe Blog</h2>
    <p>Discover expert tips, aquascaping inspiration, species profiles, and industry insights from our team of aquatic specialists.</p>
    
    <h3>Featured Topics</h3>
    <ul>
        <li>Aquarium Setup and Maintenance</li>
        <li>Aquascaping Techniques</li>
        <li>Species Care Guides</li>
        <li>Equipment Reviews</li>
        <li>Industry News and Trends</li>
        <li>Conservation and Sustainability</li>
    </ul>
</div>
        ';
    }

    /**
     * Get FAQ page content
     *
     * @return string
     */
    private function get_faq_content() {
        return '
<div class="prose max-w-none">
    <h2>Frequently Asked Questions</h2>
    
    <h3>Ordering & Shipping</h3>
    <h4>How do you ship live fish?</h4>
    <p>We use specialized packaging with temperature control and oxygen systems to ensure safe arrival of all livestock. All shipments are tracked and insured.</p>
    
    <h4>What are your shipping costs?</h4>
    <p>Shipping costs vary by location and order size. Live fish shipments require overnight delivery. We offer free shipping on orders over $200.</p>
    
    <h4>Do you ship internationally?</h4>
    <p>Yes, we ship worldwide with proper documentation and permits. International shipping costs and times vary by destination.</p>
    
    <h3>Care & Maintenance</h3>
    <h4>How often should I clean my aquarium?</h4>
    <p>We recommend weekly water changes of 20-25% and monthly deep cleaning. Our maintenance services can handle this for you.</p>
    
    <h4>What if my fish get sick?</h4>
    <p>Contact us immediately for diagnosis and treatment advice. We offer emergency consultation services and can recommend appropriate medications.</p>
    
    <h3>Equipment & Setup</h3>
    <h4>Do you provide installation services?</h4>
    <p>Yes, we offer full installation and setup services for aquarium systems of all sizes, from home aquariums to commercial installations.</p>
    
    <h4>What equipment do I need for a new aquarium?</h4>
    <p>Essential equipment includes filtration, lighting, heating, and water testing supplies. Our experts can recommend the perfect setup for your needs.</p>
    
    <h3>Returns & Warranties</h3>
    <h4>What is your return policy?</h4>
    <p>We offer a 30-day return policy on equipment and a live arrival guarantee on all livestock. See our full policy for details.</p>
    
    <h4>Do you warranty your equipment?</h4>
    <p>All equipment comes with manufacturer warranties. We also offer extended warranty options for premium products.</p>
</div>
        ';
    }

    /**
     * Get privacy policy content
     *
     * @return string
     */
    private function get_privacy_content() {
        return '
<div class="prose max-w-none">
    <h2>Privacy Policy</h2>
    <p><em>Last updated: ' . date('F j, Y') . '</em></p>
    
    <h3>Information We Collect</h3>
    <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
    
    <h3>How We Use Your Information</h3>
    <p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>
    
    <h3>Information Sharing</h3>
    <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
    
    <h3>Data Security</h3>
    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
    
    <h3>Contact Us</h3>
    <p>If you have any questions about this Privacy Policy, please contact us at privacy@aqualuxe.com.</p>
</div>
        ';
    }

    /**
     * Get terms and conditions content
     *
     * @return string
     */
    private function get_terms_content() {
        return '
<div class="prose max-w-none">
    <h2>Terms & Conditions</h2>
    <p><em>Last updated: ' . date('F j, Y') . '</em></p>
    
    <h3>Acceptance of Terms</h3>
    <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
    
    <h3>Products and Services</h3>
    <p>All products and services are subject to availability. We reserve the right to discontinue any product or service without notice.</p>
    
    <h3>Pricing and Payment</h3>
    <p>All prices are subject to change without notice. Payment is due in full at the time of order placement.</p>
    
    <h3>Shipping and Delivery</h3>
    <p>Shipping costs and delivery times vary by location and product type. Live livestock requires special handling and shipping methods.</p>
    
    <h3>Returns and Refunds</h3>
    <p>Returns must be authorized in advance and comply with our return policy. Refunds will be processed within 5-7 business days.</p>
    
    <h3>Limitation of Liability</h3>
    <p>AquaLuxe shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use of our products or services.</p>
</div>
        ';
    }

    /**
     * Get shipping and returns content
     *
     * @return string
     */
    private function get_shipping_content() {
        return '
<div class="prose max-w-none">
    <h2>Shipping & Returns</h2>
    
    <h3>Shipping Methods</h3>
    <ul>
        <li><strong>Standard Shipping:</strong> 5-7 business days for equipment and supplies</li>
        <li><strong>Express Shipping:</strong> 2-3 business days for urgent orders</li>
        <li><strong>Overnight Shipping:</strong> Required for all live livestock</li>
        <li><strong>International Shipping:</strong> Varies by destination, includes customs documentation</li>
    </ul>
    
    <h3>Shipping Costs</h3>
    <ul>
        <li>Free shipping on orders over $200 (domestic)</li>
        <li>Live fish shipping: $45-75 depending on box size</li>
        <li>International shipping calculated at checkout</li>
    </ul>
    
    <h3>Live Animal Shipping</h3>
    <p>All livestock is shipped via overnight delivery with specialized packaging including:</p>
    <ul>
        <li>Insulated shipping containers</li>
        <li>Oxygen injection systems</li>
        <li>Temperature control materials</li>
        <li>Protective cushioning</li>
    </ul>
    
    <h3>Return Policy</h3>
    <ul>
        <li><strong>Equipment:</strong> 30-day return window for unused items</li>
        <li><strong>Live Animals:</strong> Live arrival guarantee - replacements for DOA</li>
        <li><strong>Plants:</strong> 7-day guarantee from delivery date</li>
        <li><strong>Custom Orders:</strong> Non-returnable unless defective</li>
    </ul>
    
    <h3>How to Return Items</h3>
    <ol>
        <li>Contact customer service within the return window</li>
        <li>Receive return authorization number</li>
        <li>Package items securely in original packaging</li>
        <li>Include return authorization number on package</li>
        <li>Ship to our returns center</li>
    </ol>
    
    <h3>Refund Processing</h3>
    <p>Approved returns are processed within 5-7 business days. Refunds are issued to the original payment method.</p>
</div>
        ';
    }

    /**
     * Set featured image for page based on title
     *
     * @param int    $page_id    Page ID
     * @param string $page_title Page title
     */
    private function set_page_featured_image( $page_id, $page_title ) {
        // Map page titles to appropriate images
        $image_map = array(
            'About Us' => 'aquascaping-plants.jpg',
            'Services' => 'aquarium-maintenance.jpg',
            'Contact'  => 'coral-reef-aquarium.jpg',
            'Shop'     => 'tropical-fish-aquarium.jpg',
        );
        
        if ( isset( $image_map[ $page_title ] ) ) {
            $image_filename = $image_map[ $page_title ];
            $attachment = $this->get_attachment_by_filename( $image_filename );
            
            if ( $attachment ) {
                set_post_thumbnail( $page_id, $attachment->ID );
            }
        }
    }

    /**
     * Create export file
     *
     * @param array $options Export options
     * @return string|false Export file URL on success, false on failure
     */
    private function create_export_file( $options ) {
        $export_data = array(
            'version'    => AQUALUXE_VERSION,
            'timestamp'  => current_time( 'mysql' ),
            'site_url'   => get_site_url(),
            'data'       => array(),
        );

        if ( $options['posts'] ) {
            $export_data['data']['posts'] = $this->export_posts_and_pages();
        }

        if ( $options['products'] ) {
            $export_data['data']['products'] = $this->export_products();
        }

        if ( $options['media'] ) {
            $export_data['data']['media'] = $this->export_media();
        }

        if ( $options['customizer'] ) {
            $export_data['data']['customizer'] = $this->export_customizer_settings();
        }

        // Create export file
        $upload_dir = wp_upload_dir();
        $export_dir = $upload_dir['basedir'] . '/aqualuxe-exports';
        
        if ( ! file_exists( $export_dir ) ) {
            wp_mkdir_p( $export_dir );
        }

        $filename = 'aqualuxe-export-' . date( 'Y-m-d-H-i-s' ) . '.json';
        $file_path = $export_dir . '/' . $filename;

        if ( file_put_contents( $file_path, wp_json_encode( $export_data, JSON_PRETTY_PRINT ) ) ) {
            return $upload_dir['baseurl'] . '/aqualuxe-exports/' . $filename;
        }

        return false;
    }

    /**
     * Export posts and pages
     *
     * @return array
     */
    private function export_posts_and_pages() {
        $posts = get_posts( array(
            'post_type'      => array( 'post', 'page', 'service', 'event', 'testimonial' ),
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );

        $export_posts = array();
        foreach ( $posts as $post ) {
            $export_posts[] = array(
                'title'         => $post->post_title,
                'content'       => $post->post_content,
                'excerpt'       => $post->post_excerpt,
                'status'        => $post->post_status,
                'type'          => $post->post_type,
                'date'          => $post->post_date,
                'slug'          => $post->post_name,
                'meta'          => get_post_meta( $post->ID ),
                'categories'    => wp_get_post_categories( $post->ID ),
                'tags'          => wp_get_post_tags( $post->ID ),
            );
        }

        return $export_posts;
    }

    /**
     * Export products
     *
     * @return array
     */
    private function export_products() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return array();
        }

        $products = get_posts( array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );

        $export_products = array();
        foreach ( $products as $product_post ) {
            $product = wc_get_product( $product_post->ID );
            if ( $product ) {
                $export_products[] = array(
                    'name'              => $product->get_name(),
                    'description'       => $product->get_description(),
                    'short_description' => $product->get_short_description(),
                    'type'              => $product->get_type(),
                    'price'             => $product->get_regular_price(),
                    'sale_price'        => $product->get_sale_price(),
                    'stock_quantity'    => $product->get_stock_quantity(),
                    'manage_stock'      => $product->get_manage_stock(),
                    'categories'        => wp_get_post_terms( $product_post->ID, 'product_cat' ),
                    'attributes'        => $product->get_attributes(),
                    'meta'              => get_post_meta( $product_post->ID ),
                );
            }
        }

        return $export_products;
    }

    /**
     * Export media
     *
     * @return array
     */
    private function export_media() {
        $attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        ) );

        $export_media = array();
        foreach ( $attachments as $attachment ) {
            $export_media[] = array(
                'title'       => $attachment->post_title,
                'description' => $attachment->post_content,
                'caption'     => $attachment->post_excerpt,
                'alt_text'    => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                'file_url'    => wp_get_attachment_url( $attachment->ID ),
                'mime_type'   => $attachment->post_mime_type,
                'meta'        => get_post_meta( $attachment->ID ),
            );
        }

        return $export_media;
    }

    /**
     * Export customizer settings
     *
     * @return array
     */
    private function export_customizer_settings() {
        $theme_mods = get_theme_mods();
        $custom_css = wp_get_custom_css();

        return array(
            'theme_mods' => $theme_mods,
            'custom_css' => $custom_css,
        );
    }

    /**
     * Validate system for import
     *
     * @return array
     */
    private function validate_system() {
        $results = array();

        // PHP Version
        $php_version = phpversion();
        $results['php_version'] = array(
            'label'  => __( 'PHP Version', 'aqualuxe' ),
            'value'  => $php_version,
            'status' => version_compare( $php_version, '7.4', '>=' ) ? 'pass' : 'warning',
            'message' => version_compare( $php_version, '7.4', '>=' ) ? 
                __( 'PHP version is compatible', 'aqualuxe' ) : 
                __( 'PHP 7.4 or higher recommended', 'aqualuxe' ),
        );

        // WordPress Version
        $wp_version = get_bloginfo( 'version' );
        $results['wp_version'] = array(
            'label'  => __( 'WordPress Version', 'aqualuxe' ),
            'value'  => $wp_version,
            'status' => version_compare( $wp_version, '5.0', '>=' ) ? 'pass' : 'fail',
            'message' => version_compare( $wp_version, '5.0', '>=' ) ? 
                __( 'WordPress version is compatible', 'aqualuxe' ) : 
                __( 'WordPress 5.0 or higher required', 'aqualuxe' ),
        );

        // Memory Limit
        $memory_limit = wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) );
        $results['memory_limit'] = array(
            'label'  => __( 'Memory Limit', 'aqualuxe' ),
            'value'  => size_format( $memory_limit ),
            'status' => $memory_limit >= 134217728 ? 'pass' : 'warning', // 128MB
            'message' => $memory_limit >= 134217728 ? 
                __( 'Memory limit is sufficient', 'aqualuxe' ) : 
                __( '128MB or higher recommended', 'aqualuxe' ),
        );

        // Upload Directory
        $upload_dir = wp_upload_dir();
        $results['upload_writable'] = array(
            'label'  => __( 'Upload Directory', 'aqualuxe' ),
            'value'  => $upload_dir['error'] ? $upload_dir['error'] : __( 'Writable', 'aqualuxe' ),
            'status' => ! $upload_dir['error'] ? 'pass' : 'fail',
            'message' => ! $upload_dir['error'] ? 
                __( 'Upload directory is writable', 'aqualuxe' ) : 
                __( 'Upload directory is not writable', 'aqualuxe' ),
        );

        // WooCommerce
        $results['woocommerce'] = array(
            'label'  => __( 'WooCommerce', 'aqualuxe' ),
            'value'  => class_exists( 'WooCommerce' ) ? 
                WC()->version : __( 'Not installed', 'aqualuxe' ),
            'status' => class_exists( 'WooCommerce' ) ? 'pass' : 'info',
            'message' => class_exists( 'WooCommerce' ) ? 
                __( 'WooCommerce is active - product import available', 'aqualuxe' ) : 
                __( 'WooCommerce not active - products will be skipped', 'aqualuxe' ),
        );

        // CURL
        $results['curl'] = array(
            'label'  => __( 'CURL Extension', 'aqualuxe' ),
            'value'  => extension_loaded( 'curl' ) ? __( 'Available', 'aqualuxe' ) : __( 'Not available', 'aqualuxe' ),
            'status' => extension_loaded( 'curl' ) ? 'pass' : 'warning',
            'message' => extension_loaded( 'curl' ) ? 
                __( 'CURL is available for media import', 'aqualuxe' ) : 
                __( 'CURL not available - may affect media import', 'aqualuxe' ),
        );

        // Max Execution Time
        $max_execution_time = ini_get( 'max_execution_time' );
        $results['execution_time'] = array(
            'label'  => __( 'Max Execution Time', 'aqualuxe' ),
            'value'  => $max_execution_time ? $max_execution_time . 's' : __( 'Unlimited', 'aqualuxe' ),
            'status' => ( ! $max_execution_time || $max_execution_time >= 60 ) ? 'pass' : 'warning',
            'message' => ( ! $max_execution_time || $max_execution_time >= 60 ) ? 
                __( 'Execution time is sufficient', 'aqualuxe' ) : 
                __( '60 seconds or higher recommended', 'aqualuxe' ),
        );

        return $results;
    }
}