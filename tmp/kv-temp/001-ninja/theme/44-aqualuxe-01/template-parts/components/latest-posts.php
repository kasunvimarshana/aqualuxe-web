<?php
/**
 * Template part for displaying latest blog posts on the homepage
 *
 * @package AquaLuxe
 */

// Get latest posts options from theme customizer
$show_latest_posts = get_theme_mod('aqualuxe_show_home_latest_posts', true);
$latest_posts_title = get_theme_mod('aqualuxe_home_latest_posts_title', __('Latest News', 'aqualuxe'));
$latest_posts_subtitle = get_theme_mod('aqualuxe_home_latest_posts_subtitle', __('Stay updated with our latest articles and news', 'aqualuxe'));
$latest_posts_count = get_theme_mod('aqualuxe_home_latest_posts_count', 3);
$latest_posts_columns = get_theme_mod('aqualuxe_home_latest_posts_columns', 3);
$latest_posts_style = get_theme_mod('aqualuxe_home_latest_posts_style', 'grid');
$latest_posts_category = get_theme_mod('aqualuxe_home_latest_posts_category', 0);
$latest_posts_orderby = get_theme_mod('aqualuxe_home_latest_posts_orderby', 'date');
$latest_posts_order = get_theme_mod('aqualuxe_home_latest_posts_order', 'DESC');
$latest_posts_button_text = get_theme_mod('aqualuxe_home_latest_posts_button_text', __('View All Posts', 'aqualuxe'));
$latest_posts_button_url = get_theme_mod('aqualuxe_home_latest_posts_button_url', get_permalink(get_option('page_for_posts')));

// Check if latest posts should be displayed
if (!$show_latest_posts) {
    return;
}

// Set up query arguments
$args = array(
    'post_type'      => 'post',
    'posts_per_page' => $latest_posts_count,
    'orderby'        => $latest_posts_orderby,
    'order'          => $latest_posts_order,
    'post_status'    => 'publish',
);

// Add category filter
if ($latest_posts_category > 0) {
    $args['cat'] = $latest_posts_category;
}

// Get posts
$latest_posts_query = new WP_Query($args);

// Check if we have posts
if (!$latest_posts_query->have_posts()) {
    return;
}

// Set up column classes
$column_class = 'col-lg-4 col-md-6';

switch ($latest_posts_columns) {
    case 1:
        $column_class = 'col-lg-12';
        break;
    case 2:
        $column_class = 'col-lg-6 col-md-6';
        break;
    case 3:
        $column_class = 'col-lg-4 col-md-6';
        break;
    case 4:
        $column_class = 'col-lg-3 col-md-6';
        break;
}

// Post item classes
$post_item_class = 'post-item';
$post_item_class .= ' post-style-' . $latest_posts_style;
?>

<div class="latest-posts-section section-padding">
    <div class="container">
        <div class="section-header text-center">
            <?php if (!empty($latest_posts_title)) : ?>
                <h2 class="section-title"><?php echo esc_html($latest_posts_title); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($latest_posts_subtitle)) : ?>
                <div class="section-subtitle"><?php echo esc_html($latest_posts_subtitle); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="latest-posts-wrapper">
            <?php if ($latest_posts_style === 'carousel') : ?>
                <div class="posts-carousel">
                    <?php
                    // Loop through posts
                    while ($latest_posts_query->have_posts()) :
                        $latest_posts_query->the_post();
                        ?>
                        <div class="<?php echo esc_attr($post_item_class); ?>">
                            <div class="post-inner">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('aqualuxe-blog-thumbnail', array('class' => 'img-fluid')); ?>
                                        </a>
                                        
                                        <div class="post-date">
                                            <span class="day"><?php echo get_the_date('d'); ?></span>
                                            <span class="month"><?php echo get_the_date('M'); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="post-content">
                                    <?php
                                    // Categories
                                    $categories_list = get_the_category_list(', ');
                                    if ($categories_list) {
                                        ?>
                                        <div class="post-categories">
                                            <?php echo wp_kses_post($categories_list); ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    
                                    <h3 class="post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="post-meta">
                                        <span class="post-author">
                                            <i class="fas fa-user"></i>
                                            <?php the_author_posts_link(); ?>
                                        </span>
                                        
                                        <span class="post-comments">
                                            <i class="fas fa-comments"></i>
                                            <?php comments_popup_link('0', '1', '%'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="post-excerpt">
                                        <?php echo wp_kses_post(aqualuxe_get_excerpt(20)); ?>
                                    </div>
                                    
                                    <div class="post-read-more">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                            <?php echo esc_html__('Read More', 'aqualuxe'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php else : ?>
                <div class="row">
                    <?php
                    // Loop through posts
                    while ($latest_posts_query->have_posts()) :
                        $latest_posts_query->the_post();
                        ?>
                        <div class="<?php echo esc_attr($column_class); ?>">
                            <div class="<?php echo esc_attr($post_item_class); ?>">
                                <div class="post-inner">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="post-image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('aqualuxe-blog-thumbnail', array('class' => 'img-fluid')); ?>
                                            </a>
                                            
                                            <div class="post-date">
                                                <span class="day"><?php echo get_the_date('d'); ?></span>
                                                <span class="month"><?php echo get_the_date('M'); ?></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="post-content">
                                        <?php
                                        // Categories
                                        $categories_list = get_the_category_list(', ');
                                        if ($categories_list) {
                                            ?>
                                            <div class="post-categories">
                                                <?php echo wp_kses_post($categories_list); ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        
                                        <h3 class="post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <div class="post-meta">
                                            <span class="post-author">
                                                <i class="fas fa-user"></i>
                                                <?php the_author_posts_link(); ?>
                                            </span>
                                            
                                            <span class="post-comments">
                                                <i class="fas fa-comments"></i>
                                                <?php comments_popup_link('0', '1', '%'); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="post-excerpt">
                                            <?php echo wp_kses_post(aqualuxe_get_excerpt(20)); ?>
                                        </div>
                                        
                                        <div class="post-read-more">
                                            <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                                <?php echo esc_html__('Read More', 'aqualuxe'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($latest_posts_button_text) && !empty($latest_posts_button_url)) : ?>
            <div class="text-center mt-5">
                <a href="<?php echo esc_url($latest_posts_button_url); ?>" class="btn btn-primary">
                    <?php echo esc_html($latest_posts_button_text); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>