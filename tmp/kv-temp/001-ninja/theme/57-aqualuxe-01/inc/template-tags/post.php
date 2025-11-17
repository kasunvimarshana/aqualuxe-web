<?php
/**
 * Post template tags
 *
 * @package AquaLuxe
 */

if (!function_exists('aqualuxe_post_meta')) :
    /**
     * Display post meta information
     */
    function aqualuxe_post_meta() {
        // Check if post meta is enabled
        if (!get_theme_mod('aqualuxe_show_post_meta', true)) {
            return;
        }

        echo '<div class="entry-meta">';
        
        // Author
        if (get_theme_mod('aqualuxe_show_post_author', true)) {
            echo '<span class="byline">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" /></svg>';
            echo '<span class="author vcard">';
            echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
            echo '</span>';
            echo '</span>';
        }
        
        // Date
        if (get_theme_mod('aqualuxe_show_post_date', true)) {
            echo '<span class="posted-on">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" /></svg>';
            echo '<time class="entry-date published" datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
            echo '</span>';
        }
        
        // Categories
        if (get_theme_mod('aqualuxe_show_post_categories', true) && has_category()) {
            echo '<span class="cat-links">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>';
            the_category(', ');
            echo '</span>';
        }
        
        // Comments
        if (get_theme_mod('aqualuxe_show_post_comments', true) && comments_open()) {
            echo '<span class="comments-link">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902 1.168.188 2.352.327 3.55.414.28.02.521.18.642.413l1.713 3.293a.75.75 0 001.33 0l1.713-3.293a.783.783 0 01.642-.413 41.102 41.102 0 003.55-.414c1.437-.231 2.43-1.49 2.43-2.902V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zM6.75 6a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 2.5a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z" clip-rule="evenodd" /></svg>';
            comments_popup_link(
                esc_html__('Leave a comment', 'aqualuxe'),
                esc_html__('1 Comment', 'aqualuxe'),
                esc_html__('% Comments', 'aqualuxe')
            );
            echo '</span>';
        }
        
        // Reading time
        if (get_theme_mod('aqualuxe_show_reading_time', true)) {
            $reading_time = aqualuxe_get_reading_time();
            echo '<span class="reading-time">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>';
            printf(
                /* translators: %s: Reading time in minutes */
                _n('%s min read', '%s min read', $reading_time, 'aqualuxe'),
                number_format_i18n($reading_time)
            );
            echo '</span>';
        }
        
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_post_tags')) :
    /**
     * Display post tags
     */
    function aqualuxe_post_tags() {
        // Check if post tags are enabled
        if (!get_theme_mod('aqualuxe_show_post_tags', true)) {
            return;
        }

        if (has_tag()) {
            echo '<div class="entry-tags">';
            echo '<span class="tags-title">' . esc_html__('Tags:', 'aqualuxe') . '</span> ';
            the_tags('', ', ', '');
            echo '</div>';
        }
    }
endif;

if (!function_exists('aqualuxe_post_thumbnail')) :
    /**
     * Display post thumbnail with fallback
     *
     * @param string $size Thumbnail size
     */
    function aqualuxe_post_thumbnail($size = 'post-thumbnail') {
        if (post_password_required() || is_attachment()) {
            return;
        }

        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            the_post_thumbnail($size, array(
                'class' => 'img-fluid',
                'alt'   => get_the_title(),
            ));
            echo '</div>';
        } else {
            // Fallback image
            $fallback_image = get_theme_mod('aqualuxe_fallback_thumbnail');
            if ($fallback_image) {
                echo '<div class="post-thumbnail">';
                echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '" class="img-fluid wp-post-image">';
                echo '</div>';
            }
        }
    }
endif;

if (!function_exists('aqualuxe_post_navigation')) :
    /**
     * Display post navigation
     */
    function aqualuxe_post_navigation() {
        // Check if post navigation is enabled
        if (!get_theme_mod('aqualuxe_show_post_navigation', true)) {
            return;
        }

        the_post_navigation(array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        ));
    }
endif;

if (!function_exists('aqualuxe_author_bio')) :
    /**
     * Display author bio
     */
    function aqualuxe_author_bio() {
        // Check if author bio is enabled
        if (!get_theme_mod('aqualuxe_show_author_bio', true)) {
            return;
        }

        if (get_the_author_meta('description')) {
            echo '<div class="author-bio">';
            echo '<div class="author-avatar">';
            echo get_avatar(get_the_author_meta('ID'), 100);
            echo '</div>';
            
            echo '<div class="author-content">';
            echo '<h3 class="author-name">' . esc_html__('About', 'aqualuxe') . ' ' . get_the_author() . '</h3>';
            echo '<div class="author-description">' . wpautop(get_the_author_meta('description')) . '</div>';
            echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="author-link">' . sprintf(esc_html__('View all posts by %s', 'aqualuxe'), get_the_author()) . '</a>';
            echo '</div>';
            
            echo '</div>';
        }
    }
