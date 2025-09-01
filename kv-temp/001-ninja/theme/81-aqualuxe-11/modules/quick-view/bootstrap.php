<?php
/** Quick View modal for Woo products */
if (!defined('ABSPATH')) { exit; }

add_action('wp_footer', function(){
    echo '<div id="ax-quick-view" class="hidden fixed inset-0 bg-black/60 z-50" role="dialog" aria-modal="true" aria-labelledby="ax-qv-title"><div class="max-w-3xl m-auto bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 p-4 rounded shadow-lg mt-16"><button class="ax-qv-close float-right">&times;</button><div id="ax-qv-content" class="mt-4"></div></div></div>';
});

add_action('rest_api_init', function(){
    register_rest_route('aqualuxe/v1','/quick-view/(?P<id>\\d+)', [
        'methods' => 'GET', 'permission_callback' => '__return_true',
        'callback' => function($req){
            $id = (int) $req['id'];
            if (get_post_type($id) !== 'product') return new WP_Error('invalid','Not a product', ['status'=>400]);
            setup_postdata(get_post($id));
            ob_start();
            if (function_exists('wc_get_template_part')) {
                call_user_func('wc_get_template_part', 'content', 'single-product');
            } else {
                echo '<article class="p-4">' . esc_html(get_the_title($id)) . '</article>';
            }
            wp_reset_postdata();
            return [ 'html' => ob_get_clean() ];
        }
    ]);
});
