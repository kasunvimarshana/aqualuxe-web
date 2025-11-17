<?php
/**
 * Dark Mode Module Initialization
 *
 * @package AquaLuxe
 * @subpackage Modules/DarkMode
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize Dark Mode module
 */
function aqualuxe_dark_mode_init() {
    // Add hooks
    add_action('wp_enqueue_scripts', 'aqualuxe_dark_mode_enqueue_scripts');
    add_action('wp_footer', 'aqualuxe_dark_mode_toggle_button');
    add_action('wp_head', 'aqualuxe_dark_mode_inline_styles');
    add_filter('body_class', 'aqualuxe_dark_mode_body_class');
    
    // Admin hooks
    if (is_admin()) {
        add_action('admin_menu', 'aqualuxe_dark_mode_admin_menu');
        add_action('admin_init', 'aqualuxe_dark_mode_register_settings');
    }
}

/**
 * Enqueue Dark Mode scripts
 */
function aqualuxe_dark_mode_enqueue_scripts() {
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        AQUALUXE_MODULES_DIR . 'dark-mode/assets/js/dark-mode.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'cookieName' => 'aqualuxe_dark_mode',
        'cookieExpiry' => 30, // Days
        'defaultMode' => aqualuxe_get_option('dark_mode_default', 'light'),
        'respectSystemPreference' => aqualuxe_get_option('dark_mode_respect_system', true),
    ));
}

/**
 * Add Dark Mode toggle button to footer
 */
function aqualuxe_dark_mode_toggle_button() {
    $position = aqualuxe_get_option('dark_mode_toggle_position', 'bottom-right');
    $show_text = aqualuxe_get_option('dark_mode_toggle_show_text', true);
    $light_text = aqualuxe_get_option('dark_mode_toggle_light_text', __('Light Mode', 'aqualuxe'));
    $dark_text = aqualuxe_get_option('dark_mode_toggle_dark_text', __('Dark Mode', 'aqualuxe'));
    
    ?>
    <div id="dark-mode-toggle" class="dark-mode-toggle position-<?php echo esc_attr($position); ?>">
        <button class="dark-mode-toggle-btn" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
            <span class="dark-mode-toggle-icon light-icon"></span>
            <span class="dark-mode-toggle-icon dark-icon"></span>
            <?php if ($show_text) : ?>
                <span class="dark-mode-toggle-text light-text"><?php echo esc_html($light_text); ?></span>
                <span class="dark-mode-toggle-text dark-text"><?php echo esc_html($dark_text); ?></span>
            <?php endif; ?>
        </button>
    </div>
    <?php
}

/**
 * Add inline styles for Dark Mode
 */
