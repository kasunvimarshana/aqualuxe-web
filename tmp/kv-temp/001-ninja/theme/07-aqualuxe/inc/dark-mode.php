<?php
/**
 * Dark Mode Support Functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Initialize dark mode support
 */
function aqualuxe_dark_mode_init() {
    // Check if dark mode is enabled
    if (!aqualuxe_is_dark_mode_enabled()) {
        return;
    }

    // Add dark mode toggle to the appropriate location
    $position = aqualuxe_get_option('aqualuxe_dark_mode_toggle_position', 'header');

    switch ($position) {
        case 'header':
            add_action('aqualuxe_header_icons', 'aqualuxe_dark_mode_toggle', 30);
            break;
        case 'fixed':
            add_action('wp_footer', 'aqualuxe_dark_mode_fixed_toggle');
            break;
        case 'menu':
            add_filter('wp_nav_menu_items', 'aqualuxe_add_dark_mode_toggle_to_menu', 10, 2);
            break;
        case 'footer':
            add_action('aqualuxe_footer_widgets', 'aqualuxe_dark_mode_toggle', 30);
            break;
    }

    // Add dark mode scripts
    add_action('wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts');

    // Add dark mode body class
    add_filter('body_class', 'aqualuxe_dark_mode_body_class');

    // Add dark mode admin support
    if (aqualuxe_get_option('aqualuxe_dark_mode_admin', false)) {
        add_action('admin_enqueue_scripts', 'aqualuxe_dark_mode_admin_scripts');
    }

    // Add dark mode custom CSS
    add_action('wp_head', 'aqualuxe_dark_mode_custom_css');
}
add_action('after_setup_theme', 'aqualuxe_dark_mode_init');

/**
 * Display dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    // Get toggle style
    $style = aqualuxe_get_option('aqualuxe_dark_mode_toggle_style', 'switch');

    // Get toggle icons
    $light_icon = aqualuxe_get_option('aqualuxe_dark_mode_toggle_icon_light', 'sun');
    $dark_icon = aqualuxe_get_option('aqualuxe_dark_mode_toggle_icon_dark', 'moon');

    // Get toggle text
    $light_text = aqualuxe_get_option('aqualuxe_dark_mode_toggle_text_light', esc_html__('Light', 'aqualuxe'));
    $dark_text = aqualuxe_get_option('aqualuxe_dark_mode_toggle_text_dark', esc_html__('Dark', 'aqualuxe'));

    // Start output
    $output = '<div class="dark-mode-toggle-wrapper">';

    switch ($style) {
        case 'switch':
            $output .= aqualuxe_dark_mode_toggle_switch();
            break;
        case 'icon':
            $output .= aqualuxe_dark_mode_toggle_icon($light_icon, $dark_icon);
            break;
        case 'button':
            $output .= aqualuxe_dark_mode_toggle_button($light_icon, $dark_icon, $light_text, $dark_text);
            break;
    }

    $output .= '</div>';

    echo $output;
}

/**
 * Generate switch dark mode toggle
 *
 * @return string
 */
function aqualuxe_dark_mode_toggle_switch() {
    $output = '<button type="button" id="dark-mode-toggle" class="dark-mode-toggle" role="switch" aria-checked="false">';
    $output .= '<span class="sr-only">' . esc_html__('Toggle dark mode', 'aqualuxe') . '</span>';
    $output .= '<span class="dark-mode-toggle-knob"></span>';
    $output .= '</button>';

    return $output;
}

/**
 * Generate icon dark mode toggle
 *
 * @param string $light_icon Light mode icon.
 * @param string $dark_icon  Dark mode icon.
 * @return string
 */
function aqualuxe_dark_mode_toggle_icon($light_icon, $dark_icon) {
    $output = '<button type="button" id="dark-mode-toggle-icon" class="dark-mode-toggle-icon" aria-label="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '">';
    
    // Light mode icon
    $output .= '<span class="dark-mode-toggle-icon-light">';
    $output .= aqualuxe_get_icon_svg($light_icon);
    $output .= '</span>';
    
    // Dark mode icon
    $output .= '<span class="dark-mode-toggle-icon-dark">';
    $output .= aqualuxe_get_icon_svg($dark_icon);
    $output .= '</span>';
    
    $output .= '</button>';

    return $output;
}

/**
 * Generate button dark mode toggle
 *
 * @param string $light_icon  Light mode icon.
 * @param string $dark_icon   Dark mode icon.
 * @param string $light_text  Light mode text.
 * @param string $dark_text   Dark mode text.
 * @return string
 */
