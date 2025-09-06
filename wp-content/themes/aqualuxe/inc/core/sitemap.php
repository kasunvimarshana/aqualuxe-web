<?php
/**
 * Simple XML sitemap and robots.txt integration
 * @package AquaLuxe\Core
 */

namespace AquaLuxe\Core;
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Determine if theme-provided sitemap should be active (auto-disable if SEO plugins are present)
function alx_sitemap_is_enabled(): bool {
    $enabled = true;
    // Admin setting can disable sitemap explicitly
    $opt_enabled = \function_exists('get_option') ? (string) \call_user_func('get_option','aqualuxe_sitemap_enabled','1') : '1';
    if ( $opt_enabled === '0' ) { $enabled = false; }
    if ( class_exists('WPSEO_Sitemaps') || defined('RANK_MATH_VERSION') || class_exists('SEOPress') || defined('AIOSEO_VERSION') ) {
        $enabled = false;
    }
    if ( \function_exists('apply_filters') ) { $enabled = (bool) \call_user_func('apply_filters', 'aqualuxe_sitemap_enabled', $enabled ); }
    return $enabled;
}

// Add query vars and rewrites for sitemap index and per-type sitemaps
\add_filter('query_vars', function($vars){
    $vars[] = 'alx_sitemap';
    $vars[] = 'alx_sitemap_type';
    $vars[] = 'alx_sitemap_page';
    return $vars;
});
\add_action('init', function(){
    if ( ! alx_sitemap_is_enabled() ) { return; }
    if ( \function_exists('add_rewrite_rule') ) {
        // New index route
        \call_user_func('add_rewrite_rule', '^sitemap\\.xml$', 'index.php?alx_sitemap=index', 'top' );
        // Back-compat: old route used alx_sitemap=1
        \call_user_func('add_rewrite_rule', '^sitemap-old\\.xml$', 'index.php?alx_sitemap=1', 'top' );
        // Per-type: /sitemap-{type}.xml
        \call_user_func('add_rewrite_rule', '^sitemap-([a-z0-9_-]+)\\.xml$', 'index.php?alx_sitemap=type&alx_sitemap_type=$matches[1]', 'top' );
        // Paged: /sitemap-{type}-{n}.xml
        \call_user_func('add_rewrite_rule', '^sitemap-([a-z0-9_-]+)-([0-9]+)\\.xml$', 'index.php?alx_sitemap=type&alx_sitemap_type=$matches[1]&alx_sitemap_page=$matches[2]', 'top' );
    }
});

// Helpers
function alx_sitemap_types(): array {
    $types = [ 'page', 'post' ];
    if ( class_exists('WooCommerce') ) { $types[] = 'product'; }
    if ( \function_exists('post_type_exists') && \call_user_func('post_type_exists','alx_classified') ) { $types[] = 'alx_classified'; }
    if ( \function_exists('apply_filters') ) { $types = (array) \call_user_func('apply_filters','aqualuxe_sitemap_post_types',$types); }
    // Remove excluded types from option (CSV)
    $excluded = [];
    if ( \function_exists('get_option') ) {
        $raw = (string) \call_user_func('get_option','aqualuxe_sitemap_excluded_types','');
        if ( $raw !== '' ) {
            foreach ( preg_split('/\s*,\s*/', $raw) as $t ) { $t = sanitize_key((string)$t); if ($t!=='') { $excluded[] = $t; } }
        }
    }
    if ( $excluded ) { $types = array_values( array_diff( $types, $excluded ) ); }
    return array_values( array_unique( array_filter( $types ) ) );
}

function alx_sitemap_ver(): string {
    $v = \function_exists('get_option') ? (string) \call_user_func('get_option','alx_sitemap_ver','0') : '0';
    return $v !== '' ? $v : '0';
}

function alx_sitemap_key( string $suffix ): string { return 'alx_sitemap_xml_' . alx_sitemap_ver() . '_' . $suffix; }

