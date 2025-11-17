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
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'featured-image')); ?>
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
                    'class' => 'thumbnail-image',
                )
            );
            ?>
        </a>

        <?php
    endif; // End is_singular().
}

/**
 * Prints the site logo or site title.
 */
function aqualuxe_site_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-title"><?php bloginfo('name'); ?></a>
        <?php
    }
}

/**
 * Prints the site description.
 */
function aqualuxe_site_description() {
    $description = get_bloginfo('description', 'display');
    if ($description || is_customize_preview()) {
        ?>
        <p class="site-description"><?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        <?php
    }
}

/**
 * Prints the primary navigation.
 */
function aqualuxe_primary_navigation() {
    if (has_nav_menu('primary')) {
        ?>
        <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <?php echo aqualuxe_get_icon('menu', array('class' => 'menu-icon')); ?>
                <?php echo aqualuxe_get_icon('close', array('class' => 'close-icon')); ?>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            </button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'primary-menu',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #site-navigation -->
        <?php
    }
}

/**
 * Prints the mobile navigation.
 */
function aqualuxe_mobile_navigation() {
    if (has_nav_menu('mobile')) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    } elseif (has_nav_menu('primary')) {
        ?>
        <nav id="mobile-navigation" class="mobile-navigation" aria-label="<?php esc_attr_e('Mobile Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #mobile-navigation -->
        <?php
    }
}

/**
 * Prints the footer navigation.
 */
function aqualuxe_footer_navigation() {
    if (has_nav_menu('footer')) {
        ?>
        <nav id="footer-navigation" class="footer-navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #footer-navigation -->
        <?php
    }
}

/**
 * Prints the top bar navigation.
 */
function aqualuxe_top_bar_navigation() {
    if (has_nav_menu('top-bar')) {
        ?>
        <nav id="top-bar-navigation" class="top-bar-navigation" aria-label="<?php esc_attr_e('Top Bar Navigation', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'top-bar',
                    'menu_id'        => 'top-bar-menu',
                    'container'      => false,
                    'menu_class'     => 'top-bar-menu',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #top-bar-navigation -->
        <?php
    }
}

/**
 * Prints the social navigation.
 */
function aqualuxe_social_navigation() {
    if (has_nav_menu('social')) {
        ?>
        <nav id="social-navigation" class="social-navigation" aria-label="<?php esc_attr_e('Social Links', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'social',
                    'menu_id'        => 'social-menu',
                    'container'      => false,
                    'menu_class'     => 'social-menu',
                    'depth'          => 1,
                    'link_before'    => '<span class="screen-reader-text">',
                    'link_after'     => '</span>',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #social-navigation -->
        <?php
    }
}

/**
 * Prints the breadcrumbs.
 */
function aqualuxe_display_breadcrumbs() {
    if (function_exists('aqualuxe_breadcrumbs')) {
        aqualuxe_breadcrumbs();
    }
}

/**
 * Prints the page title.
 */
function aqualuxe_page_title() {
    if (is_front_page()) {
        return;
    }

    ?>
    <header class="page-header">
        <?php
        if (is_archive()) {
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
        } elseif (is_search()) {
            ?>
            <h1 class="page-title">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
            <?php
        } elseif (is_404()) {
            ?>
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
            <?php
        } elseif (is_page()) {
            the_title('<h1 class="page-title">', '</h1>');
        } elseif (is_singular('post')) {
            the_title('<h1 class="entry-title">', '</h1>');
        }
        ?>
    </header><!-- .page-header -->
    <?php
}

/**
 * Prints the post navigation.
 */
function aqualuxe_display_post_navigation() {
    if (function_exists('aqualuxe_post_navigation')) {
        aqualuxe_post_navigation();
    }
}

/**
 * Prints the author bio.
 */
function aqualuxe_display_author_bio() {
    if (function_exists('aqualuxe_author_bio')) {
        aqualuxe_author_bio();
    }
}

/**
 * Prints the related posts.
 */
function aqualuxe_display_related_posts() {
    if (function_exists('aqualuxe_related_posts')) {
        aqualuxe_related_posts();
    }
}

/**
 * Prints the social sharing buttons.
 */
function aqualuxe_display_social_sharing() {
    if (function_exists('aqualuxe_social_sharing')) {
        aqualuxe_social_sharing();
    }
}

/**
 * Prints the language switcher.
 */
function aqualuxe_display_language_switcher() {
    if (function_exists('aqualuxe_language_switcher')) {
        aqualuxe_language_switcher();
    }
}

/**
 * Prints the dark mode toggle.
 */
function aqualuxe_display_dark_mode_toggle() {
    if (function_exists('aqualuxe_dark_mode_toggle')) {
        aqualuxe_dark_mode_toggle();
    }
}

/**
 * Prints the search form.
 */
function aqualuxe_display_search_form() {
    if (function_exists('aqualuxe_search_form')) {
        aqualuxe_search_form();
    }
}

/**
 * Prints the mobile menu toggle.
 */
