    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widget-areas">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-area footer-widget-1">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-area footer-widget-2">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-area footer-widget-3">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (is_active_sidebar('footer-4')) : ?>
                            <div class="footer-widget-area footer-widget-4">
                                <?php dynamic_sidebar('footer-4'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'container' => false,
                            'menu_class' => 'footer-menu',
                            'fallback_cb' => false,
                            'depth' => 1,
                        ));
                        ?>
                    </div>

                    <div class="copyright">
                        <p>
                            <?php
                            /* translators: 1: Theme name, 2: Theme author. */
                            printf(esc_html__('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'), 
                                date('Y'), 
                                get_bloginfo('name')
                            );
                            ?>
                        </p>
                        <p>
                            <?php
                            /* translators: %s: WordPress */
                            printf(esc_html__('Powered by %s', 'aqualuxe'), 
                                '<a href="' . esc_url(__('https://wordpress.org/', 'aqualuxe')) . '" target="_blank" rel="noopener">WordPress</a>'
                            );
                            ?>
                            <?php
                            /* translators: %s: Theme name */
                            printf(' ' . esc_html__('& %s theme', 'aqualuxe'), 
                                '<a href="https://github.com/kasunvimarshana/aqualuxe-web" target="_blank" rel="noopener">AquaLuxe</a>'
                            );
                            ?>
                        </p>
                    </div>

                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <div class="footer-woocommerce">
                            <?php
                            // Payment methods or other WooCommerce info
                            $payment_methods = WC()->payment_gateways->get_available_payment_gateways();
                            if (!empty($payment_methods)) :
                                ?>
                                <div class="payment-methods">
                                    <span class="payment-methods-label"><?php esc_html_e('We accept:', 'aqualuxe'); ?></span>
                                    <?php foreach ($payment_methods as $gateway) : ?>
                                        <?php if ($gateway->enabled === 'yes' && !empty($gateway->icon)) : ?>
                                            <img src="<?php echo esc_url($gateway->icon); ?>" 
                                                 alt="<?php echo esc_attr($gateway->get_title()); ?>" 
                                                 class="payment-icon">
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <?php
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>