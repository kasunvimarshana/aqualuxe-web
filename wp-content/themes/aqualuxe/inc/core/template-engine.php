<?php
/**
 * Template Engine - Advanced template management system for AquaLuxe theme
 *
 * Handles all template-related functionality including:
 * - Template loading and rendering
 * - Template hierarchy management
 * - Partial template system
 * - Template caching
 * - Context management
 * - Template hooks and filters
 * - Multi-theme support
 * - Template customization API
 * 
 * @package AquaLuxe\Core
 * @since 2.0.0
 * @author Kasun Vimarshana
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'Direct script access denied.' );
}

/**
 * Template Engine Class
 * 
 * Implements a robust template management system with support for
 * template hierarchy, caching, and dynamic template loading.
 * 
 * @since 2.0.0
 */
class Template_Engine implements Singleton_Interface {
    use Singleton_Trait;

    /**
     * Template configuration
     *
     * @var array
     */
    private $config = [];

    /**
     * Template cache
     *
     * @var array
     */
    private $template_cache = [];

    /**
     * Template context stack
     *
     * @var array
     */
    private $context_stack = [];

    /**
     * Loaded templates
     *
     * @var array
     */
    private $loaded_templates = [];

    /**
     * Template hooks
     *
     * @var array
     */
    private $template_hooks = [];

    /**
     * Default template directory
     *
     * @var string
     */
    private $template_dir = '';

    /**
     * Partials directory
     *
     * @var string
     */
    private $partials_dir = '';

    /**
     * Initialize template engine
     *
     * @since 2.0.0
     */
    protected function __construct() {
        $this->setup_directories();
        $this->load_configuration();
        $this->initialize_hooks();
    }

    /**
     * Setup template directories
     *
     * @since 2.0.0
     */
    private function setup_directories(): void {
        $theme_dir = function_exists( 'get_template_directory' ) 
            ? get_template_directory() 
            : ( defined( 'AQUALUXE_THEME_DIR' ) ? AQUALUXE_THEME_DIR : '' );

        $this->template_dir = $theme_dir . '/templates';
        $this->partials_dir = $theme_dir . '/template-parts';

        // Create directories if they don't exist
        if ( ! empty( $theme_dir ) ) {
            if ( ! is_dir( $this->template_dir ) ) {
                wp_mkdir_p( $this->template_dir );
            }
            if ( ! is_dir( $this->partials_dir ) ) {
                wp_mkdir_p( $this->partials_dir );
            }
        }
    }

