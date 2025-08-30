
<?php
/**
 * AquaLuxe Demo Content Class
 *
 * Handles demo content import functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Demo Content Class
 */
class AquaLuxe_Demo_Content {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Demo_Content
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Demo_Content
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add demo content page to admin menu
        add_action('admin_menu', array($this, 'add_demo_content_page'));
        
        // Register AJAX handlers for demo content import
        add_action('wp_ajax_aqualuxe_import_demo_content', array($this, 'ajax_import_demo_content'));
        
        // Add demo content notice
        add_action('admin_notices', array($this, 'demo_content_notice'));
        
        // Dismiss demo content notice
        add_action('wp_ajax_aqualuxe_dismiss_demo_notice', array($this, 'ajax_dismiss_demo_notice'));
        
        // Enqueue admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add demo content page to admin menu
     */
    public function add_demo_content_page() {
        add_submenu_page(
            'themes.php',
            esc_html__('AquaLuxe Demo Content', 'aqualuxe'),
            esc_html__('AquaLuxe Demo Content', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-content',
            array($this, 'render_demo_content_page')
        );
    }

    /**
     * Render demo content page
     */
    public function render_demo_content_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Demo Content', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-content-wrapper">
                <div class="aqualuxe-demo-content-intro">
                    <p><?php esc_html_e('Welcome to AquaLuxe Demo Content Importer. This tool will help you import demo content for your AquaLuxe theme.', 'aqualuxe'); ?></p>
                    <p><?php esc_html_e('The demo content includes sample pages, posts, products, images, sliders, widgets, and theme options to help you get started with your website.', 'aqualuxe'); ?></p>
                    <p><strong><?php esc_html_e('Note: It is recommended to import demo content on a fresh WordPress installation to avoid conflicts with existing content.', 'aqualuxe'); ?></strong></p>
                </div>
                
                <div class="aqualuxe-demo-content-options">
                    <h2><?php esc_html_e('Available Demo Content', 'aqualuxe'); ?></h2>
                    
                    <div class="aqualuxe-demo-content-grid">
                        <?php $this->render_demo_content_items(); ?>
                    </div>
                </div>
                
                <div class="aqualuxe-demo-content-requirements">
                    <h2><?php esc_html_e('System Requirements', 'aqualuxe'); ?></h2>
                    
                    <ul>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('PHP Version:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo version_compare(PHP_VERSION, '7.4', '>=') ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo PHP_VERSION; ?> (<?php echo version_compare(PHP_VERSION, '7.4', '>=') ? esc_html__('Good', 'aqualuxe') : esc_html__('Minimum 7.4 required', 'aqualuxe'); ?>)
                            </span>
                        </li>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('PHP Memory Limit:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo $this->get_memory_limit() >= 128 ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo $this->get_memory_limit(); ?>MB (<?php echo $this->get_memory_limit() >= 128 ? esc_html__('Good', 'aqualuxe') : esc_html__('Minimum 128MB required', 'aqualuxe'); ?>)
                            </span>
                        </li>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('PHP Max Execution Time:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo ini_get('max_execution_time') >= 180 || ini_get('max_execution_time') == 0 ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo ini_get('max_execution_time'); ?> (<?php echo ini_get('max_execution_time') >= 180 || ini_get('max_execution_time') == 0 ? esc_html__('Good', 'aqualuxe') : esc_html__('Minimum 180 seconds required', 'aqualuxe'); ?>)
                            </span>
                        </li>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('PHP Post Max Size:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo $this->get_post_max_size() >= 32 ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo $this->get_post_max_size(); ?>MB (<?php echo $this->get_post_max_size() >= 32 ? esc_html__('Good', 'aqualuxe') : esc_html__('Minimum 32MB required', 'aqualuxe'); ?>)
                            </span>
                        </li>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('PHP Upload Max Size:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo $this->get_upload_max_filesize() >= 32 ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo $this->get_upload_max_filesize(); ?>MB (<?php echo $this->get_upload_max_filesize() >= 32 ? esc_html__('Good', 'aqualuxe') : esc_html__('Minimum 32MB required', 'aqualuxe'); ?>)
                            </span>
                        </li>
                        <li>
                            <span class="requirement-title"><?php esc_html_e('WooCommerce:', 'aqualuxe'); ?></span>
                            <span class="requirement-value <?php echo class_exists('WooCommerce') ? 'requirement-met' : 'requirement-not-met'; ?>">
                                <?php echo class_exists('WooCommerce') ? esc_html__('Installed', 'aqualuxe') : esc_html__('Not Installed', 'aqualuxe'); ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <style>
            .aqualuxe-demo-content-wrapper {
                margin-top: 20px;
            }
            
            .aqualuxe-demo-content-intro {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
                margin-bottom: 20px;
            }
            
            .aqualuxe-demo-content-options {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
                margin-bottom: 20px;
            }
            
            .aqualuxe-demo-content-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                grid-gap: 20px;
                margin-top: 20px;
            }
            
            .aqualuxe-demo-content-item {
                border: 1px solid #ddd;
                border-radius: 5px;
                overflow: hidden;
                transition: all 0.3s ease;
            }
            
            .aqualuxe-demo-content-item:hover {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .aqualuxe-demo-content-item-image {
                height: 200px;
                overflow: hidden;
            }
            
            .aqualuxe-demo-content-item-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .aqualuxe-demo-content-item-info {
                padding: 15px;
            }
            
            .aqualuxe-demo-content-item-title {
                margin-top: 0;
                margin-bottom: 10px;
                font-size: 18px;
            }
            
            .aqualuxe-demo-content-item-description {
                margin-bottom: 15px;
                color: #666;
            }
            
            .aqualuxe-demo-content-item-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .aqualuxe-demo-content-item-actions .button {
                flex: 1;
                text-align: center;
            }
            
            .aqualuxe-demo-content-item-actions .button:first-child {
                margin-right: 5px;
            }
            
            .aqualuxe-demo-content-requirements {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            }
            
            .aqualuxe-demo-content-requirements ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .aqualuxe-demo-content-requirements li {
                padding: 10px 0;
                border-bottom: 1px solid #f0f0f0;
                display: flex;
                justify-content: space-between;
            }
            
            .aqualuxe-demo-content-requirements li:last-child {
                border-bottom: none;
            }
            
            .requirement-title {
                font-weight: 600;
            }
            
            .requirement-met {
                color: #46b450;
            }
            
            .requirement-not-met {
                color: #dc3232;
            }
            
            .aqualuxe-import-progress {
                display: none;
                margin-top: 20px;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 5px;
            }
            
            .aqualuxe-import-progress-bar {
                height: 20px;
                background-color: #ddd;
                border-radius: 5px;
                margin-bottom: 10px;
                overflow: hidden;
            }
            
            .aqualuxe-import-progress-bar-inner {
                height: 100%;
                background-color: #0073aa;
                width: 0;
                transition: width 0.3s ease;
            }
            
            .aqualuxe-import-progress-status {
                font-weight: 600;
            }
            
            .aqualuxe-import-progress-log {
                margin-top: 10px;
                max-height: 200px;
                overflow-y: auto;
                padding: 10px;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-family: monospace;
                font-size: 12px;
            }
        </style>
        <?php
    }

    /**
     * Render demo content items
     */
    private function render_demo_content_items() {
        $demo_content_items = $this->get_demo_content_items();
        
        foreach ($demo_content_items as $id => $item) {
            ?>
            <div class="aqualuxe-demo-content-item" data-id="<?php echo esc_attr($id); ?>">
                <div class="aqualuxe-demo-content-item-image">
                    <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" />
                </div>
                <div class="aqualuxe-demo-content-item-info">
                    <h3 class="aqualuxe-demo-content-item-title"><?php echo esc_html($item['title']); ?></h3>
                    <p class="aqualuxe-demo-content-item-description"><?php echo esc_html($item['description']); ?></p>
                    <div class="aqualuxe-demo-content-item-actions">
                        <a href="<?php echo esc_url($item['preview_url']); ?>" class="button" target="_blank"><?php esc_html_e('Preview', 'aqualuxe'); ?></a>
                        <button class="button button-primary aqualuxe-import-demo-content" data-id="<?php echo esc_attr($id); ?>"><?php esc_html_e('Import', 'aqualuxe'); ?></button>
                    </div>
                    
                    <div class="aqualuxe-import-progress" id="aqualuxe-import-progress-<?php echo esc_attr($id); ?>">
                        <div class="aqualuxe-import-progress-bar">
                            <div class="aqualuxe-import-progress-bar-inner"></div>
                        </div>
                        <div class="aqualuxe-import-progress-status"><?php esc_html_e('Importing...', 'aqualuxe'); ?> <span class="aqualuxe-import-progress-percentage">0%</span></div>
                        <div class="aqualuxe-import-progress-log"></div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Get demo content items
     *
     * @return array Demo content items.
     */
    private function get_demo_content_items() {
        return array(
            'main' => array(
                'title' => esc_html__('Main Demo', 'aqualuxe'),
                'description' => esc_html__('Complete demo with all features including homepage, shop, blog, and more.', 'aqualuxe'),
                'image' => AQUALUXE_ASSETS_URI . '/images/demo/main-demo.jpg',
                'preview_url' => 'https://demo.aqualuxe.com/main/',
                'import_steps' => array(
                    'theme_options',
                    'widgets',
                    'sliders',
                    'content',
                    'menus',
                    'homepage',
                ),
            ),
            'minimal' => array(
                'title' => esc_html__('Minimal Demo', 'aqualuxe'),
                'description' => esc_html__('Clean and minimal design with essential features.', 'aqualuxe'),
                'image' => AQUALUXE_ASSETS_URI . '/images/demo/minimal-demo.jpg',
                'preview_url' => 'https://demo.aqualuxe.com/minimal/',
                'import_steps' => array(
                    'theme_options',
                    'widgets',
                    'content',
                    'menus',
                    'homepage',
                ),
            ),
            'shop' => array(
                'title' => esc_html__('Shop Demo', 'aqualuxe'),
                'description' => esc_html__('Focused on e-commerce with multiple product layouts and shop pages.', 'aqualuxe'),
                'image' => AQUALUXE_ASSETS_URI . '/images/demo/shop-demo.jpg',
                'preview_url' => 'https://demo.aqualuxe.com/shop/',
                'import_steps' => array(
                    'theme_options',
                    'widgets',
                    'content',
                    'products',
                    'menus',
                    'homepage',
                ),
            ),
        );
    }

    /**
     * Get memory limit
     *
     * @return int Memory limit in MB.
     */
    private function get_memory_limit() {
        $memory_limit = ini_get('memory_limit');
        
        if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
            if ($matches[2] == 'G') {
                $memory_limit = $matches[1] * 1024;
            } else if ($matches[2] == 'M') {
                $memory_limit = $matches[1];
            } else if ($matches[2] == 'K') {
                $memory_limit = $matches[1] / 1024;
            }
        }
        
        return (int) $memory_limit;
    }

    /**
     * Get post max size
     *
     * @return int Post max size in MB.
     */
    private function get_post_max_size() {
        $post_max_size = ini_get('post_max_size');
        
        if (preg_match('/^(\d+)(.)$/', $post_max_size, $matches)) {
            if ($matches[2] == 'G') {
                $post_max_size = $matches[1] * 1024;
            } else if ($matches[2] == 'M') {
                $post_max_size = $matches[1];
            } else if ($matches[2] == 'K') {
                $post_max_size = $matches[1] / 1024;
            }
        }
        
        return (int) $post_max_size;
    }

    /**
     * Get upload max filesize
     *
     * @return int Upload max filesize in MB.
     */
    private function get_upload_max_filesize() {
        $upload_max_filesize = ini_get('upload_max_filesize');
        
        if (preg_match('/^(\d+)(.)$/', $upload_max_filesize, $matches)) {
            if ($matches[2] == 'G') {
                $upload_max_filesize = $matches[1] * 1024;
            } else if ($matches[2] == 'M') {
                $upload_max_filesize = $matches[1];
            } else if ($matches[2] == 'K') {
                $upload_max_filesize = $matches[1] / 1024;
            }
        }
        
        return (int) $upload_max_filesize;
    }

    /**
     * AJAX import demo content
     */
    public function ajax_import_demo_content() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_import_demo_content')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => esc_html__('You do not have permission to import demo content.', 'aqualuxe')));
            exit;
        }
        
        // Get demo ID
        $demo_id = isset($_POST['demo_id']) ? sanitize_text_field($_POST['demo_id']) : '';
        
        if (empty($demo_id)) {
            wp_send_json_error(array('message' => esc_html__('Demo ID is required.', 'aqualuxe')));
            exit;
        }
        
        // Get demo content items
        $demo_content_items = $this->get_demo_content_items();
        
        if (!isset($demo_content_items[$demo_id])) {
            wp_send_json_error(array('message' => esc_html__('Demo content not found.', 'aqualuxe')));
            exit;
        }
        
        // Get import step
        $step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : '';
        
        if (empty($step)) {
            // Start import
            $import_steps = $demo_content_items[$demo_id]['import_steps'];
            $total_steps = count($import_steps);
            
            wp_send_json_success(array(
                'message' => esc_html__('Import started.', 'aqualuxe'),
                'step' => $import_steps[0],
                'current_step' => 1,
                'total_steps' => $total_steps,
                'percentage' => 0,
            ));
            exit;
        }
        
        // Import step
        $import_steps = $demo_content_items[$demo_id]['import_steps'];
        $total_steps = count($import_steps);
        $current_step = array_search($step, $import_steps) + 1;
        $percentage = round(($current_step / $total_steps) * 100);
        
        // Process import step
        $result = $this->process_import_step($demo_id, $step);
        
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
            exit;
        }
        
        // Check if there are more steps
        if ($current_step < $total_steps) {
            $next_step = $import_steps[$current_step];
            
            wp_send_json_success(array(
                'message' => sprintf(esc_html__('Step %1$s of %2$s completed.', 'aqualuxe'), $current_step, $total_steps),
                'step' => $next_step,
                'current_step' => $current_step + 1,
                'total_steps' => $total_steps,
                'percentage' => $percentage,
                'log' => $result,
            ));
        } else {
            // Import completed
            update_option('aqualuxe_demo_content_imported', $demo_id);
            
            wp_send_json_success(array(
                'message' => esc_html__('Import completed successfully.', 'aqualuxe'),
                'step' => 'completed',
                'current_step' => $total_steps,
                'total_steps' => $total_steps,
                'percentage' => 100,
                'log' => $result,
            ));
        }
        
        exit;
    }

    /**
     * Process import step
     *
     * @param string $demo_id Demo ID.
     * @param string $step    Import step.
     * @return string|WP_Error Import step result or error.
     */
    private function process_import_step($demo_id, $step) {
        // Get demo content directory
        $demo_content_dir = AQUALUXE_DIR . '/inc/demo-content/' . $demo_id;
        
        // Check if demo content directory exists
        if (!file_exists($demo_content_dir)) {
            return new WP_Error('demo_content_dir_not_found', sprintf(esc_html__('Demo content directory not found: %s', 'aqualuxe'), $demo_content_dir));
        }
        
        // Process import step
        switch ($step) {
            case 'theme_options':
                return $this->import_theme_options($demo_id, $demo_content_dir);
                
            case 'widgets':
                return $this->import_widgets($demo_id, $demo_content_dir);
                
            case 'sliders':
                return $this->import_sliders($demo_id, $demo_content_dir);
                
            case 'content':
                return $this->import_content($demo_id, $demo_content_dir);
                
            case 'products':
                return $this->import_products($demo_id, $demo_content_dir);
                
            case 'menus':
                return $this->import_menus($demo_id, $demo_content_dir);
                
            case 'homepage':
                return $this->import_homepage($demo_id, $demo_content_dir);
                
            default:
                return new WP_Error('invalid_step', sprintf(esc_html__('Invalid import step: %s', 'aqualuxe'), $step));
        }
    }

    /**
     * Import theme options
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_theme_options($demo_id, $demo_content_dir) {
        // Check if theme options file exists
        $theme_options_file = $demo_content_dir . '/theme-options.json';
        
        if (!file_exists($theme_options_file)) {
            return esc_html__('Theme options file not found.', 'aqualuxe');
        }
        
        // Get theme options
        $theme_options = json_decode(file_get_contents($theme_options_file), true);
        
        if (empty($theme_options)) {
            return esc_html__('Theme options file is empty or invalid.', 'aqualuxe');
        }
        
        // Import theme options
        foreach ($theme_options as $option_name => $option_value) {
            update_option($option_name, $option_value);
        }
        
        return esc_html__('Theme options imported successfully.', 'aqualuxe');
    }

    /**
     * Import widgets
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_widgets($demo_id, $demo_content_dir) {
        // Check if widgets file exists
        $widgets_file = $demo_content_dir . '/widgets.json';
        
        if (!file_exists($widgets_file)) {
            return esc_html__('Widgets file not found.', 'aqualuxe');
        }
        
        // Get widgets
        $widgets = json_decode(file_get_contents($widgets_file), true);
        
        if (empty($widgets)) {
            return esc_html__('Widgets file is empty or invalid.', 'aqualuxe');
        }
        
        // Import widgets
        foreach ($widgets as $sidebar_id => $sidebar_widgets) {
            $sidebars_widgets = get_option('sidebars_widgets', array());
            
            if (!isset($sidebars_widgets[$sidebar_id])) {
                $sidebars_widgets[$sidebar_id] = array();
            }
            
            foreach ($sidebar_widgets as $widget_id => $widget_data) {
                $widget_name = explode('-', $widget_id);
                $widget_name = $widget_name[0];
                
                $widget_options = get_option('widget_' . $widget_name, array());
                
                if (!is_array($widget_options)) {
                    $widget_options = array();
                }
                
                $widget_options[] = $widget_data;
                
                end($widget_options);
                $widget_index = key($widget_options);
                
                update_option('widget_' . $widget_name, $widget_options);
                
                $sidebars_widgets[$sidebar_id][] = $widget_name . '-' . $widget_index;
            }
            
            update_option('sidebars_widgets', $sidebars_widgets);
        }
        
        return esc_html__('Widgets imported successfully.', 'aqualuxe');
    }

    /**
     * Import sliders
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_sliders($demo_id, $demo_content_dir) {
        // Check if sliders file exists
        $sliders_file = $demo_content_dir . '/sliders.json';
        
        if (!file_exists($sliders_file)) {
            return esc_html__('Sliders file not found.', 'aqualuxe');
        }
        
        // Check if Revolution Slider is active
        if (!class_exists('RevSlider')) {
            return esc_html__('Revolution Slider plugin is not active.', 'aqualuxe');
        }
        
        // Get sliders
        $sliders = json_decode(file_get_contents($sliders_file), true);
        
        if (empty($sliders)) {
            return esc_html__('Sliders file is empty or invalid.', 'aqualuxe');
        }
        
        // Import sliders
        $slider_count = 0;
        
        foreach ($sliders as $slider) {
            $slider_file = $demo_content_dir . '/sliders/' . $slider;
            
            if (file_exists($slider_file)) {
                $revslider = new RevSlider();
                $revslider->importSliderFromPost(true, true, $slider_file);
                $slider_count++;
            }
        }
        
        return sprintf(esc_html__('%d sliders imported successfully.', 'aqualuxe'), $slider_count);
    }

    /**
     * Import content
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_content($demo_id, $demo_content_dir) {
        // Check if content file exists
        $content_file = $demo_content_dir . '/content.xml';
        
        if (!file_exists($content_file)) {
            return esc_html__('Content file not found.', 'aqualuxe');
        }
        
        // Check if WordPress Importer is available
        if (!class_exists('WP_Import')) {
            // Include WordPress Importer
            if (!file_exists(ABSPATH . 'wp-admin/includes/import.php')) {
                return esc_html__('WordPress Importer not found.', 'aqualuxe');
            }
            
            require_once ABSPATH . 'wp-admin/includes/import.php';
            
            if (!file_exists(ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php')) {
                return esc_html__('WordPress Importer plugin not found.', 'aqualuxe');
            }
            
            require_once ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
        }
        
        // Import content
        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        
        ob_start();
        $wp_import->import($content_file);
        ob_end_clean();
        
        return esc_html__('Content imported successfully.', 'aqualuxe');
    }

    /**
     * Import products
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_products($demo_id, $demo_content_dir) {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return esc_html__('WooCommerce plugin is not active.', 'aqualuxe');
        }
        
        // Check if products file exists
        $products_file = $demo_content_dir . '/products.xml';
        
        if (!file_exists($products_file)) {
            return esc_html__('Products file not found.', 'aqualuxe');
        }
        
        // Check if WordPress Importer is available
        if (!class_exists('WP_Import')) {
            // Include WordPress Importer
            if (!file_exists(ABSPATH . 'wp-admin/includes/import.php')) {
                return esc_html__('WordPress Importer not found.', 'aqualuxe');
            }
            
            require_once ABSPATH . 'wp-admin/includes/import.php';
            
            if (!file_exists(ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php')) {
                return esc_html__('WordPress Importer plugin not found.', 'aqualuxe');
            }
            
            require_once ABSPATH . 'wp-content/plugins/wordpress-importer/wordpress-importer.php';
        }
        
        // Import products
        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        
        ob_start();
        $wp_import->import($products_file);
        ob_end_clean();
        
        return esc_html__('Products imported successfully.', 'aqualuxe');
    }

    /**
     * Import menus
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_menus($demo_id, $demo_content_dir) {
        // Check if menus file exists
        $menus_file = $demo_content_dir . '/menus.json';
        
        if (!file_exists($menus_file)) {
            return esc_html__('Menus file not found.', 'aqualuxe');
        }
        
        // Get menus
        $menus = json_decode(file_get_contents($menus_file), true);
        
        if (empty($menus)) {
            return esc_html__('Menus file is empty or invalid.', 'aqualuxe');
        }
        
        // Import menus
        foreach ($menus as $menu_location => $menu_name) {
            $menu = wp_get_nav_menu_object($menu_name);
            
            if ($menu) {
                $locations = get_theme_mod('nav_menu_locations');
                $locations[$menu_location] = $menu->term_id;
                set_theme_mod('nav_menu_locations', $locations);
            }
        }
        
        return esc_html__('Menus imported successfully.', 'aqualuxe');
    }

    /**
     * Import homepage
     *
     * @param string $demo_id         Demo ID.
     * @param string $demo_content_dir Demo content directory.
     * @return string Import result.
     */
    private function import_homepage($demo_id, $demo_content_dir) {
        // Check if homepage file exists
        $homepage_file = $demo_content_dir . '/homepage.json';
        
        if (!file_exists($homepage_file)) {
            return esc_html__('Homepage file not found.', 'aqualuxe');
        }
        
        // Get homepage
        $homepage = json_decode(file_get_contents($homepage_file), true);
        
        if (empty($homepage)) {
            return esc_html__('Homepage file is empty or invalid.', 'aqualuxe');
        }
        
        // Import homepage
        if (isset($homepage['page_on_front'])) {
            $page = get_page_by_title($homepage['page_on_front']);
            
            if ($page) {
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
            }
        }
        
        if (isset($homepage['page_for_posts'])) {
            $page = get_page_by_title($homepage['page_for_posts']);
            
            if ($page) {
                update_option('page_for_posts', $page->ID);
            }
        }
        
        return esc_html__('Homepage imported successfully.', 'aqualuxe');
    }

    /**
     * Demo content notice
     */
    public function demo_content_notice() {
        // Check if notice is dismissed
        $dismissed = get_option('aqualuxe_demo_notice_dismissed', false);
        
        if ($dismissed) {
            return;
        }
        
        // Check if demo content is imported
        $imported = get_option('aqualuxe_demo_content_imported', false);
        
        if ($imported) {
            return;
        }
        
        // Check if current screen is admin dashboard or themes page
        $screen = get_current_screen();
        
        if (!$screen || !in_array($screen->id, array('dashboard', 'themes'))) {
            return;
        }
        
        ?>
        <div class="notice notice-info is-dismissible aqualuxe-demo-notice">
            <h3><?php esc_html_e('Welcome to AquaLuxe Theme!', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Would you like to import demo content to get started quickly?', 'aqualuxe'); ?></p>
            <p>
                <a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-content')); ?>" class="button button-primary"><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></a>
                <a href="#" class="button aqualuxe-dismiss-demo-notice"><?php esc_html_e('Dismiss', 'aqualuxe'); ?></a>
            </p>
        </div>
        <?php
    }

    /**
     * AJAX dismiss demo notice
     */
    public function ajax_dismiss_demo_notice() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_dismiss_demo_notice')) {
            wp_send_json_error(array('message' => esc_html__('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        // Update option
        update_option('aqualuxe_demo_notice_dismissed', true);
        
        wp_send_json_success(array('message' => esc_html__('Notice dismissed.', 'aqualuxe')));
        exit;
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook_suffix Admin page hook suffix.
     */
    public function enqueue_admin_scripts($hook_suffix) {
        // Enqueue scripts for demo content page
        if ('appearance_page_aqualuxe-demo-content' === $hook_suffix) {
            wp_enqueue_script('aqualuxe-demo-content', AQUALUXE_ASSETS_URI . '/js/admin/demo-content.js', array('jquery'), AQUALUXE_VERSION, true);
            
            wp_localize_script('aqualuxe-demo-content', 'aqualuxe_demo_content', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_import_demo_content'),
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'import_complete' => esc_html__('Import Complete', 'aqualuxe'),
                'import_error' => esc_html__('Import Error', 'aqualuxe'),
                'confirm_import' => esc_html__('Are you sure you want to import this demo content? This will overwrite your current content.', 'aqualuxe'),
            ));
        }
        
        // Enqueue scripts for demo notice
        if (in_array($hook_suffix, array('index.php', 'themes.php'))) {
            wp_enqueue_script('aqualuxe-demo-notice', AQUALUXE_ASSETS_URI . '/js/admin/demo-notice.js', array('jquery'), AQUALUXE_VERSION, true);
            
            wp_localize_script('aqualuxe-demo-notice', 'aqualuxe_demo_notice', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_dismiss_demo_notice'),
            ));
        }
    }
}
