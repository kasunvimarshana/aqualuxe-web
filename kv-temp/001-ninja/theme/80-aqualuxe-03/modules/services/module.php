<?php
/** Services custom post type + booking placeholders */

add_action('init', function(){
    register_post_type('service', [
        'label' => __('Services','aqualuxe'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-admin-tools',
        'supports' => ['title','editor','thumbnail','excerpt'],
        'show_in_rest' => true,
        'rewrite' => ['slug'=>'services']
    ]);
});

add_shortcode('aqualuxe_services_grid', function(){
    $q = new WP_Query(['post_type'=>'service','posts_per_page'=>12]);
    ob_start();
    echo '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">';
    while ($q->have_posts()){ $q->the_post();
        echo '<a class="block p-4 border rounded hover:shadow" href="' . esc_url(get_permalink()) . '">';
        if (has_post_thumbnail()) the_post_thumbnail('medium', ['class'=>'rounded mb-2','loading'=>'lazy']);
        echo '<h3 class="font-semibold">' . esc_html(get_the_title()) . '</h3>';
        echo '<p class="text-sm opacity-80">' . esc_html(get_the_excerpt()) . '</p>';
        echo '</a>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
});
