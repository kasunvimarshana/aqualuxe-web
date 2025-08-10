<?php
/**
 * The template for displaying single project
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Get project meta
            $project_client = get_post_meta( get_the_ID(), 'project_client', true );
            $project_location = get_post_meta( get_the_ID(), 'project_location', true );
            $project_date = get_post_meta( get_the_ID(), 'project_date', true );
            $project_completion_date = get_post_meta( get_the_ID(), 'project_completion_date', true );
            $project_status = get_post_meta( get_the_ID(), 'project_status', true );
            $project_featured = get_post_meta( get_the_ID(), 'project_featured', true );
            $project_budget = get_post_meta( get_the_ID(), 'project_budget', true );
            $project_size = get_post_meta( get_the_ID(), 'project_size', true );
            $project_challenges = get_post_meta( get_the_ID(), 'project_challenges', true );
            $project_solutions = get_post_meta( get_the_ID(), 'project_solutions', true );
            $project_results = get_post_meta( get_the_ID(), 'project_results', true );
            $project_testimonial = get_post_meta( get_the_ID(), 'project_testimonial', true );
            $project_testimonial_author = get_post_meta( get_the_ID(), 'project_testimonial_author', true );
            $project_testimonial_position = get_post_meta( get_the_ID(), 'project_testimonial_position', true );
            $project_gallery = get_post_meta( get_the_ID(), 'project_gallery', true );
            $project_video = get_post_meta( get_the_ID(), 'project_video', true );
            
            // Format dates if available
            $formatted_date = '';
            if ( $project_date ) {
                $date_object = DateTime::createFromFormat( 'Y-m-d', $project_date );
                if ( $date_object ) {
                    $formatted_date = $date_object->format( get_option( 'date_format' ) );
                } else {
                    $formatted_date = $project_date;
                }
            }
            
            $formatted_completion_date = '';
            if ( $project_completion_date ) {
                $completion_date_object = DateTime::createFromFormat( 'Y-m-d', $project_completion_date );
                if ( $completion_date_object ) {
                    $formatted_completion_date = $completion_date_object->format( get_option( 'date_format' ) );
                } else {
                    $formatted_completion_date = $project_completion_date;
                }
            }
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-12 text-center">
                    <?php the_title( '<h1 class="entry-title text-4xl md:text-5xl lg:text-6xl mb-4">', '</h1>' ); ?>
                    
                    <?php if ( $project_status ) : ?>
                        <div class="project-status inline-block px-4 py-2 rounded-full text-lg font-medium mb-6
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
                    
                    <div class="project-meta flex flex-wrap justify-center gap-6 text-lg mb-6">
                        <?php if ( $project_client ) : ?>
                            <div class="project-client flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo esc_html( $project_client ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $project_location ) : ?>
                            <div class="project-location flex items-center">
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
                                <span>
                                    <?php 
                                    echo esc_html( $formatted_date );
                                    if ( $formatted_completion_date ) {
                                        echo ' - ' . esc_html( $formatted_completion_date );
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
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
                        <div class="project-terms flex flex-wrap justify-center gap-2 mb-6">
                            <?php foreach ( $project_terms as $term ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                    <?php echo esc_html( $term->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="entry-content">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="project-featured-image mb-12 rounded-lg overflow-hidden shadow-medium">
                            <?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-auto' ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="project-layout grid grid-cols-1 lg:grid-cols-12 gap-12">
                        <div class="project-main lg:col-span-8">
                            <div class="project-description prose max-w-none dark:prose-invert mb-12">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ( $project_challenges && is_array( $project_challenges ) && !empty( $project_challenges ) ) : ?>
                                <div class="project-challenges mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Challenges', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="bg-dark-50 dark:bg-dark-800 rounded-lg p-6">
                                        <ul class="space-y-4">
                                            <?php foreach ( $project_challenges as $challenge ) : 
                                                if ( empty( $challenge ) ) continue;
                                                ?>
                                                <li class="flex items-start">
                                                    <svg class="w-6 h-6 mr-3 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo wp_kses_post( $challenge ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $project_solutions && is_array( $project_solutions ) && !empty( $project_solutions ) ) : ?>
                                <div class="project-solutions mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Solutions', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="bg-primary-50 dark:bg-primary-900/30 rounded-lg p-6">
                                        <ul class="space-y-4">
                                            <?php foreach ( $project_solutions as $solution ) : 
                                                if ( empty( $solution ) ) continue;
                                                ?>
                                                <li class="flex items-start">
                                                    <svg class="w-6 h-6 mr-3 text-primary-600 dark:text-primary-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo wp_kses_post( $solution ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $project_results && is_array( $project_results ) && !empty( $project_results ) ) : ?>
                                <div class="project-results mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Results', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="bg-secondary-50 dark:bg-secondary-900/30 rounded-lg p-6">
                                        <ul class="space-y-4">
                                            <?php foreach ( $project_results as $result ) : 
                                                if ( empty( $result ) ) continue;
                                                ?>
                                                <li class="flex items-start">
                                                    <svg class="w-6 h-6 mr-3 text-secondary-600 dark:text-secondary-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo wp_kses_post( $result ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $project_gallery && is_array( $project_gallery ) && !empty( $project_gallery ) ) : ?>
                                <div class="project-gallery mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Project Gallery', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        <?php foreach ( $project_gallery as $image_id ) : 
                                            if ( empty( $image_id ) ) continue;
                                            
                                            $image_url = wp_get_attachment_image_url( $image_id, 'large' );
                                            $image_full_url = wp_get_attachment_image_url( $image_id, 'full' );
                                            $image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                                            
                                            if ( $image_url ) : ?>
                                                <a href="<?php echo esc_url( $image_full_url ); ?>" class="gallery-item block rounded-lg overflow-hidden shadow-soft hover:shadow-medium transition-shadow" data-fancybox="gallery">
                                                    <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" class="w-full h-48 object-cover">
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $project_video ) : ?>
                                <div class="project-video mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Project Video', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden shadow-medium">
                                        <?php echo wp_oembed_get( $project_video ); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $project_testimonial ) : ?>
                                <div class="project-testimonial mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Client Testimonial', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="bg-white dark:bg-dark-800 rounded-lg shadow-medium p-6 border-l-4 border-primary-500 dark:border-primary-600">
                                        <blockquote class="text-lg italic mb-4">
                                            <?php echo wp_kses_post( $project_testimonial ); ?>
                                        </blockquote>
                                        
                                        <?php if ( $project_testimonial_author ) : ?>
                                            <div class="testimonial-author font-medium">
                                                <?php echo esc_html( $project_testimonial_author ); ?>
                                                
                                                <?php if ( $project_testimonial_position ) : ?>
                                                    <span class="text-dark-500 dark:text-dark-400 font-normal">
                                                        <?php echo ', ' . esc_html( $project_testimonial_position ); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-navigation flex justify-between items-center pt-6 border-t border-dark-200 dark:border-dark-700">
                                <?php
                                $prev_post = get_previous_post();
                                $next_post = get_next_post();
                                ?>
                                
                                <div class="project-prev">
                                    <?php if ( $prev_post ) : ?>
                                        <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="hidden md:inline"><?php esc_html_e( 'Previous Project', 'aqualuxe' ); ?></span>
                                            <span class="md:hidden"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <a href="<?php echo esc_url( get_post_type_archive_link( 'projects' ) ); ?>" class="btn-outline text-sm">
                                    <?php esc_html_e( 'All Projects', 'aqualuxe' ); ?>
                                </a>
                                
                                <div class="project-next">
                                    <?php if ( $next_post ) : ?>
                                        <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                            <span class="hidden md:inline"><?php esc_html_e( 'Next Project', 'aqualuxe' ); ?></span>
                                            <span class="md:hidden"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
                                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="project-sidebar lg:col-span-4">
                            <div class="project-details bg-white dark:bg-dark-800 rounded-lg shadow-medium p-6 sticky top-32">
                                <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Project Details', 'aqualuxe' ); ?></h3>
                                
                                <ul class="project-details-list space-y-4">
                                    <?php if ( $project_client ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Client', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $project_client ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $project_location ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Location', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $project_location ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $formatted_date || $formatted_completion_date ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <?php if ( $formatted_date && $formatted_completion_date ) : ?>
                                                    <div class="font-medium"><?php esc_html_e( 'Timeline', 'aqualuxe' ); ?></div>
                                                    <div><?php echo esc_html( $formatted_date ) . ' - ' . esc_html( $formatted_completion_date ); ?></div>
                                                <?php elseif ( $formatted_date ) : ?>
                                                    <div class="font-medium"><?php esc_html_e( 'Start Date', 'aqualuxe' ); ?></div>
                                                    <div><?php echo esc_html( $formatted_date ); ?></div>
                                                <?php elseif ( $formatted_completion_date ) : ?>
                                                    <div class="font-medium"><?php esc_html_e( 'Completion Date', 'aqualuxe' ); ?></div>
                                                    <div><?php echo esc_html( $formatted_completion_date ); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $project_status ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Status', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $project_status ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $project_budget ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Budget', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $project_budget ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if ( $project_size ) : ?>
                                        <li class="flex">
                                            <div class="w-8 flex-shrink-0 text-primary-600 dark:text-primary-400">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.5 2a3.5 3.5 0 101.665 6.58L8.585 10l-1.42 1.42a3.5 3.5 0 101.414 1.414l8.128-8.127a1 1 0 00-1.414-1.414L10 8.586l-1.42-1.42A3.5 3.5 0 005.5 2zM4 5.5a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zm0 9a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" clip-rule="evenodd"></path>
                                                    <path d="M12.828 11.414a1 1 0 00-1.414 1.414l3.879 3.88a1 1 0 001.414-1.415l-3.879-3.879z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium"><?php esc_html_e( 'Size', 'aqualuxe' ); ?></div>
                                                <div><?php echo esc_html( $project_size ); ?></div>
                                            </div>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                
                                <div class="project-share mt-6 pt-6 border-t border-dark-200 dark:border-dark-700">
                                    <h4 class="font-medium mb-3"><?php esc_html_e( 'Share This Project', 'aqualuxe' ); ?></h4>
                                    
                                    <div class="flex space-x-4">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                            </svg>
                                        </a>
                                        
                                        <a href="mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                            // Related projects based on categories
                            $related_args = array(
                                'post_type' => 'projects',
                                'posts_per_page' => 3,
                                'post__not_in' => array( get_the_ID() ),
                                'orderby' => 'rand',
                            );
                            
                            if ( $project_categories ) {
                                $category_ids = array();
                                foreach ( $project_categories as $category ) {
                                    $category_ids[] = $category->term_id;
                                }
                                $related_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'project_category',
                                        'field' => 'term_id',
                                        'terms' => $category_ids,
                                    ),
                                );
                            }
                            
                            $related_projects = new WP_Query( $related_args );
                            
                            if ( $related_projects->have_posts() ) : ?>
                                <div class="related-projects mt-8">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Related Projects', 'aqualuxe' ); ?></h3>
                                    
                                    <div class="related-projects-list space-y-4">
                                        <?php while ( $related_projects->have_posts() ) : $related_projects->the_post(); ?>
                                            <a href="<?php the_permalink(); ?>" class="related-project-item block card overflow-hidden hover:shadow-medium transition-shadow">
                                                <div class="flex">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <div class="related-project-image w-24 h-24">
                                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="related-project-content p-4">
                                                        <h4 class="text-base font-medium"><?php the_title(); ?></h4>
                                                        
                                                        <?php
                                                        $rel_project_client = get_post_meta( get_the_ID(), 'project_client', true );
                                                        if ( $rel_project_client ) : ?>
                                                            <div class="text-sm text-dark-500 dark:text-dark-400">
                                                                <?php echo esc_html( $rel_project_client ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();