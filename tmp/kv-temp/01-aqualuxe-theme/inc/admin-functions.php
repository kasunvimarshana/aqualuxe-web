<?php
/**
 * Admin Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin Customizations Class
 */
class AquaLuxe_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_theme_options_page'));
        add_action('admin_init', array($this, 'register_theme_settings'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_filter('admin_footer_text', array($this, 'admin_footer_text'));
    }
    
    /**
     * Add theme options page
     */
    public function add_theme_options_page() {
        add_theme_page(
            __('AquaLuxe Options', 'aqualuxe'),
            __('AquaLuxe Options', 'aqualuxe'),
            'manage_options',
            'aqualuxe-options',
            array($this, 'theme_options_page')
        );
    }
    
    /**
     * Register theme settings
     */
    public function register_theme_settings() {
        register_setting('aqualuxe_options', 'aqualuxe_options', array($this, 'sanitize_options'));
        
        add_settings_section(
            'aqualuxe_general',
            __('General Settings', 'aqualuxe'),
            array($this, 'general_section_callback'),
            'aqualuxe-options'
        );
        
        add_settings_field(
            'enable_animations',
            __('Enable Animations', 'aqualuxe'),
            array($this, 'checkbox_field_callback'),
            'aqualuxe-options',
            'aqualuxe_general',
            array('field' => 'enable_animations')
        );
        
        add_settings_field(
            'enable_lazy_loading',
            __('Enable Lazy Loading', 'aqualuxe'),
            array($this, 'checkbox_field_callback'),
            'aqualuxe-options',
            'aqualuxe_general',
            array('field' => 'enable_lazy_loading')
        );
    }
    
    /**
     * Theme options page
     */
    public function theme_options_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('AquaLuxe Theme Options', 'aqualuxe'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aqualuxe_options');
                do_settings_sections('aqualuxe-options');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * General section callback
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure general theme settings.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Checkbox field callback
     */
    public function checkbox_field_callback($args) {
        $options = get_option('aqualuxe_options');
        $field = $args['field'];
        $value = isset($options[$field]) ? $options[$field] : 0;
        
        echo '<input type="checkbox" id="' . $field . '" name="aqualuxe_options[' . $field . ']" value="1" ' . checked(1, $value, false) . ' />';
        echo '<label for="' . $field . '">' . __('Enable this feature', 'aqualuxe') . '</label>';
    }
    
    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized = array();
        
        if (isset($input['enable_animations'])) {
            $sanitized['enable_animations'] = 1;
        }
        
        if (isset($input['enable_lazy_loading'])) {
            $sanitized['enable_lazy_loading'] = 1;
        }
        
        return $sanitized;
    }
    
    /**
     * Admin scripts
     */
    public function admin_scripts($hook) {
        if ($hook === 'appearance_page_aqualuxe-options') {
            wp_enqueue_style('aqualuxe-admin', get_stylesheet_directory_uri() . '/assets/css/admin.css');
            wp_enqueue_script('aqualuxe-admin', get_stylesheet_directory_uri() . '/assets/js/admin.js', array('jquery'));
        }
    }
    
    /**
     * Custom admin footer text
     */
    public function admin_footer_text($text) {
        if (get_current_screen()->parent_base === 'themes') {
            return __('Thank you for using AquaLuxe theme!', 'aqualuxe');
        }
        return $text;
    }
}

new AquaLuxe_Admin();
