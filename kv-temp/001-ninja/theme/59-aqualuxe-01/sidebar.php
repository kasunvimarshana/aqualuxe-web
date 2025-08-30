<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if sidebar should be displayed
$sidebar_position = aqualuxe_get_sidebar_position();

if ('none' === $sidebar_position) {
    return;
}

// Get the appropriate sidebar based on the page type
$sidebar_id = aqualuxe_get_sidebar_id();
?>

<aside id="secondary" class="widget-area sidebar sidebar--<?php echo esc_attr($sidebar_position); ?>">
    <?php
    /**
     * Hook: aqualuxe_before_sidebar
     */
    do_action('aqualuxe_before_sidebar');
    
    if (is_active_sidebar($sidebar_id)) {
        dynamic_sidebar($sidebar_id);
    } else {
        // Default widgets if no sidebar is active
        ?>
        <section class="widget widget_search">
            <h2 class="widget-title"><?php esc_html_e('Search', 'aqualuxe'); ?></h2>
            <?php get_search_form(); ?>
        </section>

        <section class="widget widget_recent_entries">
            <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h2>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts([
                    'numberposts' => 5,
                    'post_status' => 'publish',
                ]);
                
                foreach ($recent_posts as $post) {
                    echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
                }
                wp_reset_postdata();
                ?>
            </ul>
        </section>

        <section class="widget widget_categories">
            <h2 class="widget-title"><?php esc_html_e('Categories', 'aqualuxe'); ?></h2>
            <ul>
                <?php
                wp_list_categories([
                    'title_li' => '',
                    'number' => 10,
                ]);
                ?>
            </ul>
        </section>
        <?php
    }
    
    /**
     * Hook: aqualuxe_after_sidebar
     */
    do_action('aqualuxe_after_sidebar');
    ?>
</aside><!-- #secondary -->