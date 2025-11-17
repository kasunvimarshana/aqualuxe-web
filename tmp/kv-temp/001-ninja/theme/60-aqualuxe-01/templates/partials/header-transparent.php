<?php
/**
 * Template part for displaying the transparent header
 *
 * @package AquaLuxe
 */

// Get header options from customizer
$show_search = aqualuxe_get_option('header_show_search', true);
$show_cart = aqualuxe_get_option('header_show_cart', true) && aqualuxe_is_woocommerce_active();
$sticky_header = aqualuxe_get_option('header_sticky', true);
$header_class = 'site-header transparent-header';
if ($sticky_header) {
    $header_class .= ' sticky-header';
}

// Check if we're on a page that should have a dark or light transparent header
$header_scheme = aqualuxe_get_transparent_header_scheme();
$header_class .= ' scheme-' . $header_scheme;
?>

<header id="masthead" class="<?php echo esc_attr($header_class); ?>">
    <div class="header-container <?php echo esc_attr(aqualuxe_get_container_class()); ?>">
        <div class="site-branding">
            <?php
            // For transparent header, we may want to use a different logo
            $logo_id = '';
            if ($header_scheme === 'light') {
                // Light scheme means dark logo
                $logo_id = aqualuxe_get_option('transparent_header_dark_logo', '');
            } else {
                // Dark scheme means light logo
                $logo_id = aqualuxe_get_option('transparent_header_light_logo', '');
            }

            if ($logo_id) {
                echo wp_get_attachment_image($logo_id, 'full', false, array(
                    'class' => 'custom-logo',
                    'alt' => get_bloginfo('name'),
                ));
            } elseif (has_custom_logo()) {
                the_custom_logo();
            } else :
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
                $aqualuxe_description = get_bloginfo('description', 'display');
                if ($aqualuxe_description || is_customize_preview()) :
                    ?>
                    <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
            </button>
            
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                )
            );
            ?>
        </nav><!-- #site-navigation -->

        <div class="header-actions">
            <?php if ($show_search) : ?>
                <div class="header-search">
                    <button class="search-toggle" aria-expanded="false">
                        <span class="search-icon"></span>
                        <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
                    </button>
                    <div class="header-search-form">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <?php if ($show_cart) : ?>
                    <div class="header-cart">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-contents">
                            <span class="cart-icon"></span>
                            <span class="cart-count"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                            <span class="screen-reader-text"><?php esc_html_e('Cart', 'aqualuxe'); ?></span>
                        </a>
                        <div class="header-cart-dropdown">
                            <?php woocommerce_mini_cart(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="header-account">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-link">
                        <span class="account-icon"></span>
                        <span class="screen-reader-text"><?php esc_html_e('Account', 'aqualuxe'); ?></span>
                    </a>
                </div>
            <?php endif; ?>
        </div><!-- .header-actions -->
    </div><!-- .header-container -->
</header><!-- #masthead -->