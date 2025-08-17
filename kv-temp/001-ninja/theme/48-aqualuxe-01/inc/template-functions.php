<?php
/**
 * Template functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Display site logo
 *
 * @param string $class Additional CSS classes
 */
function aqualuxe_site_logo($class = '') {
    $logo_id = get_theme_mod('custom_logo');
    $logo_class = 'site-logo';
    
    if ($class) {
        $logo_class .= ' ' . $class;
    }
    
    if ($logo_id) {
        $logo_attr = [
            'class' => $logo_class,
            'itemprop' => 'logo',
        ];
        
        // Check if we're in dark mode and if there's a dark logo
        if (aqualuxe_is_dark_mode()) {
            $dark_logo_id = aqualuxe_get_option('dark_logo');
            if ($dark_logo_id) {
                $logo_id = $dark_logo_id;
            }
        }
        
        echo wp_get_attachment_image($logo_id, 'full', false, $logo_attr);
    } else {
        echo '<span class="' . esc_attr($logo_class) . ' site-title">' . esc_html(get_bloginfo('name')) . '</span>';
    }
}

/**
 * Display primary navigation
 */
function aqualuxe_primary_navigation() {
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => 'nav',
            'container_class' => 'primary-navigation',
            'menu_class' => 'primary-menu',
            'menu_id' => 'primary-menu',
            'fallback_cb' => false,
            'depth' => 3,
        ]);
    }
}

/**
 * Display footer navigation
 */
function aqualuxe_footer_navigation() {
    if (has_nav_menu('footer')) {
        wp_nav_menu([
            'theme_location' => 'footer',
            'container' => 'nav',
            'container_class' => 'footer-navigation',
            'menu_class' => 'footer-menu',
            'menu_id' => 'footer-menu',
            'fallback_cb' => false,
            'depth' => 1,
        ]);
    }
}

/**
 * Display social navigation
 */
