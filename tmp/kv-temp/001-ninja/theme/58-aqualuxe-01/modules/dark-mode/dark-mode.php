<?php
/**
 * Dark Mode Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Dark Mode Module Class
 */
class AquaLuxe_Dark_Mode {
    /**
     * Constructor
     */
    public function __construct() {
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $enable_dark_mode = isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
        
        // Only initialize if dark mode is enabled
        if ($enable_dark_mode) {
            $this->init();
        }
    }

    /**
     * Initialize the module
     */
    public function init() {
        // Add dark mode scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add dark mode body class
        add_filter('body_class', array($this, 'body_class'));
        
        // Add dark mode toggle to footer
        add_action('wp_footer', array($this, 'add_floating_toggle'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $dark_mode_default = isset($options['dark_mode_default']) ? $options['dark_mode_default'] : 'light';
        
        // Enqueue dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/modules/dark-mode/js/dark-mode.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script with options
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            array(
                'default' => $dark_mode_default,
                'toggleSelector' => '.dark-mode-toggle-button',
                'cookieName' => 'aqualuxe_dark_mode',
                'cookieExpiry' => 30, // Days
            )
        );
        
        // Enqueue dark mode styles
        wp_enqueue_style(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/modules/dark-mode/css/dark-mode.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Add dark mode body class
     *
     * @param array $classes Body classes
     * @return array Modified body classes
     */
    public function body_class($classes) {
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $dark_mode_default = isset($options['dark_mode_default']) ? $options['dark_mode_default'] : 'light';
        
        // Add dark mode class if default is dark
        if ($dark_mode_default === 'dark') {
            $classes[] = 'dark-mode';
        } elseif ($dark_mode_default === 'auto') {
            $classes[] = 'auto-dark-mode';
        }
        
        return $classes;
    }

    /**
     * Add floating dark mode toggle
     */
    public function add_floating_toggle() {
        // Get theme options
        $options = get_option('aqualuxe_options', array());
        $dark_mode_toggle_position = isset($options['dark_mode_toggle_position']) ? $options['dark_mode_toggle_position'] : 'header';
        $dark_mode_toggle_style = isset($options['dark_mode_toggle_style']) ? $options['dark_mode_toggle_style'] : 'icon';
        
        // Only add floating toggle if position is set to floating
        if ($dark_mode_toggle_position !== 'floating') {
            return;
        }
        
        ?>
        <div class="dark-mode-toggle dark-mode-toggle-floating dark-mode-toggle-<?php echo esc_attr($dark_mode_toggle_style); ?>">
            <button class="dark-mode-toggle-button" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
                <span class="icon-light"></span>
                <span class="icon-dark"></span>
                <?php if ($dark_mode_toggle_style === 'text' || $dark_mode_toggle_style === 'button') : ?>
                    <span class="toggle-text toggle-text-light"><?php esc_html_e('Light', 'aqualuxe'); ?></span>
                    <span class="toggle-text toggle-text-dark"><?php esc_html_e('Dark', 'aqualuxe'); ?></span>
                <?php endif; ?>
            </button>
        </div>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Dark_Mode();