<?php
/**
 * AquaLuxe Theme Assets
 *
 * Functions for handling theme assets (scripts and styles)
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts and styles.
 */
function aqualuxe_enqueue_scripts() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $css_path = isset($manifest['/css/style.css']) ? $manifest['/css/style.css'] : '/css/style.css';
    $js_path = isset($manifest['/js/app.js']) ? $manifest['/js/app.js'] : '/js/app.js';
    $woocommerce_css_path = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';

    // Enqueue main stylesheet
    wp_enqueue_style('aqualuxe-style', AQUALUXE_ASSETS_URI . ltrim($css_path, '/'), array(), AQUALUXE_VERSION);

    // Enqueue WooCommerce styles if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        wp_enqueue_style('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . ltrim($woocommerce_css_path, '/'), array('aqualuxe-style'), AQUALUXE_VERSION);
    }

    // Enqueue main script
    wp_enqueue_script('aqualuxe-app', AQUALUXE_ASSETS_URI . ltrim($js_path, '/'), array(), AQUALUXE_VERSION, true);

    // Localize script with theme data
    wp_localize_script(
        'aqualuxe-app',
        'aqualuxe_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'is_rtl' => is_rtl(),
            'is_user_logged_in' => is_user_logged_in(),
            'is_woocommerce_active' => aqualuxe_is_woocommerce_active(),
            'currency_symbol' => aqualuxe_is_woocommerce_active() ? get_woocommerce_currency_symbol() : '$',
            'home_url' => home_url('/'),
            'theme_url' => AQUALUXE_THEME_URI,
            'assets_url' => AQUALUXE_ASSETS_URI,
            'dark_mode' => array(
                'enabled' => get_theme_mod('aqualuxe_enable_dark_mode', true),
                'default' => get_theme_mod('aqualuxe_default_color_scheme', 'light'),
                'auto' => get_theme_mod('aqualuxe_auto_dark_mode', true),
            ),
        )
    );

    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_scripts');

/**
 * Enqueue admin scripts and styles.
 */
function aqualuxe_admin_enqueue_scripts() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $admin_css_path = isset($manifest['/css/admin.css']) ? $manifest['/css/admin.css'] : '/css/admin.css';
    $admin_js_path = isset($manifest['/js/admin.js']) ? $manifest['/js/admin.js'] : '/js/admin.js';

    // Enqueue admin styles
    wp_enqueue_style('aqualuxe-admin', AQUALUXE_ASSETS_URI . ltrim($admin_css_path, '/'), array(), AQUALUXE_VERSION);

    // Enqueue admin script
    wp_enqueue_script('aqualuxe-admin', AQUALUXE_ASSETS_URI . ltrim($admin_js_path, '/'), array('jquery'), AQUALUXE_VERSION, true);

    // Localize admin script
    wp_localize_script(
        'aqualuxe-admin',
        'aqualuxe_admin_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
            'theme_url' => AQUALUXE_THEME_URI,
            'assets_url' => AQUALUXE_ASSETS_URI,
        )
    );
}
add_action('admin_enqueue_scripts', 'aqualuxe_admin_enqueue_scripts');

/**
 * Enqueue block editor assets.
 */
function aqualuxe_block_editor_assets() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $editor_css_path = isset($manifest['/css/editor-style.css']) ? $manifest['/css/editor-style.css'] : '/css/editor-style.css';

    // Enqueue editor styles
    wp_enqueue_style('aqualuxe-editor-style', AQUALUXE_ASSETS_URI . ltrim($editor_css_path, '/'), array(), AQUALUXE_VERSION);
}
add_action('enqueue_block_editor_assets', 'aqualuxe_block_editor_assets');

/**
 * Enqueue customizer scripts and styles.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $customizer_js_path = isset($manifest['/js/customizer.js']) ? $manifest['/js/customizer.js'] : '/js/customizer.js';

    // Enqueue customizer script
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_ASSETS_URI . ltrim($customizer_js_path, '/'), array('jquery', 'customize-preview'), AQUALUXE_VERSION, true);

    // Localize customizer script
    wp_localize_script(
        'aqualuxe-customizer',
        'aqualuxe_customizer_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-customizer-nonce'),
            'theme_url' => AQUALUXE_THEME_URI,
            'assets_url' => AQUALUXE_ASSETS_URI,
        )
    );
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Enqueue customizer preview scripts.
 */
