<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ( 'post' === get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
        echo '<span class="comments-link">';
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
                wp_kses_post( get_the_title() )
            )
        );
        echo '</span>';
    }

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
            wp_kses_post( get_the_title() )
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function aqualuxe_post_thumbnail() {
    if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
        return;
    }

    if ( is_singular() ) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg shadow-lg' ) ); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'post-thumbnail',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'rounded-lg shadow hover:shadow-xl transition-shadow duration-300',
                )
            );
            ?>
        </a>
        <?php
    endif; // End is_singular().
}

/**
 * Prints the site logo with fallback to site title.
 */
function aqualuxe_site_logo() {
    $logo_html = '';
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    
    if ( $custom_logo_id ) {
        $logo_html = sprintf(
            '<a href="%1$s" class="custom-logo-link" rel="home">%2$s</a>',
            esc_url( home_url( '/' ) ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                'class' => 'custom-logo',
                'loading' => 'eager',
            ) )
        );
    } else {
        $logo_html = sprintf(
            '<a href="%1$s" class="site-title" rel="home">%2$s</a>',
            esc_url( home_url( '/' ) ),
            esc_html( get_bloginfo( 'name' ) )
        );
        
        $description = get_bloginfo( 'description', 'display' );
        if ( $description || is_customize_preview() ) {
            $logo_html .= sprintf(
                '<p class="site-description">%1$s</p>',
                $description
            );
        }
    }
    
    echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the site navigation.
 */
function aqualuxe_primary_navigation() {
    if ( has_nav_menu( 'primary' ) ) {
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'primary-menu',
                'container'      => 'nav',
                'container_class' => 'primary-navigation',
                'container_id'   => 'primary-navigation',
                'fallback_cb'    => false,
            )
        );
    } else {
        echo '<nav id="primary-navigation" class="primary-navigation">';
        echo '<ul id="primary-menu" class="primary-menu">';
        wp_list_pages(
            array(
                'match_menu_classes' => true,
                'title_li' => false,
            )
        );
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Displays the footer navigation.
 */
function aqualuxe_footer_navigation() {
    if ( has_nav_menu( 'footer' ) ) {
        wp_nav_menu(
            array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'menu_class'     => 'footer-menu',
                'container'      => 'nav',
                'container_class' => 'footer-navigation',
                'container_id'   => 'footer-navigation',
                'depth'          => 1,
                'fallback_cb'    => false,
            )
        );
    }
}

/**
 * Displays the social links navigation.
 */
function aqualuxe_social_navigation() {
    if ( has_nav_menu( 'social' ) ) {
        wp_nav_menu(
            array(
                'theme_location' => 'social',
                'menu_id'        => 'social-menu',
                'menu_class'     => 'social-menu',
                'container'      => 'nav',
                'container_class' => 'social-navigation',
                'container_id'   => 'social-navigation',
                'link_before'    => '<span class="screen-reader-text">',
                'link_after'     => '</span>',
                'depth'          => 1,
                'fallback_cb'    => false,
            )
        );
    }
}

/**
 * Displays the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    // Skip on the homepage
    if ( is_front_page() ) {
        return;
    }
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
    echo '<ol class="breadcrumb-list">';
    
    // Home link
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';
    echo '</li>';
    
    // Handle different page types
    if ( is_category() || is_single() ) {
        if ( is_single() ) {
            // Get categories
            $categories = get_the_category();
            if ( ! empty( $categories ) ) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
                echo '</li>';
            }
            
            // Current post
            echo '<li class="breadcrumb-item current">';
            echo esc_html( get_the_title() );
            echo '</li>';
        } else {
            // Category archive
            echo '<li class="breadcrumb-item current">';
            echo esc_html( single_cat_title( '', false ) );
            echo '</li>';
        }
    } elseif ( is_page() ) {
        // Check if the page has a parent
        if ( $post->post_parent ) {
            $ancestors = get_post_ancestors( $post->ID );
            $ancestors = array_reverse( $ancestors );
            
            foreach ( $ancestors as $ancestor ) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
                echo '</li>';
            }
        }
        
        // Current page
        echo '<li class="breadcrumb-item current">';
        echo esc_html( get_the_title() );
        echo '</li>';
    } elseif ( is_tag() ) {
        // Tag archive
        echo '<li class="breadcrumb-item current">';
        echo esc_html( single_tag_title( '', false ) );
        echo '</li>';
    } elseif ( is_author() ) {
        // Author archive
        echo '<li class="breadcrumb-item current">';
        echo esc_html( get_the_author() );
        echo '</li>';
    } elseif ( is_year() ) {
        // Year archive
        echo '<li class="breadcrumb-item current">';
        echo esc_html( get_the_date( 'Y' ) );
        echo '</li>';
    } elseif ( is_month() ) {
        // Month archive
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a>';
        echo '</li>';
        echo '<li class="breadcrumb-item current">';
        echo esc_html( get_the_date( 'F' ) );
        echo '</li>';
    } elseif ( is_day() ) {
        // Day archive
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a>';
        echo '</li>';
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ) . '">' . esc_html( get_the_date( 'F' ) ) . '</a>';
        echo '</li>';
        echo '<li class="breadcrumb-item current">';
        echo esc_html( get_the_date( 'j' ) );
        echo '</li>';
    } elseif ( is_search() ) {
        // Search results
        echo '<li class="breadcrumb-item current">';
        echo esc_html__( 'Search results for: ', 'aqualuxe' ) . esc_html( get_search_query() );
        echo '</li>';
    } elseif ( is_404() ) {
        // 404 page
        echo '<li class="breadcrumb-item current">';
        echo esc_html__( 'Page not found', 'aqualuxe' );
        echo '</li>';
    }
    
    // WooCommerce support
    if ( aqualuxe_is_woocommerce_active() ) {
        if ( is_shop() ) {
            echo '<li class="breadcrumb-item current">';
            echo esc_html( woocommerce_page_title( false ) );
            echo '</li>';
        } elseif ( is_product_category() || is_product_tag() ) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a>';
            echo '</li>';
            echo '<li class="breadcrumb-item current">';
            echo esc_html( single_term_title( '', false ) );
            echo '</li>';
        } elseif ( is_product() ) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a>';
            echo '</li>';
            
            // Get product categories
            $terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
            if ( ! empty( $terms ) ) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url( get_term_link( $terms[0] ) ) . '">' . esc_html( $terms[0]->name ) . '</a>';
                echo '</li>';
            }
            
            // Current product
            echo '<li class="breadcrumb-item current">';
            echo esc_html( get_the_title() );
            echo '</li>';
        }
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Displays the dark mode toggle button.
 */
