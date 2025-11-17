<?php
/**
 * Template part for displaying footer copyright and menu
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<div class="footer-bottom py-6 border-t border-white border-opacity-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="site-info mb-4 md:mb-0">
                <?php
                $footer_copyright = get_theme_mod('aqualuxe_footer_copyright');
                if ($footer_copyright) {
                    echo wp_kses_post(str_replace('{year}', date('Y'), $footer_copyright));
                } else {
                    /* translators: %1$s: Current year, %2$s: Site name */
                    printf(esc_html__('© %1$s %2$s. All Rights Reserved.', 'aqualuxe'), date('Y'), get_bloginfo('name'));
                }
                ?>
                
                <?php if (get_theme_mod('aqualuxe_footer_credits', true)) : ?>
                    <span class="sep"> | </span>
                    <?php
                    /* translators: %1$s: Theme name, %2$s: Theme author. */
                    printf(esc_html__('Theme: %1$s by %2$s.', 'aqualuxe'), 'AquaLuxe', '<a href="https://ninjatech.ai/">NinjaTech AI</a>');
                    ?>
                <?php endif; ?>
            </div><!-- .site-info -->
            
            <?php if (has_nav_menu('footer')) : ?>
                <nav class="footer-navigation">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'flex flex-wrap justify-center',
                            'container'      => false,
                            'depth'          => 1,
                        )
                    );
                    ?>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>