function aqualuxe_display_mobile_menu_toggle() {
    if (function_exists('aqualuxe_mobile_menu_toggle')) {
        aqualuxe_mobile_menu_toggle();
    }
}

/**
 * Prints the WooCommerce cart link.
 */
function aqualuxe_display_cart_link() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_woocommerce_cart_link')) {
        aqualuxe_woocommerce_cart_link();
    }
}

/**
 * Prints the WooCommerce currency switcher.
 */
function aqualuxe_display_currency_switcher() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_currency_switcher')) {
        aqualuxe_currency_switcher();
    }
}

/**
 * Prints the WooCommerce advanced product filtering.
 */
function aqualuxe_display_advanced_product_filtering() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_advanced_product_filtering')) {
        aqualuxe_advanced_product_filtering();
    }
}

/**
 * Prints the WooCommerce product availability.
 */
function aqualuxe_display_product_availability() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_product_availability')) {
        aqualuxe_product_availability();
    }
}

/**
 * Prints the WooCommerce estimated delivery.
 */
function aqualuxe_display_estimated_delivery() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_estimated_delivery')) {
        aqualuxe_estimated_delivery();
    }
}

/**
 * Prints the WooCommerce vendor info.
 */
function aqualuxe_display_vendor_info() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_vendor_info')) {
        aqualuxe_vendor_info();
    }
}

/**
 * Prints the WooCommerce quick view button.
 */
function aqualuxe_display_quick_view_button() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_quick_view_button')) {
        aqualuxe_quick_view_button();
    }
}

/**
 * Prints the WooCommerce wishlist button.
 */
function aqualuxe_display_wishlist_button() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_add_wishlist_button')) {
        aqualuxe_add_wishlist_button();
    }
}

/**
 * Prints the WooCommerce live fish details.
 */
function aqualuxe_display_live_fish_details() {
    if (aqualuxe_is_woocommerce_active() && function_exists('aqualuxe_display_live_fish_details')) {
        aqualuxe_display_live_fish_details();
    }
}

/**
 * Prints the comments template.
 */
function aqualuxe_display_comments() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Prints the pagination.
 */
function aqualuxe_display_pagination() {
    if (is_singular()) {
        return;
    }

    global $wp_query;

    // Don't print empty markup if there's only one page.
    if ($wp_query->max_num_pages < 2) {
        return;
    }

    $paged        = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args   = array();
    $url_parts    = explode('?', $pagenum_link);

    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }

    $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';

    $format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links(
        array(
            'base'      => $pagenum_link,
            'format'    => $format,
            'total'     => $wp_query->max_num_pages,
            'current'   => $paged,
            'mid_size'  => 1,
            'add_args'  => array_map('urlencode', $query_args),
            'prev_text' => aqualuxe_get_icon('chevron-left', array('class' => 'pagination-icon')) . '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span>' . aqualuxe_get_icon('chevron-right', array('class' => 'pagination-icon')),
            'type'      => 'list',
        )
    );

    if ($links) {
        echo '<nav class="pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">' . $links . '</nav>';
    }
}

/**
 * Prints the footer widgets.
 */
function aqualuxe_display_footer_widgets() {
    if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) {
        ?>
        <div class="footer-widgets">
            <div class="footer-widgets-container">
                <?php if (is_active_sidebar('footer-1')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-2')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-3')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                <?php endif; ?>

                <?php if (is_active_sidebar('footer-4')) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div><!-- .footer-widgets -->
        <?php
    }
}

/**
 * Prints the footer copyright.
 */
function aqualuxe_display_footer_copyright() {
    $copyright = aqualuxe_get_option('footer_copyright', '');

    if (empty($copyright)) {
        $copyright = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date_i18n('Y'),
            get_bloginfo('name')
        );
    }

    echo '<div class="site-info">' . wp_kses_post($copyright) . '</div>';
}

/**
 * Prints the footer credits.
 */
function aqualuxe_display_footer_credits() {
    $credits = aqualuxe_get_option('footer_credits', '');

    if (empty($credits)) {
        $credits = sprintf(
            /* translators: %1$s: WordPress link, %2$s: Theme name, %3$s: Theme author link */
            __('Powered by %1$s | Theme: %2$s by %3$s', 'aqualuxe'),
            '<a href="' . esc_url(__('https://wordpress.org/', 'aqualuxe')) . '">WordPress</a>',
            'AquaLuxe',
            '<a href="' . esc_url(__('https://ninjatech.ai/', 'aqualuxe')) . '">NinjaTech AI</a>'
        );
    }

    echo '<div class="site-credits">' . wp_kses_post($credits) . '</div>';
}

/**
 * Prints the sidebar.
 */
function aqualuxe_display_sidebar() {
    if (!is_active_sidebar('sidebar-1') || is_page_template('templates/full-width.php')) {
        return;
    }
    ?>
    <aside id="secondary" class="widget-area">
        <?php dynamic_sidebar('sidebar-1'); ?>
    </aside><!-- #secondary -->
    <?php
}

