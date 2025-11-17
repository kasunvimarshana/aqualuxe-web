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
function aqualuxe_body_classes( $classes ) {
    // Adds a class of hfeed to non-singular pages.
    if ( ! is_singular() ) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if ( ! aqualuxe_has_sidebar() ) {
        $classes[] = 'no-sidebar';
    }

    // Add layout class
    $layout = aqualuxe_get_page_layout();
    $classes[] = 'layout-' . $layout;

    // Add dark mode class if enabled
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark';
    }

    // Add WooCommerce class if active
    if ( aqualuxe_is_woocommerce_active() ) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add language class
    $language = aqualuxe_get_current_language();
    if ( $language ) {
        $classes[] = 'lang-' . $language;
    }

    // Add currency class
    $currency = aqualuxe_get_current_currency();
    if ( $currency ) {
        $classes[] = 'currency-' . strtolower( $currency );
    }

    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_schema( $attr ) {
    $schema = 'https://schema.org/';
    $type   = 'WebPage';

    // Check if the schema should be changed
    if ( is_singular( 'post' ) ) {
        $type = 'Article';
    } elseif ( is_author() ) {
        $type = 'ProfilePage';
    } elseif ( is_search() ) {
        $type = 'SearchResultsPage';
    }

    // Apply the schema
    $attr['itemscope'] = '';
    $attr['itemtype']  = $schema . $type;

    return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_schema' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
    // Add a class for featured image
    if ( has_post_thumbnail() ) {
        $classes[] = 'has-post-thumbnail';
    } else {
        $classes[] = 'no-post-thumbnail';
    }

    return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Change the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Change the excerpt more string
 *
 * @param string $more The excerpt more string.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add a wrapper around the site logo
 *
 * @param string $html Logo HTML.
 * @return string
 */
function aqualuxe_get_custom_logo( $html ) {
    $html = '<div class="site-logo">' . $html . '</div>';
    return $html;
}
add_filter( 'get_custom_logo', 'aqualuxe_get_custom_logo' );

/**
 * Add a wrapper around the site title
 *
 * @param string $title The site title.
 * @return string
 */
function aqualuxe_wrap_site_title( $title ) {
    if ( is_home() || is_front_page() ) {
        return '<h1 class="site-title">' . $title . '</h1>';
    } else {
        return '<p class="site-title">' . $title . '</p>';
    }
}
add_filter( 'aqualuxe_site_title', 'aqualuxe_wrap_site_title' );

/**
 * Add a wrapper around the site description
 *
 * @param string $description The site description.
 * @return string
 */
function aqualuxe_wrap_site_description( $description ) {
    return '<p class="site-description">' . $description . '</p>';
}
add_filter( 'aqualuxe_site_description', 'aqualuxe_wrap_site_description' );

/**
 * Add custom attributes to the navigation menu links
 *
 * @param array $atts The link attributes.
 * @param object $item The menu item.
 * @param object $args The menu args.
 * @return array
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args ) {
    // Add class to menu items with children
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' has-dropdown' : 'has-dropdown';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3 );

/**
 * Add custom classes to the navigation menu items
 *
 * @param array $classes The menu item classes.
 * @param object $item The menu item.
 * @param object $args The menu args.
 * @return array
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args ) {
    // Add class to primary menu items
    if ( 'primary' === $args->theme_location ) {
        $classes[] = 'nav-item';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3 );

/**
 * Add custom attributes to the navigation menu
 *
 * @param array $atts The menu attributes.
 * @param object $args The menu args.
 * @return array
 */
function aqualuxe_nav_menu_attributes( $atts, $args ) {
    // Add attributes to primary menu
    if ( 'primary' === $args->theme_location ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' primary-menu' : 'primary-menu';
        $atts['id'] = 'primary-menu';
    }

    return $atts;
}
add_filter( 'aqualuxe_nav_menu_attributes', 'aqualuxe_nav_menu_attributes', 10, 2 );

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    // Don't add Open Graph tags if Yoast SEO or similar plugins are active
    if ( class_exists( 'WPSEO_Options' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
        return;
    }

    global $post;

    if ( is_singular() && $post ) {
        echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            if ( $thumbnail ) {
                echo '<meta property="og:image" content="' . esc_url( $thumbnail[0] ) . '" />' . "\n";
            }
        }
    } else {
        echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        echo '<meta property="og:type" content="website" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $logo ) {
                echo '<meta property="og:image" content="' . esc_url( $logo[0] ) . '" />' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags', 5 );

/**
 * Add Twitter Card meta tags
 */
function aqualuxe_add_twitter_card_tags() {
    // Don't add Twitter Card tags if Yoast SEO or similar plugins are active
    if ( class_exists( 'WPSEO_Options' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
        return;
    }

    global $post;

    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    
    if ( is_singular() && $post ) {
        echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( wp_strip_all_tags( get_the_excerpt() ) ) . '" />' . "\n";
        
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
            if ( $thumbnail ) {
                echo '<meta name="twitter:image" content="' . esc_url( $thumbnail[0] ) . '" />' . "\n";
            }
        }
    } else {
        echo '<meta name="twitter:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( get_bloginfo( 'description' ) ) . '" />' . "\n";
        
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $logo ) {
                echo '<meta name="twitter:image" content="' . esc_url( $logo[0] ) . '" />' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'aqualuxe_add_twitter_card_tags', 6 );

/**
 * Add schema.org markup
 */
function aqualuxe_add_schema_markup() {
    // Don't add schema markup if Yoast SEO or similar plugins are active
    if ( class_exists( 'WPSEO_Options' ) || class_exists( 'All_in_One_SEO_Pack' ) ) {
        return;
    }

    global $post;

    if ( is_singular() && $post ) {
        $schema = array(
            '@context'  => 'https://schema.org',
            '@type'     => 'Article',
            'headline'  => get_the_title(),
            'url'       => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author'    => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
            ),
        );

        if ( has_post_thumbnail( $post->ID ) ) {
            $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
            if ( $thumbnail ) {
                $schema['image'] = $thumbnail[0];
            }
        }

        echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_schema_markup', 10 );

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-card', 600, 400, true );
    add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library
 *
 * @param array $sizes The image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
        'aqualuxe-card'     => __( 'Card Image', 'aqualuxe' ),
        'aqualuxe-thumbnail' => __( 'Thumbnail Square', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add lazy loading to images
 *
 * @param string $html The image HTML.
 * @return string
 */
function aqualuxe_add_lazy_loading( $html ) {
    // Don't add lazy loading if WordPress 5.5+ is active (it has native lazy loading)
    if ( function_exists( 'wp_lazy_loading_enabled' ) && wp_lazy_loading_enabled( 'img', 'the_content' ) ) {
        return $html;
    }

    // Add lazy loading attribute
    $html = str_replace( '<img', '<img loading="lazy"', $html );

    return $html;
}
add_filter( 'the_content', 'aqualuxe_add_lazy_loading', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_lazy_loading', 99 );
add_filter( 'get_avatar', 'aqualuxe_add_lazy_loading', 99 );

/**
 * Add responsive attributes to images
 *
 * @param array $attr The image attributes.
 * @return array
 */
function aqualuxe_add_responsive_image_attributes( $attr ) {
    $attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' img-fluid' : 'img-fluid';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_add_responsive_image_attributes', 10 );

/**
 * Add custom classes to the archive title
 *
 * @param string $title The archive title.
 * @return string
 */
function aqualuxe_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }

    return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_archive_title' );

/**
 * Add custom classes to the archive description
 *
 * @param string $description The archive description.
 * @return string
 */
function aqualuxe_archive_description( $description ) {
    if ( is_category() || is_tag() || is_tax() ) {
        $description = wpautop( $description );
    }

    return $description;
}
add_filter( 'get_the_archive_description', 'aqualuxe_archive_description' );

/**
 * Add SVG support
 *
 * @param array $mimes Allowed mime types.
 * @return array
 */
function aqualuxe_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_mime_types' );

/**
 * Add preconnect for Google Fonts
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add custom classes to the navigation menu
 *
 * @param array $classes The menu classes.
 * @param object $item The menu item.
 * @param object $args The menu args.
 * @param int $depth The menu depth.
 * @return array
 */
function aqualuxe_nav_menu_submenu_css_class( $classes, $args, $depth ) {
    $classes[] = 'sub-menu';
    $classes[] = 'dropdown-menu';
    
    return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'aqualuxe_nav_menu_submenu_css_class', 10, 3 );

/**
 * Add custom classes to the comment form fields
 *
 * @param array $fields The comment form fields.
 * @return array
 */
function aqualuxe_comment_form_fields( $fields ) {
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] = '<div class="comment-form-author mb-4"><label for="author" class="block text-sm font-medium mb-1">' . __( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>';
    
    $fields['email'] = '<div class="comment-form-email mb-4"><label for="email" class="block text-sm font-medium mb-1">' . __( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>';
    
    $fields['url'] = '<div class="comment-form-url mb-4"><label for="url" class="block text-sm font-medium mb-1">' . __( 'Website', 'aqualuxe' ) . '</label><input id="url" name="url" type="url" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>';

    return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_fields' );

/**
 * Add custom classes to the comment form
 *
 * @param array $defaults The comment form defaults.
 * @return array
 */
function aqualuxe_comment_form_defaults( $defaults ) {
    $defaults['comment_field'] = '<div class="comment-form-comment mb-4"><label for="comment" class="block text-sm font-medium mb-1">' . _x( 'Comment', 'noun', 'aqualuxe' ) . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" rows="5" required></textarea></div>';
    
    $defaults['class_submit'] = 'submit bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary';
    
    $defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title text-2xl font-bold mb-6">';
    $defaults['title_reply_after'] = '</h3>';
    
    $defaults['comment_notes_before'] = '<p class="comment-notes text-sm text-gray-600 dark:text-gray-400 mb-4">' . __( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>';
    
    return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Add custom classes to the password form
 *
 * @param string $output The password form HTML.
 * @return string
 */
function aqualuxe_password_form( $output ) {
    $output = str_replace( 'class="post-password-form"', 'class="post-password-form bg-gray-100 dark:bg-gray-800 p-6 rounded-lg"', $output );
    $output = str_replace( '<input name="post_password"', '<input name="post_password" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 mr-2"', $output );
    $output = str_replace( '<input type="submit"', '<input type="submit" class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"', $output );
    
    return $output;
}
add_filter( 'the_password_form', 'aqualuxe_password_form' );

/**
 * Add custom classes to the search form
 *
 * @param string $form The search form HTML.
 * @return string
 */
function aqualuxe_search_form( $form ) {
    $form = str_replace( 'class="search-form"', 'class="search-form flex"', $form );
    $form = str_replace( 'class="search-field"', 'class="search-field w-full rounded-l border-gray-300 dark:border-gray-700 dark:bg-gray-800 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"', $form );
    $form = str_replace( 'class="search-submit"', 'class="search-submit bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-r"', $form );
    
    return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Add custom classes to the pagination
 *
 * @param string $template The pagination template.
 * @return string
 */
function aqualuxe_navigation_markup_template( $template ) {
    $template = '
    <nav class="navigation %1$s" role="navigation" aria-label="%2$s">
        <h2 class="screen-reader-text">%2$s</h2>
        <div class="nav-links flex justify-between items-center">%3$s</div>
    </nav>';
    
    return $template;
}
add_filter( 'navigation_markup_template', 'aqualuxe_navigation_markup_template' );

/**
 * Add custom classes to the posts pagination
 *
 * @param array $args The pagination args.
 * @return array
 */
function aqualuxe_pagination_args( $args ) {
    $args['prev_text'] = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg> ' . __( 'Previous', 'aqualuxe' );
    $args['next_text'] = __( 'Next', 'aqualuxe' ) . ' <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>';
    $args['class'] = 'pagination flex flex-wrap justify-center mt-8';
    
    return $args;
}
add_filter( 'aqualuxe_pagination_args', 'aqualuxe_pagination_args' );

/**
 * Add custom classes to the post navigation
 *
 * @param string $link The post navigation link.
 * @param string $direction The navigation direction.
 * @return string
 */
function aqualuxe_post_navigation_link( $link, $direction ) {
    if ( 'previous' === $direction ) {
        $link = str_replace( '<a ', '<a class="prev-post flex items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow" ', $link );
        $link = str_replace( 'rel="prev">', 'rel="prev"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>', $link );
    } else {
        $link = str_replace( '<a ', '<a class="next-post flex items-center justify-end bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-shadow" ', $link );
        $link = str_replace( 'rel="next">', 'rel="next">', $link );
        $link = str_replace( '</a>', '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg></a>', $link );
    }
    
    return $link;
}
add_filter( 'previous_post_link', function( $link ) { return aqualuxe_post_navigation_link( $link, 'previous' ); } );
add_filter( 'next_post_link', function( $link ) { return aqualuxe_post_navigation_link( $link, 'next' ); } );

/**
 * Add custom classes to the gallery
 *
 * @param string $gallery The gallery HTML.
 * @param array $attr The gallery attributes.
 * @return string
 */
function aqualuxe_gallery_style( $gallery, $attr ) {
    return str_replace( 'class="gallery', 'class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4', $gallery );
}
add_filter( 'post_gallery', 'aqualuxe_gallery_style', 10, 2 );

/**
 * Add custom classes to the gallery item
 *
 * @param string $item The gallery item HTML.
 * @return string
 */
function aqualuxe_gallery_item_style( $item ) {
    return str_replace( 'class="gallery-item', 'class="gallery-item mb-4', $item );
}
add_filter( 'gallery_style', 'aqualuxe_gallery_item_style' );

/**
 * Add custom classes to the gallery image
 *
 * @param string $html The gallery image HTML.
 * @param int $id The attachment ID.
 * @param array $attr The image attributes.
 * @param bool $content Whether to display the content.
 * @param string $type The image type.
 * @return string
 */
function aqualuxe_gallery_image_style( $html, $id, $attr, $content, $type ) {
    if ( 'attachment' === $type ) {
        $html = str_replace( 'class="attachment-thumbnail', 'class="attachment-thumbnail rounded-lg w-full h-auto', $html );
    }
    
    return $html;
}
add_filter( 'wp_get_attachment_image', 'aqualuxe_gallery_image_style', 10, 5 );