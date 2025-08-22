<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get theme options
$options = get_option('aqualuxe_options', array());

// 404 options
$error_layout = isset($options['error_layout']) ? $options['error_layout'] : 'no-sidebar';
$error_image = isset($options['error_image']) ? $options['error_image'] : '';

// Set layout classes
$content_class = 'content-area';
if ($error_layout === 'no-sidebar') {
    $content_class .= ' no-sidebar';
} elseif ($error_layout === 'full-width') {
    $content_class .= ' full-width';
}

get_header();
?>

<div class="container">
    <div class="content-sidebar-wrap">
        <main id="primary" class="<?php echo esc_attr($content_class); ?>">
            <section class="error-404 not-found">
                <div class="error-404-container">
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e('404', 'aqualuxe'); ?></h1>
                        <h2 class="page-subtitle"><?php esc_html_e('Page Not Found', 'aqualuxe'); ?></h2>
                    </header><!-- .page-header -->

                    <div class="page-content">
                        <?php if (!empty($error_image)) : ?>
                            <div class="error-image">
                                <img src="<?php echo esc_url($error_image); ?>" alt="<?php esc_attr_e('404 Error', 'aqualuxe'); ?>">
                            </div>
                        <?php else : ?>
                            <div class="error-icon">
                                <span class="icon-warning"></span>
                            </div>
                        <?php endif; ?>

                        <p><?php esc_html_e('It looks like nothing was found at this location. The page you were looking for may have been moved, deleted, or possibly never existed.', 'aqualuxe'); ?></p>

                        <div class="error-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary">
                                <span class="icon-home"></span>
                                <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                            </a>
                        </div>

                        <div class="search-form-container">
                            <h3><?php esc_html_e('Search Our Site', 'aqualuxe'); ?></h3>
                            <?php get_search_form(); ?>
                        </div>

                        <div class="helpful-links">
                            <h3><?php esc_html_e('Helpful Links', 'aqualuxe'); ?></h3>
                            <div class="helpful-links-container">
                                <div class="helpful-links-column">
                                    <h4><?php esc_html_e('Popular Pages', 'aqualuxe'); ?></h4>
                                    <ul>
                                        <?php
                                        // Display most popular pages
                                        $popular_pages = new WP_Query(array(
                                            'post_type' => 'page',
                                            'posts_per_page' => 5,
                                            'orderby' => 'comment_count',
                                            'order' => 'DESC',
                                        ));

                                        if ($popular_pages->have_posts()) :
                                            while ($popular_pages->have_posts()) : $popular_pages->the_post();
                                                echo '<li><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></li>';
                                            endwhile;
                                            wp_reset_postdata();
                                        else :
                                            // Fallback if no popular pages found
                                            $pages = get_pages(array('sort_column' => 'menu_order', 'number' => 5));
                                            foreach ($pages as $page) {
                                                echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . $page->post_title . '</a></li>';
                                            }
                                        endif;
                                        ?>
                                    </ul>
                                </div>

                                <div class="helpful-links-column">
                                    <h4><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h4>
                                    <ul>
                                        <?php
                                        $recent_posts = wp_get_recent_posts(array(
                                            'numberposts' => 5,
                                            'post_status' => 'publish',
                                        ));
                                        
                                        foreach ($recent_posts as $post) {
                                            echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . $post['post_title'] . '</a></li>';
                                        }
                                        wp_reset_postdata();
                                        ?>
                                    </ul>
                                </div>

                                <?php if (class_exists('WooCommerce')) : ?>
                                    <div class="helpful-links-column">
                                        <h4><?php esc_html_e('Popular Products', 'aqualuxe'); ?></h4>
                                        <ul>
                                            <?php
                                            $popular_products = new WP_Query(array(
                                                'post_type' => 'product',
                                                'posts_per_page' => 5,
                                                'meta_key' => 'total_sales',
                                                'orderby' => 'meta_value_num',
                                                'order' => 'DESC',
                                            ));

                                            if ($popular_products->have_posts()) :
                                                while ($popular_products->have_posts()) : $popular_products->the_post();
                                                    echo '<li><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></li>';
                                                endwhile;
                                                wp_reset_postdata();
                                            endif;
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div><!-- .page-content -->
                </div><!-- .error-404-container -->
            </section><!-- .error-404 -->
        </main><!-- #primary -->
    </div><!-- .content-sidebar-wrap -->
</div><!-- .container -->

<?php
get_footer();