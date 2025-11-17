<?php
/**
 * Demo Importer Module Bootstrap
 *
 * @package AquaLuxe
 * @subpackage Modules\DemoImporter
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Demo Importer Module Class
 */
class AquaLuxe_Demo_Importer {
    
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;
    
    /**
     * Initialize the module.
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'wp_ajax_aqualuxe_import_demo', array( $this, 'ajax_import_demo' ) );
        add_action( 'wp_ajax_aqualuxe_reset_content', array( $this, 'ajax_reset_content' ) );
        add_action( 'wp_ajax_aqualuxe_import_progress', array( $this, 'ajax_import_progress' ) );
    }
    
    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_theme_page(
            __( 'Demo Content Importer', 'aqualuxe' ),
            __( 'Demo Importer', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-demo-importer',
            array( $this, 'demo_importer_page' )
        );
    }
    
    /**
     * Enqueue admin scripts.
     *
     * @param string $hook_suffix Current admin page.
     */
    public function enqueue_admin_scripts( $hook_suffix ) {
        if ( 'appearance_page_aqualuxe-demo-importer' !== $hook_suffix ) {
            return;
        }
        
        // Get mix manifest for cache busting
        $mix_manifest = $this->get_mix_manifest();
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            $this->get_asset_url( 'js/modules/demo-importer.js', $mix_manifest ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script( 'aqualuxe-demo-importer', 'aqualuxe_demo_importer', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_demo_importer_nonce' ),
            'strings'  => array(
                'importing'        => esc_html__( 'Importing...', 'aqualuxe' ),
                'import_complete'  => esc_html__( 'Import Complete!', 'aqualuxe' ),
                'import_failed'    => esc_html__( 'Import Failed', 'aqualuxe' ),
                'confirm_import'   => esc_html__( 'Are you sure you want to import demo content? This will replace existing content.', 'aqualuxe' ),
                'confirm_reset'    => esc_html__( 'Are you sure you want to reset all content? This action cannot be undone.', 'aqualuxe' ),
            ),
        ) );
        
        wp_enqueue_style(
            'aqualuxe-demo-importer',
            $this->get_asset_url( 'css/admin.css', $mix_manifest ),
            array(),
            AQUALUXE_VERSION
        );
    }
    
