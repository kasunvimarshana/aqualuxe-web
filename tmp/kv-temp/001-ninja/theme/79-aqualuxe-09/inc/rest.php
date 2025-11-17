<?php
declare(strict_types=1);

// Lightweight REST endpoints for front page hydration.

if (function_exists('add_action')) {
    add_action('rest_api_init', static function (): void {
        // Metrics endpoint: returns simple time-series for temperature, pH, ammonia.
        register_rest_route('aqlx/v1', '/metrics', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => static function () {
                $cache_key = 'aqlx_metrics_v1';
                $payload = get_transient($cache_key);
                if (! is_array($payload)) {
                    $points = [];
                    for ($i = 0; $i < 24; $i++) {
                        $points[] = [
                            't' => $i,
                            'temperature' => 24 + sin($i / 2) * 2,
                            'ph' => 7.2 + sin($i / 3) * 0.2,
                            'ammonia' => max(0, 0.05 + sin($i / 4) * 0.02),
                        ];
                    }
                    // Also provide a temperature series in {t,v} for quick consumption.
                    $tempSeries = array_map(static function ($p) { return ['t' => $p['t'], 'v' => $p['temperature']]; }, $points);
                    $payload = [ 'points' => $points, 'temperature' => $tempSeries ];
                    set_transient($cache_key, $payload, 60); // 1 minute cache
                }
                // Allow customization via filter without affecting cached base payload
                $payload = apply_filters('aqlx_rest_metrics', $payload);
                return rest_ensure_response($payload);
            },
        ]);

        // Recommendations endpoint: returns recent posts; if Woo is active, also include a couple of products.
        register_rest_route('aqlx/v1', '/recos', [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => static function (\WP_REST_Request $request) {
                $limit = (int) $request->get_param('limit');
                if ($limit < 1) { $limit = 6; }
                if ($limit > 12) { $limit = 12; }
                $cache_key = 'aqlx_recos_' . $limit;
                $cached = get_transient($cache_key);
                if (is_array($cached)) {
                    $cached = apply_filters('aqlx_rest_recos', $cached, $limit);
                    return rest_ensure_response($cached);
                }

                $items = [];
                // Recent posts
                $q = new \WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => $limit,
                    'no_found_rows' => true,
                    'post_status' => 'publish',
                ]);
                if ($q->have_posts()) {
                    while ($q->have_posts()) { $q->the_post();
                        $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        $items[] = [
                            'title' => get_the_title(),
                            'link' => get_permalink(),
                            'excerpt' => wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 24),
                            'image' => $img ? esc_url_raw($img) : null,
                            'type' => 'post',
                        ];
                    }
                    wp_reset_postdata();
                }

                // Optionally add a couple of products if WooCommerce is active.
                if (class_exists('WooCommerce') && post_type_exists('product')) {
                    $p = new \WP_Query([
                        'post_type' => 'product',
                        'posts_per_page' => min(3, max(0, $limit - count($items))),
                        'no_found_rows' => true,
                        'post_status' => 'publish',
                    ]);
                    if ($p->have_posts()) {
                        while ($p->have_posts()) { $p->the_post();
                            $img = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                            $price_html = null;
                            if (is_callable('wc_get_product')) {
                                $prod = call_user_func('wc_get_product', get_the_ID());
                                if ($prod) { $price_html = wp_strip_all_tags($prod->get_price_html()); }
                            }
                            $items[] = [
                                'title' => get_the_title(),
                                'link' => get_permalink(),
                                'excerpt' => wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 24),
                                'image' => $img ? esc_url_raw($img) : null,
                                'price' => $price_html,
                                'type' => 'product',
                            ];
                        }
                        wp_reset_postdata();
                    }
                }

                // Cache briefly to reduce DB load.
                set_transient($cache_key, $items, 300); // 5 minutes
                $items = apply_filters('aqlx_rest_recos', $items, $limit);
                return rest_ensure_response($items);
            },
        ]);
    });
}
