<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404 not-found">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="error-content">
                        <h1 class="error-title"><?php echo esc_html__('404', 'aqualuxe'); ?></h1>
                        <h2 class="error-subtitle"><?php echo esc_html__('Page Not Found', 'aqualuxe'); ?></h2>
                        <div class="error-description">
                            <p><?php echo esc_html__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe'); ?></p>
                        </div>
                        
                        <div class="error-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <i class="fas fa-home"></i>
                                <?php echo esc_html__('Back to Home', 'aqualuxe'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-newspaper"></i>
                                <?php echo esc_html__('Browse Blog', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="error-image">
                        <?php
                        // Check if custom 404 image is set in customizer
                        $error_image = get_theme_mod('aqualuxe_404_image', '');
                        
                        if (!empty($error_image)) {
                            echo '<img src="' . esc_url($error_image) . '" alt="' . esc_attr__('404 Error', 'aqualuxe') . '" class="img-fluid">';
                        } else {
                            // Default image
                            echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/dist/images/404.svg') . '" alt="' . esc_attr__('404 Error', 'aqualuxe') . '" class="img-fluid">';
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="error-search">
                <h3><?php echo esc_html__('Search Our Website', 'aqualuxe'); ?></h3>
                <p><?php echo esc_html__('Perhaps you can find what you were looking for by searching below:', 'aqualuxe'); ?></p>
                <?php get_search_form(); ?>
            </div>
            
            <div class="error-suggestions">
                <div class="row">
                    <div class="col-md-6">
                        <div class="suggestion-box">
                            <h3><?php echo esc_html__('Popular Posts', 'aqualuxe'); ?></h3>
                            <ul class="suggestion-list">
                                <?php
                                $popular_posts_args = array(
                                    'posts_per_page' => 5,
                                    'orderby'        => 'comment_count',
                                    'order'          => 'DESC',
                                    'post_type'      => 'post',
                                    'post_status'    => 'publish',
                                );
                                
                                $popular_posts = new WP_Query($popular_posts_args);
                                
                                if ($popular_posts->have_posts()) {
                                    while ($popular_posts->have_posts()) {
                                        $popular_posts->the_post();
                                        ?>
                                        <li>
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </li>
                                        <?php
                                    }
                                    wp_reset_postdata();
                                } else {
                                    echo '<li>' . esc_html__('No posts found', 'aqualuxe') . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="suggestion-box">
                            <h3><?php echo esc_html__('Popular Categories', 'aqualuxe'); ?></h3>
                            <ul class="suggestion-list">
                                <?php
                                $categories = get_categories(array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'number'     => 5,
                                    'hide_empty' => true,
                                ));
                                
                                if (!empty($categories)) {
                                    foreach ($categories as $category) {
                                        ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                                <?php echo esc_html($category->name); ?>
                                                <span class="count">(<?php echo esc_html($category->count); ?>)</span>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo '<li>' . esc_html__('No categories found', 'aqualuxe') . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .error-404 -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();