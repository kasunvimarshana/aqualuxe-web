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

    // Add a class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add a class for the dark mode
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode';
    } else {
        $classes[] = 'light-mode';
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
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    $default = 'auto';
    $mode = isset($_COOKIE['aqualuxe_dark_mode']) ? sanitize_text_field($_COOKIE['aqualuxe_dark_mode']) : $default;
    
    if ($mode === 'dark') {
        return true;
    } elseif ($mode === 'light') {
        return false;
    } else {
        // Auto mode - check system preference
        return false; // Default to light mode for server-side rendering
    }
}

/**
 * Add preload for the dark mode script
 */
function aqualuxe_preload_dark_mode() {
    echo '<script>
        (function() {
            // Check for saved dark mode preference or system preference
            const darkModePref = localStorage.getItem("aqualuxe_dark_mode");
            const systemDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;
            
            // Apply dark mode if preference is dark or if auto and system is dark
            if (darkModePref === "dark" || (darkModePref === "auto" && systemDarkMode) || (!darkModePref && systemDarkMode)) {
                document.documentElement.classList.add("dark");
                document.cookie = "aqualuxe_dark_mode=dark;path=/;max-age=31536000";
            } else {
                document.documentElement.classList.remove("dark");
                document.cookie = "aqualuxe_dark_mode=light;path=/;max-age=31536000";
            }
        })();
    </script>';
}
add_action('wp_head', 'aqualuxe_preload_dark_mode', 0);

/**
 * Add schema markup to the body
 */
