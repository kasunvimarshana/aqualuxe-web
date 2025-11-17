<?php
/**
 * Dark mode functionality for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Enqueue dark mode scripts and styles
 */
function aqualuxe_dark_mode_scripts() {
    // Enqueue dark mode script
    wp_enqueue_script('aqualuxe-dark-mode', AQUALUXE_URI . '/assets/js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true);
    
    // Localize script with settings
    wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-dark-mode-nonce'),
        'cookieName' => 'aqualuxe_dark_mode',
        'cookieExpiry' => 365, // Days
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts');

/**
 * Add dark mode toggle to the header
 */
function aqualuxe_dark_mode_toggle_html() {
    // Check if dark mode is enabled in the customizer
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    ?>
    <div class="dark-mode-toggle-wrapper">
        <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <span class="dark-mode-toggle-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <span class="screen-reader-text"><?php esc_html_e('Light mode', 'aqualuxe'); ?></span>
            </span>
            <span class="dark-mode-toggle-dark">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <span class="screen-reader-text"><?php esc_html_e('Dark mode', 'aqualuxe'); ?></span>
            </span>
        </button>
    </div>
    <?php
}
add_action('aqualuxe_header', 'aqualuxe_dark_mode_toggle_html', 20);

/**
 * Add dark mode toggle to the customizer
 */
function aqualuxe_dark_mode_customizer($wp_customize) {
    // Add dark mode section
    $wp_customize->add_section('aqualuxe_dark_mode', array(
        'title' => __('Dark Mode', 'aqualuxe'),
        'priority' => 30,
    ));
    
    // Add dark mode enable/disable setting
    $wp_customize->add_setting('aqualuxe_enable_dark_mode', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    // Add dark mode enable/disable control
    $wp_customize->add_control('aqualuxe_enable_dark_mode', array(
        'label' => __('Enable Dark Mode', 'aqualuxe'),
        'description' => __('Enable or disable dark mode functionality.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'checkbox',
    ));
    
    // Add dark mode default setting
    $wp_customize->add_setting('aqualuxe_dark_mode_default', array(
        'default' => 'auto',
        'sanitize_callback' => 'aqualuxe_sanitize_dark_mode_default',
    ));
    
    // Add dark mode default control
    $wp_customize->add_control('aqualuxe_dark_mode_default', array(
        'label' => __('Default Mode', 'aqualuxe'),
        'description' => __('Select the default mode for new visitors.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
        'type' => 'select',
        'choices' => array(
            'light' => __('Light Mode', 'aqualuxe'),
            'dark' => __('Dark Mode', 'aqualuxe'),
            'auto' => __('Auto (based on user\'s system preference)', 'aqualuxe'),
        ),
    ));
    
    // Add dark mode background color setting
    $wp_customize->add_setting('aqualuxe_dark_mode_bg_color', array(
        'default' => '#121212',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    // Add dark mode background color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_bg_color', array(
        'label' => __('Dark Mode Background Color', 'aqualuxe'),
        'description' => __('Select the background color for dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
    )));
    
    // Add dark mode text color setting
    $wp_customize->add_setting('aqualuxe_dark_mode_text_color', array(
        'default' => '#f8f9fa',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    // Add dark mode text color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_text_color', array(
        'label' => __('Dark Mode Text Color', 'aqualuxe'),
        'description' => __('Select the text color for dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
    )));
    
    // Add dark mode accent color setting
    $wp_customize->add_setting('aqualuxe_dark_mode_accent_color', array(
        'default' => '#4dabf7',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    // Add dark mode accent color control
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_dark_mode_accent_color', array(
        'label' => __('Dark Mode Accent Color', 'aqualuxe'),
        'description' => __('Select the accent color for dark mode.', 'aqualuxe'),
        'section' => 'aqualuxe_dark_mode',
    )));
}
add_action('customize_register', 'aqualuxe_dark_mode_customizer');

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize dark mode default
 */
function aqualuxe_sanitize_dark_mode_default($input) {
    $valid = array('light', 'dark', 'auto');
    
    if (in_array($input, $valid)) {
        return $input;
    }
    
    return 'auto';
}

/**
 * Add dark mode CSS variables
 */
function aqualuxe_dark_mode_css_variables() {
    // Check if dark mode is enabled in the customizer
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    // Get dark mode colors from the customizer
    $bg_color = get_theme_mod('aqualuxe_dark_mode_bg_color', '#121212');
    $text_color = get_theme_mod('aqualuxe_dark_mode_text_color', '#f8f9fa');
    $accent_color = get_theme_mod('aqualuxe_dark_mode_accent_color', '#4dabf7');
    
    // Calculate lighter and darker variants
    $bg_lighter = aqualuxe_adjust_brightness($bg_color, 15);
    $bg_darker = aqualuxe_adjust_brightness($bg_color, -15);
    $text_lighter = aqualuxe_adjust_brightness($text_color, 15);
    $text_darker = aqualuxe_adjust_brightness($text_color, -15);
    
    // Output CSS variables
    ?>
    <style>
        :root {
            --dark-bg-color: <?php echo esc_attr($bg_color); ?>;
            --dark-bg-color-lighter: <?php echo esc_attr($bg_lighter); ?>;
            --dark-bg-color-darker: <?php echo esc_attr($bg_darker); ?>;
            --dark-text-color: <?php echo esc_attr($text_color); ?>;
            --dark-text-color-lighter: <?php echo esc_attr($text_lighter); ?>;
            --dark-text-color-darker: <?php echo esc_attr($text_darker); ?>;
            --dark-accent-color: <?php echo esc_attr($accent_color); ?>;
        }
        
        .dark {
            --bg-color: var(--dark-bg-color);
            --bg-color-lighter: var(--dark-bg-color-lighter);
            --bg-color-darker: var(--dark-bg-color-darker);
            --text-color: var(--dark-text-color);
            --text-color-lighter: var(--dark-text-color-lighter);
            --text-color-darker: var(--dark-text-color-darker);
            --accent-color: var(--dark-accent-color);
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_dark_mode_css_variables');

/**
 * Adjust brightness of a hex color
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    // Convert back to hex
    return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}

/**
 * Add dark mode toggle script
 */
function aqualuxe_dark_mode_inline_script() {
    // Check if dark mode is enabled in the customizer
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    // Get default mode from the customizer
    $default_mode = get_theme_mod('aqualuxe_dark_mode_default', 'auto');
    
    // Output inline script
    ?>
    <script>
        (function() {
            // Check for saved dark mode preference or use default
            const darkModePref = localStorage.getItem('aqualuxe_dark_mode') || '<?php echo esc_js($default_mode); ?>';
            const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Apply dark mode if preference is dark or if auto and system is dark
            if (darkModePref === 'dark' || (darkModePref === 'auto' && systemDarkMode)) {
                document.documentElement.classList.add('dark');
                document.cookie = 'aqualuxe_dark_mode=dark;path=/;max-age=31536000';
            } else {
                document.documentElement.classList.remove('dark');
                document.cookie = 'aqualuxe_dark_mode=light;path=/;max-age=31536000';
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'aqualuxe_dark_mode_inline_script', 0);

/**
 * AJAX handler for dark mode toggle
 */
function aqualuxe_dark_mode_ajax_handler() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-dark-mode-nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // Get mode from POST
    $mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : 'light';
    
    // Set cookie
    setcookie('aqualuxe_dark_mode', $mode, time() + (365 * DAY_IN_SECONDS), '/');
    
    // Send success response
    wp_send_json_success(array(
        'mode' => $mode,
    ));
}
add_action('wp_ajax_aqualuxe_dark_mode_toggle', 'aqualuxe_dark_mode_ajax_handler');
add_action('wp_ajax_nopriv_aqualuxe_dark_mode_toggle', 'aqualuxe_dark_mode_ajax_handler');

/**
 * Add dark mode class to admin body
 */
function aqualuxe_admin_dark_mode_class($classes) {
    // Check if user has dark mode enabled
    $user_id = get_current_user_id();
    $dark_mode = get_user_meta($user_id, 'aqualuxe_admin_dark_mode', true);
    
    if ($dark_mode === 'on') {
        $classes .= ' aqualuxe-admin-dark-mode';
    }
    
    return $classes;
}
add_filter('admin_body_class', 'aqualuxe_admin_dark_mode_class');

/**
 * Add dark mode toggle to admin bar
 */
function aqualuxe_admin_dark_mode_toggle($wp_admin_bar) {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return;
    }
    
    // Get current user's dark mode setting
    $user_id = get_current_user_id();
    $dark_mode = get_user_meta($user_id, 'aqualuxe_admin_dark_mode', true);
    
    // Add toggle to admin bar
    $wp_admin_bar->add_node(array(
        'id' => 'aqualuxe-admin-dark-mode',
        'title' => $dark_mode === 'on' ? __('Disable Admin Dark Mode', 'aqualuxe') : __('Enable Admin Dark Mode', 'aqualuxe'),
        'href' => wp_nonce_url(add_query_arg('aqualuxe-admin-dark-mode-toggle', 'toggle'), 'aqualuxe-admin-dark-mode-toggle'),
        'meta' => array(
            'title' => __('Toggle Admin Dark Mode', 'aqualuxe'),
        ),
    ));
}
add_action('admin_bar_menu', 'aqualuxe_admin_dark_mode_toggle', 100);

/**
 * Handle admin dark mode toggle
 */
function aqualuxe_handle_admin_dark_mode_toggle() {
    // Check if toggle is requested
    if (!isset($_GET['aqualuxe-admin-dark-mode-toggle'])) {
        return;
    }
    
    // Check nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'aqualuxe-admin-dark-mode-toggle')) {
        wp_die(__('Security check failed.', 'aqualuxe'));
    }
    
    // Get current user's dark mode setting
    $user_id = get_current_user_id();
    $dark_mode = get_user_meta($user_id, 'aqualuxe_admin_dark_mode', true);
    
    // Toggle dark mode
    if ($dark_mode === 'on') {
        update_user_meta($user_id, 'aqualuxe_admin_dark_mode', 'off');
    } else {
        update_user_meta($user_id, 'aqualuxe_admin_dark_mode', 'on');
    }
    
    // Redirect back
    wp_redirect(remove_query_arg(array('aqualuxe-admin-dark-mode-toggle', '_wpnonce')));
    exit;
}
add_action('admin_init', 'aqualuxe_handle_admin_dark_mode_toggle');

/**
 * Add admin dark mode styles
 */
function aqualuxe_admin_dark_mode_styles() {
    // Get current user's dark mode setting
    $user_id = get_current_user_id();
    $dark_mode = get_user_meta($user_id, 'aqualuxe_admin_dark_mode', true);
    
    // Only add styles if dark mode is enabled
    if ($dark_mode !== 'on') {
        return;
    }
    
    // Get dark mode colors from the customizer
    $bg_color = get_theme_mod('aqualuxe_dark_mode_bg_color', '#121212');
    $text_color = get_theme_mod('aqualuxe_dark_mode_text_color', '#f8f9fa');
    $accent_color = get_theme_mod('aqualuxe_dark_mode_accent_color', '#4dabf7');
    
    // Calculate lighter and darker variants
    $bg_lighter = aqualuxe_adjust_brightness($bg_color, 15);
    $bg_darker = aqualuxe_adjust_brightness($bg_color, -15);
    
    // Output admin dark mode styles
    ?>
    <style>
        .aqualuxe-admin-dark-mode {
            --admin-bg-color: <?php echo esc_attr($bg_color); ?>;
            --admin-bg-color-lighter: <?php echo esc_attr($bg_lighter); ?>;
            --admin-bg-color-darker: <?php echo esc_attr($bg_darker); ?>;
            --admin-text-color: <?php echo esc_attr($text_color); ?>;
            --admin-accent-color: <?php echo esc_attr($accent_color); ?>;
        }
        
        .aqualuxe-admin-dark-mode #wpcontent,
        .aqualuxe-admin-dark-mode #wpbody-content {
            background-color: var(--admin-bg-color);
            color: var(--admin-text-color);
        }
        
        .aqualuxe-admin-dark-mode .wrap {
            background-color: var(--admin-bg-color);
            color: var(--admin-text-color);
        }
        
        .aqualuxe-admin-dark-mode .wp-list-table,
        .aqualuxe-admin-dark-mode .widefat {
            background-color: var(--admin-bg-color-lighter);
            color: var(--admin-text-color);
            border-color: var(--admin-bg-color-darker);
        }
        
        .aqualuxe-admin-dark-mode .wp-list-table th,
        .aqualuxe-admin-dark-mode .widefat th {
            background-color: var(--admin-bg-color-darker);
            color: var(--admin-text-color);
        }
        
        .aqualuxe-admin-dark-mode .wp-list-table tr:nth-child(odd),
        .aqualuxe-admin-dark-mode .widefat tr:nth-child(odd) {
            background-color: var(--admin-bg-color);
        }
        
        .aqualuxe-admin-dark-mode .wp-list-table tr:hover,
        .aqualuxe-admin-dark-mode .widefat tr:hover {
            background-color: var(--admin-bg-color-darker);
        }
        
        .aqualuxe-admin-dark-mode .postbox {
            background-color: var(--admin-bg-color-lighter);
            color: var(--admin-text-color);
            border-color: var(--admin-bg-color-darker);
        }
        
        .aqualuxe-admin-dark-mode .postbox .hndle,
        .aqualuxe-admin-dark-mode .postbox .handlediv {
            background-color: var(--admin-bg-color-darker);
            color: var(--admin-text-color);
            border-color: var(--admin-bg-color-darker);
        }
        
        .aqualuxe-admin-dark-mode input[type="text"],
        .aqualuxe-admin-dark-mode input[type="password"],
        .aqualuxe-admin-dark-mode input[type="email"],
        .aqualuxe-admin-dark-mode input[type="number"],
        .aqualuxe-admin-dark-mode input[type="url"],
        .aqualuxe-admin-dark-mode input[type="search"],
        .aqualuxe-admin-dark-mode input[type="tel"],
        .aqualuxe-admin-dark-mode textarea,
        .aqualuxe-admin-dark-mode select {
            background-color: var(--admin-bg-color-darker);
            color: var(--admin-text-color);
            border-color: var(--admin-bg-color-darker);
        }
        
        .aqualuxe-admin-dark-mode a {
            color: var(--admin-accent-color);
        }
        
        .aqualuxe-admin-dark-mode .button-primary {
            background-color: var(--admin-accent-color);
            border-color: var(--admin-accent-color);
            color: var(--admin-bg-color);
        }
        
        .aqualuxe-admin-dark-mode .button-secondary {
            background-color: var(--admin-bg-color-lighter);
            border-color: var(--admin-bg-color-darker);
            color: var(--admin-text-color);
        }
    </style>
    <?php
}
add_action('admin_head', 'aqualuxe_admin_dark_mode_styles');