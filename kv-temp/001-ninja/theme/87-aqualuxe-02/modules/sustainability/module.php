<?php
add_action('init', function(){
    register_post_type('initiative', [
        'labels' => [ 'name' => __('Initiatives','aqualuxe'), 'singular_name' => __('Initiative','aqualuxe') ],
        'public' => true,
        'has_archive' => true,
        'supports' => ['title','editor','thumbnail','excerpt','revisions'],
        'show_in_rest' => true,
    ]);
});

add_shortcode('aqualuxe_initiatives', function(){
    $q = new WP_Query(['post_type' => 'initiative', 'posts_per_page' => 6]);
    ob_start();
    echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">';
    while ($q->have_posts()) { $q->the_post();
        echo '<article class="border rounded p-4">';
        if (has_post_thumbnail()) { the_post_thumbnail('medium'); }
        echo '<h3 class="font-semibold text-lg"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h3>';
        echo '<div class="prose">' . wp_kses_post(get_the_excerpt()) . '</div>';
        echo '</article>';
    }
    echo '</div>';
    wp_reset_postdata();
    return (string) ob_get_clean();
});
