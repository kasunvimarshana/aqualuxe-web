<?php
/**
 * Multilingual support for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if a multilingual plugin is active
 */
function aqualuxe_is_multilingual() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return 'wpml';
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return 'polylang';
    }
    
    return false;
}

/**
 * Get current language
 */
function aqualuxe_get_current_language() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        return apply_filters('wpml_current_language', null);
    } elseif ($multilingual === 'polylang') {
        return pll_current_language();
    }
    
    return get_locale();
}

/**
 * Get default language
 */
function aqualuxe_get_default_language() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        return apply_filters('wpml_default_language', null);
    } elseif ($multilingual === 'polylang') {
        return pll_default_language();
    }
    
    return get_locale();
}

/**
 * Get available languages
 */
function aqualuxe_get_languages() {
    $multilingual = aqualuxe_is_multilingual();
    $languages = array();
    
    if ($multilingual === 'wpml') {
        $wpml_languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        
        if (!empty($wpml_languages)) {
            foreach ($wpml_languages as $lang) {
                $languages[$lang['language_code']] = array(
                    'code' => $lang['language_code'],
                    'name' => $lang['native_name'],
                    'url' => $lang['url'],
                    'flag' => $lang['country_flag_url'],
                    'active' => $lang['active'],
                );
            }
        }
    } elseif ($multilingual === 'polylang') {
        $pll_languages = pll_languages_list(array('fields' => 'all'));
        
        if (!empty($pll_languages)) {
            foreach ($pll_languages as $lang) {
                $languages[$lang->slug] = array(
                    'code' => $lang->slug,
                    'name' => $lang->name,
                    'url' => $lang->home_url,
                    'flag' => $lang->flag_url,
                    'active' => $lang->slug === pll_current_language(),
                );
            }
        }
    } else {
        $languages[get_locale()] = array(
            'code' => get_locale(),
            'name' => get_locale(),
            'url' => home_url(),
            'flag' => '',
            'active' => true,
        );
    }
    
    return $languages;
}

/**
 * Get translation of a post
 */
function aqualuxe_get_post_translation($post_id, $language) {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        return apply_filters('wpml_object_id', $post_id, get_post_type($post_id), true, $language);
    } elseif ($multilingual === 'polylang') {
        return pll_get_post($post_id, $language);
    }
    
    return $post_id;
}

/**
 * Get translation of a term
 */
function aqualuxe_get_term_translation($term_id, $taxonomy, $language) {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        return apply_filters('wpml_object_id', $term_id, $taxonomy, true, $language);
    } elseif ($multilingual === 'polylang') {
        return pll_get_term($term_id, $language);
    }
    
    return $term_id;
}

/**
 * Register strings for translation
 */
