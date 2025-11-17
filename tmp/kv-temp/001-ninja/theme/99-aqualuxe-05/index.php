<?php
/**
 * Main template file
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="main-content" class="main-content" role="main">
    <div class="container mx-auto px-4 py-8">
        
        <?php if ( have_posts() ) : ?>
            
            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header class="page-header mb-8">
                    <h1 class="page-title text-3xl font-bold text-gray-900 dark:text-white">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>

            <div class="posts-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300' ); ?>>
                        
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail( 'aqualuxe-featured', array(
                                        'class' => 'w-full h-48 object-cover',
                                        'alt'   => get_the_title(),
                                    ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content p-6">
                            
                            <header class="post-header mb-4">
                                <?php
                                if ( is_singular() ) :
                                    the_title( '<h1 class="post-title text-2xl font-bold text-gray-900 dark:text-white mb-2">', '</h1>' );
                                else :
                                    the_title( '<h2 class="post-title text-xl font-semibold text-gray-900 dark:text-white mb-2"><a href="' . esc_url( get_permalink() ) . '" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">', '</a></h2>' );
                                endif;
                                ?>

                                <div class="post-meta text-sm text-gray-600 dark:text-gray-400">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                        <?php echo esc_html( get_the_date() ); ?>
                                    </time>
                                    <span class="mx-2">•</span>
                                    <span class="author">
                                        <?php printf( esc_html__( 'by %s', 'aqualuxe' ), get_the_author() ); ?>
                                    </span>
                                    <?php if ( has_category() ) : ?>
                                        <span class="mx-2">•</span>
                                        <span class="categories">
                                            <?php the_category( ', ' ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>

                            <div class="post-excerpt text-gray-700 dark:text-gray-300 mb-4">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more inline-flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors duration-200">
                                    <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </footer>

                        </div>

                    </article>

                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => esc_html__( '&laquo; Previous', 'aqualuxe' ),
                'next_text' => esc_html__( 'Next &raquo;', 'aqualuxe' ),
                'class'     => 'pagination flex justify-center mt-12',
            ) );
            ?>

        <?php else : ?>

            <section class="no-results text-center py-16">
                <div class="max-w-md mx-auto">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php esc_html_e( 'Nothing here', 'aqualuxe' ); ?>
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">
                        <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>
            </section>

        <?php endif; ?>

    </div>
</main>

<?php
get_sidebar();
get_footer();