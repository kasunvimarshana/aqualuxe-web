<?php
/**
 * Template tags for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site logo
 *
 * @param array $args Logo arguments
 */
function aqualuxe_site_logo($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'site-logo',
        'logo_class' => '',
        'show_title' => false,
        'show_tagline' => false,
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get logo
    $logo = '';
    $has_logo = false;
    
    // Custom logo
    if (has_custom_logo()) {
        $logo = get_custom_logo();
        $has_logo = true;
    }
    
    // Dark mode logo
    $dark_logo_id = aqualuxe_get_option('dark_logo');
    
    if ($dark_logo_id) {
        $dark_logo_url = wp_get_attachment_image_url($dark_logo_id, 'full');
        $dark_logo_alt = aqualuxe_get_image_alt($dark_logo_id);
        
        if ($dark_logo_url) {
            $dark_logo = '<img src="' . esc_url($dark_logo_url) . '" alt="' . esc_attr($dark_logo_alt) . '" class="custom-logo dark-logo">';
            
            if ($has_logo) {
                // Add dark logo class to container
                $args['container_class'] .= ' has-dark-logo';
                
                // Add dark logo
                $logo = str_replace('</a>', $dark_logo . '</a>', $logo);
            } else {
                $logo = '<a href="' . esc_url(home_url('/')) . '" rel="home" class="custom-logo-link dark-logo-link">' . $dark_logo . '</a>';
                $has_logo = true;
            }
        }
    }
    
    // Site title and tagline
    if ($args['show_title'] || $args['show_tagline'] || !$has_logo) {
        $title = '';
        
        if ($args['show_title'] || !$has_logo) {
            $title_class = $has_logo ? 'site-title screen-reader-text' : 'site-title';
            $title = '<h1 class="' . esc_attr($title_class) . '"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a></h1>';
        }
        
        $tagline = '';
        
        if ($args['show_tagline']) {
            $tagline = '<p class="site-description">' . esc_html(get_bloginfo('description')) . '</p>';
        }
        
        if ($title || $tagline) {
            $logo .= '<div class="site-branding">' . $title . $tagline . '</div>';
        }
    }
    
    // Build output
    $output = '';
    
    if ($logo) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= $logo;
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display primary navigation
 *
 * @param array $args Navigation arguments
 */
function aqualuxe_primary_navigation($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'primary-navigation',
        'menu_class' => 'menu',
        'menu_id' => 'primary-menu',
        'fallback_cb' => 'aqualuxe_primary_menu_fallback',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get menu
    $menu = wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => $args['menu_class'],
        'menu_id' => $args['menu_id'],
        'fallback_cb' => $args['fallback_cb'],
        'echo' => false,
    ]);
    
    // Build output
    $output = '';
    
    if ($menu) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= $menu;
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    if (current_user_can('edit_theme_options')) {
        echo '<ul class="menu">';
        echo '<li><a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Add a menu', 'aqualuxe') . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Display mobile navigation
 *
 * @param array $args Navigation arguments
 */
