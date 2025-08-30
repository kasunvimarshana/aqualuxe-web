<?php
/**
 * AquaLuxe Navigation Menus
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Navigation Menus Class
 */
class AquaLuxe_Nav_Menus {
    /**
     * Constructor
     */
    public function __construct() {
        // Register nav walker
        require_once AQUALUXE_DIR . 'inc/class-aqualuxe-walker-nav-menu.php';
        
        // Add custom fields to menu items
        add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_menu_item' ) );
        
        // Save custom fields
        add_action( 'wp_update_nav_menu_item', array( $this, 'update_nav_menu_item' ), 10, 3 );
        
        // Add custom fields to menu item admin screen
        add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ) );
        
        // Add menu item custom fields
        add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_custom_fields' ), 10, 4 );
        
        // Add mega menu support
        add_filter( 'nav_menu_css_class', array( $this, 'add_menu_item_classes' ), 10, 4 );
        
        // Add menu item description support
        add_filter( 'walker_nav_menu_start_el', array( $this, 'add_description' ), 10, 4 );
    }

    /**
     * Setup nav menu items
     *
     * @param object $menu_item Menu item object.
     * @return object
     */
    public function setup_nav_menu_item( $menu_item ) {
        // Add custom menu item properties
        $menu_item->mega_menu = get_post_meta( $menu_item->ID, '_menu_item_mega_menu', true );
        $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
        $menu_item->badge = get_post_meta( $menu_item->ID, '_menu_item_badge', true );
        $menu_item->badge_color = get_post_meta( $menu_item->ID, '_menu_item_badge_color', true );
        
        return $menu_item;
    }

    /**
     * Update nav menu item meta
     *
     * @param int $menu_id Menu ID.
     * @param int $menu_item_db_id Menu item ID.
     * @param array $args Menu item data.
     */
    public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
        // Update mega menu setting
        if ( isset( $_REQUEST['menu-item-mega-menu'][ $menu_item_db_id ] ) ) {
            update_post_meta( 
                $menu_item_db_id, 
                '_menu_item_mega_menu', 
                sanitize_text_field( $_REQUEST['menu-item-mega-menu'][ $menu_item_db_id ] ) 
            );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_mega_menu' );
        }
        
        // Update icon
        if ( isset( $_REQUEST['menu-item-icon'][ $menu_item_db_id ] ) ) {
            update_post_meta( 
                $menu_item_db_id, 
                '_menu_item_icon', 
                sanitize_text_field( $_REQUEST['menu-item-icon'][ $menu_item_db_id ] ) 
            );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_icon' );
        }
        
        // Update badge
        if ( isset( $_REQUEST['menu-item-badge'][ $menu_item_db_id ] ) ) {
            update_post_meta( 
                $menu_item_db_id, 
                '_menu_item_badge', 
                sanitize_text_field( $_REQUEST['menu-item-badge'][ $menu_item_db_id ] ) 
            );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_badge' );
        }
        
        // Update badge color
        if ( isset( $_REQUEST['menu-item-badge-color'][ $menu_item_db_id ] ) ) {
            update_post_meta( 
                $menu_item_db_id, 
                '_menu_item_badge_color', 
                sanitize_hex_color( $_REQUEST['menu-item-badge-color'][ $menu_item_db_id ] ) 
            );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_badge_color' );
        }
    }

    /**
     * Set custom walker for menu edit screen
     *
     * @return string
     */
    public function edit_nav_menu_walker() {
        return 'AquaLuxe_Walker_Nav_Menu_Edit';
    }

    /**
     * Add custom fields to menu items in admin
     *
     * @param int $item_id Menu item ID.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item.
     * @param array $args Menu item args.
     */
    public function add_custom_fields( $item_id, $item, $depth, $args ) {
        // Get current values
        $mega_menu = get_post_meta( $item_id, '_menu_item_mega_menu', true );
        $icon = get_post_meta( $item_id, '_menu_item_icon', true );
        $badge = get_post_meta( $item_id, '_menu_item_badge', true );
        $badge_color = get_post_meta( $item_id, '_menu_item_badge_color', true );
        
        // Only show mega menu option for top level items
        if ( $depth === 0 ) {
            ?>
            <p class="field-mega-menu description description-wide">
                <label for="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>">
                    <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo esc_attr( $item_id ); ?>" 
                           name="menu-item-mega-menu[<?php echo esc_attr( $item_id ); ?>]" 
                           value="1" <?php checked( $mega_menu, '1' ); ?> />
                    <?php esc_html_e( 'Enable Mega Menu', 'aqualuxe' ); ?>
                </label>
            </p>
            <?php
        }
        
        // Icon field for all items
        ?>
        <p class="field-icon description description-thin">
            <label for="edit-menu-item-icon-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Icon Class', 'aqualuxe' ); ?><br />
                <input type="text" id="edit-menu-item-icon-<?php echo esc_attr( $item_id ); ?>" 
                       class="widefat edit-menu-item-icon" 
                       name="menu-item-icon[<?php echo esc_attr( $item_id ); ?>]" 
                       value="<?php echo esc_attr( $icon ); ?>" />
                <span class="description"><?php esc_html_e( 'FontAwesome icon class (e.g. fa-home)', 'aqualuxe' ); ?></span>
            </label>
        </p>
        
        <!-- Badge text -->
        <p class="field-badge description description-thin">
            <label for="edit-menu-item-badge-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Badge Text', 'aqualuxe' ); ?><br />
                <input type="text" id="edit-menu-item-badge-<?php echo esc_attr( $item_id ); ?>" 
                       class="widefat edit-menu-item-badge" 
                       name="menu-item-badge[<?php echo esc_attr( $item_id ); ?>]" 
                       value="<?php echo esc_attr( $badge ); ?>" />
                <span class="description"><?php esc_html_e( 'Badge text (e.g. New, Hot)', 'aqualuxe' ); ?></span>
            </label>
        </p>
        
        <!-- Badge color -->
        <p class="field-badge-color description description-thin">
            <label for="edit-menu-item-badge-color-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Badge Color', 'aqualuxe' ); ?><br />
                <input type="color" id="edit-menu-item-badge-color-<?php echo esc_attr( $item_id ); ?>" 
                       class="widefat edit-menu-item-badge-color" 
                       name="menu-item-badge-color[<?php echo esc_attr( $item_id ); ?>]" 
                       value="<?php echo esc_attr( $badge_color ); ?>" />
            </label>
        </p>
        <?php
    }

    /**
     * Add custom classes to menu items
     *
     * @param array $classes Menu item classes.
     * @param object $item Menu item object.
     * @param object $args Menu args.
     * @param int $depth Menu item depth.
     * @return array
     */
    public function add_menu_item_classes( $classes, $item, $args, $depth ) {
        // Add mega menu class
        if ( $depth === 0 && ! empty( $item->mega_menu ) ) {
            $classes[] = 'mega-menu-item';
        }
        
        // Add icon class
        if ( ! empty( $item->icon ) ) {
            $classes[] = 'menu-item-has-icon';
        }
        
        // Add badge class
        if ( ! empty( $item->badge ) ) {
            $classes[] = 'menu-item-has-badge';
        }
        
        return $classes;
    }

    /**
     * Add description to menu items
     *
     * @param string $item_output Menu item output.
     * @param object $item Menu item object.
     * @param int $depth Menu item depth.
     * @param object $args Menu args.
     * @return string
     */
    public function add_description( $item_output, $item, $depth, $args ) {
        // Add icon if set
        if ( ! empty( $item->icon ) ) {
            $icon = '<i class="fa ' . esc_attr( $item->icon ) . '" aria-hidden="true"></i> ';
            $item_output = preg_replace( '/(<a[^>]*>)/', '$1' . $icon, $item_output );
        }
        
        // Add badge if set
        if ( ! empty( $item->badge ) ) {
            $badge_style = ! empty( $item->badge_color ) ? ' style="background-color: ' . esc_attr( $item->badge_color ) . ';"' : '';
            $badge = '<span class="menu-item-badge"' . $badge_style . '>' . esc_html( $item->badge ) . '</span>';
            $item_output = preg_replace( '/(<\/a>)/', $badge . '$1', $item_output );
        }
        
        // Add description if available and enabled in theme
        if ( ! empty( $item->description ) && get_theme_mod( 'aqualuxe_menu_descriptions', true ) ) {
            $item_output = str_replace( '</a>', '<span class="menu-item-description">' . $item->description . '</span></a>', $item_output );
        }
        
        return $item_output;
    }
}

// Initialize the class
new AquaLuxe_Nav_Menus();