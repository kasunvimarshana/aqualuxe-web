<?php
/**
 * Template tags for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site logo
 *
 * @param array $args Logo args
 * @return void
 */
function aqualuxe_site_logo($args = []) {
    $defaults = [
        'class' => 'site-logo',
        'width' => 150,
        'height' => 50,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
    
    if ($logo) {
        $logo_url = $logo[0];
        $logo_width = $logo[1];
        $logo_height = $logo[2];
        
        // Use actual dimensions if available
        if ($logo_width && $logo_height) {
            $args['width'] = $logo_width;
            $args['height'] = $logo_height;
        }
        
        echo '<a href="' . esc_url(home_url('/')) . '" class="' . esc_attr($args['class']) . '" rel="home">';
        echo '<img src="' . esc_url($logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" />';
        echo '</a>';
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title text-2xl font-bold text-primary dark:text-white" rel="home">';
        echo esc_html(get_bloginfo('name'));
        echo '</a>';
        
        if (get_bloginfo('description')) {
            echo '<p class="site-description text-sm text-gray-600 dark:text-gray-400">' . esc_html(get_bloginfo('description')) . '</p>';
        }
    }
}

/**
 * Display primary navigation
 *
 * @return void
 */
function aqualuxe_primary_nav() {
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'container' => 'nav',
            'container_class' => 'primary-menu-container hidden md:block',
            'menu_class' => 'primary-menu flex',
            'fallback_cb' => false,
            'depth' => 2,
        ]);
    }
}

/**
 * Display mobile navigation
 *
 * @return void
 */
function aqualuxe_mobile_nav() {
    if (has_nav_menu('mobile') || has_nav_menu('primary')) {
        $menu_location = has_nav_menu('mobile') ? 'mobile' : 'primary';
        
        echo '<button id="mobile-menu-button" class="mobile-menu-button md:hidden p-2 focus:outline-none" aria-label="' . esc_attr__('Toggle mobile menu', 'aqualuxe') . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
        echo '</svg>';
        echo '</button>';
        
        echo '<div id="mobile-menu" class="mobile-menu hidden md:hidden absolute top-full left-0 right-0 bg-white dark:bg-dark-bg shadow-lg z-50">';
        
        wp_nav_menu([
            'theme_location' => $menu_location,
            'menu_id' => 'mobile-menu-items',
            'container' => 'nav',
            'container_class' => 'mobile-menu-container',
            'menu_class' => 'mobile-menu-items',
            'fallback_cb' => false,
            'depth' => 1,
        ]);
        
        echo '</div>';
    }
}

/**
 * Display footer navigation
 *
 * @return void
 */
function aqualuxe_footer_nav() {
    if (has_nav_menu('footer')) {
        wp_nav_menu([
            'theme_location' => 'footer',
            'menu_id' => 'footer-menu',
            'container' => 'nav',
            'container_class' => 'footer-menu-container',
            'menu_class' => 'footer-menu',
            'fallback_cb' => false,
            'depth' => 1,
        ]);
    }
}

/**
 * Display social navigation
 *
 * @return void
 */
function aqualuxe_social_nav() {
    if (has_nav_menu('social')) {
        wp_nav_menu([
            'theme_location' => 'social',
            'menu_id' => 'social-menu',
            'container' => 'nav',
            'container_class' => 'social-menu-container',
            'menu_class' => 'social-menu flex',
            'fallback_cb' => false,
            'depth' => 1,
            'link_before' => '<span class="screen-reader-text">',
            'link_after' => '</span>',
        ]);
    }
}

/**
 * Display dark mode toggle
 *
 * @return void
 */
function aqualuxe_dark_mode_toggle() {
    $is_dark = aqualuxe_is_dark_mode();
    
    echo '<div class="dark-mode-toggle ml-4">';
    echo '<input type="checkbox" id="dark-mode-toggle" ' . ($is_dark ? 'checked' : '') . '>';
    echo '<label for="dark-mode-toggle" class="toggle"></label>';
    echo '</div>';
}

/**
 * Display language switcher
 *
 * @return void
 */
