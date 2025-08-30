<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add class for dark mode if enabled
    if (get_theme_mod('aqualuxe_enable_dark_mode', false)) {
        $classes[] = 'dark-mode';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_itemtype($attr) {
    if (!is_singular()) {
        $attr['itemtype'] = 'https://schema.org/WebPage';
        $attr['itemscope'] = 'itemscope';
    } elseif (is_page()) {
        $attr['itemtype'] = 'https://schema.org/WebPage';
        $attr['itemscope'] = 'itemscope';
    } elseif (is_singular('post')) {
        $attr['itemtype'] = 'https://schema.org/Article';
        $attr['itemscope'] = 'itemscope';
    } elseif (is_singular('product')) {
        $attr['itemtype'] = 'https://schema.org/Product';
        $attr['itemscope'] = 'itemscope';
    }

    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_itemtype');

/**
 * Add Open Graph meta tags to the head.
 */
function aqualuxe_add_opengraph_tags() {
    // Don't add OG tags if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION')) {
        return;
    }

    global $post;

    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '" />' . "\n";
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        // Get post excerpt or content
        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags');

/**
 * Add responsive container to embeds
 */
function aqualuxe_embed_wrapper($html) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_embed_wrapper', 10, 3);
add_filter('video_embed_html', 'aqualuxe_embed_wrapper'); // Jetpack

/**
 * Modify the excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Modify the excerpt more string
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size('aqualuxe-featured', 1200, 600, true);
    add_image_size('aqualuxe-product-thumbnail', 600, 600, true);
    add_image_size('aqualuxe-blog-thumbnail', 800, 450, true);
}
add_action('after_setup_theme', 'aqualuxe_add_image_sizes');

/**
 * Register custom image sizes with WordPress
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-product-thumbnail' => __('Product Thumbnail', 'aqualuxe'),
        'aqualuxe-blog-thumbnail' => __('Blog Thumbnail', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add lazy loading to images
 */
function aqualuxe_add_lazy_loading_attribute($content) {
    if (is_admin() || wp_doing_ajax()) {
        return $content;
    }
    
    // Skip if the Jetpack Lazy Load module is active
    if (class_exists('Jetpack') && Jetpack::is_module_active('lazy-images')) {
        return $content;
    }
    
    // Add loading="lazy" to img tags
    $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_add_lazy_loading_attribute');
add_filter('post_thumbnail_html', 'aqualuxe_add_lazy_loading_attribute');
add_filter('get_avatar', 'aqualuxe_add_lazy_loading_attribute');