    /**
     * Demo importer page.
     */
    public function demo_importer_page() {
        $demos = $this->get_available_demos();
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e( 'AquaLuxe Demo Content Importer', 'aqualuxe' ); ?></h1>
            
            <div class="demo-importer-notices"></div>
            
            <div class="demo-importer-intro">
                <p><?php esc_html_e( 'Import demo content to quickly set up your site with sample pages, posts, images, and settings.', 'aqualuxe' ); ?></p>
                <div class="notice notice-info">
                    <p><strong><?php esc_html_e( 'Important:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'Importing demo content will replace your existing content. Please backup your site before proceeding.', 'aqualuxe' ); ?></p>
                </div>
            </div>
            
            <div class="demo-options">
                <h2><?php esc_html_e( 'Available Demos', 'aqualuxe' ); ?></h2>
                
                <div class="demos-grid">
                    <?php foreach ( $demos as $demo_id => $demo ) : ?>
                        <div class="demo-item">
                            <div class="demo-preview">
                                <?php if ( ! empty( $demo['preview_image'] ) ) : ?>
                                    <img src="<?php echo esc_url( $demo['preview_image'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>" />
                                <?php endif; ?>
                            </div>
                            
                            <div class="demo-info">
                                <h3><?php echo esc_html( $demo['name'] ); ?></h3>
                                <p><?php echo esc_html( $demo['description'] ); ?></p>
                                
                                <div class="demo-actions">
                                    <button class="button button-primary demo-import-btn" 
                                            data-demo-id="<?php echo esc_attr( $demo_id ); ?>">
                                        <?php esc_html_e( 'Import Demo', 'aqualuxe' ); ?>
                                    </button>
                                    
                                    <?php if ( ! empty( $demo['preview_url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $demo['preview_url'] ); ?>" 
                                           class="button" 
                                           target="_blank">
                                            <?php esc_html_e( 'Preview', 'aqualuxe' ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="import-options">
                <h2><?php esc_html_e( 'Import Options', 'aqualuxe' ); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Content', 'aqualuxe' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="import_content" value="1" checked>
                                <?php esc_html_e( 'Import posts, pages, and media', 'aqualuxe' ); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Customizer', 'aqualuxe' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="import_customizer" value="1" checked>
                                <?php esc_html_e( 'Import theme customizer settings', 'aqualuxe' ); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Widgets', 'aqualuxe' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="import_widgets" value="1" checked>
                                <?php esc_html_e( 'Import widget settings', 'aqualuxe' ); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Options', 'aqualuxe' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="import_options" value="1" checked>
                                <?php esc_html_e( 'Import theme options and settings', 'aqualuxe' ); ?>
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="reset-section">
                <h2><?php esc_html_e( 'Reset Content', 'aqualuxe' ); ?></h2>
                <p><?php esc_html_e( 'Reset all content and settings to default state.', 'aqualuxe' ); ?></p>
                <button class="button button-secondary demo-reset-btn">
                    <?php esc_html_e( 'Reset All Content', 'aqualuxe' ); ?>
                </button>
            </div>
            
            <div class="demo-importer-progress" style="display: none;">
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%;"></div>
                    </div>
                    <div class="progress-text"><?php esc_html_e( 'Initializing...', 'aqualuxe' ); ?></div>
                    <div class="progress-log"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get available demo configurations.
     *
     * @return array
     */
    private function get_available_demos() {
        return array(
            'default' => array(
                'name'          => __( 'Default Demo', 'aqualuxe' ),
                'description'   => __( 'Complete aquatic commerce website with all features', 'aqualuxe' ),
                'preview_image' => AQUALUXE_THEME_URI . '/assets/src/images/demos/default-preview.jpg',
                'preview_url'   => 'https://demo.aqualuxe.com',
                'files'         => array(
                    'content'    => $this->get_demo_dir() . '/default/content.xml',
                    'widgets'    => $this->get_demo_dir() . '/default/widgets.wie',
                    'customizer' => $this->get_demo_dir() . '/default/customizer.dat',
                    'options'    => $this->get_demo_dir() . '/default/options.json',
                ),
            ),
            'minimal' => array(
                'name'          => __( 'Minimal Demo', 'aqualuxe' ),
                'description'   => __( 'Clean and minimal setup for aquatic businesses', 'aqualuxe' ),
                'preview_image' => AQUALUXE_THEME_URI . '/assets/src/images/demos/minimal-preview.jpg',
                'preview_url'   => 'https://demo.aqualuxe.com/minimal',
                'files'         => array(
                    'content'    => $this->get_demo_dir() . '/minimal/content.xml',
                    'widgets'    => $this->get_demo_dir() . '/minimal/widgets.wie',
                    'customizer' => $this->get_demo_dir() . '/minimal/customizer.dat',
                    'options'    => $this->get_demo_dir() . '/minimal/options.json',
                ),
            ),
            'shop' => array(
                'name'          => __( 'Shop Demo', 'aqualuxe' ),
                'description'   => __( 'Full WooCommerce shop with aquatic products', 'aqualuxe' ),
                'preview_image' => AQUALUXE_THEME_URI . '/assets/src/images/demos/shop-preview.jpg',
                'preview_url'   => 'https://demo.aqualuxe.com/shop',
                'files'         => array(
                    'content'    => $this->get_demo_dir() . '/shop/content.xml',
                    'widgets'    => $this->get_demo_dir() . '/shop/widgets.wie',
                    'customizer' => $this->get_demo_dir() . '/shop/customizer.dat',
                    'options'    => $this->get_demo_dir() . '/shop/options.json',
                ),
            ),
        );
    }
    
    /**
     * Get demo directory path.
     *
     * @return string
     */
    private function get_demo_dir() {
        return AQUALUXE_THEME_DIR . '/modules/demo-importer/data';
    }
    
    /**
     * AJAX handler for demo import.
     */
    public function ajax_import_demo() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'aqualuxe' ) );
        }
        
        $demo_id = sanitize_text_field( $_POST['demo_id'] );
        $options = $this->sanitize_import_options( $_POST['options'] ?? array() );
        
        // Set import progress
        set_transient( 'aqualuxe_import_progress', array(
            'percentage' => 0,
            'message'    => __( 'Starting import...', 'aqualuxe' ),
        ), 300 );
        
        $result = $this->import_demo( $demo_id, $options );
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array(
                'message' => $result->get_error_message(),
            ) );
        }
        
        // Clear import progress
        delete_transient( 'aqualuxe_import_progress' );
        
        wp_send_json_success( array(
            'message'  => __( 'Demo imported successfully!', 'aqualuxe' ),
            'redirect' => home_url(),
        ) );
    }
    
