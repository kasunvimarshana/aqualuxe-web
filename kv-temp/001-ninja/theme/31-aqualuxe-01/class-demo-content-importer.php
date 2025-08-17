<?php
/**
 * Demo Content Importer
 *
 * A comprehensive, production-ready demo content importer system that completely
 * transforms any application instance into a fully configured demonstration environment.
 *
 * @package DemoContentImporter
 * @version 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Class
 */
class Demo_Content_Importer {

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
     * Database backup directory.
     *
     * @var string
     */
    protected $backup_dir = '';

    /**
     * Demo data directory.
     *
     * @var string
     */
    protected $data_dir = '';

    /**
     * Demo data URL.
     *
     * @var string
     */
    protected $data_url = '';

    /**
     * Import options.
     *
     * @var array
     */
    protected $import_options = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->define_constants();
        $this->setup_directories();
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
        // Base paths
        define('DCI_DIR', dirname(__FILE__));
        define('DCI_URL', plugins_url('', __FILE__));
        
        // Component paths
        define('DCI_INCLUDES_DIR', DCI_DIR . '/includes');
        define('DCI_ASSETS_DIR', DCI_DIR . '/assets');
        define('DCI_ASSETS_URL', DCI_URL . '/assets');
        define('DCI_DATA_DIR', DCI_DIR . '/data');
        define('DCI_DATA_URL', DCI_URL . '/data');
        
        // Storage paths
        define('DCI_CACHE_DIR', WP_CONTENT_DIR . '/cache/demo-content-importer');
        define('DCI_BACKUP_DIR', WP_CONTENT_DIR . '/backups/demo-content-importer');
        
