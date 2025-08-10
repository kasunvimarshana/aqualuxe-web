<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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

    // Adds a class if there is a custom header.
    if ( has_header_image() ) {
        $classes[] = 'has-header-image';
    }

    // Adds a class if there is a custom background.
    if ( get_background_image() ) {
        $classes[] = 'has-background-image';
    }

    // Add a class if the site is using a sidebar
    if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
        $classes[] = 'has-sidebar';
        
        // Add a class for the sidebar position
        $sidebar_position = get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );
        $classes[] = $sidebar_position;
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add a class for the color scheme
    $color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'default' );
    $classes[] = 'color-scheme-' . esc_attr( $color_scheme );

    // Add a class for the header layout
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    $classes[] = 'header-layout-' . esc_attr( $header_layout );

    // Add a class for the footer layout
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    $classes[] = 'footer-layout-' . esc_attr( $footer_layout );

    // Add a class for boxed layout
    if ( get_theme_mod( 'aqualuxe_boxed_layout', false ) ) {
        $classes[] = 'boxed-layout';
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
 * Custom pagination function
 */
function aqualuxe_pagination() {
    $pagination_type = get_theme_mod( 'aqualuxe_pagination_type', 'numbered' );

    if ( $pagination_type === 'numbered' ) {
        // Numbered pagination
        the_posts_pagination(
            array(
                'mid_size'           => 2,
                'prev_text'          => '<i class="fa fa-angle-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
                'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'aqualuxe' ) . '</span><i class="fa fa-angle-right"></i>',
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
            )
        );
    } else {
        // Prev/Next pagination
        the_posts_navigation(
            array(
                'prev_text' => '<i class="fa fa-angle-left"></i> ' . esc_html__( 'Older Posts', 'aqualuxe' ),
                'next_text' => esc_html__( 'Newer Posts', 'aqualuxe' ) . ' <i class="fa fa-angle-right"></i>',
            )
        );
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
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                    if ( 0 != $args['avatar_size'] ) {
                        echo get_avatar( $comment, $args['avatar_size'] );
                    }
                    ?>
                    <?php
                    printf(
                        '<cite class="fn">%s</cite>',
                        get_comment_author_link()
                    );
                    ?>
                </div><!-- .comment-author -->

                <div class="comment-metadata">
                    <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            printf(
                                /* translators: 1: Comment date, 2: Comment time */
                                esc_html__( '%1$s at %2$s', 'aqualuxe' ),
                                esc_html( get_comment_date( '', $comment ) ),
                                esc_html( get_comment_time() )
                            );
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
                </div><!-- .comment-metadata -->

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
                <?php endif; ?>
            </div><!-- .comment-meta -->

            <div class="comment-content">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <?php
            comment_reply_link(
                array_merge(
                    $args,
                    array(
                        'add_below' => 'div-comment',
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'before'    => '<div class="reply">',
                        'after'     => '</div>',
                    )
                )
            );
            ?>
        </article><!-- .comment-body -->
    <?php
}

/**
 * Get post thumbnail with fallback
 *
 * @param string $size Thumbnail size.
 * @return string
 */
function aqualuxe_get_post_thumbnail( $size = 'post-thumbnail' ) {
    if ( has_post_thumbnail() ) {
        return get_the_post_thumbnail( null, $size );
    } else {
        // Return a default image if no thumbnail is set
        return '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/default-thumbnail.jpg' ) . '" alt="' . esc_attr( get_the_title() ) . '" />';
    }
}

/**
 * Get post categories
 *
 * @return string
 */
function aqualuxe_get_post_categories() {
    $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
    if ( $categories_list ) {
        return sprintf(
            '<span class="cat-links">%s</span>',
            $categories_list
        );
    }
    return '';
}

/**
 * Get post tags
 *
 * @return string
 */
function aqualuxe_get_post_tags() {
    $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
    if ( $tags_list ) {
        return sprintf(
            '<span class="tags-links">%s</span>',
            $tags_list
        );
    }
    return '';
}

/**
 * Get post author
 *
 * @return string
 */
function aqualuxe_get_post_author() {
    return sprintf(
        '<span class="author vcard"><a class="url fn n" href="%s">%s</a></span>',
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_html( get_the_author() )
    );
}

/**
 * Get post date
 *
 * @return string
 */
function aqualuxe_get_post_date() {
    return sprintf(
        '<span class="posted-on"><time class="entry-date published updated" datetime="%s">%s</time></span>',
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() )
    );
}

