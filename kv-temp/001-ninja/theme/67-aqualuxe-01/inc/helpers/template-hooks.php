<?php
/**
 * Template Hooks
 *
 * @package AquaLuxe
 * @subpackage Helpers
 * @since 1.0.0
 */

/**
 * Header
 *
 * @see aqualuxe_header_before()
 * @see aqualuxe_header_top()
 * @see aqualuxe_header_main()
 * @see aqualuxe_header_bottom()
 * @see aqualuxe_header_after()
 */
function aqualuxe_header() {
    do_action( 'aqualuxe_header' );
}

/**
 * Footer
 *
 * @see aqualuxe_footer_before()
 * @see aqualuxe_footer_widgets()
 * @see aqualuxe_footer_main()
 * @see aqualuxe_footer_bottom()
 * @see aqualuxe_footer_after()
 */
function aqualuxe_footer() {
    do_action( 'aqualuxe_footer' );
}

/**
 * Content before
 */
function aqualuxe_content_before() {
    do_action( 'aqualuxe_content_before' );
}

/**
 * Content after
 */
function aqualuxe_content_after() {
    do_action( 'aqualuxe_content_after' );
}

/**
 * Page before
 */
function aqualuxe_page_before() {
    do_action( 'aqualuxe_page_before' );
}

/**
 * Page after
 */
function aqualuxe_page_after() {
    do_action( 'aqualuxe_page_after' );
}

/**
 * Post before
 */
function aqualuxe_post_before() {
    do_action( 'aqualuxe_post_before' );
}

/**
 * Post after
 */
function aqualuxe_post_after() {
    do_action( 'aqualuxe_post_after' );
}

/**
 * Post content before
 */
function aqualuxe_post_content_before() {
    do_action( 'aqualuxe_post_content_before' );
}

/**
 * Post content after
 */
function aqualuxe_post_content_after() {
    do_action( 'aqualuxe_post_content_after' );
}

/**
 * Sidebar before
 */
function aqualuxe_sidebar_before() {
    do_action( 'aqualuxe_sidebar_before' );
}

/**
 * Sidebar after
 */
function aqualuxe_sidebar_after() {
    do_action( 'aqualuxe_sidebar_after' );
}

/**
 * Comments before
 */
function aqualuxe_comments_before() {
    do_action( 'aqualuxe_comments_before' );
}

/**
 * Comments after
 */
function aqualuxe_comments_after() {
    do_action( 'aqualuxe_comments_after' );
}

/**
 * Archive before
 */
function aqualuxe_archive_before() {
    do_action( 'aqualuxe_archive_before' );
}

/**
 * Archive after
 */
function aqualuxe_archive_after() {
    do_action( 'aqualuxe_archive_after' );
}

/**
 * Search before
 */
function aqualuxe_search_before() {
    do_action( 'aqualuxe_search_before' );
}

/**
 * Search after
 */
function aqualuxe_search_after() {
    do_action( 'aqualuxe_search_after' );
}

/**
 * 404 before
 */
function aqualuxe_404_before() {
    do_action( 'aqualuxe_404_before' );
}

/**
 * 404 after
 */
function aqualuxe_404_after() {
    do_action( 'aqualuxe_404_after' );
}

/**
 * Homepage
 *
 * @see aqualuxe_homepage_hero()
 * @see aqualuxe_homepage_featured_products()
 * @see aqualuxe_homepage_services()
 * @see aqualuxe_homepage_testimonials()
 * @see aqualuxe_homepage_blog()
 * @see aqualuxe_homepage_newsletter()
 */
function aqualuxe_homepage() {
    do_action( 'aqualuxe_homepage' );
}

/**
 * About page
 *
 * @see aqualuxe_about_hero()
 * @see aqualuxe_about_intro()
 * @see aqualuxe_about_team()
 * @see aqualuxe_about_values()
 * @see aqualuxe_about_testimonials()
 */
function aqualuxe_about_page() {
    do_action( 'aqualuxe_about_page' );
}

