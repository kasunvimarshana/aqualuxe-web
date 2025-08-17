<?php
/**
 * The template for displaying testimonials archive
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
            <div class="testimonials-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all testimonial categories
                    $testimonial_categories = get_terms( array(
                        'taxonomy' => 'testimonial_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $testimonial_categories ) && ! is_wp_error( $testimonial_categories ) ) : ?>
                        <div class="filter-group">
                            <label for="testimonial_category" class="sr-only"><?php esc_html_e( 'Filter by Category', 'aqualuxe' ); ?></label>
                            <select name="testimonial_category" id="testimonial_category" class="form-input">
                                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                                <?php foreach ( $testimonial_categories as $category ) : ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( isset( $_GET['testimonial_category'] ) ? sanitize_text_field( $_GET['testimonial_category'] ) : '', $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="filter-group">
                        <label for="testimonial_rating" class="sr-only"><?php esc_html_e( 'Filter by Rating', 'aqualuxe' ); ?></label>
                        <select name="testimonial_rating" id="testimonial_rating" class="form-input">
                            <option value=""><?php esc_html_e( 'All Ratings', 'aqualuxe' ); ?></option>
                            <option value="5" <?php selected( isset( $_GET['testimonial_rating'] ) ? sanitize_text_field( $_GET['testimonial_rating'] ) : '', '5' ); ?>>
                                <?php esc_html_e( '5 Stars', 'aqualuxe' ); ?>
                            </option>
                            <option value="4" <?php selected( isset( $_GET['testimonial_rating'] ) ? sanitize_text_field( $_GET['testimonial_rating'] ) : '', '4' ); ?>>
                                <?php esc_html_e( '4 Stars & Up', 'aqualuxe' ); ?>
                            </option>
                            <option value="3" <?php selected( isset( $_GET['testimonial_rating'] ) ? sanitize_text_field( $_GET['testimonial_rating'] ) : '', '3' ); ?>>
                                <?php esc_html_e( '3 Stars & Up', 'aqualuxe' ); ?>
                            </option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    
                    // Get testimonial meta
                    $testimonial_rating = get_post_meta( get_the_ID(), 'testimonial_rating', true );
                    $testimonial_author = get_post_meta( get_the_ID(), 'testimonial_author', true );
                    $testimonial_position = get_post_meta( get_the_ID(), 'testimonial_position', true );
                    $testimonial_company = get_post_meta( get_the_ID(), 'testimonial_company', true );
                    $testimonial_location = get_post_meta( get_the_ID(), 'testimonial_location', true );
                    $testimonial_date = get_post_meta( get_the_ID(), 'testimonial_date', true );
                    $testimonial_verified = get_post_meta( get_the_ID(), 'testimonial_verified', true );
                    
                    // Format date if available
                    $formatted_date = '';
                    if ( $testimonial_date ) {
                        $date_object = DateTime::createFromFormat( 'Y-m-d', $testimonial_date );
                        if ( $date_object ) {
                            $formatted_date = $date_object->format( get_option( 'date_format' ) );
                        } else {
                            $formatted_date = $testimonial_date;
                        }
                    }
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'testimonial-card card h-full flex flex-col' ); ?>>
                        <div class="testimonial-content p-6 flex-grow flex flex-col">
                            <?php if ( $testimonial_rating ) : ?>
                                <div class="testimonial-rating flex mb-4">
                                    <?php
                                    $rating = min( 5, max( 1, intval( $testimonial_rating ) ) );
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $rating ) {
                                            echo '<svg class="w-5 h-5 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                        } else {
                                            echo '<svg class="w-5 h-5 text-dark-300 dark:text-dark-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-text mb-6 flex-grow">
                                <?php if ( has_excerpt() ) : ?>
                                    <blockquote class="text-lg italic">
                                        <?php the_excerpt(); ?>
                                    </blockquote>
                                <?php else : ?>
                                    <blockquote class="text-lg italic">
                                        <?php echo wp_trim_words( get_the_content(), 30, '...' ); ?>
                                    </blockquote>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm mt-2 inline-block">
                                    <?php esc_html_e( 'Read full testimonial', 'aqualuxe' ); ?>
                                </a>
                            </div>

                            <footer class="testimonial-meta mt-auto">
                                <div class="flex items-center">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="testimonial-avatar mr-4">
                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-12 h-12 rounded-full object-cover' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <?php if ( $testimonial_author ) : ?>
                                            <div class="testimonial-author font-bold">
                                                <?php echo esc_html( $testimonial_author ); ?>
                                                
                                                <?php if ( $testimonial_verified ) : ?>
                                                    <span class="verified-badge ml-1 text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-0.5 rounded-full">
                                                        <?php esc_html_e( 'Verified', 'aqualuxe' ); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( $testimonial_position || $testimonial_company ) : ?>
                                            <div class="testimonial-position text-sm text-dark-600 dark:text-dark-400">
                                                <?php 
                                                if ( $testimonial_position ) {
                                                    echo esc_html( $testimonial_position );
                                                }
                                                
                                                if ( $testimonial_position && $testimonial_company ) {
                                                    echo ', ';
                                                }
                                                
                                                if ( $testimonial_company ) {
                                                    echo esc_html( $testimonial_company );
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( $testimonial_location || $formatted_date ) : ?>
                                            <div class="testimonial-details text-xs text-dark-500 dark:text-dark-500 mt-1">
                                                <?php 
                                                if ( $testimonial_location ) {
                                                    echo esc_html( $testimonial_location );
                                                }
                                                
                                                if ( $testimonial_location && $formatted_date ) {
                                                    echo ' • ';
                                                }
                                                
                                                if ( $formatted_date ) {
                                                    echo esc_html( $formatted_date );
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
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