// Image sitemap helpers
function alx_sitemap_images_enabled_global(): bool {
    $enabled = true;
    if ( \function_exists('get_option') ) { $enabled = ( (string) \call_user_func('get_option','aqualuxe_sitemap_images_enabled','1') ) === '1'; }
    if ( \function_exists('apply_filters') ) { $enabled = (bool) \call_user_func('apply_filters','aqualuxe_sitemap_images_enabled_global',$enabled); }
    return $enabled;
}

function alx_sitemap_images_enabled_for_type( string $post_type ): bool {
    $enabled = alx_sitemap_images_enabled_global();
    if ( \function_exists('apply_filters') ) { $enabled = (bool) \call_user_func('apply_filters','aqualuxe_sitemap_images_enabled_for_type',$enabled,$post_type); }
    return $enabled;
}

function alx_sitemap_images_for_post( int $post_id ): array {
    $urls = [];
    // Featured image
    if ( \function_exists('get_the_post_thumbnail_url') ) {
        $thumb = \call_user_func('get_the_post_thumbnail_url', $post_id, 'full' );
        if ( is_string($thumb) && $thumb !== '' ) { $urls[] = $thumb; }
    }
    // Inline content images
    $content = '';
    if ( \function_exists('get_post_field') ) { $content = (string) \call_user_func('get_post_field','post_content',$post_id); }
    if ( $content !== '' ) {
        if ( \function_exists('wp_kses_post') ) { $content = (string) \call_user_func('wp_kses_post', $content ); }
        if ( preg_match_all('/<img[^>]+src="([^"]+)"/i', $content, $m ) ) {
            foreach ( $m[1] as $src ) { $src = (string) $src; if ($src!=='') { $urls[] = $src; } }
        }
    }
    // Attached media (images)
    if ( \function_exists('get_attached_media') ) {
        $media = \call_user_func('get_attached_media', 'image', $post_id );
        if ( is_array($media) ) {
            foreach ( $media as $item ) {
                $u = '';
                if ( \function_exists('wp_get_attachment_image_url') ) { $u = (string) \call_user_func('wp_get_attachment_image_url', $item->ID, 'full' ); }
                if ( $u !== '' ) { $urls[] = $u; }
            }
        }
    }
    // Normalize and unique
    $urls = array_values( array_unique( array_map( 'strval', array_filter( $urls ) ) ) );
    // Allow filters to modify
    if ( \function_exists('apply_filters') ) { $urls = (array) \call_user_func('apply_filters','aqualuxe_sitemap_images_for_post',$urls,$post_id); }
    return $urls;
}

// Output sitemap index or per-type sitemaps
\add_action('template_redirect', function(){
    if ( ! alx_sitemap_is_enabled() ) { return; }
    $mode = isset($_GET['alx_sitemap']) ? (string) $_GET['alx_sitemap'] : '';
    $flush = isset($_GET['flush']) && (int) $_GET['flush'] === 1;
    // Back-compat: if numeric 1, serve combined single urlset
    if ( '1' === $mode || 1 === $mode ) { \AquaLuxe\Core\alx_sitemap_output_combined( $flush ); exit; }
    if ( $mode !== 'index' && $mode !== 'type' ) { return; }
    if ( $mode === 'index' ) { \AquaLuxe\Core\alx_sitemap_output_index( $flush ); exit; }
    // type mode
    $pt = isset($_GET['alx_sitemap_type']) ? \sanitize_key( (string) $_GET['alx_sitemap_type'] ) : '';
    if ( ! $pt ) { return; }
    $page = isset($_GET['alx_sitemap_page']) ? max( 1, (int) $_GET['alx_sitemap_page'] ) : 1;
    \AquaLuxe\Core\alx_sitemap_output_type( $pt, $page, $flush );
    exit;
});

