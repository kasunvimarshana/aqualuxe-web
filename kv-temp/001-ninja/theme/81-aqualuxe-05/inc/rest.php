<?php
/** REST endpoints */
if (!defined('ABSPATH')) { exit; }

add_action('rest_api_init', function(){
    register_rest_route('aqualuxe/v1', '/ping', [
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function(){ return ['ok' => true, 'time' => time()]; }
    ]);
});
