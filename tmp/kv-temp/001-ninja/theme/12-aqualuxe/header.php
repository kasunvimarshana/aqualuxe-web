<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <header id="masthead" class="site-header">
        <div class="top-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <?php
                        // Display contact information from theme options
                        $phone = get_theme_mod( 'aqualuxe_phone', '+1 (555) 123-4567' );
                        $email = get_theme_mod( 'aqualuxe_email', 'info@aqualuxe.com' );
                        
                        if ( $phone || $email ) :
                        ?>
                            <div class="contact-info">
                                <?php if ( $phone ) : ?>
                                    <span class="phone">
                                        <i class="fas fa-phone"></i>
                                        <?php echo esc_html( $phone ); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ( $email ) : ?>
                                    <span class="email">
                                        <i class="fas fa-envelope"></i>
                                        <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <div class="top-bar-right">
                            <?php
                            // Social media links
                            get_template_part( 'template-parts/header/social-links' );
                            
                            // Secondary navigation
                            if ( has_nav_menu( 'secondary' ) ) :
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'secondary',
                                        'menu_id'        => 'secondary-menu',
                                        'container'      => 'nav',
                                        'container_class' => 'secondary-navigation',
                                        'depth'          => 1,
                                    )
                                );
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <div class="site-branding">
                            <?php
                            if ( has_custom_logo() ) :
                                the_custom_logo();
                            else :
                                ?>
                                <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                <?php
                                $aqualuxe_description = get_bloginfo( 'description', 'display' );
                                if ( $aqualuxe_description || is_customize_preview() ) :
                                    ?>
                                    <p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div><!-- .site-branding -->
                    </div>
                    <div class="col-md-9">
                        <div class="header-right">
                            <nav id="site-navigation" class="main-navigation">
                                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                    <span class="menu-toggle-icon"></span>
                                    <?php esc_html_e( 'Menu', 'aqualuxe' ); ?>
                                </button>
                                <?php
                                if ( has_nav_menu( 'primary' ) ) :
                                    wp_nav_menu(
                                        array(
                                            'theme_location' => 'primary',
                                            'menu_id'        => 'primary-menu',
                                            'container'      => false,
                                            'menu_class'     => 'primary-menu',
                                        )
                                    );
                                endif;
                                ?>
                            </nav><!-- #site-navigation -->

                            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                <div class="header-cart">
                                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents" title="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="count"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() ); ?></span>
                                    </a>
                                </div>

                                <div class="header-account">
                                    <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>

                                <div class="header-search">
                                    <a href="#" class="search-toggle" title="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    <div class="search-form-wrapper">
                                        <?php get_search_form(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div><!-- .header-right -->
                    </div>
                </div>
            </div>
        </div>
    </header><!-- #masthead -->

    <div id="content" class="site-content">