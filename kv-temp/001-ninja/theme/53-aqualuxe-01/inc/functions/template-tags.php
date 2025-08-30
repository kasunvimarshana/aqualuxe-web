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
            <?php the_post_thumbnail(); ?>
        </div><!-- .post-thumbnail -->

    <?php else : ?>

        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'post-thumbnail',
                [
                    'alt' => the_title_attribute(
                        [
                            'echo' => false,
                        ]
                    ),
                ]
            );
            ?>
        </a>

        <?php
    endif; // End is_singular().
}

/**
 * Prints the header image.
 */
function aqualuxe_header_image() {
    if (has_header_image()) :
        ?>
        <div class="header-image">
            <img src="<?php header_image(); ?>" width="<?php echo esc_attr(get_custom_header()->width); ?>" height="<?php echo esc_attr(get_custom_header()->height); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
        </div>
        <?php
    endif;
}

/**
 * Prints the site branding.
 */
function aqualuxe_site_branding() {
    ?>
    <div class="site-branding">
        <?php
        the_custom_logo();
        if (is_front_page() && is_home()) :
            ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php
        else :
            ?>
            <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
            <?php
        endif;
        $aqualuxe_description = get_bloginfo('description', 'display');
        if ($aqualuxe_description || is_customize_preview()) :
            ?>
            <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <?php endif; ?>
    </div><!-- .site-branding -->
    <?php
}

/**
 * Prints the primary navigation.
 */
function aqualuxe_primary_navigation() {
    if (has_nav_menu('primary')) :
        ?>
        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <?php esc_html_e('Primary Menu', 'aqualuxe'); ?>
            </button>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                ]
            );
            ?>
        </nav><!-- #site-navigation -->
        <?php
    endif;
}

/**
 * Prints the mobile navigation.
 */
function aqualuxe_mobile_navigation() {
    if (has_nav_menu('mobile')) :
        ?>
        <nav id="mobile-navigation" class="mobile-navigation">
            <button class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false">
                <?php esc_html_e('Menu', 'aqualuxe'); ?>
            </button>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'mobile',
                    'menu_id' => 'mobile-menu',
                    'container' => false,
                ]
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    elseif (has_nav_menu('primary')) :
        ?>
        <nav id="mobile-navigation" class="mobile-navigation">
            <button class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false">
                <?php esc_html_e('Menu', 'aqualuxe'); ?>
            </button>
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary',
                    'menu_id' => 'mobile-menu',
                    'container' => false,
                ]
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    endif;
}

/**
 * Prints the footer navigation.
 */
function aqualuxe_footer_navigation() {
    if (has_nav_menu('footer')) :
        ?>
        <nav class="footer-navigation">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'footer',
                    'menu_id' => 'footer-menu',
                    'container' => false,
                    'depth' => 1,
                ]
            );
            ?>
        </nav><!-- .footer-navigation -->
        <?php
    endif;
}

/**
 * Prints the social navigation.
 */
function aqualuxe_social_navigation() {
    if (has_nav_menu('social')) :
        ?>
        <nav class="social-navigation">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'social',
                    'menu_id' => 'social-menu',
                    'container' => false,
                    'link_before' => '<span class="screen-reader-text">',
                    'link_after' => '</span>',
                    'depth' => 1,
                ]
            );
            ?>
        </nav><!-- .social-navigation -->
        <?php
    endif;
}

/**
 * Prints the account navigation.
 */
function aqualuxe_account_navigation() {
    if (has_nav_menu('account')) :
        ?>
        <nav class="account-navigation">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'account',
                    'menu_id' => 'account-menu',
                    'container' => false,
                    'depth' => 1,
                ]
            );
            ?>
        </nav><!-- .account-navigation -->
        <?php
    endif;
}

/**
 * Prints the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    echo aqualuxe_get_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints the social links.
 */
