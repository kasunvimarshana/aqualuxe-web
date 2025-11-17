<?php
/**
 * Navigation menu related functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register custom menu walker
 */
class AquaLuxe_Nav_Walker extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add active class for current menu item
        if (in_array('current-menu-item', $classes, true)) {
            $classes[] = 'active';
        }

        // Add has-children class for menu items with children
        if (in_array('menu-item-has-children', $classes, true)) {
            $classes[] = 'has-children';
        }

        // Add depth-specific classes
        $classes[] = 'menu-depth-' . $depth;

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item.
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener noreferrer';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']         = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title        Title attribute.
         *     @type string $target       Target attribute.
         *     @type string $rel          The rel attribute.
         *     @type string $href         The href attribute.
         *     @type string $aria-current The aria-current attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        
        // Add dropdown icon for menu items with children
        if (in_array('menu-item-has-children', $classes, true)) {
            $item_output .= '<span class="dropdown-icon"><i class="fas fa-chevron-down" aria-hidden="true"></i></span>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Ends the element output, if needed.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }
}

/**
 * Mobile menu walker
 */
class AquaLuxe_Mobile_Nav_Walker extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Add active class for current menu item
        if (in_array('current-menu-item', $classes, true)) {
            $classes[] = 'active';
        }

        // Add has-children class for menu items with children
        if (in_array('menu-item-has-children', $classes, true)) {
            $classes[] = 'has-children';
        }

        // Add depth-specific classes
        $classes[] = 'menu-depth-' . $depth;

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item.
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener noreferrer';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']         = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title        Title attribute.
         *     @type string $target       Target attribute.
         *     @type string $rel          The rel attribute.
         *     @type string $href         The href attribute.
         *     @type string $aria-current The aria-current attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        
        // For menu items with children, add a toggle button
        if (in_array('menu-item-has-children', $classes, true)) {
            $item_output .= '<div class="mobile-menu-item-wrapper">';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';
            $item_output .= '<button class="mobile-submenu-toggle" aria-expanded="false">';
            $item_output .= '<span class="screen-reader-text">' . esc_html__('Toggle submenu', 'aqualuxe') . '</span>';
            $item_output .= '<i class="fas fa-chevron-down" aria-hidden="true"></i>';
            $item_output .= '</button>';
            $item_output .= '</div>';
        } else {
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';
        }
        
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Add custom classes to menu items
 *
 * @param array $classes The CSS classes that are applied to the menu item's `<li>` element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args, $depth) {
    // Add custom classes based on menu location
    if (isset($args->theme_location)) {
        $classes[] = 'menu-item-' . $args->theme_location;
    }
    
    // Add depth-specific classes
    $classes[] = 'menu-item-depth-' . $depth;
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 4);

/**
 * Add custom attributes to menu links
 *
 * @param array $atts The HTML attributes applied to the menu item's `<a>` element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args, $depth) {
    // Add custom attributes based on menu location
    if (isset($args->theme_location)) {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' menu-link-' . $args->theme_location : 'menu-link-' . $args->theme_location;
    }
    
    // Add depth-specific classes
    $atts['class'] = isset($atts['class']) ? $atts['class'] . ' menu-link-depth-' . $depth : 'menu-link-depth-' . $depth;
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4);

/**
 * Add mega menu support
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param WP_Post $item Menu item data object.
 * @param int $depth Depth of menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return string
 */
function aqualuxe_nav_menu_start_el($item_output, $item, $depth, $args) {
    // Check if this is a mega menu item
    if (is_object($item) && isset($item->classes) && in_array('mega-menu', $item->classes, true)) {
        // Get the mega menu content
        $mega_menu_content = get_post_meta($item->ID, '_aqualuxe_mega_menu_content', true);
        
        if ($mega_menu_content) {
            $item_output .= '<div class="mega-menu-wrapper">' . do_shortcode($mega_menu_content) . '</div>';
        }
    }
    
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'aqualuxe_nav_menu_start_el', 10, 4);

/**
 * Add custom fields to menu items in the admin
 */
