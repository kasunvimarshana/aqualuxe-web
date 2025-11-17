<?php
/**
 * Hooks class
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
 * Hooks class
 */
class Hooks {
    /**
     * Hooks registry
     *
     * @var array
     */
    private static $hooks = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Register default hooks
        $this->register_default_hooks();
    }

    /**
     * Register default hooks
     */
    private function register_default_hooks() {
        // Header hooks
        self::$hooks['aqualuxe_before_header'] = [
            'description' => esc_html__( 'Fires before the header', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_header'] = [
            'description' => esc_html__( 'Fires during the header', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_after_header'] = [
            'description' => esc_html__( 'Fires after the header', 'aqualuxe' ),
            'arguments'   => [],
        ];

        // Footer hooks
        self::$hooks['aqualuxe_before_footer'] = [
            'description' => esc_html__( 'Fires before the footer', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_footer'] = [
            'description' => esc_html__( 'Fires during the footer', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_after_footer'] = [
            'description' => esc_html__( 'Fires after the footer', 'aqualuxe' ),
            'arguments'   => [],
        ];

        // Content hooks
        self::$hooks['aqualuxe_before_content'] = [
            'description' => esc_html__( 'Fires before the content', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_content'] = [
            'description' => esc_html__( 'Fires during the content', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_after_content'] = [
            'description' => esc_html__( 'Fires after the content', 'aqualuxe' ),
            'arguments'   => [],
        ];

        // Sidebar hooks
        self::$hooks['aqualuxe_before_sidebar'] = [
            'description' => esc_html__( 'Fires before the sidebar', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_sidebar'] = [
            'description' => esc_html__( 'Fires during the sidebar', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_after_sidebar'] = [
            'description' => esc_html__( 'Fires after the sidebar', 'aqualuxe' ),
            'arguments'   => [],
        ];

        // Post hooks
        self::$hooks['aqualuxe_before_post'] = [
            'description' => esc_html__( 'Fires before the post', 'aqualuxe' ),
            'arguments'   => [ 'post_id' ],
        ];

        self::$hooks['aqualuxe_post'] = [
            'description' => esc_html__( 'Fires during the post', 'aqualuxe' ),
            'arguments'   => [ 'post_id' ],
        ];

        self::$hooks['aqualuxe_after_post'] = [
            'description' => esc_html__( 'Fires after the post', 'aqualuxe' ),
            'arguments'   => [ 'post_id' ],
        ];

        // Page hooks
        self::$hooks['aqualuxe_before_page'] = [
            'description' => esc_html__( 'Fires before the page', 'aqualuxe' ),
            'arguments'   => [ 'page_id' ],
        ];

        self::$hooks['aqualuxe_page'] = [
            'description' => esc_html__( 'Fires during the page', 'aqualuxe' ),
            'arguments'   => [ 'page_id' ],
        ];

        self::$hooks['aqualuxe_after_page'] = [
            'description' => esc_html__( 'Fires after the page', 'aqualuxe' ),
            'arguments'   => [ 'page_id' ],
        ];

        // Comments hooks
        self::$hooks['aqualuxe_before_comments'] = [
            'description' => esc_html__( 'Fires before the comments', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_comments'] = [
            'description' => esc_html__( 'Fires during the comments', 'aqualuxe' ),
            'arguments'   => [],
        ];

        self::$hooks['aqualuxe_after_comments'] = [
            'description' => esc_html__( 'Fires after the comments', 'aqualuxe' ),
            'arguments'   => [],
        ];

        // WooCommerce hooks
        if ( class_exists( 'WooCommerce' ) ) {
            self::$hooks['aqualuxe_before_shop'] = [
                'description' => esc_html__( 'Fires before the shop', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_shop'] = [
                'description' => esc_html__( 'Fires during the shop', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_after_shop'] = [
                'description' => esc_html__( 'Fires after the shop', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_before_product'] = [
                'description' => esc_html__( 'Fires before the product', 'aqualuxe' ),
                'arguments'   => [ 'product_id' ],
            ];

            self::$hooks['aqualuxe_product'] = [
                'description' => esc_html__( 'Fires during the product', 'aqualuxe' ),
                'arguments'   => [ 'product_id' ],
            ];

            self::$hooks['aqualuxe_after_product'] = [
                'description' => esc_html__( 'Fires after the product', 'aqualuxe' ),
                'arguments'   => [ 'product_id' ],
            ];

            self::$hooks['aqualuxe_before_cart'] = [
                'description' => esc_html__( 'Fires before the cart', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_cart'] = [
                'description' => esc_html__( 'Fires during the cart', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_after_cart'] = [
                'description' => esc_html__( 'Fires after the cart', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_before_checkout'] = [
                'description' => esc_html__( 'Fires before the checkout', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_checkout'] = [
                'description' => esc_html__( 'Fires during the checkout', 'aqualuxe' ),
                'arguments'   => [],
            ];

            self::$hooks['aqualuxe_after_checkout'] = [
                'description' => esc_html__( 'Fires after the checkout', 'aqualuxe' ),
                'arguments'   => [],
            ];
        }

        // Module hooks
        self::$hooks['aqualuxe_before_module'] = [
            'description' => esc_html__( 'Fires before a module', 'aqualuxe' ),
            'arguments'   => [ 'module' ],
        ];

        self::$hooks['aqualuxe_module'] = [
            'description' => esc_html__( 'Fires during a module', 'aqualuxe' ),
            'arguments'   => [ 'module' ],
        ];

        self::$hooks['aqualuxe_after_module'] = [
            'description' => esc_html__( 'Fires after a module', 'aqualuxe' ),
            'arguments'   => [ 'module' ],
        ];

        // Register hooks
        self::register_hooks();
    }

    /**
     * Register hooks
     */
    private static function register_hooks() {
        // Allow modules to register hooks
        self::$hooks = apply_filters( 'aqualuxe_hooks', self::$hooks );
    }

    /**
     * Register a hook
     *
     * @param string $hook_name The hook name.
     * @param array  $args The hook arguments.
     */
    public static function register_hook( $hook_name, $args ) {
        self::$hooks[ $hook_name ] = $args;
    }

    /**
     * Get all hooks
     *
     * @return array The hooks.
     */
    public static function get_hooks() {
        return self::$hooks;
    }

    /**
     * Get a hook
     *
     * @param string $hook_name The hook name.
     * @return array|false The hook or false if not found.
     */
    public static function get_hook( $hook_name ) {
        return isset( self::$hooks[ $hook_name ] ) ? self::$hooks[ $hook_name ] : false;
    }

    /**
     * Check if a hook exists
     *
     * @param string $hook_name The hook name.
     * @return bool Whether the hook exists.
     */
    public static function hook_exists( $hook_name ) {
        return isset( self::$hooks[ $hook_name ] );
    }

    /**
     * Do a hook
     *
     * @param string $hook_name The hook name.
     * @param mixed  ...$args The hook arguments.
     */
    public static function do_hook( $hook_name, ...$args ) {
        if ( self::hook_exists( $hook_name ) ) {
            do_action( $hook_name, ...$args );
        }
    }

    /**
     * Apply a hook filter
     *
     * @param string $hook_name The hook name.
     * @param mixed  $value The value to filter.
     * @param mixed  ...$args The hook arguments.
     * @return mixed The filtered value.
     */
    public static function apply_hook_filter( $hook_name, $value, ...$args ) {
        if ( self::hook_exists( $hook_name ) ) {
            return apply_filters( $hook_name, $value, ...$args );
        }

        return $value;
    }
}

// Initialize the class
new Hooks();