function aqualuxe_social_links() {
    echo aqualuxe_get_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints the footer text.
 */
function aqualuxe_footer_text() {
    echo wp_kses_post(aqualuxe_get_footer_text());
}

/**
 * Prints the page title.
 */
function aqualuxe_page_title() {
    echo wp_kses_post(aqualuxe_get_page_title());
}

/**
 * Prints the page subtitle.
 */
function aqualuxe_page_subtitle() {
    echo wp_kses_post(aqualuxe_get_page_subtitle());
}

/**
 * Prints the post meta.
 *
 * @param int $post_id Post ID
 */
function aqualuxe_post_meta($post_id = null) {
    echo wp_kses_post(aqualuxe_get_post_meta($post_id));
}

/**
 * Prints the post footer.
 *
 * @param int $post_id Post ID
 */
function aqualuxe_post_footer($post_id = null) {
    echo wp_kses_post(aqualuxe_get_post_footer($post_id));
}

/**
 * Prints the post thumbnail.
 *
 * @param int    $post_id Post ID
 * @param string $size    Thumbnail size
 */
function aqualuxe_post_thumbnail_html($post_id = null, $size = 'post-thumbnail') {
    echo wp_kses_post(aqualuxe_get_post_thumbnail($post_id, $size));
}

/**
 * Prints the post navigation.
 */
function aqualuxe_post_navigation() {
    echo wp_kses_post(aqualuxe_get_post_navigation());
}

/**
 * Prints the posts pagination.
 */
function aqualuxe_posts_pagination() {
    echo wp_kses_post(aqualuxe_get_posts_pagination());
}

/**
 * Prints the dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
    if (aqualuxe_is_module_active('dark-mode')) {
        ?>
        <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle Dark Mode', 'aqualuxe'); ?>">
            <span class="dark-mode-toggle-icon"></span>
        </button>
        <?php
    }
}

/**
 * Prints the language switcher.
 */
function aqualuxe_language_switcher() {
    if (aqualuxe_is_module_active('multilingual')) {
        $module = aqualuxe_init()->get_module('multilingual');
        if ($module) {
            $module->render_language_switcher();
        }
    }
}

/**
 * Prints the currency switcher.
 */
function aqualuxe_currency_switcher() {
    if (aqualuxe_is_module_active('multicurrency')) {
        $module = aqualuxe_init()->get_module('multicurrency');
        if ($module) {
            $module->render_currency_switcher();
        }
    }
}

/**
 * Prints the search form.
 */
function aqualuxe_search_form() {
    get_search_form();
}

/**
 * Prints the mini cart.
 */
function aqualuxe_mini_cart() {
    if (class_exists('WooCommerce') && aqualuxe_is_module_active('woocommerce')) {
        $module = aqualuxe_init()->get_module('woocommerce');
        if ($module) {
            $module->render_mini_cart();
        }
    }
}

/**
 * Prints the wishlist link.
 */
function aqualuxe_wishlist_link() {
    if (class_exists('WooCommerce') && aqualuxe_is_module_active('wishlist')) {
        $module = aqualuxe_init()->get_module('wishlist');
        if ($module) {
            $module->render_wishlist_link();
        }
    }
}

/**
 * Prints the account link.
 */
function aqualuxe_account_link() {
    if (class_exists('WooCommerce')) {
        ?>
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-link">
            <?php esc_html_e('My Account', 'aqualuxe'); ?>
        </a>
        <?php
    }
}

/**
 * Prints the header actions.
 */
function aqualuxe_header_actions() {
    ?>
    <div class="header-actions">
        <?php aqualuxe_search_form(); ?>
        <?php aqualuxe_dark_mode_toggle(); ?>
        <?php aqualuxe_language_switcher(); ?>
        <?php aqualuxe_currency_switcher(); ?>
        <?php aqualuxe_wishlist_link(); ?>
        <?php aqualuxe_mini_cart(); ?>
        <?php aqualuxe_account_link(); ?>
    </div>
    <?php
}

/**
 * Check if a module is active.
 *
 * @param string $module_name Module name
 * @return bool
 */
function aqualuxe_is_module_active($module_name) {
    return aqualuxe_init()->is_module_active($module_name);
}

/**
 * Get a module instance.
 *
 * @param string $module_name Module name
 * @return mixed|null
 */
function aqualuxe_get_module($module_name) {
    return aqualuxe_init()->get_module($module_name);
}