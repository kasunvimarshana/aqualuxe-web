<?php
/**
 * AquaLuxe Template Functions
 * 
 * Template-specific functions and template parts.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get header template part
 */
function aqualuxe_get_header($name = null) {
    $templates = [];
    $name = (string) $name;
    
    if ('' !== $name) {
        $templates[] = "header-{$name}.php";
    }
    
    $templates[] = 'header.php';
    
    locate_template($templates, true);
}

/**
 * Get footer template part
 */
function aqualuxe_get_footer($name = null) {
    $templates = [];
    $name = (string) $name;
    
    if ('' !== $name) {
        $templates[] = "footer-{$name}.php";
    }
    
    $templates[] = 'footer.php';
    
    locate_template($templates, true);
}

/**
 * Get sidebar template part
 */
function aqualuxe_get_sidebar($name = null) {
    $templates = [];
    $name = (string) $name;
    
    if ('' !== $name) {
        $templates[] = "sidebar-{$name}.php";
    }
    
    $templates[] = 'sidebar.php';
    
    locate_template($templates, true);
}

/**
 * Get template part with custom path
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    $templates = [];
    $name = (string) $name;
    
    if ('' !== $name) {
        $templates[] = "{$slug}-{$name}.php";
    }
    
    $templates[] = "{$slug}.php";
    
    // Look in theme root and templates directory
    $template_paths = [
        '',
        'templates/',
        'templates/parts/',
        'templates/components/'
    ];
    
    $located = '';
    foreach ($template_paths as $path) {
        foreach ($templates as $template) {
            $file_path = AQUALUXE_PATH . '/' . $path . $template;
            if (file_exists($file_path)) {
                $located = $file_path;
                break 2;
            }
        }
    }
    
    if ($located) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        include $located;
    }
}

/**
 * Display site logo
 */
function aqualuxe_site_logo() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_width = get_theme_mod('aqualuxe_logo_width', 200);
    
    echo '<div class="site-logo">';
    
    if ($custom_logo_id) {
        $logo_image = wp_get_attachment_image($custom_logo_id, 'full', false, [
            'class' => 'custom-logo',
            'style' => 'max-width: ' . esc_attr($logo_width) . 'px;'
        ]);
        
        if (is_front_page() && is_home()) {
            echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . $logo_image . '</a></h1>';
        } else {
            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . $logo_image . '</a>';
        }
    } else {
        // Fallback to text logo
        $site_name = get_bloginfo('name');
        if (is_front_page() && is_home()) {
            echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html($site_name) . '</a></h1>';
        } else {
            echo '<div class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html($site_name) . '</a></div>';
        }
        
        $tagline = get_bloginfo('description');
        if ($tagline) {
            echo '<p class="site-description">' . esc_html($tagline) . '</p>';
        }
    }
    
    echo '</div>';
}

/**
 * Display main navigation
 */
function aqualuxe_main_navigation() {
    if (has_nav_menu('primary')) {
        echo '<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="' . esc_attr__('Primary menu', 'aqualuxe') . '">';
        echo '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">';
        echo '<span class="sr-only">' . esc_html__('Toggle menu', 'aqualuxe') . '</span>';
        echo '<span class="menu-icon"></span>';
        echo '</button>';
        
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'menu_class' => 'nav-menu',
            'container' => false,
            'depth' => 3,
            'walker' => new AquaLuxe_Walker_Nav_Menu()
        ]);
        
        echo '</nav>';
    }
}

/**
 * Display hero section
 */
