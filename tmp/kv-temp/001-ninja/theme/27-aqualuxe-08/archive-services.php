<?php
/**
 * The template for displaying services archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-4xl md:text-5xl mb-4"><?php post_type_archive_title(); ?></h1>
            <div class="archive-description prose mx-auto">
                <?php the_archive_description(); ?>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="services-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all service categories
                    $service_categories = get_terms( array(
                        'taxonomy' => 'service_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $service_categories ) && ! is_wp_error( $service_categories ) ) : ?>
                        <div class="filter-group">
                            <label for="service_category" class="sr-only"><?php esc_html_e( 'Filter by Category', 'aqualuxe' ); ?></label>
                            <select name="service_category" id="service_category" class="form-input">
                                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                                <?php foreach ( $service_categories as $category ) : ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( isset( $_GET['service_category'] ) ? sanitize_text_field( $_GET['service_category'] ) : '', $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <div class="services-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'service-card card h-full flex flex-col' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="service-image relative overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-64 object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="service-content p-6 flex-grow flex flex-col">
                            <header class="entry-header mb-4">
                                <?php the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            </header>

                            <div class="entry-content mb-6 flex-grow">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-auto">
                                <?php
                                // Display service categories
                                $service_categories = get_the_terms( get_the_ID(), 'service_category' );
                                if ( $service_categories && ! is_wp_error( $service_categories ) ) : ?>
                                    <div class="service-categories flex flex-wrap gap-2 mb-4">
                                        <?php foreach ( $service_categories as $category ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                                <?php echo esc_html( $category->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn-primary"><?php esc_html_e( 'Learn More', 'aqualuxe' ); ?></a>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination( array(
                'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
                'class' => 'mt-12 flex justify-center',
            ) );

        else :
            get_template_part( 'template-parts/content/content', 'none' );
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_footer();