<?php
/**
 * SEO Optimizations - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_seo_setup')) {
    /**
     * Set up SEO optimizations.
     *
     * @since 1.0.0
     */
    function aqualuxe_seo_setup() {
        // Add theme support for title tag
        add_theme_support('title-tag');
        
        // Add theme support for HTML5
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
    }
}
add_action('after_setup_theme', 'aqualuxe_seo_setup');

if (!function_exists('aqualuxe_add_meta_tags')) {
    /**
     * Add meta tags to the head.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_meta_tags() {
        // Add viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
        
        // Add charset meta tag
        echo '<meta charset="' . esc_attr(get_bloginfo('charset')) . '">' . "\n";
        
        // Add description meta tag
        if (is_home() || is_front_page()) {
            $description = get_bloginfo('description');
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
            }
        } elseif (is_single() || is_page()) {
            $description = get_post_meta(get_the_ID(), '_aqualuxe_description', true);
            if (empty($description)) {
                $description = wp_trim_words(get_the_excerpt(), 30);
            }
            if (!empty($description)) {
                echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
            }
        }
        
        // Add author meta tag
        echo '<meta name="author" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
        
        // Add generator meta tag
        echo '<meta name="generator" content="AquaLuxe WordPress Theme">' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_meta_tags', 1);

if (!function_exists('aqualuxe_add_open_graph_tags')) {
    /**
     * Add Open Graph meta tags.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_open_graph_tags() {
        if (is_single() || is_page()) {
            global $post;
            
            // Add Open Graph namespace
            echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            if (has_excerpt($post->ID)) {
                echo '<meta property="og:description" content="' . esc_attr(get_the_excerpt()) . '">' . "\n";
            } elseif (!empty($post->post_content)) {
                $excerpt = wp_trim_words($post->post_content, 30);
                echo '<meta property="og:description" content="' . esc_attr($excerpt) . '">' . "\n";
            }
            
            if (has_post_thumbnail($post->ID)) {
                $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
                echo '<meta property="og:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
            }
            
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_tags', 5);

if (!function_exists('aqualuxe_add_twitter_cards')) {
    /**
     * Add Twitter Card meta tags.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_twitter_cards() {
        if (is_single() || is_page()) {
            global $post;
            
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:site" content="@' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            
            if (has_excerpt($post->ID)) {
                echo '<meta name="twitter:description" content="' . esc_attr(get_the_excerpt()) . '">' . "\n";
            } elseif (!empty($post->post_content)) {
                $excerpt = wp_trim_words($post->post_content, 30);
                echo '<meta name="twitter:description" content="' . esc_attr($excerpt) . '">' . "\n";
            }
            
            if (has_post_thumbnail($post->ID)) {
                $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
                echo '<meta name="twitter:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
            }
        }
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_cards', 5);

if (!function_exists('aqualuxe_add_schema_markup')) {
    /**
     * Add Schema.org markup.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_schema_markup() {
        if (is_single() || is_page()) {
            global $post;
            
            $schema_type = 'Article';
            if (is_page()) {
                $schema_type = 'WebPage';
            }
            
            echo '<script type="application/ld+json">' . "\n";
            echo '{' . "\n";
            echo '  "@context": "https://schema.org",' . "\n";
            echo '  "@type": "' . $schema_type . '",' . "\n";
            echo '  "headline": "' . esc_js(get_the_title()) . '",' . "\n";
            echo '  "datePublished": "' . esc_js(get_the_date('c')) . '",' . "\n";
            echo '  "dateModified": "' . esc_js(get_the_modified_date('c')) . '",' . "\n";
            echo '  "author": {' . "\n";
            echo '    "@type": "Person",' . "\n";
            echo '    "name": "' . esc_js(get_the_author()) . '"' . "\n";
            echo '  },' . "\n";
            echo '  "publisher": {' . "\n";
            echo '    "@type": "Organization",' . "\n";
            echo '    "name": "' . esc_js(get_bloginfo('name')) . '",' . "\n";
            echo '    "logo": {' . "\n";
            echo '      "@type": "ImageObject",' . "\n";
            echo '      "url": "' . esc_js(get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : get_template_directory_uri() . '/screenshot.png') . '"' . "\n";
            echo '    }' . "\n";
            echo '  }' . "\n";
            echo '}' . "\n";
            echo '</script>' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_schema_markup', 10);

if (!function_exists('aqualuxe_optimize_image_alt_tags')) {
    /**
     * Optimize image alt tags.
     *
     * @since 1.0.0
     */
    function aqualuxe_optimize_image_alt_tags($attr, $attachment, $size) {
        if (empty($attr['alt'])) {
            $attr['alt'] = get_the_title($attachment->ID);
        }
        return $attr;
    }
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_optimize_image_alt_tags', 10, 3);

if (!function_exists('aqualuxe_add_breadcrumbs')) {
    /**
     * Add breadcrumbs to the site.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_breadcrumbs() {
        if (!is_home() && !is_front_page()) {
            echo '<nav aria-label="breadcrumb" class="breadcrumb-nav">';
            echo '<ol class="breadcrumb">';
            
            // Home link
            echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'aqualuxe') . '</a></li>';
            
            if (is_category() || is_single()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    $category = $categories[0];
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
            }
            
            if (is_single()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
            } elseif (is_category()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
            } elseif (is_page()) {
                echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
            }
            
            echo '</ol>';
            echo '</nav>';
        }
    }
}

if (!function_exists('aqualuxe_add_canonical_url')) {
    /**
     * Add canonical URL to the head.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_canonical_url() {
        if (is_single() || is_page() || is_category() || is_tag() || is_tax()) {
            echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_canonical_url', 2);