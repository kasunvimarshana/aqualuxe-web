<?php
/**
 * Template hooks for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header
 *
 * @see aqualuxe_header_before()
 * @see aqualuxe_header_start()
 * @see aqualuxe_site_branding()
 * @see aqualuxe_primary_navigation()
 * @see aqualuxe_header_actions()
 * @see aqualuxe_header_end()
 * @see aqualuxe_header_after()
 */
add_action('aqualuxe_header', 'aqualuxe_header_before', 5);
add_action('aqualuxe_header', 'aqualuxe_header_start', 10);
add_action('aqualuxe_header', 'aqualuxe_site_branding', 20);
add_action('aqualuxe_header', 'aqualuxe_primary_navigation', 30);
add_action('aqualuxe_header', 'aqualuxe_header_actions', 40);
add_action('aqualuxe_header', 'aqualuxe_header_end', 50);
add_action('aqualuxe_header', 'aqualuxe_header_after', 60);

/**
 * Footer
 *
 * @see aqualuxe_footer_before()
 * @see aqualuxe_footer_start()
 * @see aqualuxe_footer_widgets()
 * @see aqualuxe_footer_info()
 * @see aqualuxe_footer_end()
 * @see aqualuxe_footer_after()
 */
add_action('aqualuxe_footer', 'aqualuxe_footer_before', 5);
add_action('aqualuxe_footer', 'aqualuxe_footer_start', 10);
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 20);
add_action('aqualuxe_footer', 'aqualuxe_footer_info', 30);
add_action('aqualuxe_footer', 'aqualuxe_footer_end', 40);
add_action('aqualuxe_footer', 'aqualuxe_footer_after', 50);

/**
 * Content
 *
 * @see aqualuxe_content_before()
 * @see aqualuxe_content_start()
 * @see aqualuxe_content_top()
 * @see aqualuxe_content_bottom()
 * @see aqualuxe_content_end()
 * @see aqualuxe_content_after()
 */
add_action('aqualuxe_content', 'aqualuxe_content_before', 5);
add_action('aqualuxe_content', 'aqualuxe_content_start', 10);
add_action('aqualuxe_content', 'aqualuxe_content_top', 20);
add_action('aqualuxe_content', 'aqualuxe_content_bottom', 30);
add_action('aqualuxe_content', 'aqualuxe_content_end', 40);
add_action('aqualuxe_content', 'aqualuxe_content_after', 50);

/**
 * Sidebar
 *
 * @see aqualuxe_sidebar_before()
 * @see aqualuxe_sidebar_start()
 * @see aqualuxe_get_sidebar()
 * @see aqualuxe_sidebar_end()
 * @see aqualuxe_sidebar_after()
 */
add_action('aqualuxe_sidebar', 'aqualuxe_sidebar_before', 5);
add_action('aqualuxe_sidebar', 'aqualuxe_sidebar_start', 10);
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 20);
add_action('aqualuxe_sidebar', 'aqualuxe_sidebar_end', 30);
add_action('aqualuxe_sidebar', 'aqualuxe_sidebar_after', 40);

/**
 * Posts
 *
 * @see aqualuxe_post_before()
 * @see aqualuxe_post_start()
 * @see aqualuxe_post_header()
 * @see aqualuxe_post_thumbnail()
 * @see aqualuxe_post_content()
 * @see aqualuxe_post_footer()
 * @see aqualuxe_post_end()
 * @see aqualuxe_post_after()
 */
add_action('aqualuxe_post', 'aqualuxe_post_before', 5);
add_action('aqualuxe_post', 'aqualuxe_post_start', 10);
add_action('aqualuxe_post', 'aqualuxe_post_header', 20);
add_action('aqualuxe_post', 'aqualuxe_post_thumbnail', 30);
add_action('aqualuxe_post', 'aqualuxe_post_content', 40);
add_action('aqualuxe_post', 'aqualuxe_post_footer', 50);
add_action('aqualuxe_post', 'aqualuxe_post_end', 60);
add_action('aqualuxe_post', 'aqualuxe_post_after', 70);

