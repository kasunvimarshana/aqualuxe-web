<?php
/**
 * Template loader class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Template Loader class
 */
class Template_Loader {
    /**
     * Constructor
     */
    public function __construct() {
        // Filter template hierarchy
        add_filter( 'template_include', [ $this, 'template_loader' ] );
        
        // Filter template parts
        add_filter( 'aqualuxe_get_template_part', [ $this, 'get_template_part' ], 10, 3 );
        
        // Filter template path
        add_filter( 'aqualuxe_template_path', [ $this, 'template_path' ] );
    }

    /**
     * Template loader
     *
     * @param string $template The template path.
     * @return string The modified template path.
     */
    public function template_loader( $template ) {
        $find = [];
        $file = '';

        if ( is_embed() ) {
            return $template;
        }

        // WooCommerce templates
        if ( class_exists( 'WooCommerce' ) ) {
            if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
                // Let WooCommerce handle its templates
                return $template;
            }
        }

        // Custom post types
        if ( is_singular( 'service' ) ) {
            $file   = 'single-service.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_singular( 'event' ) ) {
            $file   = 'single-event.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_singular( 'team' ) ) {
            $file   = 'single-team.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_singular( 'testimonial' ) ) {
            $file   = 'single-testimonial.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_post_type_archive( 'service' ) ) {
            $file   = 'archive-service.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_post_type_archive( 'event' ) ) {
            $file   = 'archive-event.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_post_type_archive( 'team' ) ) {
            $file   = 'archive-team.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        } elseif ( is_post_type_archive( 'testimonial' ) ) {
            $file   = 'archive-testimonial.php';
            $find[] = $file;
            $find[] = AQUALUXE_TEMPLATES_DIR . $file;
        }

        // Find template file
        if ( $file ) {
            $template = locate_template( $find );
            if ( ! $template ) {
                $template = AQUALUXE_TEMPLATES_DIR . $file;
            }
        }

        return $template;
    }

    /**
     * Get template part
     *
     * @param string $template The template path.
     * @param string $slug The template slug.
     * @param string $name The template name.
     * @return string The modified template path.
     */
    public function get_template_part( $template, $slug, $name ) {
        $template_path = $this->template_path();
        
        // Look in yourtheme/templates/slug-name.php and yourtheme/templates/slug.php
        if ( $name ) {
            $template = locate_template(
                [
                    "{$template_path}/{$slug}-{$name}.php",
                    AQUALUXE_TEMPLATES_DIR . "{$slug}-{$name}.php",
                ]
            );
        }

        // Get default slug-name.php
        if ( ! $template && $name && file_exists( AQUALUXE_TEMPLATES_DIR . "{$slug}-{$name}.php" ) ) {
            $template = AQUALUXE_TEMPLATES_DIR . "{$slug}-{$name}.php";
        }

        // If template file doesn't exist, look in yourtheme/templates/slug.php
        if ( ! $template ) {
            $template = locate_template(
                [
                    "{$template_path}/{$slug}.php",
                    AQUALUXE_TEMPLATES_DIR . "{$slug}.php",
                ]
            );
        }

        // Get default slug.php
        if ( ! $template && file_exists( AQUALUXE_TEMPLATES_DIR . "{$slug}.php" ) ) {
            $template = AQUALUXE_TEMPLATES_DIR . "{$slug}.php";
        }

        return $template;
    }

    /**
     * Get template path
     *
     * @return string The template path.
     */
    public function template_path() {
        return apply_filters( 'aqualuxe_template_path', 'templates' );
    }

    /**
     * Get other templates passing attributes and including the file
     *
     * @param string $template_name The template name.
     * @param array  $args The template arguments.
     * @param string $template_path The template path.
     * @param string $default_path The default path.
     */
    public static function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        }

        $located = self::locate_template( $template_name, $template_path, $default_path );

        if ( ! file_exists( $located ) ) {
            /* translators: %s template */
            _doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'aqualuxe' ), '<code>' . $located . '</code>' ), '1.0.0' );
            return;
        }

        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters( 'aqualuxe_get_template', $located, $template_name, $args, $template_path, $default_path );

        do_action( 'aqualuxe_before_template_part', $template_name, $template_path, $located, $args );

        include $located;

        do_action( 'aqualuxe_after_template_part', $template_name, $template_path, $located, $args );
    }

    /**
     * Locate a template and return the path for inclusion
     *
     * @param string $template_name The template name.
     * @param string $template_path The template path.
     * @param string $default_path The default path.
     * @return string The template path.
     */
    public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
        if ( ! $template_path ) {
            $template_path = apply_filters( 'aqualuxe_template_path', 'templates' );
        }

        if ( ! $default_path ) {
            $default_path = AQUALUXE_TEMPLATES_DIR;
        }

        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            [
                trailingslashit( $template_path ) . $template_name,
                $template_name,
            ]
        );

        // Get default template.
        if ( ! $template ) {
            $template = trailingslashit( $default_path ) . $template_name;
        }

        // Return what we found.
        return apply_filters( 'aqualuxe_locate_template', $template, $template_name, $template_path );
    }
}

// Initialize the class
new Template_Loader();