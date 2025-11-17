<?php
/** REST endpoints */
if (!defined('ABSPATH')) { exit; }

add_action('rest_api_init', function(){
    register_rest_route('aqualuxe/v1', '/ping', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function(){ return ['ok' => true, 'time' => time()]; }
    ]);

    register_rest_route('aqualuxe/v1', '/breadcrumbs', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function(){ return aqualuxe_get_breadcrumb_items(); }
    ]);

    register_rest_route('aqualuxe/v1', '/services/featured', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function(){
            $q = new WP_Query(['post_type'=>'service','posts_per_page'=>4]);
            $out = [];
            while ($q->have_posts()){ $q->the_post(); $out[] = [ 'id'=>get_the_ID(), 'title'=>get_the_title(), 'link'=>get_permalink() ]; }
            wp_reset_postdata();
            return $out;
        }
    ]);
});
