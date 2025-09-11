<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'aqualuxe'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
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
                    $description = get_bloginfo('description', 'display');
                    if ($description || is_customize_preview()) {
                        ?>
                        <p class="site-description"><?php echo $description; ?></p>
                        <?php
                    }
                }
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Main Menu', 'aqualuxe'); ?>">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="screen-reader-text"><?php esc_html_e('Main Menu', 'aqualuxe'); ?></span>
                    <span class="hamburger"></span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'menu_class' => 'primary-menu',
                    'fallback_cb' => false,
                ));
                ?>
            </nav><!-- #site-navigation -->

            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <div class="header-woocommerce">
                    <?php
                    // Search form
                    if (function_exists('get_product_search_form')) {
                        get_product_search_form();
                    }
                    
                    // Account link
                    ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="account-link">
                        <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
                        <i class="icon-user" aria-hidden="true"></i>
                    </a>
                    
                    <?php
                    // Cart link
                    ?>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link">
                        <span class="screen-reader-text"><?php esc_html_e('View cart', 'aqualuxe'); ?></span>
                        <i class="icon-cart" aria-hidden="true"></i>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Mobile menu overlay -->
            <div class="mobile-menu-overlay">
                <div class="mobile-menu-content">
                    <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'mobile',
                        'fallback_cb' => function() {
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'container' => false,
                                'menu_class' => 'mobile-menu',
                            ));
                        },
                        'container' => false,
                        'menu_class' => 'mobile-menu',
                    ));
                    ?>
                </div>
            </div>
        </div><!-- .container -->
    </header><!-- #masthead -->

    <div id="content" class="site-content">