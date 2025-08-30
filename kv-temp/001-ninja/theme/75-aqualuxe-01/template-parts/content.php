<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail aspect-ratio-16-9">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail( 'large', array(
                    'class' => 'w-full h-full object-cover',
                    'loading' => 'lazy',
                    'alt' => get_the_title(),
                ) );
                ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="card-body">
        <header class="post-header">
            <!-- Post Meta -->
            <div class="post-meta">
                <span class="post-date">
                    <i class="fas fa-calendar" aria-hidden="true"></i>
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                        <?php echo esc_html( get_the_date() ); ?>
                    </time>
                </span>
                
                <?php if ( has_category() ) : ?>
                <span class="post-categories">
                    <i class="fas fa-folder" aria-hidden="true"></i>
                    <?php the_category( ', ' ); ?>
                </span>
                <?php endif; ?>
                
                <span class="post-author">
                    <i class="fas fa-user" aria-hidden="true"></i>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
                
                <?php if ( comments_open() || get_comments_number() ) : ?>
                <span class="post-comments">
                    <i class="fas fa-comments" aria-hidden="true"></i>
                    <a href="<?php comments_link(); ?>">
                        <?php
                        printf(
                            /* translators: %s: Comment count */
                            _n( '%s Comment', '%s Comments', get_comments_number(), 'aqualuxe' ),
                            number_format_i18n( get_comments_number() )
                        );
                        ?>
                    </a>
                </span>
                <?php endif; ?>
            </div>
            
            <!-- Post Title -->
            <?php
            if ( is_singular() ) :
                the_title( '<h1 class="post-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
            else :
                the_title( '<h2 class="post-title text-xl lg:text-2xl font-semibold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
            endif;
            ?>
        </header>
        
        <div class="post-content">
            <?php
            if ( is_singular() ) :
                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Post title. Only visible to screen readers. */
                            __( 'Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    )
                );
                
                wp_link_pages(
                    array(
                        'before' => '<div class="page-links mt-6">' . esc_html__( 'Pages:', 'aqualuxe' ),
                        'after'  => '</div>',
                        'link_before' => '<span class="page-number">',
                        'link_after'  => '</span>',
                    )
                );
            else :
                the_excerpt();
            endif;
            ?>
        </div>
        
        <?php if ( ! is_singular() ) : ?>
        <footer class="post-footer mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                    <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                    <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
                
                <div class="post-actions flex items-center space-x-2">
                    <!-- Share Buttons -->
                    <?php aqualuxe_share_buttons(); ?>
                    
                    <!-- Reading Time -->
                    <span class="reading-time text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clock" aria-hidden="true"></i>
                        <?php echo aqualuxe_reading_time(); ?> <?php esc_html_e( 'min read', 'aqualuxe' ); ?>
                    </span>
                </div>
            </div>
        </footer>
        <?php endif; ?>
    </div>
</article>

<?php if ( is_singular() ) : ?>
    <!-- Post Navigation -->
    <nav class="post-navigation mt-8 pt-8 border-t border-gray-200 dark:border-gray-700" aria-label="<?php esc_attr_e( 'Post Navigation', 'aqualuxe' ); ?>">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            ?>
            
            <?php if ( $prev_post ) : ?>
            <div class="nav-previous">
                <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="block p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-chevron-left text-primary-500" aria-hidden="true"></i>
                        <div>
                            <div class="text-sm text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></div>
                            <div class="font-semibold text-gray-900 dark:text-white"><?php echo esc_html( get_the_title( $prev_post ) ); ?></div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if ( $next_post ) : ?>
            <div class="nav-next <?php echo $prev_post ? '' : 'lg:col-start-2'; ?>">
                <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="block p-4 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex items-center justify-end space-x-3">
                        <div class="text-right">
                            <div class="text-sm text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></div>
                            <div class="font-semibold text-gray-900 dark:text-white"><?php echo esc_html( get_the_title( $next_post ) ); ?></div>
                        </div>
                        <i class="fas fa-chevron-right text-primary-500" aria-hidden="true"></i>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    
    <!-- Post Tags -->
    <?php if ( has_tag() ) : ?>
    <div class="post-tags mt-6">
        <h3 class="text-lg font-semibold mb-3"><?php esc_html_e( 'Tags', 'aqualuxe' ); ?></h3>
        <div class="flex flex-wrap gap-2">
            <?php
            $tags = get_the_tags();
            foreach ( $tags as $tag ) :
            ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                   class="px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300 rounded-full text-sm hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                    #<?php echo esc_html( $tag->name ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Author Bio -->
    <?php if ( get_the_author_meta( 'description' ) ) : ?>
    <div class="author-bio mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', array( 'class' => 'rounded-full' ) ); ?>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__( 'About %s', 'aqualuxe' ),
                        esc_html( get_the_author() )
                    );
                    ?>
                </h3>
                <div class="text-gray-600 dark:text-gray-300 mb-3">
                    <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                </div>
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" 
                   class="text-primary-500 hover:text-primary-600 font-medium text-sm">
                    <?php esc_html_e( 'View all posts by this author', 'aqualuxe' ); ?>
                    <i class="fas fa-arrow-right ml-1" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Related Posts -->
    <?php aqualuxe_related_posts(); ?>
    
    <!-- Comments -->
    <?php
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>
<?php endif; ?>
