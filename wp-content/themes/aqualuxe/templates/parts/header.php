<?php
/**
 * Header template part
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$header_layout = get_theme_mod('header_layout', 'default');
$header_transparent = get_theme_mod('header_transparent', false);
$header_sticky = get_theme_mod('header_sticky', true);
$header_classes = ['site-header'];

// Add header layout class
$header_classes[] = 'site-header--' . $header_layout;

// Add transparent class if enabled
if ($header_transparent) {
    $header_classes[] = 'site-header--transparent';
}

// Add sticky class if enabled
if ($header_sticky) {
    $header_classes[] = 'site-header--sticky';
}

// Get logo settings
$logo_id = get_theme_mod('custom_logo');
$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
$logo_alt = get_bloginfo('name');

// Get dark logo settings
$dark_logo_id = get_theme_mod('dark_logo');
$dark_logo_url = $dark_logo_id ? wp_get_attachment_image_url($dark_logo_id, 'full') : $logo_url;
?>

<header id="masthead" class="<?php echo esc_attr(implode(' ', $header_classes)); ?>">
    <div class="site-header__container container">
        <div class="site-header__row">
            <div class="site-header__logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php if ($logo_url) : ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="site-header__logo-img site-header__logo-img--light">
                    <?php endif; ?>
                    
                    <?php if ($dark_logo_url) : ?>
                        <img src="<?php echo esc_url($dark_logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="site-header__logo-img site-header__logo-img--dark">
                    <?php endif; ?>
                    
                    <?php if (!$logo_url) : ?>
                        <span class="site-header__logo-text"><?php bloginfo('name'); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            
            <div class="site-header__navigation">
                <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Main Navigation', 'aqualuxe'); ?>">
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="menu-toggle__icon"></span>
                        <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
                    </button>
                    
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
                    ]);
                    ?>
                </nav>
            </div>
            
            <div class="site-header__actions">
                <?php
                /**
                 * Hook: aqualuxe_header_actions
                 *
                 * @hooked aqualuxe_header_search - 10
                 * @hooked aqualuxe_header_account - 20
                 * @hooked aqualuxe_header_cart - 30
                 */
                do_action('aqualuxe_header_actions');
                ?>
                
                <?php
                /**
                 * Hook: aqualuxe_header_after_navigation
                 *
                 * @hooked aqualuxe_dark_mode_toggle - 10 (from dark-mode module)
                 * @hooked aqualuxe_language_switcher - 20 (from multilingual module)
                 */
                do_action('aqualuxe_header_after_navigation');
                ?>
            </div>
        </div>
    </div>
</header>

<?php
/**
 * Hook: aqualuxe_after_header
 *
 * @hooked aqualuxe_header_mobile_menu - 10
 */
do_action('aqualuxe_after_header');
?>