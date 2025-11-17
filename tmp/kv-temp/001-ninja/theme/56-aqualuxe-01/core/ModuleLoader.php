<?php
/**
 * Module Loader
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Class responsible for loading and managing modules
 */
class ModuleLoader {
    /**
     * Registered modules
     *
     * @var array
     */
    private $modules = [];

    /**
     * Active modules
     *
     * @var array
     */
    private $active_modules = [];

    /**
     * Singleton instance
     *
     * @var ModuleLoader
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return ModuleLoader
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_modules();
        
        // Register customizer settings for modules
        add_action( 'customize_register', [ $this, 'register_customizer_settings' ] );
    }

    /**
     * Load all available modules
     *
     * @return void
     */
    private function load_modules() {
        // Get all module directories
        $module_dirs = glob( get_template_directory() . '/modules/*', GLOB_ONLYDIR );
        
        foreach ( $module_dirs as $module_dir ) {
            $module_id = basename( $module_dir );
            $module_file = $module_dir . '/' . $module_id . '.php';
            
            if ( file_exists( $module_file ) ) {
                require_once $module_file;
                
                // Module class name should follow the pattern: AquaLuxe\Modules\ModuleNameModule
                $module_class = 'AquaLuxe\\Modules\\' . $this->get_class_name_from_id( $module_id ) . '\\' . $this->get_class_name_from_id( $module_id ) . 'Module';
                
                if ( class_exists( $module_class ) ) {
                    $this->register_module( $module_id, $module_class );
                }
            }
        }
        
        // Initialize active modules
        $this->initialize_active_modules();
    }

    /**
     * Convert module ID to class name
     *
     * @param string $module_id Module ID.
     * @return string
     */
    private function get_class_name_from_id( $module_id ) {
        return str_replace( ' ', '', ucwords( str_replace( '-', ' ', $module_id ) ) );
    }

    /**
     * Register a module
     *
     * @param string $module_id    Module ID.
     * @param string $module_class Module class name.
     * @return void
     */
    public function register_module( $module_id, $module_class ) {
        if ( ! class_exists( $module_class ) ) {
            return;
        }
        
        $this->modules[ $module_id ] = $module_class;
    }

    /**
     * Initialize active modules
     *
     * @return void
     */
    private function initialize_active_modules() {
        // Get active modules from theme options
        $active_modules = get_theme_mod( 'aqualuxe_active_modules', [] );
        
        // If no active modules are set, activate all modules by default
        if ( empty( $active_modules ) ) {
            $active_modules = array_keys( $this->modules );
        }
        
        foreach ( $active_modules as $module_id ) {
            if ( isset( $this->modules[ $module_id ] ) ) {
                $module_class = $this->modules[ $module_id ];
                $module = new $module_class();
                
                if ( $module instanceof ModuleInterface && $module->is_active() ) {
                    $this->active_modules[ $module_id ] = $module;
                    $module->init();
                }
            }
        }
    }

    /**
     * Get all registered modules
     *
     * @return array
     */
    public function get_modules() {
        return $this->modules;
    }

    /**
     * Get all active modules
     *
     * @return array
     */
    public function get_active_modules() {
        return $this->active_modules;
    }

    /**
     * Check if a module exists
     *
     * @param string $module_id Module ID.
     * @return boolean
     */
    public function module_exists( $module_id ) {
        return isset( $this->modules[ $module_id ] );
    }

    /**
     * Check if a module is active
     *
     * @param string $module_id Module ID.
     * @return boolean
     */
    public function is_module_active( $module_id ) {
        return isset( $this->active_modules[ $module_id ] );
    }

    /**
     * Get a module instance
     *
     * @param string $module_id Module ID.
     * @return ModuleInterface|null
     */
    public function get_module( $module_id ) {
        return isset( $this->active_modules[ $module_id ] ) ? $this->active_modules[ $module_id ] : null;
    }

    /**
     * Render a module
     *
     * @param string $module_id Module ID.
     * @param array  $args      Module arguments.
     * @return void
     */
    public function render_module( $module_id, $args = [] ) {
        if ( isset( $this->active_modules[ $module_id ] ) ) {
            $this->active_modules[ $module_id ]->render( $args );
        }
    }

    /**
     * Register customizer settings for all modules
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add modules section
        $wp_customize->add_section( 'aqualuxe_modules', [
            'title'    => __( 'Modules', 'aqualuxe' ),
            'priority' => 30,
        ] );
        
        // Add module activation settings
        $wp_customize->add_setting( 'aqualuxe_active_modules', [
            'default'           => array_keys( $this->modules ),
            'sanitize_callback' => [ $this, 'sanitize_module_activation' ],
        ] );
        
        // Add module activation control
        $wp_customize->add_control( new \WP_Customize_Control(
            $wp_customize,
            'aqualuxe_active_modules',
            [
                'section'     => 'aqualuxe_modules',
                'label'       => __( 'Active Modules', 'aqualuxe' ),
                'description' => __( 'Select which modules to activate', 'aqualuxe' ),
                'type'        => 'multi-checkbox',
                'choices'     => $this->get_module_choices(),
            ]
        ) );
        
        // Register module-specific customizer settings
        foreach ( $this->active_modules as $module ) {
            $module->register_customizer_settings( $wp_customize );
        }
    }

    /**
     * Get module choices for customizer
     *
     * @return array
     */
    private function get_module_choices() {
        $choices = [];
        
        foreach ( $this->modules as $module_id => $module_class ) {
            if ( class_exists( $module_class ) ) {
                $module = new $module_class();
                $choices[ $module_id ] = $module->get_name();
            }
        }
        
        return $choices;
    }

    /**
     * Sanitize module activation settings
     *
     * @param array $input Input value.
     * @return array
     */
    public function sanitize_module_activation( $input ) {
        $valid_modules = array_keys( $this->modules );
        $output = [];
        
        if ( is_array( $input ) ) {
            foreach ( $input as $module_id ) {
                if ( in_array( $module_id, $valid_modules, true ) ) {
                    $output[] = $module_id;
                }
            }
        }
        
        return $output;
    }
}