/**
 * Prints the shop sidebar.
 */
function aqualuxe_display_shop_sidebar() {
    if (!aqualuxe_is_woocommerce_active() || !is_active_sidebar('shop-sidebar')) {
        return;
    }
    ?>
    <aside id="shop-sidebar" class="widget-area shop-sidebar">
        <?php dynamic_sidebar('shop-sidebar'); ?>
    </aside><!-- #shop-sidebar -->
    <?php
}

/**
 * Prints the product sidebar.
 */
function aqualuxe_display_product_sidebar() {
    if (!aqualuxe_is_woocommerce_active() || !is_active_sidebar('product-sidebar')) {
        return;
    }
    ?>
    <aside id="product-sidebar" class="widget-area product-sidebar">
        <?php dynamic_sidebar('product-sidebar'); ?>
    </aside><!-- #product-sidebar -->
    <?php
}

/**
 * Prints the hero section.
 */
function aqualuxe_display_hero_section() {
    if (is_front_page()) {
        $hero_title = aqualuxe_get_option('hero_title', '');
        $hero_subtitle = aqualuxe_get_option('hero_subtitle', '');
        $hero_button_text = aqualuxe_get_option('hero_button_text', '');
        $hero_button_url = aqualuxe_get_option('hero_button_url', '');
        $hero_image = aqualuxe_get_option('hero_image', '');

        if (empty($hero_title) && empty($hero_subtitle) && empty($hero_button_text) && empty($hero_image)) {
            return;
        }

        ?>
        <section class="hero-section">
            <?php if (!empty($hero_image)) : ?>
                <div class="hero-image">
                    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
                </div>
            <?php endif; ?>

            <div class="hero-content">
                <?php if (!empty($hero_title)) : ?>
                    <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php endif; ?>

                <?php if (!empty($hero_subtitle)) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>

                <?php if (!empty($hero_button_text) && !empty($hero_button_url)) : ?>
                    <a href="<?php echo esc_url($hero_button_url); ?>" class="hero-button"><?php echo esc_html($hero_button_text); ?></a>
                <?php endif; ?>
            </div>
        </section><!-- .hero-section -->
        <?php
    }
}

/**
 * Prints the featured products section.
 */
function aqualuxe_display_featured_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_front_page()) {
        return;
    }

    $featured_products_title = aqualuxe_get_option('featured_products_title', __('Featured Products', 'aqualuxe'));
    $featured_products_count = aqualuxe_get_option('featured_products_count', 4);

    $args = array(
        'post_type'           => 'product',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $featured_products_count,
        'meta_query'          => array(
            array(
                'key'     => '_featured',
                'value'   => 'yes',
                'compare' => '=',
            ),
        ),
    );

    $featured_products = new WP_Query($args);

    if ($featured_products->have_posts()) {
        ?>
        <section class="featured-products">
            <h2 class="section-title"><?php echo esc_html($featured_products_title); ?></h2>

            <div class="products-grid">
                <?php
                while ($featured_products->have_posts()) {
                    $featured_products->the_post();
                    wc_get_template_part('content', 'product');
                }
                wp_reset_postdata();
                ?>
            </div>

            <div class="view-all">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="view-all-button"><?php esc_html_e('View All Products', 'aqualuxe'); ?></a>
            </div>
        </section><!-- .featured-products -->
        <?php
    }
}

/**
 * Prints the testimonials section.
 */
