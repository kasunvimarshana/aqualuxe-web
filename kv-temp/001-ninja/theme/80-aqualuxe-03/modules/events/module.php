<?php
/** Events custom post type + ticketing placeholder */

add_action('init', function(){
    register_post_type('event', [
        'label' => __('Events','aqualuxe'),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title','editor','thumbnail','excerpt','custom-fields'],
        'show_in_rest' => true,
        'rewrite' => ['slug'=>'events']
    ]);
});

add_shortcode('aqualuxe_events_calendar', function(){
    $q = new WP_Query(['post_type'=>'event','posts_per_page'=>12]);
    $out = '<ul class="space-y-2">';
    while($q->have_posts()){ $q->the_post();
        $out .= '<li><a class="underline" href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
    }
    wp_reset_postdata();
    return $out . '</ul>';
});
