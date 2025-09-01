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
            $q = new WP_Query([
                'post_type' => 'product',
                'post__in' => $ids,
                'orderby' => 'post__in',
                'posts_per_page' => count($ids)
            ]);
            $out = [];
            while ($q->have_posts()){ $q->the_post();
                $item = [ 'id'=>get_the_ID(), 'title'=>get_the_title(), 'permalink'=>get_permalink(), 'thumb'=>get_the_post_thumbnail_url(get_the_ID(),'thumbnail') ];
                if (class_exists('WooCommerce') && function_exists('wc_get_product')) {
                    $p = call_user_func('wc_get_product', get_the_ID());
                    if ($p) {
                        $item['price'] = $p->get_price();
                        $item['currency'] = function_exists('get_woocommerce_currency') ? call_user_func('get_woocommerce_currency') : null;
                        $item['inStock'] = is_callable([$p,'is_in_stock']) ? (bool) $p->is_in_stock() : null;
                    }
                }
                $out[] = $item;
            }
            wp_reset_postdata();
            $response = rest_ensure_response($out);
            if ($response instanceof WP_REST_Response) {
                $response->header('Cache-Control', 'public, max-age=60');
            }
            return $response;
        }
    ]);

    // Server-side wishlist for logged-in users
    register_rest_route('aqualuxe/v1', '/wishlist', [
        'methods' => 'GET',
        'permission_callback' => function(){ return is_user_logged_in(); },
        'callback' => function(){
            $ids = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
            if (!is_array($ids)) $ids = [];
            return array_values(array_map('intval', array_unique($ids)));
        }
    ]);

    register_rest_route('aqualuxe/v1', '/wishlist', [
        'methods' => 'POST',
        'permission_callback' => function(){ return is_user_logged_in(); },
        'args' => [
            'ids' => [ 'type' => 'array', 'required' => true, 'items' => [ 'type' => 'integer' ] ],
        ],
        'callback' => function($req){
            $ids = array_map('intval', (array)$req->get_param('ids'));
            $ids = array_values(array_unique(array_filter($ids)));
            update_user_meta(get_current_user_id(), 'aqualuxe_wishlist', $ids);
            return [ 'saved' => true, 'ids' => $ids ];
        }
    ]);
});