/**
 * Get post comments
 *
 * @return string
 */
function aqualuxe_get_post_comments() {
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        return sprintf(
            '<span class="comments-link">%s</span>',
            sprintf(
                /* translators: %s: post title */
                comments_popup_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: post title */
                            __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    )
                )
            )
        );
    }
    return '';
}

/**
 * Get post edit link
 *
 * @return string
 */
function aqualuxe_get_post_edit_link() {
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            get_the_title()
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Get post meta
 *
 * @return string
 */
function aqualuxe_get_post_meta() {
    $post_meta = '';
    
    // Author
    $post_meta .= aqualuxe_get_post_author();
    
    // Date
    $post_meta .= aqualuxe_get_post_date();
    
    // Categories
    $post_meta .= aqualuxe_get_post_categories();
    
    // Comments
    $post_meta .= aqualuxe_get_post_comments();
    
    // Edit link
    $post_meta .= aqualuxe_get_post_edit_link();
    
    return $post_meta;
}

/**
 * Get post excerpt
 *
 * @param int $length Excerpt length.
 * @return string
 */
function aqualuxe_get_post_excerpt( $length = 55 ) {
    $excerpt = get_the_excerpt();
    
    if ( ! $excerpt ) {
        $excerpt = get_the_content();
        $excerpt = strip_shortcodes( $excerpt );
        $excerpt = excerpt_remove_blocks( $excerpt );
        $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
        $excerpt = wp_trim_words( $excerpt, $length, '&hellip;' );
    }
    
    return $excerpt;
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID.
 * @param int $number_posts Number of posts to get.
 * @return WP_Query
 */
function aqualuxe_get_related_posts( $post_id, $number_posts = 3 ) {
    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => $number_posts,
        'post__not_in'   => array( $post_id ),
        'orderby'        => 'rand',
    );
    
    // Get categories
    $categories = get_the_category( $post_id );
    
    if ( $categories ) {
        $category_ids = array();
        
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
        
        $args['category__in'] = $category_ids;
    }
    
    return new WP_Query( $args );
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    if ( ! get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) {
        return '';
    }
    
    $breadcrumbs = '';
    $home_text = esc_html__( 'Home', 'aqualuxe' );
    
    $breadcrumbs .= '<div class="breadcrumbs">';
    $breadcrumbs .= '<a href="' . esc_url( home_url( '/' ) ) . '">' . $home_text . '</a>';
    
    if ( is_category() || is_single() ) {
        $breadcrumbs .= '<span class="separator">/</span>';
        
        if ( is_category() ) {
            $breadcrumbs .= single_cat_title( '', false );
        } elseif ( is_single() ) {
            $categories = get_the_category();
            
            if ( $categories ) {
                $breadcrumbs .= '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                $breadcrumbs .= '<span class="separator">/</span>';
            }
            
            $breadcrumbs .= get_the_title();
        }
    } elseif ( is_page() ) {
        $breadcrumbs .= '<span class="separator">/</span>';
        $breadcrumbs .= get_the_title();
    } elseif ( is_search() ) {
        $breadcrumbs .= '<span class="separator">/</span>';
        $breadcrumbs .= esc_html__( 'Search Results for: ', 'aqualuxe' ) . get_search_query();
    } elseif ( is_404() ) {
        $breadcrumbs .= '<span class="separator">/</span>';
        $breadcrumbs .= esc_html__( '404 Error', 'aqualuxe' );
    } elseif ( is_archive() ) {
        $breadcrumbs .= '<span class="separator">/</span>';
        $breadcrumbs .= get_the_archive_title();
    }
    
    $breadcrumbs .= '</div>';
    
    return $breadcrumbs;
}

/**
 * Get social links
 *
 * @return string
 */
function aqualuxe_get_social_links() {
    $social_links = array(
        'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
        'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
        'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
        'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
        'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
        'pinterest' => get_theme_mod( 'aqualuxe_pinterest_url', '' ),
    );
    
    $output = '';
    
    if ( array_filter( $social_links ) ) {
        $output .= '<div class="social-links">';
        
        foreach ( $social_links as $network => $url ) {
            if ( $url ) {
                $output .= sprintf(
                    '<a href="%s" class="social-link %s" target="_blank" rel="noopener noreferrer"><i class="fa fa-%s"></i><span class="screen-reader-text">%s</span></a>',
                    esc_url( $url ),
                    esc_attr( $network ),
                    esc_attr( $network ),
                    esc_html( ucfirst( $network ) )
                );
            }
        }
        
        $output .= '</div>';
    }
    
    return $output;
}

/**
 * Get copyright text
 *
 * @return string
 */
function aqualuxe_get_copyright_text() {
    $copyright_text = get_theme_mod( 'aqualuxe_footer_copyright_text', '' );
    
    if ( ! $copyright_text ) {
        $copyright_text = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'aqualuxe' ),
            date_i18n( 'Y' ),
            get_bloginfo( 'name' )
        );
    }
    
    return $copyright_text;
}

