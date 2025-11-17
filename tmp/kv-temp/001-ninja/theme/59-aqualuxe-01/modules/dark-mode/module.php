<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode_Module extends AquaLuxe_Module {
    /**
     * Module ID
     *
     * @var string
     */
    protected $id = 'dark-mode';

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Dark Mode';

    /**
     * Module description
     *
     * @var string
     */
    protected $description = 'Adds dark mode support with persistent user preference.';

    /**
     * Module version
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module dependencies
     *
     * @var array
     */
    protected $dependencies = [];

    /**
     * Initialize module
     */
    public function init() {
        // Call parent init
        parent::init();
        
        // Add body class
        add_filter('body_class', [$this, 'add_body_class']);
        
        // Add customizer settings
        add_action('customize_register', [$this, 'register_customizer_settings']);
        
        // Add dark mode toggle to header
        add_action('aqualuxe_header_after_navigation', [$this, 'render_dark_mode_toggle']);
        
        // Add dark mode script to footer
        add_action('wp_footer', [$this, 'add_dark_mode_script']);
    }

    /**
     * Register assets
     */
    public function register_assets() {
        // Register dark mode script
        wp_register_script(
            'aqualuxe-dark-mode',
            $this->uri . 'assets/js/dark-mode.js',
            ['alpinejs', 'alpinejs-persist'],
            $this->version,
            true
        );
        
        // Enqueue dark mode script
        wp_enqueue_script('aqualuxe-dark-mode');
        
        // Register dark mode styles
        wp_register_style(
            'aqualuxe-dark-mode',
            $this->uri . 'assets/css/dark-mode.css',
            [],
            $this->version
        );
        
        // Enqueue dark mode styles
        wp_enqueue_style('aqualuxe-dark-mode');
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager
     */
    public function register_customizer_settings($wp_customize) {
        // Add dark mode section
        $wp_customize->add_section('aqualuxe_dark_mode', [
            'title'    => __('Dark Mode', 'aqualuxe'),
            'priority' => 30,
        ]);
        
        // Add dark mode enable setting
        $wp_customize->add_setting('aqualuxe_dark_mode_enable', [
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        ]);
        
        // Add dark mode enable control
        $wp_customize->add_control('aqualuxe_dark_mode_enable', [
            'label'    => __('Enable Dark Mode', 'aqualuxe'),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'checkbox',
            'priority' => 10,
        ]);
        
        // Add dark mode default setting
        $wp_customize->add_setting('aqualuxe_dark_mode_default', [
            'default'           => 'auto',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ]);
        
        // Add dark mode default control
        $wp_customize->add_control('aqualuxe_dark_mode_default', [
            'label'    => __('Default Mode', 'aqualuxe'),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'select',
            'choices'  => [
                'light' => __('Light', 'aqualuxe'),
                'dark'  => __('Dark', 'aqualuxe'),
                'auto'  => __('Auto (based on system preference)', 'aqualuxe'),
            ],
            'priority' => 20,
        ]);
        
        // Add dark mode toggle position setting
        $wp_customize->add_setting('aqualuxe_dark_mode_toggle_position', [
            'default'           => 'header',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        ]);
        
        // Add dark mode toggle position control
        $wp_customize->add_control('aqualuxe_dark_mode_toggle_position', [
            'label'    => __('Toggle Position', 'aqualuxe'),
            'section'  => 'aqualuxe_dark_mode',
            'type'     => 'select',
            'choices'  => [
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both'   => __('Both', 'aqualuxe'),
                'none'   => __('None', 'aqualuxe'),
            ],
            'priority' => 30,
        ]);
    }

    /**
     * Add body class
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function add_body_class($classes) {
        // Add dark mode class
        $classes[] = 'aqualuxe-dark-mode-support';
        
        return $classes;
    }

    /**
     * Render dark mode toggle
     */
    public function render_dark_mode_toggle() {
        // Check if dark mode is enabled
        if (!get_theme_mod('aqualuxe_dark_mode_enable', true)) {
            return;
        }
        
        // Check toggle position
        $position = get_theme_mod('aqualuxe_dark_mode_toggle_position', 'header');
        if ($position === 'footer' || $position === 'none') {
            return;
        }
        
        // Render toggle
        ?>
        <div class="aqualuxe-dark-mode-toggle" x-data="darkMode">
            <button 
                type="button" 
                class="aqualuxe-dark-mode-toggle__button" 
                @click="toggle()"
                :aria-label="isDark ? '<?php esc_attr_e('Switch to Light Mode', 'aqualuxe'); ?>' : '<?php esc_attr_e('Switch to Dark Mode', 'aqualuxe'); ?>'"
            >
                <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--light" x-show="!isDark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
                    </svg>
                </span>
                <span class="aqualuxe-dark-mode-toggle__icon aqualuxe-dark-mode-toggle__icon--dark" x-show="isDark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
                    </svg>
                </span>
            </button>
        </div>
        <?php
    }

    /**
     * Add dark mode script
     */
    public function add_dark_mode_script() {
        // Check if dark mode is enabled
        if (!get_theme_mod('aqualuxe_dark_mode_enable', true)) {
            return;
        }
        
        // Get default mode
        $default_mode = get_theme_mod('aqualuxe_dark_mode_default', 'auto');
        
        // Add script
        ?>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('darkMode', () => ({
                    isDark: Alpine.$persist(<?php echo $default_mode === 'dark' ? 'true' : 'false'; ?>).as('aqualuxe_dark_mode'),
                    
                    init() {
                        // Check for system preference if set to auto
                        <?php if ($default_mode === 'auto') : ?>
                        if (!localStorage.getItem('_x_aqualuxe_dark_mode')) {
                            this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        }
                        <?php endif; ?>
                        
                        this.$watch('isDark', (value) => {
                            if (value) {
                                document.documentElement.classList.add('dark');
                            } else {
                                document.documentElement.classList.remove('dark');
                            }
                        });
                        
                        // Set initial class
                        if (this.isDark) {
                            document.documentElement.classList.add('dark');
                        }
                        
                        // Listen for system preference changes
                        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                            <?php if ($default_mode === 'auto') : ?>
                            if (!localStorage.getItem('_x_aqualuxe_dark_mode')) {
                                this.isDark = e.matches;
                            }
                            <?php endif; ?>
                        });
                    },
                    
                    toggle() {
                        this.isDark = !this.isDark;
                    }
                }));
            });
        </script>
        <?php
    }

    /**
     * Get default settings
     *
     * @return array
     */
    protected function get_default_settings() {
        return [
            'enabled'         => true,
            'default_mode'    => 'auto',
            'toggle_position' => 'header',
        ];
    }
}