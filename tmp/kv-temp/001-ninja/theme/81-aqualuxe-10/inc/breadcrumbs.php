<?php
/** Visible breadcrumbs helper */
if (!defined('ABSPATH')) { exit; }

function aqualuxe_get_breadcrumb_items() {
    $items = [];
    $items[] = [ 'label' => __('Home','aqualuxe'), 'url' => home_url('/') ];
    if (function_exists('is_front_page') && is_front_page()) return $items;
    if (function_exists('is_singular') && is_singular()) {
        $qid = get_queried_object_id();
        $ptype = get_post_type($qid);
        if ($ptype === 'page') {
            $anc = array_reverse(get_post_ancestors($qid));
            foreach ($anc as $aid) { $items[] = [ 'label' => get_the_title($aid), 'url' => get_permalink($aid) ]; }
        } elseif ($ptype === 'post') {
            $cats = function_exists('get_the_category') ? get_the_category($qid) : [];
            if (!empty($cats)) {
                $cat = $cats[0];
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($cat->term_id, 'category')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'category'); if ($t && !is_wp_error($t)) { $items[] = [ 'label'=>$t->name, 'url'=>get_term_link($t) ]; } }
                $items[] = [ 'label' => $cat->name, 'url' => get_term_link($cat) ];
            }
        } elseif ($ptype === 'product' && class_exists('WooCommerce')) {
            $shop_id = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
            if ($shop_id) { $items[] = [ 'label'=> get_the_title($shop_id), 'url'=> get_permalink($shop_id) ]; }
            $terms = get_the_terms($qid, 'product_cat');
            if (!is_wp_error($terms) && !empty($terms)) {
                $cat = array_shift($terms);
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($cat->term_id, 'product_cat')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'product_cat'); if ($t && !is_wp_error($t)) { $items[] = [ 'label'=>$t->name, 'url'=>get_term_link($t) ]; } }
                $items[] = [ 'label' => $cat->name, 'url' => get_term_link($cat) ];
            }
        }
        $items[] = [ 'label' => get_the_title($qid), 'url' => get_permalink($qid), 'current' => true ];
    } else {
        if (function_exists('is_tax') && is_tax('product_cat')) {
            if (class_exists('WooCommerce')) {
                $shop_id = function_exists('wc_get_page_id') ? call_user_func('wc_get_page_id','shop') : 0;
                if ($shop_id) { $items[] = [ 'label'=> get_the_title($shop_id), 'url'=> get_permalink($shop_id) ]; }
            }
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($term->term_id, 'product_cat')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'product_cat'); if ($t && !is_wp_error($t)) { $items[] = [ 'label'=>$t->name, 'url'=>get_term_link($t) ]; } }
                $items[] = [ 'label' => $term->name, 'url' => get_term_link($term), 'current' => true ];
            }
        } elseif (function_exists('is_category') && is_category()) {
            $term = get_queried_object();
            if ($term && !is_wp_error($term)) {
                $anc = function_exists('get_ancestors') ? array_reverse(get_ancestors($term->term_id, 'category')) : [];
                foreach ($anc as $tid) { $t = get_term($tid, 'category'); if ($t && !is_wp_error($t)) { $items[] = [ 'label'=>$t->name, 'url'=>get_term_link($t) ]; } }
                $items[] = [ 'label' => $term->name, 'url' => get_term_link($term), 'current' => true ];
            }
        }
    }
    return $items;
}

function aqualuxe_breadcrumbs($echo = true) {
    if (!get_theme_mod('aqualuxe_breadcrumbs_enabled', true)) return '';
    $items = aqualuxe_get_breadcrumb_items();
    if (count($items) < 2) return '';
    $out = '<nav class="ax-breadcrumb" aria-label="Breadcrumb"><ol class="flex flex-wrap gap-2 text-sm opacity-80">';
    $i = 0; $last = count($items) - 1;
    foreach ($items as $idx => $it) {
        $label = esc_html($it['label']);
        if ($idx === $last || !empty($it['current'])) {
            $out .= '<li aria-current="page" class="truncate max-w-[14ch]">' . $label . '</li>';
        } else {
            $out .= '<li><a class="ax-bc-link" href="' . esc_url($it['url']) . '">' . $label . '</a></li>';
        }
    }
    $out .= '</ol></nav>';
    if ($echo) { echo $out; return null; }
    return $out;
}

add_shortcode('ax_breadcrumbs', function(){ ob_start(); aqualuxe_breadcrumbs(true); return ob_get_clean(); });
