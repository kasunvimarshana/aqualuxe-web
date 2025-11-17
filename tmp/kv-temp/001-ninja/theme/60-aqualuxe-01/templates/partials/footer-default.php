<?php
/**
 * Template part for displaying the default footer
 *
 * @package AquaLuxe
 */

// Get footer options from customizer
$footer_columns = aqualuxe_get_option('footer_columns', 4);
$show_footer_widgets = aqualuxe_get_option('show_footer_widgets', true);
$show_footer_bottom = aqualuxe_get_option('show_footer_bottom', true);
$copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
$show_social_icons = aqualuxe_get_option('footer_show_social', true);
$show_payment_icons = aqualuxe_get_option('footer_show_payment', true) && aqualuxe_is_woocommerce_active();
?>

<footer id="colophon" class="site-footer">
    <?php if ($show_footer_widgets && (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4'))) : ?>
        <div class="footer-widgets">
            <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
                <div class="row footer-widgets-row columns-<?php echo esc_attr($footer_columns); ?>">
                    <?php for ($i = 1; $i <= $footer_columns; $i++) : ?>
                        <div class="footer-widget-area footer-widget-<?php echo esc_attr($i); ?>">
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div><!-- .footer-widgets -->
    <?php endif; ?>

    <?php if ($show_footer_bottom) : ?>
        <div class="footer-bottom">
            <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
                <div class="footer-bottom-content">
                    <div class="footer-copyright">
                        <?php echo wp_kses_post($copyright_text); ?>
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

                    <?php if ($show_payment_icons && aqualuxe_is_woocommerce_active()) : ?>
                        <div class="footer-payment">
                            <?php aqualuxe_payment_icons(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div><!-- .footer-bottom -->
    <?php endif; ?>
</footer><!-- #colophon -->