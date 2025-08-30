<?php

/**
 * Simple shortcode to show 4 latest products
 */
add_shortcode('aqualuxe_latest', 'aqualuxe_latest_shortcode');

function aqualuxe_latest_shortcode()
{
    ob_start();
    $loop = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($loop->have_posts()) {
        echo '<div class="aqualuxe-grid">';
        while ($loop->have_posts()) {
            $loop->the_post();
            wc_get_template_part('content', 'product');
        }
        echo '</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}
