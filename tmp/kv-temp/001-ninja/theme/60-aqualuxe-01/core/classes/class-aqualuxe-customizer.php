<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Customizer Class
 */
class AquaLuxe_Customizer {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('customize_register', [$this, 'register_customizer_sections']);
        add_action('customize_preview_init', [$this, 'customizer_preview_js']);
        add_action('wp_head', [$this, 'output_customizer_css']);
    }

    /**
     * Register customizer sections
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public function register_customizer_sections($wp_customize) {
        // Load customizer sections
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/branding.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/typography.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/colors.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/layout.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/header.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/footer.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/blog.php';
        require_once AQUALUXE_CORE_DIR . 'customizer/sections/social.php';
        
        // Load WooCommerce customizer sections if WooCommerce is active
        if (aqualuxe_is_woocommerce_active()) {
            require_once AQUALUXE_CORE_DIR . 'customizer/sections/woocommerce.php';
        }
        
        // Load module customizer sections
        $this->load_module_customizer_sections($wp_customize);
    }

    /**
     * Load module customizer sections
     *
     * @param WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    private function load_module_customizer_sections($wp_customize) {
        $modules = aqualuxe()->modules;
        $module_status = aqualuxe()->module_status;
        
        foreach ($modules as $module_id => $module) {
            if (isset($module_status[$module_id]) && $module_status[$module_id] === true) {
                $customizer_file = AQUALUXE_MODULES_DIR . $module_id . '/customizer.php';
                
                if (file_exists($customizer_file)) {
                    require_once $customizer_file;
                    
                    $function_name = 'aqualuxe_' . str_replace('-', '_', $module_id) . '_customizer';
                    
                    if (function_exists($function_name)) {
                        call_user_func($function_name, $wp_customize);
                    }
                }
            }
        }
    }

    /**
     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
     */
    public function customizer_preview_js() {
        wp_enqueue_script(
            'aqualuxe-customizer',
            AQUALUXE_ASSETS_URI . 'js/customizer.js',
            ['customize-preview'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        // Get customizer values
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#23282d');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#00a0d2');
        $text_color = get_theme_mod('aqualuxe_text_color', '#333333');
        $heading_color = get_theme_mod('aqualuxe_heading_color', '#222222');
        $link_color = get_theme_mod('aqualuxe_link_color', '#0073aa');
        $link_hover_color = get_theme_mod('aqualuxe_link_hover_color', '#00a0d2');
        $body_font = get_theme_mod('aqualuxe_body_font', 'Roboto');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
        
        // Output CSS
        ?>
        <style type="text/css">
            :root {
                --aqualuxe-primary-color: <?php echo esc_attr($primary_color); ?>;
                --aqualuxe-secondary-color: <?php echo esc_attr($secondary_color); ?>;
                --aqualuxe-accent-color: <?php echo esc_attr($accent_color); ?>;
                --aqualuxe-text-color: <?php echo esc_attr($text_color); ?>;
                --aqualuxe-heading-color: <?php echo esc_attr($heading_color); ?>;
                --aqualuxe-link-color: <?php echo esc_attr($link_color); ?>;
                --aqualuxe-link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
                --aqualuxe-body-font: <?php echo esc_attr($body_font); ?>, sans-serif;
                --aqualuxe-heading-font: <?php echo esc_attr($heading_font); ?>, serif;
            }
            
            body {
                font-family: var(--aqualuxe-body-font);
                color: var(--aqualuxe-text-color);
            }
            
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--aqualuxe-heading-font);
                color: var(--aqualuxe-heading-color);
            }
            
            a {
                color: var(--aqualuxe-link-color);
            }
            
            a:hover, a:focus {
                color: var(--aqualuxe-link-hover-color);
            }
            
            .btn-primary, .button, button[type="submit"], input[type="submit"] {
                background-color: var(--aqualuxe-primary-color);
                border-color: var(--aqualuxe-primary-color);
            }
            
            .btn-primary:hover, .button:hover, button[type="submit"]:hover, input[type="submit"]:hover {
                background-color: var(--aqualuxe-link-hover-color);
                border-color: var(--aqualuxe-link-hover-color);
            }
            
            /* Additional custom CSS from customizer */
            <?php echo wp_kses(get_theme_mod('aqualuxe_custom_css', ''), []); ?>
        </style>
        <?php
    }
}