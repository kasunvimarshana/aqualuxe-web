<?php
/**
 * The template for displaying projects archive
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
            <div class="projects-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all project categories
                    $project_categories = get_terms( array(
                        'taxonomy' => 'project_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $project_categories ) && ! is_wp_error( $project_categories ) ) : ?>
                        <div class="filter-group">
                            <label for="project_category" class="sr-only"><?php esc_html_e( 'Filter by Category', 'aqualuxe' ); ?></label>
                            <select name="project_category" id="project_category" class="form-input">
                                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                                <?php foreach ( $project_categories as $category ) : ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( isset( $_GET['project_category'] ) ? sanitize_text_field( $_GET['project_category'] ) : '', $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Get all project types
                    $project_types = get_terms( array(
                        'taxonomy' => 'project_type',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $project_types ) && ! is_wp_error( $project_types ) ) : ?>
                        <div class="filter-group">
                            <label for="project_type" class="sr-only"><?php esc_html_e( 'Filter by Type', 'aqualuxe' ); ?></label>
                            <select name="project_type" id="project_type" class="form-input">
                                <option value=""><?php esc_html_e( 'All Types', 'aqualuxe' ); ?></option>
                                <?php foreach ( $project_types as $type ) : ?>
                                    <option value="<?php echo esc_attr( $type->slug ); ?>" <?php selected( isset( $_GET['project_type'] ) ? sanitize_text_field( $_GET['project_type'] ) : '', $type->slug ); ?>>
                                        <?php echo esc_html( $type->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <div class="projects-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    
                    // Get project meta
                    $project_client = get_post_meta( get_the_ID(), 'project_client', true );
                    $project_location = get_post_meta( get_the_ID(), 'project_location', true );
                    $project_date = get_post_meta( get_the_ID(), 'project_date', true );
                    $project_status = get_post_meta( get_the_ID(), 'project_status', true );
                    $project_featured = get_post_meta( get_the_ID(), 'project_featured', true );
                    
                    // Format date if available
                    $formatted_date = '';
                    if ( $project_date ) {
                        $date_object = DateTime::createFromFormat( 'Y-m-d', $project_date );
                        if ( $date_object ) {
                            $formatted_date = $date_object->format( get_option( 'date_format' ) );
                        } else {
                            $formatted_date = $project_date;
                        }
                    }
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'project-card card h-full flex flex-col' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="project-image relative overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-64 object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
                                </a>
                                
                                <?php if ( $project_status ) : ?>
                                    <div class="project-status absolute top-4 right-4 px-3 py-1 rounded-full text-sm font-medium
                                        <?php 
                                        switch ( strtolower( $project_status ) ) {
                                            case 'completed':
                                                echo 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                                break;
                                            case 'in progress':
                                                echo 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                                                break;
                                            case 'upcoming':
                                                echo 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200';
                                                break;
                                            default:
                                                echo 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200';
                                        }
                                        ?>">
                                        <?php echo esc_html( $project_status ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $project_featured ) : ?>
                                    <div class="project-featured absolute top-4 left-4 bg-accent-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="project-content p-6 flex-grow flex flex-col">
                            <header class="entry-header mb-4">
                                <?php the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                            </header>

                            <div class="project-meta mb-4 text-sm">
                                <?php if ( $project_client ) : ?>
                                    <div class="project-client flex items-center mb-2">
                                        <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span><?php echo esc_html( $project_client ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $project_location ) : ?>
                                    <div class="project-location flex items-center mb-2">
                                        <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span><?php echo esc_html( $project_location ); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $formatted_date ) : ?>
                                    <div class="project-date flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span><?php echo esc_html( $formatted_date ); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="entry-content mb-6 flex-grow">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-auto">
                                <?php
                                // Display project categories and types
                                $project_terms = array();
                                $project_categories = get_the_terms( get_the_ID(), 'project_category' );
                                $project_types = get_the_terms( get_the_ID(), 'project_type' );
                                
                                if ( $project_categories && ! is_wp_error( $project_categories ) ) {
                                    $project_terms = array_merge( $project_terms, $project_categories );
                                }
                                
                                if ( $project_types && ! is_wp_error( $project_types ) ) {
                                    $project_terms = array_merge( $project_terms, $project_types );
                                }
                                
                                if ( !empty( $project_terms ) ) : ?>
                                    <div class="project-terms flex flex-wrap gap-2 mb-4">
                                        <?php foreach ( $project_terms as $term ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                                <?php echo esc_html( $term->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn-primary"><?php esc_html_e( 'View Project', 'aqualuxe' ); ?></a>
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