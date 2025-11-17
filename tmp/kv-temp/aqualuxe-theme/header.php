<?php
/**
 * The header for AquaLuxe theme
 *
 * @package AquaLuxe
 * @version 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link sr-only" href="#main"><?php _e('Skip to content', AquaLuxeTheme::TEXT_DOMAIN); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <div class="container">
            
            <div class="site-branding">
                <?php aqualuxe_site_branding(); ?>
            </div>
            
            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php _e('Primary Menu', AquaLuxeTheme::TEXT_DOMAIN); ?>">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="sr-only"><?php _e('Primary Menu', AquaLuxeTheme::TEXT_DOMAIN); ?></span>
                    <span class="menu-icon"></span>
                </button>
                
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'fallback_cb' => false,
                ]);
                ?>
            </nav>
            
            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <div class="header-cart">
                    <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', AquaLuxeTheme::TEXT_DOMAIN); ?>">
                        <span class="cart-icon" aria-hidden="true"></span>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <span class="sr-only"><?php _e('items in cart', AquaLuxeTheme::TEXT_DOMAIN); ?></span>
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </header>

    <?php if (is_front_page() && !is_home()) : ?>
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1><?php _e('Welcome to AquaLuxe', AquaLuxeTheme::TEXT_DOMAIN); ?></h1>
                    <p><?php _e('Discover the luxury and elegance of premium ornamental fish. Transform your space with our exquisite aquatic collection.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="hero-cta ripple-effect">
                        <?php _e('Shop Now', AquaLuxeTheme::TEXT_DOMAIN); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php aqualuxe_breadcrumbs(); ?>
