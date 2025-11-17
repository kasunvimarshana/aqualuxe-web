<?php
/**
 * Single post template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="main-content" class="main-content" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden' ); ?>>
                
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail mb-8">
                        <?php the_post_thumbnail( 'aqualuxe-hero', array(
                            'class' => 'w-full h-96 object-cover rounded-t-lg',
                            'alt'   => get_the_title(),
                        ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="post-content p-8">
                    
                    <header class="post-header mb-8">
                        
                        <!-- Breadcrumbs -->
                        <div class="breadcrumbs mb-4">
                            <?php echo AquaLuxe_Helpers::get_breadcrumbs(); ?>
                        </div>

                        <h1 class="post-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                            <?php the_title(); ?>
                        </h1>

                        <div class="post-meta flex flex-wrap items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mb-6">
                            <div class="meta-item flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                            </div>
                            
                            <div class="meta-item flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span><?php esc_html_e( 'by', 'aqualuxe' ); ?> <?php the_author(); ?></span>
                            </div>
                            
                            <?php if ( has_category() ) : ?>
                                <div class="meta-item flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span><?php the_category( ', ' ); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="meta-item flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span><?php echo AquaLuxe_Helpers::get_reading_time(); ?></span>
                            </div>
                        </div>

                    </header>

                    <div class="post-body prose dark:prose-invert max-w-none">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    wp_link_pages( array(
                        'before' => '<div class="page-links mt-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"><span class="page-links-title font-semibold">' . esc_html__( 'Pages:', 'aqualuxe' ) . '</span>',
                        'after'  => '</div>',
                    ) );
                    ?>

                    <?php if ( has_tag() ) : ?>
                        <div class="post-tags mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <?php esc_html_e( 'Tags:', 'aqualuxe' ); ?>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php
                                $tags = get_the_tags();
                                if ( $tags ) {
                                    foreach ( $tags as $tag ) {
                                        echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="inline-block px-3 py-1 text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors duration-200">' . esc_html( $tag->name ) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Social sharing -->
                    <div class="social-sharing mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            <?php esc_html_e( 'Share this post:', 'aqualuxe' ); ?>
                        </h3>
                        <div class="flex space-x-4">
                            <?php
                            $share_links = AquaLuxe_Helpers::get_social_share_links();
                            foreach ( $share_links as $platform => $data ) {
                                echo '<a href="' . esc_url( $data['url'] ) . '" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center w-10 h-10 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-600 dark:hover:text-primary-400 rounded-full transition-colors duration-200" title="' . esc_attr( $data['label'] ) . '">';
                                echo '<span class="sr-only">' . esc_html( $data['label'] ) . '</span>';
                                echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><use href="#icon-' . esc_attr( $data['icon'] ) . '"></use></svg>';
                                echo '</a>';
                            }
                            ?>
                        </div>
                    </div>

                </div>

            </article>

            <!-- Post navigation -->
            <nav class="post-navigation mt-12 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" aria-label="<?php esc_attr_e( 'Post Navigation', 'aqualuxe' ); ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ( $prev_post ) : ?>
                        <div class="nav-previous">
                            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 group">
                                <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-primary-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></div>
                                    <div class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors duration-200"><?php echo esc_html( get_the_title( $prev_post->ID ) ); ?></div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( $next_post ) : ?>
                        <div class="nav-next">
                            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="flex items-center justify-end p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 group">
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></div>
                                    <div class="font-medium text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors duration-200"><?php echo esc_html( get_the_title( $next_post->ID ) ); ?></div>
                                </div>
                                <svg class="w-5 h-5 ml-3 text-gray-400 group-hover:text-primary-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>

            <!-- Author bio -->
            <?php if ( get_the_author_meta( 'description' ) ) : ?>
                <div class="author-bio mt-12 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', array( 'class' => 'rounded-full' ) ); ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                <?php esc_html_e( 'About', 'aqualuxe' ); ?> <?php the_author(); ?>
                            </h3>
                            <div class="text-gray-600 dark:text-gray-400">
                                <?php echo wpautop( get_the_author_meta( 'description' ) ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Related posts -->
            <?php
            $related_posts = get_posts( array(
                'numberposts'  => 3,
                'category__in' => wp_get_post_categories( get_the_ID() ),
                'post__not_in' => array( get_the_ID() ),
                'orderby'      => 'rand',
            ) );
            
            if ( $related_posts ) : ?>
                <div class="related-posts mt-12">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                        <?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <?php foreach ( $related_posts as $related_post ) : ?>
                            <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <?php if ( has_post_thumbnail( $related_post->ID ) ) : ?>
                                    <div class="related-post-thumbnail">
                                        <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>">
                                            <?php echo get_the_post_thumbnail( $related_post->ID, 'aqualuxe-featured', array(
                                                'class' => 'w-full h-48 object-cover',
                                                'alt'   => get_the_title( $related_post->ID ),
                                            ) ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="related-post-content p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                        <a href="<?php echo esc_url( get_permalink( $related_post->ID ) ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                                            <?php echo esc_html( get_the_title( $related_post->ID ) ); ?>
                                        </a>
                                    </h4>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <?php echo esc_html( get_the_date( '', $related_post->ID ) ); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            // If comments are open or we have at least one comment, load up the comment template
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</main>

<?php
get_sidebar();
get_footer();