function aqualuxe_display_testimonials() {
    if (!is_front_page()) {
        return;
    }

    $testimonials_title = aqualuxe_get_option('testimonials_title', __('What Our Customers Say', 'aqualuxe'));
    $testimonials = aqualuxe_get_option('testimonials', array());

    if (empty($testimonials)) {
        return;
    }

    ?>
    <section class="testimonials">
        <h2 class="section-title"><?php echo esc_html($testimonials_title); ?></h2>

        <div class="testimonials-slider">
            <?php foreach ($testimonials as $testimonial) : ?>
                <div class="testimonial">
                    <div class="testimonial-content">
                        <p><?php echo esc_html($testimonial['content']); ?></p>
                    </div>

                    <div class="testimonial-author">
                        <?php if (!empty($testimonial['image'])) : ?>
                            <div class="testimonial-image">
                                <img src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>">
                            </div>
                        <?php endif; ?>

                        <div class="testimonial-info">
                            <h3 class="testimonial-name"><?php echo esc_html($testimonial['name']); ?></h3>
                            <?php if (!empty($testimonial['position'])) : ?>
                                <p class="testimonial-position"><?php echo esc_html($testimonial['position']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section><!-- .testimonials -->
    <?php
}

/**
 * Prints the newsletter section.
 */
function aqualuxe_display_newsletter() {
    if (!is_front_page()) {
        return;
    }

    $newsletter_title = aqualuxe_get_option('newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $newsletter_subtitle = aqualuxe_get_option('newsletter_subtitle', __('Stay updated with our latest products and offers.', 'aqualuxe'));
    $newsletter_form = aqualuxe_get_option('newsletter_form', '');

    if (empty($newsletter_form)) {
        return;
    }

    ?>
    <section class="newsletter">
        <div class="newsletter-content">
            <h2 class="section-title"><?php echo esc_html($newsletter_title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($newsletter_subtitle); ?></p>

            <div class="newsletter-form">
                <?php echo do_shortcode($newsletter_form); ?>
            </div>
        </div>
    </section><!-- .newsletter -->
    <?php
}

/**
 * Prints the about section.
 */
function aqualuxe_display_about_section() {
    if (!is_front_page()) {
        return;
    }

    $about_title = aqualuxe_get_option('about_title', __('About AquaLuxe', 'aqualuxe'));
    $about_content = aqualuxe_get_option('about_content', '');
    $about_image = aqualuxe_get_option('about_image', '');
    $about_button_text = aqualuxe_get_option('about_button_text', __('Learn More', 'aqualuxe'));
    $about_button_url = aqualuxe_get_option('about_button_url', '');

    if (empty($about_content) && empty($about_image)) {
        return;
    }

    ?>
    <section class="about-section">
        <div class="about-container">
            <?php if (!empty($about_image)) : ?>
                <div class="about-image">
                    <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr($about_title); ?>">
                </div>
            <?php endif; ?>

            <div class="about-content">
                <h2 class="section-title"><?php echo esc_html($about_title); ?></h2>

                <div class="about-text">
                    <?php echo wp_kses_post($about_content); ?>
                </div>

                <?php if (!empty($about_button_text) && !empty($about_button_url)) : ?>
                    <a href="<?php echo esc_url($about_button_url); ?>" class="about-button"><?php echo esc_html($about_button_text); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </section><!-- .about-section -->
    <?php
}

/**
 * Prints the services section.
 */
function aqualuxe_display_services_section() {
    if (!is_front_page()) {
        return;
    }

    $services_title = aqualuxe_get_option('services_title', __('Our Services', 'aqualuxe'));
    $services = aqualuxe_get_option('services', array());

    if (empty($services)) {
        return;
    }

    ?>
    <section class="services-section">
        <h2 class="section-title"><?php echo esc_html($services_title); ?></h2>

        <div class="services-grid">
            <?php foreach ($services as $service) : ?>
                <div class="service">
                    <?php if (!empty($service['icon'])) : ?>
                        <div class="service-icon">
                            <?php echo aqualuxe_get_icon($service['icon'], array('class' => 'service-icon-svg')); ?>
                        </div>
                    <?php endif; ?>

                    <h3 class="service-title"><?php echo esc_html($service['title']); ?></h3>

                    <div class="service-description">
                        <?php echo wp_kses_post($service['description']); ?>
                    </div>

                    <?php if (!empty($service['button_text']) && !empty($service['button_url'])) : ?>
                        <a href="<?php echo esc_url($service['button_url']); ?>" class="service-button"><?php echo esc_html($service['button_text']); ?></a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section><!-- .services-section -->
    <?php
}

/**
 * Prints the blog section.
 */
function aqualuxe_display_blog_section() {
    if (!is_front_page()) {
        return;
    }

    $blog_title = aqualuxe_get_option('blog_title', __('Latest Articles', 'aqualuxe'));
    $blog_count = aqualuxe_get_option('blog_count', 3);

    $args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $blog_count,
    );

    $blog_posts = new WP_Query($args);

    if ($blog_posts->have_posts()) {
        ?>
        <section class="blog-section">
            <h2 class="section-title"><?php echo esc_html($blog_title); ?></h2>

            <div class="blog-grid">
                <?php
                while ($blog_posts->have_posts()) {
                    $blog_posts->the_post();
                    ?>
                    <article class="blog-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                                <?php the_post_thumbnail('aqualuxe-blog-thumbnail'); ?>
                            </a>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <?php the_title('<h3 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>

                                <div class="entry-meta">
                                    <?php
                                    aqualuxe_posted_on();
                                    aqualuxe_posted_by();
                                    ?>
                                </div><!-- .entry-meta -->
                            </header><!-- .entry-header -->

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div><!-- .entry-summary -->

                            <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read More', 'aqualuxe'); ?></a>
                        </div><!-- .post-content -->
                    </article><!-- .blog-post -->
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>

            <div class="view-all">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="view-all-button"><?php esc_html_e('View All Articles', 'aqualuxe'); ?></a>
            </div>
        </section><!-- .blog-section -->
        <?php
    }
}

/**
 * Prints the contact section.
 */
function aqualuxe_display_contact_section() {
    if (!is_front_page()) {
        return;
    }

    $contact_title = aqualuxe_get_option('contact_title', __('Contact Us', 'aqualuxe'));
    $contact_subtitle = aqualuxe_get_option('contact_subtitle', __('Get in touch with us for any inquiries.', 'aqualuxe'));
    $contact_form = aqualuxe_get_option('contact_form', '');
    $contact_address = aqualuxe_get_option('contact_address', '');
    $contact_phone = aqualuxe_get_option('contact_phone', '');
    $contact_email = aqualuxe_get_option('contact_email', '');

    if (empty($contact_form) && empty($contact_address) && empty($contact_phone) && empty($contact_email)) {
        return;
    }

    ?>
    <section class="contact-section">
        <h2 class="section-title"><?php echo esc_html($contact_title); ?></h2>
        <p class="section-subtitle"><?php echo esc_html($contact_subtitle); ?></p>

        <div class="contact-container">
            <?php if (!empty($contact_form)) : ?>
                <div class="contact-form">
                    <?php echo do_shortcode($contact_form); ?>
                </div>
            <?php endif; ?>

            <div class="contact-info">
                <?php if (!empty($contact_address)) : ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <?php echo aqualuxe_get_icon('map-pin', array('class' => 'contact-icon-svg')); ?>
                        </div>
                        <div class="contact-text">
                            <h3><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                            <p><?php echo esc_html($contact_address); ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($contact_phone)) : ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <?php echo aqualuxe_get_icon('phone', array('class' => 'contact-icon-svg')); ?>
                        </div>
                        <div class="contact-text">
                            <h3><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                            <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($contact_email)) : ?>
                    <div class="contact-item">
                        <div class="contact-icon">
                            <?php echo aqualuxe_get_icon('mail', array('class' => 'contact-icon-svg')); ?>
                        </div>
                        <div class="contact-text">
                            <h3><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                            <p><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section><!-- .contact-section -->
    <?php
}

/**
 * Prints the partners section.
 */
function aqualuxe_display_partners_section() {
    if (!is_front_page()) {
        return;
    }

    $partners_title = aqualuxe_get_option('partners_title', __('Our Partners', 'aqualuxe'));
    $partners = aqualuxe_get_option('partners', array());

    if (empty($partners)) {
        return;
    }

    ?>
    <section class="partners-section">
        <h2 class="section-title"><?php echo esc_html($partners_title); ?></h2>

        <div class="partners-grid">
            <?php foreach ($partners as $partner) : ?>
                <div class="partner">
                    <?php if (!empty($partner['url'])) : ?>
                        <a href="<?php echo esc_url($partner['url']); ?>" target="_blank" rel="noopener noreferrer">
                    <?php endif; ?>

                    <?php if (!empty($partner['logo'])) : ?>
                        <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>">
                    <?php else : ?>
                        <span class="partner-name"><?php echo esc_html($partner['name']); ?></span>
                    <?php endif; ?>

                    <?php if (!empty($partner['url'])) : ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section><!-- .partners-section -->
    <?php
}

/**
 * Prints the FAQ section.
 */
function aqualuxe_display_faq_section() {
    if (!is_front_page()) {
        return;
    }

    $faq_title = aqualuxe_get_option('faq_title', __('Frequently Asked Questions', 'aqualuxe'));
    $faqs = aqualuxe_get_option('faqs', array());

    if (empty($faqs)) {
        return;
    }

    ?>
    <section class="faq-section">
        <h2 class="section-title"><?php echo esc_html($faq_title); ?></h2>

        <div class="faq-container">
            <?php foreach ($faqs as $faq) : ?>
                <div class="faq-item">
                    <h3 class="faq-question"><?php echo esc_html($faq['question']); ?></h3>
                    <div class="faq-answer">
                        <?php echo wp_kses_post($faq['answer']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section><!-- .faq-section -->
    <?php
}

/**
 * Prints the CTA section.
 */
function aqualuxe_display_cta_section() {
    if (!is_front_page()) {
        return;
    }

    $cta_title = aqualuxe_get_option('cta_title', '');
    $cta_subtitle = aqualuxe_get_option('cta_subtitle', '');
    $cta_button_text = aqualuxe_get_option('cta_button_text', '');
    $cta_button_url = aqualuxe_get_option('cta_button_url', '');
    $cta_background = aqualuxe_get_option('cta_background', '');

    if (empty($cta_title) && empty($cta_subtitle) && empty($cta_button_text)) {
        return;
    }

    $style = '';
    if (!empty($cta_background)) {
        $style = 'style="background-image: url(' . esc_url($cta_background) . ');"';
    }

    ?>
    <section class="cta-section" <?php echo $style; ?>>
        <div class="cta-content">
            <?php if (!empty($cta_title)) : ?>
                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
            <?php endif; ?>

            <?php if (!empty($cta_subtitle)) : ?>
                <p class="cta-subtitle"><?php echo esc_html($cta_subtitle); ?></p>
            <?php endif; ?>

            <?php if (!empty($cta_button_text) && !empty($cta_button_url)) : ?>
                <a href="<?php echo esc_url($cta_button_url); ?>" class="cta-button"><?php echo esc_html($cta_button_text); ?></a>
            <?php endif; ?>
        </div>
    </section><!-- .cta-section -->
    <?php
}

/**
 * Prints the page content.
 */
function aqualuxe_display_page_content() {
    while (have_posts()) {
        the_post();
        get_template_part('templates/content', 'page');
    }
}

/**
 * Prints the post content.
 */
function aqualuxe_display_post_content() {
    while (have_posts()) {
        the_post();
        get_template_part('templates/content', 'single');
    }
}

/**
 * Prints the archive content.
 */
function aqualuxe_display_archive_content() {
    if (have_posts()) {
        ?>
        <div class="archive-posts">
            <?php
            while (have_posts()) {
                the_post();
                get_template_part('templates/content', get_post_type());
            }
            ?>
        </div>

        <?php
        aqualuxe_display_pagination();
    } else {
        get_template_part('templates/content', 'none');
    }
}

/**
 * Prints the search content.
 */
function aqualuxe_display_search_content() {
    if (have_posts()) {
        ?>
        <div class="search-results">
            <?php
            while (have_posts()) {
                the_post();
                get_template_part('templates/content', 'search');
            }
            ?>
        </div>

        <?php
        aqualuxe_display_pagination();
    } else {
        get_template_part('templates/content', 'none');
    }
}

/**
 * Prints the 404 content.
 */
function aqualuxe_display_404_content() {
    ?>
    <div class="error-404 not-found">
        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>

            <?php aqualuxe_display_search_form(); ?>
        </div><!-- .page-content -->
    </div><!-- .error-404 -->
    <?php
}

/**
 * Prints the WooCommerce content.
 */
function aqualuxe_display_woocommerce_content() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_content();
}

/**
 * Prints the WooCommerce shop header.
 */
function aqualuxe_display_shop_header() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop()) {
        return;
    }

    $shop_header_title = aqualuxe_get_option('shop_header_title', '');
    $shop_header_subtitle = aqualuxe_get_option('shop_header_subtitle', '');
    $shop_header_image = aqualuxe_get_option('shop_header_image', '');

    if (empty($shop_header_title) && empty($shop_header_subtitle) && empty($shop_header_image)) {
        return;
    }

    $style = '';
    if (!empty($shop_header_image)) {
        $style = 'style="background-image: url(' . esc_url($shop_header_image) . ');"';
    }

    ?>
    <div class="shop-header" <?php echo $style; ?>>
        <div class="shop-header-content">
            <?php if (!empty($shop_header_title)) : ?>
                <h1 class="shop-header-title"><?php echo esc_html($shop_header_title); ?></h1>
            <?php endif; ?>

            <?php if (!empty($shop_header_subtitle)) : ?>
                <p class="shop-header-subtitle"><?php echo esc_html($shop_header_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div><!-- .shop-header -->
    <?php
}

/**
 * Prints the WooCommerce product categories.
 */
function aqualuxe_display_product_categories() {
    if (!aqualuxe_is_woocommerce_active() || !is_shop()) {
        return;
    }

    $product_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
    ));

    if (empty($product_categories) || is_wp_error($product_categories)) {
        return;
    }

    ?>
    <div class="product-categories">
        <h2 class="section-title"><?php esc_html_e('Product Categories', 'aqualuxe'); ?></h2>

        <div class="categories-grid">
            <?php foreach ($product_categories as $category) : ?>
                <div class="category">
                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-link">
                        <?php
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        if ($thumbnail_id) {
                            echo wp_get_attachment_image($thumbnail_id, 'aqualuxe-product-thumbnail', false, array('class' => 'category-image'));
                        }
                        ?>
                        <h3 class="category-title"><?php echo esc_html($category->name); ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div><!-- .product-categories -->
    <?php
}

