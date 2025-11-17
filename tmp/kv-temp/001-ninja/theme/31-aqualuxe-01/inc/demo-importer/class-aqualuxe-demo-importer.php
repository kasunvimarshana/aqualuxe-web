<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * @package AquaLuxe
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
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
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
                    <div class="aqualuxe-demo-importer-modal-progress">
                        <div class="aqualuxe-demo-importer-progress-bar">
                            <div class="aqualuxe-demo-importer-progress-bar-inner"></div>
                        </div>
                        <div class="aqualuxe-demo-importer-progress-status">
                            <span class="aqualuxe-demo-importer-progress-percentage">0%</span>
                            <span class="aqualuxe-demo-importer-progress-step"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    <div class="aqualuxe-demo-importer-modal-log">
                        <h3><?php esc_html_e('Import Log', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-demo-importer-log-content"></div>
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

        // Start the import process
        $this->import_log = array();
        $this->import_log[] = __('Starting import process...', 'aqualuxe');

        // Set import session
        $import_session_id = md5('aqualuxe_demo_import_' . $demo_id . '_' . time());
        set_transient('aqualuxe_demo_import_session', $import_session_id, 3600);
        set_transient('aqualuxe_demo_import_progress_' . $import_session_id, array(
            'step' => 'prepare',
            'percentage' => 0,
            'log' => $this->import_log,
        ), 3600);

        // Schedule the import steps
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_content', array($demo_id, $import_session_id));

        wp_send_json_success(array(
            'message' => __('Import process started.', 'aqualuxe'),
            'session_id' => $import_session_id,
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
     * Import content.
     */
    public function import_content($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        // Update progress
        $progress['step'] = 'content';
        $progress['percentage'] = 10;
        $progress['log'][] = __('Importing content...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Check if content file exists
        if (!isset($demo['files']['content']) || !file_exists($demo['files']['content'])) {
            $progress['log'][] = __('Content file not found.', 'aqualuxe');
            $progress['percentage'] = 20;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_widgets', array($demo_id, $session_id));
            return;
        }

        // Import content
        $importer = new AquaLuxe_WP_Import();
        $importer->fetch_attachments = true;

        // Run the importer
        ob_start();
        $importer->import($demo['files']['content']);
        ob_end_clean();

        // Update progress
        $progress['percentage'] = 30;
        $progress['log'][] = __('Content imported successfully.', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Schedule next step
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_widgets', array($demo_id, $session_id));
    }

    /**
     * Import widgets.
     */
    public function import_widgets($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        // Update progress
        $progress['step'] = 'widgets';
        $progress['percentage'] = 40;
        $progress['log'][] = __('Importing widgets...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Check if widgets file exists
        if (!isset($demo['files']['widgets']) || !file_exists($demo['files']['widgets'])) {
            $progress['log'][] = __('Widgets file not found.', 'aqualuxe');
            $progress['percentage'] = 50;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_customizer', array($demo_id, $session_id));
            return;
        }

        // Import widgets
        $widget_importer = new AquaLuxe_Widget_Importer();
        $widget_importer->import($demo['files']['widgets']);

        // Update progress
        $progress['percentage'] = 50;
        $progress['log'][] = __('Widgets imported successfully.', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Schedule next step
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_customizer', array($demo_id, $session_id));
    }

    /**
     * Import customizer settings.
     */
    public function import_customizer($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        // Update progress
        $progress['step'] = 'customizer';
        $progress['percentage'] = 60;
        $progress['log'][] = __('Importing customizer settings...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Check if customizer file exists
        if (!isset($demo['files']['customizer']) || !file_exists($demo['files']['customizer'])) {
            $progress['log'][] = __('Customizer file not found.', 'aqualuxe');
            $progress['percentage'] = 70;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_options', array($demo_id, $session_id));
            return;
        }

        // Import customizer settings
        $customizer_importer = new AquaLuxe_Customizer_Importer();
        $customizer_importer->import($demo['files']['customizer']);

        // Update progress
        $progress['percentage'] = 70;
        $progress['log'][] = __('Customizer settings imported successfully.', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Schedule next step
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_options', array($demo_id, $session_id));
    }

    /**
     * Import options.
     */
    public function import_options($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        // Update progress
        $progress['step'] = 'options';
        $progress['percentage'] = 80;
        $progress['log'][] = __('Importing options...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Check if options file exists
        if (!isset($demo['files']['options']) || !file_exists($demo['files']['options'])) {
            $progress['log'][] = __('Options file not found.', 'aqualuxe');
            $progress['percentage'] = 90;
            set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
            wp_schedule_single_event(time(), 'aqualuxe_demo_import_settings', array($demo_id, $session_id));
            return;
        }

        // Import options
        $options = json_decode(file_get_contents($demo['files']['options']), true);
        
        if ($options && is_array($options)) {
            foreach ($options as $option_name => $option_value) {
                update_option($option_name, $option_value);
            }
        }

        // Update progress
        $progress['percentage'] = 90;
        $progress['log'][] = __('Options imported successfully.', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Schedule next step
        wp_schedule_single_event(time(), 'aqualuxe_demo_import_settings', array($demo_id, $session_id));
    }

    /**
     * Import settings.
     */
    public function import_settings($demo_id, $session_id) {
        $demo = $this->demo_config[$demo_id];
        $progress = get_transient('aqualuxe_demo_import_progress_' . $session_id);

        // Update progress
        $progress['step'] = 'settings';
        $progress['percentage'] = 95;
        $progress['log'][] = __('Configuring settings...', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);

        // Set homepage and blog page
        if (isset($demo['settings']['homepage'])) {
            $homepage = get_page_by_title($demo['settings']['homepage']);
            if ($homepage) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $homepage->ID);
            }
        }

        if (isset($demo['settings']['blog_page'])) {
            $blog_page = get_page_by_title($demo['settings']['blog_page']);
            if ($blog_page) {
                update_option('page_for_posts', $blog_page->ID);
            }
        }

        // Set shop page
        if (isset($demo['settings']['shop_page']) && function_exists('wc_get_page_id')) {
            $shop_page = get_page_by_title($demo['settings']['shop_page']);
            if ($shop_page) {
                update_option('woocommerce_shop_page_id', $shop_page->ID);
            }
        }

        // Set menus
        if (isset($demo['settings']['menus']) && is_array($demo['settings']['menus'])) {
            $locations = get_theme_mod('nav_menu_locations');
            $menus = wp_get_nav_menus();

            foreach ($demo['settings']['menus'] as $location => $menu_name) {
                foreach ($menus as $menu) {
                    if ($menu->name === $menu_name) {
                        $locations[$location] = $menu->term_id;
                    }
                }
            }

            set_theme_mod('nav_menu_locations', $locations);
        }

        // Update progress
        $progress['step'] = 'complete';
        $progress['percentage'] = 100;
        $progress['log'][] = __('Settings configured successfully.', 'aqualuxe');
        $progress['log'][] = __('Import completed successfully!', 'aqualuxe');
        set_transient('aqualuxe_demo_import_progress_' . $session_id, $progress, 3600);
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

// Register import hooks
add_action('aqualuxe_demo_import_content', array(AquaLuxe_Demo_Importer::get_instance(), 'import_content'), 10, 2);
add_action('aqualuxe_demo_import_widgets', array(AquaLuxe_Demo_Importer::get_instance(), 'import_widgets'), 10, 2);
add_action('aqualuxe_demo_import_customizer', array(AquaLuxe_Demo_Importer::get_instance(), 'import_customizer'), 10, 2);
add_action('aqualuxe_demo_import_options', array(AquaLuxe_Demo_Importer::get_instance(), 'import_options'), 10, 2);
add_action('aqualuxe_demo_import_settings', array(AquaLuxe_Demo_Importer::get_instance(), 'import_settings'), 10, 2);