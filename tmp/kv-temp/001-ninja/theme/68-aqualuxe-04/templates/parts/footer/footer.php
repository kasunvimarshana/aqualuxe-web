<?php
/**
 * Template part for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
$footer_columns = get_theme_mod( 'aqualuxe_footer_columns', '4' );
$footer_logo = get_theme_mod( 'aqualuxe_footer_logo', '' );
$footer_about = get_theme_mod( 'aqualuxe_footer_about', __( 'AquaLuxe is a premium aquatic business specializing in rare fish, aquatic plants, and custom aquariums.', 'aqualuxe' ) );
$footer_social = get_theme_mod( 'aqualuxe_footer_social', true );
$footer_contact = get_theme_mod( 'aqualuxe_footer_contact', true );
$footer_newsletter = get_theme_mod( 'aqualuxe_footer_newsletter', true );
$footer_newsletter_text = get_theme_mod( 'aqualuxe_footer_newsletter_text', __( 'Subscribe to our newsletter to get the latest updates', 'aqualuxe' ) );
$copyright = \AquaLuxe\Helpers\Utils::get_theme_copyright();
$footer_text = \AquaLuxe\Helpers\Utils::get_theme_footer_text();

$footer_classes = array( 'site-footer-inner' );
$footer_classes[] = 'layout-' . $footer_layout;
$footer_classes[] = 'columns-' . $footer_columns;
?>

<div class="<?php echo esc_attr( implode( ' ', $footer_classes ) ); ?>">
    <div class="footer-widgets">
        <div class="container">
            <div class="footer-widgets-inner">
                <div class="footer-widget footer-widget-1">
                    <?php
                    if ( $footer_logo ) :
                        ?>
                        <div class="footer-logo">
                            <img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                        </div>
                        <?php
                    else :
                        ?>
                        <div class="footer-site-title">
                            <h3><?php bloginfo( 'name' ); ?></h3>
                        </div>
                        <?php
                    endif;

                    if ( $footer_about ) :
                        ?>
                        <div class="footer-about">
                            <?php echo wpautop( $footer_about ); ?>
                        </div>
                        <?php
                    endif;

                    if ( $footer_social ) :
                        $social_links = \AquaLuxe\Helpers\Utils::get_theme_social_links();
                        if ( ! empty( $social_links ) ) :
                            ?>
                            <div class="footer-social">
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
                            </div>
                            <?php
                        endif;
                    endif;
                    ?>
                </div>

                <div class="footer-widget footer-widget-2">
                    <?php
                    if ( is_active_sidebar( 'footer-1' ) ) :
                        dynamic_sidebar( 'footer-1' );
                    else :
                        ?>
                        <h4 class="widget-title"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h4>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a></li>
                            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a></li>
                        </ul>
                        <?php
                    endif;
                    ?>
                </div>

                <?php if ( $footer_columns >= 3 ) : ?>
                    <div class="footer-widget footer-widget-3">
                        <?php
                        if ( is_active_sidebar( 'footer-2' ) ) :
                            dynamic_sidebar( 'footer-2' );
                        elseif ( $footer_contact ) :
                            ?>
                            <h4 class="widget-title"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></h4>
                            <?php
                            $contact_info = \AquaLuxe\Helpers\Utils::get_theme_contact_info();
                            if ( ! empty( $contact_info ) ) :
                                ?>
                                <ul class="contact-info">
                                    <?php foreach ( $contact_info as $info ) : ?>
                                        <?php if ( ! empty( $info['value'] ) ) : ?>
                                            <li class="contact-<?php echo esc_attr( $info['icon'] ); ?>">
                                                <i class="icon-<?php echo esc_attr( $info['icon'] ); ?>"></i>
                                                <?php if ( ! empty( $info['url'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $info['url'] ); ?>">
                                                        <?php echo esc_html( $info['value'] ); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <span><?php echo esc_html( $info['value'] ); ?></span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <?php
                            endif;
                        endif;
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ( $footer_columns >= 4 ) : ?>
                    <div class="footer-widget footer-widget-4">
                        <?php
                        if ( is_active_sidebar( 'footer-3' ) ) :
                            dynamic_sidebar( 'footer-3' );
                        elseif ( $footer_newsletter ) :
                            ?>
                            <h4 class="widget-title"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h4>
                            <?php if ( $footer_newsletter_text ) : ?>
                                <p><?php echo esc_html( $footer_newsletter_text ); ?></p>
                            <?php endif; ?>
                            <div class="newsletter-form">
                                <form action="#" method="post" class="newsletter-subscribe-form">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                                        <button type="submit" class="newsletter-submit">
                                            <i class="icon-paper-plane"></i>
                                            <span class="screen-reader-text"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="copyright">
                    <?php echo wp_kses_post( $copyright ); ?>
                </div>
                <div class="footer-menu">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        )
                    );
                    ?>
                </div>
                <div class="footer-info">
                    <?php echo wp_kses_post( $footer_text ); ?>
                </div>
            </div>
        </div>
    </div>
</div><!-- .site-footer-inner -->