function aqualuxe_hero_section() {
    if (!is_front_page()) {
        return;
    }
    
    $hero_title = get_theme_mod('aqualuxe_hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'));
    $hero_subtitle = get_theme_mod('aqualuxe_hero_subtitle', __('Premium ornamental fish, aquatic plants, and luxury aquarium solutions for enthusiasts worldwide.', 'aqualuxe'));
    $hero_button_text = get_theme_mod('aqualuxe_hero_button_text', __('Explore Collection', 'aqualuxe'));
    $hero_button_url = get_theme_mod('aqualuxe_hero_button_url', '/shop');
    $hero_image = get_theme_mod('aqualuxe_hero_image');
    
    echo '<section class="hero-section" role="banner">';
    
    if ($hero_image) {
        echo '<div class="hero-background" style="background-image: url(' . esc_url($hero_image) . ');"></div>';
    }
    
    echo '<div class="hero-content">';
    echo '<div class="container">';
    
    if ($hero_title) {
        echo '<h1 class="hero-title">' . esc_html($hero_title) . '</h1>';
    }
    
    if ($hero_subtitle) {
        echo '<p class="hero-subtitle">' . esc_html($hero_subtitle) . '</p>';
    }
    
    if ($hero_button_text && $hero_button_url) {
        echo '<div class="hero-actions">';
        echo '<a href="' . esc_url($hero_button_url) . '" class="btn btn-primary btn-lg">' . esc_html($hero_button_text) . '</a>';
        echo '</div>';
    }
    
    echo '</div>'; // container
    echo '</div>'; // hero-content
    echo '</section>';
}

/**
 * Display page header
 */
function aqualuxe_page_header() {
    if (is_front_page()) {
        return;
    }
    
    echo '<div class="page-header">';
    echo '<div class="container">';
    
    // Breadcrumbs
    aqualuxe_breadcrumbs();
    
    // Page title
    echo '<div class="page-title-wrapper">';
    
    if (is_singular()) {
        echo '<h1 class="page-title">' . get_the_title() . '</h1>';
    } elseif (is_category()) {
        echo '<h1 class="page-title">' . single_cat_title('', false) . '</h1>';
        echo '<div class="archive-description">' . category_description() . '</div>';
    } elseif (is_tag()) {
        echo '<h1 class="page-title">' . single_tag_title('', false) . '</h1>';
        echo '<div class="archive-description">' . tag_description() . '</div>';
    } elseif (is_author()) {
        echo '<h1 class="page-title">' . sprintf(__('Author: %s', 'aqualuxe'), get_the_author()) . '</h1>';
        echo '<div class="archive-description">' . get_the_author_meta('description') . '</div>';
    } elseif (is_date()) {
        echo '<h1 class="page-title">' . get_the_archive_title() . '</h1>';
    } elseif (is_search()) {
        echo '<h1 class="page-title">' . sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</h1>';
    } elseif (is_404()) {
        echo '<h1 class="page-title">' . __('Page Not Found', 'aqualuxe') . '</h1>';
    } elseif (aqualuxe_is_woocommerce_page()) {
        if (is_shop()) {
            echo '<h1 class="page-title">' . woocommerce_page_title(false) . '</h1>';
        } elseif (is_product_category()) {
            echo '<h1 class="page-title">' . single_cat_title('', false) . '</h1>';
            echo '<div class="archive-description">' . term_description() . '</div>';
        } elseif (is_product_tag()) {
            echo '<h1 class="page-title">' . single_tag_title('', false) . '</h1>';
            echo '<div class="archive-description">' . term_description() . '</div>';
        }
    } else {
        echo '<h1 class="page-title">' . get_the_archive_title() . '</h1>';
        echo '<div class="archive-description">' . get_the_archive_description() . '</div>';
    }
    
    echo '</div>'; // page-title-wrapper
    echo '</div>'; // container
    echo '</div>'; // page-header
}

/**
 * Display post meta
 */