/**
 * Services page
 *
 * @see aqualuxe_services_hero()
 * @see aqualuxe_services_intro()
 * @see aqualuxe_services_list()
 * @see aqualuxe_services_cta()
 */
function aqualuxe_services_page() {
    do_action( 'aqualuxe_services_page' );
}

/**
 * Contact page
 *
 * @see aqualuxe_contact_hero()
 * @see aqualuxe_contact_info()
 * @see aqualuxe_contact_form()
 * @see aqualuxe_contact_map()
 */
function aqualuxe_contact_page() {
    do_action( 'aqualuxe_contact_page' );
}

/**
 * FAQ page
 *
 * @see aqualuxe_faq_hero()
 * @see aqualuxe_faq_intro()
 * @see aqualuxe_faq_list()
 * @see aqualuxe_faq_cta()
 */
function aqualuxe_faq_page() {
    do_action( 'aqualuxe_faq_page' );
}

/**
 * Display site logo
 */
function aqualuxe_site_logo() {
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
    
    if ( has_custom_logo() ) {
        echo '<div class="site-logo">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
        echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="' . esc_attr( $logo[1] ) . '" height="' . esc_attr( $logo[2] ) . '">';
        echo '</a>';
        echo '</div>';
    } else {
        echo '<div class="site-title">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
        echo '</div>';
        
        $description = get_bloginfo( 'description', 'display' );
        if ( $description || is_customize_preview() ) {
            echo '<div class="site-description">' . esc_html( $description ) . '</div>';
        }
    }
}

/**
 * Display primary navigation
 */
function aqualuxe_primary_navigation() {
    if ( has_nav_menu( 'primary' ) ) {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'aqualuxe' ); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
                <span class="menu-toggle-text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
            </button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'primary-menu',
                )
            );
            ?>
        </nav><!-- #site-navigation -->
        <?php
    }
}

/**
 * Display secondary navigation
 */
function aqualuxe_secondary_navigation() {
    if ( has_nav_menu( 'secondary' ) ) {
        ?>
        <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'aqualuxe' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'secondary',
                    'menu_id'        => 'secondary-menu',
                    'container'      => false,
                    'menu_class'     => 'secondary-menu',
                    'depth'          => 1,
                )
            );
            ?>
        </nav><!-- .secondary-navigation -->
        <?php
    }
}

/**
 * Display footer navigation
 */
function aqualuxe_footer_navigation() {
    if ( has_nav_menu( 'footer' ) ) {
        ?>
        <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'aqualuxe' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                )
            );
            ?>
        </nav><!-- .footer-navigation -->
        <?php
    }
}

/**
 * Display mobile navigation
 */
function aqualuxe_mobile_navigation() {
    if ( has_nav_menu( 'mobile' ) ) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'aqualuxe' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                )
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    } elseif ( has_nav_menu( 'primary' ) ) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'aqualuxe' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                )
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    }
}

/**
 * Display social links
 */
function aqualuxe_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if ( ! empty( $social_links ) ) {
        echo '<div class="social-links">';
        echo '<ul class="social-links-list">';
        
        foreach ( $social_links as $network => $data ) {
            echo '<li class="social-link-item social-link-' . esc_attr( $network ) . '">';
            echo '<a href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer">';
            echo $data['icon'];
            echo '<span class="screen-reader-text">' . esc_html( $data['label'] ) . '</span>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if ( ! get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) {
        return;
    }
    
    // Check if we're on a WooCommerce page
    if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
        return;
    }
    
    // Get the current page
    $current_page = '';
    
    if ( is_home() || is_front_page() ) {
        return;
    } elseif ( is_category() ) {
        $current_page = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $current_page = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $current_page = get_the_author();
    } elseif ( is_year() ) {
        $current_page = get_the_date( 'Y' );
    } elseif ( is_month() ) {
        $current_page = get_the_date( 'F Y' );
    } elseif ( is_day() ) {
        $current_page = get_the_date();
    } elseif ( is_search() ) {
        $current_page = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
    } elseif ( is_404() ) {
        $current_page = esc_html__( 'Page Not Found', 'aqualuxe' );
    } else {
        $current_page = get_the_title();
    }
    
    // Output breadcrumbs
    echo '<div class="breadcrumbs">';
    echo '<div class="' . esc_attr( aqualuxe_get_container_class() ) . '">';
    echo '<ul class="breadcrumbs-list">';
    
    // Home link
    echo '<li class="breadcrumbs-item">';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    // Current page
    echo '<li class="breadcrumbs-item current">';
    echo esc_html( $current_page );
    echo '</li>';
    
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display page title
 */
