<?php
/**
 * Template Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Template Class
 * 
 * This class is responsible for handling template loading and rendering.
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
        $this->setup_hooks();
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        add_filter( 'template_include', [ $this, 'template_include' ] );
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        add_filter( 'post_class', [ $this, 'post_classes' ] );
    }

    /**
     * Filter the template include
     *
     * @param string $template Template path
     * @return string
     */
    public function template_include( $template ) {
        // Check if we need to load a custom template
        if ( is_singular( 'service' ) ) {
            $new_template = locate_template( [ 'templates/service-single.php' ] );
            if ( ! empty( $new_template ) ) {
                return $new_template;
            }
        } elseif ( is_post_type_archive( 'service' ) ) {
            $new_template = locate_template( [ 'templates/service-archive.php' ] );
            if ( ! empty( $new_template ) ) {
                return $new_template;
            }
        } elseif ( is_singular( 'faq' ) ) {
            $new_template = locate_template( [ 'templates/faq-single.php' ] );
            if ( ! empty( $new_template ) ) {
                return $new_template;
            }
        } elseif ( is_post_type_archive( 'faq' ) ) {
            $new_template = locate_template( [ 'templates/faq-archive.php' ] );
            if ( ! empty( $new_template ) ) {
                return $new_template;
            }
        } elseif ( is_singular( 'team' ) ) {
            $new_template = locate_template( [ 'templates/team-single.php' ] );
            if ( ! empty( $new_template ) ) {
                return $new_template;
            }
        }

        return $template;
    }

    /**
     * Add custom classes to the body tag
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class for the page template
        if ( is_page_template() ) {
            $template_slug = str_replace( '.php', '', get_page_template_slug() );
            if ( $template_slug ) {
                $classes[] = 'template-' . $template_slug;
            }
        }

        // Add a class for the page layout
        $page_layout = $this->get_page_layout();
        if ( $page_layout ) {
            $classes[] = 'layout-' . $page_layout;
        }

        return $classes;
    }

    /**
     * Add custom classes to post elements
     *
     * @param array $classes Post classes
     * @return array
     */
    public function post_classes( $classes ) {
        // Add a class for posts with featured images
        if ( has_post_thumbnail() ) {
            $classes[] = 'has-thumbnail';
        } else {
            $classes[] = 'no-thumbnail';
        }

        return $classes;
    }

    /**
     * Get the page layout
     *
     * @return string
     */
    public function get_page_layout() {
        // Default layout from theme options
        $default_layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
        
        // Check for individual page/post layout
        if ( is_singular() ) {
            $post_layout = get_post_meta( get_the_ID(), '_aqualuxe_layout', true );
            if ( $post_layout && $post_layout !== 'default' ) {
                return $post_layout;
            }
        }
        
        // Check for specific templates
        if ( is_page_template( 'templates/full-width.php' ) ) {
            return 'full-width';
        }
        
        // Check for WooCommerce pages
        if ( \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active() ) {
            if ( is_shop() || is_product_category() || is_product_tag() ) {
                $shop_layout = get_theme_mod( 'aqualuxe_shop_layout', 'left-sidebar' );
                return $shop_layout;
            }
            
            if ( is_product() ) {
                $product_layout = get_theme_mod( 'aqualuxe_product_layout', 'full-width' );
                return $product_layout;
            }
        }
        
        return $default_layout;
    }

    /**
     * Get template part with data
     *
     * @param string $slug Template slug
     * @param string $name Template name
     * @param array $args Template arguments
     * @return void
     */
    public function get_template_part( $slug, $name = null, $args = [] ) {
        if ( $args && is_array( $args ) ) {
            extract( $args );
        }
        
        $templates = [];
        $name = (string) $name;
        
        if ( '' !== $name ) {
            $templates[] = "{$slug}-{$name}.php";
        }
        
        $templates[] = "{$slug}.php";
        
        $template = locate_template( $templates, false );
        
        if ( ! $template ) {
            return;
        }
        
        include $template;
    }

    /**
     * Load a template file
     *
     * @param string $template_name Template name
     * @param array $args Template arguments
     * @param string $template_path Template path
     * @param string $default_path Default path
     * @return void
     */
    public function load_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        if ( $args && is_array( $args ) ) {
            extract( $args );
        }
        
        $located = $this->locate_template( $template_name, $template_path, $default_path );
        
        if ( ! file_exists( $located ) ) {
            return;
        }
        
        include $located;
    }

    /**
     * Locate a template file
     *
     * @param string $template_name Template name
     * @param string $template_path Template path
     * @param string $default_path Default path
     * @return string
     */
    public function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        if ( ! $template_path ) {
            $template_path = 'templates/';
        }
        
        if ( ! $default_path ) {
            $default_path = AQUALUXE_DIR . 'templates/';
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
            $template = $default_path . $template_name;
        }
        
        return $template;
    }

    /**
     * Get template content
     *
     * @param string $template_name Template name
     * @param array $args Template arguments
     * @param string $template_path Template path
     * @param string $default_path Default path
     * @return string
     */
    public function get_template_content( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        ob_start();
        $this->load_template( $template_name, $args, $template_path, $default_path );
        return ob_get_clean();
    }
}