function aqualuxe_post_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    echo '<div class="post-meta">';
    
    // Author
    echo '<span class="post-author">';
    echo '<i class="fas fa-user" aria-hidden="true"></i>';
    echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . get_the_author() . '</a>';
    echo '</span>';
    
    // Date
    echo '<span class="post-date">';
    echo '<i class="fas fa-calendar" aria-hidden="true"></i>';
    echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . get_the_date() . '</time>';
    echo '</span>';
    
    // Categories
    $categories = get_the_category();
    if ($categories) {
        echo '<span class="post-categories">';
        echo '<i class="fas fa-folder" aria-hidden="true"></i>';
        foreach ($categories as $key => $category) {
            if ($key > 0) echo ', ';
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }
        echo '</span>';
    }
    
    // Reading time
    $reading_time = aqualuxe_reading_time($post_id);
    if ($reading_time) {
        echo '<span class="reading-time">';
        echo '<i class="fas fa-clock" aria-hidden="true"></i>';
        echo esc_html($reading_time);
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    if (!is_singular('post')) {
        return;
    }
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    echo '<nav class="post-navigation" role="navigation">';
    echo '<h2 class="sr-only">' . esc_html__('Post navigation', 'aqualuxe') . '</h2>';
    echo '<div class="nav-links">';
    
    if ($prev_post) {
        echo '<div class="nav-previous">';
        echo '<a href="' . esc_url(get_permalink($prev_post->ID)) . '" rel="prev">';
        echo '<span class="nav-subtitle">' . esc_html__('Previous Post', 'aqualuxe') . '</span>';
        echo '<span class="nav-title">' . esc_html(get_the_title($prev_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    if ($next_post) {
        echo '<div class="nav-next">';
        echo '<a href="' . esc_url(get_permalink($next_post->ID)) . '" rel="next">';
        echo '<span class="nav-subtitle">' . esc_html__('Next Post', 'aqualuxe') . '</span>';
        echo '<span class="nav-title">' . esc_html(get_the_title($next_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</nav>';
}

/**
 * Display related posts
 */
function aqualuxe_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return;
    }
    
    $category_ids = wp_list_pluck($categories, 'term_id');
    
    $related_posts = get_posts([
        'category__in' => $category_ids,
        'post__not_in' => [$post_id],
        'posts_per_page' => $limit,
        'orderby' => 'rand'
    ]);
    
    if (empty($related_posts)) {
        return;
    }
    
    echo '<section class="related-posts">';
    echo '<h3 class="section-title">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
    echo '<div class="related-posts-grid">';
    
    foreach ($related_posts as $related_post) {
        setup_postdata($related_post);
        echo '<article class="related-post">';
        
        if (has_post_thumbnail($related_post->ID)) {
            echo '<div class="related-post-thumbnail">';
            echo '<a href="' . esc_url(get_permalink($related_post->ID)) . '">';
            echo get_the_post_thumbnail($related_post->ID, 'aqualuxe-blog-thumb');
            echo '</a>';
            echo '</div>';
        }
        
        echo '<div class="related-post-content">';
        echo '<h4 class="related-post-title">';
        echo '<a href="' . esc_url(get_permalink($related_post->ID)) . '">' . esc_html(get_the_title($related_post->ID)) . '</a>';
        echo '</h4>';
        echo '<div class="related-post-excerpt">' . esc_html(aqualuxe_get_excerpt($related_post->ID, 15)) . '</div>';
        echo '</div>';
        
        echo '</article>';
    }
    
    wp_reset_postdata();
    
    echo '</div>';
    echo '</section>';
}

/**
 * Display newsletter signup
 */
function aqualuxe_newsletter_signup() {
    echo '<section class="newsletter-signup">';
    echo '<div class="container">';
    echo '<div class="newsletter-content">';
    
    echo '<h3 class="newsletter-title">' . esc_html__('Stay Updated', 'aqualuxe') . '</h3>';
    echo '<p class="newsletter-description">' . esc_html__('Subscribe to our newsletter for the latest aquatic news and exclusive offers.', 'aqualuxe') . '</p>';
    
    echo '<form class="newsletter-form" action="#" method="post">';
    echo '<div class="form-group">';
    echo '<label for="newsletter-email" class="sr-only">' . esc_html__('Email Address', 'aqualuxe') . '</label>';
    echo '<input type="email" id="newsletter-email" name="email" placeholder="' . esc_attr__('Enter your email', 'aqualuxe') . '" required>';
    echo '<button type="submit" class="btn btn-secondary">' . esc_html__('Subscribe', 'aqualuxe') . '</button>';
    echo '</div>';
    echo wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce', true, false);
    echo '</form>';
    
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

/**
 * Display copyright text
 */
function aqualuxe_copyright() {
    $copyright_text = get_theme_mod('aqualuxe_copyright_text', '© ' . date('Y') . ' AquaLuxe. All rights reserved.');
    echo '<div class="copyright-text">' . wp_kses_post($copyright_text) . '</div>';
}

/**
 * Custom Walker for Navigation Menu
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) .'"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target ) .'"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) .'"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
