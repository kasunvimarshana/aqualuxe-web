<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Posted on %s', 'post date', 'aqualuxe'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post(get_the_title())
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function aqualuxe_post_thumbnail() {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }

    if (is_singular()) :
        ?>

        <div class="post-thumbnail">
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-lg w-full h-auto')); ?>
        </div><!-- .post-thumbnail -->

    <?php else : ?>

        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'aqualuxe-blog-thumbnail',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'rounded-lg w-full h-auto transition-transform duration-300 hover:scale-105',
                )
            );
            ?>
        </a>

        <?php
    endif; // End is_singular().
}

/**
 * Prints the site logo.
 */
function aqualuxe_site_logo() {
    $logo_id = get_theme_mod('custom_logo');
    $logo_dark_id = get_theme_mod('aqualuxe_logo_dark');
    
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        $logo_dark_url = $logo_dark_id ? wp_get_attachment_image_url($logo_dark_id, 'full') : $logo_url;
        
        echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">';
        echo '<img src="' . esc_url($logo_url) . '" class="custom-logo light-logo" alt="' . esc_attr(get_bloginfo('name')) . '" />';
        
        if ($logo_dark_id) {
            echo '<img src="' . esc_url($logo_dark_url) . '" class="custom-logo dark-logo" alt="' . esc_attr(get_bloginfo('name')) . '" />';
        }
        
        echo '</a>';
    } else {
        echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a></h1>';
        
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
            echo '<p class="site-description">' . esc_html($description) . '</p>';
        }
    }
}

/**
 * Prints the site search form.
 */
function aqualuxe_search_form() {
    $search_form_style = get_theme_mod('aqualuxe_search_form_style', 'default');
    
    if ($search_form_style === 'default') {
        get_search_form();
    } else {
        get_template_part('template-parts/components/search-form', $search_form_style);
    }
}

/**
 * Prints the social icons.
 */
function aqualuxe_social_icons() {
    $social_links = array(
        'facebook' => get_theme_mod('aqualuxe_social_facebook'),
        'twitter' => get_theme_mod('aqualuxe_social_twitter'),
        'instagram' => get_theme_mod('aqualuxe_social_instagram'),
        'linkedin' => get_theme_mod('aqualuxe_social_linkedin'),
        'youtube' => get_theme_mod('aqualuxe_social_youtube'),
        'pinterest' => get_theme_mod('aqualuxe_social_pinterest'),
    );
    
    // Filter out empty links
    $social_links = array_filter($social_links);
    
    if (empty($social_links)) {
        return;
    }
    
    echo '<div class="social-icons flex space-x-4">';
    
    foreach ($social_links as $network => $link) {
        echo '<a href="' . esc_url($link) . '" class="social-icon ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer">';
        echo '<span class="screen-reader-text">' . esc_html(ucfirst($network)) . '</span>';
        echo '<i class="fab fa-' . esc_attr($network) . '" aria-hidden="true"></i>';
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Prints the post navigation.
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'class' => 'post-navigation flex justify-between my-8',
        )
    );
}

/**
 * Prints the comments navigation.
 */
function aqualuxe_comments_navigation() {
    the_comments_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'class' => 'comments-navigation flex justify-between my-8',
        )
    );
}

/**
 * Prints the comments.
 */
function aqualuxe_comments_template() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Prints the related posts.
 */
function aqualuxe_related_posts() {
    // Check if related posts are enabled
    if (!get_theme_mod('aqualuxe_related_posts_enable', true)) {
        return;
    }
    
    // Get the current post ID
    $post_id = get_the_ID();
    
    // Get the categories of the current post
    $categories = get_the_category($post_id);
    
    if ($categories) {
        $category_ids = array();
        
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in' => $category_ids,
            'post__not_in' => array($post_id),
            'posts_per_page' => 3,
            'ignore_sticky_posts' => 1,
        );
        
        $related_posts = new WP_Query($args);
        
        if ($related_posts->have_posts()) {
            ?>
            <div class="related-posts my-8">
                <h2 class="related-posts-title text-2xl font-bold mb-4"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h2>
                
                <div class="related-posts-grid grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    while ($related_posts->have_posts()) {
                        $related_posts->the_post();
                        ?>
                        <div class="related-post">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="related-post-thumbnail mb-3">
                                        <?php the_post_thumbnail('aqualuxe-blog-thumbnail', array('class' => 'rounded-lg w-full h-auto')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h3 class="related-post-title text-lg font-semibold mb-2"><?php the_title(); ?></h3>
                                
                                <div class="related-post-meta text-sm text-gray-500">
                                    <?php echo esc_html(get_the_date()); ?>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        }
    }
}

/**
 * Prints the author box.
 */
