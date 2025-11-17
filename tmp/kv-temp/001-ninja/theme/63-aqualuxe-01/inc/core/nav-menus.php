<?php
/**
 * Custom navigation menu functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add custom fields to menu item edit screen
 *
 * @param int $item_id Menu item ID.
 * @param object $item Menu item data object.
 * @param int $depth Depth of menu item.
 * @param array $args Menu item args.
 * @param int $id Nav menu ID.
 */
function aqualuxe_custom_menu_item_fields($item_id, $item, $depth, $args, $id) {
    // Mega Menu
    $mega_menu = get_post_meta($item_id, '_aqualuxe_mega_menu', true);
    ?>
    <p class="field-mega-menu description description-wide">
        <label for="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>">
            <input type="checkbox" id="edit-menu-item-mega-menu-<?php echo esc_attr($item_id); ?>" name="menu-item-mega-menu[<?php echo esc_attr($item_id); ?>]" value="1" <?php checked($mega_menu, 1); ?> />
            <?php esc_html_e('Enable Mega Menu', 'aqualuxe'); ?>
        </label>
    </p>
    <?php

    // Icon
    $icon = get_post_meta($item_id, '_aqualuxe_menu_icon', true);
    ?>
    <p class="field-menu-icon description description-wide">
        <label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Icon Class (Font Awesome)', 'aqualuxe'); ?><br />
            <input type="text" id="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>" class="widefat" name="menu-item-icon[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($icon); ?>" />
            <span class="description"><?php esc_html_e('Example: fas fa-home', 'aqualuxe'); ?></span>
        </label>
    </p>
    <?php

    // Badge
    $badge_text = get_post_meta($item_id, '_aqualuxe_menu_badge_text', true);
    $badge_color = get_post_meta($item_id, '_aqualuxe_menu_badge_color', true);
    ?>
    <p class="field-menu-badge-text description description-wide">
        <label for="edit-menu-item-badge-text-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Badge Text', 'aqualuxe'); ?><br />
            <input type="text" id="edit-menu-item-badge-text-<?php echo esc_attr($item_id); ?>" class="widefat" name="menu-item-badge-text[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($badge_text); ?>" />
            <span class="description"><?php esc_html_e('Example: New, Hot, Sale', 'aqualuxe'); ?></span>
        </label>
    </p>
    <p class="field-menu-badge-color description description-wide">
        <label for="edit-menu-item-badge-color-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Badge Color', 'aqualuxe'); ?><br />
            <select id="edit-menu-item-badge-color-<?php echo esc_attr($item_id); ?>" name="menu-item-badge-color[<?php echo esc_attr($item_id); ?>]">
                <option value="primary" <?php selected($badge_color, 'primary'); ?>><?php esc_html_e('Primary', 'aqualuxe'); ?></option>
                <option value="secondary" <?php selected($badge_color, 'secondary'); ?>><?php esc_html_e('Secondary', 'aqualuxe'); ?></option>
                <option value="success" <?php selected($badge_color, 'success'); ?>><?php esc_html_e('Success', 'aqualuxe'); ?></option>
                <option value="danger" <?php selected($badge_color, 'danger'); ?>><?php esc_html_e('Danger', 'aqualuxe'); ?></option>
                <option value="warning" <?php selected($badge_color, 'warning'); ?>><?php esc_html_e('Warning', 'aqualuxe'); ?></option>
                <option value="info" <?php selected($badge_color, 'info'); ?>><?php esc_html_e('Info', 'aqualuxe'); ?></option>
            </select>
        </label>
    </p>
    <?php

    // Image
    $image_id = get_post_meta($item_id, '_aqualuxe_menu_image', true);
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <div class="field-menu-image description description-wide">
        <label for="edit-menu-item-image-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Menu Image', 'aqualuxe'); ?><br />
            <div class="menu-item-image-preview">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="" style="max-width: 100%; height: auto; margin-bottom: 10px;" />
                <?php endif; ?>
            </div>
            <input type="hidden" id="edit-menu-item-image-<?php echo esc_attr($item_id); ?>" name="menu-item-image[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($image_id); ?>" />
            <button type="button" class="button upload-menu-item-image" data-item-id="<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Upload Image', 'aqualuxe'); ?>
            </button>
            <button type="button" class="button remove-menu-item-image" data-item-id="<?php echo esc_attr($item_id); ?>" <?php echo $image_id ? '' : 'style="display:none;"'; ?>>
                <?php esc_html_e('Remove Image', 'aqualuxe'); ?>
            </button>
        </label>
    </div>
    <?php

    // Custom Content
    $custom_content = get_post_meta($item_id, '_aqualuxe_menu_custom_content', true);
    ?>
    <div class="field-menu-custom-content description description-wide">
        <label for="edit-menu-item-custom-content-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Custom Content (HTML allowed)', 'aqualuxe'); ?><br />
            <textarea id="edit-menu-item-custom-content-<?php echo esc_attr($item_id); ?>" class="widefat" name="menu-item-custom-content[<?php echo esc_attr($item_id); ?>]" rows="5"><?php echo esc_textarea($custom_content); ?></textarea>
            <span class="description"><?php esc_html_e('Add custom content to mega menu dropdown', 'aqualuxe'); ?></span>
        </label>
    </div>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'aqualuxe_custom_menu_item_fields', 10, 5);

