<?php
/**
 * Custom pagination functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Display pagination for archive pages
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
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg><span class="sr-only">' . __( 'Previous', 'aqualuxe' ) . '</span>',
        'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg><span class="sr-only">' . __( 'Next', 'aqualuxe' ) . '</span>',
    ) );
    
    if ( ! empty( $links ) ) {
        echo '<nav class="pagination flex justify-center mt-8" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
        echo '<ul class="flex flex-wrap items-center -mx-1">';
        
        foreach ( $links as $link ) {
            $active_class = strpos( $link, 'current' ) !== false ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-white dark:bg-dark-700 text-dark-700 dark:text-dark-200 hover:bg-primary-50 dark:hover:bg-dark-600';
            
            echo '<li class="mx-1 my-1">';
            echo str_replace( 
                'page-numbers', 
                'page-numbers inline-flex items-center px-4 py-2 text-sm font-medium rounded-md ' . $active_class . ' transition-colors duration-200', 
                $link 
            );
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Custom comment callback
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item bg-gray-50 dark:bg-dark-750 rounded-lg p-6', empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta mb-4">
                <div class="flex items-center">
                    <div class="comment-author vcard mr-4">
                        <?php
                        if ( 0 != $args['avatar_size'] ) {
                            echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                        }
                        ?>
                    </div><!-- .comment-author -->

                    <div>
                        <div class="comment-author-name font-medium text-dark-800 dark:text-white">
                            <?php comment_author_link(); ?>
                        </div>
                        <div class="comment-metadata text-sm text-dark-500 dark:text-dark-300">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php
                                /* translators: 1: comment date, 2: comment time */
                                printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date( '', $comment ), get_comment_time() );
                                ?>
                            </time>
                            <?php edit_comment_link( __( 'Edit', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
                        </div><!-- .comment-metadata -->
                    </div>
                </div>

                <?php if ( '0' == $comment->comment_approved ) : ?>
                <div class="comment-awaiting-moderation mt-2 p-2 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-sm rounded">
                    <?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?>
                </div>
                <?php endif; ?>
            </footer><!-- .comment-meta -->

            <div class="comment-content prose dark:prose-invert max-w-none">
                <?php comment_text(); ?>
            </div><!-- .comment-content -->

            <div class="reply mt-4">
                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply-link">',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
            </div>
        </article><!-- .comment-body -->
    <?php
}

/**
 * Check if page title should be hidden
 */
function aqualuxe_is_page_title_hidden() {
    return get_post_meta( get_the_ID(), '_aqualuxe_hide_title', true );
}

/**
 * Check if page header should be hidden
 */
function aqualuxe_is_page_header_hidden() {
    return get_post_meta( get_the_ID(), '_aqualuxe_hide_header', true );
}

/**
 * Display post categories
 */
function aqualuxe_post_categories() {
    $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
    if ( $categories_list ) {
        echo $categories_list;
    }
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    $tags_list = get_the_tag_list( '', esc_html__( ', ', 'aqualuxe' ) );
    if ( $tags_list ) {
        echo $tags_list;
    }
}