<?php
/**
 * Module Admin Interface
 *
 * Admin interface for managing AquaLuxe modules
 *
 * @package AquaLuxe\Core\Admin
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

use AquaLuxe\Core\ModuleManager;

/**
 * Module Admin Class
 */
class AquaLuxe_Module_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_aqualuxe_toggle_module', array($this, 'ajax_toggle_module'));
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('AquaLuxe Modules', 'aqualuxe'),
            __('Theme Modules', 'aqualuxe'),
            'manage_options',
            'aqualuxe-modules',
            array($this, 'admin_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_aqualuxe-modules') {
            return;
        }

        wp_enqueue_script(
            'aqualuxe-module-admin',
            AQUALUXE_ASSETS_URI . '/js/admin/module-admin.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-module-admin', 'aqualuxeModuleAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_module_admin'),
            'strings' => array(
                'activating' => __('Activating...', 'aqualuxe'),
                'deactivating' => __('Deactivating...', 'aqualuxe'),
                'error' => __('An error occurred. Please try again.', 'aqualuxe'),
                'confirmDeactivate' => __('Are you sure you want to deactivate this module?', 'aqualuxe')
            )
        ));

        wp_enqueue_style(
            'aqualuxe-module-admin',
            AQUALUXE_ASSETS_URI . '/css/admin/module-admin.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Admin page content
     */
    public function admin_page() {
        $module_manager = ModuleManager::get_instance();
        $modules = $module_manager->get_loaded_modules();
        $all_configs = $this->get_all_module_configs();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Theme Modules', 'aqualuxe'); ?></h1>
            
            <p class="description">
                <?php esc_html_e('Manage your theme modules. Enable or disable features as needed for your site.', 'aqualuxe'); ?>
            </p>

            <div class="aqualuxe-modules-grid">
                <?php foreach ($all_configs as $module_id => $config): ?>
                    <?php $this->render_module_card($module_id, $config, $module_manager); ?>
                <?php endforeach; ?>
            </div>

            <div class="aqualuxe-module-info">
                <h2><?php esc_html_e('Module Information', 'aqualuxe'); ?></h2>
                <p><?php esc_html_e('AquaLuxe uses a modular architecture to provide flexible functionality. Each module can be independently enabled or disabled based on your needs.', 'aqualuxe'); ?></p>
                
                <h3><?php esc_html_e('Module Categories', 'aqualuxe'); ?></h3>
                <ul>
                    <li><strong><?php esc_html_e('Core:', 'aqualuxe'); ?></strong> <?php esc_html_e('Essential functionality modules', 'aqualuxe'); ?></li>
                    <li><strong><?php esc_html_e('Business:', 'aqualuxe'); ?></strong> <?php esc_html_e('Business and e-commerce features', 'aqualuxe'); ?></li>
                    <li><strong><?php esc_html_e('Marketing:', 'aqualuxe'); ?></strong> <?php esc_html_e('Marketing and promotion tools', 'aqualuxe'); ?></li>
                    <li><strong><?php esc_html_e('UI:', 'aqualuxe'); ?></strong> <?php esc_html_e('User interface enhancements', 'aqualuxe'); ?></li>
                    <li><strong><?php esc_html_e('Content:', 'aqualuxe'); ?></strong> <?php esc_html_e('Content management features', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render module card
     */
    private function render_module_card($module_id, $config, $module_manager) {
        $is_enabled = $config['enabled'] ?? false;
        $is_loaded = $module_manager->is_module_loaded($module_id);
        $has_dependencies = !empty($config['dependencies']);
        $category = $config['category'] ?? 'other';
        ?>
        <div class="aqualuxe-module-card <?php echo esc_attr($category); ?>" data-module="<?php echo esc_attr($module_id); ?>">
            <div class="module-header">
                <h3 class="module-title"><?php echo esc_html($config['name']); ?></h3>
                <span class="module-version">v<?php echo esc_html($config['version']); ?></span>
                <span class="module-category"><?php echo esc_html(ucfirst($category)); ?></span>
            </div>
            
            <div class="module-description">
                <p><?php echo esc_html($config['description']); ?></p>
            </div>

            <?php if ($has_dependencies): ?>
            <div class="module-dependencies">
                <strong><?php esc_html_e('Dependencies:', 'aqualuxe'); ?></strong>
                <?php echo esc_html(implode(', ', $config['dependencies'])); ?>
            </div>
            <?php endif; ?>

            <div class="module-status">
                <span class="status-indicator <?php echo $is_enabled ? 'enabled' : 'disabled'; ?>">
                    <?php echo $is_enabled ? esc_html__('Enabled', 'aqualuxe') : esc_html__('Disabled', 'aqualuxe'); ?>
                </span>
                
                <?php if ($is_enabled && !$is_loaded): ?>
                <span class="status-warning" title="<?php esc_attr_e('Module is enabled but not loaded (dependency issue)', 'aqualuxe'); ?>">
                    ⚠️
                </span>
                <?php endif; ?>
            </div>

            <div class="module-actions">
                <button 
                    type="button" 
                    class="button button-primary toggle-module" 
                    data-module="<?php echo esc_attr($module_id); ?>"
                    data-action="<?php echo $is_enabled ? 'disable' : 'enable'; ?>"
                >
                    <?php echo $is_enabled ? esc_html__('Disable', 'aqualuxe') : esc_html__('Enable', 'aqualuxe'); ?>
                </button>
                
                <?php if (isset($config['settings_url'])): ?>
                <a href="<?php echo esc_url($config['settings_url']); ?>" class="button">
                    <?php esc_html_e('Settings', 'aqualuxe'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get all module configurations
     */
    private function get_all_module_configs() {
        $module_manager = ModuleManager::get_instance();
        
        // Get loaded modules
        $loaded_modules = $module_manager->get_loaded_modules();
        
        // Get all possible modules by scanning the modules directory
        $all_modules = array();
        
        $modules_dir = AQUALUXE_MODULES_DIR;
        if (is_dir($modules_dir)) {
            $module_dirs = array_filter(glob($modules_dir . '/*'), 'is_dir');
            
            foreach ($module_dirs as $module_dir) {
                $module_name = basename($module_dir);
                $module_file = $module_dir . '/module.php';
                
                if (file_exists($module_file)) {
                    // Try to get config from loaded modules first
                    if (isset($loaded_modules[$module_name])) {
                        $all_modules[$module_name] = $loaded_modules[$module_name];
                    } else {
                        // Create default config for unloaded modules
                        $all_modules[$module_name] = array(
                            'name' => ucfirst(str_replace('-', ' ', $module_name)),
                            'description' => sprintf(__('The %s module for AquaLuxe theme.', 'aqualuxe'), $module_name),
                            'enabled' => false,
                            'dependencies' => array(),
                            'file' => $module_name . '/module.php',
                            'version' => '1.0.0',
                            'category' => 'other',
                        );
                    }
                }
            }
        }
        
        return $all_modules;
    }

    /**
     * Handle AJAX module toggle
     */
    public function ajax_toggle_module() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_module_admin')) {
            wp_send_json_error(__('Security check failed.', 'aqualuxe'));
            return;
        }

        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'aqualuxe'));
            return;
        }

        $module_id = sanitize_key($_POST['module'] ?? '');
        $action = sanitize_key($_POST['action'] ?? '');

        if (empty($module_id) || !in_array($action, array('enable', 'disable'), true)) {
            wp_send_json_error(__('Invalid request.', 'aqualuxe'));
            return;
        }

        $module_manager = ModuleManager::get_instance();

        if ($action === 'enable') {
            $result = $module_manager->enable_module($module_id);
            $message = $result 
                ? __('Module enabled successfully.', 'aqualuxe')
                : __('Failed to enable module.', 'aqualuxe');
        } else {
            $result = $module_manager->disable_module($module_id);
            $message = $result 
                ? __('Module disabled successfully.', 'aqualuxe')
                : __('Failed to disable module.', 'aqualuxe');
        }

        if ($result) {
            wp_send_json_success(array(
                'message' => $message,
                'module' => $module_id,
                'action' => $action,
                'new_status' => $action === 'enable' ? 'enabled' : 'disabled'
            ));
        } else {
            wp_send_json_error($message);
        }
    }

    /**
     * Display admin notices
     */
    public function admin_notices() {
        $screen = get_current_screen();
        
        if ($screen && $screen->id === 'appearance_page_aqualuxe-modules') {
            // Check for dependency issues
            $module_manager = ModuleManager::get_instance();
            $loaded_modules = $module_manager->get_loaded_modules();
            $all_configs = $this->get_all_module_configs();
            
            $dependency_issues = array();
            
            foreach ($all_configs as $module_id => $config) {
                if ($config['enabled'] && !isset($loaded_modules[$module_id])) {
                    $dependency_issues[] = $config['name'];
                }
            }
            
            if (!empty($dependency_issues)) {
                ?>
                <div class="notice notice-warning">
                    <p>
                        <strong><?php esc_html_e('Module Dependency Issues:', 'aqualuxe'); ?></strong>
                        <?php esc_html_e('The following modules are enabled but not loaded due to dependency issues:', 'aqualuxe'); ?>
                        <?php echo esc_html(implode(', ', $dependency_issues)); ?>
                    </p>
                </div>
                <?php
            }
        }
    }
}

// Initialize the admin interface
if (is_admin()) {
    new AquaLuxe_Module_Admin();
}