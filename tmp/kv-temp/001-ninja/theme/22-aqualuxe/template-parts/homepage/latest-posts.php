<?php
/**
 * Template part for displaying latest posts section on the homepage
 *
 * @package AquaLuxe
 */

// Get section settings from theme options or use defaults
$section_title = aqualuxe_get_option( 'latest_posts_title', 'Latest News & Articles' );
$section_subtitle = aqualuxe_get_option( 'latest_posts_subtitle', 'Stay updated with our latest news, tips, and insights' );
$posts_count = aqualuxe_get_option( 'latest_posts_count', 3 );
$show_view_all = aqualuxe_get_option( 'latest_posts_show_view_all', true );
$view_all_text = aqualuxe_get_option( 'latest_posts_view_all_text', 'View All Posts' );
$posts_layout = aqualuxe_get_option( 'latest_posts_layout', 'grid' );
$posts_category = aqualuxe_get_option( 'latest_posts_category', '' );

// Set up the query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $posts_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
);

// Add category filter if needed
if ( ! empty( $posts_category ) ) {
    $args['category_name'] = $posts_category;
}

// Run the query
$latest_posts = new WP_Query( $args );

// Only display the section if we have posts
if ( ! $latest_posts->have_posts() ) {
    return;
}

// Set up grid columns class
$grid_columns_class = '';
switch ( $posts_count ) {
    case 1:
        $grid_columns_class = 'grid-cols-1';
        break;
    case 2:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-2';
        break;
    case 3:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-3';
        break;
    case 4:
        $grid_columns_class = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
        break;
    default:
        $grid_columns_class = 'grid-cols-1 md:grid-cols-3';
}

// Set up layout specific classes
$container_class = '';
$item_class = '';

switch ( $posts_layout ) {
    case 'list':
        $container_class = 'space-y-8';
        $item_class = 'post-item flex flex-col md:flex-row gap-6';
        break;
    case 'masonry':
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'post-item';
        break;
    case 'minimal':
        $container_class = 'space-y-6';
        $item_class = 'post-item border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0';
        break;
    case 'grid':
    default:
        $container_class = 'grid ' . $grid_columns_class . ' gap-6';
        $item_class = 'post-item card overflow-hidden';
}

// Get blog page URL
$blog_page_id = get_option( 'page_for_posts' );
$view_all_url = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/' );

// If a category is specified, link to that category archive
if ( ! empty( $posts_category ) ) {
    $category = get_category_by_slug( $posts_category );
    if ( $category ) {
        $view_all_url = get_category_link( $category->term_id );
    }
}
?>

<section id="latest-posts" class="latest-posts py-16 bg-white dark:bg-dark-500">
    <div class="container-fluid max-w-screen-xl mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-serif font-bold mb-4"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <p class="section-subtitle text-lg text-gray-600 dark:text-gray-300"><?php echo esc_html( $section_subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="posts-container <?php echo esc_attr( $container_class ); ?>">
            <?php
            while ( $latest_posts->have_posts() ) :
                $latest_posts->the_post();
                
                // Get post details
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_permalink = get_permalink();
                $post_date = get_the_date();
                $post_author = get_the_author();
                $post_categories = get_the_category();
                ?>
                
                <?php if ( $posts_layout === 'list' ) : ?>
                    <article class="<?php echo esc_attr( $item_class ); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-image md:w-1/3">
                                <a href="<?php echo esc_url( $post_permalink ); ?>" class="block overflow-hidden rounded-lg">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content md:w-2/3">
                            <?php if ( ! empty( $post_categories ) ) : ?>
                                <div class="post-categories mb-2">
                                    <?php
                                    $category = $post_categories[0];
                                    ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="inline-block text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 px-2 py-1 rounded">
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="post-title text-xl font-bold mb-2">
                                <a href="<?php echo esc_url( $post_permalink ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                    <?php echo esc_html( $post_title ); ?>
                                </a>
                            </h3>
                            
                            <div class="post-meta flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <span class="post-date flex items-center mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo esc_html( $post_date ); ?>
                                </span>
                                
                                <span class="post-author flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <?php echo esc_html( $post_author ); ?>
                                </span>
                            </div>
                            
                            <div class="post-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo wp_kses_post( $post_excerpt ); ?>
                            </div>
                            
                            <a href="<?php echo esc_url( $post_permalink ); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </article>
                
                <?php elseif ( $posts_layout === 'minimal' ) : ?>
                    <article class="<?php echo esc_attr( $item_class ); ?>">
                        <div class="post-meta flex items-center text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span class="post-date flex items-center mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <?php echo esc_html( $post_date ); ?>
                            </span>
                            
                            <?php if ( ! empty( $post_categories ) ) : ?>
                                <span class="post-category">
                                    <?php
                                    $category = $post_categories[0];
                                    ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="post-title text-xl font-bold mb-2">
                            <a href="<?php echo esc_url( $post_permalink ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                <?php echo esc_html( $post_title ); ?>
                            </a>
                        </h3>
                        
                        <a href="<?php echo esc_url( $post_permalink ); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200">
                            <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </article>
                
                <?php else : /* Default grid/masonry layout */ ?>
                    <article class="<?php echo esc_attr( $item_class ); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-image">
                                <a href="<?php echo esc_url( $post_permalink ); ?>" class="block overflow-hidden">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-details p-4">
                            <?php if ( ! empty( $post_categories ) ) : ?>
                                <div class="post-categories mb-2">
                                    <?php
                                    $category = $post_categories[0];
                                    ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="inline-block text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 px-2 py-1 rounded">
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="post-title text-lg font-bold mb-2">
                                <a href="<?php echo esc_url( $post_permalink ); ?>" class="hover:text-primary-500 transition-colors duration-200">
                                    <?php echo esc_html( $post_title ); ?>
                                </a>
                            </h3>
                            
                            <div class="post-meta flex items-center text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <span class="post-date flex items-center mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo esc_html( $post_date ); ?>
                                </span>
                            </div>
                            
                            <div class="post-excerpt text-sm text-gray-600 dark:text-gray-300 mb-4">
                                <?php echo wp_trim_words( $post_excerpt, 15 ); ?>
                            </div>
                            
                            <a href="<?php echo esc_url( $post_permalink ); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-600 transition-colors duration-200 text-sm">
                                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <?php if ( $show_view_all ) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url( $view_all_url ); ?>" class="btn-outline">
                    <?php echo esc_html( $view_all_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>