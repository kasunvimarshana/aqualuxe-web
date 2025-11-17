<?php
/**
 * The template for displaying archive pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<main id="primary" class="site-main container py-8" role="main">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-3">
            <?php if ( have_posts() ) : ?>
                <header class="page-header mb-8">
                    <?php aqualuxe_breadcrumbs(); ?>
                    
                    <h1 class="page-title text-4xl font-serif font-bold text-gray-900 dark:text-white mt-6 mb-4">
                        <?php the_archive_title(); ?>
                    </h1>
                    
                    <?php
                    $archive_description = get_the_archive_description();
                    if ( $archive_description ) : ?>
                        <div class="archive-description text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
                            <?php echo wp_kses_post( $archive_description ); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="posts-grid grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300' ); ?>>
                            <?php if ( aqualuxe_can_show_post_thumbnail() ) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php echo esc_url( get_permalink() ); ?>">
                                        <?php aqualuxe_post_thumbnail( 'medium' ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="post-content p-6">
                                <header class="entry-header mb-4">
                                    <?php the_title( '<h2 class="entry-title text-xl font-semibold text-gray-900 dark:text-white mb-2"><a href="' . esc_url( get_permalink() ) . '" class="hover:text-primary-600 dark:hover:text-primary-400">', '</a></h2>' ); ?>
                                    
                                    <div class="entry-meta flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <?php aqualuxe_posted_on(); ?>
                                        <?php aqualuxe_reading_time(); ?>
                                    </div>
                                </header>

                                <div class="entry-summary text-gray-600 dark:text-gray-400 mb-4">
                                    <?php the_excerpt(); ?>
                                </div>

                                <div class="entry-footer">
                                    <a href="<?php echo esc_url( get_permalink() ); ?>" class="read-more-link text-primary-600 dark:text-primary-400 hover:underline font-medium">
                                        <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="pagination-wrapper mt-12">
                    <?php
                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => __( 'Previous', 'aqualuxe' ),
                        'next_text' => __( 'Next', 'aqualuxe' ),
                        'class'     => 'pagination flex justify-center items-center space-x-2',
                    ) );
                    ?>
                </div>

            <?php else : ?>
                <section class="no-results not-found">
                    <header class="page-header mb-8">
                        <h1 class="page-title text-4xl font-serif font-bold text-gray-900 dark:text-white mb-4">
                            <?php esc_html_e( 'Nothing here', 'aqualuxe' ); ?>
                        </h1>
                    </header>

                    <div class="page-content text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <div class="lg:col-span-1">
            <?php get_sidebar(); ?>
        </div>
    </div>
</main>

<?php
get_footer();