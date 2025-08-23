<?php
/**
 * Template Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

/**
 * Template Class
 * 
 * Handles template loading and rendering
 */
class Template {
    /**
     * Instance of this class
     *
     * @var Template
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Template
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
        // Register hooks
        add_filter( 'template_include', [ $this, 'template_include' ] );
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        add_filter( 'post_class', [ $this, 'post_classes' ] );
    }

    /**
     * Template include
     *
     * @param string $template Template path
     * @return string
     */
    public function template_include( $template ) {
        // Check if we need to override the template
        $override = apply_filters( 'aqualuxe_template_include', false, $template );
        
        if ( $override ) {
            return $override;
        }
        
        return $template;
    }

    /**
     * Body classes
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes( $classes ) {
        // Add theme name
        $classes[] = 'aqualuxe-theme';
        
        // Add dark mode class
        if ( DarkMode::get_instance()->is_dark_mode_enabled() ) {
            $classes[] = 'dark-mode';
        }
        
        // Add RTL class
        if ( is_rtl() ) {
            $classes[] = 'rtl';
        }
        
        // Add WooCommerce class
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }
        
        // Add responsive class
        $classes[] = 'responsive';
        
        // Add layout class
        $layout = get_theme_mod( 'layout', 'right-sidebar' );
        $classes[] = 'layout-' . $layout;
        
        return $classes;
    }

    /**
     * Post classes
     *
     * @param array $classes Post classes
     * @return array
     */
    public function post_classes( $classes ) {
        // Add post format class
        if ( has_post_format() ) {
            $classes[] = 'post-format-' . get_post_format();
        }
        
        return $classes;
    }

    /**
     * Get template part
     *
     * @param string $slug Template slug
     * @param string $name Template name
     * @param array  $args Template arguments
     */
    public static function get_template_part( $slug, $name = null, $args = [] ) {
        // Extract args
        if ( $args && is_array( $args ) ) {
            extract( $args );
        }
        
        // Get template part
        $template = '';
        
        // Look in theme directory first
        $template = locate_template( [ "{$slug}-{$name}.php", "{$slug}.php" ] );
        
        // Allow plugins/themes to override the template
        $template = apply_filters( 'aqualuxe_get_template_part', $template, $slug, $name, $args );
        
        // If template is found, include it
        if ( $template ) {
            include $template;
        }
    }

    /**
     * Get template
     *
     * @param string $template_name Template name
     * @param array  $args          Template arguments
     * @param string $template_path Template path
     * @param string $default_path  Default path
     */
    public static function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        // Extract args
        if ( $args && is_array( $args ) ) {
            extract( $args );
        }
        
        // Get template
        $located = self::locate_template( $template_name, $template_path, $default_path );
        
        // Allow plugins/themes to override the template
        $located = apply_filters( 'aqualuxe_get_template', $located, $template_name, $args, $template_path, $default_path );
        
        // If template is found, include it
        if ( file_exists( $located ) ) {
            include $located;
        }
    }

    /**
     * Locate template
     *
     * @param string $template_name Template name
     * @param string $template_path Template path
     * @param string $default_path  Default path
     * @return string
     */
    public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        // Set default template path
        if ( ! $template_path ) {
            $template_path = 'templates/';
        }
        
        // Set default path
        if ( ! $default_path ) {
            $default_path = AQUALUXE_TEMPLATES_DIR;
        }
        
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            [
                trailingslashit( $template_path ) . $template_name,
                $template_name,
            ]
        );
        
        // Get default template
        if ( ! $template ) {
            $template = trailingslashit( $default_path ) . $template_name;
        }
        
        // Return what we found
        return apply_filters( 'aqualuxe_locate_template', $template, $template_name, $template_path, $default_path );
    }

    /**
     * Get template content
     *
     * @param string $template_name Template name
     * @param array  $args          Template arguments
     * @param string $template_path Template path
     * @param string $default_path  Default path
     * @return string
     */
    public static function get_template_content( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        // Start output buffering
        ob_start();
        
        // Get template
        self::get_template( $template_name, $args, $template_path, $default_path );
        
        // Get output buffer
        $output = ob_get_clean();
        
        // Return output
        return $output;
    }
}