function aqualuxe_social_navigation() {
    if (has_nav_menu('social')) {
        wp_nav_menu([
            'theme_location' => 'social',
            'container' => 'nav',
            'container_class' => 'social-navigation',
            'menu_class' => 'social-menu',
            'menu_id' => 'social-menu',
            'fallback_cb' => false,
            'depth' => 1,
            'link_before' => '<span class="screen-reader-text">',
            'link_after' => '</span>',
        ]);
    }
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if (!aqualuxe_get_option('enable_breadcrumbs', true)) {
        return;
    }
    
    // Check if we're on a WooCommerce page and WooCommerce is active
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        woocommerce_breadcrumb();
        return;
    }
    
    $home_text = __('Home', 'aqualuxe');
    $separator = '<span class="breadcrumb-separator">/</span>';
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<ol class="breadcrumb-list" itemscope itemtype="http://schema.org/BreadcrumbList">';
    
    // Home link
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    echo '<a href="' . esc_url(home_url('/')) . '" itemprop="item"><span itemprop="name">' . esc_html($home_text) . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    echo $separator;
    
    if (is_category() || is_single()) {
        if (is_single()) {
            // If post has categories
            $categories = get_the_category();
            if ($categories) {
                $cat = $categories[0];
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" itemprop="item"><span itemprop="name">' . esc_html($cat->name) . '</span></a>';
                echo '<meta itemprop="position" content="2" />';
                echo '</li>';
                echo $separator;
            }
            
            // Post title
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
            echo '<meta itemprop="position" content="3" />';
            echo '</li>';
        } elseif (is_category()) {
            // Category name
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html(single_cat_title('', false)) . '</span>';
            echo '<meta itemprop="position" content="2" />';
            echo '</li>';
        }
    } elseif (is_page()) {
        // Check if the page has a parent
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            $position = 2;
            
            foreach ($ancestors as $ancestor) {
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_permalink($ancestor)) . '" itemprop="item"><span itemprop="name">' . esc_html(get_the_title($ancestor)) . '</span></a>';
                echo '<meta itemprop="position" content="' . esc_attr($position) . '" />';
                echo '</li>';
                echo $separator;
                $position++;
            }
        }
        
        // Current page
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
        echo '<meta itemprop="position" content="' . esc_attr(isset($position) ? $position : 2) . '" />';
        echo '</li>';
    } elseif (is_tag()) {
        // Tag name
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(single_tag_title('', false)) . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_author()) {
        // Author name
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_author()) . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_year()) {
        // Year archive
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_time('Y')) . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_month()) {
        // Month archive
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '" itemprop="item"><span itemprop="name">' . esc_html(get_the_time('Y')) . '</span></a>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
        echo $separator;
        
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_time('F')) . '</span>';
        echo '<meta itemprop="position" content="3" />';
        echo '</li>';
    } elseif (is_day()) {
        // Day archive
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '" itemprop="item"><span itemprop="name">' . esc_html(get_the_time('Y')) . '</span></a>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
        echo $separator;
        
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '" itemprop="item"><span itemprop="name">' . esc_html(get_the_time('F')) . '</span></a>';
        echo '<meta itemprop="position" content="3" />';
        echo '</li>';
        echo $separator;
        
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_time('d')) . '</span>';
        echo '<meta itemprop="position" content="4" />';
        echo '</li>';
    } elseif (is_search()) {
        // Search results
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('Search results for', 'aqualuxe') . ' "' . esc_html(get_search_query()) . '"</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_404()) {
        // 404 page
        echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('Page not found', 'aqualuxe') . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Display page title
 */
function aqualuxe_page_title() {
    if (is_front_page()) {
        return;
    }
    
    echo '<header class="page-header">';
    
    if (is_home()) {
        echo '<h1 class="page-title">' . esc_html__('Blog', 'aqualuxe') . '</h1>';
    } elseif (is_search()) {
        echo '<h1 class="page-title">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>') . '</h1>';
    } elseif (is_archive()) {
        if (is_category()) {
            echo '<h1 class="page-title">' . single_cat_title('', false) . '</h1>';
            if (category_description()) {
                echo '<div class="archive-description">' . category_description() . '</div>';
            }
        } elseif (is_tag()) {
            echo '<h1 class="page-title">' . single_tag_title('', false) . '</h1>';
            if (tag_description()) {
                echo '<div class="archive-description">' . tag_description() . '</div>';
            }
        } elseif (is_author()) {
            echo '<h1 class="page-title">' . get_the_author() . '</h1>';
        } elseif (is_day()) {
            echo '<h1 class="page-title">' . sprintf(esc_html__('Daily Archives: %s', 'aqualuxe'), get_the_date()) . '</h1>';
        } elseif (is_month()) {
            echo '<h1 class="page-title">' . sprintf(esc_html__('Monthly Archives: %s', 'aqualuxe'), get_the_date('F Y')) . '</h1>';
        } elseif (is_year()) {
            echo '<h1 class="page-title">' . sprintf(esc_html__('Yearly Archives: %s', 'aqualuxe'), get_the_date('Y')) . '</h1>';
        } elseif (is_tax()) {
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            echo '<h1 class="page-title">' . $term->name . '</h1>';
            if (term_description()) {
                echo '<div class="archive-description">' . term_description() . '</div>';
            }
        } else {
            echo '<h1 class="page-title">' . esc_html__('Archives', 'aqualuxe') . '</h1>';
        }
    } elseif (is_404()) {
        echo '<h1 class="page-title">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';
    } else {
        echo '<h1 class="page-title">' . get_the_title() . '</h1>';
    }
    
    echo '</header>';
}

/**
 * Display post meta
 *
 * @param int $post_id Post ID
 */
