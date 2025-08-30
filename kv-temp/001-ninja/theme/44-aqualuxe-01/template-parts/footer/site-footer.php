<?php
/**
 * Template part for displaying the site footer
 *
 * @package AquaLuxe
 */

// Get footer options from theme customizer
$footer_style = get_theme_mod('aqualuxe_footer_style', 'default');
$footer_columns = get_theme_mod('aqualuxe_footer_columns', 4);
$show_footer_widgets = get_theme_mod('aqualuxe_show_footer_widgets', true);
$show_footer_bottom = get_theme_mod('aqualuxe_show_footer_bottom', true);
$footer_bg_color = get_theme_mod('aqualuxe_footer_bg_color', '#222222');
$footer_text_color = get_theme_mod('aqualuxe_footer_text_color', '#ffffff');
$footer_copyright = get_theme_mod('aqualuxe_footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.');

// Footer classes
$footer_classes = array('site-footer');
$footer_classes[] = 'footer-style-' . $footer_style;
$footer_classes[] = 'footer-cols-' . $footer_columns;
?>

<footer id="colophon" class="<?php echo esc_attr(implode(' ', $footer_classes)); ?>" style="background-color: <?php echo esc_attr($footer_bg_color); ?>; color: <?php echo esc_attr($footer_text_color); ?>;">
    <?php
    // Footer Widgets
    if ($show_footer_widgets) {
        get_template_part('template-parts/footer/footer-widgets');
    }
    
    // Footer Bottom
    if ($show_footer_bottom) {
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-copyright">
                        <?php echo wp_kses_post($footer_copyright); ?>
                    </div>
                    
                    <?php
                    // Footer Menu
                    if (has_nav_menu('footer')) {
                        wp_nav_menu(
                            array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                                'menu_class'     => 'footer-menu',
                                'fallback_cb'    => false,
                            )
                        );
                    }
                    
                    // Payment Icons
                    if (get_theme_mod('aqualuxe_show_payment_icons', true) && aqualuxe_is_woocommerce_active()) {
                        ?>
                        <div class="footer-payment-icons">
                            <?php
                            $payment_methods = get_theme_mod('aqualuxe_payment_methods', array('visa', 'mastercard', 'amex', 'discover', 'paypal'));
                            
                            if (!empty($payment_methods)) {
                                foreach ($payment_methods as $method) {
                                    echo '<span class="payment-icon payment-' . esc_attr($method) . '"></span>';
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</footer><!-- #colophon -->

<?php
// Back to Top Button
if (get_theme_mod('aqualuxe_show_back_to_top', true)) {
    ?>
    <a href="#" id="back-to-top" class="back-to-top">
        <i class="fas fa-chevron-up"></i>
        <span class="screen-reader-text"><?php esc_html_e('Back to top', 'aqualuxe'); ?></span>
    </a>
    <?php
}