<?php
/**
 * Dark mode functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize dark mode functionality
 */
function aqualuxe_dark_mode_init() {
    // Add dark mode toggle script
    add_action('wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts');
    
    // Add dark mode body class
    add_filter('body_class', 'aqualuxe_dark_mode_body_class');
    
    // Save dark mode preference for logged in users
    add_action('wp_ajax_aqualuxe_save_dark_mode', 'aqualuxe_save_dark_mode_preference');
    add_action('wp_ajax_nopriv_aqualuxe_save_dark_mode', 'aqualuxe_save_dark_mode_preference');
}
add_action('after_setup_theme', 'aqualuxe_dark_mode_init');

/**
 * Enqueue dark mode scripts
 */
function aqualuxe_dark_mode_scripts() {
    wp_enqueue_script('aqualuxe-dark-mode', AQUALUXE_ASSETS_URI . 'js/dark-mode.js', array('jquery'), AQUALUXE_VERSION, true);
    
    wp_localize_script('aqualuxe-dark-mode', 'aqualuxeDarkMode', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe-dark-mode-nonce'),
        'isDarkMode' => aqualuxe_is_dark_mode() ? 'true' : 'false',
        'cookieExpiry' => 30, // Days
        'i18n' => array(
            'darkMode' => __('Dark Mode', 'aqualuxe'),
            'lightMode' => __('Light Mode', 'aqualuxe'),
        ),
    ));
}

/**
 * Add dark mode body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_dark_mode_body_class($classes) {
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode';
    } else {
        $classes[] = 'light-mode';
    }
    
    return $classes;
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check cookie first
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        return true;
    } elseif (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'false') {
        return false;
    }
    
    // Then check user preference
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $dark_mode = get_user_meta($user_id, 'aqualuxe_dark_mode', true);
        
        if ($dark_mode === 'true') {
            return true;
        } elseif ($dark_mode === 'false') {
            return false;
        }
    }
    
    // Finally check theme default
    $options = get_option('aqualuxe_options', array());
    return isset($options['default_dark_mode']) && $options['default_dark_mode'];
}

/**
 * Save dark mode preference
 */
function aqualuxe_save_dark_mode_preference() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-dark-mode-nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }
    
    // Get dark mode value
    $dark_mode = isset($_POST['darkMode']) && $_POST['darkMode'] === 'true' ? 'true' : 'false';
    
    // Save preference for logged in users
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'aqualuxe_dark_mode', $dark_mode);
    }
    
    wp_send_json_success(array('message' => __('Dark mode preference saved', 'aqualuxe')));
}

/**
 * Get dark mode CSS variables
 *
 * @return string
 */
function aqualuxe_get_dark_mode_css_variables() {
    $options = get_option('aqualuxe_options', array());
    
    $dark_background_color = isset($options['dark_background_color']) ? $options['dark_background_color'] : '#121212';
    $dark_text_color = isset($options['dark_text_color']) ? $options['dark_text_color'] : '#f5f5f5';
    
    $css = ':root {';
    $css .= '--dark-background-color: ' . esc_attr($dark_background_color) . ';';
    $css .= '--dark-text-color: ' . esc_attr($dark_text_color) . ';';
    $css .= '--dark-border-color: rgba(255, 255, 255, 0.1);';
    $css .= '--dark-input-background: #2d2d2d;';
    $css .= '--dark-input-text: #f5f5f5;';
    $css .= '--dark-card-background: #1e1e1e;';
    $css .= '--dark-card-text: #f5f5f5;';
    $css .= '--dark-header-background: #0a0a0a;';
    $css .= '--dark-footer-background: #0a0a0a;';
    $css .= '}';
    
    return $css;
}

/**
 * Add dark mode CSS to head
 */
