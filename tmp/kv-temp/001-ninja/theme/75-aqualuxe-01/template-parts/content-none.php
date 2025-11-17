<?php
/**
 * Template part for displaying "No posts found"
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<section class="no-results not-found text-center py-16">
    <div class="max-w-2xl mx-auto">
        <!-- Icon -->
        <div class="mb-8">
            <div class="w-24 h-24 mx-auto bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                <i class="fas fa-search text-3xl text-gray-400 dark:text-gray-500" aria-hidden="true"></i>
            </div>
        </div>
        
        <!-- Title -->
        <header class="page-header mb-6">
            <h1 class="page-title text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                <?php
                if ( is_search() ) {
                    printf(
                        /* translators: %s: Search query */
                        esc_html__( 'No results found for "%s"', 'aqualuxe' ),
                        '<span class="text-primary-500">' . get_search_query() . '</span>'
                    );
                } else {
                    esc_html_e( 'Nothing here', 'aqualuxe' );
                }
                ?>
            </h1>
        </header>
        
        <!-- Content -->
        <div class="page-content">
            <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    <?php
                    printf(
                        wp_kses(
                            /* translators: %s: Link to create a new post */
                            __( 'Ready to publish your first post? <a href="%s">Get started here</a>.', 'aqualuxe' ),
                            array(
                                'a' => array(
                                    'href' => array(),
                                ),
                            )
                        ),
                        esc_url( admin_url( 'post-new.php' ) )
                    );
                    ?>
                </p>
            <?php elseif ( is_search() ) : ?>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    <?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?>
                </p>
                
                <!-- Search Form -->
                <div class="search-form-wrapper max-w-md mx-auto mb-8">
                    <?php get_search_form(); ?>
                </div>
                
                <!-- Search Suggestions -->
                <div class="search-suggestions">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e( 'Search Suggestions:', 'aqualuxe' ); ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                        <div class="suggestion-category">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                <?php esc_html_e( 'Popular Topics', 'aqualuxe' ); ?>
                            </h3>
                            <ul class="space-y-1 text-sm">
                                <?php
                                // Get popular categories
                                $popular_categories = get_categories( array(
                                    'orderby' => 'count',
                                    'order'   => 'DESC',
                                    'number'  => 5,
                                ) );
                                
                                foreach ( $popular_categories as $category ) :
                                ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                                           class="text-primary-500 hover:text-primary-600 transition-colors">
                                            <?php echo esc_html( $category->name ); ?>
                                            <span class="text-gray-500">(<?php echo $category->count; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <div class="suggestion-category">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                                <?php esc_html_e( 'Popular Tags', 'aqualuxe' ); ?>
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <?php
                                // Get popular tags
                                $popular_tags = get_tags( array(
                                    'orderby' => 'count',
                                    'order'   => 'DESC',
                                    'number'  => 8,
                                ) );
                                
                                foreach ( $popular_tags as $tag ) :
                                ?>
                                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                                       class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                        #<?php echo esc_html( $tag->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                    <?php esc_html_e( 'It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe' ); ?>
                </p>
                
                <!-- Search Form -->
                <div class="search-form-wrapper max-w-md mx-auto">
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Additional Actions -->
        <div class="additional-actions mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <i class="fas fa-home mr-2" aria-hidden="true"></i>
                    <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                </a>
                
                <?php if ( aqualuxe_is_woocommerce_active() ) : ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">
                    <i class="fas fa-shopping-bag mr-2" aria-hidden="true"></i>
                    <?php esc_html_e( 'Browse Products', 'aqualuxe' ); ?>
                </a>
                <?php endif; ?>
                
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-ghost">
                    <i class="fas fa-blog mr-2" aria-hidden="true"></i>
                    <?php esc_html_e( 'View All Posts', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
        
        <!-- Recent Posts -->
        <?php
        $recent_posts = new WP_Query( array(
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => '_thumbnail_id',
                    'compare' => 'EXISTS'
                )
            )
        ) );
        
        if ( $recent_posts->have_posts() ) :
        ?>
        <div class="recent-posts-section mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                <?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
                    <article class="recent-post card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail aspect-ratio-16-9">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <h3 class="post-title text-lg font-semibold mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-primary-500 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="post-meta text-sm text-gray-500 dark:text-gray-400 mb-3">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                            </div>
                            
                            <div class="post-excerpt text-gray-600 dark:text-gray-300 text-sm">
                                <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
        <?php 
        wp_reset_postdata();
        endif;
        ?>
    </div>
</section>
