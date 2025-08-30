<?php
/**
 * Modules Management Class
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Modules {
    
    /**
     * Available modules
     */
    private $modules = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'load_modules'));
        add_action('admin_init', array($this, 'register_module_settings'));
    }
    
    /**
     * Load modules
     */
    public function load_modules() {
        $this->register_modules();
        $this->init_active_modules();
    }
    
    /**
     * Register available modules
     */
    private function register_modules() {
        $this->modules = array(
            'dark_mode' => array(
                'name' => esc_html__('Dark Mode', 'aqualuxe'),
                'description' => esc_html__('Persistent dark mode toggle for better user experience.', 'aqualuxe'),
                'file' => 'dark-mode/class-aqualuxe-dark-mode.php',
                'class' => 'AquaLuxe_Dark_Mode',
                'default' => true,
                'dependencies' => array(),
            ),
            'multilingual' => array(
                'name' => esc_html__('Multilingual Support', 'aqualuxe'),
                'description' => esc_html__('Built-in multilingual functionality for global reach.', 'aqualuxe'),
                'file' => 'multilingual/class-aqualuxe-multilingual.php',
                'class' => 'AquaLuxe_Multilingual',
                'default' => true,
                'dependencies' => array(),
            ),
            'woocommerce_integration' => array(
                'name' => esc_html__('WooCommerce Integration', 'aqualuxe'),
                'description' => esc_html__('Enhanced WooCommerce features and customizations.', 'aqualuxe'),
                'file' => 'woocommerce/class-aqualuxe-woocommerce.php',
                'class' => 'AquaLuxe_WooCommerce',
                'default' => true,
                'dependencies' => array('WooCommerce'),
            ),
            'booking_system' => array(
                'name' => esc_html__('Booking System', 'aqualuxe'),
                'description' => esc_html__('Service booking and appointment management.', 'aqualuxe'),
                'file' => 'booking/class-aqualuxe-booking.php',
                'class' => 'AquaLuxe_Booking',
                'default' => false,
                'dependencies' => array(),
            ),
            'events_calendar' => array(
                'name' => esc_html__('Events Calendar', 'aqualuxe'),
                'description' => esc_html__('Event management with ticketing and registration.', 'aqualuxe'),
                'file' => 'events/class-aqualuxe-events.php',
                'class' => 'AquaLuxe_Events',
                'default' => false,
                'dependencies' => array(),
            ),
            'subscriptions' => array(
                'name' => esc_html__('Subscriptions', 'aqualuxe'),
                'description' => esc_html__('Recurring payments and membership management.', 'aqualuxe'),
                'file' => 'subscriptions/class-aqualuxe-subscriptions.php',
                'class' => 'AquaLuxe_Subscriptions',
                'default' => false,
                'dependencies' => array('WooCommerce'),
            ),
            'auctions' => array(
                'name' => esc_html__('Auctions & Trade-ins', 'aqualuxe'),
                'description' => esc_html__('Auction system for rare fish and trade-in programs.', 'aqualuxe'),
                'file' => 'auctions/class-aqualuxe-auctions.php',
                'class' => 'AquaLuxe_Auctions',
                'default' => false,
                'dependencies' => array('WooCommerce'),
            ),
            'wholesale' => array(
                'name' => esc_html__('Wholesale & B2B', 'aqualuxe'),
                'description' => esc_html__('B2B functionality with wholesale pricing and features.', 'aqualuxe'),
                'file' => 'wholesale/class-aqualuxe-wholesale.php',
                'class' => 'AquaLuxe_Wholesale',
                'default' => false,
                'dependencies' => array('WooCommerce'),
            ),
            'franchise' => array(
                'name' => esc_html__('Franchise & Licensing', 'aqualuxe'),
                'description' => esc_html__('Franchise inquiry forms and partner portal management.', 'aqualuxe'),
                'file' => 'franchise/class-aqualuxe-franchise.php',
                'class' => 'AquaLuxe_Franchise',
                'default' => false,
                'dependencies' => array(),
            ),
            'sustainability' => array(
                'name' => esc_html__('R&D & Sustainability', 'aqualuxe'),
                'description' => esc_html__('Research, development, and sustainability initiatives.', 'aqualuxe'),
                'file' => 'sustainability/class-aqualuxe-sustainability.php',
                'class' => 'AquaLuxe_Sustainability',
                'default' => false,
                'dependencies' => array(),
            ),
            'affiliate' => array(
                'name' => esc_html__('Affiliate & Referrals', 'aqualuxe'),
                'description' => esc_html__('Affiliate program and referral system.', 'aqualuxe'),
                'file' => 'affiliate/class-aqualuxe-affiliate.php',
                'class' => 'AquaLuxe_Affiliate',
                'default' => false,
                'dependencies' => array('WooCommerce'),
            ),
            'professional_services' => array(
                'name' => esc_html__('Professional Services', 'aqualuxe'),
                'description' => esc_html__('Design, installation, maintenance, and training services.', 'aqualuxe'),
                'file' => 'services/class-aqualuxe-professional-services.php',
                'class' => 'AquaLuxe_Professional_Services',
                'default' => true,
                'dependencies' => array(),
            ),
            'import_export' => array(
                'name' => esc_html__('Import/Export Tools', 'aqualuxe'),
                'description' => esc_html__('Demo content importer and data export tools.', 'aqualuxe'),
                'file' => 'import-export/class-aqualuxe-import-export.php',
                'class' => 'AquaLuxe_Import_Export',
                'default' => true,
                'dependencies' => array(),
            ),
        );
        
        // Allow modules to be filtered
        $this->modules = apply_filters('aqualuxe_modules', $this->modules);
    }
    
    /**
     * Initialize active modules
     */
    private function init_active_modules() {
        foreach ($this->modules as $module_id => $module) {
            if ($this->is_module_active($module_id)) {
                $this->load_module($module_id, $module);
            }
        }
    }
    
    /**
     * Check if module is active
     */
    public function is_module_active($module_id) {
        $active_modules = get_option('aqualuxe_active_modules', array());
        
        // Use default setting if not explicitly set
        if (!isset($active_modules[$module_id])) {
            return isset($this->modules[$module_id]['default']) ? $this->modules[$module_id]['default'] : false;
        }
        
        return !empty($active_modules[$module_id]);
    }
    
    /**
     * Load individual module
     */
    private function load_module($module_id, $module) {
        // Check dependencies
        if (!empty($module['dependencies'])) {
            foreach ($module['dependencies'] as $dependency) {
                if ($dependency === 'WooCommerce' && !class_exists('WooCommerce')) {
                    return false;
                }
                // Add more dependency checks as needed
            }
        }
        
        // Load module file
        $module_path = AQUALUXE_THEME_DIR . '/modules/' . $module['file'];
        if (file_exists($module_path)) {
            require_once $module_path;
            
            // Initialize module class
            if (class_exists($module['class'])) {
                new $module['class']();
                
                // Hook for module loaded
                do_action('aqualuxe_module_loaded', $module_id, $module);
            }
        }
        
        return true;
    }
    
    /**
     * Get all modules
     */
    public function get_modules() {
        return $this->modules;
    }
    
    /**
     * Get active modules
     */
    public function get_active_modules() {
        $active_modules = array();
        
        foreach ($this->modules as $module_id => $module) {
            if ($this->is_module_active($module_id)) {
                $active_modules[$module_id] = $module;
            }
        }
        
        return $active_modules;
    }
    
    /**
     * Activate module
     */
    public function activate_module($module_id) {
        if (!isset($this->modules[$module_id])) {
            return false;
        }
        
        $active_modules = get_option('aqualuxe_active_modules', array());
        $active_modules[$module_id] = true;
        
        return update_option('aqualuxe_active_modules', $active_modules);
    }
    
    /**
     * Deactivate module
     */
    public function deactivate_module($module_id) {
        if (!isset($this->modules[$module_id])) {
            return false;
        }
        
        $active_modules = get_option('aqualuxe_active_modules', array());
        $active_modules[$module_id] = false;
        
        return update_option('aqualuxe_active_modules', $active_modules);
    }
    
    /**
     * Register module settings
     */
    public function register_module_settings() {
        register_setting('aqualuxe_modules', 'aqualuxe_active_modules');
        
        add_settings_section(
            'aqualuxe_modules_section',
            esc_html__('Theme Modules', 'aqualuxe'),
            array($this, 'modules_section_callback'),
            'aqualuxe_modules'
        );
        
        foreach ($this->modules as $module_id => $module) {
            add_settings_field(
                'aqualuxe_module_' . $module_id,
                $module['name'],
                array($this, 'module_field_callback'),
                'aqualuxe_modules',
                'aqualuxe_modules_section',
                array(
                    'module_id' => $module_id,
                    'module' => $module,
                )
            );
        }
    }
    
    /**
     * Modules section callback
     */
    public function modules_section_callback() {
        echo '<p>' . esc_html__('Enable or disable theme modules based on your needs. Some modules may require specific plugins to be active.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Module field callback
     */
    public function module_field_callback($args) {
        $module_id = $args['module_id'];
        $module = $args['module'];
        $active_modules = get_option('aqualuxe_active_modules', array());
        $is_active = $this->is_module_active($module_id);
        
        echo '<label>';
        echo '<input type="checkbox" name="aqualuxe_active_modules[' . esc_attr($module_id) . ']" value="1" ' . checked($is_active, true, false) . ' />';
        echo ' ' . esc_html($module['description']);
        echo '</label>';
        
        // Show dependencies
        if (!empty($module['dependencies'])) {
            echo '<br><small><em>' . esc_html__('Requires:', 'aqualuxe') . ' ' . implode(', ', $module['dependencies']) . '</em></small>';
        }
    }
}