    /**
     * Load template configuration
     *
     * @since 2.0.0
     */
    private function load_configuration(): void {
        $this->config = [
            'cache_templates' => true,
            'cache_duration' => 3600, // 1 hour
            'debug_mode' => defined( 'WP_DEBUG' ) && WP_DEBUG,
            'minify_output' => true,
            'auto_escape' => true,
            'strict_variables' => false,
            'template_extension' => '.php',
            'partial_prefix' => 'partial-',
            'component_prefix' => 'component-',
            'layout_prefix' => 'layout-',
            'custom_template_path' => '/templates/custom',
            'fallback_templates' => true,
            'template_inheritance' => true,
            'hooks_enabled' => true,
            'context_isolation' => true
        ];

        // Allow configuration override via filters
        if ( function_exists( 'apply_filters' ) ) {
            /** @var array $config */
            $config = apply_filters( 'aqualuxe_template_config', $this->config );
            $this->config = $config;
        }
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 2.0.0
     */
    private function initialize_hooks(): void {
        if ( ! function_exists( 'add_action' ) || ! function_exists( 'add_filter' ) ) {
            return;
        }

        // Template loading hooks
        add_filter( 'template_include', [ $this, 'intercept_template' ], 99 );
        add_action( 'template_redirect', [ $this, 'setup_template_context' ] );
        
        // Template part hooks
        add_action( 'get_template_part', [ $this, 'track_template_parts' ], 10, 2 );
        
        // Theme customizer integration
        add_action( 'customize_register', [ $this, 'register_template_customizer' ] );
        
        // Admin hooks for template management
        if ( function_exists( 'is_admin' ) && is_admin() ) {
            add_action( 'admin_menu', [ $this, 'add_template_admin_page' ] );
            add_action( 'wp_ajax_aqualuxe_clear_template_cache', [ $this, 'clear_template_cache' ] );
        }
    }

    /**
     * Render a template with given context
     *
     * @since 2.0.0
     * @param string $template_name Template name
     * @param array $context Template context variables
     * @param bool $return Whether to return output or echo
     * @return string|void Template output if $return is true
     */
    public function render( string $template_name, array $context = [], bool $return = false ) {
        $template_path = $this->locate_template( $template_name );
        
        if ( ! $template_path ) {
            if ( $this->config['debug_mode'] ) {
                $error_message = sprintf( 'Template not found: %s', $template_name );
                if ( $return ) {
                    return '<!-- ' . esc_html( $error_message ) . ' -->';
                }
                echo '<!-- ' . esc_html( $error_message ) . ' -->';
                return;
            }
            return $return ? '' : null;
        }

        // Start output buffering
        if ( $return ) {
            ob_start();
        }

        $this->render_template( $template_path, $context );

        if ( $return ) {
            return ob_get_clean();
        }
    }

    /**
     * Render a partial template
     *
     * @since 2.0.0
     * @param string $partial_name Partial name
     * @param array $context Partial context
     * @param bool $return Whether to return output
     * @return string|void Partial output if $return is true
     */
    public function partial( string $partial_name, array $context = [], bool $return = false ) {
        $partial_template = $this->config['partial_prefix'] . $partial_name;
        return $this->render( $partial_template, $context, $return );
    }

    /**
     * Render a component template
     *
     * @since 2.0.0
     * @param string $component_name Component name
     * @param array $context Component context
     * @param bool $return Whether to return output
     * @return string|void Component output if $return is true
     */
    public function component( string $component_name, array $context = [], bool $return = false ) {
        $component_template = $this->config['component_prefix'] . $component_name;
        return $this->render( $component_template, $context, $return );
    }

    /**
     * Render a layout template
     *
     * @since 2.0.0
     * @param string $layout_name Layout name
     * @param array $context Layout context
     * @param string $content_slot Content to insert into layout
     * @return string|void Layout output
     */
    public function layout( string $layout_name, array $context = [], string $content_slot = '' ) {
        $layout_template = $this->config['layout_prefix'] . $layout_name;
        $context['content_slot'] = $content_slot;
        
        return $this->render( $layout_template, $context, true );
    }

    /**
     * Locate template file
     *
     * @since 2.0.0
     * @param string $template_name Template name
     * @return string|false Template path or false if not found
     */
    public function locate_template( string $template_name ) {
        // Add extension if not present
        if ( ! str_ends_with( $template_name, $this->config['template_extension'] ) ) {
            $template_name .= $this->config['template_extension'];
        }

        $locations = $this->get_template_locations( $template_name );

        foreach ( $locations as $location ) {
            if ( file_exists( $location ) && is_readable( $location ) ) {
                return $location;
            }
        }

        return false;
    }

    /**
     * Get possible template locations
     *
     * @since 2.0.0
     * @param string $template_name Template name
     * @return array Template locations to check
     */
    private function get_template_locations( string $template_name ): array {
        $locations = [];
        
        // Child theme templates
        if ( function_exists( 'get_stylesheet_directory' ) ) {
            $child_theme_dir = get_stylesheet_directory();
            $locations[] = $child_theme_dir . '/templates/' . $template_name;
            $locations[] = $child_theme_dir . '/template-parts/' . $template_name;
            $locations[] = $child_theme_dir . '/' . $template_name;
        }

        // Parent theme templates
        if ( function_exists( 'get_template_directory' ) ) {
            $parent_theme_dir = get_template_directory();
            $locations[] = $parent_theme_dir . '/templates/' . $template_name;
            $locations[] = $parent_theme_dir . '/template-parts/' . $template_name;
            $locations[] = $parent_theme_dir . '/' . $template_name;
        }

        // Custom template path
        if ( ! empty( $this->template_dir ) ) {
            $locations[] = $this->template_dir . '/' . $template_name;
            $locations[] = $this->partials_dir . '/' . $template_name;
        }

        // Plugin template overrides
        $locations[] = WP_CONTENT_DIR . '/themes-custom/aqualuxe/' . $template_name;

        return array_unique( $locations );
    }

    /**
     * Render template file with context
     *
     * @since 2.0.0
     * @param string $template_path Path to template file
     * @param array $context Template context
     */
    private function render_template( string $template_path, array $context ): void {
        // Push context to stack for nested templates
        $this->push_context( $context );

        // Extract context variables
        if ( $this->config['context_isolation'] ) {
            // Isolated context - only provided variables are available
            extract( $context, EXTR_SKIP );
        } else {
            // Global context - merge with existing variables
            extract( $context, EXTR_OVERWRITE );
        }

        // Execute template hooks
        if ( $this->config['hooks_enabled'] ) {
            $this->execute_template_hooks( 'before_template', $template_path, $context );
        }

        // Include template file
        include $template_path;

        // Execute after template hooks
        if ( $this->config['hooks_enabled'] ) {
            $this->execute_template_hooks( 'after_template', $template_path, $context );
        }

        // Pop context from stack
        $this->pop_context();

        // Track loaded template
        $this->loaded_templates[] = $template_path;
    }

    /**
     * Push context to stack
     *
     * @since 2.0.0
     * @param array $context Context to push
     */
    private function push_context( array $context ): void {
        $this->context_stack[] = $context;
    }

    /**
     * Pop context from stack
     *
     * @since 2.0.0
     * @return array|null Popped context or null if stack is empty
     */
    private function pop_context(): ?array {
        return array_pop( $this->context_stack );
    }

    /**
     * Get current context
     *
     * @since 2.0.0
     * @return array Current context
     */
    public function get_current_context(): array {
        return end( $this->context_stack ) ?: [];
    }

    /**
     * Execute template hooks
     *
     * @since 2.0.0
     * @param string $hook_name Hook name
     * @param string $template_path Template path
     * @param array $context Template context
     */
    private function execute_template_hooks( string $hook_name, string $template_path, array $context ): void {
        if ( ! function_exists( 'do_action' ) ) {
            return;
        }

        $template_name = basename( $template_path, $this->config['template_extension'] );
        
        // Execute general hook
        do_action( 'aqualuxe_' . $hook_name, $template_path, $context );
        
        // Execute specific template hook
        do_action( 'aqualuxe_' . $hook_name . '_' . $template_name, $template_path, $context );
    }

    /**
     * Add template hook
     *
     * @since 2.0.0
     * @param string $hook_name Hook name
     * @param callable $callback Hook callback
     * @param int $priority Hook priority
     */
    public function add_template_hook( string $hook_name, callable $callback, int $priority = 10 ): void {
        if ( ! isset( $this->template_hooks[ $hook_name ] ) ) {
            $this->template_hooks[ $hook_name ] = [];
        }

        $this->template_hooks[ $hook_name ][] = [
            'callback' => $callback,
            'priority' => $priority
        ];

        // Sort by priority
        uasort( $this->template_hooks[ $hook_name ], function( $a, $b ) {
            return $a['priority'] <=> $b['priority'];
        } );
    }

    /**
     * Intercept WordPress template loading
     *
     * @since 2.0.0
     * @param string $template Template path
     * @return string Modified template path
     */
    public function intercept_template( string $template ): string {
        // Check for custom template overrides
        $template_name = basename( $template );
        $custom_template = $this->locate_template( 'custom-' . $template_name );
        
        if ( $custom_template ) {
            return $custom_template;
        }

        return $template;
    }

    /**
     * Setup template context for current request
     *
     * @since 2.0.0
     */
    public function setup_template_context(): void {
        global $wp_query;

        $context = [
            'is_front_page' => function_exists( 'is_front_page' ) ? is_front_page() : false,
            'is_home' => function_exists( 'is_home' ) ? is_home() : false,
            'is_single' => function_exists( 'is_single' ) ? is_single() : false,
            'is_page' => function_exists( 'is_page' ) ? is_page() : false,
            'is_archive' => function_exists( 'is_archive' ) ? is_archive() : false,
            'is_search' => function_exists( 'is_search' ) ? is_search() : false,
            'is_404' => function_exists( 'is_404' ) ? is_404() : false,
            'query_vars' => $wp_query->query_vars ?? [],
            'theme_uri' => function_exists( 'get_template_directory_uri' ) ? get_template_directory_uri() : '',
            'site_url' => function_exists( 'site_url' ) ? site_url() : '',
            'home_url' => function_exists( 'home_url' ) ? home_url() : ''
        ];

        // Add current post data if available
        if ( function_exists( 'get_queried_object' ) ) {
            $queried_object = get_queried_object();
            if ( $queried_object ) {
                $context['current_object'] = $queried_object;
            }
        }

        $this->push_context( $context );
    }

    /**
     * Track template parts being loaded
     *
     * @since 2.0.0
     * @param string $slug Template slug
     * @param string $name Template name
     */
    public function track_template_parts( string $slug, string $name = '' ): void {
        $template_part = $name ? $slug . '-' . $name : $slug;
        $this->loaded_templates[] = 'template-parts/' . $template_part . '.php';
    }

    /**
     * Register template customizer controls
     *
     * @since 2.0.0
     * @param object $wp_customize WordPress Customizer object
     */
    public function register_template_customizer( $wp_customize ): void {
        // Add template section
        $wp_customize->add_section( 'aqualuxe_templates', [
            'title' => 'Template Settings',
            'priority' => 120,
        ] );

        // Add template cache setting
        $wp_customize->add_setting( 'aqualuxe_template_cache', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
        ] );

        $wp_customize->add_control( 'aqualuxe_template_cache', [
            'label' => 'Enable Template Caching',
            'section' => 'aqualuxe_templates',
            'type' => 'checkbox',
        ] );
    }