        // Version
        define('DCI_VERSION', '1.0.0');
    }

    /**
     * Setup directories.
     */
    private function setup_directories() {
        // Set directory paths
        $this->cache_dir = DCI_CACHE_DIR;
        $this->backup_dir = DCI_BACKUP_DIR;
        $this->data_dir = DCI_DATA_DIR;
        $this->data_url = DCI_DATA_URL;
        
        // Create cache directory if it doesn't exist
        if (!file_exists($this->cache_dir)) {
            wp_mkdir_p($this->cache_dir);
            
            // Create .htaccess file to protect cache directory
            $htaccess_content = "# Deny access to all files\n";
            $htaccess_content .= "<FilesMatch &quot;.*&quot;>\n";
            $htaccess_content .= "    Order Allow,Deny\n";
            $htaccess_content .= "    Deny from all\n";
            $htaccess_content .= "</FilesMatch>\n";
            
            file_put_contents($this->cache_dir . '/.htaccess', $htaccess_content);
        }
        
        // Create backup directory if it doesn't exist
        if (!file_exists($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
            
            // Create .htaccess file to protect backup directory
            $htaccess_content = "# Deny access to all files\n";
            $htaccess_content .= "<FilesMatch &quot;.*&quot;>\n";
            $htaccess_content .= "    Order Allow,Deny\n";
            $htaccess_content .= "    Deny from all\n";
            $htaccess_content .= "</FilesMatch>\n";
            
            file_put_contents($this->backup_dir . '/.htaccess', $htaccess_content);
        }
        
        // Create index.php files to prevent directory listing
        if (!file_exists($this->cache_dir . '/index.php')) {
            file_put_contents($this->cache_dir . '/index.php', '<?php // Silence is golden');
        }
        
        if (!file_exists($this->backup_dir . '/index.php')) {
            file_put_contents($this->backup_dir . '/index.php', '<?php // Silence is golden');
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
        add_action('wp_ajax_dci_import_demo_data', array($this, 'ajax_import_demo_data'));
        add_action('wp_ajax_dci_check_plugin_status', array($this, 'ajax_check_plugin_status'));
        add_action('wp_ajax_dci_install_plugin', array($this, 'ajax_install_plugin'));
        add_action('wp_ajax_dci_activate_plugin', array($this, 'ajax_activate_plugin'));
        add_action('wp_ajax_dci_import_progress', array($this, 'ajax_import_progress'));
        add_action('wp_ajax_dci_backup_database', array($this, 'ajax_backup_database'));
        add_action('wp_ajax_dci_restore_database', array($this, 'ajax_restore_database'));
        add_action('wp_ajax_dci_reset_site', array($this, 'ajax_reset_site'));
        add_action('wp_ajax_dci_get_import_options', array($this, 'ajax_get_import_options'));
        add_action('wp_ajax_dci_save_import_options', array($this, 'ajax_save_import_options'));
    }

    /**
     * Load dependencies.
     */
    private function load_dependencies() {
        // Include WordPress Importer
        if (!class_exists('WP_Importer')) {
            require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
        }

        // Include WordPress filesystem functionality
        if (!function_exists('WP_Filesystem')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        // Include plugin installer
        if (!function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        // Include plugin installer skin
        if (!class_exists('Plugin_Installer_Skin')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        // Include our custom classes
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-logger.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-customizer.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-widgets.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-content.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-options.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-plugins.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-media.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-backup.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-reset.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-performance.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-security.php';
        require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-progress.php';
        
        // Load theme integrations
        if (file_exists(DCI_INCLUDES_DIR . '/class-demo-content-importer-aqualuxe-integration.php')) {
            require_once DCI_INCLUDES_DIR . '/class-demo-content-importer-aqualuxe-integration.php';
        }
    }

    /**
     * Setup demo configuration.
     */
    private function setup_demo_config() {
        // Default demo configuration
        $default_config = array(
            'default' => array(
                'name' => __('Default Demo', 'demo-content-importer'),
                'description' => __('Default demo content for the theme.', 'demo-content-importer'),
                'screenshot' => DCI_ASSETS_URL . '/images/screenshot-default.jpg',
                'preview_url' => 'https://demo.example.com',
                'files' => array(
                    'content' => DCI_DATA_DIR . '/default/content.xml',
                    'widgets' => DCI_DATA_DIR . '/default/widgets.json',
                    'customizer' => DCI_DATA_DIR . '/default/customizer.json',
                    'options' => DCI_DATA_DIR . '/default/options.json',
                    'menus' => DCI_DATA_DIR . '/default/menus.json',
                ),
                'plugins' => array(
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
        );

        // Allow theme/plugin to filter the demo configuration
        $this->demo_config = apply_filters('dci_demo_config', $default_config);
        
        // Setup required plugins
        foreach ($this->demo_config as $demo_id => $demo) {
            if (isset($demo['plugins']) && is_array($demo['plugins'])) {
                foreach ($demo['plugins'] as $plugin_slug => $plugin) {
                    if (isset($plugin['required']) && $plugin['required']) {
                        $this->required_plugins[$plugin_slug] = $plugin;
                    }
                }
            }
        }
    }

    /**
     * Add admin menu.
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Demo Content Importer', 'demo-content-importer'),
            __('Demo Content', 'demo-content-importer'),
            'manage_options',
            'demo-content-importer',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Enqueue scripts and styles.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_scripts($hook) {
        if ('appearance_page_demo-content-importer' !== $hook) {
            return;
        }

        // Enqueue styles
        wp_enqueue_style('dci-admin-style', DCI_ASSETS_URL . '/css/admin.css', array(), DCI_VERSION);
        wp_enqueue_style('dci-progress-style', DCI_ASSETS_URL . '/css/progress.css', array(), DCI_VERSION);
        
        // Enqueue scripts
        wp_enqueue_script('dci-admin-script', DCI_ASSETS_URL . '/js/admin.js', array('jquery'), DCI_VERSION, true);
        wp_enqueue_script('dci-progress-script', DCI_ASSETS_URL . '/js/progress.js', array('jquery'), DCI_VERSION, true);
        
        // Localize admin script
        wp_localize_script('dci-admin-script', 'dciSettings', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dci-ajax-nonce'),
            'i18n' => array(
                'importing' => __('Importing...', 'demo-content-importer'),
                'importComplete' => __('Import Complete!', 'demo-content-importer'),
                'importFailed' => __('Import Failed!', 'demo-content-importer'),
                'backingUp' => __('Backing up database...', 'demo-content-importer'),
                'backupComplete' => __('Backup Complete!', 'demo-content-importer'),
                'backupFailed' => __('Backup Failed!', 'demo-content-importer'),
                'restoring' => __('Restoring database...', 'demo-content-importer'),
                'restoreComplete' => __('Restore Complete!', 'demo-content-importer'),
                'restoreFailed' => __('Restore Failed!', 'demo-content-importer'),
                'resetting' => __('Resetting site...', 'demo-content-importer'),
                'resetComplete' => __('Reset Complete!', 'demo-content-importer'),
                'resetFailed' => __('Reset Failed!', 'demo-content-importer'),
                'installingPlugin' => __('Installing plugin...', 'demo-content-importer'),
                'activatingPlugin' => __('Activating plugin...', 'demo-content-importer'),
                'pluginInstalled' => __('Plugin installed!', 'demo-content-importer'),
                'pluginActivated' => __('Plugin activated!', 'demo-content-importer'),
                'confirmReset' => __('Are you sure you want to reset your site? This will delete all your content and cannot be undone!', 'demo-content-importer'),
                'confirmImport' => __('Are you sure you want to import demo content? This will overwrite your current content.', 'demo-content-importer'),
            ),
        ));
        
        // Localize progress script
        wp_localize_script('dci-progress-script', 'dciProgress', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('dci-progress-nonce'),
            'adminUrl' => admin_url(),
            'siteUrl' => home_url(),
            'updateFrequency' => 2000, // 2 seconds
            'startingText' => __('Starting import...', 'demo-content-importer'),
            'processingText' => __('Processing...', 'demo-content-importer'),
            'completeText' => __('Import completed successfully!', 'demo-content-importer'),
            'failedText' => __('Import failed. Please check the logs for details.', 'demo-content-importer'),
        ));
    }

    /**
     * Render admin page.
     */
    public function render_admin_page() {
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'demo-content-importer'));
        }
        
        // Get demo packages
        $demo_packages = $this->get_demo_packages();
        
        // Get settings
        $settings = get_option('dci_settings', array());
        
        // Get logs
        $logger = Demo_Content_Importer_Logger::get_instance();
        $logs = $logger->get_logs(50); // Get last 50 logs
        
        // Get backups
        $backup = Demo_Content_Importer_Backup::get_instance();
        $backups = $backup->get_backups();
        
        // Get notices
        $notices = array();
        if (isset($_GET['message'])) {
            switch ($_GET['message']) {
                case 'settings-saved':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Settings saved successfully.', 'demo-content-importer')
                    );
                    break;
                case 'backup-created':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Backup created successfully.', 'demo-content-importer')
                    );
                    break;
                case 'backup-restored':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Backup restored successfully.', 'demo-content-importer')
                    );
                    break;
                case 'backup-deleted':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Backup deleted successfully.', 'demo-content-importer')
                    );
                    break;
                case 'logs-cleared':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Logs cleared successfully.', 'demo-content-importer')
                    );
                    break;
                case 'site-reset':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Site reset successfully.', 'demo-content-importer')
                    );
                    break;
                case 'import-completed':
                    $notices[] = array(
                        'type' => 'success',
                        'message' => __('Import completed successfully.', 'demo-content-importer')
                    );
                    break;
                case 'import-failed':
                    $notices[] = array(
                        'type' => 'error',
                        'message' => __('Import failed. Please check the logs for details.', 'demo-content-importer')
                    );
                    break;
            }
        }
        
        // Include enhanced admin page template
        include_once DCI_DIR . '/templates/admin-page-enhanced.php';
    }

    /**
     * AJAX handler for importing demo data.
     */
    public function ajax_import_demo_data() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get demo ID
        $demo_id = isset($_POST['demo_id']) ? sanitize_text_field($_POST['demo_id']) : 'default';
        
        // Check if demo exists
        if (!isset($this->demo_config[$demo_id])) {
            wp_send_json_error(array('message' => __('Demo configuration not found.', 'demo-content-importer')));
        }
        
        // Get import options
        $import_options = isset($_POST['import_options']) ? $_POST['import_options'] : array();
        
        // Sanitize import options
        $sanitized_options = array();
        $valid_options = array('content', 'widgets', 'customizer', 'options', 'menus');
        
        foreach ($valid_options as $option) {
            $sanitized_options[$option] = isset($import_options[$option]) && $import_options[$option] ? true : false;
        }
        
        // Set import options
        $this->import_options = $sanitized_options;
        
        // Generate import session ID
        $this->import_session_id = uniqid('dci_');
        
        // Initialize import log
        $this->import_log = array(
            'session_id' => $this->import_session_id,
            'demo_id' => $demo_id,
            'start_time' => time(),
            'end_time' => null,
            'status' => 'in_progress',
            'steps' => array(),
            'errors' => array(),
        );
        
        // Save import log
        $this->save_import_log();
        
        // Start import process
        $result = $this->import_demo($demo_id);
        
        if (is_wp_error($result)) {
            $this->import_log['status'] = 'failed';
            $this->import_log['end_time'] = time();
            $this->import_log['errors'][] = $result->get_error_message();
            $this->save_import_log();
            
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        // Update import log
        $this->import_log['status'] = 'completed';
        $this->import_log['end_time'] = time();
        $this->save_import_log();
        
        wp_send_json_success(array(
            'message' => __('Demo content imported successfully!', 'demo-content-importer'),
            'log' => $this->import_log,
        ));
    }

    /**
     * Import demo content.
     *
     * @param string $demo_id Demo ID.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function import_demo($demo_id) {
        // Get demo configuration
        $demo = $this->demo_config[$demo_id];
        
        // Check required plugins
        $plugins_check = $this->check_required_plugins($demo);
        if (is_wp_error($plugins_check)) {
            return $plugins_check;
        }
        
        // Import content
        if ($this->import_options['content'] && isset($demo['files']['content'])) {
            $content_importer = new Demo_Content_Importer_Content();
            $content_result = $content_importer->import($demo['files']['content']);
            
            $this->import_log['steps']['content'] = $content_result;
            $this->save_import_log();
            
            if (is_wp_error($content_result)) {
                return $content_result;
            }
        }
        
        // Import widgets
        if ($this->import_options['widgets'] && isset($demo['files']['widgets'])) {
            $widget_importer = new Demo_Content_Importer_Widgets();
            $widget_result = $widget_importer->import($demo['files']['widgets']);
            
            $this->import_log['steps']['widgets'] = $widget_result;
            $this->save_import_log();
            
            if (is_wp_error($widget_result)) {
                return $widget_result;
            }
        }
        
        // Import customizer settings
        if ($this->import_options['customizer'] && isset($demo['files']['customizer'])) {
            $customizer_importer = new Demo_Content_Importer_Customizer();
            $customizer_result = $customizer_importer->import($demo['files']['customizer']);
            
            $this->import_log['steps']['customizer'] = $customizer_result;
            $this->save_import_log();
            
            if (is_wp_error($customizer_result)) {
                return $customizer_result;
            }
        }
        
        // Import options
        if ($this->import_options['options'] && isset($demo['files']['options'])) {
            $options_importer = new Demo_Content_Importer_Options();
            $options_result = $options_importer->import($demo['files']['options']);
            
            $this->import_log['steps']['options'] = $options_result;
            $this->save_import_log();
            
            if (is_wp_error($options_result)) {
                return $options_result;
            }
        }
        
        // Import menus
        if ($this->import_options['menus'] && isset($demo['files']['menus'])) {
            $menus_importer = new Demo_Content_Importer_Options();
            $menus_result = $menus_importer->import_menus($demo['files']['menus']);
            
            $this->import_log['steps']['menus'] = $menus_result;
            $this->save_import_log();
            
            if (is_wp_error($menus_result)) {
                return $menus_result;
            }
        }
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        return true;
    }

    /**
     * Check required plugins.
     *
     * @param array $demo Demo configuration.
     * @return true|WP_Error True on success, WP_Error on failure.
     */
    private function check_required_plugins($demo) {
        // Check if demo has required plugins
        if (!isset($demo['plugins']) || !is_array($demo['plugins'])) {
            return true;
        }
        
        // Get required plugins
        $required_plugins = array();
        foreach ($demo['plugins'] as $plugin_slug => $plugin) {
            if (isset($plugin['required']) && $plugin['required']) {
                $required_plugins[$plugin_slug] = $plugin;
            }
        }
        
        // Check if required plugins are active
        $plugins_importer = new Demo_Content_Importer_Plugins();
        $inactive_plugins = $plugins_importer->get_inactive_required_plugins($required_plugins);
        
        if (!empty($inactive_plugins)) {
            $plugin_names = array();
            foreach ($inactive_plugins as $plugin) {
                $plugin_names[] = $plugin['name'];
            }
            
            return new WP_Error(
                'required_plugins_inactive',
                sprintf(
                    __('The following required plugins are not active: %s. Please install and activate them before importing the demo content.', 'demo-content-importer'),
                    implode(', ', $plugin_names)
                )
            );
        }
        
        return true;
    }

    /**
     * Save import log.
     */
    private function save_import_log() {
        $log_file = $this->cache_dir . '/' . $this->import_session_id . '.json';
        file_put_contents($log_file, wp_json_encode($this->import_log));
    }

    /**
     * AJAX handler for checking plugin status.
     */
    public function ajax_check_plugin_status() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get plugin slug
        $plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field($_POST['plugin_slug']) : '';
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => __('Plugin slug is required.', 'demo-content-importer')));
        }
        
        // Check plugin status
        $plugins_importer = new Demo_Content_Importer_Plugins();
        $status = $plugins_importer->get_plugin_status($plugin_slug);
        
        wp_send_json_success(array('status' => $status));
    }

    /**
     * AJAX handler for installing a plugin.
     */
    public function ajax_install_plugin() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get plugin slug
        $plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field($_POST['plugin_slug']) : '';
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => __('Plugin slug is required.', 'demo-content-importer')));
        }
        
        // Install plugin
        $plugins_importer = new Demo_Content_Importer_Plugins();
        $result = $plugins_importer->install_plugin($plugin_slug);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('message' => __('Plugin installed successfully!', 'demo-content-importer')));
    }

    /**
     * AJAX handler for activating a plugin.
     */
    public function ajax_activate_plugin() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get plugin slug
        $plugin_slug = isset($_POST['plugin_slug']) ? sanitize_text_field($_POST['plugin_slug']) : '';
        
        if (empty($plugin_slug)) {
            wp_send_json_error(array('message' => __('Plugin slug is required.', 'demo-content-importer')));
        }
        
        // Activate plugin
        $plugins_importer = new Demo_Content_Importer_Plugins();
        $result = $plugins_importer->activate_plugin($plugin_slug);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('message' => __('Plugin activated successfully!', 'demo-content-importer')));
    }

    /**
     * AJAX handler for getting import progress.
     */
    public function ajax_import_progress() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get session ID
        $session_id = isset($_POST['session_id']) ? sanitize_text_field($_POST['session_id']) : '';
        
        if (empty($session_id)) {
            wp_send_json_error(array('message' => __('Session ID is required.', 'demo-content-importer')));
        }
        
        // Get import log
        $log_file = $this->cache_dir . '/' . $session_id . '.json';
        
        if (!file_exists($log_file)) {
            wp_send_json_error(array('message' => __('Import log not found.', 'demo-content-importer')));
        }
        
        $import_log = json_decode(file_get_contents($log_file), true);
        
        wp_send_json_success(array('log' => $import_log));
    }

    /**
     * AJAX handler for backing up the database.
     */
    public function ajax_backup_database() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Backup database
        $backup_importer = new Demo_Content_Importer_Backup();
        $result = $backup_importer->backup_database();
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array(
            'message' => __('Database backed up successfully!', 'demo-content-importer'),
            'backup_file' => $result,
        ));
    }

    /**
     * AJAX handler for restoring the database.
     */
    public function ajax_restore_database() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get backup file
        $backup_file = isset($_POST['backup_file']) ? sanitize_text_field($_POST['backup_file']) : '';
        
        if (empty($backup_file)) {
            wp_send_json_error(array('message' => __('Backup file is required.', 'demo-content-importer')));
        }
        
        // Restore database
        $backup_importer = new Demo_Content_Importer_Backup();
        $result = $backup_importer->restore_database($backup_file);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('message' => __('Database restored successfully!', 'demo-content-importer')));
    }

    /**
     * AJAX handler for resetting the site.
     */
    public function ajax_reset_site() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Reset site
        $reset_importer = new Demo_Content_Importer_Reset();
        $result = $reset_importer->reset_site();
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }
        
        wp_send_json_success(array('message' => __('Site reset successfully!', 'demo-content-importer')));
    }

    /**
     * AJAX handler for getting import options.
     */
    public function ajax_get_import_options() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get demo ID
        $demo_id = isset($_POST['demo_id']) ? sanitize_text_field($_POST['demo_id']) : 'default';
        
        // Check if demo exists
        if (!isset($this->demo_config[$demo_id])) {
            wp_send_json_error(array('message' => __('Demo configuration not found.', 'demo-content-importer')));
        }
        
        // Get demo configuration
        $demo = $this->demo_config[$demo_id];
        
        // Get available import options
        $import_options = array();
        
        if (isset($demo['files']['content'])) {
            $import_options['content'] = array(
                'label' => __('Content', 'demo-content-importer'),
                'description' => __('Import posts, pages, and other content.', 'demo-content-importer'),
                'default' => true,
            );
        }
        
        if (isset($demo['files']['widgets'])) {
            $import_options['widgets'] = array(
                'label' => __('Widgets', 'demo-content-importer'),
                'description' => __('Import widget configurations.', 'demo-content-importer'),
                'default' => true,
            );
        }
        
        if (isset($demo['files']['customizer'])) {
            $import_options['customizer'] = array(
                'label' => __('Customizer Settings', 'demo-content-importer'),
                'description' => __('Import theme customizer settings.', 'demo-content-importer'),
                'default' => true,
            );
        }
        
        if (isset($demo['files']['options'])) {
            $import_options['options'] = array(
                'label' => __('Theme Options', 'demo-content-importer'),
                'description' => __('Import theme options and settings.', 'demo-content-importer'),
                'default' => true,
            );
        }
        
        if (isset($demo['files']['menus'])) {
            $import_options['menus'] = array(
                'label' => __('Menus', 'demo-content-importer'),
                'description' => __('Import menu structures and locations.', 'demo-content-importer'),
                'default' => true,
            );
        }
        
        wp_send_json_success(array('options' => $import_options));
    }

    /**
     * AJAX handler for saving import options.
     */
    public function ajax_save_import_options() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-ajax-nonce')) {
            wp_send_json_error(array('message' => __('Invalid security token. Please refresh the page and try again.', 'demo-content-importer')));
        }
        
        // Check if user has proper permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have sufficient permissions to perform this action.', 'demo-content-importer')));
        }
        
        // Get demo ID
        $demo_id = isset($_POST['demo_id']) ? sanitize_text_field($_POST['demo_id']) : 'default';
        
        // Get import options
        $import_options = isset($_POST['import_options']) ? $_POST['import_options'] : array();
        
        // Sanitize import options
        $sanitized_options = array();
        $valid_options = array('content', 'widgets', 'customizer', 'options', 'menus');
        
        foreach ($valid_options as $option) {
            $sanitized_options[$option] = isset($import_options[$option]) && $import_options[$option] ? true : false;
        }
        
        // Save import options
        update_option('dci_import_options_' . $demo_id, $sanitized_options);
        
        wp_send_json_success(array('message' => __('Import options saved successfully!', 'demo-content-importer')));
    }
}

// Initialize the Demo Content Importer
function demo_content_importer() {
    return Demo_Content_Importer::get_instance();
}

// Start the plugin
demo_content_importer();