/**
 * Get footer widgets
 *
 * @return string
 */
function aqualuxe_get_footer_widgets() {
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    $footer_columns = get_theme_mod( 'aqualuxe_footer_columns', 4 );
    
    $output = '';
    
    if ( $footer_columns > 0 ) {
        $output .= '<div class="footer-widgets">';
        $output .= '<div class="container">';
        $output .= '<div class="row">';
        
        $column_class = 'col-md-' . ( 12 / $footer_columns );
        
        for ( $i = 1; $i <= $footer_columns; $i++ ) {
            $output .= '<div class="' . esc_attr( $column_class ) . '">';
            
            if ( is_active_sidebar( 'footer-' . $i ) ) {
                ob_start();
                dynamic_sidebar( 'footer-' . $i );
                $output .= ob_get_clean();
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    return $output;
}

/**
 * Get header layout
 *
 * @return string
 */
function aqualuxe_get_header_layout() {
    $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
    
    return $header_layout;
}

/**
 * Get footer layout
 *
 * @return string
 */
function aqualuxe_get_footer_layout() {
    $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
    
    return $footer_layout;
}

/**
 * Get sidebar position
 *
 * @return string
 */
function aqualuxe_get_sidebar_position() {
    $sidebar_position = get_theme_mod( 'aqualuxe_content_layout', 'right-sidebar' );
    
    return $sidebar_position;
}

/**
 * Check if sidebar is active
 *
 * @return bool
 */
function aqualuxe_is_sidebar_active() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    
    return ( $sidebar_position !== 'no-sidebar' ) && is_active_sidebar( 'sidebar-1' );
}

/**
 * Get content class
 *
 * @return string
 */
function aqualuxe_get_content_class() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    $has_sidebar = aqualuxe_is_sidebar_active();
    $content_class = $has_sidebar ? 'col-lg-8' : 'col-lg-12';
    
    // Add 'order-first' class if sidebar is on the right
    if ( $has_sidebar && $sidebar_position === 'left-sidebar' ) {
        $content_class .= ' order-lg-2';
    }
    
    return $content_class;
}

/**
 * Get sidebar class
 *
 * @return string
 */
function aqualuxe_get_sidebar_class() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    $sidebar_class = 'col-lg-4';
    
    if ( $sidebar_position === 'left-sidebar' ) {
        $sidebar_class .= ' order-lg-1';
    }
    
    return $sidebar_class;
}

/**
 * Get container class
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    $container_class = 'container';
    
    if ( get_theme_mod( 'aqualuxe_fluid_container', false ) ) {
        $container_class = 'container-fluid';
    }
    
    return $container_class;
}

/**
 * Get logo
 *
 * @return string
 */
function aqualuxe_get_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = '';
    
    if ( $custom_logo_id ) {
        $logo = wp_get_attachment_image( $custom_logo_id, 'full', false, array(
            'class'    => 'custom-logo',
            'itemprop' => 'logo',
        ) );
    }
    
    if ( ! $logo ) {
        $logo = '<h1 class="site-title">' . get_bloginfo( 'name' ) . '</h1>';
        
        $description = get_bloginfo( 'description', 'display' );
        if ( $description || is_customize_preview() ) {
            $logo .= '<p class="site-description">' . $description . '</p>';
        }
    }
    
    return $logo;
}

/**
 * Get primary menu
 *
 * @return string
 */
function aqualuxe_get_primary_menu() {
    ob_start();
    
    wp_nav_menu(
        array(
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'menu_class'     => 'primary-menu',
            'container'      => 'nav',
            'container_class' => 'main-navigation',
            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
            'walker'         => new AquaLuxe_Walker_Nav_Menu(),
        )
    );
    
    return ob_get_clean();
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    echo '<nav class="main-navigation"><ul class="primary-menu">';
    echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Create a menu', 'aqualuxe' ) . '</a></li>';
    echo '</ul></nav>';
}