function aqualuxe_post_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    echo '<div class="post-meta">';
    
    // Author
    echo '<span class="post-author">';
    echo '<i class="fas fa-user"></i> ';
    echo esc_html__('By', 'aqualuxe') . ' ';
    echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author_meta('display_name')) . '</a>';
    echo '</span>';
    
    // Date
    echo '<span class="post-date">';
    echo '<i class="fas fa-calendar"></i> ';
    echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
    echo '</span>';
    
    // Categories
    $categories = get_the_category();
    if ($categories) {
        echo '<span class="post-categories">';
        echo '<i class="fas fa-folder"></i> ';
        $cat_links = array();
        foreach ($categories as $category) {
            $cat_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }
        echo implode(', ', $cat_links);
        echo '</span>';
    }
    
    // Comments
    if (comments_open($post_id)) {
        echo '<span class="post-comments">';
        echo '<i class="fas fa-comments"></i> ';
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
 * @param int $post_id Post ID
 */
function aqualuxe_post_tags($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $tags = get_the_tags($post_id);
    if ($tags) {
        echo '<div class="post-tags">';
        echo '<span class="tags-title">' . esc_html__('Tags:', 'aqualuxe') . '</span> ';
        
        $tag_links = array();
        foreach ($tags as $tag) {
            $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
        }
        
        echo implode(', ', $tag_links);
        echo '</div>';
    }
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    
    echo '<nav class="post-navigation" aria-label="' . esc_attr__('Post Navigation', 'aqualuxe') . '">';
    echo '<div class="post-navigation-inner">';
    
    if ($prev_post) {
        echo '<div class="post-navigation-prev">';
        echo '<span class="post-navigation-label">' . esc_html__('Previous Post', 'aqualuxe') . '</span>';
        echo '<a href="' . esc_url(get_permalink($prev_post->ID)) . '" rel="prev">';
        echo '<span class="post-navigation-title">' . esc_html(get_the_title($prev_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    if ($next_post) {
        echo '<div class="post-navigation-next">';
        echo '<span class="post-navigation-label">' . esc_html__('Next Post', 'aqualuxe') . '</span>';
        echo '<a href="' . esc_url(get_permalink($next_post->ID)) . '" rel="next">';
        echo '<span class="post-navigation-title">' . esc_html(get_the_title($next_post->ID)) . '</span>';
        echo '</a>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '</nav>';
}

/**
 * Display related posts
 *
 * @param int $post_id Post ID
 * @param int $count Number of posts to display
 */
function aqualuxe_related_posts($post_id = null, $count = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Get current post categories
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    // Query related posts
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post__not_in' => array($post_id),
        'category__in' => $category_ids,
        'orderby' => 'rand',
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) {
        echo '<div class="related-posts">';
        echo '<h3 class="related-posts-title">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
        echo '<div class="related-posts-grid">';
        
        while ($related_query->have_posts()) {
            $related_query->the_post();
            
            echo '<article class="related-post">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '" class="related-post-thumbnail">';
                the_post_thumbnail('aqualuxe-blog');
                echo '</a>';
            }
            
            echo '<div class="related-post-content">';
            echo '<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></h4>';
            echo '<div class="related-post-meta">';
            echo '<span class="related-post-date">' . esc_html(get_the_date()) . '</span>';
            echo '</div>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display pagination
 *
 * @param object $query WP_Query object
 */
function aqualuxe_pagination($query = null) {
    if (!$query) {
        global $wp_query;
        $query = $wp_query;
    }
    
    $big = 999999999; // Need an unlikely integer
    
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $query->max_num_pages,
        'type' => 'array',
        'prev_text' => '<i class="fas fa-angle-left"></i> ' . esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe') . ' <i class="fas fa-angle-right"></i>',
    ));
    
    if (is_array($pages)) {
        echo '<nav class="pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
        echo '<ul class="pagination-list">';
        
        foreach ($pages as $page) {
            echo '<li class="pagination-item">' . $page . '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Display social sharing buttons
 *
 * @param int $post_id Post ID
 */
function aqualuxe_social_sharing($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check if social sharing is enabled
    if (!aqualuxe_get_option('enable_social_sharing', true)) {
        return;
    }
    
    $post_url = urlencode(get_permalink($post_id));
    $post_title = urlencode(get_the_title($post_id));
    
    // Get enabled social networks
    $networks = array();
    
    if (aqualuxe_get_option('share_facebook', true)) {
        $networks['facebook'] = array(
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
            'icon' => 'fab fa-facebook-f',
            'label' => __('Share on Facebook', 'aqualuxe'),
        );
    }
    
    if (aqualuxe_get_option('share_twitter', true)) {
        $networks['twitter'] = array(
            'url' => 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
            'icon' => 'fab fa-twitter',
            'label' => __('Share on Twitter', 'aqualuxe'),
        );
    }
    
    if (aqualuxe_get_option('share_linkedin', true)) {
        $networks['linkedin'] = array(
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title,
            'icon' => 'fab fa-linkedin-in',
            'label' => __('Share on LinkedIn', 'aqualuxe'),
        );
    }
    
    if (aqualuxe_get_option('share_pinterest', true) && has_post_thumbnail($post_id)) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $networks['pinterest'] = array(
            'url' => 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . urlencode($image[0]) . '&description=' . $post_title,
            'icon' => 'fab fa-pinterest-p',
            'label' => __('Pin on Pinterest', 'aqualuxe'),
        );
    }
    
    if (aqualuxe_get_option('share_email', true)) {
        $networks['email'] = array(
            'url' => 'mailto:?subject=' . $post_title . '&body=' . $post_url,
            'icon' => 'fas fa-envelope',
            'label' => __('Share via Email', 'aqualuxe'),
        );
    }
    
    if (!empty($networks)) {
        echo '<div class="social-sharing">';
        echo '<span class="social-sharing-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
        echo '<ul class="social-sharing-list">';
        
        foreach ($networks as $network => $data) {
            echo '<li class="social-sharing-item social-sharing-' . esc_attr($network) . '">';
            echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($data['label']) . '">';
            echo '<i class="' . esc_attr($data['icon']) . '"></i>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Display author box
 *
 * @param int $post_id Post ID
 */
function aqualuxe_author_box($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check if author box is enabled
    if (!aqualuxe_get_option('enable_author_box', true)) {
        return;
    }
    
    $author_id = get_post_field('post_author', $post_id);
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_posts_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    
    echo '<div class="author-box">';
    echo '<div class="author-avatar">';
    echo get_avatar($author_id, 100);
    echo '</div>';
    
    echo '<div class="author-info">';
    echo '<h4 class="author-name">' . esc_html($author_name) . '</h4>';
    echo '<div class="author-description">' . wpautop(esc_html($author_description)) . '</div>';
    echo '<a href="' . esc_url($author_posts_url) . '" class="author-link">' . sprintf(esc_html__('View all posts by %s', 'aqualuxe'), $author_name) . '</a>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display newsletter form
 */
function aqualuxe_newsletter_form() {
    // Check if newsletter form is enabled
    if (!aqualuxe_get_option('enable_newsletter', true)) {
        return;
    }
    
    $form_action = aqualuxe_get_option('newsletter_form_action', '#');
    $form_title = aqualuxe_get_option('newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $form_description = aqualuxe_get_option('newsletter_description', __('Stay updated with our latest news and offers.', 'aqualuxe'));
    $form_button_text = aqualuxe_get_option('newsletter_button_text', __('Subscribe', 'aqualuxe'));
    
    echo '<div class="newsletter-form">';
    echo '<div class="newsletter-form-inner">';
    
    if ($form_title) {
        echo '<h3 class="newsletter-title">' . esc_html($form_title) . '</h3>';
    }
    
    if ($form_description) {
        echo '<div class="newsletter-description">' . esc_html($form_description) . '</div>';
    }
    
    echo '<form action="' . esc_url($form_action) . '" method="post" class="newsletter-form-fields">';
    echo '<div class="newsletter-form-field">';
    echo '<input type="email" name="email" placeholder="' . esc_attr__('Your Email Address', 'aqualuxe') . '" required>';
    echo '</div>';
    echo '<div class="newsletter-form-submit">';
    echo '<button type="submit" class="newsletter-submit-button">' . esc_html($form_button_text) . '</button>';
    echo '</div>';
    echo '</form>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display contact information
 */
function aqualuxe_contact_info() {
    $address = aqualuxe_get_option('contact_address', '');
    $phone = aqualuxe_get_option('contact_phone', '');
    $email = aqualuxe_get_option('contact_email', '');
    $hours = aqualuxe_get_option('contact_hours', '');
    
    if (!$address && !$phone && !$email && !$hours) {
        return;
    }
    
    echo '<div class="contact-info">';
    
    if ($address) {
        echo '<div class="contact-info-item contact-address">';
        echo '<i class="fas fa-map-marker-alt"></i>';
        echo '<span>' . esc_html($address) . '</span>';
        echo '</div>';
    }
    
    if ($phone) {
        echo '<div class="contact-info-item contact-phone">';
        echo '<i class="fas fa-phone"></i>';
        echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '">' . esc_html($phone) . '</a>';
        echo '</div>';
    }
    
    if ($email) {
        echo '<div class="contact-info-item contact-email">';
        echo '<i class="fas fa-envelope"></i>';
        echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        echo '</div>';
    }
    
    if ($hours) {
        echo '<div class="contact-info-item contact-hours">';
        echo '<i class="fas fa-clock"></i>';
        echo '<span>' . esc_html($hours) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display social links
 */
function aqualuxe_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (empty($social_links)) {
        return;
    }
    
    echo '<div class="social-links">';
    echo '<ul class="social-links-list">';
    
    foreach ($social_links as $network => $url) {
        echo '<li class="social-links-item social-links-' . esc_attr($network) . '">';
        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">';
        echo '<i class="fab fa-' . esc_attr($network) . '"></i>';
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}

/**
 * Display dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode is enabled
    if (!aqualuxe_get_option('enable_dark_mode', true)) {
        return;
    }
    
    $is_dark = aqualuxe_is_dark_mode();
    $toggle_class = $is_dark ? 'dark-mode-active' : '';
    
    echo '<button id="dark-mode-toggle" class="dark-mode-toggle ' . esc_attr($toggle_class) . '" aria-label="' . esc_attr__('Toggle Dark Mode', 'aqualuxe') . '">';
    echo '<span class="dark-mode-toggle-icon">';
    echo '<i class="fas fa-sun light-icon"></i>';
    echo '<i class="fas fa-moon dark-icon"></i>';
    echo '</span>';
    echo '</button>';
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        $languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        
        if (!empty($languages)) {
            echo '<div class="language-switcher">';
            echo '<div class="language-switcher-current">';
            echo '<span class="language-switcher-flag">' . wp_kses_post($languages[ICL_LANGUAGE_CODE]['country_flag_url']) . '</span>';
            echo '<span class="language-switcher-name">' . esc_html($languages[ICL_LANGUAGE_CODE]['native_name']) . '</span>';
            echo '<i class="fas fa-chevron-down"></i>';
            echo '</div>';
            
            echo '<ul class="language-switcher-dropdown">';
            foreach ($languages as $language) {
                $active_class = $language['active'] ? 'active' : '';
                echo '<li class="language-switcher-item ' . esc_attr($active_class) . '">';
                echo '<a href="' . esc_url($language['url']) . '">';
                echo '<span class="language-switcher-flag">' . wp_kses_post($language['country_flag_url']) . '</span>';
                echo '<span class="language-switcher-name">' . esc_html($language['native_name']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    } elseif (function_exists('pll_the_languages')) {
        // Check if Polylang is active
        $args = array(
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 1,
            'hide_if_empty' => 0,
        );
        
        echo '<div class="language-switcher">';
        echo pll_the_languages($args);
        echo '</div>';
    }
}

/**
 * Display currency switcher
 */
function aqualuxe_currency_switcher() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if WOOCS (WooCommerce Currency Switcher) is active
    if (class_exists('WOOCS')) {
        global $WOOCS;
        $currencies = $WOOCS->get_currencies();
        $current_currency = $WOOCS->current_currency;
        
        if (!empty($currencies)) {
            echo '<div class="currency-switcher">';
            echo '<div class="currency-switcher-current">';
            echo '<span class="currency-switcher-code">' . esc_html($current_currency) . '</span>';
            echo '<i class="fas fa-chevron-down"></i>';
            echo '</div>';
            
            echo '<ul class="currency-switcher-dropdown">';
            foreach ($currencies as $currency) {
                $active_class = $currency['name'] === $current_currency ? 'active' : '';
                echo '<li class="currency-switcher-item ' . esc_attr($active_class) . '">';
                echo '<a href="' . esc_url(add_query_arg('currency', $currency['name'])) . '">';
                echo '<span class="currency-switcher-code">' . esc_html($currency['name']) . '</span>';
                echo '<span class="currency-switcher-symbol">' . esc_html($currency['symbol']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    } elseif (class_exists('WCML_Multi_Currency')) {
        // Check if WCML (WooCommerce Multilingual) is active
        global $woocommerce_wpml;
        
        if (isset($woocommerce_wpml->multi_currency)) {
            $currencies = $woocommerce_wpml->multi_currency->get_currencies();
            $current_currency = $woocommerce_wpml->multi_currency->get_client_currency();
            
            if (!empty($currencies)) {
                echo '<div class="currency-switcher">';
                echo '<div class="currency-switcher-current">';
                echo '<span class="currency-switcher-code">' . esc_html($current_currency) . '</span>';
                echo '<i class="fas fa-chevron-down"></i>';
                echo '</div>';
                
                echo '<ul class="currency-switcher-dropdown">';
                foreach ($currencies as $code => $currency) {
                    $active_class = $code === $current_currency ? 'active' : '';
                    echo '<li class="currency-switcher-item ' . esc_attr($active_class) . '">';
                    echo '<a href="' . esc_url(add_query_arg('currency', $code)) . '">';
                    echo '<span class="currency-switcher-code">' . esc_html($code) . '</span>';
                    echo '<span class="currency-switcher-symbol">' . esc_html($currency['symbol']) . '</span>';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }
        }
    }
}

/**
 * Display mini cart
 */
function aqualuxe_mini_cart() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();
    
    echo '<div class="mini-cart">';
    echo '<a href="' . esc_url($cart_url) . '" class="mini-cart-link">';
    echo '<i class="fas fa-shopping-cart"></i>';
    echo '<span class="mini-cart-count">' . esc_html($cart_count) . '</span>';
    echo '</a>';
    
    echo '<div class="mini-cart-dropdown">';
    
    if (function_exists('woocommerce_mini_cart')) {
        woocommerce_mini_cart();
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    echo '<div class="search-form-wrapper">';
    echo '<button class="search-toggle" aria-label="' . esc_attr__('Toggle Search', 'aqualuxe') . '">';
    echo '<i class="fas fa-search"></i>';
    echo '</button>';
    
    echo '<div class="search-form-dropdown">';
    get_search_form();
    echo '</div>';
    echo '</div>';
}

/**
 * Display mobile menu toggle
 */
function aqualuxe_mobile_menu_toggle() {
    echo '<button class="mobile-menu-toggle" aria-label="' . esc_attr__('Toggle Menu', 'aqualuxe') . '">';
    echo '<span class="mobile-menu-toggle-bar"></span>';
    echo '<span class="mobile-menu-toggle-bar"></span>';
    echo '<span class="mobile-menu-toggle-bar"></span>';
    echo '</button>';
}

/**
 * Display mobile menu
 */
function aqualuxe_mobile_menu() {
    echo '<div class="mobile-menu-container">';
    echo '<div class="mobile-menu-header">';
    echo '<button class="mobile-menu-close" aria-label="' . esc_attr__('Close Menu', 'aqualuxe') . '">';
    echo '<i class="fas fa-times"></i>';
    echo '</button>';
    echo '</div>';
    
    echo '<div class="mobile-menu-content">';
    
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => 'nav',
            'container_class' => 'mobile-navigation',
            'menu_class' => 'mobile-menu',
            'menu_id' => 'mobile-menu',
            'fallback_cb' => false,
            'depth' => 3,
        ]);
    }
    
    echo '<div class="mobile-menu-extras">';
    aqualuxe_language_switcher();
    aqualuxe_currency_switcher();
    aqualuxe_dark_mode_toggle();
    aqualuxe_social_links();
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top() {
    // Check if back to top button is enabled
    if (!aqualuxe_get_option('enable_back_to_top', true)) {
        return;
    }
    
    echo '<button id="back-to-top" class="back-to-top" aria-label="' . esc_attr__('Back to Top', 'aqualuxe') . '">';
    echo '<i class="fas fa-chevron-up"></i>';
    echo '</button>';
}

/**
 * Display copyright text
 */
function aqualuxe_copyright() {
    $copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));
    
    echo '<div class="copyright">';
    echo wp_kses_post($copyright_text);
    echo '</div>';
}

/**
 * Display page loader
 */
function aqualuxe_page_loader() {
    // Check if page loader is enabled
    if (!aqualuxe_get_option('enable_page_loader', true)) {
        return;
    }
    
    echo '<div id="page-loader" class="page-loader">';
    echo '<div class="page-loader-spinner"></div>';
    echo '</div>';
}

/**
 * Display schema.org structured data
 */
function aqualuxe_structured_data() {
    // Organization schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
    );
    
    // Logo
    $logo_id = get_theme_mod('custom_logo');
    if ($logo_id) {
        $logo_image = wp_get_attachment_image_src($logo_id, 'full');
        if ($logo_image) {
            $schema['logo'] = $logo_image[0];
        }
    }
    
    // Contact info
    $contact_info = array();
    
    $phone = aqualuxe_get_option('contact_phone', '');
    if ($phone) {
        $contact_info['telephone'] = $phone;
    }
    
    $email = aqualuxe_get_option('contact_email', '');
    if ($email) {
        $contact_info['email'] = $email;
    }
    
    $address = aqualuxe_get_option('contact_address', '');
    if ($address) {
        $contact_info['address'] = array(
            '@type' => 'PostalAddress',
            'streetAddress' => $address,
        );
    }
    
    if (!empty($contact_info)) {
        $schema['contactPoint'] = array(
            '@type' => 'ContactPoint',
            'contactType' => 'customer service',
        ) + $contact_info;
    }
    
    // Social profiles
    $social_links = aqualuxe_get_social_links();
    if (!empty($social_links)) {
        $schema['sameAs'] = array_values($social_links);
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    
    // Additional schema for specific pages
    if (is_singular('post')) {
        aqualuxe_article_schema();
    } elseif (is_singular('aqualuxe_service')) {
        aqualuxe_service_schema();
    } elseif (is_singular('aqualuxe_event')) {
        aqualuxe_event_schema();
    } elseif (aqualuxe_is_woocommerce_active() && is_product()) {
        aqualuxe_product_schema();
    }
}

/**
 * Display article schema.org structured data
 */
function aqualuxe_article_schema() {
    global $post;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
        'headline' => get_the_title(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author(),
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
            ),
        ),
    );
    
    // Featured image
    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = array(
                '@type' => 'ImageObject',
                'url' => $image[0],
                'width' => $image[1],
                'height' => $image[2],
            );
        }
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}

/**
 * Display service schema.org structured data
 */
function aqualuxe_service_schema() {
    global $post;
    
    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => get_the_title(),
        'description' => get_the_excerpt(),
        'provider' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
        ),
        'serviceType' => get_the_title(),
    );
    
    // Price
    if ($price) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $price,
            'priceCurrency' => aqualuxe_get_currency(),
        );
    }
    
    // Featured image
    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = $image[0];
        }
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}

/**
 * Display event schema.org structured data
 */
function aqualuxe_event_schema() {
    global $post;
    
    $start_date = get_post_meta($post->ID, '_aqualuxe_event_start_date', true);
    $end_date = get_post_meta($post->ID, '_aqualuxe_event_end_date', true);
    $location = get_post_meta($post->ID, '_aqualuxe_event_location', true);
    $price = get_post_meta($post->ID, '_aqualuxe_event_price', true);
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Event',
        'name' => get_the_title(),
        'description' => get_the_excerpt(),
        'organizer' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
        ),
    );
    
    // Dates
    if ($start_date) {
        $schema['startDate'] = $start_date;
    }
    
    if ($end_date) {
        $schema['endDate'] = $end_date;
    }
    
    // Location
    if ($location) {
        $schema['location'] = array(
            '@type' => 'Place',
            'name' => $location,
            'address' => $location,
        );
    }
    
    // Price
    if ($price) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $price,
            'priceCurrency' => aqualuxe_get_currency(),
        );
    }
    
    // Featured image
    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = $image[0];
        }
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}

