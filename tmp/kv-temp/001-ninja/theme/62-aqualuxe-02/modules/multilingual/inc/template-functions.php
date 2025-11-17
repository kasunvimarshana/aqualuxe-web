<?php
/**
 * AquaLuxe Multilingual Module Template Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Multilingual
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Display language switcher
 *
 * @param string $style Switcher style (dropdown, horizontal, vertical)
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_language_switcher( $style = 'dropdown', $args = array() ) {
    // Default arguments
    $defaults = array(
        'location' => 'header',
        'show_flags' => true,
        'show_names' => true,
    );
    
    // Parse arguments
    $args = wp_parse_args( $args, $defaults );
    
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // Check if we have languages
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    // Start output
    $output = '<div class="aqualuxe-language-switcher aqualuxe-language-switcher--' . esc_attr( $style ) . ' aqualuxe-language-switcher--' . esc_attr( $args['location'] ) . '">';
    
    // Dropdown style
    if ( $style === 'dropdown' ) {
        $output .= aqualuxe_language_switcher_dropdown( $languages, $current_language, $args );
    }
    
    // Horizontal style
    elseif ( $style === 'horizontal' ) {
        $output .= aqualuxe_language_switcher_horizontal( $languages, $current_language, $args );
    }
    
    // Vertical style
    elseif ( $style === 'vertical' ) {
        $output .= aqualuxe_language_switcher_vertical( $languages, $current_language, $args );
    }
    
    // End output
    $output .= '</div>';
    
    echo $output;
}

/**
 * Display dropdown language switcher
 *
 * @param array $languages Languages
 * @param string $current_language Current language
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_language_switcher_dropdown( $languages, $current_language, $args ) {
    // Start output
    $output = '<div class="aqualuxe-language-switcher__dropdown">';
    
    // Current language
    $output .= '<div class="aqualuxe-language-switcher__current">';
    
    // Current language flag
    if ( $args['show_flags'] && ! empty( $languages[ $current_language ]['flag'] ) ) {
        $output .= '<img src="' . esc_url( $languages[ $current_language ]['flag'] ) . '" alt="' . esc_attr( $languages[ $current_language ]['name'] ) . '" class="aqualuxe-language-switcher__flag" />';
    }
    
    // Current language name
    if ( $args['show_names'] ) {
        $output .= '<span class="aqualuxe-language-switcher__name">' . esc_html( $languages[ $current_language ]['name'] ) . '</span>';
    }
    
    // Dropdown icon
    $output .= '<span class="aqualuxe-language-switcher__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 16l-6-6h12z"/></svg></span>';
    
    $output .= '</div>';
    
    // Languages list
    $output .= '<ul class="aqualuxe-language-switcher__list">';
    
    foreach ( $languages as $code => $language ) {
        // Skip current language
        if ( $code === $current_language ) {
            continue;
        }
        
        $output .= '<li class="aqualuxe-language-switcher__item">';
        $output .= '<a href="' . esc_url( $language['url'] ) . '" class="aqualuxe-language-switcher__link">';
        
        // Language flag
        if ( $args['show_flags'] && ! empty( $language['flag'] ) ) {
            $output .= '<img src="' . esc_url( $language['flag'] ) . '" alt="' . esc_attr( $language['name'] ) . '" class="aqualuxe-language-switcher__flag" />';
        }
        
        // Language name
        if ( $args['show_names'] ) {
            $output .= '<span class="aqualuxe-language-switcher__name">' . esc_html( $language['name'] ) . '</span>';
        }
        
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Display horizontal language switcher
 *
 * @param array $languages Languages
 * @param string $current_language Current language
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_language_switcher_horizontal( $languages, $current_language, $args ) {
    // Start output
    $output = '<ul class="aqualuxe-language-switcher__list aqualuxe-language-switcher__list--horizontal">';
    
    foreach ( $languages as $code => $language ) {
        $is_current = $code === $current_language;
        $item_class = $is_current ? 'aqualuxe-language-switcher__item aqualuxe-language-switcher__item--current' : 'aqualuxe-language-switcher__item';
        $link_class = $is_current ? 'aqualuxe-language-switcher__link aqualuxe-language-switcher__link--current' : 'aqualuxe-language-switcher__link';
        
        $output .= '<li class="' . esc_attr( $item_class ) . '">';
        
        if ( $is_current ) {
            $output .= '<span class="' . esc_attr( $link_class ) . '">';
        } else {
            $output .= '<a href="' . esc_url( $language['url'] ) . '" class="' . esc_attr( $link_class ) . '">';
        }
        
        // Language flag
        if ( $args['show_flags'] && ! empty( $language['flag'] ) ) {
            $output .= '<img src="' . esc_url( $language['flag'] ) . '" alt="' . esc_attr( $language['name'] ) . '" class="aqualuxe-language-switcher__flag" />';
        }
        
        // Language name
        if ( $args['show_names'] ) {
            $output .= '<span class="aqualuxe-language-switcher__name">' . esc_html( $language['name'] ) . '</span>';
        }
        
        if ( $is_current ) {
            $output .= '</span>';
        } else {
            $output .= '</a>';
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    
    return $output;
}

/**
 * Display vertical language switcher
 *
 * @param array $languages Languages
 * @param string $current_language Current language
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_language_switcher_vertical( $languages, $current_language, $args ) {
    // Start output
    $output = '<ul class="aqualuxe-language-switcher__list aqualuxe-language-switcher__list--vertical">';
    
    foreach ( $languages as $code => $language ) {
        $is_current = $code === $current_language;
        $item_class = $is_current ? 'aqualuxe-language-switcher__item aqualuxe-language-switcher__item--current' : 'aqualuxe-language-switcher__item';
        $link_class = $is_current ? 'aqualuxe-language-switcher__link aqualuxe-language-switcher__link--current' : 'aqualuxe-language-switcher__link';
        
        $output .= '<li class="' . esc_attr( $item_class ) . '">';
        
        if ( $is_current ) {
            $output .= '<span class="' . esc_attr( $link_class ) . '">';
        } else {
            $output .= '<a href="' . esc_url( $language['url'] ) . '" class="' . esc_attr( $link_class ) . '">';
        }
        
        // Language flag
        if ( $args['show_flags'] && ! empty( $language['flag'] ) ) {
            $output .= '<img src="' . esc_url( $language['flag'] ) . '" alt="' . esc_attr( $language['name'] ) . '" class="aqualuxe-language-switcher__flag" />';
        }
        
        // Language name
        if ( $args['show_names'] ) {
            $output .= '<span class="aqualuxe-language-switcher__name">' . esc_html( $language['name'] ) . '</span>';
        }
        
        if ( $is_current ) {
            $output .= '</span>';
        } else {
            $output .= '</a>';
        }
        
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    
    return $output;
}

/**
 * Display language switcher template part
 *
 * @param string $style Switcher style (dropdown, horizontal, vertical)
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_language_switcher_template( $style = 'dropdown', $args = array() ) {
    // Get template part
    aqualuxe_get_template_part( 'template-parts/language-switcher', $style, $args );
}

/**
 * Add language switcher to navigation menu
 *
 * @param string $items Menu items
 * @param object $args Menu arguments
 * @return string
 */