function aqualuxe_add_custom_nav_fields($menu_item) {
    $menu_item->mega_menu = get_post_meta($menu_item->ID, '_aqualuxe_mega_menu', true);
    $menu_item->mega_menu_content = get_post_meta($menu_item->ID, '_aqualuxe_mega_menu_content', true);
    $menu_item->icon = get_post_meta($menu_item->ID, '_aqualuxe_menu_icon', true);
    return $menu_item;
}
add_filter('wp_setup_nav_menu_item', 'aqualuxe_add_custom_nav_fields');

/**
 * Save custom fields to menu items in the admin
 *
 * @param int $menu_id The menu ID.
 * @param int $menu_item_db_id The menu item ID.
 * @param array $args The menu item data.
 */
function aqualuxe_update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {
    // Check if mega menu is set
    if (isset($_REQUEST['menu-item-mega-menu'][$menu_item_db_id])) {
        $mega_menu_value = $_REQUEST['menu-item-mega-menu'][$menu_item_db_id] ? 'on' : '';
        update_post_meta($menu_item_db_id, '_aqualuxe_mega_menu', $mega_menu_value);
    }
    
    // Check if mega menu content is set
    if (isset($_REQUEST['menu-item-mega-menu-content'][$menu_item_db_id])) {
        $mega_menu_content_value = $_REQUEST['menu-item-mega-menu-content'][$menu_item_db_id];
        update_post_meta($menu_item_db_id, '_aqualuxe_mega_menu_content', $mega_menu_content_value);
    }
    
    // Check if icon is set
    if (isset($_REQUEST['menu-item-icon'][$menu_item_db_id])) {
        $icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_icon', $icon_value);
    }
}
add_action('wp_update_nav_menu_item', 'aqualuxe_update_custom_nav_fields', 10, 3);

/**
 * Add custom fields to menu item admin screen
 *
 * @param int $item_id The menu item ID.
 * @param object $item The menu item data object.
 * @param int $depth The menu item depth.
 * @param array $args The menu item args.
 */
function aqualuxe_custom_nav_menu_fields($item_id, $item, $depth, $args) {
    ?>
    <p class="field-mega-menu description description-wide">
        <label for="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>" name="menu-item-mega-menu[<?php echo esc_attr($item_id); ?>]" value="on" <?php checked($item->mega_menu, 'on'); ?> />
            <?php esc_html_e('Enable Mega Menu', 'aqualuxe'); ?>
        </label>
    </p>
    <p class="field-mega-menu-content description description-wide">
        <label for="edit-menu-item-mega-menu-content-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Mega Menu Content', 'aqualuxe'); ?><br />
            <textarea id="edit-menu-item-mega-menu-content-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-mega-menu-content" name="menu-item-mega-menu-content[<?php echo esc_attr($item_id); ?>]" rows="3"><?php echo esc_textarea($item->mega_menu_content); ?></textarea>
            <span class="description"><?php esc_html_e('Enter shortcode or HTML content for the mega menu.', 'aqualuxe'); ?></span>
        </label>
    </p>
    <p class="field-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Icon Class', 'aqualuxe'); ?><br />
            <input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-icon" name="menu-item-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item->icon); ?>" />
            <span class="description"><?php esc_html_e('Enter Font Awesome icon class (e.g. fa-home).', 'aqualuxe'); ?></span>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'aqualuxe_custom_nav_menu_fields', 10, 4);

/**
 * Display primary navigation
 */
function aqualuxe_primary_navigation() {
    if (has_nav_menu('primary')) {
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'menu_class' => 'primary-menu',
            'container' => 'nav',
            'container_class' => 'primary-navigation',
            'container_id' => 'primary-navigation',
            'walker' => new AquaLuxe_Nav_Walker(),
            'fallback_cb' => false,
        ));
    }
}

/**
 * Display mobile navigation
 */
