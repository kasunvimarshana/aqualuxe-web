<?php
/**
 * Module Loader
 * 
 * Loads and initializes all theme modules
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Module Loader class
 */
class AquaLuxe_Module_Loader {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Loaded modules
     */
    private $modules = [];
    
    /**
     * Available modules
     */
    private $available_modules = [
        'dark-mode' => [
            'name' => 'Dark Mode',
            'description' => 'Toggle between light and dark themes',
            'file' => 'class-module-dark-mode.php',
            'class' => 'Module_Dark_Mode',
            'default' => true,
        ],
        'wishlist' => [
            'name' => 'Wishlist',
            'description' => 'Save favorite items for later',
            'file' => 'class-module-wishlist.php',
            'class' => 'Module_Wishlist',
            'default' => true,
        ],
        'search' => [
            'name' => 'Enhanced Search',
            'description' => 'Live search with suggestions and filters',
            'file' => 'class-module-search.php',
            'class' => 'Module_Search',
            'default' => true,
        ],
        'cart' => [
            'name' => 'Shopping Cart',
            'description' => 'Enhanced shopping cart functionality',
            'file' => 'class-module-cart.php',
            'class' => 'Module_Cart',
            'default' => true,
            'requires' => 'woocommerce',
        ],
        'gallery' => [
            'name' => 'Image Gallery',
            'description' => 'Enhanced image galleries with lightbox',
            'file' => 'class-module-gallery.php',
            'class' => 'Module_Gallery',
            'default' => true,
        ],
        'forms' => [
            'name' => 'Contact Forms',
            'description' => 'Enhanced contact forms with validation',
            'file' => 'class-module-forms.php',
            'class' => 'Module_Forms',
            'default' => true,
        ],
        'animations' => [
            'name' => 'Animations',
            'description' => 'Scroll animations and transitions',
            'file' => 'class-module-animations.php',
            'class' => 'Module_Animations',
            'default' => true,
        ],
        'social' => [
            'name' => 'Social Sharing',
            'description' => 'Social media sharing buttons',
            'file' => 'class-module-social.php',
            'class' => 'Module_Social',
            'default' => true,
        ],
        'notifications' => [
            'name' => 'Notifications',
            'description' => 'Toast notifications and alerts',
            'file' => 'class-module-notifications.php',
            'class' => 'Module_Notifications',
            'default' => true,
        ],
        'analytics' => [
            'name' => 'Analytics',
            'description' => 'Enhanced analytics and tracking',
            'file' => 'class-module-analytics.php',
            'class' => 'Module_Analytics',
            'default' => false,
        ],
    ];
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'load_modules'], 15);
        add_action('customize_register', [$this, 'add_customizer_controls']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_ajax_aqualuxe_toggle_module', [$this, 'ajax_toggle_module']);
    }
    
    /**
     * Load all enabled modules
     */
    public function load_modules() {
        foreach ($this->available_modules as $module_key => $module_info) {
            // Check if module is enabled
            if (!$this->is_module_enabled($module_key)) {
                continue;
            }
            
            // Check requirements
            if (!$this->check_module_requirements($module_info)) {
                continue;
            }
            
            // Load module file
            $module_file = AQUALUXE_THEME_PATH . '/modules/' . $module_info['file'];
            
            if (file_exists($module_file)) {
                require_once $module_file;
                
                // Initialize module class
                if (class_exists($module_info['class'])) {
                    $module_instance = call_user_func([$module_info['class'], 'get_instance']);
                    $this->modules[$module_key] = $module_instance;
                    
                    // Hook for module loaded
                    do_action('aqualuxe_module_loaded', $module_key, $module_instance);
                }
            }
        }
        
        // Hook for all modules loaded
        do_action('aqualuxe_modules_loaded', $this->modules);
    }
    
    /**
     * Check if module is enabled
     */
    public function is_module_enabled($module_key) {
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', $this->get_default_enabled_modules());
        return in_array($module_key, $enabled_modules);
    }
    
    /**
     * Get default enabled modules
     */
    private function get_default_enabled_modules() {
        $defaults = [];
        foreach ($this->available_modules as $key => $module) {
            if ($module['default']) {
                $defaults[] = $key;
            }
        }
        return $defaults;
    }
    
    /**
     * Check module requirements
     */
    private function check_module_requirements($module_info) {
        if (empty($module_info['requires'])) {
            return true;
        }
        
        $requirements = is_array($module_info['requires']) ? 
            $module_info['requires'] : 
            [$module_info['requires']];
        
        foreach ($requirements as $requirement) {
            switch ($requirement) {
                case 'woocommerce':
                    if (!class_exists('WooCommerce')) {
                        return false;
                    }
                    break;
                    
                case 'contact-form-7':
                    if (!function_exists('wpcf7')) {
                        return false;
                    }
                    break;
                    
                default:
                    // Check for plugin by function or class
                    if (function_exists($requirement) || class_exists($requirement)) {
                        continue;
                    }
                    
                    // Check for active plugin
                    if (!is_plugin_active($requirement)) {
                        return false;
                    }
                    break;
            }
        }
        
        return true;
    }
    
    /**
     * Get loaded module
     */
    public function get_module($module_key) {
        return isset($this->modules[$module_key]) ? $this->modules[$module_key] : null;
    }
    
    /**
     * Get all loaded modules
     */
    public function get_modules() {
        return $this->modules;
    }
    
    /**
     * Get available modules
     */
    public function get_available_modules() {
        return $this->available_modules;
    }
    
    /**
     * Enable module
     */
    public function enable_module($module_key) {
        if (!isset($this->available_modules[$module_key])) {
            return false;
        }
        
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', $this->get_default_enabled_modules());
        
        if (!in_array($module_key, $enabled_modules)) {
            $enabled_modules[] = $module_key;
            set_theme_mod('aqualuxe_enabled_modules', $enabled_modules);
        }
        
        return true;
    }
    
    /**
     * Disable module
     */
    public function disable_module($module_key) {
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', $this->get_default_enabled_modules());
        $enabled_modules = array_diff($enabled_modules, [$module_key]);
        set_theme_mod('aqualuxe_enabled_modules', array_values($enabled_modules));
        
        return true;
    }
    
    /**
     * Add customizer controls
     */
    public function add_customizer_controls($wp_customize) {
        // Modules section
        $wp_customize->add_section('aqualuxe_modules', [
            'title' => __('Theme Modules', 'aqualuxe'),
            'description' => __('Enable or disable theme modules to customize functionality.', 'aqualuxe'),
            'priority' => 25,
        ]);
        
        // Enabled modules setting
        $wp_customize->add_setting('aqualuxe_enabled_modules', [
            'default' => $this->get_default_enabled_modules(),
            'sanitize_callback' => [$this, 'sanitize_enabled_modules'],
            'transport' => 'refresh',
        ]);
        
        // Create choices for modules
        $module_choices = [];
        foreach ($this->available_modules as $key => $module) {
            $module_choices[$key] = $module['name'] . ' - ' . $module['description'];
        }
        
        $wp_customize->add_control('aqualuxe_enabled_modules', [
            'label' => __('Enabled Modules', 'aqualuxe'),
            'description' => __('Select which modules to enable. Changes require a page refresh.', 'aqualuxe'),
            'section' => 'aqualuxe_modules',
            'type' => 'select',
            'multiple' => true,
            'choices' => $module_choices,
        ]);
        
        // Individual module controls
        foreach ($this->available_modules as $key => $module) {
            $wp_customize->add_setting("aqualuxe_module_{$key}_enabled", [
                'default' => $module['default'],
                'sanitize_callback' => 'wp_validate_boolean',
                'transport' => 'refresh',
            ]);
            
            $description = $module['description'];
            if (!empty($module['requires'])) {
                $requirements = is_array($module['requires']) ? 
                    implode(', ', $module['requires']) : 
                    $module['requires'];
                $description .= ' (Requires: ' . $requirements . ')';
            }
            
            $wp_customize->add_control("aqualuxe_module_{$key}_enabled", [
                'label' => $module['name'],
                'description' => $description,
                'section' => 'aqualuxe_modules',
                'type' => 'checkbox',
            ]);
        }
    }
    
    /**
     * Sanitize enabled modules
     */
    public function sanitize_enabled_modules($input) {
        if (!is_array($input)) {
            return $this->get_default_enabled_modules();
        }
        
        $valid_modules = array_keys($this->available_modules);
        return array_intersect($input, $valid_modules);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            __('Theme Modules', 'aqualuxe'),
            __('Modules', 'aqualuxe'),
            'manage_options',
            'aqualuxe-modules',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'aqualuxe_modules')) {
            $enabled_modules = isset($_POST['enabled_modules']) ? 
                array_map('sanitize_text_field', $_POST['enabled_modules']) : 
                [];
            
            set_theme_mod('aqualuxe_enabled_modules', $enabled_modules);
            
            echo '<div class="notice notice-success"><p>' . __('Modules updated successfully.', 'aqualuxe') . '</p></div>';
        }
        
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', $this->get_default_enabled_modules());
        
        ?>
        <div class="wrap">
            <h1><?php _e('Theme Modules', 'aqualuxe'); ?></h1>
            <p><?php _e('Enable or disable theme modules to customize functionality. Some modules may require specific plugins to be active.', 'aqualuxe'); ?></p>
            
            <form method="post" action="">
                <?php wp_nonce_field('aqualuxe_modules'); ?>
                
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php _e('Module', 'aqualuxe'); ?></th>
                            <th><?php _e('Description', 'aqualuxe'); ?></th>
                            <th><?php _e('Status', 'aqualuxe'); ?></th>
                            <th><?php _e('Requirements', 'aqualuxe'); ?></th>
                            <th><?php _e('Enable', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->available_modules as $key => $module): ?>
                        <tr>
                            <td><strong><?php echo esc_html($module['name']); ?></strong></td>
                            <td><?php echo esc_html($module['description']); ?></td>
                            <td>
                                <?php if (in_array($key, $enabled_modules) && $this->check_module_requirements($module)): ?>
                                    <span style="color: #46b450;">&#x2713; <?php _e('Active', 'aqualuxe'); ?></span>
                                <?php elseif (in_array($key, $enabled_modules)): ?>
                                    <span style="color: #dc3232;">&#x2717; <?php _e('Requirements not met', 'aqualuxe'); ?></span>
                                <?php else: ?>
                                    <span style="color: #999;">&#x2014; <?php _e('Disabled', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($module['requires'])): ?>
                                    <?php 
                                    $requirements = is_array($module['requires']) ? 
                                        implode(', ', $module['requires']) : 
                                        $module['requires'];
                                    echo esc_html($requirements);
                                    ?>
                                <?php else: ?>
                                    <em><?php _e('None', 'aqualuxe'); ?></em>
                                <?php endif; ?>
                            </td>
                            <td>
                                <label>
                                    <input type="checkbox" 
                                           name="enabled_modules[]" 
                                           value="<?php echo esc_attr($key); ?>"
                                           <?php checked(in_array($key, $enabled_modules)); ?>>
                                    <?php _e('Enable', 'aqualuxe'); ?>
                                </label>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e('Save Changes', 'aqualuxe'); ?>">
                </p>
            </form>
            
            <div class="card">
                <h2><?php _e('Module Information', 'aqualuxe'); ?></h2>
                <p><?php _e('Modules extend the functionality of your theme with additional features. You can enable or disable modules based on your needs.', 'aqualuxe'); ?></p>
                
                <h3><?php _e('Available Modules:', 'aqualuxe'); ?></h3>
                <ul>
                    <?php foreach ($this->available_modules as $key => $module): ?>
                    <li><strong><?php echo esc_html($module['name']); ?>:</strong> <?php echo esc_html($module['description']); ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <h3><?php _e('Requirements:', 'aqualuxe'); ?></h3>
                <ul>
                    <li><strong>WooCommerce:</strong> <?php _e('Required for e-commerce related modules like Cart and enhanced product features.', 'aqualuxe'); ?></li>
                    <li><strong>Contact Form 7:</strong> <?php _e('Required for enhanced contact form functionality.', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX toggle module
     */
    public function ajax_toggle_module() {
        check_ajax_referer('aqualuxe_modules_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions.', 'aqualuxe'));
        }
        
        $module_key = sanitize_text_field($_POST['module'] ?? '');
        $enabled = wp_validate_boolean($_POST['enabled'] ?? false);
        
        if (!isset($this->available_modules[$module_key])) {
            wp_send_json_error(__('Invalid module.', 'aqualuxe'));
        }
        
        if ($enabled) {
            $result = $this->enable_module($module_key);
        } else {
            $result = $this->disable_module($module_key);
        }
        
        if ($result) {
            wp_send_json_success([
                'message' => sprintf(
                    __('Module "%s" %s successfully.', 'aqualuxe'),
                    $this->available_modules[$module_key]['name'],
                    $enabled ? __('enabled', 'aqualuxe') : __('disabled', 'aqualuxe')
                )
            ]);
        } else {
            wp_send_json_error(__('Failed to update module.', 'aqualuxe'));
        }
    }
    
    /**
     * Get module status for external use
     */
    public function get_module_status() {
        $status = [];
        $enabled_modules = get_theme_mod('aqualuxe_enabled_modules', $this->get_default_enabled_modules());
        
        foreach ($this->available_modules as $key => $module) {
            $status[$key] = [
                'name' => $module['name'],
                'enabled' => in_array($key, $enabled_modules),
                'loaded' => isset($this->modules[$key]),
                'requirements_met' => $this->check_module_requirements($module),
            ];
        }
        
        return $status;
    }
    
    /**
     * Check if a specific module is loaded and active
     */
    public function is_module_active($module_key) {
        return isset($this->modules[$module_key]);
    }
}

// Initialize the module loader
AquaLuxe_Module_Loader::get_instance();