function aqualuxe_page_title() {
    // Check if page title is enabled
    if ( ! get_theme_mod( 'aqualuxe_page_title', true ) ) {
        return;
    }
    
    // Check if we're on a WooCommerce page
    if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
        return;
    }
    
    // Get the current title
    $title = '';
    $subtitle = '';
    
    if ( is_home() && ! is_front_page() ) {
        $title = get_theme_mod( 'aqualuxe_blog_title', esc_html__( 'Blog', 'aqualuxe' ) );
        $subtitle = get_theme_mod( 'aqualuxe_blog_subtitle', esc_html__( 'Latest News & Articles', 'aqualuxe' ) );
    } elseif ( is_archive() ) {
        $title = get_the_archive_title();
        $subtitle = get_the_archive_description();
    } elseif ( is_search() ) {
        $title = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
    } elseif ( is_404() ) {
        $title = esc_html__( 'Page Not Found', 'aqualuxe' );
        $subtitle = esc_html__( 'The page you are looking for does not exist.', 'aqualuxe' );
    } elseif ( is_page() ) {
        $title = get_the_title();
        $subtitle = get_post_meta( get_the_ID(), '_aqualuxe_page_subtitle', true );
    }
    
    // Output page title
    if ( ! empty( $title ) ) {
        echo '<div class="page-title">';
        echo '<div class="' . esc_attr( aqualuxe_get_container_class() ) . '">';
        echo '<h1 class="page-title-text">' . wp_kses_post( $title ) . '</h1>';
        
        if ( ! empty( $subtitle ) ) {
            echo '<div class="page-subtitle">' . wp_kses_post( $subtitle ) . '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display post meta
 */
function aqualuxe_post_meta() {
    // Check if post meta is enabled
    if ( ! get_theme_mod( 'aqualuxe_blog_meta', true ) ) {
        return;
    }
    
    echo '<div class="post-meta">';
    
    // Author
    if ( get_theme_mod( 'aqualuxe_blog_author', true ) ) {
        echo '<span class="post-author">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
        echo '<span class="post-author-text">';
        echo esc_html__( 'By ', 'aqualuxe' );
        echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
        echo '</span>';
        echo '</span>';
    }
    
    // Date
    if ( get_theme_mod( 'aqualuxe_blog_date', true ) ) {
        echo '<span class="post-date">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>';
        echo '<span class="post-date-text">';
        echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_date() ) . '</a>';
        echo '</span>';
        echo '</span>';
    }
    
    // Categories
    if ( get_theme_mod( 'aqualuxe_blog_categories', true ) && has_category() ) {
        echo '<span class="post-categories">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
        echo '<span class="post-categories-text">';
        echo get_the_category_list( ', ' );
        echo '</span>';
        echo '</span>';
    }
    
    // Comments
    if ( get_theme_mod( 'aqualuxe_blog_comments', true ) && comments_open() ) {
        echo '<span class="post-comments">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
        echo '<span class="post-comments-text">';
        comments_popup_link(
            esc_html__( 'No Comments', 'aqualuxe' ),
            esc_html__( '1 Comment', 'aqualuxe' ),
            esc_html__( '% Comments', 'aqualuxe' )
        );
        echo '</span>';
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    // Check if post tags are enabled
    if ( ! get_theme_mod( 'aqualuxe_blog_tags', true ) ) {
        return;
    }
    
    // Check if post has tags
    if ( has_tag() ) {
        echo '<div class="post-tags">';
        echo '<span class="post-tags-title">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
        echo get_the_tag_list( '<ul class="post-tags-list"><li>', '</li><li>', '</li></ul>' );
        echo '</div>';
    }
}

/**
 * Display post thumbnail
 */
function aqualuxe_post_thumbnail() {
    // Check if post thumbnail is enabled
    if ( ! get_theme_mod( 'aqualuxe_blog_featured_image', true ) ) {
        return;
    }
    
    // Check if post has thumbnail
    if ( has_post_thumbnail() ) {
        echo '<div class="post-thumbnail">';
        echo '<a href="' . esc_url( get_permalink() ) . '">';
        
        if ( is_singular() ) {
            the_post_thumbnail( 'full' );
        } else {
            the_post_thumbnail( 'aqualuxe-blog-thumb' );
        }
        
        echo '</a>';
        echo '</div>';
    }
}

/**
 * Display post excerpt
 */
function aqualuxe_post_excerpt() {
    // Check if post excerpt is enabled
    if ( ! get_theme_mod( 'aqualuxe_blog_excerpt', true ) ) {
        return;
    }
    
    echo '<div class="post-excerpt">';
    the_excerpt();
    echo '</div>';
}

/**
 * Display read more link
 */
function aqualuxe_read_more_link() {
    // Check if read more link is enabled
    if ( ! get_theme_mod( 'aqualuxe_blog_read_more', true ) ) {
        return;
    }
    
    echo '<div class="read-more">';
    echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more-link">';
    echo esc_html( aqualuxe_get_read_more_text() );
    echo '</a>';
    echo '</div>';
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    // Check if post navigation is enabled
    if ( ! get_theme_mod( 'aqualuxe_post_navigation', true ) ) {
        return;
    }
    
    // Get previous and next post
    $previous = get_previous_post();
    $next = get_next_post();
    
    // Output post navigation
    if ( $previous || $next ) {
        echo '<nav class="post-navigation">';
        echo '<h2 class="screen-reader-text">' . esc_html__( 'Post Navigation', 'aqualuxe' ) . '</h2>';
        echo '<div class="post-navigation-links">';
        
        if ( $previous ) {
            echo '<div class="post-navigation-previous">';
            echo '<a href="' . esc_url( get_permalink( $previous ) ) . '" rel="prev">';
            echo '<span class="post-navigation-arrow">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
            echo '</span>';
            echo '<span class="post-navigation-text">';
            echo '<span class="post-navigation-label">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span>';
            echo '<span class="post-navigation-title">' . esc_html( get_the_title( $previous ) ) . '</span>';
            echo '</span>';
            echo '</a>';
            echo '</div>';
        }
        
        if ( $next ) {
            echo '<div class="post-navigation-next">';
            echo '<a href="' . esc_url( get_permalink( $next ) ) . '" rel="next">';
            echo '<span class="post-navigation-text">';
            echo '<span class="post-navigation-label">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span>';
            echo '<span class="post-navigation-title">' . esc_html( get_the_title( $next ) ) . '</span>';
            echo '</span>';
            echo '<span class="post-navigation-arrow">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
            echo '</span>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</nav>';
    }
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    // Check if related posts are enabled
    if ( ! get_theme_mod( 'aqualuxe_related_posts', true ) ) {
        return;
    }
    
    // Get current post ID
    $post_id = get_the_ID();
    
    // Get current post categories
    $categories = get_the_category( $post_id );
    
    if ( $categories ) {
        $category_ids = array();
        
        foreach ( $categories as $category ) {
            $category_ids[] = $category->term_id;
        }
        
        // Query related posts
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => array( $post_id ),
            'category__in'   => $category_ids,
        );
        
        $related_posts = new WP_Query( $args );
        
        if ( $related_posts->have_posts() ) {
            echo '<div class="related-posts">';
            echo '<h3 class="related-posts-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h3>';
            echo '<div class="related-posts-list">';
            
            while ( $related_posts->have_posts() ) {
                $related_posts->the_post();
                
                echo '<div class="related-post">';
                
                if ( has_post_thumbnail() ) {
                    echo '<div class="related-post-thumbnail">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    the_post_thumbnail( 'aqualuxe-blog-thumb' );
                    echo '</a>';
                    echo '</div>';
                }
                
                echo '<div class="related-post-content">';
                echo '<h4 class="related-post-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
                echo '<div class="related-post-meta">';
                echo '<span class="related-post-date">' . esc_html( get_the_date() ) . '</span>';
                echo '</div>';
                echo '</div>';
                
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
            
            wp_reset_postdata();
        }
    }
}

/**
 * Display author bio
 */
function aqualuxe_author_bio() {
    // Check if author bio is enabled
    if ( ! get_theme_mod( 'aqualuxe_author_bio', true ) ) {
        return;
    }
    
    // Get author data
    $author_id = get_the_author_meta( 'ID' );
    $author_name = get_the_author_meta( 'display_name' );
    $author_description = get_the_author_meta( 'description' );
    $author_url = get_author_posts_url( $author_id );
    
    if ( ! empty( $author_description ) ) {
        echo '<div class="author-bio">';
        echo '<div class="author-bio-avatar">';
        echo get_avatar( $author_id, 100 );
        echo '</div>';
        echo '<div class="author-bio-content">';
        echo '<h3 class="author-bio-name">';
        echo '<a href="' . esc_url( $author_url ) . '">' . esc_html( $author_name ) . '</a>';
        echo '</h3>';
        echo '<div class="author-bio-description">' . wpautop( $author_description ) . '</div>';
        echo '<a href="' . esc_url( $author_url ) . '" class="author-bio-link">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( $author_name ) . '</a>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display comments
 */
function aqualuxe_comments() {
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
}

/**
 * Display footer widgets
 */
function aqualuxe_footer_widgets() {
    // Check if footer widgets are enabled
    if ( ! get_theme_mod( 'aqualuxe_footer_widgets', true ) ) {
        return;
    }
    
    // Get footer widgets columns
    $columns = get_theme_mod( 'aqualuxe_footer_widgets_columns', '4' );
    
    // Check if any footer widget area is active
    if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) || is_active_sidebar( 'footer-4' ) ) {
        echo '<div class="footer-widgets">';
        echo '<div class="' . esc_attr( aqualuxe_get_container_class() ) . '">';
        echo '<div class="footer-widgets-inner columns-' . esc_attr( $columns ) . '">';
        
        for ( $i = 1; $i <= $columns; $i++ ) {
            if ( is_active_sidebar( 'footer-' . $i ) ) {
                echo '<div class="footer-widget footer-widget-' . esc_attr( $i ) . '">';
                dynamic_sidebar( 'footer-' . $i );
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display footer copyright
 */
function aqualuxe_footer_copyright() {
    // Get footer copyright
    $copyright = get_theme_mod( 'aqualuxe_footer_copyright', sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );
    
    if ( ! empty( $copyright ) ) {
        echo '<div class="footer-copyright">';
        echo wp_kses_post( $copyright );
        echo '</div>';
    }
}

/**
 * Display footer payment icons
 */
function aqualuxe_footer_payment_icons() {
    // Check if footer payment icons are enabled
    if ( ! get_theme_mod( 'aqualuxe_footer_payment', true ) ) {
        return;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    echo '<div class="footer-payment-icons">';
    echo '<span class="payment-icons-title">' . esc_html__( 'We Accept', 'aqualuxe' ) . '</span>';
    echo '<ul class="payment-icons-list">';
    
    // Visa
    echo '<li class="payment-icon payment-icon-visa">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><path d="M14.5,7.5h-3l-3,9h3l3-9Z" fill="#00579f"/><path d="M11.5,7.5l-3,9h-2c-1,0-1-1,0-2l2-7h3Z" fill="#00579f"/><path d="M19.5,13.5c0-3-4-2-4-3s1-1,2,0l1-2c-1-1-2-1-3-1-2,0-3,1-3,2,0,3,4,2,4,3,0,0-1,1-2,0l-1,2c1,1,2,1,3,1,2,0,3-1,3-2Z" fill="#00579f"/><path d="M20.5,16.5h3l1-6h2l0-2h-2v-1c0-1,1-1,2-1l0-2c-1,0-2,0-3,1-1,0-1,1-1,2v1h-1l0,2h1l-1,6Z" fill="#00579f"/><path d="M28.5,10.5h-2l-1,6h2l1-6Z" fill="#00579f"/><path d="M28.5,7.5h-2l0,2h2l0-2Z" fill="#00579f"/></svg>';
    echo '</li>';
    
    // Mastercard
    echo '<li class="payment-icon payment-icon-mastercard">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><circle cx="16" cy="12" r="6" fill="#eb001b" opacity="0.8"/><circle cx="24" cy="12" r="6" fill="#f79e1b" opacity="0.8"/><path d="M20,7a6,6,0,0,0,0,10,6,6,0,0,0,0-10Z" fill="#ff5f00" opacity="0.5"/></svg>';
    echo '</li>';
    
    // American Express
    echo '<li class="payment-icon payment-icon-amex">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><path d="M36,12c0,2-1,3-3,3H7c-2,0-3-1-3-3V7H36v5Z" fill="#1f72cd" opacity="0.8"/><path d="M4,12c0,2,1,3,3,3H36v2c0,1-1,2-2,2H6c-1,0-2-1-2-2V12Z" fill="#1f72cd" opacity="0.8"/><path d="M10,9h2l1,1,1-1h6v1l0-1c2,0,2,1,2,1v1l1-2h2l2,2V9h2l1,2,1-2h2l-3,3,3,3h-2l-1-2-1,2h-2v-2l-1,2h-2l-2-2v2h-2c-2,0-2-1-2-1v1h-6l-1-1-1,1h-2l3-3-3-3Z" fill="#fff"/></svg>';
    echo '</li>';
    
    // PayPal
    echo '<li class="payment-icon payment-icon-paypal">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><path d="M29,8c0,2-1,4-4,4h-2l-1,4h-2l2-8h3c1,0,1,0,1,1v1c0-1,1-2,2-2h1Z" fill="#253b80"/><path d="M20,8l-2,8h-2l2-8h2Z" fill="#253b80"/><path d="M33,8c0,2-1,4-4,4h-2l-1,4h-2l2-8h3c1,0,1,0,1,1v1c0-1,1-2,2-2h1Z" fill="#179bd7"/><path d="M24,8l-2,8h-2l2-8h2Z" fill="#179bd7"/></svg>';
    echo '</li>';
    
    // Apple Pay
    echo '<li class="payment-icon payment-icon-apple-pay">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><path d="M14,10a2,2,0,0,1,2-1,2,2,0,0,1-1,2,2,2,0,0,1-1-1Z"/><path d="M16,11c0,2,2,2,2,2,0,1-1,2-2,2s-1,0-2,0c-1,0-1,0-2,0-2,0-3-3-3-5s2-3,3-3,2,1,3,1,2-1,3-1a3,3,0,0,1,3,3c-2,0-3,1-3,3Z"/><path d="M25,16h-1l-1-3h-3l-1,3h-1l3-8h1l3,8Zm-2-3-1-3-1,3h2Z"/><path d="M31,16h-4V8h1v7h3v1Z"/><path d="M32,16V8h1v8h-1Z"/><path d="M35,16h-1V9h-2V8h5V9h-2v7Z"/></svg>';
    echo '</li>';
    
    // Google Pay
    echo '<li class="payment-icon payment-icon-google-pay">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="24" viewBox="0 0 40 24"><rect width="40" height="24" rx="4" fill="#f7f7f7"/><path d="M18,12a2,2,0,1,1-2-2,2,2,0,0,1,2,2Z" fill="#ea4335"/><path d="M24,12a2,2,0,1,1-2-2,2,2,0,0,1,2,2Z" fill="#4285f4"/><path d="M21,12a2,2,0,1,1-2-2,2,2,0,0,1,2,2Z" fill="#fbbc05"/><path d="M27,12a2,2,0,1,1-2-2,2,2,0,0,1,2,2Z" fill="#34a853"/><path d="M29,10h1v4h-1Z" fill="#5f6368"/><path d="M32,13l1-3h1l-2,5h-1l1-2-1-3h1l0,1,0,1,0-1,0-1h1l-1,3Z" fill="#5f6368"/></svg>';
    echo '</li>';
    
    echo '</ul>';
    echo '</div>';
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top() {
    // Check if back to top button is enabled
    if ( ! get_theme_mod( 'aqualuxe_back_to_top', true ) ) {
        return;
    }
    
    echo '<a href="#" id="back-to-top" class="back-to-top">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>';
    echo '<span class="screen-reader-text">' . esc_html__( 'Back to Top', 'aqualuxe' ) . '</span>';
    echo '</a>';
}

/**
 * Display preloader
 */
function aqualuxe_preloader() {
    // Check if preloader is enabled
    if ( ! get_theme_mod( 'aqualuxe_preloader', true ) ) {
        return;
    }
    
    echo '<div id="preloader" class="preloader">';
    echo '<div class="preloader-inner">';
    echo '<div class="preloader-icon">';
    echo '<span></span>';
    echo '<span></span>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    // Check if search form is enabled
    if ( ! get_theme_mod( 'aqualuxe_header_search', true ) ) {
        return;
    }
    
    echo '<div class="search-form-wrapper">';
    echo '<button class="search-toggle">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
    echo '<span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span>';
    echo '</button>';
    echo '<div class="search-form-dropdown">';
    get_search_form();
    echo '</div>';
    echo '</div>';
}

/**
 * Display account link
 */
function aqualuxe_account_link() {
    // Check if account link is enabled
    if ( ! get_theme_mod( 'aqualuxe_header_account', true ) ) {
        return;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    echo '<div class="account-link-wrapper">';
    echo '<a href="' . esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) . '" class="account-link">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
    echo '<span class="account-link-text">' . esc_html__( 'Account', 'aqualuxe' ) . '</span>';
    echo '</a>';
    echo '</div>';
}

/**
 * Display wishlist link
 */
function aqualuxe_wishlist_link() {
    // Check if wishlist link is enabled
    if ( ! get_theme_mod( 'aqualuxe_header_wishlist', true ) ) {
        return;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Get wishlist page URL
    $wishlist_page_id = get_option( 'aqualuxe_wishlist_page_id' );
    $wishlist_url = $wishlist_page_id ? get_permalink( $wishlist_page_id ) : '#';
    
    // Get wishlist count
    $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( stripslashes( $_COOKIE['aqualuxe_wishlist'] ), true ) : [];
    $wishlist_count = count( $wishlist );
    
    echo '<div class="wishlist-link-wrapper">';
    echo '<a href="' . esc_url( $wishlist_url ) . '" class="wishlist-link">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
    echo '<span class="wishlist-count">' . esc_html( $wishlist_count ) . '</span>';
    echo '<span class="wishlist-link-text">' . esc_html__( 'Wishlist', 'aqualuxe' ) . '</span>';
    echo '</a>';
    echo '</div>';
}

/**
 * Display cart link
 */
function aqualuxe_cart_link() {
    // Check if cart link is enabled
    if ( ! get_theme_mod( 'aqualuxe_header_cart', true ) ) {
        return;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    echo '<div class="cart-link-wrapper">';
    echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="cart-link">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
    echo '<span class="cart-count">' . esc_html( WC()->cart->get_cart_contents_count() ) . '</span>';
    echo '<span class="cart-link-text">' . esc_html__( 'Cart', 'aqualuxe' ) . '</span>';
    echo '</a>';
    echo '</div>';
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    // Check if language switcher is enabled
    if ( ! get_theme_mod( 'aqualuxe_language_switcher_header', true ) ) {
        return;
    }
    
    // Check if Polylang or WPML is active
    if ( function_exists( 'pll_the_languages' ) ) {
        echo '<div class="language-switcher">';
        pll_the_languages( array( 'dropdown' => 1 ) );
        echo '</div>';
    } elseif ( function_exists( 'icl_get_languages' ) ) {
        $languages = icl_get_languages( 'skip_missing=0' );
        
        if ( ! empty( $languages ) ) {
            echo '<div class="language-switcher">';
            echo '<div class="language-switcher-dropdown">';
            echo '<div class="language-switcher-current">';
            
            foreach ( $languages as $language ) {
                if ( $language['active'] ) {
                    echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" />';
                    echo '<span>' . esc_html( $language['native_name'] ) . '</span>';
                    break;
                }
            }
            
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
            echo '</div>';
            
            echo '<ul class="language-switcher-list">';
            
            foreach ( $languages as $language ) {
                echo '<li class="' . ( $language['active'] ? 'active' : '' ) . '">';
                echo '<a href="' . esc_url( $language['url'] ) . '">';
                echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['language_code'] ) . '" />';
                echo '<span>' . esc_html( $language['native_name'] ) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    }
}

/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    // Check if currency switcher is enabled
    if ( ! get_theme_mod( 'aqualuxe_currency_switcher_header', true ) ) {
        return;
    }
    
    // Check if WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }
    
    // Check if WooCommerce Multilingual is active
    if ( class_exists( 'woocommerce_wpml' ) ) {
        global $woocommerce_wpml;
        
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            echo '<div class="currency-switcher">';
            $woocommerce_wpml->multi_currency->currency_switcher();
            echo '</div>';
        }
    }
}

/**
 * Display dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode toggle is enabled
    if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
        return;
    }
    
    // Get toggle style
    $style = get_theme_mod( 'aqualuxe_dark_mode_toggle_style', 'icon' );
    
    // Get toggle position
    $position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'header' );
    
    // Check if we should display the toggle in the header
    if ( $position !== 'header' ) {
        return;
    }
    
    // Get current mode
    $is_dark_mode = isset( $_COOKIE['aqualuxe_dark_mode'] ) ? $_COOKIE['aqualuxe_dark_mode'] === 'true' : get_theme_mod( 'aqualuxe_dark_mode_default', false );
    
    // Toggle classes
    $toggle_classes = array(
        'dark-mode-toggle',
        'toggle-style-' . $style,
        $is_dark_mode ? 'dark-mode-active' : '',
    );
    
    echo '<div class="dark-mode-toggle-wrapper">';
    echo '<button id="dark-mode-toggle" class="' . esc_attr( implode( ' ', array_filter( $toggle_classes ) ) ) . '" aria-label="' . esc_attr( $is_dark_mode ? __( 'Switch to Light Mode', 'aqualuxe' ) : __( 'Switch to Dark Mode', 'aqualuxe' ) ) . '" aria-pressed="' . esc_attr( $is_dark_mode ? 'true' : 'false' ) . '" data-dark-text="' . esc_attr__( 'Dark Mode', 'aqualuxe' ) . '" data-light-text="' . esc_attr__( 'Light Mode', 'aqualuxe' ) . '">';
    
    if ( $style === 'icon' || $style === 'icon-text' ) {
        echo '<span class="toggle-icon light-icon" aria-hidden="true">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        echo '</span>';
        
        echo '<span class="toggle-icon dark-icon" aria-hidden="true">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
        echo '</span>';
    }
    
    if ( $style === 'text' || $style === 'icon-text' ) {
        echo '<span class="toggle-text">';
        echo $is_dark_mode ? esc_html__( 'Light Mode', 'aqualuxe' ) : esc_html__( 'Dark Mode', 'aqualuxe' );
        echo '</span>';
    }
    
    if ( $style === 'switch' ) {
        echo '<span class="toggle-switch" aria-hidden="true">';
        echo '<span class="toggle-track"></span>';
        echo '<span class="toggle-thumb"></span>';
        echo '</span>';
    }
    
    echo '</button>';
    echo '</div>';
}