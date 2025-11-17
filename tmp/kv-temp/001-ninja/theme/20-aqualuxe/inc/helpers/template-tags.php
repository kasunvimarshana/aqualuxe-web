<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
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
endif;

if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
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
endif;

if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
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
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
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
                <?php the_post_thumbnail( 'large', array( 'class' => 'rounded-lg shadow-lg' ) ); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'medium',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'rounded shadow hover:shadow-lg transition-shadow duration-300',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'aqualuxe_breadcrumb' ) ) :
    /**
     * Display breadcrumbs for the current page
     */
    function aqualuxe_breadcrumb() {
        // Check if yoast breadcrumbs are available
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
            return;
        }

        // Home page
        echo '<nav class="breadcrumbs py-2 text-sm" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
        echo '<ol class="flex flex-wrap items-center space-x-1">';
        
        // Home link
        echo '<li><a href="' . esc_url( home_url() ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';
        echo '<li class="text-gray-400 px-1">/</li>';

        // If WooCommerce is active and we're on a shop page
        if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            
            // Shop page
            if ( is_shop() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'Shop', 'aqualuxe' ) . '</li>';
            } 
            // Product category
            elseif ( is_product_category() ) {
                $current_term = get_queried_object();
                echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a></li>';
                echo '<li class="text-gray-400 px-1">/</li>';
                
                // Handle parent categories
                if ( $current_term->parent ) {
                    $ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
                    foreach ( array_reverse( $ancestors ) as $ancestor ) {
                        $ancestor = get_term( $ancestor, 'product_cat' );
                        echo '<li><a href="' . esc_url( get_term_link( $ancestor ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html( $ancestor->name ) . '</a></li>';
                        echo '<li class="text-gray-400 px-1">/</li>';
                    }
                }
                
                echo '<li class="text-gray-900">' . esc_html( $current_term->name ) . '</li>';
            }
            // Single product
            elseif ( is_product() ) {
                global $post;
                echo '<li><a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a></li>';
                echo '<li class="text-gray-400 px-1">/</li>';
                
                // Get product categories
                $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
                if ( $terms ) {
                    $main_term = $terms[0];
                    
                    // Handle parent categories
                    if ( $main_term->parent ) {
                        $ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
                        foreach ( array_reverse( $ancestors ) as $ancestor ) {
                            $ancestor = get_term( $ancestor, 'product_cat' );
                            echo '<li><a href="' . esc_url( get_term_link( $ancestor ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html( $ancestor->name ) . '</a></li>';
                            echo '<li class="text-gray-400 px-1">/</li>';
                        }
                    }
                    
                    echo '<li><a href="' . esc_url( get_term_link( $main_term ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html( $main_term->name ) . '</a></li>';
                    echo '<li class="text-gray-400 px-1">/</li>';
                }
                
                echo '<li class="text-gray-900">' . esc_html( get_the_title() ) . '</li>';
            }
            // Cart page
            elseif ( is_cart() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'Cart', 'aqualuxe' ) . '</li>';
            }
            // Checkout page
            elseif ( is_checkout() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'Checkout', 'aqualuxe' ) . '</li>';
            }
            // Account page
            elseif ( is_account_page() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'My Account', 'aqualuxe' ) . '</li>';
            }
        }
        // Regular WordPress pages
        else {
            if ( is_category() || is_single() ) {
                echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Blog', 'aqualuxe' ) . '</a></li>';
                echo '<li class="text-gray-400 px-1">/</li>';
                
                if ( is_category() ) {
                    $cat = get_category( get_query_var( 'cat' ) );
                    echo '<li class="text-gray-900">' . esc_html( $cat->name ) . '</li>';
                }
                
                if ( is_single() ) {
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo '<li><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html( $categories[0]->name ) . '</a></li>';
                        echo '<li class="text-gray-400 px-1">/</li>';
                    }
                    echo '<li class="text-gray-900">' . esc_html( get_the_title() ) . '</li>';
                }
            } elseif ( is_page() ) {
                // If the page has ancestors, show them in the breadcrumb
                if ( $post->post_parent ) {
                    $ancestors = get_post_ancestors( $post->ID );
                    $ancestors = array_reverse( $ancestors );
                    
                    foreach ( $ancestors as $ancestor ) {
                        echo '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html( get_the_title( $ancestor ) ) . '</a></li>';
                        echo '<li class="text-gray-400 px-1">/</li>';
                    }
                }
                
                echo '<li class="text-gray-900">' . esc_html( get_the_title() ) . '</li>';
            } elseif ( is_tag() ) {
                echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Blog', 'aqualuxe' ) . '</a></li>';
                echo '<li class="text-gray-400 px-1">/</li>';
                echo '<li class="text-gray-900">' . esc_html__( 'Tag: ', 'aqualuxe' ) . single_tag_title( '', false ) . '</li>';
            } elseif ( is_author() ) {
                echo '<li><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '" class="text-gray-500 hover:text-primary transition-colors duration-200">' . esc_html__( 'Blog', 'aqualuxe' ) . '</a></li>';
                echo '<li class="text-gray-400 px-1">/</li>';
                echo '<li class="text-gray-900">' . esc_html__( 'Author: ', 'aqualuxe' ) . get_the_author() . '</li>';
            } elseif ( is_search() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</li>';
            } elseif ( is_404() ) {
                echo '<li class="text-gray-900">' . esc_html__( 'Page Not Found', 'aqualuxe' ) . '</li>';
            }
        }
        
        echo '</ol>';
        echo '</nav>';
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Display pagination for archive pages
     */
    function aqualuxe_pagination() {
        global $wp_query;

        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        $big = 999999999; // need an unlikely integer
        
        $pages = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
            'prev_text' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
            'next_text' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
        ) );
        
        if ( is_array( $pages ) ) {
            echo '<nav class="pagination flex justify-center my-8" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
            echo '<ul class="inline-flex items-center -space-x-px">';
            
            foreach ( $pages as $page ) {
                $active = strpos( $page, 'current' ) !== false ? 'bg-primary text-white' : 'bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700';
                echo '<li class="pagination-item">';
                echo str_replace( 
                    'page-numbers', 
                    'page-numbers inline-flex items-center justify-center px-4 py-2 text-sm font-medium border border-gray-300 ' . $active, 
                    $page 
                );
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_social_links' ) ) :
    /**
     * Display social media links
     */
    function aqualuxe_social_links() {
        $social_links = array(
            'facebook'  => array(
                'label' => esc_html__( 'Facebook', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_facebook', '' ),
            ),
            'twitter'   => array(
                'label' => esc_html__( 'Twitter', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_twitter', '' ),
            ),
            'instagram' => array(
                'label' => esc_html__( 'Instagram', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_instagram', '' ),
            ),
            'linkedin'  => array(
                'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_linkedin', '' ),
            ),
            'youtube'   => array(
                'label' => esc_html__( 'YouTube', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" clip-rule="evenodd" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_youtube', '' ),
            ),
            'pinterest' => array(
                'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
                'icon'  => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" clip-rule="evenodd" /></svg>',
                'url'   => get_theme_mod( 'aqualuxe_social_pinterest', '' ),
            ),
        );

        echo '<div class="social-links flex space-x-4">';
        foreach ( $social_links as $network => $data ) {
            if ( ! empty( $data['url'] ) ) {
                echo '<a href="' . esc_url( $data['url'] ) . '" class="text-gray-400 hover:text-primary transition-colors duration-200" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $data['label'] ) . '">';
                echo $data['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</a>';
            }
        }
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_dark_mode_toggle' ) ) :
    /**
     * Display dark mode toggle button
     */
    function aqualuxe_dark_mode_toggle() {
        ?>
        <button id="dark-mode-toggle" class="dark-mode-toggle p-2 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
            <!-- Sun icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sun-icon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            <!-- Moon icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 moon-icon" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
        <?php
    }
endif;

if ( ! function_exists( 'aqualuxe_language_switcher' ) ) :
    /**
     * Display language switcher if WPML or Polylang is active
     */
    function aqualuxe_language_switcher() {
        // Check if WPML is active
        if ( function_exists( 'icl_get_languages' ) ) {
            $languages = icl_get_languages( 'skip_missing=0' );
            
            if ( ! empty( $languages ) ) {
                echo '<div class="language-switcher relative inline-block text-left">';
                echo '<div>';
                echo '<button type="button" class="language-switcher-button inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
                
                // Current language
                foreach ( $languages as $language ) {
                    if ( $language['active'] ) {
                        echo esc_html( $language['native_name'] );
                        break;
                    }
                }
                
                echo '<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">';
                echo '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
                echo '</svg>';
                echo '</button>';
                echo '</div>';
                
                echo '<div class="language-switcher-dropdown hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
                echo '<div class="py-1" role="none">';
                
                // Language options
                foreach ( $languages as $language ) {
                    $active_class = $language['active'] ? 'bg-gray-100 text-gray-900' : 'text-gray-700';
                    echo '<a href="' . esc_url( $language['url'] ) . '" class="' . esc_attr( $active_class ) . ' block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">' . esc_html( $language['native_name'] ) . '</a>';
                }
                
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_the_languages' ) ) {
            $args = array(
                'dropdown'   => 1,
                'show_names' => 1,
            );
            pll_the_languages( $args );
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_mini_cart' ) ) :
    /**
     * Display mini cart for WooCommerce
     */
    function aqualuxe_mini_cart() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        ?>
        <div class="mini-cart relative">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mini-cart-toggle flex items-center text-gray-500 hover:text-primary transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span class="mini-cart-count ml-1 text-sm font-medium"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>
            <div class="mini-cart-dropdown hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50">
                <div class="p-4">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
        <?php
    }
endif;