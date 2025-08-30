<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
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
    if ( ! is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'no-sidebar';
    }

    // Add class if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        $classes[] = 'woocommerce-active';
    }

    // Add class based on the layout setting
    $site_layout = get_theme_mod( 'aqualuxe_site_layout', 'wide' );
    $classes[] = 'layout-' . esc_attr( $site_layout );

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
    
    // Check what type of page we're on
    if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
        $type = 'Blog';
    } elseif ( is_search() ) {
        $type = 'SearchResultsPage';
    } elseif ( is_author() ) {
        $type = 'ProfilePage';
    } else {
        $type = 'WebPage';
    }
    
    $attr['itemscope'] = '';
    $attr['itemtype'] = $schema . $type;
    
    return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_schema' );

/**
 * Fallback for primary menu
 */
function aqualuxe_primary_menu_fallback() {
    if ( current_user_can( 'edit_theme_options' ) ) {
        echo '<ul id="primary-menu" class="primary-menu">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Create a Menu', 'aqualuxe' ) . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Custom comment callback
 *
 * @param object $comment Comment object.
 * @param array  $args Comment arguments.
 * @param int    $depth Comment depth.
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent', $comment ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                    if ( 0 != $args['avatar_size'] ) {
                        echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'comment-avatar' ) );
                    }
                    ?>
                    <div class="comment-author-info">
                        <?php printf( '<cite class="fn">%s</cite>', get_comment_author_link( $comment ) ); ?>
                        <div class="comment-metadata">
                            <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                                <time datetime="<?php comment_time( 'c' ); ?>">
                                    <?php
                                    /* translators: 1: comment date, 2: comment time */
                                    printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date( '', $comment ), get_comment_time() );
                                    ?>
                                </time>
                            </a>
                            <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
                        </div>
                    </div>
                </div><!-- .comment-author -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <div class="reply">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<span class="reply-icon"><i class="fas fa-reply"></i></span>',
                        )
                    )
                );
                ?>
            </div>
        </article><!-- .comment-body -->
    <?php
}

/**
 * Custom breadcrumb function
 */
function aqualuxe_breadcrumb() {
    if ( function_exists( 'woocommerce_breadcrumb' ) && class_exists( 'WooCommerce' ) ) {
        woocommerce_breadcrumb(
            array(
                'delimiter'   => '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>',
                'wrap_before' => '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">',
                'wrap_after'  => '</nav>',
                'before'      => '',
                'after'       => '',
                'home'        => _x( 'Home', 'breadcrumb', 'aqualuxe' ),
            )
        );
    } else {
        // Custom breadcrumb implementation for non-WooCommerce pages
        echo '<nav class="aqualuxe-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html_x( 'Home', 'breadcrumb', 'aqualuxe' ) . '</a>';
        
        if ( is_category() || is_single() ) {
            echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
            
            if ( is_single() ) {
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                    echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
                }
                echo '<span class="current">' . get_the_title() . '</span>';
            } else {
                echo '<span class="current">' . single_cat_title( '', false ) . '</span>';
            }
        } elseif ( is_page() ) {
            echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
            echo '<span class="current">' . get_the_title() . '</span>';
        } elseif ( is_search() ) {
            echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
            echo '<span class="current">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</span>';
        } elseif ( is_404() ) {
            echo '<span class="breadcrumb-separator"><i class="fas fa-chevron-right"></i></span>';
            echo '<span class="current">' . esc_html__( 'Page Not Found', 'aqualuxe' ) . '</span>';
        }
        
        echo '</nav>';
    }
}

/**
 * Wishlist link function
 */
function aqualuxe_wishlist_link() {
    // Check if YITH WooCommerce Wishlist is active
    if ( defined( 'YITH_WCWL' ) ) {
        $wishlist_url = YITH_WCWL()->get_wishlist_url();
        $count = yith_wcwl_count_all_products();
        
        echo '<div class="header-wishlist">';
        echo '<a href="' . esc_url( $wishlist_url ) . '">';
        echo '<i class="fas fa-heart"></i>';
        echo '<span class="wishlist-count">' . esc_html( $count ) . '</span>';
        echo '</a>';
        echo '</div>';
    }
}

/**
 * Add Open Graph Meta Tags
 */
function aqualuxe_add_opengraph_tags() {
    global $post;

    if ( is_single() || is_page() ) {
        if ( has_post_thumbnail( $post->ID ) ) {
            $img_src = get_the_post_thumbnail_url( $post->ID, 'large' );
        } else {
            $img_src = get_theme_mod( 'aqualuxe_default_opengraph_image', get_template_directory_uri() . '/assets/images/default-og-image.jpg' );
        }
        
        $excerpt = $post->post_excerpt;
        if ( empty( $excerpt ) ) {
            $excerpt = wp_trim_words( $post->post_content, 55, '...' );
        }
        
        ?>
        <meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>" />
        <meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>" />
        <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>" />
        <meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>" />
        <meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
        <meta property="og:image" content="<?php echo esc_url( $img_src ); ?>" />
        <?php
    }
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags' );

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size( 'aqualuxe-featured-image', 1200, 628, true );
    add_image_size( 'aqualuxe-blog-thumbnail', 800, 450, true );
    add_image_size( 'aqualuxe-search-thumbnail', 300, 200, true );
    add_image_size( 'aqualuxe-product-thumbnail', 600, 600, true );
    add_image_size( 'aqualuxe-product-gallery', 800, 800, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to media library
 */
function aqualuxe_custom_image_sizes( $sizes ) {
    return array_merge(
        $sizes,
        array(
            'aqualuxe-featured-image' => esc_html__( 'Featured Image', 'aqualuxe' ),
            'aqualuxe-blog-thumbnail' => esc_html__( 'Blog Thumbnail', 'aqualuxe' ),
            'aqualuxe-product-thumbnail' => esc_html__( 'Product Thumbnail', 'aqualuxe' ),
            'aqualuxe-product-gallery' => esc_html__( 'Product Gallery', 'aqualuxe' ),
        )
    );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
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
 * Enqueue Google Fonts
 */
function aqualuxe_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
    if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'aqualuxe' ) ) {
        $fonts[] = 'Montserrat:400,500,600,700';
    }

    /* translators: If there are characters in your language that are not supported by Playfair Display, translate this to 'off'. Do not translate into your own language. */
    if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'aqualuxe' ) ) {
        $fonts[] = 'Playfair Display:400,500,600,700';
    }

    if ( $fonts ) {
        $fonts_url = add_query_arg(
            array(
                'family' => urlencode( implode( '|', $fonts ) ),
                'subset' => urlencode( $subsets ),
                'display' => 'swap',
            ),
            'https://fonts.googleapis.com/css'
        );
    }

    return $fonts_url;
}

/**
 * Get SVG icon
 */
function aqualuxe_get_svg( $icon ) {
    $svg = '';
    
    switch ( $icon ) {
        case 'search':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
            break;
        case 'cart':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
            break;
        case 'user':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
            break;
        case 'heart':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
            break;
        case 'menu':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
            break;
        case 'x':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            break;
        case 'chevron-down':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            break;
        case 'chevron-up':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up"><polyline points="18 15 12 9 6 15"></polyline></svg>';
            break;
    }
    
    return $svg;
}