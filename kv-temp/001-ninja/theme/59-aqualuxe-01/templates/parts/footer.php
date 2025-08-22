<?php
/**
 * Footer template part
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$footer_layout = get_theme_mod('footer_layout', 'default');
$footer_columns = get_theme_mod('footer_columns', 4);
$footer_classes = ['site-footer'];

// Add footer layout class
$footer_classes[] = 'site-footer--' . $footer_layout;

// Add footer columns class
$footer_classes[] = 'site-footer--columns-' . $footer_columns;

// Get footer logo settings
$footer_logo_id = get_theme_mod('footer_logo');
$footer_logo_url = $footer_logo_id ? wp_get_attachment_image_url($footer_logo_id, 'full') : '';
$footer_logo_alt = get_bloginfo('name');

// Get copyright text
$copyright_text = get_theme_mod('footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));

// Check if footer widgets are active
$has_footer_widgets = false;
for ($i = 1; $i <= $footer_columns; $i++) {
    if (is_active_sidebar('footer-' . $i)) {
        $has_footer_widgets = true;
        break;
    }
}
?>

<footer id="colophon" class="<?php echo esc_attr(implode(' ', $footer_classes)); ?>">
    <?php if ($has_footer_widgets) : ?>
        <div class="site-footer__widgets">
            <div class="container">
                <div class="site-footer__widgets-row">
                    <?php for ($i = 1; $i <= $footer_columns; $i++) : ?>
                        <div class="site-footer__widgets-column">
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
                
                <?php
                /**
                 * Hook: aqualuxe_footer_widgets
                 *
                 * @hooked aqualuxe_footer_language_switcher - 10 (from multilingual module)
                 */
                do_action('aqualuxe_footer_widgets');
                ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="site-footer__bottom">
        <div class="container">
            <div class="site-footer__bottom-row">
                <div class="site-footer__info">
                    <?php if ($footer_logo_url) : ?>
                        <div class="site-footer__logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                <img src="<?php echo esc_url($footer_logo_url); ?>" alt="<?php echo esc_attr($footer_logo_alt); ?>" class="site-footer__logo-img">
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="site-footer__copyright">
                        <?php echo wp_kses_post($copyright_text); ?>
                    </div>
                </div>
                
                <div class="site-footer__menu">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ]);
                    ?>
                </div>
                
                <div class="site-footer__social">
                    <?php
                    /**
                     * Hook: aqualuxe_footer_social
                     *
                     * @hooked aqualuxe_social_icons - 10
                     */
                    do_action('aqualuxe_footer_social');
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (get_theme_mod('footer_back_to_top', true)) : ?>
        <a href="#page" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                <path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" />
            </svg>
        </a>
    <?php endif; ?>
</footer>