function aqualuxe_language_switcher() {
    $languages = aqualuxe_get_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $current = null;
    
    foreach ($languages as $language) {
        if ($language['active']) {
            $current = $language;
            break;
        }
    }
    
    if (!$current) {
        return;
    }
    
    echo '<div class="language-switcher relative" x-data="{ open: false }">';
    echo '<button @click="open = !open" @click.away="open = false" class="language-switcher-button">';
    
    if ($current['flag']) {
        echo '<img src="' . esc_url($current['flag']) . '" alt="' . esc_attr($current['name']) . '" class="w-5 h-auto inline-block mr-2">';
    }
    
    echo esc_html($current['code']);
    
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
    echo '</svg>';
    echo '</button>';
    
    echo '<div x-show="open" class="language-switcher-dropdown" style="display: none;">';
    echo '<div class="py-1">';
    
    foreach ($languages as $language) {
        if (!$language['active']) {
            echo '<a href="' . esc_url($language['url']) . '" class="language-switcher-item">';
            
            if ($language['flag']) {
                echo '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="w-5 h-auto inline-block mr-2">';
            }
            
            echo esc_html($language['name']);
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display post thumbnail
 *
 * @param string $size Thumbnail size
 * @return void
 */
function aqualuxe_post_thumbnail($size = 'post-thumbnail') {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    
    if (is_singular()) {
        echo '<div class="post-thumbnail mb-6">';
        the_post_thumbnail($size, [
            'class' => 'w-full h-auto rounded-lg',
            'alt' => get_the_title(),
        ]);
        echo '</div>';
    } else {
        echo '<a class="post-thumbnail mb-4 block" href="' . esc_url(get_permalink()) . '" aria-hidden="true" tabindex="-1">';
        the_post_thumbnail($size, [
            'class' => 'w-full h-auto rounded-lg',
            'alt' => get_the_title(),
        ]);
        echo '</a>';
    }
}

/**
 * Display post meta
 *
 * @return void
 */
function aqualuxe_post_meta() {
    echo '<div class="post-meta text-sm text-gray-600 dark:text-gray-400 mb-4">';
    
    // Author
    echo '<span class="post-author">';
    echo esc_html__('By', 'aqualuxe') . ' ';
    echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">';
    echo esc_html(get_the_author());
    echo '</a>';
    echo '</span>';
    
    // Date
    echo '<span class="post-date mx-2">|</span>';
    echo '<span class="post-date">';
    echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
    echo '</span>';
    
    // Categories
    $categories_list = get_the_category_list(', ');
    if ($categories_list) {
        echo '<span class="post-categories mx-2">|</span>';
        echo '<span class="post-categories">';
        echo esc_html__('In', 'aqualuxe') . ' ' . $categories_list;
        echo '</span>';
    }
    
    // Comments
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="post-comments mx-2">|</span>';
        echo '<span class="post-comments">';
        
        comments_popup_link(
            esc_html__('No Comments', 'aqualuxe'),
            esc_html__('1 Comment', 'aqualuxe'),
            esc_html__('% Comments', 'aqualuxe')
        );
        
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display post tags
 *
 * @return void
 */
function aqualuxe_post_tags() {
    $tags_list = get_the_tag_list('', ' ');
    
    if ($tags_list) {
        echo '<div class="post-tags mt-6">';
        echo '<span class="font-bold mr-2">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        echo $tags_list;
        echo '</div>';
    }
}

/**
 * Display post pagination
 *
 * @return void
 */
function aqualuxe_post_pagination() {
    wp_link_pages([
        'before' => '<div class="page-links mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"><span class="page-links-title font-bold mr-2">' . __('Pages:', 'aqualuxe') . '</span>',
        'after' => '</div>',
        'link_before' => '<span class="page-link bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-md">',
        'link_after' => '</span>',
        'separator' => '<span class="mx-1"></span>',
    ]);
}

/**
 * Display post navigation
 *
 * @return void
 */
function aqualuxe_post_navigation() {
    the_post_navigation([
        'prev_text' => '<span class="nav-subtitle text-sm block mb-1">' . esc_html__('Previous Post', 'aqualuxe') . '</span><span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle text-sm block mb-1">' . esc_html__('Next Post', 'aqualuxe') . '</span><span class="nav-title">%title</span>',
    ]);
}

/**
 * Display posts pagination
 *
 * @return void
 */
function aqualuxe_posts_pagination() {
    the_posts_pagination([
        'mid_size' => 2,
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg><span class="screen-reader-text">' . esc_html__('Previous page', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
    ]);
}

/**
 * Display breadcrumbs
 *
 * @return void
 */
function aqualuxe_breadcrumbs() {
    // Skip on front page
    if (is_front_page()) {
        return;
    }
    
    echo '<nav class="breadcrumbs py-4 text-sm" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<ol class="flex flex-wrap items-center">';
    
    // Home
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . esc_url(home_url('/')) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">';
    echo esc_html__('Home', 'aqualuxe');
    echo '</a>';
    echo '</li>';
    
    // Separator
    echo '<li class="breadcrumb-separator mx-2">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
    echo '</svg>';
    echo '</li>';
    
    if (is_category() || is_single()) {
        // Category
        if (is_single()) {
            $categories = get_the_category();
            
            if (!empty($categories)) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">';
                echo esc_html($categories[0]->name);
                echo '</a>';
                echo '</li>';
                
                // Separator
                echo '<li class="breadcrumb-separator mx-2">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
                echo '</svg>';
                echo '</li>';
            }
        }
        
        // Current
        echo '<li class="breadcrumb-item">';
        
        if (is_category()) {
            single_cat_title();
        } elseif (is_single()) {
            the_title();
        }
        
        echo '</li>';
    } elseif (is_page()) {
        // Parent pages
        if ($post->post_parent) {
            $parents = get_post_ancestors($post->ID);
            $parents = array_reverse($parents);
            
            foreach ($parents as $parent) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_permalink($parent)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">';
                echo esc_html(get_the_title($parent));
                echo '</a>';
                echo '</li>';
                
                // Separator
                echo '<li class="breadcrumb-separator mx-2">';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />';
                echo '</svg>';
                echo '</li>';
            }
        }
        
        // Current
        echo '<li class="breadcrumb-item">';
        the_title();
        echo '</li>';
    } elseif (is_tag()) {
        // Tag
        echo '<li class="breadcrumb-item">';
        single_tag_title();
        echo '</li>';
    } elseif (is_author()) {
        // Author
        echo '<li class="breadcrumb-item">';
        echo esc_html__('Author Archives:', 'aqualuxe') . ' ' . get_the_author();
        echo '</li>';
    } elseif (is_year()) {
        // Year
        echo '<li class="breadcrumb-item">';
        echo esc_html__('Yearly Archives:', 'aqualuxe') . ' ' . get_the_date('Y');
        echo '</li>';
    } elseif (is_month()) {
        // Month
        echo '<li class="breadcrumb-item">';
        echo esc_html__('Monthly Archives:', 'aqualuxe') . ' ' . get_the_date('F Y');
        echo '</li>';
    } elseif (is_day()) {
        // Day
        echo '<li class="breadcrumb-item">';
        echo esc_html__('Daily Archives:', 'aqualuxe') . ' ' . get_the_date();
        echo '</li>';
    } elseif (is_post_type_archive()) {
        // Post type
        echo '<li class="breadcrumb-item">';
        post_type_archive_title();
        echo '</li>';
    } elseif (is_tax()) {
        // Taxonomy
        echo '<li class="breadcrumb-item">';
        single_term_title();
        echo '</li>';
    } elseif (is_search()) {
        // Search
        echo '<li class="breadcrumb-item">';
        echo esc_html__('Search Results for:', 'aqualuxe') . ' ' . get_search_query();
        echo '</li>';
    } elseif (is_404()) {
        // 404
        echo '<li class="breadcrumb-item">';
        echo esc_html__('404 Not Found', 'aqualuxe');
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Display page title
 *
 * @return void
 */
function aqualuxe_page_title() {
    if (is_front_page()) {
        return;
    }
    
    echo '<header class="page-header mb-8">';
    
    if (is_home()) {
        echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-2">';
        echo esc_html(get_the_title(get_option('page_for_posts')));
        echo '</h1>';
    } elseif (is_archive()) {
        echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-2">';
        the_archive_title();
        echo '</h1>';
        
        the_archive_description();
    } elseif (is_search()) {
        echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-2">';
        printf(
            /* translators: %s: search query */
            esc_html__('Search Results for: %s', 'aqualuxe'),
            '<span>' . get_search_query() . '</span>'
        );
        echo '</h1>';
    } elseif (is_404()) {
        echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-2">';
        echo esc_html__('Page Not Found', 'aqualuxe');
        echo '</h1>';
    }
    
    echo '</header>';
}

/**
 * Display post entry header
 *
 * @return void
 */
function aqualuxe_entry_header() {
    if (is_singular()) {
        echo '<header class="entry-header mb-6">';
        
        echo '<h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">';
        the_title();
        echo '</h1>';
        
        aqualuxe_post_meta();
        
        echo '</header>';
    } else {
        echo '<header class="entry-header mb-4">';
        
        echo '<h2 class="entry-title text-xl md:text-2xl font-bold mb-2">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="hover:text-primary transition-colors duration-300">';
        the_title();
        echo '</a>';
        echo '</h2>';
        
        aqualuxe_post_meta();
        
        echo '</header>';
    }
}

/**
 * Display post entry content
 *
 * @return void
 */
function aqualuxe_entry_content() {
    echo '<div class="entry-content prose dark:prose-invert max-w-none">';
    
    if (is_singular()) {
        the_content();
        
        aqualuxe_post_pagination();
    } else {
        the_excerpt();
        
        echo '<p class="mt-4">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-primary">';
        echo esc_html__('Read More', 'aqualuxe');
        echo '</a>';
        echo '</p>';
    }
    
    echo '</div>';
}

/**
 * Display post entry footer
 *
 * @return void
 */
function aqualuxe_entry_footer() {
    if (is_singular()) {
        echo '<footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">';
        
        aqualuxe_post_tags();
        
        if (function_exists('aqualuxe_social_sharing')) {
            aqualuxe_social_sharing();
        }
        
        echo '</footer>';
    }
}

/**
 * Display social sharing buttons
 *
 * @return void
 */
function aqualuxe_social_sharing() {
    if (!is_singular()) {
        return;
    }
    
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(null, 'large')) : '';
    
    echo '<div class="social-sharing mt-6">';
    echo '<h3 class="text-lg font-bold mb-4">' . esc_html__('Share This Post', 'aqualuxe') . '</h3>';
    echo '<div class="flex space-x-2">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" class="social-share-link facebook bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link twitter bg-blue-400 hover:bg-blue-500 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
    echo '</a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link linkedin bg-blue-700 hover:bg-blue-800 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
    echo '</a>';
    
    // Pinterest (only if post has thumbnail)
    if ($post_thumbnail) {
        echo '<a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="social-share-link pinterest bg-red-600 hover:bg-red-700 text-white p-2 rounded-full transition-colors duration-300">';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>';
        echo '</a>';
    }
    
    // Email
    echo '<a href="mailto:?subject=' . $post_title . '&body=' . $post_url . '" class="social-share-link email bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-full transition-colors duration-300">';
    echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display author bio
 *
 * @return void
 */
function aqualuxe_author_bio() {
    if (!is_singular('post')) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    
    echo '<div class="author-bio mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">';
    echo '<div class="flex flex-wrap items-center">';
    
    // Author avatar
    echo '<div class="author-avatar w-full md:w-auto mb-4 md:mb-0 md:mr-6">';
    echo get_avatar($author_id, 96, '', $author_name, [
        'class' => 'rounded-full',
    ]);
    echo '</div>';
    
    // Author info
    echo '<div class="author-info flex-1">';
    echo '<h3 class="author-name text-xl font-bold mb-2">';
    echo '<a href="' . esc_url($author_url) . '" class="hover:text-primary transition-colors duration-300">';
    echo esc_html($author_name);
    echo '</a>';
    echo '</h3>';
    
    echo '<div class="author-description prose dark:prose-invert">';
    echo wpautop($author_description);
    echo '</div>';
    
    echo '<a href="' . esc_url($author_url) . '" class="author-link inline-block mt-2 text-primary hover:text-primary-dark transition-colors duration-300">';
    printf(
        /* translators: %s: author name */
        esc_html__('View all posts by %s', 'aqualuxe'),
        esc_html($author_name)
    );
    echo '</a>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display related posts
 *
 * @param int $count Number of posts
 * @return void
 */
function aqualuxe_related_posts($count = 3) {
    if (!is_singular('post')) {
        return;
    }
    
    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = [];
    
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = [
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post__not_in' => [$post_id],
        'category__in' => $category_ids,
        'orderby' => 'rand',
    ];
    
    $related_posts = new WP_Query($args);
    
    if (!$related_posts->have_posts()) {
        return;
    }
    
    echo '<div class="related-posts mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">';
    echo '<h3 class="text-2xl font-bold mb-6">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
    
    echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
    
    while ($related_posts->have_posts()) {
        $related_posts->the_post();
        
        echo '<article class="related-post card">';
        
        // Thumbnail
        if (has_post_thumbnail()) {
            echo '<a href="' . esc_url(get_permalink()) . '" class="block mb-4">';
            the_post_thumbnail('aqualuxe-card', [
                'class' => 'w-full h-auto rounded-t-lg',
                'alt' => get_the_title(),
            ]);
            echo '</a>';
        }
        
        // Title
        echo '<div class="p-4">';
        echo '<h4 class="text-lg font-bold mb-2">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="hover:text-primary transition-colors duration-300">';
        the_title();
        echo '</a>';
        echo '</h4>';
        
        // Date
        echo '<div class="text-sm text-gray-600 dark:text-gray-400">';
        echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
        echo '</div>';
        echo '</div>';
        
        echo '</article>';
    }
    
    echo '</div>';
    echo '</div>';
    
    wp_reset_postdata();
}

/**
 * Display comments
 *
 * @return void
 */
function aqualuxe_comments() {
    if (post_password_required()) {
        return;
    }
    
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Display newsletter form
 *
 * @return void
 */
function aqualuxe_newsletter_form() {
    echo '<div class="newsletter-form mt-8 p-6 bg-primary-light dark:bg-primary-dark rounded-lg text-center">';
    echo '<h3 class="text-2xl font-bold mb-2">' . esc_html__('Subscribe to Our Newsletter', 'aqualuxe') . '</h3>';
    echo '<p class="mb-4">' . esc_html__('Get the latest updates, news and special offers delivered directly to your inbox.', 'aqualuxe') . '</p>';
    
    echo '<form id="newsletter-form" class="flex flex-wrap max-w-md mx-auto">';
    echo '<div class="w-full md:w-2/3 mb-2 md:mb-0 md:pr-2">';
    echo '<input type="email" name="email" placeholder="' . esc_attr__('Your email address', 'aqualuxe') . '" required class="w-full px-4 py-2 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    echo '</div>';
    echo '<div class="w-full md:w-1/3">';
    echo '<button type="submit" class="w-full btn btn-primary">' . esc_html__('Subscribe', 'aqualuxe') . '</button>';
    echo '</div>';
    echo '<div class="w-full mt-2 response-message"></div>';
    echo '</form>';
    
    echo '</div>';
}

/**
 * Display contact information
 *
 * @return void
 */
function aqualuxe_contact_info() {
    $contact_info = aqualuxe_get_contact_info();
    
    if (empty($contact_info['address']) && empty($contact_info['phone']) && empty($contact_info['email'])) {
        return;
    }
    
    echo '<div class="contact-info">';
    
    if (!empty($contact_info['address'])) {
        echo '<div class="contact-address flex items-start mb-4">';
        echo '<div class="contact-icon mr-3 text-primary">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div class="contact-text">';
        echo '<h4 class="font-bold mb-1">' . esc_html__('Address', 'aqualuxe') . '</h4>';
        echo '<p>' . nl2br(esc_html($contact_info['address'])) . '</p>';
        echo '</div>';
        echo '</div>';
    }
    
    if (!empty($contact_info['phone'])) {
        echo '<div class="contact-phone flex items-start mb-4">';
        echo '<div class="contact-icon mr-3 text-primary">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div class="contact-text">';
        echo '<h4 class="font-bold mb-1">' . esc_html__('Phone', 'aqualuxe') . '</h4>';
        echo '<p><a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($contact_info['phone']) . '</a></p>';
        echo '</div>';
        echo '</div>';
    }
    
    if (!empty($contact_info['email'])) {
        echo '<div class="contact-email flex items-start mb-4">';
        echo '<div class="contact-icon mr-3 text-primary">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div class="contact-text">';
        echo '<h4 class="font-bold mb-1">' . esc_html__('Email', 'aqualuxe') . '</h4>';
        echo '<p><a href="mailto:' . esc_attr($contact_info['email']) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($contact_info['email']) . '</a></p>';
        echo '</div>';
        echo '</div>';
    }
    
    if (!empty($contact_info['hours'])) {
        echo '<div class="contact-hours flex items-start">';
        echo '<div class="contact-icon mr-3 text-primary">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />';
        echo '</svg>';
        echo '</div>';
        echo '<div class="contact-text">';
        echo '<h4 class="font-bold mb-1">' . esc_html__('Business Hours', 'aqualuxe') . '</h4>';
        echo '<p>' . nl2br(esc_html($contact_info['hours'])) . '</p>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display social links
 *
 * @return void
 */
function aqualuxe_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (empty($social_links)) {
        return;
    }
    
    echo '<div class="social-links flex space-x-2">';
    
    foreach ($social_links as $network => $data) {
        echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" class="social-link ' . esc_attr($network) . ' bg-gray-200 dark:bg-gray-700 hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white p-2 rounded-full transition-colors duration-300">';
        echo '<span class="screen-reader-text">' . esc_html($data['label']) . '</span>';
        
        switch ($network) {
            case 'facebook':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
                break;
            case 'twitter':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
                break;
            case 'instagram':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
                break;
            case 'youtube':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>';
                break;
            case 'linkedin':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
                break;
            case 'pinterest':
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/></svg>';
                break;
        }
        
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Display copyright text
 *
 * @return void
 */
function aqualuxe_copyright() {
    $copyright_text = aqualuxe_get_option('copyright_text');
    
    if (empty($copyright_text)) {
        $copyright_text = sprintf(
            /* translators: %1$s: current year, %2$s: site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        );
    }
    
    echo '<div class="copyright text-sm text-gray-600 dark:text-gray-400">';
    echo wp_kses_post($copyright_text);
    echo '</div>';
}

/**
 * Display back to top button
 *
 * @return void
 */
function aqualuxe_back_to_top() {
    echo '<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 bg-primary hover:bg-primary-dark text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 z-50" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />';
    echo '</svg>';
    echo '</button>';
}

/**
 * Display WooCommerce product card
 *
 * @param int $product_id Product ID
 * @return void
 */
function aqualuxe_product_card($product_id = null) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (null === $product_id) {
        $product_id = get_the_ID();
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product-card relative bg-white dark:bg-dark-card rounded-lg shadow-soft dark:shadow-none overflow-hidden transition-all duration-300 hover:shadow-water">';
    
    // Product image
    echo '<a href="' . esc_url(get_permalink($product_id)) . '" class="product-image block relative overflow-hidden">';
    
    if ($product->is_on_sale()) {
        echo '<span class="sale-badge absolute top-4 left-4 bg-accent text-primary-dark text-xs font-bold px-2 py-1 rounded-md z-10">' . esc_html__('Sale', 'aqualuxe') . '</span>';
    }
    
    if ($product->get_image_id()) {
        echo wp_get_attachment_image($product->get_image_id(), 'woocommerce_thumbnail', false, [
            'class' => 'w-full h-auto transition-transform duration-500 hover:scale-105',
            'alt' => $product->get_name(),
        ]);
    } else {
        echo wc_placeholder_img([
            'class' => 'w-full h-auto',
            'alt' => $product->get_name(),
        ]);
    }
    
    echo '</a>';
    
    // Product details
    echo '<div class="product-details p-4">';
    
    // Product category
    $categories = wc_get_product_category_list($product_id, ', ', '<div class="product-category text-xs text-gray-600 dark:text-gray-400 mb-1">', '</div>');
    if ($categories) {
        echo $categories;
    }
    
    // Product title
    echo '<h3 class="product-title text-lg font-bold mb-2">';
    echo '<a href="' . esc_url(get_permalink($product_id)) . '" class="hover:text-primary transition-colors duration-300">';
    echo esc_html($product->get_name());
    echo '</a>';
    echo '</h3>';
    
    // Product price
    echo '<div class="product-price text-lg font-bold text-primary mb-3">';
    echo $product->get_price_html();
    echo '</div>';
    
    // Product rating
    if ($product->get_rating_count() > 0) {
        echo '<div class="product-rating flex items-center mb-3">';
        echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
        echo '<span class="text-xs text-gray-600 dark:text-gray-400 ml-2">(' . $product->get_rating_count() . ')</span>';
        echo '</div>';
    }
    
    // Product actions
    echo '<div class="product-actions flex items-center justify-between">';
    
    // Add to cart button
    echo '<a href="' . esc_url($product->add_to_cart_url()) . '" class="add-to-cart-button btn btn-primary btn-sm ' . ($product->is_purchasable() && $product->is_in_stock() ? '' : 'disabled') . '" data-product_id="' . esc_attr($product_id) . '" data-product_sku="' . esc_attr($product->get_sku()) . '">';
    
    if ($product->is_purchasable() && $product->is_in_stock()) {
        echo esc_html($product->is_type('variable') ? __('Select Options', 'aqualuxe') : __('Add to Cart', 'aqualuxe'));
    } else {
        echo esc_html__('Read More', 'aqualuxe');
    }
    
    echo '</a>';
    
    // Quick view button
    echo '<button class="quick-view-button text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300" data-product-id="' . esc_attr($product_id) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
    echo '</svg>';
    echo '</button>';
    
    // Wishlist button
    echo '<button class="wishlist-button text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary-light transition-colors duration-300 ' . (aqualuxe_is_in_wishlist($product_id) ? 'wishlist-active text-primary' : '') . '" data-product-id="' . esc_attr($product_id) . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="' . (aqualuxe_is_in_wishlist($product_id) ? 'currentColor' : 'none') . '" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    echo '</svg>';
    echo '</button>';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display WooCommerce product filter
 *
 * @return void
 */
function aqualuxe_product_filter() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop() && !is_product_category() && !is_product_tag()) {
        return;
    }
    
    echo '<div class="product-filters mb-8 p-6 bg-white dark:bg-dark-card rounded-lg shadow-soft dark:shadow-none">';
    echo '<h3 class="text-xl font-bold mb-4">' . esc_html__('Filter Products', 'aqualuxe') . '</h3>';
    
    echo '<form id="product-filters" method="get" action="' . esc_url(wc_get_page_permalink('shop')) . '">';
    
    // Keep query string parameters
    foreach ($_GET as $key => $value) {
        if (!in_array($key, ['min_price', 'max_price', 'product_cat', 'product_tag', 'filter_color', 'filter_size', 'orderby'])) {
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        }
    }
    
    // Price filter
    echo '<div class="filter-section mb-6">';
    echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Price Range', 'aqualuxe') . '</h4>';
    
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '';
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '';
    
    echo '<div class="flex space-x-2">';
    echo '<div class="w-1/2">';
    echo '<label for="min_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Min', 'aqualuxe') . '</label>';
    echo '<input type="number" id="min_price" name="min_price" value="' . esc_attr($min_price) . '" min="0" step="1" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    echo '</div>';
    
    echo '<div class="w-1/2">';
    echo '<label for="max_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . esc_html__('Max', 'aqualuxe') . '</label>';
    echo '<input type="number" id="max_price" name="max_price" value="' . esc_attr($max_price) . '" min="0" step="1" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    // Categories filter
    $product_categories = aqualuxe_get_product_categories();
    
    if (!empty($product_categories)) {
        echo '<div class="filter-section mb-6">';
        echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Categories', 'aqualuxe') . '</h4>';
        
        $current_cat = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
        
        echo '<div class="space-y-2">';
        
        foreach ($product_categories as $category) {
            echo '<div class="flex items-center">';
            echo '<input type="checkbox" id="cat-' . esc_attr($category->slug) . '" name="product_cat[]" value="' . esc_attr($category->slug) . '" ' . (in_array($category->slug, explode(',', $current_cat)) ? 'checked' : '') . ' class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">';
            echo '<label for="cat-' . esc_attr($category->slug) . '" class="ml-2 text-sm text-gray-700 dark:text-gray-300">' . esc_html($category->name) . ' <span class="text-gray-500 dark:text-gray-500">(' . $category->count . ')</span></label>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    // Attributes filter (color, size, etc.)
    $attribute_taxonomies = wc_get_attribute_taxonomies();
    
    if (!empty($attribute_taxonomies)) {
        foreach ($attribute_taxonomies as $attribute) {
            $taxonomy = 'pa_' . $attribute->attribute_name;
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => true,
            ]);
            
            if (!empty($terms) && !is_wp_error($terms)) {
                $filter_name = 'filter_' . $attribute->attribute_name;
                $current_filter = isset($_GET[$filter_name]) ? sanitize_text_field($_GET[$filter_name]) : '';
                $current_filters = explode(',', $current_filter);
                
                echo '<div class="filter-section mb-6">';
                echo '<h4 class="text-lg font-bold mb-3">' . esc_html(ucfirst($attribute->attribute_label)) . '</h4>';
                
                echo '<div class="space-y-2">';
                
                foreach ($terms as $term) {
                    echo '<div class="flex items-center">';
                    echo '<input type="checkbox" id="' . esc_attr($filter_name . '-' . $term->slug) . '" name="' . esc_attr($filter_name) . '[]" value="' . esc_attr($term->slug) . '" ' . (in_array($term->slug, $current_filters) ? 'checked' : '') . ' class="rounded border-gray-300 dark:border-gray-700 text-primary focus:ring-primary">';
                    echo '<label for="' . esc_attr($filter_name . '-' . $term->slug) . '" class="ml-2 text-sm text-gray-700 dark:text-gray-300">' . esc_html($term->name) . ' <span class="text-gray-500 dark:text-gray-500">(' . $term->count . ')</span></label>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
        }
    }
    
    // Sort by
    echo '<div class="filter-section">';
    echo '<h4 class="text-lg font-bold mb-3">' . esc_html__('Sort By', 'aqualuxe') . '</h4>';
    
    $current_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'menu_order';
    
    $orderby_options = [
        'menu_order' => __('Default sorting', 'aqualuxe'),
        'popularity' => __('Sort by popularity', 'aqualuxe'),
        'rating' => __('Sort by average rating', 'aqualuxe'),
        'date' => __('Sort by latest', 'aqualuxe'),
        'price' => __('Sort by price: low to high', 'aqualuxe'),
        'price-desc' => __('Sort by price: high to low', 'aqualuxe'),
    ];
    
    echo '<select name="orderby" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">';
    
    foreach ($orderby_options as $id => $name) {
        echo '<option value="' . esc_attr($id) . '" ' . selected($current_orderby, $id, false) . '>' . esc_html($name) . '</option>';
    }
    
    echo '</select>';
    echo '</div>';
    
    echo '<div class="filter-actions mt-6">';
    echo '<button type="submit" class="btn btn-primary w-full">' . esc_html__('Apply Filters', 'aqualuxe') . '</button>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
}

/**
 * Display WooCommerce quick view modal
 *
 * @return void
 */
function aqualuxe_quick_view_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div id="quick-view-modal" class="quick-view-modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">';
    echo '<div class="quick-view-content bg-white dark:bg-dark-card rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">';
    echo '<div class="quick-view-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">';
    echo '<h3 class="text-xl font-bold">' . esc_html__('Quick View', 'aqualuxe') . '</h3>';
    echo '<button id="quick-view-close" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
    echo '</svg>';
    echo '</button>';
    echo '</div>';
    echo '<div id="quick-view-content" class="p-6"></div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display WooCommerce cart count
 *
 * @return void
 */
function aqualuxe_cart_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $cart_count = aqualuxe_get_cart_count();
    
    echo '<a href="' . esc_url(aqualuxe_get_cart_url()) . '" class="cart-link relative ml-4">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />';
    echo '</svg>';
    
    if ($cart_count > 0) {
        echo '<span class="cart-count absolute -top-2 -right-2 bg-primary text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">' . esc_html($cart_count) . '</span>';
    }
    
    echo '</a>';
}

/**
 * Display WooCommerce wishlist count
 *
 * @return void
 */
function aqualuxe_wishlist_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $wishlist_count = aqualuxe_get_wishlist_count();
    
    echo '<a href="' . esc_url(home_url('/wishlist/')) . '" class="wishlist-link relative ml-4">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    echo '</svg>';
    
    if ($wishlist_count > 0) {
        echo '<span class="wishlist-count absolute -top-2 -right-2 bg-primary text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full">' . esc_html($wishlist_count) . '</span>';
    }
    
    echo '</a>';
}

/**
 * Display WooCommerce account link
 *
 * @return void
 */
function aqualuxe_account_link() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<a href="' . esc_url(aqualuxe_get_account_url()) . '" class="account-link ml-4">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
    echo '</svg>';
    echo '</a>';
}

/**
 * Display WooCommerce mini cart
 *
 * @return void
 */
function aqualuxe_mini_cart() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    echo '<div class="mini-cart absolute top-full right-0 mt-2 w-80 bg-white dark:bg-dark-card rounded-lg shadow-lg z-50 hidden" x-show="miniCartOpen" @click.away="miniCartOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">';
    
    echo '<div class="mini-cart-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">';
    echo '<h3 class="text-lg font-bold">' . esc_html__('Your Cart', 'aqualuxe') . '</h3>';
    echo '<button @click="miniCartOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
    echo '</svg>';
    echo '</button>';
    echo '</div>';
    
    echo '<div class="mini-cart-content p-4">';
    
    if (WC()->cart->is_empty()) {
        echo '<p class="text-center py-8">' . esc_html__('Your cart is empty.', 'aqualuxe') . '</p>';
    } else {
        echo '<ul class="mini-cart-items divide-y divide-gray-200 dark:divide-gray-700">';
        
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
            
            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                
                echo '<li class="mini-cart-item py-4 flex">';
                
                // Product image
                echo '<div class="mini-cart-item-image w-16 h-16 mr-4">';
                
                if (!empty($product_permalink)) {
                    echo '<a href="' . esc_url($product_permalink) . '">';
                }
                
                echo wp_kses_post(apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_gallery_thumbnail'), $cart_item, $cart_item_key));
                
                if (!empty($product_permalink)) {
                    echo '</a>';
                }
                
                echo '</div>';
                
                // Product details
                echo '<div class="mini-cart-item-details flex-1">';
                
                // Product name
                echo '<h4 class="mini-cart-item-title text-sm font-medium mb-1">';
                
                if (!empty($product_permalink)) {
                    echo '<a href="' . esc_url($product_permalink) . '" class="hover:text-primary transition-colors duration-300">';
                }
                
                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                
                if (!empty($product_permalink)) {
                    echo '</a>';
                }
                
                echo '</h4>';
                
                // Product quantity and price
                echo '<div class="mini-cart-item-price text-sm text-gray-600 dark:text-gray-400">';
                echo esc_html($cart_item['quantity']) . ' × ' . wp_kses_post(apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key));
                echo '</div>';
                
                echo '</div>';
                
                // Remove button
                echo '<div class="mini-cart-item-remove">';
                echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                    '<a href="%s" class="remove text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-300" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>',
                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                    esc_attr__('Remove this item', 'aqualuxe'),
                    esc_attr($product_id),
                    esc_attr($_product->get_sku())
                ), $cart_item_key);
                echo '</div>';
                
                echo '</li>';
            }
        }
        
        echo '</ul>';
        
        // Cart subtotal
        echo '<div class="mini-cart-subtotal flex justify-between items-center py-4 border-t border-gray-200 dark:border-gray-700">';
        echo '<span class="font-bold">' . esc_html__('Subtotal:', 'aqualuxe') . '</span>';
        echo '<span class="font-bold text-primary">' . wp_kses_post(WC()->cart->get_cart_subtotal()) . '</span>';
        echo '</div>';
        
        // Cart actions
        echo '<div class="mini-cart-actions grid grid-cols-2 gap-2">';
        echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn btn-outline text-center">' . esc_html__('View Cart', 'aqualuxe') . '</a>';
        echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="btn btn-primary text-center">' . esc_html__('Checkout', 'aqualuxe') . '</a>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
}