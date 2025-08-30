<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="error-404-section section">
        <div class="container">
            <div class="error-404 not-found text-center" data-aos="fade-up">
                <div class="error-404-content">
                    <div class="error-404-number">404</div>
                    <h1 class="page-title"><?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?></h1>
                    <div class="page-content">
                        <p><?php esc_html_e('It looks like nothing was found at this location. The page you were looking for does not exist or may have been moved.', 'aqualuxe'); ?></p>
                        
                        <div class="error-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <i class="fas fa-home"></i> <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="error-404-search" data-aos="fade-up" data-aos-delay="100">
                    <h2><?php esc_html_e('Search Our Website', 'aqualuxe'); ?></h2>
                    <p><?php esc_html_e('Perhaps searching can help you find what you\'re looking for.', 'aqualuxe'); ?></p>
                    <?php get_search_form(); ?>
                </div>
                
                <div class="error-404-help" data-aos="fade-up" data-aos-delay="200">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="error-help-widget">
                                <h2><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
                                <ul>
                                    <?php
                                    $recent_posts = wp_get_recent_posts(array(
                                        'numberposts' => 5,
                                        'post_status' => 'publish'
                                    ));
                                    
                                    foreach ($recent_posts as $post) :
                                    ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                                                <?php echo esc_html($post['post_title']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="error-help-widget">
                                <h2><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h2>
                                <ul>
                                    <?php
                                    wp_list_categories(array(
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'show_count' => true,
                                        'title_li' => '',
                                        'number' => 5
                                    ));
                                    ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="error-help-widget">
                                <h2><?php esc_html_e('Popular Tags', 'aqualuxe'); ?></h2>
                                <div class="tagcloud">
                                    <?php wp_tag_cloud(array(
                                        'number' => 15,
                                        'orderby' => 'count',
                                        'order' => 'DESC'
                                    )); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();