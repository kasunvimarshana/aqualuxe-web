<?php
/**
 * AquaLuxe Module Loader Class
 *
 * This class handles loading and initializing feature modules
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('AquaLuxe_Module_Loader')) {

    /**
     * Module Loader Class
     */
    class AquaLuxe_Module_Loader {

        /**
         * Available modules
         *
         * @var array
         */
        private $available_modules = array();

        /**
         * Active modules
         *
         * @var array
         */
        private $active_modules = array();

        /**
         * Constructor
         */
        public function __construct() {
            $this->available_modules = array(
                'dark-mode' => array(
                    'name' => 'Dark Mode',
                    'description' => 'Adds a dark mode toggle with persistent user preference.',
                    'path' => AQUALUXE_DIR . '/modules/dark-mode/dark-mode.php',
                    'requires_woocommerce' => false,
                ),
                'multilingual' => array(
                    'name' => 'Multilingual Support',
                    'description' => 'Adds multilingual support for content and products.',
                    'path' => AQUALUXE_DIR . '/modules/multilingual/multilingual.php',
                    'requires_woocommerce' => false,
                ),
                'subscriptions' => array(
                    'name' => 'Subscriptions',
                    'description' => 'Adds subscription functionality for recurring payments and membership tiers.',
                    'path' => AQUALUXE_DIR . '/modules/subscriptions/subscriptions.php',
                    'requires_woocommerce' => true,
                ),
                'bookings' => array(
                    'name' => 'Bookings',
                    'description' => 'Adds booking and scheduling functionality for services.',
                    'path' => AQUALUXE_DIR . '/modules/bookings/bookings.php',
                    'requires_woocommerce' => true,
                ),
                'events' => array(
                    'name' => 'Events',
                    'description' => 'Adds events calendar with ticketing functionality.',
                    'path' => AQUALUXE_DIR . '/modules/events/events.php',
                    'requires_woocommerce' => false,
                ),
                'wholesale' => array(
                    'name' => 'Wholesale',
                    'description' => 'Adds wholesale functionality for B2B customers.',
                    'path' => AQUALUXE_DIR . '/modules/wholesale/wholesale.php',
                    'requires_woocommerce' => true,
                ),
                'auctions' => array(
                    'name' => 'Auctions',
                    'description' => 'Adds auction and trade-in functionality.',
                    'path' => AQUALUXE_DIR . '/modules/auctions/auctions.php',
                    'requires_woocommerce' => true,
                ),
                'services' => array(
                    'name' => 'Services',
                    'description' => 'Adds professional services functionality.',
                    'path' => AQUALUXE_DIR . '/modules/services/services.php',
                    'requires_woocommerce' => false,
                ),
                'franchise' => array(
                    'name' => 'Franchise',
                    'description' => 'Adds franchise and licensing functionality.',
                    'path' => AQUALUXE_DIR . '/modules/franchise/franchise.php',
                    'requires_woocommerce' => false,
                ),
                'sustainability' => array(
                    'name' => 'Sustainability',
                    'description' => 'Adds R&D and sustainability features.',
                    'path' => AQUALUXE_DIR . '/modules/sustainability/sustainability.php',
                    'requires_woocommerce' => false,
                ),
                'affiliates' => array(
                    'name' => 'Affiliates',
                    'description' => 'Adds affiliate and referral program functionality.',
                    'path' => AQUALUXE_DIR . '/modules/affiliates/affiliates.php',
                    'requires_woocommerce' => false,
                ),
            );
        }

        /**
         * Initialize modules
         *
         * @param array $active_modules Array of active modules
         */
        public function init($active_modules) {
            $this->active_modules = $active_modules;
            
            // Add module settings to Customizer
            add_action('customize_register', array($this, 'add_module_settings'));
            
            // Load active modules
            $this->load_modules();
        }

        /**
         * Load active modules
         */
        private function load_modules() {
            $woocommerce_active = class_exists('WooCommerce');
            
            foreach ($this->active_modules as $module_id => $active) {
                if ($active && isset($this->available_modules[$module_id])) {
                    $module = $this->available_modules[$module_id];
                    
                    // Skip modules that require WooCommerce if it's not active
                    if ($module['requires_woocommerce'] && !$woocommerce_active) {
                        continue;
                    }
                    
                    // Load module if file exists
                    if (file_exists($module['path'])) {
                        require_once $module['path'];
                        
                        // Call module init function if it exists
                        $init_function = 'aqualuxe_' . str_replace('-', '_', $module_id) . '_init';
                        if (function_exists($init_function)) {
                            call_user_func($init_function);
                        }
                    }
                }
            }
        }

        /**
         * Add module settings to Customizer
         *
         * @param WP_Customize_Manager $wp_customize Customizer manager instance
         */
        public function add_module_settings($wp_customize) {
            // Add modules section
            $wp_customize->add_section('aqualuxe_modules', array(
                'title' => __('Modules', 'aqualuxe'),
                'priority' => 30,
                'description' => __('Enable or disable theme modules.', 'aqualuxe'),
            ));
            
            // Add settings for each module
            foreach ($this->available_modules as $module_id => $module) {
                $setting_id = 'aqualuxe_active_modules[' . $module_id . ']';
                
                // Add setting
                $wp_customize->add_setting($setting_id, array(
                    'default' => isset($this->active_modules[$module_id]) ? $this->active_modules[$module_id] : false,
                    'type' => 'option',
                    'capability' => 'edit_theme_options',
                    'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
                ));
                
                // Add control
                $wp_customize->add_control($setting_id, array(
                    'label' => $module['name'],
                    'description' => $module['description'],
                    'section' => 'aqualuxe_modules',
                    'type' => 'checkbox',
                ));
                
                // Add notice for modules that require WooCommerce
                if ($module['requires_woocommerce']) {
                    $wp_customize->add_control(new WP_Customize_Control(
                        $wp_customize,
                        $module_id . '_woocommerce_notice',
                        array(
                            'label' => '',
                            'description' => __('Requires WooCommerce', 'aqualuxe'),
                            'section' => 'aqualuxe_modules',
                            'settings' => array(),
                        )
                    ));
                }
            }
        }

        /**
         * Get available modules
         *
         * @return array Available modules
         */
        public function get_available_modules() {
            return $this->available_modules;
        }

        /**
         * Get active modules
         *
         * @return array Active modules
         */
        public function get_active_modules() {
            return $this->active_modules;
        }

        /**
         * Check if a module is active
         *
         * @param string $module_id Module ID
         * @return bool True if module is active, false otherwise
         */
        public function is_module_active($module_id) {
            return isset($this->active_modules[$module_id]) && $this->active_modules[$module_id];
        }
    }
}