function aqualuxe_dark_mode_toggle_button($light_icon, $dark_icon, $light_text, $dark_text) {
    $output = '<button type="button" id="dark-mode-toggle-button" class="dark-mode-toggle-button" aria-label="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '">';
    
    // Light mode
    $output .= '<span class="dark-mode-toggle-button-light">';
    $output .= aqualuxe_get_icon_svg($light_icon);
    $output .= '<span class="dark-mode-toggle-button-text">' . esc_html($light_text) . '</span>';
    $output .= '</span>';
    
    // Dark mode
    $output .= '<span class="dark-mode-toggle-button-dark">';
    $output .= aqualuxe_get_icon_svg($dark_icon);
    $output .= '<span class="dark-mode-toggle-button-text">' . esc_html($dark_text) . '</span>';
    $output .= '</span>';
    
    $output .= '</button>';

    return $output;
}

/**
 * Display fixed dark mode toggle
 */
function aqualuxe_dark_mode_fixed_toggle() {
    echo '<div class="dark-mode-fixed-toggle">';
    aqualuxe_dark_mode_toggle();
    echo '</div>';
}

/**
 * Add dark mode toggle to menu
 *
 * @param string $items Menu items.
 * @param object $args  Menu arguments.
 * @return string
 */
function aqualuxe_add_dark_mode_toggle_to_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        ob_start();
        aqualuxe_dark_mode_toggle();
        $toggle = ob_get_clean();
        
        $items .= '<li class="menu-item menu-item-dark-mode">' . $toggle . '</li>';
    }
    
    return $items;
}

/**
 * Enqueue dark mode scripts
 */
function aqualuxe_dark_mode_scripts() {
    // Enqueue dark mode script
    wp_enqueue_script('aqualuxe-dark-mode', AQUALUXE_URI . '/assets/js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true);

    // Pass dark mode options to script
    wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'default' => aqualuxe_get_option('aqualuxe_dark_mode_default', 'system'),
        'remember' => aqualuxe_get_option('aqualuxe_dark_mode_remember_preference', true),
        'transition' => aqualuxe_get_option('aqualuxe_dark_mode_transition', true),
        'transitionDuration' => aqualuxe_get_option('aqualuxe_dark_mode_transition_duration', 300),
    ));

    // Add inline CSS for dark mode
    $css = '';

    // Add transition if enabled
    if (aqualuxe_get_option('aqualuxe_dark_mode_transition', true)) {
        $duration = aqualuxe_get_option('aqualuxe_dark_mode_transition_duration', 300);
        $css .= 'html.transition, html.transition *, html.transition *:before, html.transition *:after { ';
        $css .= 'transition: all ' . esc_attr($duration) . 'ms !important; ';
        $css .= 'transition-delay: 0 !important; }';
    }

    // Add image adjustments if enabled
    if (aqualuxe_get_option('aqualuxe_dark_mode_image_adjustments', true)) {
        $filter = aqualuxe_get_option('aqualuxe_dark_mode_image_filter', 'brightness(0.8) contrast(1.2)');
        $css .= 'html.dark img:not(.no-dark-filter), html.dark video:not(.no-dark-filter) { ';
        $css .= 'filter: ' . esc_attr($filter) . '; }';
    }

    // Add dark mode colors
    $css .= ':root { ';
    $css .= '--dark-background: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_background_color', '#111827')) . '; ';
    $css .= '--dark-text: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_text_color', '#f9fafb')) . '; ';
    $css .= '--dark-heading: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_heading_color', '#ffffff')) . '; ';
    $css .= '--dark-link: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_link_color', '#38bdf8')) . '; ';
    $css .= '--dark-link-hover: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_link_hover_color', '#7dd3fc')) . '; ';
    $css .= '--dark-border: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_border_color', '#374151')) . '; ';
    $css .= '--dark-input-bg: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_input_background_color', '#1f2937')) . '; ';
    $css .= '--dark-input-text: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_input_text_color', '#f9fafb')) . '; ';
    $css .= '--dark-button-bg: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_button_background_color', '#0ea5e9')) . '; ';
    $css .= '--dark-button-text: ' . esc_attr(aqualuxe_get_option('aqualuxe_dark_mode_button_text_color', '#ffffff')) . '; ';
    $css .= '}';

    wp_add_inline_style('aqualuxe-style', $css);
}

/**
 * Add dark mode body class
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_dark_mode_body_class($classes) {
    if (aqualuxe_is_dark_mode_enabled()) {
        $classes[] = 'dark-mode-enabled';
    }
    
    return $classes;
}

/**
 * Enqueue dark mode admin scripts
 */
