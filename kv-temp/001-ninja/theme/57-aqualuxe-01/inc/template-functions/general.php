<?php
/**
 * General template functions
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

    // Add class based on page layout
    $layout = aqualuxe_get_page_layout();
    $classes[] = 'layout-' . $layout;

    // Add class for dark mode
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode';
    }

    // Add class for WooCommerce
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }

    // Add class for header style
    $header_style = aqualuxe_get_header_style();
    $classes[] = 'header-style-' . $header_style;

    // Add class for footer style
    $footer_style = aqualuxe_get_footer_style();
    $classes[] = 'footer-style-' . $footer_style;

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
function aqualuxe_body_schema($attr) {
    $schema = 'https://schema.org/';
    $type   = 'WebPage';

    // Check if the page is a blog post or a specialized page
    if (is_singular('post')) {
        $type = 'Article';
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } elseif (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $type = 'CollectionPage';
        } elseif (is_product()) {
            $type = 'Product';
        }
    }

    $attr['itemscope'] = '';
    $attr['itemtype']  = $schema . $type;

    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_schema');

/**
 * Filters the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length($length) {
    return get_theme_mod('aqualuxe_excerpt_length', 55);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Filters the excerpt "read more" string.
 *
 * @param string $more "Read more" excerpt string.
 * @return string Modified "read more" excerpt string.
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip; <a href="' . esc_url(get_permalink()) . '" class="more-link">' . esc_html__('Continue reading', 'aqualuxe') . ' <span class="screen-reader-text">' . get_the_title() . '</span></a>';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom attributes to the body tag.
 */
function aqualuxe_body_attributes() {
    $attributes = array();
    $attributes = apply_filters('aqualuxe_body_attributes', $attributes);

    $output = '';
    foreach ($attributes as $name => $value) {
        $output .= sprintf(' %s="%s"', esc_attr($name), esc_attr($value));
    }

    echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add Open Graph meta tags to the head.
 */
function aqualuxe_add_opengraph_tags() {
    // Only add Open Graph tags if SEO plugins are not active
    if (function_exists('wpseo_init') || function_exists('rank_math') || function_exists('the_seo_framework')) {
        return;
    }

    global $post;

    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '" />' . "\n";
        }
        
        $excerpt = strip_tags(get_the_excerpt());
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags');

/**
 * Add Twitter Card meta tags to the head.
 */
function aqualuxe_add_twitter_card_tags() {
    // Only add Twitter Card tags if SEO plugins are not active
    if (function_exists('wpseo_init') || function_exists('rank_math') || function_exists('the_seo_framework')) {
        return;
    }

    global $post;

    if (is_singular()) {
        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        
        $twitter_site = get_theme_mod('aqualuxe_twitter_username', '');
        if ($twitter_site) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_site) . '" />' . "\n";
        }
        
        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            echo '<meta name="twitter:image" content="' . esc_url($thumbnail_src[0]) . '" />' . "\n";
        }
        
        $excerpt = strip_tags(get_the_excerpt());
        echo '<meta name="twitter:description" content="' . esc_attr($excerpt) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_card_tags');

/**
 * Add Schema.org structured data to the head.
 */
function aqualuxe_add_schema_structured_data() {
    // Only add Schema.org structured data if SEO plugins are not active
    if (function_exists('wpseo_init') || function_exists('rank_math') || function_exists('the_seo_framework')) {
        return;
    }

    global $post;

    if (is_singular('post')) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
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
                    'url' => get_theme_mod('aqualuxe_schema_logo', get_site_icon_url()),
                ),
            ),
        );

        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            $schema['image'] = $thumbnail_src[0];
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    } elseif (is_singular('product') && aqualuxe_is_woocommerce_active()) {
        $product = wc_get_product($post->ID);
        
        if (!$product) {
            return;
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => $product->get_sku(),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ),
        );

        if (has_post_thumbnail($post->ID)) {
            $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            $schema['image'] = $thumbnail_src[0];
        }

        if ($product->get_rating_count() > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            );
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_schema_structured_data');

/**
 * Add custom image sizes to the media library
 *
 * @param array $sizes Image sizes.
 * @return array Modified image sizes.
 */
