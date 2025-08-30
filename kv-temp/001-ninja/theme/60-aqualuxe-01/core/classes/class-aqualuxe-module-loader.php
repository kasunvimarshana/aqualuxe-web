<?php
/**
 * AquaLuxe Module Loader
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Module Loader Class
 */
class AquaLuxe_Module_Loader {
    /**
     * Available modules
     *
     * @var array
     */
    private $modules = [];

    /**
     * Module status
     *
     * @var array
     */
    private $module_status = [];

    /**
     * Constructor
     */
    public function __construct() {
        $this->scan_modules();
        $this->load_module_status();
        
        // Add admin menu for module management
        add_action('admin_menu', [$this, 'add_modules_menu']);
        
        // Handle module activation/deactivation
        add_action('admin_init', [$this, 'handle_module_actions']);
    }

    /**
     * Scan modules directory
     */
    private function scan_modules() {
        $modules_dir = AQUALUXE_MODULES_DIR;
        
        if (!is_dir($modules_dir)) {
            return;
        }
        
        $module_dirs = glob($modules_dir . '*', GLOB_ONLYDIR);
        
        foreach ($module_dirs as $module_dir) {
            $module_id = basename($module_dir);
            $config_file = $module_dir . '/config.php';
            
            if (file_exists($config_file)) {
                $config = include $config_file;
                
                if (is_array($config) && isset($config['name'])) {
                    $this->modules[$module_id] = $config;
                }
            }
        }
    }

    /**
     * Load module status
     */
    private function load_module_status() {
        $saved_status = get_option('aqualuxe_module_status', []);
        
        // Set default status for modules
        foreach ($this->modules as $module_id => $module) {
            // If module status is not saved, use default status from config
            if (!isset($saved_status[$module_id])) {
                $this->module_status[$module_id] = isset($module['default_status']) ? $module['default_status'] : false;
            } else {
                $this->module_status[$module_id] = $saved_status[$module_id];
            }
        }
    }

    /**
     * Get modules
     *
     * @return array Modules
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get module status
     *
     * @return array Module status
     */
    public function get_module_status() {
        return $this->module_status;
    }

    /**
     * Initialize modules
     */
    public function init_modules() {
        foreach ($this->modules as $module_id => $module) {
            if (isset($this->module_status[$module_id]) && $this->module_status[$module_id] === true) {
                $this->init_module($module_id);
            }
        }
    }

    /**
     * Initialize module
     *
     * @param string $module_id Module ID
     */
    private function init_module($module_id) {
        $module_dir = AQUALUXE_MODULES_DIR . $module_id;
        $init_file = $module_dir . '/init.php';
        
        if (file_exists($init_file)) {
            require_once $init_file;
            
            $init_function = 'aqualuxe_' . str_replace('-', '_', $module_id) . '_init';
            
            if (function_exists($init_function)) {
                call_user_func($init_function);
            }
        }
    }

    /**
     * Add modules menu
     */
    public function add_modules_menu() {
        add_submenu_page(
            'themes.php',
            __('AquaLuxe Modules', 'aqualuxe'),
            __('AquaLuxe Modules', 'aqualuxe'),
            'manage_options',
            'aqualuxe-modules',
            [$this, 'render_modules_page']
        );
    }

    /**
     * Render modules page
     */
    public function render_modules_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('AquaLuxe Modules', 'aqualuxe'); ?></h1>
            
            <?php
            // Show success message
            if (isset($_GET['activated']) && $_GET['activated'] === 'true') {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Module activated successfully.', 'aqualuxe') . '</p></div>';
            } elseif (isset($_GET['deactivated']) && $_GET['deactivated'] === 'true') {
                echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Module deactivated successfully.', 'aqualuxe') . '</p></div>';
            }
            ?>
            
            <div class="aqualuxe-modules-list">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php echo esc_html__('Module', 'aqualuxe'); ?></th>
                            <th><?php echo esc_html__('Description', 'aqualuxe'); ?></th>
                            <th><?php echo esc_html__('Status', 'aqualuxe'); ?></th>
                            <th><?php echo esc_html__('Actions', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->modules as $module_id => $module) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($module['name']); ?></strong>
                                </td>
                                <td>
                                    <?php echo esc_html($module['description']); ?>
                                </td>
                                <td>
                                    <?php if (isset($this->module_status[$module_id]) && $this->module_status[$module_id] === true) : ?>
                                        <span class="aqualuxe-module-status active"><?php echo esc_html__('Active', 'aqualuxe'); ?></span>
                                    <?php else : ?>
                                        <span class="aqualuxe-module-status inactive"><?php echo esc_html__('Inactive', 'aqualuxe'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($this->module_status[$module_id]) && $this->module_status[$module_id] === true) : ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('themes.php?page=aqualuxe-modules&action=deactivate&module=' . $module_id), 'aqualuxe-module-action', 'nonce')); ?>" class="button button-secondary"><?php echo esc_html__('Deactivate', 'aqualuxe'); ?></a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url(wp_nonce_url(admin_url('themes.php?page=aqualuxe-modules&action=activate&module=' . $module_id), 'aqualuxe-module-action', 'nonce')); ?>" class="button button-primary"><?php echo esc_html__('Activate', 'aqualuxe'); ?></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    /**
     * Handle module actions
     */
    public function handle_module_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'aqualuxe-modules') {
            return;
        }
        
        if (!isset($_GET['action']) || !isset($_GET['module']) || !isset($_GET['nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_GET['nonce'], 'aqualuxe-module-action')) {
            wp_die(__('Security check failed.', 'aqualuxe'));
        }
        
        $action = sanitize_text_field($_GET['action']);
        $module_id = sanitize_text_field($_GET['module']);
        
        if (!isset($this->modules[$module_id])) {
            wp_die(__('Module not found.', 'aqualuxe'));
        }
        
        if ($action === 'activate') {
            $this->module_status[$module_id] = true;
            update_option('aqualuxe_module_status', $this->module_status);
            
            wp_redirect(admin_url('themes.php?page=aqualuxe-modules&activated=true'));
            exit;
        } elseif ($action === 'deactivate') {
            $this->module_status[$module_id] = false;
            update_option('aqualuxe_module_status', $this->module_status);
            
            wp_redirect(admin_url('themes.php?page=aqualuxe-modules&deactivated=true'));
            exit;
        }
    }
}