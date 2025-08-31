<?php
/**
 * Template functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site header
 */
function aqualuxe_header() {
    get_template_part('templates/header/header');
}

/**
 * Display site footer
 */
function aqualuxe_footer() {
    get_template_part('templates/footer/footer');
}

/**
 * Display hero section
 */
function aqualuxe_hero() {
    if (is_front_page()) {
        get_template_part('templates/sections/hero');
    }
}

/**
 * Display page header
 */
function aqualuxe_page_header() {
    if (!is_front_page()) {
        get_template_part('templates/sections/page-header');
    }
}

/**
 * Display post meta
 */
function aqualuxe_post_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $author = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
    $date = get_the_date('', $post_id);
    $reading_time = aqualuxe_get_reading_time($post_id);
    $comments_count = get_comments_number($post_id);
    
    echo '<div class="post-meta text-sm text-gray-600 mb-4">';
    
    // Author
    echo '<span class="author">By ' . esc_html($author) . '</span>';
    
    // Date
    echo '<span class="separator mx-2">•</span>';
    echo '<time class="date" datetime="' . esc_attr(get_the_date('c', $post_id)) . '">' . esc_html($date) . '</time>';
    
    // Reading time
    if ($reading_time) {
        echo '<span class="separator mx-2">•</span>';
        echo '<span class="reading-time">' . esc_html($reading_time) . '</span>';
    }
    
    // Comments
    if (comments_open($post_id) && $comments_count > 0) {
        echo '<span class="separator mx-2">•</span>';
        echo '<a href="' . esc_url(get_comments_link($post_id)) . '" class="comments-link hover:text-primary">';
        echo sprintf(_n('%d Comment', '%d Comments', $comments_count, 'aqualuxe'), $comments_count);
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Display post categories
 */
function aqualuxe_post_categories($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if ($categories) {
        echo '<div class="post-categories mb-3">';
        foreach ($categories as $category) {
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link inline-block bg-primary text-white px-3 py-1 rounded-full text-xs mr-2 mb-2 hover:bg-primary-dark transition-colors">';
            echo esc_html($category->name);
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display post tags
 */
function aqualuxe_post_tags($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $tags = get_the_tags($post_id);
    
    if ($tags) {
        echo '<div class="post-tags mt-6">';
        echo '<span class="tags-label font-semibold mr-2">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        foreach ($tags as $tag) {
            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-sm mr-1 mb-1 hover:bg-gray-200 transition-colors">';
            echo '#' . esc_html($tag->name);
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Display author bio
 */
function aqualuxe_author_bio($author_id = null) {
    if (!$author_id) {
        $author_id = get_the_author_meta('ID');
    }
    
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_bio = get_the_author_meta('description', $author_id);
    $author_avatar = get_avatar($author_id, 80);
    $author_url = get_author_posts_url($author_id);
    
    if ($author_bio) {
        echo '<div class="author-bio bg-gray-50 p-6 rounded-lg mt-8">';
        echo '<div class="flex items-start space-x-4">';
        echo '<div class="avatar flex-shrink-0">' . $author_avatar . '</div>';
        echo '<div class="content flex-1">';
        echo '<h4 class="author-name text-lg font-semibold mb-2">';
        echo '<a href="' . esc_url($author_url) . '" class="hover:text-primary transition-colors">' . esc_html($author_name) . '</a>';
        echo '</h4>';
        echo '<p class="author-description text-gray-700">' . esc_html($author_bio) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Display related posts
 */
function aqualuxe_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $related_posts = get_posts([
        'category__in' => $categories,
        'post__not_in' => [$post_id],
        'numberposts' => $limit,
        'post_status' => 'publish',
    ]);
    
    if ($related_posts) {
        echo '<div class="related-posts mt-12">';
        echo '<h3 class="text-2xl font-bold mb-6">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
        echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">';
        
        foreach ($related_posts as $related_post) {
            echo '<article class="related-post bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">';
            
            if (has_post_thumbnail($related_post->ID)) {
                echo '<div class="post-thumbnail">';
                echo '<a href="' . esc_url(get_permalink($related_post->ID)) . '">';
                echo get_the_post_thumbnail($related_post->ID, 'medium', ['class' => 'w-full h-48 object-cover']);
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="post-content p-4">';
            echo '<h4 class="post-title font-semibold mb-2">';
            echo '<a href="' . esc_url(get_permalink($related_post->ID)) . '" class="hover:text-primary transition-colors">';
            echo esc_html($related_post->post_title);
            echo '</a>';
            echo '</h4>';
            echo '<p class="post-excerpt text-gray-600 text-sm">';
            echo esc_html(wp_trim_words($related_post->post_content, 15));
            echo '</p>';
            echo '<time class="post-date text-xs text-gray-500 mt-2 block">';
            echo esc_html(get_the_date('', $related_post->ID));
            echo '</time>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display social share buttons
 */
function aqualuxe_social_share($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    $post_excerpt = aqualuxe_get_excerpt($post_id, 20);
    
    $share_links = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
            'label' => 'Facebook',
            'icon' => aqualuxe_get_social_icon('facebook'),
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title),
            'label' => 'Twitter',
            'icon' => aqualuxe_get_social_icon('twitter'),
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url),
            'label' => 'LinkedIn',
            'icon' => aqualuxe_get_social_icon('linkedin'),
        ],
    ];
    
    echo '<div class="social-share mt-6">';
    echo '<h4 class="share-title font-semibold mb-3">' . esc_html__('Share this post:', 'aqualuxe') . '</h4>';
    echo '<div class="share-buttons flex space-x-2">';
    
    foreach ($share_links as $network => $data) {
        echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" class="share-button bg-' . $network . ' text-white p-2 rounded-full hover:opacity-80 transition-opacity" title="' . esc_attr(sprintf(__('Share on %s', 'aqualuxe'), $data['label'])) . '">';
        echo $data['icon'];
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display search form
 */
function aqualuxe_search_form() {
    echo '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
    echo '<div class="search-field-wrapper relative">';
    echo '<input type="search" class="search-field w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-primary" placeholder="' . esc_attr__('Search...', 'aqualuxe') . '" value="' . get_search_query() . '" name="s">';
    echo '<button type="submit" class="search-submit absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-primary">';
    echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>';
    echo '</svg>';
    echo '</button>';
    echo '</div>';
    echo '</form>';
}

/**
 * Display newsletter signup
 */
function aqualuxe_newsletter_signup() {
    echo '<div class="newsletter-signup bg-primary text-white p-6 rounded-lg">';
    echo '<h3 class="newsletter-title text-xl font-bold mb-2">' . esc_html__('Stay Updated', 'aqualuxe') . '</h3>';
    echo '<p class="newsletter-description mb-4">' . esc_html__('Get the latest news and tips delivered to your inbox.', 'aqualuxe') . '</p>';
    
    echo '<form class="newsletter-form" action="#" method="post">';
    echo '<div class="form-group flex">';
    echo '<input type="email" name="email" placeholder="' . esc_attr__('Your email address', 'aqualuxe') . '" class="email-field flex-1 px-4 py-2 rounded-l-lg text-gray-900 focus:outline-none" required>';
    echo '<button type="submit" class="submit-btn bg-secondary text-white px-6 py-2 rounded-r-lg hover:bg-secondary-dark transition-colors">' . esc_html__('Subscribe', 'aqualuxe') . '</button>';
    echo '</div>';
    echo wp_nonce_field('aqualuxe_newsletter', 'newsletter_nonce', true, false);
    echo '</form>';
    
    echo '</div>';
}

/**
 * Display contact info
 */
function aqualuxe_contact_info() {
    $phone = get_theme_mod('aqualuxe_contact_phone', '(555) 123-4567');
    $email = get_theme_mod('aqualuxe_contact_email', 'info@aqualuxe.com');
    $address = get_theme_mod('aqualuxe_contact_address', '123 Aquarium Lane, Ocean City, OC 12345');
    
    echo '<div class="contact-info">';
    
    if ($phone) {
        echo '<div class="contact-item flex items-center mb-3">';
        echo '<div class="icon mr-3 text-primary">';
        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="content">';
        echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '" class="hover:text-primary transition-colors">' . esc_html($phone) . '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    if ($email) {
        echo '<div class="contact-item flex items-center mb-3">';
        echo '<div class="icon mr-3 text-primary">';
        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="content">';
        echo '<a href="mailto:' . esc_attr($email) . '" class="hover:text-primary transition-colors">' . esc_html($email) . '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    if ($address) {
        echo '<div class="contact-item flex items-start mb-3">';
        echo '<div class="icon mr-3 text-primary mt-1">';
        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>';
        echo '</svg>';
        echo '</div>';
        echo '<div class="content">';
        echo '<address class="not-italic text-gray-700">' . esc_html($address) . '</address>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top() {
    echo '<button id="back-to-top" class="back-to-top fixed bottom-6 right-6 bg-primary text-white p-3 rounded-full shadow-lg opacity-0 invisible transition-all duration-300 hover:bg-primary-dark z-50" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '">';
    echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>';
    echo '</svg>';
    echo '</button>';
}

/**
 * Display loading spinner
 */
function aqualuxe_loading_spinner() {
    echo '<div class="loading-spinner flex justify-center items-center py-8" aria-label="' . esc_attr__('Loading', 'aqualuxe') . '">';
    echo '<div class="spinner border-4 border-gray-200 border-t-primary rounded-full w-8 h-8 animate-spin"></div>';
    echo '</div>';
}

/**
 * Display error message
 */
function aqualuxe_error_message($message) {
    echo '<div class="error-message bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4" role="alert">';
    echo '<p>' . esc_html($message) . '</p>';
    echo '</div>';
}

/**
 * Display success message
 */
function aqualuxe_success_message($message) {
    echo '<div class="success-message bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4" role="alert">';
    echo '<p>' . esc_html($message) . '</p>';
    echo '</div>';
}

/**
 * Display info message
 */
function aqualuxe_info_message($message) {
    echo '<div class="info-message bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mb-4" role="alert">';
    echo '<p>' . esc_html($message) . '</p>';
    echo '</div>';
}
