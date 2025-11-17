<?php
/**
 * Custom hooks for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Before Header Hook
 */
function aqualuxe_before_header() {
    do_action( 'aqualuxe_before_header' );
}

/**
 * After Header Hook
 */
function aqualuxe_after_header() {
    do_action( 'aqualuxe_after_header' );
}

/**
 * Before Footer Hook
 */
function aqualuxe_before_footer() {
    do_action( 'aqualuxe_before_footer' );
}

/**
 * After Footer Hook
 */
function aqualuxe_after_footer() {
    do_action( 'aqualuxe_after_footer' );
}

/**
 * Before Content Hook
 */
function aqualuxe_before_content() {
    do_action( 'aqualuxe_before_content' );
}

/**
 * After Content Hook
 */
function aqualuxe_after_content() {
    do_action( 'aqualuxe_after_content' );
}

/**
 * Before Post Content Hook
 */
function aqualuxe_before_post_content() {
    do_action( 'aqualuxe_before_post_content' );
}

/**
 * After Post Content Hook
 */
function aqualuxe_after_post_content() {
    do_action( 'aqualuxe_after_post_content' );
}

/**
 * Before Page Content Hook
 */
function aqualuxe_before_page_content() {
    do_action( 'aqualuxe_before_page_content' );
}

/**
 * After Page Content Hook
 */
function aqualuxe_after_page_content() {
    do_action( 'aqualuxe_after_page_content' );
}

/**
 * Before Sidebar Hook
 */
function aqualuxe_before_sidebar() {
    do_action( 'aqualuxe_before_sidebar' );
}

/**
 * After Sidebar Hook
 */