function aqualuxe_mobile_navigation() {
    if (has_nav_menu('mobile')) {
        wp_nav_menu(array(
            'theme_location' => 'mobile',
            'menu_id' => 'mobile-menu',
            'menu_class' => 'mobile-menu',
            'container' => 'nav',
            'container_class' => 'mobile-navigation',
            'container_id' => 'mobile-navigation',
            'walker' => new AquaLuxe_Mobile_Nav_Walker(),
            'fallback_cb' => false,
        ));
    } elseif (has_nav_menu('primary')) {
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_id' => 'mobile-menu',
            'menu_class' => 'mobile-menu',
            'container' => 'nav',
            'container_class' => 'mobile-navigation',
            'container_id' => 'mobile-navigation',
            'walker' => new AquaLuxe_Mobile_Nav_Walker(),
            'fallback_cb' => false,
        ));
    }
}

/**
 * Display footer navigation
 */
function aqualuxe_footer_navigation() {
    if (has_nav_menu('footer')) {
        wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_id' => 'footer-menu',
            'menu_class' => 'footer-menu',
            'container' => 'nav',
            'container_class' => 'footer-navigation',
            'container_id' => 'footer-navigation',
            'depth' => 1,
            'fallback_cb' => false,
        ));
    }
}

/**
 * Display top bar navigation
 */
function aqualuxe_top_bar_navigation() {
    if (has_nav_menu('top-bar')) {
        wp_nav_menu(array(
            'theme_location' => 'top-bar',
            'menu_id' => 'top-bar-menu',
            'menu_class' => 'top-bar-menu',
            'container' => 'nav',
            'container_class' => 'top-bar-navigation',
            'container_id' => 'top-bar-navigation',
            'depth' => 1,
            'fallback_cb' => false,
        ));
    }
}

/**
 * Display account navigation
 */
function aqualuxe_account_navigation() {
    if (has_nav_menu('account')) {
        wp_nav_menu(array(
            'theme_location' => 'account',
            'menu_id' => 'account-menu',
            'menu_class' => 'account-menu',
            'container' => 'nav',
            'container_class' => 'account-navigation',
            'container_id' => 'account-navigation',
            'fallback_cb' => false,
        ));
    }
}

/**
 * Add icon to menu items
 *
 * @param string $title The menu item's title.
 * @param WP_Post $item The current menu item.
 * @return string
 */
function aqualuxe_nav_menu_item_title($title, $item, $args, $depth) {
    // Check if this item has an icon
    $icon = get_post_meta($item->ID, '_aqualuxe_menu_icon', true);
    
    if ($icon) {
        $title = '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i> ' . $title;
    }
    
    return $title;
}
add_filter('nav_menu_item_title', 'aqualuxe_nav_menu_item_title', 10, 4);

/**
 * Add custom menu items
 *
 * @param array $items The menu items.
 * @param stdClass $args The menu args.
 * @return array
 */
