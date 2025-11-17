<?php
/**
 * Template for Blog Display Module
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get module settings
$title = $this->get_setting( 'title' );
$subtitle = $this->get_setting( 'subtitle' );
$description = $this->get_setting( 'description' );
$layout = $this->get_setting( 'layout', 'grid' );
$style = $this->get_setting( 'style', 'default' );
$columns = $this->get_setting( 'columns', 3 );
$show_image = $this->get_setting( 'show_image', true );
$show_date = $this->get_setting( 'show_date', true );
$show_author = $this->get_setting( 'show_author', true );
$show_excerpt = $this->get_setting( 'show_excerpt', true );
$show_categories = $this->get_setting( 'show_categories', true );
$show_tags = $this->get_setting( 'show_tags', false );
$show_comments = $this->get_setting( 'show_comments', true );
$show_readmore = $this->get_setting( 'show_readmore', true );
$animation = $this->get_setting( 'animation', 'fade' );
$pagination = $this->get_setting( 'pagination', 'none' );
$featured_post = $this->get_setting( 'featured_post', false );

// Get posts
$posts = $this->get_posts();

// Module classes
$module_classes = [
    'aqualuxe-blog-display',
    'aqualuxe-module',
    'layout-' . $layout,
    'style-' . $style,
    'columns-' . $columns,
    'animation-' . $animation,
];

$module_class = implode( ' ', $module_classes );
?>

<section id="<?php echo esc_attr( $this->get_id() ); ?>" class="<?php echo esc_attr( $module_class ); ?>">
    <div class="container mx-auto px-4">
        <?php if ( $title || $subtitle || $description ) : ?>
            <div class="aqualuxe-blog-display__header text-center mb-12">
                <?php if ( $subtitle ) : ?>
                    <div class="aqualuxe-blog-display__subtitle text-primary text-sm uppercase tracking-wider font-semibold mb-2">
                        <?php echo esc_html( $subtitle ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $title ) : ?>
                    <h2 class="aqualuxe-blog-display__title text-3xl md:text-4xl font-bold mb-4">
                        <?php echo esc_html( $title ); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ( $description ) : ?>
                    <div class="aqualuxe-blog-display__description max-w-2xl mx-auto text-gray-600 dark:text-gray-400">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $posts ) ) : ?>
            <div class="aqualuxe-blog-display__posts">
                <?php 
                // Check if we have a featured post
                $has_featured = false;
                if ( $featured_post && isset( $posts[0]['featured'] ) && $posts[0]['featured'] ) {
                    $has_featured = true;
                    $featured = $posts[0];
                    // Remove featured post from regular posts array
                    array_shift( $posts );
                ?>
                    <div class="aqualuxe-blog-display__featured-post mb-12">
                        <div class="featured-post grid grid-cols-1 md:grid-cols-2 gap-8 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                            <?php if ( $show_image && isset( $featured['image'] ) ) : ?>
                                <div class="featured-post__image">
                                    <a href="<?php echo esc_url( $featured['url'] ); ?>" class="block h-full">
                                        <img 
                                            src="<?php echo esc_url( $featured['image']['url'] ); ?>" 
                                            alt="<?php echo esc_attr( $featured['image']['alt'] ); ?>"
                                            class="w-full h-full object-cover"
                                        >
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="featured-post__content p-6 md:p-8 flex flex-col justify-center">
                                <?php if ( $show_categories && isset( $featured['categories'] ) && ! empty( $featured['categories'] ) ) : ?>
                                    <div class="featured-post__categories mb-3">
                                        <?php foreach ( $featured['categories'] as $category ) : ?>
                                            <a href="<?php echo esc_url( $category['url'] ); ?>" class="inline-block bg-primary bg-opacity-10 text-primary rounded-full px-3 py-1 text-xs font-semibold mr-2 mb-2">
                                                <?php echo esc_html( $category['name'] ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="featured-post__title text-2xl md:text-3xl font-bold mb-4">
                                    <a href="<?php echo esc_url( $featured['url'] ); ?>" class="hover:text-primary transition-colors duration-300">
                                        <?php echo esc_html( $featured['title'] ); ?>
                                    </a>
                                </h3>
                                
                                <?php if ( $show_excerpt && isset( $featured['excerpt'] ) ) : ?>
                                    <div class="featured-post__excerpt text-gray-600 dark:text-gray-400 mb-6">
                                        <?php echo wp_kses_post( $featured['excerpt'] ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="featured-post__meta flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400 mt-auto">
                                    <?php if ( $show_author && isset( $featured['author'] ) ) : ?>
                                        <span class="featured-post__author mr-4 mb-2">
                                            <span class="inline-block mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </span>
                                            <a href="<?php echo esc_url( $featured['author']['url'] ); ?>" class="hover:text-primary">
                                                <?php echo esc_html( $featured['author']['name'] ); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ( $show_date && isset( $featured['date'] ) ) : ?>
                                        <span class="featured-post__date mr-4 mb-2">
                                            <span class="inline-block mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <time datetime="<?php echo esc_attr( date( 'c', $featured['timestamp'] ) ); ?>">
                                                <?php echo esc_html( $featured['date'] ); ?>
                                            </time>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ( $show_comments ) : ?>
                                        <span class="featured-post__comments mb-2">
                                            <span class="inline-block mr-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                            </span>
                                            <?php 
                                                printf(
                                                    _n( '%s comment', '%s comments', $featured['comments'], 'aqualuxe' ),
                                                    number_format_i18n( $featured['comments'] )
                                                );
                                            ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( $show_readmore ) : ?>
                                    <div class="featured-post__readmore mt-6">
                                        <a href="<?php echo esc_url( $featured['url'] ); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300">
                                            <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                            <span class="ml-1">→</span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if ( ! empty( $posts ) ) : ?>
                    <div class="blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr( $columns ); ?> gap-6">
                        <?php foreach ( $posts as $post ) : ?>
                            <article class="blog-post">
                                <div class="blog-post__inner bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg h-full flex flex-col">
                                    <?php if ( $show_image && isset( $post['image'] ) ) : ?>
                                        <div class="blog-post__image">
                                            <a href="<?php echo esc_url( $post['url'] ); ?>" class="block">
                                                <img 
                                                    src="<?php echo esc_url( $post['image']['url'] ); ?>" 
                                                    alt="<?php echo esc_attr( $post['image']['alt'] ); ?>"
                                                    class="w-full h-48 object-cover"
                                                >
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="blog-post__content p-6 flex-grow">
                                        <?php if ( $show_categories && isset( $post['categories'] ) && ! empty( $post['categories'] ) ) : ?>
                                            <div class="blog-post__categories mb-3">
                                                <?php foreach ( $post['categories'] as $category ) : ?>
                                                    <a href="<?php echo esc_url( $category['url'] ); ?>" class="inline-block bg-primary bg-opacity-10 text-primary rounded-full px-3 py-1 text-xs font-semibold mr-2 mb-2">
                                                        <?php echo esc_html( $category['name'] ); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <h3 class="blog-post__title text-xl font-bold mb-3">
                                            <a href="<?php echo esc_url( $post['url'] ); ?>" class="hover:text-primary transition-colors duration-300">
                                                <?php echo esc_html( $post['title'] ); ?>
                                            </a>
                                        </h3>
                                        
                                        <?php if ( $show_excerpt && isset( $post['excerpt'] ) ) : ?>
                                            <div class="blog-post__excerpt text-gray-600 dark:text-gray-400 mb-4">
                                                <?php echo wp_kses_post( $post['excerpt'] ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="blog-post__footer p-6 pt-0 mt-auto">
                                        <div class="blog-post__meta flex flex-wrap items-center text-sm text-gray-500 dark:text-gray-400">
                                            <?php if ( $show_author && isset( $post['author'] ) ) : ?>
                                                <span class="blog-post__author mr-4 mb-2">
                                                    <span class="inline-block mr-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </span>
                                                    <a href="<?php echo esc_url( $post['author']['url'] ); ?>" class="hover:text-primary">
                                                        <?php echo esc_html( $post['author']['name'] ); ?>
                                                    </a>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ( $show_date && isset( $post['date'] ) ) : ?>
                                                <span class="blog-post__date mr-4 mb-2">
                                                    <span class="inline-block mr-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                    <time datetime="<?php echo esc_attr( date( 'c', $post['timestamp'] ) ); ?>">
                                                        <?php echo esc_html( $post['date'] ); ?>
                                                    </time>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if ( $show_comments ) : ?>
                                                <span class="blog-post__comments mb-2">
                                                    <span class="inline-block mr-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                        </svg>
                                                    </span>
                                                    <?php 
                                                        printf(
                                                            _n( '%s comment', '%s comments', $post['comments'], 'aqualuxe' ),
                                                            number_format_i18n( $post['comments'] )
                                                        );
                                                    ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ( $show_readmore ) : ?>
                                            <div class="blog-post__readmore mt-4">
                                                <a href="<?php echo esc_url( $post['url'] ); ?>" class="inline-block text-primary hover:text-primary-dark font-medium transition-colors duration-300">
                                                    <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                                    <span class="ml-1">→</span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $pagination !== 'none' ) : ?>
                    <div class="aqualuxe-blog-display__pagination mt-12 flex justify-center">
                        <?php if ( $pagination === 'numbers' ) : ?>
                            <div class="pagination-numbers">
                                <!-- Pagination will be handled by JavaScript -->
                                <div class="flex items-center space-x-1">
                                    <a href="#" class="pagination-prev px-4 py-2 text-gray-500 bg-gray-200 rounded-md disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <div class="pagination-numbers-container flex items-center space-x-1">
                                        <!-- Page numbers will be inserted here by JavaScript -->
                                    </div>
                                    <a href="#" class="pagination-next px-4 py-2 text-gray-500 bg-gray-200 rounded-md disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-700 dark:text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        <?php elseif ( $pagination === 'load_more' ) : ?>
                            <button class="load-more-btn bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300">
                                <?php esc_html_e( 'Load More', 'aqualuxe' ); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php else : ?>
            <div class="aqualuxe-blog-display__no-posts text-center py-12">
                <p><?php esc_html_e( 'No posts found.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>