/**
 * Pages
 *
 * @see aqualuxe_page_before()
 * @see aqualuxe_page_start()
 * @see aqualuxe_page_header()
 * @see aqualuxe_page_thumbnail()
 * @see aqualuxe_page_content()
 * @see aqualuxe_page_footer()
 * @see aqualuxe_page_end()
 * @see aqualuxe_page_after()
 */
add_action('aqualuxe_page', 'aqualuxe_page_before', 5);
add_action('aqualuxe_page', 'aqualuxe_page_start', 10);
add_action('aqualuxe_page', 'aqualuxe_page_header', 20);
add_action('aqualuxe_page', 'aqualuxe_page_thumbnail', 30);
add_action('aqualuxe_page', 'aqualuxe_page_content', 40);
add_action('aqualuxe_page', 'aqualuxe_page_footer', 50);
add_action('aqualuxe_page', 'aqualuxe_page_end', 60);
add_action('aqualuxe_page', 'aqualuxe_page_after', 70);

/**
 * Header hooks
 */
if (!function_exists('aqualuxe_header_before')) {
    /**
     * Before header
     */
    function aqualuxe_header_before() {
        do_action('aqualuxe_header_before');
    }
}

if (!function_exists('aqualuxe_header_start')) {
    /**
     * Start header
     */
    function aqualuxe_header_start() {
        ?>
        <header id="masthead" class="site-header">
            <div class="header-container">
        <?php
    }
}

if (!function_exists('aqualuxe_site_branding')) {
    /**
     * Site branding
     */
    function aqualuxe_site_branding() {
        ?>
        <div class="site-branding">
            <?php
            if (has_custom_logo()) :
                the_custom_logo();
            else :
                ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                <?php
                $aqualuxe_description = get_bloginfo('description', 'display');
                if ($aqualuxe_description || is_customize_preview()) :
                    ?>
                    <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .site-branding -->
        <?php
    }
}

if (!function_exists('aqualuxe_primary_navigation')) {
    /**
     * Primary navigation
     */
    function aqualuxe_primary_navigation() {
        ?>
        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" @click="mobileMenuOpen = !mobileMenuOpen">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            </button>
            
            <div class="primary-menu-container" :class="{ 'is-active': mobileMenuOpen }">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'fallback_cb'    => false,
                        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                    )
                );
                ?>
            </div>
        </nav><!-- #site-navigation -->
        <?php
    }
}

if (!function_exists('aqualuxe_header_actions')) {
    /**
     * Header actions
     */
    function aqualuxe_header_actions() {
        ?>
        <div class="header-actions">
            <button class="search-toggle" aria-expanded="false" @click="searchOpen = !searchOpen">
                <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>
            </button>

            <?php if (aqualuxe_is_woocommerce_active()) : ?>
            <button class="cart-toggle" aria-expanded="false" @click="cartOpen = !cartOpen">
                <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
            </button>
            <?php endif; ?>

            <?php if (has_nav_menu('social')) : ?>
            <div class="social-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'social',
                        'menu_id'        => 'social-menu',
                        'container'      => false,
                        'menu_class'     => 'social-menu',
                        'link_before'    => '<span class="screen-reader-text">',
                        'link_after'     => '</span>',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </div>
            <?php endif; ?>
        </div><!-- .header-actions -->
        <?php
    }
}