/**
 * Get secondary menu
 *
 * @return string
 */
function aqualuxe_get_secondary_menu() {
    ob_start();
    
    wp_nav_menu(
        array(
            'theme_location' => 'secondary',
            'menu_id'        => 'secondary-menu',
            'menu_class'     => 'secondary-menu',
            'container'      => 'nav',
            'container_class' => 'secondary-navigation',
            'fallback_cb'    => false,
            'depth'          => 1,
        )
    );
    
    return ob_get_clean();
}

/**
 * Get footer menu
 *
 * @return string
 */
function aqualuxe_get_footer_menu() {
    ob_start();
    
    wp_nav_menu(
        array(
            'theme_location' => 'footer',
            'menu_id'        => 'footer-menu',
            'menu_class'     => 'footer-menu',
            'container'      => 'nav',
            'container_class' => 'footer-navigation',
            'fallback_cb'    => false,
            'depth'          => 1,
        )
    );
    
    return ob_get_clean();
}

/**
 * Get header icons
 *
 * @return string
 */
function aqualuxe_get_header_icons() {
    ob_start();
    
    do_action( 'aqualuxe_header_icons' );
    
    return ob_get_clean();
}

/**
 * Get mobile menu toggle
 *
 * @return string
 */
function aqualuxe_get_mobile_menu_toggle() {
    return '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'Menu', 'aqualuxe' ) . '</span><span class="menu-toggle-icon"><span></span></span></button>';
}

/**
 * Get search form
 *
 * @return string
 */
function aqualuxe_get_search_form() {
    ob_start();
    
    get_search_form();
    
    return ob_get_clean();
}

/**
 * Get search toggle
 *
 * @return string
 */
function aqualuxe_get_search_toggle() {
    return '<button class="search-toggle"><i class="fa fa-search"></i><span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span></button>';
}

/**
 * Get page title
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    $title = '';
    
    if ( is_home() ) {
        if ( get_option( 'page_for_posts' ) ) {
            $title = get_the_title( get_option( 'page_for_posts' ) );
        } else {
            $title = esc_html__( 'Blog', 'aqualuxe' );
        }
    } elseif ( is_archive() ) {
        $title = get_the_archive_title();
    } elseif ( is_search() ) {
        $title = sprintf(
            /* translators: %s: search query */
            esc_html__( 'Search Results for: %s', 'aqualuxe' ),
            '<span>' . get_search_query() . '</span>'
        );
    } elseif ( is_404() ) {
        $title = esc_html__( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' );
    } elseif ( is_singular() ) {
        $title = get_the_title();
    }
    
    return $title;
}

/**
 * Get page subtitle
 *
 * @return string
 */
function aqualuxe_get_page_subtitle() {
    $subtitle = '';
    
    if ( is_archive() ) {
        $subtitle = get_the_archive_description();
    } elseif ( is_singular() ) {
        $subtitle = get_post_meta( get_the_ID(), '_aqualuxe_page_subtitle', true );
    }
    
    return $subtitle;
}

/**
 * Get page header
 *
 * @return string
 */
function aqualuxe_get_page_header() {
    if ( ! get_theme_mod( 'aqualuxe_page_header', true ) ) {
        return '';
    }
    
    $title = aqualuxe_get_page_title();
    $subtitle = aqualuxe_get_page_subtitle();
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    $output = '';
    
    if ( $title || $subtitle || $breadcrumbs ) {
        $output .= '<div class="page-header">';
        $output .= '<div class="container">';
        
        if ( $breadcrumbs ) {
            $output .= $breadcrumbs;
        }
        
        if ( $title ) {
            $output .= '<h1 class="page-title">' . $title . '</h1>';
        }
        
        if ( $subtitle ) {
            $output .= '<div class="page-subtitle">' . $subtitle . '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
    }
    
    return $output;
}

/**
 * Get back to top button
 *
 * @return string
 */
function aqualuxe_get_back_to_top() {
    if ( ! get_theme_mod( 'aqualuxe_back_to_top', true ) ) {
        return '';
    }
    
    return '<a href="#" class="back-to-top"><i class="fa fa-angle-up"></i><span class="screen-reader-text">' . esc_html__( 'Back to top', 'aqualuxe' ) . '</span></a>';
}