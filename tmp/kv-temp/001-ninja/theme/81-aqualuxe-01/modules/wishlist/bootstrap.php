<?php
/** Wishlist module (cookie/localStorage based) */
if (!defined('ABSPATH')) { exit; }

add_shortcode('ax_wishlist', function(){
    // Client-side renders items from localStorage and fetches product data via REST
    return '<div id="ax-wishlist" class="grid gap-4" aria-live="polite"></div>';
});

add_action('rest_api_init', function(){
    register_rest_route('aqualuxe/v1', '/products', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function($req){
            $ids = array_map('intval', (array)$req->get_param('ids'));
            if (!$ids) return [];
            $q = new WP_Query(['post_type'=>'product','post__in'=>$ids,'posts_per_page'=>count($ids)]);
            $out = [];
            while ($q->have_posts()){ $q->the_post();
                $out[] = [ 'id'=>get_the_ID(), 'title'=>get_the_title(), 'permalink'=>get_permalink(), 'thumb'=>get_the_post_thumbnail_url(get_the_ID(),'thumbnail')];
            }
            wp_reset_postdata();
            return $out;
        }
    ]);
});
