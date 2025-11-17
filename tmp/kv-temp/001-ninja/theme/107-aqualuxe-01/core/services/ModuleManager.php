<?php
/**
 * Module Manager Service
 *
 * Manages theme modules and their lifecycle
 *
 * @package AquaLuxe\Core\Services
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Services;

use AquaLuxe\Core\Interfaces\ServiceInterface;
use AquaLuxe\Core\Interfaces\ModuleInterface;

/**
 * Module Manager Class
 */
class ModuleManager implements ServiceInterface {
    
    /**
     * Available modules configuration
     *
     * @var array
     */
    private $module_config = [];
    
    /**
     * Loaded modules
     *
     * @var array
     */
    private $modules = [];
    
    /**
     * Module dependencies
     *
     * @var array
     */
    private $dependencies = [];
    
    /**
     * Constructor
     *
     * @param array $module_config
     */
    public function __construct($module_config = []) {
        $this->module_config = $module_config;
    }
    
    /**
     * Initialize module manager
     *
     * @return void
     */
    public function init(): void {
        // Load available modules
        $this->discover_modules();
        
        // Initialize enabled modules
        $this->initialize_modules();
        
        // Setup module hooks
        add_action('admin_menu', [$this, 'add_module_admin_page']);
        add_action('wp_ajax_toggle_module', [$this, 'ajax_toggle_module']);
    }
    
    /**
     * Get service name
     *
     * @return string
     */
    public function getName(): string {
        return 'ModuleManager';
    }
    
    /**
     * Discover available modules
     *
     * @return void
     */
    private function discover_modules(): void {
        $modules_dir = AQUALUXE_PATH . '/modules';
        
        if (!is_dir($modules_dir)) {
            return;
        }
        
        $module_dirs = array_filter(glob($modules_dir . '/*'), 'is_dir');
        
        foreach ($module_dirs as $module_dir) {
            $module_name = basename($module_dir);
            $module_file = $module_dir . '/' . ucfirst(str_replace('-', '', $module_name)) . '.php';
            
            if (file_exists($module_file)) {
                require_once $module_file;
                
                // Try to instantiate the module class
                $class_name = 'AquaLuxe\\Modules\\' . ucfirst(str_replace('-', '', $module_name)) . '\\' . ucfirst(str_replace('-', '', $module_name));
                
                if (class_exists($class_name)) {
                    $this->modules[$module_name] = [
                        'class' => $class_name,
                        'path' => $module_dir,
                        'file' => $module_file,
                        'enabled' => $this->is_module_enabled($module_name),
                        'instance' => null,
                    ];
                }
            }
        }
    }
    
    /**
     * Initialize enabled modules
     *
     * @return void
     */
    private function initialize_modules(): void {
        // Sort modules by dependencies
        $sorted_modules = $this->sort_modules_by_dependencies();
        
        foreach ($sorted_modules as $module_name) {
            if ($this->is_module_enabled($module_name)) {
                $this->load_module($module_name);
            }
        }
    }
    
