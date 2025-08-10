<?php
/**
 * The template for displaying single service
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
            
            // Get service meta
            $service_price = get_post_meta( get_the_ID(), 'service_price', true );
            $service_duration = get_post_meta( get_the_ID(), 'service_duration', true );
            $service_features = get_post_meta( get_the_ID(), 'service_features', true );
            $service_cta_text = get_post_meta( get_the_ID(), 'service_cta_text', true ) ?: __( 'Book Now', 'aqualuxe' );
            $service_cta_link = get_post_meta( get_the_ID(), 'service_cta_link', true ) ?: '#booking-form';
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header mb-12 text-center">
                    <?php the_title( '<h1 class="entry-title text-4xl md:text-5xl lg:text-6xl mb-4">', '</h1>' ); ?>
                    
                    <div class="service-meta flex flex-wrap justify-center gap-6 text-lg mb-6">
                        <?php if ( $service_price ) : ?>
                            <div class="service-price flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95a1 1 0 001.715 1.029zM6 12a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm7 0a1 1 0 011-1h.01a1 1 0 110 2H14a1 1 0 01-1-1zm-.867-1a1 1 0 00-1 1v.932l-.366.138A1 1 0 009.4 14.932L10 15.5l.6-.568a1 1 0 00-.366-1.862l-.366-.138V12a1 1 0 00-1-1h-.134z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo esc_html( $service_price ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $service_duration ) : ?>
                            <div class="service-duration flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo esc_html( $service_duration ); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display service categories
                        $service_categories = get_the_terms( get_the_ID(), 'service_category' );
                        if ( $service_categories && ! is_wp_error( $service_categories ) ) : ?>
                            <div class="service-categories flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <span>
                                    <?php
                                    $category_names = array();
                                    foreach ( $service_categories as $category ) {
                                        $category_names[] = '<a href="' . esc_url( get_term_link( $category ) ) . '" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">' . esc_html( $category->name ) . '</a>';
                                    }
                                    echo implode( ', ', $category_names );
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="entry-content">
                    <div class="service-layout grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <div class="service-main lg:col-span-2">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="service-featured-image mb-8 rounded-lg overflow-hidden shadow-medium">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-description prose max-w-none dark:prose-invert">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ( $service_features ) : ?>
                                <div class="service-features mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Service Features', 'aqualuxe' ); ?></h2>
                                    
                                    <?php if ( is_array( $service_features ) ) : ?>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <?php foreach ( $service_features as $feature ) : ?>
                                                <li class="flex items-start">
                                                    <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo esc_html( $feature ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else : ?>
                                        <div class="prose max-w-none dark:prose-invert">
                                            <?php echo wp_kses_post( $service_features ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                            ?>
                        </div>
                        
                        <div class="service-sidebar">
                            <div class="service-booking card p-6 sticky top-32">
                                <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Book This Service', 'aqualuxe' ); ?></h3>
                                
                                <?php if ( $service_price ) : ?>
                                    <div class="service-price-large text-3xl font-bold text-primary-600 dark:text-primary-400 mb-4">
                                        <?php echo esc_html( $service_price ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="service-booking-cta mb-6">
                                    <a href="<?php echo esc_url( $service_cta_link ); ?>" class="btn-primary w-full text-center">
                                        <?php echo esc_html( $service_cta_text ); ?>
                                    </a>
                                </div>
                                
                                <div id="booking-form" class="service-booking-form">
                                    <?php
                                    // You can integrate a booking form here
                                    // For example, using Contact Form 7 or another booking plugin
                                    if ( function_exists( 'wpcf7_contact_form' ) ) {
                                        $booking_form_id = get_post_meta( get_the_ID(), 'service_booking_form_id', true );
                                        if ( $booking_form_id ) {
                                            echo do_shortcode( '[contact-form-7 id="' . esc_attr( $booking_form_id ) . '"]' );
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <?php
                            // Related services based on categories
                            $related_args = array(
                                'post_type' => 'services',
                                'posts_per_page' => 3,
                                'post__not_in' => array( get_the_ID() ),
                                'orderby' => 'rand',
                            );
                            
                            if ( $service_categories ) {
                                $category_ids = array();
                                foreach ( $service_categories as $category ) {
                                    $category_ids[] = $category->term_id;
                                }
                                $related_args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'service_category',
                                        'field' => 'term_id',
                                        'terms' => $category_ids,
                                    ),
                                );
                            }
                            
                            $related_services = new WP_Query( $related_args );
                            
                            if ( $related_services->have_posts() ) : ?>
                                <div class="related-services mt-8">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Related Services', 'aqualuxe' ); ?></h3>
                                    
                                    <div class="related-services-list space-y-4">
                                        <?php while ( $related_services->have_posts() ) : $related_services->the_post(); ?>
                                            <a href="<?php the_permalink(); ?>" class="related-service-item block card p-4 hover:shadow-medium transition-shadow">
                                                <div class="flex items-center">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <div class="related-service-image w-16 h-16 mr-4">
                                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded' ) ); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="related-service-content">
                                                        <h4 class="text-base font-medium"><?php the_title(); ?></h4>
                                                        <?php
                                                        $related_price = get_post_meta( get_the_ID(), 'service_price', true );
                                                        if ( $related_price ) : ?>
                                                            <div class="text-sm text-primary-600 dark:text-primary-400">
                                                                <?php echo esc_html( $related_price ); ?>
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