/**
 * Prints the WooCommerce product filters.
 */
function aqualuxe_display_product_filters() {
    if (!aqualuxe_is_woocommerce_active() || (!is_shop() && !is_product_category() && !is_product_tag())) {
        return;
    }

    aqualuxe_display_advanced_product_filtering();
}

/**
 * Prints the WooCommerce related products.
 */
function aqualuxe_display_related_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_output_related_products();
}

/**
 * Prints the WooCommerce upsell products.
 */
function aqualuxe_display_upsell_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_upsell_display();
}

/**
 * Prints the WooCommerce cross-sell products.
 */
function aqualuxe_display_cross_sell_products() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }

    woocommerce_cross_sell_display();
}

/**
 * Prints the WooCommerce product tabs.
 */
function aqualuxe_display_product_tabs() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_output_product_data_tabs();
}

/**
 * Prints the WooCommerce product meta.
 */
function aqualuxe_display_product_meta() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_meta();
}

/**
 * Prints the WooCommerce product sharing.
 */
function aqualuxe_display_product_sharing() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_sharing();
}

/**
 * Prints the WooCommerce product price.
 */
function aqualuxe_display_product_price() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_price();
}

/**
 * Prints the WooCommerce product rating.
 */
function aqualuxe_display_product_rating() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_rating();
}