    /**
     * Load a specific module
     *
     * @param string $module_name
     * @return bool
     */
    public function load_module($module_name): bool {
        if (!isset($this->modules[$module_name])) {
            return false;
        }
        
        $module_data = $this->modules[$module_name];
        
        if ($module_data['instance'] !== null) {
            return true; // Already loaded
        }
        
        // Check dependencies
        if (!$this->check_module_dependencies($module_name)) {
            return false;
        }
        
        try {
            $instance = new $module_data['class']();
            
            if ($instance instanceof ModuleInterface) {
                $instance->init();
                $this->modules[$module_name]['instance'] = $instance;
                
                do_action('aqualuxe_module_loaded', $module_name, $instance);
                
                return true;
            }
        } catch (Exception $e) {
            error_log('AquaLuxe Module Error (' . $module_name . '): ' . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Unload a specific module
     *
     * @param string $module_name
     * @return bool
     */
    public function unload_module($module_name): bool {
        if (!isset($this->modules[$module_name]) || $this->modules[$module_name]['instance'] === null) {
            return false;
        }
        
        $instance = $this->modules[$module_name]['instance'];
        
        if ($instance instanceof ModuleInterface) {
            $instance->disable();
            $this->modules[$module_name]['instance'] = null;
            
            do_action('aqualuxe_module_unloaded', $module_name, $instance);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if module is enabled
     *
     * @param string $module_name
     * @return bool
     */
    public function is_module_enabled($module_name): bool {
        // Check theme mod first
        $enabled = get_theme_mod('module_' . str_replace('-', '_', $module_name) . '_enabled', null);
        
        if ($enabled !== null) {
            return (bool) $enabled;
        }
        
        // Fall back to config
        return $this->module_config[$module_name] ?? false;
    }
    
    /**
     * Enable a module
     *
     * @param string $module_name
     * @return bool
     */
    public function enable_module($module_name): bool {
        if (!isset($this->modules[$module_name])) {
            return false;
        }
        
        // Update theme mod
        set_theme_mod('module_' . str_replace('-', '_', $module_name) . '_enabled', true);
        
        // Load the module
        return $this->load_module($module_name);
    }
    
    /**
     * Disable a module
     *
     * @param string $module_name
     * @return bool
     */
    public function disable_module($module_name): bool {
        if (!isset($this->modules[$module_name])) {
            return false;
        }
        
        // Update theme mod
        set_theme_mod('module_' . str_replace('-', '_', $module_name) . '_enabled', false);
        
        // Unload the module
        return $this->unload_module($module_name);
    }
    
    /**
     * Get module instance
     *
     * @param string $module_name
     * @return ModuleInterface|null
     */
    public function get_module($module_name): ?ModuleInterface {
        if (isset($this->modules[$module_name]) && $this->modules[$module_name]['instance']) {
            return $this->modules[$module_name]['instance'];
        }
        
        return null;
    }
    
    /**
     * Get all modules
     *
     * @return array
     */
    public function get_modules(): array {
        return $this->modules;
    }
    
    /**
     * Check module dependencies
     *
     * @param string $module_name
     * @return bool
     */
    private function check_module_dependencies($module_name): bool {
        if (!isset($this->modules[$module_name])) {
            return false;
        }
        
        $class_name = $this->modules[$module_name]['class'];
        
        if (!class_exists($class_name)) {
            return false;
        }
        
        $temp_instance = new $class_name();
        
        if (!($temp_instance instanceof ModuleInterface)) {
            return false;
        }
        
        $dependencies = $temp_instance->getDependencies();
        
        foreach ($dependencies as $dependency) {
            if (strpos($dependency, 'plugin:') === 0) {
                // Plugin dependency
                $plugin = str_replace('plugin:', '', $dependency);
                if (!is_plugin_active($plugin)) {
                    return false;
                }
            } elseif (strpos($dependency, 'module:') === 0) {
                // Module dependency
                $dep_module = str_replace('module:', '', $dependency);
                if (!$this->is_module_enabled($dep_module)) {
                    return false;
                }
            } elseif (strpos($dependency, 'theme_support:') === 0) {
                // Theme support dependency
                $feature = str_replace('theme_support:', '', $dependency);
                if (!current_theme_supports($feature)) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Sort modules by dependencies
     *
     * @return array
     */
    private function sort_modules_by_dependencies(): array {
        $sorted = [];
        $visited = [];
        
        foreach (array_keys($this->modules) as $module_name) {
            $this->dependency_sort($module_name, $visited, $sorted);
        }
        
        return $sorted;
    }
    
    /**
     * Recursive dependency sorting
     *
     * @param string $module_name
     * @param array $visited
     * @param array $sorted
     * @return void
     */
    private function dependency_sort($module_name, &$visited, &$sorted): void {
        if (isset($visited[$module_name])) {
            return;
        }
        
        $visited[$module_name] = true;
        
        if (isset($this->modules[$module_name])) {
            $class_name = $this->modules[$module_name]['class'];
            
            if (class_exists($class_name)) {
                $temp_instance = new $class_name();
                
                if ($temp_instance instanceof ModuleInterface) {
                    $dependencies = $temp_instance->getDependencies();
                    
                    foreach ($dependencies as $dependency) {
                        if (strpos($dependency, 'module:') === 0) {
                            $dep_module = str_replace('module:', '', $dependency);
                            if (isset($this->modules[$dep_module])) {
                                $this->dependency_sort($dep_module, $visited, $sorted);
                            }
                        }
                    }
                }
            }
        }
        
        $sorted[] = $module_name;
    }
    
    /**
     * Add module admin page
     *
     * @return void
     */
    public function add_module_admin_page(): void {
        add_theme_page(
            __('Theme Modules', 'aqualuxe'),
            __('Modules', 'aqualuxe'),
            'manage_options',
            'aqualuxe-modules',
            [$this, 'render_module_admin_page']
        );
    }
    
    /**
     * Render module admin page
     *
     * @return void
     */
    public function render_module_admin_page(): void {
        ?>
        <div class="wrap">
            <h1><?php _e('AquaLuxe Theme Modules', 'aqualuxe'); ?></h1>
            <p><?php _e('Manage theme modules to customize functionality.', 'aqualuxe'); ?></p>
            
            <div class="module-grid">
                <?php foreach ($this->modules as $module_name => $module_data): ?>
                    <?php
                    $instance = null;
                    if (class_exists($module_data['class'])) {
                        $instance = new $module_data['class']();
                    }
                    ?>
                    <div class="module-card <?php echo $this->is_module_enabled($module_name) ? 'enabled' : 'disabled'; ?>">
                        <h3><?php echo esc_html(ucwords(str_replace('-', ' ', $module_name))); ?></h3>
                        
                        <?php if ($instance && $instance instanceof ModuleInterface): ?>
                            <p class="module-version">
                                <?php printf(__('Version: %s', 'aqualuxe'), esc_html($instance->getVersion())); ?>
                            </p>
                            
                            <?php if ($instance->getDependencies()): ?>
                                <div class="module-dependencies">
                                    <strong><?php _e('Dependencies:', 'aqualuxe'); ?></strong>
                                    <ul>
                                        <?php foreach ($instance->getDependencies() as $dependency): ?>
                                            <li><?php echo esc_html($dependency); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="module-actions">
                            <label class="module-toggle">
                                <input type="checkbox" 
                                       data-module="<?php echo esc_attr($module_name); ?>"
                                       <?php checked($this->is_module_enabled($module_name)); ?>>
                                <span class="toggle-slider"></span>
                                <?php echo $this->is_module_enabled($module_name) ? __('Enabled', 'aqualuxe') : __('Disabled', 'aqualuxe'); ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <style>
        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .module-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            background: #fff;
            transition: all 0.3s ease;
        }
        
        .module-card.enabled {
            border-color: #00a32a;
            box-shadow: 0 2px 4px rgba(0, 163, 42, 0.1);
        }
        
        .module-card h3 {
            margin: 0 0 10px 0;
            color: #1d2327;
        }
        
        .module-version {
            color: #646970;
            font-size: 12px;
            margin: 5px 0;
        }
        
        .module-dependencies {
            margin: 10px 0;
            font-size: 12px;
        }
        
        .module-dependencies ul {
            margin: 5px 0 0 20px;
        }
        
        .module-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
            gap: 8px;
        }
        
        .module-toggle input[type="checkbox"] {
            display: none;
        }
        
        .toggle-slider {
            width: 40px;
            height: 20px;
            background: #ccc;
            border-radius: 20px;
            position: relative;
            transition: background 0.3s ease;
        }
        
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: transform 0.3s ease;
        }
        
        .module-toggle input:checked + .toggle-slider {
            background: #00a32a;
        }
        
        .module-toggle input:checked + .toggle-slider::before {
            transform: translateX(20px);
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('.module-toggle input').on('change', function() {
                var module = $(this).data('module');
                var enabled = $(this).is(':checked');
                
                $.post(ajaxurl, {
                    action: 'toggle_module',
                    module: module,
                    enabled: enabled ? 1 : 0,
                    nonce: '<?php echo wp_create_nonce('toggle_module_nonce'); ?>'
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX toggle module
     *
     * @return void
     */
    public function ajax_toggle_module(): void {
        if (!wp_verify_nonce($_POST['nonce'], 'toggle_module_nonce')) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $module = sanitize_text_field($_POST['module']);
        $enabled = (bool) $_POST['enabled'];
        
        if ($enabled) {
            $success = $this->enable_module($module);
        } else {
            $success = $this->disable_module($module);
        }
        
        if ($success) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to toggle module');
        }
    }
}