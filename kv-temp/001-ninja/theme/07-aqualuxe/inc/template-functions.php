<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

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
 * Add custom classes to the body tag
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class if sidebar is active
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the header style
    $header_style = aqualuxe_get_option('aqualuxe_header_style', 'default');
    $classes[] = 'header-style-' . $header_style;

    // Add a class for the footer style
    $footer_style = aqualuxe_get_option('aqualuxe_footer_style', 'default');
    $classes[] = 'footer-style-' . $footer_style;

    // Add a class for dark mode
    if (aqualuxe_is_dark_mode_enabled()) {
        $classes[] = 'dark-mode-enabled';
    }

    // Add a class for multilingual support
    if (aqualuxe_is_multilingual_enabled()) {
        $classes[] = 'multilingual-enabled';
    }

    // Add a class for WooCommerce
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';

        // Add shop layout class
        if (is_shop() || is_product_category() || is_product_tag()) {
            $shop_layout = aqualuxe_get_option('aqualuxe_shop_layout', 'grid');
            $classes[] = 'shop-layout-' . $shop_layout;
        }
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add custom classes to the post tag
 *
 * @param array $classes Post classes.
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add a class for featured image
    if (has_post_thumbnail()) {
        $classes[] = 'has-thumbnail';
    } else {
        $classes[] = 'no-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Add a custom excerpt length
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return aqualuxe_get_option('aqualuxe_excerpt_length', 55);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 *
 * @param string $more More string.
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add schema markup to the body tag
 *
 * @param array $attr Body attributes.
 * @return array
 */
function aqualuxe_body_schema($attr) {
    $schema = 'https://schema.org/';
    $type = 'WebPage';

    // Check if the page is a blog post
    if (is_singular('post')) {
        $type = 'Article';
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    }

    $attr['itemscope'] = '';
    $attr['itemtype'] = $schema . $type;

    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_schema');

/**
 * Add custom attributes to the body tag
 *
 * @return string
 */
function aqualuxe_body_attributes() {
    $attributes = array();

    // Apply filters to the attributes
    $attributes = apply_filters('aqualuxe_body_attributes', $attributes);

    // Convert attributes to string
    $attributes_str = '';
    foreach ($attributes as $name => $value) {
        $attributes_str .= ' ' . $name;
        if (!empty($value)) {
            $attributes_str .= '="' . esc_attr($value) . '"';
        }
    }

    return $attributes_str;
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    // Don't add Open Graph tags if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION')) {
        return;
    }

    global $post;

    // Default values
    $og_title = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url = home_url('/');
    $og_image = '';
    $og_type = 'website';

    // Get custom logo URL
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $og_image = wp_get_attachment_image_url($custom_logo_id, 'full');
    }

    // Override defaults for singular pages
    if (is_singular()) {
        $og_title = get_the_title();
        $og_url = get_permalink();
        $og_type = 'article';

        // Get excerpt or content for description
        $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');

        // Get featured image
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(null, 'large');
        }
    }

    // Output Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";

    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }

    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";

    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags', 5);

/**
 * Add schema.org structured data
 */
function aqualuxe_add_schema_markup() {
    // Don't add schema if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION')) {
        return;
    }

    global $post;

    // Default schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'url' => home_url('/'),
        'name' => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ),
    );

    // Override for singular posts/pages
    if (is_singular()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ),
            'headline' => get_the_title(),
            'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...'),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_custom_logo() ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                    'width' => 600,
                    'height' => 60,
                ),
            ),
        );

        // Add featured image
        if (has_post_thumbnail()) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url(null, 'full'),
                'width' => 1200,
                'height' => 630,
            );
        }
    }

    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
}
add_action('wp_head', 'aqualuxe_add_schema_markup', 10);

/**
 * Add custom image sizes to the WordPress media library
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => esc_html__('Featured Image', 'aqualuxe'),
        'aqualuxe-product' => esc_html__('Product Image', 'aqualuxe'),
        'aqualuxe-product-thumbnail' => esc_html__('Product Thumbnail', 'aqualuxe'),
        'aqualuxe-blog' => esc_html__('Blog Image', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add async/defer attributes to enqueued scripts
 *
 * @param string $tag Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $async_scripts = array(
        'aqualuxe-google-fonts',
    );

    if (in_array($handle, $async_scripts, true)) {
        return str_replace(' src', ' async src', $tag);
    }

    // Add defer attribute to specific scripts
    $defer_scripts = array(
        'aqualuxe-script',
    );

    if (in_array($handle, $defer_scripts, true)) {
        return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preconnect for Google Fonts
 *
 * @param array  $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array
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
 * Add custom classes to the navigation menus
 *
 * @param array $classes Nav menu item classes.
 * @param object $item Nav menu item.
 * @param object $args Nav menu args.
 * @return array
 */
