<?php
/**
 * Single post template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main container py-8" role="main">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'max-w-4xl mx-auto' ); ?>>
            <header class="entry-header mb-8">
                <?php aqualuxe_breadcrumbs(); ?>
                
                <?php the_title( '<h1 class="entry-title text-4xl font-serif font-bold text-gray-900 dark:text-white mt-6 mb-4">', '</h1>' ); ?>
                
                <div class="entry-meta flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <?php aqualuxe_posted_on(); ?>
                    <?php aqualuxe_posted_by(); ?>
                    <?php aqualuxe_reading_time(); ?>
                    
                    <?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
                        <span class="comments-link">
                            <a href="#comments" class="hover:text-primary-600 dark:hover:text-primary-400">
                                <?php comments_number( __( 'Leave a comment', 'aqualuxe' ), __( '1 Comment', 'aqualuxe' ), __( '% Comments', 'aqualuxe' ) ); ?>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if ( aqualuxe_can_show_post_thumbnail() ) : ?>
                <div class="post-thumbnail mb-8">
                    <?php aqualuxe_post_thumbnail( 'large', false ); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content prose prose-lg dark:prose-invert max-w-none mb-8">
                <?php
                the_content( sprintf(
                    wp_kses(
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                ) );

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <footer class="entry-footer mb-8">
                <?php aqualuxe_entry_footer(); ?>
                
                <?php if ( is_singular( 'post' ) ) : ?>
                    <div class="post-tags mt-4">
                        <?php the_tags( '<div class="flex flex-wrap gap-2"><span class="text-sm text-gray-600 dark:text-gray-400 mr-2">' . __( 'Tags:', 'aqualuxe' ) . '</span>', '', '</div>' ); ?>
                    </div>
                <?php endif; ?>
            </footer>

            <?php aqualuxe_social_share(); ?>
        </article>

        <?php
        // Author bio
        if ( is_singular( 'post' ) && get_the_author_meta( 'description' ) ) : ?>
            <div class="author-bio bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8 max-w-4xl mx-auto">
                <div class="flex items-start gap-4">
                    <div class="author-avatar flex-shrink-0">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', array( 'class' => 'rounded-full' ) ); ?>
                    </div>
                    <div class="author-info">
                        <h3 class="author-name text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            <?php echo esc_html( get_the_author() ); ?>
                        </h3>
                        <div class="author-description text-gray-600 dark:text-gray-400">
                            <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                        </div>
                        <div class="author-links mt-3">
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline">
                                <?php esc_html_e( 'View all posts by', 'aqualuxe' ); ?> <?php echo esc_html( get_the_author() ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Related posts
        if ( is_singular( 'post' ) ) :
            $related_posts = get_posts( array(
                'category__in'   => wp_get_post_categories( get_the_ID() ),
                'numberposts'    => 3,
                'post__not_in'   => array( get_the_ID() ),
                'orderby'        => 'rand',
            ) );

            if ( $related_posts ) : ?>
                <div class="related-posts bg-white dark:bg-gray-800 rounded-lg p-6 mb-8 max-w-4xl mx-auto">
                    <h3 class="text-2xl font-serif font-semibold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ( $related_posts as $related_post ) : setup_postdata( $related_post ); ?>
                            <article class="related-post">
                                <?php if ( has_post_thumbnail( $related_post->ID ) ) : ?>
                                    <div class="related-post-thumbnail mb-3">
                                        <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
                                            <?php echo get_the_post_thumbnail( $related_post->ID, 'medium', array( 'class' => 'w-full h-40 object-cover rounded' ) ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <h4 class="related-post-title text-lg font-semibold mb-2">
                                    <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html( get_the_title( $related_post->ID ) ); ?>
                                    </a>
                                </h4>
                                <div class="related-post-excerpt text-sm text-gray-600 dark:text-gray-400">
                                    <?php echo wp_trim_words( get_the_excerpt( $related_post->ID ), 15 ); ?>
                                </div>
                            </article>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php endif;
        endif; ?>

        <?php
        // Post navigation
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        
        if ( $prev_post || $next_post ) : ?>
            <nav class="post-navigation flex justify-between items-center bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-8 max-w-4xl mx-auto">
                <?php if ( $prev_post ) : ?>
                    <div class="nav-previous">
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <div>
                                <div class="text-sm"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></div>
                                <div class="font-medium"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></div>
                            </div>
                        </a>
                    </div>
                <?php else : ?>
                    <div></div>
                <?php endif; ?>

                <?php if ( $next_post ) : ?>
                    <div class="nav-next text-right">
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="flex items-center text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                            <div>
                                <div class="text-sm"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></div>
                                <div class="font-medium"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></div>
                            </div>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                <?php else : ?>
                    <div></div>
                <?php endif; ?>
            </nav>
        <?php endif; ?>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        ?>

    <?php endwhile; ?>
</main>

<?php
get_sidebar();
get_footer();