function aqualuxe_after_sidebar() {
    do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Before Comments Hook
 */
function aqualuxe_before_comments() {
    do_action( 'aqualuxe_before_comments' );
}

/**
 * After Comments Hook
 */
function aqualuxe_after_comments() {
    do_action( 'aqualuxe_after_comments' );
}

/**
 * Add breadcrumbs to the page
 */
function aqualuxe_add_breadcrumbs() {
    // Check if breadcrumbs are enabled in customizer
    $show_breadcrumbs = get_theme_mod( 'aqualuxe_show_breadcrumbs', true );
    
    if ( $show_breadcrumbs && ! is_front_page() ) {
        aqualuxe_breadcrumbs();
    }
}
add_action( 'aqualuxe_before_content', 'aqualuxe_add_breadcrumbs' );

/**
 * Add back to top button
 */
function aqualuxe_add_back_to_top() {
    // Check if back to top button is enabled in customizer
    $back_to_top = get_theme_mod( 'aqualuxe_back_to_top', true );
    
    if ( $back_to_top ) {
        echo '<a href="#" id="back-to-top" class="back-to-top fixed bottom-8 right-8 z-50 bg-primary hover:bg-primary-dark text-white rounded-full p-3 shadow-lg hidden transition-all duration-300 ease-in-out" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>';
        echo '</a>';
    }
}
add_action( 'wp_footer', 'aqualuxe_add_back_to_top' );

/**
 * Add schema markup to the body
 */
function aqualuxe_add_schema_markup( $attr ) {
    $attr = (array) $attr;
    
    // Apply schema markup
    if ( is_singular( 'post' ) ) {
        $attr['itemscope'] = '';
        $attr['itemtype'] = 'https://schema.org/Article';
    } elseif ( is_page() ) {
        $attr['itemscope'] = '';
        $attr['itemtype'] = 'https://schema.org/WebPage';
    } elseif ( is_search() ) {
        $attr['itemscope'] = '';
        $attr['itemtype'] = 'https://schema.org/SearchResultsPage';
    } else {
        $attr['itemscope'] = '';
        $attr['itemtype'] = 'https://schema.org/WebSite';
    }
    
    return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_add_schema_markup' );

/**
 * Add custom body classes
 */
function aqualuxe_body_classes( $classes ) {
    // Add a class if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }
    
    // Add a class for the page layout
    $classes[] = 'layout-' . aqualuxe_get_page_layout();
    
    // Add a class for the dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark';
    }
    
    // Add a class for the header layout
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    $classes[] = 'header-' . $header_layout;
    
    // Add a class for the footer layout
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    $classes[] = 'footer-' . $footer_layout;
    
    // Add a class for the blog style
    if ( is_home() || is_archive() || is_search() ) {
        $blog_style = get_theme_mod( 'aqualuxe_blog_style', 'default' );
        $classes[] = 'blog-style-' . $blog_style;
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add custom post classes
 */
function aqualuxe_post_classes( $classes ) {
    // Add a class for featured image
    if ( has_post_thumbnail() ) {
        $classes[] = 'has-post-thumbnail';
    } else {
        $classes[] = 'no-post-thumbnail';
    }
    
    // Add a class for the blog style
    if ( is_home() || is_archive() || is_search() ) {
        $blog_style = get_theme_mod( 'aqualuxe_blog_style', 'default' );
        $classes[] = 'blog-style-' . $blog_style;
    }
    
    return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Change the excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
    return get_theme_mod( 'aqualuxe_excerpt_length', 20 );
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Change the excerpt more string
 */
function aqualuxe_excerpt_more( $more ) {
    return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add a wrapper around the site logo
 */
function aqualuxe_get_custom_logo( $html ) {
    $html = '<div class="site-logo">' . $html . '</div>';
    return $html;
}
add_filter( 'get_custom_logo', 'aqualuxe_get_custom_logo' );

/**
 * Add a wrapper around the site title
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
 */
function aqualuxe_wrap_site_description( $description ) {
    return '<p class="site-description">' . $description . '</p>';
}
add_filter( 'aqualuxe_site_description', 'aqualuxe_wrap_site_description' );

/**
 * Add custom attributes to the navigation menu links
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args ) {
    // Add class to menu items with children
    if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' has-dropdown' : 'has-dropdown';
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
    }
    
    // Add class to current menu items
    if ( in_array( 'current-menu-item', $item->classes, true ) ) {
        $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' active' : 'active';
        $atts['aria-current'] = 'page';
    }
    
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 3 );

/**
 * Add custom classes to the navigation menu items
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
function aqualuxe_add_schema_markup_head() {
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
add_action( 'wp_head', 'aqualuxe_add_schema_markup_head', 10 );

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured', 1200, 600, true );
    add_image_size( 'aqualuxe-card', 600, 400, true );
    add_image_size( 'aqualuxe-square', 600, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to the media library
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
        'aqualuxe-card'     => __( 'Card Image', 'aqualuxe' ),
        'aqualuxe-square'   => __( 'Square Image', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add lazy loading to images
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
 */
function aqualuxe_add_responsive_image_attributes( $attr ) {
    $attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' img-fluid' : 'img-fluid';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_add_responsive_image_attributes', 10 );

/**
 * Add custom classes to the archive title
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
 */
function aqualuxe_mime_types( $mimes ) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_mime_types' );

/**
 * Add preconnect for Google Fonts
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
 */
function aqualuxe_nav_menu_submenu_css_class( $classes, $args, $depth ) {
    $classes[] = 'sub-menu';
    $classes[] = 'dropdown-menu';
    
    return $classes;
}
add_filter( 'nav_menu_submenu_css_class', 'aqualuxe_nav_menu_submenu_css_class', 10, 3 );

/**
 * Add custom classes to the comment form fields
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
 */
function aqualuxe_gallery_style( $gallery, $attr ) {
    return str_replace( 'class="gallery', 'class="gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4', $gallery );
}
add_filter( 'post_gallery', 'aqualuxe_gallery_style', 10, 2 );

/**
 * Add custom classes to the gallery item
 */
function aqualuxe_gallery_item_style( $item ) {
    return str_replace( 'class="gallery-item', 'class="gallery-item mb-4', $item );
}
add_filter( 'gallery_style', 'aqualuxe_gallery_item_style' );

/**
 * Add custom classes to the gallery image
 */
function aqualuxe_gallery_image_style( $html, $id, $attr, $content, $type ) {
    if ( 'attachment' === $type ) {
        $html = str_replace( 'class="attachment-thumbnail', 'class="attachment-thumbnail rounded-lg w-full h-auto', $html );
    }
    
    return $html;
}
add_filter( 'wp_get_attachment_image', 'aqualuxe_gallery_image_style', 10, 5 );

/**
 * Add custom body attributes
 */
function aqualuxe_body_attributes( $attr ) {
    $attr = apply_filters( 'aqualuxe_body_attributes', $attr );
    
    $output = '';
    foreach ( $attr as $name => $value ) {
        $output .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( $value ) );
    }
    
    return $output;
}

/**
 * Add custom body attributes to the body tag
 */
function aqualuxe_add_body_attributes() {
    $attr = array();
    echo aqualuxe_body_attributes( $attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'aqualuxe_body_attributes', 'aqualuxe_add_body_attributes' );

/**
 * Add related posts after single post content
 */
function aqualuxe_add_related_posts( $content ) {
    if ( is_singular( 'post' ) && get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
        ob_start();
        aqualuxe_related_posts();
        $related_posts = ob_get_clean();
        $content .= $related_posts;
    }
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_related_posts', 20 );

/**
 * Add author bio after single post content
 */
function aqualuxe_add_author_bio( $content ) {
    if ( is_singular( 'post' ) && get_theme_mod( 'aqualuxe_show_author_bio', true ) && get_the_author_meta( 'description' ) ) {
        ob_start();
        get_template_part( 'template-parts/content/author-bio' );
        $author_bio = ob_get_clean();
        $content .= $author_bio;
    }
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_author_bio', 15 );

/**
 * Add social sharing buttons after single post content
 */
function aqualuxe_add_social_sharing( $content ) {
    if ( is_singular( 'post' ) && function_exists( 'aqualuxe_social_sharing' ) ) {
        ob_start();
        echo '<div class="social-sharing mt-6">';
        echo '<h3 class="text-lg font-bold mb-3">' . esc_html__( 'Share This Post', 'aqualuxe' ) . '</h3>';
        aqualuxe_social_sharing();
        echo '</div>';
        $social_sharing = ob_get_clean();
        $content .= $social_sharing;
    }
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_social_sharing', 10 );

/**
 * Add custom classes to the body tag
 */
function aqualuxe_add_body_class( $classes ) {
    // Add a class for the dark mode
    if ( aqualuxe_is_dark_mode() ) {
        $classes[] = 'dark';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_body_class' );

/**
 * Add custom classes to the admin body tag
 */
function aqualuxe_admin_body_class( $classes ) {
    $classes .= ' aqualuxe-theme';
    
    return $classes;
}
add_filter( 'admin_body_class', 'aqualuxe_admin_body_class' );

/**
 * Add custom styles to the login page
 */
function aqualuxe_login_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Login CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/login.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-login-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/login.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-login-style', get_template_directory_uri() . '/assets/dist/css/login.css', array(), $theme_version );
    }
    
    // Custom logo
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_info = wp_get_attachment_image_src( $custom_logo_id, 'full' );
        if ( $logo_info ) {
            echo '<style>
                .login h1 a {
                    background-image: url(' . esc_url( $logo_info[0] ) . ') !important;
                    background-size: contain !important;
                    width: 320px !important;
                    height: ' . esc_attr( $logo_info[2] / $logo_info[1] * 320 ) . 'px !important;
                }
            </style>';
        }
    }
}
add_action( 'login_enqueue_scripts', 'aqualuxe_login_styles' );

/**
 * Change the login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url( '/' );
}
add_filter( 'login_headerurl', 'aqualuxe_login_logo_url' );

/**
 * Change the login logo title
 */
function aqualuxe_login_logo_title() {
    return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'aqualuxe_login_logo_title' );

/**
 * Add custom styles to the editor
 */
function aqualuxe_add_editor_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/editor.css'] ) ) {
        add_editor_style( '/assets/dist' . $mix_manifest['/css/editor.css'] );
    } else {
        add_editor_style( '/assets/dist/css/editor.css' );
    }
}
add_action( 'after_setup_theme', 'aqualuxe_add_editor_styles' );

/**
 * Add custom styles to the Gutenberg editor
 */
function aqualuxe_gutenberg_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/gutenberg.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-gutenberg-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/gutenberg.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-gutenberg-style', get_template_directory_uri() . '/assets/dist/css/gutenberg.css', array(), $theme_version );
    }
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_gutenberg_styles' );

/**
 * Add custom styles to the Gutenberg editor
 */
function aqualuxe_gutenberg_editor_styles() {
    echo '<style>
        .editor-styles-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif !important;
        }
        .editor-styles-wrapper .wp-block {
            max-width: 1200px;
        }
        .editor-styles-wrapper .wp-block[data-align="wide"] {
            max-width: 1400px;
        }
        .editor-styles-wrapper .wp-block[data-align="full"] {
            max-width: none;
        }
    </style>';
}
add_action( 'admin_head', 'aqualuxe_gutenberg_editor_styles' );

/**
 * Add custom styles to the Gutenberg editor
 */
function aqualuxe_gutenberg_editor_assets() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // Editor JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/gutenberg.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-gutenberg-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/gutenberg.js'], array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-gutenberg-script', get_template_directory_uri() . '/assets/dist/js/gutenberg.js', array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ), $theme_version, true );
    }
}
add_action( 'enqueue_block_editor_assets', 'aqualuxe_gutenberg_editor_assets' );

/**
 * Add WooCommerce support
 */
function aqualuxe_add_woocommerce_support() {
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_support' );

/**
 * Add WooCommerce hooks
 */
function aqualuxe_add_woocommerce_hooks() {
    if ( class_exists( 'WooCommerce' ) ) {
        // Products per page
        add_filter( 'loop_shop_per_page', function() {
            return get_theme_mod( 'aqualuxe_products_per_page', 12 );
        }, 20 );
        
        // Products per row
        add_filter( 'woocommerce_product_loop_start', function( $html ) {
            $products_per_row = get_theme_mod( 'aqualuxe_products_per_row', 3 );
            $columns_class = 'columns-' . $products_per_row;
            $html = str_replace( 'products', 'products ' . $columns_class, $html );
            return $html;
        } );
        
        // Related products
        add_filter( 'woocommerce_output_related_products_args', function( $args ) {
            $args['posts_per_page'] = 3;
            $args['columns'] = 3;
            return $args;
        } );
        
        // Quick view
        if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_quick_view_button', 15 );
        }
        
        // Wishlist
        if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
            add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_wishlist_button', 20 );
        }
        
        // Ajax add to cart
        if ( get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ) ) {
            add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_cart_fragments' );
        }
    }
}
add_action( 'init', 'aqualuxe_add_woocommerce_hooks' );