/**
 * Save custom menu item fields
 *
 * @param int $menu_id Menu ID.
 * @param int $menu_item_db_id Menu item ID.
 */
function aqualuxe_save_custom_menu_item_fields($menu_id, $menu_item_db_id) {
    // Mega Menu
    if (isset($_POST['menu-item-mega-menu'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_mega_menu', 1);
    } else {
        delete_post_meta($menu_item_db_id, '_aqualuxe_mega_menu');
    }

    // Icon
    if (isset($_POST['menu-item-icon'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_icon', sanitize_text_field($_POST['menu-item-icon'][$menu_item_db_id]));
    }

    // Badge Text
    if (isset($_POST['menu-item-badge-text'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_badge_text', sanitize_text_field($_POST['menu-item-badge-text'][$menu_item_db_id]));
    }

    // Badge Color
    if (isset($_POST['menu-item-badge-color'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_badge_color', sanitize_text_field($_POST['menu-item-badge-color'][$menu_item_db_id]));
    }

    // Image
    if (isset($_POST['menu-item-image'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_image', absint($_POST['menu-item-image'][$menu_item_db_id]));
    }

    // Custom Content
    if (isset($_POST['menu-item-custom-content'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, '_aqualuxe_menu_custom_content', wp_kses_post($_POST['menu-item-custom-content'][$menu_item_db_id]));
    }
}
add_action('wp_update_nav_menu_item', 'aqualuxe_save_custom_menu_item_fields', 10, 2);

/**
 * Add custom menu item classes
 *
 * @param array $classes Menu item classes.
 * @param object $item Menu item data object.
 * @param object $args Menu item args.
 * @return array
 */
function aqualuxe_custom_menu_item_classes($classes, $item, $args) {
    // Mega Menu
    $mega_menu = get_post_meta($item->ID, '_aqualuxe_mega_menu', true);
    
    if ($mega_menu) {
        $classes[] = 'mega-menu-item';
    }

    // Badge
    $badge_text = get_post_meta($item->ID, '_aqualuxe_menu_badge_text', true);
    
    if ($badge_text) {
        $classes[] = 'menu-item-has-badge';
    }

    // Icon
    $icon = get_post_meta($item->ID, '_aqualuxe_menu_icon', true);
    
    if ($icon) {
        $classes[] = 'menu-item-has-icon';
    }

    // Image
    $image = get_post_meta($item->ID, '_aqualuxe_menu_image', true);
    
    if ($image) {
        $classes[] = 'menu-item-has-image';
    }

    // Custom Content
    $custom_content = get_post_meta($item->ID, '_aqualuxe_menu_custom_content', true);
    
    if ($custom_content) {
        $classes[] = 'menu-item-has-custom-content';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_custom_menu_item_classes', 10, 3);

/**
 * Add custom menu item attributes
 *
 * @param array $atts Menu item attributes.
 * @param object $item Menu item data object.
 * @param object $args Menu item args.
 * @return array
 */
function aqualuxe_custom_menu_item_attributes($atts, $item, $args) {
    // Mega Menu
    $mega_menu = get_post_meta($item->ID, '_aqualuxe_mega_menu', true);
    
    if ($mega_menu) {
        $atts['data-mega-menu'] = 'true';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_custom_menu_item_attributes', 10, 3);

/**
 * Modify menu item title
 *
 * @param string $title Menu item title.
 * @param object $item Menu item data object.
 * @param object $args Menu item args.
 * @param int $depth Depth of menu item.
 * @return string
 */
function aqualuxe_custom_menu_item_title($title, $item, $args, $depth) {
    $output = '';
    
    // Icon
    $icon = get_post_meta($item->ID, '_aqualuxe_menu_icon', true);
    
    if ($icon) {
        $output .= '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i> ';
    }
    
    // Title
    $output .= '<span class="menu-item-text">' . $title . '</span>';
    
    // Badge
    $badge_text = get_post_meta($item->ID, '_aqualuxe_menu_badge_text', true);
    $badge_color = get_post_meta($item->ID, '_aqualuxe_menu_badge_color', true) ?: 'primary';
    
    if ($badge_text) {
        $output .= '<span class="menu-item-badge badge-' . esc_attr($badge_color) . '">' . esc_html($badge_text) . '</span>';
    }
    
    return $output;
}
add_filter('nav_menu_item_title', 'aqualuxe_custom_menu_item_title', 10, 4);

/**
 * Add custom menu item content after link
 *
 * @param string $item_output Menu item output.
 * @param object $item Menu item data object.
 * @param int $depth Depth of menu item.
 * @param object $args Menu item args.
 * @return string
 */
function aqualuxe_custom_menu_item_content($item_output, $item, $depth, $args) {
    // Custom Content
    $custom_content = get_post_meta($item->ID, '_aqualuxe_menu_custom_content', true);
    
    if ($custom_content && $depth === 0) {
        $item_output .= '<div class="menu-item-custom-content">' . wp_kses_post($custom_content) . '</div>';
    }
    
    // Image
    $image_id = get_post_meta($item->ID, '_aqualuxe_menu_image', true);
    
    if ($image_id && $depth === 0) {
        $image = wp_get_attachment_image($image_id, 'medium', false, array('class' => 'menu-item-image'));
        
        if ($image) {
            $item_output .= '<div class="menu-item-image-wrapper">' . $image . '</div>';
        }
    }
    
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'aqualuxe_custom_menu_item_content', 10, 4);

/**
 * Enqueue menu admin scripts
 */
function aqualuxe_menu_admin_scripts() {
    $screen = get_current_screen();
    
    if ($screen && $screen->base === 'nav-menus') {
        wp_enqueue_media();
        
        wp_enqueue_script(
            'aqualuxe-menu-admin',
            get_template_directory_uri() . '/assets/dist/js/menu-admin.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-menu-admin',
            'aqualuxeMenuAdmin',
            array(
                'mediaTitle' => __('Select Menu Item Image', 'aqualuxe'),
                'mediaBtnText' => __('Use this image', 'aqualuxe'),
            )
        );
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_menu_admin_scripts');

/**
 * Custom Walker for Nav Menu
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    /**
     * Starts the element output.
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param WP_Post $item Menu item data object.
     * @param int $depth Depth of menu item.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     * @param int $id Current item ID.
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
        
        // Add mega menu class
        $mega_menu = get_post_meta($item->ID, '_aqualuxe_mega_menu', true);
        
        if ($mega_menu && $depth === 0) {
            $classes[] = 'mega-menu-item';
        }
        
        // Add badge class
        $badge_text = get_post_meta($item->ID, '_aqualuxe_menu_badge_text', true);
        
        if ($badge_text) {
            $classes[] = 'menu-item-has-badge';
        }
        
        // Add icon class
        $icon = get_post_meta($item->ID, '_aqualuxe_menu_icon', true);
        
        if ($icon) {
            $classes[] = 'menu-item-has-icon';
        }
        
        // Add image class
        $image_id = get_post_meta($item->ID, '_aqualuxe_menu_image', true);
        
        if ($image_id) {
            $classes[] = 'menu-item-has-image';
        }
        
        // Add custom content class
        $custom_content = get_post_meta($item->ID, '_aqualuxe_menu_custom_content', true);
        
        if ($custom_content) {
            $classes[] = 'menu-item-has-custom-content';
        }
        
        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);
        
        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item.
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';
        
        // Add mega menu attribute
        if ($mega_menu && $depth === 0) {
            $atts['data-mega-menu'] = 'true';
        }
        
        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel The rel attribute.
         *     @type string $href The href attribute.
         * }
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item.
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
         * @param string $title The menu item's title.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);
        
        // Build menu item
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        
        // Add icon
        if ($icon) {
            $item_output .= '<i class="' . esc_attr($icon) . '" aria-hidden="true"></i> ';
        }
        
        $item_output .= '<span class="menu-item-text">';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</span>';
        
        // Add badge
        if ($badge_text) {
            $badge_color = get_post_meta($item->ID, '_aqualuxe_menu_badge_color', true) ?: 'primary';
            $item_output .= '<span class="menu-item-badge badge-' . esc_attr($badge_color) . '">' . esc_html($badge_text) . '</span>';
        }
        
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        // Add custom content
        if ($custom_content && $depth === 0) {
            $item_output .= '<div class="menu-item-custom-content">' . wp_kses_post($custom_content) . '</div>';
        }
        
        // Add image
        if ($image_id && $depth === 0) {
            $image = wp_get_attachment_image($image_id, 'medium', false, array('class' => 'menu-item-image'));
            
            if ($image) {
                $item_output .= '<div class="menu-item-image-wrapper">' . $image . '</div>';
            }
        }
        
        /**
         * Filters a menu item's starting output.
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * Starts the list before the elements are added.
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param int $depth Depth of menu item.
     * @param stdClass $args An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        
        $indent = str_repeat($t, $depth);
        
        // Default class
        $classes = array('sub-menu');
        
        // Add mega menu class for first level
        if ($depth === 0) {
            $classes[] = 'mega-menu-wrapper';
        }
        
        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item.
         */
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }
}

/**
 * Use custom walker for nav menu
 *
 * @param array $args Menu arguments.
 * @return array
 */
function aqualuxe_custom_nav_menu_args($args) {
    if (empty($args['walker'])) {
        $args['walker'] = new AquaLuxe_Walker_Nav_Menu();
    }
    
    return $args;
}
add_filter('wp_nav_menu_args', 'aqualuxe_custom_nav_menu_args');

/**
 * Add menu locations
 */
function aqualuxe_register_menus() {
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'top-bar' => esc_html__('Top Bar Menu', 'aqualuxe'),
            'account' => esc_html__('Account Menu', 'aqualuxe'),
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_register_menus');