/**
 * Display product schema.org structured data
 */
function aqualuxe_product_schema() {
    global $product;
    
    if (!is_a($product, 'WC_Product')) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => $product->get_short_description(),
        'sku' => $product->get_sku(),
        'brand' => array(
            '@type' => 'Brand',
            'name' => get_bloginfo('name'),
        ),
    );
    
    // Price
    if ($product->get_price()) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
        );
    }
    
    // Rating
    if ($product->get_rating_count() > 0) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }
    
    // Featured image
    if (has_post_thumbnail()) {
        $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        if ($image) {
            $schema['image'] = $image[0];
        }
    }
    
    // Output schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}

/**
 * Display Open Graph meta tags
 */
function aqualuxe_open_graph_meta() {
    // Basic Open Graph meta
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />';
    echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />';
    
    if (is_singular()) {
        global $post;
        
        echo '<meta property="og:type" content="article" />';
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />';
        echo '<meta property="og:description" content="' . esc_attr(get_the_excerpt()) . '" />';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />';
        
        // Featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                echo '<meta property="og:image" content="' . esc_url($image[0]) . '" />';
                echo '<meta property="og:image:width" content="' . esc_attr($image[1]) . '" />';
                echo '<meta property="og:image:height" content="' . esc_attr($image[2]) . '" />';
            }
        }
        
        // Article meta
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />';
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />';
        
        // Author
        echo '<meta property="article:author" content="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" />';
        
        // Categories and tags
        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />';
            }
        }
        
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />';
            }
        }
    } elseif (is_front_page() || is_home()) {
        echo '<meta property="og:type" content="website" />';
        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '" />';
        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '" />';
        echo '<meta property="og:url" content="' . esc_url(home_url('/')) . '" />';
        
        // Site logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                echo '<meta property="og:image" content="' . esc_url($logo_image[0]) . '" />';
                echo '<meta property="og:image:width" content="' . esc_attr($logo_image[1]) . '" />';
                echo '<meta property="og:image:height" content="' . esc_attr($logo_image[2]) . '" />';
            }
        }
    } elseif (is_archive()) {
        echo '<meta property="og:type" content="website" />';
        
        if (is_category()) {
            echo '<meta property="og:title" content="' . esc_attr(single_cat_title('', false)) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(category_description()) . '" />';
            echo '<meta property="og:url" content="' . esc_url(get_category_link(get_query_var('cat'))) . '" />';
        } elseif (is_tag()) {
            echo '<meta property="og:title" content="' . esc_attr(single_tag_title('', false)) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(tag_description()) . '" />';
            echo '<meta property="og:url" content="' . esc_url(get_tag_link(get_query_var('tag_id'))) . '" />';
        } elseif (is_author()) {
            echo '<meta property="og:title" content="' . esc_attr(get_the_author()) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(get_the_author_meta('description')) . '" />';
            echo '<meta property="og:url" content="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" />';
        } else {
            echo '<meta property="og:title" content="' . esc_attr(get_the_archive_title()) . '" />';
            echo '<meta property="og:description" content="' . esc_attr(get_the_archive_description()) . '" />';
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />';
        }
    }
}

/**
 * Display Twitter Card meta tags
 */
function aqualuxe_twitter_card_meta() {
    // Twitter username
    $twitter_username = aqualuxe_get_option('twitter_username', '');
    
    if ($twitter_username) {
        echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />';
    }
    
    // Default card type
    echo '<meta name="twitter:card" content="summary_large_image" />';
    
    if (is_singular()) {
        global $post;
        
        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title()) . '" />';
        echo '<meta name="twitter:description" content="' . esc_attr(get_the_excerpt()) . '" />';
        
        // Featured image
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                echo '<meta name="twitter:image" content="' . esc_url($image[0]) . '" />';
            }
        }
        
        // Author Twitter handle
        $author_twitter = get_the_author_meta('twitter');
        if ($author_twitter) {
            echo '<meta name="twitter:creator" content="@' . esc_attr($author_twitter) . '" />';
        }
    } elseif (is_front_page() || is_home()) {
        echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '" />';
        echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '" />';
        
        // Site logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                echo '<meta name="twitter:image" content="' . esc_url($logo_image[0]) . '" />';
            }
        }
    }
}