function aqualuxe_custom_menu_items($items, $args) {
    // Add WooCommerce cart to primary menu
    if ($args->theme_location === 'primary' && aqualuxe_is_woocommerce_active()) {
        $cart_item = new stdClass();
        $cart_item->ID = 0;
        $cart_item->db_id = 0;
        $cart_item->menu_item_parent = 0;
        $cart_item->object_id = 0;
        $cart_item->object = '';
        $cart_item->type = 'custom';
        $cart_item->type_label = 'Custom';
        $cart_item->title = '<i class="fas fa-shopping-cart" aria-hidden="true"></i> <span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        $cart_item->url = wc_get_cart_url();
        $cart_item->target = '';
        $cart_item->attr_title = '';
        $cart_item->description = '';
        $cart_item->classes = array('menu-item', 'menu-item-cart');
        $cart_item->xfn = '';
        $cart_item->current = false;
        $cart_item->current_item_ancestor = false;
        $cart_item->current_item_parent = false;
        
        $items[] = $cart_item;
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_custom_menu_items', 10, 2);

/**
 * Add search form to mobile menu
 */
function aqualuxe_mobile_menu_search_form() {
    ?>
    <div class="mobile-menu-search">
        <?php get_search_form(); ?>
    </div>
    <?php
}
add_action('aqualuxe_before_mobile_menu', 'aqualuxe_mobile_menu_search_form');

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_mobile_menu_language_switcher() {
    if (aqualuxe_is_multilingual_active()) {
        ?>
        <div class="mobile-menu-language-switcher">
            <?php aqualuxe_language_switcher(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_mobile_menu_language_switcher');

/**
 * Add currency switcher to mobile menu
 */
function aqualuxe_mobile_menu_currency_switcher() {
    if (aqualuxe_is_woocommerce_active() && aqualuxe_is_multilingual_active()) {
        ?>
        <div class="mobile-menu-currency-switcher">
            <?php aqualuxe_currency_switcher(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_mobile_menu_currency_switcher');

/**
 * Add social icons to mobile menu
 */
function aqualuxe_mobile_menu_social_icons() {
    ?>
    <div class="mobile-menu-social-icons">
        <?php aqualuxe_social_icons(); ?>
    </div>
    <?php
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_mobile_menu_social_icons');

/**
 * Add contact info to mobile menu
 */
function aqualuxe_mobile_menu_contact_info() {
    $phone = get_theme_mod('aqualuxe_contact_phone', '');
    $email = get_theme_mod('aqualuxe_contact_email', '');
    
    if ($phone || $email) {
        ?>
        <div class="mobile-menu-contact-info">
            <?php if ($phone) : ?>
                <div class="mobile-menu-contact-phone">
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <span><?php echo esc_html($phone); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
            <?php if ($email) : ?>
                <div class="mobile-menu-contact-email">
                    <a href="mailto:<?php echo esc_attr($email); ?>">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <span><?php echo esc_html($email); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_mobile_menu_contact_info');

/**
 * Add mobile menu toggle button
 */
function aqualuxe_mobile_menu_toggle() {
    ?>
    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-expanded="false" aria-controls="mobile-menu-container">
        <span class="mobile-menu-toggle-bar"></span>
        <span class="mobile-menu-toggle-bar"></span>
        <span class="mobile-menu-toggle-bar"></span>
        <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
    </button>
    <?php
}

/**
 * Add mobile menu container
 */
function aqualuxe_mobile_menu_container() {
    ?>
    <div id="mobile-menu-container" class="mobile-menu-container" aria-hidden="true">
        <div class="mobile-menu-header">
            <div class="mobile-menu-logo">
                <?php aqualuxe_site_logo(); ?>
            </div>
            <button id="mobile-menu-close" class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        </div>
        
        <div class="mobile-menu-content">
            <?php do_action('aqualuxe_before_mobile_menu'); ?>
            
            <?php aqualuxe_mobile_navigation(); ?>
            
            <?php do_action('aqualuxe_after_mobile_menu'); ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'aqualuxe_mobile_menu_container');

/**
 * Add mega menu shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'columns' => '4',
        'class' => '',
    ), $atts);
    
    $output = '<div class="mega-menu-content mega-menu-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    $output .= do_shortcode($content);
    $output .= '</div>';
    
    return $output;
}
add_shortcode('mega_menu', 'aqualuxe_mega_menu_shortcode');

/**
 * Add mega menu column shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_column_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'width' => '',
        'class' => '',
    ), $atts);
    
    $style = '';
    if ($atts['width']) {
        $style = ' style="width: ' . esc_attr($atts['width']) . ';"';
    }
    
    $output = '<div class="mega-menu-column ' . esc_attr($atts['class']) . '"' . $style . '>';
    $output .= do_shortcode($content);
    $output .= '</div>';
    
    return $output;
}
add_shortcode('mega_menu_column', 'aqualuxe_mega_menu_column_shortcode');

/**
 * Add mega menu title shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_title_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'class' => '',
    ), $atts);
    
    $output = '<h4 class="mega-menu-title ' . esc_attr($atts['class']) . '">';
    $output .= do_shortcode($content);
    $output .= '</h4>';
    
    return $output;
}
add_shortcode('mega_menu_title', 'aqualuxe_mega_menu_title_shortcode');

/**
 * Add mega menu list shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_list_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'class' => '',
    ), $atts);
    
    $output = '<ul class="mega-menu-list ' . esc_attr($atts['class']) . '">';
    $output .= do_shortcode($content);
    $output .= '</ul>';
    
    return $output;
}
add_shortcode('mega_menu_list', 'aqualuxe_mega_menu_list_shortcode');

/**
 * Add mega menu item shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_item_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'url' => '',
        'class' => '',
    ), $atts);
    
    $output = '<li class="mega-menu-item ' . esc_attr($atts['class']) . '">';
    
    if ($atts['url']) {
        $output .= '<a href="' . esc_url($atts['url']) . '">';
        $output .= do_shortcode($content);
        $output .= '</a>';
    } else {
        $output .= do_shortcode($content);
    }
    
    $output .= '</li>';
    
    return $output;
}
add_shortcode('mega_menu_item', 'aqualuxe_mega_menu_item_shortcode');

/**
 * Add mega menu featured shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_mega_menu_featured_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
        'title' => '',
        'image' => '',
        'url' => '',
        'class' => '',
    ), $atts);
    
    $output = '<div class="mega-menu-featured ' . esc_attr($atts['class']) . '">';
    
    if ($atts['url']) {
        $output .= '<a href="' . esc_url($atts['url']) . '">';
    }
    
    if ($atts['image']) {
        $output .= '<div class="mega-menu-featured-image">';
        $output .= '<img src="' . esc_url($atts['image']) . '" alt="' . esc_attr($atts['title']) . '" />';
        $output .= '</div>';
    } elseif ($atts['id']) {
        $output .= '<div class="mega-menu-featured-image">';
        $output .= wp_get_attachment_image($atts['id'], 'medium');
        $output .= '</div>';
    }
    
    if ($atts['title']) {
        $output .= '<h4 class="mega-menu-featured-title">' . esc_html($atts['title']) . '</h4>';
    }
    
    if ($atts['url']) {
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('mega_menu_featured', 'aqualuxe_mega_menu_featured_shortcode');

/**
 * Add mega menu products shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_mega_menu_products_shortcode($atts) {
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    $atts = shortcode_atts(array(
        'limit' => '4',
        'columns' => '4',
        'orderby' => 'date',
        'order' => 'desc',
        'category' => '',
        'tag' => '',
        'featured' => 'false',
        'best_selling' => 'false',
        'on_sale' => 'false',
        'class' => '',
    ), $atts);
    
    $query_args = array(
        'post_type' => 'product',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'no_found_rows' => true,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
    );
    
    if ($atts['category']) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => explode(',', $atts['category']),
            'operator' => 'IN',
        );
    }
    
    if ($atts['tag']) {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'product_tag',
            'field' => 'slug',
            'terms' => explode(',', $atts['tag']),
            'operator' => 'IN',
        );
    }
    
    if ($atts['featured'] === 'true') {
        $query_args['tax_query'][] = array(
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => 'featured',
            'operator' => 'IN',
        );
    }
    
    if ($atts['best_selling'] === 'true') {
        $query_args['meta_key'] = 'total_sales';
        $query_args['orderby'] = 'meta_value_num';
    }
    
    if ($atts['on_sale'] === 'true') {
        $query_args['post__in'] = wc_get_product_ids_on_sale();
    }
    
    $products = new WP_Query($query_args);
    
    if (!$products->have_posts()) {
        return '';
    }
    
    $output = '<div class="mega-menu-products mega-menu-products-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    
    while ($products->have_posts()) {
        $products->the_post();
        global $product;
        
        $output .= '<div class="mega-menu-product">';
        $output .= '<a href="' . esc_url(get_permalink()) . '" class="mega-menu-product-link">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="mega-menu-product-thumbnail">';
            $output .= woocommerce_get_product_thumbnail('thumbnail');
            $output .= '</div>';
        }
        
        $output .= '<h4 class="mega-menu-product-title">' . esc_html(get_the_title()) . '</h4>';
        
        if ($product->get_price_html()) {
            $output .= '<div class="mega-menu-product-price">' . $product->get_price_html() . '</div>';
        }
        
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    wp_reset_postdata();
    
    return $output;
}
add_shortcode('mega_menu_products', 'aqualuxe_mega_menu_products_shortcode');

/**
 * Add mega menu categories shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_mega_menu_categories_shortcode($atts) {
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    $atts = shortcode_atts(array(
        'limit' => '4',
        'columns' => '4',
        'orderby' => 'name',
        'order' => 'asc',
        'parent' => '',
        'hide_empty' => 'true',
        'class' => '',
    ), $atts);
    
    $args = array(
        'taxonomy' => 'product_cat',
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'hide_empty' => $atts['hide_empty'] === 'true',
        'number' => absint($atts['limit']),
    );
    
    if ($atts['parent'] !== '') {
        $args['parent'] = absint($atts['parent']);
    }
    
    $categories = get_terms($args);
    
    if (empty($categories) || is_wp_error($categories)) {
        return '';
    }
    
    $output = '<div class="mega-menu-categories mega-menu-categories-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    
    foreach ($categories as $category) {
        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
        $image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'thumbnail') : wc_placeholder_img_src('thumbnail');
        
        $output .= '<div class="mega-menu-category">';
        $output .= '<a href="' . esc_url(get_term_link($category)) . '" class="mega-menu-category-link">';
        
        $output .= '<div class="mega-menu-category-thumbnail">';
        $output .= '<img src="' . esc_url($image) . '" alt="' . esc_attr($category->name) . '" />';
        $output .= '</div>';
        
        $output .= '<h4 class="mega-menu-category-title">' . esc_html($category->name) . '</h4>';
        
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('mega_menu_categories', 'aqualuxe_mega_menu_categories_shortcode');

/**
 * Add mega menu posts shortcode
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_mega_menu_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => '4',
        'columns' => '4',
        'orderby' => 'date',
        'order' => 'desc',
        'category' => '',
        'tag' => '',
        'class' => '',
    ), $atts);
    
    $query_args = array(
        'post_type' => 'post',
        'posts_per_page' => absint($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'no_found_rows' => true,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
    );
    
    if ($atts['category']) {
        $query_args['category_name'] = $atts['category'];
    }
    
    if ($atts['tag']) {
        $query_args['tag'] = $atts['tag'];
    }
    
    $posts = new WP_Query($query_args);
    
    if (!$posts->have_posts()) {
        return '';
    }
    
    $output = '<div class="mega-menu-posts mega-menu-posts-columns-' . esc_attr($atts['columns']) . ' ' . esc_attr($atts['class']) . '">';
    
    while ($posts->have_posts()) {
        $posts->the_post();
        
        $output .= '<div class="mega-menu-post">';
        $output .= '<a href="' . esc_url(get_permalink()) . '" class="mega-menu-post-link">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="mega-menu-post-thumbnail">';
            $output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail');
            $output .= '</div>';
        }
        
        $output .= '<h4 class="mega-menu-post-title">' . esc_html(get_the_title()) . '</h4>';
        
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    wp_reset_postdata();
    
    return $output;
}
add_shortcode('mega_menu_posts', 'aqualuxe_mega_menu_posts_shortcode');

/**
 * Add mega menu banner shortcode
 *
 * @param array $atts Shortcode attributes.
 * @param string $content Shortcode content.
 * @return string
 */
function aqualuxe_mega_menu_banner_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'image' => '',
        'url' => '',
        'class' => '',
    ), $atts);
    
    $output = '<div class="mega-menu-banner ' . esc_attr($atts['class']) . '">';
    
    if ($atts['url']) {
        $output .= '<a href="' . esc_url($atts['url']) . '">';
    }
    
    if ($atts['image']) {
        $output .= '<div class="mega-menu-banner-image">';
        $output .= '<img src="' . esc_url($atts['image']) . '" alt="" />';
        $output .= '</div>';
    }
    
    if ($content) {
        $output .= '<div class="mega-menu-banner-content">';
        $output .= do_shortcode($content);
        $output .= '</div>';
    }
    
    if ($atts['url']) {
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('mega_menu_banner', 'aqualuxe_mega_menu_banner_shortcode');