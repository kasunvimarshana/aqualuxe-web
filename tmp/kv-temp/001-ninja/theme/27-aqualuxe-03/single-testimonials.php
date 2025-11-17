<?php
/**
 * The template for displaying single testimonial
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
            
            // Get testimonial meta
            $testimonial_rating = get_post_meta( get_the_ID(), 'testimonial_rating', true );
            $testimonial_author = get_post_meta( get_the_ID(), 'testimonial_author', true );
            $testimonial_position = get_post_meta( get_the_ID(), 'testimonial_position', true );
            $testimonial_company = get_post_meta( get_the_ID(), 'testimonial_company', true );
            $testimonial_location = get_post_meta( get_the_ID(), 'testimonial_location', true );
            $testimonial_date = get_post_meta( get_the_ID(), 'testimonial_date', true );
            $testimonial_verified = get_post_meta( get_the_ID(), 'testimonial_verified', true );
            $testimonial_product = get_post_meta( get_the_ID(), 'testimonial_product', true );
            $testimonial_service = get_post_meta( get_the_ID(), 'testimonial_service', true );
            
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

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="testimonial-layout max-w-4xl mx-auto">
                    <div class="testimonial-card bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden">
                        <div class="testimonial-header p-8 bg-primary-50 dark:bg-primary-900/30 border-b border-primary-100 dark:border-primary-800">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="flex items-center">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="testimonial-avatar mr-4">
                                            <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-16 h-16 rounded-full object-cover' ) ); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <?php if ( $testimonial_author ) : ?>
                                            <h1 class="testimonial-author text-2xl font-bold">
                                                <?php echo esc_html( $testimonial_author ); ?>
                                                
                                                <?php if ( $testimonial_verified ) : ?>
                                                    <span class="verified-badge ml-2 text-sm bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-0.5 rounded-full">
                                                        <?php esc_html_e( 'Verified', 'aqualuxe' ); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </h1>
                                        <?php else : ?>
                                            <h1 class="testimonial-title text-2xl font-bold"><?php the_title(); ?></h1>
                                        <?php endif; ?>
                                        
                                        <?php if ( $testimonial_position || $testimonial_company ) : ?>
                                            <div class="testimonial-position text-dark-600 dark:text-dark-400">
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
                                            <div class="testimonial-details text-sm text-dark-500 dark:text-dark-500 mt-1">
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
                                
                                <?php if ( $testimonial_rating ) : ?>
                                    <div class="testimonial-rating flex">
                                        <?php
                                        $rating = min( 5, max( 1, intval( $testimonial_rating ) ) );
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            if ( $i <= $rating ) {
                                                echo '<svg class="w-6 h-6 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                            } else {
                                                echo '<svg class="w-6 h-6 text-dark-300 dark:text-dark-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php
                            // Display testimonial categories
                            $testimonial_categories = get_the_terms( get_the_ID(), 'testimonial_category' );
                            if ( $testimonial_categories && ! is_wp_error( $testimonial_categories ) ) : ?>
                                <div class="testimonial-categories flex flex-wrap gap-2 mt-4">
                                    <?php foreach ( $testimonial_categories as $category ) : ?>
                                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                            <?php echo esc_html( $category->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="testimonial-content p-8">
                            <?php if ( $testimonial_product || $testimonial_service ) : ?>
                                <div class="testimonial-about mb-6 p-4 bg-dark-50 dark:bg-dark-800 rounded-lg">
                                    <h3 class="text-lg font-bold mb-2"><?php esc_html_e( 'About this testimonial', 'aqualuxe' ); ?></h3>
                                    
                                    <?php if ( $testimonial_product ) : 
                                        $product = wc_get_product( $testimonial_product );
                                        if ( $product ) : ?>
                                            <div class="testimonial-product flex items-center mb-2">
                                                <span class="font-medium mr-2"><?php esc_html_e( 'Product:', 'aqualuxe' ); ?></span>
                                                <a href="<?php echo esc_url( get_permalink( $testimonial_product ) ); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <?php echo esc_html( $product->get_name() ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php if ( $testimonial_service ) : 
                                        $service = get_post( $testimonial_service );
                                        if ( $service ) : ?>
                                            <div class="testimonial-service flex items-center">
                                                <span class="font-medium mr-2"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></span>
                                                <a href="<?php echo esc_url( get_permalink( $testimonial_service ) ); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <?php echo esc_html( $service->post_title ); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="testimonial-text prose max-w-none dark:prose-invert">
                                <blockquote class="text-xl italic font-medium mb-6">
                                    <?php the_content(); ?>
                                </blockquote>
                            </div>
                            
                            <div class="testimonial-footer mt-8 pt-6 border-t border-dark-200 dark:border-dark-700 flex justify-between items-center">
                                <div class="testimonial-share">
                                    <span class="text-sm font-medium mr-2"><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></span>
                                    
                                    <div class="inline-flex space-x-2">
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
                                
                                <a href="<?php echo esc_url( get_post_type_archive_link( 'testimonials' ) ); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php esc_html_e( 'Back to all testimonials', 'aqualuxe' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    // Related testimonials based on categories
                    $related_args = array(
                        'post_type' => 'testimonials',
                        'posts_per_page' => 3,
                        'post__not_in' => array( get_the_ID() ),
                        'orderby' => 'rand',
                    );
                    
                    if ( $testimonial_categories ) {
                        $category_ids = array();
                        foreach ( $testimonial_categories as $category ) {
                            $category_ids[] = $category->term_id;
                        }
                        $related_args['tax_query'] = array(
                            array(
                                'taxonomy' => 'testimonial_category',
                                'field' => 'term_id',
                                'terms' => $category_ids,
                            ),
                        );
                    }
                    
                    $related_testimonials = new WP_Query( $related_args );
                    
                    if ( $related_testimonials->have_posts() ) : ?>
                        <div class="related-testimonials mt-12">
                            <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'More Testimonials', 'aqualuxe' ); ?></h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <?php while ( $related_testimonials->have_posts() ) : $related_testimonials->the_post(); 
                                    $rel_testimonial_rating = get_post_meta( get_the_ID(), 'testimonial_rating', true );
                                    $rel_testimonial_author = get_post_meta( get_the_ID(), 'testimonial_author', true );
                                    ?>
                                    <div class="related-testimonial-card card p-6">
                                        <?php if ( $rel_testimonial_rating ) : ?>
                                            <div class="testimonial-rating flex mb-3">
                                                <?php
                                                $rating = min( 5, max( 1, intval( $rel_testimonial_rating ) ) );
                                                for ( $i = 1; $i <= 5; $i++ ) {
                                                    if ( $i <= $rating ) {
                                                        echo '<svg class="w-4 h-4 text-accent-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                                    } else {
                                                        echo '<svg class="w-4 h-4 text-dark-300 dark:text-dark-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <blockquote class="text-sm italic mb-4">
                                            <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                        </blockquote>
                                        
                                        <div class="flex items-center">
                                            <?php if ( has_post_thumbnail() ) : ?>
                                                <div class="mr-3">
                                                    <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-10 h-10 rounded-full object-cover' ) ); ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div>
                                                <div class="font-medium">
                                                    <?php echo $rel_testimonial_author ? esc_html( $rel_testimonial_author ) : get_the_title(); ?>
                                                </div>
                                                <a href="<?php the_permalink(); ?>" class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();