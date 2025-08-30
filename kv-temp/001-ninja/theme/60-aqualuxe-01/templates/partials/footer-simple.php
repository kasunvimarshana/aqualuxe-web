<?php
/**
 * Template part for displaying the simple footer
 *
 * @package AquaLuxe
 */

// Get footer options from customizer
$copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
$show_social_icons = aqualuxe_get_option('footer_show_social', true);
?>

<footer id="colophon" class="site-footer simple-footer">
    <div class="footer-content">
        <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
            <div class="footer-simple-content">
                <div class="footer-branding">
                    <?php
                    $footer_logo_id = aqualuxe_get_option('footer_logo', '');
                    if ($footer_logo_id) {
                        echo wp_get_attachment_image($footer_logo_id, 'full', false, array(
                            'class' => 'footer-logo',
                            'alt' => get_bloginfo('name'),
                        ));
                    } else {
                        ?>
                        <span class="site-title"><?php bloginfo('name'); ?></span>
                    <?php } ?>
                </div>

                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'depth'          => 1,
                                'container'      => false,
                            )
                        );
                        ?>
                    </nav>
                <?php endif; ?>

                <?php if ($show_social_icons) : ?>
                    <div class="footer-social">
                        <?php aqualuxe_social_icons(); ?>
                    </div>
                <?php endif; ?>

                <div class="footer-copyright">
                    <?php echo wp_kses_post($copyright_text); ?>
                </div>
            </div>
        </div>
    </div>
</footer><!-- #colophon -->