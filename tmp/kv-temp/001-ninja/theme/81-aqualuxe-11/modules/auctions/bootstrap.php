<?php
/** Auctions module: minimal CPT to list auction items; no payment gateway included */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_post_type('auction', [
        'label' => __('Auctions','aqualuxe'),
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title','editor','thumbnail','custom-fields'],
        'has_archive' => true
    ]);
});

add_shortcode('ax_auction_teaser', function(){
    $q = new WP_Query(['post_type'=>'auction','posts_per_page'=>3]);
    $out = '<div class="grid md:grid-cols-3 gap-4">';
    if ($q->have_posts()) { while ($q->have_posts()){ $q->the_post(); $out .= '<article class="ax-card p-3"><a href="'.esc_url(get_permalink()).'" class="font-semibold">'.esc_html(get_the_title()).'</a></article>'; } }
    wp_reset_postdata();
    return $out.'</div>';
});