endif;

if (!function_exists('aqualuxe_related_posts')) :
    /**
     * Display related posts
     */
    function aqualuxe_related_posts() {
        // Check if related posts are enabled
        if (!get_theme_mod('aqualuxe_show_related_posts', true)) {
            return;
        }

        $current_post_id = get_the_ID();
        $categories = get_the_category($current_post_id);
        
        if (empty($categories)) {
            return;
        }
        
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in'        => $category_ids,
            'post__not_in'        => array($current_post_id),
            'posts_per_page'      => 3,
            'ignore_sticky_posts' => 1,
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
                    the_post_thumbnail('aqualuxe-blog-thumbnail', array(
                        'class' => 'img-fluid',
                        'alt'   => get_the_title(),
                    ));
                    echo '</a>';
                }
                
                echo '<div class="related-post-content">';
                echo '<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h4>';
                echo '<div class="related-post-meta">' . get_the_date() . '</div>';
                echo '</div>';
                
                echo '</article>';
            }
            
            echo '</div>';
            echo '</div>';
            
            wp_reset_postdata();
        }
    }
endif;

if (!function_exists('aqualuxe_social_sharing')) :
    /**
     * Display social sharing buttons
     */
    function aqualuxe_social_sharing() {
        // Check if social sharing is enabled
        if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
            return;
        }

        $post_url = urlencode(get_permalink());
        $post_title = urlencode(get_the_title());
        $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full')) : '';

        echo '<div class="social-sharing">';
        echo '<span class="share-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
        
        // Facebook
        if (get_theme_mod('aqualuxe_enable_facebook_sharing', true)) {
            echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" class="share-facebook">';
            echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
            echo '</a>';
        }
        
        // Twitter
        if (get_theme_mod('aqualuxe_enable_twitter_sharing', true)) {
            echo '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-twitter">';
            echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
            echo '</a>';
        }
        
        // LinkedIn
        if (get_theme_mod('aqualuxe_enable_linkedin_sharing', true)) {
            echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-linkedin">';
            echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5"><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
            echo '</a>';
        }
        
        // Pinterest (only if post has thumbnail)
        if (get_theme_mod('aqualuxe_enable_pinterest_sharing', true) && $post_thumbnail) {
            echo '<a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-pinterest">';
            echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5"><path fill="currentColor" d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>';
            echo '</a>';
        }
        
        // Email
        if (get_theme_mod('aqualuxe_enable_email_sharing', true)) {
            echo '<a href="mailto:?subject=' . $post_title . '&body=' . esc_html__('Check out this article:', 'aqualuxe') . ' ' . $post_url . '" class="share-email">';
            echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>';
            echo '</a>';
        }
        
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_post_comments')) :
    /**
     * Display post comments
     */
    function aqualuxe_post_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    }
endif;