function aqualuxe_custom_image_sizes_media_library($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-hero' => esc_html__('Hero Image', 'aqualuxe'),
        'aqualuxe-featured' => esc_html__('Featured Image', 'aqualuxe'),
        'aqualuxe-blog-thumbnail' => esc_html__('Blog Thumbnail', 'aqualuxe'),
        'aqualuxe-product-gallery' => esc_html__('Product Gallery', 'aqualuxe'),
        'aqualuxe-team' => esc_html__('Team Member', 'aqualuxe'),
        'aqualuxe-testimonial' => esc_html__('Testimonial', 'aqualuxe'),
        'aqualuxe-service' => esc_html__('Service', 'aqualuxe'),
        'aqualuxe-partner' => esc_html__('Partner/Brand Logo', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes_media_library');

/**
 * Add lazy loading to images
 *
 * @param string $content The content.
 * @return string Modified content.
 */
function aqualuxe_add_lazy_loading($content) {
    // Skip if content is empty
    if (empty($content)) {
        return $content;
    }

    // Skip if lazy loading is disabled
    if (!get_theme_mod('aqualuxe_enable_lazy_loading', true)) {
        return $content;
    }

    // Don't lazy load if the AMP plugin is active and we're on an AMP page
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $content;
    }

    // Replace images with lazy loading attribute
    $content = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
        // Skip if image already has loading attribute
        if (strpos($matches[1], 'loading=') !== false) {
            return $matches[0];
        }

        // Add loading="lazy" attribute
        return '<img' . $matches[1] . ' loading="lazy">';
    }, $content);

    return $content;
}
add_filter('the_content', 'aqualuxe_add_lazy_loading', 99);
add_filter('post_thumbnail_html', 'aqualuxe_add_lazy_loading', 99);
add_filter('widget_text', 'aqualuxe_add_lazy_loading', 99);

/**
 * Add responsive container to embeds
 *
 * @param string $html The embed HTML.
 * @return string Modified embed HTML.
 */
function aqualuxe_responsive_embeds($html) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_responsive_embeds', 10, 3);
add_filter('video_embed_html', 'aqualuxe_responsive_embeds');

/**
 * Add custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add has-post-thumbnail class
    if (has_post_thumbnail()) {
        $classes[] = 'has-post-thumbnail';
    } else {
        $classes[] = 'no-post-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Modify the "read more" link text
 *
 * @return string Modified read more link
 */
function aqualuxe_modify_read_more_link() {
    return '<a class="more-link" href="' . get_permalink() . '">' . esc_html__('Continue reading', 'aqualuxe') . ' <span class="screen-reader-text">' . get_the_title() . '</span></a>';
}
add_filter('the_content_more_link', 'aqualuxe_modify_read_more_link');

/**
 * Add SVG support
 *
 * @param array $mimes Allowed mime types.
 * @return array Modified allowed mime types.
 */
function aqualuxe_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'aqualuxe_mime_types');

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
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
 * Disable emojis
 */
function aqualuxe_disable_emojis() {
    // Check if emojis are disabled in theme options
    if (!get_theme_mod('aqualuxe_disable_emojis', false)) {
        return;
    }

    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Remove the tinymce emoji plugin
    add_filter('tiny_mce_plugins', 'aqualuxe_disable_emojis_tinymce');
}
add_action('init', 'aqualuxe_disable_emojis');

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins Array of TinyMCE plugins.
 * @return array Modified array of TinyMCE plugins.
 */
function aqualuxe_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    
    return array();
}

/**
 * Add custom query vars
 *
 * @param array $vars Query vars.
 * @return array Modified query vars.
 */
function aqualuxe_query_vars($vars) {
    $vars[] = 'color_scheme';
    return $vars;
}
add_filter('query_vars', 'aqualuxe_query_vars');

/**
 * Set color scheme based on query var
 */
function aqualuxe_set_color_scheme() {
    $color_scheme = get_query_var('color_scheme');
    
    if ($color_scheme === 'dark' || $color_scheme === 'light') {
        setcookie('aqualuxe_dark_mode', $color_scheme === 'dark' ? 'true' : 'false', time() + (86400 * 30), '/');
    }
}
add_action('template_redirect', 'aqualuxe_set_color_scheme');