function aqualuxe_nav_menu_css_class($classes, $item, $args) {
    // Add custom classes based on menu location
    if ('primary' === $args->theme_location) {
        $classes[] = 'nav-item';
    }

    // Add active class
    if (in_array('current-menu-item', $classes, true)) {
        $classes[] = 'active';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3);

/**
 * Add custom classes to the navigation menu links
 *
 * @param array $atts Nav menu link attributes.
 * @param object $item Nav menu item.
 * @param object $args Nav menu args.
 * @return array
 */
function aqualuxe_nav_menu_link_attributes($atts, $item, $args) {
    // Add custom classes based on menu location
    if ('primary' === $args->theme_location) {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'nav-link';
    }

    // Add active class
    if (in_array('current-menu-item', $item->classes, true)) {
        $atts['class'] .= ' active';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3);

/**
 * Add custom classes to the comment form fields
 *
 * @param array $fields Comment form fields.
 * @return array
 */
function aqualuxe_comment_form_fields($fields) {
    // Add custom classes to the comment form fields
    foreach ($fields as $key => $field) {
        $fields[$key] = str_replace('input', 'input class="form-input"', $field);
        $fields[$key] = str_replace('textarea', 'textarea class="form-input"', $field);
    }

    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Add custom classes to the comment form
 *
 * @param array $defaults Comment form defaults.
 * @return array
 */
function aqualuxe_comment_form_defaults($defaults) {
    // Add custom classes to the comment form
    $defaults['comment_field'] = str_replace('textarea', 'textarea class="form-input"', $defaults['comment_field']);
    $defaults['class_submit'] = 'btn btn-primary';

    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Add custom classes to the search form
 *
 * @param string $form Search form HTML.
 * @return string
 */
function aqualuxe_search_form($form) {
    $form = str_replace('search-field', 'search-field form-input', $form);
    $form = str_replace('search-submit', 'search-submit btn btn-primary', $form);

    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Add lazy loading to images
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_add_lazy_loading($content) {
    // Skip if WordPress 5.5+ (which has native lazy loading)
    if (function_exists('wp_lazy_loading_enabled')) {
        return $content;
    }

    // Add lazy loading to images
    return preg_replace('/<img(.*?)src="(.*?)"(.*?)>/i', '<img$1src="$2"$3 loading="lazy">', $content);
}
add_filter('the_content', 'aqualuxe_add_lazy_loading');

/**
 * Add responsive container to embeds
 *
 * @param string $html Embed HTML.
 * @return string
 */
function aqualuxe_responsive_embeds($html) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_responsive_embeds');

/**
 * Get the header style
 *
 * @return string
 */
function aqualuxe_get_header_style() {
    return aqualuxe_get_option('aqualuxe_header_style', 'default');
}

/**
 * Get the footer style
 *
 * @return string
 */
function aqualuxe_get_footer_style() {
    return aqualuxe_get_option('aqualuxe_footer_style', 'default');
}

/**
 * Get the shop layout
 *
 * @return string
 */
function aqualuxe_get_shop_layout() {
    return aqualuxe_get_option('aqualuxe_shop_layout', 'grid');
}

/**
 * Get the product columns
 *
 * @return int
 */
function aqualuxe_get_product_columns() {
    return aqualuxe_get_option('aqualuxe_product_columns', 3);
}

/**
 * Get the container width
 *
 * @return int
 */
function aqualuxe_get_container_width() {
    return aqualuxe_get_option('aqualuxe_container_width', 1280);
}

/**
 * Get the button radius
 *
 * @return int
 */
function aqualuxe_get_button_radius() {
    return aqualuxe_get_option('aqualuxe_button_radius', 8);
}

/**
 * Get the card radius
 *
 * @return int
 */
function aqualuxe_get_card_radius() {
    return aqualuxe_get_option('aqualuxe_card_radius', 12);
}

/**
 * Get the body font
 *
 * @return string
 */
function aqualuxe_get_body_font() {
    return aqualuxe_get_option('aqualuxe_body_font', 'Montserrat');
}

/**
 * Get the heading font
 *
 * @return string
 */
function aqualuxe_get_heading_font() {
    return aqualuxe_get_option('aqualuxe_heading_font', 'Playfair Display');
}

/**
 * Get the primary color
 *
 * @return string
 */
function aqualuxe_get_primary_color() {
    return aqualuxe_get_option('aqualuxe_primary_color', '#0ea5e9');
}

/**
 * Get the secondary color
 *
 * @return string
 */
function aqualuxe_get_secondary_color() {
    return aqualuxe_get_option('aqualuxe_secondary_color', '#8b5cf6');
}

/**
 * Get the accent color
 *
 * @return string
 */
function aqualuxe_get_accent_color() {
    return aqualuxe_get_option('aqualuxe_accent_color', '#eab308');
}

/**
 * Get the text color
 *
 * @return string
 */
function aqualuxe_get_text_color() {
    return aqualuxe_get_option('aqualuxe_text_color', '#1f2937');
}

/**
 * Get the background color
 *
 * @return string
 */
function aqualuxe_get_background_color() {
    return aqualuxe_get_option('aqualuxe_background_color', '#ffffff');
}