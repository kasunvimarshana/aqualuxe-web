<?php

/**
 * Sidebar Template
 *
 * @package aqualuxe
 */

if (is_active_sidebar('sidebar-1')) : ?>
    <div class="sidebar">
        <?php dynamic_sidebar('sidebar-1'); ?>

        <!-- Custom Widget: Featured Products -->
        <div class="widget widget-featured-products">
            <h3 class="widget-title"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h3>
            <div class="featured-products-widget">
                <?php echo do_shortcode('[products limit="3" columns="1" visibility="featured"]'); ?>
            </div>
        </div>

        <!-- Custom Widget: Categories -->
        <div class="widget widget-categories">
            <h3 class="widget-title"><?php esc_html_e('Categories', 'aqualuxe'); ?></h3>
            <ul>
                <?php
                $categories = get_categories(array(
                    'orderby' => 'name',
                    'order'   => 'ASC'
                ));

                foreach ($categories as $category) {
                    $category_link = get_category_link($category->term_id);

                    echo '<li><a href="' . esc_url($category_link) . '">' . esc_html($category->name) . ' <span class="count">(' . $category->count . ')</span></a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- Custom Widget: Recent Posts -->
        <div class="widget widget-recent-posts">
            <h3 class="widget-title"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));

                foreach ($recent_posts as $recent) {
                    echo '<li><a href="' . get_permalink($recent["ID"]) . '">' . esc_html($recent["post_title"]) . '</a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- Custom Widget: Newsletter -->
        <div class="widget widget-newsletter">
            <h3 class="widget-title"><?php esc_html_e('Subscribe to Newsletter', 'aqualuxe'); ?></h3>
            <p><?php esc_html_e('Get the latest updates and special offers', 'aqualuxe'); ?></p>
            <?php echo do_shortcode('[newsletter_form]'); ?>
        </div>
    </div>
<?php endif; ?>