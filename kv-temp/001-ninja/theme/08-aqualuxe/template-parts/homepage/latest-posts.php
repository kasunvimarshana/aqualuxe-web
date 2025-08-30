<?php
/**
 * Template part for displaying latest blog posts on the homepage
 *
 * @package AquaLuxe
 */

// Get latest posts section settings from customizer
$latest_posts_title = get_theme_mod('aqualuxe_latest_posts_title', __('Latest From Our Blog', 'aqualuxe'));
$latest_posts_description = get_theme_mod('aqualuxe_latest_posts_description', __('Stay updated with our latest news and articles', 'aqualuxe'));
$latest_posts_count = get_theme_mod('aqualuxe_latest_posts_count', 3);
$latest_posts_enable = get_theme_mod('aqualuxe_latest_posts_enable', true);
$view_all_text = get_theme_mod('aqualuxe_latest_posts_view_all_text', __('View All Posts', 'aqualuxe'));

// Exit if latest posts section is disabled
if (!$latest_posts_enable) {
    return;
}

// Get blog page URL
$blog_page_url = get_permalink(get_option('page_for_posts'));
?>

<section class="latest-posts">
    <div class="container">
        <div class="section-header">
            <?php if ($latest_posts_title) : ?>
                <h2 class="section-title"><?php echo esc_html($latest_posts_title); ?></h2>
            <?php endif; ?>
            
            <?php if ($latest_posts_description) : ?>
                <p class="section-description"><?php echo esc_html($latest_posts_description); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="posts-wrapper">
            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => $latest_posts_count,
                'orderby'        => 'date',
                'order'          => 'DESC',
                'ignore_sticky_posts' => true,
            );
            
            $latest_posts_query = new WP_Query($args);
            
            if ($latest_posts_query->have_posts()) :
                echo '<div class="posts-grid">';
                
                while ($latest_posts_query->have_posts()) : $latest_posts_query->the_post();
                    ?>
                    <article class="post-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium_large', array('class' => 'post-thumbnail-image')); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <div class="post-meta">
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    echo '<span class="post-category">';
                                    echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                                    echo '</span>';
                                endif;
                                ?>
                            </div>
                            
                            <h3 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="read-more-link">
                                <?php echo esc_html__('Read More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                
                echo '</div>';
                
                wp_reset_postdata();
            else :
                echo '<p class="no-posts">' . esc_html__('No posts found.', 'aqualuxe') . '</p>';
            endif;
            ?>
        </div>
        
        <?php if ($blog_page_url && $view_all_text) : ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($blog_page_url); ?>" class="btn btn-primary"><?php echo esc_html($view_all_text); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>