/**
 * Prints the WooCommerce product add to cart.
 */
function aqualuxe_display_product_add_to_cart() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_add_to_cart();
}

/**
 * Prints the WooCommerce product description.
 */
function aqualuxe_display_product_description() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_excerpt();
}

/**
 * Prints the WooCommerce product images.
 */
function aqualuxe_display_product_images() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_show_product_images();
}

/**
 * Prints the WooCommerce product title.
 */
function aqualuxe_display_product_title() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    woocommerce_template_single_title();
}

/**
 * Prints the WooCommerce notices.
 */
function aqualuxe_display_woocommerce_notices() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_output_all_notices();
}

/**
 * Prints the WooCommerce cart.
 */
function aqualuxe_display_cart() {
    if (!aqualuxe_is_woocommerce_active() || !is_cart()) {
        return;
    }

    woocommerce_cart_totals();
}

/**
 * Prints the WooCommerce checkout.
 */
function aqualuxe_display_checkout() {
    if (!aqualuxe_is_woocommerce_active() || !is_checkout()) {
        return;
    }

    woocommerce_checkout_payment();
}

/**
 * Prints the WooCommerce account navigation.
 */
function aqualuxe_display_account_navigation() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page()) {
        return;
    }

    woocommerce_account_navigation();
}

/**
 * Prints the WooCommerce account content.
 */
