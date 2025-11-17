<?php
/**
 * Template part for displaying the site header
 *
 * @package AquaLuxe
 */

// Get header options from theme customizer
$header_style = get_theme_mod('aqualuxe_header_style', 'default');
$show_top_bar = get_theme_mod('aqualuxe_show_top_bar', true);
$sticky_header = get_theme_mod('aqualuxe_sticky_header', true);
$header_transparent = get_theme_mod('aqualuxe_transparent_header', false);

// Header classes
$header_classes = array('site-header');
$header_classes[] = 'header-style-' . $header_style;

if ($sticky_header) {
    $header_classes[] = 'sticky-header';
}

if ($header_transparent && (is_front_page() || is_page_template('templates/template-transparent-header.php'))) {
    $header_classes[] = 'transparent-header';
}
?>

<header id="masthead" class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
    <?php
    // Top Bar
    if ($show_top_bar) {
        get_template_part('template-parts/header/top-bar');
    }
    ?>

    <div class="main-header">
        <div class="container">
            <div class="header-inner">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <h1 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php
                        $aqualuxe_description = get_bloginfo('description', 'display');
                        if ($aqualuxe_description || is_customize_preview()) {
                            ?>
                            <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                            <?php
                        }
                    }
                    ?>
                </div><!-- .site-branding -->

                <div class="header-right">
                    <nav id="site-navigation" class="main-navigation">
                        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                            <span class="menu-toggle-icon"></span>
                            <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'aqualuxe'); ?></span>
                        </button>
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'container'      => false,
                                'menu_class'     => 'primary-menu',
                                'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                                'walker'         => new AquaLuxe_Walker_Nav_Menu(),
                            )
                        );
                        ?>
                    </nav><!-- #site-navigation -->

                    <div class="header-actions">
                        <?php
                        // Search Icon
                        if (get_theme_mod('aqualuxe_show_header_search', true)) {
                            ?>
                            <div class="header-search">
                                <button class="search-toggle" aria-expanded="false">
                                    <i class="fas fa-search"></i>
                                    <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                                </button>
                                <div class="header-search-form">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                            <?php
                        }

                        // WooCommerce Icons
                        if (aqualuxe_is_woocommerce_active()) {
                            // My Account Icon
                            if (get_theme_mod('aqualuxe_show_header_account', true)) {
                                ?>
                                <div class="header-account">
                                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" title="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
                                        <i class="fas fa-user"></i>
                                        <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
                                    </a>
                                </div>
                                <?php
                            }

                            // Wishlist Icon
                            if (get_theme_mod('aqualuxe_show_header_wishlist', true) && function_exists('YITH_WCWL')) {
                                ?>
                                <div class="header-wishlist">
                                    <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" title="<?php esc_attr_e('Wishlist', 'aqualuxe'); ?>">
                                        <i class="fas fa-heart"></i>
                                        <span class="wishlist-count"><?php echo esc_html(yith_wcwl_count_all_products()); ?></span>
                                        <span class="screen-reader-text"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
                                    </a>
                                </div>
                                <?php
                            }

                            // Cart Icon
                            if (get_theme_mod('aqualuxe_show_header_cart', true)) {
                                ?>
                                <div class="header-cart">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('Cart', 'aqualuxe'); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
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
                        }
                        ?>
                    </div><!-- .header-actions -->
                </div><!-- .header-right -->
            </div><!-- .header-inner -->
        </div><!-- .container -->
    </div><!-- .main-header -->
</header><!-- #masthead -->

<?php
// Header Search Modal
if (get_theme_mod('aqualuxe_show_header_search', true)) {
    ?>
    <div id="search-modal" class="search-modal">
        <div class="search-modal-inner">
            <button class="search-modal-close">
                <i class="fas fa-times"></i>
                <span class="screen-reader-text"><?php esc_html_e('Close search', 'aqualuxe'); ?></span>
            </button>
            <div class="search-form-container">
                <h3><?php esc_html_e('Search our site', 'aqualuxe'); ?></h3>
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
    <?php
}