if (!function_exists('aqualuxe_header_end')) {
    /**
     * End header
     */
    function aqualuxe_header_end() {
        ?>
            </div><!-- .header-container -->

            <div class="search-modal" :class="{ 'is-active': searchOpen }" @click.away="searchOpen = false">
                <div class="search-modal-container">
                    <button class="search-modal-close" @click="searchOpen = false">
                        <span class="screen-reader-text"><?php esc_html_e('Close search', 'aqualuxe'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                    </button>
                    <div class="search-form-container">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div><!-- .search-modal -->

            <?php if (aqualuxe_is_woocommerce_active()) : ?>
            <div class="cart-modal" :class="{ 'is-active': cartOpen }" @click.away="cartOpen = false">
                <div class="cart-modal-container">
                    <button class="cart-modal-close" @click="cartOpen = false">
                        <span class="screen-reader-text"><?php esc_html_e('Close cart', 'aqualuxe'); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>
                    </button>
                    <div class="cart-modal-content">
                        <div class="widget_shopping_cart_content">
                            <?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                </div>
            </div><!-- .cart-modal -->
            <?php endif; ?>
        </header><!-- #masthead -->
        <?php
    }
}

if (!function_exists('aqualuxe_header_after')) {
    /**
     * After header
     */
    function aqualuxe_header_after() {
        do_action('aqualuxe_header_after');
    }
}

/**
 * Footer hooks
 */
if (!function_exists('aqualuxe_footer_before')) {
    /**
     * Before footer
     */
    function aqualuxe_footer_before() {
        do_action('aqualuxe_footer_before');
    }
}

if (!function_exists('aqualuxe_footer_start')) {
    /**
     * Start footer
     */
    function aqualuxe_footer_start() {
        ?>
        <footer id="colophon" class="site-footer">
        <?php
    }
}

if (!function_exists('aqualuxe_footer_widgets')) {
    /**
     * Footer widgets
     */
    function aqualuxe_footer_widgets() {
        if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) :
            ?>
            <div class="footer-widgets">
                <div class="footer-widgets-container">
                    <div class="footer-widget-area">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget footer-widget-1">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget footer-widget-2">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget footer-widget-3">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <div class="footer-widget footer-widget-4">
                                <?php dynamic_sidebar('footer-4'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        endif;
    }
}

if (!function_exists('aqualuxe_footer_info')) {
    /**
     * Footer info
     */
    function aqualuxe_footer_info() {
        ?>
        <div class="site-info">
            <div class="site-info-container">
                <div class="copyright">
                    <?php
                    /* translators: %s: Current year and site name */
                    printf(esc_html__('© %s %s. All rights reserved.', 'aqualuxe'), date_i18n('Y'), get_bloginfo('name'));
                    ?>
                </div>

                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
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
                    </nav>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('aqualuxe_footer_end')) {
    /**
     * End footer
     */
    function aqualuxe_footer_end() {
        ?>
        </footer><!-- #colophon -->
        <?php
    }
}

if (!function_exists('aqualuxe_footer_after')) {
    /**
     * After footer
     */
    function aqualuxe_footer_after() {
        do_action('aqualuxe_footer_after');
    }
}

/**
 * Content hooks
 */
if (!function_exists('aqualuxe_content_before')) {
    /**
     * Before content
     */
    function aqualuxe_content_before() {
        do_action('aqualuxe_content_before');
    }
}

if (!function_exists('aqualuxe_content_start')) {
    /**
     * Start content
     */
    function aqualuxe_content_start() {
        ?>
        <div id="content" class="site-content">
        <?php
    }
}

if (!function_exists('aqualuxe_content_top')) {
    /**
     * Top content
     */
    function aqualuxe_content_top() {
        do_action('aqualuxe_content_top');
    }
}

if (!function_exists('aqualuxe_content_bottom')) {
    /**
     * Bottom content
     */
    function aqualuxe_content_bottom() {
        do_action('aqualuxe_content_bottom');
    }
}

if (!function_exists('aqualuxe_content_end')) {
    /**
     * End content
     */
    function aqualuxe_content_end() {
        ?>
        </div><!-- #content -->
        <?php
    }
}

if (!function_exists('aqualuxe_content_after')) {
    /**
     * After content
     */
    function aqualuxe_content_after() {
        do_action('aqualuxe_content_after');
    }
}

/**
 * Sidebar hooks
 */
if (!function_exists('aqualuxe_sidebar_before')) {
    /**
     * Before sidebar
     */
    function aqualuxe_sidebar_before() {
        do_action('aqualuxe_sidebar_before');
    }
}

if (!function_exists('aqualuxe_sidebar_start')) {
    /**
     * Start sidebar
     */
    function aqualuxe_sidebar_start() {
        ?>
        <aside id="secondary" class="widget-area">
        <?php
    }
}

if (!function_exists('aqualuxe_get_sidebar')) {
    /**
     * Get sidebar
     */
    function aqualuxe_get_sidebar() {
        dynamic_sidebar('sidebar-1');
    }
}

if (!function_exists('aqualuxe_sidebar_end')) {
    /**
     * End sidebar
     */
    function aqualuxe_sidebar_end() {
        ?>
        </aside><!-- #secondary -->
        <?php
    }
}

if (!function_exists('aqualuxe_sidebar_after')) {
    /**
     * After sidebar
     */
    function aqualuxe_sidebar_after() {
        do_action('aqualuxe_sidebar_after');
    }
}

/**
 * Post hooks
 */
if (!function_exists('aqualuxe_post_before')) {
    /**
     * Before post
     */
    function aqualuxe_post_before() {
        do_action('aqualuxe_post_before');
    }
}

if (!function_exists('aqualuxe_post_start')) {
    /**
     * Start post
     */
    function aqualuxe_post_start() {
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php
    }
}

if (!function_exists('aqualuxe_post_header')) {
    /**
     * Post header
     */
    function aqualuxe_post_header() {
        ?>
        <header class="entry-header">
            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title">', '</h1>');
            else :
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;

            if ('post' === get_post_type()) :
                ?>
                <div class="entry-meta">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('aqualuxe_post_thumbnail')) {
    /**
     * Post thumbnail
     */
    function aqualuxe_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>
            <div class="post-thumbnail">
                <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'featured-image')); ?>
            </div>
            <?php
        else :
            ?>
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php the_post_thumbnail('aqualuxe-card', array('class' => 'post-thumbnail-image')); ?>
                </a>
            </div>
            <?php
        endif;
    }
}