function aqualuxe_display_account_content() {
    if (!aqualuxe_is_woocommerce_active() || !is_account_page()) {
        return;
    }

    woocommerce_account_content();
}

/**
 * Prints the WooCommerce order tracking.
 */
function aqualuxe_display_order_tracking() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_order_tracking_form();
}

/**
 * Prints the WooCommerce mini cart.
 */
function aqualuxe_display_mini_cart() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_mini_cart();
}

/**
 * Prints the WooCommerce product search.
 */
function aqualuxe_display_product_search() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    get_product_search_form();
}

/**
 * Prints the WooCommerce product categories menu.
 */
function aqualuxe_display_product_categories_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $args = array(
        'taxonomy' => 'product_cat',
        'title_li' => '',
        'show_count' => true,
        'hierarchical' => true,
    );

    echo '<ul class="product-categories-menu">';
    wp_list_categories($args);
    echo '</ul>';
}

/**
 * Prints the WooCommerce product tags menu.
 */
function aqualuxe_display_product_tags_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $args = array(
        'taxonomy' => 'product_tag',
        'title_li' => '',
        'show_count' => true,
    );

    echo '<ul class="product-tags-menu">';
    wp_list_categories($args);
    echo '</ul>';
}

/**
 * Prints the WooCommerce products by attribute menu.
 */
function aqualuxe_display_products_by_attribute_menu($attribute) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $args = array(
        'taxonomy' => 'pa_' . $attribute,
        'title_li' => '',
        'show_count' => true,
    );

    echo '<ul class="products-by-attribute-menu">';
    wp_list_categories($args);
    echo '</ul>';
}

/**
 * Prints the WooCommerce products by price menu.
 */
