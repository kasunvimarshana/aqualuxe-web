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
                        'class' => 'rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
    /**
     * Display breadcrumbs
     */
    function aqualuxe_breadcrumbs() {
        if ( is_front_page() ) {
            return;
        }

        echo '<nav class="breadcrumbs py-2 text-sm" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
        echo '<ol class="flex flex-wrap items-center space-x-1">';
        
        // Home
        echo '<li><a href="' . esc_url( home_url() ) . '" class="text-primary hover:text-primary-dark transition-colors">' . esc_html__( 'Home', 'aqualuxe' ) . '</a></li>';
        echo '<li class="mx-1">/</li>';
        
        if ( is_category() || is_single() ) {
            if ( is_single() ) {
                // Get categories
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<li><a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="text-primary hover:text-primary-dark transition-colors">' . esc_html( $categories[0]->name ) . '</a></li>';
                    echo '<li class="mx-1">/</li>';
                }
                
                // Current post
                echo '<li class="text-gray-600" aria-current="page">' . get_the_title() . '</li>';
            } else {
                // Category archive
                echo '<li class="text-gray-600" aria-current="page">' . single_cat_title( '', false ) . '</li>';
            }
        } elseif ( is_page() ) {
            // Check if the page has a parent
            if ( $post->post_parent ) {
                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse( $ancestors );
                
                foreach ( $ancestors as $ancestor ) {
                    echo '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="text-primary hover:text-primary-dark transition-colors">' . get_the_title( $ancestor ) . '</a></li>';
                    echo '<li class="mx-1">/</li>';
                }
            }
            
            // Current page
            echo '<li class="text-gray-600" aria-current="page">' . get_the_title() . '</li>';
        } elseif ( is_tag() ) {
            // Tag archive
            echo '<li class="text-gray-600" aria-current="page">' . single_tag_title( '', false ) . '</li>';
        } elseif ( is_author() ) {
            // Author archive
            echo '<li class="text-gray-600" aria-current="page">' . get_the_author() . '</li>';
        } elseif ( is_year() ) {
            // Year archive
            echo '<li class="text-gray-600" aria-current="page">' . get_the_date( 'Y' ) . '</li>';
        } elseif ( is_month() ) {
            // Month archive
            echo '<li class="text-gray-600" aria-current="page">' . get_the_date( 'F Y' ) . '</li>';
        } elseif ( is_day() ) {
            // Day archive
            echo '<li class="text-gray-600" aria-current="page">' . get_the_date() . '</li>';
        } elseif ( is_post_type_archive() ) {
            // Custom post type archive
            echo '<li class="text-gray-600" aria-current="page">' . post_type_archive_title( '', false ) . '</li>';
        } elseif ( is_tax() ) {
            // Custom taxonomy archive
            echo '<li class="text-gray-600" aria-current="page">' . single_term_title( '', false ) . '</li>';
        } elseif ( is_search() ) {
            // Search results
            echo '<li class="text-gray-600" aria-current="page">' . esc_html__( 'Search results for: ', 'aqualuxe' ) . get_search_query() . '</li>';
        } elseif ( is_404() ) {
            // 404 page
            echo '<li class="text-gray-600" aria-current="page">' . esc_html__( 'Page not found', 'aqualuxe' ) . '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Display pagination
     */
    function aqualuxe_pagination() {
        global $wp_query;
        
        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }
        
        $big = 999999999; // need an unlikely integer
        
        $links = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
            'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
        ) );
        
        if ( ! empty( $links ) ) :
            echo '<nav class="pagination flex justify-center my-8" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
            echo '<ul class="flex items-center space-x-1">';
            
            foreach ( $links as $link ) {
                $active = strpos( $link, 'current' ) !== false ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50';
                echo '<li class="pagination-item">';
                echo str_replace( 
                    'page-numbers', 
                    'page-numbers inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 ' . $active . ' focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition-colors', 
                    $link 
                );
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        endif;
    }
endif;

