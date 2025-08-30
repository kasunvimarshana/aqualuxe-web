<?php
/**
 * Theme hooks system
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Header hooks
 */

/**
 * Hook: aqualuxe_before_header
 *
 * @hooked aqualuxe_skip_links - 10
 */
function aqualuxe_skip_links() {
    ?>
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>
    <?php
}
add_action('aqualuxe_before_header', 'aqualuxe_skip_links', 10);

/**
 * Hook: aqualuxe_header
 *
 * @hooked aqualuxe_header_top_bar - 10
 * @hooked aqualuxe_header_main - 20
 * @hooked aqualuxe_header_mobile_menu - 30
 */
function aqualuxe_header_top_bar() {
    if (!aqualuxe_is_top_bar_enabled()) {
        return;
    }
    ?>
    <div id="top-bar" class="top-bar bg-primary-900 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="top-bar-left flex items-center space-x-4">
                    <?php if (aqualuxe_get_theme_option('aqualuxe_contact_phone')) : ?>
                        <div class="top-bar-phone">
                            <a href="tel:<?php echo esc_attr(aqualuxe_get_theme_option('aqualuxe_contact_phone')); ?>" class="flex items-center text-sm">
                                <?php aqualuxe_svg_icon('phone', array('class' => 'w-4 h-4 mr-1')); ?>
                                <?php echo esc_html(aqualuxe_get_theme_option('aqualuxe_contact_phone')); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (aqualuxe_get_theme_option('aqualuxe_contact_email')) : ?>
                        <div class="top-bar-email">
                            <a href="mailto:<?php echo esc_attr(aqualuxe_get_theme_option('aqualuxe_contact_email')); ?>" class="flex items-center text-sm">
                                <?php aqualuxe_svg_icon('mail', array('class' => 'w-4 h-4 mr-1')); ?>
                                <?php echo esc_html(aqualuxe_get_theme_option('aqualuxe_contact_email')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="top-bar-right flex items-center space-x-4">
                    <?php if (aqualuxe_is_multilingual_enabled()) : ?>
                        <div class="language-switcher">
                            <?php aqualuxe_language_switcher(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (aqualuxe_is_multi_currency_enabled() && aqualuxe_is_woocommerce_active()) : ?>
                        <div class="currency-switcher">
                            <?php aqualuxe_currency_switcher(); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (has_nav_menu('top-bar')) : ?>
                        <nav id="top-bar-navigation" class="top-bar-navigation">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'top-bar',
                                    'menu_id'        => 'top-bar-menu',
                                    'container'      => false,
                                    'menu_class'     => 'flex items-center space-x-4 text-sm',
                                    'depth'          => 1,
                                    'fallback_cb'    => false,
                                )
                            );
                            ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_header', 'aqualuxe_header_top_bar', 10);

function aqualuxe_header_main() {
    $header_class = 'site-header bg-white';
    if (aqualuxe_get_theme_option('aqualuxe_sticky_header', true)) {
        $header_class .= ' sticky top-0 z-30';
    }
    ?>
    <header id="masthead" class="<?php echo esc_attr($header_class); ?>">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="site-branding flex items-center">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                    ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                        </h1>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation hidden lg:block">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'menu_class'     => 'flex items-center space-x-6',
                            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                        )
                    );
                    ?>
                </nav><!-- #site-navigation -->

                <div class="header-actions flex items-center space-x-4">
                    <?php if (aqualuxe_is_dark_mode_enabled()) : ?>
                        <button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
                            <?php aqualuxe_svg_icon('moon', array('class' => 'w-5 h-5 dark-icon')); ?>
                            <?php aqualuxe_svg_icon('sun', array('class' => 'w-5 h-5 light-icon')); ?>
                        </button>
                    <?php endif; ?>

                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <div class="header-search relative">
                            <button id="search-toggle" class="search-toggle" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
                                <?php aqualuxe_svg_icon('search', array('class' => 'w-5 h-5')); ?>
                            </button>
                            <div id="header-search-form" class="header-search-form hidden absolute right-0 top-full mt-2 w-72 bg-white shadow-lg rounded-lg p-4 z-50">
                                <?php get_product_search_form(); ?>
                            </div>
                        </div>

                        <?php if (function_exists('aqualuxe_header_account')) : ?>
                            <?php aqualuxe_header_account(); ?>
                        <?php endif; ?>

                        <?php if (function_exists('aqualuxe_header_wishlist')) : ?>
                            <?php aqualuxe_header_wishlist(); ?>
                        <?php endif; ?>

                        <?php if (function_exists('aqualuxe_header_cart')) : ?>
                            <?php aqualuxe_header_cart(); ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                        <?php aqualuxe_svg_icon('menu', array('class' => 'w-6 h-6')); ?>
                    </button>
                </div><!-- .header-actions -->
            </div>
        </div>
    </header><!-- #masthead -->
    <?php
}
add_action('aqualuxe_header', 'aqualuxe_header_main', 20);

function aqualuxe_header_mobile_menu() {
    ?>
    <div id="mobile-menu" class="mobile-menu hidden lg:hidden">
        <div class="container mx-auto px-4 py-4">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-items',
                    'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                )
            );
            ?>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_header', 'aqualuxe_header_mobile_menu', 30);