    /**
     * Add template admin page
     *
     * @since 2.0.0
     */
    public function add_template_admin_page(): void {
        if ( function_exists( 'add_theme_page' ) ) {
            add_theme_page(
                'Template Manager',
                'Templates',
                'edit_theme_options',
                'aqualuxe-templates',
                [ $this, 'render_template_admin_page' ]
            );
        }
    }

    /**
     * Render template admin page
     *
     * @since 2.0.0
     */
    public function render_template_admin_page(): void {
        $templates = $this->get_available_templates();
        ?>
        <div class="wrap">
            <h1>AquaLuxe Template Manager</h1>
            
            <div class="card">
                <h2>Available Templates</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Template Name</th>
                            <th>Location</th>
                            <th>Last Modified</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $templates as $template ) : ?>
                        <tr>
                            <td><?php echo esc_html( basename( $template ) ); ?></td>
                            <td><?php echo esc_html( str_replace( ABSPATH, '', $template ) ); ?></td>
                            <td><?php echo esc_html( date( 'Y-m-d H:i:s', filemtime( $template ) ) ); ?></td>
                            <td>
                                <button class="button button-small" onclick="clearTemplateCache('<?php echo esc_js( $template ); ?>')">
                                    Clear Cache
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>Cache Management</h2>
                <p>
                    <button class="button button-primary" onclick="clearAllTemplateCache()">
                        Clear All Template Cache
                    </button>
                </p>
            </div>
        </div>