if ( ! function_exists( 'aqualuxe_social_share' ) ) :
    /**
     * Display social sharing buttons
     */
    function aqualuxe_social_share() {
        $post_url = urlencode( get_permalink() );
        $post_title = urlencode( get_the_title() );
        $post_thumbnail = has_post_thumbnail() ? urlencode( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ) : '';
        
        // Social share URLs
        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
        $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
        $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
        $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
        $whatsapp_url = 'https://api.whatsapp.com/send?text=' . $post_title . ' ' . $post_url;
        
        echo '<div class="social-share my-6">';
        echo '<h3 class="text-lg font-medium mb-3">' . esc_html__( 'Share this:', 'aqualuxe' ) . '</h3>';
        echo '<div class="flex space-x-3">';
        
        // Facebook
        echo '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener noreferrer" class="social-share-link facebook bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors" aria-label="' . esc_attr__( 'Share on Facebook', 'aqualuxe' ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>';
        echo '</a>';
        
        // Twitter
        echo '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener noreferrer" class="social-share-link twitter bg-blue-400 hover:bg-blue-500 text-white p-2 rounded-full transition-colors" aria-label="' . esc_attr__( 'Share on Twitter', 'aqualuxe' ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>';
        echo '</a>';
        
        // LinkedIn
        echo '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener noreferrer" class="social-share-link linkedin bg-blue-700 hover:bg-blue-800 text-white p-2 rounded-full transition-colors" aria-label="' . esc_attr__( 'Share on LinkedIn', 'aqualuxe' ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>';
        echo '</a>';
        
        // Pinterest (only if post has thumbnail)
        if ( $post_thumbnail ) {
            echo '<a href="' . esc_url( $pinterest_url ) . '" target="_blank" rel="noopener noreferrer" class="social-share-link pinterest bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors" aria-label="' . esc_attr__( 'Share on Pinterest', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5.02.02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg>';
            echo '</a>';
        }
        
        // WhatsApp
        echo '<a href="' . esc_url( $whatsapp_url ) . '" target="_blank" rel="noopener noreferrer" class="social-share-link whatsapp bg-green-500 hover:bg-green-600 text-white p-2 rounded-full transition-colors" aria-label="' . esc_attr__( 'Share on WhatsApp', 'aqualuxe' ) . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.498 14.382c-.301-.15-1.767-.867-2.04-.966-.273-.101-.473-.15-.673.15-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.465.13-.615.136-.135.301-.345.451-.523.146-.181.194-.301.297-.496.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.172-.015-.371-.015-.571-.015-.2 0-.523.074-.797.359-.273.3-1.045 1.02-1.045 2.475s1.07 2.865 1.219 3.075c.149.195 2.105 3.195 5.1 4.485.714.3 1.27.48 1.704.629.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345m-5.446 7.443h-.016c-1.77 0-3.524-.48-5.055-1.38l-.36-.214-3.75.975 1.005-3.645-.239-.375c-.99-1.576-1.516-3.391-1.516-5.26 0-5.445 4.455-9.885 9.942-9.885 2.654 0 5.145 1.035 7.021 2.91 1.875 1.859 2.909 4.35 2.909 6.99-.004 5.444-4.46 9.885-9.935 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652c1.746.943 3.71 1.444 5.71 1.447h.006c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411"/></svg>';
        echo '</a>';
        
        echo '</div>';
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
    /**
     * Display related posts
     */
    function aqualuxe_related_posts() {
        global $post;
        
        // Get current post categories
        $categories = get_the_category( $post->ID );
        
        if ( $categories ) {
            $category_ids = array();
            
            foreach ( $categories as $category ) {
                $category_ids[] = $category->term_id;
            }
            
            $args = array(
                'category__in'        => $category_ids,
                'post__not_in'        => array( $post->ID ),
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => 1,
            );
            
            $related_query = new WP_Query( $args );
            
            if ( $related_query->have_posts() ) :
                ?>
                <div class="related-posts my-10">
                    <h3 class="text-2xl font-semibold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                            <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:translate-y-[-5px]">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <div class="p-4">
                                    <h4 class="text-lg font-medium mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                    
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php
            endif;
            
            wp_reset_postdata();
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_get_theme_mode_toggle' ) ) :
    /**
     * Get theme mode toggle button
     */
    function aqualuxe_get_theme_mode_toggle() {
        ob_start();
        ?>
        <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>
        <?php
        return ob_get_clean();
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
                echo '<div class="language-switcher">';
                echo '<div class="relative inline-block text-left">';
                echo '<div>';
                echo '<button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
                
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
                
                echo '<div class="language-dropdown hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
                echo '<div class="py-1" role="none">';
                
                // Language options
                foreach ( $languages as $language ) {
                    $class = $language['active'] ? 'bg-gray-100 text-gray-900' : 'text-gray-700';
                    echo '<a href="' . esc_url( $language['url'] ) . '" class="' . esc_attr( $class ) . ' block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1">' . esc_html( $language['native_name'] ) . '</a>';
                }
                
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        
        // Check if Polylang is active
        if ( function_exists( 'pll_the_languages' ) ) {
            $args = array(
                'dropdown'   => 0,
                'show_names' => 1,
            );
            
            echo '<div class="language-switcher">';
            echo '<div class="relative inline-block text-left">';
            echo '<div>';
            echo '<button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
            
            // Current language
            echo esc_html( pll_current_language( 'name' ) );
            
            echo '<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">';
            echo '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
            echo '</svg>';
            echo '</button>';
            echo '</div>';
            
            echo '<div class="language-dropdown hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
            echo '<div class="py-1" role="none">';
            
            // Language options
            pll_the_languages( $args );
            
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
endif;