/**
 * Hook: aqualuxe_after_header
 *
 * @hooked aqualuxe_breadcrumbs - 10
 */
function aqualuxe_breadcrumbs() {
    if (!aqualuxe_get_theme_option('aqualuxe_enable_breadcrumbs', true) || is_front_page()) {
        return;
    }
    ?>
    <div class="breadcrumbs-container bg-gray-100 py-2">
        <div class="container mx-auto px-4">
            <?php
            if (function_exists('aqualuxe_get_breadcrumbs')) {
                echo aqualuxe_get_breadcrumbs();
            }
            ?>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_after_header', 'aqualuxe_breadcrumbs', 10);

/**
 * Footer hooks
 */

/**
 * Hook: aqualuxe_before_footer
 *
 * @hooked aqualuxe_footer_newsletter - 10
 */
function aqualuxe_footer_newsletter() {
    if (!aqualuxe_get_theme_option('aqualuxe_footer_newsletter', false)) {
        return;
    }

    $newsletter_shortcode = aqualuxe_get_theme_option('aqualuxe_newsletter_shortcode', '');
    if (empty($newsletter_shortcode)) {
        return;
    }
    ?>
    <div class="footer-newsletter bg-primary-800 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto text-center">
                <h3 class="text-2xl font-bold text-white mb-2"><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h3>
                <p class="text-white opacity-80 mb-6"><?php esc_html_e('Stay updated with our latest news and special offers.', 'aqualuxe'); ?></p>
                <?php echo do_shortcode($newsletter_shortcode); ?>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_before_footer', 'aqualuxe_footer_newsletter', 10);

/**
 * Hook: aqualuxe_footer
 *
 * @hooked aqualuxe_footer_widgets - 10
 * @hooked aqualuxe_footer_navigation - 20
 * @hooked aqualuxe_footer_payment_icons - 30
 * @hooked aqualuxe_footer_social_icons - 40
 * @hooked aqualuxe_footer_info - 50
 * @hooked aqualuxe_footer_back_to_top - 60
 */
function aqualuxe_footer_widgets() {
    if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
        return;
    }

    $footer_style = aqualuxe_get_theme_option('aqualuxe_footer_style', 'default');
    $grid_class = 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';

    if ($footer_style === 'three-col') {
        $grid_class = 'grid-cols-1 md:grid-cols-3';
    } elseif ($footer_style === 'two-col') {
        $grid_class = 'grid-cols-1 md:grid-cols-2';
    }
    ?>
    <div class="footer-widgets grid <?php echo esc_attr($grid_class); ?> gap-8 mb-8">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widget-1">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-2')) : ?>
            <div class="footer-widget-2">
                <?php dynamic_sidebar('footer-2'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-3') && $footer_style !== 'two-col') : ?>
            <div class="footer-widget-3">
                <?php dynamic_sidebar('footer-3'); ?>
            </div>
        <?php endif; ?>

        <?php if (is_active_sidebar('footer-4') && $footer_style === 'default') : ?>
            <div class="footer-widget-4">
                <?php dynamic_sidebar('footer-4'); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);

function aqualuxe_footer_navigation() {
    if (!has_nav_menu('footer')) {
        return;
    }
    ?>
    <div class="footer-navigation mb-6">
        <nav class="footer-nav">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-wrap justify-center space-x-6',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav>
    </div>
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_navigation', 20);

function aqualuxe_footer_payment_icons() {
    if (!aqualuxe_get_theme_option('aqualuxe_footer_payment_icons', true) || !aqualuxe_is_woocommerce_active()) {
        return;
    }
    ?>
    <div class="payment-methods flex justify-center mb-6">
        <?php
        $payment_methods = array(
            'visa'       => __('Visa', 'aqualuxe'),
            'mastercard' => __('Mastercard', 'aqualuxe'),
            'amex'       => __('American Express', 'aqualuxe'),
            'discover'   => __('Discover', 'aqualuxe'),
            'paypal'     => __('PayPal', 'aqualuxe'),
            'apple-pay'  => __('Apple Pay', 'aqualuxe'),
            'google-pay' => __('Google Pay', 'aqualuxe'),
        );

        foreach ($payment_methods as $method => $label) {
            echo '<span class="payment-icon ' . esc_attr($method) . '" aria-label="' . esc_attr($label) . '">';
            aqualuxe_svg_icon($method, array('class' => 'w-10 h-6'));
            echo '</span>';
        }
        ?>
    </div>
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_payment_icons', 30);

function aqualuxe_footer_social_icons() {
    if (!aqualuxe_get_theme_option('aqualuxe_footer_social_icons', true)) {
        return;
    }

    $social_position = aqualuxe_get_theme_option('aqualuxe_social_icons_position', 'both');
    if ($social_position === 'header' || $social_position === 'none') {
        return;
    }
    ?>
    <div class="social-icons flex justify-center mb-6">
        <?php aqualuxe_social_icons(); ?>
    </div>
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_social_icons', 40);

function aqualuxe_footer_info() {
    $copyright_text = aqualuxe_get_theme_option('aqualuxe_footer_copyright', '© {year} AquaLuxe. All rights reserved.');
    if ($copyright_text) {
        $copyright_text = str_replace('{year}', date('Y'), $copyright_text);
    } else {
        $copyright_text = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name, %3$s: WordPress */
            __('© %1$s %2$s. All rights reserved. Powered by %3$s', 'aqualuxe'),
            date('Y'),
            '<a href="' . esc_url(home_url('/')) . '">' . esc_html(get_bloginfo('name')) . '</a>',
            '<a href="https://wordpress.org/">WordPress</a>'
        );
    }
    ?>
    <div class="site-info text-center text-sm opacity-80">
        <?php echo wp_kses_post($copyright_text); ?>
    </div><!-- .site-info -->
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_info', 50);

function aqualuxe_footer_back_to_top() {
    if (!aqualuxe_get_theme_option('aqualuxe_back_to_top', true)) {
        return;
    }
    ?>
    <div class="back-to-top text-center mt-4">
        <button id="back-to-top-btn" class="back-to-top-btn inline-flex items-center justify-center p-2 rounded-full bg-primary-700 hover:bg-primary-600 transition-colors">
            <?php aqualuxe_svg_icon('arrow-up', array('class' => 'w-5 h-5')); ?>
            <span class="sr-only"><?php esc_html_e('Back to top', 'aqualuxe'); ?></span>
        </button>
    </div>
    <?php
}
add_action('aqualuxe_footer', 'aqualuxe_footer_back_to_top', 60);

/**
 * Hook: aqualuxe_after_footer
 *
 * @hooked aqualuxe_woocommerce_modals - 10
 */
function aqualuxe_woocommerce_modals() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    // Quick view modal
    if (aqualuxe_get_theme_option('aqualuxe_quick_view', true) && function_exists('aqualuxe_quick_view_modal')) {
        aqualuxe_quick_view_modal();
    }

    // Cart drawer
    if (aqualuxe_get_theme_option('aqualuxe_cart_drawer', true) && function_exists('aqualuxe_cart_drawer')) {
        aqualuxe_cart_drawer();
    }
}
add_action('aqualuxe_after_footer', 'aqualuxe_woocommerce_modals', 10);

/**
 * Content hooks
 */

/**
 * Hook: aqualuxe_before_main_content
 *
 * @hooked aqualuxe_page_header - 10
 */
function aqualuxe_page_header() {
    if (is_front_page() || is_singular() || is_404()) {
        return;
    }

    $title = '';
    $description = '';

    if (is_home()) {
        $title = get_the_title(get_option('page_for_posts'));
    } elseif (is_archive()) {
        $title = get_the_archive_title();
        $description = get_the_archive_description();
    } elseif (is_search()) {
        /* translators: %s: search query. */
        $title = sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
    }

    if (empty($title)) {
        return;
    }
    ?>
    <header class="page-header mb-8">
        <h1 class="page-title text-3xl font-bold"><?php echo wp_kses_post($title); ?></h1>
        <?php if ($description) : ?>
            <div class="archive-description mt-4 text-gray-600"><?php echo wp_kses_post($description); ?></div>
        <?php endif; ?>
    </header><!-- .page-header -->
    <?php
}
add_action('aqualuxe_before_main_content', 'aqualuxe_page_header', 10);

/**
 * Hook: aqualuxe_after_main_content
 */

/**
 * Hook: aqualuxe_entry_header
 *
 * @hooked aqualuxe_post_thumbnail - 10
 * @hooked aqualuxe_entry_header_title - 20
 * @hooked aqualuxe_entry_meta - 30
 */
function aqualuxe_post_thumbnail() {
    if (!has_post_thumbnail() || is_singular()) {
        return;
    }

    $thumbnail_size = 'aqualuxe-featured';
    $blog_featured_image = aqualuxe_get_theme_option('aqualuxe_blog_featured_image', 'large');

    if ($blog_featured_image === 'medium') {
        $thumbnail_size = 'medium';
    } elseif ($blog_featured_image === 'thumbnail') {
        $thumbnail_size = 'thumbnail';
    } elseif ($blog_featured_image === 'none') {
        return;
    }
    ?>
    <div class="entry-thumbnail mb-4">
        <a href="<?php the_permalink(); ?>" class="block overflow-hidden rounded-lg">
            <?php the_post_thumbnail($thumbnail_size, array('class' => 'w-full h-auto hover:scale-105 transition-transform duration-300')); ?>
        </a>
    </div>
    <?php
}
add_action('aqualuxe_entry_header', 'aqualuxe_post_thumbnail', 10);

function aqualuxe_entry_header_title() {
    if (is_singular()) {
        the_title('<h1 class="entry-title text-3xl font-bold">', '</h1>');
    } else {
        the_title('<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-600 transition-colors">', '</a></h2>');
    }
}
add_action('aqualuxe_entry_header', 'aqualuxe_entry_header_title', 20);

function aqualuxe_entry_meta() {
    if ('post' !== get_post_type()) {
        return;
    }
    ?>
    <div class="entry-meta text-sm text-gray-600 mt-2">
        <?php
        if (aqualuxe_get_theme_option('aqualuxe_show_post_date', true)) {
            aqualuxe_posted_on();
        }
        
        if (aqualuxe_get_theme_option('aqualuxe_show_post_author', true)) {
            aqualuxe_posted_by();
        }

        if (aqualuxe_get_theme_option('aqualuxe_show_post_comments', true) && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link ml-4">';
            aqualuxe_svg_icon('comment', array('class' => 'w-4 h-4 mr-1 inline-block'));
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
        ?>
    </div><!-- .entry-meta -->
    <?php
}
add_action('aqualuxe_entry_header', 'aqualuxe_entry_meta', 30);

/**
 * Hook: aqualuxe_entry_content
 *
 * @hooked aqualuxe_entry_content_output - 10
 */
function aqualuxe_entry_content_output() {
    if (is_singular()) {
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after'  => '</div>',
            )
        );
    } else {
        the_excerpt();
        ?>
        <div class="read-more mt-4">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <?php aqualuxe_svg_icon('arrow-right', array('class' => 'w-4 h-4 ml-1')); ?>
            </a>
        </div>
        <?php
    }
}
add_action('aqualuxe_entry_content', 'aqualuxe_entry_content_output', 10);

/**
 * Hook: aqualuxe_entry_footer
 *
 * @hooked aqualuxe_entry_footer_meta - 10
 * @hooked aqualuxe_post_navigation - 20
 * @hooked aqualuxe_post_author_bio - 30
 * @hooked aqualuxe_post_share_buttons - 40
 * @hooked aqualuxe_related_posts - 50
 */
function aqualuxe_entry_footer_meta() {
    if ('post' !== get_post_type()) {
        return;
    }

    $show_categories = aqualuxe_get_theme_option('aqualuxe_show_post_categories', true);
    $show_tags = aqualuxe_get_theme_option('aqualuxe_show_post_tags', true);

    if (!$show_categories && !$show_tags) {
        return;
    }
    ?>
    <footer class="entry-footer mt-4">
        <?php
        if ($show_categories) {
            $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links flex items-center text-sm mb-2">' . aqualuxe_get_svg_icon('folder', array('class' => 'w-4 h-4 mr-1')) . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if ($show_tags) {
            $tags_list = get_the_tag_list('', esc_html__(', ', 'aqualuxe'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links flex items-center text-sm">' . aqualuxe_get_svg_icon('tag', array('class' => 'w-4 h-4 mr-1')) . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
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
            '<span class="edit-link flex items-center text-sm mt-2">' . aqualuxe_get_svg_icon('edit', array('class' => 'w-4 h-4 mr-1')),
            '</span>'
        );
        ?>
    </footer><!-- .entry-footer -->
    <?php
}
add_action('aqualuxe_entry_footer', 'aqualuxe_entry_footer_meta', 10);

function aqualuxe_post_navigation() {
    if (!is_singular('post') || !aqualuxe_get_theme_option('aqualuxe_post_navigation', true)) {
        return;
    }

    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if (!$prev_post && !$next_post) {
        return;
    }
    ?>
    <nav class="post-navigation my-8 border-t border-b border-gray-200 py-4">
        <div class="flex flex-wrap justify-between">
            <?php if ($prev_post) : ?>
                <div class="post-navigation-prev mb-4 md:mb-0">
                    <span class="text-sm text-gray-500 block mb-1"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="font-medium hover:text-primary-600 transition-colors">
                        <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($next_post) : ?>
                <div class="post-navigation-next text-right">
                    <span class="text-sm text-gray-500 block mb-1"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="font-medium hover:text-primary-600 transition-colors">
                        <?php echo esc_html(get_the_title($next_post->ID)); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}
add_action('aqualuxe_entry_footer', 'aqualuxe_post_navigation', 20);

function aqualuxe_post_author_bio() {
    if (!is_singular('post') || !aqualuxe_get_theme_option('aqualuxe_author_bio', true)) {
        return;
    }

    $author_id = get_the_author_meta('ID');
    if (!$author_id) {
        return;
    }

    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 96, '', $author_name, array('class' => 'rounded-full'));

    if (empty($author_description)) {
        return;
    }
    ?>
    <div class="author-bio mt-8 pt-6 border-t border-gray-200">
        <div class="flex items-start">
            <?php if ($author_avatar) : ?>
                <div class="author-avatar mr-4">
                    <a href="<?php echo esc_url($author_url); ?>">
                        <?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="author-info">
                <h3 class="author-name text-lg font-bold mb-2">
                    <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
                </h3>
                <div class="author-description text-gray-600">
                    <?php echo wpautop($author_description); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <a href="<?php echo esc_url($author_url); ?>" class="author-link inline-flex items-center mt-2 text-primary-600 hover:text-primary-700 font-medium">
                    <?php
                    /* translators: %s: Author name */
                    printf(esc_html__('View all posts by %s', 'aqualuxe'), esc_html($author_name));
                    ?>
                    <?php aqualuxe_svg_icon('arrow-right', array('class' => 'w-4 h-4 ml-1')); ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_entry_footer', 'aqualuxe_post_author_bio', 30);

function aqualuxe_post_share_buttons() {
    if (!is_singular('post') || !aqualuxe_get_theme_option('aqualuxe_social_sharing', true)) {
        return;
    }

    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large'));
    ?>
    <div class="post-share-buttons mt-6">
        <h3 class="text-lg font-medium mb-3"><?php esc_html_e('Share this post', 'aqualuxe'); ?></h3>
        <div class="flex space-x-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" target="_blank" rel="noopener noreferrer" class="social-share-button facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('facebook', array('class' => 'w-5 h-5')); ?>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-share-button twitter" aria-label="<?php esc_attr_e('Share on Twitter', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('twitter', array('class' => 'w-5 h-5')); ?>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $post_url; ?>&title=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-share-button linkedin" aria-label="<?php esc_attr_e('Share on LinkedIn', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('linkedin', array('class' => 'w-5 h-5')); ?>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo $post_url; ?>&media=<?php echo $post_thumbnail; ?>&description=<?php echo $post_title; ?>" target="_blank" rel="noopener noreferrer" class="social-share-button pinterest" aria-label="<?php esc_attr_e('Share on Pinterest', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('pinterest', array('class' => 'w-5 h-5')); ?>
            </a>
            <a href="mailto:?subject=<?php echo $post_title; ?>&body=<?php echo $post_url; ?>" class="social-share-button email" aria-label="<?php esc_attr_e('Share via Email', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('mail', array('class' => 'w-5 h-5')); ?>
            </a>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_entry_footer', 'aqualuxe_post_share_buttons', 40);

function aqualuxe_related_posts() {
    if (!is_singular('post') || !aqualuxe_get_theme_option('aqualuxe_related_posts', true)) {
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

    $related_posts = new WP_Query($args);

    if (!$related_posts->have_posts()) {
        return;
    }
    ?>
    <div class="related-posts mt-8 pt-6 border-t border-gray-200">
        <h3 class="text-xl font-bold mb-6"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            while ($related_posts->have_posts()) :
                $related_posts->the_post();
                ?>
                <article class="related-post bg-white rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md">
                    <?php if (has_post_thumbnail()) : ?>
                        <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                            <?php the_post_thumbnail('aqualuxe-blog-thumbnail', array('class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300')); ?>
                        </a>
                    <?php endif; ?>
                    <div class="p-4">
                        <h4 class="text-lg font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors"><?php the_title(); ?></a>
                        </h4>
                        <div class="text-sm text-gray-600 mb-2">
                            <?php aqualuxe_posted_on(); ?>
                        </div>
                        <div class="text-sm text-gray-700">
                            <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}
add_action('aqualuxe_entry_footer', 'aqualuxe_related_posts', 50);

/**
 * Hook: aqualuxe_comments
 *
 * @hooked aqualuxe_comments_template - 10
 */
function aqualuxe_comments_template() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_template', 10);

/**
 * WooCommerce hooks
 */

/**
 * Hook: aqualuxe_before_shop_loop
 *
 * @hooked aqualuxe_shop_filters_button - 10
 */
function aqualuxe_shop_filters_button() {
    if (!aqualuxe_is_woocommerce_active() || !is_active_sidebar('shop-sidebar') || aqualuxe_get_theme_option('aqualuxe_shop_sidebar', 'right') === 'none') {
        return;
    }
    ?>
    <button class="filter-toggle lg:hidden inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 mb-4">
        <?php aqualuxe_svg_icon('filter', array('class' => 'w-4 h-4 mr-2')); ?>
        <?php esc_html_e('Filter', 'aqualuxe'); ?>
    </button>
    <?php
}
add_action('aqualuxe_before_shop_loop', 'aqualuxe_shop_filters_button', 10);

/**
 * Hook: aqualuxe_after_shop_loop
 */

/**
 * Hook: aqualuxe_before_single_product
 */

/**
 * Hook: aqualuxe_after_single_product
 */

/**
 * Helper functions
 */

/**
 * Check if top bar is enabled
 *
 * @return bool
 */
function aqualuxe_is_top_bar_enabled() {
    return aqualuxe_get_theme_option('aqualuxe_enable_top_bar', true);
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_enabled() {
    return aqualuxe_get_theme_option('aqualuxe_enable_dark_mode', true);
}

/**
 * Check if multilingual support is enabled
 *
 * @return bool
 */
function aqualuxe_is_multilingual_enabled() {
    // Check if WPML or Polylang is active
    if (function_exists('icl_object_id') || function_exists('pll_current_language')) {
        return true;
    }
    return false;
}

/**
 * Check if multi-currency support is enabled
 *
 * @return bool
 */
function aqualuxe_is_multi_currency_enabled() {
    return aqualuxe_get_theme_option('aqualuxe_multi_currency', false);
}

/**
 * Check if page is full width
 *
 * @return bool
 */
function aqualuxe_is_page_fullwidth() {
    $page_layout = aqualuxe_get_theme_option('aqualuxe_page_layout', 'default');
    
    if ($page_layout === 'full-width') {
        return true;
    }
    
    // Check for page template
    if (is_page_template('templates/template-full-width.php')) {
        return true;
    }
    
    // Check for page meta
    $page_layout_meta = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true);
    if ($page_layout_meta === 'full-width') {
        return true;
    }
    
    return false;
}

/**
 * Check if page has header image
 *
 * @return bool
 */
function aqualuxe_is_page_header_image() {
    return get_post_meta(get_the_ID(), '_aqualuxe_header_image', true) ? true : false;
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
 * Language switcher
 */
function aqualuxe_language_switcher() {
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (!empty($languages)) {
            $current_lang = '';
            
            foreach ($languages as $lang) {
                if ($lang['active']) {
                    $current_lang = $lang;
                    break;
                }
            }
            
            if ($current_lang) {
                echo '<div class="language-switcher relative">';
                echo '<div class="current-language flex items-center cursor-pointer">';
                if ($current_lang['country_flag_url']) {
                    echo '<img src="' . esc_url($current_lang['country_flag_url']) . '" alt="' . esc_attr($current_lang['language_code']) . '" class="w-4 h-4 mr-1">';
                }
                echo esc_html($current_lang['language_code']);
                echo '</div>';
                
                echo '<div class="language-dropdown hidden">';
                foreach ($languages as $lang) {
                    if (!$lang['active']) {
                        echo '<a href="' . esc_url($lang['url']) . '">';
                        if ($lang['country_flag_url']) {
                            echo '<img src="' . esc_url($lang['country_flag_url']) . '" alt="' . esc_attr($lang['language_code']) . '" class="w-4 h-4 mr-1">';
                        }
                        echo esc_html($lang['native_name']);
                        echo '</a>';
                    }
                }
                echo '</div>';
                echo '</div>';
            }
        }
    } elseif (function_exists('pll_the_languages')) {
        $args = array(
            'dropdown'   => 1,
            'show_flags' => 1,
            'show_names' => 1,
        );
        pll_the_languages($args);
    }
}

/**
 * Currency switcher
 */
function aqualuxe_currency_switcher() {
    // This is a placeholder function that would be implemented based on the specific multi-currency plugin used
    // For example, if using WooCommerce Multi-Currency or WPML WooCommerce Multi-Currency
    
    // Example implementation:
    $currencies = array(
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
    );
    
    $current_currency = 'USD'; // This would be dynamically determined
    
    echo '<div class="currency-switcher relative">';
    echo '<div class="current-currency flex items-center cursor-pointer">';
    echo esc_html($currencies[$current_currency] . ' ' . $current_currency);
    echo '</div>';
    
    echo '<div class="currency-dropdown hidden">';
    foreach ($currencies as $code => $symbol) {
        if ($code !== $current_currency) {
            echo '<a href="#" data-currency="' . esc_attr($code) . '">';
            echo esc_html($symbol . ' ' . $code);
            echo '</a>';
        }
    }
    echo '</div>';
    echo '</div>';
}

/**
 * Social icons
 */
function aqualuxe_social_icons() {
    $social_networks = array(
        'facebook'  => aqualuxe_get_theme_option('aqualuxe_social_facebook', ''),
        'twitter'   => aqualuxe_get_theme_option('aqualuxe_social_twitter', ''),
        'instagram' => aqualuxe_get_theme_option('aqualuxe_social_instagram', ''),
        'linkedin'  => aqualuxe_get_theme_option('aqualuxe_social_linkedin', ''),
        'youtube'   => aqualuxe_get_theme_option('aqualuxe_social_youtube', ''),
        'pinterest' => aqualuxe_get_theme_option('aqualuxe_social_pinterest', ''),
    );
    
    foreach ($social_networks as $network => $url) {
        if ($url) {
            echo '<a href="' . esc_url($url) . '" class="social-icon ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr(ucfirst($network)) . '">';
            aqualuxe_svg_icon($network, array('class' => 'w-5 h-5'));
            echo '</a>';
        }
    }
}

/**
 * Payment icons
 */
function aqualuxe_payment_icons() {
    $payment_methods = array(
        'visa'       => __('Visa', 'aqualuxe'),
        'mastercard' => __('Mastercard', 'aqualuxe'),
        'amex'       => __('American Express', 'aqualuxe'),
        'discover'   => __('Discover', 'aqualuxe'),
        'paypal'     => __('PayPal', 'aqualuxe'),
        'apple-pay'  => __('Apple Pay', 'aqualuxe'),
        'google-pay' => __('Google Pay', 'aqualuxe'),
    );

    foreach ($payment_methods as $method => $label) {
        echo '<span class="payment-icon ' . esc_attr($method) . '" aria-label="' . esc_attr($label) . '">';
        aqualuxe_svg_icon($method, array('class' => 'w-10 h-6'));
        echo '</span>';
    }
}

/**
 * SVG icon helper function
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 */
function aqualuxe_svg_icon($icon, $args = array()) {
    echo aqualuxe_get_svg_icon($icon, $args); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 * @return string SVG icon.
 */
function aqualuxe_get_svg_icon($icon, $args = array()) {
    $defaults = array(
        'class' => '',
        'aria-hidden' => 'true',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $svg = '';
    
    switch ($icon) {
        case 'arrow-right':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
            break;
        case 'arrow-up':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>';
            break;
        case 'phone':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
            break;
        case 'mail':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
            break;
        case 'search':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
            break;
        case 'menu':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
            break;
        case 'close':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
            break;
        case 'moon':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
            break;
        case 'sun':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
            break;
        case 'user':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
            break;
        case 'shopping-bag':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>';
            break;
        case 'heart':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
            break;
        case 'facebook':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>';
            break;
        case 'twitter':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>';
            break;
        case 'instagram':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
            break;
        case 'linkedin':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
            break;
        case 'youtube':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>';
            break;
        case 'pinterest':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>';
            break;
        case 'comment':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>';
            break;
        case 'folder':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>';
            break;
        case 'tag':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>';
            break;
        case 'edit':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>';
            break;
        case 'clock':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>';
            break;
        case 'filter':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>';
            break;
        case 'page':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>';
            break;
        case '404':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path><path d="M8 7h4m4 0h-4m0 0v10"></path><path d="M16 17h-4m-4 0h4m0 0v-5m0 0h4"></path></svg>';
            break;
        case 'visa':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M16.539 9.186a4.155 4.155 0 0 0-1.451-.251c-1.6 0-2.73.806-2.738 1.963-.01.85.803 1.329 1.418 1.613.631.292.842.476.84.737-.004.397-.504.577-.969.577-.646 0-.988-.09-1.517-.313l-.207-.093-.227 1.327c.377.166 1.075.311 1.8.317 1.696 0 2.797-.797 2.811-2.031.006-.679-.428-1.192-1.366-1.616-.568-.273-.916-.455-.914-.73 0-.245.291-.508.919-.508.527-.008.906.106 1.203.226l.144.068.217-1.286zM21.475 9.046h-1.313c-.408 0-.713.112-.893.522l-2.525 5.733h1.783l.357-.936h2.178l.207.936h1.572l-1.366-6.255zm-2.221 4.347l.684-1.766c-.007.011.141-.364.229-.6l.117.543.396 1.823h-1.426zM8.705 9.046l-1.664 4.247-.178-.856c-.311-1-.641-1.273-1.215-1.662l1.563 4.526h1.853l2.764-6.255H8.705z"/><path d="M6.398 9.046H3.844l-.033.153c1.984.48 3.299 1.642 3.844 3.038l-.555-2.668c-.097-.366-.377-.478-.702-.523z"/></svg>';
            break;
        case 'mastercard':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M11.343 18.031c.058.049.12.098.181.146-1.177.783-2.59 1.238-4.107 1.238C3.32 19.416 0 16.096 0 12c0-4.095 3.32-7.416 7.416-7.416 1.518 0 2.931.456 4.105 1.238-.06.051-.12.098-.165.15C9.6 7.489 8.595 9.688 8.595 12c0 2.311 1.001 4.51 2.748 6.031zm5.241-13.447c-1.52 0-2.931.456-4.105 1.238.06.051.12.098.165.15C14.4 7.489 15.405 9.688 15.405 12c0 2.31-1.001 4.507-2.748 6.031-.058.049-.12.098-.181.146 1.177.783 2.588 1.238 4.107 1.238C20.68 19.416 24 16.096 24 12c0-4.094-3.32-7.416-7.416-7.416zM12 6.174c-.096.075-.189.15-.28.231C10.156 7.764 9.169 9.765 9.169 12c0 2.236.987 4.236 2.551 5.595.09.08.185.158.28.232.09-.074.189-.152.28-.232 1.563-1.359 2.551-3.359 2.551-5.595 0-2.235-.987-4.236-2.551-5.595-.09-.08-.19-.156-.28-.231z"/></svg>';
            break;
        case 'amex':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M21.5 12c0 5.25-4.25 9.5-9.5 9.5S2.5 17.25 2.5 12 6.75 2.5 12 2.5s9.5 4.25 9.5 9.5zm-2.633-3.5H17.25v-.917h-1.75v.917h-1.75v.875h1.75v.875h-1.75v.875h1.75v.875h-1.75v.875h1.75V13.8h1.617c.7 0 1.283-.583 1.283-1.283v-2.734c0-.7-.583-1.283-1.283-1.283zM9.45 8.5v.875h3.5V8.5h-3.5zm0 1.75h3.5v-.875h-3.5v.875zm0 .875v.875h3.5v-.875h-3.5zm-4.375-2.625L3.5 10.25l1.575 1.75h1.75l1.575-1.75-1.575-1.75h-1.75zm.875.875h.875l.7.875-.7.875h-.875L5.25 10.25l.7-.875z"/></svg>';
            break;
        case 'discover':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M23.808 10.787c0-4.775-3.921-8.696-8.696-8.696H8.696C3.921 2.091 0 6.012 0 10.787v2.425c0 4.775 3.921 8.696 8.696 8.696h6.417c4.775 0 8.696-3.921 8.696-8.696v-2.425h-.001zm-8.696 8.696H8.696c-3.439 0-6.271-2.832-6.271-6.271v-2.425c0-3.439 2.832-6.271 6.271-6.271h6.417c3.439 0 6.271 2.832 6.271 6.271v2.425c-.001 3.439-2.833 6.271-6.272 6.271z"/><path d="M17.261 9.574c-.893 0-1.62.728-1.62 1.62 0 .893.728 1.62 1.62 1.62.893 0 1.62-.728 1.62-1.62.001-.892-.727-1.62-1.62-1.62zm-8.696 3.241h-.81v-3.241h.81c.893 0 1.62.728 1.62 1.62s-.727 1.621-1.62 1.621z"/></svg>';
            break;
        case 'paypal':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944 3.384a.641.641 0 0 1 .632-.544h6.964c2.387 0 4.14.745 4.909 2.087.729 1.272.777 2.947.145 4.954-.003.005-.004.009-.004.014-1.673 5.312-5.098 5.312-9.002 5.312H7.815c-.351 0-.65.254-.709.6l-1.016 5.442a.644.644 0 0 1-.634.544c-.004 0-.007-.001-.011-.001-.327.001-.327-.059-.369-.059z"/><path d="M19.951 7.873C19.483 11.81 16.238 14.292 12 14.292H9.936a.64.64 0 0 0-.633.543l-1.005 5.428c-.041.222.098.438.317.507.045.013.09.021.136.021h3.188c.351 0 .649-.252.709-.599l.63-3.375a.64.64 0 0 1 .633-.544h.932c4.318 0 7.6-2.299 8.715-8.316.522-2.809.048-4.728-1.621-6.042-.321-.255-.682-.468-1.066-.646.834 1.283 1.011 2.881.08 6.604z"/></svg>';
            break;
        case 'apple-pay':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M17.72 7.628c.188-.753.328-1.533.328-2.26 0-.08-.008-.158-.016-.234-1.121.086-2.073.586-2.745 1.337-.523.602-.96 1.537-.96 2.428 0 .086.008.17.016.234 1.113.086 2.065-.509 2.737-1.26.523-.602.564-1.345.64-1.345v-.1zm.328 1.337c-.72-.086-1.769.234-2.345.234-.564 0-1.449-.234-2.345-.234-1.665 0-3.193 1.177-3.193 3.437 0 2.763 2.345 5.863 2.345 5.863s1.449 1.765 2.513 1.765c.96 0 1.665-.669 2.345-.669.72 0 1.449.669 2.345.669 1.057 0 2.513-1.765 2.513-1.765.72-.92 1.009-1.429 1.009-1.429-1.769-.669-2.073-3.186-2.073-3.186 0-1.849 1.449-2.511 1.449-2.511-.72-1.177-2.073-1.177-2.073-1.177-.72 0-1.449.669-1.769.669-.328 0-1.057-.669-1.721-.669v.003z"/></svg>';
            break;
        case 'google-pay':
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="' . esc_attr($args['class']) . '" ' . ($args['aria-hidden'] ? 'aria-hidden="true"' : '') . '><path d="M12.545 12.151c0 .866-.702 1.568-1.568 1.568-.866 0-1.568-.702-1.568-1.568 0-.867.702-1.568 1.568-1.568.866 0 1.568.701 1.568 1.568zm5.928 0c0 .866-.702 1.568-1.568 1.568-.867 0-1.568-.702-1.568-1.568 0-.867.701-1.568 1.568-1.568.866 0 1.568.701 1.568 1.568zm-2.978 0c0-.669-.354-1.253-.884-1.576v3.152c.53-.323.884-.907.884-1.576zm-7.906-1.568c-.53.323-.884.907-.884 1.576s.354 1.253.884 1.576v-3.152zm15.411-.702c0-2.333-1.868-4.202-4.202-4.202H5.202C2.868 5.679 1 7.547 1 9.881c0 2.333 1.868 4.202 4.202 4.202h13.596c2.334 0 4.202-1.869 4.202-4.202z"/></svg>';
            break;
        default:
            $svg = '';
            break;
    }
    
    return $svg;
}

/**
 * Posted on
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

    echo '<span class="posted-on flex items-center">';
    aqualuxe_svg_icon('clock', array('class' => 'w-4 h-4 mr-1'));
    echo '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Posted by
 */
function aqualuxe_posted_by() {
    echo '<span class="byline ml-4 flex items-center">';
    aqualuxe_svg_icon('user', array('class' => 'w-4 h-4 mr-1'));
    echo '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span></span>';
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = '';
    $home_link = home_url('/');
    $home_text = __('Home', 'aqualuxe');
    
    $breadcrumbs .= '<div class="breadcrumbs">';
    $breadcrumbs .= '<span class="breadcrumb-item"><a href="' . esc_url($home_link) . '">' . esc_html($home_text) . '</a></span>';
    
    if (is_category() || is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs .= '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></span>';
        }
        
        if (is_single()) {
            $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html(get_the_title()) . '</span>';
        }
    } elseif (is_page()) {
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_search()) {
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    } elseif (is_404()) {
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html__('404 Not Found', 'aqualuxe') . '</span>';
    } elseif (is_home()) {
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html(get_the_title(get_option('page_for_posts'))) . '</span>';
    } elseif (is_tax()) {
        $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html($term->name) . '</span>';
    } elseif (is_archive()) {
        $breadcrumbs .= '<span class="breadcrumb-item active">' . esc_html(get_the_archive_title()) . '</span>';
    }
    
    $breadcrumbs .= '</div>';
    
    return $breadcrumbs;
}

/**
 * Pagination
 */
function aqualuxe_pagination() {
    the_posts_pagination(
        array(
            'mid_size'  => 2,
            'prev_text' => '<span class="nav-prev-text">' . aqualuxe_get_svg_icon('arrow-left', array('class' => 'w-4 h-4')) . '</span>',
            'next_text' => '<span class="nav-next-text">' . aqualuxe_get_svg_icon('arrow-right', array('class' => 'w-4 h-4')) . '</span>',
            'class'     => 'pagination',
        )
    );
}