function aqualuxe_author_box() {
    // Check if author box is enabled
    if (!get_theme_mod('aqualuxe_author_box_enable', true)) {
        return;
    }
    
    // Get the author ID
    $author_id = get_the_author_meta('ID');
    
    // Get the author data
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 96, '', $author_name, array('class' => 'rounded-full'));
    
    if ($author_description) {
        ?>
        <div class="author-box my-8 p-6 bg-gray-100 dark:bg-gray-800 rounded-lg">
            <div class="author-box-header flex items-center mb-4">
                <?php if ($author_avatar) : ?>
                    <div class="author-avatar mr-4">
                        <?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                <?php endif; ?>
                
                <div class="author-info">
                    <h3 class="author-name text-xl font-bold mb-1">
                        <a href="<?php echo esc_url($author_url); ?>" class="author-link">
                            <?php echo esc_html($author_name); ?>
                        </a>
                    </h3>
                    
                    <?php
                    // Get the author post count
                    $author_post_count = count_user_posts($author_id);
                    ?>
                    
                    <div class="author-post-count text-sm text-gray-500">
                        <?php
                        printf(
                            /* translators: %d: number of posts */
                            esc_html(_n('%d post', '%d posts', $author_post_count, 'aqualuxe')),
                            esc_html($author_post_count)
                        );
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="author-description">
                <?php echo wpautop(wp_kses_post($author_description)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Prints the share buttons.
 */
function aqualuxe_share_buttons() {
    // Check if share buttons are enabled
    if (!get_theme_mod('aqualuxe_share_buttons_enable', true)) {
        return;
    }
    
    // Get the current post URL and title
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    
    // Get the featured image
    $post_thumbnail = '';
    if (has_post_thumbnail()) {
        $post_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
    }
    
    // Build the share URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
    $email_url = 'mailto:?subject=' . $post_title . '&body=' . $post_url;
    
    ?>
    <div class="share-buttons my-8">
        <h3 class="share-buttons-title text-lg font-bold mb-3"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
        
        <div class="share-buttons-list flex space-x-3">
            <a href="<?php echo esc_url($facebook_url); ?>" class="share-button facebook" target="_blank" rel="noopener noreferrer">
                <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
            </a>
            
            <a href="<?php echo esc_url($twitter_url); ?>" class="share-button twitter" target="_blank" rel="noopener noreferrer">
                <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                <i class="fab fa-twitter" aria-hidden="true"></i>
            </a>
            
            <a href="<?php echo esc_url($linkedin_url); ?>" class="share-button linkedin" target="_blank" rel="noopener noreferrer">
                <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
            </a>
            
            <a href="<?php echo esc_url($pinterest_url); ?>" class="share-button pinterest" target="_blank" rel="noopener noreferrer">
                <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                <i class="fab fa-pinterest-p" aria-hidden="true"></i>
            </a>
            
            <a href="<?php echo esc_url($email_url); ?>" class="share-button email">
                <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                <i class="fas fa-envelope" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Prints the post meta.
 */
function aqualuxe_post_meta() {
    // Check if post meta is enabled
    if (!get_theme_mod('aqualuxe_post_meta_enable', true)) {
        return;
    }
    
    ?>
    <div class="post-meta flex flex-wrap items-center text-sm text-gray-500 mb-4">
        <?php if (get_theme_mod('aqualuxe_post_meta_date', true)) : ?>
            <div class="post-meta-item post-date mr-4">
                <i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>
                <?php echo esc_html(get_the_date()); ?>
            </div>
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_post_meta_author', true)) : ?>
            <div class="post-meta-item post-author mr-4">
                <i class="far fa-user mr-1" aria-hidden="true"></i>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="post-author-link">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_post_meta_categories', true)) : ?>
            <div class="post-meta-item post-categories mr-4">
                <i class="far fa-folder-open mr-1" aria-hidden="true"></i>
                <?php
                $categories = get_the_category();
                if ($categories) {
                    $output = '';
                    foreach ($categories as $category) {
                        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="post-category-link">' . esc_html($category->name) . '</a>, ';
                    }
                    echo trim($output, ', '); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_post_meta_comments', true)) : ?>
            <div class="post-meta-item post-comments mr-4">
                <i class="far fa-comment mr-1" aria-hidden="true"></i>
                <?php
                comments_popup_link(
                    esc_html__('No Comments', 'aqualuxe'),
                    esc_html__('1 Comment', 'aqualuxe'),
                    esc_html__('% Comments', 'aqualuxe')
                );
                ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Prints the post tags.
 */
function aqualuxe_post_tags() {
    // Check if post tags are enabled
    if (!get_theme_mod('aqualuxe_post_tags_enable', true)) {
        return;
    }
    
    $tags = get_the_tags();
    
    if ($tags) {
        ?>
        <div class="post-tags my-6">
            <span class="post-tags-title font-semibold mr-2"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
            
            <div class="post-tags-list inline-flex flex-wrap">
                <?php
                foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="post-tag-link bg-gray-200 dark:bg-gray-700 text-sm px-3 py-1 rounded-full mr-2 mb-2">' . esc_html($tag->name) . '</a>';
                }
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Prints the pagination.
 */
function aqualuxe_pagination() {
    $pagination_type = get_theme_mod('aqualuxe_pagination_type', 'numbered');
    
    if ($pagination_type === 'numbered') {
        // Numbered pagination
        the_posts_pagination(
            array(
                'mid_size' => 2,
                'prev_text' => '<i class="fas fa-chevron-left" aria-hidden="true"></i><span class="screen-reader-text">' . esc_html__('Previous page', 'aqualuxe') . '</span>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__('Next page', 'aqualuxe') . '</span><i class="fas fa-chevron-right" aria-hidden="true"></i>',
                'class' => 'pagination flex justify-center my-8',
            )
        );
    } else {
        // Older/newer pagination
        the_posts_navigation(
            array(
                'prev_text' => '<i class="fas fa-chevron-left mr-2" aria-hidden="true"></i>' . esc_html__('Older Posts', 'aqualuxe'),
                'next_text' => esc_html__('Newer Posts', 'aqualuxe') . '<i class="fas fa-chevron-right ml-2" aria-hidden="true"></i>',
                'class' => 'posts-navigation flex justify-between my-8',
            )
        );
    }
}

/**
 * Prints the back to top button.
 */
function aqualuxe_back_to_top() {
    // Check if back to top button is enabled
    if (!get_theme_mod('aqualuxe_back_to_top_enable', true)) {
        return;
    }
    
    ?>
    <button id="back-to-top" class="back-to-top fixed bottom-8 right-8 z-50 p-3 bg-primary text-white rounded-full shadow-lg opacity-0 invisible transition-all duration-300" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <i class="fas fa-chevron-up" aria-hidden="true"></i>
    </button>
    <?php
}

/**
 * Prints the cookie notice.
 */
function aqualuxe_cookie_notice() {
    // Check if cookie notice is enabled
    if (!get_theme_mod('aqualuxe_cookie_notice_enable', true)) {
        return;
    }
    
    $cookie_notice_text = get_theme_mod('aqualuxe_cookie_notice_text', esc_html__('We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.', 'aqualuxe'));
    $cookie_notice_button_text = get_theme_mod('aqualuxe_cookie_notice_button_text', esc_html__('Accept', 'aqualuxe'));
    $cookie_notice_privacy_link = get_theme_mod('aqualuxe_cookie_notice_privacy_link');
    
    ?>
    <div id="cookie-notice" class="cookie-notice fixed bottom-0 left-0 w-full bg-gray-900 text-white p-4 z-50 transform translate-y-full transition-transform duration-300">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between">
            <div class="cookie-notice-text mb-4 md:mb-0">
                <?php echo wp_kses_post($cookie_notice_text); ?>
                
                <?php if ($cookie_notice_privacy_link) : ?>
                    <a href="<?php echo esc_url($cookie_notice_privacy_link); ?>" class="cookie-notice-privacy-link text-primary hover:underline">
                        <?php esc_html_e('Privacy Policy', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
            
            <button id="cookie-notice-accept" class="cookie-notice-button bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-md transition-colors duration-300">
                <?php echo esc_html($cookie_notice_button_text); ?>
            </button>
        </div>
    </div>
    <?php
}

/**
 * Prints the dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode is enabled
    if (!get_theme_mod('aqualuxe_dark_mode_enable', true)) {
        return;
    }
    
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle p-2" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
        <i class="fas fa-sun light-icon" aria-hidden="true"></i>
        <i class="fas fa-moon dark-icon" aria-hidden="true"></i>
    </button>
    <?php
}

/**
 * Prints the language switcher.
 */
function aqualuxe_language_switcher() {
    // Check if multilingual is active
    if (!aqualuxe_is_multilingual_active()) {
        return;
    }
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        do_action('wpml_add_language_selector');
    }
    
    // Check if Polylang is active
    if (aqualuxe_is_polylang_active()) {
        pll_the_languages(array('dropdown' => 1));
    }
}

/**
 * Prints the currency switcher.
 */
function aqualuxe_currency_switcher() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if WPML with WooCommerce Multilingual is active
    if (function_exists('wcml_is_multi_currency_on') && wcml_is_multi_currency_on()) {
        do_action('wcml_currency_switcher', array('format' => '%code%'));
    }
}