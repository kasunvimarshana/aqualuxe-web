<?php
/** Wholesale module: B2B pricing flag and taxonomy */
if (!defined('ABSPATH')) { exit; }

add_action('init', function(){
    register_taxonomy('b2b_segment', ['product'], [
        'label' => __('B2B Segment', 'aqualuxe'),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
    ]);
});