function alx_sitemap_output_index( bool $flush = false ): void {
    $key = alx_sitemap_key('index');
    if ( ! $flush && \function_exists('get_transient') ) {
        $cached = \call_user_func('get_transient', $key );
        if ( is_string( $cached ) && $cached !== '' ) { header('Content-Type: application/xml; charset=UTF-8'); echo $cached; return; }
    }
    $types = alx_sitemap_types();
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ( $types as $pt ) {
        // Count posts and paginate by 500
        $total = 0;
        if ( \function_exists('wp_count_posts') ) { $obj = \call_user_func('wp_count_posts', $pt ); $total = (int) ( $obj->publish ?? 0 ); }
        $pages = max( 1, (int) ceil( $total / 500 ) );
        // Determine last modified for the type
        $lastmod = '';
        $q = new \WP_Query([ 'post_type' => $pt, 'post_status' => 'publish', 'posts_per_page' => 1, 'orderby' => 'modified', 'order' => 'DESC', 'no_found_rows' => true ]);
        if ( $q->have_posts() ) { $q->the_post(); $ts = \function_exists('get_post_modified_time') ? \call_user_func('get_post_modified_time','c', true) : ''; $lastmod = is_string($ts) ? $ts : ''; }
        \wp_reset_postdata();
        for ( $i = 1; $i <= $pages; $i++ ) {
            $loc = ( \function_exists('home_url') ? \call_user_func('home_url','/sitemap-'. $pt . ($i>1?'-'.$i:'') .'.xml') : '/sitemap-'. $pt . ($i>1?'-'.$i:'') .'.xml' );
            $loc = \function_exists('esc_url') ? \esc_url($loc) : $loc;
            $xml .= '<sitemap><loc>' . $loc . '</loc>' . ( $lastmod ? '<lastmod>'.$lastmod.'</lastmod>' : '' ) . '</sitemap>';
        }
    }
    $xml .= '</sitemapindex>';
    if ( \function_exists('set_transient') ) { \call_user_func('set_transient', $key, $xml, 6 * HOUR_IN_SECONDS ); }
    header('Content-Type: application/xml; charset=UTF-8');
    echo $xml;
}