        <script>
        function clearTemplateCache(template) {
            if (confirm('Clear cache for this template?')) {
                // AJAX call to clear specific template cache
                jQuery.post(ajaxurl, {
                    action: 'aqualuxe_clear_template_cache',
                    template: template,
                    nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_template_cache' ) ); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('Template cache cleared successfully.');
                    }
                });
            }
        }

        function clearAllTemplateCache() {
            if (confirm('Clear all template cache?')) {
                // AJAX call to clear all template cache
                jQuery.post(ajaxurl, {
                    action: 'aqualuxe_clear_template_cache',
                    all: true,
                    nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_template_cache' ) ); ?>'
                }, function(response) {
                    if (response.success) {
                        alert('All template cache cleared successfully.');
                        location.reload();
                    }
                });
            }
        }
        </script>
        <?php
    }

    /**
     * Get available templates
     *
     * @since 2.0.0
     * @return array Template file paths
     */
    private function get_available_templates(): array {
        $templates = [];
        
        $directories = [
            $this->template_dir,
            $this->partials_dir
        ];

        foreach ( $directories as $dir ) {
            if ( is_dir( $dir ) ) {
                $files = glob( $dir . '/*' . $this->config['template_extension'] );
                if ( $files ) {
                    $templates = array_merge( $templates, $files );
                }
            }
        }

        return $templates;
    }

    /**
     * Clear template cache
     *
     * @since 2.0.0
     */
    public function clear_template_cache(): void {
        if ( ! function_exists( 'wp_verify_nonce' ) || 
             ! wp_verify_nonce( $_POST['nonce'] ?? '', 'aqualuxe_template_cache' ) ) {
            wp_die( 'Security check failed.' );
        }

        if ( isset( $_POST['all'] ) ) {
            $this->template_cache = [];
            wp_send_json_success( 'All template cache cleared.' );
        } elseif ( isset( $_POST['template'] ) ) {
            $template = sanitize_text_field( $_POST['template'] );
            unset( $this->template_cache[ $template ] );
            wp_send_json_success( 'Template cache cleared.' );
        }

        wp_send_json_error( 'Invalid request.' );
    }

    /**
     * Escape output for safe display
     *
     * @since 2.0.0
     * @param mixed $value Value to escape
     * @param string $context Escape context
     * @return string Escaped value
     */
    public function escape( $value, string $context = 'html' ): string {
        if ( ! is_string( $value ) ) {
            $value = (string) $value;
        }

        switch ( $context ) {
            case 'attr':
                return function_exists( 'esc_attr' ) ? esc_attr( $value ) : htmlspecialchars( $value, ENT_QUOTES );
            case 'url':
                return function_exists( 'esc_url' ) ? esc_url( $value ) : filter_var( $value, FILTER_SANITIZE_URL );
            case 'js':
                return function_exists( 'esc_js' ) ? esc_js( $value ) : addslashes( $value );
            case 'html':
            default:
                return function_exists( 'esc_html' ) ? esc_html( $value ) : htmlspecialchars( $value );
        }
    }

    /**
     * Get template configuration
     *
     * @since 2.0.0
     * @return array Template configuration
     */
    public function get_config(): array {
        return $this->config;
    }

    /**
     * Get loaded templates
     *
     * @since 2.0.0
     * @return array Loaded template paths
     */
    public function get_loaded_templates(): array {
        return $this->loaded_templates;
    }

    /**
     * Check if template exists
     *
     * @since 2.0.0
     * @param string $template_name Template name
     * @return bool True if template exists
     */
    public function template_exists( string $template_name ): bool {
        return $this->locate_template( $template_name ) !== false;
    }

    /**
     * Update template configuration
     *
     * @since 2.0.0
     * @param array $new_config New configuration values
     */
    public function update_config( array $new_config ): void {
        $this->config = array_merge( $this->config, $new_config );
        
        // Clear cache when configuration changes
        $this->template_cache = [];
        
        // Trigger configuration update action
        if ( function_exists( 'do_action' ) ) {
            do_action( 'aqualuxe_template_config_updated', $this->config );
        }
    }
}
