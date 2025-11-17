<?php
/**
 * Template Parts Helper Functions
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get breadcrumbs data
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = [];
    
    // Home
    $breadcrumbs[] = [
        'title' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    ];
    
    if (is_home() && !is_front_page()) {
        $breadcrumbs[] = [
            'title' => get_the_title(get_option('page_for_posts')),
            'url' => '',
        ];
    } elseif (is_single()) {
        $post_type = get_post_type();
        
        if ($post_type === 'post') {
            if (get_option('page_for_posts')) {
                $breadcrumbs[] = [
                    'title' => get_the_title(get_option('page_for_posts')),
                    'url' => get_permalink(get_option('page_for_posts')),
                ];
            }
            
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                ];
            }
        } elseif ($post_type === 'product' && aqualuxe_is_woocommerce_active()) {
            $shop_page_id = wc_get_page_id('shop');
            if ($shop_page_id) {
                $breadcrumbs[] = [
                    'title' => get_the_title($shop_page_id),
                    'url' => get_permalink($shop_page_id),
                ];
            }
            
            $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
            if (!empty($product_cats) && !is_wp_error($product_cats)) {
                $product_cat = $product_cats[0];
                $breadcrumbs[] = [
                    'title' => $product_cat->name,
                    'url' => get_term_link($product_cat),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_page()) {
        $page_id = get_the_ID();
        $ancestors = get_post_ancestors($page_id);
        
        if (!empty($ancestors)) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = [
                    'title' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_category()) {
        $breadcrumbs[] = [
            'title' => single_cat_title('', false),
            'url' => '',
        ];
    } elseif (is_tag()) {
        $breadcrumbs[] = [
            'title' => single_tag_title('', false),
            'url' => '',
        ];
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        ];
    } elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('404 Error', 'aqualuxe'),
            'url' => '',
        ];
    }
    
    return apply_filters('aqualuxe_breadcrumbs', $breadcrumbs);
}

/**
 * Get reading time for a post
 */
function aqualuxe_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    if ($reading_time == 1) {
        return __('1 min read', 'aqualuxe');
    } else {
        /* translators: %d: Reading time in minutes */
        return sprintf(__('%d min read', 'aqualuxe'), $reading_time);
    }
}

/**
 * Social links fallback for footer
 */
function aqualuxe_footer_menu_fallback() {
    $pages = array('privacy-policy', 'terms-and-conditions', 'contact');
    echo '<ul class="footer-menu flex flex-wrap justify-center lg:justify-end space-x-6">';
    
    foreach ($pages as $page_slug) {
        $page = get_page_by_path($page_slug);
        if ($page) {
            echo '<li><a href="' . esc_url(get_permalink($page)) . '" class="text-gray-400 hover:text-white transition-colors duration-200">' . esc_html(get_the_title($page)) . '</a></li>';
        }
    }
    
    echo '</ul>';
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    $pages = array('about', 'services', 'contact');
    if (aqualuxe_is_woocommerce_active()) {
        array_unshift($pages, 'shop');
    }
    
    echo '<ul class="primary-menu flex items-center space-x-6">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors duration-200">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    foreach ($pages as $page_slug) {
        $page = get_page_by_path($page_slug);
        if ($page) {
            echo '<li><a href="' . esc_url(get_permalink($page)) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors duration-200">' . esc_html(get_the_title($page)) . '</a></li>';
        } elseif ($page_slug === 'shop' && aqualuxe_is_woocommerce_active()) {
            echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors duration-200">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
        }
    }
    
    echo '</ul>';
}

/**
 * Mobile menu fallback
 */
function aqualuxe_mobile_menu_fallback() {
    $pages = array('about', 'services', 'contact');
    if (aqualuxe_is_woocommerce_active()) {
        array_unshift($pages, 'shop');
    }
    
    echo '<ul class="mobile-menu-list space-y-2">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    foreach ($pages as $page_slug) {
        $page = get_page_by_path($page_slug);
        if ($page) {
            echo '<li><a href="' . esc_url(get_permalink($page)) . '" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200">' . esc_html(get_the_title($page)) . '</a></li>';
        } elseif ($page_slug === 'shop' && aqualuxe_is_woocommerce_active()) {
            echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="block px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
        }
    }
    
    echo '</ul>';
}

/**
 * Custom Walker for Navigation Menu
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '<ul class="sub-menu absolute left-0 top-full mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">';
        } else {
            $output .= '<ul class="sub-menu">';
        }
    }
    
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        if ($depth === 0) {
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . ' relative group"' : ' class="relative group"';
        } else {
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        }
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        if ($depth === 0) {
            $link_class = 'text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 font-medium transition-colors duration-200 flex items-center';
        } else {
            $link_class = 'block px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a class="' . $link_class . '"' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown arrow for parent items
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $item_output .= '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Custom Walker for Mobile Navigation Menu
 */
class AquaLuxe_Walker_Mobile_Nav_Menu extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="sub-menu ml-4 mt-2 space-y-1">';
    }
    
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
    
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $link_class = 'block px-4 py-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors duration-200';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a class="' . $link_class . '"' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}