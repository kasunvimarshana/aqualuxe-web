<?php
/**
 * Template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Header hooks
 */
add_action('aqualuxe_header', 'aqualuxe_header_before', 5);
add_action('aqualuxe_header', 'aqualuxe_top_bar', 10);
add_action('aqualuxe_header', 'aqualuxe_header_content', 20);
add_action('aqualuxe_header', 'aqualuxe_header_after', 30);

add_action('aqualuxe_top_bar', 'aqualuxe_top_bar_left', 10);
add_action('aqualuxe_top_bar', 'aqualuxe_top_bar_right', 20);

add_action('aqualuxe_header_content', 'aqualuxe_site_branding', 10);
add_action('aqualuxe_header_content', 'aqualuxe_primary_navigation', 20);
add_action('aqualuxe_header_content', 'aqualuxe_header_actions', 30);

add_action('aqualuxe_header_actions', 'aqualuxe_header_search', 10);
add_action('aqualuxe_header_actions', 'aqualuxe_header_account', 20);
add_action('aqualuxe_header_actions', 'aqualuxe_header_cart', 30);
add_action('aqualuxe_header_actions', 'aqualuxe_dark_mode_toggle', 40);
add_action('aqualuxe_header_actions', 'aqualuxe_language_switcher', 50);

/**
 * Footer hooks
 */
add_action('aqualuxe_footer', 'aqualuxe_footer_before', 5);
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);
add_action('aqualuxe_footer', 'aqualuxe_footer_bottom', 20);
add_action('aqualuxe_footer', 'aqualuxe_footer_after', 30);

add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_info', 10);
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_menu', 20);
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_social', 30);

/**
 * Content hooks
 */
add_action('aqualuxe_content_before', 'aqualuxe_breadcrumbs', 10);
add_action('aqualuxe_content_before', 'aqualuxe_page_header', 20);

add_action('aqualuxe_content_after', 'aqualuxe_related_posts', 10);
add_action('aqualuxe_content_after', 'aqualuxe_post_navigation', 20);
add_action('aqualuxe_content_after', 'aqualuxe_comments', 30);

/**
 * Post hooks
 */
add_action('aqualuxe_post_before', 'aqualuxe_post_thumbnail', 10);
add_action('aqualuxe_post_header', 'aqualuxe_post_title', 10);
add_action('aqualuxe_post_header', 'aqualuxe_post_meta', 20);
add_action('aqualuxe_post_content', 'aqualuxe_post_content', 10);
add_action('aqualuxe_post_footer', 'aqualuxe_post_tags', 10);
add_action('aqualuxe_post_footer', 'aqualuxe_post_share', 20);
add_action('aqualuxe_post_footer', 'aqualuxe_post_author_bio', 30);

/**
 * Page hooks
 */
add_action('aqualuxe_page_before', 'aqualuxe_page_thumbnail', 10);
add_action('aqualuxe_page_header', 'aqualuxe_page_title', 10);
add_action('aqualuxe_page_content', 'aqualuxe_page_content', 10);
add_action('aqualuxe_page_footer', 'aqualuxe_page_edit_link', 10);

/**
 * Sidebar hooks
 */
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10);

/**
 * WooCommerce hooks
 */
if (class_exists('WooCommerce')) {
    // Remove default WooCommerce wrappers
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    
    // Add custom wrappers
    add_action('woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_before', 10);
    add_action('woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_after', 10);
    
    // Shop page hooks
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_filters', 15);
    add_action('woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_view_switcher', 20);
    
    // Product loop hooks
    add_action('woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_product_loop_start', 5);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_product_loop_end', 50);
    add_action('woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_product_categories', 5);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_rating', 5);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_product_actions', 15);
    
    // Single product hooks
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_gallery_wrapper_start', 5);
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_woocommerce_product_gallery_wrapper_end', 30);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_categories', 5);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_stock_status', 25);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta_start', 39);
    add_action('woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta_end', 41);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_social_share', 15);
    
    // Cart hooks
    add_action('woocommerce_before_cart', 'aqualuxe_woocommerce_cart_progress', 10);
    
    // Checkout hooks
    add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_progress', 10);
    
    // Account hooks
    add_action('woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_welcome', 10);
}

/**
 * Header template functions
 */

/**
 * Output header before content
 */
function aqualuxe_header_before() {
    ?>
    <header id="masthead" class="site-header" role="banner" itemscope itemtype="https://schema.org/WPHeader">
    <?php
}

