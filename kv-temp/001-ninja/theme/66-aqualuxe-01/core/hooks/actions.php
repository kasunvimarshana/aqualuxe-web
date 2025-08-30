<?php
/**
 * Theme actions
 *
 * @package AquaLuxe
 */

/**
 * Add theme head meta
 */
function aqualuxe_head_meta() {
    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
    
    // Add Open Graph meta tags
    echo aqualuxe_get_open_graph_meta();
    
    // Add Twitter Card meta tags
    echo aqualuxe_get_twitter_card_meta();
}
add_action('wp_head', 'aqualuxe_head_meta', 1);

/**
 * Add body classes
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add a class if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }
    
    // Add a class for each active module
    $active_modules = aqualuxe_get_active_modules();
    foreach ($active_modules as $module => $instance) {
        $classes[] = 'module-' . $module . '-active';
    }
    
    // Add a class for the current page template
    if (is_page_template()) {
        $template = get_page_template_slug();
        $template = str_replace('.php', '', $template);
        $template = str_replace('templates/', '', $template);
        $classes[] = 'template-' . $template;
    }
    
    // Add a class for the current post type
    if (is_singular()) {
        $post_type = get_post_type();
        $classes[] = 'singular-' . $post_type;
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add schema markup to HTML tag
 *
 * @param string $output
 * @return string
 */
function aqualuxe_add_schema_to_html_tag($output) {
    return str_replace('<html', '<html ' . aqualuxe_schema_markup('WebPage'), $output);
}
add_filter('language_attributes', 'aqualuxe_add_schema_to_html_tag');

/**
 * Add preload for web fonts
 */
function aqualuxe_preload_fonts() {
    // Get font preload URLs from theme options
    $font_urls = get_theme_mod('aqualuxe_font_preload_urls', []);
    
    if (!empty($font_urls) && is_array($font_urls)) {
        foreach ($font_urls as $url) {
            echo '<link rel="preload" href="' . esc_url($url) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_preload_fonts', 1);

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    // Hero image size
    add_image_size('aqualuxe-hero', 1920, 1080, true);
    
    // Featured image size
    add_image_size('aqualuxe-featured', 800, 600, true);
    
    // Thumbnail image size
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
    
    // Square image size
    add_image_size('aqualuxe-square', 600, 600, true);
    
    // Product gallery image size
    add_image_size('aqualuxe-product-gallery', 800, 800, true);
}
add_action('after_setup_theme', 'aqualuxe_add_image_sizes');

/**
 * Register image sizes for media library
 *
 * @param array $sizes
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, [
        'aqualuxe-hero' => __('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Custom Thumbnail', 'aqualuxe'),
        'aqualuxe-square' => __('Square Image', 'aqualuxe'),
        'aqualuxe-product-gallery' => __('Product Gallery', 'aqualuxe'),
    ]);
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add async/defer attributes to scripts
 *
 * @param string $tag
 * @param string $handle
 * @return string
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $async_scripts = [
        'aqualuxe-analytics',
    ];
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = [
        'aqualuxe-script',
    ];
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preconnect for external resources
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add preconnect for Google Fonts
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Register theme menu locations
 */
function aqualuxe_register_menus() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'social' => __('Social Links Menu', 'aqualuxe'),
        'mobile' => __('Mobile Menu', 'aqualuxe'),
    ]);
}
add_action('init', 'aqualuxe_register_menus');

/**
 * Add custom menu classes
 *
 * @param array $classes
 * @param object $item
 * @param object $args
 * @return array
 */
function aqualuxe_menu_css_class($classes, $item, $args) {
    // Add custom class to menu items
    if ($args->theme_location === 'primary') {
        $classes[] = 'primary-menu-item';
    } elseif ($args->theme_location === 'footer') {
        $classes[] = 'footer-menu-item';
    } elseif ($args->theme_location === 'social') {
        $classes[] = 'social-menu-item';
    } elseif ($args->theme_location === 'mobile') {
        $classes[] = 'mobile-menu-item';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_css_class', 10, 3);

/**
 * Add custom menu link attributes
 *
 * @param array $atts
 * @param object $item
 * @param object $args
 * @return array
 */
function aqualuxe_menu_link_attributes($atts, $item, $args) {
    // Add custom attributes to menu links
    if ($args->theme_location === 'primary') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' primary-menu-link' : 'primary-menu-link';
    } elseif ($args->theme_location === 'footer') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' footer-menu-link' : 'footer-menu-link';
    } elseif ($args->theme_location === 'social') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' social-menu-link' : 'social-menu-link';
    } elseif ($args->theme_location === 'mobile') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' mobile-menu-link' : 'mobile-menu-link';
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_link_attributes', 10, 3);

/**
 * Add lazy loading to images
 *
 * @param string $html
 * @param int $attachment_id
 * @return string
 */
function aqualuxe_lazy_load_images($html, $attachment_id) {
    // Skip if already has loading attribute
    if (strpos($html, 'loading=') !== false) {
        return $html;
    }
    
    // Add loading attribute
    $html = str_replace('<img', '<img loading="lazy"', $html);
    
    return $html;
}
add_filter('wp_get_attachment_image', 'aqualuxe_lazy_load_images', 10, 2);

/**
 * Add lazy loading to content images
 *
 * @param string $content
 * @return string
 */
function aqualuxe_lazy_load_content_images($content) {
    // Skip if no images
    if (strpos($content, '<img') === false) {
        return $content;
    }
    
    // Add loading attribute to images without it
    $content = preg_replace('/<img((?!loading=).)*?>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_load_content_images');

/**
 * Add custom admin footer text
 *
 * @param string $text
 * @return string
 */
function aqualuxe_admin_footer_text($text) {
    return '<span id="footer-thankyou">' . __('Thank you for creating with', 'aqualuxe') . ' <a href="https://aqualuxe.com" target="_blank">AquaLuxe</a></span>';
}
add_filter('admin_footer_text', 'aqualuxe_admin_footer_text');

/**
 * Add custom login logo
 */
function aqualuxe_login_logo() {
    $logo_id = get_theme_mod('custom_logo');
    
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        
        echo '<style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(' . $logo_url . ');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                width: 320px;
                height: 80px;
                padding-bottom: 30px;
            }
        </style>';
    }
}
add_action('login_enqueue_scripts', 'aqualuxe_login_logo');

/**
 * Change login logo URL
 *
 * @return string
 */
function aqualuxe_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Change login logo title
 *
 * @return string
 */
function aqualuxe_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'aqualuxe_login_logo_url_title');