function alx_sitemap_output_type( string $pt, int $page = 1, bool $flush = false ): void {
    $key = alx_sitemap_key('type_'.$pt.'_'.$page);
    if ( ! $flush && \function_exists('get_transient') ) {
        $cached = \call_user_func('get_transient', $key );
        if ( is_string( $cached ) && $cached !== '' ) { header('Content-Type: application/xml; charset=UTF-8'); echo $cached; return; }
    }
    $with_images = alx_sitemap_images_enabled_for_type( $pt );
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . ( $with_images ? ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' : '' ) . '>';
    $per_page = 500;
    if ( \function_exists('apply_filters') ) { $per_page = (int) \call_user_func('apply_filters','aqualuxe_sitemap_per_page', $per_page, $pt ); }
    $per_page = max(1, $per_page);
    $offset = ($page - 1) * $per_page;
    $q = new \WP_Query([ 'post_type' => $pt, 'post_status' => 'publish', 'posts_per_page' => $per_page, 'offset' => $offset, 'orderby' => 'ID', 'order' => 'ASC', 'no_found_rows' => true ]);
    while ( $q->have_posts() ) { $q->the_post();
        $loc = \function_exists('get_permalink') ? \call_user_func('get_permalink') : '';
        $loc_esc = \function_exists('esc_url') ? \esc_url( $loc ) : $loc;
        $lm = '';
        if ( \function_exists('get_post_modified_time') ) {
            $ts = \call_user_func( 'get_post_modified_time', 'c', true );
            if ( is_string( $ts ) && $ts ) { $lm = '<lastmod>'. $ts .'</lastmod>'; }
        }
        // Allow exclusion of specific posts via filter
        $exclude = false;
        if ( \function_exists('apply_filters') ) { $exclude = (bool) \call_user_func('apply_filters','aqualuxe_sitemap_exclude_post', false, get_the_ID(), $pt ); }
        if ( ! $exclude ) {
            $xml .= '<url><loc>'.$loc_esc.'</loc>'.$lm;
            if ( $with_images ) {
                $imgs = alx_sitemap_images_for_post( \get_the_ID() );
                foreach ( $imgs as $img ) {
                    $img_esc = \function_exists('esc_url') ? \esc_url( (string) $img ) : (string) $img;
                    $xml .= '<image:image><image:loc>'.$img_esc.'</image:loc></image:image>';
                }
            }
            $xml .= '</url>';
        }
    }
    \wp_reset_postdata();
    $xml .= '</urlset>';
    if ( \function_exists('set_transient') ) { \call_user_func('set_transient', $key, $xml, 6 * HOUR_IN_SECONDS ); }
    header('Content-Type: application/xml; charset=UTF-8');
    echo $xml;
}

// Back-compat combined sitemap (single file with all urls)
function alx_sitemap_output_combined( bool $flush = false ): void {
    $key = alx_sitemap_key('combined');
    if ( ! $flush && \function_exists('get_transient') ) {
        $cached = \call_user_func('get_transient', $key );
        if ( is_string( $cached ) && $cached !== '' ) { header('Content-Type: application/xml; charset=UTF-8'); echo $cached; return; }
    }
    $types = alx_sitemap_types();
    // If any type has images enabled, include the image namespace
    $any_images = false; foreach ( $types as $t ) { if ( alx_sitemap_images_enabled_for_type( $t ) ) { $any_images = true; break; } }
    $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . ( $any_images ? ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' : '' ) . '>';
    $home = \function_exists('home_url') ? \call_user_func('home_url','/') : '/';
    $home_esc = \function_exists('esc_url') ? \esc_url($home) : $home;
    $xml .= '<url><loc>'.$home_esc.'</loc></url>';
    foreach ( $types as $pt ) {
        $q = new \WP_Query([ 'post_type' => $pt, 'post_status' => 'publish', 'posts_per_page' => 500 ]);
        while ( $q->have_posts() ) { $q->the_post();
            $loc = \function_exists('get_permalink') ? \call_user_func('get_permalink') : '';
            $loc_esc = \function_exists('esc_url') ? \esc_url( $loc ) : $loc;
            $xml .= '<url><loc>'.$loc_esc.'</loc>';
            if ( alx_sitemap_images_enabled_for_type( $pt ) ) {
                $imgs = alx_sitemap_images_for_post( \get_the_ID() );
                foreach ( $imgs as $img ) {
                    $img_esc = \function_exists('esc_url') ? \esc_url( (string) $img ) : (string) $img;
                    $xml .= '<image:image><image:loc>'.$img_esc.'</image:loc></image:image>';
                }
            }
            $xml .= '</url>';
        }
        \wp_reset_postdata();
    }
    $xml .= '</urlset>';
    if ( \function_exists('set_transient') ) { \call_user_func('set_transient', $key, $xml, 6 * HOUR_IN_SECONDS ); }
    header('Content-Type: application/xml; charset=UTF-8');
    echo $xml;
}

// robots.txt: add Sitemap entry
\add_filter('robots_txt', function($output){
    if ( ! alx_sitemap_is_enabled() ) { return $output; }
    $line = 'Sitemap: ' . ( \function_exists('home_url') ? \call_user_func('home_url','/sitemap.xml') : '/sitemap.xml' );
    return trim($output."\n".$line);
}, 10, 1);

// Invalidate sitemap by bumping version on content changes
\add_action( 'save_post', function(){ if ( ! alx_sitemap_is_enabled() ) { return; } if ( \function_exists('update_option') ) { \call_user_func('update_option','alx_sitemap_ver', (string) time() ); } }, 10, 0 );
\add_action( 'edited_terms', function(){ if ( ! alx_sitemap_is_enabled() ) { return; } if ( \function_exists('update_option') ) { \call_user_func('update_option','alx_sitemap_ver', (string) time() ); } }, 10, 0 );
