<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

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
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
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
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
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
            <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'img-fluid']); ?>
        </div><!-- .post-thumbnail -->

    <?php else : ?>

        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'aqualuxe-thumbnail',
                [
                    'alt' => the_title_attribute(
                        [
                            'echo' => false,
                        ]
                    ),
                    'class' => 'img-fluid',
                ]
            );
            ?>
        </a>

        <?php
    endif; // End is_singular().
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function aqualuxe_posted_on_and_by() {
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

    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories.
 */
function aqualuxe_post_categories() {
    // Hide category text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}

/**
 * Prints HTML with meta information for the tags.
 */
function aqualuxe_post_tags() {
    // Hide tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}

/**
 * Prints HTML with meta information for the comments.
 */
function aqualuxe_post_comments() {
    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                wp_kses_post(get_the_title())
            )
        );
        echo '</span>';
    }
}

/**
 * Prints HTML with meta information for the edit link.
 */
function aqualuxe_edit_link() {
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            wp_kses_post(get_the_title())
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Displays the post excerpt with custom length.
 *
 * @param int $length The length of the excerpt in words.
 */
function aqualuxe_the_excerpt($length = 55) {
    $excerpt = get_the_excerpt();
    $excerpt = wp_trim_words($excerpt, $length, '&hellip;');
    echo '<div class="entry-excerpt">' . $excerpt . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the read more link.
 */
function aqualuxe_read_more() {
    echo '<div class="read-more-link"><a href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More', 'aqualuxe') . '</a></div>';
}

/**
 * Displays the post thumbnail with custom size.
 *
 * @param string $size The size of the thumbnail.
 */
function aqualuxe_the_post_thumbnail($size = 'aqualuxe-thumbnail') {
    if (has_post_thumbnail()) {
        if (is_singular()) {
            the_post_thumbnail($size, ['class' => 'img-fluid']);
        } else {
            echo '<a href="' . esc_url(get_permalink()) . '" class="post-thumbnail">';
            the_post_thumbnail($size, ['class' => 'img-fluid', 'alt' => the_title_attribute(['echo' => false])]);
            echo '</a>';
        }
    }
}

/**
 * Displays the post meta information.
 */
function aqualuxe_post_meta() {
    echo '<div class="entry-meta">';
    aqualuxe_posted_on_and_by();
    echo '</div>';
}

/**
 * Displays the post footer information.
 */
function aqualuxe_post_footer() {
    echo '<footer class="entry-footer">';
    aqualuxe_post_categories();
    aqualuxe_post_tags();
    aqualuxe_post_comments();
    aqualuxe_edit_link();
    echo '</footer>';
}

/**
 * Displays the pagination.
 */
function aqualuxe_the_posts_pagination() {
    the_posts_pagination([
        'mid_size' => 2,
        'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . __('Previous', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . __('Next', 'aqualuxe') . '</span><i class="fas fa-chevron-right"></i>',
    ]);
}

/**
 * Displays the post navigation.
 */
function aqualuxe_the_post_navigation() {
    the_post_navigation([
        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
    ]);
}

/**
 * Displays the comments.
 */
function aqualuxe_the_comments() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Displays the breadcrumbs.
 */
function aqualuxe_the_breadcrumbs() {
    aqualuxe_breadcrumbs();
}

/**
 * Displays the page header.
 *
 * @param string $title The title of the page.
 * @param string $subtitle The subtitle of the page.
 * @param array $args Additional arguments.
 */
function aqualuxe_the_page_header($title = '', $subtitle = '', $args = []) {
    aqualuxe_page_header($title, $subtitle, $args);
}

/**
 * Displays the social sharing buttons.
 */
function aqualuxe_the_social_sharing() {
    aqualuxe_social_sharing();
}

/**
 * Displays the related posts.
 *
 * @param int $count The number of related posts to display.
 */
function aqualuxe_the_related_posts($count = 3) {
    aqualuxe_related_posts(null, $count);
}

/**
 * Displays the author bio.
 */
function aqualuxe_the_author_bio() {
    aqualuxe_author_bio();
}

/**
 * Displays the social links.
 */
function aqualuxe_the_social_links() {
    aqualuxe_social_links();
}

/**
 * Displays the contact information.
 */
function aqualuxe_the_contact_info() {
    aqualuxe_contact_info();
}

/**
 * Displays the newsletter form.
 */
function aqualuxe_the_newsletter_form() {
    aqualuxe_newsletter_form();
}

/**
 * Displays the star rating.
 *
 * @param float $rating The rating value.
 * @param int $max The maximum rating value.
 */
function aqualuxe_the_star_rating($rating, $max = 5) {
    aqualuxe_star_rating($rating, $max);
}

/**
 * Displays the logo.
 *
 * @param string $class Additional CSS class.
 */
function aqualuxe_the_logo($class = '') {
    $logo_id = get_theme_mod('custom_logo');
    $logo_class = 'site-logo';
    
    if ($class) {
        $logo_class .= ' ' . $class;
    }
    
    if ($logo_id) {
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        $logo_alt = get_bloginfo('name');
        
        echo '<a href="' . esc_url(home_url('/')) . '" class="' . esc_attr($logo_class) . '"><img src="' . esc_url($logo_url) . '" alt="' . esc_attr($logo_alt) . '" /></a>';
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title">' . esc_html(get_bloginfo('name')) . '</a>';
    }
}

/**
 * Displays the site title.
 */
function aqualuxe_the_site_title() {
    echo '<h1 class="site-title"><a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a></h1>';
}

/**
 * Displays the site description.
 */
function aqualuxe_the_site_description() {
    $description = get_bloginfo('description', 'display');
    
    if ($description || is_customize_preview()) {
        echo '<p class="site-description">' . $description . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

/**
 * Displays the primary menu.
 */
function aqualuxe_the_primary_menu() {
    wp_nav_menu([
        'theme_location' => 'primary',
        'menu_id' => 'primary-menu',
        'container' => 'nav',
        'container_class' => 'primary-menu-container',
        'menu_class' => 'primary-menu',
        'fallback_cb' => false,
    ]);
}

/**
 * Displays the mobile menu.
 */
function aqualuxe_the_mobile_menu() {
    wp_nav_menu([
        'theme_location' => 'mobile',
        'menu_id' => 'mobile-menu',
        'container' => 'nav',
        'container_class' => 'mobile-menu-container',
        'menu_class' => 'mobile-menu',
        'fallback_cb' => false,
    ]);
}

/**
 * Displays the footer menu.
 */
function aqualuxe_the_footer_menu() {
    wp_nav_menu([
        'theme_location' => 'footer',
        'menu_id' => 'footer-menu',
        'container' => 'nav',
        'container_class' => 'footer-menu-container',
        'menu_class' => 'footer-menu',
        'fallback_cb' => false,
    ]);
}

/**
 * Displays the social menu.
 */
function aqualuxe_the_social_menu() {
    wp_nav_menu([
        'theme_location' => 'social',
        'menu_id' => 'social-menu',
        'container' => 'nav',
        'container_class' => 'social-menu-container',
        'menu_class' => 'social-menu',
        'fallback_cb' => false,
        'link_before' => '<span class="screen-reader-text">',
        'link_after' => '</span>',
    ]);
}

/**
 * Displays the copyright text.
 */
function aqualuxe_the_copyright() {
    $copyright = get_theme_mod('aqualuxe_copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
    
    echo '<div class="copyright">' . wp_kses_post($copyright) . '</div>';
}

/**
 * Displays the search form.
 */
function aqualuxe_the_search_form() {
    get_search_form();
}

/**
 * Displays the sidebar.
 */
function aqualuxe_the_sidebar() {
    get_sidebar();
}

/**
 * Displays the footer widgets.
 */
function aqualuxe_the_footer_widgets() {
    if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) {
        echo '<div class="footer-widgets">';
        echo '<div class="container">';
        echo '<div class="row">';
        
        for ($i = 1; $i <= 4; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                echo '<div class="col-md-6 col-lg-3">';
                dynamic_sidebar('footer-' . $i);
                echo '</div>';
            }
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Displays the back to top button.
 */
function aqualuxe_the_back_to_top() {
    echo '<a href="#" class="back-to-top" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '"><i class="fas fa-chevron-up"></i></a>';
}

/**
 * Displays the mobile menu toggle button.
 */
function aqualuxe_the_mobile_menu_toggle() {
    echo '<button class="mobile-menu-toggle" aria-controls="mobile-menu" aria-expanded="false">';
    echo '<span class="toggle-icon"></span>';
    echo '<span class="screen-reader-text">' . esc_html__('Menu', 'aqualuxe') . '</span>';
    echo '</button>';
}

/**
 * Displays the search toggle button.
 */
function aqualuxe_the_search_toggle() {
    echo '<button class="search-toggle" aria-controls="search-form" aria-expanded="false">';
    echo '<i class="fas fa-search"></i>';
    echo '<span class="screen-reader-text">' . esc_html__('Search', 'aqualuxe') . '</span>';
    echo '</button>';
}

/**
 * Displays the cart icon.
 */
function aqualuxe_the_cart_icon() {
    if (aqualuxe_is_woocommerce_active()) {
        echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="cart-icon">';
        echo '<i class="fas fa-shopping-cart"></i>';
        echo '<span class="cart-count">' . esc_html(WC()->cart->get_cart_contents_count()) . '</span>';
        echo '</a>';
    }
}

/**
 * Displays the mini cart.
 */
function aqualuxe_the_mini_cart() {
    if (aqualuxe_is_woocommerce_active()) {
        woocommerce_mini_cart();
    }
}

/**
 * Displays the account icon.
 */
function aqualuxe_the_account_icon() {
    if (aqualuxe_is_woocommerce_active()) {
        echo '<a href="' . esc_url(wc_get_account_endpoint_url('dashboard')) . '" class="account-icon">';
        echo '<i class="fas fa-user"></i>';
        echo '<span class="screen-reader-text">' . esc_html__('My Account', 'aqualuxe') . '</span>';
        echo '</a>';
    }
}

/**
 * Displays the wishlist icon.
 */
function aqualuxe_the_wishlist_icon() {
    if (aqualuxe_is_woocommerce_active() && aqualuxe_is_module_active('wishlist')) {
        echo '<a href="' . esc_url(get_permalink(get_option('aqualuxe_wishlist_page'))) . '" class="wishlist-icon">';
        echo '<i class="fas fa-heart"></i>';
        echo '<span class="wishlist-count">' . esc_html(aqualuxe_get_wishlist_count()) . '</span>';
        echo '</a>';
    }
}

/**
 * Get the wishlist count.
 *
 * @return int
 */
function aqualuxe_get_wishlist_count() {
    if (aqualuxe_is_woocommerce_active() && aqualuxe_is_module_active('wishlist')) {
        $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
        
        if (is_array($wishlist)) {
            return count($wishlist);
        }
    }
    
    return 0;
}

/**
 * Displays the language switcher.
 */
function aqualuxe_the_language_switcher() {
    if (aqualuxe_is_module_active('multilingual')) {
        aqualuxe_get_module_template_part('multilingual', 'language-switcher');
    }
}

/**
 * Displays the currency switcher.
 */
function aqualuxe_the_currency_switcher() {
    if (aqualuxe_is_woocommerce_active() && aqualuxe_is_module_active('multicurrency')) {
        aqualuxe_get_module_template_part('multicurrency', 'currency-switcher');
    }
}

/**
 * Displays the dark mode toggle.
 */
function aqualuxe_the_dark_mode_toggle() {
    if (aqualuxe_is_module_active('dark-mode')) {
        aqualuxe_get_module_template_part('dark-mode', 'toggle');
    }
}