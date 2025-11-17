<?php
/**
 * Split Menu Walker
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Split Menu Walker Class
 */
class AquaLuxe_Split_Menu_Walker extends Walker_Nav_Menu {
    /**
     * Start index for menu items.
     *
     * @var int
     */
    private $start_index;

    /**
     * End index for menu items.
     *
     * @var int
     */
    private $end_index;

    /**
     * Current index for menu items.
     *
     * @var int
     */
    private $current_index = 0;

    /**
     * Constructor.
     *
     * @param int $start_index Start index.
     * @param int $end_index   End index.
     */
    public function __construct( $start_index, $end_index ) {
        $this->start_index = $start_index;
        $this->end_index   = $end_index;
    }

    /**
     * Start the element output.
     *
     * @param string  $output            Used to append additional content.
     * @param WP_Post $item              Menu item data object.
     * @param int     $depth             Depth of menu item.
     * @param array   $args              An array of wp_nav_menu() arguments.
     * @param int     $current_object_id Optional. ID of the current menu item. Default 0.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
        // Only display items within the specified range.
        if ( $this->current_index >= $this->start_index && $this->current_index <= $this->end_index ) {
            parent::start_el( $output, $item, $depth, $args, $current_object_id );
        }

        $this->current_index++;
    }

    /**
     * End the element output.
     *
     * @param string  $output Used to append additional content.
     * @param WP_Post $item   Menu item data object.
     * @param int     $depth  Depth of menu item.
     * @param array   $args   An array of wp_nav_menu() arguments.
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        // Only display items within the specified range.
        $index = $this->current_index - 1;
        if ( $index >= $this->start_index && $index <= $this->end_index ) {
            parent::end_el( $output, $item, $depth, $args );
        }
    }

    /**
     * Start the level output.
     *
     * @param string  $output Used to append additional content.
     * @param int     $depth  Depth of menu item.
     * @param array   $args   An array of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        // Only display submenu if parent is within the specified range.
        $index = $this->current_index - 1;
        if ( $index >= $this->start_index && $index <= $this->end_index ) {
            parent::start_lvl( $output, $depth, $args );
        }
    }

    /**
     * End the level output.
     *
     * @param string  $output Used to append additional content.
     * @param int     $depth  Depth of menu item.
     * @param array   $args   An array of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        // Only display submenu if parent is within the specified range.
        $index = $this->current_index - 1;
        if ( $index >= $this->start_index && $index <= $this->end_index ) {
            parent::end_lvl( $output, $depth, $args );
        }
    }
}