function aqualuxe_dark_mode_inline_styles() {
    $light_bg = aqualuxe_get_option('dark_mode_light_bg', '#ffffff');
    $light_text = aqualuxe_get_option('dark_mode_light_text', '#333333');
    $dark_bg = aqualuxe_get_option('dark_mode_dark_bg', '#222222');
    $dark_text = aqualuxe_get_option('dark_mode_dark_text', '#f0f0f0');
    $accent_color = aqualuxe_get_option('dark_mode_accent_color', '#0073aa');
    
    ?>
    <style id="aqualuxe-dark-mode-styles">
        :root {
            --aqualuxe-light-bg: <?php echo esc_attr($light_bg); ?>;
            --aqualuxe-light-text: <?php echo esc_attr($light_text); ?>;
            --aqualuxe-dark-bg: <?php echo esc_attr($dark_bg); ?>;
            --aqualuxe-dark-text: <?php echo esc_attr($dark_text); ?>;
            --aqualuxe-dark-mode-accent: <?php echo esc_attr($accent_color); ?>;
        }
        
        /* Light mode styles (default) */
        body {
            --aqualuxe-bg-color: var(--aqualuxe-light-bg);
            --aqualuxe-text-color: var(--aqualuxe-light-text);
            background-color: var(--aqualuxe-bg-color);
            color: var(--aqualuxe-text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Dark mode styles */
        body.dark-mode {
            --aqualuxe-bg-color: var(--aqualuxe-dark-bg);
            --aqualuxe-text-color: var(--aqualuxe-dark-text);
        }
        
        /* Dark mode toggle button styles */
        .dark-mode-toggle {
            position: fixed;
            z-index: 999;
        }
        
        .dark-mode-toggle.position-bottom-right {
            bottom: 20px;
            right: 20px;
        }
        
        .dark-mode-toggle.position-bottom-left {
            bottom: 20px;
            left: 20px;
        }
        
        .dark-mode-toggle.position-top-right {
            top: 20px;
            right: 20px;
        }
        
        .dark-mode-toggle.position-top-left {
            top: 20px;
            left: 20px;
        }
        
        .dark-mode-toggle-btn {
            background-color: var(--aqualuxe-dark-mode-accent);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 10px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }
        
        .dark-mode-toggle-btn:hover {
            background-color: var(--aqualuxe-dark-mode-accent);
            opacity: 0.9;
        }
        
        .dark-mode-toggle-icon {
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        
        .dark-mode-toggle-icon.light-icon {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zm0 2c1.65 0 3 1.35 3 3s-1.35 3-3 3-3-1.35-3-3 1.35-3 3-3zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06z"/></svg>');
            display: none;
        }
        
        .dark-mode-toggle-icon.dark-icon {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white"><path d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/></svg>');
        }
        
        .dark-mode-toggle-text {
            margin-left: 8px;
            font-size: 14px;
        }
        
        .dark-mode-toggle-text.dark-text {
            display: none;
        }
        
        /* Toggle state */
        body.dark-mode .dark-mode-toggle-icon.light-icon {
            display: block;
        }
        
        body.dark-mode .dark-mode-toggle-icon.dark-icon {
            display: none;
        }
        
        body.dark-mode .dark-mode-toggle-text.light-text {
            display: inline;
        }
        
        body.dark-mode .dark-mode-toggle-text.dark-text {
            display: none;
        }
        
        /* System preference detection */
        @media (prefers-color-scheme: dark) {
            body.respect-system-preference {
                --aqualuxe-bg-color: var(--aqualuxe-dark-bg);
                --aqualuxe-text-color: var(--aqualuxe-dark-text);
            }
            
            body.respect-system-preference .dark-mode-toggle-icon.light-icon {
                display: block;
            }
            
            body.respect-system-preference .dark-mode-toggle-icon.dark-icon {
                display: none;
            }
            
            body.respect-system-preference .dark-mode-toggle-text.light-text {
                display: inline;
            }
            
            body.respect-system-preference .dark-mode-toggle-text.dark-text {
                display: none;
            }
        }
    </style>
    <?php
}

/**
 * Add Dark Mode body class
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_dark_mode_body_class($classes) {
    $respect_system = aqualuxe_get_option('dark_mode_respect_system', true);
    
    if ($respect_system) {
        $classes[] = 'respect-system-preference';
    }
    
    // The actual dark-mode class is added via JavaScript based on user preference
    
    return $classes;
}

/**
 * Add Dark Mode admin menu
 */
function aqualuxe_dark_mode_admin_menu() {
    add_submenu_page(
        'themes.php',
        __('Dark Mode Settings', 'aqualuxe'),
        __('Dark Mode', 'aqualuxe'),
        'manage_options',
        'aqualuxe-dark-mode',
        'aqualuxe_dark_mode_settings_page'
    );
}

/**
 * Register Dark Mode settings
 */
function aqualuxe_dark_mode_register_settings() {
    register_setting('aqualuxe_dark_mode', 'aqualuxe_dark_mode_options');
    
    add_settings_section(
        'aqualuxe_dark_mode_general',
        __('General Settings', 'aqualuxe'),
        '__return_false',
        'aqualuxe-dark-mode'
    );
    
    add_settings_field(
        'dark_mode_default',
        __('Default Mode', 'aqualuxe'),
        'aqualuxe_dark_mode_default_field',
        'aqualuxe-dark-mode',
        'aqualuxe_dark_mode_general'
    );
    
    add_settings_field(
        'dark_mode_respect_system',
        __('Respect System Preference', 'aqualuxe'),
        'aqualuxe_dark_mode_respect_system_field',
        'aqualuxe-dark-mode',
        'aqualuxe_dark_mode_general'
    );
    
    add_settings_section(
        'aqualuxe_dark_mode_appearance',
        __('Appearance', 'aqualuxe'),
        '__return_false',
        'aqualuxe-dark-mode'
    );
    
    add_settings_field(
        'dark_mode_colors',
        __('Colors', 'aqualuxe'),
        'aqualuxe_dark_mode_colors_field',
        'aqualuxe-dark-mode',
        'aqualuxe_dark_mode_appearance'
    );
    
    add_settings_field(
        'dark_mode_toggle',
        __('Toggle Button', 'aqualuxe'),
        'aqualuxe_dark_mode_toggle_field',
        'aqualuxe-dark-mode',
        'aqualuxe_dark_mode_appearance'
    );
}

/**
 * Dark Mode settings page
 */
function aqualuxe_dark_mode_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Dark Mode Settings', 'aqualuxe'); ?></h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('aqualuxe_dark_mode');
            do_settings_sections('aqualuxe-dark-mode');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Default mode field
 */
function aqualuxe_dark_mode_default_field() {
    $options = get_option('aqualuxe_dark_mode_options');
    $default_mode = isset($options['default_mode']) ? $options['default_mode'] : 'light';
    ?>
    <select name="aqualuxe_dark_mode_options[default_mode]">
        <option value="light" <?php selected($default_mode, 'light'); ?>><?php esc_html_e('Light', 'aqualuxe'); ?></option>
        <option value="dark" <?php selected($default_mode, 'dark'); ?>><?php esc_html_e('Dark', 'aqualuxe'); ?></option>
    </select>
    <p class="description"><?php esc_html_e('Select the default mode for new visitors.', 'aqualuxe'); ?></p>
    <?php
}

/**
 * Respect system preference field
 */
function aqualuxe_dark_mode_respect_system_field() {
    $options = get_option('aqualuxe_dark_mode_options');
    $respect_system = isset($options['respect_system']) ? $options['respect_system'] : true;
    ?>
    <label>
        <input type="checkbox" name="aqualuxe_dark_mode_options[respect_system]" value="1" <?php checked($respect_system, true); ?>>
        <?php esc_html_e('Use system preference as default when available', 'aqualuxe'); ?>
    </label>
    <p class="description"><?php esc_html_e('If enabled, the theme will use the visitor\'s system preference (light/dark) as the default mode.', 'aqualuxe'); ?></p>
    <?php
}

/**
 * Colors field
 */
function aqualuxe_dark_mode_colors_field() {
    $options = get_option('aqualuxe_dark_mode_options');
    $light_bg = isset($options['light_bg']) ? $options['light_bg'] : '#ffffff';
    $light_text = isset($options['light_text']) ? $options['light_text'] : '#333333';
    $dark_bg = isset($options['dark_bg']) ? $options['dark_bg'] : '#222222';
    $dark_text = isset($options['dark_text']) ? $options['dark_text'] : '#f0f0f0';
    $accent_color = isset($options['accent_color']) ? $options['accent_color'] : '#0073aa';
    ?>
    <div class="dark-mode-colors">
        <div class="dark-mode-color-field">
            <label><?php esc_html_e('Light Mode Background', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[light_bg]" value="<?php echo esc_attr($light_bg); ?>" class="color-picker">
        </div>
        
        <div class="dark-mode-color-field">
            <label><?php esc_html_e('Light Mode Text', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[light_text]" value="<?php echo esc_attr($light_text); ?>" class="color-picker">
        </div>
        
        <div class="dark-mode-color-field">
            <label><?php esc_html_e('Dark Mode Background', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[dark_bg]" value="<?php echo esc_attr($dark_bg); ?>" class="color-picker">
        </div>
        
        <div class="dark-mode-color-field">
            <label><?php esc_html_e('Dark Mode Text', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[dark_text]" value="<?php echo esc_attr($dark_text); ?>" class="color-picker">
        </div>
        
        <div class="dark-mode-color-field">
            <label><?php esc_html_e('Accent Color', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[accent_color]" value="<?php echo esc_attr($accent_color); ?>" class="color-picker">
        </div>
    </div>
    <?php
}

/**
 * Toggle button field
 */
function aqualuxe_dark_mode_toggle_field() {
    $options = get_option('aqualuxe_dark_mode_options');
    $position = isset($options['toggle_position']) ? $options['toggle_position'] : 'bottom-right';
    $show_text = isset($options['toggle_show_text']) ? $options['toggle_show_text'] : true;
    $light_text = isset($options['toggle_light_text']) ? $options['toggle_light_text'] : __('Light Mode', 'aqualuxe');
    $dark_text = isset($options['toggle_dark_text']) ? $options['toggle_dark_text'] : __('Dark Mode', 'aqualuxe');
    ?>
    <div class="dark-mode-toggle-settings">
        <div class="dark-mode-toggle-field">
            <label><?php esc_html_e('Position', 'aqualuxe'); ?></label>
            <select name="aqualuxe_dark_mode_options[toggle_position]">
                <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>><?php esc_html_e('Bottom Right', 'aqualuxe'); ?></option>
                <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>><?php esc_html_e('Bottom Left', 'aqualuxe'); ?></option>
                <option value="top-right" <?php selected($position, 'top-right'); ?>><?php esc_html_e('Top Right', 'aqualuxe'); ?></option>
                <option value="top-left" <?php selected($position, 'top-left'); ?>><?php esc_html_e('Top Left', 'aqualuxe'); ?></option>
            </select>
        </div>
        
        <div class="dark-mode-toggle-field">
            <label>
                <input type="checkbox" name="aqualuxe_dark_mode_options[toggle_show_text]" value="1" <?php checked($show_text, true); ?>>
                <?php esc_html_e('Show text label', 'aqualuxe'); ?>
            </label>
        </div>
        
        <div class="dark-mode-toggle-field">
            <label><?php esc_html_e('Light Mode Text', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[toggle_light_text]" value="<?php echo esc_attr($light_text); ?>">
        </div>
        
        <div class="dark-mode-toggle-field">
            <label><?php esc_html_e('Dark Mode Text', 'aqualuxe'); ?></label>
            <input type="text" name="aqualuxe_dark_mode_options[toggle_dark_text]" value="<?php echo esc_attr($dark_text); ?>">
        </div>
    </div>
    <?php
}