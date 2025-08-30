<?php
/**
 * The template for displaying events archive
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
            <div class="events-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all event categories
                    $event_categories = get_terms( array(
                        'taxonomy' => 'event_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $event_categories ) && ! is_wp_error( $event_categories ) ) : ?>
                        <div class="filter-group">
                            <label for="event_category" class="sr-only"><?php esc_html_e( 'Filter by Category', 'aqualuxe' ); ?></label>
                            <select name="event_category" id="event_category" class="form-input">
                                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                                <?php foreach ( $event_categories as $category ) : ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( isset( $_GET['event_category'] ) ? sanitize_text_field( $_GET['event_category'] ) : '', $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <div class="filter-group">
                        <label for="event_time" class="sr-only"><?php esc_html_e( 'Filter by Time', 'aqualuxe' ); ?></label>
                        <select name="event_time" id="event_time" class="form-input">
                            <option value=""><?php esc_html_e( 'All Events', 'aqualuxe' ); ?></option>
                            <option value="upcoming" <?php selected( isset( $_GET['event_time'] ) ? sanitize_text_field( $_GET['event_time'] ) : '', 'upcoming' ); ?>>
                                <?php esc_html_e( 'Upcoming Events', 'aqualuxe' ); ?>
                            </option>
                            <option value="past" <?php selected( isset( $_GET['event_time'] ) ? sanitize_text_field( $_GET['event_time'] ) : '', 'past' ); ?>>
                                <?php esc_html_e( 'Past Events', 'aqualuxe' ); ?>
                            </option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <div class="events-grid grid grid-cols-1 lg:grid-cols-2 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    
                    // Get event meta
                    $event_date = get_post_meta( get_the_ID(), 'event_date', true );
                    $event_time = get_post_meta( get_the_ID(), 'event_time', true );
                    $event_location = get_post_meta( get_the_ID(), 'event_location', true );
                    $event_is_virtual = get_post_meta( get_the_ID(), 'event_is_virtual', true );
                    
                    // Format date if available
                    $formatted_date = '';
                    if ( $event_date ) {
                        $date_object = DateTime::createFromFormat( 'Y-m-d', $event_date );
                        if ( $date_object ) {
                            $formatted_date = $date_object->format( get_option( 'date_format' ) );
                        } else {
                            $formatted_date = $event_date;
                        }
                    }
                    
                    // Check if event is past
                    $is_past_event = false;
                    if ( $event_date ) {
                        $today = new DateTime( 'today' );
                        $event_date_obj = DateTime::createFromFormat( 'Y-m-d', $event_date );
                        if ( $event_date_obj && $event_date_obj < $today ) {
                            $is_past_event = true;
                        }
                    }
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'event-card card h-full flex flex-col' . ( $is_past_event ? ' past-event opacity-75' : '' ) ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="event-image relative overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
                                </a>
                                
                                <?php if ( $is_past_event ) : ?>
                                    <div class="event-badge absolute top-4 right-4 bg-dark-800 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <?php esc_html_e( 'Past Event', 'aqualuxe' ); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="event-badge absolute top-4 right-4 bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <?php esc_html_e( 'Upcoming', 'aqualuxe' ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-content p-6 flex-grow flex flex-col">
                            <header class="entry-header mb-4">
                                <?php the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            </header>

                            <div class="event-meta mb-4 text-sm">
                                <?php if ( $formatted_date || $event_time ) : ?>
                                    <div class="event-datetime flex items-center mb-2">
                                        <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>
                                            <?php 
                                            if ( $formatted_date ) {
                                                echo esc_html( $formatted_date );
                                            }
                                            if ( $event_time ) {
                                                echo $formatted_date ? ' ' . esc_html__( 'at', 'aqualuxe' ) . ' ' . esc_html( $event_time ) : esc_html( $event_time );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $event_location || $event_is_virtual ) : ?>
                                    <div class="event-location flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>
                                            <?php 
                                            if ( $event_is_virtual ) {
                                                esc_html_e( 'Virtual Event', 'aqualuxe' );
                                            } elseif ( $event_location ) {
                                                echo esc_html( $event_location );
                                            }
                                            ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="entry-content mb-6 flex-grow">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-auto">
                                <?php
                                // Display event categories
                                $event_categories = get_the_terms( get_the_ID(), 'event_category' );
                                if ( $event_categories && ! is_wp_error( $event_categories ) ) : ?>
                                    <div class="event-categories flex flex-wrap gap-2 mb-4">
                                        <?php foreach ( $event_categories as $category ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                                <?php echo esc_html( $category->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn-primary">
                                    <?php 
                                    if ( $is_past_event ) {
                                        esc_html_e( 'View Details', 'aqualuxe' );
                                    } else {
                                        esc_html_e( 'Register Now', 'aqualuxe' );
                                    }
                                    ?>
                                </a>
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