function aqualuxe_dark_mode_css() {
    $css = aqualuxe_get_dark_mode_css_variables();
    
    $css .= '.dark-mode {';
    $css .= 'background-color: var(--dark-background-color);';
    $css .= 'color: var(--dark-text-color);';
    $css .= '}';
    
    $css .= '.dark-mode h1, .dark-mode h2, .dark-mode h3, .dark-mode h4, .dark-mode h5, .dark-mode h6 {';
    $css .= 'color: var(--dark-text-color);';
    $css .= '}';
    
    $css .= '.dark-mode a {';
    $css .= 'color: var(--primary-color);';
    $css .= '}';
    
    $css .= '.dark-mode input, .dark-mode textarea, .dark-mode select {';
    $css .= 'background-color: var(--dark-input-background);';
    $css .= 'color: var(--dark-input-text);';
    $css .= 'border-color: var(--dark-border-color);';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-header {';
    $css .= 'background-color: var(--dark-header-background);';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-footer {';
    $css .= 'background-color: var(--dark-footer-background);';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-card {';
    $css .= 'background-color: var(--dark-card-background);';
    $css .= 'color: var(--dark-card-text);';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-dark-mode-toggle-icon-light {';
    $css .= 'display: none;';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-dark-mode-toggle-icon-dark {';
    $css .= 'display: block;';
    $css .= '}';
    
    $css .= '.light-mode .aqualuxe-dark-mode-toggle-icon-light {';
    $css .= 'display: block;';
    $css .= '}';
    
    $css .= '.light-mode .aqualuxe-dark-mode-toggle-icon-dark {';
    $css .= 'display: none;';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-dark-mode-toggle-text-light {';
    $css .= 'display: none;';
    $css .= '}';
    
    $css .= '.dark-mode .aqualuxe-dark-mode-toggle-text-dark {';
    $css .= 'display: block;';
    $css .= '}';
    
    $css .= '.light-mode .aqualuxe-dark-mode-toggle-text-light {';
    $css .= 'display: block;';
    $css .= '}';
    
    $css .= '.light-mode .aqualuxe-dark-mode-toggle-text-dark {';
    $css .= 'display: none;';
    $css .= '}';
    
    // WooCommerce specific styles
    if (aqualuxe_is_woocommerce_active()) {
        $css .= '.dark-mode.woocommerce ul.products li.product, .dark-mode.woocommerce-page ul.products li.product {';
        $css .= 'background-color: var(--dark-card-background);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce div.product div.summary, .dark-mode.woocommerce-page div.product div.summary {';
        $css .= 'color: var(--dark-text-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce div.product .woocommerce-tabs ul.tabs li {';
        $css .= 'background-color: var(--dark-input-background);';
        $css .= 'border-color: var(--dark-border-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce div.product .woocommerce-tabs ul.tabs li.active {';
        $css .= 'background-color: var(--dark-background-color);';
        $css .= 'border-bottom-color: var(--dark-background-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce div.product .woocommerce-tabs .panel {';
        $css .= 'background-color: var(--dark-background-color);';
        $css .= 'color: var(--dark-text-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce #reviews #comments ol.commentlist li .comment-text {';
        $css .= 'border-color: var(--dark-border-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce-cart .cart-collaterals .cart_totals, .dark-mode.woocommerce-checkout .cart-collaterals .cart_totals {';
        $css .= 'background-color: var(--dark-card-background);';
        $css .= 'color: var(--dark-card-text);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce table.shop_table {';
        $css .= 'border-color: var(--dark-border-color);';
        $css .= '}';
        
        $css .= '.dark-mode.woocommerce table.shop_table td, .dark-mode.woocommerce table.shop_table th {';
        $css .= 'border-top-color: var(--dark-border-color);';
        $css .= '}';
    }
    
    echo '<style id="aqualuxe-dark-mode-css">' . $css . '</style>';
}
add_action('wp_head', 'aqualuxe_dark_mode_css');

/**
 * Create dark mode toggle JavaScript
 */
function aqualuxe_create_dark_mode_js() {
    $js = "
    (function() {
        // Check if dark mode is enabled
        function isDarkMode() {
            const darkModeCookie = getCookie('aqualuxe_dark_mode');
            
            if (darkModeCookie === 'true') {
                return true;
            } else if (darkModeCookie === 'false') {
                return false;
            }
            
            // Check for system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                return true;
            }
            
            // Default to theme setting
            return " . (aqualuxe_is_dark_mode() ? 'true' : 'false') . ";
        }
        
        // Set dark mode
        function setDarkMode(isDark) {
            if (isDark) {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
            } else {
                document.body.classList.remove('dark-mode');
                document.body.classList.add('light-mode');
            }
            
            // Save preference in cookie
            setCookie('aqualuxe_dark_mode', isDark ? 'true' : 'false', 30);
        }
        
        // Set cookie
        function setCookie(name, value, days) {
            let expires = '';
            
            if (days) {
                const date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = '; expires=' + date.toUTCString();
            }
            
            document.cookie = name + '=' + value + expires + '; path=/; SameSite=Lax';
        }
        
        // Get cookie
        function getCookie(name) {
            const nameEQ = name + '=';
            const ca = document.cookie.split(';');
            
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
            }
            
            return null;
        }
        
        // Initialize dark mode
        setDarkMode(isDarkMode());
        
        // Listen for system preference changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (getCookie('aqualuxe_dark_mode') === null) {
                    setDarkMode(e.matches);
                }
            });
        }
        
        // Add toggle functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.aqualuxe-dark-mode-toggle');
            
            toggles.forEach(function(toggle) {
                toggle.setAttribute('data-dark-mode', isDarkMode() ? 'true' : 'false');
                
                toggle.addEventListener('click', function() {
                    const isDark = toggle.getAttribute('data-dark-mode') === 'true';
                    const newState = !isDark;
                    
                    setDarkMode(newState);
                    
                    toggles.forEach(function(t) {
                        t.setAttribute('data-dark-mode', newState ? 'true' : 'false');
                    });
                });
            });
        });
    })();
    ";
    
    echo '<script id="aqualuxe-dark-mode-init">' . $js . '</script>';
}
add_action('wp_head', 'aqualuxe_create_dark_mode_js', 5);