/**
 * Output top bar
 */
function aqualuxe_top_bar() {
    if (!get_theme_mod('aqualuxe_enable_top_bar', true)) {
        return;
    }
    
    ?>
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <?php do_action('aqualuxe_top_bar'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output top bar left content
 */
function aqualuxe_top_bar_left() {
    ?>
    <div class="top-bar-left">
        <?php
        if (has_nav_menu('top-bar')) {
            wp_nav_menu([
                'theme_location' => 'top-bar',
                'container' => false,
                'menu_class' => 'top-bar-menu',
                'depth' => 1,
                'fallback_cb' => false,
            ]);
        }
        ?>
    </div>
    <?php
}

/**
 * Output top bar right content
 */
function aqualuxe_top_bar_right() {
    ?>
    <div class="top-bar-right">
        <?php
        // Display contact information
        $phone = get_theme_mod('aqualuxe_contact_phone', '');
        $email = get_theme_mod('aqualuxe_contact_email', '');
        
        if ($phone || $email) {
            echo '<div class="top-bar-contact">';
            
            if ($phone) {
                echo '<span class="top-bar-phone"><i class="fas fa-phone" aria-hidden="true"></i> <a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $phone)) . '">' . esc_html($phone) . '</a></span>';
            }
            
            if ($email) {
                echo '<span class="top-bar-email"><i class="fas fa-envelope" aria-hidden="true"></i> <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></span>';
            }
            
            echo '</div>';
        }
        
        // Display social links
        aqualuxe_social_links();
        ?>
    </div>
    <?php
}

/**
 * Output header content
 */
function aqualuxe_header_content() {
    $header_layout = aqualuxe_get_header_layout();
    ?>
    <div class="header-main">
        <div class="container">
            <div class="header-inner header-layout-<?php echo esc_attr($header_layout); ?>">
                <?php do_action('aqualuxe_header_content'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output site branding
 */
function aqualuxe_site_branding() {
    ?>
    <div class="site-branding">
        <?php
        if (has_custom_logo()) {
            the_custom_logo();
        } else {
            ?>
            <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) {
                ?>
                <p class="site-description"><?php echo $description; ?></p>
                <?php
            }
        }
        ?>
    </div>
    <?php
}

/**
 * Output primary navigation
 */
function aqualuxe_primary_navigation() {
    ?>
    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>" itemscope itemtype="https://schema.org/SiteNavigationElement">
        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span class="menu-toggle-icon"></span>
            <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
        </button>
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id' => 'primary-menu',
            'menu_class' => 'primary-menu',
            'container' => false,
            'fallback_cb' => 'aqualuxe_primary_menu_fallback',
        ]);
        ?>
    </nav>
    <?php
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    if (current_user_can('edit_theme_options')) {
        ?>
        <ul id="primary-menu" class="primary-menu">
            <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></a></li>
        </ul>
        <?php
    }
}

/**
 * Output header actions
 */
function aqualuxe_header_actions() {
    ?>
    <div class="header-actions">
        <?php do_action('aqualuxe_header_actions'); ?>
    </div>
    <?php
}

/**
 * Output header search
 */
function aqualuxe_header_search() {
    if (!get_theme_mod('aqualuxe_enable_header_search', true)) {
        return;
    }
    ?>
    <div class="header-search">
        <button class="header-search-toggle" aria-expanded="false">
            <i class="fas fa-search" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
        </button>
        <div class="header-search-dropdown">
            <?php get_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Output header account
 */
function aqualuxe_header_account() {
    if (!get_theme_mod('aqualuxe_enable_header_account', true)) {
        return;
    }
    
    if (class_exists('WooCommerce')) {
        ?>
        <div class="header-account">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="header-account-link">
                <i class="fas fa-user" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
            </a>
            <?php if (!is_user_logged_in()) : ?>
                <div class="header-account-dropdown">
                    <?php woocommerce_login_form(); ?>
                    <p class="header-account-register">
                        <?php
                        /* translators: %s: registration URL */
                        printf(
                            __('Don\'t have an account? <a href="%s">Register</a>', 'aqualuxe'),
                            esc_url(wc_get_page_permalink('myaccount'))
                        );
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    } else {
        ?>
        <div class="header-account">
            <a href="<?php echo esc_url(wp_login_url()); ?>" class="header-account-link">
                <i class="fas fa-user" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Login', 'aqualuxe'); ?></span>
            </a>
        </div>
        <?php
    }
}

/**
 * Output header cart
 */
function aqualuxe_header_cart() {
    if (!get_theme_mod('aqualuxe_enable_header_cart', true) || !class_exists('WooCommerce')) {
        return;
    }
    ?>
    <div class="header-cart">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="header-cart-link">
            <i class="fas fa-shopping-cart" aria-hidden="true"></i>
            <span class="header-cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
            <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
        </a>
        <div class="header-cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
        return;
    }
    
    $is_dark_mode = aqualuxe_is_dark_mode();
    ?>
    <div class="dark-mode-toggle">
        <button class="dark-mode-toggle-button" aria-pressed="<?php echo $is_dark_mode ? 'true' : 'false'; ?>">
            <i class="fas <?php echo $is_dark_mode ? 'fa-sun' : 'fa-moon'; ?>" aria-hidden="true"></i>
            <span class="screen-reader-text"><?php echo $is_dark_mode ? esc_html__('Light Mode', 'aqualuxe') : esc_html__('Dark Mode', 'aqualuxe'); ?></span>
        </button>
    </div>
    <?php
}

/**
 * Output language switcher
 */
function aqualuxe_language_switcher() {
    // Check if WPML or Polylang is active
    if (function_exists('icl_get_languages') || function_exists('pll_the_languages')) {
        ?>
        <div class="language-switcher">
            <?php
            if (function_exists('icl_get_languages')) {
                // WPML
                $languages = icl_get_languages('skip_missing=0');
                
                if (!empty($languages)) {
                    echo '<ul class="language-switcher-list">';
                    
                    foreach ($languages as $language) {
                        $active_class = $language['active'] ? ' active' : '';
                        echo '<li class="language-switcher-item' . $active_class . '">';
                        echo '<a href="' . esc_url($language['url']) . '">';
                        
                        if (!empty($language['country_flag_url'])) {
                            echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                        }
                        
                        echo esc_html($language['language_code']);
                        echo '</a>';
                        echo '</li>';
                    }
                    
                    echo '</ul>';
                }
            } elseif (function_exists('pll_the_languages')) {
                // Polylang
                $args = [
                    'show_flags' => 1,
                    'show_names' => 0,
                    'dropdown' => 0,
                    'echo' => 1,
                ];
                
                pll_the_languages($args);
            }
            ?>
        </div>
        <?php
    }
}

/**
 * Output header after content
 */
function aqualuxe_header_after() {
    ?>
    </header><!-- #masthead -->
    <?php
}

/**
 * Footer template functions
 */

/**
 * Output footer before content
 */
function aqualuxe_footer_before() {
    ?>
    <footer id="colophon" class="site-footer" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
    <?php
}

/**
 * Output footer widgets
 */
function aqualuxe_footer_widgets() {
    // Check if any footer widget area is active
    if (
        !is_active_sidebar('footer-1') &&
        !is_active_sidebar('footer-2') &&
        !is_active_sidebar('footer-3') &&
        !is_active_sidebar('footer-4')
    ) {
        return;
    }
    
    // Get footer layout
    $footer_layout = aqualuxe_get_footer_layout();
    ?>
    <div class="footer-widgets footer-layout-<?php echo esc_attr($footer_layout); ?>">
        <div class="container">
            <div class="footer-widgets-inner">
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
}

/**
 * Output footer bottom
 */
function aqualuxe_footer_bottom() {
    ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <?php do_action('aqualuxe_footer_bottom'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output footer info
 */
function aqualuxe_footer_info() {
    $footer_text = get_theme_mod('aqualuxe_footer_text', sprintf(
        /* translators: %1$s: current year, %2$s: site name */
        __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
        date('Y'),
        get_bloginfo('name')
    ));
    ?>
    <div class="footer-info">
        <?php echo wp_kses_post($footer_text); ?>
    </div>
    <?php
}

/**
 * Output footer menu
 */
function aqualuxe_footer_menu() {
    if (has_nav_menu('footer')) {
        ?>
        <div class="footer-menu">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container' => false,
                'menu_class' => 'footer-menu-list',
                'depth' => 1,
                'fallback_cb' => false,
            ]);
            ?>
        </div>
        <?php
    }
}

/**
 * Output footer social
 */
function aqualuxe_footer_social() {
    ?>
    <div class="footer-social">
        <?php aqualuxe_social_links(); ?>
    </div>
    <?php
}

/**
 * Output footer after content
 */
function aqualuxe_footer_after() {
    ?>
    </footer><!-- #colophon -->
    <?php
}

/**
 * Content template functions
 */

/**
 * Output breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (!get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
        return;
    }
    
    if (is_front_page()) {
        return;
    }
    
    ?>
    <div class="breadcrumbs-wrapper">
        <div class="container">
            <?php aqualuxe_get_breadcrumbs(); ?>
        </div>
    </div>
    <?php
}

/**
 * Output page header
 */
function aqualuxe_page_header() {
    if (is_front_page()) {
        return;
    }
    
    $show_page_header = true;
    
    // Don't show on single product pages
    if (function_exists('is_product') && is_product()) {
        $show_page_header = false;
    }
    
    if (!$show_page_header) {
        return;
    }
    
    ?>
    <header class="page-header">
        <div class="container">
            <?php
            if (is_archive()) {
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
            } elseif (is_search()) {
                ?>
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query */
                    printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
                <?php
            } elseif (is_404()) {
                ?>
                <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                <?php
            } elseif (is_home()) {
                ?>
                <h1 class="page-title"><?php single_post_title(); ?></h1>
                <?php
            }
            ?>
        </div>
    </header>
    <?php
}

/**
 * Output related posts
 */
function aqualuxe_related_posts() {
    if (!is_singular('post')) {
        return;
    }
    
    if (!get_theme_mod('aqualuxe_enable_related_posts', true)) {
        return;
    }
    
    $related_posts = aqualuxe_get_related_posts(get_the_ID(), 3);
    
    if (!$related_posts->have_posts()) {
        return;
    }
    
    ?>
    <div class="related-posts">
        <div class="container">
            <h2 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h2>
            <div class="related-posts-list">
                <?php
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('related-post'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="related-post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('aqualuxe-blog-thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="related-post-content">
                            <h3 class="related-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <div class="related-post-meta">
                                <?php echo aqualuxe_get_post_date(); ?>
                            </div>
                        </div>
                    </article>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Output comments
 */
function aqualuxe_comments() {
    if (is_singular() && (comments_open() || get_comments_number())) {
        comments_template();
    }
}

/**
 * Post template functions
 */

/**
 * Output post thumbnail
 */
function aqualuxe_post_thumbnail() {
    if (!has_post_thumbnail()) {
        return;
    }
    
    ?>
    <div class="post-thumbnail">
        <?php
        if (is_singular()) {
            the_post_thumbnail('full', ['class' => 'post-thumbnail-image']);
        } else {
            ?>
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('aqualuxe-blog-thumbnail', ['class' => 'post-thumbnail-image']); ?>
            </a>
            <?php
        }
        ?>
    </div>
    <?php
}

/**
 * Output post title
 */
function aqualuxe_post_title() {
    if (is_singular()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } else {
        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
    }
}

/**
 * Output post meta
 */
function aqualuxe_post_meta() {
    echo aqualuxe_get_post_meta();
}

/**
 * Output post content
 */
function aqualuxe_post_content() {
    ?>
    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    get_the_title()
                )
            );
            
            wp_link_pages(
                [
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                    'after' => '</div>',
                ]
            );
        } else {
            echo aqualuxe_get_excerpt();
            ?>
            <p class="more-link-wrapper">
                <a href="<?php the_permalink(); ?>" class="more-link">
                    <?php esc_html_e('Continue Reading', 'aqualuxe'); ?>
                    <span class="screen-reader-text"><?php the_title(); ?></span>
                </a>
            </p>
            <?php
        }
        ?>
    </div>
    <?php
}

