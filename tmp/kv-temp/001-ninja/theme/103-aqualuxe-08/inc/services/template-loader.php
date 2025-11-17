<?php
/**
 * Template Loader Service
 * 
 * Handles template loading, template hierarchy, and partial rendering
 * following SOLID principles and WordPress best practices.
 *
 * @package AquaLuxe
 * @subpackage Services
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Services;

use AquaLuxe\Core\Base_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Template Loader Service Class
 *
 * Responsible for:
 * - Template hierarchy management
 * - Partial template loading with context
 * - Template caching and optimization
 * - Fallback template handling
 * - Context-aware template selection
 *
 * @since 1.0.0
 */
class Template_Loader extends Base_Service {

    /**
     * Template paths cache
     *
     * @var array
     */
    private $template_cache = array();

    /**
     * Template directories
     *
     * @var array
     */
    private $template_dirs = array();

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        $this->setup_template_dirs();
        $this->setup_hooks();
    }

    /**
     * Setup template directories.
     *
     * @return void
     */
    private function setup_template_dirs(): void {
        $this->template_dirs = array(
            'templates'   => AQUALUXE_THEME_DIR . '/templates',
            'partials'    => AQUALUXE_THEME_DIR . '/templates/partials',
            'modules'     => AQUALUXE_MODULES_DIR,
            'woocommerce' => AQUALUXE_THEME_DIR . '/woocommerce',
        );

        // Allow filtering of template directories
        $this->template_dirs = apply_filters( 'aqualuxe_template_directories', $this->template_dirs );
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        // Template hierarchy filters
        add_filter( 'template_include', array( $this, 'template_include' ), 99 );
        add_filter( 'get_template_part', array( $this, 'get_template_part' ), 10, 3 );
        
        // WooCommerce template filters
        if ( class_exists( 'WooCommerce' ) ) {
            add_filter( 'woocommerce_locate_template', array( $this, 'locate_woocommerce_template' ), 10, 3 );
        }
    }

    /**
     * Load a template with context.
     *
     * @param string $template Template name (without .php extension).
     * @param array  $context Data to pass to template.
     * @param bool   $echo Whether to echo the output.
     * @return string|void Template output if not echoing.
     */
    public function load_template( string $template, array $context = array(), bool $echo = true ) {
        $template_file = $this->locate_template( $template );
        
        if ( ! $template_file ) {
            $this->log( "Template not found: {$template}", 'warning' );
            return $echo ? '' : '';
        }

        // Extract context variables
        extract( $context, EXTR_SKIP );

        if ( $echo ) {
            include $template_file;
            return;
        } else {
            ob_start();
            include $template_file;
            return ob_get_clean();
        }
    }

    /**
     * Load a partial template.
     *
     * @param string $partial Partial name (without .php extension).
     * @param array  $context Data to pass to partial.
     * @param bool   $echo Whether to echo the output.
     * @return string|void Partial output if not echoing.
     */
    public function load_partial( string $partial, array $context = array(), bool $echo = true ) {
        // Look for partial in partials directory first
        $partial_file = $this->locate_template( "partials/{$partial}" );
        
        if ( ! $partial_file ) {
            $partial_file = $this->locate_template( $partial );
        }

        if ( ! $partial_file ) {
            $this->log( "Partial not found: {$partial}", 'warning' );
            return $echo ? '' : '';
        }

        // Extract context variables
        extract( $context, EXTR_SKIP );

        if ( $echo ) {
            include $partial_file;
            return;
        } else {
            ob_start();
            include $partial_file;
            return ob_get_clean();
        }
    }

    /**
     * Locate a template file.
     *
     * @param string $template Template name (with or without .php extension).
     * @return string|false Template file path or false if not found.
     */
    public function locate_template( string $template ) {
        // Add .php extension if not present
        if ( substr( $template, -4 ) !== '.php' ) {
            $template .= '.php';
        }

        // Check cache first
        $cache_key = md5( $template );
        if ( isset( $this->template_cache[ $cache_key ] ) ) {
            return $this->template_cache[ $cache_key ];
        }

        $template_file = false;

        // Search in template directories
        foreach ( $this->template_dirs as $dir ) {
            $file_path = $dir . '/' . $template;
            if ( file_exists( $file_path ) ) {
                $template_file = $file_path;
                break;
            }
        }

        // Try WordPress template hierarchy
        if ( ! $template_file ) {
            $template_file = locate_template( $template );
        }

        // Cache the result
        $this->template_cache[ $cache_key ] = $template_file;

        return $template_file;
    }

    /**
     * Get template hierarchy for current context.
     *
     * @return array Template hierarchy.
     */
    public function get_template_hierarchy(): array {
        $hierarchy = array();

        if ( is_404() ) {
            $hierarchy[] = '404.php';
        } elseif ( is_search() ) {
            $hierarchy[] = 'search.php';
            $hierarchy[] = 'archive.php';
        } elseif ( is_front_page() ) {
            $hierarchy[] = 'front-page.php';
            $hierarchy[] = 'home.php';
        } elseif ( is_home() ) {
            $hierarchy[] = 'home.php';
        } elseif ( is_post_type_archive() ) {
            $post_type = get_query_var( 'post_type' );
            $hierarchy[] = "archive-{$post_type}.php";
            $hierarchy[] = 'archive.php';
        } elseif ( is_tax() || is_category() || is_tag() ) {
            $term = get_queried_object();
            if ( $term ) {
                $hierarchy[] = "taxonomy-{$term->taxonomy}-{$term->slug}.php";
                $hierarchy[] = "taxonomy-{$term->taxonomy}.php";
                $hierarchy[] = 'taxonomy.php';
                $hierarchy[] = 'archive.php';
            }
        } elseif ( is_singular() ) {
            $post = get_queried_object();
            if ( $post ) {
                $hierarchy[] = "{$post->post_type}-{$post->post_name}.php";
                $hierarchy[] = "{$post->post_type}-{$post->ID}.php";
                $hierarchy[] = "{$post->post_type}.php";
                $hierarchy[] = 'singular.php';
            }
        } elseif ( is_page() ) {
            $page = get_queried_object();
            if ( $page ) {
                $template = get_page_template_slug( $page );
                if ( $template ) {
                    $hierarchy[] = $template;
                }
                $hierarchy[] = "page-{$page->post_name}.php";
                $hierarchy[] = "page-{$page->ID}.php";
                $hierarchy[] = 'page.php';
            }
        }

        $hierarchy[] = 'index.php';

        return apply_filters( 'aqualuxe_template_hierarchy', $hierarchy );
    }

    /**
     * Template include filter.
     *
     * @param string $template Template path.
     * @return string Modified template path.
     */
    public function template_include( string $template ): string {
        // Check if we have a custom template for this context
        $hierarchy = $this->get_template_hierarchy();
        
        foreach ( $hierarchy as $template_name ) {
            $custom_template = $this->locate_template( $template_name );
            if ( $custom_template && $custom_template !== $template ) {
                return $custom_template;
            }
        }

        return $template;
    }

    /**
     * Get template part filter.
     *
     * @param string $slug Template slug.
     * @param string $name Template name.
     * @param array  $args Template arguments.
     * @return void
     */
    public function get_template_part( string $slug, string $name = '', array $args = array() ): void {
        // Build template names to look for
        $templates = array();
        
        if ( $name ) {
            $templates[] = "{$slug}-{$name}.php";
        }
        $templates[] = "{$slug}.php";

        // Look for template
        foreach ( $templates as $template ) {
            $template_file = $this->locate_template( $template );
            if ( $template_file ) {
                $this->load_template( str_replace( '.php', '', $template ), $args );
                return;
            }
        }
    }

    /**
     * Locate WooCommerce template.
     *
     * @param string $template Template path.
     * @param string $template_name Template name.
     * @param string $template_path Template path.
     * @return string Template path.
     */
    public function locate_woocommerce_template( string $template, string $template_name, string $template_path ): string {
        // Look in theme's woocommerce directory
        $theme_template = $this->template_dirs['woocommerce'] . '/' . $template_name;
        
        if ( file_exists( $theme_template ) ) {
            return $theme_template;
        }

        return $template;
    }

    /**
     * Render a template with fallback.
     *
     * @param string $template Primary template name.
     * @param string $fallback Fallback template name.
     * @param array  $context Template context.
     * @param bool   $echo Whether to echo output.
     * @return string|void Template output if not echoing.
     */
    public function render_with_fallback( string $template, string $fallback, array $context = array(), bool $echo = true ) {
        $template_file = $this->locate_template( $template );
        
        if ( ! $template_file ) {
            $template_file = $this->locate_template( $fallback );
        }

        if ( ! $template_file ) {
            $error = sprintf( 'Neither template "%s" nor fallback "%s" found', $template, $fallback );
            $this->log( $error, 'error' );
            return $echo ? '' : '';
        }

        return $this->load_template( str_replace( '.php', '', basename( $template_file ) ), $context, $echo );
    }

    /**
     * Check if template exists.
     *
     * @param string $template Template name.
     * @return bool True if template exists.
     */
    public function template_exists( string $template ): bool {
        return (bool) $this->locate_template( $template );
    }

    /**
     * Get template info for debugging.
     *
     * @return array Template information.
     */
    public function get_template_info(): array {
        global $wp_query;
        
        return array(
            'is_404'         => is_404(),
            'is_search'      => is_search(),
            'is_front_page'  => is_front_page(),
            'is_home'        => is_home(),
            'is_singular'    => is_singular(),
            'is_page'        => is_page(),
            'is_archive'     => is_archive(),
            'post_type'      => get_post_type(),
            'template_file'  => get_page_template_slug(),
            'hierarchy'      => $this->get_template_hierarchy(),
            'template_dirs'  => $this->template_dirs,
            'cache_size'     => count( $this->template_cache ),
        );
    }

    /**
     * Clear template cache.
     *
     * @return void
     */
    public function clear_cache(): void {
        $this->template_cache = array();
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'template_loader';
    }
}