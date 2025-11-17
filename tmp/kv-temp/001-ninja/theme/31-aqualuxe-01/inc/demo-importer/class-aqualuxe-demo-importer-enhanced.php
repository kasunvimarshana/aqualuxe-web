<?php
/**
 * AquaLuxe Demo Content Importer - Enhanced Version
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Demo Importer Class - Enhanced with better performance and error handling
 */
class AquaLuxe_Demo_Importer {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Demo data configuration.
     *
     * @var array
     */
    protected $demo_config = array();

    /**
     * Required plugins for demo import.
     *
     * @var array
     */
    protected $required_plugins = array();

    /**
     * Import status and logs.
     *
     * @var array
     */
    protected $import_log = array();

    /**
     * Import session ID.
     *
     * @var string
     */
    protected $import_session_id = '';

    /**
     * Cache directory.
     *
     * @var string
     */
    protected $cache_dir = '';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->setup_cache_directory();
        $this->init_hooks();
        $this->load_dependencies();
        $this->setup_demo_config();
    }

    /**
     * Get instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Define constants.
     */
    private function define_constants() {
        define('AQUALUXE_DEMO_DIR', AQUALUXE_DIR . '/inc/demo-importer');
        define('AQUALUXE_DEMO_URI', AQUALUXE_URI . '/inc/demo-importer');
        define('AQUALUXE_DEMO_DATA_DIR', AQUALUXE_DIR . '/demo');
        define('AQUALUXE_DEMO_DATA_URI', AQUALUXE_URI . '/demo');
        define('AQUALUXE_DEMO_CACHE_DIR', WP_CONTENT_DIR . '/cache/aqualuxe-demo-importer');
    }

    /**
     * Setup cache directory.
     */
    private function setup_cache_directory() {
        $this->cache_dir = AQUALUXE_DEMO_CACHE_DIR;
        
        // Create cache directory if it doesn't exist
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
            
            // Create .htaccess file to protect cache directory
            $htaccess_content = "# Disable directory browsing\nOptions -Indexes\n\n# Deny access to all files\n<FilesMatch &quot;.*&quot;>\nOrder Allow,Deny\nDeny from all\n</FilesMatch>";
            file_put_contents($this->cache_dir . '/.htaccess', $htaccess_content);
            
            // Create index.php file for extra security
            file_put_contents($this->cache_dir . '/index.php', '<?php // Silence is golden');
        }
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        // Admin menu and pages
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_import_demo_data', array($this, 'ajax_import_demo_data'));
        add_action('wp_ajax_aqualuxe_check_plugin_status', array($this, 'ajax_check_plugin_status'));
        add_action('wp_ajax_aqualuxe_install_plugin', array($this, 'ajax_install_plugin'));
        add_action('wp_ajax_aqualuxe_activate_plugin', array($this, 'ajax_activate_plugin'));
        add_action('wp_ajax_aqualuxe_import_progress', array($this, 'ajax_import_progress'));
        add_action('wp_ajax_aqualuxe_cancel_import', array($this, 'ajax_cancel_import'));
        add_action('wp_ajax_aqualuxe_clear_import_cache', array($this, 'ajax_clear_import_cache'));
        
        // Import steps
        add_action('aqualuxe_demo_import_content', array($this, 'import_content'), 10, 2);
        add_action('aqualuxe_demo_import_widgets', array($this, 'import_widgets'), 10, 2);
        add_action('aqualuxe_demo_import_customizer', array($this, 'import_customizer'), 10, 2);
        add_action('aqualuxe_demo_import_options', array($this, 'import_options'), 10, 2);
        add_action('aqualuxe_demo_import_settings', array($this, 'import_settings'), 10, 2);
        
        // Cleanup
        add_action('aqualuxe_demo_import_cleanup', array($this, 'cleanup_import'), 10, 2);
        
        // Error handling
        add_action('aqualuxe_demo_import_error', array($this, 'handle_import_error'), 10, 3);
    }

    /**
     * Load dependencies.
     */
    private function load_dependencies() {
        // Include WordPress Importer
        if (!class_exists('WP_Importer')) {
            require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
        }

        // Include WordPress Importer API
        if (!class_exists('WP_Import')) {
            require AQUALUXE_DEMO_DIR . '/includes/wordpress-importer/wordpress-importer.php';
        }

        // Include helper functions
        require_once AQUALUXE_DEMO_DIR . '/includes/class-aqualuxe-demo-importer-helper.php';
        require_once AQUALUXE_DEMO_DIR . '/includes/class-aqualuxe-widget-importer.php';
        require_once AQUALUXE_DEMO_DIR . '/includes/class-aqualuxe-customizer-importer.php';
    }

    /**
     * Setup demo configuration.
     */
    private function setup_demo_config() {
        $this->demo_config = array(
            'main' => array(
                'name' => __('Main Demo', 'aqualuxe'),
                'description' => __('Complete demo with all pages, posts, products, and settings.', 'aqualuxe'),
                'preview_url' => 'https://aqualuxe.example.com',
                'screenshot' => AQUALUXE_DEMO_DATA_URI . '/screenshots/main-demo.jpg',
                'files' => array(
                    'content' => AQUALUXE_DEMO_DATA_DIR . '/content.xml',
                    'widgets' => AQUALUXE_DEMO_DATA_DIR . '/widgets.json',
                    'customizer' => AQUALUXE_DEMO_DATA_DIR . '/customizer.dat',
                    'options' => AQUALUXE_DEMO_DATA_DIR . '/options.json',
                    'sliders' => AQUALUXE_DEMO_DATA_DIR . '/sliders.json',
                ),
                'settings' => array(
                    'homepage' => 'Home',
                    'blog_page' => 'Blog',
                    'shop_page' => 'Shop',
                    'menus' => array(
                        'primary' => 'Primary Menu',
                        'footer' => 'Footer Menu',
                        'social' => 'Social Links Menu',
                        'mobile' => 'Mobile Menu',
                    ),
                ),
                'selective_import' => array(
                    'content' => array(
                        'pages' => true,
                        'posts' => true,
                        'products' => true,
                        'media' => true,
                        'menus' => true,
                    ),
                    'widgets' => true,
                    'customizer' => true,
                    'options' => true,
                ),
            ),
            'minimal' => array(
                'name' => __('Minimal Demo', 'aqualuxe'),
                'description' => __('Basic demo with essential pages and minimal content.', 'aqualuxe'),
                'preview_url' => 'https://aqualuxe-minimal.example.com',
                'screenshot' => AQUALUXE_DEMO_DATA_URI . '/screenshots/minimal-demo.jpg',
                'files' => array(
                    'content' => AQUALUXE_DEMO_DATA_DIR . '/minimal-content.xml',
                    'widgets' => AQUALUXE_DEMO_DATA_DIR . '/minimal-widgets.json',
                    'customizer' => AQUALUXE_DEMO_DATA_DIR . '/minimal-customizer.dat',
                    'options' => AQUALUXE_DEMO_DATA_DIR . '/minimal-options.json',
                ),
                'settings' => array(
                    'homepage' => 'Home',
                    'blog_page' => 'Blog',
                    'menus' => array(
                        'primary' => 'Primary Menu',
                        'footer' => 'Footer Menu',
                    ),
                ),
                'selective_import' => array(
                    'content' => array(
                        'pages' => true,
                        'posts' => true,
                        'products' => false,
                        'media' => true,
                        'menus' => true,
                    ),
                    'widgets' => true,
                    'customizer' => true,
                    'options' => true,
                ),
            ),
            'woocommerce' => array(
                'name' => __('WooCommerce Shop Demo', 'aqualuxe'),
                'description' => __('E-commerce focused demo with products, shop pages, and checkout configuration.', 'aqualuxe'),
                'preview_url' => 'https://aqualuxe-shop.example.com',
                'screenshot' => AQUALUXE_DEMO_DATA_URI . '/screenshots/woocommerce-demo.jpg',
                'files' => array(
                    'content' => AQUALUXE_DEMO_DATA_DIR . '/woocommerce-content.xml',
                    'widgets' => AQUALUXE_DEMO_DATA_DIR . '/woocommerce-widgets.json',
                    'customizer' => AQUALUXE_DEMO_DATA_DIR . '/woocommerce-customizer.dat',
                    'options' => AQUALUXE_DEMO_DATA_DIR . '/woocommerce-options.json',
                ),
                'settings' => array(
                    'homepage' => 'Shop Home',
                    'blog_page' => 'Blog',
                    'shop_page' => 'Shop',
                    'menus' => array(
                        'primary' => 'Shop Menu',
                        'footer' => 'Footer Menu',
                    ),
                ),
                'selective_import' => array(
                    'content' => array(
                        'pages' => true,
                        'posts' => false,
                        'products' => true,
                        'media' => true,
                        'menus' => true,
                    ),
                    'widgets' => true,
                    'customizer' => true,
                    'options' => true,
                ),
            ),
        );

        $this->required_plugins = array(
            'woocommerce' => array(
                'name' => 'WooCommerce',
                'slug' => 'woocommerce',
                'required' => true,
                'version' => '7.0.0',
            ),
            'contact-form-7' => array(
                'name' => 'Contact Form 7',
                'slug' => 'contact-form-7',
                'required' => false,
                'version' => '5.7.0',
            ),
            'wordpress-seo' => array(
                'name' => 'Yoast SEO',
                'slug' => 'wordpress-seo',
                'required' => false,
                'version' => '20.0',
            ),
            'elementor' => array(
                'name' => 'Elementor',
                'slug' => 'elementor',
                'required' => false,
                'version' => '3.12.0',
            ),
        );
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_theme_page(
            __('AquaLuxe Demo Import', 'aqualuxe'),
            __('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            array($this, 'demo_import_page')
        );
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue_scripts($hook) {
        if ('appearance_page_aqualuxe-demo-import' !== $hook) {
            return;
        }

        // Styles
        wp_enqueue_style(
            'aqualuxe-demo-importer',
            AQUALUXE_DEMO_URI . '/assets/css/demo-importer.css',
            array(),
            AQUALUXE_VERSION
        );

        // Scripts
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_DEMO_URI . '/assets/js/demo-importer.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-demo-importer',
            'aqualuxeDemoImporter',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-demo-importer'),
                'importing' => __('Importing...', 'aqualuxe'),
                'imported' => __('Imported', 'aqualuxe'),
                'failed' => __('Failed', 'aqualuxe'),
                'confirmImport' => __('Are you sure you want to import this demo? This will overwrite your current site content.', 'aqualuxe'),
                'installingPlugin' => __('Installing plugin...', 'aqualuxe'),
                'activatingPlugin' => __('Activating plugin...', 'aqualuxe'),
                'pluginInstalled' => __('Plugin installed successfully.', 'aqualuxe'),
                'pluginActivated' => __('Plugin activated successfully.', 'aqualuxe'),
                'pluginFailed' => __('Plugin installation/activation failed.', 'aqualuxe'),
                'cancelImport' => __('Are you sure you want to cancel the import process?', 'aqualuxe'),
                'clearCache' => __('Are you sure you want to clear the import cache?', 'aqualuxe'),
                'selectContent' => __('Select content to import', 'aqualuxe'),
            )
        );
    }

    /**
     * Demo import page.
     */
    public function demo_import_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Import', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-importer-intro">
                <p><?php esc_html_e('Import demo content to make your site look like our demo. This will import posts, pages, images, widgets, menus, and settings.', 'aqualuxe'); ?></p>
                <p class="aqualuxe-demo-importer-warning"><?php esc_html_e('Warning: Demo import will override your current site content. It is recommended to use this on a fresh WordPress installation.', 'aqualuxe'); ?></p>
                
                <div class="aqualuxe-demo-importer-actions">
                    <button class="button aqualuxe-clear-cache"><?php esc_html_e('Clear Import Cache', 'aqualuxe'); ?></button>
                    <span class="aqualuxe-cache-info"><?php echo sprintf(__('Cache directory: %s', 'aqualuxe'), AQUALUXE_DEMO_CACHE_DIR); ?></span>
                </div>
            </div>

            <div class="aqualuxe-demo-importer-plugins">
                <h2><?php esc_html_e('Required Plugins', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('The following plugins are required or recommended for the demo import:', 'aqualuxe'); ?></p>
                
                <ul class="aqualuxe-plugin-list">
                    <?php foreach ($this->required_plugins as $plugin_slug => $plugin) : ?>
                        <li class="aqualuxe-plugin-item" data-plugin="<?php echo esc_attr($plugin_slug); ?>">
                            <span class="aqualuxe-plugin-name"><?php echo esc_html($plugin['name']); ?></span>
                            <span class="aqualuxe-plugin-status"><?php esc_html_e('Checking...', 'aqualuxe'); ?></span>
                            <div class="aqualuxe-plugin-actions"></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="aqualuxe-demo-importer-demos">
                <h2><?php esc_html_e('Available Demos', 'aqualuxe'); ?></h2>
                
                <div class="aqualuxe-demo-list">
                    <?php foreach ($this->demo_config as $demo_id => $demo) : ?>
                        <div class="aqualuxe-demo-item" data-demo-id="<?php echo esc_attr($demo_id); ?>">
                            <div class="aqualuxe-demo-screenshot">
                                <img src="<?php echo esc_url($demo['screenshot']); ?>" alt="<?php echo esc_attr($demo['name']); ?>">
                            </div>
                            <div class="aqualuxe-demo-info">
                                <h3 class="aqualuxe-demo-name"><?php echo esc_html($demo['name']); ?></h3>
                                <p class="aqualuxe-demo-description"><?php echo esc_html($demo['description']); ?></p>
                                <div class="aqualuxe-demo-actions">
                                    <a href="<?php echo esc_url($demo['preview_url']); ?>" class="button" target="_blank"><?php esc_html_e('Preview', 'aqualuxe'); ?></a>
                                    <button class="button button-primary aqualuxe-import-demo" data-demo-id="<?php echo esc_attr($demo_id); ?>"><?php esc_html_e('Import', 'aqualuxe'); ?></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="aqualuxe-demo-importer-modal" style="display: none;">
                <div class="aqualuxe-demo-importer-modal-content">
                    <span class="aqualuxe-demo-importer-modal-close">&times;</span>
                    <h2 class="aqualuxe-demo-importer-modal-title"><?php esc_html_e('Importing Demo Content', 'aqualuxe'); ?></h2>
                    
                    <div class="aqualuxe-demo-importer-modal-selective" style="display: none;">
                        <h3><?php esc_html_e('Select Content to Import', 'aqualuxe'); ?></h3>
                        <form class="aqualuxe-selective-import-form">
                            <div class="aqualuxe-selective-import-content">
                                <h4><?php esc_html_e('Content', 'aqualuxe'); ?></h4>
                                <label>
                                    <input type="checkbox" name="import_pages" checked>
                                    <?php esc_html_e('Pages', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_posts" checked>
                                    <?php esc_html_e('Posts', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_products" checked>
                                    <?php esc_html_e('Products', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_media" checked>
                                    <?php esc_html_e('Media', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_menus" checked>
                                    <?php esc_html_e('Menus', 'aqualuxe'); ?>
                                </label>
                            </div>
                            
                            <div class="aqualuxe-selective-import-options">
                                <h4><?php esc_html_e('Options', 'aqualuxe'); ?></h4>
                                <label>
                                    <input type="checkbox" name="import_widgets" checked>
                                    <?php esc_html_e('Widgets', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_customizer" checked>
                                    <?php esc_html_e('Customizer Settings', 'aqualuxe'); ?>
                                </label>
                                <label>
                                    <input type="checkbox" name="import_options" checked>
                                    <?php esc_html_e('Theme Options', 'aqualuxe'); ?>
                                </label>
                            </div>
                            
                            <div class="aqualuxe-selective-import-actions">
                                <button type="button" class="button button-primary aqualuxe-start-import"><?php esc_html_e('Start Import', 'aqualuxe'); ?></button>
                                <button type="button" class="button aqualuxe-cancel-selective"><?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="aqualuxe-demo-importer-modal-progress">
                        <div class="aqualuxe-demo-importer-progress-bar">
                            <div class="aqualuxe-demo-importer-progress-bar-inner"></div>
                        </div>
                        <div class="aqualuxe-demo-importer-progress-status">
                            <span class="aqualuxe-demo-importer-progress-percentage">0%</span>
                            <span class="aqualuxe-demo-importer-progress-step"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></span>
                        </div>
                        <div class="aqualuxe-demo-importer-progress-actions">
                            <button class="button aqualuxe-cancel-import"><?php esc_html_e('Cancel Import', 'aqualuxe'); ?></button>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-demo-importer-modal-log">
                        <h3><?php esc_html_e('Import Log', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-demo-importer-log-content"></div>
                    </div>
                    
                    <div class="aqualuxe-demo-importer-modal-complete" style="display: none;">
                        <div class="aqualuxe-demo-importer-complete-message">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <h3><?php esc_html_e('Import Completed Successfully!', 'aqualuxe'); ?></h3>
                            <p><?php esc_html_e('Your website has been populated with demo content.', 'aqualuxe'); ?></p>
                        </div>
                        <div class="aqualuxe-demo-importer-complete-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary" target="_blank"><?php esc_html_e('View Your Website', 'aqualuxe'); ?></a>
                            <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button" target="_blank"><?php esc_html_e('Customize Your Site', 'aqualuxe'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX handler for checking plugin status.
     */
    public function ajax_check_plugin_status() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check plugin slug
        if (!isset($_POST['plugin'])) {
            wp_send_json_error(array('message' => __('No plugin specified.', 'aqualuxe')));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin']);

        // Check if plugin exists in our list
        if (!isset($this->required_plugins[$plugin_slug])) {
            wp_send_json_error(array('message' => __('Plugin not found in required plugins list.', 'aqualuxe')));
        }

        $plugin = $this->required_plugins[$plugin_slug];
        $status = $this->get_plugin_status($plugin_slug);

        wp_send_json_success(array(
            'status' => $status,
            'plugin' => $plugin,
        ));
    }

    /**
     * AJAX handler for installing a plugin.
     */
    public function ajax_install_plugin() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check plugin slug
        if (!isset($_POST['plugin'])) {
            wp_send_json_error(array('message' => __('No plugin specified.', 'aqualuxe')));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin']);

        // Check if plugin exists in our list
        if (!isset($this->required_plugins[$plugin_slug])) {
            wp_send_json_error(array('message' => __('Plugin not found in required plugins list.', 'aqualuxe')));
        }

        // Include required files for plugin installation
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php';

        // Get plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => $plugin_slug,
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
        ));

        if (is_wp_error($api)) {
            wp_send_json_error(array('message' => $api->get_error_message()));
        }

        // Install the plugin
        $upgrader = new Plugin_Upgrader(new WP_Ajax_Upgrader_Skin());
        $install = $upgrader->install($api->download_link);

        if (is_wp_error($install)) {
            wp_send_json_error(array('message' => $install->get_error_message()));
        }

        if (false === $install) {
            wp_send_json_error(array('message' => __('Plugin installation failed.', 'aqualuxe')));
        }

        // Get the plugin basename
        $plugin_basename = $upgrader->plugin_info();

        // Activate the plugin
        $activate = activate_plugin($plugin_basename);

        if (is_wp_error($activate)) {
            wp_send_json_error(array('message' => $activate->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Plugin installed and activated successfully.', 'aqualuxe'),
            'status' => 'active',
        ));
    }

    /**
     * AJAX handler for activating a plugin.
     */
    public function ajax_activate_plugin() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check plugin slug
        if (!isset($_POST['plugin'])) {
            wp_send_json_error(array('message' => __('No plugin specified.', 'aqualuxe')));
        }

        $plugin_slug = sanitize_text_field($_POST['plugin']);

        // Check if plugin exists in our list
        if (!isset($this->required_plugins[$plugin_slug])) {
            wp_send_json_error(array('message' => __('Plugin not found in required plugins list.', 'aqualuxe')));
        }

        // Include required files for plugin activation
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Get the plugin basename
        $plugins = get_plugins();
        $plugin_basename = '';

        foreach ($plugins as $basename => $plugin_data) {
            if (strpos($basename, $plugin_slug . '/') === 0 || $basename === $plugin_slug . '.php') {
                $plugin_basename = $basename;
                break;
            }
        }

        if (empty($plugin_basename)) {
            wp_send_json_error(array('message' => __('Plugin not found.', 'aqualuxe')));
        }

        // Activate the plugin
        $activate = activate_plugin($plugin_basename);

        if (is_wp_error($activate)) {
            wp_send_json_error(array('message' => $activate->get_error_message()));
        }

        wp_send_json_success(array(
            'message' => __('Plugin activated successfully.', 'aqualuxe'),
            'status' => 'active',
        ));
    }

    /**
     * AJAX handler for importing demo data.
     */
    public function ajax_import_demo_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check demo ID
        if (!isset($_POST['demo_id'])) {
            wp_send_json_error(array('message' => __('No demo specified.', 'aqualuxe')));
        }

        $demo_id = sanitize_text_field($_POST['demo_id']);

        // Check if demo exists in our list
        if (!isset($this->demo_config[$demo_id])) {
            wp_send_json_error(array('message' => __('Demo not found.', 'aqualuxe')));
        }

        // Get selective import options
        $selective_import = isset($_POST['selective_import']) ? $_POST['selective_import'] : array();

        // Start the import process
        $this->import_log = array();
        $this->import_log[] = __('Starting import process...', 'aqualuxe');

        // Set import session
        $this->import_session_id = md5('aqualuxe_demo_import_' . $demo_id . '_' . time());
        set_transient('aqualuxe_demo_import_session', $this->import_session_id, 3600);
        
        // Store selective import options
        if (!empty($selective_import)) {
            set_transient('aqualuxe_selective_import_' . $this->import_session_id, $selective_import, 3600);
        }
        
        // Set initial progress
        set_transient('aqualuxe_demo_import_progress_' . $this->import_session_id, array(
            'step' => 'prepare',
            'percentage' => 0,
            'log' => $this->import_log,
            'demo_id' => $demo_id,
        ), 3600);

        // Schedule the import steps
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_content', array($demo_id, $this->import_session_id));

        wp_send_json_success(array(
            'message' => __('Import process started.', 'aqualuxe'),
            'session_id' => $this->import_session_id,
        ));
    }

    /**
     * AJAX handler for checking import progress.
     */
    public function ajax_import_progress() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check session ID
        if (!isset($_POST['session_id'])) {
            wp_send_json_error(array('message' => __('No import session specified.', 'aqualuxe')));
        }

        $session_id = sanitize_text_field($_POST['session_id']);
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        if (!$progress) {
            wp_send_json_error(array('message' => __('Import session not found or expired.', 'aqualuxe')));
        }

        wp_send_json_success($progress);
    }

    /**
     * AJAX handler for cancelling import.
     */
    public function ajax_cancel_import() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }

        // Check session ID
        if (!isset($_POST['session_id'])) {
            wp_send_json_error(array('message' => __('No import session specified.', 'aqualuxe')));
        }

        $session_id = sanitize_text_field($_POST['session_id']);
        
        // Clear scheduled events
        $timestamp = wp_next_scheduled('aqualuxe_demo_import_content', array($session_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'aqualuxe_demo_import_content', array($session_id));
        }
        
        $timestamp = wp_next_scheduled('aqualuxe_demo_import_widgets', array($session_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'aqualuxe_demo_import_widgets', array($session_id));
        }
        
        $timestamp = wp_next_scheduled('aqualuxe_demo_import_customizer', array($session_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'aqualuxe_demo_import_customizer', array($session_id));
        }
        
        $timestamp = wp_next_scheduled('aqualuxe_demo_import_options', array($session_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'aqualuxe_demo_import_options', array($session_id));
        }
        
        $timestamp = wp_next_scheduled('aqualuxe_demo_import_settings', array($session_id));
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'aqualuxe_demo_import_settings', array($session_id));
        }
        
        // Delete transients
        delete_transient('aqualuxe_demo_import_session');
        delete_transient('aqualuxe_demo_import_progress_' . $session_id);
        delete_transient('aqualuxe_selective_import_' . $session_id);
        
        wp_send_json_success(array(
            'message' => __('Import cancelled successfully.', 'aqualuxe'),
        ));
    }

    /**
     * AJAX handler for clearing import cache.
     */
    public function ajax_clear_import_cache() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-demo-importer')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
        }
        
        // Clear cache directory
        $this->clear_cache_directory();
        
        wp_send_json_success(array(
            'message' => __('Import cache cleared successfully.', 'aqualuxe'),
        ));
    }

    /**
     * Clear cache directory.
     */
    private function clear_cache_directory() {
        if (!file_exists($this->cache_dir)) {
            return;
        }
        
        $files = glob($this->cache_dir . '/*');
        
        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.htaccess' && basename($file) !== 'index.php') {
                @unlink($file);
            }
        }
    }

    /**
     * Import content.
     */
    public function import_content($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        $selective_import = get_transient('aqualuxe_selective_import_' . $session_id);

        // Update progress
        $progress['step'] = 'content';
        $progress['percentage'] = 10;
        $progress['log'][] = __('Importing content...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        try {
            // Check if content file exists
            if (!isset($demo['files']['content']) || !file_exists($demo['files']['content'])) {
                throw new Exception(__('Content file not found.', 'aqualuxe'));
            }

            // Check if we should skip content import based on selective import
            $skip_content = false;
            if (!empty($selective_import) && isset($selective_import['skip_content']) && $selective_import['skip_content']) {
                $skip_content = true;
            }

            if (!$skip_content) {
                // Import content
                $importer = new WP_Import();
                $importer->fetch_attachments = true;

                // Apply selective import filters if needed
                if (!empty($selective_import)) {
                    // Filter post types to import
                    add_filter('wp_import_post_data_raw', function($post) use ($selective_import) {
                        // Skip pages if not selected
                        if ($post['post_type'] === 'page' && isset($selective_import['import_pages']) && !$selective_import['import_pages']) {
                            return false;
                        }
                        
                        // Skip posts if not selected
                        if ($post['post_type'] === 'post' && isset($selective_import['import_posts']) && !$selective_import['import_posts']) {
                            return false;
                        }
                        
                        // Skip products if not selected
                        if ($post['post_type'] === 'product' && isset($selective_import['import_products']) && !$selective_import['import_products']) {
                            return false;
                        }
                        
                        // Skip attachments if media not selected
                        if ($post['post_type'] === 'attachment' && isset($selective_import['import_media']) && !$selective_import['import_media']) {
                            return false;
                        }
                        
                        return $post;
                    });
                    
                    // Skip menu items if menus not selected
                    if (isset($selective_import['import_menus']) && !$selective_import['import_menus']) {
                        add_filter('wp_import_terms', function($terms) {
                            foreach ($terms as $key => $term) {
                                if ($term['term_taxonomy'] === 'nav_menu') {
                                    unset($terms[$key]);
                                }
                            }
                            return $terms;
                        });
                        
                        add_filter('wp_import_post_data_raw', function($post) {
                            if ($post['post_type'] === 'nav_menu_item') {
                                return false;
                            }
                            return $post;
                        });
                    }
                }

                // Run the importer
                ob_start();
                $importer->import($demo['files']['content']);
                $import_output = ob_get_clean();
                
                // Log any errors
                if (strpos($import_output, 'Error:') !== false) {
                    preg_match_all('/Error: ([^\n]+)/', $import_output, $matches);
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $error) {
                            $progress['log'][] = 'Error: ' . $error;
                        }
                        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
                    }
                }
            } else {
                $progress['log'][] = __('Content import skipped based on selection.', 'aqualuxe');
            }

            // Update progress
            $progress['percentage'] = 30;
            $progress['log'][] = __('Content imported successfully.', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

            // Schedule next step
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_widgets', array($demo_id, $session_id));
            
        } catch (Exception $e) {
            // Handle error
            do_action('aqualuxe_demo_import_error', $session_id, 'content', $e->getMessage());
            
            $progress['log'][] = 'Error: ' . $e->getMessage();
            $progress['percentage'] = 30;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Continue to next step despite error
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_widgets', array($demo_id, $session_id));
        }
    }

    /**
     * Import widgets.
     */
    public function import_widgets($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        $selective_import = get_transient('aqualuxe_selective_import_' . $session_id);

        // Update progress
        $progress['step'] = 'widgets';
        $progress['percentage'] = 40;
        $progress['log'][] = __('Importing widgets...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        try {
            // Check if widgets file exists
            if (!isset($demo['files']['widgets']) || !file_exists($demo['files']['widgets'])) {
                throw new Exception(__('Widgets file not found.', 'aqualuxe'));
            }

            // Check if we should skip widgets import based on selective import
            $skip_widgets = false;
            if (!empty($selective_import) && isset($selective_import['import_widgets']) && !$selective_import['import_widgets']) {
                $skip_widgets = true;
            }

            if (!$skip_widgets) {
                // Import widgets
                $widget_importer = new AquaLuxe_Widget_Importer();
                $widget_result = $widget_importer->import($demo['files']['widgets']);
                
                // Log widget import results
                if (is_wp_error($widget_result)) {
                    $progress['log'][] = 'Widget Error: ' . $widget_result->get_error_message();
                } elseif (!empty($widget_result['success'])) {
                    $progress['log'][] = sprintf(__('Imported %d widgets successfully.', 'aqualuxe'), count($widget_result['success']));
                }
                
                if (!empty($widget_result['errors'])) {
                    foreach ($widget_result['errors'] as $error) {
                        $progress['log'][] = 'Widget Error: ' . $error;
                    }
                }
            } else {
                $progress['log'][] = __('Widget import skipped based on selection.', 'aqualuxe');
            }

            // Update progress
            $progress['percentage'] = 50;
            $progress['log'][] = __('Widgets imported successfully.', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

            // Schedule next step
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_customizer', array($demo_id, $session_id));
            
        } catch (Exception $e) {
            // Handle error
            do_action('aqualuxe_demo_import_error', $session_id, 'widgets', $e->getMessage());
            
            $progress['log'][] = 'Error: ' . $e->getMessage();
            $progress['percentage'] = 50;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Continue to next step despite error
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_customizer', array($demo_id, $session_id));
        }
    }

    /**
     * Import customizer settings.
     */
    public function import_customizer($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        $selective_import = get_transient('aqualuxe_selective_import_' . $session_id);

        // Update progress
        $progress['step'] = 'customizer';
        $progress['percentage'] = 60;
        $progress['log'][] = __('Importing customizer settings...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        try {
            // Check if customizer file exists
            if (!isset($demo['files']['customizer']) || !file_exists($demo['files']['customizer'])) {
                throw new Exception(__('Customizer file not found.', 'aqualuxe'));
            }

            // Check if we should skip customizer import based on selective import
            $skip_customizer = false;
            if (!empty($selective_import) && isset($selective_import['import_customizer']) && !$selective_import['import_customizer']) {
                $skip_customizer = true;
            }

            if (!$skip_customizer) {
                // Import customizer settings
                $customizer_importer = new AquaLuxe_Customizer_Importer();
                $customizer_result = $customizer_importer->import($demo['files']['customizer']);
                
                // Log customizer import results
                if (is_wp_error($customizer_result)) {
                    $progress['log'][] = 'Customizer Error: ' . $customizer_result->get_error_message();
                }
            } else {
                $progress['log'][] = __('Customizer import skipped based on selection.', 'aqualuxe');
            }

            // Update progress
            $progress['percentage'] = 70;
            $progress['log'][] = __('Customizer settings imported successfully.', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

            // Schedule next step
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_options', array($demo_id, $session_id));
            
        } catch (Exception $e) {
            // Handle error
            do_action('aqualuxe_demo_import_error', $session_id, 'customizer', $e->getMessage());
            
            $progress['log'][] = 'Error: ' . $e->getMessage();
            $progress['percentage'] = 70;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Continue to next step despite error
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_options', array($demo_id, $session_id));
        }
    }

    /**
     * Import options.
     */
    public function import_options($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        $selective_import = get_transient('aqualuxe_selective_import_' . $session_id);

        // Update progress
        $progress['step'] = 'options';
        $progress['percentage'] = 80;
        $progress['log'][] = __('Importing options...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        try {
            // Check if options file exists
            if (!isset($demo['files']['options']) || !file_exists($demo['files']['options'])) {
                throw new Exception(__('Options file not found.', 'aqualuxe'));
            }

            // Check if we should skip options import based on selective import
            $skip_options = false;
            if (!empty($selective_import) && isset($selective_import['import_options']) && !$selective_import['import_options']) {
                $skip_options = true;
            }

            if (!$skip_options) {
                // Import options
                $options = json_decode(file_get_contents($demo['files']['options']), true);
                
                if ($options && is_array($options)) {
                    foreach ($options as $option_name => $option_value) {
                        update_option($option_name, $option_value);
                    }
                    $progress['log'][] = sprintf(__('Imported %d theme options.', 'aqualuxe'), count($options));
                } else {
                    $progress['log'][] = __('No valid options found in the options file.', 'aqualuxe');
                }
            } else {
                $progress['log'][] = __('Options import skipped based on selection.', 'aqualuxe');
            }

            // Update progress
            $progress['percentage'] = 90;
            $progress['log'][] = __('Options imported successfully.', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

            // Schedule next step
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_settings', array($demo_id, $session_id));
            
        } catch (Exception $e) {
            // Handle error
            do_action('aqualuxe_demo_import_error', $session_id, 'options', $e->getMessage());
            
            $progress['log'][] = 'Error: ' . $e->getMessage();
            $progress['percentage'] = 90;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Continue to next step despite error
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_settings', array($demo_id, $session_id));
        }
    }

    /**
     * Import settings.
     */
    public function import_settings($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        $selective_import = get_transient('aqualuxe_selective_import_' . $session_id);

        // Update progress
        $progress['step'] = 'settings';
        $progress['percentage'] = 95;
        $progress['log'][] = __('Configuring settings...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        try {
            // Check if we should skip menu settings based on selective import
            $skip_menus = false;
            if (!empty($selective_import) && isset($selective_import['import_menus']) && !$selective_import['import_menus']) {
                $skip_menus = true;
            }

            // Set homepage and blog page
            if (isset($demo['settings']['homepage'])) {
                $homepage = get_page_by_title($demo['settings']['homepage']);
                if ($homepage) {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $homepage->ID);
                    $progress['log'][] = sprintf(__('Set "%s" as homepage.', 'aqualuxe'), $demo['settings']['homepage']);
                }
            }

            if (isset($demo['settings']['blog_page'])) {
                $blog_page = get_page_by_title($demo['settings']['blog_page']);
                if ($blog_page) {
                    update_option('page_for_posts', $blog_page->ID);
                    $progress['log'][] = sprintf(__('Set "%s" as blog page.', 'aqualuxe'), $demo['settings']['blog_page']);
                }
            }

            // Set shop page
            if (isset($demo['settings']['shop_page']) && function_exists('wc_get_page_id')) {
                $shop_page = get_page_by_title($demo['settings']['shop_page']);
                if ($shop_page) {
                    update_option('woocommerce_shop_page_id', $shop_page->ID);
                    $progress['log'][] = sprintf(__('Set "%s" as shop page.', 'aqualuxe'), $demo['settings']['shop_page']);
                }
            }

            // Set menus
            if (!$skip_menus && isset($demo['settings']['menus']) && is_array($demo['settings']['menus'])) {
                $locations = get_theme_mod('nav_menu_locations');
                $menus = wp_get_nav_menus();

                foreach ($demo['settings']['menus'] as $location => $menu_name) {
                    foreach ($menus as $menu) {
                        if ($menu->name === $menu_name) {
                            $locations[$location] = $menu->term_id;
                            $progress['log'][] = sprintf(__('Assigned "%s" menu to %s location.', 'aqualuxe'), $menu_name, $location);
                        }
                    }
                }

                set_theme_mod('nav_menu_locations', $locations);
            } elseif ($skip_menus) {
                $progress['log'][] = __('Menu assignment skipped based on selection.', 'aqualuxe');
            }

            // Update progress
            $progress['step'] = 'complete';
            $progress['percentage'] = 100;
            $progress['log'][] = __('Settings configured successfully.', 'aqualuxe');
            $progress['log'][] = __('Import completed successfully!', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Schedule cleanup
            wp_schedule_single_event(time() + 3600, 'aqualuxe_demo_import_cleanup', array($demo_id, $session_id));
            
        } catch (Exception $e) {
            // Handle error
            do_action('aqualuxe_demo_import_error', $session_id, 'settings', $e->getMessage());
            
            $progress['log'][] = 'Error: ' . $e->getMessage();
            $progress['percentage'] = 100;
            $progress['step'] = 'complete';
            $progress['log'][] = __('Import completed with some errors.', 'aqualuxe');
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            
            // Schedule cleanup
            wp_schedule_single_event(time() + 3600, 'aqualuxe_demo_import_cleanup', array($demo_id, $session_id));
        }
    }

    /**
     * Cleanup import.
     */
    public function cleanup_import($demo_id, $session_id) {
        // Delete transients
        delete_transient('aqualuxe_demo_import_session');
        delete_transient('aqualuxe_demo_import_progress_' . $session_id);
        delete_transient('aqualuxe_selective_import_' . $session_id);
        
        // Clear cache
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }

    /**
     * Handle import error.
     */
    public function handle_import_error($session_id, $step, $error_message) {
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);
        
        if ($progress) {
            $progress['log'][] = sprintf(__('Error during %s import: %s', 'aqualuxe'), $step, $error_message);
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
        }
        
        // Log error to error log
        error_log(sprintf('AquaLuxe Demo Importer Error (%s): %s', $step, $error_message));
    }

    /**
     * Get plugin status.
     */
    private function get_plugin_status($plugin_slug) {
        // Include required files
        require_once ABSPATH . 'wp-admin/includes/plugin.php';

        // Get all plugins
        $all_plugins = get_plugins();

        // Check if plugin is installed
        $installed = false;
        $plugin_basename = '';

        foreach ($all_plugins as $basename => $plugin_data) {
            if (strpos($basename, $plugin_slug . '/') === 0 || $basename === $plugin_slug . '.php') {
                $installed = true;
                $plugin_basename = $basename;
                break;
            }
        }

        // Check if plugin is active
        if ($installed) {
            if (is_plugin_active($plugin_basename)) {
                return 'active';
            } else {
                return 'inactive';
            }
        }

        return 'not-installed';
    }
}

// Initialize the demo importer
add_action('after_setup_theme', function() {
    AquaLuxe_Demo_Importer::get_instance();
});