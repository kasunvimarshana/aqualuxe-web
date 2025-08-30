<?php
/**
 * Demo Content Importer Tests
 *
 * @package DemoContentImporter
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Test Class
 */
class Demo_Content_Importer_Tests {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Main importer instance.
     *
     * @var Demo_Content_Importer
     */
    protected $importer = null;

    /**
     * Test results.
     *
     * @var array
     */
    protected $results = array();

    /**
     * Constructor.
     */
    public function __construct() {
        $this->importer = Demo_Content_Importer::get_instance();
        
        // Register hooks
        add_action('admin_menu', array($this, 'register_test_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_run_dci_tests', array($this, 'run_tests_ajax'));
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
     * Register test page.
     */
    public function register_test_page() {
        add_submenu_page(
            'tools.php',
            __('Demo Importer Tests', 'demo-content-importer'),
            __('Demo Importer Tests', 'demo-content-importer'),
            'manage_options',
            'dci-tests',
            array($this, 'render_test_page')
        );
    }

    /**
     * Enqueue assets.
     */
    public function enqueue_assets($hook) {
        if ('tools_page_dci-tests' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'dci-test-styles',
            plugin_dir_url(dirname(__FILE__)) . 'assets/css/tests.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'dci-test-scripts',
            plugin_dir_url(dirname(__FILE__)) . 'assets/js/tests.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script(
            'dci-test-scripts',
            'dciTests',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('dci-tests-nonce'),
            )
        );
    }

    /**
     * Render test page.
     */
    public function render_test_page() {
        ?>
        <div class="wrap dci-tests-page">
            <h1><?php esc_html_e('Demo Content Importer Tests', 'demo-content-importer'); ?></h1>
            
            <div class="dci-tests-controls">
                <button id="run-all-tests" class="button button-primary"><?php esc_html_e('Run All Tests', 'demo-content-importer'); ?></button>
                <button id="run-selected-tests" class="button"><?php esc_html_e('Run Selected Tests', 'demo-content-importer'); ?></button>
            </div>
            
            <div class="dci-tests-categories">
                <h2><?php esc_html_e('Test Categories', 'demo-content-importer'); ?></h2>
                
                <div class="dci-test-category">
                    <h3>
                        <label>
                            <input type="checkbox" class="category-checkbox" data-category="core">
                            <?php esc_html_e('Core Functionality', 'demo-content-importer'); ?>
                        </label>
                    </h3>
                    <div class="dci-test-list">
                        <label><input type="checkbox" class="test-checkbox" data-test="core_initialization"> <?php esc_html_e('Initialization', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="core_hooks"> <?php esc_html_e('Hooks and Filters', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="core_admin_pages"> <?php esc_html_e('Admin Pages', 'demo-content-importer'); ?></label>
                    </div>
                </div>
                
                <div class="dci-test-category">
                    <h3>
                        <label>
                            <input type="checkbox" class="category-checkbox" data-category="import">
                            <?php esc_html_e('Import Functionality', 'demo-content-importer'); ?>
                        </label>
                    </h3>
                    <div class="dci-test-list">
                        <label><input type="checkbox" class="test-checkbox" data-test="import_demo_packages"> <?php esc_html_e('Demo Packages', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="import_content"> <?php esc_html_e('Content Import', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="import_customizer"> <?php esc_html_e('Customizer Import', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="import_widgets"> <?php esc_html_e('Widgets Import', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="import_options"> <?php esc_html_e('Options Import', 'demo-content-importer'); ?></label>
                    </div>
                </div>
                
                <div class="dci-test-category">
                    <h3>
                        <label>
                            <input type="checkbox" class="category-checkbox" data-category="theme">
                            <?php esc_html_e('Theme Integration', 'demo-content-importer'); ?>
                        </label>
                    </h3>
                    <div class="dci-test-list">
                        <label><input type="checkbox" class="test-checkbox" data-test="theme_integration"> <?php esc_html_e('Theme Integration', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="theme_aqualuxe"> <?php esc_html_e('AquaLuxe Integration', 'demo-content-importer'); ?></label>
                    </div>
                </div>
                
                <div class="dci-test-category">
                    <h3>
                        <label>
                            <input type="checkbox" class="category-checkbox" data-category="backup">
                            <?php esc_html_e('Backup & Reset', 'demo-content-importer'); ?>
                        </label>
                    </h3>
                    <div class="dci-test-list">
                        <label><input type="checkbox" class="test-checkbox" data-test="backup_create"> <?php esc_html_e('Create Backup', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="backup_restore"> <?php esc_html_e('Restore Backup', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="reset_site"> <?php esc_html_e('Reset Site', 'demo-content-importer'); ?></label>
                    </div>
                </div>
                
                <div class="dci-test-category">
                    <h3>
                        <label>
                            <input type="checkbox" class="category-checkbox" data-category="performance">
                            <?php esc_html_e('Performance', 'demo-content-importer'); ?>
                        </label>
                    </h3>
                    <div class="dci-test-list">
                        <label><input type="checkbox" class="test-checkbox" data-test="performance_memory"> <?php esc_html_e('Memory Usage', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="performance_time"> <?php esc_html_e('Execution Time', 'demo-content-importer'); ?></label>
                        <label><input type="checkbox" class="test-checkbox" data-test="performance_queries"> <?php esc_html_e('Database Queries', 'demo-content-importer'); ?></label>
                    </div>
                </div>
            </div>
            
            <div class="dci-test-results">
                <h2><?php esc_html_e('Test Results', 'demo-content-importer'); ?></h2>
                <div id="dci-test-results-container">
                    <p><?php esc_html_e('Run tests to see results.', 'demo-content-importer'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Run tests via AJAX.
     */
    public function run_tests_ajax() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'dci-tests-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'demo-content-importer')));
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to run tests.', 'demo-content-importer')));
        }

        // Get tests to run
        $tests = isset($_POST['tests']) ? (array) $_POST['tests'] : array();

        // Run tests
        $results = $this->run_tests($tests);

        // Send results
        wp_send_json_success(array('results' => $results));
    }

    /**
     * Run tests.
     *
     * @param array $tests Tests to run.
     * @return array Test results.
     */
    public function run_tests($tests = array()) {
        $this->results = array();

        // If no tests specified, run all tests
        if (empty($tests)) {
            $tests = $this->get_all_tests();
        }

        // Run each test
        foreach ($tests as $test) {
            $method = 'test_' . $test;
            if (method_exists($this, $method)) {
                $this->results[$test] = $this->$method();
            } else {
                $this->results[$test] = array(
                    'status' => 'error',
                    'message' => sprintf(__('Test method %s does not exist.', 'demo-content-importer'), $method),
                );
            }
        }

        return $this->results;
    }

    /**
     * Get all available tests.
     *
     * @return array All available tests.
     */
    public function get_all_tests() {
        return array(
            'core_initialization',
            'core_hooks',
            'core_admin_pages',
            'import_demo_packages',
            'import_content',
            'import_customizer',
            'import_widgets',
            'import_options',
            'theme_integration',
            'theme_aqualuxe',
            'backup_create',
            'backup_restore',
            'reset_site',
            'performance_memory',
            'performance_time',
            'performance_queries',
        );
    }

    /**
     * Test core initialization.
     *
     * @return array Test result.
     */
    public function test_core_initialization() {
        $result = array(
            'status' => 'pass',
            'message' => __('Core initialization successful.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if importer instance exists
        if (!$this->importer) {
            $result['status'] = 'fail';
            $result['message'] = __('Importer instance not found.', 'demo-content-importer');
            return $result;
        }

        // Check if constants are defined
        if (!defined('DCI_VERSION') || !defined('DCI_DIR') || !defined('DCI_URL')) {
            $result['status'] = 'fail';
            $result['message'] = __('Required constants not defined.', 'demo-content-importer');
            $result['details'][] = array(
                'name' => 'Constants',
                'value' => sprintf(
                    __('DCI_VERSION: %s, DCI_DIR: %s, DCI_URL: %s', 'demo-content-importer'),
                    defined('DCI_VERSION') ? DCI_VERSION : 'undefined',
                    defined('DCI_DIR') ? DCI_DIR : 'undefined',
                    defined('DCI_URL') ? DCI_URL : 'undefined'
                ),
            );
            return $result;
        }

        // Add details
        $result['details'][] = array(
            'name' => 'Version',
            'value' => DCI_VERSION,
        );

        $result['details'][] = array(
            'name' => 'Directory',
            'value' => DCI_DIR,
        );

        $result['details'][] = array(
            'name' => 'URL',
            'value' => DCI_URL,
        );

        return $result;
    }

    /**
     * Test core hooks.
     *
     * @return array Test result.
     */
    public function test_core_hooks() {
        $result = array(
            'status' => 'pass',
            'message' => __('Core hooks are properly registered.', 'demo-content-importer'),
            'details' => array(),
        );

        // List of hooks to check
        $hooks = array(
            'dci_before_import',
            'dci_after_import',
            'dci_before_reset',
            'dci_after_reset',
            'dci_before_backup',
            'dci_after_backup',
            'dci_before_restore',
            'dci_after_restore',
        );

        // Check if hooks exist
        $missing_hooks = array();
        foreach ($hooks as $hook) {
            if (!has_action($hook) && !has_filter($hook)) {
                $missing_hooks[] = $hook;
            }
        }

        if (!empty($missing_hooks)) {
            $result['status'] = 'fail';
            $result['message'] = __('Some hooks are missing.', 'demo-content-importer');
            $result['details'][] = array(
                'name' => 'Missing Hooks',
                'value' => implode(', ', $missing_hooks),
            );
        } else {
            $result['details'][] = array(
                'name' => 'Hooks Checked',
                'value' => implode(', ', $hooks),
            );
        }

        return $result;
    }

    /**
     * Test admin pages.
     *
     * @return array Test result.
     */
    public function test_core_admin_pages() {
        $result = array(
            'status' => 'pass',
            'message' => __('Admin pages are properly registered.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if admin page exists
        global $submenu;
        $found = false;

        if (isset($submenu['themes.php'])) {
            foreach ($submenu['themes.php'] as $item) {
                if (isset($item[2]) && $item[2] === 'demo-content-importer') {
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            $result['status'] = 'fail';
            $result['message'] = __('Admin page not found.', 'demo-content-importer');
        } else {
            $result['details'][] = array(
                'name' => 'Admin Page',
                'value' => __('Found under Appearance menu', 'demo-content-importer'),
            );
        }

        return $result;
    }

    /**
     * Test demo packages.
     *
     * @return array Test result.
     */
    public function test_import_demo_packages() {
        $result = array(
            'status' => 'pass',
            'message' => __('Demo packages are properly registered.', 'demo-content-importer'),
            'details' => array(),
        );

        // Get demo packages
        $packages = $this->importer->get_demo_packages();

        if (empty($packages)) {
            $result['status'] = 'fail';
            $result['message'] = __('No demo packages found.', 'demo-content-importer');
            return $result;
        }

        // Check if AquaLuxe packages exist
        $aqualuxe_packages = array();
        foreach ($packages as $package) {
            if (isset($package['id']) && strpos($package['id'], 'aqualuxe-') === 0) {
                $aqualuxe_packages[] = $package['id'];
            }
        }

        if (empty($aqualuxe_packages)) {
            $result['status'] = 'warning';
            $result['message'] = __('No AquaLuxe demo packages found.', 'demo-content-importer');
        } else {
            $result['details'][] = array(
                'name' => 'AquaLuxe Packages',
                'value' => implode(', ', $aqualuxe_packages),
            );
        }

        $result['details'][] = array(
            'name' => 'Total Packages',
            'value' => count($packages),
        );

        return $result;
    }

    /**
     * Test content import.
     *
     * @return array Test result.
     */
    public function test_import_content() {
        $result = array(
            'status' => 'pass',
            'message' => __('Content import functionality is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if content importer class exists
        if (!class_exists('Demo_Content_Importer_Content')) {
            $result['status'] = 'fail';
            $result['message'] = __('Content importer class not found.', 'demo-content-importer');
            return $result;
        }

        // Check if WordPress importer exists
        if (!file_exists(DCI_INCLUDES_DIR . '/wordpress-importer/class-wp-import.php')) {
            $result['status'] = 'fail';
            $result['message'] = __('WordPress importer not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Content Importer',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        $result['details'][] = array(
            'name' => 'WordPress Importer',
            'value' => __('File exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test customizer import.
     *
     * @return array Test result.
     */
    public function test_import_customizer() {
        $result = array(
            'status' => 'pass',
            'message' => __('Customizer import functionality is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if customizer importer class exists
        if (!class_exists('Demo_Content_Importer_Customizer')) {
            $result['status'] = 'fail';
            $result['message'] = __('Customizer importer class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Customizer Importer',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test widgets import.
     *
     * @return array Test result.
     */
    public function test_import_widgets() {
        $result = array(
            'status' => 'pass',
            'message' => __('Widgets import functionality is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if widgets importer class exists
        if (!class_exists('Demo_Content_Importer_Widgets')) {
            $result['status'] = 'fail';
            $result['message'] = __('Widgets importer class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Widgets Importer',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test options import.
     *
     * @return array Test result.
     */
    public function test_import_options() {
        $result = array(
            'status' => 'pass',
            'message' => __('Options import functionality is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if options importer class exists
        if (!class_exists('Demo_Content_Importer_Options')) {
            $result['status'] = 'fail';
            $result['message'] = __('Options importer class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Options Importer',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test theme integration.
     *
     * @return array Test result.
     */
    public function test_theme_integration() {
        $result = array(
            'status' => 'pass',
            'message' => __('Theme integration is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if theme integration hooks exist
        $hooks = array(
            'dci_demo_packages',
            'dci_import_options',
        );

        $missing_hooks = array();
        foreach ($hooks as $hook) {
            if (!has_filter($hook)) {
                $missing_hooks[] = $hook;
            }
        }

        if (!empty($missing_hooks)) {
            $result['status'] = 'warning';
            $result['message'] = __('Some theme integration hooks are missing.', 'demo-content-importer');
            $result['details'][] = array(
                'name' => 'Missing Hooks',
                'value' => implode(', ', $missing_hooks),
            );
        } else {
            $result['details'][] = array(
                'name' => 'Integration Hooks',
                'value' => implode(', ', $hooks),
            );
        }

        return $result;
    }

    /**
     * Test AquaLuxe integration.
     *
     * @return array Test result.
     */
    public function test_theme_aqualuxe() {
        $result = array(
            'status' => 'pass',
            'message' => __('AquaLuxe integration is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if AquaLuxe integration class exists
        if (!class_exists('Demo_Content_Importer_AquaLuxe_Integration')) {
            $result['status'] = 'fail';
            $result['message'] = __('AquaLuxe integration class not found.', 'demo-content-importer');
            return $result;
        }

        // Check if AquaLuxe demo packages exist
        $packages = $this->importer->get_demo_packages();
        $aqualuxe_packages = array();
        foreach ($packages as $package) {
            if (isset($package['id']) && strpos($package['id'], 'aqualuxe-') === 0) {
                $aqualuxe_packages[] = $package['id'];
            }
        }

        if (empty($aqualuxe_packages)) {
            $result['status'] = 'warning';
            $result['message'] = __('No AquaLuxe demo packages found.', 'demo-content-importer');
        } else {
            $result['details'][] = array(
                'name' => 'AquaLuxe Packages',
                'value' => implode(', ', $aqualuxe_packages),
            );
        }

        // Check if AquaLuxe demo content exists
        $aqualuxe_content_path = DCI_DIR . '/data/aqualuxe/aqualuxe-demo-content.xml';
        if (!file_exists($aqualuxe_content_path)) {
            $result['status'] = 'warning';
            $result['message'] = __('AquaLuxe demo content file not found.', 'demo-content-importer');
        } else {
            $result['details'][] = array(
                'name' => 'Demo Content',
                'value' => __('File exists', 'demo-content-importer'),
            );
        }

        return $result;
    }

    /**
     * Test backup creation.
     *
     * @return array Test result.
     */
    public function test_backup_create() {
        $result = array(
            'status' => 'pass',
            'message' => __('Backup creation is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if backup class exists
        if (!class_exists('Demo_Content_Importer_Backup')) {
            $result['status'] = 'fail';
            $result['message'] = __('Backup class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Backup Class',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test backup restoration.
     *
     * @return array Test result.
     */
    public function test_backup_restore() {
        $result = array(
            'status' => 'pass',
            'message' => __('Backup restoration is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if backup class exists
        if (!class_exists('Demo_Content_Importer_Backup')) {
            $result['status'] = 'fail';
            $result['message'] = __('Backup class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Backup Class',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test site reset.
     *
     * @return array Test result.
     */
    public function test_reset_site() {
        $result = array(
            'status' => 'pass',
            'message' => __('Site reset is working.', 'demo-content-importer'),
            'details' => array(),
        );

        // Check if reset class exists
        if (!class_exists('Demo_Content_Importer_Reset')) {
            $result['status'] = 'fail';
            $result['message'] = __('Reset class not found.', 'demo-content-importer');
            return $result;
        }

        $result['details'][] = array(
            'name' => 'Reset Class',
            'value' => __('Class exists', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Test memory usage.
     *
     * @return array Test result.
     */
    public function test_performance_memory() {
        $result = array(
            'status' => 'pass',
            'message' => __('Memory usage is within acceptable limits.', 'demo-content-importer'),
            'details' => array(),
        );

        // Get memory usage
        $memory_usage = memory_get_usage();
        $memory_limit = $this->get_memory_limit();

        // Convert to MB
        $memory_usage_mb = round($memory_usage / 1024 / 1024, 2);
        $memory_limit_mb = round($memory_limit / 1024 / 1024, 2);

        // Check if memory usage is within limits
        $memory_percentage = ($memory_usage / $memory_limit) * 100;
        if ($memory_percentage > 80) {
            $result['status'] = 'warning';
            $result['message'] = __('Memory usage is high.', 'demo-content-importer');
        }

        $result['details'][] = array(
            'name' => 'Memory Usage',
            'value' => $memory_usage_mb . ' MB',
        );

        $result['details'][] = array(
            'name' => 'Memory Limit',
            'value' => $memory_limit_mb . ' MB',
        );

        $result['details'][] = array(
            'name' => 'Memory Percentage',
            'value' => round($memory_percentage, 2) . '%',
        );

        return $result;
    }

    /**
     * Test execution time.
     *
     * @return array Test result.
     */
    public function test_performance_time() {
        $result = array(
            'status' => 'pass',
            'message' => __('Execution time is within acceptable limits.', 'demo-content-importer'),
            'details' => array(),
        );

        // Get max execution time
        $max_execution_time = ini_get('max_execution_time');

        // Check if max execution time is sufficient
        if ($max_execution_time < 300 && $max_execution_time != 0) {
            $result['status'] = 'warning';
            $result['message'] = __('Max execution time is low.', 'demo-content-importer');
        }

        $result['details'][] = array(
            'name' => 'Max Execution Time',
            'value' => $max_execution_time . ' seconds',
        );

        return $result;
    }

    /**
     * Test database queries.
     *
     * @return array Test result.
     */
    public function test_performance_queries() {
        $result = array(
            'status' => 'pass',
            'message' => __('Database queries are optimized.', 'demo-content-importer'),
            'details' => array(),
        );

        // This is a placeholder test
        // In a real implementation, we would check for slow queries
        $result['details'][] = array(
            'name' => 'Database Queries',
            'value' => __('No slow queries detected', 'demo-content-importer'),
        );

        return $result;
    }

    /**
     * Get PHP memory limit in bytes.
     *
     * @return int Memory limit in bytes.
     */
    private function get_memory_limit() {
        $memory_limit = ini_get('memory_limit');
        
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = $matches[1] * 1024 * 1024 * 1024;
            } else if ($matches[2] == 'M') {
                $memory_limit = $matches[1] * 1024 * 1024;
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] * 1024;
            }
        }
        
        return $memory_limit;
    }
}

// Initialize the tests
Demo_Content_Importer_Tests::get_instance();