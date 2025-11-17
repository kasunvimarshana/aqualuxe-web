<?php
/**
 * Asset management functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get asset URL from manifest
 *
 * @param string $path Asset path
 * @return string Asset URL
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
    }
    
    if (isset($manifest['/' . $path])) {
        return AQUALUXE_ASSETS_URI . $manifest['/' . $path];
    }
    
    return AQUALUXE_ASSETS_URI . '/' . $path;
}

/**
 * Enqueue frontend scripts and styles
 */
function aqualuxe_enqueue_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'aqualuxe-fonts',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap',
        array(),
        AQUALUXE_VERSION
    );
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'aqualuxe-style',
        aqualuxe_asset('css/main.css'),
        array(),
        AQUALUXE_VERSION
    );
    
    // Enqueue Tailwind CSS
    wp_enqueue_style(
        'aqualuxe-tailwind',
        aqualuxe_asset('css/tailwind.css'),
        array(),
        AQUALUXE_VERSION
    );
    
    // Enqueue main script
    wp_enqueue_script(
        'aqualuxe-script',
        aqualuxe_asset('js/main.js'),
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize script
    wp_localize_script(
        'aqualuxe-script',
        'aqualuxeData',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'isWooCommerceActive' => class_exists('WooCommerce'),
            'homeUrl' => home_url('/'),
            'isLoggedIn' => is_user_logged_in(),
        )
    );
    
    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Enqueue WooCommerce specific assets if WooCommerce is active
    if (class_exists('WooCommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        wp_enqueue_style(
            'aqualuxe-woocommerce',
            aqualuxe_asset('css/woocommerce.css'),
            array('aqualuxe-style'),
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            aqualuxe_asset('js/woocommerce.js'),
            array('jquery', 'aqualuxe-script'),
            AQUALUXE_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Enqueue admin scripts and styles
 *
 * @param string $hook Current admin page
 */
function aqualuxe_admin_scripts($hook) {
    // Enqueue admin stylesheet
    wp_enqueue_style(
        'aqualuxe-admin',
        aqualuxe_asset('css/admin.css'),
        array(),
        AQUALUXE_VERSION
    );
    
    // Enqueue admin script
    wp_enqueue_script(
        'aqualuxe-admin',
        aqualuxe_asset('js/admin.js'),
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
    
    // Localize admin script
    wp_localize_script(
        'aqualuxe-admin',
        'aqualuxeAdminData',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
        )
    );
    
    // Enqueue customizer script only on customizer page
    if ('customize.php' === $hook) {
        wp_enqueue_script(
            'aqualuxe-customizer',
            aqualuxe_asset('js/customizer.js'),
            array('jquery', 'customize-preview'),
            AQUALUXE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');

/**
 * Enqueue block editor assets
 */
function aqualuxe_block_editor_assets() {
    // Enqueue editor stylesheet
    wp_enqueue_style(
        'aqualuxe-editor-style',
        aqualuxe_asset('css/editor-style.css'),
        array(),
        AQUALUXE_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Add preconnect for Google Fonts
 *
 * @param array $urls URLs to print for resource hints
 * @param string $relation_type The relation type the URLs are printed
 * @return array URLs to print for resource hints
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add async/defer attributes to enqueued scripts
 *
 * @param string $tag The script tag
 * @param string $handle The script handle
 * @return string Modified script tag
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $async_scripts = array(
        'aqualuxe-script',
    );
    
    if (in_array($handle, $async_scripts, true)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = array(
        'aqualuxe-woocommerce',
    );
    
    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preload for critical assets
 */
function aqualuxe_preload_assets() {
    ?>
    <link rel="preload" href="<?php echo esc_url(aqualuxe_asset('css/main.css')); ?>" as="style">
    <link rel="preload" href="<?php echo esc_url(aqualuxe_asset('css/tailwind.css')); ?>" as="style">
    <link rel="preload" href="<?php echo esc_url(aqualuxe_asset('js/main.js')); ?>" as="script">
    <?php
}
add_action('wp_head', 'aqualuxe_preload_assets', 1);

/**
 * Add custom inline CSS
 */
function aqualuxe_inline_css() {
    // Get theme options
    $options = get_option('aqualuxe_options', array());
    
    // Default colors
    $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#0077b6';
    $secondary_color = isset($options['secondary_color']) ? $options['secondary_color'] : '#00b4d8';
    $accent_color = isset($options['accent_color']) ? $options['accent_color'] : '#90e0ef';
    
    // Generate CSS variables
    $css = ':root {
        --color-primary: ' . $primary_color . ';
        --color-primary-light: ' . aqualuxe_adjust_brightness($primary_color, 20) . ';
        --color-primary-dark: ' . aqualuxe_adjust_brightness($primary_color, -20) . ';
        --color-secondary: ' . $secondary_color . ';
        --color-secondary-light: ' . aqualuxe_adjust_brightness($secondary_color, 20) . ';
        --color-secondary-dark: ' . aqualuxe_adjust_brightness($secondary_color, -20) . ';
        --color-accent: ' . $accent_color . ';
        --color-accent-light: ' . aqualuxe_adjust_brightness($accent_color, 20) . ';
        --color-accent-dark: ' . aqualuxe_adjust_brightness($accent_color, -20) . ';
    }';
    
    // Print inline CSS
    wp_add_inline_style('aqualuxe-style', $css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_inline_css', 20);

/**
 * Adjust color brightness
 *
 * @param string $hex Hex color code
 * @param int $steps Steps to adjust brightness (positive for lighter, negative for darker)
 * @return string Adjusted hex color
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
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

/**
 * Add critical CSS inline
 */
function aqualuxe_critical_css() {
    // Critical CSS for above-the-fold content
    $critical_css = '
        /* Critical CSS for above-the-fold content */
        body {
            margin: 0;
            padding: 0;
            font-family: var(--font-family, "Montserrat", sans-serif);
            color: var(--color-text, #333333);
            background-color: var(--color-background, #ffffff);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .site-header {
            position: relative;
            z-index: 100;
        }
        
        .site-branding {
            display: flex;
            align-items: center;
        }
        
        .site-navigation {
            display: flex;
            align-items: center;
        }
        
        .hero-section {
            position: relative;
            overflow: hidden;
        }
        
        /* Skip loading animation for critical elements */
        .site-header,
        .hero-section {
            animation: none !important;
            opacity: 1 !important;
        }
    ';
    
    echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
}
add_action('wp_head', 'aqualuxe_critical_css', 1);

/**
 * Defer non-critical CSS
 */
function aqualuxe_defer_non_critical_css() {
    // List of non-critical stylesheets to defer
    $deferred_styles = array(
        'aqualuxe-woocommerce',
    );
    
    // Remove non-critical stylesheets
    foreach ($deferred_styles as $handle) {
        wp_dequeue_style($handle);
    }
    
    // Re-enqueue them with preload
    foreach ($deferred_styles as $handle) {
        $src = wp_styles()->registered[$handle]->src;
        $version = wp_styles()->registered[$handle]->ver;
        $media = wp_styles()->registered[$handle]->args;
        
        // Add preload
        echo '<link rel="preload" href="' . esc_url($src) . '?ver=' . esc_attr($version) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" media="' . esc_attr($media) . '">';
        echo '<noscript><link rel="stylesheet" href="' . esc_url($src) . '?ver=' . esc_attr($version) . '" media="' . esc_attr($media) . '"></noscript>';
    }
}
add_action('wp_head', 'aqualuxe_defer_non_critical_css', 99);

/**
 * Add DNS prefetch for external resources
 */
function aqualuxe_dns_prefetch() {
    echo '<meta http-equiv="x-dns-prefetch-control" content="on">';
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//s.w.org">';
}
add_action('wp_head', 'aqualuxe_dns_prefetch', 0);

/**
 * Remove emoji scripts and styles
 */
function aqualuxe_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce');
}
add_action('init', 'aqualuxe_disable_emojis');

/**
 * Filter function to remove the emoji plugin from TinyMCE
 *
 * @param array $plugins TinyMCE plugins
 * @return array Filtered TinyMCE plugins
 */
function aqualuxe_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    
    return array();
}

/**
 * Remove jQuery migrate
 *
 * @param WP_Scripts $scripts WP_Scripts object
 */
function aqualuxe_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'aqualuxe_remove_jquery_migrate');

/**
 * Remove unnecessary meta tags
 */
function aqualuxe_remove_unnecessary_meta() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
}
add_action('init', 'aqualuxe_remove_unnecessary_meta');

/**
 * Add theme version to admin footer
 *
 * @param string $text Default admin footer text
 * @return string Modified admin footer text
 */
function aqualuxe_admin_footer_text($text) {
    $theme = wp_get_theme();
    
    return $text . ' | ' . $theme->get('Name') . ' ' . $theme->get('Version');
}
add_filter('admin_footer_text', 'aqualuxe_admin_footer_text');