function aqualuxe_dark_mode_admin_scripts() {
    // Enqueue dark mode admin script
    wp_enqueue_script('aqualuxe-dark-mode-admin', AQUALUXE_URI . '/assets/js/dark-mode-admin.js', array('jquery'), AQUALUXE_VERSION, true);

    // Pass dark mode options to script
    wp_localize_script('aqualuxe-dark-mode-admin', 'aqualuxeDarkModeAdmin', array(
        'default' => aqualuxe_get_option('aqualuxe_dark_mode_default', 'system'),
        'remember' => aqualuxe_get_option('aqualuxe_dark_mode_remember_preference', true),
    ));

    // Add inline CSS for dark mode admin
    $css = '';

    // Add dark mode admin colors
    $css .= 'body.dark-mode { ';
    $css .= 'background-color: #1f2937; ';
    $css .= 'color: #f9fafb; ';
    $css .= '}';

    $css .= 'body.dark-mode #wpcontent, body.dark-mode #wpbody-content { ';
    $css .= 'background-color: #111827; ';
    $css .= 'color: #f9fafb; ';
    $css .= '}';

    $css .= 'body.dark-mode #adminmenu, body.dark-mode #adminmenuback, body.dark-mode #adminmenuwrap { ';
    $css .= 'background-color: #0f172a; ';
    $css .= '}';

    $css .= 'body.dark-mode #adminmenu a { ';
    $css .= 'color: #d1d5db; ';
    $css .= '}';

    $css .= 'body.dark-mode #adminmenu div.wp-menu-image:before { ';
    $css .= 'color: #9ca3af; ';
    $css .= '}';

    $css .= 'body.dark-mode #adminmenu li.menu-top:hover, body.dark-mode #adminmenu li.opensub > a.menu-top, body.dark-mode #adminmenu li > a.menu-top:focus { ';
    $css .= 'background-color: #1e293b; ';
    $css .= 'color: #ffffff; ';
    $css .= '}';

    $css .= 'body.dark-mode #adminmenu li.current a.menu-top, body.dark-mode #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu { ';
    $css .= 'background-color: #0ea5e9; ';
    $css .= 'color: #ffffff; ';
    $css .= '}';

    $css .= 'body.dark-mode .wp-core-ui .button, body.dark-mode .wp-core-ui .button-secondary { ';
    $css .= 'background-color: #374151; ';
    $css .= 'border-color: #4b5563; ';
    $css .= 'color: #f9fafb; ';
    $css .= '}';

    $css .= 'body.dark-mode .wp-core-ui .button-primary { ';
    $css .= 'background-color: #0ea5e9; ';
    $css .= 'border-color: #0284c7; ';
    $css .= 'color: #ffffff; ';
    $css .= '}';

    $css .= 'body.dark-mode .postbox, body.dark-mode .welcome-panel { ';
    $css .= 'background-color: #1f2937; ';
    $css .= 'border-color: #374151; ';
    $css .= 'color: #f9fafb; ';
    $css .= '}';

    $css .= 'body.dark-mode input[type="text"], body.dark-mode input[type="password"], body.dark-mode input[type="email"], body.dark-mode input[type="number"], body.dark-mode input[type="tel"], body.dark-mode input[type="url"], body.dark-mode textarea, body.dark-mode select { ';
    $css .= 'background-color: #374151; ';
    $css .= 'border-color: #4b5563; ';
    $css .= 'color: #f9fafb; ';
    $css .= '}';

    wp_add_inline_style('admin-menu', $css);
}

/**
 * Add dark mode custom CSS
 */
function aqualuxe_dark_mode_custom_css() {
    $custom_css = aqualuxe_get_option('aqualuxe_dark_mode_custom_css', '');
    
    if (!empty($custom_css)) {
        echo '<style id="aqualuxe-dark-mode-custom-css">';
        echo 'html.dark {';
        echo wp_strip_all_tags($custom_css);
        echo '}';
        echo '</style>';
    }
}

/**
 * Get icon SVG
 *
 * @param string $icon Icon name.
 * @return string
 */
function aqualuxe_get_icon_svg($icon) {
    $svg = '';
    
    switch ($icon) {
        case 'sun':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" /></svg>';
            break;
        case 'brightness':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" /></svg>';
            break;
        case 'light-bulb':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" /></svg>';
            break;
        case 'day':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" /></svg>';
            break;
        case 'moon':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>';
            break;
        case 'night':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>';
            break;
        case 'stars':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>';
            break;
    }
    
    return $svg;
}