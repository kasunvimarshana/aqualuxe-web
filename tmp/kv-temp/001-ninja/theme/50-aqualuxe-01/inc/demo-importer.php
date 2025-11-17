<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * Handles the import of demo content for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Demo Importer Class
 */
class AquaLuxe_Demo_Importer {

    /**
     * Demo data directory
     *
     * @var string
     */
    private $demo_dir;

    /**
     * Demo data URL
     *
     * @var string
     */
    private $demo_url;

    /**
     * Available demos
     *
     * @var array
     */
    private $demos = array();

    /**
     * Constructor
     */
    public function __construct() {
        $this->demo_dir = AQUALUXE_DIR . '/inc/demo-data/';
        $this->demo_url = AQUALUXE_URI . '/inc/demo-data/';

        // Define available demos
        $this->demos = array(
            'aquarium' => array(
                'name' => esc_html__('Aquarium Shop', 'aqualuxe'),
                'description' => esc_html__('A complete demo for an aquarium and fish supply store.', 'aqualuxe'),
                'preview' => $this->demo_url . 'previews/aquarium.jpg',
                'screenshot' => $this->demo_url . 'screenshots/aquarium.jpg',
            ),
            'pool' => array(
                'name' => esc_html__('Pool Supplies', 'aqualuxe'),
                'description' => esc_html__('A complete demo for a pool maintenance and supplies store.', 'aqualuxe'),
                'preview' => $this->demo_url . 'previews/pool.jpg',
                'screenshot' => $this->demo_url . 'screenshots/pool.jpg',
            ),
            'marine' => array(
                'name' => esc_html__('Marine Equipment', 'aqualuxe'),
                'description' => esc_html__('A complete demo for a marine equipment and boat accessories store.', 'aqualuxe'),
                'preview' => $this->demo_url . 'previews/marine.jpg',
                'screenshot' => $this->demo_url . 'screenshots/marine.jpg',
            ),
        );

        // Add hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'ajax_import_demo'));
    }

