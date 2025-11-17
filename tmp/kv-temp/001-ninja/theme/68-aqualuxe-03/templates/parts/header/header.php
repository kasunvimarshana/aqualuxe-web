<?php
/**
 * Template part for displaying the header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
$sticky_header = get_theme_mod( 'aqualuxe_sticky_header', true );
$header_search = get_theme_mod( 'aqualuxe_header_search', true );
$header_cart = get_theme_mod( 'aqualuxe_header_cart', true );
$header_account = get_theme_mod( 'aqualuxe_header_account', true );
$header_wishlist = get_theme_mod( 'aqualuxe_header_wishlist', true );
$header_social = get_theme_mod( 'aqualuxe_header_social', true );
$header_contact = get_theme_mod( 'aqualuxe_header_contact', true );
$header_top_bar = get_theme_mod( 'aqualuxe_header_top_bar', true );
$header_top_bar_text = get_theme_mod( 'aqualuxe_header_top_bar_text', __( 'Welcome to AquaLuxe', 'aqualuxe' ) );
$dark_mode_toggle = get_theme_mod( 'aqualuxe_dark_mode_toggle', true );

$header_classes = array( 'site-header-inner' );
$header_classes[] = 'layout-' . $header_layout;

if ( $sticky_header ) {
    $header_classes[] = 'sticky-header';
}

// Check if WooCommerce is active
$is_woocommerce_active = \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active();
?>

<?php if ( $header_top_bar ) : ?>
    <div class="header-top-bar">
        <div class="container">
            <div class="top-bar-content">
                <?php if ( $header_top_bar_text ) : ?>
                    <div class="top-bar-text">
                        <?php echo wp_kses_post( $header_top_bar_text ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( $header_contact ) : ?>
                    <div class="top-bar-contact">
                        <?php
                        $contact_info = \AquaLuxe\Helpers\Utils::get_theme_contact_info();
                        if ( ! empty( $contact_info ) ) :
                            foreach ( $contact_info as $info ) :
                                if ( ! empty( $info['value'] ) ) :
                                    ?>
                                    <div class="contact-item contact-<?php echo esc_attr( $info['icon'] ); ?>">
                                        <i class="icon-<?php echo esc_attr( $info['icon'] ); ?>"></i>
                                        <?php if ( ! empty( $info['url'] ) ) : ?>
                                            <a href="<?php echo esc_url( $info['url'] ); ?>">
                                                <?php echo esc_html( $info['value'] ); ?>
                                            </a>
                                        <?php else : ?>
                                            <span><?php echo esc_html( $info['value'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ( $header_social ) : ?>
                    <div class="top-bar-social">
                        <?php
                        $social_links = \AquaLuxe\Helpers\Utils::get_theme_social_links();
                        if ( ! empty( $social_links ) ) :
                            ?>
                            <ul class="social-links">
                                <?php foreach ( $social_links as $network => $link ) : ?>
                                    <li class="social-<?php echo esc_attr( $network ); ?>">
                                        <a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                            <span class="screen-reader-text"><?php echo esc_html( $link['label'] ); ?></span>
                                            <i class="icon-<?php echo esc_attr( $link['icon'] ); ?>"></i>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>">
    <div class="container">
        <div class="site-branding">
            <?php
            $logo = \AquaLuxe\Helpers\Utils::get_theme_logo();
            if ( $logo ) :
                ?>
                <div class="site-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    </a>
                </div>
                <?php
            else :
                ?>
                <div class="site-title-wrapper">
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <?php
                    $aqualuxe_description = get_bloginfo( 'description', 'display' );
                    if ( $aqualuxe_description || is_customize_preview() ) :
                        ?>
                        <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                    <?php endif; ?>
                </div>
                <?php
            endif;
            ?>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                <span class="menu-toggle-icon"></span>
                <span class="screen-reader-text"><?php esc_html_e( 'Primary Menu', 'aqualuxe' ); ?></span>
            </button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav><!-- #site-navigation -->

        <div class="header-actions">
            <?php if ( $dark_mode_toggle ) : ?>
                <div class="dark-mode-toggle">
                    <button class="dark-mode-button" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
                        <span class="dark-mode-icon light"></span>
                        <span class="dark-mode-icon dark"></span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ( $header_search ) : ?>
                <div class="header-search">
                    <button class="search-toggle" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle Search', 'aqualuxe' ); ?>">
                        <i class="icon-search"></i>
                    </button>
                    <div class="search-dropdown">
                        <?php get_search_form(); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( $is_woocommerce_active ) : ?>
                <?php if ( $header_wishlist && function_exists( 'wc_get_page_id' ) ) : ?>
                    <div class="header-wishlist">
                        <a href="<?php echo esc_url( get_permalink( get_option( 'aqualuxe_wishlist_page_id' ) ) ); ?>" aria-label="<?php esc_attr_e( 'Wishlist', 'aqualuxe' ); ?>">
                            <i class="icon-heart"></i>
                            <span class="wishlist-count">0</span>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( $header_account ) : ?>
                    <div class="header-account">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
                            <i class="icon-user"></i>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if ( $header_cart ) : ?>
                    <div class="header-cart">
                        <a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                            <i class="icon-cart"></i>
                            <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                        </a>
                        <div class="mini-cart-dropdown">
                            <div class="widget_shopping_cart_content"></div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .header-actions -->
    </div><!-- .container -->
</div><!-- .site-header-inner -->