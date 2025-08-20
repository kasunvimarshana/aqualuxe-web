<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
    </div><!-- #content -->

    <?php
    // Newsletter Section
    if (get_theme_mod('aqualuxe_enable_footer_newsletter', true) && !is_page_template('templates/landing-page.php')) {
        get_template_part('templates/footer/newsletter');
    }
    ?>

    <footer id="colophon" class="site-footer">
        <?php
        // Footer Widgets
        if (
            is_active_sidebar('footer-1') ||
            is_active_sidebar('footer-2') ||
            is_active_sidebar('footer-3') ||
            is_active_sidebar('footer-4')
        ) {
            ?>
            <div class="site-footer__widgets">
                <div class="site-footer__widgets-container footer-columns-<?php echo esc_attr(get_theme_mod('aqualuxe_footer_columns', '4')); ?>">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <div class="footer-widget-area">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <div class="footer-widget-area">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <div class="footer-widget-area">
                            <?php dynamic_sidebar('footer-3'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <div class="footer-widget-area">
                            <?php dynamic_sidebar('footer-4'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="site-footer__bottom">
            <div class="site-footer__bottom-container">
                <div class="site-footer__bottom-copyright">
                    <?php
                    $copyright_text = get_theme_mod('aqualuxe_footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));
                    echo wp_kses_post($copyright_text);
                    ?>
                </div>

                <?php if (has_nav_menu('footer')) : ?>
                    <div class="site-footer__bottom-menu">
                        <?php
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu',
                                'depth'          => 1,
                                'container'      => false,
                            )
                        );
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (get_theme_mod('aqualuxe_enable_footer_payment_icons', true)) : ?>
                    <div class="site-footer__bottom-payment">
                        <span><?php esc_html_e('Payment Methods:', 'aqualuxe'); ?></span>
                        <div class="payment-icons">
                            <?php
                            $payment_icons = get_theme_mod('aqualuxe_footer_payment_icons', array('visa', 'mastercard', 'amex', 'paypal'));
                            foreach ($payment_icons as $icon) {
                                echo '<img src="' . esc_url(AQUALUXE_ASSETS_URI . 'images/payment/' . $icon . '.svg') . '" alt="' . esc_attr($icon) . '">';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </footer><!-- #colophon -->

    <?php
    // Back to top button
    if (get_theme_mod('aqualuxe_enable_back_to_top', true)) {
        echo '<div id="back-to-top" class="site-footer__back-to-top" title="' . esc_attr__('Back to top', 'aqualuxe') . '"><i class="fas fa-chevron-up" aria-hidden="true"></i></div>';
    }
    ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>