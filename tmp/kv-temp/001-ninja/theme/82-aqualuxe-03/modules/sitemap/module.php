<?php
// Lightweight XML sitemap at /sitemap.xml with transient caching.

add_action('init', function(){
    add_rewrite_rule('sitemap\.xml$', 'index.php?aqualuxe_sitemap=1', 'top');
    add_rewrite_tag('%aqualuxe_sitemap%', '([0-1])');
});

add_action('template_redirect', function(){
    if ( get_query_var('aqualuxe_sitemap') ) {
        $xml = get_transient('alx_sitemap_xml');
        if ( ! $xml ) {
            $home = home_url('/');
            $items = [];
            $push_post_type = function( $type ) use ( &$items ){
                $q = new \WP_Query([ 'post_type' => $type, 'post_status'=>'publish', 'posts_per_page'=>500, 'no_found_rows'=>true, 'fields'=>'ids' ]);
                foreach ( $q->posts as $pid ) {
                    $items[] = [ get_permalink($pid), get_post_modified_time('c', true, $pid) ];
                }
            };
            // Core types
            $push_post_type('page');
            $push_post_type('post');
            if ( post_type_exists('service') ) { $push_post_type('service'); }
            if ( post_type_exists('event') ) { $push_post_type('event'); }
            if ( class_exists('WooCommerce') ) { $push_post_type('product'); }

            // Build XML
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            // Home
            $xml .= '<url><loc>' . esc_url( $home ) . '</loc><changefreq>daily</changefreq><priority>1.0</priority></url>';
            foreach ( $items as $it ) {
                $loc = esc_url( $it[0] ); $mod = esc_html( $it[1] );
                $xml .= '<url><loc>' . $loc . '</loc>' . ( $mod ? '<lastmod>' . $mod . '</lastmod>' : '' ) . '</url>';
            }
            $xml .= '</urlset>';
            set_transient('alx_sitemap_xml', $xml, HOUR_IN_SECONDS);
        }
        header('Content-Type: application/xml; charset=UTF-8');
        echo $xml; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        exit;
    }
});
