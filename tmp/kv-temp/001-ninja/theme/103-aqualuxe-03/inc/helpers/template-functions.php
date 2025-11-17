<?php
/**
 * Template helper functions
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get the post thumbnail with fallback
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'medium', $attr = []) {
    $post_id = $post_id ? $post_id : get_the_ID();
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }
    
    // Fallback image
    $fallback = AQUALUXE_ASSETS_URI . '/dist/images/placeholder.jpg';
    $alt = get_the_title($post_id);
    
    return sprintf(
        '<img src="%s" alt="%s" class="%s">',
        esc_url($fallback),
        esc_attr($alt),
        esc_attr($attr['class'] ?? '')
    );
}

/**
 * Get breadcrumb navigation
 */
function aqualuxe_get_breadcrumb() {
    if (is_front_page()) {
        return '';
    }
    
    $breadcrumb = '<nav class="breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    $breadcrumb .= '<ol class="flex items-center space-x-2 text-sm text-neutral-600 dark:text-neutral-300">';
    
    // Home link
    $breadcrumb .= '<li>';
    $breadcrumb .= '<a href="' . esc_url(home_url('/')) . '" class="hover:text-primary-600 transition-colors">';
    $breadcrumb .= esc_html__('Home', 'aqualuxe');
    $breadcrumb .= '</a>';
    $breadcrumb .= '</li>';
    
    if (is_category()) {
        $breadcrumb .= '<li class="flex items-center">';
        $breadcrumb .= '<svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
        $breadcrumb .= '<span>' . single_cat_title('', false) . '</span>';
        $breadcrumb .= '</li>';
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            $breadcrumb .= '<li class="flex items-center">';
            $breadcrumb .= '<svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
            $breadcrumb .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="hover:text-primary-600 transition-colors">';
            $breadcrumb .= esc_html($category->name);
            $breadcrumb .= '</a>';
            $breadcrumb .= '</li>';
        }
        
        $breadcrumb .= '<li class="flex items-center">';
        $breadcrumb .= '<svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
        $breadcrumb .= '<span>' . get_the_title() . '</span>';
        $breadcrumb .= '</li>';
    } elseif (is_page()) {
        $breadcrumb .= '<li class="flex items-center">';
        $breadcrumb .= '<svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';
        $breadcrumb .= '<span>' . get_the_title() . '</span>';
        $breadcrumb .= '</li>';
    }
    
    $breadcrumb .= '</ol>';
    $breadcrumb .= '</nav>';
    
    return $breadcrumb;
}

/**
 * Get reading time estimate
 */
function aqualuxe_get_reading_time($post_id = null) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    return sprintf(
        esc_html(_n('%d min read', '%d min read', $reading_time, 'aqualuxe')),
        $reading_time
    );
}

/**
 * Get social share buttons
 */
function aqualuxe_get_social_share($post_id = null) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    
    $share_buttons = '<div class="social-share flex items-center space-x-4">';
    $share_buttons .= '<span class="text-sm font-medium text-neutral-600 dark:text-neutral-300">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    
    // Facebook
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url);
    $share_buttons .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-700 transition-colors" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
    $share_buttons .= '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
    $share_buttons .= '</a>';
    
    // Twitter
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title);
    $share_buttons .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" class="text-blue-400 hover:text-blue-500 transition-colors" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '">';
    $share_buttons .= '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>';
    $share_buttons .= '</a>';
    
    // LinkedIn
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url);
    $share_buttons .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" class="text-blue-700 hover:text-blue-800 transition-colors" aria-label="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
    $share_buttons .= '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
    $share_buttons .= '</a>';
    
    $share_buttons .= '</div>';
    
    return $share_buttons;
}

/**
 * Get post navigation
 */
function aqualuxe_get_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return '';
    }
    
    $navigation = '<nav class="post-navigation flex items-center justify-between py-8 border-t border-neutral-200 dark:border-neutral-700">';
    
    if ($prev_post) {
        $navigation .= '<div class="nav-previous">';
        $navigation .= '<a href="' . esc_url(get_permalink($prev_post)) . '" class="flex items-center text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400">';
        $navigation .= '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
        $navigation .= '<div>';
        $navigation .= '<div class="text-sm text-neutral-500">' . esc_html__('Previous', 'aqualuxe') . '</div>';
        $navigation .= '<div class="font-medium">' . esc_html(get_the_title($prev_post)) . '</div>';
        $navigation .= '</div>';
        $navigation .= '</a>';
        $navigation .= '</div>';
    } else {
        $navigation .= '<div></div>';
    }
    
    if ($next_post) {
        $navigation .= '<div class="nav-next">';
        $navigation .= '<a href="' . esc_url(get_permalink($next_post)) . '" class="flex items-center text-neutral-600 hover:text-primary-600 transition-colors dark:text-neutral-300 dark:hover:text-primary-400">';
        $navigation .= '<div class="text-right">';
        $navigation .= '<div class="text-sm text-neutral-500">' . esc_html__('Next', 'aqualuxe') . '</div>';
        $navigation .= '<div class="font-medium">' . esc_html(get_the_title($next_post)) . '</div>';
        $navigation .= '</div>';
        $navigation .= '<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        $navigation .= '</a>';
        $navigation .= '</div>';
    }
    
    $navigation .= '</nav>';
    
    return $navigation;
}

/**
 * Get related posts
 */
function aqualuxe_get_related_posts($post_id = null, $limit = 3) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return '';
    }
    
    $related_posts = new WP_Query([
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'post__not_in'   => [$post_id],
        'category__in'   => $categories,
        'orderby'        => 'rand',
    ]);
    
    if (!$related_posts->have_posts()) {
        return '';
    }
    
    $output = '<section class="related-posts mt-12 pt-12 border-t border-neutral-200 dark:border-neutral-700">';
    $output .= '<h3 class="text-2xl font-bold mb-8">' . esc_html__('Related Articles', 'aqualuxe') . '</h3>';
    $output .= '<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">';
    
    while ($related_posts->have_posts()) {
        $related_posts->the_post();
        
        $output .= '<article class="card group">';
        
        if (has_post_thumbnail()) {
            $output .= '<div class="aspect-video overflow-hidden">';
            $output .= get_the_post_thumbnail(get_the_ID(), 'medium', [
                'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300'
            ]);
            $output .= '</div>';
        }
        
        $output .= '<div class="p-4">';
        $output .= '<h4 class="font-semibold mb-2">';
        $output .= '<a href="' . esc_url(get_permalink()) . '" class="text-neutral-800 hover:text-primary-600 transition-colors dark:text-neutral-100 dark:hover:text-primary-400">';
        $output .= get_the_title();
        $output .= '</a>';
        $output .= '</h4>';
        $output .= '<div class="text-sm text-neutral-500 mb-3">' . get_the_date() . '</div>';
        $output .= '<div class="text-sm text-neutral-600 dark:text-neutral-300">' . wp_trim_words(get_the_excerpt(), 15) . '</div>';
        $output .= '</div>';
        $output .= '</article>';
    }
    
    $output .= '</div>';
    $output .= '</section>';
    
    wp_reset_postdata();
    
    return $output;
}

/**
 * Check if dark mode is enabled
 */
function aqualuxe_is_dark_mode() {
    return get_theme_mod('aqualuxe_dark_mode_default', false);
}

/**
 * Get theme option with fallback
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod('aqualuxe_' . $option, $default);
}