function aqualuxe_register_strings() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        // Register theme mods
        $theme_mods = array(
            'aqualuxe_header_phone' => __('Header Phone Number', 'aqualuxe'),
            'aqualuxe_header_email' => __('Header Email', 'aqualuxe'),
            'aqualuxe_footer_copyright' => __('Footer Copyright Text', 'aqualuxe'),
            'aqualuxe_footer_address' => __('Footer Address', 'aqualuxe'),
            'aqualuxe_footer_phone' => __('Footer Phone Number', 'aqualuxe'),
            'aqualuxe_footer_email' => __('Footer Email', 'aqualuxe'),
        );
        
        foreach ($theme_mods as $mod => $name) {
            $value = get_theme_mod($mod);
            
            if ($value) {
                do_action('wpml_register_single_string', 'AquaLuxe', $name, $value);
            }
        }
    } elseif ($multilingual === 'polylang') {
        // Register theme mods
        $theme_mods = array(
            'aqualuxe_header_phone' => __('Header Phone Number', 'aqualuxe'),
            'aqualuxe_header_email' => __('Header Email', 'aqualuxe'),
            'aqualuxe_footer_copyright' => __('Footer Copyright Text', 'aqualuxe'),
            'aqualuxe_footer_address' => __('Footer Address', 'aqualuxe'),
            'aqualuxe_footer_phone' => __('Footer Phone Number', 'aqualuxe'),
            'aqualuxe_footer_email' => __('Footer Email', 'aqualuxe'),
        );
        
        foreach ($theme_mods as $mod => $name) {
            $value = get_theme_mod($mod);
            
            if ($value && function_exists('pll_register_string')) {
                pll_register_string($name, $value, 'AquaLuxe');
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_register_strings');

/**
 * Translate a string
 */
function aqualuxe_translate_string($string, $name, $domain = 'AquaLuxe') {
    $multilingual = aqualuxe_is_multilingual();
    
    if ($multilingual === 'wpml') {
        return apply_filters('wpml_translate_single_string', $string, $domain, $name);
    } elseif ($multilingual === 'polylang' && function_exists('pll__')) {
        return pll__($string);
    }
    
    return $string;
}

/**
 * Translate a theme mod
 */
function aqualuxe_translate_theme_mod($mod, $name) {
    $value = get_theme_mod($mod);
    
    if ($value) {
        return aqualuxe_translate_string($value, $name);
    }
    
    return $value;
}

/**
 * Add language switcher to the header
 */
function aqualuxe_language_switcher_html() {
    // Check if multilingual support is enabled in the customizer
    if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
        return;
    }
    
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return;
    }
    
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    
    // Output language switcher
    ?>
    <div class="language-switcher">
        <div class="language-switcher-current">
            <?php foreach ($languages as $lang) : ?>
                <?php if ($lang['active']) : ?>
                    <?php if ($lang['flag']) : ?>
                        <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['code']); ?>" width="18" height="12" />
                    <?php endif; ?>
                    <span><?php echo esc_html($lang['name']); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <ul class="language-switcher-dropdown">
            <?php foreach ($languages as $lang) : ?>
                <li class="<?php echo $lang['active'] ? 'active' : ''; ?>">
                    <a href="<?php echo esc_url($lang['url']); ?>">
                        <?php if ($lang['flag']) : ?>
                            <img src="<?php echo esc_url($lang['flag']); ?>" alt="<?php echo esc_attr($lang['code']); ?>" width="18" height="12" />
                        <?php endif; ?>
                        <span><?php echo esc_html($lang['name']); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}
add_action('aqualuxe_header', 'aqualuxe_language_switcher_html', 30);

/**
 * Add multilingual support to the customizer
 */