/**
 * Quick view button
 */
function aqualuxe_quick_view_button() {
    global $product;
    
    echo '<a href="#" class="quick-view-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</a>';
}

/**
 * Wishlist button
 */
function aqualuxe_wishlist_button() {
    global $product;
    
    echo '<a href="#" class="wishlist-button button" data-product-id="' . esc_attr( $product->get_id() ) . '">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</a>';
}

/**
 * Cart fragments
 */
function aqualuxe_cart_fragments( $fragments ) {
    ob_start();
    ?>
    <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();
    
    return $fragments;
}

/**
 * Add custom image sizes for WooCommerce
 */
function aqualuxe_add_woocommerce_image_sizes() {
    add_image_size( 'aqualuxe-product-thumbnail', 300, 300, true );
    add_image_size( 'aqualuxe-product-gallery', 600, 600, true );
    add_image_size( 'aqualuxe-product-single', 800, 800, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_woocommerce_image_sizes' );

/**
 * Add custom image sizes to the media library for WooCommerce
 */
function aqualuxe_add_woocommerce_image_sizes_to_media_library( $sizes ) {
    return array_merge( $sizes, array(
        'aqualuxe-product-thumbnail' => __( 'Product Thumbnail', 'aqualuxe' ),
        'aqualuxe-product-gallery'   => __( 'Product Gallery', 'aqualuxe' ),
        'aqualuxe-product-single'    => __( 'Product Single', 'aqualuxe' ),
    ) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_add_woocommerce_image_sizes_to_media_library' );

/**
 * Add custom body classes for WooCommerce
 */
function aqualuxe_add_woocommerce_body_classes( $classes ) {
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'woocommerce-active';
        
        if ( is_product() ) {
            $classes[] = 'single-product';
        }
        
        if ( is_shop() ) {
            $classes[] = 'woocommerce-shop';
        }
        
        if ( is_product_category() ) {
            $classes[] = 'woocommerce-category';
        }
        
        if ( is_product_tag() ) {
            $classes[] = 'woocommerce-tag';
        }
        
        if ( is_cart() ) {
            $classes[] = 'woocommerce-cart';
        }
        
        if ( is_checkout() ) {
            $classes[] = 'woocommerce-checkout';
        }
        
        if ( is_account_page() ) {
            $classes[] = 'woocommerce-account';
        }
    } else {
        $classes[] = 'woocommerce-inactive';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_woocommerce_body_classes' );

/**
 * Add custom styles to the WooCommerce pages
 */
function aqualuxe_add_woocommerce_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // WooCommerce CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/woocommerce.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/woocommerce.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-woocommerce-style', get_template_directory_uri() . '/assets/dist/css/woocommerce.css', array(), $theme_version );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_add_woocommerce_styles' );

/**
 * Add custom scripts to the WooCommerce pages
 */
function aqualuxe_add_woocommerce_scripts() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // WooCommerce JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/woocommerce.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/woocommerce.js'], array( 'jquery' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-woocommerce-script', get_template_directory_uri() . '/assets/dist/js/woocommerce.js', array( 'jquery' ), $theme_version, true );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_add_woocommerce_scripts' );

/**
 * Add custom styles to the WooCommerce admin pages
 */
function aqualuxe_add_woocommerce_admin_styles() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // WooCommerce admin CSS file
    if ( $mix_manifest && isset( $mix_manifest['/css/woocommerce-admin.css'] ) ) {
        wp_enqueue_style( 'aqualuxe-woocommerce-admin-style', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/css/woocommerce-admin.css'], array(), null );
    } else {
        wp_enqueue_style( 'aqualuxe-woocommerce-admin-style', get_template_directory_uri() . '/assets/dist/css/woocommerce-admin.css', array(), $theme_version );
    }
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_add_woocommerce_admin_styles' );

/**
 * Add custom scripts to the WooCommerce admin pages
 */
function aqualuxe_add_woocommerce_admin_scripts() {
    // Get the theme version
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Check if we have a mix-manifest.json file
    $mix_manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
    $mix_manifest = file_exists( $mix_manifest_path ) ? json_decode( file_get_contents( $mix_manifest_path ), true ) : null;
    
    // WooCommerce admin JavaScript file
    if ( $mix_manifest && isset( $mix_manifest['/js/woocommerce-admin.js'] ) ) {
        wp_enqueue_script( 'aqualuxe-woocommerce-admin-script', get_template_directory_uri() . '/assets/dist' . $mix_manifest['/js/woocommerce-admin.js'], array( 'jquery' ), null, true );
    } else {
        wp_enqueue_script( 'aqualuxe-woocommerce-admin-script', get_template_directory_uri() . '/assets/dist/js/woocommerce-admin.js', array( 'jquery' ), $theme_version, true );
    }
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_add_woocommerce_admin_scripts' );