if (!function_exists('aqualuxe_post_content')) {
    /**
     * Post content
     */
    function aqualuxe_post_content() {
        ?>
        <div class="entry-content">
            <?php
            if (is_singular()) :
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
            else :
                the_excerpt();
                ?>
                <a href="<?php the_permalink(); ?>" class="read-more-link">
                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                    <span class="screen-reader-text"><?php esc_html_e('about', 'aqualuxe'); ?> <?php the_title(); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>
                </a>
            <?php endif; ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('aqualuxe_post_footer')) {
    /**
     * Post footer
     */
    function aqualuxe_post_footer() {
        ?>
        <footer class="entry-footer">
            <?php aqualuxe_entry_footer(); ?>
        </footer><!-- .entry-footer -->
        <?php
    }
}

if (!function_exists('aqualuxe_post_end')) {
    /**
     * End post
     */
    function aqualuxe_post_end() {
        ?>
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
    }
}

if (!function_exists('aqualuxe_post_after')) {
    /**
     * After post
     */
    function aqualuxe_post_after() {
        do_action('aqualuxe_post_after');
    }
}

/**
 * Page hooks
 */
if (!function_exists('aqualuxe_page_before')) {
    /**
     * Before page
     */
    function aqualuxe_page_before() {
        do_action('aqualuxe_page_before');
    }
}

if (!function_exists('aqualuxe_page_start')) {
    /**
     * Start page
     */
    function aqualuxe_page_start() {
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php
    }
}

if (!function_exists('aqualuxe_page_header')) {
    /**
     * Page header
     */
    function aqualuxe_page_header() {
        ?>
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('aqualuxe_page_thumbnail')) {
    /**
     * Page thumbnail
     */
    function aqualuxe_page_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'featured-image')); ?>
        </div>
        <?php
    }
}

if (!function_exists('aqualuxe_page_content')) {
    /**
     * Page content
     */
    function aqualuxe_page_content() {
        ?>
        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('aqualuxe_page_footer')) {
    /**
     * Page footer
     */
    function aqualuxe_page_footer() {
        if (get_edit_post_link()) :
            ?>
            <footer class="entry-footer">
                <?php
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
                ?>
            </footer><!-- .entry-footer -->
            <?php
        endif;
    }
}

if (!function_exists('aqualuxe_page_end')) {
    /**
     * End page
     */
    function aqualuxe_page_end() {
        ?>
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
    }
}

if (!function_exists('aqualuxe_page_after')) {
    /**
     * After page
     */
    function aqualuxe_page_after() {
        do_action('aqualuxe_page_after');
    }
}