/**
 * Output post tags
 */
function aqualuxe_post_tags() {
    echo aqualuxe_get_post_tags();
}

/**
 * Output post share
 */
function aqualuxe_post_share() {
    if (!get_theme_mod('aqualuxe_enable_post_share', true)) {
        return;
    }
    
    ?>
    <div class="post-share">
        <h3 class="post-share-title"><?php esc_html_e('Share This Post', 'aqualuxe'); ?></h3>
        <div class="post-share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="post-share-button facebook">
                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url(get_permalink()); ?>&text=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="post-share-button twitter">
                <i class="fab fa-twitter" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
            </a>
            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink()); ?>&title=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="post-share-button linkedin">
                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
            </a>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>&description=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="post-share-button pinterest">
                <i class="fab fa-pinterest-p" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
            </a>
            <a href="mailto:?subject=<?php echo esc_attr(get_the_title()); ?>&body=<?php echo esc_url(get_permalink()); ?>" class="post-share-button email">
                <i class="fas fa-envelope" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Output post author bio
 */
function aqualuxe_post_author_bio() {
    if (!get_theme_mod('aqualuxe_enable_author_bio', true)) {
        return;
    }
    
    if (!is_singular('post')) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    
    ?>
    <div class="author-bio">
        <div class="author-avatar">
            <?php echo get_avatar($author_id, 100); ?>
        </div>
        <div class="author-content">
            <h3 class="author-name">
                <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
            </h3>
            <div class="author-description">
                <?php echo wpautop($author_description); ?>
            </div>
            <a href="<?php echo esc_url($author_url); ?>" class="author-link">
                <?php
                /* translators: %s: author name */
                printf(esc_html__('View all posts by %s', 'aqualuxe'), esc_html($author_name));
                ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Page template functions
 */

/**
 * Output page thumbnail
 */
function aqualuxe_page_thumbnail() {
    if (!has_post_thumbnail()) {
        return;
    }
    
    ?>
    <div class="page-thumbnail">
        <?php the_post_thumbnail('full', ['class' => 'page-thumbnail-image']); ?>
    </div>
    <?php
}

/**
 * Output page title
 */
function aqualuxe_page_title() {
    the_title('<h1 class="entry-title">', '</h1>');
}

/**
 * Output page content
 */
function aqualuxe_page_content() {
    ?>
    <div class="entry-content">
        <?php
        the_content();
        
        wp_link_pages(
            [
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                'after' => '</div>',
            ]
        );
        ?>
    </div>
    <?php
}

/**
 * Output page edit link
 */
function aqualuxe_page_edit_link() {
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
            get_the_title()
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Sidebar template functions
 */

/**
 * Output sidebar
 */
function aqualuxe_get_sidebar() {
    get_sidebar();
}

/**
 * WooCommerce template functions
 */
if (class_exists('WooCommerce')) {
    /**
     * Output WooCommerce wrapper before
     */
    function aqualuxe_woocommerce_wrapper_before() {
        ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
        <?php
    }
    
    /**
     * Output WooCommerce wrapper after
     */
    function aqualuxe_woocommerce_wrapper_after() {
        ?>
            </main><!-- #main -->
        </div><!-- #primary -->
        <?php
    }
    
    /**
     * Output WooCommerce shop filters
     */
    function aqualuxe_woocommerce_shop_filters() {
        if (!get_theme_mod('aqualuxe_enable_shop_filters', true)) {
            return;
        }
        
        ?>
        <div class="shop-filters">
            <?php
            // Add custom filters here
            do_action('aqualuxe_shop_filters');
            ?>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce shop view switcher
     */
    function aqualuxe_woocommerce_shop_view_switcher() {
        if (!get_theme_mod('aqualuxe_enable_shop_view_switcher', true)) {
            return;
        }
        
        $current_view = isset($_COOKIE['aqualuxe_shop_view']) ? $_COOKIE['aqualuxe_shop_view'] : 'grid';
        ?>
        <div class="shop-view-switcher">
            <button class="shop-view-button grid-view<?php echo $current_view === 'grid' ? ' active' : ''; ?>" data-view="grid">
                <i class="fas fa-th" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('Grid View', 'aqualuxe'); ?></span>
            </button>
            <button class="shop-view-button list-view<?php echo $current_view === 'list' ? ' active' : ''; ?>" data-view="list">
                <i class="fas fa-list" aria-hidden="true"></i>
                <span class="screen-reader-text"><?php esc_html_e('List View', 'aqualuxe'); ?></span>
            </button>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce product loop start
     */
    function aqualuxe_woocommerce_product_loop_start() {
        echo '<div class="product-inner">';
    }
    
    /**
     * Output WooCommerce product loop end
     */
    function aqualuxe_woocommerce_product_loop_end() {
        echo '</div>';
    }
    
    /**
     * Output WooCommerce product categories
     */
    function aqualuxe_woocommerce_product_categories() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        echo wc_get_product_category_list($product->get_id(), ', ', '<div class="product-categories">', '</div>');
    }
    
    /**
     * Output WooCommerce product rating
     */
    function aqualuxe_woocommerce_product_rating() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        if ($product->get_rating_count() === 0) {
            return;
        }
        
        echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
    }
    
    /**
     * Output WooCommerce product actions
     */
    function aqualuxe_woocommerce_product_actions() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        ?>
        <div class="product-actions">
            <?php
            // Quick view button
            if (get_theme_mod('aqualuxe_enable_quick_view', true)) {
                ?>
                <a href="#" class="quick-view-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <i class="fas fa-eye" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
                </a>
                <?php
            }
            
            // Wishlist button
            if (get_theme_mod('aqualuxe_enable_wishlist', true)) {
                ?>
                <a href="#" class="wishlist-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <i class="far fa-heart" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
                </a>
                <?php
            }
            
            // Compare button
            if (get_theme_mod('aqualuxe_enable_compare', true)) {
                ?>
                <a href="#" class="compare-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                    <i class="fas fa-exchange-alt" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Compare', 'aqualuxe'); ?></span>
                </a>
                <?php
            }
            ?>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce product gallery wrapper start
     */
    function aqualuxe_woocommerce_product_gallery_wrapper_start() {
        echo '<div class="product-gallery-wrapper">';
    }
    
    /**
     * Output WooCommerce product gallery wrapper end
     */
    function aqualuxe_woocommerce_product_gallery_wrapper_end() {
        echo '</div>';
    }
    
    /**
     * Output WooCommerce product stock status
     */
    function aqualuxe_woocommerce_product_stock_status() {
        global $product;
        
        if (!$product) {
            return;
        }
        
        // Get stock status
        $availability = $product->get_availability();
        $stock_status = $availability['class'] ?? '';
        
        if ($stock_status) {
            echo '<div class="product-stock-status ' . esc_attr($stock_status) . '">';
            echo esc_html($availability['availability'] ?? '');
            echo '</div>';
        }
    }
    
    /**
     * Output WooCommerce product meta start
     */
    function aqualuxe_woocommerce_product_meta_start() {
        echo '<div class="product-meta">';
    }
    
    /**
     * Output WooCommerce product meta end
     */
    function aqualuxe_woocommerce_product_meta_end() {
        echo '</div>';
    }
    
    /**
     * Output WooCommerce product social share
     */
    function aqualuxe_woocommerce_product_social_share() {
        if (!get_theme_mod('aqualuxe_enable_product_share', true)) {
            return;
        }
        
        ?>
        <div class="product-share">
            <h3 class="product-share-title"><?php esc_html_e('Share This Product', 'aqualuxe'); ?></h3>
            <div class="product-share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="product-share-button facebook">
                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url(get_permalink()); ?>&text=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="product-share-button twitter">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink()); ?>&title=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="product-share-button linkedin">
                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                </a>
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&media=<?php echo esc_url(get_the_post_thumbnail_url(null, 'full')); ?>&description=<?php echo esc_attr(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="product-share-button pinterest">
                    <i class="fab fa-pinterest-p" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                </a>
                <a href="mailto:?subject=<?php echo esc_attr(get_the_title()); ?>&body=<?php echo esc_url(get_permalink()); ?>" class="product-share-button email">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce cart progress
     */
    function aqualuxe_woocommerce_cart_progress() {
        ?>
        <div class="cart-progress">
            <div class="cart-progress-steps">
                <div class="cart-progress-step current">
                    <span class="cart-progress-step-number">1</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
                </div>
                <div class="cart-progress-step">
                    <span class="cart-progress-step-number">2</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
                </div>
                <div class="cart-progress-step">
                    <span class="cart-progress-step-number">3</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce checkout progress
     */
    function aqualuxe_woocommerce_checkout_progress() {
        ?>
        <div class="cart-progress">
            <div class="cart-progress-steps">
                <div class="cart-progress-step completed">
                    <span class="cart-progress-step-number">1</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></span>
                </div>
                <div class="cart-progress-step current">
                    <span class="cart-progress-step-number">2</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Checkout', 'aqualuxe'); ?></span>
                </div>
                <div class="cart-progress-step">
                    <span class="cart-progress-step-number">3</span>
                    <span class="cart-progress-step-label"><?php esc_html_e('Order Complete', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Output WooCommerce account welcome
     */
    function aqualuxe_woocommerce_account_welcome() {
        if (!is_user_logged_in()) {
            return;
        }
        
        $current_user = wp_get_current_user();
        ?>
        <div class="account-welcome">
            <h2 class="account-welcome-title">
                <?php
                /* translators: %s: user display name */
                printf(esc_html__('Welcome, %s', 'aqualuxe'), esc_html($current_user->display_name));
                ?>
            </h2>
        </div>
        <?php
    }
}