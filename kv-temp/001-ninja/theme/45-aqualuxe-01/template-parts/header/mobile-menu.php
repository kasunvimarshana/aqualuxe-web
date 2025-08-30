<?php
/**
 * Template part for displaying the mobile menu
 *
 * @package AquaLuxe
 */
?>

<div id="mobile-menu" class="mobile-menu">
    <div class="mobile-menu-container">
        <div class="mobile-menu-header">
            <div class="mobile-menu-logo">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                }
                ?>
            </div>
            <button class="mobile-menu-close">
                <i class="fas fa-times"></i>
                <span class="screen-reader-text"><?php esc_html_e('Close menu', 'aqualuxe'); ?></span>
            </button>
        </div>

        <div class="mobile-menu-search">
            <?php get_search_form(); ?>
        </div>

        <nav class="mobile-navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-primary-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu-list',
                    'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                    'walker'         => new AquaLuxe_Walker_Mobile_Menu(),
                )
            );
            ?>
        </nav>

        <div class="mobile-menu-extras">
            <?php
            // Contact Information
            $mobile_phone = get_theme_mod('aqualuxe_header_phone', '');
            $mobile_email = get_theme_mod('aqualuxe_header_email', '');
            
            if (!empty($mobile_phone) || !empty($mobile_email)) {
                ?>
                <div class="mobile-contact">
                    <h4><?php echo esc_html__('Contact Us', 'aqualuxe'); ?></h4>
                    
                    <?php if (!empty($mobile_phone)) : ?>
                        <div class="mobile-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $mobile_phone)); ?>">
                                <?php echo esc_html($mobile_phone); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($mobile_email)) : ?>
                        <div class="mobile-contact-item">
                            <i class="far fa-envelope"></i>
                            <a href="mailto:<?php echo esc_attr($mobile_email); ?>">
                                <?php echo esc_html($mobile_email); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            }

            // Language Switcher
            if (aqualuxe_is_multilingual_active()) {
                ?>
                <div class="mobile-languages">
                    <h4><?php echo esc_html__('Language', 'aqualuxe'); ?></h4>
                    <?php
                    echo aqualuxe_get_language_switcher(array(
                        'dropdown'   => false,
                        'show_flags' => true,
                        'show_names' => true,
                        'classes'    => 'mobile-language-switcher',
                    ));
                    ?>
                </div>
                <?php
            }

            // Currency Switcher
            if (aqualuxe_is_woocommerce_active()) {
                ?>
                <div class="mobile-currency">
                    <h4><?php echo esc_html__('Currency', 'aqualuxe'); ?></h4>
                    <?php
                    echo aqualuxe_get_currency_switcher(array(
                        'dropdown'     => false,
                        'show_symbols' => true,
                        'show_names'   => true,
                        'classes'      => 'mobile-currency-switcher',
                    ));
                    ?>
                </div>
                <?php
            }

            // Social Links
            ?>
            <div class="mobile-social">
                <h4><?php echo esc_html__('Follow Us', 'aqualuxe'); ?></h4>
                <?php aqualuxe_social_links(); ?>
            </div>
        </div>
    </div>
</div>

<div class="mobile-menu-overlay"></div>