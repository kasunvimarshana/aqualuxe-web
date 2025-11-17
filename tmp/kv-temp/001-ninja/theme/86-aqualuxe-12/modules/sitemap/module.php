<?php
// Simple sitemap.xml output (basic posts/pages), filterable
\add_action('init', function(){
    \add_rewrite_rule('sitemap\.xml$', 'index.php?aqlx_sitemap=1', 'top');
});
\add_filter('query_vars', function($vars){ $vars[] = 'aqlx_sitemap'; return $vars; });
\add_action('template_redirect', function(){
    if (get_query_var('aqlx_sitemap')) {
        header('Content-Type: application/xml; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $q = new \WP_Query(['post_type' => ['page','post'], 'posts_per_page' => 1000, 'post_status' => 'publish']);
        while ($q->have_posts()) { $q->the_post();
            echo '<url><loc>' . esc_url(get_permalink()) . '</loc><lastmod>' . esc_html(get_post_modified_time('c')) . '</lastmod></url>';
        }
        \wp_reset_postdata();
        echo '</urlset>';
        exit;
    }
});
