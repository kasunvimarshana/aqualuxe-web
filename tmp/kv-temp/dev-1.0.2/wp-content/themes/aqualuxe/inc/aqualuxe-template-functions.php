<?php

/**
 * AquaLuxe Template Functions
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Display site branding.
 */
function aqualuxe_site_branding()
{
    if (function_exists('the_custom_logo') && has_custom_logo()) {
        the_custom_logo();
    } elseif (get_bloginfo('name')) {
?>
        <div class="site-branding-text">
            <?php if (is_front_page() && is_home()) : ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php else : ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
            <?php endif; ?>

            <?php
            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) :
            ?>
                <p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
            <?php endif; ?>
        </div>
    <?php
    }
}

/**
 * Display primary navigation.
 */
function aqualuxe_primary_navigation()
{
    if (has_nav_menu('primary')) {
    ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">
            <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Primary Menu', 'aqualuxe'); ?></button>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'primary-menu',
            ));
            ?>
        </nav>
    <?php
    }
}

/**
 * Display site footer.
 */
function aqualuxe_site_footer()
{
    ?>
    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="site-info">
            <?php
            echo esc_html(apply_filters('aqualuxe_copyright_text', $content = '&copy; ' . get_bloginfo('name') . ' ' . date('Y')));
            ?>
        </div>
    </footer>
<?php
}