function aqualuxe_mobile_navigation($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'mobile-navigation',
        'menu_class' => 'menu',
        'menu_id' => 'mobile-menu',
        'fallback_cb' => 'aqualuxe_mobile_menu_fallback',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get menu
    $menu = wp_nav_menu([
        'theme_location' => 'mobile',
        'container' => false,
        'menu_class' => $args['menu_class'],
        'menu_id' => $args['menu_id'],
        'fallback_cb' => $args['fallback_cb'],
        'echo' => false,
    ]);
    
    // Build output
    $output = '';
    
    if ($menu) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= $menu;
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Mobile menu fallback
 */
function aqualuxe_mobile_menu_fallback() {
    aqualuxe_primary_menu_fallback();
}

/**
 * Display footer navigation
 *
 * @param array $args Navigation arguments
 */
function aqualuxe_footer_navigation($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'footer-navigation',
        'menu_class' => 'menu',
        'menu_id' => 'footer-menu',
        'fallback_cb' => 'aqualuxe_footer_menu_fallback',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get menu
    $menu = wp_nav_menu([
        'theme_location' => 'footer',
        'container' => false,
        'menu_class' => $args['menu_class'],
        'menu_id' => $args['menu_id'],
        'fallback_cb' => $args['fallback_cb'],
        'echo' => false,
    ]);
    
    // Build output
    $output = '';
    
    if ($menu) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= $menu;
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Footer menu fallback
 */
function aqualuxe_footer_menu_fallback() {
    // No fallback for footer menu
}

/**
 * Display breadcrumbs
 *
 * @param array $args Breadcrumbs arguments
 */
function aqualuxe_breadcrumbs($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'breadcrumbs',
        'separator' => '/',
        'before' => '',
        'after' => '',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get breadcrumbs
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if (empty($breadcrumbs)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    if ($breadcrumbs) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . ' aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
        $output .= $args['before'];
        
        $count = count($breadcrumbs);
        $i = 1;
        
        foreach ($breadcrumbs as $breadcrumb) {
            if ($i === $count) {
                $output .= '<span class="breadcrumb-current" aria-current="page">' . esc_html($breadcrumb['title']) . '</span>';
            } else {
                $output .= '<a href="' . esc_url($breadcrumb['url']) . '" class="breadcrumb-link">' . esc_html($breadcrumb['title']) . '</a>';
                $output .= '<span class="breadcrumb-separator" aria-hidden="true">' . esc_html($args['separator']) . '</span>';
            }
            
            $i++;
        }
        
        $output .= $args['after'];
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display pagination
 *
 * @param array $args Pagination arguments
 */
function aqualuxe_pagination($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'pagination',
        'prev_text' => __('Previous', 'aqualuxe'),
        'next_text' => __('Next', 'aqualuxe'),
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get pagination
    $pagination = aqualuxe_get_pagination([
        'prev_text' => $args['prev_text'],
        'next_text' => $args['next_text'],
    ]);
    
    if (empty($pagination)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    if ($pagination) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . ' aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
        $output .= '<ul class="pagination-list">';
        
        foreach ($pagination as $item) {
            $class = 'pagination-item';
            
            if ($item['current']) {
                $class .= ' pagination-item-current';
            }
            
            if ($item['prev']) {
                $class .= ' pagination-item-prev';
            }
            
            if ($item['next']) {
                $class .= ' pagination-item-next';
            }
            
            if ($item['dots']) {
                $class .= ' pagination-item-dots';
            }
            
            $output .= '<li class="' . esc_attr($class) . '">';
            
            if ($item['url']) {
                $output .= '<a href="' . esc_url($item['url']) . '" class="pagination-link">' . esc_html($item['text']) . '</a>';
            } else {
                $output .= '<span class="pagination-text">' . esc_html($item['text']) . '</span>';
            }
            
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display post thumbnail
 *
 * @param array $args Thumbnail arguments
 */
function aqualuxe_post_thumbnail($args = []) {
    // Set defaults
    $defaults = [
        'size' => 'post-thumbnail',
        'class' => 'post-thumbnail',
        'link' => true,
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if post has thumbnail
    if (!has_post_thumbnail()) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $class = $args['class'] ? ' class="' . esc_attr($args['class']) . '"' : '';
    
    $output = '<div' . $class . '>';
    
    if ($args['link']) {
        $output .= '<a href="' . esc_url(get_permalink()) . '" aria-hidden="true" tabindex="-1">';
    }
    
    $output .= get_the_post_thumbnail(null, $args['size']);
    
    if ($args['link']) {
        $output .= '</a>';
    }
    
    $output .= '</div>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display post meta
 *
 * @param array $args Meta arguments
 */
function aqualuxe_post_meta($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'post-meta',
        'date' => true,
        'author' => true,
        'categories' => true,
        'tags' => false,
        'comments' => true,
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    $meta_items = [];
    
    // Date
    if ($args['date']) {
        $meta_items[] = '<span class="post-date"><time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time></span>';
    }
    
    // Author
    if ($args['author']) {
        $meta_items[] = '<span class="post-author">' . esc_html__('By', 'aqualuxe') . ' <a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';
    }
    
    // Categories
    if ($args['categories'] && has_category()) {
        $meta_items[] = '<span class="post-categories">' . get_the_category_list(', ') . '</span>';
    }
    
    // Tags
    if ($args['tags'] && has_tag()) {
        $meta_items[] = '<span class="post-tags">' . get_the_tag_list('', ', ') . '</span>';
    }
    
    // Comments
    if ($args['comments'] && comments_open()) {
        $meta_items[] = '<span class="post-comments"><a href="' . esc_url(get_comments_link()) . '">' . esc_html(get_comments_number_text(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe'))) . '</a></span>';
    }
    
    if (!empty($meta_items)) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= implode(' <span class="meta-separator">|</span> ', $meta_items);
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display post excerpt
 *
 * @param array $args Excerpt arguments
 */
function aqualuxe_post_excerpt($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'post-excerpt',
        'length' => 55,
        'more' => '&hellip;',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get excerpt
    $excerpt = aqualuxe_get_excerpt($args['length']);
    
    if (!$excerpt) {
        return '';
    }
    
    // Build output
    $output = '';
    
    if ($excerpt) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        $output .= $excerpt;
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display read more link
 *
 * @param array $args Read more arguments
 */
function aqualuxe_read_more($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'read-more',
        'text' => __('Read More', 'aqualuxe'),
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= '<a href="' . esc_url(get_permalink()) . '" class="read-more-link">' . esc_html($args['text']) . '</a>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display post navigation
 *
 * @param array $args Navigation arguments
 */
function aqualuxe_post_navigation($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'nav',
        'container_class' => 'post-navigation',
        'prev_text' => __('Previous Post', 'aqualuxe'),
        'next_text' => __('Next Post', 'aqualuxe'),
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . ' aria-label="' . esc_attr__('Post Navigation', 'aqualuxe') . '">';
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ($prev_post || $next_post) {
        $output .= '<div class="post-navigation-links">';
        
        if ($prev_post) {
            $output .= '<div class="post-navigation-prev">';
            $output .= '<span class="post-navigation-label">' . esc_html($args['prev_text']) . '</span>';
            $output .= '<a href="' . esc_url(get_permalink($prev_post)) . '" class="post-navigation-link">' . esc_html(get_the_title($prev_post)) . '</a>';
            $output .= '</div>';
        }
        
        if ($next_post) {
            $output .= '<div class="post-navigation-next">';
            $output .= '<span class="post-navigation-label">' . esc_html($args['next_text']) . '</span>';
            $output .= '<a href="' . esc_url(get_permalink($next_post)) . '" class="post-navigation-link">' . esc_html(get_the_title($next_post)) . '</a>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
    }
    
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display related posts
 *
 * @param array $args Related posts arguments
 */
function aqualuxe_related_posts($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'related-posts',
        'title' => __('Related Posts', 'aqualuxe'),
        'count' => 3,
        'taxonomy' => 'category',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get related posts
    $related_posts = aqualuxe_get_related_posts(null, $args['count'], $args['taxonomy']);
    
    if (empty($related_posts)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    if ($related_posts) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        
        if ($args['title']) {
            $output .= '<h3 class="related-posts-title">' . esc_html($args['title']) . '</h3>';
        }
        
        $output .= '<div class="related-posts-grid">';
        
        foreach ($related_posts as $related_post) {
            $output .= '<div class="related-post">';
            
            if (has_post_thumbnail($related_post->ID)) {
                $output .= '<a href="' . esc_url(get_permalink($related_post->ID)) . '" class="related-post-thumbnail">';
                $output .= get_the_post_thumbnail($related_post->ID, 'thumbnail');
                $output .= '</a>';
            }
            
            $output .= '<h4 class="related-post-title"><a href="' . esc_url(get_permalink($related_post->ID)) . '">' . esc_html(get_the_title($related_post->ID)) . '</a></h4>';
            $output .= '<div class="related-post-meta">' . esc_html(get_the_date('', $related_post->ID)) . '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display social share links
 *
 * @param array $args Social share arguments
 */
function aqualuxe_social_share($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'social-share',
        'title' => __('Share This', 'aqualuxe'),
        'networks' => ['facebook', 'twitter', 'linkedin', 'pinterest', 'email'],
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get social share links
    $links = aqualuxe_get_social_share_links();
    
    if (empty($links)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    if ($links) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        
        if ($args['title']) {
            $output .= '<span class="social-share-title">' . esc_html($args['title']) . '</span>';
        }
        
        $output .= '<ul class="social-share-list">';
        
        foreach ($args['networks'] as $network) {
            if (isset($links[$network])) {
                $output .= '<li class="social-share-item social-share-' . esc_attr($network) . '">';
                $output .= '<a href="' . esc_url($links[$network]['url']) . '" class="social-share-link" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($links[$network]['label']) . '">';
                
                if (function_exists('aqualuxe_get_icon')) {
                    $output .= aqualuxe_get_icon($links[$network]['icon'], ['title' => $links[$network]['label']]);
                } else {
                    $output .= esc_html($network);
                }
                
                $output .= '</a>';
                $output .= '</li>';
            }
        }
        
        $output .= '</ul>';
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display comments
 *
 * @param array $args Comments arguments
 */
function aqualuxe_comments($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'comments-area',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if comments are open or we have at least one comment
    if (!comments_open() && get_comments_number() === 0) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . ' id="comments">';
    
    ob_start();
    comments_template();
    $output .= ob_get_clean();
    
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display comment form
 *
 * @param array $args Comment form arguments
 */
function aqualuxe_comment_form($args = []) {
    // Set defaults
    $defaults = [
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if comments are open
    if (!comments_open()) {
        return '';
    }
    
    // Build output
    $output = '';
    
    // Get comment form fields
    $fields = aqualuxe_get_comment_form_fields();
    
    // Get comment form defaults
    $form_args = aqualuxe_get_comment_form_defaults();
    
    // Merge fields
    $form_args['fields'] = $fields;
    
    ob_start();
    comment_form($form_args);
    $output = ob_get_clean();
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display color mode toggle
 *
 * @param array $args Color mode toggle arguments
 */
function aqualuxe_color_mode_toggle($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'color-mode-toggle',
        'light_text' => __('Light', 'aqualuxe'),
        'dark_text' => __('Dark', 'aqualuxe'),
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= '<button type="button" class="color-mode-button" aria-label="' . esc_attr__('Toggle color mode', 'aqualuxe') . '">';
    $output .= '<span class="color-mode-light">' . esc_html($args['light_text']) . '</span>';
    $output .= '<span class="color-mode-dark">' . esc_html($args['dark_text']) . '</span>';
    $output .= '</button>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display language switcher
 *
 * @param array $args Language switcher arguments
 */
function aqualuxe_language_switcher($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'language-switcher',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    // WPML support
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (!empty($languages)) {
            $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
            
            $output = '<' . esc_html($args['container']) . $container_class . '>';
            $output .= '<ul class="language-switcher-list">';
            
            foreach ($languages as $language) {
                $class = $language['active'] ? ' class="language-switcher-item active"' : ' class="language-switcher-item"';
                
                $output .= '<li' . $class . '>';
                $output .= '<a href="' . esc_url($language['url']) . '" lang="' . esc_attr($language['language_code']) . '">';
                
                if ($language['country_flag_url']) {
                    $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                }
                
                $output .= esc_html($language['native_name']);
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</' . esc_html($args['container']) . '>';
        }
    }
    
    // Polylang support
    elseif (function_exists('pll_the_languages')) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        
        ob_start();
        pll_the_languages([
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
        ]);
        $output .= ob_get_clean();
        
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display currency switcher
 *
 * @param array $args Currency switcher arguments
 */
function aqualuxe_currency_switcher($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'currency-switcher',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    // Build output
    $output = '';
    
    // WooCommerce Currency Switcher support
    if (function_exists('woocs_auto_switcher')) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        
        ob_start();
        woocs_auto_switcher();
        $output .= ob_get_clean();
        
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    // WPML WooCommerce Multilingual support
    elseif (function_exists('wcml_multi_currency_is_enabled') && wcml_multi_currency_is_enabled()) {
        $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
        
        $output = '<' . esc_html($args['container']) . $container_class . '>';
        
        ob_start();
        do_action('wcml_currency_switcher', [
            'format' => '%code%',
        ]);
        $output .= ob_get_clean();
        
        $output .= '</' . esc_html($args['container']) . '>';
    }
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display search form
 *
 * @param array $args Search form arguments
 */
function aqualuxe_search_form($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'search-form-container',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= get_search_form(false);
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display site footer
 *
 * @param array $args Footer arguments
 */
function aqualuxe_site_footer($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'site-footer-container',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    
    // Footer widgets
    if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) {
        $output .= '<div class="footer-widgets">';
        $output .= '<div class="footer-widgets-inner">';
        
        for ($i = 1; $i <= 4; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                $output .= '<div class="footer-widget footer-widget-' . esc_attr($i) . '">';
                
                ob_start();
                dynamic_sidebar('footer-' . $i);
                $output .= ob_get_clean();
                
                $output .= '</div>';
            }
        }
        
        $output .= '</div>';
        $output .= '</div>';
    }
    
    // Footer navigation
    $output .= aqualuxe_footer_navigation(['echo' => false]);
    
    // Footer info
    $output .= '<div class="footer-info">';
    $output .= '<div class="footer-copyright">';
    $output .= sprintf(
        /* translators: %1$s: Current year, %2$s: Site name */
        esc_html__('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
        date('Y'),
        get_bloginfo('name')
    );
    $output .= '</div>';
    $output .= '</div>';
    
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display page header
 *
 * @param array $args Page header arguments
 */
function aqualuxe_page_header($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'page-header',
        'show_title' => true,
        'show_breadcrumbs' => true,
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    
    // Title
    if ($args['show_title']) {
        $title = '';
        
        if (is_home()) {
            $title = get_the_title(get_option('page_for_posts'));
        } elseif (is_archive()) {
            $title = get_the_archive_title();
        } elseif (is_search()) {
            $title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
        } elseif (is_404()) {
            $title = __('Page Not Found', 'aqualuxe');
        } elseif (is_singular()) {
            $title = get_the_title();
        }
        
        if ($title) {
            $output .= '<h1 class="page-title">' . esc_html($title) . '</h1>';
        }
    }
    
    // Breadcrumbs
    if ($args['show_breadcrumbs']) {
        $output .= aqualuxe_breadcrumbs(['echo' => false]);
    }
    
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display hero section
 *
 * @param array $args Hero arguments
 */
function aqualuxe_hero_section($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'hero-section',
        'title' => '',
        'subtitle' => '',
        'content' => '',
        'image' => '',
        'button_text' => '',
        'button_url' => '',
        'button_class' => 'button',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    
    // Image
    if ($args['image']) {
        $output .= '<div class="hero-image">';
        $output .= '<img src="' . esc_url($args['image']) . '" alt="' . esc_attr($args['title']) . '">';
        $output .= '</div>';
    }
    
    $output .= '<div class="hero-content">';
    
    // Title
    if ($args['title']) {
        $output .= '<h2 class="hero-title">' . esc_html($args['title']) . '</h2>';
    }
    
    // Subtitle
    if ($args['subtitle']) {
        $output .= '<p class="hero-subtitle">' . esc_html($args['subtitle']) . '</p>';
    }
    
    // Content
    if ($args['content']) {
        $output .= '<div class="hero-text">' . wp_kses_post($args['content']) . '</div>';
    }
    
    // Button
    if ($args['button_text'] && $args['button_url']) {
        $output .= '<div class="hero-button">';
        $output .= '<a href="' . esc_url($args['button_url']) . '" class="' . esc_attr($args['button_class']) . '">' . esc_html($args['button_text']) . '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display call to action section
 *
 * @param array $args CTA arguments
 */
function aqualuxe_cta_section($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'cta-section',
        'title' => '',
        'content' => '',
        'button_text' => '',
        'button_url' => '',
        'button_class' => 'button',
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= '<div class="cta-content">';
    
    // Title
    if ($args['title']) {
        $output .= '<h2 class="cta-title">' . esc_html($args['title']) . '</h2>';
    }
    
    // Content
    if ($args['content']) {
        $output .= '<div class="cta-text">' . wp_kses_post($args['content']) . '</div>';
    }
    
    // Button
    if ($args['button_text'] && $args['button_url']) {
        $output .= '<div class="cta-button">';
        $output .= '<a href="' . esc_url($args['button_url']) . '" class="' . esc_attr($args['button_class']) . '">' . esc_html($args['button_text']) . '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display testimonials section
 *
 * @param array $args Testimonials arguments
 */
function aqualuxe_testimonials_section($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'testimonials-section',
        'title' => '',
        'testimonials' => [],
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if testimonials exist
    if (empty($args['testimonials'])) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    
    // Title
    if ($args['title']) {
        $output .= '<h2 class="testimonials-title">' . esc_html($args['title']) . '</h2>';
    }
    
    $output .= '<div class="testimonials-grid">';
    
    foreach ($args['testimonials'] as $testimonial) {
        $output .= '<div class="testimonial">';
        $output .= '<div class="testimonial-content">';
        $output .= '<blockquote class="testimonial-quote">' . wp_kses_post($testimonial['content']) . '</blockquote>';
        $output .= '</div>';
        
        $output .= '<div class="testimonial-meta">';
        
        if (isset($testimonial['image']) && $testimonial['image']) {
            $output .= '<div class="testimonial-image">';
            $output .= '<img src="' . esc_url($testimonial['image']) . '" alt="' . esc_attr($testimonial['name']) . '">';
            $output .= '</div>';
        }
        
        $output .= '<div class="testimonial-info">';
        $output .= '<div class="testimonial-name">' . esc_html($testimonial['name']) . '</div>';
        
        if (isset($testimonial['position']) && $testimonial['position']) {
            $output .= '<div class="testimonial-position">' . esc_html($testimonial['position']) . '</div>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}

/**
 * Display features section
 *
 * @param array $args Features arguments
 */
function aqualuxe_features_section($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'features-section',
        'title' => '',
        'subtitle' => '',
        'features' => [],
        'echo' => true,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Check if features exist
    if (empty($args['features'])) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    
    // Title
    if ($args['title']) {
        $output .= '<h2 class="features-title">' . esc_html($args['title']) . '</h2>';
    }
    
    // Subtitle
    if ($args['subtitle']) {
        $output .= '<p class="features-subtitle">' . esc_html($args['subtitle']) . '</p>';
    }
    
    $output .= '<div class="features-grid">';
    
    foreach ($args['features'] as $feature) {
        $output .= '<div class="feature">';
        
        if (isset($feature['icon']) && $feature['icon']) {
            $output .= '<div class="feature-icon">';
            
            if (function_exists('aqualuxe_get_icon')) {
                $output .= aqualuxe_get_icon($feature['icon']);
            } else {
                $output .= '<span class="icon-placeholder"></span>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '<div class="feature-content">';
        $output .= '<h3 class="feature-title">' . esc_html($feature['title']) . '</h3>';
        $output .= '<div class="feature-text">' . wp_kses_post($feature['content']) . '</div>';
        $output .= '</div>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    } else {
        return $output;
    }
}