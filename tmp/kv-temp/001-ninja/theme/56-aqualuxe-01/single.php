<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
        while ( have_posts() ) :
            the_post();

            // Post header
            ?>
            <header class="entry-header mb-8">
                <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">', '</h1>' ); ?>
                
                <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <?php
                    // Author
                    echo '<span class="author mr-4 mb-2">';
                    echo '<span class="inline-block mr-1">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
                    echo '</svg>';
                    echo '</span>';
                    echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" class="hover:text-primary">';
                    echo esc_html( get_the_author() );
                    echo '</a>';
                    echo '</span>';
                    
                    // Date
                    echo '<span class="date mr-4 mb-2">';
                    echo '<span class="inline-block mr-1">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
                    echo '</svg>';
                    echo '</span>';
                    echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">';
                    echo esc_html( get_the_date() );
                    echo '</time>';
                    echo '</span>';
                    
                    // Comments
                    echo '<span class="comments mb-2">';
                    echo '<span class="inline-block mr-1">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />';
                    echo '</svg>';
                    echo '</span>';
                    echo '<a href="' . esc_url( get_comments_link() ) . '" class="hover:text-primary">';
                    comments_number( 
                        esc_html__( 'No comments', 'aqualuxe' ), 
                        esc_html__( '1 comment', 'aqualuxe' ), 
                        esc_html__( '% comments', 'aqualuxe' ) 
                    );
                    echo '</a>';
                    echo '</span>';
                    ?>
                </div>
                
                <?php
                // Categories
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<div class="entry-categories mb-6">';
                    foreach ( $categories as $category ) {
                        echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block bg-primary bg-opacity-10 text-primary rounded-full px-3 py-1 text-xs font-semibold mr-2 mb-2 hover:bg-opacity-20 transition-all duration-300">';
                        echo esc_html( $category->name );
                        echo '</a>';
                    }
                    echo '</div>';
                }
                ?>
                
                <?php
                // Featured image
                if ( has_post_thumbnail() ) {
                    echo '<div class="entry-thumbnail mb-8">';
                    echo '<img src="' . esc_url( get_the_post_thumbnail_url( null, 'large' ) ) . '" alt="' . esc_attr( get_the_title() ) . '" class="w-full h-auto rounded-lg shadow-lg">';
                    echo '</div>';
                }
                ?>
            </header>

            <div class="entry-content prose prose-lg dark:prose-invert max-w-none mb-8">
                <?php the_content(); ?>
                
                <?php
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links my-6">' . esc_html__( 'Pages:', 'aqualuxe' ),
                        'after'  => '</div>',
                    )
                );
                ?>
            </div>

            <footer class="entry-footer mb-8">
                <?php
                // Tags
                $tags = get_the_tags();
                if ( ! empty( $tags ) ) {
                    echo '<div class="entry-tags mb-6">';
                    echo '<h4 class="text-lg font-semibold mb-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</h4>';
                    echo '<div class="flex flex-wrap">';
                    foreach ( $tags as $tag ) {
                        echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2 mb-2 hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300">';
                        echo esc_html( $tag->name );
                        echo '</a>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
                ?>
                
                <div class="entry-share">
                    <h4 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></h4>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( get_the_title() ); ?>&url=<?php echo esc_url( get_permalink() ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url( get_permalink() ); ?>&title=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                            </svg>
                        </a>
                        <a href="mailto:?subject=<?php echo esc_attr( get_the_title() ); ?>&body=<?php echo esc_url( get_permalink() ); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 transition-colors duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </footer>

            <div class="entry-author bg-gray-100 dark:bg-gray-800 rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start">
                    <div class="author-avatar mb-4 md:mb-0 md:mr-6">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', '', array( 'class' => 'rounded-full' ) ); ?>
                    </div>
                    <div class="author-content">
                        <h3 class="author-name text-xl font-bold mb-2">
                            <?php echo esc_html( get_the_author() ); ?>
                        </h3>
                        <?php if ( get_the_author_meta( 'description' ) ) : ?>
                            <div class="author-bio text-gray-600 dark:text-gray-400 mb-4">
                                <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                            </div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300">
                            <?php
                            /* translators: %s: Author name */
                            printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), get_the_author() );
                            ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="post-navigation mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php
                    $prev_post = get_previous_post();
                    if ( ! empty( $prev_post ) ) {
                        ?>
                        <div class="post-navigation__prev bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 transition-all duration-300 hover:shadow-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400 block mb-2"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
                            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="text-lg font-semibold hover:text-primary transition-colors duration-300">
                                <?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    
                    $next_post = get_next_post();
                    if ( ! empty( $next_post ) ) {
                        ?>
                        <div class="post-navigation__next bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 transition-all duration-300 hover:shadow-lg <?php echo empty( $prev_post ) ? 'md:col-start-2' : ''; ?>">
                            <span class="text-sm text-gray-600 dark:text-gray-400 block mb-2"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
                            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="text-lg font-semibold hover:text-primary transition-colors duration-300">
                                <?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
        
        <?php
        // Related posts
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $category_ids = wp_list_pluck( $categories, 'term_id' );
            
            $related_args = array(
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post__not_in'   => array( get_the_ID() ),
                'category__in'   => $category_ids,
            );
            
            $related_query = new WP_Query( $related_args );
            
            if ( $related_query->have_posts() ) {
                ?>
                <div class="related-posts mb-8">
                    <h3 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php
                        while ( $related_query->have_posts() ) {
                            $related_query->the_post();
                            ?>
                            <article class="related-post bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <img src="<?php the_post_thumbnail_url( 'medium' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-48 object-cover">
                                    </a>
                                <?php endif; ?>
                                
                                <div class="p-6">
                                    <h4 class="text-xl font-bold mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>
                                    
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="inline-block text-primary hover:text-primary-dark font-medium transition-colors duration-300">
                                        <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                        <span class="ml-1">→</span>
                                    </a>
                                </div>
                            </article>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();