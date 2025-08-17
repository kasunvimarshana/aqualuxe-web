<?php
/**
 * Template part for displaying the latest blog posts on the homepage
 *
 * @package AquaLuxe
 */

// Get latest posts section settings from customizer or use defaults
$section_title = get_theme_mod( 'aqualuxe_latest_posts_title', __( 'Latest From Our Blog', 'aqualuxe' ) );
$section_description = get_theme_mod( 'aqualuxe_latest_posts_description', __( 'Stay updated with the latest aquarium care guides, aquascaping tips, and industry news.', 'aqualuxe' ) );
$posts_count = get_theme_mod( 'aqualuxe_latest_posts_count', 3 );
$button_text = get_theme_mod( 'aqualuxe_latest_posts_button_text', __( 'View All Posts', 'aqualuxe' ) );
$button_url = get_theme_mod( 'aqualuxe_latest_posts_button_url', get_permalink( get_option( 'page_for_posts' ) ) );
$show_section = get_theme_mod( 'aqualuxe_latest_posts_show', true );

// If section is disabled, return
if ( ! $show_section ) {
    return;
}

// Get latest posts
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_count,
    'post_status'    => 'publish',
);

$latest_posts = new WP_Query( $args );

// If no posts found, return
if ( ! $latest_posts->have_posts() ) {
    return;
}
?>

<section class="latest-posts-section py-16 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_description ) : ?>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto"><?php echo esc_html( $section_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            while ( $latest_posts->have_posts() ) :
                $latest_posts->the_post();
                ?>
                <article class="post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg hover:-translate-y-1">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="post-thumbnail block">
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="post-content p-6">
                        <div class="post-meta flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                            <span class="post-date">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php echo get_the_date(); ?>
                            </span>
                            
                            <span class="post-categories ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <?php
                                $categories = get_the_category();
                                if ( ! empty( $categories ) ) {
                                    echo esc_html( $categories[0]->name );
                                }
                                ?>
                            </span>
                        </div>
                        
                        <h3 class="post-title text-xl font-bold mb-3">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary"><?php the_title(); ?></a>
                        </h3>
                        
                        <div class="post-excerpt text-gray-600 dark:text-gray-400 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="read-more text-primary hover:text-primary-dark font-medium flex items-center">
                            <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $button_text && $button_url ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $button_url ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <?php echo esc_html( $button_text ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>