    /**
     * AJAX handler for content reset.
     */
    public function ajax_reset_content() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'aqualuxe' ) );
        }
        
        $result = $this->reset_content();
        
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( array(
                'message' => $result->get_error_message(),
            ) );
        }
        
        wp_send_json_success( array(
            'message' => __( 'Content reset successfully!', 'aqualuxe' ),
        ) );
    }
    
    /**
     * AJAX handler for import progress.
     */
    public function ajax_import_progress() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'aqualuxe_demo_importer_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'aqualuxe' ) );
        }
        
        $progress = get_transient( 'aqualuxe_import_progress' );
        
        if ( ! $progress ) {
            wp_send_json_error( array(
                'message' => __( 'No import in progress.', 'aqualuxe' ),
            ) );
        }
        
        wp_send_json_success( $progress );
    }
    
    /**
     * Import demo content.
     *
     * @param string $demo_id Demo ID.
     * @param array  $options Import options.
     * @return true|WP_Error
     */
    private function import_demo( $demo_id, $options = array() ) {
        $demos = $this->get_available_demos();
        
        if ( ! isset( $demos[ $demo_id ] ) ) {
            return new WP_Error( 'invalid_demo', __( 'Invalid demo ID.', 'aqualuxe' ) );
        }
        
        $demo = $demos[ $demo_id ];
        
        // Import content
        if ( ! empty( $options['content'] ) && isset( $demo['files']['content'] ) ) {
            $this->update_progress( 20, __( 'Importing content...', 'aqualuxe' ) );
            $result = $this->import_content( $demo['files']['content'] );
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }
        
        // Import widgets
        if ( ! empty( $options['widgets'] ) && isset( $demo['files']['widgets'] ) ) {
            $this->update_progress( 50, __( 'Importing widgets...', 'aqualuxe' ) );
            $result = $this->import_widgets( $demo['files']['widgets'] );
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }
        
        // Import customizer
        if ( ! empty( $options['customizer'] ) && isset( $demo['files']['customizer'] ) ) {
            $this->update_progress( 70, __( 'Importing customizer settings...', 'aqualuxe' ) );
            $result = $this->import_customizer( $demo['files']['customizer'] );
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }
        
        // Import options
        if ( ! empty( $options['options'] ) && isset( $demo['files']['options'] ) ) {
            $this->update_progress( 90, __( 'Importing theme options...', 'aqualuxe' ) );
            $result = $this->import_options( $demo['files']['options'] );
            if ( is_wp_error( $result ) ) {
                return $result;
            }
        }
        
        // Finalize import
        $this->update_progress( 100, __( 'Import complete!', 'aqualuxe' ) );
        $this->finalize_import();
        
        return true;
    }
    
    /**
     * Import WordPress content from XML file.
     *
     * @param string $file_path Path to XML file.
     * @return true|WP_Error
     */
    private function import_content( $file_path ) {
        if ( ! file_exists( $file_path ) ) {
            return new WP_Error( 'file_not_found', __( 'Content file not found.', 'aqualuxe' ) );
        }
        
        // Include WordPress importer
        if ( ! class_exists( 'WP_Importer' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        }
        
        if ( ! class_exists( 'WXR_Importer' ) ) {
            // Generate sample content if XML file doesn't exist
            $this->generate_sample_content();
            return true;
        }
        
        // TODO: Implement actual XML import using WXR_Importer
        // For now, generate sample content
        $this->generate_sample_content();
        
        return true;
    }
    
    /**
     * Generate sample content.
     */
    private function generate_sample_content() {
        // Create sample pages
        $pages = array(
            'Home' => array(
                'content' => $this->get_home_page_content(),
                'template' => 'front-page.php',
            ),
            'About' => array(
                'content' => $this->get_about_page_content(),
            ),
            'Services' => array(
                'content' => $this->get_services_page_content(),
            ),
            'Contact' => array(
                'content' => $this->get_contact_page_content(),
            ),
            'Blog' => array(
                'content' => '',
            ),
        );
        
        foreach ( $pages as $title => $page_data ) {
            $existing = get_page_by_title( $title );
            if ( ! $existing ) {
                wp_insert_post( array(
                    'post_title'   => $title,
                    'post_content' => $page_data['content'],
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'meta_input'   => array(
                        '_wp_page_template' => $page_data['template'] ?? '',
                    ),
                ) );
            }
        }
        
        // Create sample posts
        $posts = array(
            'Welcome to AquaLuxe',
            'Getting Started with Aquascaping',
            'Choosing the Right Fish for Your Aquarium',
            'Aquarium Maintenance Tips',
            'The Benefits of Live Plants',
        );
        
        foreach ( $posts as $title ) {
            $existing = get_page_by_title( $title, OBJECT, 'post' );
            if ( ! $existing ) {
                wp_insert_post( array(
                    'post_title'   => $title,
                    'post_content' => $this->get_sample_post_content( $title ),
                    'post_status'  => 'publish',
                    'post_type'    => 'post',
                    'post_excerpt' => 'This is a sample excerpt for the blog post.',
                ) );
            }
        }
        
        // Set front page
        $front_page = get_page_by_title( 'Home' );
        $blog_page = get_page_by_title( 'Blog' );
        
        if ( $front_page ) {
            update_option( 'show_on_front', 'page' );
            update_option( 'page_on_front', $front_page->ID );
        }
        
        if ( $blog_page ) {
            update_option( 'page_for_posts', $blog_page->ID );
        }
    }
    
    /**
     * Get home page content.
     *
     * @return string
     */
    private function get_home_page_content() {
        return '<!-- wp:group {"className":"hero-section"} -->
<div class="wp-block-group hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Bringing Elegance to Aquatic Life</h1>
        <p class="hero-subtitle">Discover premium aquatic solutions for enthusiasts worldwide</p>
        <div class="hero-cta">
            <a href="/shop" class="btn btn-primary btn-lg">Shop Now</a>
            <a href="/about" class="btn btn-outline btn-lg">Learn More</a>
        </div>
    </div>
</div>
<!-- /wp:group -->';
    }
    
    /**
     * Get about page content.
     *
     * @return string
     */
    private function get_about_page_content() {
        return '<h1>About AquaLuxe</h1>
        <p>AquaLuxe is dedicated to bringing elegance and innovation to aquatic life worldwide. We specialize in premium aquatic solutions for enthusiasts, collectors, and professionals.</p>
        <h2>Our Mission</h2>
        <p>To provide the highest quality aquatic products and services while promoting sustainable and ethical practices in the aquarium industry.</p>';
    }
    
    /**
     * Get services page content.
     *
     * @return string
     */
    private function get_services_page_content() {
        return '<h1>Our Services</h1>
        <p>We offer a comprehensive range of aquatic services to meet all your needs:</p>
        <ul>
            <li>Custom Aquarium Design</li>
            <li>Professional Installation</li>
            <li>Maintenance Services</li>
            <li>Consultation and Training</li>
            <li>Emergency Support</li>
        </ul>';
    }
    
    /**
     * Get contact page content.
     *
     * @return string
     */
    private function get_contact_page_content() {
        return '<h1>Contact Us</h1>
        <p>Get in touch with our team of aquatic specialists.</p>
        <div class="contact-info">
            <p><strong>Email:</strong> info@aqualuxe.com</p>
            <p><strong>Phone:</strong> +1 (555) 123-4567</p>
            <p><strong>Address:</strong> 123 Aquatic Street, Ocean City, AC 12345</p>
        </div>';
    }
    
    /**
     * Get sample post content.
     *
     * @param string $title Post title.
     * @return string
     */
    private function get_sample_post_content( $title ) {
        return "<h1>{$title}</h1>
        <p>This is a sample blog post about aquatic life and aquarium maintenance. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
        <h2>Key Points</h2>
        <ul>
            <li>Water quality management</li>
            <li>Fish health monitoring</li>
            <li>Equipment maintenance</li>
            <li>Feeding schedules</li>
        </ul>";
    }
    
    /**
     * Import widgets.
     *
     * @param string $file_path Path to widgets file.
     * @return true|WP_Error
     */
    private function import_widgets( $file_path ) {
        // Generate sample widgets
        $this->generate_sample_widgets();
        return true;
    }
    
    /**
     * Generate sample widgets.
     */
    private function generate_sample_widgets() {
        // Sample widget data
        $sidebar_widgets = array(
            'sidebar-1' => array( 'search-2', 'recent-posts-2', 'categories-2' ),
            'footer-1' => array( 'text-2' ),
            'footer-2' => array( 'nav_menu-2' ),
            'footer-3' => array( 'text-3' ),
            'footer-4' => array( 'text-4' ),
        );
        
        $widget_instances = array(
            'search' => array(
                2 => array( 'title' => 'Search' ),
            ),
            'recent-posts' => array(
                2 => array( 'title' => 'Recent Posts', 'number' => 5 ),
            ),
            'categories' => array(
                2 => array( 'title' => 'Categories' ),
            ),
            'text' => array(
                2 => array(
                    'title' => 'About AquaLuxe',
                    'text'  => 'Premium aquatic solutions for enthusiasts worldwide.',
                ),
                3 => array(
                    'title' => 'Contact Info',
                    'text'  => 'Email: info@aqualuxe.com<br>Phone: +1 (555) 123-4567',
                ),
                4 => array(
                    'title' => 'Follow Us',
                    'text'  => 'Stay connected with AquaLuxe on social media.',
                ),
            ),
        );
        
        update_option( 'sidebars_widgets', $sidebar_widgets );
        
        foreach ( $widget_instances as $widget_type => $instances ) {
            update_option( "widget_{$widget_type}", $instances );
        }
    }
    
    /**
     * Import customizer settings.
     *
     * @param string $file_path Path to customizer file.
     * @return true|WP_Error
     */
    private function import_customizer( $file_path ) {
        // Set default customizer values
        $customizer_settings = array(
            'aqualuxe_primary_color'   => '#0077b6',
            'aqualuxe_secondary_color' => '#00b4d8',
            'aqualuxe_accent_color'    => '#90e0ef',
        );
        
        foreach ( $customizer_settings as $setting => $value ) {
            set_theme_mod( $setting, $value );
        }
        
        return true;
    }
    
    /**
     * Import theme options.
     *
     * @param string $file_path Path to options file.
     * @return true|WP_Error
     */
    private function import_options( $file_path ) {
        // Set default theme options
        $options = array(
            'aqualuxe_enable_top_bar'     => true,
            'aqualuxe_enable_breadcrumbs' => true,
            'aqualuxe_phone'              => '+1 (555) 123-4567',
            'aqualuxe_email'              => 'info@aqualuxe.com',
        );
        
        foreach ( $options as $option => $value ) {
            update_option( $option, $value );
        }
        
        return true;
    }
    
    /**
     * Reset all content.
     *
     * @return true|WP_Error
     */
    private function reset_content() {
        global $wpdb;
        
        // Delete posts and pages
        $posts = get_posts( array(
            'numberposts' => -1,
            'post_type'   => array( 'post', 'page' ),
            'post_status' => 'any',
        ) );
        
        foreach ( $posts as $post ) {
            wp_delete_post( $post->ID, true );
        }
        
        // Reset widgets
        update_option( 'sidebars_widgets', array() );
        
        // Reset customizer
        remove_theme_mods();
        
        // Reset theme options
        $options = array(
            'aqualuxe_enable_top_bar',
            'aqualuxe_enable_breadcrumbs',
            'aqualuxe_phone',
            'aqualuxe_email',
        );
        
        foreach ( $options as $option ) {
            delete_option( $option );
        }
        
        return true;
    }
    
    /**
     * Update import progress.
     *
     * @param int    $percentage Progress percentage.
     * @param string $message Progress message.
     */
    private function update_progress( $percentage, $message ) {
        set_transient( 'aqualuxe_import_progress', array(
            'percentage' => $percentage,
            'message'    => $message,
        ), 300 );
    }
    
    /**
     * Finalize import process.
     */
    private function finalize_import() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Clear caches
        if ( function_exists( 'wp_cache_flush' ) ) {
            wp_cache_flush();
        }
    }
    
    /**
     * Sanitize import options.
     *
     * @param array $options Raw options.
     * @return array
     */
    private function sanitize_import_options( $options ) {
        $sanitized = array();
        $allowed_options = array( 'content', 'widgets', 'customizer', 'options' );
        
        foreach ( $allowed_options as $option ) {
            $sanitized[ $option ] = ! empty( $options[ $option ] );
        }
        
        return $sanitized;
    }
    
    /**
     * Get mix manifest for asset versioning.
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . '/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            return json_decode( file_get_contents( $manifest_path ), true );
        }
        
        return array();
    }
    
    /**
     * Get asset URL with proper versioning.
     *
     * @param string $asset
     * @param array  $manifest
     * @return string
     */
    private function get_asset_url( $asset, $manifest = array() ) {
        $asset_path = '/' . $asset;
        
        if ( isset( $manifest[ $asset_path ] ) ) {
            return AQUALUXE_ASSETS_URI . '/dist' . $manifest[ $asset_path ];
        }
        
        return AQUALUXE_ASSETS_URI . '/dist' . $asset_path;
    }
}

// Initialize demo importer module
AquaLuxe_Demo_Importer::get_instance();

/**
 * Helper function to get demo importer instance.
 *
 * @return AquaLuxe_Demo_Importer
 */
function aqualuxe_demo_importer() {
    return AquaLuxe_Demo_Importer::get_instance();
}