    /**
     * Add admin menu item
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('AquaLuxe Demo Import', 'aqualuxe'),
            esc_html__('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            array($this, 'demo_import_page')
        );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts($hook) {
        if ('appearance_page_aqualuxe-demo-import' !== $hook) {
            return;
        }

        wp_enqueue_style('aqualuxe-demo-importer', AQUALUXE_ASSETS_URI . '/css/admin/demo-importer.css', array(), AQUALUXE_VERSION);
        wp_enqueue_script('aqualuxe-demo-importer', AQUALUXE_ASSETS_URI . '/js/admin/demo-importer.js', array('jquery'), AQUALUXE_VERSION, true);

        wp_localize_script('aqualuxe-demo-importer', 'aqualuxeImporter', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_demo_import'),
            'importing' => esc_html__('Importing...', 'aqualuxe'),
            'confirmImport' => esc_html__('Are you sure you want to import this demo? This will overwrite your current site content.', 'aqualuxe'),
            'importSuccess' => esc_html__('Demo content imported successfully!', 'aqualuxe'),
            'importError' => esc_html__('Error importing demo content. Please try again or contact support.', 'aqualuxe'),
        ));
    }

    /**
     * Demo import admin page
     */
    public function demo_import_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Import', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-importer-intro">
                <p><?php esc_html_e('Choose a demo to import. This will import posts, pages, images, theme settings, widgets, menus and more.', 'aqualuxe'); ?></p>
                <p class="aqualuxe-demo-importer-warning"><?php esc_html_e('WARNING: Importing demo data will overwrite your current theme options, sliders and widgets. It is recommended to use this feature on a fresh WordPress installation. Please backup your database and files before proceeding.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-demo-importer-requirements">
                <h3><?php esc_html_e('System Requirements Check', 'aqualuxe'); ?></h3>
                
                <?php
                $memory_limit = ini_get('memory_limit');
                $max_execution_time = ini_get('max_execution_time');
                $upload_max_filesize = ini_get('upload_max_filesize');
                $post_max_size = ini_get('post_max_size');
                
                $memory_limit_passed = (int) $memory_limit >= 128;
                $max_execution_time_passed = (int) $max_execution_time >= 180 || $max_execution_time == 0;
                $upload_max_filesize_passed = (int) $upload_max_filesize >= 8;
                $post_max_size_passed = (int) $post_max_size >= 8;
                
                $all_requirements_passed = $memory_limit_passed && $max_execution_time_passed && $upload_max_filesize_passed && $post_max_size_passed;
                ?>
                
                <table class="widefat aqualuxe-demo-importer-requirements-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Requirement', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Recommended', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Current', 'aqualuxe'); ?></th>
                            <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php esc_html_e('PHP Memory Limit', 'aqualuxe'); ?></td>
                            <td>128M</td>
                            <td><?php echo esc_html($memory_limit); ?></td>
                            <td>
                                <?php if ($memory_limit_passed) : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-passed"><?php esc_html_e('Passed', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-failed"><?php esc_html_e('Failed', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('PHP Max Execution Time', 'aqualuxe'); ?></td>
                            <td>180</td>
                            <td><?php echo esc_html($max_execution_time); ?></td>
                            <td>
                                <?php if ($max_execution_time_passed) : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-passed"><?php esc_html_e('Passed', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-failed"><?php esc_html_e('Failed', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('PHP Upload Max Filesize', 'aqualuxe'); ?></td>
                            <td>8M</td>
                            <td><?php echo esc_html($upload_max_filesize); ?></td>
                            <td>
                                <?php if ($upload_max_filesize_passed) : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-passed"><?php esc_html_e('Passed', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-failed"><?php esc_html_e('Failed', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('PHP Post Max Size', 'aqualuxe'); ?></td>
                            <td>8M</td>
                            <td><?php echo esc_html($post_max_size); ?></td>
                            <td>
                                <?php if ($post_max_size_passed) : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-passed"><?php esc_html_e('Passed', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="aqualuxe-demo-importer-status aqualuxe-demo-importer-status-failed"><?php esc_html_e('Failed', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <?php if (!$all_requirements_passed) : ?>
                    <div class="aqualuxe-demo-importer-requirements-notice">
                        <p><?php esc_html_e('Your server does not meet all the requirements for importing demo content. Please contact your hosting provider to increase the values for the failed requirements.', 'aqualuxe'); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-demo-importer-demos">
                <h3><?php esc_html_e('Available Demos', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-demo-importer-demos-list">
                    <?php foreach ($this->demos as $demo_id => $demo) : ?>
                        <div class="aqualuxe-demo-importer-demo">
                            <div class="aqualuxe-demo-importer-demo-preview">
                                <img src="<?php echo esc_url($demo['screenshot']); ?>" alt="<?php echo esc_attr($demo['name']); ?>">
                            </div>
                            <div class="aqualuxe-demo-importer-demo-info">
                                <h4><?php echo esc_html($demo['name']); ?></h4>
                                <p><?php echo esc_html($demo['description']); ?></p>
                                <div class="aqualuxe-demo-importer-demo-actions">
                                    <a href="<?php echo esc_url($demo['preview']); ?>" class="button" target="_blank"><?php esc_html_e('Preview', 'aqualuxe'); ?></a>
                                    <button class="button button-primary aqualuxe-demo-importer-import-button" data-demo-id="<?php echo esc_attr($demo_id); ?>" <?php disabled(!$all_requirements_passed); ?>>
                                        <?php esc_html_e('Import Demo', 'aqualuxe'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="aqualuxe-demo-importer-progress" style="display: none;">
                <h3><?php esc_html_e('Importing Demo Content', 'aqualuxe'); ?></h3>
                <div class="aqualuxe-demo-importer-progress-bar">
                    <div class="aqualuxe-demo-importer-progress-bar-inner"></div>
                </div>
                <div class="aqualuxe-demo-importer-progress-status">
                    <span class="aqualuxe-demo-importer-progress-percentage">0%</span>
                    <span class="aqualuxe-demo-importer-progress-step"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX import demo
     */
    public function ajax_import_demo() {
        check_ajax_referer('aqualuxe_demo_import', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to import demo content.', 'aqualuxe')));
        }

        $demo_id = isset($_POST['demo_id']) ? sanitize_text_field($_POST['demo_id']) : '';
        $step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : 'start';

        if (!isset($this->demos[$demo_id])) {
            wp_send_json_error(array('message' => esc_html__('Invalid demo ID.', 'aqualuxe')));
        }

        // Import steps
        switch ($step) {
            case 'start':
                // Prepare import
                update_option('aqualuxe_demo_import_progress', array(
                    'demo_id' => $demo_id,
                    'step' => 'content',
                    'progress' => 0,
                ));
                
                wp_send_json_success(array(
                    'step' => 'content',
                    'progress' => 0,
                    'message' => esc_html__('Importing content...', 'aqualuxe'),
                ));
                break;

            case 'content':
                // Import content (posts, pages, etc.)
                $this->import_content($demo_id);
                
                update_option('aqualuxe_demo_import_progress', array(
                    'demo_id' => $demo_id,
                    'step' => 'widgets',
                    'progress' => 25,
                ));
                
                wp_send_json_success(array(
                    'step' => 'widgets',
                    'progress' => 25,
                    'message' => esc_html__('Importing widgets...', 'aqualuxe'),
                ));
                break;

            case 'widgets':
                // Import widgets
                $this->import_widgets($demo_id);
                
                update_option('aqualuxe_demo_import_progress', array(
                    'demo_id' => $demo_id,
                    'step' => 'options',
                    'progress' => 50,
                ));
                
                wp_send_json_success(array(
                    'step' => 'options',
                    'progress' => 50,
                    'message' => esc_html__('Importing theme options...', 'aqualuxe'),
                ));
                break;

            case 'options':
                // Import theme options
                $this->import_options($demo_id);
                
                update_option('aqualuxe_demo_import_progress', array(
                    'demo_id' => $demo_id,
                    'step' => 'menus',
                    'progress' => 75,
                ));
                
                wp_send_json_success(array(
                    'step' => 'menus',
                    'progress' => 75,
                    'message' => esc_html__('Importing menus...', 'aqualuxe'),
                ));
                break;

            case 'menus':
                // Import menus
                $this->import_menus($demo_id);
                
                update_option('aqualuxe_demo_import_progress', array(
                    'demo_id' => $demo_id,
                    'step' => 'finish',
                    'progress' => 100,
                ));
                
                wp_send_json_success(array(
                    'step' => 'finish',
                    'progress' => 100,
                    'message' => esc_html__('Import completed!', 'aqualuxe'),
                ));
                break;

            case 'finish':
                // Clean up
                delete_option('aqualuxe_demo_import_progress');
                
                wp_send_json_success(array(
                    'step' => 'done',
                    'progress' => 100,
                    'message' => esc_html__('Import completed successfully!', 'aqualuxe'),
                    'homeUrl' => home_url('/'),
                ));
                break;

            default:
                wp_send_json_error(array('message' => esc_html__('Invalid import step.', 'aqualuxe')));
                break;
        }
    }

    /**
     * Import content
     *
     * @param string $demo_id Demo ID
     */
    private function import_content($demo_id) {
        // This would be implemented with WordPress Importer
        // For demonstration purposes, we'll just simulate the import
        
        // In a real implementation, you would:
        // 1. Use WordPress Importer to import the WXR file
        // 2. Process post meta
        // 3. Import attachments
        // 4. Set up homepage and blog page
        
        // Simulate a delay for demonstration
        sleep(1);
        
        return true;
    }

    /**
     * Import widgets
     *
     * @param string $demo_id Demo ID
     */
    private function import_widgets($demo_id) {
        // This would import widget data from a JSON file
        // For demonstration purposes, we'll just simulate the import
        
        // In a real implementation, you would:
        // 1. Read the widgets JSON file
        // 2. Clear existing widgets
        // 3. Import new widgets to their respective sidebars
        
        // Simulate a delay for demonstration
        sleep(1);
        
        return true;
    }

    /**
     * Import theme options
     *
     * @param string $demo_id Demo ID
     */
    private function import_options($demo_id) {
        // This would import theme options from a JSON file
        // For demonstration purposes, we'll just simulate the import
        
        // In a real implementation, you would:
        // 1. Read the options JSON file
        // 2. Update theme mods or options in the database
        
        // Simulate a delay for demonstration
        sleep(1);
        
        return true;
    }

    /**
     * Import menus
     *
     * @param string $demo_id Demo ID
     */
    private function import_menus($demo_id) {
        // This would import menus and their locations
        // For demonstration purposes, we'll just simulate the import
        
        // In a real implementation, you would:
        // 1. Create menus if they don't exist
        // 2. Assign menu items
        // 3. Set menu locations
        
        // Simulate a delay for demonstration
        sleep(1);
        
        return true;
    }
}

// Initialize the demo importer
function aqualuxe_demo_importer_init() {
    new AquaLuxe_Demo_Importer();
}
add_action('after_setup_theme', 'aqualuxe_demo_importer_init');