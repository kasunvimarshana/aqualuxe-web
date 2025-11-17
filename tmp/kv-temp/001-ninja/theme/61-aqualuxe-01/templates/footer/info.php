<?php
/**
 * Template part for displaying the footer info
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get footer width
$footer_width = get_theme_mod( 'aqualuxe_footer_width', 'container' );

// Get footer logo
$footer_logo = get_theme_mod( 'aqualuxe_footer_logo' );
?>

<div class="footer-info">
    <div class="<?php echo esc_attr( $footer_width ); ?>">
        <div class="footer-info-inner">
            <div class="footer-info-left">
                <?php if ( $footer_logo ) : ?>
                    <div class="footer-logo">
                        <img src="<?php echo esc_url( wp_get_attachment_url( $footer_logo ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    </div>
                <?php else : ?>
                    <div class="footer-site-info">
                        <h3 class="site-title"><?php bloginfo( 'name' ); ?></h3>
                        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                    </div>
                <?php endif; ?>

                <div class="footer-contact-info">
                    <?php echo aqualuxe_get_contact_info(); ?>
                </div>
            </div>

            <div class="footer-info-right">
                <div class="footer-social">
                    <?php echo aqualuxe_get_social_links(); ?>
                </div>

                <?php if ( has_nav_menu( 'footer' ) ) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(
                            [
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'fallback_cb'    => false,
                            ]
                        );
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>