function aqualuxe_add_language_switcher_to_menu( $items, $args ) {
    // Check if module is active
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return $items;
    }
    
    // Check if language switcher should be added to this menu
    $menu_location = isset( $args->theme_location ) ? $args->theme_location : '';
    
    if ( ! $menu_location || ! $module->get_setting( 'show_in_menu_' . $menu_location, false ) ) {
        return $items;
    }
    
    // Get language switcher style
    $style = $module->get_setting( 'switcher_style_menu', 'dropdown' );
    
    // Get language switcher HTML
    ob_start();
    aqualuxe_language_switcher( $style, array(
        'location' => 'menu',
        'show_flags' => $module->get_setting( 'show_flags', true ),
        'show_names' => $module->get_setting( 'show_names', true ),
    ) );
    $switcher = ob_get_clean();
    
    // Add language switcher to menu
    $items .= '<li class="menu-item menu-item-language-switcher">' . $switcher . '</li>';
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_menu', 10, 2 );

/**
 * Add language class to menu items
 *
 * @param array $classes Menu item classes
 * @param object $item Menu item
 * @param object $args Menu arguments
 * @return array
 */
function aqualuxe_add_language_class_to_menu_items( $classes, $item, $args ) {
    // Check if item has language meta
    $item_language = get_post_meta( $item->ID, '_menu_item_language', true );
    
    if ( $item_language ) {
        $classes[] = 'menu-item-language-' . sanitize_html_class( $item_language );
    }
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_add_language_class_to_menu_items', 10, 3 );

/**
 * Add language field to menu item edit screen
 *
 * @param int $item_id Menu item ID
 * @param object $item Menu item
 * @param int $depth Menu item depth
 * @param array $args Menu item arguments
 * @return void
 */
function aqualuxe_add_language_field_to_menu_item( $item_id, $item, $depth, $args ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Get current value
    $item_language = get_post_meta( $item_id, '_menu_item_language', true );
    
    ?>
    <p class="field-language description description-wide">
        <label for="edit-menu-item-language-<?php echo esc_attr( $item_id ); ?>">
            <?php esc_html_e( 'Language', 'aqualuxe' ); ?><br />
            <select name="menu-item-language[<?php echo esc_attr( $item_id ); ?>]" id="edit-menu-item-language-<?php echo esc_attr( $item_id ); ?>">
                <option value=""><?php esc_html_e( 'All Languages', 'aqualuxe' ); ?></option>
                <?php foreach ( $languages as $code => $language ) : ?>
                    <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $item_language, $code ); ?>>
                        <?php echo esc_html( $language['name'] ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'aqualuxe_add_language_field_to_menu_item', 10, 4 );

/**
 * Save language field for menu item
 *
 * @param int $menu_id Menu ID
 * @param int $menu_item_db_id Menu item ID
 * @return void
 */
function aqualuxe_save_language_field_for_menu_item( $menu_id, $menu_item_db_id ) {
    if ( isset( $_POST['menu-item-language'][ $menu_item_db_id ] ) ) {
        $language = sanitize_text_field( $_POST['menu-item-language'][ $menu_item_db_id ] );
        update_post_meta( $menu_item_db_id, '_menu_item_language', $language );
    } else {
        delete_post_meta( $menu_item_db_id, '_menu_item_language' );
    }
}
add_action( 'wp_update_nav_menu_item', 'aqualuxe_save_language_field_for_menu_item', 10, 2 );

/**
 * Add language meta box to posts
 *
 * @return void
 */
function aqualuxe_add_language_meta_box() {
    // Check if module is active
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_current_language' ) ) {
        return;
    }
    
    // Get post types
    $post_types = get_post_types( array( 'public' => true ) );
    
    // Add meta box
    foreach ( $post_types as $post_type ) {
        add_meta_box(
            'aqualuxe-language',
            __( 'Language', 'aqualuxe' ),
            'aqualuxe_language_meta_box_callback',
            $post_type,
            'side',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'aqualuxe_add_language_meta_box' );

/**
 * Language meta box callback
 *
 * @param WP_Post $post Post object
 * @return void
 */
function aqualuxe_language_meta_box_callback( $post ) {
    // Get languages
    $languages = aqualuxe_get_languages();
    
    // Get current value
    $post_language = get_post_meta( $post->ID, '_aqualuxe_language', true );
    
    // If no language is set, use default
    if ( ! $post_language ) {
        $post_language = aqualuxe_get_default_language();
    }
    
    // Nonce field
    wp_nonce_field( 'aqualuxe_language_meta_box', 'aqualuxe_language_meta_box_nonce' );
    
    ?>
    <p>
        <label for="aqualuxe-language"><?php esc_html_e( 'Select language', 'aqualuxe' ); ?></label>
        <select name="aqualuxe_language" id="aqualuxe-language">
            <?php foreach ( $languages as $code => $language ) : ?>
                <option value="<?php echo esc_attr( $code ); ?>" <?php selected( $post_language, $code ); ?>>
                    <?php echo esc_html( $language['name'] ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <?php
}

/**
 * Save language meta box
 *
 * @param int $post_id Post ID
 * @return void
 */
function aqualuxe_save_language_meta_box( $post_id ) {
    // Check if nonce is set
    if ( ! isset( $_POST['aqualuxe_language_meta_box_nonce'] ) ) {
        return;
    }
    
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['aqualuxe_language_meta_box_nonce'], 'aqualuxe_language_meta_box' ) ) {
        return;
    }
    
    // Check if autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save language
    if ( isset( $_POST['aqualuxe_language'] ) ) {
        $language = sanitize_text_field( $_POST['aqualuxe_language'] );
        update_post_meta( $post_id, '_aqualuxe_language', $language );
    }
}
add_action( 'save_post', 'aqualuxe_save_language_meta_box' );

/**
 * Filter posts by language
 *
 * @param WP_Query $query Query object
 * @return void
 */
function aqualuxe_filter_posts_by_language( $query ) {
    // Check if module is active
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'multilingual' );
    
    if ( ! $module ) {
        return;
    }
    
    // Check if WPML or Polylang is active
    if ( defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_current_language' ) ) {
        return;
    }
    
    // Check if filter is enabled
    if ( ! $module->get_setting( 'filter_posts', true ) ) {
        return;
    }
    
    // Check if main query and not admin
    if ( ! $query->is_main_query() || is_admin() ) {
        return;
    }
    
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // Add meta query
    $query->set( 'meta_query', array(
        'relation' => 'OR',
        array(
            'key' => '_aqualuxe_language',
            'value' => $current_language,
            'compare' => '=',
        ),
        array(
            'key' => '_aqualuxe_language',
            'compare' => 'NOT EXISTS',
        ),
    ) );
}
add_action( 'pre_get_posts', 'aqualuxe_filter_posts_by_language' );