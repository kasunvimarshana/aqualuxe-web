<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * This script helps import the demo content for the AquaLuxe WordPress theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

    /**
     * The single instance of the class.
     *
     * @var AquaLuxe_Demo_Importer
     */
    protected static $_instance = null;

    /**
     * Main AquaLuxe_Demo_Importer Instance.
     *
     * Ensures only one instance of AquaLuxe_Demo_Importer is loaded or can be loaded.
     *
     * @return AquaLuxe_Demo_Importer - Main instance.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Add admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_import_content', array($this, 'ajax_import_content'));
        add_action('wp_ajax_aqualuxe_import_customizer', array($this, 'ajax_import_customizer'));
        add_action('wp_ajax_aqualuxe_import_widgets', array($this, 'ajax_import_widgets'));
        add_action('wp_ajax_aqualuxe_import_woocommerce', array($this, 'ajax_import_woocommerce'));
        add_action('wp_ajax_aqualuxe_import_products', array($this, 'ajax_import_products'));
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __('AquaLuxe Demo Import', 'aqualuxe'),
            __('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Enqueue admin scripts.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_scripts($hook) {
        if ('appearance_page_aqualuxe-demo-import' !== $hook) {
            return;
        }

        wp_enqueue_style('aqualuxe-demo-import', get_template_directory_uri() . '/assets/css/admin/demo-import.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-demo-import', get_template_directory_uri() . '/assets/js/admin/demo-import.js', array('jquery'), AQUALUXE_VERSION, true);
        
        wp_localize_script('aqualuxe-demo-import', 'aqualuxeDemoImport', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-demo-import-nonce'),
            'i18n' => array(
                'importing' => __('Importing...', 'aqualuxe'),
                'imported' => __('Imported!', 'aqualuxe'),
                'error' => __('Error!', 'aqualuxe'),
                'confirmImport' => __('Are you sure you want to import the demo content? This will overwrite your current settings.', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Render admin page.
     */
    public function render_admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-import">
            <h1><?php esc_html_e('AquaLuxe Demo Content Import', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-import-notice notice notice-warning">
                <p><strong><?php esc_html_e('Important:', 'aqualuxe'); ?></strong> <?php esc_html_e('Importing demo content will overwrite your current theme settings, widgets, and may add new pages or posts. It is recommended to use this on a fresh WordPress installation.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-demo-import-grid">
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('WordPress Content', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import pages, posts, menus, and other WordPress content.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-button" data-import-type="content"><?php esc_html_e('Import Content', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('Customizer Settings', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import theme customizer settings including colors, fonts, and layout options.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-button" data-import-type="customizer"><?php esc_html_e('Import Customizer', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('Widgets', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import widget settings for sidebars, footer, and product filters.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-button" data-import-type="widgets"><?php esc_html_e('Import Widgets', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('WooCommerce Settings', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import WooCommerce settings including store details, product display, and checkout options.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-button" data-import-type="woocommerce"><?php esc_html_e('Import WooCommerce', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('Products', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import sample products with categories, attributes, and images.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-button" data-import-type="products"><?php esc_html_e('Import Products', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-import-card">
                    <div class="aqualuxe-demo-import-card-header">
                        <h2><?php esc_html_e('Import All', 'aqualuxe'); ?></h2>
                    </div>
                    <div class="aqualuxe-demo-import-card-body">
                        <p><?php esc_html_e('Import all demo content and settings at once.', 'aqualuxe'); ?></p>
                    </div>
                    <div class="aqualuxe-demo-import-card-footer">
                        <button class="button button-primary aqualuxe-import-all-button"><?php esc_html_e('Import All', 'aqualuxe'); ?></button>
                        <span class="aqualuxe-import-status"></span>
                    </div>
                </div>
            </div>
            
            <div class="aqualuxe-demo-import-log">
                <h3><?php esc_html_e('Import Log', 'aqualuxe'); ?></h3>
                <div class="aqualuxe-demo-import-log-content"></div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX import content.
     */
    public function ajax_import_content() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-import-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }

        // Import content
        $import_file = AQUALUXE_DIR . 'inc/demo-content/content/aqualuxe-demo-content.xml';
        
        if (!file_exists($import_file)) {
            wp_send_json_error(array('message' => __('Import file not found.', 'aqualuxe')));
        }

        // Load WordPress Importer
        if (!class_exists('WP_Importer')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        }

        if (!class_exists('WP_Import')) {
            require_once AQUALUXE_DIR . 'inc/demo-content/wordpress-importer/wordpress-importer.php';
        }

        $importer = new WP_Import();
        $importer->fetch_attachments = true;

        ob_start();
        $importer->import($import_file);
        $output = ob_get_clean();

        // Set home page
        $home_page = get_page_by_title('Home');
        if ($home_page) {
            update_option('page_on_front', $home_page->ID);
            update_option('show_on_front', 'page');
        }

        // Set blog page
        $blog_page = get_page_by_title('Blog');
        if ($blog_page) {
            update_option('page_for_posts', $blog_page->ID);
        }

        wp_send_json_success(array('message' => __('Content imported successfully!', 'aqualuxe')));
    }

    /**
     * AJAX import customizer.
     */
    public function ajax_import_customizer() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-import-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }

        // Import customizer settings
        $import_file = AQUALUXE_DIR . 'inc/demo-content/settings/customizer.dat';
        
        if (!file_exists($import_file)) {
            wp_send_json_error(array('message' => __('Import file not found.', 'aqualuxe')));
        }

        $data = @unserialize(file_get_contents($import_file));
        
        if (!$data || !isset($data['mods'])) {
            wp_send_json_error(array('message' => __('Invalid customizer data.', 'aqualuxe')));
        }

        // Import customizer settings
        foreach ($data['mods'] as $key => $value) {
            set_theme_mod($key, $value);
        }

        wp_send_json_success(array('message' => __('Customizer settings imported successfully!', 'aqualuxe')));
    }

    /**
     * AJAX import widgets.
     */
    public function ajax_import_widgets() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-import-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }

        // Import widget settings
        $import_file = AQUALUXE_DIR . 'inc/demo-content/settings/widget-settings.json';
        
        if (!file_exists($import_file)) {
            wp_send_json_error(array('message' => __('Import file not found.', 'aqualuxe')));
        }

        $data = json_decode(file_get_contents($import_file), true);
        
        if (!$data) {
            wp_send_json_error(array('message' => __('Invalid widget data.', 'aqualuxe')));
        }

        // Get all sidebars
        $sidebars_widgets = get_option('sidebars_widgets');
        
        // Clear all widgets from sidebars
        foreach ($sidebars_widgets as $sidebar_id => $widgets) {
            if ($sidebar_id !== 'wp_inactive_widgets' && is_array($widgets)) {
                $sidebars_widgets[$sidebar_id] = array();
            }
        }
        
        // Import widgets
        foreach ($data as $sidebar_id => $widgets) {
            if (!isset($sidebars_widgets[$sidebar_id])) {
                continue;
            }
            
            $sidebars_widgets[$sidebar_id] = array();
            
            foreach ($widgets as $widget) {
                $widget_id = $widget['id'];
                $widget_type = $widget['widget'];
                $widget_params = isset($widget['params']) ? $widget['params'] : array();
                
                // Add widget to sidebar
                $sidebars_widgets[$sidebar_id][] = $widget_id;
                
                // Get current widget options
                $widget_options = get_option('widget_' . $widget_type, array());
                
                // Extract widget number
                preg_match('/-([0-9]+)$/', $widget_id, $matches);
                $widget_number = $matches[1];
                
                // Add widget options
                $widget_options[$widget_number] = $widget_params;
                
                // Save widget options
                update_option('widget_' . $widget_type, $widget_options);
            }
        }
        
        // Save sidebars widgets
        update_option('sidebars_widgets', $sidebars_widgets);

        wp_send_json_success(array('message' => __('Widgets imported successfully!', 'aqualuxe')));
    }

    /**
     * AJAX import WooCommerce settings.
     */
    public function ajax_import_woocommerce() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-import-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => __('WooCommerce is not active.', 'aqualuxe')));
        }

        // Import WooCommerce settings
        $import_file = AQUALUXE_DIR . 'inc/demo-content/settings/woocommerce-settings.json';
        
        if (!file_exists($import_file)) {
            wp_send_json_error(array('message' => __('Import file not found.', 'aqualuxe')));
        }

        $data = json_decode(file_get_contents($import_file), true);
        
        if (!$data) {
            wp_send_json_error(array('message' => __('Invalid WooCommerce data.', 'aqualuxe')));
        }

        // Import WooCommerce settings
        foreach ($data as $key => $value) {
            update_option($key, $value);
        }

        wp_send_json_success(array('message' => __('WooCommerce settings imported successfully!', 'aqualuxe')));
    }

    /**
     * AJAX import products.
     */
    public function ajax_import_products() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-import-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }

        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(array('message' => __('WooCommerce is not active.', 'aqualuxe')));
        }

        // Import products
        $import_file = AQUALUXE_DIR . 'inc/demo-content/content/aqualuxe-products.csv';
        
        if (!file_exists($import_file)) {
            wp_send_json_error(array('message' => __('Import file not found.', 'aqualuxe')));
        }

        // Include WooCommerce product importer
        if (!class_exists('WC_Product_CSV_Importer')) {
            include_once WC_ABSPATH . 'includes/import/class-wc-product-csv-importer.php';
        }

        // Set up product importer
        $importer = new WC_Product_CSV_Importer(
            $import_file,
            array(
                'parse_as_variation' => false,
                'update_existing'    => false,
                'use_sku_as_id'      => true,
                'allow_unknown_columns' => true,
                'mapping'            => array(
                    'from' => array(),
                    'to'   => array(),
                ),
            )
        );

        // Run the import
        $result = $importer->import();

        // Check for errors
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success(array('message' => __('Products imported successfully!', 'aqualuxe')));
    }
}

// Initialize the class
AquaLuxe_Demo_Importer::instance();