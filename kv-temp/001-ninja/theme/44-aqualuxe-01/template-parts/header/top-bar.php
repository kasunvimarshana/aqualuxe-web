<?php
/**
 * Template part for displaying the top bar
 *
 * @package AquaLuxe
 */

// Get top bar options from theme customizer
$top_bar_bg_color = get_theme_mod('aqualuxe_top_bar_bg_color', '#f5f5f5');
$top_bar_text_color = get_theme_mod('aqualuxe_top_bar_text_color', '#333333');
$top_bar_phone = get_theme_mod('aqualuxe_header_phone', '');
$top_bar_email = get_theme_mod('aqualuxe_header_email', '');
$top_bar_address = get_theme_mod('aqualuxe_header_address', '');
$top_bar_hours = get_theme_mod('aqualuxe_header_hours', '');
$show_social_icons = get_theme_mod('aqualuxe_show_top_bar_social', true);
?>

<div class="top-bar" style="background-color: <?php echo esc_attr($top_bar_bg_color); ?>; color: <?php echo esc_attr($top_bar_text_color); ?>;">
    <div class="container">
        <div class="top-bar-inner">
            <div class="top-bar-left">
                <div class="top-bar-contact">
                    <?php if (!empty($top_bar_phone)) : ?>
                        <div class="contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $top_bar_phone)); ?>">
                                <?php echo esc_html($top_bar_phone); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($top_bar_email)) : ?>
                        <div class="contact-item">
                            <i class="far fa-envelope"></i>
                            <a href="mailto:<?php echo esc_attr($top_bar_email); ?>">
                                <?php echo esc_html($top_bar_email); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($top_bar_address)) : ?>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo esc_html($top_bar_address); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($top_bar_hours)) : ?>
                        <div class="contact-item">
                            <i class="far fa-clock"></i>
                            <span><?php echo esc_html($top_bar_hours); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="top-bar-right">
                <?php
                // Language Switcher
                if (aqualuxe_is_multilingual_active()) {
                    echo aqualuxe_get_language_switcher(array(
                        'dropdown'   => true,
                        'show_flags' => true,
                        'show_names' => false,
                        'classes'    => 'top-bar-language-switcher',
                    ));
                }

                // Currency Switcher
                if (aqualuxe_is_woocommerce_active()) {
                    echo aqualuxe_get_currency_switcher(array(
                        'dropdown'     => true,
                        'show_symbols' => true,
                        'show_names'   => false,
                        'classes'      => 'top-bar-currency-switcher',
                    ));
                }

                // Social Icons
                if ($show_social_icons) {
                    ?>
                    <div class="top-bar-social">
                        <?php aqualuxe_social_links(); ?>
                    </div>
                    <?php
                }

                // Secondary Menu
                if (has_nav_menu('top-bar')) {
                    wp_nav_menu(
                        array(
                            'theme_location' => 'top-bar',
                            'menu_id'        => 'top-bar-menu',
                            'container'      => false,
                            'depth'          => 1,
                            'menu_class'     => 'top-bar-menu',
                            'fallback_cb'    => false,
                        )
                    );
                }
                ?>
            </div>
        </div>
    </div>
</div>