function aqualuxe_dark_mode_toggle() {
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
        <span class="dark-mode-toggle__icon dark-mode-toggle__icon--light">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>
        </span>
        <span class="dark-mode-toggle__icon dark-mode-toggle__icon--dark">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>
        </span>
    </button>
    <?php
}

/**
 * Displays the search form.
 */
function aqualuxe_search_form() {
    ?>
    <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <label class="screen-reader-text" for="search-input"><?php echo esc_html_x( 'Search for:', 'label', 'aqualuxe' ); ?></label>
        <div class="search-form-container">
            <input type="search" id="search-input" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
            <button type="submit" class="search-submit" aria-label="<?php echo esc_attr_x( 'Search', 'submit button', 'aqualuxe' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
            </button>
        </div>
        <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
            <input type="hidden" name="post_type" value="product" />
        <?php endif; ?>
    </form>
    <?php
}

/**
 * Displays pagination for archive pages.
 */
function aqualuxe_pagination() {
    $args = array(
        'prev_text' => sprintf(
            '<span class="nav-prev-text">%s</span>',
            esc_html__( 'Previous', 'aqualuxe' )
        ),
        'next_text' => sprintf(
            '<span class="nav-next-text">%s</span>',
            esc_html__( 'Next', 'aqualuxe' )
        ),
    );

    the_posts_pagination( $args );
}

/**
 * Displays the post navigation.
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
        )
    );
}

/**
 * Displays the language switcher if WPML is active.
 */
function aqualuxe_language_switcher() {
    if ( function_exists( 'icl_object_id' ) ) {
        do_action( 'wpml_add_language_selector' );
    }
}

/**
 * Displays the currency switcher if WooCommerce and currency plugins are active.
 */
function aqualuxe_currency_switcher() {
    if ( aqualuxe_is_woocommerce_active() ) {
        // Check for WOOCS (WooCommerce Currency Switcher)
        if ( class_exists( 'WOOCS' ) ) {
            echo do_shortcode( '[woocs]' );
        }
        
        // Check for WCML (WooCommerce Multilingual)
        if ( class_exists( 'woocommerce_wpml' ) ) {
            do_action( 'wcml_currency_switcher', array( 'format' => '%code%' ) );
        }
    }
}

/**
 * Displays the mobile menu toggle button.
 */
function aqualuxe_mobile_menu_toggle() {
    ?>
    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
        <span class="mobile-menu-toggle__icon">
            <span class="mobile-menu-toggle__line"></span>
            <span class="mobile-menu-toggle__line"></span>
            <span class="mobile-menu-toggle__line"></span>
        </span>
        <span class="mobile-menu-toggle__text"><?php esc_html_e( 'Menu', 'aqualuxe' ); ?></span>
    </button>
    <?php
}

/**
 * Displays the copyright information.
 */
function aqualuxe_copyright() {
    $site_name = get_bloginfo( 'name' );
    $year = date( 'Y' );
    
    printf(
        /* translators: %1$s: Site name, %2$s: Current year */
        esc_html__( '© %2$s %1$s. All rights reserved.', 'aqualuxe' ),
        esc_html( $site_name ),
        esc_html( $year )
    );
}