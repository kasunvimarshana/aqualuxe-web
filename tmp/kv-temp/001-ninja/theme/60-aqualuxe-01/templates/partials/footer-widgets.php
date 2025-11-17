<?php
/**
 * Template part for displaying the widgets-focused footer
 *
 * @package AquaLuxe
 */

// Get footer options from customizer
$footer_columns = aqualuxe_get_option('footer_columns', 4);
$copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');
$show_social_icons = aqualuxe_get_option('footer_show_social', true);
$show_payment_icons = aqualuxe_get_option('footer_show_payment', true) && aqualuxe_is_woocommerce_active();
$footer_background = aqualuxe_get_option('footer_background', '');
$footer_style = '';

if ($footer_background) {
    $footer_style = 'style="background-image: url(' . esc_url($footer_background) . ');"';
}
?>

<footer id="colophon" class="site-footer widgets-footer" <?php echo $footer_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
    <div class="footer-widgets-expanded">
        <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
            <div class="footer-widgets-top">
                <?php if (is_active_sidebar('footer-top')) : ?>
                    <div class="footer-top-widget-area">
                        <?php dynamic_sidebar('footer-top'); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row footer-widgets-row columns-<?php echo esc_attr($footer_columns); ?>">
                <?php for ($i = 1; $i <= $footer_columns; $i++) : ?>
                    <div class="footer-widget-area footer-widget-<?php echo esc_attr($i); ?>">
                        <?php if (is_active_sidebar('footer-' . $i)) : ?>
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>

            <?php if (is_active_sidebar('footer-bottom')) : ?>
                <div class="footer-widgets-bottom">
                    <div class="footer-bottom-widget-area">
                        <?php dynamic_sidebar('footer-bottom'); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

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

                <div class="footer-right">
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
        </div>
    </div>
</footer><!-- #colophon -->