if (!function_exists('aqualuxe_excerpt')) :
    /**
     * Display custom excerpt
     *
     * @param int $length Excerpt length
     * @param string $more Read more text
     */
    function aqualuxe_excerpt($length = 55, $more = '...') {
        $excerpt = get_the_excerpt();
        
        if (!$excerpt) {
            $excerpt = get_the_content();
            $excerpt = strip_shortcodes($excerpt);
            $excerpt = excerpt_remove_blocks($excerpt);
            $excerpt = strip_tags($excerpt);
        }
        
        $words = explode(' ', $excerpt, $length + 1);
        
        if (count($words) > $length) {
            array_pop($words);
            $excerpt = implode(' ', $words);
            $excerpt .= $more;
        } else {
            $excerpt = implode(' ', $words);
        }
        
        echo '<div class="entry-excerpt">';
        echo wp_kses_post($excerpt);
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_read_more')) :
    /**
     * Display read more link
     *
     * @param string $text Read more text
     */
    function aqualuxe_read_more($text = null) {
        if (null === $text) {
            $text = esc_html__('Continue reading', 'aqualuxe');
        }
        
        echo '<div class="read-more-link">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="read-more">' . esc_html($text) . ' <span class="screen-reader-text">' . get_the_title() . '</span></a>';
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_post_card')) :
    /**
     * Display post card
     *
     * @param string $layout Card layout
     */
    function aqualuxe_post_card($layout = 'default') {
        $post_classes = array('post-card', 'post-card-' . $layout);
        
        if (has_post_thumbnail()) {
            $post_classes[] = 'has-thumbnail';
        } else {
            $post_classes[] = 'no-thumbnail';
        }
        
        echo '<article id="post-' . get_the_ID() . '" class="' . esc_attr(implode(' ', $post_classes)) . '">';
        
        if ('default' === $layout) {
            // Default layout
            if (has_post_thumbnail()) {
                echo '<div class="post-card-thumbnail">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('aqualuxe-blog-thumbnail', array(
                    'class' => 'img-fluid',
                    'alt'   => get_the_title(),
                ));
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="post-card-content">';
            
            echo '<header class="post-card-header">';
            the_title('<h2 class="post-card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
            aqualuxe_post_meta();
            echo '</header>';
            
            echo '<div class="post-card-excerpt">';
            aqualuxe_excerpt(20);
            echo '</div>';
            
            aqualuxe_read_more();
            
            echo '</div>';
        } elseif ('horizontal' === $layout) {
            // Horizontal layout
            echo '<div class="post-card-inner">';
            
            if (has_post_thumbnail()) {
                echo '<div class="post-card-thumbnail">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('aqualuxe-blog-thumbnail', array(
                    'class' => 'img-fluid',
                    'alt'   => get_the_title(),
                ));
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="post-card-content">';
            
            echo '<header class="post-card-header">';
            the_title('<h2 class="post-card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
            aqualuxe_post_meta();
            echo '</header>';
            
            echo '<div class="post-card-excerpt">';
            aqualuxe_excerpt(20);
            echo '</div>';
            
            aqualuxe_read_more();
            
            echo '</div>';
            echo '</div>';
        } elseif ('overlay' === $layout) {
            // Overlay layout
            echo '<div class="post-card-inner">';
            
            if (has_post_thumbnail()) {
                echo '<div class="post-card-thumbnail">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('aqualuxe-featured', array(
                    'class' => 'img-fluid',
                    'alt'   => get_the_title(),
                ));
                echo '</a>';
                echo '</div>';
            }
            
            echo '<div class="post-card-content">';
            
            echo '<header class="post-card-header">';
            the_title('<h2 class="post-card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
            aqualuxe_post_meta();
            echo '</header>';
            
            aqualuxe_read_more();
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</article>';
    }
endif;

if (!function_exists('aqualuxe_post_featured')) :
    /**
     * Display featured post
     */
    function aqualuxe_post_featured() {
        $post_classes = array('post-featured');
        
        if (has_post_thumbnail()) {
            $post_classes[] = 'has-thumbnail';
        } else {
            $post_classes[] = 'no-thumbnail';
        }
        
        echo '<article id="post-' . get_the_ID() . '" class="' . esc_attr(implode(' ', $post_classes)) . '">';
        
        echo '<div class="post-featured-inner">';
        
        if (has_post_thumbnail()) {
            echo '<div class="post-featured-thumbnail">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            the_post_thumbnail('aqualuxe-featured', array(
                'class' => 'img-fluid',
                'alt'   => get_the_title(),
            ));
            echo '</a>';
            echo '</div>';
        }
        
        echo '<div class="post-featured-content">';
        
        echo '<header class="post-featured-header">';
        
        if (has_category()) {
            echo '<div class="post-featured-categories">';
            the_category(' ');
            echo '</div>';
        }
        
        the_title('<h2 class="post-featured-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>');
        
        aqualuxe_post_meta();
        
        echo '</header>';
        
        echo '<div class="post-featured-excerpt">';
        aqualuxe_excerpt(30);
        echo '</div>';
        
        aqualuxe_read_more();
        
        echo '</div>';
        
        echo '</div>';
        
        echo '</article>';
    }
endif;

if (!function_exists('aqualuxe_posts_pagination')) :
    /**
     * Display posts pagination
     */
    function aqualuxe_posts_pagination() {
        $pagination_type = get_theme_mod('aqualuxe_pagination_type', 'numbered');

        if ($pagination_type === 'numbered') {
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>',
            ));
        } else {
            the_posts_navigation(array(
                'prev_text' => '<span class="nav-previous-text">' . esc_html__('Older posts', 'aqualuxe') . '</span>',
                'next_text' => '<span class="nav-next-text">' . esc_html__('Newer posts', 'aqualuxe') . '</span>',
            ));
        }
    }
endif;

if (!function_exists('aqualuxe_post_views')) :
    /**
     * Display post views
     */
    function aqualuxe_post_views() {
        // Check if post views are enabled
        if (!get_theme_mod('aqualuxe_show_post_views', true)) {
            return;
        }

        $post_views = aqualuxe_get_post_views();
        
        echo '<span class="post-views">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" /><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>';
        printf(
            /* translators: %s: Number of post views */
            _n('%s view', '%s views', $post_views, 'aqualuxe'),
            number_format_i18n($post_views)
        );
        echo '</span>';
    }
endif;

if (!function_exists('aqualuxe_update_post_views')) :
    /**
     * Update post views
     */
    function aqualuxe_update_post_views() {
        if (is_single() && !is_bot()) {
            aqualuxe_set_post_views();
        }
    }
    add_action('wp_head', 'aqualuxe_update_post_views');
endif;

if (!function_exists('aqualuxe_is_bot')) :
    /**
     * Check if current visitor is a bot
     *
     * @return bool
     */
    function aqualuxe_is_bot() {
        $bot_agents = array(
            'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'facebook', 'google', 'yahoo', 'bing', 'baidu',
        );
        
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
            
            foreach ($bot_agents as $bot_agent) {
                if (strpos($user_agent, $bot_agent) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }
endif;