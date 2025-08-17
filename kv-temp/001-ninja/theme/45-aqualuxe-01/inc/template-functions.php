<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

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

    // Add a class if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add a class for the header style
    $header_style = get_theme_mod('aqualuxe_header_style', 'default');
    $classes[] = 'header-style-' . $header_style;

    // Add a class for the footer style
    $footer_style = get_theme_mod('aqualuxe_footer_style', 'default');
    $classes[] = 'footer-style-' . $footer_style;

    // Add a class for dark mode
    if (get_theme_mod('aqualuxe_dark_mode_enable', true)) {
        $classes[] = 'dark-mode-enabled';
        
        // Check if dark mode is active by default
        if (get_theme_mod('aqualuxe_dark_mode_default', false)) {
            $classes[] = 'dark-mode-active';
        }
    }

    // Add a class for RTL support
    if (is_rtl()) {
        $classes[] = 'rtl';
    }

    // Add a class for the layout
    $layout = get_theme_mod('aqualuxe_layout', 'wide');
    $classes[] = 'layout-' . $layout;

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
 * Add schema.org markup to the HTML tag.
 *
 * @param string $output The HTML output.
 * @return string
 */
function aqualuxe_add_schema_markup($output) {
    $schema = 'http://schema.org/';
    
    // Check if we're on a product page
    if (aqualuxe_is_woocommerce_active() && is_product()) {
        $type = 'Product';
    } elseif (is_singular('post')) {
        $type = 'Article';
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    } else {
        $type = 'WebPage';
    }
    
    $schema_type = apply_filters('aqualuxe_schema_type', $type);
    
    return $output . ' itemscope itemtype="' . esc_attr($schema . $schema_type) . '"';
}
add_filter('language_attributes', 'aqualuxe_add_schema_markup');

/**
 * Add Open Graph meta tags to the head.
 */
function aqualuxe_add_open_graph_tags() {
    global $post;
    
    if (!is_singular()) {
        return;
    }
    
    if (!$post) {
        return;
    }
    
    $og_title = get_the_title();
    $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20);
    $og_url = get_permalink();
    $og_type = is_front_page() ? 'website' : 'article';
    
    // Get the featured image
    $og_image = '';
    if (has_post_thumbnail()) {
        $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    }
    
    // Output the Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    // Add Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_open_graph_tags');

/**
 * Display breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if (!get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
        return;
    }
    
    // Check if we're on the front page
    if (is_front_page()) {
        return;
    }
    
    // Use WooCommerce breadcrumbs if available and on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout())) {
        woocommerce_breadcrumb();
        return;
    }
    
    // Custom breadcrumbs
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    echo '<ol class="breadcrumb-list">';
    
    // Home link
    echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    if (is_category() || is_single()) {
        // Category
        if (is_single()) {
            $categories = get_the_category();
            if ($categories) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
            }
        } else {
            echo '<li class="breadcrumb-item">' . esc_html(single_cat_title('', false)) . '</li>';
        }
    } elseif (is_page()) {
        // Page
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
            }
        }
    } elseif (is_search()) {
        // Search
        echo '<li class="breadcrumb-item">' . esc_html__('Search Results', 'aqualuxe') . '</li>';
    } elseif (is_tag()) {
        // Tag
        echo '<li class="breadcrumb-item">' . esc_html__('Tag', 'aqualuxe') . ': ' . esc_html(single_tag_title('', false)) . '</li>';
    } elseif (is_author()) {
        // Author
        echo '<li class="breadcrumb-item">' . esc_html__('Author', 'aqualuxe') . ': ' . esc_html(get_the_author()) . '</li>';
    } elseif (is_archive()) {
        // Archive
        echo '<li class="breadcrumb-item">' . esc_html(get_the_archive_title()) . '</li>';
    } elseif (is_404()) {
        // 404
        echo '<li class="breadcrumb-item">' . esc_html__('404 Not Found', 'aqualuxe') . '</li>';
    }
    
    // Current page
    if (is_single() || is_page()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Get the page title.
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    if (is_home()) {
        if (get_option('page_for_posts')) {
            return get_the_title(get_option('page_for_posts'));
        } else {
            return esc_html__('Blog', 'aqualuxe');
        }
    } elseif (is_archive()) {
        return get_the_archive_title();
    } elseif (is_search()) {
        return sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
    } elseif (is_404()) {
        return esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe');
    } else {
        return get_the_title();
    }
}

/**
 * Get the page description.
 *
 * @return string
 */
function aqualuxe_get_page_description() {
    if (is_home()) {
        if (get_option('page_for_posts')) {
            return get_the_excerpt(get_option('page_for_posts'));
        }
    } elseif (is_archive()) {
        return get_the_archive_description();
    } elseif (is_search()) {
        return '';
    } elseif (is_404()) {
        return esc_html__('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe');
    } else {
        return has_excerpt() ? get_the_excerpt() : '';
    }
    
    return '';
}

/**
 * Check if the current page has a sidebar.
 *
 * @return bool
 */
function aqualuxe_has_sidebar() {
    // Check if the sidebar is active
    if (!is_active_sidebar('sidebar-1')) {
        return false;
    }
    
    // Check if we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        // Check if the shop sidebar is active
        if (!is_active_sidebar('shop-sidebar')) {
            return false;
        }
        
        // Check if the shop sidebar is enabled
        return get_theme_mod('aqualuxe_shop_sidebar_enable', true);
    }
    
    // Check if we're on a single post or page
    if (is_singular()) {
        // Check if the sidebar is disabled for this post or page
        $sidebar_disabled = get_post_meta(get_the_ID(), '_aqualuxe_sidebar_disabled', true);
        if ($sidebar_disabled) {
            return false;
        }
    }
    
    // Check if the sidebar is enabled in the customizer
    return get_theme_mod('aqualuxe_sidebar_enable', true);
}

/**
 * Get the container class.
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    $container_class = 'container mx-auto px-4';
    
    // Check if we're using a full-width layout
    if (get_theme_mod('aqualuxe_layout', 'wide') === 'full-width') {
        $container_class = 'container-fluid px-4';
    }
    
    return apply_filters('aqualuxe_container_class', $container_class);
}

/**
 * Get the sidebar position.
 *
 * @return string
 */
function aqualuxe_get_sidebar_position() {
    $position = get_theme_mod('aqualuxe_sidebar_position', 'right');
    
    // Check if we're on a single post or page
    if (is_singular()) {
        // Check if the sidebar position is overridden for this post or page
        $sidebar_position = get_post_meta(get_the_ID(), '_aqualuxe_sidebar_position', true);
        if ($sidebar_position && $sidebar_position !== 'default') {
            $position = $sidebar_position;
        }
    }
    
    // Check if we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        // Check if the shop sidebar position is overridden
        $shop_sidebar_position = get_theme_mod('aqualuxe_shop_sidebar_position', 'left');
        if ($shop_sidebar_position) {
            $position = $shop_sidebar_position;
        }
    }
    
    return apply_filters('aqualuxe_sidebar_position', $position);
}

/**
 * Get the content class.
 *
 * @return string
 */
function aqualuxe_get_content_class() {
    $content_class = 'site-content';
    
    // Check if we have a sidebar
    if (aqualuxe_has_sidebar()) {
        $content_class .= ' has-sidebar';
        
        // Check the sidebar position
        if (aqualuxe_get_sidebar_position() === 'left') {
            $content_class .= ' has-sidebar-left';
        } else {
            $content_class .= ' has-sidebar-right';
        }
    } else {
        $content_class .= ' no-sidebar';
    }
    
    return apply_filters('aqualuxe_content_class', $content_class);
}