function aqualuxe_multilingual_customizer($wp_customize) {
    // Add multilingual section
    $wp_customize->add_section('aqualuxe_multilingual', array(
        'title' => __('Multilingual', 'aqualuxe'),
        'priority' => 40,
    ));
    
    // Add multilingual enable/disable setting
    $wp_customize->add_setting('aqualuxe_enable_multilingual', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    // Add multilingual enable/disable control
    $wp_customize->add_control('aqualuxe_enable_multilingual', array(
        'label' => __('Enable Language Switcher', 'aqualuxe'),
        'description' => __('Enable or disable the language switcher in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'checkbox',
    ));
    
    // Add multilingual dropdown style setting
    $wp_customize->add_setting('aqualuxe_multilingual_style', array(
        'default' => 'dropdown',
        'sanitize_callback' => 'aqualuxe_sanitize_multilingual_style',
    ));
    
    // Add multilingual dropdown style control
    $wp_customize->add_control('aqualuxe_multilingual_style', array(
        'label' => __('Language Switcher Style', 'aqualuxe'),
        'description' => __('Select the style for the language switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => array(
            'dropdown' => __('Dropdown', 'aqualuxe'),
            'horizontal' => __('Horizontal', 'aqualuxe'),
            'flags' => __('Flags Only', 'aqualuxe'),
        ),
    ));
    
    // Add multilingual show flags setting
    $wp_customize->add_setting('aqualuxe_multilingual_show_flags', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    // Add multilingual show flags control
    $wp_customize->add_control('aqualuxe_multilingual_show_flags', array(
        'label' => __('Show Flags', 'aqualuxe'),
        'description' => __('Show or hide flags in the language switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'checkbox',
    ));
    
    // Add multilingual show names setting
    $wp_customize->add_setting('aqualuxe_multilingual_show_names', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    // Add multilingual show names control
    $wp_customize->add_control('aqualuxe_multilingual_show_names', array(
        'label' => __('Show Language Names', 'aqualuxe'),
        'description' => __('Show or hide language names in the language switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'checkbox',
    ));
}
add_action('customize_register', 'aqualuxe_multilingual_customizer');

/**
 * Add RTL support
 */
function aqualuxe_rtl_support() {
    // Check if current language is RTL
    $is_rtl = is_rtl();
    
    if ($is_rtl) {
        // Add RTL class to body
        add_filter('body_class', function($classes) {
            $classes[] = 'rtl';
            return $classes;
        });
        
        // Enqueue RTL stylesheet
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('aqualuxe-rtl', AQUALUXE_URI . '/assets/css/rtl.css', array('aqualuxe-style'), AQUALUXE_VERSION);
        }, 20);
    }
}
add_action('after_setup_theme', 'aqualuxe_rtl_support');

/**
 * Filter menu items by language
 */
function aqualuxe_filter_menu_items($items, $menu, $args) {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return $items;
    }
    
    $current_language = aqualuxe_get_current_language();
    $filtered_items = array();
    
    foreach ($items as $item) {
        // Check if item has language meta
        $item_language = get_post_meta($item->ID, '_menu_item_language', true);
        
        // If no language is set or language matches current language, include the item
        if (!$item_language || $item_language === $current_language) {
            $filtered_items[] = $item;
        }
    }
    
    return $filtered_items;
}
add_filter('wp_get_nav_menu_items', 'aqualuxe_filter_menu_items', 10, 3);

/**
 * Add language meta field to menu items
 */
function aqualuxe_add_menu_item_language_meta_box() {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return;
    }
    
    add_meta_box(
        'aqualuxe_menu_item_language',
        __('Language', 'aqualuxe'),
        'aqualuxe_menu_item_language_meta_box_callback',
        'nav-menus',
        'side',
        'default'
    );
}
add_action('admin_init', 'aqualuxe_add_menu_item_language_meta_box');

/**
 * Menu item language meta box callback
 */
function aqualuxe_menu_item_language_meta_box_callback() {
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return;
    }
    
    ?>
    <div id="aqualuxe-menu-item-language-wrap">
        <p><?php esc_html_e('Select the language for this menu item:', 'aqualuxe'); ?></p>
        <select name="aqualuxe-menu-item-language" id="aqualuxe-menu-item-language">
            <option value=""><?php esc_html_e('All Languages', 'aqualuxe'); ?></option>
            <?php foreach ($languages as $lang) : ?>
                <option value="<?php echo esc_attr($lang['code']); ?>"><?php echo esc_html($lang['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <p class="description"><?php esc_html_e('This menu item will only be displayed for the selected language.', 'aqualuxe'); ?></p>
        <script>
            jQuery(document).ready(function($) {
                $('#aqualuxe-menu-item-language-wrap').appendTo('#menu-item-language-wrap');
            });
        </script>
    </div>
    <?php
}

/**
 * Save menu item language
 */
function aqualuxe_save_menu_item_language($menu_id, $menu_item_db_id) {
    if (isset($_POST['aqualuxe-menu-item-language'])) {
        $language = sanitize_text_field($_POST['aqualuxe-menu-item-language']);
        update_post_meta($menu_item_db_id, '_menu_item_language', $language);
    }
}
add_action('wp_update_nav_menu_item', 'aqualuxe_save_menu_item_language', 10, 2);

/**
 * Add language field to menu item edit screen
 */
function aqualuxe_menu_item_language_field($item_id, $item) {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return;
    }
    
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return;
    }
    
    $language = get_post_meta($item_id, '_menu_item_language', true);
    
    ?>
    <p class="field-language description description-wide">
        <label for="edit-menu-item-language-<?php echo esc_attr($item_id); ?>">
            <?php esc_html_e('Language', 'aqualuxe'); ?><br />
            <select id="edit-menu-item-language-<?php echo esc_attr($item_id); ?>" name="menu-item-language[<?php echo esc_attr($item_id); ?>]">
                <option value=""><?php esc_html_e('All Languages', 'aqualuxe'); ?></option>
                <?php foreach ($languages as $lang) : ?>
                    <option value="<?php echo esc_attr($lang['code']); ?>" <?php selected($language, $lang['code']); ?>><?php echo esc_html($lang['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'aqualuxe_menu_item_language_field', 10, 2);

/**
 * Save menu item language from edit screen
 */
function aqualuxe_save_menu_item_language_from_edit_screen($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-language'][$menu_item_db_id])) {
        $language = sanitize_text_field($_POST['menu-item-language'][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, '_menu_item_language', $language);
    }
}
add_action('wp_update_nav_menu_item', 'aqualuxe_save_menu_item_language_from_edit_screen', 10, 2);

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return;
    }
    
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return;
    }
    
    foreach ($languages as $lang) {
        echo '<link rel="alternate" hreflang="' . esc_attr($lang['code']) . '" href="' . esc_url($lang['url']) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_links');

/**
 * Filter widgets by language
 */
function aqualuxe_filter_widgets($instance) {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return $instance;
    }
    
    // Check if widget has language setting
    if (isset($instance['aqualuxe_widget_language'])) {
        $widget_language = $instance['aqualuxe_widget_language'];
        $current_language = aqualuxe_get_current_language();
        
        // If widget language is set and doesn't match current language, hide widget
        if ($widget_language && $widget_language !== $current_language) {
            return false;
        }
    }
    
    return $instance;
}
add_filter('widget_display_callback', 'aqualuxe_filter_widgets', 10, 1);

/**
 * Add language field to widget form
 */
function aqualuxe_widget_language_field($widget, $return, $instance) {
    $multilingual = aqualuxe_is_multilingual();
    
    if (!$multilingual) {
        return $return;
    }
    
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return $return;
    }
    
    $language = isset($instance['aqualuxe_widget_language']) ? $instance['aqualuxe_widget_language'] : '';
    
    ?>
    <p>
        <label for="<?php echo esc_attr($widget->get_field_id('aqualuxe_widget_language')); ?>"><?php esc_html_e('Language:', 'aqualuxe'); ?></label>
        <select id="<?php echo esc_attr($widget->get_field_id('aqualuxe_widget_language')); ?>" name="<?php echo esc_attr($widget->get_field_name('aqualuxe_widget_language')); ?>">
            <option value=""><?php esc_html_e('All Languages', 'aqualuxe'); ?></option>
            <?php foreach ($languages as $lang) : ?>
                <option value="<?php echo esc_attr($lang['code']); ?>" <?php selected($language, $lang['code']); ?>><?php echo esc_html($lang['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <br />
        <small><?php esc_html_e('This widget will only be displayed for the selected language.', 'aqualuxe'); ?></small>
    </p>
    <?php
    
    return $return;
}
add_filter('in_widget_form', 'aqualuxe_widget_language_field', 10, 3);

/**
 * Save widget language
 */
function aqualuxe_save_widget_language($instance, $new_instance) {
    $instance['aqualuxe_widget_language'] = !empty($new_instance['aqualuxe_widget_language']) ? sanitize_text_field($new_instance['aqualuxe_widget_language']) : '';
    
    return $instance;
}
add_filter('widget_update_callback', 'aqualuxe_save_widget_language', 10, 2);