<?php
/** SEO: meta tags, Open Graph */
if (!defined('ABSPATH')) { exit; }

add_action('wp_head', function(){
    // Robots for low-value pages
    if ((function_exists('is_search') && is_search()) || (function_exists('is_404') && is_404()) || (function_exists('is_author') && is_author())) {
        echo "\n<meta name=\"robots\" content=\"noindex,follow\">\n";
    }
    echo "\n<meta name=\"theme-color\" content=\"" . esc_attr(get_theme_mod('aqualuxe_primary_color', '#0ea5e9')) . "\">\n";
    echo "<meta property=\"og:site_name\" content=\"" . esc_attr(get_bloginfo('name')) . "\">\n";
    echo "<meta property=\"og:locale\" content=\"" . esc_attr(get_locale()) . "\">\n";
    // Twitter basic card
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
    $tw = trim((string) get_theme_mod('aqualuxe_twitter_handle', ''));
    if ($tw !== '') { echo '<meta name="twitter:site" content="@' . esc_attr(ltrim($tw,'@')) . '">' . "\n"; }

    // hreflang (Polylang only if available)
    if (function_exists('pll_the_languages')) {
        $langs = call_user_func('pll_the_languages', ['raw' => 1]);
        if (is_array($langs)) {
            foreach ($langs as $l) {
                if (!empty($l['url']) && !empty($l['locale'])) {
                    echo '<link rel="alternate" hreflang="' . esc_attr($l['locale']) . '" href="' . esc_url($l['url']) . '">' . "\n";
                }
            }
        }
    }

    // Organization JSON-LD (minimal)
    $org = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    ];
    $sameas_raw = (string) get_theme_mod('aqualuxe_sameas', '');
    if ($sameas_raw) {
        $urls = array_filter(array_map('trim', preg_split('/\r?\n/', $sameas_raw)));
        if (!empty($urls)) { $org['sameAs'] = array_values(array_map('esc_url_raw', $urls)); }
    }
    if (function_exists('has_custom_logo') && has_custom_logo()) {
        $logo_id = get_theme_mod('custom_logo');
        $src = wp_get_attachment_image_src($logo_id, 'full');
        if ($src && isset($src[0])) { $org['logo'] = esc_url_raw($src[0]); }
    }
    echo '<script type="application/ld+json">' . wp_json_encode($org) . '</script>' . "\n";

    // WebSite + SearchAction JSON-LD
    $site = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url'  => home_url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => esc_url_raw(add_query_arg('s', '{search_term_string}', home_url('/'))),
            'query-input' => 'required name=search_term_string'
        ]
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($site) . '</script>' . "\n";

    // Contextual meta and JSON-LD for single entities
    $title = wp_get_document_title();
    $desc  = get_bloginfo('description');
    $img   = function_exists('get_site_icon_url') ? get_site_icon_url(512, '', get_bloginfo('template_directory').'/screenshot.png') : '';
    if (function_exists('is_singular') && is_singular()) {
        $qid = get_queried_object_id();
        $ex  = function_exists('get_the_excerpt') ? wp_strip_all_tags(get_the_excerpt($qid)) : '';
        if ($ex) { $desc = $ex; }
        if (function_exists('get_the_post_thumbnail_url')) {
            $fi = get_the_post_thumbnail_url($qid, 'large');
            if ($fi) { $img = $fi; }
        }
    // og:type by context
        $ogType = (get_post_type($qid) === 'product') ? 'product' : 'article';
        echo '<meta property="og:type" content="' . esc_attr($ogType) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(get_permalink($qid)) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
        if ($img) {
            echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";
            echo '<meta name="twitter:image" content="' . esc_url($img) . '">' . "\n";
        }

        // Article schema (default for posts/pages)
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'datePublished' => function_exists('get_the_date') ? get_the_date('c', $qid) : null,
            'dateModified'  => function_exists('get_the_modified_date') ? get_the_modified_date('c', $qid) : null,
            'author' => [ 'name' => get_bloginfo('name') ],
        ];

        // If this is a WooCommerce product, prefer Product schema
        if (get_post_type($qid) === 'product' && class_exists('WooCommerce')) {
            $currency = function_exists('get_woocommerce_currency') ? call_user_func('get_woocommerce_currency') : 'USD';
            $price = null; $inStock = null; $sku = get_post_meta($qid, '_sku', true);
            $avg = null; $rc = null;
            if (function_exists('wc_get_product')) {
                $p = call_user_func('wc_get_product', $qid);
                if ($p) {
                    $price = $p->get_price();
                    $inStock = $p->is_in_stock();
                    if (is_callable([$p,'get_average_rating'])) { $avg = (float) $p->get_average_rating(); }
                    if (is_callable([$p,'get_review_count'])) { $rc = (int) $p->get_review_count(); }
                }
            } else {
                $price = get_post_meta($qid, '_price', true);
            }
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => get_the_title($qid),
                'image' => $img ?: null,
                'description' => $desc,
                'sku' => $sku ?: null,
                'brand' => [ 'name' => get_bloginfo('name') ],
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => $currency,
                    'price' => $price,
                    'availability' => $inStock === null ? null : ($inStock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'),
                    'url' => get_permalink($qid),
                ],
            ];
            // AggregateRating
            if ($avg !== null && $rc !== null) {
                $schema['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $avg,
                    'reviewCount' => $rc,
                ];
            }
        }
        echo '<script type="application/ld+json">' . wp_json_encode(array_filter($schema)) . '</script>' . "\n";
    } else {
        // Non-singular: still output basic OG for the site
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
        if ($img) echo '<meta property="og:image" content="' . esc_url($img) . '">' . "\n";
        // Best-effort canonical for archives
        global $wp; $req = (is_object($wp) && isset($wp->request)) ? $wp->request : '';
        $archive_url = home_url('/' . ltrim($req, '/') . '/');
        echo '<meta property="og:url" content="' . esc_url($archive_url) . '">' . "\n";
    }

    // BreadcrumbList JSON-LD (best-effort)
    $items = [];
    $pos = 1;
    $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_bloginfo('name'), 'item' => home_url('/') ];
    if (function_exists('is_singular') && is_singular()) {
        $qid = get_queried_object_id();
        $ptype = get_post_type($qid);
        if ($ptype === 'page') {
            $anc = array_reverse(get_post_ancestors($qid));
            foreach ($anc as $aid) {
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title($aid), 'item' => get_permalink($aid) ];
            }
        } elseif ($ptype === 'post') {
            $cats = function_exists('get_the_category') ? get_the_category($qid) : [];
            if (!empty($cats)) {
                $cat = $cats[0];
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($cat->term_id, 'category')) : [];
                foreach ($anc as $tid) {
                    $t = get_term($tid, 'category'); if ($t && !is_wp_error($t)) {
                        $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $t->name, 'item' => get_term_link($t) ];
                    }
                }
                // current category
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $cat->name, 'item' => get_term_link($cat) ];
            }
        } elseif ($ptype === 'product' && class_exists('WooCommerce')) {
            // Shop page
            $shop_id = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
            if ($shop_id) {
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title($shop_id), 'item' => get_permalink($shop_id) ];
            }
            // Product category chain
            $terms = get_the_terms($qid, 'product_cat');
            if (!is_wp_error($terms) && !empty($terms)) {
                $cat = array_shift($terms);
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($cat->term_id, 'product_cat')) : [];
                foreach ($anc as $tid) {
                    $t = get_term($tid, 'product_cat'); if ($t && !is_wp_error($t)) {
                        $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $t->name, 'item' => get_term_link($t) ];
                    }
                }
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $cat->name, 'item' => get_term_link($cat) ];
            }
        }
        // Current item
        $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title($qid), 'item' => get_permalink($qid) ];
    } else {
        // Archives
        if (function_exists('is_tax') && is_tax('product_cat')) {
            if (class_exists('WooCommerce')) {
                $shop_id = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
                if ($shop_id) { $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title($shop_id), 'item' => get_permalink($shop_id) ]; }
            }
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($term->term_id, 'product_cat')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'product_cat'); if ($t && !is_wp_error($t)) { $items[] = [ '@type'=>'ListItem','position'=>$pos++, 'name'=>$t->name, 'item'=>get_term_link($t) ]; } }
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $term->name, 'item' => get_term_link($term) ];
            }
        } elseif (function_exists('is_shop') && function_exists('class_exists') && class_exists('WooCommerce') && is_shop()) {
            // Woo shop archive itself
            $shop_id = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
            if ($shop_id) {
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title($shop_id), 'item' => get_permalink($shop_id) ];
            }
        } elseif (function_exists('is_category') && is_category()) {
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($term->term_id, 'category')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'category'); if ($t && !is_wp_error($t)) { $items[] = [ '@type'=>'ListItem','position'=>$pos++, 'name'=>$t->name, 'item'=>get_term_link($t) ]; } }
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => $term->name, 'item' => get_term_link($term) ];
            }
        } elseif (function_exists('is_tag') && is_tag()) {
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                $items[] = [ '@type' => 'ListItem', 'position' => $pos++, 'name' => sprintf(__('Tag: %s','aqualuxe'), $term->name), 'item' => get_term_link($term) ];
            }
        }
    }
    if (count($items) > 1) {
        $breadcrumb = [ '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items ];
        echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb) . '</script>' . "\n";
    }
}, 5);