function aqualuxe_customize_preview_init() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $customizer_js_path = isset($manifest['/js/customizer.js']) ? $manifest['/js/customizer.js'] : '/js/customizer.js';

    // Enqueue customizer preview script
    wp_enqueue_script('aqualuxe-customizer-preview', AQUALUXE_ASSETS_URI . ltrim($customizer_js_path, '/'), array('jquery', 'customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_init');

/**
 * Add async/defer attributes to enqueued scripts
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Add async attribute to specific scripts
    $async_scripts = array('aqualuxe-app');
    if (in_array($handle, $async_scripts, true)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = array('aqualuxe-customizer');
    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Add preconnect for Google Fonts
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
 * Add inline CSS for theme customizer options
 */
function aqualuxe_customizer_inline_css() {
    // Get theme customizer options
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0B6E99');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#1A3C40');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#D4AF37');
    $container_width = get_theme_mod('aqualuxe_container_width', '1280');
    $logo_width = get_theme_mod('aqualuxe_logo_width', '180');
    $body_font_family = get_theme_mod('aqualuxe_body_font_family', 'Montserrat, sans-serif');
    $heading_font_family = get_theme_mod('aqualuxe_heading_font_family', 'Playfair Display, serif');
    $base_font_size = get_theme_mod('aqualuxe_base_font_size', '16');

    // Generate inline CSS
    $css = "
        :root {
            --color-primary: {$primary_color};
            --color-primary-rgb: " . aqualuxe_hex_to_rgb($primary_color) . ";
            --color-secondary: {$secondary_color};
            --color-secondary-rgb: " . aqualuxe_hex_to_rgb($secondary_color) . ";
            --color-accent: {$accent_color};
            --color-accent-rgb: " . aqualuxe_hex_to_rgb($accent_color) . ";
            --container-width: {$container_width}px;
            --logo-width: {$logo_width}px;
            --font-sans: {$body_font_family};
            --font-serif: {$heading_font_family};
            --font-size-base: {$base_font_size}px;
        }
    ";

    // Add the inline CSS
    wp_add_inline_style('aqualuxe-style', $css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_customizer_inline_css', 20);

/**
 * Convert hex color to RGB
 */
function aqualuxe_hex_to_rgb($hex) {
    $hex = str_replace('#', '', $hex);
    
    if (strlen($hex) === 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return $r . ', ' . $g . ', ' . $b;
}

/**
 * Add Google Fonts
 */
function aqualuxe_google_fonts() {
    $body_font_family = get_theme_mod('aqualuxe_body_font_family', 'Montserrat, sans-serif');
    $heading_font_family = get_theme_mod('aqualuxe_heading_font_family', 'Playfair Display, serif');
    
    // Extract font family names
    $body_font = explode(',', $body_font_family)[0];
    $heading_font = explode(',', $heading_font_family)[0];
    
    // Clean font names
    $body_font = str_replace("'", '', trim($body_font));
    $heading_font = str_replace("'", '', trim($heading_font));
    
    // Build Google Fonts URL
    $fonts_url = '';
    $fonts = array();
    $subsets = 'latin,latin-ext';
    
    // Add body font
    if ($body_font && 'system-ui' !== $body_font && 'sans-serif' !== $body_font && 'serif' !== $body_font) {
        $fonts[] = $body_font . ':400,500,600,700';
    }
    
    // Add heading font
    if ($heading_font && 'system-ui' !== $heading_font && 'sans-serif' !== $heading_font && 'serif' !== $heading_font && $heading_font !== $body_font) {
        $fonts[] = $heading_font . ':400,500,600,700';
    }
    
    // Build URL if fonts are specified
    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family' => urlencode(implode('|', $fonts)),
            'subset' => urlencode($subsets),
            'display' => 'swap',
        ), 'https://fonts.googleapis.com/css');
    }
    
    // Enqueue Google Fonts
    if ($fonts_url) {
        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, array(), AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_google_fonts');

/**
 * Add Font Awesome
 */
function aqualuxe_font_awesome() {
    if (get_theme_mod('aqualuxe_enable_font_awesome', true)) {
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css', array(), '5.15.4');
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_font_awesome');

/**
 * Add custom login page styles
 */
function aqualuxe_login_styles() {
    // Get the asset manifest
    $manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
    $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();

    // Determine the correct asset paths with cache busting
    $login_css_path = isset($manifest['/css/login.css']) ? $manifest['/css/login.css'] : '/css/login.css';

    // Enqueue login styles
    wp_enqueue_style('aqualuxe-login', AQUALUXE_ASSETS_URI . ltrim($login_css_path, '/'), array(), AQUALUXE_VERSION);
    
    // Add custom logo to login page
    if (has_custom_logo()) {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        
        if ($logo) {
            ?>
            <style type="text/css">
                .login h1 a {
                    background-image: url(<?php echo esc_url($logo[0]); ?>);
                    background-size: contain;
                    width: 320px;
                    height: <?php echo esc_attr($logo[2] / ($logo[1] / 320)); ?>px;
                }
            </style>
            <?php
        }
    }
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Change login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Change login logo title
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'aqualuxe_login_logo_title');

/**
 * Add custom favicon
 */
function aqualuxe_favicon() {
    $favicon_id = get_theme_mod('aqualuxe_favicon');
    if ($favicon_id) {
        $favicon_url = wp_get_attachment_url($favicon_id);
        echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_favicon');

/**
 * Add custom meta tags to head
 */
function aqualuxe_meta_tags() {
    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add theme color meta tag
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0B6E99');
    echo '<meta name="theme-color" content="' . esc_attr($primary_color) . '">' . "\n";
    
    // Add mobile app capable meta tag
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
    
    // Add Open Graph meta tags
    if (is_single() || is_page()) {
        global $post;
        
        // Get the post title
        $og_title = get_the_title();
        
        // Get the post excerpt
        $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
        
        // Get the post thumbnail
        $og_image = '';
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(null, 'large');
        }
        
        // Get the post URL
        $og_url = get_permalink();
        
        // Output the Open Graph meta tags
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
        if ($og_image) {
            echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
        }
        echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
        echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '">' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_meta_tags', 1);