function aqualuxe_display_products_by_price_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    global $wpdb;

    $prices = $wpdb->get_row("
        SELECT MIN(min_price) as min_price, MAX(max_price) as max_price
        FROM {$wpdb->wc_product_meta_lookup}
    ");

    $min_price = floor($prices->min_price);
    $max_price = ceil($prices->max_price);

    $step = ceil(($max_price - $min_price) / 5);

    echo '<ul class="products-by-price-menu">';

    for ($i = $min_price; $i < $max_price; $i += $step) {
        $next = $i + $step;
        $url = add_query_arg(array(
            'min_price' => $i,
            'max_price' => $next,
        ), wc_get_page_permalink('shop'));

        echo '<li><a href="' . esc_url($url) . '">' . wc_price($i) . ' - ' . wc_price($next) . '</a></li>';
    }

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by rating menu.
 */
function aqualuxe_display_products_by_rating_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-rating-menu">';

    for ($i = 5; $i >= 1; $i--) {
        $url = add_query_arg(array(
            'rating_filter' => $i,
        ), wc_get_page_permalink('shop'));

        echo '<li><a href="' . esc_url($url) . '">' . $i . ' ' . esc_html__('stars and up', 'aqualuxe') . '</a></li>';
    }

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by stock menu.
 */
function aqualuxe_display_products_by_stock_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-stock-menu">';

    $url = add_query_arg(array(
        'stock_status' => 'instock',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('In Stock', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'stock_status' => 'outofstock',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Out of Stock', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'stock_status' => 'onbackorder',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('On Backorder', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by featured menu.
 */
function aqualuxe_display_products_by_featured_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-featured-menu">';

    $url = add_query_arg(array(
        'featured' => 'yes',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Featured Products', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'featured' => 'no',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Non-Featured Products', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by sale menu.
 */
function aqualuxe_display_products_by_sale_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-sale-menu">';

    $url = add_query_arg(array(
        'on_sale' => 'yes',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('On Sale', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'on_sale' => 'no',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Regular Price', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by shipping class menu.
 */
function aqualuxe_display_products_by_shipping_class_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $args = array(
        'taxonomy' => 'product_shipping_class',
        'title_li' => '',
        'show_count' => true,
    );

    echo '<ul class="products-by-shipping-class-menu">';
    wp_list_categories($args);
    echo '</ul>';
}

/**
 * Prints the WooCommerce products by vendor menu.
 */
function aqualuxe_display_products_by_vendor_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $vendors = get_users(array(
        'role__in' => array('administrator', 'shop_manager'),
        'fields' => array('ID', 'display_name'),
    ));

    if (empty($vendors)) {
        return;
    }

    echo '<ul class="products-by-vendor-menu">';

    foreach ($vendors as $vendor) {
        $url = add_query_arg(array(
            'vendor' => $vendor->ID,
        ), wc_get_page_permalink('shop'));

        echo '<li><a href="' . esc_url($url) . '">' . esc_html($vendor->display_name) . '</a></li>';
    }

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by new menu.
 */
function aqualuxe_display_products_by_new_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-new-menu">';

    $url = add_query_arg(array(
        'orderby' => 'date',
        'order' => 'desc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Newest First', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'orderby' => 'date',
        'order' => 'asc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Oldest First', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by popularity menu.
 */
function aqualuxe_display_products_by_popularity_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-popularity-menu">';

    $url = add_query_arg(array(
        'orderby' => 'popularity',
        'order' => 'desc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Most Popular', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'orderby' => 'popularity',
        'order' => 'asc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Least Popular', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by price menu.
 */
function aqualuxe_display_products_by_price_order_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-price-order-menu">';

    $url = add_query_arg(array(
        'orderby' => 'price',
        'order' => 'asc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Price: Low to High', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'orderby' => 'price',
        'order' => 'desc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Price: High to Low', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products by rating order menu.
 */
function aqualuxe_display_products_by_rating_order_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<ul class="products-by-rating-order-menu">';

    $url = add_query_arg(array(
        'orderby' => 'rating',
        'order' => 'desc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Rating: High to Low', 'aqualuxe') . '</a></li>';

    $url = add_query_arg(array(
        'orderby' => 'rating',
        'order' => 'asc',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($url) . '">' . esc_html__('Rating: Low to High', 'aqualuxe') . '</a></li>';

    echo '</ul>';
}

/**
 * Prints the WooCommerce products ordering.
 */
function aqualuxe_display_products_ordering() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_catalog_ordering();
}

/**
 * Prints the WooCommerce products result count.
 */
function aqualuxe_display_products_result_count() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_result_count();
}

/**
 * Prints the WooCommerce products pagination.
 */
function aqualuxe_display_products_pagination() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    woocommerce_pagination();
}

/**
 * Prints the WooCommerce products per page.
 */
function aqualuxe_display_products_per_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $per_page = aqualuxe_get_option('products_per_page', 12);
    $options = array(12, 24, 36, 48, 60);

    echo '<div class="products-per-page">';
    echo '<span>' . esc_html__('Show:', 'aqualuxe') . '</span>';
    echo '<ul>';

    foreach ($options as $option) {
        $url = add_query_arg(array(
            'per_page' => $option,
        ), wc_get_page_permalink('shop'));

        echo '<li><a href="' . esc_url($url) . '" class="' . ($per_page == $option ? 'active' : '') . '">' . esc_html($option) . '</a></li>';
    }

    echo '</ul>';
    echo '</div>';
}

/**
 * Prints the WooCommerce products view mode.
 */
function aqualuxe_display_products_view_mode() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    $view_mode = isset($_GET['view_mode']) ? sanitize_text_field($_GET['view_mode']) : 'grid';

    echo '<div class="products-view-mode">';
    echo '<span>' . esc_html__('View as:', 'aqualuxe') . '</span>';
    echo '<ul>';

    $grid_url = add_query_arg(array(
        'view_mode' => 'grid',
    ), wc_get_page_permalink('shop'));

    $list_url = add_query_arg(array(
        'view_mode' => 'list',
    ), wc_get_page_permalink('shop'));

    echo '<li><a href="' . esc_url($grid_url) . '" class="' . ($view_mode == 'grid' ? 'active' : '') . '">' . aqualuxe_get_icon('grid', array('class' => 'view-mode-icon')) . '</a></li>';
    echo '<li><a href="' . esc_url($list_url) . '" class="' . ($view_mode == 'list' ? 'active' : '') . '">' . aqualuxe_get_icon('list', array('class' => 'view-mode-icon')) . '</a></li>';

    echo '</ul>';
    echo '</div>';
}

/**
 * Prints the WooCommerce products filter toggle.
 */
function aqualuxe_display_products_filter_toggle() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<button id="products-filter-toggle" class="products-filter-toggle">';
    echo aqualuxe_get_icon('filter', array('class' => 'filter-icon'));
    echo '<span>' . esc_html__('Filter', 'aqualuxe') . '</span>';
    echo '</button>';
}

/**
 * Prints the WooCommerce active filters.
 */
function aqualuxe_display_active_filters() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    the_widget('WC_Widget_Layered_Nav_Filters');
}