function aqualuxe_body_schema() {
    // Default itemtype
    $schema = 'WebPage';

    // Get specific schema for different pages
    if (is_home() || is_archive() || is_attachment() || is_tax() || is_single()) {
        $schema = 'Blog';
    } elseif (is_author()) {
        $schema = 'ProfilePage';
    } elseif (is_search()) {
        $schema = 'SearchResultsPage';
    }

    echo 'itemscope itemtype="https://schema.org/' . esc_attr($schema) . '"';
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    global $post;

    if (is_single() || is_page()) {
        if (has_post_thumbnail($post->ID)) {
            $img_src = get_the_post_thumbnail_url($post->ID, 'large');
        } else {
            $img_src = get_theme_mod('aqualuxe_default_opengraph_image', '');
        }

        $excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 55, '...');

        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        echo '<meta property="og:type" content="' . (is_single() ? 'article' : 'website') . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        if ($img_src) {
            echo '<meta property="og:image" content="' . esc_url($img_src) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags', 5);

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_card_tags() {
    global $post;

    if (is_single() || is_page()) {
        if (has_post_thumbnail($post->ID)) {
            $img_src = get_the_post_thumbnail_url($post->ID, 'large');
        } else {
            $img_src = get_theme_mod('aqualuxe_default_twitter_image', '');
        }

        $excerpt = $post->post_excerpt ? $post->post_excerpt : wp_trim_words($post->post_content, 55, '...');

        echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($excerpt) . '" />' . "\n";
        
        if ($img_src) {
            echo '<meta name="twitter:image" content="' . esc_url($img_src) . '" />' . "\n";
        }
        
        $twitter_site = get_theme_mod('aqualuxe_twitter_username', '');
        if ($twitter_site) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_site) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_twitter_card_tags', 5);

/**
 * Implement lazy loading for images
 */
function aqualuxe_lazy_load_images($content) {
    if (is_admin() || is_feed()) {
        return $content;
    }

    // Skip if AMP is active
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $content;
    }

    // Replace image tags with lazy loading
    $content = preg_replace_callback('/<img([^>]+)>/i', 'aqualuxe_lazy_load_image_callback', $content);

    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_load_images', 99);
add_filter('post_thumbnail_html', 'aqualuxe_lazy_load_images', 99);
add_filter('get_avatar', 'aqualuxe_lazy_load_images', 99);

/**
 * Callback for lazy loading images
 */
function aqualuxe_lazy_load_image_callback($matches) {
    $image = $matches[0];
    
    // Skip if already has loading attribute
    if (strpos($image, 'loading=') !== false) {
        return $image;
    }
    
    // Add loading attribute
    $image = str_replace('<img', '<img loading="lazy"', $image);
    
    return $image;
}

/**
 * Add custom image sizes to the WordPress media library
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-product-thumbnail' => __('Product Thumbnail', 'aqualuxe'),
        'aqualuxe-product-gallery' => __('Product Gallery', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add responsive image support
 */
function aqualuxe_responsive_image_sizes($attr, $attachment, $size) {
    if ($size === 'aqualuxe-featured') {
        $attr['sizes'] = '(max-width: 600px) 100vw, (max-width: 1024px) 75vw, 1200px';
    } elseif ($size === 'aqualuxe-product-thumbnail') {
        $attr['sizes'] = '(max-width: 600px) 50vw, (max-width: 1024px) 33vw, 300px';
    } elseif ($size === 'aqualuxe-product-gallery') {
        $attr['sizes'] = '(max-width: 600px) 100vw, (max-width: 1024px) 50vw, 600px';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'aqualuxe_responsive_image_sizes', 10, 3);

/**
 * Add custom classes to menu items
 */
function aqualuxe_menu_classes($classes, $item, $args) {
    if (property_exists($args, 'theme_location')) {
        if ($args->theme_location === 'primary') {
            $classes[] = 'nav-item';
        } elseif ($args->theme_location === 'footer') {
            $classes[] = 'footer-nav-item';
        } elseif ($args->theme_location === 'social') {
            $classes[] = 'social-nav-item';
        }
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_classes', 10, 3);

/**
 * Add custom classes to menu links
 */
function aqualuxe_menu_link_classes($atts, $item, $args) {
    if (property_exists($args, 'theme_location')) {
        if ($args->theme_location === 'primary') {
            $atts['class'] = 'nav-link';
        } elseif ($args->theme_location === 'footer') {
            $atts['class'] = 'footer-nav-link';
        } elseif ($args->theme_location === 'social') {
            $atts['class'] = 'social-nav-link';
        }
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_link_classes', 10, 3);

/**
 * Add custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Add custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom archive title
 */
function aqualuxe_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add custom archive description
 */
function aqualuxe_archive_description($description) {
    if (is_category() || is_tag() || is_tax()) {
        $description = term_description();
    }
    
    return $description;
}
add_filter('get_the_archive_description', 'aqualuxe_archive_description');

/**
 * Add custom search form
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
        <label>
            <span class="screen-reader-text">' . _x('Search for:', 'label', 'aqualuxe') . '</span>
            <input type="search" class="search-field" placeholder="' . esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
        </label>
        <button type="submit" class="search-submit">
            <span class="screen-reader-text">' . _x('Search', 'submit button', 'aqualuxe') . '</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        </button>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Add custom comment form fields
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? ' aria-required="true"' : '');
    
    $fields['author'] = '<div class="comment-form-author">
        <label for="author">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>
        <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' />
    </div>';
    
    $fields['email'] = '<div class="comment-form-email">
        <label for="email">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label>
        <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' />
    </div>';
    
    $fields['url'] = '<div class="comment-form-url">
        <label for="url">' . __('Website', 'aqualuxe') . '</label>
        <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" />
    </div>';
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Add custom comment form defaults
 */
function aqualuxe_comment_form_defaults($defaults) {
    $defaults['comment_field'] = '<div class="comment-form-comment">
        <label for="comment">' . _x('Comment', 'noun', 'aqualuxe') . ' <span class="required">*</span></label>
        <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>
    </div>';
    
    $defaults['class_submit'] = 'submit button';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Add custom pagination
 */
function aqualuxe_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $big = 999999999; // need an unlikely integer
    
    $links = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg>',
        'next_text' => '<span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        'type' => 'list',
    ));
    
    $links = str_replace('page-numbers', 'pagination', $links);
    
    echo '<nav class="navigation pagination" role="navigation">' . $links . '</nav>';
}