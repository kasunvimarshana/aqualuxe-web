<?php
/**
 * Template part for displaying the homepage blog section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get section settings from theme options
$section_title = get_theme_mod('aqualuxe_blog_title', __('Latest News & Articles', 'aqualuxe'));
$section_subtitle = get_theme_mod('aqualuxe_blog_subtitle', __('Our Blog', 'aqualuxe'));
$section_description = get_theme_mod('aqualuxe_blog_description', __('Stay updated with the latest news, tips, and insights from the world of ornamental fish farming.', 'aqualuxe'));
$posts_count = get_theme_mod('aqualuxe_blog_count', 3);
$view_all_text = get_theme_mod('aqualuxe_blog_view_all', __('View All Posts', 'aqualuxe'));
$section_background = get_theme_mod('aqualuxe_blog_background', 'white');
$blog_category = get_theme_mod('aqualuxe_blog_category', '');

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="blog-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="blog-posts grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            // Query blog posts
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => $posts_count,
                'post_status'    => 'publish',
            );
            
            // Add category filter if set
            if ($blog_category) {
                $args['cat'] = $blog_category;
            }
            
            $blog_query = new WP_Query($args);
            
            if ($blog_query->have_posts()) :
                while ($blog_query->have_posts()) :
                    $blog_query->the_post();
                    ?>
                    
                    <article class="blog-card bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:transform hover:scale-105">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'w-full h-56 object-cover')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content p-6">
                            <div class="post-meta flex items-center text-sm text-gray-600 mb-2">
                                <span class="post-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>
                                
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    ?>
                                    <span class="post-category ml-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="hover:text-primary">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="post-title text-xl font-bold mb-3">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                            
                            <div class="post-excerpt text-gray-600 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="post-link text-primary hover:text-primary-dark font-medium inline-flex items-center">
                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </article>
                    
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="no-posts col-span-full text-center py-8">
                    <p><?php esc_html_e('No posts found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($view_all_text) : ?>
            <div class="view-all text-center mt-12">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="button button-primary">
                    <?php echo esc_html($view_all_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>